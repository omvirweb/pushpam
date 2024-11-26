<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FleetJson extends Model
{
    // Explicitly set the table name if it doesn't follow Laravel's naming convention
    protected $table = 'fleet_json';

    // Define the fillable attributes to allow mass assignment
    protected $fillable = ['type','file_name', 'data'];

    // Specify that 'data' is cast as JSON for proper handling
    protected $casts = [
        'data' => 'array',
    ];
}
