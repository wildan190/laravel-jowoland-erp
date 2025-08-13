<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->unsignedBigInteger('deal_id')->nullable()->after('id');
        });

        // Migrasi data lama ke deal_id jika ada mapping yang bisa dilakukan
        DB::table('incomes')->orderBy('id')->chunk(100, function ($incomes) {
            foreach ($incomes as $income) {
                $deal = DB::table('deals')
                    ->where('contact_id', $income->contact_id)
                    ->where('value', $income->amount)
                    ->first();

                if ($deal) {
                    DB::table('incomes')
                        ->where('id', $income->id)
                        ->update(['deal_id' => $deal->id]);
                }
            }
        });

        Schema::table('incomes', function (Blueprint $table) {
            $table->foreign('deal_id')->references('id')->on('deals')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->dropForeign(['deal_id']);
            $table->dropColumn('deal_id');
        });
    }
};
