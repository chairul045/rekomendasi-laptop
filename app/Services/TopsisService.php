<?php

namespace App\Services;

use App\Models\Kriteria;
use App\Models\Laptop;
use Illuminate\Support\Collection;

class TopsisService
{
    /**
     * Menjalankan algoritme TOPSIS untuk sekumpulan alternatif laptop.
     *
     * @param Collection|array $laptops Kumpulan objek Laptop
     * @param array $rocWeights Array [ 'kode_kriteria' => bobot_float ]
     * @return array [
     *     'ranked' => [ [ 'laptop' => Laptop, 'nilai_v' => float, 'peringkat' => int, ... ] ],
     *     'matrix_x' => array,
     *     'matrix_r' => array,
     *     'matrix_y' => array,
     *     'ideal_positive' => array,
     *     'ideal_negative' => array,
     * ]
     */
    public function calculate(Collection|array $laptops, array $rocWeights): array
    {
        $items = collect($laptops)->values();
        if ($items->isEmpty()) {
            return [
                'ranked' => [],
                'matrix_x' => [],
                'matrix_r' => [],
                'matrix_y' => [],
                'ideal_positive' => [],
                'ideal_negative' => [],
            ];
        }

        // Ambil konfigurasi 6 kriteria dari basis data
        $criteriaDb = Kriteria::all()->keyBy('kode');

        // Pemetaan atribut laptop ke kode kriteria
        $criteriaMap = [
            'C1' => [
                'name' => 'Harga',
                'attribute' => fn (Laptop $l) => (float) ($l->price ?: 10000000),
                'type' => $criteriaDb['C1']->tipe ?? 'cost',
            ],
            'C2' => [
                'name' => 'Performa',
                'attribute' => fn (Laptop $l) => (float) ($l->performa ?: 50),
                'type' => $criteriaDb['C2']->tipe ?? 'benefit',
            ],
            'C3' => [
                'name' => 'RAM',
                'attribute' => fn (Laptop $l) => (float) ($l->ram_gb ?: 8),
                'type' => $criteriaDb['C3']->tipe ?? 'benefit',
            ],
            'C4' => [
                'name' => 'Storage',
                'attribute' => fn (Laptop $l) => (float) ($l->storage_gb ?: 512),
                'type' => $criteriaDb['C4']->tipe ?? 'benefit',
            ],
            'C5' => [
                'name' => 'Baterai',
                'attribute' => fn (Laptop $l) => (float) ($l->battery_hours ?: 6.0),
                'type' => $criteriaDb['C5']->tipe ?? 'benefit',
            ],
            'C6' => [
                'name' => 'Portabilitas',
                'attribute' => fn (Laptop $l) => (float) ($l->weight_kg ?: 1.6),
                'type' => $criteriaDb['C6']->tipe ?? 'cost', // Semakin ringan bobot beratnya semakin baik
            ],
        ];

        // 1. Matriks Keputusan X
        $matrixX = [];
        foreach ($items as $laptop) {
            $row = [];
            foreach ($criteriaMap as $code => $config) {
                $row[$code] = ($config['attribute'])($laptop);
            }
            $matrixX[$laptop->id] = $row;
        }

        // 2. Normalisasi Vektor r_ij = x_ij / sqrt(sum(x_kj^2))
        $divider = [];
        foreach (array_keys($criteriaMap) as $code) {
            $sumSquares = 0.0;
            foreach ($matrixX as $row) {
                $sumSquares += pow($row[$code], 2);
            }
            $divider[$code] = sqrt($sumSquares) ?: 1.0;
        }

        $matrixR = [];
        foreach ($matrixX as $id => $row) {
            $rRow = [];
            foreach ($criteriaMap as $code => $config) {
                $rRow[$code] = $row[$code] / $divider[$code];
            }
            $matrixR[$id] = $rRow;
        }

        // 3. Matriks Ternormalisasi Terbobot y_ij = W_j * r_ij
        $matrixY = [];
        foreach ($matrixR as $id => $rRow) {
            $yRow = [];
            foreach ($criteriaMap as $code => $config) {
                $w = $rocWeights[$code] ?? (1.0 / count($criteriaMap));
                $yRow[$code] = $rRow[$code] * $w;
            }
            $matrixY[$id] = $yRow;
        }

        // 4. Menentukan Solusi Ideal Positif (A+) dan Solusi Ideal Negatif (A-)
        $idealPositive = [];
        $idealNegative = [];

        foreach ($criteriaMap as $code => $config) {
            $values = array_column($matrixY, $code);
            $maxVal = !empty($values) ? max($values) : 0;
            $minVal = !empty($values) ? min($values) : 0;

            if ($config['type'] === 'benefit') {
                $idealPositive[$code] = $maxVal;
                $idealNegative[$code] = $minVal;
            } else { // cost
                $idealPositive[$code] = $minVal;
                $idealNegative[$code] = $maxVal;
            }
        }

        // 5. Menghitung Jarak Solusi Ideal (D+ dan D-) & Nilai Preferensi V_i
        $results = [];
        foreach ($items as $laptop) {
            $yRow = $matrixY[$laptop->id];

            $sumDPos = 0.0;
            $sumDNeg = 0.0;

            foreach ($criteriaMap as $code => $config) {
                $sumDPos += pow($yRow[$code] - $idealPositive[$code], 2);
                $sumDNeg += pow($yRow[$code] - $idealNegative[$code], 2);
            }

            $dPos = sqrt($sumDPos);
            $dNeg = sqrt($sumDNeg);

            $sumDistances = $dPos + $dNeg;
            $nilaiV = $sumDistances > 0 ? ($dNeg / $sumDistances) : 0.0;

            $results[] = [
                'laptop' => $laptop,
                'laptop_id' => $laptop->id,
                'nama' => $laptop->name,
                'merek' => $laptop->brand,
                'harga' => $laptop->price,
                'kondisi' => $laptop->condition ?: 'baru',
                'ram' => $laptop->ram_gb,
                'storage' => $laptop->storage_gb,
                'baterai' => $laptop->battery_hours,
                'berat' => $laptop->weight_kg,
                'performa' => $laptop->performa,
                'd_pos' => round($dPos, 5),
                'd_neg' => round($dNeg, 5),
                'nilai_v' => round($nilaiV, 6),
                'skor_persen' => round($nilaiV * 100, 2),
            ];
        }

        // 6. Urutkan berdasarkan Nilai V terbesar ke terkecil
        usort($results, function ($a, $b) {
            return $b['nilai_v'] <=> $a['nilai_v'];
        });

        // 7. Berikan nomor peringkat
        foreach ($results as $index => &$item) {
            $item['peringkat'] = $index + 1;
        }

        return [
            'ranked' => $results,
            'matrix_x' => $matrixX,
            'matrix_r' => $matrixR,
            'matrix_y' => $matrixY,
            'ideal_positive' => $idealPositive,
            'ideal_negative' => $idealNegative,
        ];
    }
}
