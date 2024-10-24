<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chorsa extends Model
{
    use HasFactory;
    protected $table = 'chorsa'; // Explicitly set table name

    protected $fillable = [
        'date',
        'name',
        'weight',
        'rate',
        'total',
        'notes',
        'type',
        'account_id',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id', 'account_id');
    }
}
