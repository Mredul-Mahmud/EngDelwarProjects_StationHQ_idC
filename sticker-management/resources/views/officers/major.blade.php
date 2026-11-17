@extends('layouts.master')
@section('content')
    @include('layouts.header')
    @include('layouts.new_website.menubar')
    @include('layouts.new_website.buliten')

    {{-- @include('layouts.newsticker') --}}

    <style>
        .os1-summary strong.site-color-1 {
            color: #31a650;
        }

        .os1-summary h3,
        .os1-summary h6,
        .os1-summary strong {
            color: #a23021;
        }

        .page-content {
            color: #97dbbc;
        }
        .res p strong{
            color: #cfcfb4;
        }
    </style>
    <div class="single-page-content-wrap section-paddinghjh">
        <div class="container">
            <div class="row">

                <!-- page-content -->
                <div class="col-lg-12 page-content m-2">

                    <div class="row officer-section-1 section-padding">
                        <div class="col-lg-5">
                            <figure class="os1-fig">
                                <img src="{{ asset('assets/images/majors/' . $major->image) }}" alt="{{ $major->name }}">
                            </figure>
                        </div>

                        <div class="col-lg-7 text-white">
                            <div class="os1-summary">

                                <h4 class="text-white">{{ $major->sso }}</h4>
                                {{-- <p>
                                    BA - {{ $major->ba }}<br>
                                    Rank - {{ $major->rank }}<br>
                                    Name - {{ $major->name }}<br>
                                </p> --}}
                                 <p class="commander-info">BA - {{ $major->ba ?? ''}} {{ $major->rank ?? ''}} {{ $major->name ?? ''}}</p>
                        

                                <h6 style="color: #fff">Major Responsibilities:</h6>


                                <div class="text-white res" style="color: #fff">
                                    {!! $major->responsibility !!}
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
