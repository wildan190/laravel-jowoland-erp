<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ads_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // nama campaign
            $table->string('objective'); // misalnya: Awareness, Engagement, Leads
            $table->text('audience'); // target audience
            $table->decimal('budget', 15, 2);
            $table->date('start_date');
            $table->date('end_date');
            $table->string('platform')->default('Meta Ads'); // bisa ditambah Google, TikTok, dll
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ads_plans');
    }
};
