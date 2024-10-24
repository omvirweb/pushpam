<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    use HasFactory;
    protected $table = "items";

    protected $primaryKey = 'id'; // Set the primary key column namej
    protected $fillable = [
        'item_name'
    ];
    public $incrementing = true; // Ensure it auto-increments
    protected $keyType = 'int'; // Ensure the key type is integer


    public function transactions()
    {
        return $this->hasMany(Transactions::class, 'item_id');
        // return $this->belongsTo(Items::class, 'id', 'item_id');
    }
    public function dhal()
    {
        return $this->hasMany(Dhal::class, 'item_id');
    }
    protected $casts = [
        'id',
        'item_name',
    ];

}
