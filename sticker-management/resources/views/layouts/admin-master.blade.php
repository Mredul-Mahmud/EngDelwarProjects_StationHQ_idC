<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel | Station HeadQuarters, Dhaka Cantonment, Dhaka</title>

    <!-- favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/LogoS1.png') }}" />
    <!-- fonts -->
    <link href="{{ url('/assets/cdn') }}/all.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans:400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700" rel="stylesheet">
    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/cdn/all.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/cdn/bootstrap.min.css') }}">

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/cdn/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/cdn/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/cdn/dataTables.searchHighlight.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/cdn/jquery.dataTables.min.css') }}">

    <!-- Plugins -->
    <link rel="stylesheet" href="{{ asset('assets/sweetalert2/dist/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admins/css/zebra_datepicker.min.css') }}">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('assets/admins/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admins/css/newStyle.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/front-style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/print-style.css') }}">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
        integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- title tag -->
    <title>Admin Panel | Station HeadQuarters, Dhaka Cantonment, Dhaka</title>
    <style>
        button#adminMenuButton:hover+.dropdown-menu,
        .dropdown-menu:hover {
            display: block;
        }

        .dropdown-menu {
            margin-top: -3px !important;
        }

        .select2-container {

            width: 100% !important;
        }
    </style>
    @yield('style')
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>

<body>
    <div id='app'>


        <header class="admin-header">
            <div class="wrapper header-bar" id="adm-header-bar">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-3 col-lg-3 col-xl-2">
                            <div id="adm-logo">

                                <img src="{{ asset('assets/admins/images/logo-1.png') }}" class="img-fluid"
                                    alt="logo">

                            </div>
                        </div>
                        <div class="col-md-5 col-lg-5 col-xl-6">
                            <div class="nav-wrapper">
                                <nav class="navbar navbar-expand-md navbar-toggleable-sm">
                                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                                        data-target="#adm-navbar" aria-controls="navbarNav" aria-expanded="false"
                                        aria-label="Toggle navigation"><span class="navbar-toggler-icon"><i
                                                class="fas fa-bars"></i></span>
                                    </button>

                                    @if (Auth::user()->role == 'super-admin' || Auth::user()->role == 'admin')
                                        <div class="collapse navbar-collapse" id="adm-navbar">
                                            <ul class="navbar-nav">

                                                <li class="nav-item"><a href="{{ url('/home') }}"
                                                        class="nav-link">Home</a></li>
                                                <li class="nav-item"><a href="{{ url('/customer-list') }}"
                                                        class="nav-link">Clients</a></li>
                                                @if (auth()->user()->role === 'super-admin')
                                                    <li class="nav-item">
                                                        <a href="{{ url('/admin-list') }}" class="nav-link"> Admins</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="{{ url('/sticker-list') }}" class="nav-link">Sticker
                                                            Types</a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    @endif


                                    @if (Auth::user()->role != 'admin' && Auth::user()->role != 'super-admin')
                                        <div class="collapse navbar-collapse" id="adm-navbar">
                                            <ul class="navbar-nav">
                                                <li class="nav-item"><a href="{{ url('/checking') }}"
                                                        class="nav-link">Home</a></li>


                                            </ul>
                                        </div>
                                    @endif
                                </nav>
                            </div>
                        </div>

                        @if (Auth::check())
                            <div class="col-md-2 col-sm-6">
                                <notification :userid="{{ auth()->id() }}"
                                    :unreads="{{ auth()->user()->unreadNotifications->sortByDesc('created_at')->take(30) }}">
                                </notification>
                            </div>

                            <div class="col-md-2 col-sm-6">
                                <div id="adm-account">
                                    <div class="dropdown">
                                        <button class="btn btn-block text-center dropdown-toggle" type="button"
                                            id="adminMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            {{ auth()->user()->name }}
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="adminMenuButton">
                                            <a class="dropdown-item" href="#myModal" data-toggle="modal"
                                                data-target="#myModal"><i class="fas fa-unlock-alt"></i> &nbsp; Change
                                                Password
                                            </a>
                                            <a id="logout" class="dropdown-item" href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                                <i class="fas fa-power-off"></i> &nbsp; Logout
                                            </a>


                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                style="display: none;">
                                                {{ csrf_field() }}

                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </header>

        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Change Your Password </h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <form id="change_password_form" method="POST">
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4 offset-md-1">
                                    <label for="" class="label-form">Type Old Password</label><span>*</span>
                                    <br>
                                    <small></small>
                                </div>
                                <div class="col-md-7">
                                    <input type="password" name="old_password" id="app_old_pass"
                                        class="form-control in-form" value="" AUTOCOMPLETE="off">
                                </div>
                                <div class="col-md-4 offset-md-1">
                                    <label for="" class="label-form">Type New Password</label><span>*</span>
                                    <br>
                                    <small></small>
                                </div>
                                <div class="col-md-7">
                                    <input type="password" name="password" id="password"
                                        class="form-control in-form" value="" AUTOCOMPLETE="off">

                                </div>
                                <div class="col-md-4 offset-md-1">
                                    <label for="" class="label-form">Type Confirm
                                        Password</label><span>*</span> <br>
                                    <small></small>
                                </div>
                                <div class="col-md-7">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="form-control in-form" value="" AUTOCOMPLETE="off">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary"> Submit </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if (Auth::user()->role == 'super-admin' || Auth::user()->role == 'admin')
            {{-- Admin or Super Admin Layout --}}
            <div class="wrapper main-content" id="adm-main-content">
                <div class="container-fluid">
                    <div class="row">
                        {{-- Admin Sidebars --}}
                        @yield('applicant-sidebar')
                        @yield('admin-sidebar')

                        <div class="col-md-9 col-lg-9 col-xl-10 adm-right-content">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- Other Roles Layout --}}
            <div class="wrapper main-content" id="user-main-content">
                <div class="container-fluid">
                    <div class="row">
                        {{-- No sidebars for other users --}}
                        <div class="col-md-12 col-sm-12">
                            @yield('checking_content')
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @include('layouts.footer')
    </div><!-- app ends -->

    <!-- JS Files -->

    <!-- Core -->
    <script src="{{ asset('assets/cdn/jquery-3.7.0.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/cdn/jquery-3.6.0.min.js') }}"></script> --}}
    <script src="{{ asset('assets/cdn/popper.min.js') }}"></script>
    <script src="{{ asset('assets/cdn/bootstrap.min.js') }}"></script>

    <!-- DataTables Core -->
    <script src="{{ asset('assets/cdn/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/cdn/dataTables.bootstrap4.min.js') }}"></script>

    <!-- DataTables Buttons -->
    <script src="{{ asset('assets/cdn/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/cdn/buttons.bootstrap4.min.js') }}"></script>

    <script src="{{ asset('assets/cdn/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/cdn/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/cdn/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/cdn/buttons.print.min.js') }}"></script>

    <!-- Search Highlight -->
    <script src="{{ asset('assets/cdn/jquery.highlight.js') }}"></script>
    <script src="{{ asset('assets/cdn/dataTables.searchHighlight.min.js') }}"></script>
    <script src="{{ asset('/assets/sweetalert2/dist/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('/assets/admins/js/zebra_datepicker.min.js') }}"></script>
    <script src="{{ asset('/assets/admins/js/custom.js') }}"></script>

    <!-- <script src="{{ asset('/assets/zoom/js/zoom.jquery.js') }}"></script>
    <script src="{{ asset('/assets/zoom/js/main.js') }}"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
        integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @yield('admin-script')
</body>

</html>
