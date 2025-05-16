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
        //Daybook
        Schema::create('voucher_entries', function (Blueprint $table) {
            $table->id();
            $table->date('voucher_date')->nullable();
            $table->string('voucher_type')->nullable();
            $table->string('voucher_number')->nullable();
            $table->uuid('guid')->nullable();
            $table->unsignedBigInteger('master_id')->nullable();
            $table->unsignedBigInteger('alter_id')->nullable();
            $table->string('voucher_key')->nullable();
            $table->string('entered_by')->nullable();
            $table->string('ref_no')->nullable();
            $table->date('ref_date')->nullable();
            $table->string('cost_centre_name')->nullable();
            $table->text('narration')->nullable();
            $table->integer('kms_reading')->nullable();
            $table->integer('hours_reading')->nullable();
            $table->decimal('diesel_ltr', 10, 2)->nullable();
            $table->integer('no_of_trips')->nullable();
            $table->decimal('trip_factor', 10, 2)->nullable();
            $table->decimal('quantity', 10, 2)->nullable();
            $table->string('entry_type')->nullable();
            $table->string('entry_ledger_name')->nullable();
            $table->decimal('entry_amount', 20, 2)->nullable();
            $table->enum('entry_side', ['Dr', 'Cr'])->nullable(); // extracted from amount string
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucher_entries');
    }
};
