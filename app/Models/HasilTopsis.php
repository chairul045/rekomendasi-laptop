<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilTopsis extends Model
{
    use HasFactory;

    protected $table = 'hasil_topsis';

    protected $fillable = [
        'kuisioner_jawaban_id',
        'laptop_id',
        'kondisi',
        'nilai_v',
        'peringkat',
    ];

    protected $casts = [
        'nilai_v' => 'float',
        'peringkat' => 'integer',
    ];

    public function kuisionerJawaban(): BelongsTo
    {
        return $this->belongsTo(KuisionerJawaban::class, 'kuisioner_jawaban_id');
    }

    public function laptop(): BelongsTo
    {
        return $this->belongsTo(Laptop::class, 'laptop_id');
    }

    public function getSkorPersenAttribute(): float
    {
        return round($this->nilai_v * 100, 2);
    }
}
