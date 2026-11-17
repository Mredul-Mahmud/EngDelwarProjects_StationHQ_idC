<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>B4 Sticker</title>
  <link rel="stylesheet" href="{{asset('assets/css/sticker_style.css')}}">
  <style>
    .b4-sticker {
        background-image: url("{{ asset('assets/images/newstickers/b4.png') }}"); 
    }
    @media print{
      .sticker-container{
        margin-top: 20px;
        margin-left: 20px;
      }
      .content-position-b4 {
        top: 42%;
        left: 50%;
      }
      .year-b4 {
        font-size: 60px;
        color: #fff;
        font-weight: 900;
      }
      .vehicle-position-b4 {
        width: 81.5%;
        top: 68%;
        left: 50%;
        color: #000;
        background-color: rgb(255, 255, 0);
        font-size: 21px;
      }
      .serial-b4 {
        top: 44%;
        right: -30px;
        color: rgb(252, 171, 2);
        transform: rotate(-90deg);
      }
    }
  </style>
</head>
<body>
    <div class="sticker-container">
      <div class="sticker-size b4-sticker">
        <div class="content content-position-b4">
          <div class="year year-b4">{{ numberConvert(substr($app->sticker_for_year?? '', 0, 2) ) ?: '২০' }}</div>
          <div class="qr-section">
            {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
          </div>
          <div class="year year-b4">{{ numberConvert(substr($app->sticker_for_year?? '', 2, 2) ) ?: '২৮' }}</div>
        </div>
        <strong class="serial serial-b4">{{ $app->sticker_category  }}-{{ $app->sticker_number ?? 'B4-79' }}</strong>
        <strong class="vehicle vehicle-position-b4" style="{{strlen(formatVehicleText($app->vehicleinfo->reg_number)) <= 23 ? 'font-size: 22px' : 'font-size: 19px'}}">{{ !empty($app->vehicleinfo->reg_number) ? formatVehicleText($app->vehicleinfo->reg_number) : '' }}</strong>
      </div>
    </div>
  <script>
    window.onload = function() {
        window.print();
    };
</script>
</body>
</html>