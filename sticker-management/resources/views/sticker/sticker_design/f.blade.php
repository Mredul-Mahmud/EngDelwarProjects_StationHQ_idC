<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>F Sticker</title>
  <link rel="stylesheet" href="{{asset('assets/css/sticker_style.css')}}">
  <style>
    .f-sticker {
      background-image: url("{{ asset('assets/images/newstickers/f.png') }}"); 
    }
    @media print{
      .sticker-container{
        margin-top: 20px;
        margin-left: 20px;
      }
      .content-position-f {
        top: 38%;
        left: 50%;
      }
      .year-f {
        font-size: 60px;
        color: #fff;
        font-weight: 900;
      }
      .vehicle-position-f {
        width: 97.5%;
        top: 67%;
        left: 50%;
        color: #fff;
        background-color: rgb(18, 123, 9);
        font-size: 22px;
      }
      .serial-f {
        top: 44%;
        right: -34px;
        color: #fff;
        transform: rotate(-90deg);
      }
    }
  </style>
</head>
<body>
  <div class="sticker-container">
    <div class="sticker-size f-sticker">
      <div class="content content-position-f">
        <div class="year year-f">{{ numberConvert(substr($app->sticker_for_year?? '', 0, 2) ) ?: '২০' }}</div>
        <div class="qr-section">
          {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
        </div>
        <div class="year year-f">{{ numberConvert(substr($app->sticker_for_year?? '', 2, 2) ) ?: '২৬' }}</div>
      </div>
      <strong class="serial serial-f">{{ $app->sticker_category  }}-{{ $app->sticker_number ?? 'F-22' }}</strong>
      <strong class="vehicle vehicle-position-f" style="{{strlen(formatVehicleText($app->vehicleinfo->reg_number)) <= 23 ? 'font-size: 23px' : 'font-size: 20px'}}">{{ !empty($app->vehicleinfo->reg_number) ? formatVehicleText($app->vehicleinfo->reg_number) : '' }}</strong>
    </div>
  </div>
  <script>
    window.onload = function() {
        window.print();
    };
</script>
</body>
</html>