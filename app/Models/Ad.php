<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ad extends Model
{
    use HasFactory;
    protected $fillable = ['img'];

    public function getImgUrlAttribute()
    {
        return $this->img ? asset('storage/ads/' . $this->img) : asset('img/logo.png');
    }
}
