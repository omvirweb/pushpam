<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;
    protected $table = "transactions";

    // protected $primaryKey = 'account_id'; // Set the primary key column namej
    protected $primaryKey = 'id'; // Set the primary key column namej

    public $incrementing = true; // Ensure it auto-increments
    protected $keyType = 'int'; // Ensure the key type is integer
    protected $fillable = [
        'id',
        'date',
        'account_id',
        'amount',
        'opp_account_id',
        'fine',
        'item',
        'dhal',
        'touch',
        'fineCalc',
        'notes',
        'type',
        'weight',
        'rate',
        'method'
    ];
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id', 'account_id');
    }
    public function oppositeAccount()
    {
        return $this->belongsTo(Account::class, 'opp_account_id', 'account_id');
    }

    // public function item()
    // {
    //     return $this->belongsTo(Items::class, 'item', 'id');
    // }
    public function item()
    {
        return $this->belongsTo(Items::class,'item','id','item_name');
    }
    public function oppAccount()
    {
        return $this->belongsTo(Account::class, 'opp_account_id');
    }

    public function delivered()
    {
        return  $this->hasMany(Delivered::class);
    }
}
