<?php

namespace App\Services;

use App\Models\KuisionerJawaban;
use App\Models\Laptop;
use App\Models\PenjelasanAi;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiExplanationService
{
    /**
     * Menghasilkan penjelasan naratif AI untuk laptop teratas dan menyimpannya di cache tabel penjelasan_ai.
     *
     * @param KuisionerJawaban $jawaban
     * @param array $topRankedLaptops Array daftar laptop hasil TOPSIS teratas
     * @param array $rocWeights Peringkat bobot kriteria
     * @param int $limit Jumlah laptop teratas yang diberikan penjelasan (top-N, default 5)
     * @return array [ laptop_id => string narasi ]
     */
    public function generateForTopLaptops(KuisionerJawaban $jawaban, array $topRankedLaptops, array $rocWeights = [], int $limit = 5): array
    {
        $selectedItems = array_slice($topRankedLaptops, 0, $limit);
        $explanations = [];

        // Ambil kriteria terpenting (prioritas 1)
        $topCriteriaName = 'Spesifikasi Seimbang';
        if (!empty($jawaban->ranking_kriteria[0])) {
            $code = $jawaban->ranking_kriteria[0];
            $map = [
                'C1' => 'Efisiensi Anggaran (Harga)',
                'C2' => 'Performa Komputasi Tinggi',
                'C3' => 'Kapasitas RAM Multitasking',
                'C4' => 'Kecepatan & Kapasitas Storage SSD',
                'C5' => 'Daya Tahan Baterai Seharian',
                'C6' => 'Portabilitas & Bobot Ringan',
            ];
            $topCriteriaName = $map[$code] ?? $code;
        }

        foreach ($selectedItems as $item) {
            $laptopId = $item['laptop_id'] ?? $item['laptop']->id;
            $laptop = $item['laptop'] ?? Laptop::find($laptopId);

            if (!$laptop) {
                continue;
            }

            // 1. Periksa apakah sudah ada di cache tabel penjelasan_ai
            $cached = PenjelasanAi::where('kuisioner_jawaban_id', $jawaban->id)
                ->where('laptop_id', $laptop->id)
                ->first();

            if ($cached) {
                $explanations[$laptop->id] = $cached->penjelasan;
                continue;
            }

            // 2. Jika belum ada di cache, generate penjelasan naratif
            $narasi = $this->generateNarrative($laptop, $jawaban, $item, $topCriteriaName);

            // 3. Simpan ke cache tabel penjelasan_ai
            PenjelasanAi::create([
                'kuisioner_jawaban_id' => $jawaban->id,
                'laptop_id' => $laptop->id,
                'penjelasan' => $narasi,
            ]);

            $explanations[$laptop->id] = $narasi;
        }

        return $explanations;
    }

    /**
     * Membangun narasi penjelasan menggunakan LLM API (jika terkonfigurasi) atau generator sintesis cerdas.
     */
    protected function generateNarrative(Laptop $laptop, KuisionerJawaban $jawaban, array $item, string $topCriteriaName): string
    {
        $apiKey = config('services.gemini.api_key') ?: env('GEMINI_API_KEY');
        $rank = $item['peringkat'] ?? 1;
        $scorePct = isset($item['nilai_v']) ? round($item['nilai_v'] * 100, 1) : 95.0;

        if (!empty($apiKey)) {
            try {
                $prompt = "Hasil dari Sistem Rekomendasi Laptop Mahasiswa (SPK TOPSIS), buat penjelasan ringkas (2-3 kalimat lugas) mengapa laptop berikut cocok untuk mahasiswa:\n"
                    . "- Nama Laptop: {$laptop->name} ({$laptop->condition})\n"
                    . "- Harga: Rp " . number_format($laptop->price, 0, ',', '.') . "\n"
                    . "- Spesifikasi: RAM {$laptop->ram_gb}GB, SSD {$laptop->storage_gb}GB, Baterai {$laptop->battery_hours} jam, Berat {$laptop->weight_kg} kg, Skor Performa: {$laptop->performa}\n"
                    . "- Peringkat Rekomendasi: Peringkat {$rank} (Skor TOPSIS: {$scorePct}%)\n"
                    . "- Kebutuhan Mahasiswa: Peruntukan {$jawaban->peruntukan}, Frekuensi bawa: {$jawaban->frekuensi_membawa}, Prioritas Utama: {$topCriteriaName}\n"
                    . "Gunakan bahasa Indonesia yang profesional, ramah, dan solutif.";

                $response = Http::timeout(5)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ]
                ]);

                if ($response->successful()) {
                    $aiText = $response->json('candidates.0.content.parts.0.text');
                    if (!empty($aiText)) {
                        return trim($aiText);
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Hasil Service Fallback: ' . $e->getMessage());
            }
        }

        // Generator Cerdas Kontekstual (High-fidelity narrative fallback)
        $kondisiLabel = $laptop->condition === 'second' ? 'kondisi second berkualitas' : 'kondisi baru bergaransi resmi';
        $hargaFormatted = 'Rp ' . number_format($laptop->price, 0, ',', '.');
        $peruntukan = ucfirst($jawaban->peruntukan);

        $highlights = [];
        if ($laptop->ram_gb >= 16) {
            $highlights[] = "RAM {$laptop->ram_gb}GB yang sangat lapang untuk multitasking";
        } else {
            $highlights[] = "RAM {$laptop->ram_gb}GB yang responsif untuk komputasi harian";
        }

        if ($laptop->storage_gb >= 512) {
            $highlights[] = "penyimpanan SSD {$laptop->storage_gb}GB berkecepatan tinggi";
        }

        if ($laptop->weight_kg && $laptop->weight_kg <= 1.6) {
            $highlights[] = "bobot ultra-ringan {$laptop->weight_kg} kg yang sangat nyaman dibawa (" . strtolower($jawaban->frekuensi_membawa ?: 'rutin') . ")";
        } elseif ($laptop->battery_hours && $laptop->battery_hours >= 7) {
            $highlights[] = "daya tahan baterai hingga {$laptop->battery_hours} jam pemakaian";
        }

        $highlightText = implode(', didukung ', $highlights);

        if ($rank === 1) {
            return "Laptop ini merupakan pilihan terbaik (Peringkat 1) dengan skor preferensi TOPSIS tertinggi sebesar {$scorePct}%. Ditenagai {$highlightText}, laptop {$kondisiLabel} seharga {$hargaFormatted} ini sangat optimal dalam mengakomodasi prioritas utama Anda pada {$topCriteriaName} untuk kebutuhan {$peruntukan}.";
        }

        return "Direkomendasikan pada Peringkat {$rank} dengan skor kecocokan {$scorePct}%. Menawarkan kombinasi seimbang antara {$highlightText}, menjadikannya alternatif yang sangat layak dipertimbangkan untuk kebutuhan {$peruntukan} dengan banderol {$hargaFormatted}.";
    }
}
