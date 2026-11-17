<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\ApplicantDetail;
use App\Models\Application;
use App\Models\ApplicationNotify;
use App\Models\FollowUp;
use App\Models\Rank;
use App\Models\StickerCategory;
use App\Models\VehicleInfo;
use App\Models\VehicleType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Sms;
use Illuminate\Support\Facades\DB;

class ETypeApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function verifyApplication(Request $request, $def)
    {

        $data['stickerTypes'] = StickerCategory::all();
        $data['applicant_name'] = $request->applicant_name ?? "";
        $data['ba'] = $request->ba ?? "";
        $data['rank'] = $request->rank ?? "";
        $data['reg_no'] = $request->reg_no ?? "";
        $data['phone'] = $request->phone ?? "";
        $data['date'] = $request->date ?? "";
        $data['Vehicle_Type'] = $request->Vehicle_Type ?? "";
        $data['glass_type'] = $request->Vehicle_Type ?? "";
        $data['sticker_type'] = $request->sticker_type ?? "";
        $data['present_address'] = $request->present_address ?? "";
        $data['def'] = $def ?? "";
        return view('apps.verify', $data);
    }

    public function allVerifyDatatable11(Request $request)
    {
        $def = request('def');

        $query = Application::with(['applicant', 'vehicleinfo', 'vehicleinfo.vehicleType'])->where('app_status', 'verify')->where('payment_status', 0);

        if ($def == 'def') {
            $query->where('type', '=', 'def');
        }
        if ($def == 'non-def') {
            $query->where('type', '=', 'non-def');
        }
        if ($def == 'not-transparent') {
            $query->where('glass_type', '!=', 'transparent');
        }
        if ($def == 'transparent') {
            $query->where('glass_type', '=', 'transparent');
        }

        // Search Data 
        if ($request->applicant_name) {
            $applicant_ids = Applicant::where('name', 'like', '%' . $request->applicant_name . '%')->pluck('id');
            $query->whereIn('applicant_id', $applicant_ids);
        }

        if ($request->phone) {
            $applicant_ids = Applicant::where('phone', 'like', '%' . $request->phone . '%')->pluck('id');
            $query->whereIn('applicant_id', $applicant_ids);
        }

        if ($request->ba) {
            $applicant_ids = ApplicantDetail::where('applicant_BA_no', 'like', '%' . $request->ba . '%')->pluck('applicant_id');
            $query->whereIn('applicant_id', $applicant_ids);
        }

        if ($request->rank) {
            $rank_ids = Rank::where('name', 'like',  '%' . $request->rank . '%')->pluck('id');
            $applicant_ids = ApplicantDetail::whereIn('rank_id', $rank_ids)->pluck('applicant_id');
            $query->whereIn('applicant_id', $applicant_ids);
        }

        if ($request->present_address) {
            $applicant_ids = ApplicantDetail::where('address', 'like', '%' . $request->present_address . '%')->pluck('applicant_id');
            $query->whereIn('applicant_id', $applicant_ids);
        }

        if ($request->reg_no) {
            $application_ids = VehicleInfo::where('reg_number', 'like', '%' . $request->reg_no . '%')->pluck('application_id');
            $query->whereIn('id', $application_ids);
        }

        if ($request->date) {
            $query->where('app_date', date('Y-m-d', strtotime($request->date)));
        }

        if ($request->Vehicle_Type) {
            $vehicle_type_ids = VehicleType::where('name', 'like', '%' . $request->Vehicle_Type . '%')->pluck('id');
            $query->whereIn('vehicle_type_id', $vehicle_type_ids);
        }

        // if ($request->glass_type) {
        //     $query->where('glass_type', $request->glass_type);
        // }

        if ($request->sticker_type) {
            $query->where('sticker_category', $request->sticker_type);
        }


        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('applicant_name', function (Application $application) {
                $nid = ($application->applicant->applicantDetail) ? $application->applicant->applicantDetail->nid_number : '';
                return '<a href="' . route('application.review', ['app_number' => $application->app_number]) . '" target="_blank">' . $application->applicant->name . '</a><br>' . $nid;
            })
            ->addColumn('BA_no', function (Application $application) {
                $ba_no = $application->applicant->applicantDetail->applicant_BA_no ?? '';

                return $ba_no;
            })
            ->addColumn('Rank_id', function (Application $application) {
                $rank_id =  $application->applicant->applicantDetail->rank->name ?? '';
                return $rank_id;
            })
            ->addColumn('Reg_number', function (Application $application) {
                $reg_number = ($application->vehicleinfo) ? $application->vehicleinfo->reg_number : '';
                return $reg_number;
            })
            ->addColumn('phone_number', function (Application $application) {
                return $application->applicant->phone;
            })
            ->editColumn('app_date', function (Application $application) {
                return date('d-m-Y', strtotime($application->app_date));
            })
            ->filterColumn('app_date', function ($query, $keyword) {
                $query->whereDate('app_date', date('Y-m-d', strtotime($keyword)));
            })
            ->filterColumn('app_date', function ($query, $keyword) {
                $query->whereDate('app_date', date('Y-m-d', strtotime($keyword)));
            })
            ->addColumn('vehicleType', function (Application $application) {
                $vehicle_type =
                    $application->vehicleinfo->vehicleType->name ?? '';
                return $vehicle_type;
            })
            // ->addColumn('address', function (Application $application) {

            //     $app_address = ($application->applicant->applicantDetail) ? json_decode($application->applicant->applicantDetail->address, true) : '';
            //     if ($app_address) {
            //         return $app_address['present']['flat'] . ', ' . $app_address['present']['house'] . ', ' . $app_address['present']['road'] . ', ' . $app_address['present']['block'] . ', ' . $app_address['present']['area'] . '.';
            //     }
            // })
             ->addColumn('address', function (Application $application) {
                return !empty($application->applicant->applicantDetail) && $application->applicant->applicantDetail->address ? $application->applicant->applicantDetail->address : '';
            })
            ->rawColumns(['applicant_name'])
            ->toJson();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function eTypeApplication(Request $request)
    {

        $data['stickerTypes'] = StickerCategory::all();
        $data['applicant_name'] = $request->applicant_name ?? "";
        $data['ba'] = $request->ba ?? "";
        $data['rank'] = $request->rank ?? "";
        $data['reg_no'] = $request->reg_no ?? "";
        $data['phone'] = $request->phone ?? "";
        $data['date'] = $request->date ?? "";
        $data['Vehicle_Type'] = $request->Vehicle_Type ?? "";
        $data['glass_type'] = $request->Vehicle_Type ?? "";
        $data['sticker_type'] = $request->sticker_type ?? "";
        $data['present_address'] = $request->present_address ?? "";
        $data['type'] = $type ?? "";
        return view('e_type_application.index', $data);
    }

    public function allETypeDatatable(Request $request)
    {

        $query = Application::with(['applicant', 'vehicleinfo', 'vehicleinfo.vehicleType']);

        // Search Data 
        if ($request->applicant_name) {
            $applicant_ids = Applicant::where('name', 'like', '%' . $request->applicant_name . '%')->pluck('id');
            $query->whereIn('applicant_id', $applicant_ids);
        }

        if ($request->phone) {
            $applicant_ids = Applicant::where('phone', 'like', '%' . $request->phone . '%')->pluck('id');
            $query->whereIn('applicant_id', $applicant_ids);
        }

        if ($request->present_address) {
            $applicant_ids = ApplicantDetail::where('address', 'like', '%' . $request->present_address . '%')->pluck('applicant_id');
            $query->whereIn('applicant_id', $applicant_ids);
        }

        if ($request->reg_no) {
            $application_ids = VehicleInfo::where('reg_number', 'like', '%' . $request->reg_no . '%')->pluck('application_id');
            $query->whereIn('id', $application_ids);
        }

        if ($request->date) {
            $query->where('app_date', date('Y-m-d', strtotime($request->date)));
        }

        if ($request->Vehicle_Type) {
            $vehicle_type_ids = VehicleType::where('name', 'like', '%' . $request->Vehicle_Type . '%')->pluck('id');
            $query->whereIn('vehicle_type_id', $vehicle_type_ids);
        }

        if ($request->sticker_type) {
            $query->where('sticker_category', $request->sticker_type);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('applicant_name', function (Application $application) {
                $nid = ($application->applicant->applicantDetail) ? $application->applicant->applicantDetail->nid_number : '';
                return '<a href="' . route('application.review', ['app_number' => $application->app_number]) . '" target="_blank">' . $application->applicant->name . '</a><br>' . $nid;
            })
            ->addColumn('Reg_number', function (Application $application) {
                $reg_number = ($application->vehicleinfo) ? $application->vehicleinfo->reg_number : '';
                return $reg_number;
            })
            ->addColumn('phone_number', function (Application $application) {
                return $application->applicant->phone;
            })
            ->editColumn('app_date', function (Application $application) {
                return date('d-m-Y', strtotime($application->app_date));
            })
            ->filterColumn('app_date', function ($query, $keyword) {
                $query->whereDate('app_date', date('Y-m-d', strtotime($keyword)));
            })
            ->filterColumn('app_date', function ($query, $keyword) {
                $query->whereDate('app_date', date('Y-m-d', strtotime($keyword)));
            })
            ->addColumn('vehicleType', function (Application $application) {
                $vehicle_type =
                    $application->vehicleinfo->vehicleType->name ?? '';
                return $vehicle_type;
            })
            // ->addColumn('address', function (Application $application) {

            //     $app_address = ($application->applicant->applicantDetail) ? json_decode($application->applicant->applicantDetail->address, true) : '';
            //     if ($app_address) {
            //         return $app_address['present']['flat'] . ', ' . $app_address['present']['house'] . ', ' . $app_address['present']['road'] . ', ' . $app_address['present']['block'] . ', ' . $app_address['present']['area'] . '.';
            //     }
            // })
             ->addColumn('address', function (Application $application) {
                return !empty($application->applicant->applicantDetail) && $application->applicant->applicantDetail->address ? $application->applicant->applicantDetail->address : '';
            })
            ->rawColumns(['applicant_name'])
            ->toJson();
    }

    public function applicationVerify(Request $request)
    {
        $app = Application::where('app_number', $request->app_num)->first();

        $sticker_category = StickerCategory::where('value', $request->sticker_type)->first();
        $appnotifyexist = ApplicationNotify::where('application_id', $app->id)->first();

        if (!$sticker_category) {
            return json_encode(["fail", "Application type is invalid."]);
        }

        if ($app->app_status == "pending" || $app->app_status == "updated" && $app->retake == 2 || $app->app_status == "forwarded to PS") {

            $app->app_status = "verify";
            $app->sticker_category = $request->sticker_type;
            $app->retake = 1;

            $app->save();

            if (!$appnotifyexist) {
                $ApplicationNotify = new ApplicationNotify;
                $ApplicationNotify->application_id = $app->id;
                $ApplicationNotify->applicant_phone = $app->applicant->phone;
                $ApplicationNotify->app_status = $app->app_status;
                $ApplicationNotify->sticker_verify_date = $request->sticker_verify_date;
                $ApplicationNotify->save();
            } else {
                $appnotifyexist->app_status = $app->app_status;
                $appnotifyexist->sticker_verify_date = $request->sticker_verify_date;
                $appnotifyexist->mis_matched = Null;
                $appnotifyexist->custom_reject_sms = Null;
                $appnotifyexist->save();
            }

            $follow_up = new FollowUp();
            $follow_up->application_id = $app->id;
            $follow_up->app_status = $app->app_status;
            $follow_up->status = "Application verify";
            $follow_up->created_date = Carbon::now();
            $follow_up->updater_role = auth()->user()->role;
            $follow_up->updated_by = auth()->user()->name;
            $follow_up->save();

            return json_encode(["success", "Application Verify successfully!!"]);
        } elseif ($app->app_status == "verify") {
            return json_encode(["fail", "This Application already verfied"]);
        } else {
            return json_encode(["not-now", "You can approve pending application only.Thank You."]);
        }
    }




    public function applicationdelivery(Request $request)
    {
        // dd($request->all());
        $final_approveSms = '';
        $mail_status = '';
        $sms_sent = '';
        $app = Application::where('app_number', $request->app_num)->first();
        $appnotifyexist = ApplicationNotify::where('application_id', $app->id)->first();
        //start_newapproved_msg
        $sticker_category = StickerCategory::where('value', $request->sticker_type)->first();
        //end_newapproved_msg
        // if ($app->payment_status == 0) {
        //     return json_encode(['Payment not completed.', 'fail']);
        // }
        if ($app->payment_status == 0 && $app->issue_type != 'free') {
            return json_encode(['Payment not completed.', 'fail']);
        }
        if ($app->app_status == "approved" || $app->app_status == "updated" && $app->retake == 2 || $app->app_status == "forwarded to PS" || $app->app_status == "PS approved") {
            $approveAppCount = DB::table('applications')
                ->where(function ($query) {
                    $query->where('applications.app_status', 'approved')
                        ->orWhere('applications.app_status', 'PS approved');
                })
                ->join('application_notifies', 'application_notifies.application_id', '=', 'applications.id')
                ->whereDate('application_notifies.sticker_delivery_date', '=', $request->sticker_delivery_date)
                ->count();
            $time = '';
            $app_per_slot = 20;
            $slotFactor = $approveAppCount / $app_per_slot;
            $no_free_slot = '';
            if ($slotFactor <= 1) {
                $time = '10.00 AM';
            } elseif (1 < $slotFactor && $slotFactor <= 2) {
                $time = '10.30 AM';
            } elseif (2 < $slotFactor && $slotFactor <= 3) {
                $time = '11.00 AM';
            } elseif (3 < $slotFactor && $slotFactor <= 4) {
                $time = '11.30 AM';
            } elseif (4 < $slotFactor && $slotFactor <= 5) {
                $time = '12.00 PM';
            } elseif (5 < $slotFactor && $slotFactor <= 6) {
                $time = '12.30 PM';
            } elseif (6 < $slotFactor && $slotFactor <= 7) {
                $time = '13.00 PM';
            } elseif (7 < $slotFactor && $slotFactor <= 8) {
                $time = '13.30 PM';
            } else {
                $no_free_slot = "No space available in any delivery time slot on " . $request->sticker_delivery_date;
                return json_encode(array('no slot available', $no_free_slot));
            }
            if ($app->app_status == "forwarded to PS" || $app->is_ps_forwarded == "yes") {
                $app->ps_approved = 1;
            }

            $app->app_status = "delivered";
            // $app->app_status = "deliveried";
            // $app->app_status = $app->is_ps_forwarded == "yes" || $app->app_status == "forwarded to PS" ? "PS approved" : "approved";
            $app->sticker_category = $request->sticker_type;
            $app->retake = 1;
            $app->approval_count = $app->approval_count == 0 ? 1 : $app->approval_count + 1;

            $app->print_status = "pending";
            // $app->sticker_for_year = $request->sticker_for_year;
            $app->sticker_for_year = ($request->sticker_type == 'A1' || $request->sticker_type == 'A2') ? (int) $request->sticker_for_year + 1
                : $request->sticker_for_year;
            $stickerNumber = explode('-', $request->sticker_number);
            $app->sticker_number = end($stickerNumber);

            // dd($app);
            $app->save();

            if (!$appnotifyexist) {
                $ApplicationNotify = new ApplicationNotify;
                $ApplicationNotify->application_id = $app->id;
                $ApplicationNotify->applicant_phone = $app->applicant->phone;
                $ApplicationNotify->app_status = $app->app_status;
                $ApplicationNotify->sticker_delivery_date = $request->sticker_delivery_date;

                $ApplicationNotify->save();
            } else {
                $appnotifyexist->app_status = $app->app_status;
                $appnotifyexist->sticker_delivery_date = $request->sticker_delivery_date;
                // $appnotifyexist->sticker_delivery_date = $request->sticker_delivery_date;
                $appnotifyexist->mis_matched = Null;
                $appnotifyexist->custom_reject_sms = Null;
                // dd($appnotifyexist);
                $appnotifyexist->save();
            }



            if ($request->free_sticker_ckbox) {
                $sms = Sms::where('type', '=', 'FreeApproved')->first();
            } elseif ($request->is_discount == 1) {
                $sms = Sms::where('type', '=', 'DiscountApproved')->first();
            } else {
                $sms = Sms::where('type', '=', 'approved')->first();
            }



            $sms = '';


            $sms = Sms::where('sms_template_name', '=', 'delivery')->first();

            if (!$sms) {
                // Handle missing SMS template gracefully
                return json_encode(['fail', 'SMS template for delivery not found. Please contact admin.']);
            }

            // dd($sms);

            $ids = str_split($app->id);
            //dd($ids);
            $mapping_id_special = [
                0 => 'Ya',
                1 => 'Is',
                2 => 'Pa',
                3 => 'Nq',
                4 => 'MV',
                5 => 'rD',
                6 => 'QH',
                7 => 'Lm',
                8 => 'Nb',
                9 => 'Ei'
            ];
            $encryptedId = '';
            foreach ($ids as $key => $value) {
                $encryptedId = $encryptedId . $mapping_id_special[$value];
            }

            // $url_link = route('payment.view', $encryptedId);
            // $bn = array("১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০");
            // $en = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
            // $banglaDate = str_replace($en, $bn, $request->sticker_delivery_date);
            // $banglaTime = str_replace($en, $bn, $time);
            // $banglaRegNumber = str_replace($en, $bn, $app->vehicleinfo->reg_number);
            $dateApplicationNotify = date('d-m-Y', strtotime($request->sticker_delivery_date));

            //  $final_approveSms = str_replace('/time/', $app->vehicleinfo->reg_number, $time, $approveSms);
            $smsReplaceData = [
                // '/price/' => $sticker_category->price,
                // '/date/' => $appnotifyexist->sticker_delivery_date,
                '/date/' => date('d-m-Y', strtotime($appnotifyexist->sticker_delivery_date)),
                '/time/' => $time,
                '/reg/' => $app->vehicleinfo->reg_number,
            ];
            $final_approveSms = str_replace(array_keys($smsReplaceData), $smsReplaceData, $sms->sms_text);



            $follow_up = new FollowUp;
            $follow_up->application_id = $app->id;
            $follow_up->app_status = $app->app_status;
            $follow_up->status = "Application Deliveried";
            $follow_up->created_date = Carbon::now();
            $follow_up->comment = $final_approveSms;
            $follow_up->updater_role = auth()->user()->role;
            $follow_up->updated_by = auth()->user()->name;
            //    dd($follow_up);
            $follow_up->save();
            $queue_status = "approve" . time();
            // $ApplicationNotify->applicant_phone;

            // Send SMS
            $follow_up = FollowUp::find($follow_up->id);
            $res = HomeController::callSmsApi($app->applicant->phone, $final_approveSms);

            $sms_sent = '';
            if ($res == 1) {
                /*$sms_applicant = new SmsApplicant;
                $sms_applicant->application_id = $follow_up->application_id;
                $sms_applicant->sms_id = $this->sms->id;
                $sms_applicant->sms_status = $this->sms->type;
                $sms_applicant->api_CamID = $res['CamID'];
                $sms_applicant->save();*/
                $sms_sent = 'success';
            } else {
                $sms_sent = 'fail';
            }

            $follow_up->sms_sent = $sms_sent;
            $follow_up->mail_sent = '';
            // $follow_up->update();
            // dd($follow_up);
            $follow_up->update();

            /*$job = (new SendSMSandMail($ApplicationNotify->applicant_phone,$final_approveSms,$app->applicant->email,$follow_up->id,$sms))
            ->onQueue($queue_status)->delay(Carbon::now()->addSeconds(0));
            dispatch($job); */
            $successOrFail = "success";
            $data = "Application delivery successfully!!";
            $changleDate = date('d-m-Y', strtotime($request->sticker_delivery_date));
            // return json_encode(array($data, $successOrFail, $app,  $changleDate, $follow_up));
            return json_encode(array($data, $successOrFail, $app, $queue_status, $changleDate, $follow_up));
        } elseif ($app->app_status == "delivery") {
            $successOrFail = "fail";
            $data = "This Application already delivered";
            return json_encode(array($data, $successOrFail));
        } else {
            $data = "You can delivery approve application only.Thank You.";
            $successOrFail = "not-now";
            return array($data, $successOrFail);
        }
    }
}
