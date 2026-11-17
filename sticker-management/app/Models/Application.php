<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Applicant;
use App\Models\DriverInfo;
use App\Models\VehicleInfo;
use App\Models\VehicleType;
use App\Models\StickerCategory;
use App\Models\VehicleSticker;
use App\Models\Invoice;
use App\Models\FollowUp;
use App\Models\Document;
use App\Models\TemporaryPass;
use App\Models\ApplicationNotify;

class Application extends Model
{

    public function driverinfo()
    {
        return $this->hasOne(DriverInfo::class);
    }
    public function vehicleowner()
    {
        return $this->hasOne(VehicleOwner::class);
    }
    public function vehicleSticker()
    {
        return $this->hasOne(VehicleSticker::class);
    }
    public function vehicleinfo()
    {
        return $this->hasOne(VehicleInfo::class);
    }
    public function document()
    {
        return $this->hasOne(Document::class);
    }

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }
    public function stickerCategory()
    {
        return $this->belongsTo('App\Models\StickerCategory', 'sticker_category', 'value');
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
    public function followups()
    {
        return $this->hasMany(FollowUp::class);
    }
    public function temporaryPasses()
    {
        return $this->hasMany(TemporaryPass::class);
    }
    public function applicationNotify()
    {
        return $this->hasOne(ApplicationNotify::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'application_id');
    }


    public function payment_s()
    {
        return $this->hasOne(Payment::class, 'application_id');
    }

    public function sslPayment()
    {
        return $this->hasOne(SslPayment::class, 'application_id');
    }
}
