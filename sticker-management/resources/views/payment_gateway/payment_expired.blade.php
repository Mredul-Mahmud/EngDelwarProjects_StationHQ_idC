@extends('layouts.customer-master')
@section('content')
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        .payment_gateway {
            width: 75%;
            margin: 30px auto;
        }

        .page {
            border: 1px solid rgb(215, 215, 215);
            box-shadow: 0 0 10px 1px rgba(0, 0, 0, .1);
            border-radius: 6px;
            overflow: hidden;
        }

        .page-title {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            height: 50px;
            background-color: #16A751;
        }

        .page-title.expired-title {
            background-color: #a72016;
        }

        .content {
            display: flex;
        }

        .contain-l {
            width: 60%;
            min-height: 300px;
            padding: 20px 0;
        }

        .contain-r {
            width: 40%;
            background-color: #a72016;
            min-height: 300px;
            border-left: 1px solid rgb(215, 215, 215);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .persional-details .table {
            width: 100%;
        }

        .persional-details .table tr td {
            color: #252625;
            text-align: left;
            padding: 8px 20px;
        }

        .persional-details .table tr td h5 {
            color: #252625;
            font-size: 15px;
        }

        .expired-content {
            padding: 30px 25px;
            text-align: center;
        }

        .expired-content i {
            font-size: 55px;
            margin-bottom: 15px;
        }

        .expired-content h4 {
            margin-bottom: 15px;
            font-size: 20px;
        }

        .expired-content p {
            font-size: 15px;
            line-height: 1.6;
        }

        .expired-content .expire-date,
        .expired-content .contact-number {
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .content {
                flex-direction: column;
            }

            .contain-l,
            .contain-r {
                width: 100%;
            }

            .payment_gateway {
                width: 90%;
            }
        }
    </style>

    <div class="payment_gateway">
        <div class="page">
            <div class="col-12 page-title expired-title">
                <h3>Payment Link Expired</h3>
            </div>

            @if (Session::has('alert'))
                <div class="alert alert-danger text-center" role="alert">
                    {{ Session::get('alert') }}
                </div>
            @endif

            @if (Session::has('message'))
                <div class="alert alert-success text-center" role="alert">
                    {{ Session::get('message') }}
                </div>
            @endif

            <div class="content">
                <!-- Left: Applicant Info -->
                <div class="contain-l">
                    <div class="persional-details">
                        <table class="table">
                            <tr>
                                <td>
                                    <h5>Name:</h5>
                                </td>
                                <td>{{ $application->applicant->name }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <h5>Reg No:</h5>
                                </td>
                                <td>{{ $application->vehicleinfo->reg_number }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <h5>Phone No:</h5>
                                </td>
                                <td>{{ $application->applicant->phone }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <h5>Apply Date:</h5>
                                </td>
                                <td>{{ $application->app_date }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <h5>Vehicle Type:</h5>
                                </td>
                                <td>{{ $application->vehicleType->name }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <h5>National Id:</h5>
                                </td>
                                <td>{{ $application->applicant->applicantDetail->nid_number }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Right: Expired / Dead message -->
                <div class="contain-r">
                    <div class="expired-content">
                        <i class="fa fa-exclamation-triangle"></i>

                        @if ($application->app_status == 'dead')
                            <h4>This Application is No Longer Active</h4>
                            <p>This payment link is not valid for this application.</p>
                        @else
                            <h4>Your Payment Link Expired</h4>
                        @endif

                        @if (!empty($application->applicationNotify->payment_expire_date))
                            <p>
                                Payment expire date:
                                <span class="expire-date">
                                    {{ \Carbon\Carbon::parse($application->applicationNotify->payment_expire_date)->format('d-m-Y') }}
                                </span>
                            </p>
                        @endif

                        <p>Please contact: <span class="contact-number">+012555</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
