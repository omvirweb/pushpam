<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetFile extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'file_name', 'company_id'];

    public function fleetData()
    {
        return $this->hasMany(FleetData::class);
    }
    public function type()
    {
        return $this->belongsTo(Type::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
