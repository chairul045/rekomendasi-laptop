<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenjelasanAi extends Model
{
    use HasFactory;

    protected $table = 'penjelasan_ai';

    protected $fillable = [
        'kuisioner_jawaban_id',
        'laptop_id',
        'penjelasan',
    ];

    public function kuisionerJawaban(): BelongsTo
    {
        return $this->belongsTo(KuisionerJawaban::class, 'kuisioner_jawaban_id');
    }

    public function laptop(): BelongsTo
    {
        return $this->belongsTo(Laptop::class, 'laptop_id');
    }
}
