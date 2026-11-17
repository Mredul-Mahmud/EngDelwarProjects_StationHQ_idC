<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Application;
use App\Models\VehicleInfo;
use App\Models\Sticker;
use App\Models\Invoice;
use App\Models\Applicant;
class VehicleSticker extends Model
{
    protected $table = 'vehicle_stickers';
    protected $fillable = ['sticker_value', 'reg_number', 'application_id', 'applicant_id', 'exp_date', 'issue_date', 'sticker_number', 'gate_no', 'sms_exp_warn', 'sms_exp_msg_isSent', 'sms_exp_expired', 'send_sms', 'document'];

    public function vehicleinfo()
    {
        return $this->belongsTo(VehicleInfo::class);
    }
    public function application()
    {
        return $this->belongsTo(Application::class);
    }  
      public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    } 
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}
