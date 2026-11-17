<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>A3 Sticker</title>
  <link rel="stylesheet" href="{{asset('assets/css/sticker_style.css')}}">
  <style>
    .a3-sticker {
        background-image: url("{{ asset('assets/images/newstickers/a3.png') }}"); 
    }
    @media print{
      .sticker-container{
        margin-top: 20px;
        margin-left: 20px;
      }
      .content-position-a3 {
        top: 33%;
        left: 50%;
      }
      .year-a3 {
        font-size: 70px;
        color: rgb(115, 1, 61);
        font-weight: 900;
      }
      .vehicle-position-a3 {
        width: 88%;
        top: 62%;
        left: 50%;
        color: #fff;
        background-color: rgb(18, 123, 9);
        font-size: 22px;
      }
      .serial-a3 {
        top: 42%;
        right: -40px;
        color: #fff;
        transform: rotate(-90deg);
      }
    }
  </style>
</head>
<body>
    <div class="sticker-container">
      <div class="sticker-size a3-sticker">
        <div class="content content-position-a3">
          <div class="year year-a3">{{ numberConvert(substr($app->sticker_for_year?? '', 0, 2) ) ?: '২০' }}</div>
          <div class="qr-section">
            {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
          </div>
          <div class="year year-a3">{{ numberConvert(substr($app->sticker_for_year?? '', 2, 2) ) ?: '২৬' }}</div>
        </div>
        <strong class="serial serial-a3">{{ $app->sticker_category  }}-{{ $app->sticker_number ?? 'A3-39' }}</strong>
        <strong class="vehicle vehicle-position-a3" style="{{strlen(formatVehicleText($app->vehicleinfo->reg_number)) <= 23 ? 'font-size: 22px' : 'font-size: 19px'}}">{{ !empty($app->vehicleinfo->reg_number) ? formatVehicleText($app->vehicleinfo->reg_number) : '' }}</strong>
      </div>
    </div>
  <script>
    window.onload = function() {
        window.print();
    };
</script>
</body>
</html>
