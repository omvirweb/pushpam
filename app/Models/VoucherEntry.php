<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherEntry extends Model
{
    protected $fillable = [
        'voucher_date', 'voucher_type', 'voucher_number', 'guid', 'master_id',
        'alter_id', 'voucher_key', 'entered_by', 'ref_no', 'ref_date',
        'cost_centre_name', 'narration', 'kms_reading', 'hours_reading',
        'diesel_ltr', 'no_of_trips', 'trip_factor', 'quantity',
        'entry_type', 'entry_ledger_name', 'entry_amount', 'entry_side',
    ];

    protected $casts = [
        'voucher_date' => 'date',
        'ref_date' => 'date',
        'diesel_ltr' => 'decimal:2',
        'trip_factor' => 'decimal:2',
        'quantity' => 'decimal:2',
        'entry_amount' => 'decimal:2',
    ];
}
