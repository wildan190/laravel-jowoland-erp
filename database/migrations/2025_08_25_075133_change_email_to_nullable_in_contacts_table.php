<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah kolom email menjadi nullable dan hapus constraint unique
        DB::statement('ALTER TABLE `contacts` MODIFY `email` VARCHAR(255) NULL;');
        // Catatan: Ini tidak akan menghapus index unik secara otomatis,
        // Anda mungkin perlu menghapusnya secara manual jika ada.
        // Contoh: DB::statement('ALTER TABLE `contacts` DROP INDEX `contacts_email_unique`;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan kolom email menjadi NOT NULL dan tambahkan constraint unique
        // Perhatian: Ini akan gagal jika ada data dengan nilai email NULL
        DB::statement('ALTER TABLE `contacts` MODIFY `email` VARCHAR(255) NOT NULL;');
        // DB::statement('ALTER TABLE `contacts` ADD UNIQUE INDEX (`email`);');
    }
};
