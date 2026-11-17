<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\ApplicantDetail;
use App\Models\Application;
use App\Models\ApplicationNotify;
use App\Models\Payment;
use App\Models\Rank;
use App\Models\StickerCategory;
use App\Models\VehicleInfo;
use App\Models\VehicleSticker;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;
use App\Models\Sms;
use App\Models\FollowUp;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function paymentView($id)
    {
        try {
            $applicationId = decrypt($id);
        } catch (\Exception $e) {
            $mapping_id_special = [
                'Ya' => 0,
                'Is' => 1,
                'Pa' => 2,
                'Nq' => 3,
                'MV' => 4,
                'rD' => 5,
                'QH' => 6,
                'Lm' => 7,
                'Nb' => 8,
                'Ei' => 9
            ];
            $splited = str_split($id, 2);
            $applicationId = '';
            foreach ($splited as $value) {
                $applicationId .= $mapping_id_special[$value];
            }
        }
        $application = Application::find($applicationId);

        return view('payment_gateway.payment_view', compact('application'));
    }

    public function payment_success($id)
    {
        $application = Application::find($id);

        // if($application->payment_status == '1'){
        //      return view('payment_gateway.payment_success', compact('application'));
        // }

        $approveAppCount = DB::table('applications')
            ->where(function ($query) {
                $query->where('applications.app_status', 'approved')
                    ->orWhere('applications.app_status', 'PS approved');
            })
            ->join('application_notifies', 'application_notifies.application_id', '=', 'applications.id')
            ->whereDate('application_notifies.sticker_delivery_date', '=', $application->applicationNotify->sticker_delivery_date)
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
            $time = '13.30 PM';
        }
        $dateApplicationNotify = date('d-m-Y', strtotime($application->applicationNotify->sticker_delivery_date));
        $sms = Sms::where('type', '=', 'payment-success')->first();

        $smsReplaceData = [
            '/date/' => $dateApplicationNotify,
            '/time/' => $time,
            '/reg/' => $application->vehicleinfo->reg_number,
        ];
        $final_approveSms = str_replace(array_keys($smsReplaceData), $smsReplaceData, $sms->sms_text);

        $follow_up = new FollowUp;
        $follow_up->application_id = $application->id;
        $follow_up->app_status = $application->app_status;
        $follow_up->status = "Application payment";
        $follow_up->created_date = Carbon::now();
        $follow_up->comment = $final_approveSms;
        $follow_up->updater_role = $application->applicant->role;
        $follow_up->updated_by = $application->applicant->name;
        $follow_up->save();
        $queue_status = "approve" . time();

        // Send SMS
        $follow_up = FollowUp::find($follow_up->id);
        $res = HomeController::callSmsApi($application->applicant->phone, $final_approveSms);

        $sms_sent = '';
        if ($res == 1) {
            $sms_sent = 'success';
        } else {
            $sms_sent = 'fail';
        }

        $follow_up->sms_sent = $sms_sent;
        $follow_up->mail_sent = '';
        $follow_up->update();

        Log::info('payment_success called', [
            'application_id' => $id,
            'ip' => request()->ip(),
            'url' => request()->fullUrl(),
        ]);

        //  return view('payment_gateway.payment_success', compact('application'));

        return redirect()->route('payment_after_success', $application->id);
    }

    public function paymentReportData(Request $request)
    {
        $data['sticker_categories'] = StickerCategory::all();
        $data['applicant_name'] = $request->applicant_name ?? "";
        $data['applicant_type'] = $request->applicant_type ?? "";
        $data['sticker_type'] = $request->sticker_category ?? "";
        $data['inspec_from_date'] = $request->inspec_from_date ?? "";
        $data['inspec_to_date'] = $request->inspec_to_date ?? "";
        $data['approved_inspec_from_date'] = $request->approved_inspec_from_date ?? "";
        $data['approved_inspec_to_date'] = $request->approved_inspec_to_date ?? "";
        $data['ba'] = $request->ba ?? "";
        $data['rank'] = $request->rank ?? "";
        $data['reg_no'] = $request->reg_no ?? "";
        $data['phone'] = $request->phone ?? "";
        $data['vehicle_type'] = $request->vehicle_type ?? "";
        $data['present_address'] = $request->present_address ?? "";
        $data['approval_count'] = $request->approval_count ?? "";

        return view('payment_gateway.payment-report-list', $data);
    }

    public function paymentReportDatatable(Request $request)
    {
        $query = Payment::with(['application', 'application.applicant.applicantDetail.rank', 'application.vehicleType', 'application.applicationNotify', 'application.vehicleInfo', 'application.vehicleSticker']);

        $from_date = request('inspec_from_date') ? date('Y-m-d 00:00:00', strtotime(request('inspec_from_date'))) : '';
        $to_date = request('inspec_to_date') ? date('Y-m-d 23:59:59', strtotime(request('inspec_to_date'))) : '';
        $approved_from_date = request('approved_inspec_from_date') ? date('Y-m-d 00:00:00', strtotime(request('approved_inspec_from_date'))) : '';
        $approved_to_date = request('approved_inspec_to_date') ? date('Y-m-d 23:59:59', strtotime(request('approved_inspec_to_date'))) : '';

        if ($request->applicant_name) {
            $applicant_ids = DB::table('applicants')
                ->where('name', 'LIKE', '%' . $request->applicant_name . '%')
                ->pluck('id');

            $application_ids = DB::table('applications')
                ->whereIn('applicant_id', $applicant_ids)
                ->pluck('id');

            $query->whereIn('payments.application_id', $application_ids);
        }


        if ($request->applicant_type) {
            $applicant_ids = DB::table('applicants')
                ->where('role', 'LIKE', '%' . $request->applicant_type . '%')
                ->pluck('id');

            $application_ids = DB::table('applications')
                ->whereIn('applicant_id', $applicant_ids)
                ->pluck('id');

            $query->whereIn('payments.application_id', $application_ids);
        }

        if ($request->sticker_type) {
            $application_ids = Application::where('sticker_category', 'like', '%' . $request->sticker_type . '%')->pluck('id');
            $query = $query->whereIn('payments.application_id', $application_ids);
        }
        if ($request->ba) {
            $applicant_ids = ApplicantDetail::where('applicant_BA_no', 'like', '%' . $request->ba . '%')->pluck('applicant_id');
            $application_id = Application::whereIn('applicant_id', $applicant_ids)->pluck('id');
            $query = $query->whereIn('payments.application_id', $application_id);
        }
        if ($request->rank) {
            $rank_id = Rank::where('name', 'like', '%' . $request->rank . '%')->pluck('id');
            $applicant_ids = ApplicantDetail::whereIn('rank_id', $rank_id)->pluck('applicant_id');
            $application_id = Application::whereIn('applicant_id', $applicant_ids)->pluck('id');
            $query = $query->whereIn('payments.application_id', $application_id);
        }
        if ($request->phone) {
            $applicant_ids = Applicant::where('phone', 'like', '%' . $request->phone . '%')->pluck('id');
            $query = $query->whereIn('applications.applicant_id', $applicant_ids);
        }
        if ($request->vehicle_type) {
            $vehicle_type_ids = VehicleType::where('name', 'like', '%' . $request->vehicle_type . '%')->pluck('id');
            $query = $query->whereIn('vehicle_type_id', $vehicle_type_ids);
        }

        if ($request->reg_no) {
            $application_ids = VehicleSticker::where('reg_number', 'like', '%' . $request->reg_no . '%')->pluck('application_id');
            $query = $query->whereIn('payments.application_id', $application_ids);
        }

        if ($from_date && $to_date) {
            $query = $query->whereBetween('payments.created_at', [$from_date, $to_date]);
        }

        if ($approved_from_date && $approved_to_date) {
            $query = $query->whereIn('application_id', function ($subQuery) use ($approved_from_date, $approved_to_date) {
                $subQuery->select('application_id')
                    ->from('application_notifies')
                    ->whereBetween('sticker_delivery_date', [$approved_from_date, $approved_to_date]);
            });
        }

        if ($request->present_address) {
            $applicant_ids = ApplicantDetail::where('address', 'like', '%' . $request->present_address . '%')->pluck('applicant_id');
            $application_id = Application::whereIn('applicant_id', $applicant_ids)->pluck('id');
            $query = $query->whereIn('payments.application_id', $application_id);
        }


        if ($request->approval_count) {
            if ($request->approval_count == '1st') {
                $application_ids = Application::where('approval_count', '=', 1)->pluck('id');
            } elseif ($request->approval_count == '2nd') {
                $application_ids = Application::where('approval_count', '>', 1)->pluck('id');
            } else {
                $application_ids = Application::where('approval_count', 'like', '%' . $request->approval_count . '%')->pluck('id');
            }

            $query = $query->whereIn('application_id', $application_ids); // ✅ correct
        }
        // dd($query);
        return DataTables::of($query)
            ->addIndexColumn()
            // ->addColumn('name', function ($query) {
            //     $name = $query->application && $query->application->applicant ? $query->application->applicant->name : ''; // Null check
            //     $applicant_nid = $query->application && $query->application->applicant && $query->application->applicant->applicantDetail ? $query->application->applicant->applicantDetail->nid_number : '';
            //     return '<a href="' . route('application.review', ['app_number' => $query->application->app_number]) . '" target="_blank">' . $name . '</a>  <br>' . $applicant_nid;
            // })
            ->addColumn('name', function ($query) {
                $name = $query->application && $query->application->applicant ? $query->application->applicant->name : ''; // Null check
                $applicant_nid = $query->application && $query->application->applicant && $query->application->applicant->applicantDetail ? $query->application->applicant->applicantDetail->nid_number : '';
                // return '<a href="' . route('application.review', ['app_number' => $query->application->app_number]) . '" target="_blank">' . $name . '</a>  <br>' . $applicant_nid;

                $app_number = $query->application->app_number ?? null;
                if ($app_number) {
                    $link = '<a href="' . route('application.review', ['app_number' => $app_number]) . '" target="_blank">' . $name . '</a>';
                } else {
                    $link = $name;
                }

                return $link . '<br>' . $applicant_nid;
            })
            ->addColumn('type', function ($query) {
                return $query->application && $query->application->applicant ? $query->application->applicant->role : ''; // Null check
            })
            ->addColumn('applicant_BA_no', function ($query) {
                return $query->application && $query->application->applicant && $query->application->applicant->applicantDetail ? $query->application->applicant->applicantDetail->applicant_BA_no : ''; // Null check
            })
            ->addColumn('rank_name', function ($query) {
                return $query->application && $query->application->applicant && $query->application->applicant->applicantDetail && $query->application->applicant->applicantDetail->rank ? $query->application->applicant->applicantDetail->rank->name : ''; // Null check
            })
            ->addColumn('sticker_reg_number', function ($query) {
                return $query->application && $query->application->vehicleInfo ? $query->application->vehicleInfo->reg_number : ''; // Null check
            })
            ->addColumn('phone', function ($query) {
                return $query->application && $query->application->applicant ? $query->application->applicant->phone : ''; // Null check
            })
            ->addColumn('vehicle_name', function ($query) {
                return $query->application && $query->application->vehicleType ? $query->application->vehicleType->name : ''; // Null check
            })
            ->addColumn('glass_type', function ($query) {
                return $query->application ? $query->application->glass_type : ''; // Null check
            })
            ->addColumn('sticker_category', function ($query) {
                return $query->application && $query->application->sticker_category ? ($query->application->sticker_category == 'e' ? 'E' : $query->application->sticker_category) : ''; // Null check
            })
            ->addColumn('created_at', function ($query) {
                return $query->created_at ? $query->created_at : ''; // Null check
            })

            // ->addColumn('sticker_delivery_date', function ($query) {
            //     return $query->application && $query->application->applicationNotify ? $query->application->applicationNotify->sticker_delivery_date : $query->application->applicationNotify->sticker_approve_date; // Null check
            // })
            ->addColumn('sticker_delivery_date', function ($query) {
                return $query->application && $query->application->applicationNotify ? $query->application->applicationNotify->sticker_delivery_date : $query->application->applicationNotify->sticker_approve_date ??  ''; // Null check
            })
            ->addColumn('payment_status', function ($query) {
                if ($query->application) {
                    // If it's free issue_type, mark as Paid directly
                    if ($query->application->issue_type === 'free') {
                        return 'Free';
                    }

                    // Otherwise check payment_status
                    return $query->application->payment_status == 1 ? 'Paid' : 'Unpaid';
                }

                return 'Unpaid';
            })
            ->addColumn('credit', function ($query) {
                return $query->credit !== null ? $query->credit : '';
            })
            // ->addColumn('address', function ($query) {
            //     $address = $query->application && $query->application->applicant && $query->application->applicant->applicantDetail ? json_decode($query->application->applicant->applicantDetail->address, true) : [];
            //     return isset($address['present']) ? $address['present']['flat'] . ', ' . $address['present']['house'] . ', ' . $address['present']['road'] . ', ' . $address['present']['block'] . ', ' . $address['present']['area'] . '.' : '';
            // })
            ->addColumn('address', function ($query) {
                return !empty($query->application->applicant->applicantDetail) && $query->application->applicant->applicantDetail->address ? $query->application->applicant->applicantDetail->address : '';
            })
            ->addColumn('approval_count', function ($query) {
                $approval_count = $query->application ? $query->application->approval_count : '';
                if ($approval_count == 1) {
                    return '1st time';
                } elseif ($approval_count >= 2) {
                    return '2nd time';
                } else {
                    return "None";
                }
            })
            // ->addColumn('action', function ($query) {
            //     $url = route('applications.invoice', $query->application->id ?? '#');

            //     return '<a href="' . $url . '" target="_blank" class="btn btn-sm btn-primary">
            //                 Invoice
            //             </a>';
            // })
            ->addColumn('action', function ($query) {
                $url = route('applications.invoice', $query->application->id ?? '');
                $deleteUrl = route('payments.delete', $query->id ?? '');
                $buttons = '<a href="' . $url . '" target="_blank" class="btn btn-sm btn-primary">Invoice</a>';

                // return '<a href="' . $url . '" target="_blank" class="btn btn-sm btn-primary">
                //             Invoice
                //         </a>';
                if (
                    auth()->check() &&
                    auth()->user()->role === 'super-admin' &&
                    auth()->user()->email === 'tiladmin@til.com'
                ) {
                    $buttons .= '
                    <button class="btn btn-sm btn-danger delete-payment"
                        data-url="' . $deleteUrl . '">
                        Delete
                    </button>';
                }

                return $buttons;
            })
            ->rawColumns(['name', 'type', 'applicant_BA_no', 'rank_name', 'sticker_reg_number', 'phone', 'vehicle_name', 'glass_type', 'sticker_category', 'created_at', 'sticker_delivery_date', 'payment_status', 'credit', 'address', 'approval_count', 'action'])
            ->make(true);
    }

    public function allPaidRecollect()
    {
        return view('payment_gateway.payment_paid_recollect');
    }
    public function defpaymentReportData(Request $request)
    {
        $sticker_categories = StickerCategory::all();
        $applicant_name = $request->applicant_name ?? "";
        $applicant_type = $request->applicant_type ?? "";
        $sticker_type = $request->sticker_category ?? "";
        $inspec_from_date = $request->inspec_from_date ?? "";
        $inspec_to_date = $request->inspec_to_date ?? "";
        $ba = $request->ba ?? "";
        $rank = $request->rank ?? "";
        $reg_no = $request->reg_no ?? "";
        $phone = $request->phone ?? "";
        $vehicle_type = $request->vehicle_type ?? "";
        $present_address = $request->present_address ?? "";
        $approval_count = $request->approval_count ?? "";

        return view('payment_gateway.def-payment-report-list', compact('sticker_categories', 'applicant_name', 'applicant_type', 'sticker_type', 'inspec_from_date', 'inspec_to_date', 'ba', 'rank', 'reg_no', 'phone', 'vehicle_type', 'present_address', 'approval_count'));
    }
    public function defpaymentReportDatatable(Request $request)
    {
        $query = Payment::join('applications', 'applications.id', 'payments.application_id')
            ->leftJoin('applicants', 'applicants.id', 'applications.applicant_id')
            ->leftJoin('vehicle_types', 'vehicle_types.id', 'applications.vehicle_type_id')
            ->leftJoin('applicant_details', 'applicants.id', 'applicant_details.applicant_id')
            ->leftJoin('ranks', 'ranks.id', 'applicant_details.rank_id')
            ->leftJoin('vehicle_stickers', 'applications.id', 'vehicle_stickers.application_id');

        $from_date = request('inspec_from_date') ? date('Y-m-d', strtotime(request('inspec_from_date'))) : '';
        $to_date = request('inspec_to_date') ? date('Y-m-d', strtotime(request('inspec_to_date'))) : '';

        if ($request->applicant_name) {
            $applicant_ids = Applicant::where('name', 'like', '%' . $request->applicant_name . '%')->pluck('id');
            $query = $query->whereIn('applications.applicant_id', $applicant_ids);
        }
        if ($request->applicant_type) {
            $applicant_ids = Applicant::where('role', 'like', '%' . $request->applicant_type . '%')->pluck('id');
            $query = $query->whereIn('applications.applicant_id', $applicant_ids);
        }
        if ($request->sticker_type) {
            $application_ids = Application::where('sticker_category', 'like', '%' . $request->sticker_type . '%')->pluck('id');
            $query = $query->whereIn('payments.application_id', $application_ids);
        }
        if ($request->ba) {
            $applicant_ids = ApplicantDetail::where('applicant_BA_no', 'like', '%' . $request->ba . '%')->pluck('applicant_id');
            $application_id = Application::whereIn('applicant_id', $applicant_ids)->pluck('id');
            $query = $query->whereIn('payments.application_id', $application_id);
        }
        if ($request->rank) {
            $rank_id = Rank::where('name', 'like', '%' . $request->rank . '%')->pluck('id');
            $applicant_ids = ApplicantDetail::whereIn('rank_id', $rank_id)->pluck('applicant_id');
            $application_id = Application::whereIn('applicant_id', $applicant_ids)->pluck('id');
            $query = $query->whereIn('payments.application_id', $application_id);
        }

        if ($request->phone) {
            $applicant_ids = Applicant::where('phone', 'like', '%' . $request->phone . '%')->pluck('id');
            $query = $query->whereIn('applications.applicant_id', $applicant_ids);
        }
        if ($request->vehicle_type) {
            $vehicle_type_ids = VehicleType::where('name', 'like', '%' . $request->vehicle_type . '%')->pluck('id');
            $query = $query->whereIn('vehicle_type_id', $vehicle_type_ids);
        }
        if ($request->reg_no) {
            $application_ids = VehicleSticker::where('reg_number', 'like', '%' . $request->reg_no . '%')->pluck('application_id');
            $query = $query->whereIn('payments.application_id', $application_ids);
        }

        if ($from_date && $to_date) {
            $payment_ids = Payment::whereBetween('created_at', [$from_date, $to_date])->pluck('id');
            $query->whereIn('payments.id', $payment_ids);
        }
        if ($request->present_address) {
            $applicant_ids = ApplicantDetail::where('address', 'like', '%' . $request->present_address . '%')->pluck('applicant_id');
            $application_id = Application::whereIn('applicant_id', $applicant_ids)->pluck('id');
            $query = $query->whereIn('payments.application_id', $application_id);
        }
        if ($request->approval_count) {
            if ($request->approval_count == '1st') {
                $application_ids = Application::where('approval_count', '=', 1)->pluck('id');
                $query = $query->whereIn('applications.id', $application_ids);
            } elseif ($request->approval_count == '2nd') {
                $application_ids = Application::where('approval_count', '>', 1)->pluck('id');
                $query = $query->whereIn('applications.id', $application_ids);
            } else {
                $application_ids = Application::where('approval_count', 'like', '%' . $request->approval_count . '%')->pluck('id');
                $query = $query->whereIn('applications.id', $application_ids);
            }
        }

        $query = $query->select(
            'applicants.name as applicant_name',
            'applications.app_number',
            'applicants.phone',
            'applicants.role as applicant_role',
            'applications.sticker_category',
            'applications.glass_type',
            'vehicle_types.name as vehicle_name',
            'applicant_details.applicant_BA_no',
            'applicant_details.address',
            'ranks.name as rank_name',
            'vehicle_stickers.reg_number as sticker_reg_number',
            'payments.*',
            'applications.approval_count'
        )
            ->where('applicants.role', 'def');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('name', function ($query) {
                $name = ($query->applicant_name) ? $query->applicant_name : '';
                return '<a href="' . route('application.review', ['app_number' => $query->app_number]) . '" target="_blank">' . $name . '</a>';
            })
            ->addColumn('type', function ($query) {
                $type = ($query->applicant_role) ? $query->applicant_role : '';
                return $type;
            })
            ->addColumn('sticker_category', function ($query) {
                $sticker_category = ($query->sticker_category) ? $query->sticker_category : '';
                return $sticker_category;
            })
            ->addColumn('glass_type', function ($query) {
                $glass_type = ($query->glass_type) ? $query->glass_type : '';
                return $glass_type;
            })
            ->addColumn('vehicle_name', function ($query) {
                $vehicle_name = ($query->vehicle_name) ? $query->vehicle_name : '';
                return $vehicle_name;
            })
            ->addColumn('phone', function ($query) {
                $phone = ($query->phone) ? $query->phone : '';
                return $phone;
            })
            ->addColumn('rank_name', function ($query) {
                $rank_name = ($query->rank_name) ? $query->rank_name : '';
                return $rank_name;
            })
            ->addColumn('applicant_BA_no', function ($query) {
                $applicant_BA_no = ($query->applicant_BA_no) ? $query->applicant_BA_no : '';
                return $applicant_BA_no;
            })
            ->addColumn('sticker_reg_number', function ($query) {
                $sticker_reg_number = ($query->sticker_reg_number) ? $query->sticker_reg_number : '';
                return $sticker_reg_number;
            })
            ->addColumn('address', function ($query) {
                $address = ($query->address) ? json_decode($query->address, true) : '';
                return $address['present']['flat'] . ', ' . $address['present']['house'] . ', ' . $address['present']['road'] . ', ' . $address['present']['block'] . ', ' . $address['present']['area'] . '.';
            })
            ->addColumn('approval_count', function ($query) {
                $approval_count = ($query->approval_count) ? $query->approval_count : '';
                if ($approval_count == 1) {
                    return '1st time';
                } elseif ($approval_count >= 2) {
                    return '2nd time';
                } else {
                    return "None";
                }
            })
            ->with('sum_balance', $query->sum('credit'))
            ->rawColumns(['name', 'type', 'sticker_category', 'create_at', 'created_by', 'credit', 'vehicle_name', 'glass_type', 'phone', 'rank_name', 'applicant_BA_no', 'sticker_reg_number', 'address', 'approval_count'])
            ->toJson();
    }
    public function NondefPaymentReportData(Request $request)
    {
        $sticker_categories = StickerCategory::all();
        $applicant_name = $request->applicant_name ?? "";
        $applicant_type = $request->applicant_type ?? "";
        $sticker_type = $request->sticker_category ?? "";
        $inspec_from_date = $request->inspec_from_date ?? "";
        $inspec_to_date = $request->inspec_to_date ?? "";
        $ba = $request->ba ?? "";
        $rank = $request->rank ?? "";
        $reg_no = $request->reg_no ?? "";
        $phone = $request->phone ?? "";
        $vehicle_type = $request->vehicle_type ?? "";
        $present_address = $request->present_address ?? "";
        $approval_count = $request->approval_count ?? "";

        return view('payment_gateway.nondef-payment-report-list', compact('sticker_categories', 'applicant_name', 'applicant_type', 'sticker_type', 'inspec_from_date', 'inspec_to_date', 'ba', 'rank', 'reg_no', 'phone', 'vehicle_type', 'present_address', 'approval_count'));
    }
    public function NondefPaymentReportDatatable(Request $request)
    {
        $query = Payment::join('applications', 'applications.id', 'payments.application_id')
            ->leftJoin('applicants', 'applicants.id', 'applications.applicant_id')
            ->leftJoin('vehicle_types', 'vehicle_types.id', 'applications.vehicle_type_id')
            ->leftJoin('applicant_details', 'applicants.id', 'applicant_details.applicant_id')
            ->leftJoin('ranks', 'ranks.id', 'applicant_details.rank_id')
            ->leftJoin('vehicle_stickers', 'applications.id', 'vehicle_stickers.application_id');

        $from_date = request('inspec_from_date') ? date('Y-m-d', strtotime(request('inspec_from_date'))) : '';
        $to_date = request('inspec_to_date') ? date('Y-m-d', strtotime(request('inspec_to_date'))) : '';

        if ($request->applicant_name) {
            $applicant_ids = Applicant::where('name', 'like', '%' . $request->applicant_name . '%')->pluck('id');
            $query = $query->whereIn('applications.applicant_id', $applicant_ids);
        }
        if ($request->applicant_type) {
            $applicant_ids = Applicant::where('role', 'like', '%' . $request->applicant_type . '%')->pluck('id');
            $query = $query->whereIn('applications.applicant_id', $applicant_ids);
        }
        if ($request->sticker_type) {
            $application_ids = Application::where('sticker_category', 'like', '%' . $request->sticker_type . '%')->pluck('id');
            $query = $query->whereIn('payments.application_id', $application_ids);
        }
        if ($request->ba) {
            $applicant_ids = ApplicantDetail::where('applicant_BA_no', 'like', '%' . $request->ba . '%')->pluck('applicant_id');
            $application_id = Application::whereIn('applicant_id', $applicant_ids)->pluck('id');
            $query = $query->whereIn('payments.application_id', $application_id);
        }
        if ($request->rank) {
            $rank_id = Rank::where('name', 'like', '%' . $request->rank . '%')->pluck('id');
            $applicant_ids = ApplicantDetail::whereIn('rank_id', $rank_id)->pluck('applicant_id');
            $application_id = Application::whereIn('applicant_id', $applicant_ids)->pluck('id');
            $query = $query->whereIn('payments.application_id', $application_id);
        }

        if ($request->phone) {
            $applicant_ids = Applicant::where('phone', 'like', '%' . $request->phone . '%')->pluck('id');
            $query = $query->whereIn('applications.applicant_id', $applicant_ids);
        }
        if ($request->vehicle_type) {
            $vehicle_type_ids = VehicleType::where('name', 'like', '%' . $request->vehicle_type . '%')->pluck('id');
            $query = $query->whereIn('vehicle_type_id', $vehicle_type_ids);
        }
        if ($request->reg_no) {
            $application_ids = VehicleSticker::where('reg_number', 'like', '%' . $request->reg_no . '%')->pluck('application_id');
            $query = $query->whereIn('payments.application_id', $application_ids);
        }

        if ($from_date && $to_date) {
            $payment_ids = Payment::whereBetween('created_at', [$from_date, $to_date])->pluck('id');
            $query->whereIn('payments.id', $payment_ids);
        }
        if ($request->present_address) {
            $applicant_ids = ApplicantDetail::where('address', 'like', '%' . $request->present_address . '%')->pluck('applicant_id');
            $application_id = Application::whereIn('applicant_id', $applicant_ids)->pluck('id');
            $query = $query->whereIn('payments.application_id', $application_id);
        }
        if ($request->approval_count) {
            if ($request->approval_count == '1st') {
                $application_ids = Application::where('approval_count', '=', 1)->pluck('id');
                $query = $query->whereIn('applications.id', $application_ids);
            } elseif ($request->approval_count == '2nd') {
                $application_ids = Application::where('approval_count', '>', 1)->pluck('id');
                $query = $query->whereIn('applications.id', $application_ids);
            } else {
                $application_ids = Application::where('approval_count', 'like', '%' . $request->approval_count . '%')->pluck('id');
                $query = $query->whereIn('applications.id', $application_ids);
            }
        }

        $query = $query->select(
            'applicants.name as applicant_name',
            'applications.app_number',
            'applicants.phone',
            'applicants.role as applicant_role',
            'applications.sticker_category',
            'applications.glass_type',
            'vehicle_types.name as vehicle_name',
            'applicant_details.applicant_BA_no',
            'applicant_details.address',
            'ranks.name as rank_name',
            'vehicle_stickers.reg_number as sticker_reg_number',
            'payments.*',
            'applications.approval_count'
        )
            ->where('applicants.role', 'non-def');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('name', function ($query) {
                $name = ($query->applicant_name) ? $query->applicant_name : '';
                return '<a href="' . route('application.review', ['app_number' => $query->app_number]) . '" target="_blank">' . $name . '</a>';
            })
            ->addColumn('type', function ($query) {
                $type = ($query->applicant_role) ? $query->applicant_role : '';
                return $type;
            })
            ->addColumn('sticker_category', function ($query) {
                $sticker_category = ($query->sticker_category) ? $query->sticker_category : '';
                return $sticker_category;
            })
            ->addColumn('glass_type', function ($query) {
                $glass_type = ($query->glass_type) ? $query->glass_type : '';
                return $glass_type;
            })
            ->addColumn('vehicle_name', function ($query) {
                $vehicle_name = ($query->vehicle_name) ? $query->vehicle_name : '';
                return $vehicle_name;
            })
            ->addColumn('phone', function ($query) {
                $phone = ($query->phone) ? $query->phone : '';
                return $phone;
            })
            ->addColumn('rank_name', function ($query) {
                $rank_name = ($query->rank_name) ? $query->rank_name : '';
                return $rank_name;
            })
            ->addColumn('applicant_BA_no', function ($query) {
                $applicant_BA_no = ($query->applicant_BA_no) ? $query->applicant_BA_no : '';
                return $applicant_BA_no;
            })
            ->addColumn('sticker_reg_number', function ($query) {
                $sticker_reg_number = ($query->sticker_reg_number) ? $query->sticker_reg_number : '';
                return $sticker_reg_number;
            })
            ->addColumn('address', function ($query) {
                $address = ($query->address) ? json_decode($query->address, true) : '';
                return $address['present']['flat'] . ', ' . $address['present']['house'] . ', ' . $address['present']['road'] . ', ' . $address['present']['block'] . ', ' . $address['present']['area'] . '.';
            })
            ->addColumn('approval_count', function ($query) {
                $approval_count = ($query->approval_count) ? $query->approval_count : '';
                if ($approval_count == 1) {
                    return '1st time';
                } elseif ($approval_count >= 2) {
                    return '2nd time';
                } else {
                    return "None";
                }
            })
            ->with('sum_balance', $query->sum('credit'))
            ->rawColumns(['name', 'type', 'sticker_category', 'create_at', 'created_by', 'credit', 'vehicle_name', 'glass_type', 'phone', 'rank_name', 'applicant_BA_no', 'sticker_reg_number', 'address'])
            ->toJson();
    }


    public function newpaymentReportData(Request $request)
    {
        $sticker_categories  = StickerCategory::all();
        $applicant_name = $request->applicant_name ?? "";
        $ba = $request->ba ?? "";
        $rank = $request->rank ?? "";
        $reg_no = $request->reg_no ?? "";
        $phone = $request->phone ?? "";
        $date = $request->date ?? "";
        $Vehicle_Type = $request->Vehicle_Type ?? "";
        $glass_type = $request->Vehicle_Type ?? "";
        $sticker_type = $request->sticker_type ?? "";
        $present_address = $request->present_address ?? "";

        // Date range parameters
        $approve_from_date = $request->approve_from_date ?? "";
        $approve_to_date = $request->approve_to_date ?? "";
        $payment_from_date = $request->payment_from_date ?? "";
        $payment_to_date = $request->payment_to_date ?? "";
        $inspec_from_date = $request->inspec_from_date ?? "";
        $inspec_to_date = $request->inspec_to_date ?? "";

        return view('payment_gateway.new_payment-report-list', compact('sticker_categories', 'applicant_name', 'ba', 'rank', 'reg_no', 'phone', 'date', 'Vehicle_Type', 'glass_type', 'sticker_type', 'present_address', 'approve_from_date', 'approve_to_date', 'payment_from_date', 'payment_to_date', 'inspec_from_date', 'inspec_to_date'));
    }

    public function newpaymentReportDatatable(Request $request)
    {
        // dd($request->all());
        // Get applications with required conditions
        $query = Application::with(['applicant', 'vehicleinfo', 'vehicleinfo.vehicleType', 'payment_s', 'applicationNotify'])
            ->whereIn('app_status', ['approved', 'PS approved'])
            ->where(function ($q) {
                $q->where('payment_status', 1)
                    ->orWhere('issue_type', 'free');
            });

        // Filter by payment date using join approach
        $query->join('payments', 'applications.id', '=', 'payments.application_id')
            ->whereDate('payments.created_at', '>=', '2025-08-01')
            ->select('applications.*');




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
            $rank_ids = Rank::where('name', 'like', '%' . $request->rank . '%')->pluck('id');
            $applicant_ids = ApplicantDetail::whereIn('rank_id', $rank_ids)->pluck('applicant_id');
            $query->whereIn('applicant_id', $applicant_ids);
        }

        if ($request->present_address) {
            $applicant_ids = ApplicantDetail::where('address', 'like', '%' . $request->present_address . '%')->pluck('applicant_id');
            $query->whereIn('applicant_id', $applicant_ids);
        }

        // if ($request->reg_no) {
        //     $application_ids = vehicleinfo::where('reg_number', 'like', '%' . $request->reg_no . '%')->pluck('application_id');
        //     $query->whereIn('id', $application_ids);
        // }
        if ($request->reg_no) {
            $application_ids = vehicleinfo::where('reg_number', 'like', '%' . $request->reg_no . '%')
                ->pluck('application_id');
            $query->whereIn('applications.id', $application_ids);
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


        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('applicant_name', function (Application $application) {
                $nid = ($application->applicant->applicantDetail) ? $application->applicant->applicantDetail->nid_number : '';
                return '<a href="' . route('application.review', ['app_number' => $application->app_number]) . '" target="_blank">' . $application->applicant->name . '</a><br>' . $nid;
            })
            ->addColumn('type', function (Application $application) {
                $type = $application->applicant->role ?? '';

                return $type;
            })
            ->addColumn('BA_no', function (Application $application) {
                $ba_no = $application->applicant->applicantDetail->applicant_BA_no ?? '';

                return $ba_no;
            })
            ->addColumn('Rank_id', function (Application $application) {
                $rank_id = $application->applicant->applicantDetail->rank->name ?? '';
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

            ->addColumn('sticker_approve_date', function (Application $application) {
                $sticker_approve_date =
                    $application->applicationNotify->sticker_approve_date ?? '';
                return $sticker_approve_date;
            })
            ->addColumn('payment_date', function (Application $application) {
                if (!empty($application->payment_s)) {
                    return date('d-m-Y', strtotime($application->payment_s->created_at ?? ''));
                }
            })

            ->addColumn('payment_status', function ($query) {
                // যদি free হয়
                if ($query->issue_type == 'free' && $query->payment_status == 0) {
                    return 'Free';
                }

                // otherwise check payment_status
                return $query->payment_status == 1 ? 'Paid' : 'Unpaid';
            })
            // ->addColumn('credit', function ($query) {               
            //     return $query->payment->credit !== null ? $query->payment->credit : '';
            // })
            ->addColumn('credit', function ($query) {
                return $query->payment_s ? $query->payment_s->credit : 0;
            })
            ->addColumn('address', function (Application $application) {

                $app_address = ($application->applicant->applicantDetail) ? json_decode($application->applicant->applicantDetail->address, true) : '';
                if ($app_address) {
                    return $app_address['present']['flat'] . ', ' . $app_address['present']['house'] . ', ' . $app_address['present']['road'] . ', ' . $app_address['present']['block'] . ', ' . $app_address['present']['area'] . '.';
                }
            })
            ->addColumn('action', function (Application $application) {
                $url = route('applications.invoice', $application->id);

                return '<a href="' . $url . '" target="_blank" class="btn btn-sm btn-primary">
                            Invoice
                        </a>';
            })
            ->rawColumns(['applicant_name', 'action'])
            ->toJson();
    }



    public function delete(Payment $payment)
    {
        DB::transaction(function () use ($payment) {


            $payment->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Payment deleted successfully'
        ]);
    }

    public function payment_after_sucess($id)
    {
        $application = Application::find($id);

        return view('payment_gateway.payment_success', compact('application'));
    }
}
