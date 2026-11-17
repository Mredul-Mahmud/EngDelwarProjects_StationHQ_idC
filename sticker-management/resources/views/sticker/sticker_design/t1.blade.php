<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>T1 Sticker</title>
  <link rel="stylesheet" href="{{asset('assets/css/sticker_style.css')}}">
  <style>
    .t1-sticker {
      background-image: url("{{ asset('assets/images/newstickers/t1.png') }}"); 
    }
    @media print{
      .sticker-container{
        margin-top: 20px;
        margin-left: 20px;
      }
      .content-position-t1 {
        top: 18%;
        left: 51%;
      }
      .year-t1 {
        font-size: 60px;
        color: #fff;
        font-weight: 900;
      }
      .vehicle-position-t1 {
        width: 83.5%;
        top: 44%;
        left: 50%;
        color: #000;
        background-color: rgb(255, 255, 0);
        font-size: 20px;
      }
      .serial-t1 {
        top: 70%;
        right: 18px;
        color: #fff;
        transform: rotate(-90deg);
      }
    }
  </style>
</head>
<body>
    <div class="sticker-container">
      <div class="sticker-size t1-sticker">
        <div class="content content-position-t1">
          <div class="year year-t1">{{ numberConvert(substr($app->sticker_for_year?? '', 0, 2) ) ?: '২০' }}</div>
          <div class="qr-section">
            {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
          </div>
          <div class="year year-t1" style="font-family: SutonnyMJ;">{{ numberConvert(substr($app->sticker_for_year?? '', 2, 2) ) ?: '২৬' }}</div>
        </div>
        <strong class="serial serial-t1">{{ $app->sticker_category  }}-{{ $app->sticker_number ?? 'T1-22' }}</strong>
        <strong class="vehicle vehicle-position-t1" style="{{strlen(formatVehicleText($app->vehicleinfo->reg_number)) <= 23 ? 'font-size: 22px' : 'font-size: 18px'}}">{{ !empty($app->vehicleinfo->reg_number) ? formatVehicleText($app->vehicleinfo->reg_number) : '' }}</strong>
      </div>
    </div>
  <script>
    window.onload = function() {
        window.print();
    };
</script>
</body>
</html>