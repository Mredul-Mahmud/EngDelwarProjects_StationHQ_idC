<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\VehicleSticker;
use App\Models\StickerCategory;
class Sticker extends Model
{
   
    public function vehicleStickers()
    {
        return $this->hasmany(VehicleSticker::class);
    }


    public function stickerCategory()
    {
        return $this->belongsTo(StickerCategory::class);
    }

}
