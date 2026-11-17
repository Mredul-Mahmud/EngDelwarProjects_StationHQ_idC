<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>M1 Type Sticker</title>
  <link rel="stylesheet" href="{{asset('assets/css/sticker_style.css')}}">
  <style>
    .m1-sticker {
      background-image: url("{{ asset('assets/images/newstickers/m1.png') }}"); 
    }
    @media print{
      .sticker-container{
        margin-top: 20px;
        margin-left: 20px;
      }
    .content-position-m1 {
          top: 41.5%;
          left: 50%;
      }
      .year-m1 {
          font-size: 48px;
          color: #000;
          font-weight: 900;
      }
      .vehicle-position-m1 {
          width: 50%;
          top: 60%;
          left: 51%;
          color: rgb(19, 1, 124);
          font-size: 18px;
          padding: 7px;
      }
      .serial-m1 {
        top: 33%;
        right: 27px;
        color: red;
        font-size: 18px;
        transform: rotate(-117deg);
      }
    }
  </style>
</head>
<body>
    <div class="sticker-container">
      <div class="sticker-size m1-sticker">
        <div class="content content-position-m1">
          <div class="year year-m1" style="color: #000;">{{ numberConvert(substr($app->sticker_for_year?? '', 0, 2) ) ?: '২০' }}</div>
          <div class="qr-section" style="width: 50px; height:50px;">
            {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
          </div>
          <div class="year year-m1" style="color: #000;">{{ numberConvert(substr($app->sticker_for_year?? '', 2, 2) ) ?: '২৬' }}</div>
        </div>
        <strong class="serial serial-m1">{{ $app->sticker_category  }}-{{ $app->sticker_number ?? 'M1-0411' }}</strong>
        <strong class="vehicle vehicle-position-m1" style="color: rgb(19, 1, 124);{{strlen(formatVehicleText($app->vehicleinfo->reg_number)) <= 23 ? 'font-size: 20px' : 'font-size: 18px'}}">{{ !empty($app->vehicleinfo->reg_number) ? formatVehicleText($app->vehicleinfo->reg_number) : '' }}</strong>
      </div>
    </div>
  <script>
    window.onload = function() {
        window.print();
    };
</script>
</body>
</html>