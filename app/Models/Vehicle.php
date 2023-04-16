<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function vehicle_images(){
        return $this->hasMany(VehicleImage::class, 'vehicle_id','id');
    }
}
