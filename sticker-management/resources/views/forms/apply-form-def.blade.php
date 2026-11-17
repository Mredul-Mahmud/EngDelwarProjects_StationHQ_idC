@php
    // dd(auth()->guard('applicant')->user()->applicantDetail->rank_id);
@endphp
<style>
    .form-personal_info {

        /* ddddisplay: flex;
        padding: 10px;
        margin: 10px; */
        background-color: #F5F5F5;
        padding: 30px 10px;
        margin: 10px;
        border-radius: 5px;
        border: 1px solid lightgray;
    }

    .form-personal_info .row {
        margin-top: 10px;
    }

    .details-container {
        width: 100%;
    }

    .vehicle-details-container {
        width: 100%;
        background-color: #F5F5F5;
        padding: 30px 10px;
        margin: 10px;
        border-radius: 5px;
        border: 1px solid lightgray;
    }

    .driver-details-container {
        width: 100%;
        background-color: #F5F5F5;
        padding: 30px 10px;
        margin: 10px;
        border-radius: 5px;
        border: 1px solid lightgray;
    }

    .doc-details-container {
        width: 100%;
        background-color: #F5F5F5;
        padding: 30px 10px;
        margin: 10px;
        border-radius: 5px;
        border: 1px solid lightgray;
    }
</style>
<div class="content-area" id="app-content-area" style="position: relative;">
    <div id="content-heading">
        <h4>Cantonment User's Vehicle Sticker
            @if (!empty($allocated_sticker))
                Renew
            @endif
            Application Form for Defence
        </h4>
    </div>

    <?php $vehicleInfo_exist = null;
    $driverInfo_exist = null; ?>
    @if (!empty(auth()->guard('applicant')->user()->applicantDetail->app_id))
        <?php
        $vehicleInfo_exist = App\Models\VehicleInfo::where('application_id', auth()->guard('applicant')->user()->applicantDetail->app_id)->first();
        $driverInfo_exist = App\Models\DriverInfo::where('application_id', auth()->guard('applicant')->user()->applicantDetail->app_id)->first();
        $documentInfo_exist = App\Models\Document::where('application_id', auth()->guard('applicant')->user()->applicantDetail->app_id)->first();
        ?>
    @endif
    <ul class="nav nav-tabs" id="E-myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="app-info-tab" data-toggle="tab" href="#app-info" role="tab"
                aria-controls="app-info" aria-selected="true">Applicant's Details</a>
        </li>
        <li class="nav-item">
            <a class="{{ !empty(auth()->guard('applicant')->user()->applicantDetail->app_id) ? 'nav-link' : 'nav-link not-active' }}"
                id="vehicle-tab" data-toggle="tab" href="#vehicle" role="tab" aria-controls="vehicle"
                aria-selected="false">Vehicle Details</a>
        </li>
        {{-- <li class="nav-item">
            <a class="{{ !empty($vehicleInfo_exist) ? 'nav-link' : 'nav-link not-active' }}" id="driver-tab" data-toggle="tab" href="#driver" role="tab" aria-controls="driver" aria-selected="false">Driver's
                Details</a>
        </li>
        <li class="nav-item">
            <a class="{{ !empty($driverInfo_exist) ? 'nav-link' : 'nav-link not-active' }}" id="documents-tab" data-toggle="tab" href="#document" role="tab" aria-controls="document" aria-selected="false">Documents</a>
        </li> --}}
    </ul>
    @if (!empty(auth()->guard('applicant')->user()->applicantDetail->app_id))
        <?php
        
        $runningApp = App\Models\Application::findOrFail(auth()->guard('applicant')->user()->applicantDetail->app_id);
        
        ?>
    @endif
    <div id="" class="Applycationform">
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane show fade active" id="app-info" role="tabpanel" aria-labelledby="app-info-tab">
                <div class="container-fluid">
                    <form id="applicant_detail_form-DEF" class="form Applycationform" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="details-container">
                                <div class="form-personal_info">
                                    <div class="row">
                                        <div class="col-md-3 offset-md-1">
                                            <label for="" class="label-form">Applicant's full name</label>
                                            <br>
                                            <small>As per national ID Card/Sve ID Card</small>
                                        </div>
                                        @if (!empty($allocated_sticker))
                                            <div class="col-md-8">
                                                <input type="text" id="applicant_name"
                                                    value="{{ auth()->guard('applicant')->user()->name }}"
                                                    name="applicant_name" class="form-control in-form mandatory">
                                            </div>
                                        @else
                                            <div class="col-md-8">
                                                <input type="text" id="applicant_name"
                                                    value="{{ auth()->guard('applicant')->user()->name }}"
                                                    name="applicant_name" class="form-control in-form mandatory"
                                                    placeholder="">
                                                <div id="err_msg_applicantName" class="err_msg" hidden> <i
                                                        class="fas fa-exclamation-triangle"></i> <span
                                                        id="err_applicantName">
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>


                                    <div class="row">
                                        <div class="col-md-3 offset-md-1 not_applicant_spouse_child">
                                            <label for="" class="label-form">Rank</label> <span>*</span> <br>
                                            <small></small>
                                        </div>
                                        <div class="col-md-8 mt-2 not_applicant_spouse_child">

                                            <select name="applicant_rank" id="applicant_rank"
                                                class="form-control in-form mandatory">
                                                <option selected value="" disabled>--Select Rank--</option>
                                                @if (isset($ranks) && count($ranks) > 0)
                                                    @foreach ($ranks as $rank)
                                                        <!-- <option {{ isset(auth()->guard('applicant')->user()->applicantDetail->rank_id) == $rank->id ? 'selected' : '' }} value="{{ $rank->id }}">{{ $rank->name }}</option> -->
                                                        <option
                                                            {{ isset(auth()->guard('applicant')->user()->applicantDetail->rank_id) && auth()->guard('applicant')->user()->applicantDetail->rank_id == $rank->id ? 'selected' : '' }}
                                                            value="{{ $rank->id }}">{{ $rank->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>

                                            <div id="err_msg_applicant_rank" class="err_msg" hidden> <i
                                                    class="fas fa-exclamation-triangle"></i> <span
                                                    id="err_applicant_rank">
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 offset-md-1 not_applicant_spouse_child">
                                            <label for="" class="label-form">Personal No</label> <span>*</span>
                                            <br>
                                            <small>(BA No/Army No)</small>
                                        </div>
                                        <div class="col-md-8 not_applicant_spouse_child">
                                            @if (!empty(auth()->guard('applicant')->user()->applicantDetail->applicant_BA_no))
                                                <input type="text" class="form-control in-form"
                                                    value="{{ auth()->guard('applicant')->user()->applicantDetail->applicant_BA_no }}"
                                                    placeholder="" name="BA_no" id="BA_no">
                                            @else
                                                <input type="text" class="form-control in-form" value=""
                                                    placeholder="" name="BA_no" id="BA_no">
                                            @endif
                                            <div id="err_msg_applicant_BA_no" class="err_msg" hidden> <i
                                                    class="fas fa-exclamation-triangle"></i> <span
                                                    id="err_applicant_BA_no">
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Service Id/Rtd ID Card Photo (Front) --}}
                                    <div class="row">
                                        <div class="col-md-3 offset-md-1">
                                            <label for="" class="label-form">Service Id/Rtd ID Card (Front
                                                Part)</label>
                                            <span>*</span>
                                        </div>
                                        <div class="col-md-8">
                                            @if (
                                                !empty(auth()->guard('applicant')->user()->applicantDetail->defIdCopy) ||
                                                    !empty($runningApp->applicant->applicantDetail->defIdCopy))
                                                <div>
                                                    <img src="{{ !empty($runningApp->applicant->applicantDetail->defIdCopy) ? url($runningApp->applicant->applicantDetail->defIdCopy) : url(auth()->guard('applicant')->user()->applicantDetail->defIdCopy) }}"
                                                        id="prev_image2_e_def_id_Exist" alt="preview application"
                                                        style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                                </div>
                                                <input type="file" id="image2_e_def_id_Exist"
                                                    accept="image/png, image/jpg, image/jpeg"
                                                    name="applicant_Def_id_photo"
                                                    class="form-control in-form mandatory resize-image"
                                                    disabled="">
                                                <button type="button" name="" class="btn btn-info change-btn"
                                                    data-photo="{{ !empty($runningApp->applicant->applicantDetail->defIdCopy) ? url($runningApp->applicant->applicantDetail->defIdCopy) : url(auth()->guard('applicant')->user()->applicantDetail->defIdCopy) }}">Change</button>
                                                <button type='button' class='btn btn-warning cancel-btn'
                                                    hidden="">Cancel</button>
                                                <div id="err_msg_def_id" class="err_msg" hidden> <i
                                                        class="fas fa-exclamation-triangle"></i> <span
                                                        id="err_def_id"> </span>
                                                </div>
                                            @else
                                                <div>
                                                    <img src="" id="prev_image2_e_def_id"
                                                        alt="preview application" hidden>
                                                </div>
                                                <input type="file" id="image2_e_def_id"
                                                    accept="image/png, image/jpg, image/jpeg"
                                                    name="applicant_Def_id_photo"
                                                    class="form-control in-form mandatory resize-image">
                                                <div id="err_msg_def_id" class="err_msg" hidden> <i
                                                        class="fas fa-exclamation-triangle"></i> <span
                                                        id="err_def_id"> </span>
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                    {{-- Service Id/Rtd ID Card Photo (Back) --}}
                                    <div class="row">
                                        <div class="col-md-3 offset-md-1">
                                            <label for="" class="label-form">Service Id/Rtd ID Card (Back
                                                Part)</label>
                                            <span></span>
                                        </div>
                                        <div class="col-md-8">
                                            @if (
                                                !empty(auth()->guard('applicant')->user()->applicantDetail->def_id_back_photo) ||
                                                    !empty($runningApp->applicant->applicantDetail->def_id_back_photo))
                                                <div>
                                                    <img src="{{ !empty($runningApp->applicant->applicantDetail->def_id_back_photo) ? url($runningApp->applicant->applicantDetail->def_id_back_photo) : url(auth()->guard('applicant')->user()->applicantDetail->def_id_back_photo) }}"
                                                        id="prev_image3_e_def_id_Exist" alt="preview application"
                                                        style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                                </div>
                                                <input type="file" id="image3_e_def_id_Exist"
                                                    accept="image/png, image/jpg, image/jpeg"
                                                    name="applicant_Def_id_back_photo"
                                                    class="form-control in-form mandatory resize-image"
                                                    disabled="">
                                                <button type="button" name="" class="btn btn-info change-btn"
                                                    data-photo="{{ !empty($runningApp->applicant->applicantDetail->def_id_back_photo) ? url($runningApp->applicant->applicantDetail->def_id_back_photo) : url(auth()->guard('applicant')->user()->applicantDetail->def_id_back_photo) }}">Change</button>
                                                <button type='button' class='btn btn-warning cancel-btn'
                                                    hidden="">Cancel</button>
                                                <div id="err_msg_def_id" class="err_msg" hidden> <i
                                                        class="fas fa-exclamation-triangle"></i> <span
                                                        id="err_def_id"> </span>
                                                </div>
                                            @else
                                                <div>
                                                    <img src="" id="prev_image3_e_def_id"
                                                        alt="preview application" hidden>
                                                </div>
                                                <input type="file" id="image3_e_def_id"
                                                    accept="image/png, image/jpg, image/jpeg"
                                                    name="applicant_Def_id_back_photo"
                                                    class="form-control in-form mandatory resize-image">
                                                <div id="err_msg_def_id" class="err_msg" hidden> <i
                                                        class="fas fa-exclamation-triangle"></i> <span
                                                        id="err_def_id"> </span>
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 offset-md-1">
                                            <label for="" class="label-form">Present Address </label>
                                            <span></span> <br>
                                            <small>*</small>
                                        </div>
                                        <div class="col-md-8">
                                            <textarea id="applicant_present_address" name="applicant_present_address" class="form-control in-form"
                                                placeholder="Enter your present address...">{{ old('applicant_present_address', auth()->guard('applicant')->user()->applicantDetail->address ?? '') }}</textarea>
                                        </div>
                                    </div>

                                    @if (
                                        !empty(auth()->guard('applicant')->user()->applicantDetail->applicant_photo) ||
                                            !empty($runningApp->applicant->applicantDetail->applicant_photo))
                                        <div class="row">
                                            <div class="col-md-3 offset-md-1">
                                                <label for="" class="label-form">Change Applicant's
                                                    photo</label>
                                                <span>*</span> <br>
                                            </div>
                                            <div class="col-md-8">
                                                <div>
                                                    <img id="prev_image1exist_e"
                                                        src="{{ !empty(auth()->guard('applicant')->user()->applicantDetail->applicant_photo) ? url(auth()->guard('applicant')->user()->applicantDetail->applicant_photo) : url($runningApp->applicant->applicantDetail->applicant_photo) }}"
                                                        alt="preview application"
                                                        style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                                    <span class="file_error"></span>
                                                </div>
                                                <input type="file" id="image1exist_e"
                                                    accept="image/png, image/jpg, image/jpeg" name="applicant_photo"
                                                    class="form-control in-form mandatory resize-image" placeholder=""
                                                    value="" disabled="">
                                                <button type="button" name="" class="btn btn-info change-btn"
                                                    data-photo="{{ !empty(auth()->guard('applicant')->user()->applicantDetail->applicant_photo) ? url(auth()->guard('applicant')->user()->applicantDetail->applicant_photo) : url($runningApp->applicant->applicantDetail->applicant_photo) }}">Change
                                                </button>
                                                <button type='button' class='btn btn-warning cancel-btn'
                                                    hidden="">Cancel</button>

                                                <div id="err_msg_applicantPhoto" class="err_msg" hidden> <i
                                                        class="fas fa-exclamation-triangle"></i> <span
                                                        id="err_applicantPhoto">
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row">
                                            <div class="col-md-3 offset-md-1">
                                                <label for="" class="label-form">Applicant's photo</label>
                                                <span>*</span>
                                                <br>
                                            </div>
                                            <div class="col-md-8">
                                                <div>
                                                    <img src="" id="prev_image1_e" alt="preview application"
                                                        hidden>
                                                    <span class="file_error"></span>
                                                </div>
                                                <input type="file" id="image1_e"
                                                    accept="image/png, image/jpg, image/jpeg" name="applicant_photo"
                                                    class="form-control in-form mandatory resize-image" placeholder=""
                                                    value="">
                                                <div id="err_msg_applicantPhoto" class="err_msg" hidden> <i
                                                        class="fas fa-exclamation-triangle"></i> <span
                                                        id="err_applicantPhoto">
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="row">
                                        <div class="col-md-3 offset-md-1">
                                            <label for="" class="label-form">Mobile number</label>
                                            <span>*</span> <br>
                                            <small></small>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text"
                                                value="{{ auth()->guard('applicant')->user()->phone }}"
                                                name="applicant_phone" id="applicant_phone"
                                                class="form-control in-form mandatory" placeholder="">
                                            <div id="err_msg_applicantPhone" class="err_msg" hidden> <i
                                                    class="fas fa-exclamation-triangle"></i> <span
                                                    id="err_applicantPhone">
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 offset-md-1">
                                            <label for="" class="label-form">Email</label> <br>
                                            <small></small>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text"
                                                value="{{ auth()->guard('applicant')->user()->email ? auth()->guard('applicant')->user()->email : '' }}"
                                                name="applicant_email" class="form-control in-form" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 offset-md-1">
                                            <label class="label-form">Service Status <span>*</span></label>
                                        </div>
                                        <div class="col-md-8 mt-2">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="service_status"
                                                    id="on_service" value="on_service"
                                                    {{ old('service_status', $runningApp->applicant->applicantDetail->is_applicant_retired ?? '') == 0 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="on_service">On Service</label>
                                            </div>

                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="service_status"
                                                    id="retired" value="retired"
                                                    {{ old('service_status', $runningApp->applicant->applicantDetail->is_applicant_retired ?? '') == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="retired">Retired</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3 offset-md-1">
                                            <label for="" class="label-form">Applicant Remarks (If
                                                Any)</label> <span></span> <br>
                                            <small></small>
                                        </div>
                                        <div class="col-md-8">
                                            <textarea id="applicant_remarks" name="applicant_remarks" class="form-control in-form">{{ old('applicant_remarks', auth()->guard('applicant')->user()->applicantDetail->applicant_remark ?? '') }}</textarea>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-2 offset-md-5">
                                    <button type="submit" class="btn btn-primary custm-btn" id="def-E-n-btn1">Save &
                                        Continue</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="tab-pane" id="vehicle" role="tabpanel" aria-labelledby="vehicle-tab">
                <div class="container-fluid">
                    <form id="vehicle_detail_form" class="form Applycationform" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="old_application_id"
                            value="@isset($old_application_id) {{ $old_application_id }} @endisset">
                        <div class="row">
                            <div class="vehicle-details-container">
                                <div class="row">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="" class="label-form">Vehicle type</label> <span>*</span>
                                        <br>
                                        <small></small>
                                    </div>

                                    @if (!empty($allocated_sticker))
                                        <div class="col-md-8">
                                            <select name="vehicle_type" id="vehicle_type"
                                                class="form-control-sm mandatory">
                                                <option selected
                                                    value="{{ $allocated_sticker->application->vehicleType->id }}">
                                                    {{ $allocated_sticker->application->vehicleType->name }}
                                                </option>
                                            </select>

                                        </div>
                                    @else
                                        <div class="col-md-8">

                                            <select name="vehicle_type" id="vehicle_type"
                                                class="form-control-sm mandatory">
                                                @if (!empty($runningApp->vehicleType->id))
                                                    <option value="{{ $runningApp->vehicleType->id }}" selected>
                                                        {{ $runningApp->vehicleType->name }}
                                                    </option>
                                                @else
                                                    <option selected="" disabled> Select A Vehicle</option>
                                                @endif
                                                @if (isset($vehicleTypes))
                                                    @foreach ($vehicleTypes as $vehicleType)
                                                        <option value="{{ $vehicleType->id }}">
                                                            {{ $vehicleType->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>

                                            <div id="err_msg_vehicletype" class="err_msg" hidden> <i
                                                    class="fas fa-exclamation-triangle"></i>
                                                <span id="err_vehicletype"> </span>
                                            </div>
                                        </div>

                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="" class="label-form">Vehicle registration number</label>
                                        <span>*</span> <br>
                                        <small></small>
                                    </div>
                                    @if (!empty($allocated_sticker))
                                        <div class="col-md-8">
                                            <input type="text" name="vehicle_reg_no" id="vehicle_reg_no"
                                                class="form-control in-form"
                                                value="{{ $allocated_sticker->reg_number }}">
                                        </div>
                                    @else
                                        <div class="col-md-8">
                                            <input type="text" name="vehicle_reg_no" id="vehicle_reg_no"
                                                class="form-control in-form mandatory"
                                                placeholder="Dhaka Metro KHA 00-0000"
                                                value="{{ !empty($runningApp->vehicleinfo->reg_number) ? $runningApp->vehicleinfo->reg_number : '' }}">
                                            <div id="err_msg_vehiclereg" class="err_msg" hidden><i
                                                    class="fas fa-exclamation-triangle"></i>
                                                <span id="err_vehiclereg"> </span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                {{-- Registration certificate (Front Part) --}}
                                <div class="row">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="" class="label-form">Registration certificate (Front
                                            Part)</label>
                                        <span>*</span> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-8">
                                        @if (!empty($allocated_sticker))

                                            <div>
                                                <img src="{{ url('') }}{{ $allocated_sticker->application->vehicleinfo->reg_cert_photo }}"
                                                    id="prev_image2_b_exist" alt="preview application"
                                                    style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                            </div>
                                            <input type="file" id="image2_b_exist"
                                                accept="image/png, image/jpg, image/jpeg"
                                                class="form-control in-form mandatory resize-image"
                                                name="vehicle_reg_photo" disabled="">
                                            <button type="button" name="" class="btn btn-info change-btn"
                                                data-photo="{{ url('') }}{{ $allocated_sticker->application->vehicleinfo->reg_cert_photo }}">Change</button>
                                            <button type='button' class='btn btn-warning cancel-btn'
                                                hidden="">Cancel</button>
                                        @else
                                            @if (!empty($runningApp->vehicleinfo->reg_cert_photo))
                                                <div>
                                                    <img src="{{ url('') }}{{ $runningApp->vehicleinfo->reg_cert_photo }}"
                                                        id="prev_image2_b_exist" alt="preview application"
                                                        style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                                </div>
                                                <input type="file" id="image2_b_exist"
                                                    accept="image/png, image/jpg, image/jpeg"
                                                    class="form-control in-form mandatory" name="vehicle_reg_photo"
                                                    disabled="">
                                                <button type="button" name="" class="btn btn-info change-btn"
                                                    data-photo="{{ url('') }}{{ $runningApp->vehicleinfo->reg_cert_photo }}">Change</button>
                                                <button type='button' class='btn btn-warning cancel-btn'
                                                    hidden="">Cancel</button>
                                            @else
                                                <div>
                                                    <img src="" id="prev_image2_b" alt="preview application"
                                                        hidden
                                                        style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                                </div>
                                                <input type="file" id="image2_b"
                                                    accept="image/png, image/jpg, image/jpeg"
                                                    class="form-control in-form mandatory resize-image"
                                                    name="vehicle_reg_photo">
                                                <div id="err_msg_vehicleregphoto" class="err_msg" hidden> <i
                                                        class="fas fa-exclamation-triangle"></i> <span
                                                        id="err_vehicleregphoto"> </span>
                                                </div>
                                            @endif
                                        @endif
                                    </div>

                                </div>

                                {{-- Registration certificate (Back Part) --}}
                                <div class="row">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="" class="label-form">Registration certificate (Back Part)
                                        </label> <span></span>
                                        <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-8">
                                        @if (!empty($allocated_sticker))

                                            <div>
                                                <img src="{{ url('') }}{{ $allocated_sticker->application->vehicleinfo->reg_cert_back_photo }}"
                                                    id="prev_image3_b_exist" alt="preview application"
                                                    style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                            </div>
                                            <input type="file" id="image3_b_exist"
                                                accept="image/png, image/jpg, image/jpeg"
                                                class="form-control in-form mandatory resize-image"
                                                name="vehicle_reg_back_photo" disabled="">
                                            <button type="button" name="" class="btn btn-info change-btn"
                                                data-photo="{{ url('') }}{{ $allocated_sticker->application->vehicleinfo->reg_cert_back_photo }}">
                                                Change
                                            </button>
                                            <button type='button' class='btn btn-warning cancel-btn'
                                                hidden="">Cancel</button>
                                        @else
                                            @if (!empty($runningApp->vehicleinfo->reg_cert_back_photo))
                                                <div>
                                                    <img src="{{ url('') }}{{ $runningApp->vehicleinfo->reg_cert_back_photo }}"
                                                        id="prev_image3_b_exist" alt="preview application"
                                                        style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                                </div>
                                                <input type="file" id="image3_b_exist"
                                                    accept="image/png, image/jpg, image/jpeg"
                                                    class="form-control in-form mandatory resize-image"
                                                    name="vehicle_reg_back_photo" disabled="">
                                                <button type="button" name="" class="btn btn-info change-btn"
                                                    data-photo="{{ url('') }}{{ $runningApp->vehicleinfo->reg_cert_back_photo }}">
                                                    Change
                                                </button>
                                                <button type='button' class='btn btn-warning cancel-btn'
                                                    hidden="">Cancel
                                                </button>
                                            @else
                                                <div>
                                                    <img src="" id="prev_image3_b" alt="preview application"
                                                        hidden
                                                        style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                                </div>
                                                <input type="file" id="image3_b"
                                                    accept="image/png, image/jpg, image/jpeg"
                                                    class="form-control in-form mandatory resize-image"
                                                    name="vehicle_reg_back_photo">
                                                <div id="err_msg_vehicleregphoto" class="err_msg" hidden><i
                                                        class="fas fa-exclamation-triangle"></i> <span
                                                        id="err_vehicleregphoto"> </span>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="vehicle_owner" class="label-form">Owner of the Vehicle</label>
                                        <span>*</span> <br>
                                    </div>
                                    <div class="col-md-8">
                                        <select name="vehicle_owner" id="vehicle_owner"
                                            class="form-control-sm mandatory">
                                            <option value="">Select Vehicle Owner</option>
                                            <option value="self">Self</option>
                                            <option value="spouse">Spouse</option>
                                            <option value="civil_company">Civil Company</option>
                                            <option value="govt_company">Govt. Institution</option>
                                            <option value="other">Other</option>
                                        </select>
                                        <div id="err_msg_vehicle_owner" class="err_msg" hidden><i
                                                class="fas fa-exclamation-triangle"></i> <span id="err_vehicle_owner">
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Self - Applicant's Name -->
                                <div class="row" id="owner_name_row" style="display: none;">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="owner_name" class="label-form">Applicant's Name</label>
                                        <span>*</span> <br>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" name="owner_name" id="owner_name"
                                            class="form-control in-form" placeholder="Enter owner name"
                                            value="{{ auth()->guard('applicant')->user()->name }}" readonly>
                                    </div>
                                </div>

                                <!-- Spouse - Passport/NID Copy -->
                                <div class="row" id="passport_copy_row" style="display: none;">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="spouse_passport_copy" class="label-form">Attach Passport
                                            copy</label> <span>*</span> <br>
                                        <small>Upload passport copy</small>
                                    </div>
                                    <div class="col-md-8">
                                        <div>
                                            <img src="" id="prev_spouse_passport" alt="preview passport"
                                                hidden
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                        <input type="file" id="spouse_passport_copy" name="spouse_passport_copy"
                                            accept="image/png, image/jpg, image/jpeg, application/pdf"
                                            class="form-control in-form resize-image" placeholder="">
                                        <div id="err_msg_passport" class="err_msg" hidden>
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <span id="err_passport"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Civil Company - Certificate -->
                                <div class="row" id="civil_company_row" style="display: none;">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="civil_company_certificate" class="label-form">Attach Certificate
                                            of the Company</label> <span>*</span> <br>
                                        <small>Upload company certificate</small>
                                    </div>
                                    <div class="col-md-8">
                                        <div>
                                            <img src="" id="prev_civil_company_certificate"
                                                alt="preview certificate" hidden
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                        <input type="file" id="civil_company_certificate"
                                            name="civil_company_certificate"
                                            accept="image/png, image/jpg, image/jpeg, application/pdf"
                                            class="form-control in-form resize-image">
                                        <div id="err_msg_civil_certificate" class="err_msg" hidden>
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <span id="err_civil_certificate"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Govt. Institution - Certificate -->
                                <div class="row" id="govt_company_row" style="display: none;">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="govt_institution_certificate" class="label-form">Attach
                                            Certificate of the Govt. Institution</label> <span>*</span> <br>
                                        <small>Upload government institution certificate</small>
                                    </div>
                                    <div class="col-md-8">
                                        <div>
                                            <img src="" id="prev_govt_institution_certificate"
                                                alt="preview certificate" hidden
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                        <input type="file" id="govt_institution_certificate"
                                            name="govt_institution_certificate"
                                            accept="image/png, image/jpg, image/jpeg, application/pdf"
                                            class="form-control in-form resize-image">
                                        <div id="err_msg_govt_certificate" class="err_msg" hidden>
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <span id="err_govt_certificate"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-2" id="other" style="display: none;">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="relation" class="label-form">Relationship</label> <span>*</span> <br>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" id="relation" name="relation"
                                            class="form-control in-form"
                                            placeholder="Write here relationship with vehicle owner...">

                                        <div id="err_msg_relation" class="err_msg" hidden>
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <span id="err_relation"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 offset-md-1">
                                        <label for="relation_copy" class="label-form">Attach certificate of the
                                            relationship</label> <br>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="file" id="relation_copy" name="relation_certificate"
                                            accept="image/png, image/jpg, image/jpeg, application/pdf"
                                            class="form-control in-form resize-image" placeholder="">
                                        <div>
                                            <img src="" id="prev_relation" alt="preview relation" hidden
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                        <div id="err_msg_relation" class="err_msg" hidden>
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <span id="err_relation"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1 offset-md-5">
                                <button type="button" class="btn btn-secondary  custm-btn"
                                    id="E-p-btn1">Previous</button>
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary next_btn custm-btn" id="E-n-btn2">Save
                                    & Finished</button>
                            </div>


                        </div>
                    </form>
                </div>
            </div>
            <div class="tab-pane " id="driver" role="tabpanel" aria-labelledby="driver-tab">
                <div class="container-fluid">
                    <form id="driver_detail_form" class="form Applycationform" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="old_application_id"
                            value="@isset($old_application_id) {{ $old_application_id }} @endisset">

                        <div class="row">
                            <div class="driver-details-container">
                                <div class="row">

                                    <div class="col-md-3 offset-md-1">
                                        <label for="" class="label-form">Is Vehicle Self Driven?</label>
                                        <span></span> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-8">
                                        @if (
                                            (!empty($allocated_sticker->application->driverinfo->driver_is_owner) &&
                                                $allocated_sticker->application->driverinfo->driver_is_owner == 1) ||
                                                (!empty($runningApp->driverinfo->driver_is_owner) && $runningApp->driverinfo->driver_is_owner == 1))
                                            <input type="checkbox" id="self_driven_checkbox" value="1"
                                                name="self_driven" checked class="self_driven"
                                                style="margin-top: 3px;width: 16px; height: 16px;">
                                        @else
                                            <input type="checkbox" id="self_driven_checkbox" value="1"
                                                name="self_driven" class="self_driven"
                                                style="margin-top: 3px;width: 16px; height: 16px;">
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 offset-md-1 not_self_driven" hidden>
                                        <label for="" class="label-form">Driver's name</label> <span>*</span>
                                        <br>
                                        <small></small>
                                    </div>

                                    <div class="col-md-8 not_self_driven" hidden>
                                        @if (!empty($allocated_sticker->application->driverinfo->name) || !empty($runningApp->driverinfo->name))
                                            <input type="text" name="name" id="driver_name"
                                                class="form-control in-form"
                                                value="{{ !empty($allocated_sticker->application->driverinfo->name) ? $allocated_sticker->application->driverinfo->name : $runningApp->driverinfo->name }}">
                                            <div id="err_msg_drivername" class="err_msg" hidden> <i
                                                    class="fas fa-exclamation-triangle"></i> <span
                                                    id="err_drivername">
                                                </span>
                                            </div>
                                        @else
                                            <input type="text" name="name" id="driver_name"
                                                class="form-control in-form">

                                            <div id="err_msg_drivername" class="err_msg" hidden> <i
                                                    class="fas fa-exclamation-triangle"></i> <span
                                                    id="err_drivername">
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 offset-md-1 not_self_driven" hidden>
                                        <label for="" class="label-form">Driver's photo</label> <span>*</span>
                                        <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-8 not_self_driven" hidden>
                                        @if (!empty($allocated_sticker->application->driverinfo->photo) || !empty($runningApp->driverinfo->photo))
                                            <div>
                                                <img src="{{ !empty($allocated_sticker->application->driverinfo->photo) ? url($allocated_sticker->application->driverinfo->photo) : url($runningApp->driverinfo->photo) }}"
                                                    id="prev_image10_b_exist" alt="preview application"
                                                    style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                            </div>
                                            <input type="file" id="image10_b_exist"
                                                accept="image/png, image/jpg, image/jpeg" name="photo"
                                                class="form-control in-form resize-image" placeholder="" disabled>
                                            <button type="button" name="" class="btn btn-info change-btn"
                                                data-photo="{{ !empty($allocated_sticker->application->driverinfo->photo) ? url($allocated_sticker->application->driverinfo->photo) : url($runningApp->driverinfo->photo) }}">Change</button>
                                            <button type='button' class='btn btn-warning cancel-btn'
                                                hidden="">Cancel</button>
                                            <div id="err_msg_driverphoto" class="err_msg" hidden> <i
                                                    class="fas fa-exclamation-triangle"></i> <span
                                                    id="err_driverphoto">
                                                </span>
                                            </div>
                                        @else
                                            <div>

                                                <img src="" id="prev_image10_b" alt="preview application"
                                                    hidden>
                                            </div>
                                            <input type="file" id="image10_b"
                                                accept="image/png, image/jpg, image/jpeg" name="photo"
                                                class="form-control in-form resize-image" placeholder="">
                                            <div id="err_msg_driverphoto" class="err_msg" hidden> <i
                                                    class="fas fa-exclamation-triangle"></i> <span
                                                    id="err_driverphoto">
                                                </span>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                                <?php $driver_address = null; ?>
                                @if (!empty($allocated_sticker->application->driverinfo->address) || !empty($runningApp->driverinfo->address))
                                    <?php $driver_address = json_decode(!empty($allocated_sticker->application->driverinfo->address) ? $allocated_sticker->application->driverinfo->address : $runningApp->driverinfo->address, true); ?>
                                @endif
                                <div class="row">
                                    <div class="col-md-3 offset-md-1 not_self_driven" hidden>
                                        <p style="margin-bottom: 0px; margin-top: 30px;">Present address</p> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-8 not_self_driven" hidden>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 offset-md-1 not_self_driven" hidden>
                                        <label for="" class="label-form">Flat No.</label> <span>*</span> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-8 not_self_driven" hidden>
                                        <input type="text" name="dri_pre_flat" id="dri_pre_flat"
                                            class="driver_pre_flat form-control in-form"
                                            value="{{ !empty($driver_address['present']['flat']) ? $driver_address['present']['flat'] : '' }}">
                                        <div id="err_msg_driverflat" class="err_msg" hidden> <i
                                                class="fas fa-exclamation-triangle"></i> <span id="err_driverflat">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 offset-md-1 not_self_driven" hidden>
                                        <label for="" class="label-form">House No.</label> <span>*</span> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-8 not_self_driven" hidden>
                                        <input type="text" name="dri_pre_house" id="dri_pre_house"
                                            class="driver_pre_house form-control in-form"
                                            value="{{ !empty($driver_address['present']['house']) ? $driver_address['present']['house'] : '' }}">
                                        <div id="err_msg_driverhouse" class="err_msg" hidden> <i
                                                class="fas fa-exclamation-triangle"></i> <span id="err_driverhouse">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 offset-md-1 not_self_driven" hidden>
                                        <label for="" class="label-form">Road No.</label> <span>*</span> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-8 not_self_driven" hidden>
                                        <input type="text" name="dri_pre_road" id="dri_pre_road"
                                            class="driver_pre_road form-control in-form" placeholder=""
                                            value="{{ !empty($driver_address['present']['road']) ? $driver_address['present']['road'] : '' }}">
                                        <div id="err_msg_driverroad" class="err_msg" hidden> <i
                                                class="fas fa-exclamation-triangle"></i> <span id="err_driverroad">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 offset-md-1 not_self_driven" hidden>
                                        <label for="" class="label-form">Block/Section</label> <span>*</span>
                                        <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-8 not_self_driven" hidden>
                                        <input type="text" name="dri_pre_block" id="dri_pre_block"
                                            class="driver_pre_block form-control in-form" placeholder=""
                                            value="{{ !empty($driver_address['present']['block']) ? $driver_address['present']['block'] : '' }}">
                                        <div id="err_msg_driverblock" class="err_msg" hidden> <i
                                                class="fas fa-exclamation-triangle"></i> <span id="err_driverblock">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 offset-md-1 not_self_driven" hidden>
                                        <label for="" class="label-form">Area</label> <span>*</span> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-8 not_self_driven" hidden>
                                        <input type="text" name="dri_pre_area" id="dri_pre_area"
                                            class="driver_pre_area form-control in-form" placeholder=""
                                            value="{{ !empty($driver_address['present']['area']) ? $driver_address['present']['area'] : '' }}">
                                        <div id="err_msg_driverarea" class="err_msg" hidden> <i
                                                class="fas fa-exclamation-triangle"></i> <span id="err_driverarea">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 offset-md-1 not_self_driven" hidden>
                                        <p style="margin-bottom: 0px; margin-top: 30px;">Permanent address</p> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-8 not_self_driven" hidden>


                                        @if (isset($driver_address['permanent']) != '' &&
                                                isset($driver_address['present']['flat']) == isset($driver_address['permanent']['p_flat']) &&
                                                isset($driver_address['present']['house']) == isset($driver_address['permanent']['p_house']) &&
                                                isset($driver_address['present']['road']) == isset($driver_address['permanent']['p_road']) &&
                                                isset($driver_address['present']['block']) == isset($driver_address['permanent']['p_block']) &&
                                                isset($driver_address['present']['area']) == isset($driver_address['permanent']['p_area']))
                                            <input type="checkbox" name="driver_address_same_as_present"
                                                id="driver_address_same_as_present"
                                                class="driver_address_same_as_present" checked
                                                style="margin-top: 3px;width: 16px; height: 16px;">
                                        @else
                                            <input type="checkbox" name="driver_address_same_as_present"
                                                id="driver_address_same_as_present"
                                                class="driver_address_same_as_present"
                                                style="margin-top: 3px;width: 16px; height: 16px;">
                                        @endif
                                        <label for="driver_address_same_as_present"
                                            style="display: inline-block; margin-bottom: 0px; margin-top: 30px;"
                                            title="Use present address">Same as present address</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 offset-md-1 not_self_driven" hidden>
                                        <label for="" class="label-form">Flat No.</label> <span></span> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-8 not_self_driven driver_perm" hidden>
                                        <input type="text" name="dri_per_flat" id=""
                                            class="driver_per_flat form-control in-form" placeholder=""
                                            value="{{ !empty($driver_address['permanent']['p_flat']) ? $driver_address['permanent']['p_flat'] : '' }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 offset-md-1 not_self_driven" hidden>
                                        <label for="" class="label-form">House No.</label> <span></span> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-8 not_self_driven driver_perm" hidden>
                                        <input type="text" name="dri_per_house" id=""
                                            class="driver_per_house form-control in-form" placeholder=""
                                            value="{{ !empty($driver_address['permanent']['p_house']) ? $driver_address['permanent']['p_house'] : '' }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 offset-md-1 not_self_driven" hidden>
                                        <label for="" class="label-form">Road No.</label> <span></span> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-8 not_self_driven driver_perm" hidden>
                                        <input type="text" name="dri_per_road" id=""
                                            class="driver_per_road form-control in-form" placeholder=""
                                            value="{{ !empty($driver_address['permanent']['p_road']) ? $driver_address['permanent']['p_road'] : '' }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 offset-md-1 not_self_driven" hidden>
                                        <label for="" class="label-form">Block/Section</label> <span></span>
                                        <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-8 not_self_driven driver_perm" hidden>
                                        <input type="text" name="dri_per_block" id=""
                                            class="driver_per_block form-control in-form" placeholder=""
                                            value="{{ !empty($driver_address['permanent']['p_block']) ? $driver_address['permanent']['p_block'] : '' }}">
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-3 offset-md-1 not_self_driven" hidden>
                                        <label for="" class="label-form">Area</label> <span></span> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-8 not_self_driven driver_perm" hidden>
                                        <input type="text" name="dri_per_area" id=""
                                            class="driver_per_area  form-control in-form" placeholder=""
                                            value="{{ !empty($driver_address['permanent']['p_area']) ? $driver_address['permanent']['p_area'] : '' }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 offset-md-1 not_self_driven" hidden>
                                        <label for="" class="label-form">National ID number</label>
                                        <span>*</span>
                                        <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-8 not_self_driven" hidden>
                                        @if (!empty($allocated_sticker->application->driverinfo->nid_number) || !empty($runningApp->driverinfo->nid_number))
                                            <input type="number" name="nid_number" id="drivernid_number"
                                                class="form-control in-form mandatory" placeholder="5654445454"
                                                value="{{ !empty($allocated_sticker->application->driverinfo->nid_number) ? $allocated_sticker->application->driverinfo->nid_number : $runningApp->driverinfo->nid_number }}">
                                            <div id="err_msg_driverNid" class="err_msg" hidden> <i
                                                    class="fas fa-exclamation-triangle"></i> <span id="err_driverNid">
                                                </span>
                                            </div>
                                        @else
                                            <input type="number" name="nid_number" id="drivernid_number"
                                                class="form-control in-form mandatory" placeholder="5654445454">

                                            <div id="err_msg_driverNid" class="err_msg" hidden> <i
                                                    class="fas fa-exclamation-triangle"></i> <span id="err_driverNid">
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 offset-md-1 not_self_driven" hidden>
                                        <label for="" class="label-form">NID copy</label> <span>*</span> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-8 not_self_driven" hidden>
                                        @if (!empty($allocated_sticker->application->driverinfo->nid_photo) || !empty($runningApp->driverinfo->nid_photo))
                                            <div>
                                                <img src="{{ !empty($allocated_sticker->application->driverinfo->nid_photo) ? url($allocated_sticker->application->driverinfo->nid_photo) : url($runningApp->driverinfo->nid_photo) }}"
                                                    id="prev_image11_b_exist" alt="preview application"
                                                    style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                            </div>
                                            <input type="file" accept="image/png, image/jpg, image/jpeg"
                                                id="image11_b_exist" name="nid_photo"
                                                class="form-control mandatory in-form resize-image" disabled>
                                            <button type="button" name="" class="btn btn-info change-btn"
                                                data-photo="{{ !empty($allocated_sticker->application->driverinfo->nid_photo) ? url($allocated_sticker->application->driverinfo->nid_photo) : url($runningApp->driverinfo->nid_photo) }}">Change</button>
                                            <button type='button' class='btn btn-warning cancel-btn'
                                                hidden="">Cancel</button>
                                            <div id="err_msg_driverNidcopy" class="err_msg" hidden> <i
                                                    class="fas fa-exclamation-triangle"></i> <span
                                                    id="err_driverNidcopy">
                                                </span>
                                            </div>
                                        @else
                                            <div>
                                                <img src="" id="prev_image11_b" alt="preview application"
                                                    hidden>
                                            </div>
                                            <input type="file" accept="image/png, image/jpg, image/jpeg"
                                                id="image11_b" name="nid_photo"
                                                class="form-control mandatory in-form resize-image">
                                            <div id="err_msg_driverNidcopy" class="err_msg" hidden> <i
                                                    class="fas fa-exclamation-triangle"></i> <span
                                                    id="err_driverNidcopy">
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="" class="label-form">Driving license copy</label>
                                        <span>*</span>
                                        <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-8">
                                        @if (
                                            !empty($allocated_sticker->application->driverinfo->licence_photo) ||
                                                !empty($runningApp->driverinfo->licence_photo))
                                            <div>
                                                <img src="{{ !empty($allocated_sticker->application->driverinfo->licence_photo) ? url($allocated_sticker->application->driverinfo->licence_photo) : url($runningApp->driverinfo->licence_photo) }}"
                                                    id="prev_image12_b_exist" alt="preview application"
                                                    style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                            </div>
                                            <input type="file" id="image12_b_exist"
                                                accept="image/png, image/jpg, image/jpeg" name="licence_photo"
                                                class="form-control in-form mandatory resize-image" disabled>
                                            <button type="button" name="" class="btn btn-info change-btn"
                                                data-photo="{{ !empty($allocated_sticker->application->driverinfo->licence_photo) ? url($allocated_sticker->application->driverinfo->licence_photo) : url($runningApp->driverinfo->licence_photo) }}">Change</button>
                                            <button type='button' class='btn btn-warning cancel-btn'
                                                hidden="">Cancel</button>
                                            <div id="err_msg_driverlicence" class="err_msg" hidden> <i
                                                    class="fas fa-exclamation-triangle"></i> <span
                                                    id="err_driverlicence">
                                                </span>
                                            </div>
                                        @else
                                            <div>
                                                <img src="" id="prev_image12_b" alt="preview application"
                                                    hidden>
                                            </div>
                                            <input type="file" id="image12_b"
                                                accept="image/png, image/jpg, image/jpeg" name="licence_photo"
                                                class="form-control in-form mandatory resize-image">
                                            <div id="err_msg_driverlicence" class="err_msg" hidden> <i
                                                    class="fas fa-exclamation-triangle"></i> <span
                                                    id="err_driverlicence">
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1 offset-md-5">
                                <button type="button" class="btn btn-secondary custm-btn"
                                    id="E-p-btn2">Previous</button>
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary  custm-btn" id="E-n-btn3">Save &
                                    Continue</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            <div class="tab-pane " id="document" role="tabpanel" aria-labelledby="documents-tab">
                <div class="container-fluid">
                    <form id="document_form" class="form Applycationform" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="old_application_id"
                            value="@isset($old_application_id) {{ $old_application_id }} @endisset">

                        <div class="row">
                            <div class="doc-details-container">
                                <div class="row">
                                    <div class="col-md-3 offset-md-1 ">
                                        <label for="" class="label-form">Father's Testimonial: </label>
                                        <span></span> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-8 ">

                                        <div>
                                            <img src="" id="prev_image11_b_father_test"
                                                alt="preview application" hidden>
                                        </div>
                                        <input type="file" accept="image/png, image/jpg, image/jpeg"
                                            id="image11_b_father_test" name="father_testm_photo"
                                            class="form-control mandatory in-form resize-image">

                                        <div id="" class="err_msg" hidden> <i
                                                class="fas fa-exclamation-triangle"></i> <span id=""> </span>
                                        </div>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 offset-md-1 ">
                                        <label for="" class="label-form">Mother's Testimonial: </label>
                                        <span></span> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-8 ">

                                        <div>
                                            <img src="" id="prev_image11_b_mother_test"
                                                alt="preview application" hidden>
                                        </div>
                                        <input type="file" accept="image/png, image/jpg, image/jpeg"
                                            id="image11_b_mother_test" name="mother_testm_photo"
                                            class="form-control mandatory in-form resize-image">

                                        <div id="" class="err_msg" hidden> <i
                                                class="fas fa-exclamation-triangle"></i> <span id=""> </span>
                                        </div>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 offset-md-1 ">
                                        <label for="" class="label-form"> Authorized Certificate : </label>
                                        <span></span> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-8 ">

                                        <div>
                                            <img src="" id="prev_image11_b_auth_cert"
                                                alt="preview application" hidden>
                                        </div>
                                        <input type="file" accept="image/png, image/jpg, image/jpeg"
                                            id="image11_b_auth_cert" name="auth_cert_photo"
                                            class="form-control in-form resize-image">

                                        <div id="" class="err_msg" hidden> <i
                                                class="fas fa-exclamation-triangle"></i> <span id=""> </span>
                                        </div>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 offset-md-1 ">
                                        <label for="" class="label-form"> Marriage Certificate Copy: </label>
                                        <span></span> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-8 ">

                                        <div>
                                            <img src="" id="prev_image11_b_marriage_cert"
                                                alt="preview application" hidden>
                                        </div>
                                        <input type="file" accept="image/png, image/jpg, image/jpeg"
                                            id="image11_b_marriage_cert" name="marriage_cert_photo"
                                            class="form-control in-form resize-image">

                                        <div id="" class="err_msg" hidden> <i
                                                class="fas fa-exclamation-triangle"></i> <span id="">
                                            </span>
                                        </div>
                                        @if (!empty($allocated_sticker))
                                            <input type="hidden" name="renew_app" class="form-control in-form"
                                                value="yes">
                                            <input type="hidden" name="app_id" class="form-control in-form"
                                                value="{{ $allocated_sticker->application->id }}">
                                        @endif

                                    </div>
                                </div>

                            </div>
                            <div class="col-md-1 offset-md-5">
                                <button type="button" class="btn btn-secondary custm-btn"
                                    id="E-p-btn3">Previous</button>
                            </div>
                            <div class="col-md-1">
                                <button type="submit" id="submit-btn"
                                    class="btn btn-success submit-btn custm-btn">Save & Finish</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @include('forms.crop_modal')
        </div>
    </div>
</div>
<div id="ajax-loader" hidden>
    <div>
        <img src="{{ url('assets/images/loading_spinner.gif') }}" />
        <div>Please wait for a while</div>
    </div>
</div>
