<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sticker Payment Money Receipt</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Raleway:ital,wght@0,100..900;1,100..900&family=Tiro+Bangla:ital@0;1&display=swap"
        rel="stylesheet">

    <style>
        /* RESET */
        * {
            box-sizing: border-box;
            font-family: "Montserrat", sans-serif;
            font-optical-sizing: auto;
        }

        body {
            background: #f2f2f2;
            margin: 0;
            padding: 20px;
        }

        /* A4 PAGE */
        .page {
            width: 50%;
            /* min-height: 297mm; */
            background: #fff;
            margin: auto;
            padding: 2mm;
            border: 4px solid #000;
        }

        .inner-border {
            border: 4px solid #000;
            padding: 15px;
            height: 100%;
        }

        /* HEADER */
        .logo {
            text-align: center;
            margin-bottom: 10px;
        }

        .logo img {
            width: 130px;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 28px;
            margin: 5px 0px;
        }

        .subtitle {
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        /* INFO GRID */
        .info {
            width: 100%;
            margin-bottom: 15px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .info-row div {
            width: 48%;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 14px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
            font-size: 18px;
        }

        th {
            font-weight: bold;
        }

        .total-row td {
            font-weight: bold;
            text-align: right;
        }

        .total-row td:last-child {
            text-align: center;
        }



        /* NOTE */
        .note {
            margin-top: 20px;
            border: 1px solid #7bbbc5;
            padding: 10px;
            text-align: center;
            color: red;
            font-weight: bold;
            background: #e9f7fa;
        }

        .font{
            font-size: 17px;
        }

        /* PRINT SETTINGS */
        @media print {
            body {
                background: none;
                padding: 0;
            }

            .page {
                /* border: none; */
                margin: 0;
                width: 210mm;
                /* min-height: 297mm; */
            }
            th,
            td {
                border: 1px solid #000;
                padding: 6px;
                text-align: center;
                font-size: 18px !important;
            }
            .font{
                font-size: 17px !important;
            }
            /* INFO GRID */
        .info {
            width: 100%;
            margin-bottom: 15px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .info-row div {
            width: 48%;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 14px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
            font-size: 18px;
        }

        th {
            font-weight: bold;
        }

        .total-row td {
            font-weight: bold;
            text-align: right;
        }

        .total-row td:last-child {
            text-align: center;
        }



        /* NOTE */
        .note {
            margin-top: 20px;
            border: 1px solid #7bbbc5;
            padding: 10px;
            text-align: center;
            color: red;
            font-weight: bold;
            background: #e9f7fa;
        }
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .page {
                width: 100%;
                min-height: auto;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="page">
        <div class="inner-border">

            <!-- LOGO -->
            <div class="logo">
                <!-- Replace src with your actual image -->
                <img src="{{asset('assets/images/img/logoonly.png')}}" alt="Logo">
            </div>

            <div class="title">STICKER PAYMENT MONEY RECEIPT</div>
            <div class="subtitle">Station Headquarters Dhaka</div>

            <!-- INFO -->
            <div class="info">

                <div class="info-row">
                    <div>
                        <strong style="font-size: 17px;">Invoice No:</strong>
                        <span style="font-size: 17px;">StaHQD-{{ empty($application->app_number) ? 'N/A' : $application->app_number }}</span>
                    </div>

                    <div>
                        <strong class="font">Date:</strong>
                        <span class="font">{{ empty($application->payment->created_at) ? 'N/A' : \Carbon\Carbon::parse($application->payment->created_at)->format('d-M-Y') }}</span>
                    </div>
                </div>

                <div class="info-row">
                    <div>
                        <strong class="font">Owner:</strong>
                        @if(!empty($application->applicant) && !empty($application->applicant->name))
                            <span class="font">{{ $application->applicant->name }}</span>
                        @else
                             N/A
                        @endif
                        </div>

                    @if(!empty($application->vehicleinfo) && !empty($application->vehicleinfo->reg_number))
                        <div>
                            <strong class="font"> Reg. No:</strong>
                            <span class="font">{{ $application->vehicleinfo->reg_number }}</span>
                        </div>
                    @endif
                </div>

                <div class="info-row">
                    @if(!empty($application->payment) && !empty($application->payment->type))
                        <div>
                            <strong class="font">Payment Mode:</strong>
                            <span class="font">{{ $application->payment->type }}</span>
                        </div>
                    @endif

                    @if(!empty($transactionId))
                        <div>
                            <strong class="font">TrxID:</strong>
                           <span class="font"> {{ $transactionId }}</span>
                        </div>
                    @endif
                </div>

            </div>

            <!-- TABLE -->
            @if(!empty($application->payment) && !empty($application->payment->credit))
            <table>
                <thead>
                    <tr>
                        <th class="font-2">STICKER TYPE</th>
                        <th class="font-2">FOR YEAR</th>
                        <th class="font-2">AMOUNT (TK.)</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td class="font-2">
                            {{ empty($application->sticker_category) ? 'N/A' : $application->sticker_category }}
                        </td>

                        <td class="font-2">
                            @if(in_array($application->sticker_category, ['A1', 'A2']))
                                {{ empty($application->sticker_for_year) 
                                    ? 'N/A' 
                                    : ($application->sticker_for_year - 1) . ' - ' . $application->sticker_for_year 
                                }}
                            @else
                                {{ empty($application->sticker_for_year) ? 'N/A' : $application->sticker_for_year }}
                            @endif
                        </td>

                        <td class="font-2">
                            {{ number_format($application->payment->credit, 2) }}
                        </td>
                    </tr>

                    <tr class="total-row">
                        <td colspan="2"><strong>Total Amount:</strong></td>
                        <td>
                            <strong class="font-2">{{ number_format($application->payment->credit, 2) }}</strong>
                        </td>
                    </tr>
                </tbody>
            </table>
            @endif


            <!-- NOTE -->
            <div class="note">
                Note: Please show this receipt during sticker collection.
            </div>

        </div>
    </div>

</body>

</html>