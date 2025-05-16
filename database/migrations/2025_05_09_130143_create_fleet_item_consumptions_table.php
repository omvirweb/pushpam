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
        Schema::create('fleet_item_consumptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('sr_no')->nullable();
            $table->date('date')->nullable();
            $table->string('vch_no')->nullable();
            $table->string('door_no')->nullable();
            $table->string('godown_name')->nullable();
            $table->float('kms', 10, 2)->nullable();
            $table->float('hmr', 10, 2)->nullable();
            $table->string('item_name');
            $table->string('stock_group')->nullable();
            $table->string('stock_category')->nullable();
            $table->string('unit')->nullable();
            $table->float('quantity', 10, 2)->nullable();
            $table->string('unit_type')->nullable();
            $table->float('rate', 10, 2)->nullable();
            $table->float('amount', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fleet_item_consumptions');
    }
};
