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
        Schema::table('fleet_wise_trip_diesel_kms_hours', function (Blueprint $table) {
            $table->integer('number_of_trips')->nullable()->after('diesel_per_quantity');
            $table->decimal('diesel_ltr', 10, 2)->nullable()->after('number_of_trips');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fleet_wise_trip_diesel_kms_hours', function (Blueprint $table) {
            $table->dropColumn(['number_of_trips', 'diesel_ltr']);
        });
    }
};
