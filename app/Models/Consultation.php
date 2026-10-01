<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consultation extends Model
{
    protected $fillable = [
        'user_id',
        'questionnaire',
        'weights',
        'ranking',
        'best_laptop_name',
        'best_laptop_price',
    ];

    protected $casts = [
        'questionnaire' => 'array',
        'weights' => 'array',
        'ranking' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTitleAttribute(): string
    {
        return $this->questionnaire['title'] ?? ('Kuisioner #' . $this->id);
    }
}
