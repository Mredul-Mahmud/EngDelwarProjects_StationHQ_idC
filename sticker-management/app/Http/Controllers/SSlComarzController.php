<?php

namespace App\Http\Controllers;

use App\Library\SslCommerz\SslCommerzNotification;
use App\Models\Application;
use App\Models\FollowUp;
use App\Models\Sms;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class SSlComarzController extends Controller
{
    public function make_payment(Request $request)
    {
        $applicantion = Application::find($request->application_id);

        if ($applicantion->payment_status == 1) {
            Session::flash('alert', 'You have already made a payment for this application.');

            return redirect()->back();
        }
        // $data = json_decode($request->cart_json, true);
        $post_data = [];
        $post_data['total_amount'] = $request->amount;
        $post_data['currency'] = 'BDT';
        $post_data['tran_id'] = uniqid();

        // CUSTOMER INFORMATION
        $post_data['cus_name'] = $applicantion->applicant->name;
        $post_data['cus_email'] = $applicantion->applicant->email ? $applicantion->applicant->email : 'softdev2@tilbd.net';
        $post_data['cus_add1'] = '';
        $post_data['cus_add2'] = '';
        $post_data['cus_city'] = '';
        $post_data['cus_state'] = '';
        $post_data['cus_postcode'] = '';
        $post_data['cus_country'] = 'Bangladesh';
        $post_data['cus_phone'] = $applicantion->applicant->phone;
        $post_data['cus_fax'] = '';

        // SHIPMENT INFORMATION
        $post_data['ship_name'] = 'Store Test';
        $post_data['ship_add1'] = 'Dhaka';
        $post_data['ship_add2'] = 'Dhaka';
        $post_data['ship_city'] = 'Dhaka';
        $post_data['ship_state'] = 'Dhaka';
        $post_data['ship_postcode'] = '1000';
        $post_data['ship_phone'] = '';
        $post_data['ship_country'] = 'Bangladesh';

        $post_data['shipping_method'] = 'NO';
        $post_data['product_name'] = 'Computer';
        $post_data['product_category'] = 'Goods';
        $post_data['product_profile'] = 'physical-goods';

        // OPTIONAL PARAMETERS
        $post_data['value_a'] = $applicantion->id;
        $post_data['value_b'] = $applicantion->applicant->id;
        $post_data['value_c'] = $applicantion->vehicleinfo->reg_number;

        $values = [
            'application_id' => $applicantion->id,
            'name' => $post_data['cus_name'],
            'email' => $post_data['cus_email'],
            'amount' => $post_data['total_amount'],
            'phone' => $post_data['cus_phone'],
            'status' => 0,
            'address' => $post_data['cus_add1'],
            'transaction_id' => $post_data['tran_id'],
            'payment_by' => 'SSL',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        DB::table('ssl_payments')->insert($values);
        $sslc = new SslCommerzNotification;



        // dd($sslc->makePayment($post_data, 'hosted'));

        // dd($sslc->makePayment($post_data, 'hosted'));
        // dd($sslc);

        $payment_options = $sslc->makePayment($post_data, 'hosted');
        // dd($payment_options);
        if (! is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = [];
        }
    }

    public function success(Request $request)
    {

        // dd($request->all());
        $tran_id = $request->input('tran_id');
        $amount = $request->input('amount');
        $currency = $request->input('currency');
        $sslc = new SslCommerzNotification;

        // dd($sslc);
        $order_detials = DB::table('ssl_payments')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'amount')
            ->first();

        // dd($order_detials);

        if ($order_detials->status == 0) {
            // orderValidate signature: ($post_data, $trx_id, $amount, $currency)
            $validation = $sslc->orderValidate($request->all(), $tran_id, $amount, $currency);

            if ($validation == true) {
                DB::table('ssl_payments')
                    ->where('transaction_id', $tran_id)
                    ->update(['status' => 1]);
                $this->complete_payment($request->value_a, $amount);
                Session::flash('message', 'Transaction is successfully Completed');

                return redirect()->route('payment_success', $request->value_a);
            } else {
                Log::error('SSLCommerz validation failed', [
                    'error' => $sslc->getError(),
                    'tran_id' => $tran_id,
                    'amount' => $amount,
                    'currency' => $currency,
                    'post_data' => $request->except(['card_issuer_country', 'card_issuer_country_code', 'card_no', 'card_issuer', 'card_brand']),
                ]);
                DB::table('ssl_payments')
                    ->where('transaction_id', $tran_id)
                    ->update(['status' => 3]);
                Session::flash('alert', 'validation Fail');
                $ids = str_split($request->value_a);
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
                    9 => 'Ei',
                ];
                $encryptedId = '';
                foreach ($ids as $value) {
                    $encryptedId .= $mapping_id_special[$value];
                }

                return redirect()->route('payment.view', $encryptedId);
            }
        } elseif ($order_detials->status == 'Processing' || $order_detials->status == 'Complete') {
            DB::table('ssl_payments')
                ->where('transaction_id', $tran_id)
                ->update(['status' => 1]);
            $this->complete_payment($request->value_a, $amount);
            Session::flash('message', 'Transaction is successfully Completed');

            return redirect()->route('payment_success', $request->value_a);
        } else {
            DB::table('ssl_payments')
                ->where('transaction_id', $tran_id)
                ->update(['status' => 3]);
            Session::flash('message', 'Transaction is successfully Completed');

            return redirect()->route('payment_success');
        }
    }

    public function fail(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order_detials = DB::table('ssl_payments')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'amount')
            ->first();

        $ids = str_split($request->value_a);
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
            9 => 'Ei',
        ];
        $encryptedId = '';
        foreach ($ids as $key => $value) {
            $encryptedId = $encryptedId . $mapping_id_special[$value];
        }

        if ($order_detials->status == 0) {
            $update_product = DB::table('ssl_payments')
                ->where('transaction_id', $tran_id)
                ->update(['status' => 3]);
            Session::flash('alert', 'Transaction is Falied');

            return redirect()->route('payment.view', $encryptedId);
        } elseif ($order_detials->status == 'Processing' || $order_detials->status == 'Complete') {
            Session::flash('message', 'Transaction is already Completed');

            return redirect()->route('payment.view', $encryptedId);
        } else {
            Session::flash('alert', 'Invalid Transaction');

            return redirect()->route('payment.view', $encryptedId);
        }
    }

    public function cancel(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order_detials = DB::table('ssl_payments')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'amount')
            ->first();

        $ids = str_split($request->value_a);
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
            9 => 'Ei',
        ];
        $encryptedId = '';
        foreach ($ids as $key => $value) {
            $encryptedId = $encryptedId . $mapping_id_special[$value];
        }

        if ($order_detials->status == 0) {
            $update_product = DB::table('ssl_payments')
                ->where('transaction_id', $tran_id)
                ->update(['status' => 2]);
            Session::flash('alert', 'Transaction is Cancel');

            return redirect()->route('payment.view', $encryptedId);
        } elseif ($order_detials->status == 'Processing' || $order_detials->status == 'Complete') {
            Session::flash('message', 'Transaction is already Completed');

            return redirect()->route('payment.view', $encryptedId);
        } else {
            Session::flash('alert', 'Invalid Transaction');

            return redirect()->route('payment.view', $encryptedId);
        }
    }

    public function ipn(Request $request)
    {
        // Received all the payement information from the gateway
        if ($request->input('tran_id')) {
            // Check transation id is posted or not.
            $tran_id = $request->input('tran_id');

            // Check order status in order tabel against the transaction id or order id.
            $order_details = DB::table('ssl_payments')
                ->where('transaction_id', $tran_id)
                ->select('transaction_id', 'status', 'currency', 'amount')
                ->first();

            if ($order_details->status == 0) {
                $sslc = new SslCommerzNotification;
                // orderValidate signature: ($post_data, $trx_id, $amount, $currency)
                $validation = $sslc->orderValidate($request->all(), $tran_id, $order_details->amount, $order_details->currency);
                if ($validation == true) {
                    /*
                    That means IPN worked. Here you need to update order status
                    in order table as Processing or Complete.
                    Here you can also sent sms or email for successful transaction to customer
                    */
                    $this->Establish_connection($request->value_b, $request->value_a, $request->value_c, $order_details->amount);
                    $update_product = DB::table('ssl_payments')
                        ->where('transaction_id', $tran_id)
                        ->update(['status' => 1]);

                    echo 'Transaction is successfully Completed';
                } else {
                    /*
                    That means IPN worked, but Transation validation failed.
                    Here you need to update order status as Failed in order table.
                    */
                    $update_product = DB::table('ssl_payments')
                        ->where('transaction_id', $tran_id)
                        ->update(['status' => 3]);

                    echo 'validation Fail';
                }
            } elseif ($order_details->status == 'Processing' || $order_details->status == 'Complete') {
                // That means Order status already updated. No need to udate database.

                echo 'Transaction is already successfully Completed';
            } else {
                // That means something wrong happened. You can redirect customer to your product page.

                echo 'Invalid Transaction';
            }
        } else {
            echo 'Invalid Data';
        }
    }

    private function complete_payment($application_id, $amount)
    {
        $application = Application::find($application_id);
        $application->payment_status = 1;
        $application->update();

        $now = date('Y-m-d H:i:s');
        $debit = 0;
        $credit = $amount;
        $balance = 0;

        // credit add into into payment table
        $payment_values = [
            'application_id' => $application->id,
            'type' => 'SSL',
            'comment' => 'By online',
            'debit' => $debit,
            'credit' => $credit,
            'balance' => $balance,
            'created_at' => $now,
            'created_by' => $application->applicant->id,
            'created_user_type' => 'subscriber',
        ];
        DB::table('payments')->insert($payment_values);
    }

    public function paymentByAdmin(Request $request)
    {
        $application = Application::with('stickerCategory')->where('id', $request->application_id)->first();

        if (empty($application->sticker_category)) {
            Session::flash('message', 'This application have not select category.');

            return back();
        }

        $a = DB::table('ssl_payments')
            ->where('application_id', $application->id)
            ->update(['status' => 1]);

        $this->complete_payment_by_admin($application->id, $application->stickerCategory->price);
        Session::flash('message', 'This application payment by admin successfully.');

        return back();
    }

    private function complete_payment_by_admin($application_id, $amount)
    {
        $application = Application::find($application_id);
        $application->payment_status = 1;
        $application->update();

        $now = date('Y-m-d H:i:s');
        $debit = 0;
        // $credit = $amount;
        $credit = $application->discount_amount ? $application->discount_amount : $amount;
        $balance = 0;

        // credit add into into payment table

        $adminName = Auth::user() ? Auth::user()->name : 'Admin';

        $payment_values = [
            'application_id' => $application->id,
            'type' => 'SSL',
            'comment' => $adminName,
            // 'comment' => 'By Admin',
            'debit' => $debit,
            'credit' => $credit,
            'balance' => $balance,
            'created_at' => $now,
            'created_by' => $application->applicant->id,
            'created_user_type' => 'system',
            // 'created_user_type' => 'Admin',
        ];

        DB::table('payments')->insert($payment_values);

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
        } elseif ($slotFactor > 1 && $slotFactor <= 2) {
            $time = '10.30 AM';
        } elseif ($slotFactor > 2 && $slotFactor <= 3) {
            $time = '11.00 AM';
        } elseif ($slotFactor > 3 && $slotFactor <= 4) {
            $time = '11.30 AM';
        } elseif ($slotFactor > 4 && $slotFactor <= 5) {
            $time = '12.00 PM';
        } elseif ($slotFactor > 5 && $slotFactor <= 6) {
            $time = '12.30 PM';
        } elseif ($slotFactor > 6 && $slotFactor <= 7) {
            $time = '13.00 PM';
        } elseif ($slotFactor > 7 && $slotFactor <= 8) {
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
        $follow_up->status = 'Application payment';
        $follow_up->created_date = Carbon::now();
        $follow_up->comment = $final_approveSms;
        $follow_up->updater_role = $application->applicant->role;
        $follow_up->updated_by = $application->applicant->name;
        $follow_up->save();
        $queue_status = 'approve' . time();

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
            'application_id' => $application->id,
            'ip' => request()->ip(),
            'url' => request()->fullUrl(),
        ]);
    }
}
