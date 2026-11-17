<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- CSS -->

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">


    <script src="{{ asset('css/bootstrap.bundle.min.js') }}"></script>





    <style>
        /* body {
            margin: 0;
            background: #222;
            color: #fff;
            font-family: Arial, sans-serif;
            overflow-x: hidden;
            overflow-y: auto;
        } */

        body {
            margin: 0;
            background: #111;
            font-family: sans-serif;
            overflow-x: hidden;
            overflow-y: auto;
            color: #060606;
        }

        .header {
            background: #1a3d1a;
            padding: 20px 0;
            text-align: center;
            border-bottom: 4px solid #3c6e3c;
        }

        .header h1 {
            color: #fff;
            margin: 0;
            font-size: 2.2rem;
            letter-spacing: 2px;
        }

        .header .subtitle {
            color: #b2ffb2;
            font-size: 1.1rem;
        }

        .navbar-custom {
            background: #2e4d2e;
            border-bottom: 2px solid #3c6e3c;
        }

        .navbar-custom .nav-link {
            color: #fff !important;
            font-weight: bold;
            border-radius: 4px;
            margin: 0 4px;
        }

        .navbar-custom .nav-link:hover {
            background: #3c6e3c;
        }

        .left {
            /* background: #2e4d2e; */
            border-radius: 8px;
            text-align: center;
            padding: 18px;
            margin-bottom: 20px;
        }

        .left img {
            width: 400px;
            border-radius: 8px;
            border: 2px solid #ffd700;
        }

        .left h3 {
            margin-top: 12px;
            color: #236e12;
            font-size: 1.1rem;
        }

        .center {
            background: #333;
            border-radius: 8px;
            padding: 22px;
            margin-bottom: 20px;
        }

        .center h2 {
            color: #ffd700;
            font-size: 1.3rem;
            margin-bottom: 10px;
        }

        .grid .box {
            background: #2e4d2e;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #3c6e3c;
            padding: 18px;
            margin-bottom: 18px;
        }

        .grid .box h4 {
            color: #ffd700;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }

        .activities {
            background: #222;
            border-radius: 8px;
            border: 1px solid #3c6e3c;
            padding: 22px;
            margin-top: 36px;
        }

        .activities h3 {
            color: #ffd700;
            font-size: 1.2rem;
            margin-bottom: 16px;
        }

        .activities .caption {
            color: #fff;
            margin-top: 8px;
            text-align: center;
            font-size: 0.98rem;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        @include('new_website.topsection')
        @include('new_website.carusal')
        @include('new_website.navbar')
        @include('new_website.buliten')

        @yield('new_website_content')



        @include('new_website.footrt')
	@include('new_website.expired-check-script')
    </div>


</body>

</html>
