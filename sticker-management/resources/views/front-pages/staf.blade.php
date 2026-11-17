@extends('layouts.master')
@section('content')
    @include('layouts.header')
    @include('layouts.new_website.menubar')
    @include('layouts.new_website.buliten')

    <style>
        body {
            /* background: url('bg-texture.jpg') repeat; */
            /* Replace with your grunge background image */
            /* background-color: #1e1f17; */
            color: #fff;
            font-family: Arial, sans-serif;
            background-image: url('assets/images/STATION_HQ.png');
            background-size: contain;
            background-position: center;
            background-repeat: repeat;
        }

        /* Station Commander Section */
        .commander-card {
            text-align: center;
            padding: 20px;
            background: #1e1f17;
            border-radius: 15px;
            box-shadow: 0 0 15px #485627;
            margin-bottom: 30px;
            width: 350px;
            margin: 0 auto;
        }

        /* .commander-card img {
            width: 100%;
            border-radius: 8px;
            border: 2px solid #444;
            margin-bottom: 15px;
            box-shadow: 0 0 15px #485627;
            max-width: 250px;
        } */

        .commander-card h4 {
            font-size: 20px;
            font-weight: bold;
            color: #aaff77;
        }

        .commander-info {
            font-size: 14px;
            margin: 2px 0;
        }

        .read-more {
            color: #a8ff60;
            font-weight: bold;
            font-size: 14px;
            text-decoration: none;
        }

        .read-more:hover {
            text-decoration: underline;
        }

        /* Officer Card */
        .officer-card {
            background: #3c3d32;
            border-radius: 10px;
            padding: 15px;
            display: flex;
            align-items: flex-start;
            /* box-shadow: 0 0 10px rgba(0, 255, 0, 0.2); */
            min-height: 160px;
        }

        .officer-card img {
            width: 80px;
            height: auto;
            border-radius: 6px;
            margin-right: 15px;
            border: 2px solid #333;
        }

        .officer-details {
            flex: 1;
        }

        .officer-title {
            font-weight: bold;
            font-size: 16px;
            color: #aaff77;
            margin-bottom: 5px;
        }

        .officer-info {
            font-size: 13px;
            color: #ddd;
            margin: 0;
        }

        .officer-card .read-more {
            display: inline-block;
            margin-top: 5px;
        }

        .label {
            color: #a5c333;
            display: inline-block;
            width: 70px;
            /* adjust until it looks perfect */
            margin-right: 10px;
        }

        .present-commander-img {
            width: 100%;
            height: 100%;
            border-radius: 2px;
            box-shadow: 0 0 12px 4px rgb(235 239 13 / 50%);
            object-fit: cover;

        }

        .mjaor-info{
            font-size: 16px;
            font-weight: bold;
        }
    </style>


    <div class="container py-4">

        <!-- Station Commander -->

        <div class="row justify-content-center mb-4">
            <div class="col-md-6 col-lg-4">
                <div class="commander-card p-0">
                    <div style="width: 350px !important; height: 350px !important; overflow: hidden;">
                        <img class="present-commander-img"
                        src="{{ asset('assets/images/commander/' . $present_commander->image) }}" alt="Station Commander">
                    </div>
                    <div class="p-3 text-center">
                        <h4>{{ $present_commander->title ?? '' }}</h4>
                        <p class="commander-info">BA - {{ $present_commander->ba ?? '' }}
                            {{ $present_commander->rank ?? '' }} {{ $present_commander->name ?? '' }}</p>

                    </div>
                </div>
            </div>
        </div>



        <div class="row d-flex justify-content-center">
            @foreach ($majors as $major)
                <div class="col-sm-12 col-md-6 mb-3 d-flex">
                    <div class="officer-card card flex-fill">
                        <div class="row no-gutters h-100 align-items-center">
                            <div class="col-12 col-md-4">
                                <img src="{{ asset('assets/images/majors/' . $major->image) }}" alt="{{ $major->sso }}"
                                    class="img-fluid w-100" style="object-fit: cover; border-radius: 8px 0 0 8px;">
                            </div>
                            <div class="col-12 col-md-8 d-flex flex-column p-3">
                                <h5 class="mb-1" style="color: #a5c333">{{ $major->sso }}</h5>
                                <p class="mb-1"><strong>Mill Ext </strong> : {{ $major->telephone ?? '' }}</p>
                                <hr class="my-1" style="border-top:3px solid #868774;">

                               

                                <p class="mjaor-info">BA - {{ $major->ba ?? '' }} {{ $major->rank ?? '' }}
                                    {{ $major->name ?? '' }}</p>


                                <p class="mb-2 mt-2" style="color: #a5c333"><strong>Major Responsibilities</strong></p>
                                <p class="flex-grow-1">{{ $major->short_description }}</p>

                                <div class="text-right mt-auto">
                                    <a href="{{ route('major', [$major->id, $major->rank]) }}" class="read-more"
                                        style="color:#ff0; font-weight:600; text-decoration:none;">
                                        Read More
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>


        {{-- <div class="row d-flex justify-content-center">
            @foreach ($majors as $major)
                <div class="col-sm-12 col-md-6 mb-3 d-flex">
                    <div class="officer-card card flex-fill shadow-sm"
                        style="border-radius: 12px; overflow: hidden; background:#2e3024; color:#fff;">
                        <div class="row no-gutters h-100 align-items-center">

                            <!-- Officer Image -->
                            <div class="col-4 p-2 d-flex justify-content-center align-items-center">
                                <img src="{{ asset('assets/images/majors/' . $major->image) }}" alt="{{ $major->sso }}"
                                    class="img-fluid"
                                    style="object-fit: cover; border-radius: 12px; border:3px solid #fff;">
                            </div>

                            <!-- Officer Info -->
                            <div class="col-8 d-flex flex-column p-3">
                                <h5 class="mb-1 text-warning">{{ $major->sso }}</h5>
                                <p class="mb-1"><strong>Mill Ext :</strong> {{ $major->mill_ext ?? '' }}</p>
                                <hr class="my-1" style="border-top:1px solid rgba(255,255,255,.2);">

                                <p class="mb-1"><strong>BA :</strong> {{ $major->ba ?? '' }}</p>
                                <p class="mb-1"><strong>Rank :</strong> {{ $major->rank ?? '' }}</p>
                                <p class="mb-1"><strong>Name :</strong> {{ $major->name ?? '' }}</p>

                                <p class="mb-1 text-warning"><strong>Major Responsibilities</strong></p>
                                <p class="flex-grow-1">{{ $major->short_description }}</p>

                                <div class="text-right mt-auto">
                                    <a href="{{ route('major', [$major->id, $major->rank]) }}" class="read-more"
                                        style="color:#ff0; font-weight:600; text-decoration:none;">
                                        Read More
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div> --}}



    </div>
@endsection
