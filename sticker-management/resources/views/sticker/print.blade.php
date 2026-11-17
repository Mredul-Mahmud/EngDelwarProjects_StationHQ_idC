<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>A1 Type Sticker</title>
  <link rel="stylesheet" href="{{asset('assets/css/sticker_style.css')}}">
  <style>
    .a1-sticker {
      background-image: url("{{ asset('assets/images/newstickers/a1.png') }}");
    }
    .a2-sticker {
      background-image: url("{{ asset('assets/images/newstickers/a2.png') }}"); 
    }
    .a3-sticker {
      background-image: url("{{ asset('assets/images/newstickers/a3.png') }}"); 
    }
    .a4-staff-sticker {
      background-image: url("{{ asset('assets/images/newstickers/a4.png') }}"); 
    }
    .a4-sticker {
      background-image: url("{{ asset('assets/images/newstickers/a4_all.png') }}"); 
    }
    .b1-sticker {
      background-image: url("{{ asset('assets/images/newstickers/b1.png') }}"); 
    }
    .b2-sticker {
      background-image: url("{{ asset('assets/images/newstickers/b2.png') }}"); 
    }
    .b3-sticker {
      background-image: url("{{ asset('assets/images/newstickers/b3.png') }}"); 
    }
    .b4-sticker {
      background-image: url("{{ asset('assets/images/newstickers/b4.png') }}"); 
    }
    .b5-sticker {
      background-image: url("{{ asset('assets/images/newstickers/b5.png') }}"); 
    }
    .d1-sticker {
      background-image: url("{{ asset('assets/images/newstickers/d1.png') }}"); 
    }
    .d2-sticker {
      background-image: url("{{ asset('assets/images/newstickers/d2.png') }}"); 
    }
    .e-sticker {
      background-image: url("{{ asset('assets/images/newstickers/e.png') }}"); 
    }
    .f-sticker {
      background-image: url("{{ asset('assets/images/newstickers/f.png') }}"); 
    }
    .m-sticker {
      background-image: url("{{ asset('assets/images/newstickers/m.png') }}"); 
    }
    .m1-sticker {
      background-image: url("{{ asset('assets/images/newstickers/m1.png') }}"); 
    }
    .t-sticker {
      background-image: url("{{ asset('assets/images/newstickers/t.png') }}"); 
    }
    .t1-sticker {
      background-image: url("{{ asset('assets/images/newstickers/t1.png') }}"); 
    }
    .army-chief-sticker {
      background-image: url("{{ asset('assets/images/newstickers/x_army_chief.png') }}");
      position: relative;
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }
  </style>
</head>
<body>
  {{-- Sticker A1 --}}
  <div class="sticker-container">
    <div class="sticker-size a1-sticker">
      <div class="content content-position-a1">
        <div class="year-a1">২০</div>
        <div class="qr-section">
          {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
        </div>
        <div class="year-a1">২৭</div>
      </div>
      <strong class="serial serial-a1">A1-01</strong>
      <strong class="vehicle vehicle-position-a1">{{ !empty($app->vehicleinfo->reg_number) ? $app->vehicleinfo->reg_number : '' }}</strong>
    </div>
  </div>

  {{-- Sticker A2 --}}
  <div class="sticker-container">
    <div class="sticker-size a2-sticker">
      <div class="content content-position-a2">
        <div class="year-a2">২০</div>
        <div class="qr-section">
          {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
        </div>
        <div class="year-a2">২৭</div>
      </div>
      <strong class="serial serial-a2">A2-01</strong>
      <strong class="vehicle vehicle-position-a2">{{ !empty($app->vehicleinfo->reg_number) ? $app->vehicleinfo->reg_number : '' }}</strong>
    </div>
  </div>

  {{-- Sticker A3 --}}
  <div class="sticker-container">
    <div class="sticker-size a3-sticker">
      <div class="content content-position-a3">
        <div class="year-a3">২০</div>
        <div class="qr-section">
          {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
        </div>
        <div class="year-a3">২৬</div>
      </div>
      <strong class="serial serial-a3">A3-01</strong>
      <strong class="vehicle vehicle-position-a3">{{ !empty($app->vehicleinfo->reg_number) ? $app->vehicleinfo->reg_number : '' }}</strong>
    </div>
  </div>

  {{-- Sticker A4 Without Staff --}}
  <div class="sticker-container">
    <div class="sticker-size a4-staff-sticker">
      <div class="content content-position-a4-staff">
        <div class="year-a4-staff">২০</div>
        <div class="qr-section">
          {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
        </div>
        <div class="year-a4-staff">২৬</div>
      </div>
      <strong class="serial serial-a4-staff">A4-01</strong>
      <strong class="vehicle vehicle-position-a4-staff">{{ !empty($app->vehicleinfo->reg_number) ? $app->vehicleinfo->reg_number : '' }}</strong>
    </div>
  </div>

  {{-- Sticker A4 --}}
  <div class="sticker-container">
    <div class="sticker-size a4-sticker">
      <div class="content content-position-a4">
        <div class="year-a4">২০</div>
        <div class="qr-section">
          {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
        </div>
        <div class="year-a4">২৬</div>
      </div>
      <strong class="serial serial-a4">A4-01</strong>
      <strong class="vehicle vehicle-position-a4">{{ !empty($app->vehicleinfo->reg_number) ? $app->vehicleinfo->reg_number : '' }}</strong>
    </div>
  </div>

  {{-- Sticker B1 --}}
  <div class="sticker-container">
    <div class="sticker-size b1-sticker">
      <div class="content content-position-b1">
        <div class="year-b1">২০</div>
        <div class="qr-section">
          {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
        </div>
        <div class="year-b1">২৬</div>
      </div>
      <strong class="serial serial-b1">B1-01</strong>
      <strong class="vehicle vehicle-position-b1">{{ !empty($app->vehicleinfo->reg_number) ? $app->vehicleinfo->reg_number : '' }}</strong>
    </div>
  </div>

  {{-- Sticker B2 --}}
  <div class="sticker-container">
    <div class="sticker-size b2-sticker">
      <div class="content content-position-b2">
        <div class="year-b2">২০</div>
        <div class="qr-section">
          {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
        </div>
        <div class="year-b2">২৬</div>
      </div>
      <strong class="serial serial-b2">B2-01</strong>
      <strong class="vehicle vehicle-position-b2">{{ !empty($app->vehicleinfo->reg_number) ? $app->vehicleinfo->reg_number : '' }}</strong>
    </div>
  </div>

  {{-- Sticker B3 --}}
  <div class="sticker-container">
    <div class="sticker-size b3-sticker">
      <div class="content content-position-b3">
        <div class="year-b3">২০</div>
        <div class="qr-section">
          {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
        </div>
        <div class="year-b3">২৬</div>
      </div>
      <strong class="serial serial-b3">B3-01</strong>
      <strong class="vehicle vehicle-position-b3">{{ !empty($app->vehicleinfo->reg_number) ? $app->vehicleinfo->reg_number : '' }}</strong>
    </div>
  </div>

  {{-- Sticker B4 --}}
  <div class="sticker-container">
    <div class="sticker-size b4-sticker">
      <div class="content content-position-b4">
        <div class="year-b4">২০</div>
        <div class="qr-section">
          {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
        </div>
        <div class="year-b4">২৬</div>
      </div>
      <strong class="serial serial-b4">B4-01</strong>
      <strong class="vehicle vehicle-position-b4">{{ !empty($app->vehicleinfo->reg_number) ? $app->vehicleinfo->reg_number : '' }}</strong>
    </div>
  </div>

  {{-- Sticker B5 --}}
  <div class="sticker-container">
    <div class="sticker-size b5-sticker">
      <div class="content content-position-b5">
        <div class="year-b5">২০</div>
        <div class="qr-section">
          {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
        </div>
        <div class="year-b5">২৬</div>
      </div>
      <strong class="serial serial-b5">B5-01</strong>
      <strong class="vehicle vehicle-position-b5">{{ !empty($app->vehicleinfo->reg_number) ? $app->vehicleinfo->reg_number : '' }}</strong>
    </div>
  </div>

  {{-- Sticker D1 --}}
  <div class="sticker-container">
    <div class="sticker-size d1-sticker">
      <div class="content content-position-d1">
        <div class="year-d1">২০</div>
        <div class="qr-section">
          {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
        </div>
        <div class="year-d1">২৬</div>
      </div>
      <strong class="serial serial-d1">D1-01</strong>
      <strong class="vehicle vehicle-position-d1">{{ !empty($app->vehicleinfo->reg_number) ? $app->vehicleinfo->reg_number : '' }}</strong>
    </div>
  </div>

  {{-- Sticker D2 --}}
  <div class="sticker-container">
    <div class="sticker-size d2-sticker">
      <div class="content content-position-d1">
        <div class="year-d1" style="color: rgb(140, 15, 3)">২০</div>
        <div class="qr-section">
          {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
        </div>
        <div class="year-d1" style="color: rgb(140, 15, 3)">২৬</div>
      </div>
      <strong class="serial serial-d1" style="color: red;">D2-01</strong>
      <strong class="vehicle vehicle-position-d2">{{ !empty($app->vehicleinfo->reg_number) ? $app->vehicleinfo->reg_number : '' }}</strong>
    </div>
  </div>

  {{-- Sticker E --}}
  <div class="sticker-container">
    <div class="sticker-size e-sticker">
      <div class="content content-position-e">
        <div class="year-e">২০</div>
        <div class="qr-section">
          {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
        </div>
        <div class="year-e">২৬</div>
      </div>
      <strong class="serial serial-e">E-01</strong>
      <strong class="vehicle vehicle-position-e">{{ !empty($app->vehicleinfo->reg_number) ? $app->vehicleinfo->reg_number : '' }}</strong>
    </div>
  </div>

  {{-- Sticker F --}}
  <div class="sticker-container">
    <div class="sticker-size f-sticker">
      <div class="content content-position-f">
        <div class="year-f">২০</div>
        <div class="qr-section">
          {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
        </div>
        <div class="year-f">২৬</div>
      </div>
      <strong class="serial serial-f">F-01</strong>
      <strong class="vehicle vehicle-position-f">{{ !empty($app->vehicleinfo->reg_number) ? $app->vehicleinfo->reg_number : '' }}</strong>
    </div>
  </div>

  {{-- Sticker M --}}
  <div class="sticker-container">
    <div class="sticker-size m-sticker">
      <div class="content content-position-m">
        <div class="year-m">২০</div>
        <div class="qr-section" style="width: 50px; height:50px;">
          {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
        </div>
        <div class="year-m">২৬</div>
      </div>
      <strong class="serial serial-m">M-01</strong>
      <strong class="vehicle vehicle-position-m">{{ !empty($app->vehicleinfo->reg_number) ? $app->vehicleinfo->reg_number : '' }}</strong>
    </div>
  </div>

  {{-- Sticker M1 --}}
  <div class="sticker-container">
    <div class="sticker-size m1-sticker">
      <div class="content content-position-m">
        <div class="year-m" style="color: #000;">২০</div>
        <div class="qr-section" style="width: 50px; height:50px;">
          {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
        </div>
        <div class="year-m" style="color: #000;">২৬</div>
      </div>
      <strong class="serial serial-m1">M1-01</strong>
      <strong class="vehicle vehicle-position-m" style="color: rgb(19, 1, 124);">{{ !empty($app->vehicleinfo->reg_number) ? $app->vehicleinfo->reg_number : '' }}</strong>
    </div>
  </div>

  {{-- Sticker T --}}
  <div class="sticker-container">
    <div class="sticker-size t-sticker">
      <div class="content content-position-t">
        <div class="year-t">২০</div>
        <div class="qr-section">
          {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
        </div>
        <div class="year-t">২৬</div>
      </div>
      <strong class="serial serial-t">T-01</strong>
      <strong class="vehicle vehicle-position-t">{{ !empty($app->vehicleinfo->reg_number) ? $app->vehicleinfo->reg_number : '' }}</strong>
    </div>
  </div>

  {{-- Sticker T1 --}}
  <div class="sticker-container">
    <div class="sticker-size t1-sticker">
      <div class="content content-position-t1">
        <div class="year-t1">২০</div>
        <div class="qr-section">
          {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
        </div>
        <div class="year-t1">২৬</div>
      </div>
      <strong class="serial serial-t1">T-01</strong>
      <strong class="vehicle vehicle-position-t1">{{ !empty($app->vehicleinfo->reg_number) ? $app->vehicleinfo->reg_number : '' }}</strong>
    </div>
  </div>

  {{-- Sticker X Army Chief --}}
  <div class="sticker-container army-chief-sticker">
      <div class="content content-position-x-army-chief">
        <div class="qr-section" style="width: 35px; height:35px; border: 1px solid #bebebe; padding: 2px;">
          {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
        </div>
      </div>
      <strong class="serial serial-x-army-chief">Ser-01</strong>
      <strong class="vehicle vehicle-position-x-army-chief">{{ !empty($app->vehicleinfo->reg_number) ? $app->vehicleinfo->reg_number : '' }}</strong>
  </div>


  {{-- <script>
    window.onload = function() {
        window.print();
    };
  </script> --}}
</body>
</html>
