@extends('layouts.master')
@section('content')
    @include('layouts.header')
    @include('layouts.new_website.menubar')
    @include('layouts.new_website.buliten')

    {{-- @include('layouts.newsticker') --}}
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
            margin: 10px 0px;
            padding-bottom: 3px;
            text-align:  left !important;
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
            color: #cfcfb4
        }

        .team-section-2 {
            background-image: url('assets/images/STATION_HQ.png');
            background-size: contain;
            background-position: center;
            background-repeat: repeat;
        }
        .welcome p{
            font-size: 16px;
            line-height: 32px;
            text-align: justify;
            margin-top: 28px;
        }
    </style>
    <div class="single-page-content-wrap section-padding">
        <div class="container">
            <div class="row">

                <!-- page-content -->
                <div class="col-lg-12 page-content m-2">

                    <div class="row officer-section-1 section-padding">
                        <div class="col-lg-5">
                            <figure class="os1-fig">
                                <img src="{{ asset('assets/images/commander/' . $present_commander->image) }}"
                                    alt="{{ $present_commander->name }}">
                            </figure>
                        </div>

                        <div class="col-lg-7 text-white">
                            <div class="os1-summary">
                                  
                                
                                <h3 style="color: #a5c333; font-size: 22px; color:#fff"><b>BA - </b> {{ $present_commander->ba }}
                                {{ $present_commander->rank }}
                                {{ $present_commander->name }} </h3>
                                <h3 style="font-size:18px; margin-top: 20px; color:#fff;border: 1px solid #a5c333; display: inline-block; padding: 6px;">{{ $present_commander->title }}</h3>
                             
                        
                                <div class="col-md-12 welcome" style="color:#fff">
                                    
                                    {!! $present_commander->description !!}
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
