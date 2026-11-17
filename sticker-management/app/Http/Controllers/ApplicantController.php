<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Application;
use App\Models\StickerCategory;
use App\Models\SpouseParentsUnit;
use App\Models\Rank;
use App\Models\Invoice;

use Illuminate\Http\Request;
use App\Models\VehicleType;
use Illuminate\Support\Facades\DB;

class ApplicantController extends Controller
{
	 public function __construct()
    {
        $this->middleware('applicant');
    }

      public function applyForm(){

	$stickers=StickerCategory::all();
      	$vehicleTypes=VehicleType::orderBy('name','ASC')->get();
        $ranks = Rank::orderBy('name','ASC')->get();
        $units = SpouseParentsUnit::orderBy('name','ASC')->get();
	

        $file_name_nid = null;
            	return view('applyForm',compact('stickers','vehicleTypes','ranks','units','file_name_nid'));
    }   
    
    public function showAppliedForms(){
    	return view('layouts.applied-forms');
    }

    public function invReceipt($id)
    {
      try {
        $id = decrypt($id);
        $application = Application::select('id', 'app_number', 'sticker_category', 'sticker_for_year', 'applicant_id')->with([
          'applicant:id,name',
          'vehicleinfo:id,application_id,reg_number',
          'payment:id,application_id,type,credit,created_at',
        ])->findOrFail($id);

        $transactionId = DB::table('ssl_payments')
          ->where('application_id', $application->id)
          ->value('transaction_id');

        return view('layouts.invoice-receipt', compact(
          'application',
          'transactionId'
        ));
      } catch (\Throwable $th) {
        return $th->getMessage();
      }
    }

    
}
