<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amount extends Model
{
    use HasFactory;
    protected $table="amount";
    protected $fillable = [
        'date',
        'account_id',
        'opp_account_id',
        'name',
        'amount',
        'notes',
        'type',
    ];
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id', 'account_id');
    }
}
