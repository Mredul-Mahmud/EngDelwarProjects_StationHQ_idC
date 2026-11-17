<?php

namespace App\Models;
use App\Models\Applicant;
use App\Models\Rank;
use App\Models\SpouseParentsUnit;
use Illuminate\Database\Eloquent\Model;

class ApplicantDetail extends Model
{

    public function getAddressAttribute($value)
    {
        if (empty($value)) {
            return '';
        }

        $decoded = json_decode($value, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {

            if (isset($decoded['present']) && is_array($decoded['present'])) {

                return implode(', ', array_filter([
                    !empty($decoded['present']['flat'])  ? $decoded['present']['flat']  : null,
                    !empty($decoded['present']['house']) ? $decoded['present']['house'] : null,
                    !empty($decoded['present']['road'])  ? $decoded['present']['road']  : null,
                    !empty($decoded['present']['block']) ? $decoded['present']['block'] : null,
                    !empty($decoded['present']['area'])  ? $decoded['present']['area']  : null,
                ])) . '.';
            }
        }

        return $value;
    }

    
    public function applicant(){
    	return $this->belongsTo(Applicant::class);
    }   
    public function rank(){
    	return $this->belongsTo(Rank::class);
    }  
    public function spouseOrParentRank(){ 
    	return $this->belongsTo('App\Rank' ,'spouseOrParent_Rank_id');
    }  
    public function spouseParentUnit(){
    	return $this->belongsTo('App\SpouseParentsUnit' ,'spouse_parents_units_id');
    }
}
