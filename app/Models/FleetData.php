<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetData extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_id', 'location', 'door_no', 'total_cost','total_cost_type','kms_reading','hour_reading',
        'cost_per_kms','cost_per_hour', 'monthly_hour','monthly_kms','category_name', 'category_amount','category_amount_type',
    ];

    public function file()
    {
        return $this->belongsTo(FleetFile::class);
    }
}
