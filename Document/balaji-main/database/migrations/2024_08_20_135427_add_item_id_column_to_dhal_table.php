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
        // Schema::table('dhal', function (Blueprint $table) {
        //     $table->unsignedInteger('item_id')->after('date')->nullable();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('dhal', function (Blueprint $table) {
        //     if (Schema::hasColumn('dhal', 'item_id'))
        //     {
        //         Schema::table('dhal', function (Blueprint $table) {
        //             $table->dropColumn('item_id');
        //         });
        //     }
        // });
    }
};
