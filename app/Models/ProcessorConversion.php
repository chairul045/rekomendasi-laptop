<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcessorConversion extends Model
{
    protected $table = 'processor_conversions';

    protected $fillable = ['label', 'score'];
}
