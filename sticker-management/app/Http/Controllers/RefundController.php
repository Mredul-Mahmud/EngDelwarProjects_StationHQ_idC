<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\FollowUp;
use App\Models\Payment;
use App\Models\Refund;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RefundController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($application_id)
    {
        $data['application_id'] = $application_id;
        return view('refund.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function store(Request $request)
    {
        
        DB::beginTransaction();
        try {
            $application = Application::find($request->application_id);
    
            $ssl_payment = DB::table('ssl_payments')->where('application_id', $request->application_id)->first();
            $payment = Payment::where('application_id', $request->application_id)->first();

            if ($application && $ssl_payment && $payment) {

                $follow_up = new FollowUp();
                $follow_up->application_id = $request->application_id;
                $follow_up->app_status = 'refund';
                $follow_up->status = "Application refund";
                $follow_up->created_date = Carbon::now();
                // $follow_up->comment = $final_approveSms;
                $follow_up->updater_role = auth()->user()->role;
                $follow_up->updated_by = auth()->user()->name;
                $follow_up->save();
    
                $refund = new Refund();
                $refund->application_id = $request->application_id;
                $refund->transaction_id = $ssl_payment->transaction_id;
                $refund->reason = $request->reason;
                $refund->refund_amout = $ssl_payment->amount;
                $refund->refund_by = $request->refund_by ?? auth()->user()->name;
                $refund->save();
    
                $application->payment_status = 0;
                $application->app_status = 'refund';
                $application->update();
    
                $payment->delete();
                
            }
            
            DB::commit();
            return redirect()->route('application.review', ['app_number' => $application->app_number])->with('message','Refund successfully.');

        } catch (Exception $th) {
            DB::rollback();
            return back()->with('fail', 'Something is worng please try again.');
        }
    }
}
