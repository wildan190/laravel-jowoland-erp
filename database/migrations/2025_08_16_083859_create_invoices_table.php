<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('invoice_number')->unique();
            $table->decimal('project_amount', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0); // project_amount + items
            $table->decimal('tax', 15, 2)->default(0); // 11% dari subtotal
            $table->decimal('grand_total', 15, 2)->default(0); // subtotal + tax
            $table->date('due_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
