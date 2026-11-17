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
    </style>
    <div class="wrapper" id="login-area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="bar login-div">
                        <i class="fa fa-user"></i> Login
                    </div>
                    <div class="well login-div">
                        {{-- <p class="text-denger">
                            You are not active user. Please contact station headquter dhaka to activate your account.
                        </p> --}}
                        <div class="alert alert-danger text-center font-weight-bold" role="alert">
                            You are not active user. Please contact Station Headquarters Dhaka to activate your account. Please contact : +8801797585010
                        </div>



                    </div>
                </div>
            </div>
        </div>

    </div>








    <script src="{{ url('/assets/cdn') }}/popper.min.js"></script>
    </script>
@endsection
