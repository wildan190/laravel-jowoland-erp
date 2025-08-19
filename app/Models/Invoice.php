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

    // relasi ke kontak

    public function incomes()
    {
        return $this->hasMany(Income::class);
    }

    public function contact()
    {
        return $this->hasOneThrough(
            Contact::class,
            Income::class,
            'invoice_id', // foreign key di incomes
            'id',         // foreign key di contacts
            'id',         // local key di invoices
            'contact_id'  // local key di incomes
        );
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
