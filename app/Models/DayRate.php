<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DayRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'value'
    ];
}
