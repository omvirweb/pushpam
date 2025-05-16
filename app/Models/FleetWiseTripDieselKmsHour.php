<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FleetWiseTripDieselKmsHour extends Model
{
    use HasFactory;
    protected $fillable =[
        'created_at','diesel_per_quantity','door_no','hsd_per_hour','hsd_per_km','lead_in_kms','location','monthly_hours','monthly_kms','updated_at',
    ];
}
