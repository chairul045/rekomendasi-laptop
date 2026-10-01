<?php

namespace App\Services;

class RecommendationService
{
    public function calculateWeights(array $answers): array
    {
        $base = [
            'C1' => 0.18,
            'C2' => 0.17,
            'C3' => 0.16,
            'C4' => 0.14,
            'C5' => 0.13,
            'C6' => 0.12,
            'C7' => 0.10,
        ];

        $profile = [
            'budget' => $this->normalizeBudget($answers['budget_range'] ?? '7-10 juta'),
            'goal' => $answers['primary_goal'] ?? 'kuliah',
            'mobility' => $answers['mobility'] ?? 'ringan',
            'ram' => $this->normalizeRam($answers['min_ram'] ?? '16GB'),
            'storage' => $this->normalizeStorage($answers['min_storage'] ?? '512GB'),
            'screen' => $this->normalizeScreen($answers['screen_size'] ?? '14-15.6'),
        ];

        $adjustments = [
            'budget' => [
                'under-5-juta' => ['C1' => 0.22, 'C2' => 0.16, 'C3' => 0.15, 'C4' => 0.15, 'C5' => 0.10, 'C6' => 0.12, 'C7' => 0.10],
                '5-7-juta' => ['C1' => 0.19, 'C2' => 0.17, 'C3' => 0.15, 'C4' => 0.15, 'C5' => 0.12, 'C6' => 0.12, 'C7' => 0.10],
                '7-10-juta' => ['C1' => 0.18, 'C2' => 0.18, 'C3' => 0.16, 'C4' => 0.14, 'C5' => 0.12, 'C6' => 0.12, 'C7' => 0.10],
                '10-15-juta' => ['C1' => 0.17, 'C2' => 0.19, 'C3' => 0.17, 'C4' => 0.14, 'C5' => 0.13, 'C6' => 0.11, 'C7' => 0.09],
                '15-juta-keatas' => ['C1' => 0.15, 'C2' => 0.20, 'C3' => 0.18, 'C4' => 0.15, 'C5' => 0.14, 'C6' => 0.10, 'C7' => 0.08],
            ],
            'goal' => [
                'kuliah' => ['C1' => 0.18, 'C2' => 0.18, 'C3' => 0.14, 'C4' => 0.14, 'C5' => 0.12, 'C6' => 0.13, 'C7' => 0.11],
                'desain' => ['C1' => 0.17, 'C2' => 0.19, 'C3' => 0.15, 'C4' => 0.15, 'C5' => 0.13, 'C6' => 0.12, 'C7' => 0.09],
                'programming' => ['C1' => 0.15, 'C2' => 0.19, 'C3' => 0.18, 'C4' => 0.16, 'C5' => 0.12, 'C6' => 0.11, 'C7' => 0.09],
                'kerja-kantoran' => ['C1' => 0.18, 'C2' => 0.18, 'C3' => 0.14, 'C4' => 0.14, 'C5' => 0.12, 'C6' => 0.14, 'C7' => 0.10],
                'gaming' => ['C1' => 0.14, 'C2' => 0.18, 'C3' => 0.13, 'C4' => 0.19, 'C5' => 0.15, 'C6' => 0.11, 'C7' => 0.10],
            ],
            'mobility' => [
                'ringan' => ['C1' => 0.20, 'C2' => 0.15, 'C3' => 0.10, 'C4' => 0.15, 'C5' => 0.10, 'C6' => 0.16, 'C7' => 0.14],
                'stasioner' => ['C1' => 0.17, 'C2' => 0.18, 'C3' => 0.18, 'C4' => 0.14, 'C5' => 0.16, 'C6' => 0.09, 'C7' => 0.08],
            ],
            'ram' => [
                '8GB' => ['C1' => 0.16, 'C2' => 0.14, 'C3' => 0.14, 'C4' => 0.14, 'C5' => 0.15, 'C6' => 0.15, 'C7' => 0.12],
                '16GB' => ['C1' => 0.16, 'C2' => 0.17, 'C3' => 0.16, 'C4' => 0.15, 'C5' => 0.14, 'C6' => 0.12, 'C7' => 0.10],
                '32GB' => ['C1' => 0.15, 'C2' => 0.18, 'C3' => 0.18, 'C4' => 0.16, 'C5' => 0.15, 'C6' => 0.09, 'C7' => 0.09],
            ],
            'storage' => [
                '256GB' => ['C1' => 0.17, 'C2' => 0.15, 'C3' => 0.14, 'C4' => 0.15, 'C5' => 0.15, 'C6' => 0.12, 'C7' => 0.12],
                '512GB' => ['C1' => 0.16, 'C2' => 0.16, 'C3' => 0.15, 'C4' => 0.15, 'C5' => 0.14, 'C6' => 0.12, 'C7' => 0.12],
                '1TB' => ['C1' => 0.15, 'C2' => 0.17, 'C3' => 0.17, 'C4' => 0.15, 'C5' => 0.15, 'C6' => 0.11, 'C7' => 0.10],
            ],
            'screen' => [
                '13-14' => ['C1' => 0.18, 'C2' => 0.15, 'C3' => 0.10, 'C4' => 0.15, 'C5' => 0.10, 'C6' => 0.18, 'C7' => 0.14],
                '14-15.6' => ['C1' => 0.17, 'C2' => 0.16, 'C3' => 0.15, 'C4' => 0.15, 'C5' => 0.12, 'C6' => 0.15, 'C7' => 0.10],
                '16-17' => ['C1' => 0.15, 'C2' => 0.17, 'C3' => 0.17, 'C4' => 0.16, 'C5' => 0.15, 'C6' => 0.11, 'C7' => 0.09],
            ],
        ];

        foreach ($profile as $key => $value) {
            if (isset($adjustments[$key][$value])) {
                foreach ($adjustments[$key][$value] as $criterion => $weight) {
                    $base[$criterion] += $weight;
                }
            }
        }

        $total = array_sum($base);
        foreach ($base as $key => $value) {
            $base[$key] = $value / $total;
        }

        return $base;
    }

    protected function normalizeBudget(string $budget): string
    {
        $value = strtolower(trim($budget));

        if (str_contains($value, 'under') || str_contains($value, '<') || str_contains($value, 'dibawah')) {
            return 'under-5-juta';
        }
        if (str_contains($value, '5') && str_contains($value, '7')) {
            return '5-7-juta';
        }
        if (str_contains($value, '7') && str_contains($value, '10')) {
            return '7-10-juta';
        }
        if (str_contains($value, '10') && str_contains($value, '15')) {
            return '10-15-juta';
        }

        return '15-juta-keatas';
    }

    protected function normalizeRam(string $ram): string
    {
        $value = (int) preg_replace('/[^0-9]/', '', $ram);

        if ($value >= 32) {
            return '32GB';
        }
        if ($value >= 16) {
            return '16GB';
        }

        return '8GB';
    }

    protected function normalizeStorage(string $storage): string
    {
        $value = (int) preg_replace('/[^0-9]/', '', $storage);

        if ($value >= 1000) {
            return '1TB';
        }
        if ($value >= 512) {
            return '512GB';
        }

        return '256GB';
    }

    protected function normalizeScreen(string $screen): string
    {
        $value = strtolower(trim($screen));

        if (str_contains($value, '16') || str_contains($value, '17')) {
            return '16-17';
        }
        if (str_contains($value, '13') || str_contains($value, '14')) {
            return '13-14';
        }

        return '14-15.6';
    }

    public function rankLaptops(array $items, array $weights): array
    {
        if (empty($items)) {
            return [];
        }

        $criteriaMap = [
            'price' => 'C1',
            'processor_score' => 'C2',
            'ram' => 'C3',
            'storage' => 'C4',
            'display_size' => 'C5',
            'vga_score' => 'C6',
            'mobility_score' => 'C7',
        ];

        $minMax = [];
        foreach ($criteriaMap as $field => $criterion) {
            $values = array_column($items, $field);
            $minMax[$field] = [
                'min' => min($values),
                'max' => max($values),
            ];
        }

        $weightedMatrix = [];
        foreach ($items as $item) {
            $row = ['name' => $item['name']];
            foreach ($criteriaMap as $field => $criterion) {
                $value = $item[$field];
                $min = $minMax[$field]['min'];
                $max = $minMax[$field]['max'];

                if ($field === 'price') {
                    $normalized = $max == $min ? 1 : 1 - (($value - $min) / ($max - $min));
                } else {
                    $normalized = $max == $min ? 1 : (($value - $min) / ($max - $min));
                }

                $row[$field] = $normalized * ($weights[$criterion] ?? 0.1);
            }
            $weightedMatrix[] = $row;
        }

        $idealPositive = [];
        $idealNegative = [];
        foreach ($criteriaMap as $field => $criterion) {
            $values = array_column($weightedMatrix, $field);
            $idealPositive[$field] = max($values);
            $idealNegative[$field] = min($values);
        }

        $ranked = [];
        foreach ($weightedMatrix as $index => $row) {
            $distancePositive = 0;
            $distanceNegative = 0;

            foreach ($criteriaMap as $field => $criterion) {
                $distancePositive += pow($row[$field] - $idealPositive[$field], 2);
                $distanceNegative += pow($row[$field] - $idealNegative[$field], 2);
            }

            $distancePositive = sqrt($distancePositive);
            $distanceNegative = sqrt($distanceNegative);

            $score = $distancePositive + $distanceNegative == 0 ? 0 : $distanceNegative / ($distancePositive + $distanceNegative);
            $ranked[] = [
                'name' => $row['name'],
                'score' => $score,
                'price' => $items[$index]['price'],
                'ram' => $items[$index]['ram'],
                'storage' => $items[$index]['storage'],
                'display_size' => $items[$index]['display_size'],
            ];
        }

        usort($ranked, fn ($a, $b) => $b['score'] <=> $a['score']);

        return $ranked;
    }
}
