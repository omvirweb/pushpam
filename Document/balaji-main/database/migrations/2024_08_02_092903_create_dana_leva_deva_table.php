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
        Schema::create('dana_leva_deva', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('name');
            $table->decimal('weight', 8, 3);
            $table->decimal('rate', 8, 2);
            $table->decimal('total', 8, 2);
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
        Schema::dropIfExists('dana_leva_deva');
    }
};
