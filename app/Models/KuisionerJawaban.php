<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KuisionerJawaban extends Model
{
    use HasFactory;

    protected $table = 'kuisioner_jawaban';

    protected $fillable = [
        'user_id',
        'budget_min',
        'budget_max',
        'peruntukan',
        'ranking_kriteria',
        'merek_pilihan',
        'kondisi_pilihan',
        'frekuensi_membawa',
        'judul',
        'tanggal_pengisian',
    ];

    protected $casts = [
        'ranking_kriteria' => 'array',
        'merek_pilihan' => 'array',
        'budget_min' => 'integer',
        'budget_max' => 'integer',
        'tanggal_pengisian' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bobotHasil(): HasMany
    {
        return $this->hasMany(BobotKriteriaHasil::class, 'kuisioner_jawaban_id')->orderBy('prioritas');
    }

    public function hasilTopsis(): HasMany
    {
        return $this->hasMany(HasilTopsis::class, 'kuisioner_jawaban_id')->orderBy('peringkat');
    }

    public function hasilBaru(): HasMany
    {
        return $this->hasMany(HasilTopsis::class, 'kuisioner_jawaban_id')
            ->where('kondisi', 'baru')
            ->orderBy('peringkat');
    }

    public function hasilSecond(): HasMany
    {
        return $this->hasMany(HasilTopsis::class, 'kuisioner_jawaban_id')
            ->where('kondisi', 'second')
            ->orderBy('peringkat');
    }

    public function penjelasanAi(): HasMany
    {
        return $this->hasMany(PenjelasanAi::class, 'kuisioner_jawaban_id');
    }

    public function getJudulSesiAttribute(): string
    {
        return $this->judul ?: ('Sesi Kuisioner #' . $this->id);
    }

    public function getEffectiveDateAttribute()
    {
        return $this->tanggal_pengisian ?: $this->created_at;
    }

    public function getTanggalFormattedAttribute(): string
    {
        $dt = $this->effective_date;
        if (!$dt) {
            return '-';
        }
        return $dt->timezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') . ' WIB';
    }

    public function getTanggalShortAttribute(): string
    {
        $dt = $this->effective_date;
        if (!$dt) {
            return '-';
        }
        return $dt->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') . ' WIB';
    }

    public function getTanggalDateOnlyAttribute(): string
    {
        $dt = $this->effective_date;
        if (!$dt) {
            return '-';
        }
        return $dt->timezone('Asia/Jakarta')->translatedFormat('d F Y');
    }
}
