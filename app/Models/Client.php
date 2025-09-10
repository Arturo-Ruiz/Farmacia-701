<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'id_card',
        'email',
        'phone',
        'address',
        'number_of_purchases',
    ];

    protected $casts = [
        'number_of_purchases' => 'integer',
    ];

    public function incrementPurchases(): void
    {
        $this->increment('number_of_purchases');
    }

     public function sales()  
    {  
        return $this->hasMany(Sale::class);  
    }  
}
