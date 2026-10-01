<?php

namespace App\Services;

class RocService
{
    /**
     * Menghitung bobot ROC (Rank Order Centroid) berdasarkan urutan prioritas kriteria.
     *
     * Rumus: W_i = (1 / K) * sum_{j=i}^{K} (1 / j)
     * di mana:
     * - K = Jumlah kriteria
     * - i = Peringkat kriteria (1 = Paling Penting, K = Paling Tidak Penting)
     *
     * @param array $orderedCriterionCodes Array kode kriteria berurutan dari prioritas tertinggi ke terendah (misal: ['C1', 'C2', 'C3', 'C4', 'C5', 'C6'])
     * @return array [ 'kode_kriteria' => ['prioritas' => i, 'bobot' => W_i] ]
     */
    public function calculateWeights(array $orderedCriterionCodes): array
    {
        $k = count($orderedCriterionCodes);
        if ($k === 0) {
            return [];
        }

        $result = [];
        $rank = 1;

        foreach ($orderedCriterionCodes as $code) {
            $sum = 0.0;
            for ($j = $rank; $j <= $k; $j++) {
                $sum += (1.0 / $j);
            }

            $weight = $sum / $k;

            $result[$code] = [
                'prioritas' => $rank,
                'bobot' => round($weight, 4),
            ];

            $rank++;
        }

        // Normalisasi presisi agar jumlah tepat 1.0000
        $totalWeight = array_sum(array_column($result, 'bobot'));
        if ($totalWeight > 0 && abs($totalWeight - 1.0) > 0.0001) {
            $diff = 1.0 - $totalWeight;
            // Tambahkan selisih pembulatan kecil ke kriteria prioritas pertama
            $firstKey = array_key_first($result);
            $result[$firstKey]['bobot'] = round($result[$firstKey]['bobot'] + $diff, 4);
        }

        return $result;
    }
}
