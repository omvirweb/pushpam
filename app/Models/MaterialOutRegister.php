<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialOutRegister extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'party_name',
        'voucher_type',
        'voucher_group',
        'godown',
        'ref_no',
        'voucher_no',
        'amount',
        'kms',
        'kms_life',
        'hmr',
        'hmr_life',
        'transaction_type',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'float',
    ];
}
