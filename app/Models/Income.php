<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable = ['deal_id', 'description', 'amount', 'date'];

    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }

    public function getClientNameAttribute()
    {
        return $this->deal->contact->name ?? null;
    }
}
