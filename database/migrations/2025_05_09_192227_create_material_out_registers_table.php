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
        Schema::create('material_out_registers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->string('party_name');
            $table->string('voucher_type')->nullable();
            $table->string('voucher_group')->nullable();
            $table->string('godown')->nullable();
            $table->string('ref_no')->nullable();
            $table->string('voucher_no')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->enum('transaction_type', ['Dr', 'Cr'])->nullable();
            $table->string('kms')->nullable();
            $table->string('kms_life')->nullable();
            $table->string('hmr')->nullable();
            $table->string('hmr_life')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_out_registers');
    }
};
