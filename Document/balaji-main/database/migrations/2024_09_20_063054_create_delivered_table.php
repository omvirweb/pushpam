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
        Schema::create('delivered', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('transactions_id');
            $table->foreign('transactions_id')->references('id')->on('transactions');
            $table->decimal('delivered_qty', 8, 3)->nullable()->default(0);
            $table->date('delivered_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivered');
    }
};
