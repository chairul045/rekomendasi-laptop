<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Pastikan kolom performa_komposit dan atribut pendukung ada di tabel laptops
        Schema::table('laptops', function (Blueprint $table) {
            if (!Schema::hasColumn('laptops', 'performa_komposit')) {
                $table->integer('performa_komposit')->nullable()->after('display_size');
            }
        });

        // Update nilai performa_komposit dari processor_score dan vga_score jika masih null
        DB::statement("
            UPDATE laptops 
            SET performa_komposit = ROUND((COALESCE(processor_score, 50) + COALESCE(vga_score, 50)) / 2) 
            WHERE performa_komposit IS NULL
        ");

        // Pastikan sebagian laptop memiliki kondisi 'second' untuk dataset pengujian jika semua masih 'baru'
        $secondCount = DB::table('laptops')->where('condition', 'second')->count();
        if ($secondCount === 0) {
            DB::statement("UPDATE laptops SET `condition` = 'second' WHERE id % 2 = 0");
        }

        // 2. Tabel kriteria (6 Kriteria acuan: Harga, Performa, RAM, Storage, Baterai, Portabilitas)
        if (!Schema::hasTable('kriteria')) {
            Schema::create('kriteria', function (Blueprint $table) {
                $table->id();
                $table->string('kode', 10)->unique();
                $table->string('nama', 100);
                $table->enum('tipe', ['benefit', 'cost'])->default('benefit');
                $table->text('deskripsi')->nullable();
                $table->timestamps();
            });

            // Seed 6 Kriteria Default
            DB::table('kriteria')->insert([
                [
                    'kode' => 'C1',
                    'nama' => 'Harga',
                    'tipe' => 'cost',
                    'deskripsi' => 'Biaya pengadaan laptop, semakin murah semakin baik.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'kode' => 'C2',
                    'nama' => 'Performa',
                    'tipe' => 'benefit',
                    'deskripsi' => 'Performa komposit komputasi CPU dan GPU.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'kode' => 'C3',
                    'nama' => 'RAM',
                    'tipe' => 'benefit',
                    'deskripsi' => 'Kapasitas memori RAM multitasking (GB).',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'kode' => 'C4',
                    'nama' => 'Storage',
                    'tipe' => 'benefit',
                    'deskripsi' => 'Kapasitas ruang penyimpanan SSD (GB).',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'kode' => 'C5',
                    'nama' => 'Baterai',
                    'tipe' => 'benefit',
                    'deskripsi' => 'Ketahanan daya baterai pemakaian (Jam).',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'kode' => 'C6',
                    'nama' => 'Portabilitas',
                    'tipe' => 'cost',
                    'deskripsi' => 'Bobot fisik laptop (Kg), semakin ringan semakin portabel.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        // 3. Tabel kuisioner_jawaban (entitas penghubung riwayat pengisian kuisioner)
        if (!Schema::hasTable('kuisioner_jawaban')) {
            Schema::create('kuisioner_jawaban', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->bigInteger('budget_min')->default(0);
                $table->bigInteger('budget_max')->default(50000000);
                $table->string('peruntukan')->default('Kuliah');
                $table->json('ranking_kriteria'); // urutan kriteria hasil drag-and-rank
                $table->json('merek_pilihan');   // array merek terpilih atau ["semua"]
                $table->enum('kondisi_pilihan', ['baru', 'second', 'keduanya'])->default('keduanya');
                $table->string('frekuensi_membawa')->nullable();
                $table->string('judul')->nullable(); // label sesi opsional
                $table->timestamps();
            });
        }

        // 4. Tabel bobot_kriteria_hasil (hasil perhitungan bobot ROC per kriteria per sesi)
        if (!Schema::hasTable('bobot_kriteria_hasil')) {
            Schema::create('bobot_kriteria_hasil', function (Blueprint $table) {
                $table->id();
                $table->foreignId('kuisioner_jawaban_id')->constrained('kuisioner_jawaban')->cascadeOnDelete();
                $table->foreignId('kriteria_id')->constrained('kriteria')->cascadeOnDelete();
                $table->integer('prioritas'); // Urutan peringkat 1 - 6
                $table->decimal('bobot', 6, 4); // Nilai bobot ROC (0.0000 - 1.0000)
                $table->timestamps();
            });
        }

        // 5. Tabel hasil_topsis (skor preferensi Vi dan peringkat per laptop per sesi)
        if (!Schema::hasTable('hasil_topsis')) {
            Schema::create('hasil_topsis', function (Blueprint $table) {
                $table->id();
                $table->foreignId('kuisioner_jawaban_id')->constrained('kuisioner_jawaban')->cascadeOnDelete();
                $table->foreignId('laptop_id')->constrained('laptops')->cascadeOnDelete();
                $table->enum('kondisi', ['baru', 'second']);
                $table->decimal('nilai_v', 8, 6); // Skor preferensi Vi
                $table->integer('peringkat');      // Peringkat 1..N
                $table->timestamps();
            });
        }

        // 6. Tabel penjelasan_ai (cache penjelasan naratif AI untuk setiap laptop top rekomendasi)
        if (!Schema::hasTable('penjelasan_ai')) {
            Schema::create('penjelasan_ai', function (Blueprint $table) {
                $table->id();
                $table->foreignId('kuisioner_jawaban_id')->constrained('kuisioner_jawaban')->cascadeOnDelete();
                $table->foreignId('laptop_id')->constrained('laptops')->cascadeOnDelete();
                $table->text('penjelasan');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('penjelasan_ai');
        Schema::dropIfExists('hasil_topsis');
        Schema::dropIfExists('bobot_kriteria_hasil');
        Schema::dropIfExists('kuisioner_jawaban');
        Schema::dropIfExists('kriteria');
        
        if (Schema::hasColumn('laptops', 'performa_komposit')) {
            Schema::table('laptops', function (Blueprint $table) {
                $table->dropColumn('performa_komposit');
            });
        }
    }
};
