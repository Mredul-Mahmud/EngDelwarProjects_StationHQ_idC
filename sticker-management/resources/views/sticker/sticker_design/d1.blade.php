<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>D1 Sticker</title>
  <link rel="stylesheet" href="{{asset('assets/css/sticker_style.css')}}">
  <style>
    .d1-sticker {
        background-image: url("{{ asset('assets/images/newstickers/d1.png') }}"); 
    }
    @media print{
      .sticker-container{
        margin-top: 20px;
        margin-left: 20px;
      }

      .content-position-d1 {
        top: 50%;
        left: 50%;
      }
      .year-d1 {
        font-size: 54px;
        color: #fff;
        font-weight: 900;
        letter-spacing: -2px;
      }
      .vehicle-position-d1 {
        width: 83.5%;
        top: 76%;
        left: 50%;
        color: #000;
        background-color: rgb(255, 255, 0);
        font-size: 21px;
      }
      .serial-d1 {
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
      <div class="sticker-size d1-sticker">
        <div class="content content-position-d1">
          <div class="year year-d1">{{ numberConvert(substr($app->sticker_for_year?? '', 0, 2) ) ?: '২০' }}</div>
          <div class="qr-section">
            {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
          </div>
          <div class="year year-d1">{{ numberConvert(substr($app->sticker_for_year?? '', 2, 2) ) ?: '২৬' }}</div>
        </div>
        <strong class="serial serial-d1">{{ $app->sticker_category  }}-{{ $app->sticker_number ?? 'D1-79' }}</strong>
        <strong class="vehicle vehicle-position-d1" style="{{strlen($app->vehicleinfo->reg_number) <= 23 ? 'font-size: 21px' : 'font-size: 18px'}}">{{ !empty($app->vehicleinfo->reg_number) ? formatVehicleText($app->vehicleinfo->reg_number) : '' }}</strong>
      </div>
    </div>
  <script>
    window.onload = function() {
        window.print();
    };
</script>
</body>
</html>