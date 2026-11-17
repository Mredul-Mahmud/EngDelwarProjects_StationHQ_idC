<?php

namespace App\Http\Controllers;

use App\Models\ApplicantDetail;
use App\Http\Requests\FormValidate;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\VehicleInfo;
use App\Models\VehicleOwner;
use App\Models\FollowUp;
use Auth;
use App\Notifications\ApplicationNotification;
use App\Models\User;
use Carbon\Carbon;

class VehicleInfoController extends Controller
{

	public function storeVehicleDetails(FormValidate $request)
	{
		// dd($request->all());
		$this->validate($request, [
			// Common fields
			'vehicle_reg_photo' => 'nullable|mimes:jpg,jpeg,png',
			'vehicle_owner' => 'required|in:self,spouse,civil_company,govt_company,other',
			

			// Defence form - Owner documents
			'spouse_passport_copy' => 'nullable|mimes:jpg,jpeg,png,pdf',
			'relation_certificate' => 'nullable|mimes:jpg,jpeg,png,pdf',
			'civil_company_certificate' => 'nullable|mimes:jpg,jpeg,png,pdf',
			'govt_institution_certificate' => 'nullable|mimes:jpg,jpeg,png,pdf',

			// Non-Defence form - Application category documents
			'application_category' => 'nullable|string',
			'service_retd_doc' => 'nullable|mimes:jpg,jpeg,png,pdf',
			'house_owner_doc' => 'nullable|mimes:jpg,jpeg,png,pdf',
			'civil_institution_doc' => 'nullable|mimes:jpg,jpeg,png,pdf',
			'proyash_school_doc' => 'nullable|mimes:jpg,jpeg,png,pdf',
			'teacher_official_doc' => 'nullable|mimes:jpg,jpeg,png,pdf',
			'bus_laguna_doc' => 'nullable|mimes:jpg,jpeg,png,pdf',
			'motorcycle_doc' => 'nullable|mimes:jpg,jpeg,png,pdf',
			'transit_kochukhet_doc' => 'nullable|mimes:jpg,jpeg,png,pdf',
			'transit_matikata_doc' => 'nullable|mimes:jpg,jpeg,png,pdf',
			'vehicle_reg_back_photo' => 'nullable|mimes:jpg,jpeg,png',
		]);

		try {
			// Get old application data if exists
			$old_vehicle_info = null;
			if ($request->old_application_id) {
				$old_vehicle_info = VehicleInfo::where('application_id', $request->old_application_id)->first();
			}

			// Check for existing stickers
			$exist_stickers = VehicleInfo::where('reg_number', $request->vehicle_reg_no)->get();
			$pending_count = 0;
			$approve_count = 0;

			if (count($exist_stickers) > 0) {
				foreach ($exist_stickers as $exist_sticker) {
					if ($exist_sticker->application->app_status == 'pending') {
						$pending_count++;
					}
					if ($exist_sticker->application->app_status == 'approved') {
						$approve_count++;
					}
				}
			}

			// If vehicle already has pending/approved application
			if ($pending_count > 0 || $approve_count > 0) {
				$data = "You have already applied for this vehicle.";
				$flag = "already-applied";
				$success = false;
				return array($data, $flag, 'success' => $success);
			}

			$application_id = auth()->guard('applicant')->user()->applicantDetail->app_id;
			$app_number = auth()->guard('applicant')->user()->applicantDetail->app_number;

			// VEHICLE INFO
			$existentVehicleinfo = VehicleInfo::where('application_id', $application_id)->first();
			$existentOwnerinfo = VehicleOwner::where('application_id', $application_id)->first();

			if (!empty($existentVehicleinfo) && !empty($existentOwnerinfo)) {
				// Update existing vehicle info
				$VehicleInfo = VehicleInfo::findOrFail($existentVehicleinfo->id);
				$VehicleInfo->reg_number = $request->vehicle_reg_no;
				$VehicleInfo->vehicle_type_id = $request->vehicle_type;

				// Handle vehicle registration photo (Front)
				if ($request->hasFile('vehicle_reg_photo')) {
					if ($VehicleInfo->reg_cert_photo) {
						\File::delete(public_path($VehicleInfo->reg_cert_photo));
					}
					$vehicle_reg_filename = time() . '_reg.' . $request->vehicle_reg_photo->getClientOriginalExtension();
					$vehicle_reg_name = '/images/vehicle_reg/' . $vehicle_reg_filename;
					$request->vehicle_reg_photo->move(public_path('images/vehicle_reg'), $vehicle_reg_name);
					$VehicleInfo->reg_cert_photo = $vehicle_reg_name;
				} elseif ($old_vehicle_info) {
					$VehicleInfo->reg_cert_photo = $old_vehicle_info->reg_cert_photo;
				}

				// Handle vehicle registration photo (Back)
				if ($request->hasFile('vehicle_reg_back_photo')) {
					if ($VehicleInfo->reg_cert_back_photo) {
						\File::delete(public_path($VehicleInfo->reg_cert_back_photo));
					}
					$vehicle_reg_filename = time() . '_reg_back.' . $request->vehicle_reg_back_photo->getClientOriginalExtension();
					$vehicle_reg_name_back = '/images/vehicle_reg/' . $vehicle_reg_filename;
					$request->vehicle_reg_back_photo->move(public_path('images/vehicle_reg'), $vehicle_reg_name_back);
					$VehicleInfo->reg_cert_back_photo = $vehicle_reg_name_back;
				} elseif ($old_vehicle_info) {
					$VehicleInfo->reg_cert_back_photo = $old_vehicle_info->reg_cert_back_photo;
				}

				$VehicleInfo->update();

				// Update vehicle owner
				$VehicleOwner = VehicleOwner::findOrFail($existentOwnerinfo->id);
				$VehicleOwner->owner_name = $request->owner_name ? $request->owner_name : auth()->guard('applicant')->user()->name;
				$VehicleOwner->vehicle_owner_type = $request->vehicle_owner;
				$VehicleOwner->relation = $request->relation ?? null;

				 // Handle renew request for owner NID

				// Handle owner-specific documents (Defence form)
				$this->handleOwnerDocuments($request, $VehicleOwner, $old_vehicle_info);

				$VehicleOwner->update();

				// Update application
				$app = Application::findOrFail($VehicleInfo->application_id);
				$app->vehicle_type_id = $request->vehicle_type;

				// Handle application category (Non-Defence form)
				if ($request->has('application_category')) {
					$app->application_category = $request->application_category;

					// Handle category-specific documents
					$this->handleCategoryDocuments($request, $app);
				}

				$app->update();
			} else {
				// Create new vehicle info
				$VehicleInfo = new VehicleInfo;
				$VehicleInfo->application_id = $application_id;
				$VehicleInfo->app_number = $app_number;
				$VehicleInfo->reg_number = $request->vehicle_reg_no;
				$VehicleInfo->vehicle_type_id = $request->vehicle_type;

				// Handle vehicle registration photo
				if ($request->hasFile('vehicle_reg_photo')) {
					$vehicle_reg_filename = time() . '_reg.' . $request->vehicle_reg_photo->getClientOriginalExtension();
					$vehicle_reg_name = '/images/vehicle_reg/' . $vehicle_reg_filename;
					$request->vehicle_reg_photo->move(public_path('images/vehicle_reg'), $vehicle_reg_name);
					$VehicleInfo->reg_cert_photo = $vehicle_reg_name;
				} elseif ($old_vehicle_info) {
					$VehicleInfo->reg_cert_photo = $old_vehicle_info->reg_cert_photo;
				}

				// Handle vehicle registration photo (Back)
				if ($request->hasFile('vehicle_reg_back_photo')) {
					$vehicle_reg_filename = time() . '_reg_back.' . $request->vehicle_reg_back_photo->getClientOriginalExtension();
					$vehicle_reg_name_back = '/images/vehicle_reg/' . $vehicle_reg_filename;
					$request->vehicle_reg_back_photo->move(public_path('images/vehicle_reg'), $vehicle_reg_name_back);
					$VehicleInfo->reg_cert_back_photo = $vehicle_reg_name_back;
				} elseif ($old_vehicle_info) {
					$VehicleInfo->reg_cert_back_photo = $old_vehicle_info->reg_cert_back_photo;
				}

				// Handle renew request
				if (!empty($request->renew_request) && $request->renew_request == 'yes' && !$request->hasFile('vehicle_reg_photo')) {
					$old_app = Application::findOrFail($request->app_id);
					$VehicleInfo->reg_cert_photo = $old_app->vehicleinfo->reg_cert_photo;
				}

				$VehicleInfo->save();

				// Create new vehicle owner
				$VehicleOwner = new VehicleOwner;
				$VehicleOwner->application_id = $application_id;
				$VehicleOwner->app_number = $app_number;
				$VehicleOwner->owner_name = $request->owner_name ?? auth()->guard('applicant')->user()->name;
				$VehicleOwner->vehicle_owner_type = $request->vehicle_owner;

				$VehicleOwner->relation = $request->relation ?? null;

				// Handle owner-specific documents (Defence form)
				$this->handleOwnerDocuments($request, $VehicleOwner, $old_vehicle_info);

				// Handle renew request for owner NID
				if (!empty($request->renew_request) && $request->renew_request == 'yes' && !$request->hasFile('owner_nid_photo')) {
					$old_app = Application::findOrFail($request->app_id);
					$VehicleOwner->nid_photo = $old_app->vehicleowner->nid_photo;
				}

				$VehicleOwner->save();

				// Update application
				$app = Application::findOrFail($VehicleInfo->application_id);
				$app->vehicle_type_id = $request->vehicle_type;

				// Handle application category (Non-Defence form)
				if ($request->has('application_category')) {
					$app->application_category = $request->application_category;

					// Handle category-specific documents
					$this->handleCategoryDocuments($request, $app);
				}

				$app->update();
			}

			// UPDATE APPLICATION STATUS
			$appStatusUp = Application::findOrFail($application_id);
			$appStatusUp->app_status = "pending";
			$appStatusUp->app_date = Carbon::now();
			$appStatusUp->update();

			// Clear applicant detail app_number and app_id
			$applicantDetailUp = ApplicantDetail::findOrFail(auth()->guard('applicant')->user()->applicantDetail->id);
			$applicantDetailUp->app_number = null;
			$applicantDetailUp->app_id = null;
			$applicantDetailUp->update();

			// Create follow-up record
			$follow_up = new FollowUp;
			$follow_up->application_id = $appStatusUp->id;
			$follow_up->app_status = $appStatusUp->app_status;
			$follow_up->status = "Application requested";
			$follow_up->updater_role = 'customer';
			$follow_up->updated_by = auth()->guard('applicant')->user()->name;
			$follow_up->created_date = Carbon::now();
			$follow_up->save();

			// Handle renew application
			if ($request->renew_app == "yes") {
				$old_app = Application::findOrFail($request->app_id);
				$old_app->renew = 'renew-applied. New appId:' . $appStatusUp->id;
				$old_app->renew_app_id = $appStatusUp->id;
				$old_app->save();
				$data = "Application Submitted for Renew Successfully!!";
				$renew_flag = "success renew";
			} else {
				$data = "New Application Submitted Successfully!!";
				$renew_flag = "success renew";
			}

			return array($data, $renew_flag, $appStatusUp->id);
		} catch (\Exception $e) {
			\Log::error('Vehicle Details Store Error: ' . $e->getMessage());
			$data = 'Something went wrong: ' . $e->getMessage();
			$flag = "error";
			return array($data, $flag);
		}
	}

	/**
	 * Handle owner-specific documents (Defence form)
	 */
	private function handleOwnerDocuments($request, $VehicleOwner, $old_vehicle_info)
	{
		// Spouse - Passport/NID Copy
		if ($request->vehicle_owner === 'spouse' && $request->hasFile('spouse_passport_copy')) {
			if ($VehicleOwner->spouse_passport_photo) {
				\File::delete(public_path($VehicleOwner->spouse_passport_photo));
			}
			$filename = time() . '_spouse_passport.' . $request->spouse_passport_copy->getClientOriginalExtension();
			$path = '/images/spouse_passport/' . $filename;
			$request->spouse_passport_copy->move(public_path('images/spouse_passport'), $path);
			$VehicleOwner->owner_type_attachment = $path;
		} elseif ($request->vehicle_owner === 'spouse' && $old_vehicle_info && isset($old_vehicle_info->vehicleowner)) {
			$VehicleOwner->owner_type_attachment = $old_vehicle_info->vehicleowner->owner_type_attachment;
		}

		// relation certificate
		if ($request->vehicle_owner === 'other' && $request->hasFile('relation_certificate')) {
			if ($VehicleOwner->owner_type_attachment) {
				\File::delete(public_path($VehicleOwner->owner_type_attachment));
			}
			$filename = time() . '_relation_cert.' . $request->relation_certificate->getClientOriginalExtension();
			$path = '/images/relation_certificate/' . $filename;
			$request->relation_certificate->move(public_path('images/relation_certificate'), $path);
			$VehicleOwner->owner_type_attachment = $path;
		} elseif ($request->vehicle_owner === 'other' && $old_vehicle_info && isset($old_vehicle_info->vehicleowner)) {
			$VehicleOwner->owner_type_attachment = $old_vehicle_info->vehicleowner->owner_type_attachment;
		}

		// Civil Company - Certificate
		if ($request->vehicle_owner === 'civil_company' && $request->hasFile('civil_company_certificate')) {
			if ($VehicleOwner->civil_company_certificate) {
				\File::delete(public_path($VehicleOwner->civil_company_certificate));
			}
			$filename = time() . '_civil_cert.' . $request->civil_company_certificate->getClientOriginalExtension();
			$path = '/images/civil_company/' . $filename;
			$request->civil_company_certificate->move(public_path('images/civil_company'), $path);
			$VehicleOwner->owner_type_attachment = $path;
		} elseif ($request->vehicle_owner === 'civil_company' && $old_vehicle_info && isset($old_vehicle_info->vehicleowner)) {
			$VehicleOwner->owner_type_attachment = $old_vehicle_info->vehicleowner->owner_type_attachment;
		}

		// Govt Institution - Certificate
		if ($request->vehicle_owner === 'govt_company' && $request->hasFile('govt_institution_certificate')) {
			if ($VehicleOwner->govt_institution_certificate) {
				\File::delete(public_path($VehicleOwner->govt_institution_certificate));
			}
			$filename = time() . '_govt_cert.' . $request->govt_institution_certificate->getClientOriginalExtension();
			$path = '/images/govt_institution/' . $filename;
			$request->govt_institution_certificate->move(public_path('images/govt_institution'), $path);
			$VehicleOwner->owner_type_attachment = $path;
		} elseif ($request->vehicle_owner === 'govt_company' && $old_vehicle_info && isset($old_vehicle_info->vehicleowner)) {
			$VehicleOwner->owner_type_attachment = $old_vehicle_info->vehicleowner->owner_type_attachment;
		}
	}

	/**
	 * Handle application category documents (Non-Defence form)
	 */
	private function handleCategoryDocuments($request, $app)
	{
		$categoryDocuments = [
			'service_retd' => 'service_retd_doc',
			'house_owner' => 'house_owner_doc',
			'civil_institution' => 'civil_institution_doc',
			'proyash_school' => 'proyash_school_doc',
			'teacher_official' => 'teacher_official_doc',
			'bus_laguna' => 'bus_laguna_doc',
			'motorcycle' => 'motorcycle_doc',
			'transit_kochukhet' => 'transit_kochukhet_doc',
			'transit_matikata' => 'transit_matikata_doc',
		];

		foreach ($categoryDocuments as $category => $docField) {
			if ($request->application_category === $category && $request->hasFile($docField)) {
				// Delete old file if exists
				if ($app->app_category_attachment) {
					\File::delete(public_path($app->app_category_attachment));
				}

				$filename = time() . '_' . $category . '.' . $request->$docField->getClientOriginalExtension();
				$path = '/images/category_documents/' . $filename;
				$request->$docField->move(public_path('images/category_documents'), $path);
				$app->app_category_attachment = $path;
				break;
			}
		}
	}
	// public function storeVehicleDetails(FormValidate $request){
	// 	$this->validate($request, [
	// 		'vehicle_reg_photo' => 'nullable|mimes:jpg,jpeg,png',
	// 		'insurance_cert_photo' => 'nullable|mimes:jpg,jpeg,png',
	// 		'tax_token_photo' => 'nullable|mimes:jpg,jpeg,png',
	// 		'fitness_cert_photo' => 'nullable|mimes:jpg,jpeg,png',
	// 		'road_permit_photo' => 'nullable|mimes:jpg,jpeg,png',
	// 		'entry_pass_photo' => 'nullable|mimes:jpg,jpeg,png',
	// 		'jt_licence_photo' => 'nullable|mimes:jpg,jpeg,png',
	// 	]);

	//     if ($request->old_application_id){
	//         $old_vcehicle_info = VehicleInfo::where('application_id',$request->old_application_id)->first();
	//     }
	// 	$exist_stickers = VehicleInfo::where('reg_number',$request->vehicle_reg_no)->get();
	// 	$pending_count=0;
	// 	$approve_count=0;
	// 	if(count($exist_stickers)>0){
	// 		foreach($exist_stickers as $exist_sticker){
	// 			if($exist_sticker->application->app_status =='pending' ){
	// 				$pending_count++;
	// 			}
	// 			if($exist_sticker->application->app_status =='approved' ){
	// 				$approve_count++;
	// 			}
	// 		}
	// 	}
	// 	// dd($request->all());
	// 	if($pending_count==0 && $approve_count==0){
	// 		$existentVehicleinfo = VehicleInfo::where('application_id',auth()->guard('applicant')->user()->applicantDetail->app_id)->first();
	// 		$existentOwnerinfo = VehicleOwner::where('application_id',auth()->guard('applicant')->user()->applicantDetail->app_id)->first();
	// 		if(!empty($existentVehicleinfo) && !empty($existentOwnerinfo)){
	// 			$VehicleInfo =VehicleInfo::findOrFail($existentVehicleinfo->id);
	// 			$VehicleInfo->insurance_validity=$request->insurance_validity;
	// 			$VehicleInfo->fitness_validity=$request->fitnness_validity;
	// 			$VehicleInfo->tax_token_validity=$request->tax_paid_upto;
	// 			$VehicleInfo->necessity_to_use=$request->necessity_to_use;
	// 			$VehicleInfo->reg_number=$request->vehicle_reg_no;
	// 			$VehicleInfo->vehicle_type_id=$request->vehicle_type;
	// 			$VehicleInfo->loan_taken=$request->loan_taken;
	// 			if ($request->is_not_transparent=='0'){
	// 				$VehicleInfo->glass_type=$request->glass_type;
	// 			}else{
	// 				$VehicleInfo->glass_type='transparent';
	// 			}
	// 			$VehicleInfo->in_out_gate=$request->in_out_gate;
	// 			$VehicleInfo->in_out_time=$request->in_out_time;

	// 			if($request->hasFile('vehicle_reg_photo')){
	// 				\File::delete('images/vehicle_reg/' . basename($VehicleInfo->reg_cert_photo));
	// 				$vehicle_reg_filename = time() . '.' . $request->vehicle_reg_photo->getClientOriginalExtension();
	// 				$vehicle_reg_name = '/images/vehicle_reg/' . $vehicle_reg_filename;
	// 				$request->vehicle_reg_photo->move(public_path('images/vehicle_reg'), $vehicle_reg_name);
	// 				$VehicleInfo->reg_cert_photo = $vehicle_reg_name;
	// 			}else{

	// 				$VehicleInfo->reg_cert_photo = $old_vcehicle_info->reg_cert_photo??'';
	// 			}
	// 			if($request->hasFile('insurance_cert_photo')) {
	// 				\File::delete('images/vehicle_insurance/' . basename($VehicleInfo->insurance_cert_photo));

	// 				$vehicle_insurance_filename = time() . '.' . $request->insurance_cert_photo->getClientOriginalExtension();
	// 				$vehicle_insurance_name = '/images/vehicle_insurance/' . $vehicle_insurance_filename;
	// 				$request->insurance_cert_photo->move(public_path('images/vehicle_insurance'), $vehicle_insurance_name);
	// 				$VehicleInfo->insurance_cert_photo = $vehicle_insurance_name;
	// 			}else{
	// 				$VehicleInfo->insurance_cert_photo = $old_vcehicle_info->insurance_cert_photo??'';
	// 			}
	// 			if($request->hasFile('tax_token_photo')) {
	// 				\File::delete('images/vehicle_tax_token/' . basename($VehicleInfo->tax_token_photo));

	// 				$vehicle_tax_token_filename = time() . '.' . $request->tax_token_photo->getClientOriginalExtension();
	// 				$vehicle_tax_token_name = '/images/vehicle_tax_token/' . $vehicle_tax_token_filename;
	// 				$request->tax_token_photo->move(public_path('images/vehicle_tax_token'), $vehicle_tax_token_name);
	// 				$VehicleInfo->tax_token_photo = $vehicle_tax_token_name;
	// 			}else{
	// 				$VehicleInfo->tax_token_photo = $old_vcehicle_info->tax_token_photo??'';
	// 			}
	// 			if($request->hasFile('fitness_cert_photo')){
	// 				\File::delete('images/vehicle_fitness/' . basename($VehicleInfo->fitness_cert_photo));

	// 				$vehicle_fitness_filename = time() . '.' . $request->fitness_cert_photo->getClientOriginalExtension();
	// 				$vehicle_fitness_name = '/images/vehicle_fitness/'.$vehicle_fitness_filename;
	// 				$request->fitness_cert_photo->move(public_path('images/vehicle_fitness'), $vehicle_fitness_name);
	// 				$VehicleInfo->fitness_cert_photo = $vehicle_fitness_name;
	// 			}else{
	// 				$VehicleInfo->fitness_cert_photo = $old_vcehicle_info->fitness_cert_photo??'';
	// 			}
	// 			$VehicleInfo->update();
	// 			$VehicleOwner =VehicleOwner::findOrFail($existentOwnerinfo->id);
	// 			$com_address=array(
	// 				"flat" => $request->c_flat,
	// 				"house" => $request->c_house,
	// 				"road" => $request->c_road,
	// 				"block" => $request->c_block,
	// 				"area" => $request->c_area,
	// 			);
	// 			if($request->owner_is_company == "1"){
	// 				$VehicleOwner->company_name= $request->company_name;
	// 				$VehicleOwner->company_address=json_encode($com_address);
	// 			}else{
	// 				$VehicleOwner->company_name= '';
	// 				$VehicleOwner->company_address=null;
	// 			}
	// 			$VehicleOwner->owner_name= $request->owner_name;
	// 			$VehicleOwner->update();
	// 			$app = Application::findOrFail($VehicleInfo->application_id);
	// 			$app->vehicle_type_id = $request->vehicle_type;
	// 			if ($request->is_not_transparent=='0'){
	// 				$app->glass_type=$request->glass_type;
	// 			}else{
	// 				$app->glass_type='transparent';
	// 			}
	// 			$app->update();
	// 		}else{
	// 			$VehicleInfo = new VehicleInfo;
	// 			$VehicleInfo->application_id=auth()->guard('applicant')->user()->applicantDetail->app_id;
	// 			$VehicleInfo->app_number=auth()->guard('applicant')->user()->applicantDetail->app_number;
	// 			$VehicleInfo->insurance_validity=$request->insurance_validity;
	// 			$VehicleInfo->fitness_validity=$request->fitnness_validity;
	// 			$VehicleInfo->tax_token_validity=$request->tax_paid_upto;
	// 			$VehicleInfo->necessity_to_use=$request->necessity_to_use;
	// 			$VehicleInfo->reg_number=$request->vehicle_reg_no;
	// 			$VehicleInfo->vehicle_type_id=$request->vehicle_type;
	// 			$VehicleInfo->loan_taken=$request->loan_taken;
	// 			if ($request->is_not_transparent=='0'){
	// 				$VehicleInfo->glass_type=$request->glass_type;
	// 			}else{
	// 				$VehicleInfo->glass_type='transparent';
	// 			}
	// 			$VehicleInfo->in_out_gate=$request->in_out_gate;
	// 			$VehicleInfo->in_out_time=$request->in_out_time;
	// 			if($request->hasFile('vehicle_reg_photo')){
	// 				$vehicle_reg_filename = time() . '.' . $request->vehicle_reg_photo->getClientOriginalExtension();
	// 				$vehicle_reg_name ='/images/vehicle_reg/'.$vehicle_reg_filename;
	// 				$request->vehicle_reg_photo->move(public_path('images/vehicle_reg'), $vehicle_reg_name);
	// 				$VehicleInfo->reg_cert_photo = $vehicle_reg_name;
	// 			}else{

	//                 $VehicleInfo->reg_cert_photo = $old_vcehicle_info->reg_cert_photo ?? '';
	//             }
	// 			if(!empty($request->renew_request) && $request->renew_request=='yes' && empty($request->hasFile('vehicle_reg_photo'))){
	// 				$old_app = Application::findOrFail($request->app_id);
	// 				$VehicleInfo->reg_cert_photo=$old_app->vehicleinfo->reg_cert_photo;
	// 			}
	// 			if($request->hasFile('tax_token_photo')){
	// 				$vehicle_tax_token_filename = time() . '.' . $request->tax_token_photo->getClientOriginalExtension();
	// 				$vehicle_tax_token_name ='/images/vehicle_tax_token/'.$vehicle_tax_token_filename;
	// 				$request->tax_token_photo->move(public_path('images/vehicle_tax_token'), $vehicle_tax_token_name);
	// 				$VehicleInfo->tax_token_photo = $vehicle_tax_token_name;
	// 			}else{

	//                 $VehicleInfo->tax_token_photo = $old_vcehicle_info->tax_token_photo ?? '';
	//             }

	// 			if($request->hasFile('insurance_cert_photo')){
	// 				$vehicle_insurance_filename = time() . '.' . $request->insurance_cert_photo->getClientOriginalExtension();
	// 				$vehicle_insurance_name ='/images/vehicle_insurance/'.$vehicle_insurance_filename;
	// 				$request->insurance_cert_photo->move(public_path('images/vehicle_insurance'), $vehicle_insurance_name);
	// 				$VehicleInfo->insurance_cert_photo = $vehicle_insurance_name;
	// 			}else{

	//                 $VehicleInfo->insurance_cert_photo = $old_vcehicle_info->insurance_cert_photo ?? '';
	//             }
	// 			if($request->hasFile('fitness_cert_photo')){
	// 				$vehicle_fitness_filename = time() . '.' . $request->fitness_cert_photo->getClientOriginalExtension();
	// 				$vehicle_fitness_name = '/images/vehicle_fitness/'.$vehicle_fitness_filename;
	// 				$request->fitness_cert_photo->move(public_path('images/vehicle_fitness'), $vehicle_fitness_name);
	// 				$VehicleInfo->fitness_cert_photo = $vehicle_fitness_name;
	// 			}else{

	//                 $VehicleInfo->fitness_cert_photo = $old_vcehicle_info->fitness_cert_photo ?? '';
	//             }
	// 			if($request->hasFile('road_permit_photo')){
	// 				$road_permit_filename = time() . '.' . $request->road_permit_photo->getClientOriginalExtension();
	// 				$road_permit_name = '/images/vehicle_road_permit/'.$road_permit_filename;
	// 				$request->road_permit_photo->move(public_path('images/vehicle_road_permit'), $road_permit_name);
	// 				$VehicleInfo->road_permit_photo = $road_permit_name;
	// 			}else{

	//                 $VehicleInfo->road_permit_photo = $old_vcehicle_info->road_permit_photo ?? '';
	//             }
	// 			if($request->hasFile('entry_pass_photo')){
	// 				$vehicle_port_entry_pass_filename = time() . '.' . $request->entry_pass_photo->getClientOriginalExtension();
	// 				$vehicle_port_entry_pass_name = '/images/vehicle_port_pass/'.$vehicle_port_entry_pass_filename;
	// 				$request->entry_pass_photo->move(public_path('images/vehicle_port_pass'), $vehicle_port_entry_pass_name);
	// 				$VehicleInfo->port_entry_pass_photo = $vehicle_port_entry_pass_name;
	// 			}else{

	//                 $VehicleInfo->port_entry_pass_photo = $old_vcehicle_info->port_entry_pass_photo ?? '';
	//             }
	// 			if($request->hasFile('jt_licence_photo')){
	// 				$vehicle_jt_licence_copy_filename = time() . '.' . $request->jt_licence_photo->getClientOriginalExtension();
	// 				$vehicle_jt_licence_copy_name = '/images/vehicle_jt_licence/'.$vehicle_jt_licence_copy_filename;
	// 				$request->jt_licence_photo->move(public_path('images/vehicle_jt_licence'), $vehicle_jt_licence_copy_name);
	// 				$VehicleInfo->jt_licence_photo = $vehicle_jt_licence_copy_name;
	// 			}else{

	//                 $VehicleInfo->jt_licence_photo = $old_vcehicle_info->jt_licence_photo ?? '';
	//             }
	// 			$VehicleInfo->save();
	// 			$VehicleOwner = new VehicleOwner;
	// 			$VehicleOwner->application_id=auth()->guard('applicant')->user()->applicantDetail->app_id;
	// 			$VehicleOwner->app_number=auth()->guard('applicant')->user()->applicantDetail->app_number;
	// 			$com_address=array(
	// 				"flat" => $request->c_flat,
	// 				"house" => $request->c_house,
	// 				"road" => $request->c_road,
	// 				"block" => $request->c_block,
	// 				"area" => $request->c_area,
	// 			);
	// 			if($request->owner_is_company == "1"){
	// 				$VehicleOwner->company_name= $request->company_name;
	// 				$VehicleOwner->company_address=json_encode($com_address);
	// 			}
	// 			$VehicleOwner->owner_name= $request->owner_name;
	// 			if(!empty($request->renew_request) && $request->renew_request=='yes' && empty($request->hasFile('owner_nid_photo'))){
	// 				$old_app = Application::findOrFail($request->app_id);
	// 				$VehicleOwner->nid_photo=$old_app->vehicleowner->nid_photo;
	// 			}
	// 			$VehicleOwner->save();
	// 			$app = Application::findOrFail($VehicleInfo->application_id);
	// 			$app->vehicle_type_id = $request->vehicle_type;
	// 			if ($request->is_not_transparent=='0'){
	// 				$app->glass_type=$request->glass_type;
	// 			}else{
	// 				$app->glass_type='transparent';
	// 			}
	// 			$app->update();
	// 		}
	// 	}else{
	// 		$data ="You have already applied for this vehicle.";
	// 		$flag ="already-applied";
	// 		return (array($data, $flag));
	// 	}
	// }
	// public function vehicleInfoUpdate($app_id, FormValidate $request)
	// {


	// 	$this->validate($request, [
	// 		'vehicle_reg_photo' => 'nullable|mimes:jpg,jpeg,png',
	// 		'insurance_cert_photo' => 'nullable|mimes:jpg,jpeg,png',
	// 		'tax_token_photo' => 'nullable|mimes:jpg,jpeg,png',
	// 		'fitness_cert_photo' => 'nullable|mimes:jpg,jpeg,png',
	// 		'road_permit_photo' => 'nullable|mimes:jpg,jpeg,png',
	// 		'entry_pass_photo' => 'nullable|mimes:jpg,jpeg,png',
	// 		'jt_licence_photo' => 'nullable|mimes:jpg,jpeg,png',
	// 	]);

	// 	$application = Application::findOrFail($app_id);

	// 	if (Auth::guard('applicant')->user() && $application->retake == 1) {
	// 		$application->retake = 2;
	// 	}

	// 	$application->save();
	// 	$VehicleInfo = VehicleInfo::findOrFail($application->vehicleinfo->id);

	// 	$VehicleInfo->insurance_validity = $request->insurance_validity;
	// 	$VehicleInfo->fitness_validity = $request->fitnness_validity;
	// 	$VehicleInfo->tax_token_validity = $request->tax_paid_upto;
	// 	$VehicleInfo->necessity_to_use = $request->necessity_to_use;
	// 	$VehicleInfo->reg_number = $request->vehicle_reg_no;
	// 	$VehicleInfo->vehicle_type_id = $request->vehicle_type;
	// 	$VehicleInfo->loan_taken = $request->loan_taken;
	// 	if ($request->is_not_transparent == '1') {
	// 		$VehicleInfo->glass_type = $request->glass_type;
	// 	} else {
	// 		$VehicleInfo->glass_type = 'transparent';
	// 	}
	// 	$VehicleInfo->in_out_gate = $request->in_out_gate;
	// 	$VehicleInfo->in_out_time = $request->in_out_time;

	// 	if ($request->hasFile('vehicle_reg_photo')) {
	// 		\File::delete('images/vehicle_reg/' . basename($VehicleInfo->reg_cert_photo));
	// 		$vehicle_reg_filename = time() . '.' . $request->vehicle_reg_photo->getClientOriginalExtension();
	// 		$vehicle_reg_name = '/images/vehicle_reg/' . $vehicle_reg_filename;
	// 		$request->vehicle_reg_photo->move(public_path('images/vehicle_reg'), $vehicle_reg_name);
	// 		$VehicleInfo->reg_cert_photo = $vehicle_reg_name;
	// 	} else {
	// 		$old_app = Application::findOrFail($request->app_id);
	// 		$VehicleInfo->tax_token_photo = $old_app->vehicleinfo->vehicle_reg_photo;
	// 	}
	// 	if ($request->hasFile('insurance_cert_photo')) {
	// 		\File::delete('images/vehicle_insurance/' . basename($VehicleInfo->insurance_cert_photo));
	// 		$vehicle_insurance_filename = time() . '.' . $request->insurance_cert_photo->getClientOriginalExtension();
	// 		$vehicle_insurance_name = '/images/vehicle_insurance/' . $vehicle_insurance_filename;
	// 		$request->insurance_cert_photo->move(public_path('images/vehicle_insurance'), $vehicle_insurance_name);
	// 		$VehicleInfo->insurance_cert_photo = $vehicle_insurance_name;
	// 	} else {
	// 		$old_app = Application::findOrFail($request->app_id);
	// 		$VehicleInfo->insurance_cert_photo = $old_app->vehicleinfo->insurance_cert_photo;
	// 	}
	// 	if ($request->hasFile('tax_token_photo')) {
	// 		\File::delete('images/vehicle_tax_token/' . basename($VehicleInfo->tax_token_photo));
	// 		$vehicle_tax_token_filename = time() . '.' . $request->tax_token_photo->getClientOriginalExtension();
	// 		$vehicle_tax_token_name = '/images/vehicle_tax_token/' . $vehicle_tax_token_filename;
	// 		$request->tax_token_photo->move(public_path('images/vehicle_tax_token'), $vehicle_tax_token_name);
	// 		$VehicleInfo->tax_token_photo = $vehicle_tax_token_name;
	// 	} else {
	// 		$old_app = Application::findOrFail($request->app_id);
	// 		$VehicleInfo->tax_token_photo = $old_app->vehicleinfo->tax_token_photo;
	// 	}
	// 	if ($request->hasFile('fitness_cert_photo')) {
	// 		\File::delete('images/vehicle_fitness/' . basename($VehicleInfo->fitness_cert_photo));
	// 		$vehicle_fitness_filename = time() . '.' . $request->fitness_cert_photo->getClientOriginalExtension();
	// 		$vehicle_fitness_name = '/images/vehicle_fitness/' . $vehicle_fitness_filename;
	// 		$request->fitness_cert_photo->move(public_path('images/vehicle_fitness'), $vehicle_fitness_name);
	// 		$VehicleInfo->fitness_cert_photo = $vehicle_fitness_name;
	// 	} else {
	// 		$old_app = Application::findOrFail($request->app_id);
	// 		$VehicleInfo->fitness_cert_photo = $old_app->vehicleinfo->fitness_cert_photo;
	// 	}
	// 	$VehicleInfo->save();

	// 	$VehicleOwner = VehicleOwner::findOrFail($application->vehicleowner->id);
	// 	$com_address = array(
	// 		"flat" => $request->c_flat,
	// 		"house" => $request->c_house,
	// 		"road" => $request->c_road,
	// 		"block" => $request->c_block,
	// 		"area" => $request->c_area,
	// 	);
	// 	if ($request->owner_is_company == "1") {
	// 		$VehicleOwner->company_name = $request->company_name;
	// 		$VehicleOwner->company_address = json_encode($com_address);
	// 	} else {
	// 		$VehicleOwner->company_name = '';
	// 		$VehicleOwner->company_address = null;
	// 	}
	// 	$VehicleOwner->owner_name = $request->owner_name;
	// 	$VehicleOwner->save();
	// 	$follow_up = new FollowUp;
	// 	$follow_up->application_id = $application->id;
	// 	$follow_up->updater_role = !empty(auth()->guard('applicant')->user()->name) ? 'customer' : auth()->user()->role;
	// 	$follow_up->status = "Vehicle Info Updated";
	// 	$follow_up->created_date = Carbon::now();
	// 	$follow_up->updated_by = !empty(auth()->guard('applicant')->user()->name) ? auth()->guard('applicant')->user()->name : auth()->user()->name;
	// 	$follow_up->save();
	// 	$users = User::all();
	// 	foreach ($users as $user) {
	// 		if (!empty(auth()->guard('applicant')->user()->name)) {
	// 			$app_dtail = array(
	// 				"app_number" => $application->app_number,
	// 				"applicant_name" => auth()->guard('applicant')->user()->name,
	// 				"action" => "updated vehicle detail",
	// 			);
	// 			$user->notify(new ApplicationNotification($app_dtail));
	// 		} elseif (!empty(auth()->user()->name)) {
	// 			$app_dtail = array(
	// 				"app_number" => $application->app_number,
	// 				"applicant_name" => auth()->user()->name,
	// 				"action" => "updated vehicle detail",
	// 			);
	// 			$user->notify(new ApplicationNotification($app_dtail));
	// 		}
	// 	}
	// 	$flag = "success";
	// 	$data = "Vehicle Detail Updated Successfully!";
	// 	return (array($data, $flag));
	// }

	public function vehicleInfoUpdate($app_id, FormValidate $request)
	{
		// dd($request->all());
		$this->validate($request, [
			// Common fields
			'vehicle_reg_photo' => 'nullable|mimes:jpg,jpeg,png',
			'vehicle_owner' => 'required|in:self,spouse,civil_company,govt_company,other',

			// Defence form - Owner documents
			'spouse_passport_copy' => 'nullable|mimes:jpg,jpeg,png,pdf',
			'civil_company_certificate' => 'nullable|mimes:jpg,jpeg,png,pdf',
			'govt_institution_certificate' => 'nullable|mimes:jpg,jpeg,png,pdf',
			'relation_certificate' => 'nullable|mimes:jpg,jpeg,png,pdf',

			//Non-def
			'vehicle_reg_photo' => 'nullable|mimes:jpg,jpeg,png',
			'vehicle_reg_back_photo' => 'nullable|mimes:jpg,jpeg,png',
			'service_retd_doc' => 'nullable|mimes:jpg,jpeg,png',
			'house_owner_doc' => 'nullable|mimes:jpg,jpeg,png',
			'civil_institution_doc' => 'nullable|mimes:jpg,jpeg,png',
			'proyash_school_doc' => 'nullable|mimes:jpg,jpeg,png',
			'teacher_official_doc' => 'nullable|mimes:jpg,jpeg,png',
			'bus_laguna_doc' => 'nullable|mimes:jpg,jpeg,png',
			'motorcycle_doc' => 'nullable|mimes:jpg,jpeg,png',
			'transit_kochukhet_doc' => 'nullable|mimes:jpg,jpeg,png',
			'transit_matikata_doc' => 'nullable|mimes:jpg,jpeg,png',
		]);

		$application = Application::findOrFail($app_id);

		if (Auth::guard('applicant')->user() && $application->retake == 1) {
			$application->retake = 2;
		}
		if ($request->application_category) {
			$application->application_category = $request->application_category;
		}

		$this->handleCategoryDocuments($request, $application);

		$application->save();
		$VehicleInfo = VehicleInfo::findOrFail($application->vehicleinfo->id);


		$VehicleInfo->reg_number = $request->vehicle_reg_no;
		$VehicleInfo->vehicle_type_id = $request->vehicle_type;

		if ($request->hasFile('vehicle_reg_photo')) {
			\File::delete('images/vehicle_reg/' . basename($VehicleInfo->reg_cert_photo));
			$vehicle_reg_filename = time() . '.' . $request->vehicle_reg_photo->getClientOriginalExtension();
			$vehicle_reg_name = '/images/vehicle_reg/' . $vehicle_reg_filename;
			$request->vehicle_reg_photo->move(public_path('images/vehicle_reg'), $vehicle_reg_name);
			$VehicleInfo->reg_cert_photo = $vehicle_reg_name;
		}

		// Handle vehicle registration photo (Back)
		if ($request->hasFile('vehicle_reg_back_photo')) {
			if ($VehicleInfo->reg_cert_back_photo) {
				\File::delete('images/vehicle_reg/' . basename($VehicleInfo->reg_cert_back_photo));
			}
			$vehicle_reg_filename = time() . '_reg_back.' . $request->vehicle_reg_back_photo->getClientOriginalExtension();
			$vehicle_reg_name_back = '/images/vehicle_reg/' . $vehicle_reg_filename;
			$request->vehicle_reg_back_photo->move(public_path('images/vehicle_reg'), $vehicle_reg_name_back);
			$VehicleInfo->reg_cert_back_photo = $vehicle_reg_name_back;
		}


		$VehicleInfo->save();

		$VehicleOwner = VehicleOwner::findOrFail($application->vehicleowner->id);
		$VehicleOwner->vehicle_owner_type = $request->vehicle_owner;
		$VehicleOwner->owner_name = $request->owner_name ?? $VehicleOwner->owner_name;
		$VehicleOwner->relation = $request->relation ?? $VehicleOwner->relation;

		if ($request->vehicle_owner === 'spouse' && $request->hasFile('spouse_passport_copy')) {
			if ($VehicleOwner->spouse_passport_photo) {
				\File::delete(public_path($VehicleOwner->spouse_passport_photo));
			}
			$filename = time() . '_spouse_passport.' . $request->spouse_passport_copy->getClientOriginalExtension();
			$path = '/images/spouse_passport/' . $filename;
			$request->spouse_passport_copy->move(public_path('images/spouse_passport'), $path);
			$VehicleOwner->owner_type_attachment = $path;
		}

		
		if ($request->vehicle_owner === 'other' && $request->hasFile('relation_certificate')) {
			if ($VehicleOwner->relation_certificate) {
				\File::delete(public_path($VehicleOwner->relation_certificate));
			}
			$filename = time() . '_relation_cert.' . $request->relation_certificate->getClientOriginalExtension();
			$path = '/images/relation_certificate/' . $filename;
			$request->relation_certificate->move(public_path('images/relation_certificate'), $path);
			$VehicleOwner->owner_type_attachment = $path;
		}

		// Civil Company - Certificate
		if ($request->vehicle_owner === 'civil_company' && $request->hasFile('civil_company_certificate')) {
			if ($VehicleOwner->civil_company_certificate) {
				\File::delete(public_path($VehicleOwner->civil_company_certificate));
			}
			$filename = time() . '_civil_cert.' . $request->civil_company_certificate->getClientOriginalExtension();
			$path = '/images/civil_company/' . $filename;
			$request->civil_company_certificate->move(public_path('images/civil_company'), $path);
			$VehicleOwner->owner_type_attachment = $path;
		}

		// Govt Institution - Certificate
		if ($request->vehicle_owner === 'govt_company' && $request->hasFile('govt_institution_certificate')) {
			if ($VehicleOwner->govt_institution_certificate) {
				\File::delete(public_path($VehicleOwner->govt_institution_certificate));
			}
			$filename = time() . '_govt_cert.' . $request->govt_institution_certificate->getClientOriginalExtension();
			$path = '/images/govt_institution/' . $filename;
			$request->govt_institution_certificate->move(public_path('images/govt_institution'), $path);
			$VehicleOwner->owner_type_attachment = $path;
		}

		$VehicleOwner->save();
		$follow_up = new FollowUp;
		$follow_up->application_id = $application->id;
		$follow_up->updater_role = !empty(auth()->guard('applicant')->user()->name) ? 'customer' : auth()->user()->role;
		$follow_up->status = "Vehicle Info Updated";
		$follow_up->created_date = Carbon::now();
		$follow_up->updated_by = !empty(auth()->guard('applicant')->user()->name) ? auth()->guard('applicant')->user()->name : auth()->user()->name;
		$follow_up->save();
		$users = User::all();
		foreach ($users as $user) {
			if (!empty(auth()->guard('applicant')->user()->name)) {
				$app_dtail = array(
					"app_number" => $application->app_number,
					"applicant_name" => auth()->guard('applicant')->user()->name,
					"action" => "updated vehicle detail",
				);
				$user->notify(new ApplicationNotification($app_dtail));
			} elseif (!empty(auth()->user()->name)) {
				$app_dtail = array(
					"app_number" => $application->app_number,
					"applicant_name" => auth()->user()->name,
					"action" => "updated vehicle detail",
				);
				$user->notify(new ApplicationNotification($app_dtail));
			}
		}
		$flag = "success";
		$data = "Vehicle Detail Updated Successfully!";
		return (array($data, $flag));
	}
}
