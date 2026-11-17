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
    @media print{
      .sticker-container{
        margin-top: 20px;
        margin-left: 20px;
      }
      .content-position-a1 {
        top: 38%;
        left: 50%;
      }
      .year-a1 {
        font-size: 60px;
        color: #ffb31d;
      }
      .vehicle-position-a1 {
          top: 63%;
          left: 50%;
          color: #000;
          background-color: rgb(255, 254, 1);
      }
      .serial-a1 {
        top: 42%;
        right: -23px;
        color: yellow;
        transform: rotate(-90deg);
      }
    }
  </style>
</head>
<body>
    <div class="sticker-container">
      <div class="sticker-size a1-sticker">
        <div class="content content-position-a1">
          <h1 class="year year-a1">{{ numberConvert(substr($app->sticker_for_year?? '', 0, 2) ) ?: '২০' }}</h1>
          <div class="qr-section">
            {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
          </div>
          <h1 class="year year-a1">{{ numberConvert(substr($app->sticker_for_year?? '', 2, 2) ) ?: '২৭' }}</h1>
        </div>
        <strong class="serial serial-a1">{{ $app->sticker_category  }}-{{ $app->sticker_number ?? 'A1-0552' }}</strong>
        <strong class="vehicle vehicle-position-a1" style="{{strlen(formatVehicleText($app->vehicleinfo->reg_number)) <= 23 ? 'font-size: 22px' : 'font-size:19px'}}">{{ !empty($app->vehicleinfo->reg_number) ? formatVehicleText($app->vehicleinfo->reg_number) : '' }}</strong>
      </div>
    </div>
  <script>
    window.onload = function() {
        window.print();
    };
</script>
</body>
</html>
