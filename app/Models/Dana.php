<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dana extends Model
{
    use HasFactory;
    protected $table = "dana_leva_deva";
    protected $fillable = [
        'date',
        'name',
        'weight',
        'rate',
        'total',
        'notes',
        'type',
    ];
}
