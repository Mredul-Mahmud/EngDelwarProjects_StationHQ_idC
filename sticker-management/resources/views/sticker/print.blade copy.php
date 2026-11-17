<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    {{-- <h1>Sticker Print for Application Number: {{ $app->app_number }}</h1> --}}
    <div class="col-md-3">
        <span>Vehicle Registration No. : </span>
        <span>{{ !empty($app->vehicleinfo->reg_number) ? $app->vehicleinfo->reg_number : '' }}</span>
    </div>
    <div class="col-md-3">
        <span>RFID No. : </span> <span>{{ !empty($app->rf_id_no) ? $app->rf_id_no : '' }}</span>
    </div>
    <div class="col-md-3">
        <span>Sticker Type : </span> <span>{{ !empty($app->sticker_category) ? $app->sticker_category : '' }}</span>
    </div>
    <div class="col-md-9">

    </div>
    <div class="col-md-3">
        <span>QR Code</span>
    </div>
    <div class="col-md-9">
        {!! QrCode::size(100)->generate(route('application.qr', $app->app_number)) !!}
    </div>
    <div class="col-md-1">
       
         <div style="width: 100%; max-width: 300px; padding: 10px; border: 1px solid #ccc; display: flex; justify-content: center; align-items: center; background: white; overflow: hidden;">

            @php
                 $url = url('qr/' . $app->app_number); 
                // $url = route('application.qr', $app->app_number);
            @endphp

            <div style="transform: scale(1.5); transform-origin: left; white-space: nowrap;">
                {{-- {!! DNS1D::getBarcodeHTML($url, 'C128', 1, 30) !!} --}}
            </div>
        </div>
    </div>
    <div class="col-md-9">
        
        <div style="width: 100%; max-width: 300px; padding: 10px; border: 1px solid #ccc; display: flex; justify-content: center; align-items: center; background: white;">

            @php
                 $url = url('qr/' . $app->app_number); 
                
            @endphp

            <div style="transform: scale(1.5); transform-origin: left; white-space: nowrap;">
                {{-- {!! DNS1D::getBarcodeHTML($url, 'C128', 1, 60) !!} --}}
            </div>
        </div>
    </div>
</body>

</html>
