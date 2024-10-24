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
        Schema::create('chorsa', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            // $table->string('name')->nullable();
            $table->unsignedInteger('account_id');
            $table->decimal('weight', 8, 2);
            $table->decimal('rate', 8, 2);
            $table->decimal('total', 8, 2);
            $table->string('is_delivered', 100)->nullable()->default('0');
            $table->text('notes')->nullable();
            $table->enum('type', ['levana', 'devana']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chorsa');
    }
};
