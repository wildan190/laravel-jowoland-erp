<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'type', // income / expense
        'income_id',
        'purchasing_id',
        'amount',
        'description',
        'date',
    ];

    public function income()
    {
        return $this->belongsTo(Income::class);
    }

    public function purchasing()
    {
        return $this->belongsTo(Purchasing::class);
    }
}
