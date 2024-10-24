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
        Schema::create('account', function (Blueprint $table) {
            $table->id('account_id'); // Auto-incrementing ID column
            $table->string('account_name', 222)->nullable();
            // $table->string('account_phone', 222)->nullable();
            $table->string('account_mobile', 255)->nullable();
            $table->boolean('opp_account')->default(false);
            // $table->string('account_email_ids', 222)->nullable();
            // $table->text('account_address')->nullable();
            // $table->integer('account_state')->nullable();
            // $table->integer('account_city')->nullable();
            // $table->string('account_postal_code', 50)->nullable();
            // $table->string('account_gst_no', 222)->nullable();
            // $table->string('account_pan', 22)->nullable();
            // $table->string('account_aadhaar', 22)->nullable();
            // $table->string('account_contect_person_name', 222)->nullable();
            // $table->integer('account_group_id')->nullable();
            // $table->text('account_remarks')->nullable();
            // $table->double('opening_balance')->nullable();
            // $table->double('interest')->nullable();
            // $table->tinyInteger('credit_debit')->nullable();
            // $table->double('opening_balance_in_gold')->nullable();
            // $table->tinyInteger('gold_ob_credit_debit')->nullable();
            // $table->double('opening_balance_in_silver')->nullable();
            // $table->tinyInteger('silver_ob_credit_debit')->nullable();
            // $table->double('opening_balance_in_rupees')->nullable();
            // $table->tinyInteger('rupees_ob_credit_debit')->nullable();
            // $table->double('opening_balance_in_c_amount')->nullable();
            // $table->tinyInteger('c_amount_ob_credit_debit')->nullable();
            // // ->default(1)->comment('1 = Credit, 2 = Debit');
            // $table->integer('opening_balance_in_r_amount')->nullable();
            // $table->tinyInteger('r_amount_ob_credit_debit')->nullable();
            // // ->default(1)->comment('1 = Credit, 2 = Debit');
            // $table->string('bank_name', 255)->nullable();
            // $table->string('bank_account_no', 255)->nullable();
            // $table->string('ifsc_code', 255)->nullable();
            // $table->double('bank_interest')->nullable();
            // $table->double('gold_fine')->nullable();
            // $table->double('silver_fine')->nullable();
            // $table->double('amount')->nullable();
            // $table->double('c_amount')->nullable();
            // $table->double('r_amount')->nullable();
            // $table->double('credit_limit')->nullable();
            // $table->dateTime('balance_date')->nullable();
            // $table->tinyInteger('status')->nullable();
            // // ->default(1)->comment('1 = Approved, 2 = Not Approved');
            // $table->integer('user_id')->nullable();
            // $table->string('user_name', 255)->nullable();
            // $table->tinyInteger('is_supplier')->nullable();
            // // ->default(0)->comment('0 = Not Supplier, 1 = Supplier');
            // $table->string('password', 255)->nullable();
            // $table->double('min_price')->nullable();
            // $table->double('chhijjat_per_100_ad')->nullable();
            // $table->double('meena_charges')->nullable();
            // $table->double('price_per_pcs')->nullable();
            // $table->tinyInteger('is_active')->nullable();
            // ->default(1)->comment('0 = Not Active, 1 = Active');
            $table->timestamps();

            $table->primary('account_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account');
    }
};
