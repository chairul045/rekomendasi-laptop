<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriteria';

    protected $fillable = [
        'kode',
        'nama',
        'tipe',
        'bobot',
        'deskripsi',
    ];

    protected $casts = [
        'bobot' => 'float',
    ];

    public function bobotHasil(): HasMany
    {
        return $this->hasMany(BobotKriteriaHasil::class, 'kriteria_id');
    }
}
