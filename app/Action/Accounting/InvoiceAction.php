<?php

namespace App\Action\Accounting;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class InvoiceAction
{
    public static function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            // 🔹 Ambil project + deal value
            $project = Project::with('deal')->findOrFail($data['project_id']);
            $projectAmount = $project->deal->value ?? 0;

            // 🔹 Hitung items tambahan
            $itemsTotal = 0;
            if (! empty($data['items'])) {
                foreach ($data['items'] as $item) {
                    $itemsTotal += $item['price'] ?? 0;
                }
            }

            // 🔹 Hitung subtotal, tax, grand total
            $subtotal = $projectAmount + $itemsTotal;
            $tax = $subtotal * 0.11;
            $grandTotal = $subtotal + $tax;

            // 🚀 Generate nomor invoice otomatis
            $latest = Invoice::latest('id')->first();
            $nextNumber = 'INV-'.date('Y').'-'.str_pad(($latest->id ?? 0) + 1, 4, '0', STR_PAD_LEFT);

            // 🔹 Simpan invoice
            $invoice = Invoice::create([
                'project_id' => $data['project_id'],
                'invoice_number' => $nextNumber,
                'due_date' => $data['due_date'],
                'project_amount' => $projectAmount,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'grand_total' => $grandTotal,
            ]);

            // 🔹 Simpan item detail
            if (! empty($data['items'])) {
                foreach ($data['items'] as $item) {
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'description' => $item['description'],
                        'price' => $item['price'],
                    ]);
                }
            }

            return $invoice;
        });
    }
}
