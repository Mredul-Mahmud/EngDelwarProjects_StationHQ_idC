<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\VehicleSticker;
use App\Models\Application;
use App\Models\VehicleType;
class VehicleInfo extends Model
{
    
    public function application()
    {
        return $this->belongsTo(Application::class);
    }
    public function vehicleStickers()
    {
        return $this->hasMany(VehicleSticker::class);
    }
      public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }

}
