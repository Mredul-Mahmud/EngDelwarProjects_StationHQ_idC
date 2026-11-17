<style>
    .form-personal_info {

        /* display: flex;
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
            @if(!empty($allocated_sticker))
            Renew
            @endif

            Application Form for Non-Defence</h4>
    </div>
    <?php $vehicleInfo_exist = null;
    $driverInfo_exist = null; ?>
    @if(!empty(auth()->guard('applicant')->user()->applicantDetail->app_id))
    <?php
    $vehicleInfo_exist = App\Models\VehicleInfo::where('application_id', auth()->guard('applicant')->user()->applicantDetail->app_id)->first();
    $driverInfo_exist = App\Models\DriverInfo::where('application_id', auth()->guard('applicant')->user()->applicantDetail->app_id)->first();
    $documentInfo_exist = App\Models\Document::where('application_id', auth()->guard('applicant')->user()->applicantDetail->app_id)->first();

    ?>
    @endif
    <ul class="nav nav-tabs" id="E-myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="app-info-tab" data-toggle="tab" href="#app-info" role="tab" aria-controls="app-info" aria-selected="true">Applicant's Details</a>
        </li>
        <li class="nav-item">
            <a class="{{!empty(auth()->guard('applicant')->user()->applicantDetail->app_id)?'nav-link':'nav-link not-active'}}" id="vehicle-tab" data-toggle="tab" href="#vehicle" role="tab" aria-controls="vehicle" aria-selected="false">Vehicle Details</a>
        </li>
        {{-- <li class="nav-item">
            <a class="{{!empty($vehicleInfo_exist)?'nav-link':'nav-link not-active'}}" id="driver-tab" data-toggle="tab" href="#driver" role="tab" aria-controls="driver" aria-selected="false">Driver's Details</a>
        </li>
        <li class="nav-item">
            <a class="{{!empty($driverInfo_exist)?'nav-link':'nav-link not-active'}}" id="documents-tab" data-toggle="tab" href="#document" role="tab" aria-controls="document" aria-selected="false">Documents</a>
        </li> --}}
    </ul>
    @if(!empty(auth()->guard('applicant')->user()->applicantDetail->app_id))
    <?php
    $runningApp = App\Models\Application::findOrFail(auth()->guard('applicant')->user()->applicantDetail->app_id);
    ?>
    @endif
    <div id="" class="form Applycationform">
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane show fade active" id="app-info" role="tabpanel" aria-labelledby="app-info-tab">
                <div class="container-fluid">
                    <form id="applicant_detail_form" method="post" action="{{url('/applicant-details/store')}}" class="form Applycationform" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="details-container">
                                <div class="form-personal_info">
                                    <div class="row">
                                        <div class="col-md-3 offset-md-1">
                                            <label for="" class="label-form">Applicant's full name</label> <span>*</span> <br>
                                            <small>As per national ID card</small>
                                        </div>
                                        @if(!empty($allocated_sticker))
                                        <div class="col-md-8">
                                            <input type="text" id="applicant_name" value="{{auth()->guard('applicant')->user()->name}}" name="applicant_name" class="form-control in-form mandatory">
                                        </div>
                                        @else
                                        <div class="col-md-8">
                                            <input type="text" id="applicant_name" value="{{auth()->guard('applicant')->user()->name}}" name="applicant_name" class="form-control in-form mandatory" placeholder="">
                                            <div id="err_msg_applicantName" class="err_msg" hidden><i class="fas fa-exclamation-triangle"></i> <span id="err_applicantName"> </span>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="row">
                                        @if(!empty(auth()->guard('applicant')->user()->applicantDetail->applicant_photo) || !empty($runningApp->applicant->applicantDetail->applicant_photo))
                                        <div class="col-md-3 offset-md-1">
                                            <label for="" class="label-form">Change Applicant's photo</label> <span>*</span>
                                            <br>
                                        </div>
                                        <div class="col-md-8">
                                            <div>
                                                <img id="prev_image1exist_e" src="{{!empty(auth()->guard('applicant')->user()->applicantDetail->applicant_photo)?url(auth()->guard('applicant')->user()->applicantDetail->applicant_photo):url($runningApp->applicant->applicantDetail->applicant_photo)}}" alt="preview application" style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                                <span class="file_error"></span>
                                            </div>
                                            <input type="file" id="image1exist_e" accept="image/png, image/jpg, image/jpeg" name="applicant_photo" class="form-control in-form mandatory resize-image" placeholder="" value="" disabled="">
                                            <button type="button" name="" class="btn btn-info change-btn" data-photo="{{!empty(auth()->guard('applicant')->user()->applicantDetail->applicant_photo)?url(auth()->guard('applicant')->user()->applicantDetail->applicant_photo):url($runningApp->applicant->applicantDetail->applicant_photo)}}">
                                                Change
                                            </button>
                                            <button type='button' class='btn btn-warning cancel-btn' hidden="">Cancel</button>

                                            <div id="err_msg_applicantPhoto" class="err_msg" hidden><i class="fas fa-exclamation-triangle"></i> <span id="err_applicantPhoto"> </span>
                                            </div>
                                        </div>
                                        @else
                                        <div class="col-md-3 offset-md-1">
                                            <label for="" class="label-form">Applicant's photo</label> <span>*</span> <br>
                                        </div>
                                        <div class="col-md-8">
                                            <div>
                                                <img src="" id="prev_image1_e" alt="preview application" hidden>
                                                <span class="file_error"></span>
                                            </div>
                                            <input type="file" id="image1_e" accept="image/png, image/jpg, image/jpeg" name="applicant_photo" class="form-control in-form mandatory resize-image" placeholder="" value="">
                                            <div id="err_msg_applicantPhoto" class="err_msg" hidden><i class="fas fa-exclamation-triangle"></i> <span id="err_applicantPhoto"> </span>
                                            </div>
                                        </div>
                                        @endif
                                    </div>

                                    <?php


                                    if ($file_name_nid = auth()->guard('applicant')->user()->applicantDetail) {
                                        $file_name_nid = auth()->guard('applicant')->user()->applicantDetail->nid_photo;
                                        $slash = substr($file_name_nid, 1);
                                    } else {
                                        $slash = '';
                                    }



                                    ?>
                                    <div class="row">
                                        @if(is_file($slash) || !empty($runningApp->applicant->applicantDetail->nid_photo))

                                        <div class="col-md-3 offset-md-1">
                                            <label for="" class="label-form">NID Photo (Front Part)</label> <span>*</span> <br>
                                            <small></small>
                                        </div>
                                        <div class="col-md-8">
                                            <div>
                                                <img src="{{!empty(auth()->guard('applicant')->user()->applicantDetail->nid_photo)?url(auth()->guard('applicant')->user()->applicantDetail->nid_photo):url($runningApp->applicant->applicantDetail->nid_photo)}}" id="prev_image2exist_e" alt="preview application" style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                            </div>
                                            <input type="file" id="image2exist_e" accept="image/png, image/jpg, image/jpeg" name="applicant_nid_photo" class="form-control in-form mandatory resize-image" disabled="">
                                            <button type="button" name="" class="btn btn-info change-btn" data-photo="{{!empty($runningApp->applicant->applicantDetail->nid_photo)?url($runningApp->applicant->applicantDetail->nid_photo):url(auth()->guard('applicant')->user()->applicantDetail->nid_photo)}}">
                                                Change
                                            </button>
                                            <button type='button' class='btn btn-warning cancel-btn' hidden="">Cancel</button>

                                            <div id="err_msg_appNidCopy" class="err_msg" hidden><i class="fas fa-exclamation-triangle"></i> <span id="err_appNidCopy"> </span>
                                            </div>
                                        </div>
                                        @else
                                        <div class="col-md-3 offset-md-1">
                                            <label for="" class="label-form">NID Photo (Front Part)</label> <span>*</span> <br>
                                            <small></small>
                                        </div>
                                        <div class="col-md-8">
                                            <div>
                                                <img src="" id="prev_image2_e" alt="preview application" hidden>
                                            </div>
                                            <input type="file" id="image2_e" accept="image/png, image/jpg, image/jpeg" name="applicant_nid_photo" class="form-control in-form mandatory resize-image">
                                            <div id="err_msg_appNidCopy" class="err_msg" hidden><i class="fas fa-exclamation-triangle"></i> <span id="err_appNidCopy"> </span>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <?php
                                        if ($file_name_nid_back = auth()->guard('applicant')->user()->applicantDetail) {
                                            $file_name_nid_back = auth()->guard('applicant')->user()->applicantDetail->nid_back_photo;
                                            $slash = substr($file_name_nid_back, 1);
                                        } else {
                                            $slash = '';
                                        }
                                    ?>
                                    <div class="row">
                                        @if(is_file($slash) || !empty($runningApp->applicant->applicantDetail->nid_back_photo))

                                        <div class="col-md-3 offset-md-1">
                                            <label for="" class="label-form">NID Photo (Back Part)</label> <span>*</span> <br>
                                            <small></small>
                                        </div>
                                        <div class="col-md-8">
                                            <div>
                                                <img src="{{!empty(auth()->guard('applicant')->user()->applicantDetail->nid_back_photo)?url(auth()->guard('applicant')->user()->applicantDetail->nid_back_photo):url($runningApp->applicant->applicantDetail->nid_back_photo)}}" id="prev_image3exist_e" alt="preview application" style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                            </div>
                                            <input type="file" id="image3exist_e" accept="image/png, image/jpg, image/jpeg" name="applicant_nid_back_photo" class="form-control in-form mandatory resize-image" disabled="">
                                            <button type="button" name="" class="btn btn-info change-btn" data-photo="{{!empty($runningApp->applicant->applicantDetail->nid_back_photo)?url($runningApp->applicant->applicantDetail->nid_back_photo):url(auth()->guard('applicant')->user()->applicantDetail->nid_back_photo)}}">
                                                Change
                                            </button>
                                            <button type='button' class='btn btn-warning cancel-btn' hidden="">Cancel</button>

                                            <div id="err_msg_appNidBack" class="err_msg" hidden><i class="fas fa-exclamation-triangle"></i> <span id="err_msg_appNidBack"> </span>
                                            </div>
                                        </div>
                                        @else
                                        <div class="col-md-3 offset-md-1">
                                            <label for="" class="label-form">NID Photo (Back Part)</label> <span>*</span> <br>
                                            <small></small>
                                        </div>
                                        <div class="col-md-8">
                                            <div>
                                                <img src="" id="prev_image4_e" alt="preview application" hidden>
                                            </div>
                                            <input type="file" id="image4_e" accept="image/png, image/jpg, image/jpeg" name="applicant_nid_back_photo" class="form-control in-form mandatory resize-image">
                                            <div id="err_msg_appNidBack" class="err_msg" hidden><i class="fas fa-exclamation-triangle"></i> <span id="err_msg_appNidBack"> </span>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 offset-md-1">
                                            <label for="" class="label-form">Mobile number</label> <span>*</span> <br>
                                            <small></small>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" value="{{auth()->guard('applicant')->user()->phone}}" name="applicant_phone" id="applicant_phone" class="form-control in-form mandatory" placeholder="">
                                            <div id="err_msg_applicantPhone" class="err_msg" hidden><i class="fas fa-exclamation-triangle"></i> <span id="err_applicantPhone"> </span>
                                            </div>
                                        </div>
                                    </div>
                                
                                    <?php $app_address = null; ?>

                                    @if(isset(auth()->guard('applicant')->user()->applicantDetail->address))
                                    <?php $app_address = json_decode(auth()->guard('applicant')->user()->applicantDetail->address, true);   ?>
                                    @endif

                                    <div class="row">
                                        <div class="col-md-3 offset-md-1">
                                            <label for="" class="label-form">Email</label> <span></span> <br>
                                            <small></small>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" value="{{auth()->guard('applicant')->user()->email ? auth()->guard('applicant')->user()->email : '' }}" name="applicant_email" class="form-control in-form" placeholder="">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3 offset-md-1">
                                            <label for="" class="label-form">Present Address </label> <span>*</span> <br>
                                            <small>*</small>
                                        </div>
                                        <div class="col-md-8">
                                            <textarea id="applicant_present_address" name="applicant_present_address" class="form-control in-form" placeholder="Enter your present address...">{{ old('applicant_present_address', auth()->guard('applicant')->user()->applicantDetail->address ?? '') }}</textarea>
                                            <div id="err_msg_address" class="err_msg" hidden><i class="fas fa-exclamation-triangle"></i> <span id="err_msg_address"> </span>
                                            </div>
                                        </div>
                                    </div>

                                   
                                    <div class="row">
                                        <div class="col-md-3 offset-md-1">
                                            <label for="" class="label-form">Applicant Remarks (If Any) </label> <span></span> <br>
                                            <small></small>
                                        </div>
                                        <div class="col-md-8">
                                            <textarea id="applicant_remarks" name="applicant_remarks" class="form-control in-form">{{ old('applicant_remarks', auth()->guard('applicant')->user()->applicantDetail->applicant_remark ?? '') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 offset-md-5">
                                    <button type="submit" class="btn btn-primary custm-btn" id="E-n-btn1">Save & Continue
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="tab-pane" id="vehicle" role="tabpanel" aria-labelledby="vehicle-tab">

                <div class="container-fluid">
                    <form id="vehicle_detail_form" method="post" action="{{'/vehicle-detail/store'}}" class="form Applycationform" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <input type="hidden" name="old_application_id" value="@isset($old_application_id) {{ $old_application_id }} @endisset">

                        <div class="row">
                            <div class="vehicle-details-container">
                                <div class="row">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="" class="label-form">Vehicle type</label> <span>*</span> <br>
                                        <small></small>
                                    </div>

                                    @if(!empty($allocated_sticker))
                                        <div class="col-md-8">
                                            <select name="vehicle_type" id="vehicle_type" class="form-control-sm mandatory">
                                                <option selected value="{{$allocated_sticker->application->vehicleType->id}}">{{$allocated_sticker->application->vehicleType->name}}</option>
                                            </select>

                                        </div>
                                    @else
                                        <div class="col-md-8">
                                            <select name="vehicle_type" id="vehicle_type" class="form-control-sm mandatory">
                                                @if(!empty($runningApp->vehicleType->id))

                                                <option value="{{$runningApp->vehicleType->id}}" selected>{{$runningApp->vehicleType->name}}</option>

                                                @else
                                                <option selected="" disabled> Select A Vehicle</option>
                                                @endif
                                                @if(isset($vehicleTypes))
                                                @foreach($vehicleTypes as $vehicleType)
                                                <option value="{{$vehicleType->id}}">{{$vehicleType->name}}</option>
                                                @endforeach
                                                @endif
                                            </select>

                                            <div id="err_msg_vehicletype" class="err_msg" hidden><i class="fas fa-exclamation-triangle"></i>
                                                <span id="err_vehicletype"> </span>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-3 offset-md-1">
                                        <label for="" class="label-form">Vehicle registration number</label> <span>*</span> <br>
                                        <small></small>
                                    </div>
                                    @if(!empty($allocated_sticker))
                                        <div class="col-md-8">
                                            <input type="text" name="vehicle_reg_no" id="vehicle_reg_no" class="form-control in-form mandatory" value="{{$allocated_sticker->reg_number}}">
                                        </div>
                                    @else
                                        <div class="col-md-8">
                                            <input type="text" name="vehicle_reg_no" id="vehicle_reg_no" class="form-control in-form mandatory" placeholder="Enter your vehicle registration number" value="{{!empty($runningApp->vehicleinfo->reg_number)?$runningApp->vehicleinfo->reg_number:''}}">
                                            <div id="err_msg_vehiclereg" class="err_msg" hidden><i class="fas fa-exclamation-triangle"></i>
                                                <span id="err_vehiclereg"> </span>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- REgistration Certificate Photo (Front) --}}
                                <div class="row">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="" class="label-form">Registration certificate (Front Part) </label> <span>*</span>
                                        <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-8">
                                        @if(!empty($allocated_sticker))

                                            <div>
                                                <img src="{{url('')}}{{$allocated_sticker->application->vehicleinfo->reg_cert_photo}}" id="prev_image2_b_exist" alt="preview application" style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                            </div>
                                            <input type="file" id="image2_b_exist" accept="image/png, image/jpg, image/jpeg" class="form-control in-form mandatory resize-image" name="vehicle_reg_photo" disabled="">
                                            <button type="button" name="" class="btn btn-info change-btn" data-photo="{{url('')}}{{$allocated_sticker->application->vehicleinfo->reg_cert_photo}}">
                                                Change
                                            </button>
                                            <button type='button' class='btn btn-warning cancel-btn' hidden="">Cancel</button>
                                        @else

                                            @if(!empty($runningApp->vehicleinfo->reg_cert_photo))
                                                <div>
                                                    <img src="{{url('')}}{{$runningApp->vehicleinfo->reg_cert_photo}}" id="prev_image2_b_exist" alt="preview application" style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                                </div>
                                                <input type="file" id="image2_b_exist" accept="image/png, image/jpg, image/jpeg" class="form-control in-form mandatory resize-image" name="vehicle_reg_photo" disabled="">
                                                <button type="button" name="" class="btn btn-info change-btn" data-photo="{{url('')}}{{$runningApp->vehicleinfo->reg_cert_photo}}">
                                                    Change
                                                </button>
                                                <button type='button' class='btn btn-warning cancel-btn' hidden="">Cancel
                                                </button>
                                                @else
                                                <div>
                                                    <img src="" id="prev_image2_b" alt="preview application" hidden style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                                </div>
                                                <input type="file" id="image2_b" accept="image/png, image/jpg, image/jpeg" class="form-control in-form mandatory resize-image" name="vehicle_reg_photo">
                                                <div id="err_msg_vehicleregphoto" class="err_msg" hidden><i class="fas fa-exclamation-triangle"></i> <span id="err_vehicleregphoto"> </span>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                {{-- Registration certificate (Back) --}}
                                <div class="row">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="" class="label-form">Registration certificate (Back Part) </label> <span></span>
                                        <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-8">
                                        @if(!empty($allocated_sticker))

                                            <div>
                                                <img src="{{url('')}}{{$allocated_sticker->application->vehicleinfo->reg_cert_back_photo}}" id="prev_image3_b_exist" alt="preview application" style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                            </div>
                                            <input type="file" id="image3_b_exist" accept="image/png, image/jpg, image/jpeg" class="form-control in-form mandatory resize-image" name="vehicle_reg_back_photo" disabled="">
                                            <button type="button" name="" class="btn btn-info change-btn" data-photo="{{url('')}}{{$allocated_sticker->application->vehicleinfo->reg_cert_back_photo}}">
                                                Change
                                            </button>
                                            <button type='button' class='btn btn-warning cancel-btn' hidden="">Cancel</button>
                                        @else

                                            @if(!empty($runningApp->vehicleinfo->reg_cert_back_photo))
                                                <div>
                                                    <img src="{{url('')}}{{$runningApp->vehicleinfo->reg_cert_back_photo}}" id="prev_image3_b_exist" alt="preview application" style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                                </div>
                                                <input type="file" id="image3_b_exist" accept="image/png, image/jpg, image/jpeg" class="form-control in-form mandatory resize-image" name="vehicle_reg_back_photo"  disabled="">
                                                <button type="button" name="" class="btn btn-info change-btn" data-photo="{{url('')}}{{$runningApp->vehicleinfo->reg_cert_back_photo}}">
                                                    Change
                                                </button>
                                                <button type='button' class='btn btn-warning cancel-btn' hidden="">Cancel
                                                </button>
                                                @else
                                                <div>
                                                    <img src="" id="prev_image3_b" alt="preview application" hidden style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                                </div>
                                                <input type="file" id="vehicle_reg_image3_b" accept="image/png, image/jpg, image/jpeg" class="form-control in-form mandatory resize-image" name="vehicle_reg_back_photo">
                                                <div id="err_msg_vehicleregphoto" class="err_msg" hidden><i class="fas fa-exclamation-triangle"></i> <span id="err_vehicleregphoto"> </span>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="row mt-2">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="vehicle_owner" class="label-form">Owner of the Vehicle</label> <span>*</span> <br>
                                    </div>
                                    <div class="col-md-8">
                                        <select name="vehicle_owner" id="vehicle_owner"  class="form-control-sm mandatory">
                                            <option value="">Select Vehicle Owner</option>
                                            <option value="self">Self</option>
                                            <option value="spouse">Spouse</option>
                                            <option value="other">Other</option>
                                        </select>
                                        <div id="err_msg_vehicle_owner" class="err_msg" hidden><i class="fas fa-exclamation-triangle"></i> <span id="err_vehicle_owner"> </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2" id="owner_name_row" style="display: none;">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="owner_name" class="label-form">Applicant's Name</label> <span>*</span> <br>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" name="owner_name" id="owner_name" class="form-control in-form" placeholder="Enter owner name" value="{{auth()->guard('applicant')->user()->name}}">
                                    </div>
                                </div>
                                <div class="row mt-2" id="passport_copy_row" style="display: none;">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="spouse_passport_copy" class="label-form">Attach Scanned copy of Passport</label> <span>*</span> <br>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="file" id="spouse_passport_copy" name="spouse_passport_copy" accept="image/png, image/jpg, image/jpeg, application/pdf" class="form-control in-form resize-image" placeholder="">
                                        <div>
                                            <img src="" id="prev_spouse_passport" alt="preview passport" hidden style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                        <div id="err_msg_passport" class="err_msg" hidden>
                                            <i class="fas fa-exclamation-triangle"></i> 
                                            <span id="err_passport"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2" id="other" style="display: none;">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="relation" class="label-form">Relationship</label> <span>*</span> <br>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" id="relation" name="relation"  class="form-control in-form" placeholder="Write here relationship with vehicle owner...">
                                       
                                        <div id="err_msg_relation" class="err_msg" hidden>
                                            <i class="fas fa-exclamation-triangle"></i> 
                                            <span id="err_relation"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 offset-md-1">
                                        <label for="relation_copy" class="label-form">Attach certificate of the relationship</label>  <br>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="file" id="relation_copy" name="relation_certificate" accept="image/png, image/jpg, image/jpeg, application/pdf" class="form-control in-form resize-image" placeholder="">
                                        <div>
                                            <img src="" id="prev_relation" alt="preview relation" hidden style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                        <div id="err_msg_relation" class="err_msg" hidden>
                                            <i class="fas fa-exclamation-triangle"></i> 
                                            <span id="err_relation"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="application_category" class="label-form">Application Category</label> <span>*</span> <br>
                                    </div>
                                    <div class="col-md-8">
                                        <select name="application_category" id="application_category" class="form-control-sm mandatory">
                                            <option value="">Select Application Category</option>
                                            <option value="service_retd">Service/Retd Under Defence Ministry</option>
                                            <option value="house_owner">House Owner Inside Dhaka Cantt.</option>
                                            <option value="civil_institution">Civil Institution/Establishment Inside Dhaka Cantt.</option>
                                            <option value="proyash_school">For Proyash School & College</option>
                                            <option value="teacher_official">Teacher/Official of School & College</option>
                                            <option value="bus_laguna">Bus/Laguna</option>
                                            <option value="motorcycle">Motorcycle</option>
                                            <option value="transit_kochukhet">Transit (Kochukhet to Banani) - (10000TK)</option>
                                            <option value="transit_matikata">Transit (Matikata to Jahangir Gate) - (10000TK)</option>
                                            <option value="special">Special - (25000TK)</option>
                                         
                                        </select>
                                        <div id="err_msg_app_category" class="err_msg" hidden>
                                            <i class="fas fa-exclamation-triangle"></i> 
                                            <span id="err_app_category"></span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Service/Retd Under Defence Ministry -->
                                <div class="row category-document mt-2" id="doc_service_retd" style="display: none;">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="service_retd_doc" class="label-form">Attach Job Certificate/Retd ID Card</label> <span>*</span> <br>
                                        <small>Upload job certificate or retirement ID card</small>
                                    </div>
                                    <div class="col-md-8">
                                        <div>
                                            <img src="" id="prev_service_retd_doc" alt="preview document" hidden style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                        <input type="file" id="service_retd_doc" name="service_retd_doc" accept="image/png, image/jpg, image/jpeg, application/pdf" class="form-control in-form resize-image">
                                        <div id="err_msg_service_retd" class="err_msg" hidden>
                                            <i class="fas fa-exclamation-triangle"></i> 
                                            <span id="err_service_retd"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- House Owner Inside Dhaka Cantt. -->
                                <div class="row category-document mt-2" id="doc_house_owner" style="display: none;">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="house_owner_doc" class="label-form">Attach CEO Certificate</label> <span>*</span> <br>
                                        <small>Upload CEO certificate</small>
                                    </div>
                                    <div class="col-md-8">
                                        <div>
                                            <img src="" id="prev_house_owner_doc" alt="preview document" hidden style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                        <input type="file" id="house_owner_doc" name="house_owner_doc" accept="image/png, image/jpg, image/jpeg, application/pdf" class="form-control in-form resize-image">
                                        <div id="err_msg_house_owner" class="err_msg" hidden>
                                            <i class="fas fa-exclamation-triangle"></i> 
                                            <span id="err_house_owner"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Civil Institution/Establishment Inside Dhaka Cantt. -->
                                <div class="row category-document mt-2" id="doc_civil_institution" style="display: none;">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="civil_institution_doc" class="label-form">Attach Job Certificate</label> <span>*</span> <br>
                                        <small>Upload job certificate</small>
                                    </div>
                                    <div class="col-md-8">
                                        <div>
                                            <img src="" id="prev_civil_institution_doc" alt="preview document" hidden style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                        <input type="file" id="civil_institution_doc" name="civil_institution_doc" accept="image/png, image/jpg, image/jpeg, application/pdf" class="form-control in-form resize-image">
                                        <div id="err_msg_civil_institution" class="err_msg" hidden>
                                            <i class="fas fa-exclamation-triangle"></i> 
                                            <span id="err_civil_institution"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- For Proyash School & College -->
                                <div class="row category-document mt-2" id="doc_proyash_school" style="display: none;">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="proyash_school_doc" class="label-form">Attach Certificate Issued From Proyash</label> <span>*</span> <br>
                                        <small>Upload certificate issued from Proyash</small>
                                    </div>
                                    <div class="col-md-8">
                                        <div>
                                            <img src="" id="prev_proyash_school_doc" alt="preview document" hidden style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                        <input type="file" id="proyash_school_doc" name="proyash_school_doc" accept="image/png, image/jpg, image/jpeg, application/pdf" class="form-control in-form resize-image">
                                        <div id="err_msg_proyash_school" class="err_msg" hidden>
                                            <i class="fas fa-exclamation-triangle"></i> 
                                            <span id="err_proyash_school"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Teacher/Official of School & College -->
                                <div class="row category-document mt-2" id="doc_teacher_official" style="display: none;">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="teacher_official_doc" class="label-form">Attach Job Certificate</label> <span>*</span> <br>
                                        <small>Upload job certificate</small>
                                    </div>
                                    <div class="col-md-8">
                                        <div>
                                            <img src="" id="prev_teacher_official_doc" alt="preview document" hidden style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                        <input type="file" id="teacher_official_doc" name="teacher_official_doc" accept="image/png, image/jpg, image/jpeg, application/pdf" class="form-control in-form resize-image">
                                        <div id="err_msg_teacher_official" class="err_msg" hidden>
                                            <i class="fas fa-exclamation-triangle"></i> 
                                            <span id="err_teacher_official"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bus/Laguna -->
                                <div class="row category-document mt-2" id="doc_bus_laguna" style="display: none;">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="bus_laguna_doc" class="label-form">Attach Approved Copy From Sta HQ Dhaka</label> <span>*</span> <br>
                                        <small>Upload approved copy from Sta HQ Dhakar</small>
                                    </div>
                                    <div class="col-md-8">
                                        <div>
                                            <img src="" id="prev_bus_laguna_doc" alt="preview document" hidden style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                        <input type="file" id="bus_laguna_doc" name="bus_laguna_doc" accept="image/png, image/jpg, image/jpeg, application/pdf" class="form-control in-form resize-image">
                                        <div id="err_msg_bus_laguna" class="err_msg" hidden>
                                            <i class="fas fa-exclamation-triangle"></i> 
                                            <span id="err_bus_laguna"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Motorcycle -->
                                <div class="row category-document mt-2" id="doc_motorcycle" style="display: none;">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="motorcycle_doc" class="label-form">Attach CEO Certificate/Job Certificate</label> <span>*</span> <br>
                                        <small>Upload ceo/job certificate</small>
                                    </div>
                                    <div class="col-md-8">
                                        <div>
                                            <img src="" id="prev_motorcycle_doc" alt="preview document" hidden style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                        <input type="file" id="motorcycle_doc" name="motorcycle_doc" accept="image/png, image/jpg, image/jpeg, application/pdf" class="form-control in-form resize-image">
                                        <div id="err_msg_motorcycle" class="err_msg" hidden>
                                            <i class="fas fa-exclamation-triangle"></i> 
                                            <span id="err_motorcycle"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Transit (Kochukhet to Banani) -->
                                <div class="row category-document mt-2" id="doc_transit_kochukhet" style="display: none;">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="transit_kochukhet_doc" class="label-form">Attach Word Commissioner Certificate</label> <span>*</span> <br>
                                        <small>Upload word commissioner certificate</small>
                                    </div>
                                    <div class="col-md-8">
                                        <div>
                                            <img src="" id="prev_transit_kochukhet_doc" alt="preview document" hidden style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                        <input type="file" id="transit_kochukhet_doc" name="transit_kochukhet_doc" accept="image/png, image/jpg, image/jpeg, application/pdf" class="form-control in-form resize-image">
                                        <div id="err_msg_transit_kochukhet" class="err_msg" hidden>
                                            <i class="fas fa-exclamation-triangle"></i> 
                                            <span id="err_transit_kochukhet"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Transit (Matikata to Jahangir Gate) -->
                                <div class="row category-document mt-2" id="doc_transit_matikata" style="display: none;">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="transit_matikata_doc" class="label-form">Attach Word Commissioner Certificate</label> <span>*</span> <br>
                                        <small>Upload word Commissioner certificate</small>
                                    </div>
                                    <div class="col-md-8">
                                        <div>
                                            <img src="" id="prev_transit_matikata_doc" alt="preview document" hidden style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                        <input type="file" id="transit_matikata_doc" name="transit_matikata_doc" accept="image/png, image/jpg, image/jpeg, application/pdf" class="form-control in-form resize-image">
                                        <div id="err_msg_transit_matikata" class="err_msg" hidden>
                                            <i class="fas fa-exclamation-triangle"></i> 
                                            <span id="err_transit_matikata"></span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-1 offset-md-5">
                                <button type="button" class="btn btn-secondary  custm-btn" id="E-p-btn1">Previous
                                </button>
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary next_btn custm-btn" id="E-n-btn2">Save &
                                    Finish
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div> 
 @include('forms.crop_modal')
        </div>

    </div>
</div>
<!-- <div class="applicant-dtl-loading-progress"></div>
<div class="vehicle-dtl-loading-progress"></div>
<div class="driver-dtl-loading-progress"></div>
<div class="doc-loading-progress"></div>
<div class="sms-loading-progress"></div> -->
<div id="ajax-loader" hidden>
    <div>
        <img src="{{ url('assets/images/loading_spinner.gif') }}" />
        <div>Please wait for a while</div>
    </div>
</div>