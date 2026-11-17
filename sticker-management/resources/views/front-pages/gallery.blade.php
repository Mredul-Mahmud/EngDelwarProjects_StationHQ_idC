@extends('layouts.master')
@section('content')
    @include('layouts.header')
    @include('layouts.new_website.menubar')
    @include('layouts.new_website.buliten')



    <link rel="stylesheet" href="{{ asset('css/lightbox.min.css') }}">

    <script src="{{ asset('css/lightbox.min.js') }}"></script>

    <style>
        body {
            background-image: url('assets/images/STATION_HQ.png');
            background-size: contain;
            background-position: center;
            background-repeat: repeat;
        }

        .vid-thumb i {
            position: absolute;
            z-index: 9;
            margin-top: 24%;
            font-size: 86px;
            opacity: 0.5;
            transform: translateX(-50%);
        }

        .sta_gallery .card {

            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .sta_gallery {
            background-image: url('assets/images/STATION_HQ.png');
            background-size: contain;
            background-position: center;
            background-repeat: repeat;
        }


        .sta_gallery .card p {
            text-align: center;
            font-size: 12px;
            margin-top: 5px;
            padding: 0 5px;
        }
    </style>
    <style>
        .lightbox .lb-data .lb-caption {
            text-align: center;
            font-size: 1.5rem;
            /* make it larger */
            font-weight: bold;
            color: #fff;
            /* optional: make it white */
        }

        .gallery_content {
            /* background-color: rgb(8, 45, 33); */
            color: #e3d700;
            background-image: url('assets/images/STATION_HQ.png');
            background-size: contain;
            background-position: center;
            background-repeat: repeat;
        }
    </style>



    <div class="container mt-4 ">
        @include('layouts.slider')

        <div class="row g-4 d-flex justify-content-center mt-4">
            @foreach ($photo_galleries as $photo_gallery)
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                    <div class="card border-0 shadow-sm"
                        style="background-color: transparent; border: 5px solid #d4c7a1 !important; height: 94% !important;">
                        <a href="{{ asset('assets/images/photo_galleries/' . $photo_gallery->image) }}"
                            data-lightbox="gallery" data-title="{{ $photo_gallery->name }}">
                            <img src="{{ asset('assets/images/photo_galleries/' . $photo_gallery->image) }}"
                                class="card-img-top img-fluid" alt="{{ $photo_gallery->name }}"
                                style="height: 220px; width: 100%; object-fit: cover; border-bottom:5px solid #d4c7a1">
                        </a>
                        <div class="card-body text-center" style="background-color: transparent;">
                            <p class="card-title fw-bold" style="text-transform: uppercase; color: #e3d700;">
                                {{ $photo_gallery->name ?? 'No Title' }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>



    <script>
        $(document).ready(function() {
            $('.gallery_carousel').owlCarousel({
                items: 3, // Number of items to show
                loop: true, // Enable looping of items
                margin: 10, // Space between items
                nav: true, // Enable navigation buttons
                navText: [
                    '<i class="fas fa-chevron-left"></i>', // Previous button icon
                    '<i class="fas fa-chevron-right"></i>' // Next button icon
                ],
                responsive: {
                    0: {
                        items: 1 // For small screens, show 1 item
                    },
                    600: {
                        items: 2 // For medium screens, show 2 items
                    },
                    1000: {
                        items: 3 // For large screens, show 3 items
                    }
                }
            });
        });
    </script>
@endsection
