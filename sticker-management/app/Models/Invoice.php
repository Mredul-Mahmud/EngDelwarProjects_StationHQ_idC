<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Application;
use App\Models\VehicleType;
use App\Models\StickerCategory;
use App\Models\VehicleSticker;
use App\Models\TemporaryPass;
class Invoice extends Model
{
    public function application()
    {
        return $this->belongsTo(Application::class);
    } 

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }

    public function stickerCategory()
    {
        return $this->belongsTo(StickerCategory::class);
    } 

    public function vehicleSticker()
    {
        return $this->belongsTo(VehicleSticker::class);
    } 
    
    public function temporaryPass()
    {
        return $this->belongsTo(TemporaryPass::class);
    }

}
