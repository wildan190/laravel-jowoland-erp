<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['project_id', 'invoice_number', 'project_amount', 'items_total', 'subtotal', 'tax', 'grand_total', 'due_date', 'is_pending'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function calculateTotals()
    {
        $itemsTotal = $this->items->sum('price');
        $subtotal = $this->project_amount + $itemsTotal;
        $tax = $subtotal * 0.11;
        $grandTotal = $subtotal + $tax;

        return compact('subtotal', 'tax', 'grandTotal');
    }
}
