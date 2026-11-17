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
        <h4>
            Application Edit Form</h4>
    </div>
    <ul class="nav nav-tabs" id="E-myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="app-info-tab" data-toggle="tab" href="#app-info" role="tab"
                aria-controls="app-info" aria-selected="true">Applicant's Details</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="vehicle-tab" data-toggle="tab" href="#vehicle" role="tab"
                aria-controls="vehicle" aria-selected="false">Vehicle details</a>
        </li>
        {{-- <li class="nav-item">
      <a class="nav-link" id="driver-tab" data-toggle="tab" href="#driver" role="tab" aria-controls="driver" aria-selected="false">Driver's details</a>
    </li>
    <li class="nav-item">
      <a class="nav-link " id="documents-tab" data-toggle="tab" href="#document" role="tab" aria-controls="document" aria-selected="false">Documents</a>
    </li> --}}
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane show fade active" id="app-info" role="tabpanel" aria-labelledby="app-info-tab">
            <div class="container-fluid">
                <form id="applicant_detail_form_Edit" class="form Applycationform" enctype="multipart/form-data"
                    data-id="{{ $app->id }}">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="details-container">
                            <div class="form-personal_info">

                                @if (
                                    !request()->is('*/applicant/*') &&
                                        ($app->app_status == 'delivered' || $app->app_status == 'updated' || $app->app_status == 'issued'))
                                    <div class="row">
                                        <div class="col-md-3 offset-md-1">
                                            <label for="" class="label-form">Sticker Type</label> <span>*</span>
                                            <br>

                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" id="sticker_category"
                                                value="{{ $app->sticker_category ?? '' }}" name="sticker_category"
                                                class="form-control in-form" readonly disabled>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 offset-md-1">
                                            <label for="" class="label-form">Sticker For Year</label>
                                            <span>*</span>
                                            <br>

                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" id="sticker_for_year"
                                                value="{{ $app->sticker_for_year ?? '' }}" name="sticker_for_year"
                                                class="form-control in-form mandatory">
                                            <input type="hidden" id="year" name="year"
                                                value="{{ date('Y') }}" class="form-control in-form" placeholder=""
                                                required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 offset-md-1">
                                            <label for="" class="label-form">Sticker Number</label>
                                            <span>*</span>
                                            <br>

                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" id="sticker_number" name="sticker_number"
                                                value="{{ old('sticker_number', isset($app->sticker_number) ? $app->sticker_number : '') }}"
                                                class="form-control in-form delivery-sticker-number entair-focus"
                                                placeholder="" required>
                                        </div>
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="" class="label-form">Applicant's full name</label>
                                        <span>*</span> <br>
                                        <small>As per national ID card</small>
                                    </div>
                                    @if (!empty($app))
                                        <div class="col-md-8">
                                            <input type="text" id="applicant_name"
                                                value="{{ $app->applicant->name }}" name="applicant_name"
                                                class="form-control in-form mandatory">
                                        </div>
                                    @else
                                        <div class="col-md-8">
                                            <input type="text" id="applicant_name"
                                                value="{{ $app->applicant->name }}" name="applicant_name"
                                                class="form-control in-form mandatory" placeholder="">
                                            <div id="err_msg_applicantName" class="err_msg" hidden> <i
                                                    class="fas fa-exclamation-triangle"></i> <span
                                                    id="err_applicantName"> </span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                @if (!empty($app->applicant->applicantDetail->applicant_photo))
                                    <div class="row">
                                        <div class="col-md-3 offset-md-1">
                                            <label for="" class="label-form">Change Applicant's photo</label>
                                            <span>*</span> <br>
                                        </div>
                                        <div class="col-md-8">
                                            <div>
                                                <img id="prev_image1exist_e"
                                                    src="{{ url('/') }}{{ $app->applicant->applicantDetail->applicant_photo }}"
                                                    alt="preview application"
                                                    style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                                <span class="file_error"></span>
                                            </div>
                                            <input type="file" id="image1exist_e"
                                                accept="image/png, image/jpg, image/jpeg" name="applicant_photo"
                                                class="form-control in-form mandatory resize-image" placeholder=""
                                                value="">
                                            <div id="err_msg_applicantPhoto" class="err_msg" hidden> <i
                                                    class="fas fa-exclamation-triangle"></i> <span
                                                    id="err_applicantPhoto"> </span>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="row">
                                        <div class="col-md-3 offset-md-1">
                                            <label for="" class="label-form">Applicant's photo</label>
                                            <span>*</span> <br>
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
                                                    id="err_applicantPhoto"> </span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                {{-- Rank --}}
                                <div class="row">
                                    <div class="col-md-3 offset-md-1 not_applicant_spouse_child">
                                        <label for="" class="label-form">Rank</label> <span>*</span> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-8 mt-2 not_applicant_spouse_child">

                                        <select name="applicant_rank" id="applicant_rank"
                                            class="form-control in-form mandatory">
                                            <option selected value="" disabled>--Select Unit--</option>
                                            @if (isset($ranks) && count($ranks) > 0)
                                                @foreach ($ranks as $rank)
                                                    <option
                                                        {{ $app->applicant->applicantDetail->rank_id == $rank->id ? 'selected' : '' }}
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

                                {{-- BA Number --}}
                                <div class="row">
                                    <div class="col-md-3 offset-md-1 not_applicant_spouse_child">
                                        <label for="" class="label-form">BA Number</label> <span>*</span> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-8 not_applicant_spouse_child">
                                        @if (isset($app->applicant->applicantDetail->applicant_BA_no) && $app->applicant->applicantDetail->applicant_BA_no != '')
                                            <input type="text" class="form-control in-form"
                                                value="{{ $app->applicant->applicantDetail->applicant_BA_no }}"
                                                placeholder="" name="BA_no" id="BA_no">
                                            <div id="err_msg_applicant_BA_no" class="err_msg" hidden> <i
                                                    class="fas fa-exclamation-triangle"></i> <span
                                                    id="err_applicant_BA_no"> </span>
                                            </div>
                                        @else
                                            <input type="text" class="form-control in-form"
                                                value="{{ $app->applicant->applicantDetail->applicant_BA_no }}"
                                                placeholder="" name="BA_no" id="BA_no">
                                            <div id="err_msg_applicant_BA_no" class="err_msg" hidden> <i
                                                    class="fas fa-exclamation-triangle"></i> <span
                                                    id="err_applicant_BA_no"> </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Service ID CARD/Retd ID CARD  --}}
                                <div class="row">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="" class="label-form">Service Id card/Rtd ID Card (Front
                                            Part)</label>
                                        <span>*</span>
                                    </div>
                                    <div class="col-md-8">
                                        @if (!empty($app->applicant->applicantDetail->defIdCopy))
                                            <div>
                                                <img src="{{ !empty($app->applicant->applicantDetail->defIdCopy) ? url($app->applicant->applicantDetail->defIdCopy) : url(auth()->guard('applicant')->user()->applicantDetail->defIdCopy) }}"
                                                    id="prev_image2_e_def_id_Exist" alt="preview application"
                                                    style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                            </div>
                                            <input type="file" id="image2_e_def_id_Exist"
                                                accept="image/png, image/jpg, image/jpeg"
                                                name="applicant_Def_id_photo"
                                                class="form-control in-form mandatory resize-image" disabled="">
                                            <button type="button" name="" class="btn btn-info change-btn"
                                                data-photo="{{ !empty($app->applicant->applicantDetail->defIdCopy) ? url($app->applicant->applicantDetail->defIdCopy) : url(auth()->guard('applicant')->user()->applicantDetail->defIdCopy) }}">Change</button>
                                            <button type='button' class='btn btn-warning cancel-btn'
                                                hidden="">Cancel</button>
                                            <div id="err_msg_def_id" class="err_msg" hidden> <i
                                                    class="fas fa-exclamation-triangle"></i> <span id="err_def_id">
                                                </span>
                                            </div>
                                        @else
                                            <div>
                                                <img src="" id="prev_image2_e_def_id"
                                                    alt="preview application" hidden>
                                            </div>
                                            <input type="file" id="image2_e_def_id"
                                                accept="image/png, image/jpg, image/jpeg"
                                                name="applicant_Def_id_photo"
                                                class="form-control in-form mandatory resize-image" required>
                                            <div id="err_msg_def_id" class="err_msg" hidden> <i
                                                    class="fas fa-exclamation-triangle"></i> <span id="err_def_id">
                                                </span>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                                {{-- Service Id/Rtd ID Card Photo (Back) --}}
                                <div class="row">
                                    <div class="col-md-3 offset-md-1">
                                        <label class="label-form">Service Id/Rtd ID Card (Back Part)</label>
                                    </div>

                                    <div class="col-md-8">
                                        @if (!empty($app->applicant->applicantDetail->def_id_back_photo))
                                            <div>
                                                <img src="{{ url($app->applicant->applicantDetail->def_id_back_photo) }}"
                                                    id="prev_image3_e_def_id_Exist"
                                                    style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                            </div>

                                            <input type="file" id="image3_e_def_id_Exist"
                                                name="applicant_Def_id_back_photo"
                                                class="form-control in-form mandatory resize-image" disabled>

                                            <button type="button" class="btn btn-info change-btn"
                                                data-photo="{{ url($app->applicant->applicantDetail->def_id_back_photo) }}">
                                                Change
                                            </button>

                                            <button type="button" class="btn btn-warning cancel-btn"
                                                hidden>Cancel</button>
                                        @else
                                            <img id="prev_image3_e_def_id" hidden>

                                            <input type="file" id="image3_e_def_id"
                                                name="applicant_Def_id_back_photo"
                                                class="form-control in-form mandatory resize-image">
                                        @endif
                                    </div>
                                </div>
                                @php
                                    $rawAddress = $app->applicant->applicantDetail->address ?? null;
                                    $app_address = null;
                                    $address_text = '';

                                    if ($rawAddress) {
                                        $decoded = json_decode($rawAddress, true);

                                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                            $app_address = $decoded;

                                            // Present address text build
                                            if (!empty($decoded['present'])) {
                                                $p = $decoded['present'];

                                                $address_text = trim(
                                                    implode(
                                                        ', ',
                                                        array_filter([
                                                            $p['flat'] ?? null,
                                                            $p['house'] ?? null,
                                                            $p['road'] ?? null,
                                                            $p['block'] ?? null,
                                                            $p['area'] ?? null,
                                                        ]),
                                                    ),
                                                );
                                            }
                                        } else {
                                            // Already normal text
                                            $address_text = $rawAddress;
                                        }
                                    }
                                @endphp

                                <div class="row">
                                    <div class="col-md-3 offset-md-1">
                                        <label class="label-form">Address</label> <span>*</span>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" name="applicant_permanent_address"
                                            class="form-control mandatory" value="{{ $address_text }}"
                                            placeholder="Enter permanent address">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="" class="label-form">Mobile number</label> <span>*</span>
                                        <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" value="{{ $app->applicant->phone }}"
                                            name="applicant_phone" id="applicant_phone"
                                            class="form-control in-form mandatory" placeholder="">
                                        <div id="err_msg_applicantPhone" class="err_msg" hidden> <i
                                                class="fas fa-exclamation-triangle"></i> <span
                                                id="err_applicantPhone"> </span>
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
                                            value="{{ $app->applicant->email ? $app->applicant->email : '' }}"
                                            name="applicant_email" class="form-control in-form  mandatory"
                                            placeholder="">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="" class="label-form">Applicant Remarks </label>
                                        <span></span> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-8">
                                        <textarea id="applicant_remarks" name="applicant_remarks" class="form-control in-form" value="">{{ !empty($app->remark) ? $app->remark : '' }}</textarea>
                                    </div>
                                </div>


                            </div>
                        </div>
                        <div class="col-md-2 offset-md-5">
                            <button type="submit" class="btn btn-primary custm-btn" id="E-n-btn1">Update &
                                Save</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>

        <div class="tab-pane" id="vehicle" role="tabpanel" aria-labelledby="vehicle-tab">

            <div class="container-fluid">
                <form id="vehicle_detail_form_Edit" data-id="{{ $app->id }}" class="form Applycationform"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="vehicle-details-container">
                            <div class="row">
                                <div class="col-md-3 offset-md-1">
                                    <label for="" class="label-form">Vehicle type</label> <span>*</span> <br>
                                    <small></small>
                                </div>
                                @if (!empty($app))
                                    <div class="col-md-8">
                                        <select name="vehicle_type" id="vehicle_type"
                                            class="form-control-sm mandatory">
                                            <option selected value="{{ $app->vehicleinfo->vehicleType->id }}">
                                                {{ $app->vehicleinfo->vehicleType->name }}</option>
                                            {{-- <?#php $vehicleTypes = App\Models\VehicleType::all(); ?> --}}
                                            @if (isset($vehicleTypes))
                                                @foreach ($vehicleTypes as $vehicleType)
                                                    <option value="{{ $vehicleType->id }}">{{ $vehicleType->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>

                                    </div>
                            </div>
                            @endif
                            <div class="row">
                                <div class="col-md-3 offset-md-1">
                                    <label for="" class="label-form">Vehicle registration number</label>
                                    <span>*</span> <br>
                                    <small></small>
                                </div>
                                @if (!empty($app))
                                    <div class="col-md-8">
                                        <input type="text" name="vehicle_reg_no" id="vehicle_reg_no"
                                            class="form-control in-form mandatory"
                                            value="{{ $app->vehicleinfo->reg_number }}">
                                    </div>
                                @else
                                    <div class="col-md-8">
                                        <input type="text" name="vehicle_reg_no" id="vehicle_reg_no"
                                            class="form-control in-form mandatory" placeholder="5654445454">
                                        <div id="err_msg_vehiclereg" class="err_msg" hidden><i
                                                class="fas fa-exclamation-triangle"></i>
                                            <span id="err_vehiclereg"> </span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            {{-- Registration certificate (Front) --}}
                            <div class="row">
                                <div class="col-md-3 offset-md-1">
                                    <label for="" class="label-form">Registration certificate (Front
                                        Part)</label> <span>*</span> <br>
                                    <small></small>
                                </div>
                                <div class="col-md-8">
                                    @if (!empty($app))
                                        <div>
                                            <img src="{{ url('') }}{{ $app->vehicleinfo->reg_cert_photo }}"
                                                id="prev_image2_b_exist" alt="preview application"
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                        <input type="file" id="image2_b_exist"
                                            accept="image/png, image/jpg, image/jpeg"
                                            class="form-control in-form mandatory resize-image"
                                            name="vehicle_reg_photo">
                                        <div id="err_msg_vehicleregphoto" class="err_msg" hidden> <i
                                                class="fas fa-exclamation-triangle"></i> <span
                                                id="err_vehicleregphoto"> </span>
                                        </div>
                                    @else
                                        <div>
                                            <img src="" id="prev_image2_b" alt="preview application" hidden>
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
                                </div>
                            </div>

                            {{-- Registration certificate (Back) --}}
                            <div class="row">
                                <div class="col-md-3 offset-md-1">
                                    <label for="" class="label-form">Registration certificate (Back
                                        Part)</label> <span></span>
                                    <br>
                                    <small></small>
                                </div>
                                <div class="col-md-8">
                                    @if (!empty($app->vehicleinfo->reg_cert_back_photo))
                                        <div>
                                            <img src="{{ url('') }}{{ $app->vehicleinfo->reg_cert_back_photo }}"
                                                id="prev_image3_b_exist" alt="preview application"
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                        <input type="file" id="image3_b_exist"
                                            accept="image/png, image/jpg, image/jpeg"
                                            class="form-control in-form mandatory resize-image"
                                            name="vehicle_reg_back_photo">
                                        <div id="err_msg_vehicleregphoto" class="err_msg" hidden><i
                                                class="fas fa-exclamation-triangle"></i> <span
                                                id="err_vehicleregphoto"> </span></div>
                                    @else
                                        <div>
                                            <img src="" id="prev_image3_b" alt="preview application" hidden
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                        <input type="file" id="image3_b"
                                            accept="image/png, image/jpg, image/jpeg"
                                            class="form-control in-form mandatory resize-image"
                                            name="vehicle_reg_back_photo">
                                        <div id="err_msg_vehicleregphoto" class="err_msg" hidden><i
                                                class="fas fa-exclamation-triangle"></i> <span
                                                id="err_vehicleregphoto"> </span></div>
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
                                        <option value="" selected disabled>Select Vehicle Owner</option>
                                        <option
                                            {{ $app->vehicleowner->vehicle_owner_type == 'self' ? 'selected' : '' }}
                                            value="self">Self</option>
                                        <option
                                            {{ $app->vehicleowner->vehicle_owner_type == 'spouse' ? 'selected' : '' }}
                                            value="spouse">Spouse</option>
                                        <option
                                            {{ $app->vehicleowner->vehicle_owner_type == 'civil_company' ? 'selected' : '' }}
                                            value="civil_company">Civil Company</option>
                                        <option
                                            {{ $app->vehicleowner->vehicle_owner_type == 'govt_company' ? 'selected' : '' }}
                                            value="govt_company">Govt. Institution</option>
                                        <option
                                            {{ $app->vehicleowner->vehicle_owner_type == 'other' ? 'selected' : '' }}
                                            value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Self - Applicant's Name -->
                            <div id="owner_name_row" style="display: none;">
                                <div class="row">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="owner_name" class="label-form">Applicant's Name</label>
                                        <span>*</span> <br>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" name="owner_name" id="owner_name"
                                            class="form-control in-form" placeholder="Enter owner name"
                                            value="{{ $app->vehicleowner->owner_name }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Spouse - Passport/NID Copy -->
                            <div id="passport_copy_row" style="display: none;">
                                <div class="row">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="spouse_passport_copy" class="label-form">Attach Scanned copy of
                                            Passport</label> <span>*</span> <br>
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
                            </div>

                            <!-- Civil Company - Certificate -->
                            <div id="civil_company_row" style="display: none;">
                                <div class="row">
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
                            </div>

                            <!-- Govt. Institution - Certificate -->
                            <div id="govt_company_row" style="display: none;">
                                <div class="row">
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
                            </div>
                            @if ($app->vehicleowner->vehicle_owner_type == 'self')
                                <div class="row" id="owner_name_row">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="owner_name" class="label-form">Applicant's Name</label>
                                        <span>*</span> <br>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" name="owner_name" id="owner_name"
                                            class="form-control in-form" placeholder="Enter owner name"
                                            value="{{ $app->vehicleowner->owner_name }}" readonly>
                                    </div>
                                </div>
                            @endif

                            @if ($app->vehicleowner->vehicle_owner_type == 'spouse')
                                <div class="row" id="passport_copy_row">
                                    <div class="col-md-3 offset-md-1">
                                        <label class="label-form">Attach Scanned copy of Passport/NID</label>
                                        <span>*</span> <br>
                                        <small>Change passport or NID copy</small>
                                    </div>

                                    <div class="col-md-8">
                                        @if (!empty($app) && !empty($app->vehicleowner->owner_type_attachment))
                                            {{-- Existing file preview --}}
                                            <div>
                                                <img src="{{ url('') }}{{ $app->vehicleowner->owner_type_attachment }}"
                                                    id="prev_passport_exist" alt="preview passport"
                                                    style="display:inline-block;height:auto;padding:10px 0;max-width:200px;max-height:200px;">
                                            </div>

                                            <input type="file" id="passport_copy_exist"
                                                name="spouse_passport_copy"
                                                accept="image/png,image/jpg,image/jpeg,application/pdf"
                                                class="form-control in-form mandatory">
                                        @else
                                            <div>
                                                <img src="" id="prev_passport" alt="preview passport" hidden>
                                            </div>

                                            <input type="file" id="passport_copy" name="spouse_passport_copy"
                                                accept="image/png,image/jpg,image/jpeg,application/pdf"
                                                class="form-control in-form mandatory">
                                        @endif

                                        <div id="err_msg_passport" class="err_msg" hidden>
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <span id="err_passport"></span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($app->vehicleowner->vehicle_owner_type == 'civil_company')
                                <div class="row" id="civil_company_row">
                                    <div class="col-md-3 offset-md-1">
                                        <label class="label-form">Attach Certificate of the Company</label>
                                        <span>*</span> <br>
                                        <small>Change company certificate</small>
                                    </div>

                                    <div class="col-md-8">
                                        @if (!empty($app) && !empty($app->vehicleowner->owner_type_attachment))
                                            {{-- Existing certificate preview --}}
                                            <div>
                                                <img src="{{ url('') }}{{ $app->vehicleowner->owner_type_attachment }}"
                                                    id="prev_civil_company_exist" alt="preview certificate"
                                                    style="display:inline-block;height:auto;padding:10px 0;max-width:200px;max-height:200px;">
                                            </div>

                                            <input type="file" id="civil_company_certificate_exist"
                                                name="civil_company_certificate"
                                                accept="image/png,image/jpg,image/jpeg,application/pdf"
                                                class="form-control in-form mandatory">
                                        @else
                                            <div>
                                                <img src="" id="prev_civil_company_certificate"
                                                    alt="preview certificate" hidden>
                                            </div>

                                            <input type="file" id="civil_company_certificate"
                                                name="civil_company_certificate"
                                                accept="image/png,image/jpg,image/jpeg,application/pdf"
                                                class="form-control in-form mandatory">
                                        @endif

                                        <div id="err_msg_civil_certificate" class="err_msg" hidden>
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <span id="err_civil_certificate"></span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($app->vehicleowner->vehicle_owner_type == 'govt_company')
                                <div class="row" id="govt_company_row">
                                    <div class="col-md-3 offset-md-1">
                                        <label class="label-form">Attach Certificate of the Govt. Institution</label>
                                        <span>*</span> <br>
                                        <small>Change government institution certificate</small>
                                    </div>

                                    <div class="col-md-8">
                                        @if (!empty($app) && !empty($app->vehicleowner->owner_type_attachment))
                                            <div>
                                                <img src="{{ url('') }}{{ $app->vehicleowner->owner_type_attachment }}"
                                                    id="prev_govt_company_exist" alt="preview certificate"
                                                    style="display:inline-block;height:auto;padding:10px 0;max-width:70%;max-height:200px;">
                                            </div>

                                            <input type="file" id="govt_institution_certificate_exist"
                                                name="govt_institution_certificate"
                                                accept="image/png,image/jpg,image/jpeg,application/pdf"
                                                class="form-control in-form mandatory">
                                        @else
                                            <div>
                                                <img src="" id="prev_govt_institution_certificate"
                                                    alt="preview certificate" hidden>
                                            </div>

                                            <input type="file" id="govt_institution_certificate"
                                                name="govt_institution_certificate"
                                                accept="image/png,image/jpg,image/jpeg,application/pdf"
                                                class="form-control in-form mandatory">
                                        @endif

                                        <div id="err_msg_govt_certificate" class="err_msg" hidden>
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <span id="err_govt_certificate"></span>
                                        </div>
                                    </div>
                                </div>
                            @endif

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

                        <!-- <div class="col-md-1 offset-md-5">
                            <button type="button" class="btn btn-secondary  custm-btn" id="E-p-btn1">Previous</button>
                          </div> -->
                        <div class="col-md-2 offset-md-5">
                            <button type="submit" class="btn btn-primary next_btn custm-btn" id="E-n-btn2">Update &
                                Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="tab-pane" id="driver" role="tabpanel" aria-labelledby="driver-tab">
            <div class="container-fluid">
                <form id="driver_detail_form_Edit" data-id="{{ $app->id }}" class="form Applycationform"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="driver-details-container">
                            <div class="row">
                                <div class="col-md-3 offset-md-1">
                                    <label for="" class="label-form">Is Vehicle Self Driven?</label>
                                    <span></span> <br>
                                    <small></small>
                                </div>
                                <div class="col-md-8">
                                    @if (!empty($app->driverinfo->driver_is_owner) && $app->driverinfo->driver_is_owner == 1)
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
                                    <label for="" class="label-form">Driver's name</label> <span>*</span> <br>
                                    <small></small>
                                </div>

                                <div class="col-md-8 not_self_driven" hidden>
                                    @if (!empty($app->driverinfo->name))
                                        <input type="text" name="name" id="driver_name"
                                            class="form-control in-form" value="{{ $app->driverinfo->name }}">
                                        <div id="err_msg_drivername" class="err_msg" hidden> <i
                                                class="fas fa-exclamation-triangle"></i> <span id="err_drivername">
                                            </span>
                                        </div>
                                    @else
                                        <input type="text" name="name" id="driver_name"
                                            class="form-control in-form">
                                        <div id="err_msg_drivername" class="err_msg" hidden> <i
                                                class="fas fa-exclamation-triangle"></i> <span id="err_drivername">
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
                                    @if (!empty($app->driverinfo->photo))
                                        <div>
                                            <img src="{{ url('') }}{{ $app->driverinfo->photo }}"
                                                id="prev_image10_b_exist" alt="preview application"
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                        <input type="file" id="image10_b_exist"
                                            accept="image/png, image/jpg, image/jpeg" name="photo"
                                            class="form-control in-form" disabled>
                                        <button type="button" name="" class="btn btn-info change-btn"
                                            data-photo="{{ !empty($app->driverinfo->photo) ? url($app->driverinfo->photo) : '' }}">Change
                                        </button>
                                        <button type='button' class='btn btn-warning cancel-btn'
                                            hidden="">Cancel</button>
                                    @else
                                        <div>
                                            <img src="" id="prev_image10_b" alt="preview application" hidden>
                                        </div>
                                        <input type="file" id="image10_b"
                                            accept="image/png, image/jpg, image/jpeg" name="photo"
                                            class="form-control in-form" placeholder="">
                                        <div id="err_msg_driverphoto" class="err_msg" hidden> <i
                                                class="fas fa-exclamation-triangle"></i> <span id="err_driverphoto">
                                            </span>
                                        </div>
                                    @endif

                                </div>
                            </div>
                            <?php $driver_address = null; ?>
                            @if (!empty($app->driverinfo->address))
                                <?php $driver_address = json_decode($app->driverinfo->address, true); ?>
                            @endif
                            <div class="row">
                                <div class="col-md-3 offset-md-1 not_self_driven" hidden>
                                    <p style="margin-bottom: 0px; margin-top: 30px;"><b>Present Address</b></p> <br>
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
                                    <label for="" class="label-form">Block/Section</label> <span>*</span> <br>
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
                                    <p style="margin-bottom: 0px; margin-top: 30px;"><b>Permanent Address</b></p> <br>
                                    <small></small>
                                </div>
                                <div class="col-md-8 not_self_driven" hidden>


                                    @if (isset($driver_address['permanent']) != '' &&
                                            isset($driver_address['present']['house']) == isset($driver_address['permanent']['p_flat']) &&
                                            isset($driver_address['present']['flat']) == isset($driver_address['permanent']['p_house']) &&
                                            isset($driver_address['present']['road']) == isset($driver_address['permanent']['p_road']) &&
                                            isset($driver_address['present']['block']) == isset($driver_address['permanent']['p_block']) &&
                                            isset($driver_address['present']['area']) == isset($driver_address['permanent']['p_area']))
                                        <input type="checkbox" name="driver_address_same_as_present"
                                            id="driver_address_same_as_present" class="driver_address_same_as_present"
                                            checked style="margin-top: 3px;width: 16px; height: 16px;">
                                    @else
                                        <input type="checkbox" name="driver_address_same_as_present"
                                            id="driver_address_same_as_present" class="driver_address_same_as_present"
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
                                    <input type="text" name="dri_per_flat" id="dri_per_flat"
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
                                    <input type="text" name="dri_per_house" id="dri_per_house"
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
                                    <input type="text" name="dri_per_road" id="dri_per_road"
                                        class="driver_per_road form-control in-form" placeholder=""
                                        value="{{ !empty($driver_address['permanent']['p_road']) ? $driver_address['permanent']['p_road'] : '' }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 offset-md-1 not_self_driven" hidden>
                                    <label for="" class="label-form">Block/Section</label> <span></span> <br>
                                    <small></small>
                                </div>
                                <div class="col-md-8 not_self_driven driver_perm" hidden>
                                    <input type="text" name="dri_per_block" id="dri_per_block"
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
                                    <input type="text" name="dri_per_area" id="dri_per_area"
                                        class="driver_per_area  form-control in-form" placeholder=""
                                        value="{{ !empty($driver_address['permanent']['p_area']) ? $driver_address['permanent']['p_area'] : '' }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 offset-md-1 not_self_driven" hidden>
                                    <label for="" class="label-form">National ID number</label> <span>*</span>
                                    <br>
                                    <small></small>
                                </div>
                                <div class="col-md-8 not_self_driven" hidden>
                                    @if (!empty($app->driverinfo->nid_number))
                                        <input type="number" name="nid_number" id="drivernid_number"
                                            class="form-control in-form mandatory" placeholder="5654445454"
                                            value="{{ $app->driverinfo->nid_number }}">
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
                                    @if (!empty($app->driverinfo->nid_photo))
                                        <div>
                                            <img src="{{ url('') }}{{ $app->driverinfo->nid_photo }}"
                                                id="prev_image11_b_exists" alt="preview application"
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                        <input type="file" accept="image/png, image/jpg, image/jpeg"
                                            id="image11_b_exists" name="nid_photo" class="form-control  in-form"
                                            disabled>
                                        <button type="button" name="" class="btn btn-info change-btn"
                                            data-photo="{{ !empty($app->driverinfo->nid_photo) ? url($app->driverinfo->nid_photo) : '' }}">Change
                                        </button>
                                        <button type='button' class='btn btn-warning cancel-btn'
                                            hidden="">Cancel</button>
                                    @else
                                        <div>
                                            <img src="" id="prev_image11_b"
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;"
                                                alt="preview application" hidden>
                                        </div>
                                        <input type="file" accept="image/png, image/jpg, image/jpeg"
                                            id="image11_b" name="nid_photo" class="form-control mandatory in-form">
                                        <div id="err_msg_driverNidcopy" class="err_msg" hidden> <i
                                                class="fas fa-exclamation-triangle"></i> <span id="err_driverNidcopy">
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 offset-md-1">
                                    <label for="" class="label-form">Driving license copy</label>
                                    <span>*</span> <br>
                                    <small></small>
                                </div>
                                <div class="col-md-8">
                                    @if (!empty($app->driverinfo->licence_photo))
                                        <div>
                                            <img src="{{ url('') }}{{ $app->driverinfo->licence_photo }}"
                                                id="prev_image12_b_exist" alt="preview application"
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                        <input type="file" id="image12_b_exist"
                                            accept="image/png, image/jpg, image/jpeg" name="licence_photo"
                                            class="form-control in-form mandatory" disabled>
                                        <button type="button" name="" class="btn btn-info change-btn"
                                            data-photo="{{ !empty($app->driverinfo->licence_photo) ? url($app->driverinfo->licence_photo) : '' }}">Change
                                        </button>
                                        <button type='button' class='btn btn-warning cancel-btn'
                                            hidden="">Cancel</button>
                                        <div id="err_msg_driverlicence" class="err_msg" hidden> <i
                                                class="fas fa-exclamation-triangle"></i> <span id="err_driverlicence">
                                            </span>
                                        </div>
                                    @else
                                        <div>
                                            <img src="" id="prev_image12_b"
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;"
                                                alt="preview application" hidden>
                                        </div>
                                        <input type="file" id="image12_b"
                                            accept="image/png, image/jpg, image/jpeg" name="licence_photo"
                                            class="form-control in-form mandatory">
                                        <div id="err_msg_driverlicence" class="err_msg" hidden> <i
                                                class="fas fa-exclamation-triangle"></i> <span id="err_driverlicence">
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>

                        <!--  <div class="col-md-1 offset-md-5">
                          <button type="button" class="btn btn-secondary custm-btn" id="E-p-btn2">Previous</button>
                        </div> -->
                        <div class="col-md-2 offset-md-5">
                            <button type="submit" class="btn btn-primary  custm-btn" id="E-n-btn3">Update &
                                Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="tab-pane " id="document" role="tabpanel" aria-labelledby="documents-tab">
            <div class="container-fluid">
                <form id="document_form_Edit" data-id="{{ $app->id }}" class="form Applycationform"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="doc-details-container">
                            <div class="row">
                                <div class="col-md-3 offset-md-1 ">
                                    <label for="" class="label-form">Father's Testimonial: </label>
                                    <span></span> <br>
                                    <small></small>
                                </div>
                                <div class="col-md-8 ">
                                    @if (!empty($app->document->father_testm))
                                        <div>
                                            <img src="{{ url('') }}{{ $app->document->father_testm }}"
                                                id="prev_image11_b_father_test_exist"
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;"
                                                alt="preview application">
                                        </div>
                                        <input type="file" accept="image/png, image/jpg, image/jpeg"
                                            id="image11_b_father_test_exist" name="father_testm_photo"
                                            class="form-control mandatory in-form">
                                        <div id="" class="err_msg" hidden> <i
                                                class="fas fa-exclamation-triangle"></i> <span id=""> </span>
                                        </div>
                                    @else
                                        <div>
                                            <img src="" id="prev_image11_b_father_test"
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;"
                                                alt="preview application" hidden>
                                        </div>
                                        <input type="file" accept="image/png, image/jpg, image/jpeg"
                                            id="image11_b_father_test" name="father_testm_photo"
                                            class="form-control mandatory in-form">
                                        <div id="" class="err_msg" hidden> <i
                                                class="fas fa-exclamation-triangle"></i> <span id=""> </span>
                                        </div>
                                    @endif

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 offset-md-1 ">
                                    <label for="" class="label-form">Mother's Testimonial: </label>
                                    <span></span> <br>
                                    <small></small>
                                </div>
                                <div class="col-md-8 ">
                                    @if (!empty($app->document->mother_testm))
                                        <div>
                                            <img src="{{ url('') }}{{ $app->document->mother_testm }}"
                                                id="prev_image11_b_mother_test_exist"
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;"
                                                alt="preview application">
                                        </div>
                                        <input type="file" accept="image/png, image/jpg, image/jpeg"
                                            id="image11_b_mother_test_exist" name="mother_testm_photo"
                                            class="form-control mandatory in-form">
                                        <div id="" class="err_msg" hidden> <i
                                                class="fas fa-exclamation-triangle"></i> <span id=""> </span>
                                        </div>
                                    @else
                                        <div>
                                            <img src="" id="prev_image11_b_mother_test"
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;"
                                                alt="preview application" hidden>
                                        </div>
                                        <input type="file" accept="image/png, image/jpg, image/jpeg"
                                            id="image11_b_mother_test" name="mother_testm_photo"
                                            class="form-control mandatory in-form">
                                        <div id="" class="err_msg" hidden> <i
                                                class="fas fa-exclamation-triangle"></i> <span id="">
                                            </span>
                                        </div>
                                    @endif

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 offset-md-1 ">
                                    <label for="" class="label-form"> Authorized Certificate : </label>
                                    <span></span> <br>
                                    <small></small>
                                </div>
                                <div class="col-md-8 ">
                                    @if (!empty($app->document->auth_cert))
                                        <div>
                                            <img src="{{ url('') }}{{ $app->document->auth_cert }}"
                                                id="prev_image11_b_auth_cert_exist" alt="preview application"
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                        <input type="file" id="image11_b_auth_cert_exist"
                                            accept="image/png, image/jpg, image/jpeg" name="auth_cert_photo"
                                            class="form-control in-form mandatory">
                                        <div id="" class="err_msg" hidden> <i
                                                class="fas fa-exclamation-triangle"></i> <span id="">
                                            </span>
                                        </div>
                                    @else
                                        <div>
                                            <img src="" id="prev_image11_b_auth_cert"
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;"
                                                alt="preview application" hidden>
                                        </div>
                                        <input type="file" id="image11_b_auth_cert"
                                            accept="image/png, image/jpg, image/jpeg" name="auth_cert_photo"
                                            class="form-control in-form mandatory">
                                        <div id="" class="err_msg" hidden> <i
                                                class="fas fa-exclamation-triangle"></i> <span id="">
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 offset-md-1 ">
                                    <label for="" class="label-form"> Marriage Certificate Copy: </label>
                                    <span></span> <br>
                                    <small></small>
                                </div>
                                <div class="col-md-8 ">
                                    @if (!empty($app->document->marriage_cert))
                                        <div>
                                            <img src="{{ url('') }}{{ $app->document->marriage_cert }}"
                                                id="prev_image11_b_marriage_cert_exist"
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;"
                                                alt="preview application">
                                        </div>
                                        <input type="file" accept="image/png, image/jpg, image/jpeg"
                                            id="image11_b_marriage_cert_exist" name="marriage_cert_photo"
                                            class="form-control in-form">
                                        <div id="" class="err_msg" hidden> <i
                                                class="fas fa-exclamation-triangle"></i> <span id="">
                                            </span>
                                        </div>
                                    @else
                                        <div>
                                            <img src="" id="prev_image11_b_marriage_cert"
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;"
                                                alt="preview application" hidden>
                                        </div>
                                        <input type="file" accept="image/png, image/jpg, image/jpeg"
                                            id="image11_b_marriage_cert" name="marriage_cert_photo"
                                            class="form-control in-form">
                                        <div id="" class="err_msg" hidden> <i
                                                class="fas fa-exclamation-triangle"></i> <span id="">
                                            </span>
                                        </div>
                                    @endif

                                </div>
                            </div>

                        </div>
                        <div class="col-md-2 offset-md-5">
                            <button type="submit" id="submit-btn"
                                class="btn btn-primary submit-btn custm-btn">Update & Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </form>
    @include('forms.crop_modal')
</div>
<!-- <div class="applicant-dtl-loading-progress"></div>
<div class="vehicle-dtl-loading-progress"></div>
<div class="driver-dtl-loading-progress"></div>
<div class="doc-loading-progress"></div> -->
<div id="ajax-loader" hidden>
    <div>
        <img src="{{ url('assets/images/loading_spinner.gif') }}" />
        <div>Please wait for a while</div>
    </div>
</div>

<script>
    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            const file = input.files[0];

            // If PDF image preview skip
            if (file.type === 'application/pdf') {
                document.getElementById(previewId).style.display = 'none';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById(previewId);
                preview.src = e.target.result;
                preview.hidden = false;
                preview.style.display = 'inline-block';
            };
            reader.readAsDataURL(file);
        }
    }

    // Passport / NID
    document.addEventListener('change', function(e) {
        if (e.target.id === 'passport_copy_exist' || e.target.id === 'passport_copy') {
            previewImage(e.target, 'prev_passport_exist');
            previewImage(e.target, 'prev_passport');
        }

        // Civil company
        if (e.target.id === 'civil_company_certificate_exist' || e.target.id === 'civil_company_certificate') {
            previewImage(e.target, 'prev_civil_company_exist');
            previewImage(e.target, 'prev_civil_company_certificate');
        }

        // Govt company
        if (e.target.id === 'govt_institution_certificate_exist' || e.target.id ===
            'govt_institution_certificate') {
            previewImage(e.target, 'prev_govt_company_exist');
            previewImage(e.target, 'prev_govt_institution_certificate');
        }
    });

    document.addEventListener('DOMContentLoaded', function() {

        // Vehicle Owner Section
        const vehicleOwnerSelect = document.getElementById('vehicle_owner');
        const ownerNameRow = document.getElementById('owner_name_row');
        const passportCopyRow = document.getElementById('passport_copy_row');
        const civilCompanyRow = document.getElementById('civil_company_row');
        const govtCompanyRow = document.getElementById('govt_company_row');

        const ownerNameInput = document.getElementById('owner_name');
        const passportInput = document.getElementById('spouse_passport_copy');
        const civilCertificateInput = document.getElementById('civil_company_certificate');
        const govtCertificateInput = document.getElementById('govt_institution_certificate');

        const applicantNameInput = document.getElementById('applicant_name');
        const applicantName = applicantNameInput ? applicantNameInput.value : '';

        const passportPreview = document.getElementById('prev_spouse_passport');
        const civilCertificatePreview = document.getElementById('prev_civil_company_certificate');
        const govtCertificatePreview = document.getElementById('prev_govt_institution_certificate');

        // Helper: hide element
        function hide(el) {
            if (el) el.style.display = 'none';
        }

        // Helper: show element
        function show(el) {
            if (el) el.style.display = 'block';
        }

        // Reset previews
        function resetPreview(img) {
            if (img) {
                img.src = '';
                img.style.display = 'none';
            }
        }

        // Handle vehicle owner change
        if (vehicleOwnerSelect) {
            vehicleOwnerSelect.addEventListener('change', function() {

                // Hide all conditional rows
                hide(ownerNameRow);
                hide(passportCopyRow);
                hide(civilCompanyRow);
                hide(govtCompanyRow);

                // Reset inputs & required
                if (ownerNameInput) {
                    ownerNameInput.value = '';
                    ownerNameInput.required = false;
                }

                if (passportInput) {
                    passportInput.value = '';
                    passportInput.required = false;
                }

                if (civilCertificateInput) {
                    civilCertificateInput.value = '';
                    civilCertificateInput.required = false;
                }

                if (govtCertificateInput) {
                    govtCertificateInput.value = '';
                    govtCertificateInput.required = false;
                }

                resetPreview(passportPreview);
                resetPreview(civilCertificatePreview);
                resetPreview(govtCertificatePreview);

                // Show relevant section
                if (this.value === 'self') {
                    if (ownerNameInput) {
                        ownerNameInput.value = applicantName;
                        ownerNameInput.required = true;
                    }
                    show(ownerNameRow);

                } else if (this.value === 'spouse') {
                    show(passportCopyRow);
                    if (passportInput) passportInput.required = true;

                } else if (this.value === 'civil_company') {
                    show(civilCompanyRow);
                    if (civilCertificateInput) civilCertificateInput.required = true;

                } else if (this.value === 'govt_company') {
                    show(govtCompanyRow);
                    if (govtCertificateInput) govtCertificateInput.required = true;
                }
            });
        }

        // Generic preview handler
        function handlePreview(inputEl, previewEl) {
            if (!inputEl || !previewEl) return;

            inputEl.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;

                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        previewEl.src = event.target.result;
                        previewEl.style.display = 'inline-block';
                    };
                    reader.readAsDataURL(file);

                } else if (file.type === 'application/pdf') {
                    previewEl.src = '';
                    previewEl.style.display = 'none';
                    alert('PDF file selected: ' + file.name);
                }
            });
        }

        // Bind previews
        handlePreview(passportInput, passportPreview);
        handlePreview(civilCertificateInput, civilCertificatePreview);
        handlePreview(govtCertificateInput, govtCertificatePreview);

    });
</script>
