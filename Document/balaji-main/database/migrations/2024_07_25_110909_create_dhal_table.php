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
        Schema::create('dhal', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedInteger('item_id');
            $table->decimal('dhal', 20, 3);
            $table->decimal('touch', 10, 2);
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
        Schema::dropIfExists('dhal');
    }
};
