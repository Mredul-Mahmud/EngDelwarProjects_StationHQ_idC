<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>E Sticker</title>
  <link rel="stylesheet" href="{{asset('assets/css/sticker_style.css')}}">
  <style>
    .e-sticker {
        background-image: url("{{ asset('assets/images/newstickers/e.png') }}"); 
    }
    @media print{
      .sticker-container{
        margin-top: 20px;
        margin-left: 20px;
      }
      .content-position-e {
        top: 31%;
        left: 50%;
      }
      .year-e {
        font-size: 60px;
        color: #fff;
        font-weight: 900;
      }
      .vehicle-position-e {
        width: 89.5%;
        top: 59%;
        left: 50%;
        color: #000;
        background-color: rgb(255, 255, 0);
        font-size: 22px;
      }
      .serial-e {
        top: 40%;
        right: -40px;
        color: #fff;
        transform: rotate(-90deg);
      }
    }
  </style>
</head>
<body>
  <div class="sticker-container">
    <div class="sticker-size e-sticker">
      <div class="content content-position-e">
        <div class="year year-e">{{ numberConvert(substr($app->sticker_for_year?? '', 0, 2) ) ?: '২০' }}</div>
        <div class="qr-section">
          {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
        </div>
        <div class="year year-e">{{ numberConvert(substr($app->sticker_for_year?? '', 2, 2) ) ?: '২৬' }}</div>
      </div>
      <strong class="serial serial-e">E-{{ $app->sticker_number ?? 'E-00' }}</strong>
      {{-- <strong class="serial serial-e">{{ $app->sticker_category  }}-{{ $app->sticker_number ?? 'E-00' }}</strong> --}}
      <strong class="vehicle vehicle-position-e" style="{{strlen(formatVehicleText($app->vehicleinfo->reg_number)) <= 23 ? 'font-size: 23px' : 'font-size: 20px'}}">{{ !empty($app->vehicleinfo->reg_number) ? formatVehicleText($app->vehicleinfo->reg_number) : '' }}</strong>
    </div>
  </div>
  <script>
    window.onload = function() {
        window.print();
    };
</script>
</body>
</html>