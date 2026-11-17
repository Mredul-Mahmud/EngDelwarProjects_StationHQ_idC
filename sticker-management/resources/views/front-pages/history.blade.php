@extends('layouts.master')
@section('content')
    @include('layouts.header')
    @include('layouts.new_website.menubar')
    @include('layouts.new_website.buliten')

    <style>
        body {
            background-image: url('assets/images/STATION_HQ.png');
            background-size: contain;
            background-position: center;
            background-repeat: repeat;
        }

        .welcome ul {
            padding-left: 50px;
            margin-bottom: 16px;
            list-style-image: url('{{ asset('/assets/images/arrow-1.png') }}');
        }

        .welcome ul li {
            list-style: unset;
            /*list-style-type: circle;*/
            margin-bottom: 0px;
            padding-bottom: 3px;
        }

        .card {
            transition: transform 0.3s, box-shadow 0.3s;
            /* Smooth transition */
        }

        .card:hover {
            transform: scale(1.05);
            /* Slightly scale up the card */
            box-shadow: 0 0 10px 0 rgba(228, 66, 66, 0.5);
            /* Increase the shadow */
        }

        .top_section_history {
            background-color: #3c3d32 !important;
            background-size: cover;
            padding: 50px 0;
            color: #fff;
            border-radius: 10px;
            padding: 5px;
            margin: 2px;
        }

        .welcome {
            color: #f1f1ef !important;
        }

        .team-section-2 {
            background-image: url('assets/images/STATION_HQ.png');
            background-size: contain;
            background-position: center;
            background-repeat: repeat;
        }
    </style>


    <section class="top_section_history">
        <div class="welcome-section ">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 welcome">
                        <h3 class="text-uppercase" style="text-align: center;text-decoration: underline; color: #fff;">
                            History & Activities of Station Headquarters Dhaka
                        </h3>
                        {!! $present_commander->description !!}
                    </div>
                </div>
            </div>
        </div>
    </section>


   

    <div class="container-fluid p-0 m-0" style="">
        <div class="team-section-2">
            <div class="container-fluid" style="color:#42916b;">
                <h1 class="text-center pt-5 text-uppercase" style="margin-top: 1rem;">Board of Honor</h1>

                <!-- Nav Pills and Content in same container -->
                <div class="container">
                    <!-- Nav Pills -->
                    <ul class="nav nav-pills mb-3 justify-content-center mx-auto" id="pills-tab" role="tablist"
                        style="max-width: 500px;">
                        <li class="nav-item flex-fill text-center" role="presentation">
                            <button class="nav-link active w-100" id="pills-home-tab" data-toggle="pill"
                                data-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                                aria-selected="true">
                                EX Station Commanders
                            </button>
                        </li>
                        <li class="nav-item flex-fill text-center" role="presentation">
                            <button class="nav-link w-100" id="pills-profile-tab" data-toggle="pill"
                                data-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile"
                                aria-selected="false">
                                Ex Station Staff Officers
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="pills-tabContent">
                        <!-- Grid View -->
                        <div class="tab-pane fade active show" id="pills-home" role="tabpanel"
                            aria-labelledby="pills-home-tab">
                            <div class="row justify-content-center">
                                @foreach ($exComds as $exComd)
                                    <div class="col-12 col-sm-6 col-md-3 d-flex align-items-stretch justify-content-center">
                                        <div class="card m-3" style="width: 20rem; background-color: transparent;">
                                            <img style="border: 5px groove gold; height: 250px; width: 100%; object-fit: cover;"
                                                src="{{ asset('assets/images/excomd/' . $exComd->image) }}"
                                                class="card-img-top img-fluid m-0 p-0" alt="{{ $exComd->image }}">
                                            <div class="card-body text-center p-1 mt-3">
                                                <p class="card-title">
                                                    <span style="color:#cfcfb4">{{ $exComd->rank ?? '' }}</span> <br>
                                                    <span style="color: #cfcfb4"> {{ $exComd->name ?? '' }}
                                                    </span><br>
                                                    <span style="color: #cfcfb4">
                                                        {{ $exComd->duration ? '(' . $exComd->duration . ')' : '' }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>

                        <!-- Table View -->
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                            <div class="row justify-content-center">
                                <div class="col-lg-10 col-md-12">
                                    <div class="mb-3 row align-items-center">
                                        <label for="appointmentFilter" class="col-12 col-sm-auto mb-2 mb-sm-0">Filter by
                                            Appointment:</label>
                                        <div class="col-12 col-sm-auto">
                                            <select id="appointmentFilter" class="form-control">
                                                <option value="">All</option>
                                                @foreach ($exSsos->pluck('appointment')->unique() as $appointment)
                                                    <option value="{{ $appointment }}">{{ $appointment }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        @php
                                            // $ssos = ['SSO 1', 'SSO 2', 'SSO 3', 'SSO 4', 'STO'];
                                        @endphp
                                        <table class="table table-bordered table-striped">
                                            <thead class="text-info">
                                                <tr>
                                                    <th>SL No:</th>
                                                    <th>BA NO</th>
                                                    <th>Rank & Name</th>
                                                    <th>Appointment</th>
                                                    <th>Duration</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-light">
                                                @foreach ($exSsos as $index => $exSso)
                                                    <tr data-appointment="{{ $exSso->appointment }}">
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $exSso->rank ?? '' }}</td>
                                                        <td>{{ $exSso->name ?? '' }}</td>
                                                        <td>{{ $exSso->appointment ?? '' }}</td>
                                                        <td>{{ $exSso->duration ?? '' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('appointmentFilter').addEventListener('change', function() {
            var selectedValue = this.value;
            var rows = document.querySelectorAll('#pills-profile tbody tr');

            rows.forEach(function(row) {
                var appointment = row.getAttribute('data-appointment');
                if (selectedValue === '' || appointment === selectedValue) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
@endsection
