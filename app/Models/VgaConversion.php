<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VgaConversion extends Model
{
    protected $table = 'vga_conversions';

    protected $fillable = ['label', 'score'];
}
