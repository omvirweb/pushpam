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
        Schema::table('fleet_data', function (Blueprint $table) {
            $table->float('monthly_hour')->nullable()->after('cost_per_hour');
            $table->float('monthly_kms')->nullable()->after('monthly_hour');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fleet_data', function (Blueprint $table) {
            $table->dropColumn(['monthly_hour', 'monthly_kms']);
        });
    }
};
