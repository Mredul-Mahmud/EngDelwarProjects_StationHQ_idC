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
            <a class="nav-link" id="documents-tab" data-toggle="tab" href="#document" role="tab" aria-controls="document" aria-selected="false">Documents</a>
        </li> --}}
    </ul>


    <div class="tab-content" id="myTabContent">
        <div class="tab-pane show fade active" id="app-info" role="tabpanel" aria-labelledby="app-info-tab">
            <div class="container-fluid">
                <form id="applicant_detail_form_Edit" data-id="{{ $app->id }}" class="form"
                    enctype="multipart/form-data">
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
                                            <input type="text" id="applicant_name" value=""
                                                name="applicant_name" class="form-control in-form mandatory"
                                                placeholder="">
                                            <div id="err_msg_applicantName" class="err_msg" hidden><i
                                                    class="fas fa-exclamation-triangle"></i> <span
                                                    id="err_applicantName"> </span></div>
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
                                                class="form-control in-form mandatory" placeholder="" value="">
                                            <div id="err_msg_applicantPhoto" class="err_msg" hidden><i
                                                    class="fas fa-exclamation-triangle"></i> <span
                                                    id="err_applicantPhoto"> </span></div>
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
                                                class="form-control in-form mandatory" placeholder="" value="">
                                            <div id="err_msg_applicantPhoto" class="err_msg" hidden><i
                                                    class="fas fa-exclamation-triangle"></i> <span
                                                    id="err_applicantPhoto"> </span></div>
                                        </div>
                                    </div>
                                @endif

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
                                        <div id="err_msg_applicantPhone" class="err_msg" hidden><i
                                                class="fas fa-exclamation-triangle"></i> <span
                                                id="err_applicantPhone"> </span></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="" class="label-form">Email</label> <span></span> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text"
                                            value="{{ $app->applicant->email ? $app->applicant->email : '' }}"
                                            name="applicant_email" class="form-control in-form" placeholder="">
                                    </div>
                                </div>

                                {{-- Nid Front --}}
                                <div class="row">
                                    @if (!empty($app->applicant->applicantDetail->nid_photo))
                                        <div class="col-md-3 offset-md-1">
                                            <label for="" class="label-form">Change NID Photo (Front)</label>
                                            <span>*</span> <br>
                                            <small></small>
                                        </div>
                                        <div class="col-md-8">
                                            <div>
                                                <img src="{{ url('/') }}{{ $app->applicant->applicantDetail->nid_photo }}"
                                                    id="prev_image2exist_e" alt="preview application"
                                                    style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                            </div>
                                            <input type="file" id="image2exist_e"
                                                accept="image/png, image/jpg, image/jpeg" name="applicant_nid_photo"
                                                class="form-control in-form mandatory resize-image" disabled="">
                                            <button type="button" name="" class="btn btn-info change-btn"
                                                data-photo="{{ !empty($app->applicant->applicantDetail->nid_photo) ? url($app->applicant->applicantDetail->nid_photo) : url(auth()->guard('applicant')->user()->applicantDetail->nid_photo) }}">
                                                Change
                                            </button>
                                            <button type='button' class='btn btn-warning cancel-btn'
                                                hidden="">Cancel</button>

                                            <div id="err_msg_appNidCopy" class="err_msg" hidden><i
                                                    class="fas fa-exclamation-triangle"></i> <span
                                                    id="err_appNidCopy"> </span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-md-3 offset-md-1">
                                            <label for="" class="label-form">NID Photo (Front)</label>
                                            <span>*</span> <br>
                                            <small></small>
                                        </div>
                                        <div class="col-md-8">
                                            <div>
                                                <img src="" id="prev_image2_e" alt="preview application"
                                                    hidden>
                                            </div>
                                            <input type="file" id="image2_e"
                                                accept="image/png, image/jpg, image/jpeg" name="applicant_nid_photo"
                                                class="form-control in-form mandatory resize-image" required>
                                            <div id="err_msg_appNidCopy" class="err_msg" hidden><i
                                                    class="fas fa-exclamation-triangle"></i> <span
                                                    id="err_appNidCopy"> </span>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- Nid Back --}}
                                <div class="row">
                                    @if (!empty($app->applicant->applicantDetail->nid_back_photo))
                                        <div class="col-md-3 offset-md-1">
                                            <label for="" class="label-form">Change NID Photo (Back)</label>
                                            <span>*</span> <br>
                                            <small></small>
                                        </div>
                                        <div class="col-md-8">
                                            <div>
                                                <img src="{{ url('/') }}{{ $app->applicant->applicantDetail->nid_back_photo }}"
                                                    id="prev_image3exist_e" alt="preview application"
                                                    style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                            </div>
                                            <input type="file" id="image3exist_e"
                                                accept="image/png, image/jpg, image/jpeg"
                                                name="applicant_nid_back_photo"
                                                class="form-control in-form mandatory resize-image" disabled="">
                                            <button type="button" name="" class="btn btn-info change-btn"
                                                data-photo="{{ !empty($app->applicant->applicantDetail->nid_back_photo) ? url($app->applicant->applicantDetail->nid_back_photo) : url(auth()->guard('applicant')->user()->applicantDetail->nid_back_photo) }}">
                                                Change
                                            </button>
                                            <button type='button' class='btn btn-warning cancel-btn'
                                                hidden="">Cancel</button>

                                            <div id="err_msg_appNidCopy" class="err_msg" hidden><i
                                                    class="fas fa-exclamation-triangle"></i> <span
                                                    id="err_appNidCopy"> </span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-md-3 offset-md-1">
                                            <label for="" class="label-form">NID Photo (Back)</label>
                                            <span>*</span> <br>
                                            <small></small>
                                        </div>
                                        <div class="col-md-8">
                                            <div>
                                                <img src="" id="prev_image4_e" alt="preview application"
                                                    hidden>
                                            </div>
                                            <input type="file" id="image4_e"
                                                accept="image/png, image/jpg, image/jpeg"
                                                name="applicant_nid_back_photo"
                                                class="form-control in-form mandatory resize-image">
                                            <div id="err_msg_appNidCopy" class="err_msg" hidden><i
                                                    class="fas fa-exclamation-triangle"></i> <span
                                                    id="err_appNidCopy"> </span>
                                            </div>
                                        </div>
                                    @endif
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
                                        <label for="" class="label-form">Applicant Remarks</label>
                                        <span></span> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-8">
                                        <textarea id="applicant_remarks" name="applicant_remarks" class="form-control in-form">{{ old('applicant_remarks', $app->applicant->applicantDetail->applicant_remark ?? '') }}</textarea>
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
                                @if (!empty($app->vehicleinfo->vehicleType->name))
                                    <div class="col-md-8">
                                        <select name="vehicle_type" id="vehicle_type"
                                            class="form-control-sm mandatory">
                                            <option selected value="{{ $app->vehicleinfo->vehicleType->id }}">
                                                {{ $app->vehicleinfo->vehicleType->name }}</option>
                                            <?php $vehicleTypes = App\Models\VehicleType::all(); ?>
                                            @if (isset($vehicleTypes))
                                                @foreach ($vehicleTypes as $vehicleType)
                                                    <option value="{{ $vehicleType->id }}">{{ $vehicleType->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                @else
                                    <div class="col-md-8">
                                        <select name="vehicle_type" id="vehicle_type"
                                            class="form-control-sm mandatory">
                                            <option selected="" disabled> Select A Vehicle</option>
                                            @if (isset($vehicleTypes))
                                                @foreach ($vehicleTypes as $vehicleType)
                                                    <option value="{{ $vehicleType->id }}">{{ $vehicleType->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <div id="err_msg_vehicletype" class="err_msg" hidden><i
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
                                @if (!empty($app->vehicleinfo->reg_number))
                                    <div class="col-md-8">
                                        <input type="text" name="vehicle_reg_no" id="vehicle_reg_no"
                                            class="form-control in-form mandatory"
                                            value="{{ $app->vehicleinfo->reg_number }}">
                                    </div>
                                @else
                                    <div class="col-md-8">
                                        <input type="text" name="vehicle_reg_no" id="vehicle_reg_no"
                                            class="form-control in-form mandatory"
                                            placeholder="Enter your vehicle registration number">
                                        <div id="err_msg_vehiclereg" class="err_msg" hidden><i
                                                class="fas fa-exclamation-triangle"></i>
                                            <span id="err_vehiclereg"> </span>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="row">
                                <div class="col-md-3 offset-md-1">
                                    <label for="" class="label-form">Registration certificate (Front
                                        Part)</label> <span>*</span>
                                    <br>
                                    <small></small>
                                </div>
                                <div class="col-md-8">
                                    @if (!empty($app->vehicleinfo->reg_cert_photo))
                                        <div>
                                            <img src="{{ url('') }}{{ $app->vehicleinfo->reg_cert_photo }}"
                                                id="prev_image2_b_exist" alt="preview application"
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                        <input type="file" id="image2_b_exist"
                                            accept="image/png, image/jpg, image/jpeg"
                                            class="form-control in-form mandatory resize-image"
                                            name="vehicle_reg_photo">
                                        <div id="err_msg_vehicleregphoto" class="err_msg" hidden><i
                                                class="fas fa-exclamation-triangle"></i> <span
                                                id="err_vehicleregphoto"> </span></div>
                                    @else
                                        <div>
                                            <img src="" id="prev_image2_b" alt="preview application" hidden
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                        <input type="file" id="image2_b"
                                            accept="image/png, image/jpg, image/jpeg"
                                            class="form-control in-form mandatory resize-image"
                                            name="vehicle_reg_photo">
                                        <div id="err_msg_vehicleregphoto" class="err_msg" hidden><i
                                                class="fas fa-exclamation-triangle"></i> <span
                                                id="err_vehicleregphoto"> </span></div>
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

                            <div class="row mt-2">
                                <div class="col-md-3 offset-md-1">
                                    <label for="vehicle_owner" class="label-form">Owner of the Vehicle</label>
                                    <span>*</span> <br>
                                </div>
                                <div class="col-md-8">
                                    <select name="vehicle_owner" id="vehicle_owner"
                                        class="form-control-sm mandatory">
                                        <option value="">Select Vehicle Owner</option>
                                        <option value="self"
                                            {{ !empty($app->vehicleowner->vehicle_owner_type) && $app->vehicleowner->vehicle_owner_type == 'self' ? 'selected' : '' }}>
                                            Self</option>
                                        <option value="spouse"
                                            {{ !empty($app->vehicleowner->vehicle_owner_type) && $app->vehicleowner->vehicle_owner_type == 'spouse' ? 'selected' : '' }}>
                                            Spouse</option>
                                        <option value="other"
                                            {{ !empty($app->vehicleowner->vehicle_owner_type) && $app->vehicleowner->vehicle_owner_type == 'other' ? 'selected' : '' }}>
                                            Other</option>
                                    </select>
                                </div>
                            </div>

                            <div id="owner_name_row"
                                style="{{ !empty($app->vehicleowner->vehicle_owner_type) && $app->vehicleowner->vehicle_owner_type == 'self' ? 'display: block;' : 'display: none;' }}">
                                <div class="row mt-2">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="owner_name" class="label-form">Applicant's Name</label>
                                        <span>*</span> <br>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" name="owner_name" id="owner_name"
                                            class="form-control in-form" placeholder="Enter owner name"
                                            value="{{ !empty($app->vehicleowner->owner_name) ? $app->vehicleowner->owner_name : auth()->guard('applicant')->user()->name }}">
                                    </div>
                                </div>
                            </div>

                            <div id="passport_copy_row"
                                style="{{ !empty($app->vehicleowner->vehicle_owner_type) && $app->vehicleowner->vehicle_owner_type == 'spouse' ? 'display: block;' : 'display: none;' }}">
                                <div class="row mt-2">
                                    <div class="col-md-3 offset-md-1">
                                        <label for="spouse_passport_copy" class="label-form">Attach Scanned copy of
                                            Passport</label> <span>*</span> <br>
                                    </div>
                                    <div class="col-md-8">
                                        @if (!empty($app->vehicleowner->owner_type_attachment))
                                            <div>
                                                <img src="{{ url('') }}{{ $app->vehicleowner->owner_type_attachment }}"
                                                    id="prev_spouse_passport" alt="preview passport"
                                                    style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                            </div>
                                            <input type="file" id="spouse_passport_copy"
                                                name="spouse_passport_copy"
                                                accept="image/png, image/jpg, image/jpeg, application/pdf"
                                                class="form-control in-form resize-image" placeholder="">
                                        @else
                                            <div>
                                                <img src="" id="prev_spouse_passport" alt="preview passport"
                                                    hidden
                                                    style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                            </div>
                                            <input type="file" id="spouse_passport_copy"
                                                name="spouse_passport_copy"
                                                accept="image/png, image/jpg, image/jpeg, application/pdf"
                                                class="form-control in-form resize-image" placeholder="">
                                        @endif
                                        <div id="err_msg_passport" class="err_msg" hidden>
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <span id="err_passport"></span>
                                        </div>
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

                            <div class="row mt-2">
                                <div class="col-md-3 offset-md-1">
                                    <label for="application_category" class="label-form">Application Category</label>
                                    <span>*</span> <br>
                                </div>
                                <div class="col-md-8">
                                    <select name="application_category" id="application_category"
                                        class="form-control-sm mandatory">
                                        <option value="">Select Application Category</option>
                                        <option value="service_retd"
                                            {{ !empty($app->application_category) && $app->application_category == 'service_retd' ? 'selected' : '' }}>
                                            Service/Retd Under Defence Ministry</option>
                                        <option value="house_owner"
                                            {{ !empty($app->application_category) && $app->application_category == 'house_owner' ? 'selected' : '' }}>
                                            House Owner Inside Dhaka Cantt.</option>
                                        <option value="civil_institution"
                                            {{ !empty($app->application_category) && $app->application_category == 'civil_institution' ? 'selected' : '' }}>
                                            Civil Institution/Establishment Inside Dhaka Cantt.</option>
                                        <option value="proyash_school"
                                            {{ !empty($app->application_category) && $app->application_category == 'proyash_school' ? 'selected' : '' }}>
                                            For Proyash School & College</option>
                                        <option value="teacher_official"
                                            {{ !empty($app->application_category) && $app->application_category == 'teacher_official' ? 'selected' : '' }}>
                                            Teacher/Official of School & College</option>
                                        <option value="bus_laguna"
                                            {{ !empty($app->application_category) && $app->application_category == 'bus_laguna' ? 'selected' : '' }}>
                                            Bus/Laguna</option>
                                        <option value="motorcycle"
                                            {{ !empty($app->application_category) && $app->application_category == 'motorcycle' ? 'selected' : '' }}>
                                            Motorcycle</option>
                                        <option value="transit_kochukhet"
                                            {{ !empty($app->application_category) && $app->application_category == 'transit_kochukhet' ? 'selected' : '' }}>
                                            Transit (Kochukhet to Banani) - (10000TK)</option>
                                        <option value="transit_matikata"
                                            {{ !empty($app->application_category) && $app->application_category == 'transit_matikata' ? 'selected' : '' }}>
                                            Transit (Matikata to Jahangir Gate) - (10000TK)</option>
                                        <option value="special"
                                            {{ !empty($app->application_category) && $app->application_category == 'special' ? 'selected' : '' }}>
                                            Special - (25000TK)</option>
                                    </select>
                                    <div id="err_msg_app_category" class="err_msg" hidden>
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <span id="err_app_category"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Service/Retd Under Defence Ministry -->
                            <div class="row category-document mt-2" id="doc_service_retd"
                                style="{{ !empty($app->application_category) && $app->application_category == 'service_retd' ? 'display: flex;' : 'display: none;' }}">
                                <div class="col-md-3 offset-md-1">
                                    <label for="service_retd_doc" class="label-form">Attach Job Certificate/Retd ID
                                        Card</label> <span>*</span> <br>
                                    <small>Upload job certificate or retirement ID card</small>
                                </div>
                                <div class="col-md-8">
                                    @if (!empty($app->app_category_attachment))
                                        <div>
                                            <img src="{{ url($app->app_category_attachment) }}"
                                                id="prev_service_retd_doc" alt="preview document"
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                    @else
                                        <div>
                                            <img src="" id="prev_service_retd_doc" alt="preview document"
                                                hidden
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                    @endif
                                    <input type="file" id="service_retd_doc" name="service_retd_doc"
                                        accept="image/png, image/jpg, image/jpeg, application/pdf"
                                        class="form-control in-form resize-image">
                                    <div id="err_msg_service_retd" class="err_msg" hidden>
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <span id="err_service_retd"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- House Owner Inside Dhaka Cantt. -->
                            <div class="row category-document mt-2" id="doc_house_owner"
                                style="{{ !empty($app->application_category) && $app->application_category == 'house_owner' ? 'display: flex;' : 'display: none;' }}">
                                <div class="col-md-3 offset-md-1">
                                    <label for="house_owner_doc" class="label-form">Attach CEO Certificate</label>
                                    <span>*</span> <br>
                                    <small>Upload CEO certificate</small>
                                </div>
                                <div class="col-md-8">
                                    @if (!empty($app->app_category_attachment))
                                        <div>
                                            <img src="{{ url($app->app_category_attachment) }}"
                                                id="prev_house_owner_doc" alt="preview document"
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                    @else
                                        <div>
                                            <img src="" id="prev_house_owner_doc" alt="preview document"
                                                hidden
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                    @endif
                                    <input type="file" id="house_owner_doc" name="house_owner_doc"
                                        accept="image/png, image/jpg, image/jpeg, application/pdf"
                                        class="form-control in-form resize-image">
                                    <div id="err_msg_house_owner" class="err_msg" hidden>
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <span id="err_house_owner"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Civil Institution/Establishment Inside Dhaka Cantt. -->
                            <div class="row category-document mt-2" id="doc_civil_institution"
                                style="{{ !empty($app->application_category) && $app->application_category == 'civil_institution' ? 'display: flex;' : 'display: none;' }}">
                                <div class="col-md-3 offset-md-1">
                                    <label for="civil_institution_doc" class="label-form">Attach Job
                                        Certificate</label> <span>*</span> <br>
                                    <small>Upload job certificate</small>
                                </div>
                                <div class="col-md-8">
                                    @if (!empty($app->app_category_attachment))
                                        <div>
                                            <img src="{{ url($app->app_category_attachment) }}"
                                                id="prev_civil_institution_doc" alt="preview document"
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                    @else
                                        <div>
                                            <img src="" id="prev_civil_institution_doc"
                                                alt="preview document" hidden
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                    @endif
                                    <input type="file" id="civil_institution_doc" name="civil_institution_doc"
                                        accept="image/png, image/jpg, image/jpeg, application/pdf"
                                        class="form-control in-form resize-image">
                                    <div id="err_msg_civil_institution" class="err_msg" hidden>
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <span id="err_civil_institution"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- For Proyash School & College -->
                            <div class="row category-document mt-2" id="doc_proyash_school"
                                style="{{ !empty($app->application_category) && $app->application_category == 'proyash_school' ? 'display: flex;' : 'display: none;' }}">
                                <div class="col-md-3 offset-md-1">
                                    <label for="proyash_school_doc" class="label-form">Attach Certificate Issued From
                                        Proyash</label> <span>*</span> <br>
                                    <small>Upload certificate issued from Proyash</small>
                                </div>
                                <div class="col-md-8">
                                    @if (!empty($app->app_category_attachment))
                                        <div>
                                            <img src="{{ url($app->app_category_attachment) }}"
                                                id="prev_proyash_school_doc" alt="preview document"
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                    @else
                                        <div>
                                            <img src="" id="prev_proyash_school_doc" alt="preview document"
                                                hidden
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                    @endif
                                    <input type="file" id="proyash_school_doc" name="proyash_school_doc"
                                        accept="image/png, image/jpg, image/jpeg, application/pdf"
                                        class="form-control in-form resize-image">
                                    <div id="err_msg_proyash_school" class="err_msg" hidden>
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <span id="err_proyash_school"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Teacher/Official of School & College -->
                            <div class="row category-document mt-2" id="doc_teacher_official"
                                style="{{ !empty($app->application_category) && $app->application_category == 'teacher_official' ? 'display: flex;' : 'display: none;' }}">
                                <div class="col-md-3 offset-md-1">
                                    <label for="teacher_official_doc" class="label-form">Attach Job
                                        Certificate</label> <span>*</span> <br>
                                    <small>Upload job certificate</small>
                                </div>
                                <div class="col-md-8">
                                    @if (!empty($app->app_category_attachment))
                                        <div>
                                            <img src="{{ url($app->app_category_attachment) }}"
                                                id="prev_teacher_official_doc" alt="preview document"
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                    @else
                                        <div>
                                            <img src="" id="prev_teacher_official_doc" alt="preview document"
                                                hidden
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                    @endif
                                    <input type="file" id="teacher_official_doc" name="teacher_official_doc"
                                        accept="image/png, image/jpg, image/jpeg, application/pdf"
                                        class="form-control in-form resize-image">
                                    <div id="err_msg_teacher_official" class="err_msg" hidden>
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <span id="err_teacher_official"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Bus/Laguna -->
                            <div class="row category-document mt-2" id="doc_bus_laguna"
                                style="{{ !empty($app->application_category) && $app->application_category == 'bus_laguna' ? 'display: flex;' : 'display: none;' }}">
                                <div class="col-md-3 offset-md-1">
                                    <label for="bus_laguna_doc" class="label-form">Attach Approved Copy From Sta HQ
                                        Dhaka</label> <span>*</span> <br>
                                    <small>Upload approved copy from Sta HQ Dhaka</small>
                                </div>
                                <div class="col-md-8">
                                    @if (!empty($app->app_category_attachment))
                                        <div>
                                            <img src="{{ url($app->app_category_attachment) }}"
                                                id="prev_bus_laguna_doc" alt="preview document"
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                    @else
                                        <div>
                                            <img src="" id="prev_bus_laguna_doc" alt="preview document"
                                                hidden
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                    @endif
                                    <input type="file" id="bus_laguna_doc" name="bus_laguna_doc"
                                        accept="image/png, image/jpg, image/jpeg, application/pdf"
                                        class="form-control in-form resize-image">
                                    <div id="err_msg_bus_laguna" class="err_msg" hidden>
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <span id="err_bus_laguna"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Motorcycle -->
                            <div class="row category-document mt-2" id="doc_motorcycle"
                                style="{{ !empty($app->application_category) && $app->application_category == 'motorcycle' ? 'display: flex;' : 'display: none;' }}">
                                <div class="col-md-3 offset-md-1">
                                    <label for="motorcycle_doc" class="label-form">Attach CEO Certificate/Job
                                        Certificate</label> <span>*</span> <br>
                                    <small>Upload CEO/job certificate</small>
                                </div>
                                <div class="col-md-8">
                                    @if (!empty($app->app_category_attachment))
                                        <div>
                                            <img src="{{ url($app->app_category_attachment) }}"
                                                id="prev_motorcycle_doc" alt="preview document"
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                    @else
                                        <div>
                                            <img src="" id="prev_motorcycle_doc" alt="preview document"
                                                hidden
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                    @endif
                                    <input type="file" id="motorcycle_doc" name="motorcycle_doc"
                                        accept="image/png, image/jpg, image/jpeg, application/pdf"
                                        class="form-control in-form resize-image">
                                    <div id="err_msg_motorcycle" class="err_msg" hidden>
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <span id="err_motorcycle"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Transit (Kochukhet to Banani) -->
                            <div class="row category-document mt-2" id="doc_transit_kochukhet"
                                style="{{ !empty($app->application_category) && $app->application_category == 'transit_kochukhet' ? 'display: flex;' : 'display: none;' }}">
                                <div class="col-md-3 offset-md-1">
                                    <label for="transit_kochukhet_doc" class="label-form">Attach Word Commissioner
                                        Certificate</label> <span>*</span> <br>
                                    <small>Upload word commissioner certificate</small>
                                </div>
                                <div class="col-md-8">
                                    @if (!empty($app->app_category_attachment))
                                        <div>
                                            <img src="{{ url($app->app_category_attachment) }}"
                                                id="prev_transit_kochukhet_doc" alt="preview document"
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                    @else
                                        <div>
                                            <img src="" id="prev_transit_kochukhet_doc"
                                                alt="preview document" hidden
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                    @endif
                                    <input type="file" id="transit_kochukhet_doc" name="transit_kochukhet_doc"
                                        accept="image/png, image/jpg, image/jpeg, application/pdf"
                                        class="form-control in-form resize-image">
                                    <div id="err_msg_transit_kochukhet" class="err_msg" hidden>
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <span id="err_transit_kochukhet"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Transit (Matikata to Jahangir Gate) -->
                            <div class="row category-document mt-2" id="doc_transit_matikata"
                                style="{{ !empty($app->application_category) && $app->application_category == 'transit_matikata' ? 'display: flex;' : 'display: none;' }}">
                                <div class="col-md-3 offset-md-1">
                                    <label for="transit_matikata_doc" class="label-form">Attach Word Commissioner
                                        Certificate</label> <span>*</span> <br>
                                    <small>Upload word Commissioner certificate</small>
                                </div>
                                <div class="col-md-8">
                                    @if (!empty($app->app_category_attachment))
                                        <div>
                                            <img src="{{ url($app->app_category_attachment) }}"
                                                id="prev_transit_matikata_doc" alt="preview document"
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                    @else
                                        <div>
                                            <img src="" id="prev_transit_matikata_doc" alt="preview document"
                                                hidden
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                    @endif
                                    <input type="file" id="transit_matikata_doc" name="transit_matikata_doc"
                                        accept="image/png, image/jpg, image/jpeg, application/pdf"
                                        class="form-control in-form resize-image">
                                    <div id="err_msg_transit_matikata" class="err_msg" hidden>
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <span id="err_transit_matikata"></span>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-2 offset-md-5">
                            <button type="submit" class="btn btn-primary next_btn custm-btn" id="E-n-btn2">Update &
                                Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="tab-pane " id="driver" role="tabpanel" aria-labelledby="driver-tab">
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
                                            name="self_driven" checked class="self_driven" style="margin-top: 3px;">
                                    @else
                                        <input type="checkbox" id="self_driven_checkbox" value="1"
                                            name="self_driven" class="self_driven" style="margin-top: 3px;">
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
                                            checked style="margin-top: 3px;width:16px;height:16px;">
                                    @else
                                        <input type="checkbox" name="driver_address_same_as_present"
                                            id="driver_address_same_as_present" class="driver_address_same_as_present"
                                            style="margin-top: 3px;width:16px;height:16px;">
                                    @endif
                                    <label for="driver_address_same_as_present"
                                        style="display: inline-block; margin-bottom: 0px; margin-top: 30px;cursor: pointer;"
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
                                                id="prev_image11_b_exist" alt="preview application"
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                        <input type="file" accept="image/png, image/jpg, image/jpeg"
                                            id="image11_b_exist" name="nid_photo" class="form-control  in-form"
                                            disabled>
                                        <input type="file" accept="image/png, image/jpg, image/jpeg"
                                            id="image11_b_exists" name="nid_photo" class="form-control  in-form"
                                            disabled>
                                        <button type="button" name="" class="btn btn-info change-btn"
                                            data-photo="{{ !empty($app->driverinfo->nid_photo) ? url($app->driverinfo->nid_photo) : '' }}">Change
                                        </button>
                                    @else
                                        <div>
                                            <img src="" id="prev_image11_b" alt="preview application" hidden>
                                        </div>
                                        <input type="file" accept="image/png, image/jpg, image/jpeg"
                                            id="image11_b" name="nid_photo" class="form-control mandatory in-form">
                                        <div id="err_msg_driverNidcopy" class="err_msg" hidden> <i
                                                class="fas fa-exclamation-triangle"></i> <span
                                                id="err_driverNidcopy"> </span>
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
                                                class="fas fa-exclamation-triangle"></i> <span
                                                id="err_driverlicence"> </span>
                                        </div>
                                    @else
                                        <div>
                                            <img src="" id="prev_image12_b" alt="preview application"
                                                hidden>
                                        </div>
                                        <input type="file" id="image12_b"
                                            accept="image/png, image/jpg, image/jpeg" name="licence_photo"
                                            class="form-control in-form mandatory">
                                        <div id="err_msg_driverlicence" class="err_msg" hidden> <i
                                                class="fas fa-exclamation-triangle"></i> <span
                                                id="err_driverlicence"> </span>
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
                                    <label for="" class="label-form">School Certificate: (If your Child
                                        Studying Proyash School/College.)</label> <span></span> <br>
                                    <small></small>
                                </div>
                                <div class="col-md-8 ">
                                    @if (!empty($app->document->school_cert))
                                        <div>
                                            <img src="{{ url('') }}{{ $app->document->school_cert }}"
                                                id="prev_image11_b_school_exist" alt="preview application"
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                        <input type="file" id="image11_b_school_exist"
                                            accept="image/png, image/jpg, image/jpeg" name="school_cert_photo"
                                            class="form-control in-form mandatory">
                                        <div id="" class="err_msg" hidden> <i
                                                class="fas fa-exclamation-triangle"></i> <span id="">
                                            </span>
                                        </div>
                                    @else
                                        <div>
                                            <img src="" id="prev_image11_b_school"
                                                alt="preview application" hidden>
                                        </div>
                                        <input type="file" id="image11_b_school"
                                            accept="image/png, image/jpg, image/jpeg" name="school_cert_photo"
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
                                    <label for="" class="label-form">Civil Service ID : (If you are serving
                                        inside cantt.)</label> <span></span> <br>
                                    <small></small>
                                </div>
                                <div class="col-md-8 ">
                                    @if (!empty($app->document->civil_service_id))
                                        <div>
                                            <img src="{{ url('') }}{{ $app->document->civil_service_id }}"
                                                id="prev_image11_b_civil_service_exist" alt="preview application"
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                        <input type="file" id="image11_b_civil_service_exist"
                                            accept="image/png, image/jpg, image/jpeg" name="civil_service_photo"
                                            class="form-control in-form mandatory">

                                        <div id="" class="err_msg" hidden> <i
                                                class="fas fa-exclamation-triangle"></i> <span id="">
                                            </span>
                                        </div>
                                    @else
                                        <div>
                                            <img src="" id="prev_image11_b_civil_service"
                                                alt="preview application" hidden>
                                        </div>
                                        <input type="file" id="image11_b_civil_service"
                                            accept="image/png, image/jpg, image/jpeg" name="civil_service_photo"
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
                                    <label for="" class="label-form">Job Certificate: (If you are asking for
                                        Transit Pass.)</label> <span></span> <br>
                                    <small></small>
                                </div>
                                <div class="col-md-8 ">
                                    @if (!empty($app->document->job_cert))
                                        <div>
                                            <img src="{{ url('') }}{{ $app->document->job_cert }}"
                                                id="prev_image11_b_job_exist" alt="preview application"
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                        <input type="file" id="image11_b_job_exist"
                                            accept="image/png, image/jpg, image/jpeg" name="job_cert_photo"
                                            class="form-control in-form mandatory">

                                        <div id="" class="err_msg" hidden> <i
                                                class="fas fa-exclamation-triangle"></i> <span id="">
                                            </span>
                                        </div>
                                    @else
                                        <div>
                                            <img src="" id="prev_image11_b_job" alt="preview application"
                                                hidden>
                                        </div>
                                        <input type="file" id="image11_b_job"
                                            accept="image/png, image/jpg, image/jpeg" name="job_cert_photo"
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
                                    <label for="" class="label-form">House Owner/CEO Certificate: (If you
                                        are staying inside cantt.)</label> <span></span> <br>
                                    <small></small>
                                </div>
                                <div class="col-md-8 ">
                                    @if (!empty($app->document->house_owner_cert))
                                        <div>
                                            <img src="{{ url('') }}{{ $app->document->house_owner_cert }}"
                                                id="prev_image11_b_house_owner_ceo_cert_photo_exist"
                                                alt="preview application"
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                        <input type="file" id="image11_b_house_owner_ceo_cert_photo_exist"
                                            accept="image/png, image/jpg, image/jpeg" name="house_owner_cert"
                                            class="form-control in-form mandatory">

                                        <div id="" class="err_msg" hidden> <i
                                                class="fas fa-exclamation-triangle"></i> <span id="">
                                            </span>
                                        </div>
                                    @else
                                        <div>
                                            <img src="" id="prev_image11_b_house_owner_ceo_cert_photo"
                                                alt="preview application" hidden>
                                        </div>
                                        <input type="file" id="image11_b_house_owner_ceo_cert_photo"
                                            accept="image/png, image/jpg, image/jpeg" name="house_owner_cert"
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
                                    <label for="" class="label-form">Ward Commissioner Certificate: (If you
                                        are asking for transit pass.)</label> <span></span> <br>
                                    <small></small>
                                </div>
                                <div class="col-md-8 ">
                                    @if (!empty($app->document->ward_comm_cert))
                                        <div>
                                            <img src="{{ url('') }}{{ $app->document->ward_comm_cert }}"
                                                id="prev_image11_b_wardCom_exist" alt="preview application"
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
                                        </div>
                                        <input type="file" id="image11_b_wardCom_exist"
                                            accept="image/png, image/jpg, image/jpeg" name="ward_com_cert"
                                            class="form-control in-form mandatory">

                                        <div id="" class="err_msg" hidden> <i
                                                class="fas fa-exclamation-triangle"></i> <span id="">
                                            </span>
                                        </div>
                                    @else
                                        <div>
                                            <img src="" id="prev_image11_b_wardCom"
                                                alt="preview application" hidden>
                                        </div>
                                        <input type="file" id="image11_b_wardCom"
                                            accept="image/png, image/jpg, image/jpeg" name="ward_com_cert"
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
                                                id="prev_image11_b_marriage_cert_exist" alt="preview application"
                                                style="display: inline-block; height: auto; padding: 10px 0px; max-width: 70%; width: auto; max-height: 200px;">
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
                            <!--  <div class="col-md-1 offset-md-5">
                <button type="button" class="btn btn-secondary custm-btn" id="E-p-btn3">Previous</button>
            </div> -->
                        </div>
                        <div class="col-md-2 offset-md-5">
                            <button type="submit" id="submit-btn"
                                class="btn btn-primary submit-btn custm-btn">Update & Save</button>
                        </div>
                    </div>
                </form>
            </div>
            @include('forms.crop_modal')
        </div>
    </div>
</div>
<!-- <div class="applicant-dtl-loading-progress"></div>
<div class="vehicle-dtl-loading-progress"></div>
<div class="driver-dtl-loading-progress"></div>
<div class="doc-loading-progress"></div>
-->
<div id="ajax-loader" hidden>
    <div>
        <img src="{{ url('assets/images/loading_spinner.gif') }}" />
        <div>Please wait for a while</div>
    </div>
</div>
