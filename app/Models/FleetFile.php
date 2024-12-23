<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetFile extends Model
{
    use HasFactory;

    protected $fillable = ['type','file_name'];

    public function fleetData()
    {
        return $this->hasMany(FleetData::class);
    }
}
