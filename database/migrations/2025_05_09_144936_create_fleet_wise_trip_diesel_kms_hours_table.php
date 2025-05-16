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
        Schema::create('fleet_wise_trip_diesel_kms_hours', function (Blueprint $table) {
            $table->id();
            $table->string('location')->nullable();
            $table->string('door_no');
            $table->decimal('monthly_kms', 10, 2)->nullable();
            $table->decimal('monthly_hours', 10, 2)->nullable();
            $table->decimal('lead_in_kms', 10, 3)->nullable();
            $table->decimal('hsd_per_km', 10, 3)->nullable();
            $table->decimal('hsd_per_hour', 10, 3)->nullable();
            $table->decimal('diesel_per_quantity', 10, 2)->nullable();
            $table->integer('number_of_trips')->nullable();
            $table->decimal('diesel_ltr', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fleet_wise_trip_diesel_kms_hours');
    }
};
