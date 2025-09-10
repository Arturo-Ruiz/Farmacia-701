<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Carousel extends Model
{
    use HasFactory;
    protected $fillable = ['img'];

    public function getImgUrlAttribute()
    {
        return $this->img ? asset('storage/carousels/' . $this->img) : asset('img/logo.png');
    }
}
