<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'category_id',
        'tax_id',
        'name',
        'laboratory',
        'price',
        'stock',
        'img',
        'medical_prescription',
        'sales',
        'min_stock',
        'max_stock',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'medical_prescription' => 'boolean',
        'stock' => 'integer',
        'sales' => 'integer',
        'min_stock' => 'integer',
        'max_stock' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    public function faults()
    {
        return $this->hasMany(ProductFault::class);
    }

    public function getImgUrlAttribute()
    {
        if ($this->img && file_exists(public_path('storage/products/' . $this->img))) {
            return asset('storage/products/' . $this->img);
        }
        return asset('img/logo.png'); 
    }
}
