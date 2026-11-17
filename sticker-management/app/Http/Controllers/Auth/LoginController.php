<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use DateTime;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    protected $redirectTo_other = '/checking';


    protected $maxAttempts = 3;
    protected $decayMinutes = 2;
    protected $passwordField = 'password';



    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        // This section is the only change
        if ($this->guard()->validate($this->credentials($request))) {
            $user = $this->guard()->getLastAttempted();

            // Make sure the user is active
            if ($user->active && $this->attemptLogin($request)) {
                // Send the normal successful login response
                return $this->sendLoginResponse($request);
            } else {

                // Increment the failed login attempts and redirect back to the
                // login form with an error message.
                if ($user->status == 'active' || $user->status == null) {
                    $this->incrementLoginAttempts($request);

                    $user = User::findOrFail($user->id);
                    
                    $user->verification_code = random_int(10, 1000000);
                    $user->verification_time_start = date("Y-m-d H:i:s");
                    $user->update();
                    $sms = "আপনার ভেরিফিকেশন কোডঃ " . $user->verification_code . " । ধন্যবাদ। ";
                    HomeController::callSmsApi($user->mobile_number, $sms);
                    session()->put('verified', false);
                    session()->put('admin', $user);
                    return redirect()
                        ->back()
                        ->withInput($request->only($this->username(), 'remember'))
                        ->withErrors(['active' => 'You must be verified to login.']);
                } else {
                    // If the user is not active, we can redirect them back with an error
                    return view('customer-auth.inactive_member');
                }
            }
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }


    public function verifyLogin(Request $req)
    {
        $user = session()->get('admin');

        if (!$user) {
            return redirect()->route('login');
        }

        $ip = $req->ip();

        $attemptKey = 'admin_verify_attempts_' . $ip . '_' . $user->id;
        $blockKey   = 'admin_verify_block_'    . $ip . '_' . $user->id;

        // 🚫 Already blocked
        if (Cache::has($blockKey)) {
            session()->forget('verified');
            session()->forget('admin');

            return redirect()->route('login')->with([
                'blocked'          => true,
                'block_expires_at' => Cache::get($blockKey . '_expires'),
                'message'          => 'You are blocked. Try again later.',
            ]);
        }

        $attempts = Cache::get($attemptKey, 0);

        // ⏱️ Check OTP expiry (600 seconds = 10 min)
        $now_date = date("Y-m-d H:i:s");
        $diff = (new DateTime($now_date))->getTimestamp()
            - (new DateTime($user->verification_time_start))->getTimestamp();

        if ($diff > 600) {
            Cache::forget($attemptKey);
            session()->forget('verified');
            session()->forget('admin');
            session()->flash('message', 'Verification Time Expired. Please log in again.');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('login');
        }

        // ✅ Correct OTP
        if ($req->verify_code == $user->verification_code) {
            Cache::forget($attemptKey);

            $user = User::findOrFail($user->id);
            Auth::guard('web')->login($user);
            session()->forget('verified');
            session()->forget('admin');

            if ($user->role == 'admin' || $user->role == 'super-admin' || $user->role == 'other') {
                return redirect($this->redirectTo);
            } else {
                return redirect($this->redirectTo_other);
            }
        }

        // ❌ Wrong OTP — increment attempts
        $attempts++;
        Cache::put($attemptKey, $attempts, Carbon::now()->addMinutes(5));

        if ($attempts >= 3) {
            $blockDuration = Carbon::now()->addMinutes(5);

            Cache::put($blockKey, true, $blockDuration);
            Cache::put($blockKey . '_expires', $blockDuration->timestamp, $blockDuration);
            Cache::forget($attemptKey);

            session()->forget('verified');
            session()->forget('admin');

            return redirect()->route('login')->with([
                'blocked'          => true,
                'block_expires_at' => $blockDuration->timestamp,
                'message'          => 'Too many failed attempts. You are blocked for 10 minutes.',
            ]);
        }

        $remaining = 3 - $attempts;
        session()->flash('message', "Invalid code. {$remaining} attempt(s) remaining.");
        Session::flash('alert-class', 'alert-danger');
        return redirect()->back();
    }


    // public function verifyLogin(Request $req)
    // {

    //     $user = session()->get('admin');

    //     $now_date = date("Y-m-d H:i:s");
    //     $date = new DateTime($user->verification_time_start);
    //     $date2 = new DateTime($now_date);
    //     $diff = $date2->getTimestamp() - $date->getTimestamp();


    //        if ($diff <= 600) {
    //         if ($req->verify_code == $user->verification_code) {
    //             $user = User::findOrFail($user->id);
    //             Auth::guard('web')->login($user);
    //             session()->forget('verified');
    //             session()->forget('admin');
    //             // Role wise redirect
    //             if ($user->role == 'admin' || $user->role == 'super-admin') {
    //                 return redirect($this->redirectTo);
    //             } else {
    //                 return redirect($this->redirectTo_other); // default
    //             }
    //             // return redirect($this->redirectTo);
    //         } else {
    //             session()->flash('message', 'invalid code');
    //             Session::flash('alert-class', 'alert-danger');
    //             return redirect()->back();
    //         }
    //     } else {
    //         session()->forget('verified');
    //         session()->forget('admin');
    //         session()->flash('message', 'Verification Time Expired');
    //         Session::flash('alert-class', 'alert-danger');
    //         return redirect()->back();
    //     }
    // }

    protected function credentials(Request $request)
    {
        $login = $request->input('login');

        // Decide if it's an email or mobile_number
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile_number';

        return [
            $field => $login,
            $this->passwordField => $request->input($this->passwordField),
        ];
    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            'login' => 'required|string',
            $this->passwordField => 'required|string',
        ]);
    }


    protected function guard()
    {
        return auth()->guard('web');
    }
}
