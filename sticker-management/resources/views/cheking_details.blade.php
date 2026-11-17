@extends('layouts.admin-master')

@section('checking_content')

    <div class="container-fluid  col-md-5 px-2 px-md-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">
                <div class="dropdown d-inline d-md-none">
                    <!-- 3 Dot Button -->
                    <button class="btn btn-success dropdown-toggle" type="button" id="mobileMenuButton" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        {{-- <i class="fas fa-ellipsis-v"></i> --}}
                       <i class="fa fa-bars" aria-hidden="true"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="mobileMenuButton">
                        <a class="dropdown-item" href="{{ route('checking') }}">
                            <i class="fas fa-home"></i> Home
                        </a>
                        <a class="dropdown-item" href="#myModal" data-toggle="modal" data-target="#myModal">
                            <i class="fas fa-unlock-alt"></i> Change Password
                        </a>
                        <a id="logout" class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-power-off"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </div>
                
                <!-- Application Summary Section -->
                <div class="card shadow-sm rounded-3 mb-2">
                    <div class="card-body p-3 p-md-4">
                        <h5 class="text-center py-2 heading-bg mb-3">Application Summary</h5>
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="row g-2 g-md-3">


                                    <div class="col-6 col-md-3">
                                        <span class="fw-bold text-muted small">Application Status</span>
                                    </div>
                                    <div class="col-6 col-md-9 app_status_wrapper">
                                        <span style="color: red;" id="app_status">
                                            @if ($app->retake == 2 && $app->app_status == 'updated')
                                                Re-take
                                            @elseif($app->app_status == 'forwarded to PS')
                                                Forwarded To MP DTE
                                            @else
                                                {{ $app->app_status != 'updated' ? $app->app_status : 'updated' }}
                                            @endif

                                        </span>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <span class="fw-bold text-muted small">Payment Status</span>
                                    </div>
                                    <div class="col-6 col-md-9 app_status_wrapper">
                                        <span>
                                            @if ($app->payment_status == '0')
                                                @if ($app->issue_type == 'free')
                                                    <button class="btn btn-success btn-sm">Free</button>
                                                @else
                                                    <button class="btn btn-danger btn-sm">Unpaid</button>
                                                @endif
                                            @else
                                                <button class="btn btn-success btn-sm">Paid</button>
                                            @endif


                                        </span>
                                    </div>

                                    @if (!empty($app->sticker_category))
                                        <div class="col-6  col-md-3">
                                            <span class="fw-bold text-muted small">Sticker Type</span>
                                        </div>
                                        <div class="col-6  col-md-9">
                                            <span
                                                class="fw-bolder  small">{{ $stickerCategory->type == 'e' ? 'E' : $app->sticker_category }}</span>
                                        </div>
                                    @endif

                                    @if (!empty($app->is_discount == 1))
                                        <div class="col-6  col-md-3">
                                            <span class="fw-bold text-muted small">Discount Price</span>
                                        </div>
                                        <div class="col-6  col-md-9">
                                            <span
                                                class="fw-bolder  small">{{ $app->discount_amount ? $app->discount_amount : '' }}</span>
                                        </div>
                                    @endif

                                    @if (!empty($app->vehicleSticker->sticker_number))
                                        <div class="col-6  col-md-3">
                                            <span class="fw-bold text-muted small">Sticker No.</span>
                                        </div>
                                        <div class="col-6  col-md-9">
                                            <span class="fw-bolder  small">{{ $app->vehicleSticker->sticker_number }}</span>
                                        </div>
                                    @endif




                                    @if (!empty($app->app_date))
                                        <div class="col-6  col-md-3">
                                            <span class="fw-bold text-muted small">Apply Date</span>
                                        </div>
                                        <div class="col-6  col-md-9">
                                            <span
                                                class="fw-bolder  small">{{ date('d-m-Y', strtotime($app->app_date)) }}</span>
                                        </div>
                                    @endif

                                    @if (!empty($app->applicationNotify->mp_frowerd_date))
                                        <div class="col-6  col-md-3">
                                            <span class="fw-bold text-muted small">Forward To MP DTE Date</span>
                                        </div>
                                        <div class="col-6  col-md-9">
                                            <span
                                                class="fw-bolder  small">{{ date('d-m-Y', strtotime($app->applicationNotify->mp_frowerd_date)) }}</span>
                                        </div>
                                    @endif
                                   
                                    @if (!empty($app->applicationNotify->sticker_verify_date))
                                        @if ($app->app_status != 'pending' && $app->app_status != 'updated')
                                            <div class="col-6  col-md-3">
                                                <span class="fw-bold text-muted small">Verify Date</span>
                                            </div>
                                            <div class="col-6  col-md-9">
                                                <span
                                                    class="fw-bolder  small">{{ date('d-m-Y', strtotime($app->applicationNotify->sticker_verify_date)) }}</span>
                                            </div>
                                        @endif
                                    @endif


                                    @if (!empty($app->applicationNotify->sticker_approve_date))
                                        @if ($app->app_status != 'pending' && $app->app_status != 'updated')
                                            <div class="col-6  col-md-3">
                                                <span class="fw-bold text-muted small">Approve Date</span>
                                            </div>
                                            <div class="col-6  col-md-9">
                                                <span
                                                    class="fw-bolder  small">{{ date('d-m-Y', strtotime($app->applicationNotify->sticker_approve_date)) }}</span>
                                            </div>
                                        @endif
                                    @endif


                                    @if ($app->payment && $app->payment->created_at)
                                        <div class="col-6  col-md-3">
                                            <span class="fw-bold text-muted small">Payment Date</span>
                                        </div>
                                        <div class="col-6  col-md-9">
                                            <span
                                                class="fw-bolder  small">{{ date('d-m-Y', strtotime($app->payment->created_at)) }}</span>
                                        </div>
                                    @endif
                                    @if (!empty($app->applicationNotify->sticker_delivery_date))
                                        @if ($app->app_status != 'pending' && $app->app_status != 'updated')
                                            <div class="col-6  col-md-3">
                                                <span class="fw-bold text-muted small">Delivery Date</span>
                                            </div>
                                            <div class="col-6  col-md-9">
                                                <span
                                                    class="fw-bolder  small">{{ date('d-m-Y', strtotime($app->applicationNotify->sticker_delivery_date)) }}</span>
                                            </div>
                                        @endif
                                    @endif

                                    @if (!empty($app->vehicleSticker->issue_date))
                                        <div class="col-6  col-md-3">
                                            <span class="fw-bold text-muted small">Sticker Issue Date</span>
                                        </div>
                                        <div class="col-6  col-md-9">
                                            <span
                                                class="fw-bolder  small">{{ date('d-m-Y', strtotime($app->vehicleSticker->issue_date)) }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Applicant Details Section -->
                <div class="card shadow-sm rounded-3 mb-2">
                    <div class="card-body p-3 p-md-4">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-center py-2 heading-bg mb-3">Applicant Details</h5>
                            </div>
                            <div class="col-12 py-2 d-flex justify-content-center">
                                @if (isset($app->applicant->applicantDetail))
                                    <img id="applicant_photo"
                                        src="{{ url('/') }}{{ $app->applicant->applicantDetail->applicant_photo }}"
                                        class="img-fluid preview-img-review rounded"
                                        style="max-height: 150px; max-width: 150px; object-fit: cover;"
                                        alt="{{ $app->applicant->name }}">
                                @endif
                            </div>

                            <div class="col-12">
                                <div class="row g-2 g-md-3">
                                    <div class="col-6  col-md-3">
                                        <span class="fw-bold text-muted small">Applicant Full Name :</span>
                                    </div>
                                    <div class="col-6  col-md-9">
                                        <span class="fw-bolder  small">{{ $app->applicant->name }}</span>
                                    </div>

                                    @if (!empty($app->applicant->applicantDetail->applicant_BA_no))
                                        <div class="col-6  col-md-3">
                                            <span class="fw-bold text-muted small">BA No :</span>
                                        </div>
                                        <div class="col-6  col-md-9">
                                            <span
                                                class="fw-bolder  small">{{ $app->applicant->applicantDetail->applicant_BA_no }}</span>
                                        </div>
                                    @endif

                                    @if (!empty($app->applicant->applicantDetail->rank->name))
                                        <div class="col-6  col-md-3">
                                            <span class="fw-bold text-muted small">Rank :</span>
                                        </div>
                                        <div class="col-6  col-md-9">
                                            <span
                                                class="fw-bolder  small">{{ $app->applicant->applicantDetail->rank->name }}</span>
                                        </div>
                                    @endif

                                    {{-- @if (!empty($app->applicant->applicantDetail->spouseOrParent_BA_no))
                                        <div class="col-6  col-md-3">
                                            <span class="fw-bold text-muted small">Spouse/Parent BA No :</span>
                                        </div>
                                        <div class="col-6  col-md-9">
                                            <span
                                                class="fw-bolder  small">{{ $app->applicant->applicantDetail->spouseOrParent_BA_no }}</span>
                                        </div>
                                    @endif --}}

                                    {{-- @if (!empty($app->applicant->applicantDetail->spouseOrParent_Rank))
                                        <div class="col-6  col-md-3">
                                            <span class="fw-bold text-muted small">Spouse/Parent Rank :</span>
                                        </div>
                                        <div class="col-6  col-md-9">
                                            <span
                                                class="fw-bolder  small">{{ $app->applicant->applicantDetail->spouseOrParent_Rank }}</span>
                                        </div>
                                    @endif --}}
                                    {{-- 
                                    @if (!empty($app->applicant->applicantDetail->is_spouseOrChild))
                                        <div class="col-6  col-md-3">
                                            <span class="fw-bold text-muted small">Is Applicant Def Person's Spouse/Child?
                                                :</span>
                                        </div>
                                        <div class="col-6  col-md-9">
                                            <span>Yes</span>
                                        </div>
                                    @endif --}}

                                    {{-- @if (!empty($app->applicant->applicantDetail->spouseOrParent_Name))
                                        <div class="col-6  col-md-3">
                                            <span class="fw-bold text-muted small">Spouse/Parent Name :</span>
                                        </div>
                                        <div class="col-6  col-md-9">
                                            <span
                                                class="fw-bolder  small">{{ $app->applicant->applicantDetail->spouseOrParent_Name }}</span>
                                        </div>
                                    @endif --}}

                                    {{-- @if (!empty($app->applicant->applicantDetail->spouseOrParent_Unit))
                                        <div class="col-6  col-md-3">
                                            <span class="fw-bold text-muted small">Spouse/Parent Unit :</span>
                                        </div>
                                        <div class="col-6  col-md-9">
                                            <span
                                                class="fw-bolder  small">{{ $app->applicant->applicantDetail->spouseOrParent_Unit }}</span>
                                        </div>
                                    @endif --}}
                                    {{-- 
                                    @if (!empty($app->applicant->applicantDetail->profession))
                                        <div class="col-6  col-md-3">
                                            <span class="fw-bold text-muted small">Profession :</span>
                                        </div>
                                        <div class="col-6  col-md-9">
                                            <span
                                                class="fw-bolder  small">{{ $app->applicant->applicantDetail->profession }}</span>
                                        </div>
                                    @endif --}}

                                    @if (!empty($app->applicant->applicantDetail->company_name))
                                        <div class="col-6  col-md-3">
                                            <span class="fw-bold text-muted small">Company Name :</span>
                                        </div>
                                        <div class="col-6  col-md-9">
                                            <span
                                                class="fw-bolder  small">{{ $app->applicant->applicantDetail->company_name }}</span>
                                        </div>
                                    @endif

                                    @if (!empty($app->applicant->applicantDetail->designation))
                                        <div class="col-6  col-md-3">
                                            <span class="fw-bold text-muted small">Designation :</span>
                                        </div>
                                        <div class="col-6  col-md-9">
                                            <span
                                                class="fw-bolder  small">{{ $app->applicant->applicantDetail->designation }}</span>
                                        </div>
                                    @endif

                                    {{-- <div class="col-6  col-md-3">
                                        <span class="fw-bold text-muted small">Residence Type :</span>
                                    </div>
                                    <div class="col-6  col-md-9">
                                        <span
                                            class="fw-bolder  small">{{ !empty($app->applicant->applicantDetail->residence_type) ? $app->applicant->applicantDetail->residence_type : '-' }}</span>
                                    </div> --}}

                                    <div class="col-6  col-md-3">
                                        <span class="fw-bold text-muted small">Mobile No. :</span>
                                    </div>
                                    <div class="col-6  col-md-9">
                                        <span
                                            class="fw-bolder  small">{{ !empty($app->applicant->phone) ? $app->applicant->phone : '-' }}</span>
                                    </div>

                                    {{-- <div class="col-6  col-md-3">
                                        <span class="fw-bold text-muted small">Email :</span>
                                    </div>
                                    <div class="col-6  col-md-9">
                                        <span
                                            class="fw-bolder  small">{{ !empty($app->applicant->email) ? $app->applicant->email : '-' }}</span>
                                    </div> --}}

                                    {{-- @if (!empty($app->applicant->applicantDetail->no_sticker_to_self_family))
                                        <div class="col-6  col-md-3">
                                            <span class="fw-bold text-muted small">Number Of Sticker allocated to
                                                applicant/applicant's family 2018 :</span>
                                        </div>
                                        <div class="col-6  col-md-9">
                                            <span
                                                class="fw-bolder  small">{{ $app->applicant->applicantDetail->no_sticker_to_self_family }}</span>
                                        </div>
                                    @endif --}}

                                    {{-- @if (!empty($app->applicant->applicantDetail->allocated_current_sticker_type))
                                        <div class="col-6  col-md-3">
                                            <span class="fw-bold text-muted small">Currently allocated Sticker Types
                                                :</span>
                                        </div>
                                        <div class="col-6  col-md-9">
                                            <span
                                                class="fw-bolder  small">{{ $app->applicant->applicantDetail->allocated_current_sticker_type }}</span>
                                        </div>
                                    @endif --}}

                                    {{-- @if (!empty($app->applicant->applicantDetail->allocated_current_sticker_no))
                                        <div class="col-6  col-md-3">
                                            <span class="fw-bold text-muted small">Currently allocated Sticker No</span>
                                        </div>
                                        <div class="col-6  col-md-9">
                                            <span
                                                class="fw-bolder  small">{{ $app->applicant->applicantDetail->allocated_current_sticker_no }}</span>
                                        </div>
                                    @endif --}}

                                    {{-- @if (!empty($app->remark))
                                        <div class="col-6  col-md-3">
                                            <span class="fw-bold text-muted small">Applicant Remarks </span>
                                        </div>
                                        <div class="col-6  col-md-9">
                                            <span class="fw-bolder  small">{{ $app->remark }}</span>
                                        </div>
                                    @endif --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vehicle Details Section -->
                <div class="card shadow-sm rounded-3 mb-2">
                    <div class="card-body p-3 p-md-4">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-center py-2 heading-bg mb-3">Vehicle Details</h5>
                            </div>
                            <div class="col-12">
                                <div class="row g-2 g-md-3">
                                    <div class="col-6  col-md-3">
                                        <span class="fw-bold text-muted small">Vehicle Type</span>
                                    </div>
                                    <div class="col-6  col-md-9">
                                        <span
                                            class="fw-bolder  small">{{ !empty($app->vehicleinfo->vehicleType->name) ? $app->vehicleinfo->vehicleType->name : '' }}</span>
                                    </div>

                                    <div class="col-6  col-md-3">
                                        <span class="fw-bold text-muted small">Vehicle Registration No.</span>
                                    </div>
                                    <div class="col-6  col-md-9">
                                        <span
                                            class="fw-bolder  small">{{ !empty($app->vehicleinfo->reg_number) ? $app->vehicleinfo->reg_number : '' }}</span>
                                    </div>

                                    {{-- <div class="col-6  col-md-3">
                                        <span class="fw-bold text-muted small">Owner is company?</span>
                                    </div>
                                    <div class="col-6  col-md-9">
                                        <span
                                            class="fw-bolder  small">{{ !empty($app->vehicleowner->company_name) ? 'Yes' : 'No' }}</span>
                                    </div> --}}

                                    @if (!empty($app->vehicleowner->company_name))
                                        <div class="col-6  col-md-3">
                                            <span class="fw-bold text-muted small">Company Name</span>
                                        </div>
                                        <div class="col-6  col-md-9">
                                            <span
                                                class="fw-bolder  small">{{ !empty($app->vehicleowner->company_name) ? $app->vehicleowner->company_name : '' }}</span>
                                        </div>
                                        <div class="col-6  col-md-3">
                                            <span class="fw-bold text-muted small">Company Address</span>
                                        </div>
                                        <div class="col-6  col-md-9">
                                            <?php $com_address = json_decode($app->vehicleowner->company_address, true); ?>
                                            <div class="d-flex flex-column">
                                                <span>House: {{ $com_address['house'] }}</span>
                                                <span>Road: {{ $com_address['road'] }}</span>
                                                <span>Block: {{ $com_address['block'] }}</span>
                                                <span>Area: {{ $com_address['area'] }}</span>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-6  col-md-3">
                                        <span class="fw-bold text-muted small">Owner Name</span>
                                    </div>
                                    <div class="col-6  col-md-9">
                                        <span
                                            class="fw-bolder  small">{{ isset($app->vehicleowner->owner_name) ? $app->vehicleowner->owner_name : '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Follow Up Section -->
        <div class="row justify-content-center mb-2">
            <div class="col-12 col-lg-10 col-xl-8">
                <div class="d-none d-md-block">
                    <div class="card shadow-sm rounded-3">
                        <div class="card-body p-3 p-md-4">
                            <h5 class="text-center py-2 heading-bg mb-3">Follow Up</h5>
                            <div class="">
                                <div class="table">
                                    <table class="table table-bordered table-striped ">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Comments</th>
                                                <th>Notified</th>
                                                <th>By</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (isset($app->followups) && count($app->followups) > 0)
                                                @foreach ($app->followups->sortByDesc('created_at') as $follow_up)
                                                    <tr class="follow-up-row">
                                                        <td class="fw-bolder  small">{{ $loop->iteration }}</td>
                                                        <td class="fw-bolder  small">
                                                            {{ \Carbon\Carbon::parse($follow_up->created_at)->toDayDateTimeString() }}
                                                        </td>
                                                        <td>
                                                            {{ $follow_up->app_status == 'forwarded to PS' ? 'Application forwarded To MP DTE' : $follow_up->status }}
                                                        </td>
                                                        <td class="fw-bolder  small">
                                                            {{ !empty($follow_up->comment) ? $follow_up->comment : '' }}
                                                        </td>
                                                        <td>
                                                            <div class="row justify-content-center">
                                                                <div class="col-md-3">
                                                                    @if ($follow_up->sms_sent == 'success')
                                                                        <span style="color: green;" title="sms sent"><i
                                                                                class="fas fa-check"></i></span>
                                                                    @elseif($follow_up->sms_sent == 'fail')
                                                                        <span class="sms-status-replace" hidden></span>
                                                                        <span class="sms-failed" style="color: red;"
                                                                            title="sms not sent"><i
                                                                                class="fas fa-times"></i></span>
                                                                        <span class="resendSms sms-failed"
                                                                            data-id="{{ $follow_up->id }}"
                                                                            style="color:#07adca; cursor: pointer;"
                                                                            title="retry to send"><i
                                                                                class="fas fa-redo-alt"></i></span>
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-3">
                                                                    @if ($follow_up->mail_sent == 'success')
                                                                        <span style="color: green;" title="mail sent"><i
                                                                                class="fas fa-check"></i></span>
                                                                    @elseif($follow_up->mail_sent == 'fail')
                                                                        <span style="color: red;" title="mail not sent"><i
                                                                                class="fas fa-times"></i></span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="fw-bolder  small">{{ $follow_up->updated_by }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Mobile Cards -->
                            <div class="d-md-none">
                                @if (isset($app->followups) && count($app->followups) > 0)
                                    @foreach ($app->followups->sortByDesc('created_at') as $follow_up)
                                        <div class="card mb-3 border">
                                            <div class="card-body p-3">
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <small class="text-muted fw-bold">#{{ $loop->iteration }}</small>
                                                    </div>
                                                    <div class="col-6 text-end">
                                                        <small class="text-muted"
                                                            class="fw-bolder  small">{{ \Carbon\Carbon::parse($follow_up->created_at)->format('d/m/Y H:i') }}</small>
                                                    </div>
                                                    <div class="col-12">
                                                        <strong>Status:</strong>
                                                        {{ $follow_up->app_status == 'forwarded to PS' ? 'Application forwarded To MP DTE' : $follow_up->status }}
                                                    </div>
                                                    @if (!empty($follow_up->comment))
                                                        <div class="col-12">
                                                            <strong>Comments:</strong> {{ $follow_up->comment }}
                                                        </div>
                                                    @endif
                                                    <div class="col-6">
                                                        <strong>Notified:</strong>
                                                        <div class="d-flex gap-2 mt-1">
                                                            @if ($follow_up->sms_sent == 'success')
                                                                <span style="color: green;" title="sms sent"><i
                                                                        class="fas fa-check"></i></span>
                                                            @elseif($follow_up->sms_sent == 'fail')
                                                                <span class="sms-failed" style="color: red;"
                                                                    title="sms not sent"><i
                                                                        class="fas fa-times"></i></span>
                                                                <span class="resendSms sms-failed"
                                                                    data-id="{{ $follow_up->id }}"
                                                                    style="color:#07adca; cursor: pointer;"
                                                                    title="retry to send"><i
                                                                        class="fas fa-redo-alt"></i></span>
                                                            @endif
                                                            @if ($follow_up->mail_sent == 'success')
                                                                <span style="color: green;" title="mail sent"><i
                                                                        class="fas fa-check"></i></span>
                                                            @elseif($follow_up->mail_sent == 'fail')
                                                                <span style="color: red;" title="mail not sent"><i
                                                                        class="fas fa-times"></i></span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-6 text-end">
                                                        <strong>By:</strong> {{ $follow_up->updated_by }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-info-circle fa-2x mb-2"></i>
                                        <p>No follow-up records found</p>
                                    </div>
                                @endif
                            </div>
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
        <script type="text/javascript" src="{{ asset('/assets/admins/js/review-blade.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/assets/admins/js/application-verify.js') }}"></script>

        <style>
            /* Responsive Design Styles */
            .heading-bg {
                background: #48814C;
                /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
                color: white;
                border-radius: 8px;
                font-weight: 600;
            }

            .card {
                border: none;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
            }

            .card:hover {
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
                transform: translateY(-2px);
            }

            .preview-img-review {
                border: 3px solid #e9ecef;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            .table-responsive {
                border-radius: 8px;
                overflow: hidden;
            }

            .follow-up-table th {
                background: #48814C;

                color: white;
                border: none;
                font-weight: 600;
                text-align: center;
            }

            .follow-up-table td {
                vertical-align: middle;
                border-color: #e9ecef;
            }

            .follow-up-table tbody tr:hover {
                background-color: #f8f9fa;
            }

            /* Mobile Optimizations */
            @media (max-width: 767.98px) {
                .container-fluid {
                    padding-left: 10px;
                    padding-right: 10px;
                }

                .card-body {
                    padding: 1rem;
                }

                .btn-sm {
                    font-size: 12.8px;
                    /* font-size: 0.75rem; */
                    padding: 0.25rem 0.5rem;
                }

                .fw-bold.text-muted.small {
                    font-size: 12.8px;
                    /* font-size: 0.8rem; */
                    color: #6c757d !important;
                }

                /* .row.g-2.g-md-3>div {
                                                            margin-bottom: 0.5rem;
                                                        } */
            }

            /* Tablet Optimizations */
            @media (min-width: 768px) and (max-width: 991.98px) {
                .card-body {
                    padding: 1.5rem;
                }
            }

            /* Large Screen Optimizations */
            @media (min-width: 1200px) {
                .container-fluid {
                    max-width: 1400px;
                    margin: 0 auto;
                }
            }

            /* Image Magnifier Styles */
            .img-magnifier-container {
                position: relative;
            }

            .img-magnifier-glass {
                position: absolute;
                border: 3px solid #000;
                border-radius: 50%;
                cursor: none;
                width: 150px;
                height: 150px;
            }

            /* Button Group Responsive */
            .app_status_wrapper .d-flex {
                flex-wrap: wrap;
            }

            .app_status_wrapper .btn {
                margin-bottom: 0.25rem;
            }

            /* Status Indicators */
            .status-indicator {
                display: inline-block;
                width: 8px;
                height: 8px;
                border-radius: 50%;
                margin-right: 5px;
            }

            .status-success {
                background-color: #28a745;
            }

            .status-warning {
                background-color: #ffc107;
            }

            .status-danger {
                background-color: #dc3545;
            }

            .status-info {
                background-color: #17a2b8;
            }

            /* Loading States */
            .loading {
                opacity: 0.6;
                pointer-events: none;
            }

            /* Animation for cards */
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .card {
                animation: fadeInUp 0.6s ease-out;
            }

            /* Print Styles */
            @media print {

                .btn,
                .d-print-none {
                    display: none !important;
                }

                .card {
                    box-shadow: none;
                    border: 1px solid #ddd;
                }

                .container-fluid {
                    max-width: none;
                    padding: 0;
                }
            }
        </style>
    @endsection
