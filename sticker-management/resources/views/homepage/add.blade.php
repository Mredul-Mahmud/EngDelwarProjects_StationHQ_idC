@extends('layouts.admin-master')
@section('admin-sidebar')
    @include('layouts.admin-sidebar')
@endsection
@section('content')
    <div class="content-area sms-panel" style="padding-top: 15px;">
        <div class="container-fluid  pl-0 pr-0">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <b class=""> Add Home Page </b>
                    <a href="{{ route('home-page.index') }}" class="btn btn-primary btn-sm" style="float:right"> Home Page</a>
                </div>
                @if (session('success'))
                    <div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-warning alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {{ $error }}
                        </div>
                    @endforeach
                @endif
                <div class="panel-body" style="padding:10px 0;">
                    <div id="example-wrapper">
                        <form action="{{ route('home-page.store') }}" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="" class="label-form">Name <span class="text-danger">*</span>
                                        </label> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" name="name" id="name" class="form-control in-form"
                                            value="{{ old('name') }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="" class="label-form"> Title <span class="text-danger">*</span>
                                        </label> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" name="title" id="title" class="form-control in-form"
                                            value="{{ old('title') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="" class="label-form"> Location <span
                                                class="text-danger">*</span>
                                        </label> <br>
                                        <small></small>
                                    </div>

                                    <div class="col-md-9">
                                        <input type="text" name="location" id="location" class="form-control in-form"
                                            value="{{ old('location') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="" class="label-form"> Background Image <span
                                                class="text-danger">*</span>
                                        </label> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-9">

                                        <div class="col-md-9">
                                            <br>
                                            <div class="slider_image">
                                                <img id="background_image_show" src="{{ asset('assets/images/no_image.png') }}"  alt="Slider Image" style=" object-fit:contain;">
                                            </div>
                                            <input type="file" id="background_image" name="background_image"
                                                id="background_image" class="in-form">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="" class="label-form"> Image <span
                                                class="text-danger">*</span></label> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-9">
                                        <br>
                                        <div class="slider_image">
                                            <img id="image_show" src="{{ asset('assets/images/no_image.png') }}"  alt="Slider Image" style=" object-fit:contain;">
                                        </div>
                                        <input type="file" id="image" name="image" id="image" class="in-form">
                                    </div>




                                </div>
                                <div class="col-md-12 text-center">
                                    <br><br>
                                    <button type="submit" class="btn btn-primary" id="confirm_add_sms"> Save </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>


    </div>

@endsection

@section('style')
    <style>
        .slider_image img {
            width: 236px;
            height: 295px;
            border: 1px solid #ddd;
            border-radius: 3px;
            padding: 2px;
        }
    </style>
@endsection
@section('admin-script')
    <link href="{{ asset('assets/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/assets/ckeditor/ckeditor.js') }}"></script>

    <script>
        // CK editor
        CKEDITOR.replace('responsibility');
        // Image Preview
        // $(document).ready(function() {
        //     function readURL(input) {
        //         if (input.files && input.files[0]) {
        //             var reader = new FileReader();
        //             reader.onload = function(e) {
        //                 $('#image_show').attr('src', e.target.result);
        //             }
        //             reader.readAsDataURL(input.files[0]);
        //         }
        //     }

        //     $("#image").change(function() {
        //         readURL(this);
        //     });
        // });
        $(document).ready(function() {
            // Function to handle image preview
            function readURL(input, imageId) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        // Update the respective image's src
                        $(imageId).attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            // Handle background image change
            $("#background_image").change(function() {
                readURL(this, '#background_image_show');
            });

            // Handle regular image change
            $("#image").change(function() {
                readURL(this, '#image_show');
            });
        });
    </script>
@endsection
