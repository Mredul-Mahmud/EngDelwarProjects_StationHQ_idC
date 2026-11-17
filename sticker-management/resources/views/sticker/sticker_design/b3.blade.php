<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>B3 Sticker</title>
  <link rel="stylesheet" href="{{asset('assets/css/sticker_style.css')}}">
  <style>
    .b3-sticker {
        background-image: url("{{ asset('assets/images/newstickers/b3.png') }}"); 
    }
    @print{
      .sticker-container{
        margin-top: 20px;
        margin-left: 20px;
      }
      .content-position-b3 {
        top: 44%;
        left: 50%;
      }
      .year-b3 {
        font-size: 60px;
        color: rgb(237, 203, 2);
        font-weight: 900;
      }
      .vehicle-position-b3 {
        width: 79.5%;
        top: 68%;
        left: 50%;
        color: #000;
        background-color: rgb(255, 255, 0);
        font-size: 22px;
      }
      .serial-b3 {
        top: 44%;
        right: -35px;
        color: #000;
        transform: rotate(-90deg);
      }
    }
  </style>
</head>
<body>
    <div class="sticker-container">
      <div class="sticker-size b3-sticker">
        <div class="content content-position-b3">
          <div class="year year-b3">{{ numberConvert(substr($app->sticker_for_year?? '', 0, 2) ) ?: '২০' }}</div>
          <div class="qr-section">
            {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
          </div>
          <div class="year year-b3">{{ numberConvert(substr($app->sticker_for_year?? '', 2, 2) ) ?: '২৮' }}</div>
        </div>
        <strong class="serial serial-b3">{{ $app->sticker_category  }}-{{ $app->sticker_number ?? 'B3-9795' }}</strong>
        <strong class="vehicle vehicle-position-b3" style="{{strlen(formatVehicleText($app->vehicleinfo->reg_number)) <= 23 ? 'font-size: 20px' : 'font-size: 17px'}}">{{ !empty($app->vehicleinfo->reg_number) ? formatVehicleText($app->vehicleinfo->reg_number) : '' }}</strong>
      </div>
    </div>
  <script>
    window.onload = function() {
        window.print();
    };
</script>
</body>
</html>