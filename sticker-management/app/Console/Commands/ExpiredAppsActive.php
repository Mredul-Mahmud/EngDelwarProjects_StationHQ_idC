<?php

namespace App\Console\Commands;

use App\Http\Controllers\HomeController;
use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\VehicleSticker;
use App\Models\Application;
use App\Models\Sms;
use App\Models\SmsApplicant;
use App\Models\FollowUp;
use DB;
use Mail;
use App\Mail\notifyExpiredMail;
use App\Mail\notifyWarningMail;

class ExpiredAppsActive extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'ExpiredAppsActive:expireStatus';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Do Issued status to Expired';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
    $a = 1;
    $this->info('Sending...');

    $data = DB::table('vehicle_stickers')
      ->where(function ($query) {
        $query->whereDate('exp_date', '<', '2026-01-01')
          ->whereIn('sticker_value', ['e8'])
          ->whereYear('issue_date', 2025)
          ->whereNull('send_sms');
      })

      //    ->where(function ($query) {
      //     $query->whereDate('exp_date', '<', '2025-01-01')
      //       ->whereIn('sticker_value', ['D1', 'D2', 'A3', 'A4', 'B1', 'B2', 'B3', 'B4', 'T', 'T1', 'M', 'E'])
      //       ->whereYear('issue_date', 2025)
      //   ->whereNull('send_sms');
      //   })
      //   ->Where(function ($query) {
      //     $query->whereDate('exp_date', '<', '2025-01-01')
      //       ->whereIn('sticker_value', ['A1',])
      //       ->whereYear('issue_date', 2024)
      //   ->whereNull('send_sms');
      //   })
      ->limit(200)
      ->get();
    // ->count();

    $this->info('Total to send: ' . $data);


    foreach ($data as $key => $sticker) {

      $app = Application::findOrFail($sticker->application_id);
      $app->app_status = "warning";
      $app->renew = "renew-warned";
      $app->save();
      $stick = VehicleSticker::findOrFail($sticker->id);
      $stick->sms_exp_warn = "warned";
      $stick->sms_exp_expired = "will be expired";
      $stick->send_sms = '1';
      $stick->save();
      $sms = Sms::where('type', 'warning')->first();
      $sms_add_reg = str_replace('/reg/', $sticker->reg_number, $sms->sms_text);
      $sms_final = str_replace('/date/', $sticker->exp_date, $sms_add_reg);
      $this->info($sms_final);
        HomeController::callSmsApi($stick->applicant->phone, $sms_final);

      $this->info('sms send' . $a++);
    }

    $this->info('done' . $a);
  }
}
