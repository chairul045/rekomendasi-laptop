<?php

namespace Database\Seeders;

use App\Models\Criterion;
use App\Models\Laptop;
use App\Models\ProcessorConversion;
use App\Models\User;
use App\Models\VgaConversion;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrator',
                'password' => 'admin123',
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'mahasiswa@gmail.com'],
            [
                'name' => 'Mahasiswa User',
                'password' => 'password',
                'role' => 'mahasiswa',
            ]
        );

        if (Criterion::count() === 0) {
            Criterion::insert([
                ['code' => 'C1', 'label' => 'Harga', 'base_weight' => 0.18, 'description' => 'indikator anggaran utama'],
                ['code' => 'C2', 'label' => 'Processor', 'base_weight' => 0.17, 'description' => 'kecepatan kerja dan multitasking'],
                ['code' => 'C3', 'label' => 'RAM', 'base_weight' => 0.16, 'description' => 'kapasitas multitasking'],
                ['code' => 'C4', 'label' => 'Penyimpanan', 'base_weight' => 0.14, 'description' => 'ruang penyimpanan data'],
                ['code' => 'C5', 'label' => 'Ukuran Layar', 'base_weight' => 0.13, 'description' => 'kenyamanan visual'],
                ['code' => 'C6', 'label' => 'VGA', 'base_weight' => 0.12, 'description' => 'kinerja grafis'],
                ['code' => 'C7', 'label' => 'Mobilitas', 'base_weight' => 0.10, 'description' => 'mudah dibawa'],
            ]);
        }

        if (ProcessorConversion::count() === 0) {
            ProcessorConversion::insert([
                ['label' => 'Intel Core i3', 'score' => 55],
                ['label' => 'Intel Core i5', 'score' => 72],
                ['label' => 'Intel Core i7', 'score' => 85],
                ['label' => 'AMD Ryzen 5', 'score' => 74],
                ['label' => 'AMD Ryzen 7', 'score' => 88],
            ]);
        }

        if (VgaConversion::count() === 0) {
            VgaConversion::insert([
                ['label' => 'Intel UHD', 'score' => 40],
                ['label' => 'NVIDIA MX', 'score' => 60],
                ['label' => 'NVIDIA GTX', 'score' => 75],
                ['label' => 'NVIDIA RTX', 'score' => 90],
            ]);
        }

        if (Laptop::count() === 0) {
            Laptop::insert([
                ['name' => 'Acer Aspire 5', 'brand' => 'Acer', 'price' => 8200000, 'ram_gb' => 8, 'storage_gb' => 512, 'display_size' => 14, 'processor_score' => 72, 'vga_score' => 60, 'battery_hours' => 7, 'weight_kg' => 1.8, 'mobility_score' => 75, 'category' => 'kuliah', 'image' => ''],
                ['name' => 'Lenovo Ideapad Gaming 3', 'brand' => 'Lenovo', 'price' => 12600000, 'ram_gb' => 16, 'storage_gb' => 512, 'display_size' => 15.6, 'processor_score' => 82, 'vga_score' => 84, 'battery_hours' => 5, 'weight_kg' => 2.4, 'mobility_score' => 60, 'category' => 'gaming', 'image' => ''],
                ['name' => 'Dell XPS 13', 'brand' => 'Dell', 'price' => 16800000, 'ram_gb' => 16, 'storage_gb' => 512, 'display_size' => 13.4, 'processor_score' => 88, 'vga_score' => 52, 'battery_hours' => 10, 'weight_kg' => 1.2, 'mobility_score' => 95, 'category' => 'programming', 'image' => ''],
                ['name' => 'ASUS TUF Gaming F15', 'brand' => 'ASUS', 'price' => 15400000, 'ram_gb' => 16, 'storage_gb' => 1000, 'display_size' => 15.6, 'processor_score' => 87, 'vga_score' => 90, 'battery_hours' => 5.5, 'weight_kg' => 2.3, 'mobility_score' => 58, 'category' => 'gaming', 'image' => ''],
            ]);
        }
    }
}
