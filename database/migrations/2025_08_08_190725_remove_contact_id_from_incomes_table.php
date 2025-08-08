<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('incomes', function (Blueprint $table) {
            // Langkah 1: Hapus foreign key constraint terlebih dahulu
            $table->dropForeign(['contact_id']);

            // Langkah 2: Hapus kolomnya
            $table->dropColumn('contact_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incomes', function (Blueprint $table) {
            // Langkah 1: Tambahkan kembali kolomnya
            $table->unsignedBigInteger('contact_id')->nullable();

            // Langkah 2: Tambahkan kembali foreign key constraint
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('set null'); // Sesuaikan dengan logika constraint Anda sebelumnya
        });
    }
};