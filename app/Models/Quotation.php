<?php

// app/Models/Quotation.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = ['contact_id', 'category', 'quotation_number', 'quotation_date', 'subtotal', 'ppn', 'total'];

    protected $casts = [
        'quotation_date' => 'date',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }
}
