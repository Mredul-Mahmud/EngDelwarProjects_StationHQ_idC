<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\ApplicantDetail;
use App\Models\Application;
use App\Models\ApplicationNotify;
use App\Models\FollowUp;
use App\Models\Rank;
use App\Models\Sms;
use App\Models\StickerCategory;
use App\Models\VehicleInfo;
use App\Models\VehicleType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AllAprovedApplication extends Controller
{
    public function allApplicationApproveDef(Request $request)
    {

        $final_approveSms = '';
        $mail_status = '';
        $sms_sent = '';
        $allApplications = Application::where('app_status', 'verify')->where('type', 'def')->with('applicationNotify')->count();
        // dd($allApplications);
        $applications = Application::where('app_status', 'verify')->where('type', 'def')->with('applicationNotify')->take(20)->get();

        $TotalapproveAppCount  = $applications->count();
        $reaminingApplications = $allApplications - $TotalapproveAppCount;

        // dd($applications);


        foreach ($applications as $key => $app) {


            $appnotifyexist = ApplicationNotify::where('application_id', $app->id)->first();

            //start_newapproved_msg
            $sticker_category = StickerCategory::where('value', $app->sticker_category)->first();
            //end_newapproved_msg

            $approveAppCount = DB::table('applications')
                ->where(function ($query) {
                    $query->where('applications.app_status', 'approved')
                        ->orWhere('applications.app_status', 'PS approved');
                })
                ->join('application_notifies', 'application_notifies.application_id', '=', 'applications.id')
                ->whereDate('application_notifies.sticker_approve_date', '=', $app->applicationNotify->sticker_approve_date)
                ->count();
            // dd($approveAppCount);



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
                $time = '13.30 PM';
            }


            if ($app->app_status == "forwarded to PS")
                $app->ps_approved = 1;

            $app->app_status = $app->app_status == "forwarded to PS" ? "PS approved" : "approved";
            $app->retake = 1;
            $app->approval_count = $app->approval_count == 0 ? 1 : $app->approval_count + 1;
            $app->save();


            $paymentExpireDate = Carbon::parse($request->sticker_approve_date)->addDays(4);

            if (!$appnotifyexist) {
                $ApplicationNotify = new ApplicationNotify;
                $ApplicationNotify->application_id = $app->id;
                $ApplicationNotify->applicant_phone = $app->applicant->phone;
                $ApplicationNotify->app_status = $app->app_status;
                $ApplicationNotify->sticker_approve_date = Carbon::today()->toDateString();
                $ApplicationNotify->payment_expire_date = $paymentExpireDate->toDateString();
                $ApplicationNotify->save();
            } else {
                $appnotifyexist->app_status = $app->app_status;
                $appnotifyexist->sticker_approve_date = Carbon::today()->toDateString();
                $appnotifyexist->payment_expire_date = $paymentExpireDate->toDateString();
                $appnotifyexist->mis_matched = Null;
                $appnotifyexist->custom_reject_sms = Null;
                $appnotifyexist->save();
            }
            $sms = '';

            $sms = Sms::where('type', '=', 'approved')->first();



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

            $url_link = route('payment.view', $encryptedId);
            $bn = array("১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০");
            $en = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
            $banglaDate = str_replace($en, $bn, $app->applicationNotify->sticker_delivery_date);
            $banglaTime = str_replace($en, $bn, $time);
            $banglaRegNumber = str_replace($en, $bn, $app->vehicleinfo->reg_number);
            $dateApplicationNotify = date('d-m-Y', strtotime($app->applicationNotify->sticker_delivery_date));

            // If application approved normally
            $sms = Sms::where('type', '=', 'approved')->first();
            // $approveSms = str_replace('/date/', $dateApplicationNotify, $sms->sms_text);
            // $approveSms1 = str_replace('/time/', $time, $approveSms);

            if ($app->payment_status == "0") {
                //If Application approved First time
                // $first_approve_add =  "অনুগ্রহপূর্বক স্টিকারের স্টিকারের নির্দিষ্ট মূল্য " . $sticker_category->price . " টাকা অনলাইনে লিংকঃ (" . $url_link . ") পরিশোধের পর |";
                // $approveSms2 = str_replace('/add_msg/', $first_approve_add, $approveSms1);
                // $final_approveSms = str_replace('/reg/', $app->vehicleinfo->reg_number, $approveSms2);

                $smsReplaceData = [
                    '/price/' => $sticker_category->price,
                    '/link/' => $url_link,
                    '/reg/' => $app->vehicleinfo->reg_number,
                    '/expire_date/' => date('d-m-Y', strtotime($paymentExpireDate)),
                ];
                $final_approveSms = str_replace(array_keys($smsReplaceData), $smsReplaceData, $sms->sms_text);
            } else {
                //If Application already paid but approved again
                // $first_approve_add = "";
                // $approveSms2 = str_replace('/add_msg/', $first_approve_add, $approveSms1);
                // $final_approveSms = str_replace('/reg/', $app->vehicleinfo->reg_number, $approveSms2);
                $final_approveSms = '';
            }


            $follow_up = new FollowUp();
            $follow_up->application_id = $app->id;
            $follow_up->app_status = $app->app_status;
            $follow_up->status = "Application approved";
            $follow_up->created_date = Carbon::now();
            $follow_up->comment = $final_approveSms;
            $follow_up->updater_role = auth()->user()->role;
            $follow_up->updated_by = auth()->user()->name;
            $follow_up->save();
            $queue_status = "approve" . time();
            // $ApplicationNotify->applicant_phone;

            // Send SMS
            $follow_up = FollowUp::find($follow_up->id);
            $res = HomeController::callSmsApi($app->applicant->phone, $final_approveSms);

            $sms_sent = '';
            if ($res == 1) {
                $sms_sent = 'success';
            } else {
                $sms_sent = 'fail';
            }

            $follow_up->sms_sent = $sms_sent;
            $follow_up->mail_sent = '';
            $follow_up->update();
        }

        return redirect()->back()->with('success', "Total Application approved . $TotalapproveAppCount . 'successfully!!" . " Remaining Application" . $reaminingApplications . "Please try again");
    }


    public function allApplicationApproveNonDef(Request $request)
    {

        $final_approveSms = '';
        $mail_status = '';
        $sms_sent = '';
        $allApplications = Application::where('app_status', 'verify')->where('type', 'non-def')->with('applicationNotify')->count();
        // dd($allApplications);
        $applications = Application::where('app_status', 'verify')->where('type', 'non-def')->with('applicationNotify')->take(20)->get();

        $TotalapproveAppCount  = $applications->count();
        $reaminingApplications = $allApplications - $TotalapproveAppCount;

        // dd($applications);


        foreach ($applications as $key => $app) {


            $appnotifyexist = ApplicationNotify::where('application_id', $app->id)->first();

            //start_newapproved_msg
            $sticker_category = StickerCategory::where('value', $app->sticker_category)->first();
            //end_newapproved_msg

            $approveAppCount = DB::table('applications')
                ->where(function ($query) {
                    $query->where('applications.app_status', 'approved')
                        ->orWhere('applications.app_status', 'PS approved');
                })
                ->join('application_notifies', 'application_notifies.application_id', '=', 'applications.id')
                ->whereDate('application_notifies.sticker_approve_date', '=', $app->applicationNotify->sticker_approve_date)
                ->count();
            // dd($approveAppCount);



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
                $time = '13.30 PM';
            }


            if ($app->app_status == "forwarded to PS")
                $app->ps_approved = 1;

            $app->app_status = $app->app_status == "forwarded to PS" ? "PS approved" : "approved";
            $app->retake = 1;
            $app->approval_count = $app->approval_count == 0 ? 1 : $app->approval_count + 1;
            $app->save();


            $paymentExpireDate = Carbon::parse($request->sticker_approve_date)->addDays(4);

            if (!$appnotifyexist) {
                $ApplicationNotify = new ApplicationNotify;
                $ApplicationNotify->application_id = $app->id;
                $ApplicationNotify->applicant_phone = $app->applicant->phone;
                $ApplicationNotify->app_status = $app->app_status;
                $ApplicationNotify->sticker_approve_date = $request->sticker_approve_date;
                // $ApplicationNotify->sticker_approve_date = Carbon::today()->toDateString();
                $ApplicationNotify->payment_expire_date = $paymentExpireDate;
                $ApplicationNotify->save();
            } else {
                $appnotifyexist->app_status = $app->app_status;
                $appnotifyexist->sticker_approve_date = $request->sticker_approve_date;
                $appnotifyexist->payment_expire_date = $paymentExpireDate;
                $appnotifyexist->mis_matched = Null;
                $appnotifyexist->custom_reject_sms = Null;
                $appnotifyexist->save();
            }


            $sms = '';

            $sms = Sms::where('type', '=', 'approved')->first();



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

            $url_link = route('payment.view', $encryptedId);
            $bn = array("১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০");
            $en = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
            $banglaDate = str_replace($en, $bn, $app->applicationNotify->sticker_delivery_date);
            $banglaTime = str_replace($en, $bn, $time);
            $banglaRegNumber = str_replace($en, $bn, $app->vehicleinfo->reg_number);
            $dateApplicationNotify = date('d-m-Y', strtotime($app->applicationNotify->sticker_delivery_date));

            // If application approved normally
            $sms = Sms::where('type', '=', 'approved')->first();
            // $approveSms = str_replace('/date/', $dateApplicationNotify, $sms->sms_text);
            // $approveSms1 = str_replace('/time/', $time, $approveSms);

            if ($app->payment_status == "0") {
                //If Application approved First time
                // $first_approve_add =  "অনুগ্রহপূর্বক স্টিকারের স্টিকারের নির্দিষ্ট মূল্য " . $sticker_category->price . " টাকা অনলাইনে লিংকঃ (" . $url_link . ") পরিশোধের পর |";
                // $approveSms2 = str_replace('/add_msg/', $first_approve_add, $approveSms1);
                // $final_approveSms = str_replace('/reg/', $app->vehicleinfo->reg_number, $approveSms2);

                $smsReplaceData = [
                    '/price/' => $sticker_category->price,
                    '/link/' => $url_link,
                    '/reg/' => $app->vehicleinfo->reg_number,
                    '/expire_date/' => date('d-m-Y', strtotime($paymentExpireDate)),
                ];
                $final_approveSms = str_replace(array_keys($smsReplaceData), $smsReplaceData, $sms->sms_text);
            } else {
                //If Application already paid but approved again
                // $first_approve_add = "";
                // $approveSms2 = str_replace('/add_msg/', $first_approve_add, $approveSms1);
                // $final_approveSms = str_replace('/reg/', $app->vehicleinfo->reg_number, $approveSms2);
                $final_approveSms = '';
            }


            $follow_up = new FollowUp();
            $follow_up->application_id = $app->id;
            $follow_up->app_status = $app->app_status;
            $follow_up->status = "Application approved";
            $follow_up->created_date = Carbon::now();
            $follow_up->comment = $final_approveSms;
            $follow_up->updater_role = auth()->user()->role;
            $follow_up->updated_by = auth()->user()->name;
            $follow_up->save();
            $queue_status = "approve" . time();
            // $ApplicationNotify->applicant_phone;

            // Send SMS
            $follow_up = FollowUp::find($follow_up->id);
            $res = HomeController::callSmsApi($app->applicant->phone, $final_approveSms);

            $sms_sent = '';
            if ($res == 1) {
                $sms_sent = 'success';
            } else {
                $sms_sent = 'fail';
            }

            $follow_up->sms_sent = $sms_sent;
            $follow_up->mail_sent = '';
            $follow_up->update();
        }

        return redirect()->back()->with(
            'success',
            "Total Application approved " . $TotalapproveAppCount . " successfully!! " .
                "Remaining Application: " . $reaminingApplications . ". Please try again."
        );
    }




    // public function allApplicationDelivery(Request $request)
    // {
    //     $final_approveSms = '';
    //     $mail_status = '';
    //     $sms_sent = '';

    //     // Get applications eligible for delivery
    //     // $applications = Application::with(['applicant', 'vehicleinfo', 'vehicleinfo.vehicleType', 'payment', 'applicationNotify'])
    //     //     ->whereIn('app_status', ['approved', 'PS approved'])
    //     //     ->where(function ($q) {
    //     //         $q->where('payment_status', 1)
    //     //             ->orWhere('issue_type', 'free');
    //     //     })
    //     //     ->get();
    //     $applications = Application::with(['applicant', 'vehicleinfo', 'vehicleinfo.vehicleType', 'payment', 'applicationNotify'])
    //         ->whereIn('app_status', ['approved', 'PS approved'])
    //         ->where(function ($q) {
    //             $q->where('payment_status', 1)
    //                 ->orWhere('issue_type', 'free');
    //         })
    //         ->join('payments', 'applications.id', '=', 'payments.application_id')
    //         ->whereDate('payments.created_at', '>=', '2025-08-01')
    //         ->select('applications.*')
    //         ->get();

    //     // return response()->json(['applications' => $applications]);

    //     foreach ($applications as $app) {
    //         $appnotifyexist = ApplicationNotify::where('application_id', $app->id)->first();

    //         // Sticker category for SMS
    //         $sticker_category = StickerCategory::where('value', $app->sticker_category)->first();

    //         // Calculate delivery slot
    //         $approveAppCount = DB::table('applications')
    //             ->where(function ($query) {
    //                 $query->where('applications.app_status', 'delivered')
    //                     ->orWhere('applications.app_status', 'PS approved');
    //             })
    //             ->join('application_notifies', 'application_notifies.application_id', '=', 'applications.id')
    //             ->whereDate('application_notifies.sticker_delivery_date', '=', ($app->applicationNotify ? $app->applicationNotify->sticker_delivery_date : null))
    //             ->count();

    //         $timeSlots = [
    //             1 => '10.00 AM',
    //             2 => '10.30 AM',
    //             3 => '11.00 AM',
    //             4 => '11.30 AM',
    //             5 => '12.00 PM',
    //             6 => '12.30 PM',
    //             7 => '13.00 PM',
    //             8 => '13.30 PM',
    //         ];

    //         $app_per_slot = 20;
    //         $slotFactor = ceil($approveAppCount / $app_per_slot);
    //         $time = $timeSlots[$slotFactor] ?? '13.30 PM';

    //         // Update application status
    //         if (in_array($app->app_status, ['approved', 'PS approved'])) {
    //             $app->ps_approved = 1;
    //         }
    //         $app->app_status = 'delivered';
    //         $app->retake = 1;
    //         $app->approval_count = $app->approval_count == 0 ? 1 : $app->approval_count + 1;
    //         $app->save();

    //         // Update or create application notify
    //         if (!$appnotifyexist) {
    //             $ApplicationNotify = new ApplicationNotify();
    //             $ApplicationNotify->application_id = $app->id;
    //             $ApplicationNotify->applicant_phone = $app->applicant->phone;
    //             $ApplicationNotify->app_status = $app->app_status;
    //             $ApplicationNotify->sticker_delivery_date = $request->input('sticker_delivery_date');
    //             // $ApplicationNotify->sticker_delivery_date = Carbon::today()->toDateString();
    //             $ApplicationNotify->save();
    //             // dd($ApplicationNotify);
    //         } else {
    //             $appnotifyexist->app_status = $app->app_status;
    //             $appnotifyexist->sticker_delivery_date = $request->input('sticker_delivery_date');
    //             // $appnotifyexist->sticker_delivery_date = Carbon::today()->toDateString();
    //             $appnotifyexist->mis_matched = null;
    //             $appnotifyexist->custom_reject_sms = null;
    //             $appnotifyexist->save();
    //             // dd($appnotifyexist);
    //         }

    //         // Prepare SMS
    //         $smsTemplate = Sms::where('sms_template_name', 'delivery')->first();
    //         if (!$smsTemplate) continue;

    //         $smsReplaceData = [
    //             '/date/' => ($app->applicationNotify && $app->applicationNotify->sticker_delivery_date ? date('d-m-Y', strtotime($app->applicationNotify->sticker_delivery_date)) : ''),
    //             '/time/' => $time,
    //             '/reg/' => $app->vehicleinfo->reg_number,
    //         ];
    //         $final_approveSms = str_replace(array_keys($smsReplaceData), $smsReplaceData, $smsTemplate->sms_text);

    //         // Log follow up
    //         $follow_up = new FollowUp();
    //         $follow_up->application_id = $app->id;
    //         $follow_up->app_status = $app->app_status;
    //         $follow_up->status = "Application Delivered";
    //         $follow_up->created_date = Carbon::now();
    //         $follow_up->comment = $final_approveSms;
    //         $follow_up->updater_role = auth()->user()->role;
    //         $follow_up->updated_by = auth()->user()->name;
    //         $follow_up->save();

    //         // Send SMS (disabled)
    //         $res = HomeController::callSmsApi($app->applicant->phone, $final_approveSms);
    //         $follow_up->sms_sent = '';
    //         $follow_up->mail_sent = '';
    //         $follow_up->update();
    //     }

    //     return redirect()->back()->with('success', "Applications delivered successfully!!");
    // }


    public function allApplicationDelivery(Request $request)
    {
        try {
            $final_approveSms = '';
            $mail_status = '';
            $sms_sent = '';
            $deliveryDate = $request->input('sticker_delivery_date');

            // Get applications eligible for delivery
            $query = Application::with(['applicant', 'vehicleinfo', 'vehicleinfo.vehicleType', 'payment', 'applicationNotify'])
                ->whereIn('app_status', ['approved', 'PS approved'])
                ->where(function ($q) {
                    $q->where('payment_status', 1)
                        ->orWhere('issue_type', 'free');
                })
                ->join('payments', 'applications.id', '=', 'payments.application_id')
                ->whereDate('payments.created_at', '>=', '2025-08-01')
                ->select('applications.*');

            // Check delivery option
            $deliveryOption = $request->input('delivery_option', 'searched');

            // Apply search filters only if "searched" option is selected
            if ($deliveryOption === 'searched') {
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
                    $rank_ids = Rank::where('name', 'like', '%' . $request->rank . '%')->pluck('id');
                    $applicant_ids = ApplicantDetail::whereIn('rank_id', $rank_ids)->pluck('applicant_id');
                    $query->whereIn('applicant_id', $applicant_ids);
                }

                if ($request->present_address) {
                    $applicant_ids = ApplicantDetail::where('address', 'like', '%' . $request->present_address . '%')->pluck('applicant_id');
                    $query->whereIn('applicant_id', $applicant_ids);
                }

                if ($request->reg_no) {
                    $application_ids = VehicleInfo::where('reg_number', 'like', '%' . $request->reg_no . '%')->pluck('application_id');
                    $query->whereIn('applications.id', $application_ids);
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

                // Date range filtering
                $approve_from_date = request('approve_from_date') ? date('Y-m-d', strtotime(request('approve_from_date'))) : '';
                $approve_to_date = request('approve_to_date') ? date('Y-m-d', strtotime(request('approve_to_date'))) : '';

                $payment_from_date = request('payment_from_date') ? date('Y-m-d', strtotime(request('payment_from_date'))) : '';
                $payment_to_date = request('payment_to_date') ? date('Y-m-d', strtotime(request('payment_to_date'))) : '';

                // Apply approval date filtering
                if ($approve_from_date && $approve_to_date) {
                    $query->whereRaw("applications.id IN (SELECT application_id FROM application_notifies WHERE DATE(sticker_approve_date) BETWEEN ? AND ?)", [$approve_from_date, $approve_to_date]);
                } elseif ($approve_from_date) {
                    $query->whereRaw("applications.id IN (SELECT application_id FROM application_notifies WHERE DATE(sticker_approve_date) >= ?)", [$approve_from_date]);
                } elseif ($approve_to_date) {
                    $query->whereRaw("applications.id IN (SELECT application_id FROM application_notifies WHERE DATE(sticker_approve_date) <= ?)", [$approve_to_date]);
                }

                // Apply payment date filtering
                if ($payment_from_date && $payment_to_date) {
                    $query->whereRaw("applications.id IN (SELECT application_id FROM payments WHERE DATE(created_at) BETWEEN ? AND ?)", [$payment_from_date, $payment_to_date]);
                } elseif ($payment_from_date) {
                    $query->whereRaw("applications.id IN (SELECT application_id FROM payments WHERE DATE(created_at) >= ?)", [$payment_from_date]);
                } elseif ($payment_to_date) {
                    $query->whereRaw("applications.id IN (SELECT application_id FROM payments WHERE DATE(created_at) <= ?)", [$payment_to_date]);
                }
            }

            // Clone query to get total count
            $countQuery = clone $query;
            $totalApplications = $countQuery->count();

            // Get limited applications from original query
            $applications = $query->take(20)->get();
            $deliveredCount = $applications->count();
            $remainingApplications = $totalApplications - $deliveredCount;

            // return response()->json(['applications' => $applications]);

            DB::beginTransaction();
            $stickerNumbersByCategory = [];

            // Calculate sticker_for_year for each application
            foreach ($applications as $app) {
                if ($app->sticker_category == 'A1' || $app->sticker_category == 'A2') {
                    $app->calculated_sticker_year = $request->sticker_for_year + 1;
                } else {
                    $app->calculated_sticker_year = $request->sticker_for_year;
                }
            }
            // Calculate sticker numbers using the calculated year
            // foreach ($applications as $app) {
            //     if (empty($app->sticker_number)) {
            //         // Create unique key combining category and year
            //         $categoryKey = $app->sticker_category . '_' . $app->calculated_sticker_year;

            //         if (!isset($stickerNumbersByCategory[$categoryKey])) {
            //             // Get max number for this category AND year
            //             $maxNumber = Application::where('sticker_category', $app->sticker_category)
            //                 ->where('sticker_for_year', $app->calculated_sticker_year)
            //                 ->whereNotNull('sticker_number')
            //                 ->lockForUpdate()
            //                 // ->max('sticker_number');
            //                 ->selectRaw("MAX(CAST(sticker_number AS UNSIGNED)) as max_num")
            //                 ->value('max_num');

            //             if (is_string($maxNumber)) {
            //                 preg_match('/\d+/', $maxNumber, $matches);
            //                 $maxNumber = isset($matches[0]) ? (int)$matches[0] : 0;
            //             } else {
            //                 $maxNumber = (int)($maxNumber ?? 0);
            //             }

            //             $stickerNumbersByCategory[$categoryKey] = $maxNumber;
            //         }

            //         // Increment and assign
            //         $stickerNumbersByCategory[$categoryKey]++;
            //         $app->temp_sticker_number = $stickerNumbersByCategory[$categoryKey];
            //     }
            // }
            foreach ($applications as $app) {
                if (empty($app->sticker_number)) {
                    // Create unique key combining category and year
                    $categoryKey = $app->sticker_category . '_' . $app->calculated_sticker_year;

                    if (!isset($stickerNumbersByCategory[$categoryKey])) {
                        // Get max number for this category AND year
                        $maxNumber = Application::where('sticker_category', $app->sticker_category)
                            ->where('sticker_for_year', $app->calculated_sticker_year)
                            ->whereNotNull('sticker_number')
                            ->lockForUpdate()
                            ->selectRaw("MAX(CAST(sticker_number AS UNSIGNED)) as max_num")
                            ->value('max_num'); // 

                        $maxNumber = (int) ($maxNumber ?? 0); // 

                        $stickerNumbersByCategory[$categoryKey] = $maxNumber;
                    }

                    // Increment and assign
                    $stickerNumbersByCategory[$categoryKey]++;
                    $app->temp_sticker_number = $stickerNumbersByCategory[$categoryKey];
                }
            }

            foreach ($applications as $app) {
                $appnotifyexist = ApplicationNotify::where('application_id', $app->id)->first();

                $sticker_category = StickerCategory::where('value', $app->sticker_category)->first();



                $timeSlots = [
                    1 => '10.00 AM',
                    2 => '10.30 AM',
                    3 => '11.00 AM',
                    4 => '11.30 AM',
                    5 => '12.00 PM',
                    6 => '12.30 PM',
                    7 => '13.00 PM',
                    8 => '13.30 PM',
                ];


                $app_per_slot = 20;

                // Count how many applications are already scheduled/delivered today
                $approveAppCount = DB::table('applications')
                    ->join('application_notifies', 'application_notifies.application_id', '=', 'applications.id')
                    ->whereDate('application_notifies.sticker_delivery_date', $deliveryDate)
                    ->count();

                // Include current application
                $approveAppCount++;

                // Calculate slot
                $slotFactor = ceil($approveAppCount / $app_per_slot);

                // Assign time
                $time = $timeSlots[$slotFactor] ? $timeSlots[$slotFactor] : '10:00 AM';
                $app->app_status = 'delivered';
                $app->print_status = 'pending';
                $app->sticker_for_year = $app->calculated_sticker_year;

                if (isset($app->temp_sticker_number)) {
                    $app->sticker_number = $app->temp_sticker_number;
                }
                $app->retake = 1;
                $app->approval_count = $app->approval_count == 0 ? 1 : $app->approval_count + 1;
                unset($app->calculated_sticker_year);
                unset($app->temp_sticker_number);
                $app->save();

                // Update or create application notify
                if (!$appnotifyexist) {
                    $ApplicationNotify = new ApplicationNotify();
                    $ApplicationNotify->application_id = $app->id;
                    $ApplicationNotify->applicant_phone = $app->applicant->phone;
                    $ApplicationNotify->app_status = $app->app_status;
                    $ApplicationNotify->sticker_delivery_date = $request->input('sticker_delivery_date');
                    $ApplicationNotify->save();
                } else {
                    $appnotifyexist->app_status = $app->app_status;
                    $appnotifyexist->sticker_delivery_date = $request->input('sticker_delivery_date');
                    $appnotifyexist->mis_matched = null;
                    $appnotifyexist->custom_reject_sms = null;
                    $appnotifyexist->save();
                }

                // Prepare SMS
                $smsTemplate = Sms::where('sms_template_name', 'delivery')->first();
                if (!$smsTemplate) continue;

                $smsReplaceData = [
                    // '/date/' => ($app->applicationNotify && $app->applicationNotify->sticker_delivery_date ? date('d-m-Y', strtotime($app->applicationNotify->sticker_delivery_date)) : ''),
                    '/date/' => date('d-m-Y', strtotime($appnotifyexist->sticker_delivery_date)),
                    '/time/' => $time,
                    '/reg/' => $app->vehicleinfo->reg_number,
                ];
                $final_approveSms = str_replace(array_keys($smsReplaceData), $smsReplaceData, $smsTemplate->sms_text);

                // Log follow up
                $follow_up = new FollowUp();
                $follow_up->application_id = $app->id;
                $follow_up->app_status = $app->app_status;
                $follow_up->status = "Application Delivered";
                $follow_up->created_date = Carbon::now();
                $follow_up->comment = $final_approveSms;
                $follow_up->updater_role = auth()->user()->role;
                $follow_up->updated_by = auth()->user()->name;
                $follow_up->save();

                // Send SMS (disabled)
                $res = HomeController::callSmsApi($app->applicant->phone, $final_approveSms);
                $sms_sent = '';
                if ($res == 1) {
                    $sms_sent = 'success';
                } else {
                    $sms_sent = 'fail';
                }

                $follow_up->sms_sent = $sms_sent;
                $follow_up->mail_sent = '';
                $follow_up->update();
            }

            // $optionText = $deliveryOption === 'all' ? 'All Approved Applications (Auto-detected: No search filters)' : 'Searched Applications (Auto-detected: Search filters applied)';
            // return redirect()->back()->with('success', "Applications delivered successfully!! ({$optionText}) Total processed: " . $totalApplications . " applications.");

            DB::commit();

            // $optionText = $deliveryOption === 'all' ? 'All Approved Applications (Auto-detected: No search filters)' : 'Searched Applications (Auto-detected: Search filters applied)';
            // return redirect()->back()->with('success', "Applications delivered successfully!! ({$optionText}) Total processed: " . $totalApplications . " applications.");
            return redirect()->back()->with(
                'success',
                "Applications delivered " . $deliveredCount . " successfully!! " .

                    "Remaining Applications: " . $remainingApplications . ". Please try again."
            );
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'All Delivery failed: ' . $e->getMessage());
        }
    }



    // public function getStickerLastestSerialNumberByCategory(Request $request)
    // {
    //     $maxNumber = Application::where('sticker_category', $request->sticker_category)
    //         ->where('sticker_for_year', $request->year)
    //         ->max('sticker_number');
    //     return response()->json(['serial' => $maxNumber ? (int)$maxNumber + 1 : 1]);
    // }

    public function getStickerLastestSerialNumberByCategory(Request $request)
    {
        // Category wise validity (only where needed)
        $validityMap = [
            'A1' => 2,
            'A2' => 2,
            'Exe' => 2,
        ];

        $inputYear = (int) $request->year;
        $category  = $request->sticker_category;

        // calculate effective year
        $validity = $validityMap[$category] ?? 1;
        $effectiveYear = $inputYear + max(0, $validity - 1);
        // dd($effectiveYear);

        $maxNumber = Application::where('sticker_category', $category)
            ->where('sticker_for_year', $effectiveYear)
            // ->max('sticker_number');
            ->selectRaw(
                "MAX(CAST(SUBSTRING_INDEX(sticker_number, '-', -1) AS UNSIGNED)) as max_serial"
            )->value('max_serial');
        // dd($maxNumber);

        return response()->json([
            'serial' => $maxNumber ? ((int)$maxNumber + 1) : 1,
            'year'   => $effectiveYear
        ]);
    }
}
