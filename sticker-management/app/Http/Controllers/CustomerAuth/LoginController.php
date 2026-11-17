<?php

namespace App\Http\Controllers\CustomerAuth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use DateTime;
use Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Applicant;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;




class LoginController extends Controller
{
    protected $phone = 'phone';

    protected $maxAttempts = 3; // Default is 5
    protected $decayMinutes = 2; // Default is 1


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
    public function username()
    {
        return 'phone';
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/customer/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    //    public function __construct()
    //    {
    //        $this->middleware('guest')->except('logout');
    //    }
    protected function guard()
    {
        return auth()->guard('applicant');
    }
    public function showLoginForm()
    {
        return view('customer-auth.login');
    }
    public function login(\Illuminate\Http\Request $request)
    {
        $this->validateLogin($request);
        //  dd( session()->all());
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.


        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        $user = Applicant::where('phone', $request->phone)->first();

        // Mobile number not found
        if (!$user) {
            return back()
                ->withInput($request->only('phone'))
                ->withErrors(['phone' => 'User not found with this mobile number.']);
        }

        // Password incorrect
        if (!Hash::check($request->password, $user->password)) {
            return back()
                ->withInput($request->only('phone'))
                ->withErrors(['password' => 'Your password is incorrect.']);
        }
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        // This section is the only change
        if ($this->guard()->validate($this->credentials($request))) {
            $user = $this->guard()->getLastAttempted();

            // Make sure the user is active
            // if ($user->active && $this->attemptLogin($request)) {
            //     // Send the normal successful login response
            //     return $this->sendLoginResponse($request);
            // } else {

            // Increment the failed login attempts and redirect back to the
            // login form with an error message.
            if ($user->status == 'active' || $user->status == null) {
                $this->incrementLoginAttempts($request);

                $applicant = Applicant::findOrFail($user->id);
                
                $applicant->verification_code = random_int(100000, 999999);
                // $applicant->verification_code = rand(10, 1000000);
                $applicant->verification_time_start = date("Y-m-d H:i:s");
                // $applicant->verification_expires_at  = now()->addMinutes(5);
                $applicant->update();
                $sms = "আপনার ভেরিফিকেশন কোডঃ " . $applicant->verification_code . " । ধন্যবাদ। ";
                HomeController::callSmsApi($applicant->phone, $sms);
                session()->put('verified', false);
                session()->put('user', $applicant);
                return redirect()
                    ->back()
                    ->withInput($request->only($this->username(), 'remember'))
                    ->withErrors(['active' => 'You must be verified to login.']);
            } else {
                // If the user is not active, we can redirect them back with an error
                return view('customer-auth.inactive_member')
                    ->with('message', 'You are not an active user. Please contact the station headquarters in Dhaka to activate your account.')
                    ->with('alert-class', 'alert-danger');
            }

            // }
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }


    public function verifyLogin(Request $req)
    {
        $user = session()->get('user');

        if (!$user) {
            return redirect()->route('customer.login');
        }

        $ip = $req->ip();

        $attemptKey = 'verify_attempts_' . $ip . '_' . $user->id;
        $blockKey   = 'verify_block_'    . $ip . '_' . $user->id;
        // $strikeKey  = 'verify_strike_'   . $ip . '_' . $user->id;

        // 🚫 Already blocked — use 'blocked' key so Blade shows the blocked section
        if (Cache::has($blockKey)) {
            session()->forget('verified');
            session()->forget('user');
            return redirect()->route('customer.login')->with([
                'blocked' => true,
                'message' => 'You are blocked. Try again later.',
            ]);
        }

        $attempts = Cache::get($attemptKey, 0);

        // ⏱️ Check expiry first
        $now_date = date("Y-m-d H:i:s");
        $diff = (new DateTime($now_date))->getTimestamp()
            - (new DateTime($user->verification_time_start))->getTimestamp();

        if ($diff > 100) {
            Cache::forget($attemptKey);
            session()->forget('verified');
            session()->forget('user');
            session()->flash('message', 'Verification time expired. Please log in again.');
            session()->flash('alert-class', 'alert-danger');
            return redirect()->route('customer.login');
        }

        // ✅ Correct OTP
        if ($req->verify_code == $user->verification_code) {
            Cache::forget($attemptKey);
            // Cache::forget($strikeKey);

            $applicant = Applicant::findOrFail($user->id);
            $applicant->active = 1;
            $applicant->update();

            Auth::guard('applicant')->login($user);
            session()->forget('verified');
            session()->forget('user');

            return redirect($this->redirectTo);
        }

        // ❌ Wrong OTP — increment attempts
        $attempts++;
        Cache::put($attemptKey, $attempts, Carbon::now()->addMinutes(10));

        if ($attempts >= 3) {
            // ✅ Always 10 minute block — no strike logic
            $blockDuration = Carbon::now()->addMinutes(10);

            Cache::put($blockKey, true, $blockDuration);
            Cache::put($blockKey . '_expires', $blockDuration->timestamp, $blockDuration);
            Cache::forget($attemptKey);

            session()->forget('verified');
            session()->forget('user');

            return redirect()->route('customer.login')->with([
                'blocked'          => true,
                'block_expires_at' => $blockDuration->timestamp,
                'message'          => 'Too many failed attempts. You are blocked for 10 minutes.',
            ]);
        }

        $remaining = 3 - $attempts;
        session()->flash('message', "Invalid code. {$remaining} attempt(s) remaining.");
        session()->flash('alert-class', 'alert-danger');
        return redirect()->back();
    }




    public function logout()
    {
        $applicant = Applicant::findOrFail(auth()->guard('applicant')->user()->id);
        $applicant->active = 0;
        $applicant->update();
        Auth::guard('applicant')->logout();
        session()->regenerate();
        session()->forget('verified');
        return redirect('/customer/login');
    }
}
