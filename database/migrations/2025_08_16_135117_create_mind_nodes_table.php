<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mind_nodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mind_map_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('mind_nodes')->onDelete('cascade');
            $table->string('title');
            $table->text('content')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mind_nodes');
    }
};
