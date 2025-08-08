<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchasing extends Model
{
    protected $fillable = [
        'item_name',
        'unit_price',
        'quantity',
        'total_price',
        'date'
    ];

    protected static function booted()
    {
        static::saving(function ($purchasing) {
            $purchasing->total_price = $purchasing->unit_price * $purchasing->quantity;
        });
    }
}
