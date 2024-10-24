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
        Schema::create('transactions', function (Blueprint $table) {
              // $table->id();
              $table->increments('id');
              $table->date('date');
              // $table->string('name');
              $table->unsignedInteger('account_id');
              // $table->integer('account_id');
              $table->decimal('amount', 15, 2)->nullable();
              $table->unsignedInteger('opp_account_id')->nullable();
              // $table->integer('opp_account_id')->nullable();
              $table->decimal('fine', 15, 3)->nullable();
            //   $table->string('item')->nullable();
              $table->unsignedInteger('item')->nullable();
              $table->decimal('dhal', 15, 3)->nullable();
              $table->decimal('touch', 15, 2)->nullable();
              $table->decimal('fineCalc', 15, 3)->nullable();
              $table->text('notes')->nullable();
              $table->enum('type', ['credit', 'debit']);
              $table->decimal('weight', 8, 3)->nullable()->default(0);
              $table->decimal('rate', 8, 2)->nullable()->default(0);
              $table->string('is_delivered', 100)->nullable();
              $table->string('method', 100)->nullable()->default('1')->comment('1=Chorsa,2=Amount,3=Fine,4=Dhal');
              $table->timestamps();

              // $table->foreign('account_id')->references('account_id')->on('account');
              // $table->foreign('opp_account_id')->references('account_id')->on('account');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
