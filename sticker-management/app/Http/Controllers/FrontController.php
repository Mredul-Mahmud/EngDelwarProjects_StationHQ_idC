<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Application;
use App\Models\ExComander;
use App\Models\FixedFile;
use App\Models\HomePage;
use App\Models\Notice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Major;
use App\Models\Slider;
use App\Models\PresentCommander;
use App\Models\PhotoGallery;
use App\Models\PdfFile;
use App\Models\Slogan;
use App\Models\HeaderFooter;
use DB;
use App\Models\ExSso;

class FrontController extends Controller
{
    public function index(Request $request)
    {
        // return "Website updating.................";
        $front_sliders = Slider::orderBy('position', 'asc')->where('status', 1)->get();
        $present_commander = PresentCommander::first();
        $slogan = Slogan::first();

        $majors = Major::orderBy('position','asc')->where('status',1)->get();
        $photo_galleries = PhotoGallery::orderBy('position','asc')->where('status',1)->get();
         $header_footer = HeaderFooter::first();
		 
		  $pdf_files_notice = PdfFile::orderBy('position', 'desc')->where('type', 'notice')->where('status', 1)->take(2)->get();
        // dd( $pdf_files_notice);
        $pdf_files_policy = PdfFile::orderBy('position', 'desc')->where('type', 'policy')->where('status', 1)->take(2)->get();
        // dd( $pdf_files_policy);
        $homePage = HomePage::get();
        // dd( $homePage);
		 
        return view('front-pages.welcome', compact('front_sliders', 'present_commander', 'slogan', 'majors', 'photo_galleries', 'header_footer', 'pdf_files_notice', 'pdf_files_policy', 'homePage'));
    }

    public function noticeDetails($slug)
    {
        $notice = Notice::with('files')->where('slug',$slug)->first();
        if(!$notice)
            abort('404');

        return view('front-pages.notice_details',compact('notice'));
    }

    public function about(Request $request)
    {
        $present_commander = PresentCommander::first();
        return view('front-pages.about-station-headquarters', compact('present_commander'));
    }

    public function major(Request $request,$id, $name)
    {
        $major = Major::where('id',$id)->first();
        return view('officers.major', compact('major'));
    }

    public function policy(Request $request)
    {
        $title = "Sticker Policy";
        $fixed_file = FixedFile::first()? FixedFile::first()->sticker_policy:'';
        // dd($url);
        return view('front-pages.fixed_file', compact('title', 'fixed_file'));
    }
    public function graveyard(Request $request)
    {
        $title = "Graveyard";
        $fixed_file = FixedFile::first() ? FixedFile::first()->graveyard_allotment : '';
        return view('front-pages.fixed_file', compact('title', 'fixed_file'));
    }
    public function bus(Request $request)
    {
        $title = "School Bus Service";
        $fixed_file = FixedFile::first() ? FixedFile::first()->bus_service : '';
        return view('front-pages.fixed_file', compact('title', 'fixed_file'));
    }
    public function private(Request $request)
    {
        $title = "Private Service";
        $fixed_file = FixedFile::first() ? FixedFile::first()->private_car : '';
        return view('front-pages.fixed_file', compact('title', 'fixed_file'));
    }
    public function anti_malaria_drive(Request $request)
    {
        $title = "Anti malaria Drive";
        $fixed_file = FixedFile::first() ? FixedFile::first()->anti_malaria_drive : '';
        return view('front-pages.fixed_file', compact('title', 'fixed_file'));
    }
    public function drainage_and_cleaning(Request $request)
    {
        $title = "Drainage and Cleaning";
        $fixed_file = FixedFile::first() ? FixedFile::first()->drainage_and_cleaning : '';
        return view('front-pages.fixed_file', compact('title', 'fixed_file'));
    }
    public function disposal_of_garbage(Request $request)
    {
        $title = "Disposal of Garbage";
        $fixed_file = FixedFile::first() ? FixedFile::first()->disposal_of_garbage : '';
        return view('front-pages.fixed_file', compact('title', 'fixed_file'));
    }
    public function mp_checkpost(Request $request)
    {
        $title = "MP Checkpost";
        $fixed_file = FixedFile::first() ? FixedFile::first()->mp_checkpost : '';
        return view('front-pages.fixed_file', compact('title', 'fixed_file'));
    }
    public function shuttle_service(Request $request)
    {
        $title = "Shuttle Service";
        $fixed_file = FixedFile::first() ? FixedFile::first()->shuttle_service : '';
        return view('front-pages.fixed_file', compact('title', 'fixed_file'));
    }
    public function other_services(Request $request)
    {
        $title = "Other Service";
        $fixed_file = FixedFile::first() ? FixedFile::first()->other_services : '';
        return view('front-pages.fixed_file', compact('title', 'fixed_file'));
    }
    public function contact_info(Request $request)
    {
        $title = "General Contact Info";
        $fixed_file = FixedFile::first() ? FixedFile::first()->contact_info : '';
        return view('front-pages.fixed_file', compact('title', 'fixed_file'));
    }
    public function helpline(Request $request)
    {
        $title = "Helpline";
        $fixed_file = FixedFile::first() ? FixedFile::first()->helpline : '';
        return view('front-pages.fixed_file', compact('title', 'fixed_file'));
    }

    public function previousRemarkUpdate(Request $request)
    {
        $applicants = Applicant::all();
        $counter = 0;
        // dd($applicants);
        foreach ($applicants as $key => $applicant) {
            $applications = Application::where('applicant_id', $applicant->id)->get();
            foreach ($applications as $key => $application) {
                $application->remark = $applicant->applicantDetail?$applicant->applicantDetail->applicant_remark:'';
                $application->save();
                $counter++;
            }
        }
        return "Total " . $counter . " Application updated.";
    }


    public function gallery()
    {
        $photo_galleries = PhotoGallery::orderBy('position', 'asc')->where('status', '1')->get();
          $front_sliders = Slider::orderBy('position', 'asc')->where('status', 1)->get();

        return view('front-pages.gallery', compact('photo_galleries', 'front_sliders'));
    }

    public function staf()
    {

        $present_commander = PresentCommander::first();
        $majors = Major::orderBy('position', 'asc')->where('status', '1')->get();
        return view('front-pages.staf', compact('present_commander', 'majors'));
    }
   public function history()
    {

        $present_commander = PresentCommander::first();
        $exComds = ExComander::where('status', '1')->get();    
         $exSsos = ExSso::where('status', '1')->get();

        return view('front-pages.history', compact('present_commander', 'exComds', 'exSsos'));
    }
	
    public function notice()
    {
        return view('layouts.sidebar');
    }

     public function policyForms()
    {
        return view('layouts.policy_forms');
    }

    public function gate_on_of_time(Request $request)
    {
        $title = "Gate On/Off Time";
        $fixed_file = FixedFile::first() ? FixedFile::first()->other_services : '';
        return view('front-pages.fixed_file', compact('title', 'fixed_file'));
    }

     public function commender()
    {
        $present_commander = PresentCommander::first();
        return view('front-pages.commander_details', compact('present_commander'));
    }

}
