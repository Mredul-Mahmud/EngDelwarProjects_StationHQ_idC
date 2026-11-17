<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>A4 Sticker</title>
  <link rel="stylesheet" href="{{asset('assets/css/sticker_style.css')}}">
  <style>
    .a4-staff-sticker {
        background-image: url("{{ asset('assets/images/newstickers/a4.png') }}"); 
    }

    @media print{
      .sticker-container{
        margin-top: 20px;
        margin-left: 20px;
      }
      .content-position-a4-staff {
        top: 42%;
        left: 53%;
      }
      .year-a4-staff {
        font-size: 60px;
        color: rgb(237, 203, 2);
        font-weight: 900;
      }
      .vehicle-position-a4-staff {
        width: 86.5%;
        top: 73%;
        left: 51.5%;
        color: #000;
        background-color: rgb(255, 255, 0);
        font-size: 23px;
      }
      .serial-a4-staff {
        top: 40%;
        right: -42px;
        color: #fff;
        transform: rotate(-90deg);
      }
    }
  </style>
</head>
<body>
    <div class="sticker-container">
      <div class="sticker-size a4-staff-sticker">
        <div class="content content-position-a4-staff">
          <div class="year year-a4-staff">{{ numberConvert(substr($app->sticker_for_year?? '', 0, 2) ) ?: '২০' }}</div>
          <div class="qr-section">
            {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
          </div>
          <div class="year year-a4-staff">{{ numberConvert(substr($app->sticker_for_year?? '', 2, 2) ) ?: '২৬' }}</div>
        </div>
        <strong class="serial serial-a4-staff">A4-{{ $app->sticker_number ?? 'A4-23' }}</strong>
        <strong class="vehicle vehicle-position-a4-staff" style="{{strlen(formatVehicleText($app->vehicleinfo->reg_number)) <= 23 ? 'font-size: 22px' : 'font-size: 19px'}}">{{ !empty($app->vehicleinfo->reg_number) ? formatVehicleText($app->vehicleinfo->reg_number) : '' }}</strong>
      </div>
    </div>
    <script>
      window.onload = function() {
          window.print();
      };
  </script>
</body>
</html>
