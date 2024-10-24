<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetData extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_id', 'location', 'door_no', 'total_cost','kms_reading','hour_reading',
        'cost_per_kms','cost_per_hour', 'category_name', 'category_amount'
    ];

    public function file()
    {
        return $this->belongsTo(FleetFile::class);
    }
}
