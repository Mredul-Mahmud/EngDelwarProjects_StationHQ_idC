<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>B2 Sticker</title>
  <link rel="stylesheet" href="{{asset('assets/css/sticker_style.css')}}">
  <style>
    .b2-sticker {
      background-image: url("{{ asset('assets/images/newstickers/b2.png') }}"); 
    }

    @media print{
      .sticker-container{
        margin-top: 20px;
        margin-left: 20px;
      }
      .content-position-b2 {
        top: 39%;
        left: 50%;
      }
      .vehicle-position-b2 {
        width: 85.5%;
        top: 78%;
        left: 50%;
        color: #000;
        background-color: rgb(255, 255, 0);
        font-size: 21px;
      }
      .serial-b2 {
        top: 44%;
        right: -35px;
        color: #fff;
        transform: rotate(-90deg);
      }

      .year-b2 {
        font-size: 60px;
        color: rgb(139, 13, 6);
        font-weight: 900;
      }
          }
  </style>
</head>
<body>
    <div class="sticker-container">
      <div class="sticker-size b2-sticker">
        <div class="content content-position-b2">
          <div class="year year-b2">{{ numberConvert(substr($app->sticker_for_year?? '', 0, 2) ) ?: '২০' }}</div>
          <div class="qr-section">
            {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
          </div>
          <div class="year year-b2">{{ numberConvert(substr($app->sticker_for_year?? '', 2, 2) ) ?: '২৮' }}</div>
        </div>
        <strong class="serial serial-b2">{{ $app->sticker_category  }}-{{ $app->sticker_number ?? 'B2-77' }}</strong>
        <strong class="vehicle vehicle-position-b2" style="{{strlen($app->vehicleinfo->reg_number) <= 23 ? 'font-size: 23px' : 'font-size: 19px'}}">{{ !empty($app->vehicleinfo->reg_number) ? formatVehicleText($app->vehicleinfo->reg_number) : '' }}</strong>
      </div>
    </div>
  <script>
    window.onload = function() {
      window.print();
    };
  </script>
</body>
</html>