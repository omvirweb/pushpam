<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    protected $table="account";
    // protected $primaryKey = 'account_id';
    // public $timestamps = true;
    protected $fillable = [
        'account_id ',
        'account_name',
        'account_phone',
        'account_mobile',
        'opp_account',
        'account_email_ids',
        'account_address',
        'account_state',
        'account_city',
        'account_postal_code',
        'account_gst_no',
        'account_pan',
        'account_aadhaar',
        'account_contect_person_name',
        'account_group_id',
        'account_remarks',
        'opening_balance',
        'interest',
        'credit_debit',
        'opening_balance_in_gold',
        'gold_ob_credit_debit',
        'opening_balance_in_silver',
        'silver_ob_credit_debit',
        'opening_balance_in_rupees',
        'rupees_ob_credit_debit',
        'opening_balance_in_c_amount',
        'c_amount_ob_credit_debit',
        'opening_balance_in_r_amount',
        'r_amount_ob_credit_debit',
        'bank_name',
        'bank_account_no',
        'ifsc_code',
        'bank_interest',
        'gold_fine',
        'silver_fine',
        'amount',
        'c_amount',
        'r_amount',
        'credit_limit',
        'balance_date',
        'status',
        'user_id',
        'user_name',
        'is_supplier',
        'password',
        'min_price',
        'chhijjat_per_100_ad',
        'meena_charges',
        'price_per_pcs',
        'is_active',
        // 'updated_at',
        // 'created_at',
    ];

    protected $primaryKey = 'account_id'; // Set the primary key column name
    public $incrementing = true; // Ensure it auto-increments
    protected $keyType = 'int'; // Ensure the key type is integer


    public function transactions()
    {
        return $this->hasMany(Transactions::class, 'account_id', 'account_id');
    }

}
