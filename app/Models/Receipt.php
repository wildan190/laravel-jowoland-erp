<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = ['receipt_number', 'project_id', 'amount', 'date', 'note', 'invoice_id'];

    // app/Models/Receipt.php
    protected $casts = [
        'date' => 'datetime', // atau 'date' jika tidak ada waktu
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    // Generate nomor kwitansi otomatis
    public static function generateReceiptNumber()
    {
        $prefix = 'KW/'.date('Y').'/'.date('m').'/';
        $last = self::where('receipt_number', 'like', $prefix.'%')
            ->orderBy('id', 'desc')
            ->first();

        $number = $last ? (int) substr($last->receipt_number, -4) + 1 : 1;

        return $prefix.str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
