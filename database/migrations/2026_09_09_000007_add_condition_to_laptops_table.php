<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laptops', function (Blueprint $table) {
            if (!Schema::hasColumn('laptops', 'condition')) {
                $table->string('condition')->default('baru')->after('category');
            }
        });
    }

    public function down(): void
    {
        Schema::table('laptops', function (Blueprint $table) {
            if (Schema::hasColumn('laptops', 'condition')) {
                $table->dropColumn('condition');
            }
        });
    }
};
