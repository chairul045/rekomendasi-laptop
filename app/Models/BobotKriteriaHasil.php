<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BobotKriteriaHasil extends Model
{
    use HasFactory;

    protected $table = 'bobot_kriteria_hasil';

    protected $fillable = [
        'kuisioner_jawaban_id',
        'kriteria_id',
        'prioritas',
        'bobot',
    ];

    protected $casts = [
        'prioritas' => 'integer',
        'bobot' => 'float',
    ];

    public function kuisionerJawaban(): BelongsTo
    {
        return $this->belongsTo(KuisionerJawaban::class, 'kuisioner_jawaban_id');
    }

    public function kriteria(): BelongsTo
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id');
    }
}
