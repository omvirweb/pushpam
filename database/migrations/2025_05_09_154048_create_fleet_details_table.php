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
        Schema::create('fleet_details', function (Blueprint $table) {
            $table->id();
            $table->string('s_no')->nullable();
            $table->string('door_no')->nullable();
            $table->boolean('vehicle_status')->default(true);
            $table->string('cost_center')->nullable();
            $table->string('regd_state')->nullable();
            $table->string('invoice_no')->nullable();
            $table->string('name_of_owner')->nullable();
            $table->string('seaction')->nullable();
            $table->date('date_of_delivery')->nullable();
            $table->date('regd_date')->nullable();
            $table->string('regd_rto')->nullable();
            $table->string('regd_no')->nullable();
            $table->string('engine_no')->nullable();
            $table->string('chasis_no')->nullable();
            $table->date('road_tax_from')->nullable();
            $table->date('road_tax_to')->nullable();
            $table->date('fitness_from')->nullable();
            $table->date('fitness_to')->nullable();
            $table->string('permit_for_state')->nullable();
            $table->date('permit_from')->nullable();
            $table->date('permit_to')->nullable();
            $table->string('insured_by')->nullable();
            $table->string('insurance_policy_no')->nullable();
            $table->string('insurance_idv')->nullable();
            $table->date('insurance_from')->nullable();
            $table->date('insurance_to')->nullable();
            $table->date('puc_from')->nullable();
            $table->date('puc_to')->nullable();
            $table->string('name_of_financer')->nullable();
            $table->string('agreement_number')->nullable();
            $table->decimal('loan_amount', 15, 2)->nullable();
            $table->integer('tenure')->nullable();
            $table->date('emi_start_date')->nullable();
            $table->date('emi_end_date')->nullable();
            $table->decimal('emi_amount', 15, 2)->nullable();
            $table->string('loading_capacity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fleet_details');
    }
};
