<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable = ['contact_id', 'invoice_id', 'description', 'amount', 'date'];

    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function getClientNameAttribute()
    {
        return $this->deal->contact->name ?? null;
    }
}
