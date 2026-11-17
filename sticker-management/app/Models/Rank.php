<?php

namespace App\Models;
use App\Models\ApplicantDetail;
use Illuminate\Database\Eloquent\Model;

class Rank extends Model
{

      public function applicantDetail(){
    	return $this->hasmany( ApplicantDetail::class);
    } 
}
