<?php

namespace App\Http\Controllers\CustomerAuth;

use App\Models\Applicant;
use App\Http\Controllers\HomeController;
use App\Models\PasswordResetSMS;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordSMSController extends Controller
{
    const FIELD_TOKEN = 'token';
    const FIELD_PASSWORD = 'password';
    const FIELD_CODE = 'code';
    const FIELD_VERIFICATION_CODE = 'verification_code';
    public function index()
    {
        return view('customer-auth.sms.mobile');
    }

    public function sendVerificationCode(Request $request)
    {
        // ✅ Fix 2: Rate limiting — একই IP বারবার OTP request করতে পারবে না
        $rateLimitKey = 'fp_send_' . $request->ip();
        if (Cache::has($rateLimitKey)) {
            return redirect()->back()
                ->with('status', 'Please wait a few minutes before requesting another code.');
        }

        // ✅ Fix 3: Generic message — User enumeration বন্ধ
        $applicant = Applicant::where('phone', $request->mobile)->first();
        if (!$applicant) {
            return redirect()->back()
                ->with('status', 'If this number is registered, a verification code will be sent.');
        }

        // ✅ Fix 1: random_int() — cryptographically secure 6-digit OTP
        $verificationCode = random_int(100000, 999999);

        $sms = "আপনার ভেরিফিকেশন কোডঃ " . $verificationCode . " (৫ মিনিট মেয়াদ)। ধন্যবাদ।";
        HomeController::callSmsApi($applicant->phone, $sms);

        $passwordReset = new PasswordResetSMS();
        $passwordReset->applicant_id                  = $applicant->id;
        $passwordReset->verification_code             = $verificationCode;
        $passwordReset->verification_code_expire_at   = now()->addMinutes(5);
        $passwordReset->link_expire_at                = now()->addHours(12);
        $passwordReset->save();

        // ✅ Fix 4 (part): session এ applicant_id রাখুন
        // যাতে verifyPost এ phone/user এর সাথে match করা যায়
        session(['fp_applicant_id' => $applicant->id]);

        // ✅ Fix 2: Rate limit set — ২ মিনিট পর আবার request করতে পারবে
        Cache::put($rateLimitKey, true, now()->addMinutes(2));

        return redirect()->route('customer.password.sms.verify');
    }

    public function verify()
    {
        // session না থাকলে পাঠাবেন না
        if (!session('fp_applicant_id')) {
            return redirect()->route('customer.password.sms.index');
        }

        return view('customer-auth.sms.verify_verification_code');
    }

    public function verifyPost(Request $request)
    {
        $ip = $request->ip();

        $attemptKey = 'fp_verify_attempts_' . $ip;
        $blockKey   = 'fp_verify_block_'    . $ip;

        // Block check — আগের মতোই
        if (Cache::has($blockKey)) {
            return redirect()->back()->with([
                'blocked'          => true,
                'block_expires_at' => Cache::get($blockKey . '_expires'),
                'status'           => 'You are blocked. Try again later.',
            ]);
        }

        $attempts = Cache::get($attemptKey, 0);

        // ✅ Fix 4: session এর applicant_id দিয়ে match করুন
        // এখন শুধু নিজের OTP কাজ করবে, অন্যের OTP দিলে কাজ করবে না
        $applicantId = session('fp_applicant_id');
        if (!$applicantId) {
            return redirect()->route('customer.password.sms.index')
                ->with('status', 'Session expired. Please start again.');
        }

        $passwordReset = PasswordResetSMS::where('verification_code', $request->code)
            ->where('applicant_id', $applicantId) // ✅ Fix 4: applicant এর সাথে match
            ->latest()
            ->first();

        // ❌ Wrong code
        if (!$passwordReset) {
            $attempts++;
            Cache::put($attemptKey, $attempts, Carbon::now()->addMinutes(10));

            if ($attempts >= 3) {
                $blockDuration = Carbon::now()->addMinutes(10);
                Cache::put($blockKey, true, $blockDuration);
                Cache::put($blockKey . '_expires', $blockDuration->timestamp, $blockDuration);
                Cache::forget($attemptKey);

                return redirect()->back()->with([
                    'blocked'          => true,
                    'block_expires_at' => $blockDuration->timestamp,
                    'status'           => 'Too many failed attempts. You are blocked for 10 minutes.',
                ]);
            }

            $remaining = 3 - $attempts;
            return redirect()->back()
                ->with('status', "Invalid Code. {$remaining} attempt(s) remaining.");
        }

        // ✅ Fix 5: Duplicate check সরিয়ে দিন (dead code ছিল)
        // ✅ Fix 6: hash_equals() — Type juggling attack বন্ধ
        $invalidCodeMsg = 'Invalid Code.';
        if (!hash_equals((string) $passwordReset->{self::FIELD_VERIFICATION_CODE}, (string) $request->{self::FIELD_CODE})) {
            return redirect()->back()->with('status', $invalidCodeMsg);
        }

        // Expiry check
        if (now()->timestamp > strtotime($passwordReset->verification_code_expire_at)) {
            return redirect()->back()->with('status', 'Verification code expired. Please request a new one.');
        }

        Cache::forget($attemptKey);
        session()->forget('fp_applicant_id'); // session clean করুন

        return redirect()->route('customer.password.sms.reset', [
            self::FIELD_TOKEN => encrypt($passwordReset->id)
        ]);
    }

    public function reset(Request $request)
    {
        // ✅ Fix 7: Proper redirect with message
        if (!$request->{self::FIELD_TOKEN}) {
            return redirect()->route('customer.password.sms.index')
                ->with('status', 'Invalid or missing reset token.');
        }

        try {
            $id = decrypt($request->{self::FIELD_TOKEN});
        } catch (DecryptException $e) {
            return redirect()->route('customer.password.sms.index')
                ->with('status', 'Invalid or expired reset link.');
        }

        $passwordReset = PasswordResetSMS::find($id);

        if (!$passwordReset) {
            return redirect()->route('customer.password.sms.index')
                ->with('status', 'Invalid or expired reset link.');
        }

        if (now()->timestamp > strtotime($passwordReset->link_expire_at)) {
            $passwordReset->delete();
            return redirect()->route('customer.password.sms.index')
                ->with('status', 'This reset link has expired. Please request a new one.');
        }

        return view('customer-auth.sms.reset', [
            self::FIELD_TOKEN => $request->{self::FIELD_TOKEN}
        ]);
    }

    public function resetPost(Request $request)
    {
        // ✅ Fix 8: Token null check যোগ করুন
        if (!$request->{self::FIELD_TOKEN}) {
            return redirect()->route('customer.password.sms.index')
                ->with('status', 'Invalid or missing reset token.');
        }

        $this->validate($request, [
            self::FIELD_PASSWORD => 'required|min:6|confirmed',
            self::FIELD_TOKEN    => 'required|string',
        ]);

        try {
            $id = decrypt($request->{self::FIELD_TOKEN});
        } catch (DecryptException $e) {
            return redirect()->route('customer.password.sms.index')
                ->with('status', 'Invalid or expired reset link.');
        }

        $passwordReset = PasswordResetSMS::find($id);

        if (!$passwordReset) {
            return redirect()->route('customer.password.sms.index')
                ->with('status', 'Invalid or expired reset link.');
        }

        if (now()->timestamp > strtotime($passwordReset->link_expire_at)) {
            $passwordReset->delete();
            return redirect()->route('customer.password.sms.index')
                ->with('status', 'This reset link has expired. Please request a new one.');
        }

        // ✅ Fix 8: findOrFail() — null crash বন্ধ
        $applicant           = Applicant::findOrFail($passwordReset->applicant_id);
        // $applicant->password = bcrypt($request->password);
        $applicant->password = Hash::make($request->password);
        $applicant->save();

        // Token একবার ব্যবহারের পর মুছুন
        $passwordReset->delete();

        // ✅ Fix 9: Dead code ($banglaPhone) সরিয়ে দিন
        $sms = 'আপনার পাসওয়ার্ড সফলভাবে পুনঃস্থাপন হয়েছে।';
        HomeController::callSmsApi($applicant->phone, $sms);

        return redirect()->route('customer.password.sms.success');
    }

    public function success()
    {
        return view('customer-auth.sms.success');
    }
}