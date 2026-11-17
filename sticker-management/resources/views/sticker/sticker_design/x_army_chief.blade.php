<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ex Army Chief Sticker</title>
  <link rel="stylesheet" href="{{asset('assets/css/sticker_style.css')}}">
  <style>
    .army-chief-sticker {
      background-image: url("{{ asset('assets/images/newstickers/x_army_chief.png') }}");
      position: relative;
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }

      .serial-ex {
    top: 50% !important;
    right: 2% !important;
    color: #000;
    transform: rotate(-90deg) !important;
    font-size: 18px;
    }

    .content-position-ex {
        top: 54% !important;
        left: 21% !important;
    }

     .vehicle-position-ex {
        width: 70% !important;
        top: 69% !important;
        left: 50% !important;
        color: #000;
        background-color: rgb(255, 255, 0);
        font-size: 13px;
    }

    @media print{
      .sticker-container{
        margin-left: 20px;
        margin-top: 20px;
      }
    .serial-ex {
    top: 50% !important;
    right: 2% !important;
    color: #000;
    transform: rotate(-90deg) !important;
    font-size: 18px;
    }

    .content-position-ex {
        top: 54% !important;
        left: 21% !important;
    }

     .vehicle-position-ex {
        width: 70% !important;
        top: 69% !important;
        left: 50% !important;
        color: #000;
        background-color: rgb(255, 255, 0);
        font-size: 13px;
    }
    }


  </style>
</head>
<body>
    <div class="sticker-container army-chief-sticker">
        <div class="content content-position-ex">
          <div class="qr-section" style="width: 35px; height:35px; border: 1px solid #bebebe; padding: 2px;">
            {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
          </div>
        </div>
        <strong class="serial serial-ex">{{$app->sticker_category  }}-{{ $app->sticker_number ?? '' }}</strong>
        <strong class="vehicle vehicle-position-ex" style="font-size:{{ getRegNumberFontSize($app->sticker_category, $app->vehicleinfo->reg_number ?? '') }}">{{ !empty($app->vehicleinfo->reg_number) ? $app->vehicleinfo->reg_number : '' }}</strong>
    </div>
  <script>
    window.onload = function() {
        window.print();
    };
</script>
</body>
</html>