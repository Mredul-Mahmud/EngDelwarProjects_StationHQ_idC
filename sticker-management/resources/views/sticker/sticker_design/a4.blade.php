<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>A4 Sticker All CheckPost</title>
  <link rel="stylesheet" href="{{asset('assets/css/sticker_style.css')}}">
  <style>
    .a4-sticker {
        background-image: url("{{ asset('assets/images/newstickers/a4_all.png') }}"); 
    }
    @media print{
      .sticker-container{
        margin-top: 20px;
        margin-left: 20px;
      }
      .content-position-a4 {
        top: 40%;
        left: 53%;
      }
      .year-a4 {
        font-size: 70px;
        color: #000;
        font-weight: 900;
      }
      .vehicle-position-a4 {
        width: 88%;
        top: 73%;
        left: 51%;
        color: #fff;
        background-color: rgb(0, 54, 22);
        font-size: 23px;
      }
      .serial-a4 {
        top: 42%;
        right: -42px;
        color: #000;
        transform: rotate(-90deg);
      }

      body{
        margin-left: 30px !important;
      }
    }
  </style>
</head>
<body>
    <div class="sticker-container">
      <div class="sticker-size a4-sticker">
        <div class="content content-position-a4">
          <div class="year year-a4">{{ numberConvert(substr($app->sticker_for_year?? '', 0, 2) ) ?: '২০' }}</div>
          <div class="qr-section">
            {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
          </div>
          <div class="year year-a4">{{ numberConvert(substr($app->sticker_for_year?? '', 2, 2) ) ?: '২৬' }}</div>
        </div>
        <strong class="serial serial-a4">{{ $app->sticker_category  }}-{{ $app->sticker_number ?? 'A4-99' }}</strong>
        <strong class="vehicle vehicle-position-a4" style="{{strlen(formatVehicleText($app->vehicleinfo->reg_number)) <= 23 ? 'font-size: 22px' : 'font-size: 19px'}}">{{ !empty($app->vehicleinfo->reg_number) ? formatVehicleText($app->vehicleinfo->reg_number) : '' }}</strong>
      </div>
    </div>
      <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>