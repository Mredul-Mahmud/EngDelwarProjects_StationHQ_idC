<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>T Sticker</title>
  <link rel="stylesheet" href="{{asset('assets/css/sticker_style.css')}}">
  <style>
    .t-sticker {
      background-image: url("{{ asset('assets/images/newstickers/t.png') }}"); 
    }
    @media print{
      .sticker-container{
        margin-top: 20px;
        margin-left: 20px;
      }
      .content-position-t {
        top: 19%;
        left: 50%;
      }
      .year-t {
        font-size: 60px;
        color: #fff;
        font-weight: 900;
      }
      .vehicle-position-t {
        width: 82%;
        top: 67%;
        left: 50%;
        color: #fff;
        background-color: rgb(18, 123, 9);
        font-size: 20px;
      }
      .serial-t {
        top: 48%;
        right: -36px;
        color: #12db12;
        transform: rotate(-90deg);
      }
    }
  </style>
</head>
<body>
  <div class="sticker-container">
    <div class="sticker-size t-sticker">
      <div class="content content-position-t">
        <div class="year year-t">{{ numberConvert(substr($app->sticker_for_year?? '', 0, 2) ) ?: '২০' }}</div>
        <div class="qr-section">
          {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
        </div>
        <div class="year year-t">{{ numberConvert(substr($app->sticker_for_year?? '', 2, 2) ) ?: '২৬' }}</div> 
      </div>
      <strong class="serial serial-t">{{ $app->sticker_category  }}-{{ $app->sticker_number ?? 'T-9999' }}</strong>
      <strong class="vehicle vehicle-position-t" style="{{strlen(formatVehicleText($app->vehicleinfo->reg_number)) <= 23 ? 'font-size: 21px' : 'font-size: 18px'}}">{{ !empty($app->vehicleinfo->reg_number) ? formatVehicleText($app->vehicleinfo->reg_number) : '' }}</strong>
    </div>
  </div>
  <script>
    window.onload = function() {
        window.print();
    };
</script>
</body>
</html>