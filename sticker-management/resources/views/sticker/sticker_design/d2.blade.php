<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>D2 Sticker</title>
  <link rel="stylesheet" href="{{asset('assets/css/sticker_style.css')}}">
  <style>
    .d2-sticker {
        background-image: url("{{ asset('assets/images/newstickers/d2.png') }}"); 
    }

    @media print{
      .sticker-container{
        margin-top: 20px;
        margin-left: 20px;
      }
      .content-position-d2 {
        top: 50%;
        left: 50%;
      }
      .year-d2 {
        font-size: 54px;
        color: rgb(140, 15, 3);
        font-weight: 900;
        letter-spacing: -2px;
      }
      .vehicle-position-d2 {
        width: 83.5%;
        top: 76%;
        left: 50%;
        color: #fff;
        background-color: rgb(18, 123, 9);
        font-size: 21px;
      }
      .serial-d2 {
        top: 44%;
        right: -17px;
        color: #fff;
        transform: rotate(-90deg);
      }
    }
  </style>
</head>
<body>
    <div class="sticker-container">
      <div class="sticker-size d2-sticker">
        <div class="content content-position-d2">
          <div class="year year-d2" style="color: rgb(140, 15, 3)">{{ numberConvert(substr($app->sticker_for_year?? '', 0, 2) ) ?: '২০' }}</div>
          <div class="qr-section">
            {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
          </div>
          <div class="year year-d2" style="color: rgb(140, 15, 3)">{{ numberConvert(substr($app->sticker_for_year?? '', 2, 2) ) ?: '২৬' }}</div>
        </div>
        <strong class="serial serial-d2" style="color: red;">{{ $app->sticker_category  }}-{{ $app->sticker_number ?? 'D2-33' }}</strong>
        <strong class="vehicle vehicle-position-d2" style="{{strlen(formatVehicleText($app->vehicleinfo->reg_number)) <= 23 ? 'font-size: 21px' : 'font-size: 18px'}}">{{ !empty($app->vehicleinfo->reg_number) ? formatVehicleText($app->vehicleinfo->reg_number) : '' }}</strong>
      </div>
    </div>
  <script>
    window.onload = function() {
        window.print();
    };
</script>
</body>
</html>