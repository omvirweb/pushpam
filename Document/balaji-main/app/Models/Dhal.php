<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dhal extends Model
{
    use HasFactory;
    protected $table="dhal";
    protected $fillable = ['date', 'item_id','dhal', 'touch', 'fine', 'notes', 'type'];
    // protected $primaryKey = 'id'; // Set the primary key column namej

    protected $primaryKey = 'item_id'; // Set the primary key column namej

    public $incrementing = true; // Ensure it auto-increments
    protected $keyType = 'int'; // Ensure the key type is integer

    public function item()
    {
        return $this->belongsTo(Items::class, 'item_id', 'id');
    }
}
