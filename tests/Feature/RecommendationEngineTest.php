<?php

namespace Tests\Feature;

use App\Services\RecommendationService;
use Tests\TestCase;

class RecommendationEngineTest extends TestCase
{
    public function test_dynamic_weights_are_normalized_and_topsis_returns_ordered_results(): void
    {
        $service = new RecommendationService();

        $weights = $service->calculateWeights([
            'budget_range' => '7-10 juta',
            'primary_goal' => 'programming',
            'mobility' => 'ringan',
            'min_ram' => '16GB',
            'min_storage' => '512GB',
            'screen_size' => '14-15.6',
        ]);

        $this->assertCount(7, $weights);
        $this->assertEqualsWithDelta(1.0, array_sum($weights), 0.0001);

        $items = [
            ['name' => 'Laptop A', 'price' => 8500000, 'ram' => 8, 'storage' => 512, 'display_size' => 14, 'processor_score' => 78, 'vga_score' => 55, 'mobility_score' => 70],
            ['name' => 'Laptop B', 'price' => 12000000, 'ram' => 16, 'storage' => 1000, 'display_size' => 15.6, 'processor_score' => 82, 'vga_score' => 70, 'mobility_score' => 62],
            ['name' => 'Laptop C', 'price' => 6500000, 'ram' => 16, 'storage' => 512, 'display_size' => 13.3, 'processor_score' => 74, 'vga_score' => 52, 'mobility_score' => 88],
        ];

        $ranked = $service->rankLaptops($items, $weights);

        $this->assertNotEmpty($ranked);
        $this->assertSame('Laptop B', $ranked[0]['name']);
    }
}
