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
        Schema::create('amount', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedInteger('account_id');
            $table->unsignedInteger('opp_account_id')->nullable();
            // $table->string('name')->nullable();
            $table->decimal('amount', 20, 2);
            $table->string('notes')->nullable();
            $table->enum('type', ['credit', 'debit']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amount');
    }
};
