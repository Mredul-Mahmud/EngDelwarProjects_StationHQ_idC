@extends('layouts.admin-master')
@section('admin-sidebar')
    @include('layouts.admin-sidebar')
@endsection
@section('content')
    <div class="content-area sms-panel" style="padding-top: 15px;">
        <div class="container-fluid  pl-0 pr-0">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <h3 class="pptitle"> Update Header & Footer Content </h3>
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
                        <form action="{{ route('header_footer_store') }}" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="" class="label-form"> Telephone Number</label> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" name="telephone" id="telephone" class="form-control in-form"
                                            value="@isset($header_footer) {{ $header_footer->telephone }} @endisset">
                                    </div>



                                    <div class="col-md-3">
                                        <label for="" class="label-form">Duty Clark</label> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" name="duty_clark" id="duty_clark" class="form-control in-form"
                                            value="@isset($header_footer) {{ $header_footer->duty_clark }} @endisset">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="" class="label-form">IT</label> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" name="it_phone" id="it_phone" class="form-control in-form"
                                            value="@isset($header_footer) {{ $header_footer->it_phone }} @endisset">
                                    </div>





                                    <div class="col-md-3">
                                        <label for="" class="label-form"> Mobile Number</label> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" name="mobile" id="mobile" class="form-control in-form"
                                            value="@isset($header_footer) {{ $header_footer->mobile }} @endisset">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="" class="label-form"> Fax Number</label> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" name="fax" id="fax" class="form-control in-form"
                                            value="@isset($header_footer) {{ $header_footer->fax }} @endisset">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="" class="label-form"> Sicker Query </label> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" name="sticker_query" id="sticker_query"
                                            class="form-control in-form"
                                            value="@isset($header_footer) {{ $header_footer->sticker_query }} @endisset">
                                    </div>


                                    <div class="col-md-3">
                                        <label for="" class="label-form"> Graveyard </label> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" name="graveyard" id="graveyard" class="form-control in-form"
                                            value="@isset($header_footer) {{ $header_footer->graveyard }} @endisset">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="" class="label-form"> Location </label> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" name="location" id="location" class="form-control in-form"
                                            value="@isset($header_footer) {{ $header_footer->location }} @endisset">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="" class="label-form"> Footer About </label> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" name="about" id="about" class="form-control in-form"
                                            value="@isset($header_footer) {{ $header_footer->about }} @endisset">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="" class="label-form"> Army Exch </label> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" name="army_exch" id="army_exch"
                                            class="form-control in-form"
                                            value="@isset($header_footer) {{ $header_footer->army_exch }} @endisset">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="" class="label-form"> Duty Clerk Mil </label> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" name="duty_clerk_mil" id="duty_clerk_mil"
                                            class="form-control in-form"
                                            value="@isset($header_footer) {{ $header_footer->duty_clerk_mil }} @endisset">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="" class="label-form"> Unique Visitor </label> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" name="unique_visitor" id="unique_visitor"
                                            class="form-control in-form"
                                            value="@isset($header_footer) {{ $header_footer->unique_visitor }} @endisset">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="" class="label-form"> Copyright text </label> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" name="copyright" id="copyright"
                                            class="form-control in-form"
                                            value="@isset($header_footer) {{ $header_footer->copyright }} @endisset">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="" class="label-form"> Breaking News </label> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" name="breaking_news" id="breaking_news"
                                            class="form-control in-form"
                                            value="@isset($header_footer) {{ $header_footer->breaking_news }} @endisset">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="" class="label-form">Family ID Card Menu </label> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="form-control in-form" name="is_enable_family_id">
                                            <option value="no"
                                                {{ $header_footer && $header_footer->is_enable_family_id == 'no' ? 'selected' : '' }}>
                                                Disable</option>
                                            <option value="yes"
                                                {{ $header_footer && $header_footer->is_enable_family_id == 'yes' ? 'selected' : '' }}>
                                                Enable</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="" class="label-form">Batman Maidserven & Other Id Card
                                        </label> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="form-control in-form" name="is_enable_batman_id">
                                            <option value="no"
                                                {{ $header_footer && $header_footer->is_enable_batman_id == 'no' ? 'selected' : '' }}>
                                                Disable</option>
                                            <option value="yes"
                                                {{ $header_footer && $header_footer->is_enable_batman_id == 'yes' ? 'selected' : '' }}>
                                                Enable</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="" class="label-form">Civil Employee & School Bus Card </label>
                                        <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="form-control in-form" name="is_enable_employee_id">
                                            <option value="no"
                                                {{ $header_footer && $header_footer->is_enable_employee_id == 'no' ? 'selected' : '' }}>
                                                Disable</option>
                                            <option value="yes"
                                                {{ $header_footer && $header_footer->is_enable_employee_id == 'yes' ? 'selected' : '' }}>
                                                Enable</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="" class="label-form"> Sticker Login</label> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="form-control in-form" name="is_sticker_login">
                                            <option value="no"
                                                {{ $header_footer && $header_footer->is_sticker_login == 'no' ? 'selected' : '' }}>
                                                Disable </option>
                                            <option value="yes"
                                                {{ $header_footer && $header_footer->is_sticker_login == 'yes' ? 'selected' : '' }}>
                                                Enable </option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="" class="label-form"> Sticker Register</label> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="form-control in-form" name="is_sticker_register">
                                            <option value="no"
                                                {{ $header_footer && $header_footer->is_sticker_register == 'no' ? 'selected' : '' }}>
                                                Disable </option>
                                            <option value="yes"
                                                {{ $header_footer && $header_footer->is_sticker_register == 'yes' ? 'selected' : '' }}>
                                                Enable </option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="" class="label-form"> Sticker Policy</label> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="form-control in-form" name="is_sticker_policy">
                                            <option value="no"
                                                {{ $header_footer && $header_footer->is_sticker_policy == 'no' ? 'selected' : '' }}>
                                                Disable </option>
                                            <option value="yes"
                                                {{ $header_footer && $header_footer->is_sticker_policy == 'yes' ? 'selected' : '' }}>
                                                Enable </option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="" class="label-form"> Sticker Tutorial</label> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="form-control in-form" name="is_sticker_tutorial">
                                            <option value="no"
                                                {{ $header_footer && $header_footer->is_sticker_tutorial == 'no' ? 'selected' : '' }}>
                                                Disable </option>
                                            <option value="yes"
                                                {{ $header_footer && $header_footer->is_sticker_tutorial == 'yes' ? 'selected' : '' }}>
                                                Enable </option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="" class="label-form">Graveyard Allotment</label> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="form-control in-form" name="is_graveyard_allotment">
                                            <option value="no"
                                                {{ $header_footer && $header_footer->is_graveyard_allotment == 'no' ? 'selected' : '' }}>
                                                Disable </option>
                                            <option value="yes"
                                                {{ $header_footer && $header_footer->is_graveyard_allotment == 'yes' ? 'selected' : '' }}>
                                                Enable </option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="" class="label-form">Graveyard Policy</label> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="form-control in-form" name="is_graveyard_policy">
                                            <option value="no"
                                                {{ $header_footer && $header_footer->is_graveyard_policy == 'no' ? 'selected' : '' }}>
                                                Disable </option>
                                            <option value="yes"
                                                {{ $header_footer && $header_footer->is_graveyard_policy == 'yes' ? 'selected' : '' }}>
                                                Enable </option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="" class="label-form">Graveyard Application Form</label> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="form-control in-form" name="is_graveyard_application_form">
                                            <option value="no"
                                                {{ $header_footer && $header_footer->is_graveyard_application_form == 'no' ? 'selected' : '' }}>
                                                Disable </option>
                                            <option value="yes"
                                                {{ $header_footer && $header_footer->is_graveyard_application_form == 'yes' ? 'selected' : '' }}>
                                                Enable </option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="" class="label-form">Photo Gallery</label> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="form-control in-form" name="is_photo_gallery">
                                            <option value="no"
                                                {{ $header_footer && $header_footer->is_photo_gallery == 'no' ? 'selected' : '' }}>
                                                Disable </option>
                                            <option value="yes"
                                                {{ $header_footer && $header_footer->is_photo_gallery == 'yes' ? 'selected' : '' }}>
                                                Enable </option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="" class="label-form">Staf ORG</label> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="form-control in-form" name="is_staf_org">
                                            <option value="no"
                                                {{ $header_footer && $header_footer->is_staf_org == 'no' ? 'selected' : '' }}>
                                                Disable </option>
                                            <option value="yes"
                                                {{ $header_footer && $header_footer->is_staf_org == 'yes' ? 'selected' : '' }}>
                                                Enable </option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="" class="label-form">History</label> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="form-control in-form" name="is_history">
                                            <option value="no"
                                                {{ $header_footer && $header_footer->is_history == 'no' ? 'selected' : '' }}>
                                                Disable </option>
                                            <option value="yes"
                                                {{ $header_footer && $header_footer->is_history == 'yes' ? 'selected' : '' }}>
                                                Enable </option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="" class="label-form">Contact</label> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="form-control in-form" name="is_contact">
                                            <option value="no"
                                                {{ $header_footer && $header_footer->is_contact == 'no' ? 'selected' : '' }}>
                                                Disable </option>
                                            <option value="yes"
                                                {{ $header_footer && $header_footer->is_contact == 'yes' ? 'selected' : '' }}>
                                                Enable </option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="" class="label-form">Bus</label> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="form-control in-form" name="is_bus">
                                            <option value="no"
                                                {{ $header_footer && $header_footer->is_bus == 'no' ? 'selected' : '' }}>
                                                Disable </option>
                                            <option value="yes"
                                                {{ $header_footer && $header_footer->is_bus == 'yes' ? 'selected' : '' }}>
                                                Enable </option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="" class="label-form">Shuttle Service</label> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="form-control in-form" name="is_shuttle">
                                            <option value="no"
                                                {{ $header_footer && $header_footer->is_shuttle == 'no' ? 'selected' : '' }}>
                                                Disable </option>
                                            <option value="yes"
                                                {{ $header_footer && $header_footer->is_shuttle == 'yes' ? 'selected' : '' }}>
                                                Enable </option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="" class="label-form">General contact info</label> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="form-control in-form" name="is_general_contact_info">
                                            <option value="no"
                                                {{ $header_footer && $header_footer->is_general_contact_info == 'no' ? 'selected' : '' }}>
                                                Disable </option>
                                            <option value="yes"
                                                {{ $header_footer && $header_footer->is_general_contact_info == 'yes' ? 'selected' : '' }}>
                                                Enable </option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="" class="label-form">Pocket Gate On/Off Time</label> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="form-control in-form" name="gate_on_of_time">
                                            <option value="no"
                                                {{ $header_footer && $header_footer->gate_on_of_time == 'no' ? 'selected' : '' }}>
                                                Disable </option>
                                            <option value="yes"
                                                {{ $header_footer && $header_footer->gate_on_of_time == 'yes' ? 'selected' : '' }}>
                                                Enable </option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="" class="label-form">Comander & Mp Login</label> <br>
                                        <small></small>
                                    </div>

                                    <div class="col-md-9">
                                        <select class="form-control in-form" name="login">
                                            <option value="no"
                                                {{ $header_footer && $header_footer->login == 'no' ? 'selected' : '' }}>
                                                Disable </option>
                                            <option value="yes"
                                                {{ $header_footer && $header_footer->login == 'yes' ? 'selected' : '' }}>
                                                Enable </option>
                                        </select>
                                    </div>

                                </div>
                                <div class="col-md-12 text-center">
                                    <br><br>
                                    <button type="submit" class="btn btn-primary" id="confirm_add_sms"> Update </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>


    </div>

@endsection
@section('admin-script')
    <link href="{{ asset('assets/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">

    <script src="{{ asset('assets/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/assets/admins/js/admin-script.js') }}"></script>
@endsection
