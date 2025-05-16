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
            $table->string('total_cost_type')->nullable()->after('total_cost');
            $table->string('category_amount_type')->nullable()->after('category_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fleet_data', function (Blueprint $table) {
            $table->dropColumn(['total_cost_type', 'category_amount_type']);
        });
    }
};
