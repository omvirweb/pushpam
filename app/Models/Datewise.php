<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Datewise extends Model
{
    use HasFactory;

    protected $table="datewise";

    protected $fillable = [
        'for_date',
        'close_rate'
    ];
}
