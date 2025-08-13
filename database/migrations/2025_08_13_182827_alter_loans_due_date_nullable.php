<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah kolom due_date menjadi nullable
        DB::statement('ALTER TABLE loans MODIFY due_date DATE NULL');
    }

    public function down(): void
    {
        // Balik lagi jadi NOT NULL (default)
        DB::statement('ALTER TABLE loans MODIFY due_date DATE NOT NULL');
    }
};
