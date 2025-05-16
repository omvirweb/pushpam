<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FleetDetail extends Model
{
    use HasFactory;

    protected $fillable = [
            's_no','door_no','vehicle_status','cost_center','regd_state','invoice_no','name_of_owner','seaction','date_of_delivery','regd_date','regd_rto','regd_no','engine_no','chasis_no','road_tax_from','road_tax_to','fitness_from','fitness_to','permit_for_state','permit_from','permit_to','insured_by','insurance_policy_no','insurance_idv','insurance_from','insurance_to','puc_from','puc_to','name_of_financer','agreement_number','loan_amount','tenure','emi_start_date','emi_end_date','emi_amount','loading_capacity'
        ];
}