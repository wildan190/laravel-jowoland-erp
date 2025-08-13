<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('vendor'); // Vendor / Bank
            $table->decimal('principal', 15, 2); // Besaran hutang
            $table->decimal('interest_rate', 5, 2); // Bunga dalam persen
            $table->integer('installments'); // Berapa bulan dicicil
            $table->date('due_date'); // Tanggal jatuh tempo
            $table->text('description')->nullable(); // Keterangan tambahan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
