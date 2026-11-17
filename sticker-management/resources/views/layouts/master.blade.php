<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Station Headquarters, Dhaka Cantonment, Dhaka </title>
    <link rel="stylesheet" href="{{ asset('/assets/cdn/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/cdn/all.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/css/front-style.css') }}?id=23">
    <link rel="stylesheet" href="{{ asset('/assets/css/responsive-style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/LogoS1.png') }}" />
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans:400,400i,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/assets/cdn/animate.min.css') }}">

    <link rel="stylesheet" href="{{ asset('/assets/cdn/animate.min.css') }}">

    {{-- <script src="{{ asset('/assets/cdn') }}/jquery-3.7.0.min.js"></script> --}}
    <script src="{{ asset('/assets/cdn/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('/assets/js/jquery.fancybox.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('/assets/css/jquery.fancybox.min.css') }}">



    <style>
        ul.widget-menu {
            overflow-x: auto;
            /* height: 280px; */
            list-style: none;
            padding: 10px 0;
            margin-bottom: 0;
        }

        span.mrq-text a {
            color: #fff;
        }

        span.mrq-text a {
            color: #fff;
        }

        span.mrq-text a:hover {
            color: #a22f18;
            border-bottom: 1px solid #A22F18;
        }

        .notice-description {
            text-align: justify;
            padding: 14px 0;
            font-size: 15px;
        }


        /*first animation start*/
        a.notice-alert {
            background: #A22F18;
            display: block;
            color: #fff;
            padding: 7px 13px;
        }

        a.blink_me {
            animation: blinker 3s linear infinite;
        }

        @keyframes blinker {
            0% {
                opacity: 1;
            }

            75% {
                opacity: 1;
            }

            100% {
                opacity: 0;
            }
        }


        .notice-area {
            position: relative;
        }

        .notice-area a {
            position: absolute;
            left: 0;
            top: 0;
            z-index: 99;
            padding: 8px 4px;
        }

        span.notice-text {
            font-size: 15px;
        }

        /*first animation start*/

        /*first animation start*/
        .rotate-180 {
            transform: rotate(180deg);
            transition: transform 0.3s ease;
            /* Smooth transition */
        }

        .rotate-90 {
            transform: rotate(90deg);
            transition: transform 0.3s ease;
            /* Smooth transition */
        }

        .rotate-360 {
            transform: rotate(360deg);
            transition: transform 0.3s ease;
            /* Smooth transition */
        }

        .rotate-270 {
            transform: rotate(270deg);
            transition: transform 0.3s ease;
            /* Smooth transition */
        }
    </style>
</head>

<body>
    <div class="main-header">

        <div class="container">
            {{--  <div class="row"> --}}

            @include('layouts.new_website.top_header')

            {{-- </div> --}}
        </div>
    </div>
    @include('layouts.new_website.stahq_header')
    <div class="site-wrapper">
        @yield('content')

        @include('layouts.footer')
    </div>


    <script src="{{ asset('/assets/cdn/popper.min.js') }}"></script>
    <script src="{{ asset('/assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('/assets/cdn/bootstrap.min.js') }}"></script>

    <script src="{{ asset('/assets/cdn/masonry.pkgd.min.js') }}"></script>
    <script src="{{ asset('/assets/cdn/jquery.bxslider.min.js') }}"></script>


    <script>
        $(document).ready(function() {
            $('.main-slider').bxSlider({
                mode: 'fade',
                captions: true,
                pause: 5000,
                auto: true,
            });

            var gallery_carousel = $('.gallery_carousel');

            // gallery_carousel
            // gallery_carousel.owlCarousel({
            //     margin: 15,
            //     loop: true,
            //     dots: true,
            //     nav: true,
            //     autoplay: true,
            //     autoplayTimeout: 2000,
            //     autoplayHoverPause: true,
            //     responsiveClass: true,
            //     navText: [
            //         '<i class="fa fa-angle-left"></i>',
            //         '<i class="fa fa-angle-right"></i>'
            //     ],
            //     responsive: {
            //         0: {
            //             items: 1
            //         },
            //         600: {
            //             items: 2
            //         },
            //         1200: {
            //             items: 2
            //         }
            //     }
            // });
            gallery_carousel.owlCarousel({
                // margin: 15,
                loop: true,
                dots: true,
                nav: true,
                autoplay: true,
                autoplayTimeout: 2000,
                autoplayHoverPause: true,
                responsiveClass: true,
                navText: [
                    '<i class="fa fa-angle-left"></i>',
                    '<i class="fa fa-angle-right"></i>'
                ],
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 1
                    },
                    1200: {
                        items: 1
                    }
                }
            });
            // setTimeout(function(){
            //     $('.sta_gallery').masonry({
            //       // options
            //       itemSelector: '.gall-img',
            //       columnWidth: 550,
            //       gutter: 10
            //     });
            // }, 1000);

            // scrollTop
            $(".navbar-icon").on('click', function() {
                $('.main-menu').stop(true, false, true).slideToggle(300);
            });

            // Submenu SlideToggle
            // $('ul.submenu').parent('li').addClass('has-submenu').children("a").append('<i class="fa fa-angle-down"></i>');

            // if ($(window).width() > 767) {
            //     $("ul.main-menu li").hover(function() {
            //         $(this).children('ul.submenu').stop(true, false, true).slideToggle(300);
            //     });
            // }
            // else {
            //     $('ul.main-menu>li>a>i').click(function(e) {               
            //         $(this).parent("ul.main-menu li a").parent("ul.main-menu li").children('ul.submenu').stop(true, false, true).slideToggle(300); // toggle element
            //         return false;
            //     });
            // }

            $('ul.submenu').parent('li').addClass('has-submenu').children("a").append(
                '<i class="fa fa-angle-down"></i>'
            );

            if ($(window).width() > 767) {
                $("ul.main-menu li").hover(
                    function() {
                        // Rotate the icon to 180 degrees
                        $(this).children("a").find("i").removeClass("rotate-90").addClass("rotate-180");
                        $(this).children('ul.submenu').stop(true, false, true).slideDown(300);
                    },
                    function() {
                        // Rotate the icon back to 0 degrees
                        $(this).children("a").find("i").removeClass("rotate-180");
                        $(this).children('ul.submenu').stop(true, false, true).slideUp(300);
                    }
                );
            } else {
                $('ul.main-menu > li > a > i').click(function(e) {
                    e.preventDefault(); // Prevent default action

                    var $icon = $(this);
                    var $parentLi = $icon.closest("li"); // Find the closest parent li

                    // Toggle rotation for the icon
                    if ($icon.hasClass("rotate-90")) {
                        $icon.removeClass("rotate-90").removeClass("rotate-180");
                    } else {
                        $icon.addClass("rotate-90").removeClass("rotate-180");
                    }

                    // Toggle the submenu visibility
                    $parentLi.children('ul.submenu').stop(true, false, true).slideToggle(300);
                    return false; // Prevent default action
                });
            }

            // $('ul.submenu').find('has-submenu').addClass('has-submenu');

            $('ul.submenu').find('a').each(function() {
                var icon = $(this).find('i');
                console.log(icon);
                icon.addClass('child-class').addClass('rotate-90');
            });


            $('ul.submenu').parent('li').on('mouseenter', function(e) {
                // console.log($(this).find('.fa').addClass("fa-angle-right").removeClass("fa-angle-down"));
                // $(this).find('.fa').addClass("fa-angle-right").removeClass("fa-angle-down")
                console.log($(this).find('.fa').addClass("fa-angle-right").addClass("rotate-270")
                    .removeClass("fa-angle-down").removeClass("rotate-180"));
                console.log('hello');
                $(this).find('.child-class').removeClass('rotate-270');
            });

            $('ul.submenu').parent('li').on('mouseleave', function(e) {
                $(this).find('.fa').removeClass("fa-angle-right").removeClass('rotate-270').addClass(
                    "fa-angle-down");
                $(this).find('.child-class').removeClass('rotate-90');
            });



            // click event to scroll to next section
            $('ul.main-menu>li>a').on('click', function(event) {
                var $anchor = $(this);
                $('html, body').stop().animate({
                    scrollTop: $($anchor.attr('href')).offset().top
                }, 2000);
            });

            // scrollTop
            $(".scrolltotop").on('click', function() {
                $("html").animate({
                    'scrollTop': '0'
                }, 500);
                return false;
            });

            $(window).scroll(function() {
                var windowpos = $(window).scrollTop();
                if (windowpos >= 50) {
                    $("a.scrolltotop").fadeIn();
                } else {
                    $("a.scrolltotop").fadeOut();
                }
            });

            $(".role").change(function() {
                $(".role").prop('checked', false);
                $(this).prop('checked', true);
                $("#submit").attr('disabled', false);
            });
            $(".team-member a.readmore").click(function(event) {
                // event.preventDefault();
                console.log('hello click');
            });

            var currentyear = new Date().getFullYear() + 1;
            $('.sticker_next_year').text(currentyear);
        });

        $(document).ready(function() {
            // Initialize the modal
            $('#videoModal').modal({
                show: false // Ensures the modal is hidden by default
            });

            // Pause video when modal is closed
            $('#videoModal').on('hidden.bs.modal', function() {
                $('#videoPlayer').get(0).pause();
            });

            // Play video when modal is opened
            $('#videoModal').on('shown.bs.modal', function() {
                $('#videoPlayer').get(0).play();
            });
        });
    </script>

    @yield('script-container')
</body>

</html>
