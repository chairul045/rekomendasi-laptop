<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom tanggal_pengisian pada tabel kuisioner_jawaban
        Schema::table('kuisioner_jawaban', function (Blueprint $table) {
            if (!Schema::hasColumn('kuisioner_jawaban', 'tanggal_pengisian')) {
                $table->dateTime('tanggal_pengisian')->nullable()->after('judul');
            }
        });

        // Backfill tanggal_pengisian dari created_at
        DB::statement("UPDATE kuisioner_jawaban SET tanggal_pengisian = created_at WHERE tanggal_pengisian IS NULL");

        // 2. Tambah kolom bobot pada tabel kriteria (untuk pengelolaan bobot hanya di admin)
        Schema::table('kriteria', function (Blueprint $table) {
            if (!Schema::hasColumn('kriteria', 'bobot')) {
                $table->decimal('bobot', 6, 4)->default(0.1667)->after('tipe');
            }
        });

        // Set default bobot acuan kriteria awal
        $defaultWeights = [
            'C1' => 0.4083,
            'C2' => 0.2417,
            'C3' => 0.1583,
            'C4' => 0.1028,
            'C5' => 0.0611,
            'C6' => 0.0278,
        ];
        foreach ($defaultWeights as $kode => $bobot) {
            DB::table('kriteria')->where('kode', $kode)->update(['bobot' => $bobot]);
        }

        // 3. Tambah kolom phone pada tabel users
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 30)->nullable()->after('email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kuisioner_jawaban', function (Blueprint $table) {
            if (Schema::hasColumn('kuisioner_jawaban', 'tanggal_pengisian')) {
                $table->dropColumn('tanggal_pengisian');
            }
        });

        Schema::table('kriteria', function (Blueprint $table) {
            if (Schema::hasColumn('kriteria', 'bobot')) {
                $table->dropColumn('bobot');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'phone')) {
                $table->dropColumn('phone');
            }
        });
    }
};
