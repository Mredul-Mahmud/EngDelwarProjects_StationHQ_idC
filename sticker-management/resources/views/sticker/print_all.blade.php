<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sticker Print</title>
    <link rel="stylesheet" href="{{ asset('assets/css/sticker_style.css') }}">
    <style>
        .a1-sticker {
            background-image: url("{{ asset('assets/images/newstickers/a1.png') }}");
        }

        .a2-sticker {
            background-image: url("{{ asset('assets/images/newstickers/a2.png') }}");
        }

        .a3-sticker {
            background-image: url("{{ asset('assets/images/newstickers/a3.png') }}");
        }

        .a4-staff-sticker {
            background-image: url("{{ asset('assets/images/newstickers/a4.png') }}");
        }

        .a4-sticker {
            background-image: url("{{ asset('assets/images/newstickers/a4_all.png') }}");
        }

        .b1-sticker {
            background-image: url("{{ asset('assets/images/newstickers/b1.png') }}");
        }

        .b2-sticker {
            background-image: url("{{ asset('assets/images/newstickers/b2.png') }}");
        }

        .b3-sticker {
            background-image: url("{{ asset('assets/images/newstickers/b3.png') }}");
        }

        .b4-sticker {
            background-image: url("{{ asset('assets/images/newstickers/b4.png') }}");
        }

        .b5-sticker {
            background-image: url("{{ asset('assets/images/newstickers/b5.png') }}");
        }

        .d1-sticker {
            background-image: url("{{ asset('assets/images/newstickers/d1.png') }}");
        }

        .d2-sticker {
            background-image: url("{{ asset('assets/images/newstickers/d2.png') }}");
        }

        .e-sticker {
            background-image: url("{{ asset('assets/images/newstickers/e.png') }}");
        }

        .f-sticker {
            background-image: url("{{ asset('assets/images/newstickers/f.png') }}");
        }

        .m-sticker {
            background-image: url("{{ asset('assets/images/newstickers/m.png') }}");
        }

        .m1-sticker {
            background-image: url("{{ asset('assets/images/newstickers/m1.png') }}");
        }

        .t-sticker {
            background-image: url("{{ asset('assets/images/newstickers/t.png') }}");
        }

        .t1-sticker {
            background-image: url("{{ asset('assets/images/newstickers/t1.png') }}");
        }

        .ex-sticker {
            background-image: url("{{ asset('assets/images/newstickers/x_army_chief.png') }}");
            position: relative;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .print-page {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            place-content: center center;
            gap: 16px;
        }

        /* Print styles for A4 paper */
        @media print {
            @page {
                size: A4;
                /* padding: 5mm; */
                page-break-after: always;
                margin: 0;
                padding: 0;
                margin-top: 8px;

            }


            .print-page {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                /* grid-auto-rows: 346px; */
                place-content: center center;
                gap: 16px;
            }

            .sticker-container {
                margin: 0px auto;
                break-inside: avoid;
                page-break-inside: avoid;
            }

            .qr-section-a2 {
                width: 70px;
                height: 70px;
                background-color: #fff;
                padding: 3px;
            }

            /*
       .sticker-container:nth-child(6n) {
        page-break-after: always;
      }
      
      ::-webkit-scrollbar {
        display: none;
      } */
        }
    </style>
</head>

<body>
    @php
    @endphp
    <div class="print-page">
        @foreach ($applications as $app)
            <div class="sticker-container">
                <div
                    class="sticker-size {{ getLowerStickerCategory($app->sticker_category) }}-sticker">
                    <div
                        class="content content-position-{{  getLowerStickerCategory($app->sticker_category) }}">
                        @if ($app->sticker_category == 'Ex')
                        <div class="qr-section"
                        style="width: 35px; height:35px; border: 1px solid #bebebe; padding: 2px;">
                            {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
                        </div>
                        @else
                        <h1 style="margin-right:8px;"
                            class="year year-{{  getLowerStickerCategory($app->sticker_category) }}">
                            {{ numberConvert(substr($app->sticker_for_year ?? '', 0, 2)) ?: '২০' }}</h1>
                        <div class="{{ $app->sticker_category == 'A2' ? 'qr-section-a2' : 'qr-section' }}"
                            style="{{ $app->sticker_category == 'M' || $app->sticker_category == 'M1' ? 'width: 50px; height:50px;' : '' }}">
                            {!! QrCode::size(60)->generate(route('application.qr', $app->app_number)) !!}
                        </div>
                        <h1
                            class="year year-{{  getLowerStickerCategory($app->sticker_category) }}">
                            {{ numberConvert(substr($app->sticker_for_year ?? '', 2, 2)) ?: '২৭' }}</h1>
                        @endif
                    </div>
                    <strong class="serial serial-{{  getLowerStickerCategory($app->sticker_category) }}">{{ formatStickerNumber($app) }}</strong>
                    <strong
                        class="vehicle vehicle-position-{{  getLowerStickerCategory($app->sticker_category) }}"
                        style="font-size:{{ getRegNumberFontSize($app->sticker_category, $app->vehicleinfo->reg_number ?? '') }}">{{ !empty($app->vehicleinfo->reg_number) ? formatVehicleText($app->vehicleinfo->reg_number) : '' }}</strong>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>

</html>
