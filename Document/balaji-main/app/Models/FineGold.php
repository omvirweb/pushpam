<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FineGold extends Model
{
    use HasFactory;

    protected $table="fine_golds";
    protected $fillable = ['date', 'name', 'fine', 'notes', 'type'];
}
