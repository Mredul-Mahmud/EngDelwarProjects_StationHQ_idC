<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ApplicantResetPasswordNotification;
use App\Models\ApplicantDetail;
use App\Models\Application;
use App\Models\StickerCategory;
use App\Models\VehicleSticker;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Authenticatable
{
	 use Notifiable;
    protected $guard = 'applicant';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        
        'name', 'user_name', 'email', 'phone', 'password', 'role',
    ];

 	protected $hidden = [
 	    
        'password', 'remember_token',
    ];

	public function sendPasswordResetNotification($token)
    {
        $this->notify(new ApplicantResetPasswordNotification($token));

    }

   public function applicantDetail(){
    	return $this->hasOne( ApplicantDetail::class);
    }
    public function applications(){
        return $this->hasMany( Application::class);
    } 
     public function vehicleStickers(){
    	return $this->hasMany( VehicleSticker::class);
    }

}
