<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sms extends Model
{
   	
	
	  public function getSmsTextAttribute($value)
    {
        if (request()->app_number != '') {
            $veh = VehicleInfo::where('app_number', request()->app_number)->first();

            if ($veh && $veh->reg_number) {
                return str_replace('{reg}', $veh->reg_number, $value);
            } else {
                // Return with fallback value if vehicle not found
                return str_replace('{reg}', 'N/A', $value);
            }
        }

        return $value;
    }
}
