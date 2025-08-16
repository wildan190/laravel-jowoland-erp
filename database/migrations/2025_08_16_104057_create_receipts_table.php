<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptsTable extends Migration
{
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number')->unique(); // nomor kwitansi otomatis
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade'); // relasi ke invoice
            $table->decimal('amount', 15, 2); // total kwitansi
            $table->date('date'); // tanggal kwitansi
            $table->text('note')->nullable(); // catatan opsional
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('receipts');
    }
}
