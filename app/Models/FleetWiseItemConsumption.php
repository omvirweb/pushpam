<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FleetWiseItemConsumption extends Model
{
    use HasFactory;
    protected $fillable = [
        'amount','company_id','created_at','date','door_no','godown_name','hmr','item_name','kms','quantity','rate','sr_no','stock_category','stock_group','unit','unit_type','updated_at','vch_no',
    ];
}
