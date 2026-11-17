@extends('layouts.master')
@section('content')
    {{-- @include('layouts.header') --}}
    @include('layouts.new_website.slider')
    @include('layouts.new_website.menubar')
    @include('layouts.new_website.buliten')
    {{-- @include('layouts.slider') --}}

    <style>
        .vid-thumb img {
            width: 100%;
            height: auto;
            max-height: 320px;
            object-fit: cover;
        }

        .vid-thumb {
            cursor: pointer;
            position: relative;
        }

        .vid-thumb h1 {
            background: #fff;
            margin-top: -17px;
            font-size: 16px;
            padding: 14px 10px;
        }

        .vid-thumb i {
            position: absolute;
            z-index: 9;
            top: 50%;
            left: 50%;
            font-size: 60px;
            opacity: 0.7;
            transform: translate(-50%, -50%);
            color: #fff;
        }

        .present-commander-img {
            width: 100%;
            max-width: 200px;
            height: auto;
            border-radius: 15px;

            box-shadow: 0 0 12px 4px rgb(235 239 13 / 50%);
            /* box-shadow: 0 4px 12px rgba(204, 226, 104, 0.3); */

            object-fit: cover;

            border: 3px solid #eeeaea;

        }

        .notice {
            /* background-color: #383232; */
            padding: 20px 0;
            background-image: url('assets/images/STATION_HQ.png');
            background-size: contain;
            background-position: center;
            background-repeat: repeat;
        }
        
        @media (max-width: 768px) {
            .welcome-section {
                padding: 20px 0;
            }
            .section-padding {
                padding-top: 20px;
                padding-bottom: 20px;
            }
            .vid-thumb i {
                font-size: 40px;
            }
        }
    </style>
    <style>
        .welcome-section {
            background-color: #1e1f17;
            /* padding: 40px 0; */
        }


        .card {
            background: transparent;
            border: none;
            box-shadow: none;
        }



        .card-body p {
            color: #d2d2b7 !important;
            font-size: 17px;
            font-weight: 400;
            line-height: 1.8;
            /* text-align: justify;             */
        }

        .commander-description a {
            color: #ffd700;
            font-weight: bold;
            text-decoration: none;
            transition: color 0.3s;
        }
        .commander-description p {
            color: #ffffff !important;
            font-size: 16px;
            
        }

        .commander-description a:hover {
            color: #ff8800;
            text-decoration: underline;
        }

        .notice-section {
            /* background-color: #3b3939; */
            background-image: url('assets/images/STATION_HQ.png');
        }
    </style>

    <style>
        .custom-card {
            background: linear-gradient(to bottom, #21750e, #0c120b);
            color: white;
            border-radius: 12px;
            border: 4px solid #dedca2;
            box-shadow: 0 0 10px 4px rgba(136, 219, 98, 0.4);
        }

        .custom-card h4 {
            color: #f5f5f5;
            font-weight: bold;
        }

        .custom-card hr {
            border: 1px solid #ccc;
            margin: 10px auto;
            width: 60%;
        }

        .custom-card ul li {
            font-size: 16px;
            margin-bottom: 6px;
        }

        .read-more {
            display: inline-block;
            margin-top: 10px;
            color: yellow;
            font-weight: bold;
            text-decoration: none;
        }

        .read-more:hover {
            text-decoration: underline;
        }

        /* show only first 3 items */
        .collapse-list li {
            display: none;
        }

        .collapse-list li:nth-child(-n+3) {
            display: list-item;
        }
    </style>


    <div class="welcome-section section-padding" style="padding: 0; padding-top: 20px;">
        <div class="container-fluid">
            <div class="row d-flex justify-content-center align-items-stretch">
                <!-- Commander Card -->
                <div class="col-md-4 col-sm-12 mb-4 d-flex">
                    <div class="card shadow-lg h-100 w-100">
                        <div class="card-body text-center">
                            <img src="{{ asset('assets/images/commander/' . $present_commander->image) }}"
                                alt="Present Commander" class="present-commander-img mb-3">
                            <h3 style="color: #a5c333; font-size: 18px;"><b>BA-</b>{{ $present_commander->ba }}
                                {{ $present_commander->rank }}
                                {{ $present_commander->name }} </h3>
                            <p style="color: #d2d2b7; font-size: 16px;">{{ $present_commander->title }}</p>
                             
                        </div>
                      
                    </div>
                </div>

                <!-- Welcome Card -->
                <div class="col-md-6 col-sm-12 mb-4 d-flex">
                    <div class="card shadow-lg h-100 w-100">
                        <div class="card-body commander-description">
                            <h3 style="color: #a5c333;text-align:center;">
                                Station Commander's Message<br>

                            </h3>

                            <p class="" style="text-align: justify; ">
                                {!! substr($present_commander->description, 0, 585) !!}...
                                {{-- <a href="{{ url('/') }}/about-station-headquarters">Read More</a> --}}
                            </p>
                            <div class="text-right mt-auto">
                                    <a href="{{ route('commender_details') }}" class="read-more"
                                        style="color:#ff0; font-weight:600; text-decoration:none;">
                                        Read More
                                    </a>
                                </div>
                        </div>
                         
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="notice">
        <div class="container  my-1">
            <div class="row g-4">

             
                <div class="col-md-4 col-sm-6 mt-4">
                    <div class="card custom-card h-100">
                        <div class="card-body d-flex flex-column">
                            <h4 class="card-title text-center">Notice</h4>
                            <hr>
                            <ul class="widget-menu collapse-list">
                                @foreach ($pdf_files as $pdf_file)
                                    @if ($pdf_file->type == 'notice')
                                        <li>
                                            <a href="{{ asset('assets/pdf_files/' . $pdf_file->file) }}" target="_blank">
                                                <i class="fa fa-external-link-alt"></i> {{ $pdf_file->name }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                            <div class="text-right mt-auto">
                                <a href="javascript:void(0)" class="read-more">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="col-md-4 col-sm-6 mt-4">
                    <div class="card custom-card h-100">
                        <div class="card-body d-flex flex-column">
                            <h4 class="card-title text-center">News & Events</h4>
                            <hr>
                            @php
                                $newsEventsFiles = $pdf_files->filter(function ($item) {
                                    return $item->type === 'news_events';
                                });
                            @endphp

                            <ul class="widget-menu collapse-list">
                                @forelse ($newsEventsFiles as $pdf_file)
                                    <li>
                                        <a href="{{ asset('assets/pdf_files/' . $pdf_file->file) }}" target="_blank">
                                            <i class="fa fa-external-link-alt"></i> {{ $pdf_file->name }}
                                        </a>
                                    </li>
                                @empty
                                    <li>No list available now</li>
                                @endforelse
                            </ul>

                            <div class="text-right mt-auto">
                                <a href="javascript:void(0)" class="read-more">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="col-md-4 col-sm-6 mt-4">
                    <div class="card custom-card h-100">
                        <div class="card-body d-flex flex-column">
                            <h4 class="card-title text-center">Policy & Forms</h4>
                            <hr>
                            <ul class="widget-menu collapse-list">
                                @foreach ($pdf_files as $pdf_file)
                                    @if ($pdf_file->type == 'policy')
                                        <li>
                                            <a href="{{ asset('assets/pdf_files/' . $pdf_file->file) }}" target="_blank">
                                                <i class="fa fa-external-link-alt"></i> {{ $pdf_file->name }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                                @foreach ($pdf_files as $pdf_file)
                                    @if ($pdf_file->type == 'forms')
                                        <li>
                                            <a href="{{ asset('assets/pdf_files/' . $pdf_file->file) }}" target="_blank">
                                                <i class="fa fa-external-link-alt"></i> {{ $pdf_file->name }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                            <div class="text-right mt-auto">
                                <a href="javascript:void(0)" class="read-more">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 1 -->
                {{-- <div class="col-md-4 col-sm-6 mt-4 apply-sticker">
                    <div class="card custom-card h-100">
                        <div class="card-body ">
                            <h4 class="card-title text-center">Apply For Sticker</h4>
                            <hr>
                            <ul class="widget-menu">
                                @if ($header_footer->is_sticker_login == 'yes')
                                    <li class="{{ request()->is('customer/login') ? 'active' : '' }}">
                                        <a href="{{ route('customer.login') }}"><i
                                                class="fa fa-external-link-alt"></i>Login</a>
                                    </li>
                                @endif

                                @if ($header_footer->is_sticker_register == 'yes')
                                    <li class="{{ request()->is('customer/register') ? 'active' : '' }}">
                                        <a href="{{ route('customer.register') }}"><i
                                                class="fa fa-external-link-alt"></i>Register</a>
                                    </li>
                                @endif

                                @if ($header_footer->is_sticker_policy == 'yes')
                                    <li class="{{ request()->is('policy') ? 'active' : '' }}">
                                        <a href="{{ url('/policy') }}"><i class="fa fa-external-link-alt"></i>Policy</a>
                                    </li>
                                @endif
                                @if ($header_footer->is_sticker_policy == 'yes')
                                    <li class="{{ request()->is('policy') ? 'active' : '' }}">
                                        <a href="{{ url('/policy') }}"><i class="fa fa-external-link-alt"></i>Policy</a>
                                    </li>
                                @endif

                                @if ($header_footer->login == 'yes')
                                    <li class="{{ request()->is('login') ? 'active' : '' }}">
                                        <a href="{{ url('/login') }}"><i class="fa fa-external-link-alt"></i>Sticker Info
                                            Check</a>
                                    </li>
                                @endif
                            </ul>
                            <a href="#" class="read-more">Read More</a>
                        </div>
                    </div>
                </div> --}}

                <!-- Card 2 -->
                {{-- <div class="col-md-4 col-sm-6 mt-4">
                    <div class="card custom-card h-100">
                        <div class="card-body text-center">
                            <h4 class="card-title">News & Events</h4>
                            <hr>
                            <ul class="list-unstyled text-start">
                                <li>Army Chief Welcomes Interim Govt</li>
                                <li>DOHS Executive Committee Election 23</li>
                            </ul>
                            <a href="#" class="read-more">Read More</a>
                        </div>
                    </div>
                </div> --}}

                <!-- Card 3 -->
                {{-- <div class="col-md-4 col-sm-6 mt-4">
                    <div class="card custom-card h-100">
                        <div class="card-body text-center">
                            <h4 class="card-title">Policy & Forms</h4>
                            <hr>
                            <ul class="list-unstyled text-start">
                                <li>Unit CO Certificate</li>
                                <li>Job Certificate</li>
                                <li>House Owner/CEO Certificate</li>
                            </ul>
                            <a href="#" class="read-more">Read More</a>
                        </div>
                    </div>
                </div> --}}

                <!-- Add more cards similarly -->
            </div>
        </div>
    </section>







    <div id="sta_gallery" class="section-padding "
        style="background-image: url('/videos/video-bj.jpg');background-size: cover; background-color: #1e1f17;">
        <div class="container text-center">
            {{-- <h2 class="section-title">Varoius Activites </h2> --}}
            <h2 class="section-title border-bottom border-3 border-success d-inline-block pb-2 ">
                Various Activities
            </h2>
            <div class="row justify-content-center gx-5">
                <div class="col-lg-5 col-md-5 col-12 mb-4 sta_gallery-wrap text-center">
                    {{-- <h2 class="section-title">DOHS Executive Committee Election 2023 </h2> --}}

                    <div class="row">

                        <div class="col-sm-12 sta_gallery owl-carousel owl-theme gallery_carousel">
                            @foreach ($photo_galleries as $photo_gallery)
                                <div class="gall-img" style="height: 300px; overflow: hidden;">
                                    <img src="{{ asset('assets/images/photo_galleries/' . $photo_gallery->image) }}"
                                        alt="{{ $photo_gallery->name }}" class="img-fluid w-100" style="width: 100%; height: 300px; object-fit: cover;">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                  <div class="col-lg-2 d-none d-lg-block"></div>
                <div class="col-lg-5 col-md-5 col-12 mb-4 sta_gallery-wrap text-center">
                    

                    <div class="row">

                        <div class="col-sm-12 sta_gallery owl-carousel owl-theme gallery_carousel">

                            <div class="gall-img vid-thumb" data-fancybox="gallery"
                                data-caption="ডিওএইচএস পিরিষদ কার্যনির্বাহী কমিটি নির্বাচন -২০২৩"
                                href="{{ url('/videos/DOHS-VOTING.mp4') }}" style="height: 300px; overflow: hidden;">
                                <i class="fa fa-play-circle"></i>
                                <img src="{{ asset('/videos/DOHS-VOTING.jpg') }}" alt="" style="width: 100%; height: 300px; object-fit: cover;" />
                                
                            </div>
                           
                            <div class="gall-img vid-thumb" data-fancybox="gallery"
                                data-caption="বারিধারা ডিওএইচএস পিরিষদ কার্যনির্বাহী কমিটি নির্বাচন -২০২৩"
                                href="{{ url('/videos/DOHS-VOTING-2.mp4') }}" style="height: 300px; overflow: hidden;">
                                <i class="fa fa-play-circle"></i>
                                <img src="{{ url('/videos/DOHS-VOTING-2.jpg') }}" alt="" style="width: 100%; height: 300px; object-fit: cover;" />
                                
                            </div>
                            <div class="gall-img vid-thumb" data-fancybox="gallery"
                                data-caption="মহাখালী ডিওএইচএস পিরিষদ কার্যনির্বাহী কমিটি নির্বাচন -২০২৩"
                                href="{{ url('/videos/Mohakhali-DOHS.mp4') }}" style="height: 300px; overflow: hidden;">
                                <i class="fa fa-play-circle"></i>
                                <img src="{{ url('/videos/Mohakhali-DOHS.jpg') }}" alt="" style="width: 100%; height: 300px; object-fit: cover;" />
                                
                            </div>
                            <div class="gall-img vid-thumb" data-fancybox="gallery"
                                data-caption="বনানী ডিওএইচএস পিরিষদ কার্যনির্বাহী কমিটি নির্বাচন -২০২৩"
                                href="{{ url('/videos/Bonani-DOHS.mp4') }}" style="height: 300px; overflow: hidden;">
                                <i class="fa fa-play-circle"></i>
                                <img src="{{ url('/videos/Bonani-DOHS.jpg') }}" alt="" style="width: 100%; height: 300px; object-fit: cover;" />
                                
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    {{-- <div id="sta_gallery" class="section-padding"
        style="background-image: url('/videos/video-bj.jpg');background-size: cover;">
        <div class="container">
            <div class="row">
                <div class="col-md-12 sta_gallery-wrap text-center">
                    <h2 class="section-title">Various Activities </h2>
                    <hr class="section-title">
                    <div class="row">

                        <div class="col-sm-12 sta_gallery owl-carousel owl-theme gallery_carousel">

                            <div class="gall-img vid-thumb" data-fancybox="gallery"
                                data-caption="ডিওএইচএস পিরিষদ কার্যনির্বাহী কমিটি নির্বাচন -২০২৩"
                                href="{{ url('/videos/DOHS-VOTING.mp4') }}">
                                <i class="fa fa-play-circle"></i>
                                <img src="{{ url('/videos/DOHS-VOTING.jpg') }}" alt="" />
                                <h1>ডিওএইচএস পিরিষদ কার্যনির্বাহী কমিটি নির্বাচন -২০২৩</h1>
                            </div>
                            <div class="gall-img vid-thumb" data-fancybox="gallery"
                                data-caption="বারিধারা ডিওএইচএস পিরিষদ কার্যনির্বাহী কমিটি নির্বাচন -২০২৩"
                                href="{{ url('/videos/DOHS-VOTING-2.mp4') }}">
                                <i class="fa fa-play-circle"></i>
                                <img src="{{ url('/videos/DOHS-VOTING-2.jpg') }}" alt="" />
                                <h1>বারিধারা ডিওএইচএস পিরিষদ কার্যনির্বাহী কমিটি নির্বাচন -২০২৩</h1>
                            </div>
                            <div class="gall-img vid-thumb" data-fancybox="gallery"
                                data-caption="মহাখালী ডিওএইচএস পিরিষদ কার্যনির্বাহী কমিটি নির্বাচন -২০২৩"
                                href="{{ url('/videos/Mohakhali-DOHS.mp4') }}">
                                <i class="fa fa-play-circle"></i>
                                <img src="{{ url('/videos/Mohakhali-DOHS.jpg') }}" alt="" />
                                <h1>মহাখালী ডিওএইচএস পিরিষদ কার্যনির্বাহী কমিটি নির্বাচন -২০২৩</h1>
                            </div>
                            <div class="gall-img vid-thumb" data-fancybox="gallery"
                                data-caption="বনানী ডিওএইচএস পিরিষদ কার্যনির্বাহী কমিটি নির্বাচন -২০২৩"
                                href="{{ url('/videos/Bonani-DOHS.mp4') }}">
                                <i class="fa fa-play-circle"></i>
                                <img src="{{ url('/videos/Bonani-DOHS.jpg') }}" alt="" />
                                <h1>বনানী ডিওএইচএস পিরিষদ কার্যনির্বাহী কমিটি নির্বাচন -২০২৩</h1>
                            </div>

                        </div>



                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- <div id="sta_gallery" class="gallery-section section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12 sta_gallery-wrap text-center">
                    <h2 class="section-title">Photo Gallery</h2>

                    <div class="row">

                        <div class="col-sm-12 sta_gallery owl-carousel owl-theme gallery_carousel">
                            @foreach ($photo_galleries as $photo_gallery)
                                <div class="gall-img">
                                    <img src="{{ asset('assets/images/photo_galleries/' . $photo_gallery->image) }}"
                                        alt="{{ $photo_gallery->name }}">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Commented for VAPT issues -->
    {{--
        <div class="location-map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5163.038133664099!2d90.38885460736066!3d23.78944906268389!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c73effb31dc7%3A0x65ff33f01fc6f8b4!2sStation+Headquarters+Canteen!5e0!3m2!1sen!2sbd!4v1537252406842" width="100%" height="480" frameborder="0" style="border:0" allowfullscreen sandbox="allow-same-origin allow-scripts"></iframe>
        </div>
        --}}

    <div class="contact-section section-padding" style="display: none;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 contact">
                    <h2 class="section-title text-center">Contact Us</h2>

                    <div class="contact-form">
                        <form>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <!-- <label for="inputName">Name:</label> -->
                                    <input type="text" class="form-control" id="inputName" placeholder="Name">
                                </div>

                                <div class="form-group col-md-6">
                                    <!-- <label for="inputEmail4">Email:</label> -->
                                    <input type="email" class="form-control" id="inputEmail4" placeholder="Email">
                                </div>
                            </div>
                            <div class="form-group">
                                <!-- <label for="inputSubject">Subject:</label> -->
                                <input type="text" class="form-control" id="inputSubject" autocomplete="off"
                                    placeholder="Subject">
                            </div>

                            <div class="form-group">
                                <!-- <label for="textareaMessage">Message:</label> -->
                                <textarea name="textareaMessage" class="form-control" id="textareaMessage" style="width: 100%;"
                                    placeholder="Message"></textarea>
                            </div>

                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-contact-send">Send</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).on('click', '.read-more', function() {
            let list = $(this).closest('.card-body').find('.collapse-list');
            let hiddenItems = list.find('li:hidden');

            if (hiddenItems.length > 0) {
                hiddenItems.slideDown();
                $(this).text('Read Less');
            } else {
                list.find('li').not(':lt(3)').slideUp();
                $(this).text('Read More');
            }
        });
    </script>
@endsection
