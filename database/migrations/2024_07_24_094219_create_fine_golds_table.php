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
        Schema::create('fine_golds', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('name');
            $table->decimal('fine', 20, 3);
            $table->string('notes')->nullable();
            $table->enum('type', ['credit', 'debit']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fine_golds');
    }
};
