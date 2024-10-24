<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivered extends Model
{
    use HasFactory;

    protected $table="delivered";
    public $timestamps = false; 
}
