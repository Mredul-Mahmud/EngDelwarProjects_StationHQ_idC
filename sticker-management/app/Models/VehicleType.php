<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\VehicleInfo;
use App\Models\Invoice;
use App\Models\Application;
class VehicleType extends Model
{
   
      public function vehicleinfo()
    {
        return $this->hasOne(VehicleInfo::class);
    }  
     public function invoices()
    {
        return $this->hasMany(Invoice::class);
    } 
     public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
