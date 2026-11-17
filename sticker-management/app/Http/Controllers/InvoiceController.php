<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\VehicleType;
use App\Models\VehicleInfo;
use App\Models\VehicleSticker;
use Yajra\Datatables\Datatables;
use Auth;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function printInvoice($id)
    {
        $invoice = Invoice::find($id);
        return view('layouts.invoice', compact('invoice'));
    }
    public function allInvoice(Request $request)
    {
        $date_from = $request->start_inv_date ?? '';
        $date_to = $request->end_inv_date ?? '';

        $applicant_name = $request->applicant_name ?? '';
        $invoice_number = $request->invoice_number ?? '';
        $reg_no = $request->reg_no ?? '';
        $date = $request->date ?? '';
        $Vehicle_Type = $request->Vehicle_Type ?? '';
        $collector = $request->collector ?? '';
        $amount = $request->amount ?? '';

        return view('layouts.invoice-list', compact('date_from', 'date_to', 'date', 'applicant_name', 'invoice_number', 'reg_no', 'Vehicle_Type', 'collector', 'amount'));
    }

    public function invoiceDatatable(Request $request)
    {
        // $query = Invoice::with('application', 'vehicleType', 'stickerCategory');
        $query = Invoice::with('application', 'application.sslPayment:id,application_id,transaction_id', 'vehicleType', 'stickerCategory');
        $date_from = date('Y-m-d', strtotime($request->date_from));
        $date_to = date('Y-m-d', strtotime($request->date_to));

        if ($request->date_from && $request->date_to) {
            $query->whereBetween('invoice_date', [$date_from, $date_to]);
        }

        if ($request->applicant_name) {
            $applicant_ids = Applicant::where('name', 'like', '%' . $request->applicant_name . '%')->pluck('id');
            $query->whereIn('application_id', $applicant_ids);
        }

        if ($request->invoice_number) {
            $query->where('number', $request->invoice_number);
        }

        if ($request->collector) {
            $query->where('collector', $request->collector);
        }

        if ($request->reg_no) {
            $application_ids = VehicleSticker::where('reg_number', 'like', '%' . $request->reg_no . '%')->pluck('application_id');
            $query->whereIn('application_id', $application_ids);
        }

        if ($request->date) {
            $query->whereDate('created_at', date('Y-m-d', strtotime($request->date)));
        }

        if ($request->amount) {
            $query->where('amount', $request->amount);
        }

        if ($request->Vehicle_Type) {
            $vehicle_type_ids = VehicleType::where('name', 'like', '%' . $request->Vehicle_Type . '%')->pluck('id');
            $query->whereIn('vehicle_type_id', $vehicle_type_ids);
        }




        return DataTables::of($query)
            ->editColumn('invoice_number', function (Invoice $invoice) {
                return '<a href="' . url('/invoice') . '/' . $invoice->id . '" target="_blank">' . $invoice->number . '</a>';
            })
            ->addColumn('application_applicant_name', function (Invoice $invoice) {
                $applicant_name = ($invoice->application->applicant->name) ?? '';
                return $applicant_name;
            })
            ->addColumn('vehicleType_name', function (Invoice $invoice) {
                return $invoice->vehicleType->name ?? '';
            })
            ->addColumn('application_vehicleinfo_reg_number', function (Invoice $invoice) {
                return $invoice->application->vehicleinfo->reg_number ?? '';
            })
            ->addColumn('stickerCategory_value', function (Invoice $invoice) {
                return $invoice->stickerCategory->value ?? '';
            })
            ->addColumn('transaction_id', function (Invoice $invoice) {
                return $invoice->application->sslPayment->transaction_id ?? 'N/A';
            })
            ->addColumn('invoice_date', function (Invoice $invoice) {
                return date('d-m-Y', strtotime($invoice->created_at));
            })
            // ->addColumn('action', function (Invoice $invoice) {
            //     if (Auth::user()->role == 'super-admin') {
            //         return '<button class=" btn btn-info">Edit</button><button class=" btn btn-danger">delete</button>';
            //     } else {
            //         return "";
            //     }
            // })
            // ->addColumn('action', function ($query) {
            //     $url = route('applications.invoice', $query->application->id);

            //     return '<a href="' . $url . '" target="_blank" class="btn btn-sm btn-primary">
            //                 Invoice
            //             </a>';
            // })
            ->addColumn('action', function ($query) {
                $buttons = '';

                // Add Invoice button
                if ($query->application && $query->application->id) {
                    $url = route('applications.invoice', $query->application->id);
                    $buttons .= '<a href="' . $url . '" target="_blank" class="btn btn-sm btn-primary">
                        Invoice
                     </a>';
                }

                // Add Edit and Delete buttons for super-admin
                if (Auth::user()->role == 'super-admin') {
                    $buttons .= ' <button class="btn btn-sm btn-info">Edit</button>';
                    $buttons .= ' <button class="btn btn-sm btn-danger">Delete</button>';
                }

                return $buttons;
            })
            ->rawColumns(['action', 'invoice_number', 'application_applicant_name', 'transaction_id', 'action'])
            ->toJson();
    }

    public function searchInvoice(Request $request)
    {

        if ($request->start_inv_date != '') {
            $start = date('Y-m-d', strtotime($request->start_inv_date));
        }
        if ($request->end_inv_date != '') {
            $end = date('Y-m-d', strtotime($request->end_inv_date));
        }
        if ($request->start_inv_date != '' && $request->end_inv_date != '') {
            $invoices = Invoice::whereBetween('invoice_date', [$start, $end])->orderBy('created_at', 'desc')->get();
        } elseif ($request->start_inv_date != '') {
            $invoices = Invoice::whereDate('invoice_date', $start)->orderBy('created_at', 'desc')->get();
        } elseif ($request->end_inv_date != '') {
            $invoices = Invoice::whereDate('invoice_date',  $end)->orderBy('created_at', 'desc')->get();
        }
        return view('layouts.invoice-list', compact('invoices'))
            ->with('date_from', $request->start_inv_date)
            ->with('date_to', $request->end_inv_date);
    }
    // public function invoiceReport()
    // {
    //     $invoices = Invoice::groupBy('vehicle_type_id')->get();
    //     return view('layouts.invoice-report', compact('invoices'));
    // }
    // public function searchInvoiceReport(Request $request)
    // {
    //     $vehicle = '';
    //     if ($request->start_inv_date != '') {
    //         $start = date('Y-m-d', strtotime($request->start_inv_date));
    //     }
    //     if ($request->end_inv_date != '') {
    //         $end = date('Y-m-d', strtotime($request->end_inv_date));
    //     }
    //     if ($request->start_inv_date != '' && $request->end_inv_date != '' && $request->vehicle_type == '') {
    //         $invoices = Invoice::whereBetween('invoice_date', [$start, $end])->orderBy('created_at', 'desc')->groupBy('vehicle_type_id')->get();
    //     } elseif ($request->start_inv_date != '' && $request->end_inv_date == '' && $request->vehicle_type == '') {
    //         $invoices = Invoice::whereDate('invoice_date', $start)->orderBy('created_at', 'desc')->groupBy('vehicle_type_id')->get();
    //     } elseif ($request->end_inv_date != '' && $request->start_inv_date == '' && $request->vehicle_type == '') {
    //         $invoices = Invoice::whereDate('invoice_date', $end)->orderBy('created_at', 'desc')->groupBy('vehicle_type_id')->get();
    //     } elseif ($request->vehicle_type != '' && $request->start_inv_date == '' && $request->end_inv_date == '') {
    //         $invoices = Invoice::where('vehicle_type_id',  $request->vehicle_type)->orderBy('created_at', 'desc')->groupBy('vehicle_type_id')->get();
    //     } elseif ($request->vehicle_type != '' && $request->start_inv_date != '' && $request->end_inv_date != '') {
    //         $invoices = Invoice::whereBetween('invoice_date', [$start, $end])->where('vehicle_type_id',  $request->vehicle_type)->groupBy('vehicle_type_id')->orderBy('created_at', 'desc')->get();
    //     } elseif ($request->vehicle_type != '' && $request->start_inv_date != '' && $request->end_inv_date == '') {
    //         $invoices = Invoice::whereDate('invoice_date', $start)
    //             ->where('vehicle_type_id', $request->vehicle_type)->groupBy('vehicle_type_id')
    //             ->orderBy('created_at', 'desc')->get();
    //     } elseif ($request->vehicle_type != '' && $request->start_inv_date == '' && $request->end_inv_date != '') {
    //         $invoices = Invoice::whereDate('invoice_date', $end)
    //             ->where('vehicle_type_id', $request->vehicle_type)->groupBy('vehicle_type_id')
    //             ->orderBy('created_at', 'desc')->get();
    //     }

    //     $vehicle = VehicleType::where('id', $request->vehicle_type)->first();
    //     return view('layouts.invoice-report', compact('invoices'))
    //         ->with('date_from', $request->start_inv_date)
    //         ->with('date_to', $request->end_inv_date)
    //         ->with('vehicle_type', $vehicle);
    // }


    public function invoiceReport() 
{ 
    $invoices = Invoice::select('vehicle_type_id')
        ->selectRaw('COUNT(*) as total_count')
        ->selectRaw('SUM(net_amount) as total_amount')
        ->with('vehicleType')
        ->groupBy('vehicle_type_id')
        ->get();
        
    return view('layouts.invoice-report', compact('invoices')); 
}

public function searchInvoiceReport(Request $request) 
{ 
    $vehicle = null;
    $start = $request->start_inv_date ? date('Y-m-d', strtotime($request->start_inv_date)) : null;
    $end = $request->end_inv_date ? date('Y-m-d', strtotime($request->end_inv_date)) : null;

    $query = Invoice::select('vehicle_type_id')
        ->selectRaw('COUNT(*) as total_count')
        ->selectRaw('SUM(net_amount) as total_amount')
        ->with('vehicleType')
        ->groupBy('vehicle_type_id')
        ->orderBy('created_at', 'desc');

    if ($start && $end) {
        $query->whereBetween('invoice_date', [$start, $end]);
    } elseif ($start) {
        $query->whereDate('invoice_date', $start);
    } elseif ($end) {
        $query->whereDate('invoice_date', $end);
    }

    if ($request->vehicle_type) {
        $query->where('vehicle_type_id', $request->vehicle_type);
    }

    $invoices = $query->get();
    $vehicle = $request->vehicle_type 
        ? VehicleType::where('id', $request->vehicle_type)->first() 
        : null;

    return view('layouts.invoice-report', compact('invoices'))
        ->with('date_from', $request->start_inv_date)
        ->with('date_to', $request->end_inv_date)
        ->with('vehicle_type', $vehicle);
}

    public function invoiceReceipt($id)
    {
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
    }
}
