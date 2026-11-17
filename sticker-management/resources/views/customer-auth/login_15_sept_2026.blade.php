@extends('layouts.master')
@section('content')
    @include('layouts.header')
    @include('layouts.new_website.menubar')
    @include('layouts.new_website.buliten')

    <style>
        #request_to_apply {
            background: #eee;
            padding: 15px;
            color: #f00;
            margin: -15px 0 25px;
            font-size: 17px;
            border-radius: 10px;
        }
        .block-box {
            /* background: #ffe5e5; */
            /* border-left: 5px solid #cc0000; */
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .block-box h4 {
            /* color: #cc0000; */
            margin: 0;
        }
        .block-box ul {
            color: #555;
            font-size: 14px;
            padding-left: 20px;
            margin-top: 10px;
        }
        .btn-group-block {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 15px;
        }
        #countdown-box {
            /* background: #fff0f0; */
            /* border: 2px dashed #cc0000; */
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            margin: 15px 0;
        }
        #countdown-timer {
            font-size: 48px;
            font-weight: bold;
            /* color: #cc0000; */
            letter-spacing: 4px;
            font-family: monospace;
        }
        #countdown-label {
            margin: 6px 0 0;
            color: #888;
            font-size: 12px;
        }
    </style>

    <div class="wrapper" id="login-area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="bar login-div">
                        <i class="fa fa-user"></i> Login
                    </div>
                    <div class="well login-div">

                        {{-- 🔴 BLOCKED USER --}}
                        @if (session('blocked'))

                            <div class="block-box">

                                <div style="display:flex; align-items:center; gap:10px; margin-bottom:12px;">
                                    <i class="fa fa-ban" style="font-size:28px; color:#cc0000;"></i>
                                    <h4>Access Blocked</h4>
                                </div>

                                <p class="alert alert-danger">
                                    <strong>{{ session('message') ?? 'You are blocked. Try again later.' }}</strong>
                                </p>

                                {{-- ⏳ Countdown Timer --}}
                                @if (session('block_expires_at'))
                                    <div id="countdown-box">
                                        <p style="margin:0 0 8px; color:#555; font-size:13px;">
                                            <i class="fa fa-clock-o"></i> You can try again in:
                                        </p>
                                        <div id="countdown-timer">10:00</div>
                                        <p id="countdown-label">minutes : seconds</p>
                                    </div>

                                    <div id="unblock-message" style="display:none;">
                                        <div class="alert alert-success">
                                            <i class="fa fa-check-circle"></i>
                                            <strong>You are now unblocked!</strong> You can log in again.
                                        </div>
                                        <a href="{{ route('customer.login') }}" class="btn btn-success btn-block">
                                            <i class="fa fa-sign-in"></i> Go to Login
                                        </a>
                                    </div>
                                @endif

                                <ul>
                                    <li>You entered the wrong OTP <strong>3 times</strong>.</li>
                                    <li>Please wait <strong>10 minutes</strong> before trying again.</li>
                                </ul>

                                <hr style="border-color:#ffaaaa;">

                                {{-- <div class="btn-group-block">
                                    <a href="{{ route('customer.login') }}" class="btn btn-danger">
                                        <i class="fa fa-arrow-left"></i> Back to Login
                                    </a>
                                    <a href="mailto:support@yoursite.com" class="btn btn-default">
                                        <i class="fa fa-envelope"></i> Contact Support
                                    </a>
                                </div> --}}

                            </div>{{-- end block-box --}}

                        {{-- 🟡 OTP VERIFICATION STEP --}}
                        @elseif (session()->has('verified'))

                            <div class="form-group" id="request_to_apply">
                                A verification code has been sent to your phone. Please verify within
                                <strong>100 seconds</strong> to proceed.
                            </div>

                            {{-- ⚠️ Flash message (wrong code / remaining attempts) --}}
                            @if (Session::has('message'))
                                <div class="alert {{ Session::get('alert-class', 'alert-info') }}">
                                    {{ Session::get('message') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('customer.login.verify') }}">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label>Verification Code</label>
                                    <input type="text" name="verify_code"
                                        class="form-control{{ $errors->has('verify_code') ? ' is-invalid' : '' }}"
                                        placeholder="Enter your Verification code">
                                    @if ($errors->has('verify_code'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('verify_code') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary btn-block">Log In</button>
                                    </div>
                                </div>
                            </form>

                        {{-- 🟢 NORMAL LOGIN FORM --}}
                        @else

                            @if (Session::has('message'))
                                <div id="request_to_apply">
                                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">
                                        {{ Session::get('message') }}
                                    </p>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('customer.login') }}">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label>Mobile</label>
                                    <input type="text" name="phone"
                                        class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                        placeholder="+8801515XXXXXX" required>
                                    @if ($errors->has('phone'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('phone') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" name="password"
                                        class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                        placeholder="Enter your password" autocomplete="off" required>
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary btn-block">Log In</button>
                                    </div>
                                    <div class="col-md-4">&nbsp;</div>
                                    <div class="col-md-4">
                                        <a class="btn btn-warning btn-block"
                                            href="{{ route('customer.password.sms.index') }}">
                                            Forgot password?
                                        </a>
                                    </div>
                                </div>
                                <hr>
                               Don't have an account?
                                <a class="btn btn-info" href="{{ route('customer.register') }}">Register</a> 
                            </form>

                        @endif

                    </div>{{-- end .well --}}
                </div>
            </div>
        </div>
    </div>

    <script src="{{ url('/assets/cdn') }}/popper.min.js"></script>

    {{-- ⏳ Countdown Script --}}
    @if (session('block_expires_at'))
        <script>
            (function() {
                const expiresAt = {{ session('block_expires_at') }};

                const timerEl   = document.getElementById('countdown-timer');
                const labelEl   = document.getElementById('countdown-label');
                const unblockEl = document.getElementById('unblock-message');
                const boxEl     = document.getElementById('countdown-box');

                function pad(n) {
                    return String(n).padStart(2, '0');
                }

                function tick() {
                    const nowSeconds = Math.floor(Date.now() / 1000);
                    const diff       = expiresAt - nowSeconds;

                    if (diff <= 0) {
                        boxEl.style.display     = 'none';
                        unblockEl.style.display = 'block';
                        return;
                    }

                    const minutes = Math.floor(diff / 60);
                    const seconds = diff % 60;

                    timerEl.textContent = `${pad(minutes)}:${pad(seconds)}`;
                    labelEl.textContent = 'minutes : seconds';

                    setTimeout(tick, 1000);
                }

                tick();
            })();
        </script>
    @endif

@endsection