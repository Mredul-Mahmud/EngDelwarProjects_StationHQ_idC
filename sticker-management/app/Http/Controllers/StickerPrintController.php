<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\StickerCategory;
use App\Models\VehicleSticker;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StickerPrintController extends Controller
{
    public function index($app_number)
    {
        $app = Application::where('app_number', $app_number)->first();
        //  $stickercategory = StickerCategory::where('value', $app->sticker_category)->first();

        $app->print_status = "Printed";
        $app->print_by = Auth::user()->name;
        $app->save();

         $category = $app->sticker_category;

        if (in_array($category, ['E', 'e1', 'e2', 'e3', 'e4', 'e5', 'e6', 'e7', 'e8'])) {
            $category = 'E';
        }

        switch ($category) {
            case 'A1':
                return view('sticker.sticker_design.a1', compact('app'));
            case 'A2':
                return view('sticker.sticker_design.a2', compact('app'));
            case 'A3':
                return view('sticker.sticker_design.a3', compact('app'));
            case 'A4':
                return view('sticker.sticker_design.a4', compact('app'));
            case 'A4 Without Staff Gate':
                return view('sticker.sticker_design.a4_staff', compact('app'));
            case 'B1':
                return view('sticker.sticker_design.b1', compact('app'));
            case 'B2':
                return view('sticker.sticker_design.b2', compact('app'));
            case 'B3':
                return view('sticker.sticker_design.b3', compact('app'));
            case 'B4':
                return view('sticker.sticker_design.b4', compact('app'));
            case 'B5':
                return view('sticker.sticker_design.b5', compact('app'));
            case 'D1':
                return view('sticker.sticker_design.d1', compact('app'));
            case 'D2':
                return view('sticker.sticker_design.d2', compact('app'));
            case 'E':
                return view('sticker.sticker_design.e', compact('app'));
            case 'F':
                return view('sticker.sticker_design.f', compact('app'));
            case 'M':
                return view('sticker.sticker_design.m', compact('app'));
            case 'M1':
                return view('sticker.sticker_design.m1', compact('app'));
            case 'T':
                return view('sticker.sticker_design.t', compact('app'));
            case 'T1':
                return view('sticker.sticker_design.t1', compact('app'));
            case 'Ex':
          return view('sticker.sticker_design.x_army_chief', compact('app'));
         default:
            return ;
        }
        // return view('sticker.print', compact('app'));
    }

    public function showByQr($app_number)
    {
        // $app = Application::where('id', $app_number)->first();
        $app = Application::where('app_number', $app_number)->first();

       $vehicleType = VehicleSticker::where('application_id', $app->id)->first();
        // dd( $vehicleType);


        $stickerPrice = null;
        $stickerCategory = StickerCategory::where('value', $app->sticker_category)->first();
        if (!empty($stickerCategory)) {
            if ($stickerCategory->hasMultiVehicleType == 1) {
                $stickerPrice = DB::SELECT("SELECT vehicle_type_wise_sticker_price.price FROM vehicle_type_wise_sticker_price,sticker_categories WHERE sticker_categories.id=sticker_categorie_id AND sticker_categories.value='" . $app->sticker_category . "' AND vehicle_type_id='" . $app->vehicleinfo->vehicle_type_id . "'");
                if (!empty($stickerPrice)) {
                    $stickerPrice = $stickerPrice[0]->price;
                } else {
                    $stickerPrice = 0;
                }
            } else {



                $stickerPrice = $stickerCategory->price;
            }
        }
        return view('qr_show', compact('app',  'stickerPrice', 'stickerCategory', 'vehicleType'));
        // অথবা json return করতে চাইলে:
        // return response()->json($application);
    }
    // Print All Sticker
    public function printAllStickers(Request $request)
    {
        $ids = $request->ids;
        // Fetch all applications
        $applications = Application::whereIn('id', $ids)->get();
        // Update Application Print Status
        Application::whereIn('id', $ids)->update([
            // 'print_status' => 'Printed',
            'print_by' => Auth::user()->name,
        ]);
        return view('sticker.print_all', compact('applications'));
    }



    public function setAllStickerPrintDate(Request $request)
        {
        // dd($request->all());
          $stickerDate =  Application::whereIn('id', $request->ids)
                ->update([
                    'sticker_print_date' => $request->print_date,
                     'print_status' => 'Printed',
                ]);

            return response()->json([
                'status' => true,
                'message' => 'Print date updated successfully.',
                'stickerDate' =>  $stickerDate 
            ]);
        }
}
