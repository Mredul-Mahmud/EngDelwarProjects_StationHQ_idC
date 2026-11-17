<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>A2 Sticker</title>
  <link rel="stylesheet" href="{{asset('assets/css/sticker_style.css')}}">
  <style>
    .a2-sticker {
        background-image: url("{{ asset('assets/images/newstickers/a2.png') }}"); 
    }
    @media print{
      .sticker-container{
        margin-top: 20px;
        margin-left: 20px;
      }
      .content-position-a2 {
        top: 38.9%;
        left: 53%;
      }
      .year-a2 {
        font-size: 60px;
        color: #fff;
        font-family: 'sutonnyMJ', sans-serif;
        font-weight: 900;
        margin: 0 2px;
      }
      .serial-a2 {
        top: 45%;
        right: -33px;
        color: #fff;
        transform: rotate(-90deg);
      }
    }
  </style>
</head>
@php
  // dd(strlen(formatVehicleText($app->vehicleinfo->reg_number)));
@endphp
<body>
    <div class="sticker-container front-side">
      <div class="sticker-size a2-sticker">
        <div class="content content-position-a2">
          <h1 class="year year-a2" style="font-family: 'SutonnyMJ', sans-serif;">{{ numberConvert(substr($app->sticker_for_year?? '', 0, 2) ) ?: '২০' }}</h1>
          <div class="qr-section" style="width: 70px; height:70px;">
            {!! QrCode::size(70)->generate(route('application.qr', $app->app_number)) !!}
          </div>
          <h1 class="year year-a2">{{ numberConvert(substr($app->sticker_for_year?? '', 2, 2) ) ?: '২৭' }}</h1>
        </div>
        <strong class="serial serial-a2">{{$app->sticker_category}}-{{ $app->sticker_number ?? '88' }}</strong>
        <strong class="vehicle vehicle-position-a2" style="{{strlen(formatVehicleText($app->vehicleinfo->reg_number)) <= 23 ? 'font-size: 21px' : 'font-size:19px'}}">{{ !empty($app->vehicleinfo->reg_number) ? formatVehicleText($app->vehicleinfo->reg_number) : '' }}</strong>
      </div>
    </div>
  <script>
    window.onload = function() {
        window.print();
    };
</script>

</body>
</html>
