<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>B5 Sticker</title>
  <link rel="stylesheet" href="{{asset('assets/css/sticker_style.css')}}">
  <style>
    .b5-sticker {
        background-image: url("{{ asset('assets/images/newstickers/b5.png') }}"); 
    }
    @media print{
      .sticker-container{
        margin-top: 20px;
        margin-left: 20px;
      }
      .content-position-b4 {
        top: 50%;
        left: 50%;
      }
      .year-b5 {
        font-size: 60px;
        color: #fff;
        font-weight: 900;
      }
      .vehicle-position-b5 {
        width: 90.5%;
        top: 80%;
        left: 50%;
        color: #000;
        background-color: rgb(255, 255, 0);
        font-size: 23px;
      }
      .serial-b5 {
        top: 44%;
        right: -42px;
        color: #fff;
        transform: rotate(-90deg);
      }
    }
  </style>
</head>
<body>
    <div class="sticker-container">
      <div class="sticker-size b5-sticker">
        <div class="content content-position-b5">
          <div class="year year-b5">{{ numberConvert(substr($app->sticker_for_year?? '', 0, 2) ) ?: '২০' }}</div>
          <div class="qr-section">
            {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
          </div>
          <div class="year year-b5">{{ numberConvert(substr($app->sticker_for_year?? '', 2, 2) ) ?: '২৬' }}</div>
        </div>
        <strong class="serial serial-b5">{{ $app->sticker_category  }}-{{ $app->sticker_number ?? 'B5-7777' }}</strong>
        <strong class="vehicle vehicle-position-b5" style="{{strlen(formatVehicleText($app->vehicleinfo->reg_number)) <= 23 ? 'font-size: 23px' : 'font-size: 20px'}}">{{ !empty($app->vehicleinfo->reg_number) ? formatVehicleText($app->vehicleinfo->reg_number) : '' }}</strong>
      </div>
    </div>
  <script>
    window.onload = function() {
        window.print();
    };
</script>
</body>
</html>