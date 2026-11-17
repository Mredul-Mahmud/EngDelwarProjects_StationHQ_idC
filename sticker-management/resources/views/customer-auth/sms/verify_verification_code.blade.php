@extends('layouts.master')

@section('content')

    @include('layouts.header')
    @include('layouts.newsticker')
     <style>
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
    <div class="container">
        <div class="row  mt-3 justify-content-center">
            <div class="col-md-8">
                <div class="card" style="margin: 80px auto;">

                    {{-- 🔴 BLOCKED --}}
                    @if (session('blocked'))
                        <div class="block-box">
                            <div style="display:flex; align-items:center; gap:10px; margin-bottom:12px;">
                                <i class="fa fa-ban" style="font-size:28px; color:#cc0000;"></i>
                                <h4>Access Blocked</h4>
                            </div>

                            <p class="alert alert-danger">
                                <strong>{{ session('status') ?? 'You are blocked. Try again later.' }}</strong>
                            </p>

                            {{-- ⏳ Countdown --}}
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
                                        <strong>You are now unblocked!</strong> You can try again.
                                    </div>
                                    <a href="{{ route('customer.password.sms.verify') }}" class="btn btn-success btn-block">
                                        <i class="fa fa-sign-in"></i> Try Again
                                    </a>
                                </div>
                            @endif

                            <ul>
                                <li>You entered the wrong code <strong>3 times</strong>.</li>
                                <li>Please wait <strong>10 minutes</strong> before trying again.</li>
                            </ul>

                            <hr style="border-color:#ffaaaa;">

                            {{-- <div class="btn-group-block">
                                <a href="{{ route('customer.password.sms.index') }}" class="btn btn-danger">
                                    <i class="fa fa-arrow-left"></i> Back
                                </a>
                                <a href="mailto:support@yoursite.com" class="btn btn-default">
                                    <i class="fa fa-envelope"></i> Contact Support
                                </a>
                            </div> --}}
                        </div>

                        {{-- 🟢 NORMAL VERIFY FORM --}}
                    @else
                        <div class="card-header">Customer Reset Password</div>

                        <div class="alert alert-info">
                            A verification code has been sent to your phone. Please verify within 100 seconds to proceed.
                        </div>

                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-danger">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('customer.password.sms.verify.post') }}">
                                {{ csrf_field() }}

                                <div class="form-group row">
                                    <label for="code" class="col-md-4 col-form-label text-md-right">Verification
                                        Code</label>

                                    <div class="col-md-6">
                                        <input id="code" type="text"
                                            class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}"
                                            name="code" value="{{ old('code') }}" required>

                                        @if ($errors->has('code'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('code') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            Verify
                                        </button>
                                    </div>
                                </div>
                            </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>


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
