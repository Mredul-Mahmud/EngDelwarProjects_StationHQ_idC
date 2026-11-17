@extends('layouts.admin-master')
@section('admin-sidebar')
    @include('layouts.admin-sidebar')
@endsection
{{-- @php
    dd(empty($app->vehicleinfo->fitness_validity));
@endphp --}}
@section('content')

    <div class="content-area" id="review-content">

        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        <div id="action-bar">
            <ul>
                <li>
                    <button id='' data-toggle="modal" {{ $app->app_status == 'refund' ? 'disabled' : '' }} data-target="#verifyModal" class="btn btn-success"><i
                            class="fas fa-check"></i> Verify</button>
                </li>
                @if (auth()->user()->role == 'super-admin')
                    <li>
                        <button id='' data-toggle="modal" {{ $app->app_status == 'refund' ? 'disabled' : '' }} data-target="#myApproveModal" class="btn btn-success"><i
                                class="fas fa-check"></i> Approve</button>
                    </li>
                @endif

              
                    <li>
                        <button id='' data-toggle="modal" {{ $app->app_status == 'refund' ? 'disabled' : '' }} data-target="#myDeliveryeModal" class="btn btn-success"><i
                                class="fas fa-check"></i> Delivery</button>
                    </li>
          
                <li>
                    <button data-number="{{ $app->app_number }}" {{ $app->app_status == 'refund' ? 'disabled' : '' }} id='issue_sticker' data-toggle="modal"
                        data-target=" #myIssueModal" class="btn btn-success"><i class="fas fa-check-circle"></i>
                        Issue</button>
                </li>
                <li>
                    
                    @if ($app->app_status == 'refund')
                        <button data-number="{{ $app->app_number }}" style="color: #fff;" class="btn btn-info"><i class="far fa-edit"></i> Edit</button>
                    @else
                        <a data-number="{{ $app->app_number }}" {{ $app->app_status == 'refund' ? 'disabled' : '' }} href="{{ url('/application/edit') }}/{{ $app->app_number }}" id='edit_App' style="color: #fff;" class="btn btn-info"><i class="far fa-edit"></i> Edit</a>

                    @endif


                </li>
                <li>
                    <button data-number="{{ $app->app_number }}" {{ $app->app_status == 'refund' ? 'disabled' : '' }} id='notify_user' data-toggle="modal"
                        data-target=" #notify_user_modal" class="btn btn-info"><i class="far fa-bell"></i> Notify</button>
                </li>
                <li>
                    <button data-number="{{ $app->app_number }}" {{ $app->app_status == 'refund' ? 'disabled' : '' }} id='forward_ps' class="btn btn-info"><i
                            class="fas fa-forward"></i> Forward To MP DTE</button>

                </li>
                <li>
                    <button data-id="{{ $app->id }}" id='undo_app' class="btn btn-info"><i class="fas fa-undo"></i>
                        Undo</button>

                </li>
                <li>
                    <button data-number="{{ $app->app_number }}" {{ $app->app_status == 'refund' ? 'disabled' : '' }} id='' data-toggle="modal"
                        data-target="#myRejectModal" class="btn btn-warning">Update</button>
                </li>
                <li>
                    <button data-number="{{ $app->id }}" {{ $app->app_status == 'refund' ? 'disabled' : '' }} data-toggle="modal" data-target="#holdApplicationModal"
                        class="btn btn-info" title="Hold the Application"><i class="fas fa-pause"></i> Hold</button>
                </li>
                <li>
                    <button data-number="{{ $app->app_number }}" {{ $app->app_status == 'refund' ? 'disabled' : '' }} id='delete_App' class="btn btn-danger"><i
                            class="fas fa-trash-alt"></i> Delete</button>
                </li>

                <li>
                    <button data-number="{{ $app->app_number }}" {{ $app->app_status == 'refund' ? 'disabled' : '' }} id='print_App'
                        data-logo="{{ asset('assets/images/LogoS1.png') }}" class="btn btn-primary"><i
                            class="fas fa-print"></i> Print</button>
                </li>
               @if (strtolower($app->app_status) == 'delivered' &&  $app->sticker_number)
                    <li>
                      
                    <button 
                        type="button"
                        class="btn btn-primary"
                        onclick="window.open('{{ route('sticker.print', $app->app_number) }}', '_blank')">
                        <i class="fas fa-print"></i> Sticker Print
                    </button>
                   
                    @elseif(auth()->user()->role == 'super-admin' && $app->payment_status == 1 &&  $app->sticker_number )

                    <button 
                        type="button"
                        class="btn btn-primary"
                        onclick="window.open('{{ route('sticker.print', $app->app_number) }}', '_blank')">
                        <i class="fas fa-print"></i> Sticker Print
                    </button>
                 @endif
                  </li>
               
            </ul>
        </div>
        <div class="container-fluid" id="app-review">

            <input id="userRole" type="hidden" value="{{ auth()->user()->role ?? '' }}" />
            <div class="row app-section">
                @if ($stickerPrice == 0 && $app->sticker_category == 'E')
                    <div class="col-md-12">
                        <div class="alert alert-danger" role="alert">
                            <strong>Alert</strong> Invalid Vehicle Type / Sticker Type.
                        </div>
                    </div>
                @endif
                <div class="col-md-12">
                    <h5 class="text-center py-2 heading-bg">Application Summary</h5>
                </div>
                @if (!empty($app->app_status))
                    <div class="col-md-3">
                        <span>Status</span>
                    </div>
                    <div class="col-md-9 app_status_wrapper">
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
                @endif

                <div class="col-md-3">
                    <span>Payment Status</span>
                </div>
                {{-- new changes replace old --}}
                <div class="col-md-9 app_status_wrapper">
                    <span>
                       @if ($app->issue_type === 'free' && $app->payment_status == 0)
                                    <button class="btn btn-success btn-sm">Free</button>
                                @elseif ($app->payment_status == 1)
                                    <button class="btn btn-success btn-sm">Paid</button>
                                @else
                                    <button class="btn btn-danger btn-sm">Unpaid</button>
                                @endif

                        @if (auth()->user()->role == 'super-admin' && in_array(auth()->user()->email, ['tiladmin@til.com', 'engr.robiul.shq@gmail.com']))
                            <a href="{{ route('paymentbyadmin') }}"
                                onclick="event.preventDefault();
                                            document.getElementById('admin-payment-form').submit();"
                                class="btn btn-outline-secondary">Payment by Admin</a>

                            <form id="admin-payment-form" action="{{ route('paymentbyadmin') }}" method="POST"
                                style="display: none;">
                                {{ csrf_field() }}
                                <input type="hidden" id="applicant_name" value="{{ $app->id }}"
                                    name="application_id">
                            </form>
                        @endif

                        @if (auth()->user()->role == 'super-admin' && auth()->user()->email == 'tiladmin@til.com' )
                            <a href="{{ route('resent_schedule_sms') }}"
                                onclick="event.preventDefault();
                                            document.getElementById('admin-sms-form').submit();"
                                class="btn btn-outline-secondary">Schedule sms send by Admin</a>

                            <form id="admin-sms-form" action="{{ route('resent_schedule_sms') }}" method="POST"
                                style="display: none;">
                                {{ csrf_field() }}
                                <input type="hidden" id="applicant_name" value="{{ $app->id }}"
                                    name="application_id">
                            </form>
                        @endif

                        @if (auth()->user()->role == 'super-admin' && auth()->user()->email == 'tiladmin@til.com')
                            <a href="{{ route('admin.refund.create', $app->id ) }}" class="btn btn-outline-secondary">Refund by Admin</a>
                        @endif
						
						 @if (auth()->user()->role == 'super-admin' && auth()->user()->email == 'tiladmin@til.com')
                            <a href="{{ route('admin.ps_approved') }}"
                                onclick="event.preventDefault();
                                            document.getElementById('admin-mp-frowerd-form').submit();"
                                class="btn btn-outline-secondary">Frowerd to MP</a>

                            <form id="admin-mp-frowerd-form" action="{{ route('admin.ps_approved') }}" method="POST"
                                style="display: none;">
                                {{ csrf_field() }}
                                <input type="hidden" id="applicant_mp_frowerd" value="{{ $app->id }}"
                                    name="application_id">
                            </form>
                        @endif


                         @if (auth()->user()->role == 'super-admin' && auth()->user()->email == 'tiladmin@til.com')
                            <a href="{{ route('admin.ps_approved_remove') }}"
                                onclick="event.preventDefault();
                                            document.getElementById('admin-mp-frowerd-form-remove').submit();"
                                class="btn btn-outline-secondary">Remove from MP</a>

                            <form id="admin-mp-frowerd-form-remove" action="{{ route('admin.ps_approved_remove') }}" method="POST"
                                style="display: none;">
                                {{ csrf_field() }}
                                <input type="hidden" id="applicant_mp_frowerd" value="{{ $app->id }}"
                                    name="application_id">
                            </form>
                        @endif

                    </span>
                </div>
                {{-- new changes replace old --}}
                @if (!empty($app->sticker_category))
                    <div class="col-md-3">
                        <span>Sticker Type</span>
                    </div>
                    <div class="col-md-9">
                        <!-- <span>{{ $stickerCategory->type == 'e' ? 'E' : $app->sticker_category }}</span> -->
                        @if ($stickerCategory->value === 'e')
                            E
                        @elseif ($stickerCategory->value === 'Ex')
                            Ex Army Chief
                        @else
                            {{ $app->sticker_category }}
                        @endif
                    </div>
                @endif


                @php
                    use App\Models\Payment;

                    $adminComments = ['By Admin', 'Robiul Islam', 'TIL-Admin']; // allowed admin comments

                    $payment = Payment::where('application_id', $app->id)
                        ->whereIn('comment', $adminComments)
                        ->orderBy('created_at', 'desc')
                        ->first();
                       
                   
                @endphp
                

                @if (!empty($payment) && in_array($payment->comment, $adminComments) && auth()->user()->role == 'super-admin')
                    <div class="col-md-3">
                        <span>Payment Admin Name</span>
                    </div>
                    <div class="col-md-9">
                        <span>{{   $payment->comment }}</span>
                        
                    </div>
                @endif
                @if (!empty($app->rf_id_no))
                    <div class="col-md-3">
                        <span>RFID No.</span>
                    </div>
                    <div class="col-md-9">
                        <span>{{ $app->rf_id_no }}</span>
                    </div>
                @endif

                @if (!empty($app->is_discount == 1))
                    <div class="col-md-3">
                        <span>Discount Price</span>
                    </div>
                    <div class="col-md-9">
                        <span>{{ $app->discount_amount  ? $app->discount_amount  : "" }} TK</span>
                    </div>
                @endif



                @if (!empty($app->sticker_number ))
                    <div class="col-md-3">
                        <span>Sticker No.</span>
                    </div>
                    <div class="col-md-9">                        
                         <span>{{ $app->stickerCategory == 'e' ? 'E' : $app->sticker_category }}-{{ $app->sticker_number ?? 'n/a' }}</span>
                    </div>
                @endif
              

                 @if (!empty($app->app_date))
                   
                        <div class="col-md-3">
                            <span>Apply Date</span>
                        </div>
                        <div class="col-md-9">
                            <span>{{ date('d-m-Y', strtotime($app->app_date)) }}</span>
                        </div>
                    
                @endif
                @if (!empty($app->applicationNotify->mp_frowerd_date))
                   
                        <div class="col-md-3">
                            <span> Forward To MP DTE Date</span>
                        </div>
                        <div class="col-md-9">
                            <span>{{ date('d-m-Y', strtotime($app->applicationNotify->mp_frowerd_date)) }}</span>
                        </div>
                   
                @endif
              
                @if (!empty($app->applicationNotify->sticker_verify_date))
                    @if ($app->app_status != 'pending' && $app->app_status != 'updated')
                        <div class="col-md-3">
                            <span>Verify Date</span>
                        </div>
                        <div class="col-md-9">
                            <span>{{ date('d-m-Y', strtotime($app->applicationNotify->sticker_verify_date)) }}</span>
                        </div>
                    @endif
                @endif
                @if (!empty($app->applicationNotify->sticker_approve_date))
                    @if ($app->app_status != 'pending' && $app->app_status != 'updated')
                        <div class="col-md-3">
                            <span>Approve Date</span>
                        </div>
                        <div class="col-md-9">
                            <span>{{ date('d-m-Y', strtotime($app->applicationNotify->sticker_approve_date)) }}</span>
                        </div>
                    @endif
                @endif
                
                @if ($app->payment && $app->payment->created_at)
                    <div class="col-md-3">
                        <span>Payment Date</span>
                    </div>
                    <div class="col-md-9">
                        <span>{{ date('d-m-Y', strtotime($app->payment->created_at)) }}</span>
                    </div>
                @endif
                @if (!empty($app->applicationNotify->sticker_delivery_date))
                    @if ($app->app_status != 'pending' && $app->app_status != 'updated')
                        <div class="col-md-3">
                            <span>Delivery Date</span>
                        </div>
                        <div class="col-md-9">
                            <span>{{ date('d-m-Y', strtotime($app->applicationNotify->sticker_delivery_date)) }}</span>
                        </div>
                    @endif
                @endif

               

                @if (!empty($app->vehicleSticker->issue_date))
                    <div class="col-md-3">
                        <span>Sticker Issue Date</span>
                    </div>
                    <div class="col-md-9">
                        <span>{{ date('d-m-Y', strtotime($app->vehicleSticker->issue_date)) }} </span>
                    </div>
                @endif

                   @if (!empty($app->print_status))
                    <div class="col-md-3">
                        <span>Print Status</span>
                    </div>
                    <div class="col-md-9">
                        <span class="btn btn-warning">{{ $app->print_status ?? '' }}</span>
                    </div>
                @endif

                @if (!empty($app->application_category))
                     <div class="col-md-3">
                       <span>Application Category</span>
                     </div>
                       <div class="col-md-9">
                        <span> {{ isset($app->application_category) ? getApplicationCategoryLabel($app->application_category) : '-' }}</span>
                    </div>
                @endif

                @if (!empty($app->applicant->user_name))
                    <div class="col-md-3">
                        <span>User Name</span>
                    </div>
                    <div class="col-md-9">
                        <span>{{ $app->applicant->user_name }}</span>
                    </div>
                @endif
            </div>

            <div class="row app-section">
                <div class="col-md-12">
                    <h5 class="text-center py-2 heading-bg">Applicant Details</h5>
                </div>
                <div class="col-md-3">
                    <span>Applicant Full Name</span>
                </div>
                <div class="col-md-5">
                    <span>{{ $app->applicant->name }}</span>
                </div>
                <div class="col-md-4 py-1" style="position: relative;border:none;">
                    @if (isset($app->applicant->applicantDetail))
                        <img id="applicant_photo"
                            src="{{ url('/') }}{{ $app->applicant->applicantDetail->applicant_photo }}"
                            class="img-fluid preview-img-review"
                            style="position: absolute; top: 0px; right: 15px; z-index: 2;cursor: pointer;" height="150"
                            width="150" alt="{{ $app->applicant->name }}" data-toggle="modal"
                            data-target="#applicantPhotoModal">
                    @endif

                </div>
                <div class="col-md-3">
                    <span>Father/Husband's Name</span>
                </div>
                <div class="col-md-9">
                    <span>
                        @if (isset($app->applicant->applicantDetail))
                            {{ isset($app->applicant->applicantDetail->father_name) ? $app->applicant->applicantDetail->father_name : $app->applicant->applicantDetail->husband_name }}
                        @else
                            -
                        @endif
                    </span>
                </div>

                @if (!empty($app->applicant->applicantDetail->applicant_BA_no))
                    <div class="col-md-3">
                        <span>BA No</span>
                    </div>
                    <div class="col-md-9">
                        <span>
                            {{ $app->applicant->applicantDetail->applicant_BA_no }}
                        </span>
                    </div>
                @endif
                @if (!empty($app->applicant->applicantDetail->rank->name))
                    <div class="col-md-3">
                        <span>Rank</span>
                    </div>
                    <div class="col-md-9">
                        <span>
                            {{ $app->applicant->applicantDetail->rank->name }}
                        </span>
                    </div>
                @endif
                  @if ($app->type == 'def')
                    <div class="col-md-3">
                        <span>Service Status</span>
                    </div>
                    <div class="col-md-9">
                        <span>
                            {{ $app->applicant->applicantDetail->is_applicant_retired == 0 ? "On-service"  : 'Retired'}}
                        </span>
                    </div>
                @endif
                @if (!empty($app->applicant->applicantDetail->spouseOrParent_BA_no))
                    <div class="col-md-3">
                        <span>Spouse/Parent BA No</span>
                    </div>
                    <div class="col-md-9">
                        <span>
                            {{ $app->applicant->applicantDetail->spouseOrParent_BA_no }}
                        </span>
                    </div>
                @endif

                @if (!empty($app->applicant->applicantDetail->spouseOrParent_Rank))
                    <div class="col-md-3">
                        <span>Spouse/Parent Rank</span>
                    </div>
                    <div class="col-md-9">
                        <span>
                            {{ $app->applicant->applicantDetail->spouseOrParent_Rank }}
                        </span>
                    </div>
                @endif

                @if (!empty($app->applicant->applicantDetail->is_spouseOrChild))
                    <div class="col-md-3">
                        <span>Is Applicant Def Person's Spouse/Child?</span>
                    </div>
                    <div class="col-md-9">
                        <span>
                            {{ !empty($app->applicant->applicantDetail->is_spouseOrChild) ? 'Yes' : '' }}
                        </span>
                    </div>
                @endif

                @if (!empty($app->applicant->applicantDetail->spouseOrParent_Name))
                    <div class="col-md-3">
                        <span>Spouse/Parent Name</span>
                    </div>
                    <div class="col-md-9">
                        <span>
                            {{ $app->applicant->applicantDetail->spouseOrParent_Name }}
                        </span>
                    </div>
                @endif
                @if (!empty($app->applicant->applicantDetail->spouseOrParent_Unit))
                    <div class="col-md-3">
                        <span>Spouse/Parent Unit</span>
                    </div>
                    <div class="col-md-9">
                        <span>
                            {{ $app->applicant->applicantDetail->spouseOrParent_Unit }}
                        </span>
                    </div>
                @endif
                @if (!empty($app->applicant->applicantDetail->profession))
                    <div class="col-md-3">
                        <span>Profession</span>
                    </div>
                    <div class="col-md-9">
                        <span>

                            {{ $app->applicant->applicantDetail->profession }}


                        </span>
                    </div>
                @endif
                @if (!empty($app->applicant->applicantDetail->company_name))
                    <div class="col-md-3">
                        <span>Company Name</span>
                    </div>
                    <div class="col-md-9">
                        <span>
                            {{ $app->applicant->applicantDetail->company_name }}
                        </span>
                    </div>
                @endif
                @if (!empty($app->applicant->applicantDetail->designation))
                    <div class="col-md-3">
                        <span>Designation</span>
                    </div>

                    <div class="col-md-9">
                        <span>


                            {{ $app->applicant->applicantDetail->designation }}


                        </span>
                    </div>
                @endif

                <div class="col-md-3">
                    <p>Address</p>
                </div>
                
                <div class="col-md-9">
                     <span>
                        {{ isset($app->applicant->applicantDetail->address) ? $app->applicant->applicantDetail->address : '-' }}
                    </span>
                </div>
                


                @if (empty($app->applicant->applicantDetail->residence_type))
                    <div class="col-md-3">
                        <span>Residence Type</span>
                    </div>
                    <div class="col-md-9">
                        <span>
                            {{ isset($app->applicant->applicantDetail->residence_type) ? $app->applicant->applicantDetail->residence_type : '-' }}
                        </span>
                    </div>
                @endif

                @if (!empty($app->applicant->applicantDetail->nid_number))
                    <div class="col-md-3">
                        <span>National ID</span>
                    </div>
                    <div class="col-md-9">
                        <span>
                            {{ isset($app->applicant->applicantDetail->nid_number) ? $app->applicant->applicantDetail->nid_number : '-' }}
                        </span>
                    </div>
                @endif
                
                <div class="col-md-3">
                    <span>Mobile No.</span>
                </div>
                <div class="col-md-9">
                    <span>
                        {{ isset($app->applicant->phone) ? $app->applicant->phone : '-' }}
                    </span>
                </div>
                <div class="col-md-3">
                    <span>Email</span>
                </div>
                <div class="col-md-9">
                    <span>
                        {{ isset($app->applicant->email) ? $app->applicant->email : '-' }}

                    </span>
                </div>

                @if (!empty($app->applicant->applicantDetail->tin))
                    <div class="col-md-3">
                        <span>Tin</span>
                    </div>
                    <div class="col-md-9">
                        <span>
                            {{ isset($app->applicant->applicantDetail->tin) ? $app->applicant->applicantDetail->tin : '-' }}
                        </span>
                    </div>
                @endif

                @if (!empty($app->applicant->applicantDetail->no_sticker_to_self_family))
                    <div class="col-md-3">
                        <span>Number Of Sticker allocated to applicant/applicant's family 2018</span>
                    </div>
                    <div class="col-md-9">
                        <span>
                            {{ $app->applicant->applicantDetail->no_sticker_to_self_family }}
                        </span>
                    </div>
                @endif
                @if (!empty($app->applicant->applicantDetail->allocated_current_sticker_type))
                    <div class="col-md-3">
                        <span>Currently allocated Sticker Types</span>
                    </div>
                    <div class="col-md-9">
                        <span>
                            {{ $app->applicant->applicantDetail->allocated_current_sticker_type }}
                        </span>
                    </div>
                @endif
                @if (!empty($app->applicant->applicantDetail->allocated_current_sticker_no))
                    <div class="col-md-3">
                        <span>Currently allocated Sticker No</span>
                    </div>
                    <div class="col-md-9">
                        <span>
                            {{ $app->applicant->applicantDetail->allocated_current_sticker_no }}
                        </span>
                    </div>
                @endif
                @if (!empty($app->remark))
                    <div class="col-md-3">
                        <span>Applicant Remarks </span>
                    </div>
                    <div class="col-md-9">
                        <span>
                            {{ $app->remark }}
                        </span>
                    </div>
                @endif
            </div>
            <div class="row app-section mt-3">
                <div class="col-md-12">
                    <h5 class="text-center py-2 heading-bg">Vehicle Details</h5>
                </div>
                <div class="col-md-3">
                    <span>Vehicle Type</span>
                </div>
                <div class="col-md-9">
                    <span>{{ !empty($app->vehicleinfo->vehicleType->name) ? $app->vehicleinfo->vehicleType->name : '' }}</span>
                </div>
                @if ($app->glass_type)
                    <div class="col-md-3">
                        <span>Vehicle Glass Type</span>
                    </div>
                    <div class="col-md-9">
                        <span>{{ ucfirst($app->glass_type) }}</span>
                    </div>
                @endif
                <div class="col-md-3">
                    <span>Vehicle Registration No.</span>
                </div>
                <div class="col-md-9">
                    <span>{{ !empty($app->vehicleinfo->reg_number) ? $app->vehicleinfo->reg_number : '' }}</span>
                </div>
                @if ( !empty($app->vehicleowner->company_name))
                    <div class="col-md-3">
                        <span>
                            Owner is company?
                        </span>
                    </div>
                    <div class="col-md-9">
                        <span>
                            {{ !empty($app->vehicleowner->company_name) ? 'Yes' : 'No' }}
                        </span>
                    </div>
                @endif
                @if (!empty($app->vehicleowner->company_name))
                    <div class="col-md-3">
                        <span>Company Name</span>
                    </div>
                    <div class="col-md-9">
                        <span>{{ !empty($app->vehicleowner->company_name) ? $app->vehicleowner->company_name : '' }}</span>
                    </div>
                    <div class="col-md-3">
                        <p>Company Address</p>
                    </div>
                    <div class="col-md-9">
                        <?php $com_address = json_decode($app->vehicleowner->company_address, true); ?>
                        <span>House: {{ $com_address['house'] }}</span><br>
                        <span>Road: {{ $com_address['road'] }}</span><br>
                        <span>Block: {{ $com_address['block'] }}</span><br>
                        <span>Area: {{ $com_address['area'] }}</span>
                    </div>
                @endif
                @if (!empty($app->vehicleowner->vehicle_owner_type))
                    <div class="col-md-3">
                        <span>Owner of the Vehicle</span>
                    </div>
                    <div class="col-md-9">
                        <span style="text-transform: capitalize;">
                            {{ isset($app->vehicleowner->vehicle_owner_type) ? $app->vehicleowner->vehicle_owner_type : '-' }}
                        </span>
                    </div>
                @endif




                @if (!empty($app->vehicleowner->relation))
                    <div class="col-md-3">
                        <span>Relation with Vehicle Owner</span>
                    </div>
                    <div class="col-md-9">
                        <span style="text-transform: capitalize;">
                            {{ isset($app->vehicleowner->relation) ? $app->vehicleowner->relation : '-' }}
                        </span>
                    </div>
                @endif


                @if ($app->vehicleowner?->vehicle_owner_type == 'self')
                    <div class="col-md-3">
                        <span>Owner Name</span>
                    </div>
                    <div class="col-md-9">
                        <span>
                            {{ isset($app->vehicleowner->owner_name) ? $app->vehicleowner->owner_name : '-' }}
                        </span>
                    </div>
                @endif
                @if (empty($app->vehicleinfo->tax_token_validity))
                    <div class="col-md-3">
                        <span>Tax Paid Upto</span>
                    </div>
                    <div class="col-md-9">
                        <span>
                            {{ isset($app->vehicleinfo->tax_token_validity) ? Carbon\Carbon::parse($app->vehicleinfo->tax_token_validity)->format('d-m-Y') : '-' }}

                        </span>
                    </div>
                @endif
                @if (!empty($app->vehicleinfo->loan_taken))
                    <div class="col-md-3">
                        <span>Loan Taken?</span>
                    </div>
                    <div class="col-md-9">
                        <span>
                            {{ !empty($app->vehicleinfo->loan_taken) ? 'Yes' : 'No' }}

                        </span>
                    </div>
                @endif

                @if (empty($app->vehicleinfo->fitness_validity))
                    <div class="col-md-3">
                        <span>Fitness Validity</span>
                    </div>
                    <div class="col-md-9">
                        <span>
                            {{ isset($app->vehicleinfo->fitness_validity) ? Carbon\Carbon::parse($app->vehicleinfo->fitness_validity)->format('d-m-Y') : '-' }}
                        </span>
                    </div>
                @endif

                @if (!empty($app->vehicleinfo->insurance_validity))
                    <div class="col-md-3">
                        <span>Insurance Validity</span>
                    </div>
                    <div class="col-md-9">
                        <span>
                            {{ isset($app->vehicleinfo->insurance_validity) ? Carbon\Carbon::parse($app->vehicleinfo->insurance_validity)->format('d-m-Y') : '-' }}
                        </span>
                    </div>
                @endif

                @if (!empty($app->vehicleinfo->necessity_to_use))
                    <div class="col-md-3">
                        <span>Necessity of Using Station HQ </span>
                    </div>
                    <div class="col-md-9">
                        <span>
                            {{ isset($app->vehicleinfo->necessity_to_use) ? $app->vehicleinfo->necessity_to_use : '-' }}
                        </span>
                    </div>
                @endif
            </div>

            @if ($app->driverinfo)
                <div class="row app-section mt-3">
                    <div class="col-md-12">
                        <h5 class="text-center py-2 heading-bg">Driver Details</h5>
                    </div>
                    @if (!empty($app->driverinfo->driver_is_owner) && $app->driverinfo->driver_is_owner == '1')
                        <div class="col-md-3">
                            <span>Vehicle is self driven?</span>
                        </div>
                        <div class="col-md-9">
                            <span>
                                Yes
                            </span>
                        </div>
                        <div class="col-md-3">
                            <span>Driver's Name</span>
                        </div>
                        <div class="col-md-4" style="border: none;">
                            <span>
                                -
                            </span>
                        </div>
                        <div class="col-md-5" style="position: relative; border: none;">
                            <img id="driver_photo"
                                src="{{ url('/') }}{{ !empty($app->applicant->applicantDetail->applicant_photo) ? $app->applicant->applicantDetail->applicant_photo : '' }}"
                                class="img-fluid preview-img-review" alt=""
                                style="position: absolute; top: 15px; right: 15px; z-index: 2;cursor: pointer;" height="150"
                                width="150" data-toggle="modal" data-target="#DriverPhotoModaldata">
                        </div>
                        <div class="col-md-3">
                            <p>Present Address</p>
                        </div>

                        <div class="col-md-9">
                            -
                        </div>
                        <div class="col-md-3">
                            <p>Permanent Address</p>
                        </div>
                        <div class="col-md-9">
                            -
                        </div>

                        <div class="col-md-3">
                            <span>NID No.</span>
                        </div>
                        <div class="col-md-9">
                            <span>
                                -
                            </span>
                        </div>
                    @elseif(empty($app->driverinfo->driver_is_owner))
                        <div class="col-md-3">
                            <span>Vehicle is self driven?</span>
                        </div>
                        <div class="col-md-9">
                            <span>
                                No
                            </span>

                        </div>
                        <div class="col-md-3">
                            <span>Driver's Name</span>
                        </div>
                        <div class="col-md-4" style="border: none;">
                            <span>
                                {{ isset($app->driverinfo->name) ? $app->driverinfo->name : '-' }}
                            </span>
                        </div>
                        <div class="col-md-5" style="position: relative;">
                            <img id="driver_photo"
                                src="{{ url('/') }}{{ !empty($app->driverinfo->photo) ? $app->driverinfo->photo : '' }}"
                                class="img-fluid preview-img-review" alt=""
                                style="position: absolute; top: 15px; right: 15px; z-index: 2;cursor: pointer;" height="150"
                                width="150" data-toggle="modal" data-target="#DriverPhotoModaldata">
                        </div>
                        @if (isset($app->driverinfo->address) && isset($app->driverinfo->address) != '')
                            <?php $driver_address = json_decode($app->driverinfo->address, true); ?>
                            <div class="col-md-3">
                                <p>Address</p>
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-4 asaddress-type">
                                        <div class="sta_address">

                                            <p class="address-title present">Prersent Address</p>
                                            <div class="present_address">
                                                <span>House:
                                                    {{ !empty($driver_address['present']['house']) ? $driver_address['present']['house'] : '' }}</span>
                                                <br>
                                                <span>Road:
                                                    {{ !empty($driver_address['present']['road']) ? $driver_address['present']['road'] : '' }}</span>
                                                <br>
                                                <span>Block:
                                                    {{ !empty($driver_address['present']['block']) ? $driver_address['present']['block'] : '' }}</span>
                                                <br>
                                                <span>Area:
                                                    {{ !empty($driver_address['present']['area']) ? $driver_address['present']['area'] : '' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 asaddress-type">
                                        <div class="sta_address">
                                            <p class="address-title permanent">Permanent Address</p>
                                            <div class="permanant_address">
                                                <span>House:
                                                    {{ !empty($driver_address['permanent']['p_house']) ? $driver_address['permanent']['p_house'] : '-' }}</span>
                                                <br>
                                                <span>Road:
                                                    {{ !empty($driver_address['permanent']['p_road']) ? $driver_address['permanent']['p_road'] : '-' }}</span>
                                                <br>
                                                <span>Block:
                                                    {{ !empty($driver_address['permanent']['p_block']) ? $driver_address['permanent']['p_block'] : '-' }}</span>
                                                <br>
                                                <span>Area:
                                                    {{ !empty($driver_address['permanent']['p_area']) ? $driver_address['permanent']['p_area'] : '-' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endif





                        <div class="col-md-3">
                            <span>NID No.</span>
                        </div>
                        <div class="col-md-9">
                            <span>
                                {{ isset($app->driverinfo->nid_number) ? $app->driverinfo->nid_number : '-' }}
                            </span>
                        </div>
                    @endif

                </div>
            @endif
            


            <div class="row app-section doc-section mt-3">
                <div class="col-md-12">
                    <h5 class="text-center py-2 heading-bg">Documents</h5>
                </div>
                @if (!empty($app->applicant->applicantDetail->nid_photo) && !empty($app->applicant->applicantDetail->nid_photo))
                    <div class="col-md-4">
                        <span>Applicant NID (Front)</span>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-info doc_img_prev" data-toggle="modal"
                            data-target="#nid1-copy">
                            View
                        </button>

                        <div class="modal fade" id="nid1-copy" tabindex="-1" role="dialog" aria-labelledby=""
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        Applicant NID Copy
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="padding:0;">
                                        <img id="docImg1"
                                            src="{{ url('/') }}{{ $app->applicant->applicantDetail->nid_photo }}"
                                            class="img-fluid" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if (!empty($app->applicant->applicantDetail->nid_back_photo) && !empty($app->applicant->applicantDetail->nid_back_photo))
                    <div class="col-md-4">
                        <span>Applicant NID (Back)</span>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-info doc_img_prev" data-toggle="modal"
                            data-target="#nid-back">
                            View
                        </button>

                        <div class="modal fade" id="nid-back" tabindex="-1" role="dialog" aria-labelledby=""
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        Applicant NID (Back)
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="padding:0;">
                                        <img id="docImg1"
                                            src="{{ url('/') }}{{ $app->applicant->applicantDetail->nid_back_photo }}"
                                            class="img-fluid" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif


                @if (!empty($app->application_category) && !empty($app->app_category_attachment))
                    <div class="col-md-4">
                        <span>Application Category Attachment</span>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-info doc_img_prev" data-toggle="modal"
                            data-target="#app-category-attachment">
                            View
                        </button>
                        <div class="modal fade" id="app-category-attachment" tabindex="-1" role="dialog" aria-labelledby=""
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        Application Category Attachment
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="padding:0;">
                                        <img id="docImg20"
                                            src="{{ url('/') }}{{ $app->app_category_attachment }}"
                                            class="img-fluid" alt="">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (!empty($app->vehicleowner) && !empty($app->vehicleowner->owner_type_attachment))
                    <div class="col-md-4">
                        <span>Owner Of the Vehicle Attachment</span>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-info doc_img_prev" data-toggle="modal"
                            data-target="#owner-type-attachment">
                            View
                        </button>
                        <div class="modal fade" id="owner-type-attachment" tabindex="-1" role="dialog" aria-labelledby=""
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        Owner Of the Vehicle Attachment
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="padding:0;">
                                        <img id="docImg19"
                                            src="{{ url('/') }}{{ $app->vehicleowner->owner_type_attachment }}"
                                            class="img-fluid" alt="">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif


                @if (!empty($app->applicant->applicantDetail->defIdCopy) && !empty($app->applicant->applicantDetail->defIdCopy))
                    <div class="col-md-4">
                        <span>Applicant Def ID (Front Part)</span>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-info doc_img_prev" data-toggle="modal"
                            data-target="#defID-copy">
                            View
                        </button>
                        <div class="modal fade" id="defID-copy" tabindex="-1" role="dialog" aria-labelledby=""
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        Applicant Def ID Copy
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="padding:0;">
                                        <img id="docImg2"
                                            src="{{ url('/') }}{{ $app->applicant->applicantDetail->defIdCopy }}"
                                            class="img-fluid" alt="">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if (!empty($app->applicant->applicantDetail->def_id_back_photo) && !empty($app->applicant->applicantDetail->def_id_back_photo))
                    <div class="col-md-4">
                        <span>Applicant Def ID (Back)</span>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-info doc_img_prev" data-toggle="modal"
                            data-target="#defID-back">
                            View
                        </button>
                        <div class="modal fade" id="defID-back" tabindex="-1" role="dialog" aria-labelledby=""
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        Applicant Def ID Copy
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="padding:0;">
                                        <img id="docImg2"
                                            src="{{ url('/') }}{{ $app->applicant->applicantDetail->def_id_back_photo }}"
                                            class="img-fluid" alt="">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if (isset($app->vehicleinfo->reg_cert_photo) && !empty($app->vehicleinfo->reg_cert_photo))
                    <div class="col-md-4">
                        <span>Vehicle Registration (Front)</span>
                    </div>
                    <div class="col-md-1">
                        
                        <button type="button" class="btn btn-info doc_img_prev" data-toggle="modal"
                            data-target="#nid3-copy">
                            View
                        </button>
                        <div class="modal fade" id="nid3-copy" tabindex="-1" role="dialog" aria-labelledby=""
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        Vehicle Registration Copy
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="padding:0;">

                                        <img id="docImg3"
                                            src="{{ url('/') }}{{ $app->vehicleinfo->reg_cert_photo }}"
                                            class="img-fluid" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if (isset($app->vehicleinfo->reg_cert_back_photo) && !empty($app->vehicleinfo->reg_cert_back_photo))
                    <div class="col-md-4">
                        <span>Vehicle Registration (Back)</span>
                    </div>
                    <div class="col-md-1">

                        <button type="button" class="btn btn-info doc_img_prev" data-toggle="modal"
                            data-target="#reg_back">
                            View
                        </button>
                        <div class="modal fade" id="reg_back" tabindex="-1" role="dialog" aria-labelledby=""
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        Vehicle Registration Copy
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="padding:0;">

                                        <img id="docImg3"
                                            src="{{ url('/') }}{{ $app->vehicleinfo->reg_cert_back_photo }}"
                                            class="img-fluid" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if (isset($app->applicant->applicantDetail->applicant_photo) && !empty($app->applicant->applicantDetail->applicant_photo))
                    <div class="col-md-4">
                        <span>Applicant's Photo</span>
                    </div>
                    <div class="col-md-1">
                        
                        <button type="button" class="btn btn-info doc_img_prev" data-toggle="modal"
                            data-target="#nid4-copy">
                            View
                        </button>
                        <div class="modal fade" id="nid4-copy" tabindex="-1" role="dialog" aria-labelledby=""
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        Applicant's Photo
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="padding:0;">
                                        <img id="docImg3"
                                            src="{{ url('/') }}{{ $app->applicant->applicantDetail->applicant_photo }}"
                                            class="img-fluid" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (isset($app->vehicleinfo->tax_token_photo) && !empty($app->vehicleinfo->tax_token_photo))
                    <div class="col-md-4">
                        <span>Tax Token Copy</span>
                    </div>

                    <div class="col-md-1">
                        
                        <button type="button" class="btn btn-info doc_img_prev" data-toggle="modal"
                            data-target="#nid5-copy">
                            View
                        </button>
                        <div class="modal fade" id="nid5-copy" tabindex="-1" role="dialog" aria-labelledby=""
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        Tax Token Copy
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="padding:0;">
                                        <img id="docImg4"
                                            src="{{ url('/') }}{{ $app->vehicleinfo->tax_token_photo }}"
                                            class="img-fluid" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (isset($app->vehicleinfo->fitness_cert_photo) && !empty($app->vehicleinfo->fitness_cert_photo))
                    <div class="col-md-4">
                        <span>Fitness Certificate Copy</span>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-info doc_img_prev" data-toggle="modal"
                            data-target="#nid6-copy">
                            View
                        </button>
                        <div class="modal fade" id="nid6-copy" tabindex="-1" role="dialog" aria-labelledby=""
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        Fitness Certificate Copy
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="padding:0;">
                                        <img id="docImg5"
                                            src="{{ url('/') }}{{ $app->vehicleinfo->fitness_cert_photo }}"
                                            class="img-fluid" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (isset($app->vehicleinfo->insurance_cert_photo) && !empty($app->vehicleinfo->insurance_cert_photo))
                    <div class="col-md-4">
                        <span>Insurance Certificate Copy</span>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-info doc_img_prev" data-toggle="modal"
                            data-target="#nid7-copy">
                            View
                        </button>
                        <div class="modal fade" id="nid7-copy" tabindex="-1" role="dialog" aria-labelledby=""
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        Insurance Certificate Copy
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="padding:0;">
                                        <img id="docImg6"
                                            src="{{ url('/') }}{{ $app->vehicleinfo->insurance_cert_photo }}"
                                            class="img-fluid" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (isset($app->document->civil_service_id) && !empty($app->document->civil_service_id))
                    <div class="col-md-4">
                        <span>Civil Service ID Copy</span>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-info doc_img_prev" data-toggle="modal"
                            data-target="#nid8-copy">
                            View
                        </button>
                        <div class="modal fade" id="nid8-copy" tabindex="-1" role="dialog" aria-labelledby=""
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        Civil Service ID Copy
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="padding:0;">
                                        <img id="docImg7"
                                            src="{{ url('/') }}{{ $app->document->civil_service_id }}"
                                            class="img-fluid" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (isset($app->document->school_cert) && !empty($app->document->school_cert))
                    <div class="col-md-4">
                        <span>Proyash School Certificate Copy</span>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-info doc_img_prev" data-toggle="modal"
                            data-target="#nid9-copy">
                            View
                        </button>

                        <div class="modal fade" id="nid9-copy" tabindex="-1" role="dialog" aria-labelledby=""
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        Proyash School Certificate Copy
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="padding:0;">
                                        <img id="docImg8" src="{{ url('/') }}{{ $app->document->school_cert }}"
                                            class="img-fluid" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (isset($app->document->marriage_cert) && !empty($app->document->marriage_cert))
                    <div class="col-md-4">
                        <span>Marriage Certificate Copy</span>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-info doc_img_prev" data-toggle="modal"
                            data-target="#nid10-copy">
                            View
                        </button>
                        <div class="modal fade" id="nid10-copy" tabindex="-1" role="dialog" aria-labelledby=""
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        Marriage Certificate Copy
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="padding:0;">

                                        <img id="docImg9"
                                            src="{{ url('/') }}{{ $app->document->marriage_cert }}"
                                            class="img-fluid" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (isset($app->driverinfo->nid_photo) && !empty($app->driverinfo->nid_photo))
                    <div class="col-md-4">
                        <span>Driver NID Copy</span>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-info doc_img_prev" data-toggle="modal"
                            data-target="#nid11-copy">
                            View
                        </button>
                        <div class="modal fade" id="nid11-copy" tabindex="-1" role="dialog" aria-labelledby=""
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        Driver NID Copy
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="padding:0;">
                                        <img id="docImg10" src="{{ url('/') }}{{ $app->driverinfo->nid_photo }}"
                                            class="img-fluid" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (isset($app->driverinfo->licence_photo) && !empty($app->driverinfo->licence_photo))
                    <div class="col-md-4">
                        <span>Driving License Copy</span>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-info doc_img_prev" data-toggle="modal"
                            data-target="#nid12-copy">
                            View
                        </button>
                        <div class="modal fade" id="nid12-copy" tabindex="-1" role="dialog" aria-labelledby=""
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        Driving License Copy
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="padding:0;">

                                        <img id="docImg11"src="{{ url('/') }}{{ $app->driverinfo->licence_photo }}"
                                            class="img-fluid" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (isset($app->document->job_cert) && !empty($app->document->job_cert))
                    <div class="col-md-4 ">
                        <span>Job Certificate Copy</span>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-info doc_img_prev" data-toggle="modal"
                            data-target="#nid13-copy">
                            View
                        </button>
                        <div class="modal fade" id="nid13-copy" tabindex="-1" role="dialog" aria-labelledby=""
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        Job Certificate Copy
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="padding:0;">
                                        <img id="docImg12"src="{{ url('/') }}{{ $app->document->job_cert }}"
                                            class="img-fluid" alt="">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (isset($app->document->house_owner_cert) && !empty($app->document->house_owner_cert))
                    <div class="col-md-4">
                        <span>House Owner Certificate Copy</span>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-info doc_img_prev" data-toggle="modal"
                            data-target="#nid14-copy">
                            View
                        </button>
                        <div class="modal fade" id="nid14-copy" tabindex="-1" role="dialog" aria-labelledby=""
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        House Owner Certificate Copy
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="padding:0;">\
                                        <img id="docImg13"
                                            src="{{ url('/') }}{{ $app->document->house_owner_cert }}"
                                            class="img-fluid" alt="">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (isset($app->document->ward_comm_cert) && !empty($app->document->ward_comm_cert))
                    <div class="col-md-4">
                        <span>Ward Commissioner Certificate Copy</span>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-info doc_img_prev" data-toggle="modal"
                            data-target="#nid15-copy">
                            View
                        </button>
                        <div class="modal fade" id="nid15-copy" tabindex="-1" role="dialog" aria-labelledby=""
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        Ward Commissioner Certificate Copy
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="padding:0;">
                                        <img id="docImg14"
                                            src="{{ url('/') }}{{ $app->document->ward_comm_cert }}"
                                            class="img-fluid" alt="">


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (isset($app->document->auth_cert) && !empty($app->document->auth_cert))
                    <div class="col-md-4">
                        <span>Autorised Certificate Copy</span>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-info doc_img_prev" data-toggle="modal"
                            data-target="#nid16-copy">
                            View
                        </button>
                        <div class="modal fade" id="nid16-copy" tabindex="-1" role="dialog" aria-labelledby=""
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        Autorised Certificate Copy
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="padding:0;">
                                        <img id="docImg15" src="{{ url('/') }}{{ $app->document->auth_cert }}"
                                            class="img-fluid" alt="">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (isset($app->document->father_testm) && !empty($app->document->father_testm))
                    <div class="col-md-4 ">
                        <span>Father Testimonial Copy</span>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-info doc_img_prev" data-toggle="modal"
                            data-target="#nid17-copy">
                            View
                        </button>
                        <div class="modal fade" id="nid17-copy" tabindex="-1" role="dialog" aria-labelledby=""
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        Father Testimonial Copy
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="padding:0;">

                                        <img id="docImg16"
                                            src="{{ url('/') }}{{ $app->document->father_testm }}"
                                            class="img-fluid" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (isset($app->document->mother_testm) && !empty($app->document->mother_testm))
                    <div class="col-md-4">
                        <span>Mother Testimonial Copy</span>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-info doc_img_prev" data-toggle="modal"
                            data-target="#nid18-copy">
                            View
                        </button>
                        <div class="modal fade" id="nid18-copy" tabindex="-1" role="dialog" aria-labelledby=""
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        Mother Testimonial Copy
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="padding:0;">
                                        <img id="docImg17"
                                            src="{{ url('/') }}{{ $app->document->mother_testm }}"
                                            class="img-fluid" alt="">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
            <div class="row app-section doc-section mt-3">
                <div class="col-md-12">
                    <h5 class="text-center py-2  heading-bg">Follow Up</h5>
                </div>
                <table class="table table-bordered table-striped follow-up-table">
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
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ \Carbon\Carbon::parse($follow_up->created_at)->toDayDateTimeString() }}</td>
                                    <td>
                                        {{ $follow_up->status }}

                                        {{ $follow_up->app_status == 'forwarded to PS' ? 'Application forwarded  To MP DTE' : $follow_up->status }}
                                    </td>
                                    <td>{{ !empty($follow_up->comment) ? $follow_up->comment : '' }}</td>
                                    <td>
                                        <div class="row justify-content-center">
                                            <div class="col-md-3">
                                                @if ($follow_up->sms_sent == 'success')
                                                    <span style="color: green;" title="sms sent"><i
                                                            class="fas fa-check"></i></span>
                                                @elseif($follow_up->sms_sent == 'fail')
                                                    <span class="sms-status-replace" hidden></span>
                                                    <span class="sms-failed" style="color: red;" title="sms not sent"><i
                                                            class="fas fa-times"></i></span>
                                                    <span class="resendSms sms-failed" data-id="{{ $follow_up->id }}"
                                                        style="color:#07adca; cursor: pointer;" title="retry to send"><i
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
                                    <td>{{ $follow_up->updated_by }}</td>
                                </tr>
                            @endforeach
                        @endif


                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Hold Modal-->
    <div class="modal fade" id="holdApplicationModal" tabindex="-1" role="dialog" aria-labelledby=""
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #4785c7; padding: 10px 0;">
                    <legend style="color:#fff; text-align: center; ">Hold the Application</legend>
                    <button style="color: #fff;" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="padding:10px 10px 0 0;">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding:0;">
                    <div class="row">
                        <div class="col-md-12">


                            <textarea style="margin: 10px" class="form-control" id="holdMessage" placeholder="Enter the hold reason here"></textarea>

                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" data-number="{{ $app->id }}" class="btn btn-primary custm-btn"
                        id="confirm-hold">Confirm</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Hold Modal End-->

    {{-- start free approval --}}
    <!-- Approve Modal -->

    <div class="modal fade" id="myApproveModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #4785c7; padding: 10px 0;">
                    <legend style="color:#fff; text-align: center; ">Approve Application</legend>
                    <button style="color: #fff;" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="padding:10px 10px 0 0;">&times;</span>
                    </button>
                </div>
                <form id="approve_App_frm">
                    {{ csrf_field() }}
                    <div class="modal-body" style="padding:0;">

                        <div class="row">
                            <div class="col-md-4 offset-md-1">
                                <label for="" class="label-form"> Sticker Type </label> <span>*</span> <br>
                                <small></small>
                            </div>

                            @php
                                $re = !empty($app->vehicleinfo->reg_number) ? $app->vehicleinfo->reg_number : '';
                                    $vehicle_type = !empty($app->vehicleinfo->vehicleType->type) ? $app->vehicleinfo->vehicleType->type : '';
                                    $stickerk = App\Models\ETypeStickerRegisterNumber::where('reg_number', $re)->first();
                                    
                                    $sticker_categories = App\Models\StickerCategory::query();
                                  

                                    if ($vehicle_type == 'e') {
                                        $sticker_categories = $sticker_categories->where('id', $stickerk->sticker_category_id ?? '');
                                    }

                                    if ($vehicle_type !== 'e') {
                                        
                                        $sticker_categories = $sticker_categories->where('type', '!=', 'e')->orWhereNull('type');
                                    }

                                    $sticker_categories = $sticker_categories->get();
                                
                            @endphp

                            <div class="col-md-7">
                                <select name="sticker_type" id="sticker_type" class="form-control in-form mandatory"
                                    required>
                                    {{-- <option selected disabled value=""> --Select One--</option> --}}
                                    @if (!$stickerk)
                                        <option selected disabled value=""> --Select One--</option>
                                    @endif
                                    @foreach ($sticker_categories as $sticker_categorie)
                                        <option value="{{ $sticker_categorie->value }}"
                                            {{ $sticker_categorie->value == $app->sticker_category ? 'selected' : '' }}>
                                            {{ $sticker_categorie->name }}</option>
                                    @endforeach

                                </select>
                                <div id="err_msg_sticker_type" class="" style="color:#bd2130;" hidden> <i
                                        class="fas fa-exclamation-triangle"></i> <span id="err_sticker_type"> </span>
                                </div>
                            </div>
                             <div class="col-md-4 offset-md-1">
                                <label for="" class="label-form"> Approve Date </label> <span>*</span> <br>
                                <small></small>
                            </div>
                            <div class="col-md-7">
                                <input type="date" id="sticker_approve_date"
                                    value="{{ $app->applicationNotify ? $app->applicationNotify->sticker_approve_date :'' }}"
                                    name="sticker_approve_date" class="form-control in-form" placeholder="" required>
                                <div id="err_msg_delDate" class="" style="color:#bd2130;" hidden> <i
                                        class="fas fa-exclamation-triangle"></i> <span id="err_delDate"> </span>
                                </div>
                            </div>

                           <!--  @if ($app->payment_status == '0')
                                <div class="col-md-4 offset-md-1">

                                </div>
                                <div class="col-md-7">
                                    <input type="checkbox" class="free_sticker_ckbox" id="free_sticker_ckbox"
                                        name="free_sticker_ckbox" value="free" />
                                    <label for="free_sticker_ckbox">Free Sticker</label>
                                </div>
                                <div class="row free_sticker_expand" style="display: none">
                                    <div class="col-md-4 offset-md-1">
                                        <label for="free_comment" class="label-form"> Comment </label> <span>*</span> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-7">
                                        <textarea class="" id="free_comment" name="free_comment" value=""></textarea>

                                    </div>
                                    <div class="col-md-4 offset-md-1">
                                        <label for="misc_doc" class="label-form"> MISC Document </label> <span></span>
                                        <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-7">
                                        <input type="file" class="" id="misc_doc" name="misc_doc" />

                                    </div>
                                </div>
                            @endif
                            -->

                             @if ($app->payment_status == '0')
                                <div class="col-md-4 offset-md-1">

                                </div>
                                <div class="col-md-7">
                                    <input type="checkbox" class="free_sticker_ckbox" id="free_sticker_ckbox"
                                        name="free_sticker_ckbox" value="free" />
                                    <label for="free_sticker_ckbox">Free Sticker</label>
                                </div>

                                {{-- Discount checkbox --}}
                                    <div class="col-md-4 offset-md-1">
                                    </div>
                                    <div class="col-md-7">
                                        <input type="checkbox" class="discount_ckbox" id="discount_ckbox"
                                            name="is_discount" value="1" />
                                        <label for="discount_ckbox">Is Discount</label>
                                    </div>

                                <div class="row free_sticker_expand" style="display: none">
                                    <div class="col-md-4 offset-md-1">
                                        <label for="free_comment" class="label-form"> Comment </label> <span>*</span> <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-7">
                                        <textarea class="" id="free_comment" name="free_comment" value=""></textarea>

                                    </div>
                                    <div class="col-md-4 offset-md-1">
                                        <label for="misc_doc" class="label-form"> MISC Document </label> <span></span>
                                        <br>
                                        <small></small>
                                    </div>
                                    <div class="col-md-7">
                                        <input type="file" class="" id="misc_doc" name="misc_doc" />

                                    </div>
                                </div>

                               
                             {{-- Discount Expand --}}
                                <div class="row discount_expand" style="display: none">
                                    <div class="col-md-4 offset-md-1">
                                        <label for="sticker_amount" class="label-form">Sticker Price </label> <span>*</span><br>
                                        <small></small>
                                    </div>
                                        <div class="col-md-7">
                                            
                                            <input type="text" name="sticker_amount" value="{{ $stickerPrice ?? '' }}" disabled>
                                        

                                        </div>
                                        <div class="col-md-4 offset-md-1">
                                            <label for="discount_amount" class="label-form"> Discount Amount </label> <span>*</span><br>
                                            <small></small>
                                        </div>
                                        <div class="col-md-7">
                                        
                                            <input type="text" name="discount_amount">
                                        

                                        </div>
                                        <div class="col-md-4 offset-md-1">
                                            <label for="discount_reason" class="label-form"> Discount Reason </label> <span>*</span><br>
                                            <small></small>
                                        </div>
                                        <div class="col-md-7">
                                            {{-- <textarea class="form-control" id="discount_comment" name="discount_comment"></textarea> --}}
                                            <textarea class="" id="discount_reason" name="discount_reason"></textarea>

                                        </div>

                                       <!--  <div class="col-md-4 offset-md-1">
                                            <label for="discount_doc" class="label-form"> Supporting Document </label><br>
                                            <small></small>
                                        </div>
                                        <div class="col-md-7">
                                            <input type="file" class="form-control-file" id="discount_doc" name="discount_doc" />
                                        </div> -->
                                </div>
                            @endif
                        </div>

                    </div>
                    <input type="hidden" name="app_num" value="{{ $app->app_number }}">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" data-number="{{ $app->app_number }}" class="btn btn-primary custm-btn"
                            id="approve_App">Confirm Approve</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- The Modal -->

    {{-- end free approval --}}
    <!-- Reject Modal -->

    <div class="modal fade" id="myRejectModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #4785c7; padding: 10px 0;">
                    <legend style="color:#fff; text-align: center; ">Show Reject Reason</legend>
                    <button style="color: #fff;" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="padding:10px 10px 0 0;">&times;</span>
                    </button>
                </div>
                <div class="modal-body mt-2" style="padding:0;">
                    <div class="row ">
                        <div class="col-md-12 ">
                            <div class="row funkyradio justify-content-center">
                                <div class="funkyradio-primary col-md-4">
                                    <input type="radio" name="reason_type" id="file_miss_case" checked
                                        value="file_miss_case" />
                                    <label for="file_miss_case">File Missing/Obscurity</label>
                                </div>
                                <div class="funkyradio-info col-md-4">
                                    <input type="radio" name="reason_type" id="custom_case" value="custom_case" />
                                    <label for="custom_case">Custom Reason</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col" id="mis_matched">
                            <form id="reject_file_sms_form">
                                <fieldset class="xfieldset">
                                    <div id="accordion">
                                        <div class="row justify-content-center">
                                            <div class="col-xs-6">
                                                {{-- <div  class="reject-update-dropdown-options">
                                                  <input type="checkbox" class="attach_file" id="Applicant_Photo" name="Applicant_Photo"
                                                  value="আবেদনকারীর ছবি" />
                                                  <label for="Applicant_Photo">Applicant Photo</label>
                                                </div> --}}
                                                <div class="card">
                                                    <div class="card-header" id="headingApplicantPhoto">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link collapsed" type="button"
                                                                data-toggle="collapse" data-target="#ApplicantPhoto"
                                                                aria-expanded="false" aria-controls="ApplicantPhoto">
                                                                Applicant Photo
                                                            </button>
                                                        </h5>
                                                    </div>
                                                    <div id="ApplicantPhoto" class="collapse accordion-collapse"
                                                        aria-labelledby="headingApplicantPhoto">
                                                        <div class="card-body">
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Applicant_Photo_one" name="Applicant_Photo_one"
                                                                    value="আবেদনকারীর ছবি আপলোড করতে হবে" />
                                                                <label for="Applicant_Photo_one">আপলোড</label>
                                                            </div>
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Applicant_Photo_two" name="Applicant_Photo"
                                                                    value="আবেদনকারীর ছবি অস্পষ্ট" />
                                                                <label for="Applicant_Photo_two">অস্পষ্টতা</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                {{-- <div  class="reject-update-dropdown-options">
                                                  <input type="checkbox" class="attach_file" id="Applicant_NID" name="Applicant_NID"
                                                  value="আবেদনকারীর জাতীয় পরিচয়পত্রের কপি" />
                                                  <label for="Applicant_NID">Applicant NID</label>
                                                </div> --}}
                                                <div class="card">
                                                    <div class="card-header" id="headingApplicantNID">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link collapsed" type="button"
                                                                data-toggle="collapse" data-target="#ApplicantNID"
                                                                aria-expanded="false" aria-controls="ApplicantNID">
                                                                Applicant NID
                                                            </button>
                                                        </h5>
                                                    </div>
                                                    <div id="ApplicantNID" class="collapse accordion-collapse"
                                                        aria-labelledby="headingApplicantNID">
                                                        <div class="card-body">
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Applicant_NID_one" name="Applicant_NID_one"
                                                                    value="আবেদনকারীর জাতীয় পরিচয়পত্রের উভয়পাশের ছবি একত্রে আপলোড করতে হবে" />
                                                                <label for="Applicant_NID_one">আপলোড</label>
                                                            </div>
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Applicant_NID_two" name="Applicant_NID_two"
                                                                    value="আবেদনকারীর জাতীয় পরিচয়পত্রের উভয়পাশের ছবি অস্পষ্ট" />
                                                                <label for="Applicant_NID_two">অস্পষ্টতা</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- <div  class="reject-update-dropdown-options">
                                                    <input type="checkbox" class="attach_file" id="def_id" name="def_id"
                                                    value="আবেদনকারীর সামরিক পরিচয়পত্রের কপি" />
                                                    <label for="def_id">Applicant Def ID</label>
                                                  </div> --}}
                                                <div class="card">
                                                    <div class="card-header" id="headingApplicantDefID">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link collapsed" type="button"
                                                                data-toggle="collapse" data-target="#ApplicantDefID"
                                                                aria-expanded="false" aria-controls="ApplicantDefID">
                                                                Applicant Def ID
                                                            </button>
                                                        </h5>
                                                    </div>
                                                    <div id="ApplicantDefID" class="collapse"
                                                        aria-labelledby="headingApplicantDefID">
                                                        <div class="card-body">
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="def_id_one" name="def_id_one"
                                                                    value=" আবেদনকারীর সামরিক পরিচয়পত্রের
                                                                    উভয়পাশের ছবি একত্রে আপলোড করতে
                                                                    হবে" />
                                                                <label for="def_id_one">আপলোড</label>
                                                            </div>
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="def_id_two" name="def_id_two"
                                                                    value="আবেদনকারীর সামরিক পরিচয়পত্রের
                                                                    উভয়পাশের ছবি অস্পষ্ট" />
                                                                <label for="def_id_two">অস্পষ্টতা</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- <div  class="reject-update-dropdown-options">
                    <input type="checkbox" class="attach_file" id="Driver_Photo" name="Driver_Photo"
                    value="গাড়ি চালকের ছবি" />
                    <label for="Driver_Photo">Driver Photo</label>
                  </div> --}}
                                                <div class="card">
                                                    <div class="card-header" id="headingDriverPhoto">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link collapsed" type="button"
                                                                data-toggle="collapse" data-target="#DriverPhoto"
                                                                aria-expanded="false" aria-controls="DriverPhoto">
                                                                Driver Photo
                                                            </button>
                                                        </h5>
                                                    </div>
                                                    <div id="DriverPhoto" class="collapse"
                                                        aria-labelledby="headingDriverPhoto">
                                                        <div class="card-body">
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Driver_Photo_one" name="Driver_Photo_one"
                                                                    value="গাড়ি চালকের ছবি আপলোড করতে হবে" />
                                                                <label for="Driver_Photo_one">আপলোড</label>
                                                            </div>
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Driver_Photo_two" name="Driver_Photo_two"
                                                                    value="গাড়ি চালকের ছবি অস্পষ্ট" />
                                                                <label for="Driver_Photo_two">অস্পষ্টতা</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- <div  class="reject-update-dropdown-options">
                    <input type="checkbox" class="attach_file" id="Driver_NID" name="Driver_NID"
                    value="গাড়ি চালকের জাতীয় পরিচয়পত্রের কপি" />
                    <label for="Driver_NID">Driver NID</label>
                  </div> --}}
                                                <div class="card">
                                                    <div class="card-header" id="headingDriverNID">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link collapsed" type="button"
                                                                data-toggle="collapse" data-target="#DriverNID"
                                                                aria-expanded="false" aria-controls="DriverNID">
                                                                Driver NID
                                                            </button>
                                                        </h5>
                                                    </div>
                                                    <div id="DriverNID" class="collapse"
                                                        aria-labelledby="headingDriverNID">
                                                        <div class="card-body">
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Driver_NID_one" name="Driver_NID_one"
                                                                    value="গাড়ি চালকের জাতীয় পরিচয়পত্রের
                                                                    উভয়পাশের ছবি একত্রে আপলোড করতে
                                                                    হবে" />
                                                                <label for="Driver_NID_one">আপলোড</label>
                                                            </div>
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Driver_NID_two" name="Driver_NID_two"
                                                                    value="গাড়ি চালকের জাতীয় পরিচয়পত্রের
                                                                    উভয়পাশের ছবি অস্পষ্ট" />
                                                                <label for="Driver_NID_two">অস্পষ্টতা</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- <div  class="reject-update-dropdown-options">
                    <input type="checkbox" class="attach_file" id="Driver_License_Copy" name="Driver_License_Copy"
                    value="ড্রাইভিং লাইসেন্সের কপি" />
                    <label for="Driver_License_Copy">Driver License </label>
                  </div> --}}
                                                <div class="card">
                                                    <div class="card-header" id="headingDriverLicense">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link collapsed" type="button"
                                                                data-toggle="collapse" data-target="#DriverLicense"
                                                                aria-expanded="false" aria-controls="DriverLicense">
                                                                Driver License
                                                            </button>
                                                        </h5>
                                                    </div>
                                                    <div id="DriverLicense" class="collapse"
                                                        aria-labelledby="headingDriverLicense">
                                                        <div class="card-body">
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Driver_License_Copy_one"
                                                                    name="Driver_License_Copy_one"
                                                                    value="ড্রাইভিং লাইসেন্স আপলোড করতে হবে" />
                                                                <label for="Driver_License_Copy_one">আপলোড</label>
                                                            </div>
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Driver_License_Copy_two"
                                                                    name="Driver_License_Copy_two"
                                                                    value="ড্রাইভিং লাইসেন্স অস্পষ্ট" />
                                                                <label for="Driver_License_Copy_two">অস্পষ্টতা</label>
                                                            </div>
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Driver_License_Copy_three"
                                                                    name="Driver_License_Copy_three"
                                                                    value="ড্রাইভিং লাইসেন্স এর মেয়াদউত্তীর্ণ" />
                                                                <label
                                                                    for="Driver_License_Copy_three">মেয়াদউত্তীর্ণ</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- <div  class="reject-update-dropdown-options">
                    <input type="checkbox" class="attach_file" id="Father_Testimonial" name="Father_Testimonial"
                    value="পিতার প্রত্যয়নপত্র" />
                    <label for="Father_Testimonial">Father Testimonial </label>
                  </div> --}}
                                                <div class="card">
                                                    <div class="card-header" id="headingFatherTestimonial">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link collapsed" type="button"
                                                                data-toggle="collapse" data-target="#FatherTestimonial"
                                                                aria-expanded="false" aria-controls="FatherTestimonial">
                                                                Father Testimonial
                                                            </button>
                                                        </h5>
                                                    </div>
                                                    <div id="FatherTestimonial" class="collapse"
                                                        aria-labelledby="headingFatherTestimonial">
                                                        <div class="card-body">
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Father_Testimonial_one"
                                                                    name="Father_Testimonial_one"
                                                                    value="আবেদনকারীর পিতার প্রত্যয়নপত্র আপলোড
                                                                    করতে হবে" />
                                                                <label for="Father_Testimonial_one">আপলোড</label>
                                                            </div>
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Father_Testimonial_two"
                                                                    name="Father_Testimonial_two"
                                                                    value="আবেদনকারীর পিতার প্রত্যয়নপত্র অস্পষ্ট" />
                                                                <label for="Father_Testimonial_two">অস্পষ্টতা</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- 
                  <div  class="reject-update-dropdown-options">
                    <input type="checkbox" class="attach_file" id="Mother_Testimonial" name="Mother_Testimonial"
                    value="মাতার প্রত্যয়নপত্র" />
                    <label for="Mother_Testimonial">Mother Testimonial </label>
                  </div> --}}
                                                <div class="card">
                                                    <div class="card-header" id="headingMotherTestimonial">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link collapsed" type="button"
                                                                data-toggle="collapse" data-target="#MotherTestimonial"
                                                                aria-expanded="false" aria-controls="MotherTestimonial">
                                                                Mother Testimonial
                                                            </button>
                                                        </h5>
                                                    </div>
                                                    <div id="MotherTestimonial" class="collapse"
                                                        aria-labelledby="headingMotherTestimonial">
                                                        <div class="card-body">
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Mother_Testimonial_one"
                                                                    name="Mother_Testimonial_one"
                                                                    value="আবেদনকারীর মাতার প্রত্যয়নপত্র আপলোড
                                                                    করতে হবে" />
                                                                <label for="Mother_Testimonial_one">আপলোড</label>
                                                            </div>
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Mother_Testimonial_two"
                                                                    name="Mother_Testimonial_two"
                                                                    value="আবেদনকারীর মাতার প্রত্যয়নপত্র অস্পষ্ট" />
                                                                <label for="Mother_Testimonial_two">অস্পষ্টতা</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- <div  class="reject-update-dropdown-options">
                    <input type="checkbox" class="attach_file" id="Autorised_Certificate" name="Autorised_Certificate"
                    value="কোম্পানীর অনুমোদিত প্রত্যয়নপত্র" />
                    <label for="Autorised_Certificate">Authorised Certificate </label>
                  </div> --}}
                                                <div class="card">
                                                    <div class="card-header" id="headingAuthorisedCertificate">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link collapsed" type="button"
                                                                data-toggle="collapse"
                                                                data-target="#AuthorisedCertificate"
                                                                aria-expanded="false"
                                                                aria-controls="AuthorisedCertificate">
                                                                Authorised Certificate
                                                            </button>
                                                        </h5>
                                                    </div>
                                                    <div id="AuthorisedCertificate" class="collapse"
                                                        aria-labelledby="headingAuthorisedCertificate">
                                                        <div class="card-body">
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Autorised_Certificate_one"
                                                                    name="Autorised_Certificate_one"
                                                                    value="কোম্পানীর অনুমোদিত প্রত্যয়নপত্র
                                                                    আপলোড করতে হবে" />
                                                                <label for="Autorised_Certificate_one">আপলোড</label>
                                                            </div>
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Autorised_Certificate_two"
                                                                    name="Autorised_Certificate_two"
                                                                    value="কোম্পানীর অনুমোদিত প্রত্যয়নপত্র অস্পষ্ট" />
                                                                <label for="Autorised_Certificate_two">অস্পষ্টতা</label>
                                                            </div>
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Autorised_Certificate_three"
                                                                    name="Autorised_Certificate_three"
                                                                    value="প্রতি বছরের কোম্পানীর অনুমোদিত
                                                                    প্রত্যয়নপত্রের কপি দিতে হবে" />
                                                                <label
                                                                    for="Autorised_Certificate_three">মেয়াদউত্তীর্ণ</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- <div  class="reject-update-dropdown-options">
                    <input type="checkbox" class="attach_file" id="Marriage_Certificate_Copy" name="Marriage_Certificate_Copy"
                    value="বিবাহিত সনদপত্র / সন্তানের জন্ম সনদ / কোরো সনদ"/>
                    <label for="Marriage_Certificate_Copy">Marriage Certificate </label>
                  </div> --}}
                                                <div class="card">
                                                    <div class="card-header" id="headingMarriageCertificate">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link collapsed" type="button"
                                                                data-toggle="collapse"
                                                                data-target="#MarriageCertificate" aria-expanded="false"
                                                                aria-controls="MarriageCertificate">
                                                                Marriage Certificate
                                                            </button>
                                                        </h5>
                                                    </div>
                                                    <div id="MarriageCertificate" class="collapse"
                                                        aria-labelledby="headingMarriageCertificate">
                                                        <div class="card-body">
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Marriage_Certificate_Copy_one"
                                                                    name="Marriage_Certificate_Copy_one"
                                                                    value="বিবাহিত সনদপত্র/সন্তানের জন্ম সনদ/কোরো
                                                                    সনদ আপলোড করতে হবে" />
                                                                <label for="Marriage_Certificate_Copy_one">আপলোড</label>
                                                            </div>
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Marriage_Certificate_Copy_two"
                                                                    name="Marriage_Certificate_Copy_two"
                                                                    value="বিবাহিত সনদপত্র/সন্তানের জন্ম সনদ/কোরো
                                                                    সনদ অস্পষ্ট" />
                                                                <label
                                                                    for="Marriage_Certificate_Copy_two">অস্পষ্টতা</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- <div  class="reject-update-dropdown-options">
                      <input type="checkbox" class="attach_file" id="Owner_NID" name="Owner_NID"
                            value="গাড়ির মালিকের জাতীয় পরিচয়পত্র"  />
                      <label for="Owner_NID">Owner NID</label>
                    </div> --}}
                                                <div class="card">
                                                    <div class="card-header" id="headingOwnerNID">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link collapsed" type="button"
                                                                data-toggle="collapse" data-target="#OwnerNID"
                                                                aria-expanded="false" aria-controls="OwnerNID">
                                                                Owner NID
                                                            </button>
                                                        </h5>
                                                    </div>
                                                    <div id="OwnerNID" class="collapse"
                                                        aria-labelledby="headingOwnerNID">
                                                        <div class="card-body">
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Owner_NID_one" name="Owner_NID_one"
                                                                    value="গাড়ির মালিকের জাতীয় পরিচয়পত্রের
                                                                    উভয়পাশের ছবি একত্রে আপলোড করতে
                                                                    হবে" />
                                                                <label for="Owner_NID_one">আপলোড</label>
                                                            </div>
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Owner_NID_two" name="Owner_NID_two"
                                                                    value="গাড়ির মালিকের জাতীয় পরিচয়পত্রের
                                                                    উভয়পাশের ছবি অস্পষ্ট" />
                                                                <label for="Owner_NID_two">অস্পষ্টতা</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-6">

                                                <div class="card">
                                                    <div class="card-header" id="headingVehicleReg">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link collapsed" type="button"
                                                                data-toggle="collapse" data-target="#VehicleReg"
                                                                aria-expanded="false" aria-controls="VehicleReg">
                                                                Vehicle Reg
                                                            </button>
                                                        </h5>
                                                    </div>
                                                    <div id="VehicleReg" class="collapse"
                                                        aria-labelledby="headingVehicleReg">
                                                        <div class="card-body">
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Vehicle_Reg_Copy_one"
                                                                    name="Vehicle_Reg_Copy_one"
                                                                    value=" আবেদনকারীর গাড়ির রেজিষ্ট্রেশন আপলোড
                                                                    করতে হবে" />
                                                                <label for="Vehicle_Reg_Copy_one">আপলোড</label>
                                                            </div>
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Vehicle_Reg_Copy_two"
                                                                    name="Vehicle_Reg_Copy_two"
                                                                    value="আবেদনকারীর গাড়ির রেজিস্ট্রেশন অস্পষ্ট" />
                                                                <label for="Vehicle_Reg_Copy_two">অস্পষ্টতা</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- <div  class="reject-update-dropdown-options">
                      <input type="checkbox" class="attach_file" id="Vehicle_Reg_Copy" name="Vehicle_Reg_Copy"
                      value="গাড়ির রেজিষ্ট্রেশন" />
                      <label for="Vehicle_Reg_Copy">Vehicle Reg </label>
                    </div> --}}

                                                <div class="card">
                                                    <div class="card-header" id="headingTaxToken">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link collapsed" type="button"
                                                                data-toggle="collapse" data-target="#TaxToken"
                                                                aria-expanded="false" aria-controls="TaxToken">
                                                                Tax Token
                                                            </button>
                                                        </h5>
                                                    </div>
                                                    <div id="TaxToken" class="collapse"
                                                        aria-labelledby="headingTaxToken">
                                                        <div class="card-body">
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Tax_Token_Copy_one" name="Tax_Token_Copy_one"
                                                                    value="গাড়ির ট্যাক্স টোকেন আপলোড করতে হবে" />
                                                                <label for="Tax_Token_Copy_one">আপলোড</label>
                                                            </div>
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Tax_Token_Copy_two" name="Tax_Token_Copy_two"
                                                                    value="গাড়ির ট্যাক্স টোকেন অস্পষ্ট" />
                                                                <label for="Tax_Token_Copy_two">অস্পষ্টতা</label>
                                                            </div>
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Tax_Token_Copy_three"
                                                                    name="Tax_Token_Copy_three"
                                                                    value="গাড়ির ট্যাক্স টোকেন এর মেয়াদউত্তীর্ণ" />
                                                                <label for="Tax_Token_Copy_three">মেয়াদউত্তীর্ণ</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- <div  class="reject-update-dropdown-options">
                      <input type="checkbox" class="attach_file" id="Tax_Token_Copy" name="Tax_Token_Copy"
                      value="গাড়ির ট্যাক্স টোকেন" />
                      <label for="Tax_Token_Copy">Tax Token </label>
                    </div> --}}


                                                <div class="card">
                                                    <div class="card-header" id="headingFitnessCertificate">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link collapsed" type="button"
                                                                data-toggle="collapse" data-target="#FitnessCertificate"
                                                                aria-expanded="false"
                                                                aria-controls="FitnessCertificate">
                                                                Fitness Certificate
                                                            </button>
                                                        </h5>
                                                    </div>
                                                    <div id="FitnessCertificate" class="collapse"
                                                        aria-labelledby="headingFitnessCertificate">
                                                        <div class="card-body">
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Fitness_Certificate_Copy_one"
                                                                    name="Fitness_Certificate_Copy_one"
                                                                    value=" গাড়ির ফিটনেস সনদপত্র আপলোড করতে
                                                                    হবে" />
                                                                <label for="Fitness_Certificate_Copy_one">আপলোড</label>
                                                            </div>
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Fitness_Certificate_Copy_two"
                                                                    name="Fitness_Certificate_Copy_two"
                                                                    value=" গাড়ির ফিটনেস সনদপত্র অস্পষ্ট" />
                                                                <label
                                                                    for="Fitness_Certificate_Copy_two">অস্পষ্টতা</label>
                                                            </div>
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Fitness_Certificate_Copy_three"
                                                                    name="Fitness_Certificate_Copy_three"
                                                                    value="গাড়ির ফিটনেস সনদপত্র এর মেয়াদউত্তীর্ণ" />
                                                                <label
                                                                    for="Fitness_Certificate_Copy_three">মেয়াদউত্তীর্ণ</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- <div  class="reject-update-dropdown-options">
                      <input type="checkbox" class="attach_file" id="Fitness_Certificate_Copy" name="Fitness_Certificate_Copy"
                      value="গাড়ির ফিটনেস সনদপত্র" />
                      <label for="Fitness_Certificate_Copy">Fitness Certificate </label>
                    </div> --}}

                                                {{-- ///insurance --}}
                                                {{-- <div  class="reject-update-dropdown-options">
                      <input type="checkbox" class="attach_file" id="Insurance_Certificate_Copy" name="Insurance_Certificate_Copy"
                      value="ইন্সুরেন্স সনদপত্র" />
                      <label for="Insurance_Certificate_Copy">Insurance Certificate</label>
                    </div> --}}

                                                <div class="card">
                                                    <div class="card-header" id="headingWardCommissionerCert">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link collapsed" type="button"
                                                                data-toggle="collapse"
                                                                data-target="#WardCommissionerCert"
                                                                aria-expanded="false"
                                                                aria-controls="WardCommissionerCert">
                                                                Ward Commissioner Cert.
                                                            </button>
                                                        </h5>
                                                    </div>
                                                    <div id="WardCommissionerCert" class="collapse"
                                                        aria-labelledby="headingWardCommissionerCert">
                                                        <div class="card-body">
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Ward_Commissioner_Certificate_one"
                                                                    name="Ward_Commissioner_Certificate_one"
                                                                    value="ওয়ার্ড কমিশনারের সনদপত্র আপলোড করতে
                                                                    হবে" />
                                                                <label
                                                                    for="Ward_Commissioner_Certificate_one">আপলোড</label>
                                                            </div>
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Ward_Commissioner_Certificate_two"
                                                                    name="Ward_Commissioner_Certificate_two"
                                                                    value="ওয়ার্ড কমিশনারের সনদপত্র অস্পষ্ট" />
                                                                <label
                                                                    for="Ward_Commissioner_Certificate_two">অস্পষ্টতা</label>
                                                            </div>
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Ward_Commissioner_Certificate_three"
                                                                    name="Ward_Commissioner_Certificate_three"
                                                                    value="প্রতি বছরের ওয়ার্ড কমিশনারের সনদপত্রের
                                                                    কপি দিতে হবে" />
                                                                <label
                                                                    for="Ward_Commissioner_Certificate_three">মেয়াদউত্তীর্ণ</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- <div  class="reject-update-dropdown-options">
                                                    <input type="checkbox" class="attach_file" id="Ward_Commissioner_Certificate" name="Ward_Commissioner_Certificate"
                                                    value="ওয়ার্ড কমিশনারের সনদপত্র" />
                                                    <label for="Ward_Commissioner_Certificate">Ward Commissioner Cert.</label>
                                                </div> --}}


                                                <div class="card">
                                                    <div class="card-header" id="headingHouseOwnerCert">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link collapsed" type="button"
                                                                data-toggle="collapse" data-target="#HouseOwnerCert"
                                                                aria-expanded="false" aria-controls="HouseOwnerCert">
                                                                House Owner/CEO Cert.
                                                            </button>
                                                        </h5>
                                                    </div>
                                                    <div id="HouseOwnerCert" class="collapse"
                                                        aria-labelledby="headingHouseOwnerCert">
                                                        <div class="card-body">
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="House_Owner_CEO_Certificate_one"
                                                                    name="House_Owner_CEO_Certificate_one"
                                                                    value="বাড়ির মালিক/ক্যান্টনমেন্ট বোর্ড এর সনদপত্র আপলোড করতে হবে" />
                                                                <label for="House_Owner_CEO_Certificate_one">আপলোড</label>
                                                            </div>
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="House_Owner_CEO_Certificate_two"
                                                                    name="House_Owner_CEO_Certificate_two"
                                                                    value=" বাড়ির মালিক/ক্যান্টনমেন্ট বোর্ড এর সনদপত্র অস্পষ্ট" />
                                                                <label
                                                                    for="House_Owner_CEO_Certificate_two">অস্পষ্টতা</label>
                                                            </div>
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="House_Owner_CEO_Certificate_three"
                                                                    name="House_Owner_CEO_Certificate_three"
                                                                    value="প্রতি বছরের ক্যান্টনমেন্ট বোর্ড এর সনদপত্রের কপি দিতে হবে" />
                                                                <label
                                                                    for="House_Owner_CEO_Certificate_three">মেয়াদউত্তীর্ণ</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- <div  class="reject-update-dropdown-options">
                                                <input type="checkbox" class="attach_file" id="House_Owner_CEO_Certificate" name="House_Owner_CEO_Certificate"
                                                value="বাড়ির মালিক / ক্যান্টনমেন্ট বোর্ড  এর সনদপত্র" />
                                                <label for="House_Owner_CEO_Certificate">House Owner/CEO Cert.</label>
                                                </div> --}}

                                                <div class="card">
                                                    <div class="card-header" id="headingJobCertificate">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link collapsed" type="button"
                                                                data-toggle="collapse" data-target="#JobCertificate"
                                                                aria-expanded="false" aria-controls="JobCertificate">
                                                                Job Certificate
                                                            </button>
                                                        </h5>
                                                    </div>
                                                    <div id="JobCertificate" class="collapse"
                                                        aria-labelledby="headingJobCertificate">
                                                        <div class="card-body">
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Job_Certificate_one" name="Job_Certificate_one"
                                                                    value="চাকুরী সনদপত্র আপলোড করতে হবে" />
                                                                <label for="Job_Certificate_one">আপলোড</label>
                                                            </div>
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Job_Certificate_two" name="Job_Certificate_two"
                                                                    value=" চাকুরী সনদপত্র অস্পষ্ট" />
                                                                <label for="Job_Certificate_two">অস্পষ্টতা</label>
                                                            </div>
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Job_Certificate_three"
                                                                    name="Job_Certificate_three"
                                                                    value="প্রতি বছরের চাকুরী সনদপত্রের কপি দিতে
                                                                    হবে" />
                                                                <label for="Job_Certificate_three">মেয়াদউত্তীর্ণ</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- <div  class="reject-update-dropdown-options">
                      <input type="checkbox" class="attach_file" id="Job_Certificate" name="Job_Certificate"
                      value="চাকুরী সনদপত্র" />
                      <label for="Job_Certificate">Job Certificate </label>
                    </div> --}}

                                                <div class="card">
                                                    <div class="card-header" id="headingCivilServiceID">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link collapsed" type="button"
                                                                data-toggle="collapse" data-target="#CivilServiceID"
                                                                aria-expanded="false" aria-controls="CivilServiceID">
                                                                Civil Service ID
                                                            </button>
                                                        </h5>
                                                    </div>
                                                    <div id="CivilServiceID" class="collapse"
                                                        aria-labelledby="headingCivilServiceID">
                                                        <div class="card-body">
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Civil_Service_ID_one"
                                                                    name="Civil_Service_ID_one"
                                                                    value="বেসামরিক সার্ভিস আইডি কার্ডের উভয় পাশের ছবি একত্রে আপলোড করতে হবে" />
                                                                <label for="Civil_Service_ID_one">আপলোড</label>
                                                            </div>
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="Civil_Service_ID_two"
                                                                    name="Civil_Service_ID_two"
                                                                    value="বেসামরিক সার্ভিস আইডি কার্ডের কপি অস্পষ্ট" />
                                                                <label for="Civil_Service_ID_two">অস্পষ্টতা</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- <div  class="reject-update-dropdown-options">
                      <input type="checkbox" class="attach_file" id="Civil_Service_ID" name="Civil_Service_ID"
                      value="বেসামরিক সার্ভিস আইডি কার্ডের কপি" />
                      <label for="Civil_Service_ID">Civil Service ID </label>
                    </div> --}}

                                                <div class="card">
                                                    <div class="card-header" id="headingSchoolCertificate">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link collapsed" type="button"
                                                                data-toggle="collapse" data-target="#SchoolCertificate"
                                                                aria-expanded="false" aria-controls="SchoolCertificate">
                                                                School Certificate
                                                            </button>
                                                        </h5>
                                                    </div>
                                                    <div id="SchoolCertificate" class="collapse"
                                                        aria-labelledby="headingSchoolCertificate">
                                                        <div class="card-body">
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="School_Certificate_one"
                                                                    name="School_Certificate_one"
                                                                    value=" স্কুল সার্টিফিকেটের কপি
                                                                    আপলোড করতে হবে" />
                                                                <label for="School_Certificate_one">আপলোড</label>
                                                            </div>
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="School_Certificate_two"
                                                                    name="School_Certificate_two"
                                                                    value="স্কুল সার্টিফিকেটের কপি অস্পষ্ট" />
                                                                <label for="School_Certificate_two">অস্পষ্টতা</label>
                                                            </div>
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="School_Certificate_three"
                                                                    name="School_Certificate_three"
                                                                    value="প্রতি বছরের স্কুল সার্টিফিকেটের কপি দিতে
                                                                    হবে" />
                                                                <label
                                                                    for="School_Certificate_three">মেয়াদউত্তীর্ণ</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- <div  class="reject-update-dropdown-options">
                      <input type="checkbox" class="attach_file" id="School_Certificate" name="School_Certificate"
                      value="স্কুল সার্টিফিকেটের কপি" />
                      <label for="School_Certificate">School Certificate </label>
                    </div> --}}

                                                <div class="card">
                                                    <div class="card-header" id="headingRoadPermit">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link collapsed" type="button"
                                                                data-toggle="collapse" data-target="#RoadPermit"
                                                                aria-expanded="false" aria-controls="RoadPermit">
                                                                Road Permit
                                                            </button>
                                                        </h5>
                                                    </div>
                                                    <div id="RoadPermit" class="collapse"
                                                        aria-labelledby="headingRoadPermit">
                                                        <div class="card-body">
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="road-permit_one" name="road-permit_one"
                                                                    value="রোড পারমিট আপলোড করতে হবে" />
                                                                <label for="road-permit_one">আপলোড</label>
                                                            </div>
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="road-permit_two" name="road-permit_two"
                                                                    value="রোড পারমিট অস্পষ্ট" />
                                                                <label for="road-permit_two">অস্পষ্টতা</label>
                                                            </div>
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="road-permit_three" name="road-permit_three"
                                                                    value="রোড পারমিট এর মেয়াদউত্তীর্ণ" />
                                                                <label for="road-permit_three">মেয়াদউত্তীর্ণ</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- <div  class="reject-update-dropdown-options">
                      <input type="checkbox" class="other_sms" id="road-permit" name="other_sms"
                            value="road-permit" />
                      <label for="road-permit">Road Permit</label>
                    </div> --}}

                                                <div class="card">
                                                    <div class="card-header" id="headingPoliceVerification">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link collapsed" type="button"
                                                                data-toggle="collapse" data-target="#PoliceVerification"
                                                                aria-expanded="false"
                                                                aria-controls="PoliceVerification">
                                                                Police Verification
                                                            </button>
                                                        </h5>
                                                    </div>
                                                    <div id="PoliceVerification" class="collapse"
                                                        aria-labelledby="headingPoliceVerification">
                                                        <div class="card-body">
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="police-verification_one"
                                                                    name="police-verification_one"
                                                                    value="পুলিশ ভেরিভিকেশন আপলোড করতে হবে" />
                                                                <label for="police-verification_one">আপলোড</label>
                                                            </div>
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="police-verification_two"
                                                                    name="police-verification_two"
                                                                    value="পুলিশ ভেরিভিকেশন অস্পষ্ট" />
                                                                <label for="police-verification_two">অস্পষ্টতা</label>
                                                            </div>
                                                            <div class="reject-update-dropdown-options">
                                                                <input type="checkbox" class="attach_file"
                                                                    id="police-verification_three"
                                                                    name="police-verification_three"
                                                                    value="প্রতি বছরের পুলিশ ভেরিভিকেশন এর কপি
                                                                    দিতে হবে" />
                                                                <label
                                                                    for="police-verification_three">মেয়াদউত্তীর্ণ</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- <div  class="reject-update-dropdown-options">
                      <input type="checkbox" class="other_sms" id="police-verification" name="other_sms"
                            value="police-verification" />
                      <label for="police_verification">Police Verification </label>
                    </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                        <div hidden id="custom_reason" class="col-md-12">
                            <div class="row justify-content-center">
                                <div class="col-md-10">
                                    <form id="reject_sms_form">
                                        <div class="row justify-content-center">
                                            <div class="col-md-3 offset-md-1">
                                                <label for="" class="label-form">SMS
                                                    Template</label><span>*</span> <br>
                                                <small></small>
                                            </div>
                                            <div class="col-md-7">
                                                <select name="sms_template select2" data-sms="{{ $all_sms }}"
                                                    class="form-control in-form sms_template">
                                                    <option value="">Select SMS Template</option>
                                                    @if (isset($all_sms) && count($all_sms) > 0)
                                                        @foreach ($all_sms as $sms)
                                                            <option value="{{ $sms->id }}">
                                                                {{ $sms->sms_template_name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col-md-3 offset-md-1">
                                                <label for="" class="label-form">Subject</label><span>*</span>
                                                <br>
                                                <small></small>
                                            </div>
                                            <div class="col-md-7">
                                                <input type="text" name="sms_subject"
                                                    class="form-control in-form sms_subject" value="">
                                                <input type="hidden" name="sms_id"
                                                    class="form-control in-form sms_id" value="">
                                            </div>
                                            <div class="col-md-3 offset-md-1">
                                                <label for="" class="label-form">Message</label><span>*</span>
                                                <br>
                                                <small></small>
                                            </div>
                                            <div class="col-md-7 mb-4">
                                                <textarea type="text" rows='5' name="sms_text" class="form-control in-form sms_text"></textarea>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" data-number="{{ $app->app_number }}" class="btn btn-primary"
                        id="reject_App">Confirm Reject</button>
                </div>
            </div>
        </div>
    </div>
    <!-- The Issue Modal -->

    <div class="modal fade" id="myIssueModal" tabindex="-1" role="dialog" aria-labelledby=""
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #4785c7; padding: 10px 0;">
                    <legend style="color:#fff; text-align: center; ">Issue Vehicle Sticker</legend>
                    <button style="color: #fff;" type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true" style="padding:10px 10px 0 0;">&times;</span>
                    </button>
                </div>
                <form id="issueSticker_Form">
                    {{ csrf_field() }}
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-4 offset-md-1">
                                <label for="" class="label-form">Applicant Name</label><span></span> <br>
                                <small></small>
                            </div>
                            <div class="col-md-7">
                                {{ $app->applicant->name }}
                            </div>
                            <div class="col-md-4 offset-md-1">
                                <label for="" class="label-form">Phone Number</label><span></span> <br>
                                <small></small>
                            </div>
                            <div class="col-md-7">
                                {{ $app->applicant->phone }}
                            </div>
                            <div class="col-md-4 offset-md-1">
                                <label for="" class="label-form">Sticker Type</label><span></span> <br>
                                <small></small>
                            </div>
                            <div class="col-md-7">
                                {{ $app->sticker_category }}
                            </div>
                            <div class="col-md-4 offset-md-1">
                                <label for="" class="label-form">Vehicle Type</label><span></span> <br>
                                <small></small>
                            </div>
                            <div class="col-md-7">
                                {{ !empty($app->vehicleinfo->vehicleType->name) ? $app->vehicleinfo->vehicleType->name : '' }}
                            </div>
                            <div class="col-md-4 offset-md-1">
                                <label for="" class="label-form">Vehicle Reg. Number</label><span></span> <br>
                                <small></small>
                            </div>
                            <div class="col-md-7">
                                {{ !empty($app->vehicleinfo->reg_number) ? $app->vehicleinfo->reg_number : '' }}
                            </div>
                            <div class="col-md-4 offset-md-1">
                                <label for="" class="label-form">Sticker Number</label> <span
                                    style="color:red;">*</span> <br>
                                <small></small>
                            </div>
                            <div class="col-md-7">
                                <input type="text" id="sticker_number" value=" {{ $app->sticker_category ?? ''}}-{{ $sticker_number ?? '' }}" name="sticker_number"
                                    class="form-control in-form" placeholder="" required>
                            </div>
                            <div class="col-md-4 offset-md-1">
                                <label for="" class="label-form">Sticker Issue Date</label> <span
                                    style="color:red;">*</span> <br>
                                <small></small>
                            </div>
                            <div class="col-md-7">
                                <input type="date" id="issue_sticker_date" value=""
                                    name="issue_sticker_date" class="form-control in-form" placeholder="" required>

                            </div>

                            @php
                                $exp_date = null;
                                // Prefer the $stickerCategory variable passed from controller; fall back to $app relationship
                                $duration = $stickerCategory->duration ?? ($app->stickerCategory->duration ?? null);
                                if (!empty($duration)) {
                                    $current_year_lastdate = date('Y-m-d', strtotime('12/31'));
                                    $exp_date = date('Y-m-d', strtotime('+' . ($duration - 1) . 'year', strtotime($current_year_lastdate)));
                                }
                            @endphp
                            <div class="col-md-4 offset-md-1">
                                <label for="" class="label-form">Expired Date</label> <span
                                    style="color:red;">*</span> <br>
                                <small></small>
                            </div>
                            <div class="col-md-7">
                                <input type="date" id="sticker_exp_date"
                                    value="{{ !empty($exp_date) ? $exp_date : '' }}" name="sticker_exp_date"
                                    class="form-control in-form" placeholder="" required readonly
                                    style=" cursor:no-drop;">
                                <input type="hidden" id="sticker_duration" value="{{ $duration ?? '' }}">
                                <input type="hidden" value="{{ $app->app_number }}" name="app_number"
                                    class="form-control in-form" placeholder="">
                            </div>
                            <div class="col-md-4 offset-md-1">
                                <label for="" class="label-form">Amount</label> <span
                                    style="color:red;">*</span> <br>
                                <small></small>
                            </div>
                            <div class="col-md-7">
                                <input type="text" value="{{ $stickerPrice }}" name="amount" id="amount"
                                    class="form-control in-form" placeholder="" required readonly
                                    style=" cursor:no-drop;">
                            </div>

                            @if ($app->issue_type == 'free')
                                @php
                                    $stickerPrice = 0;
                                @endphp

                                <div class="col-md-4 offset-md-1 special_case" hidden>
                                    <label for="" class="label-form">Discount Type</label> <br>
                                    <small></small>
                                </div>
                                <div class="col-md-7 special_case" hidden>
                                    <button class="btn btn-warning">Free</button>
                                </div>
                            @endif
                            <div class="col-md-4 offset-md-1">
                                <label for="" class="label-form">Total Amount</label> <span
                                    style="color:red;">*</span> <br>
                                <small></small>
                            </div>

                            <div class="col-md-7">
                                <input type="number" value="{{ $stickerPrice }}" name="total_amount"
                                    class="form-control in-form" id="total_amount" placeholder="" required readonly
                                    style=" cursor:no-drop;">
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="confirm_sticker">Confirm Sticker</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Notify Modal -->

    <script>
        (function () {
            // Helper to format Date object to YYYY-MM-DD
            function formatDate(d) {
                if (!d || isNaN(d.getTime())) return ''; 
                var mm = d.getMonth() + 1; // getMonth() is zero-based
                var dd = d.getDate();
                return d.getFullYear() + '-' + (mm < 10 ? '0' + mm : mm) + '-' + (dd < 10 ? '0' + dd : dd);
            }

            var durationEl = document.getElementById('sticker_duration');
            var issueEl = document.getElementById('issue_sticker_date');
            var expiryEl = document.getElementById('sticker_exp_date');

            var duration = durationEl ? parseInt(durationEl.value || 0) : 0;

            function calcExpiry(issueDateStr) {
                if (!duration || !issueDateStr) {
                    if (expiryEl) expiryEl.value = '';
                    return;
                }

                var issue = new Date(issueDateStr);
                if (isNaN(issue.getTime())) return;

                // use the issue date year, set expiry to Dec 31 of that year then add (duration-1) years
                var year = issue.getFullYear();
                var expiry = new Date(year, 11, 31); // Dec 31
                expiry.setFullYear(expiry.getFullYear() + (duration - 1));
                if (expiryEl) expiryEl.value = formatDate(expiry);
            }

            if (issueEl && expiryEl) {
                issueEl.addEventListener('change', function (e) {
                    calcExpiry(this.value);
                });

                // initialize
                calcExpiry(issueEl.value);
            }
        })();
    </script>
    <div class="modal fade" id="notify_user_modal" tabindex="-1" role="dialog" aria-labelledby=""
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #4785c7; padding: 10px 0;">
                    <legend style="color:#fff; text-align: center; ">Send Sms To Notify User </legend>
                    <button style="color: #fff;" type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true" style="padding:10px 10px 0 0;">&times;</span>
                    </button>
                </div>
                <form id="sendSms_Form">
                    {{ csrf_field() }}
                    <div class="modal-body">

                        <div class="row">

                            <div class="col-md-4 offset-md-1">
                                <label for="" class="label-form">SMS Template</label><span>*</span> <br>
                                <small></small>
                            </div>
                            <div class="col-md-7">
                                <select name="sms_template select2" data-sms="{{ $all_sms }}" id="sms_template"
                                    class="form-control in-form sms_template">
                                    <option value="" selected="">Select SMS Template</option>
                                    @if (isset($all_sms) && count($all_sms) > 0)
                                        @foreach ($all_sms as $sms)
                                            <option value="{{ $sms->id }}">{{ $sms->sms_template_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-4 offset-md-1">
                                <label for="" class="label-form">Subject</label><span>*</span> <br>
                                <small></small>
                            </div>
                            <div class="col-md-7">
                                <input type="text" name="sms_subject" id="sms_subject"
                                    class="form-control in-form sms_subject" value="">
                            </div>
                            <div class="col-md-4 offset-md-1">
                                <label for="" class="label-form">Message</label><span>*</span> <br>
                                <small></small>
                            </div>
                            <div class="col-md-7">
                                <textarea type="text" rows='5' id="sms_text" name="sms_text" class="form-control in-form sms_text"></textarea>
                            </div>
                            <!-- <input type="hidden" name="app_phone_num" id="app_phone_num" class="form-control in-form" value="{{ $app->applicant->phone }}"> -->
                            <input type="hidden" name="app_email" id="app_email" class="form-control in-form"
                                value="{{ $app->applicant->email }}">
                            <input type="hidden" name="app_id" id="app_id" class="form-control in-form"
                                value="{{ $app->id }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="confirm_sms">Send SMS</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Applicant Photo -->
    <div class="modal fade" id="applicantPhotoModal" tabindex="-1" role="dialog" aria-labelledby="modal1Label"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    Applicant's Photo
                    <button type="button" class="close" data-dismiss="modal"><span
                            aria-hidden="true">&times;</span><span class="sr-only">Close</span>

                    </button>

                </div>
                <div class="modal-body" style="padding:0;">
                    <img src="{{ !empty($app->applicant->applicantDetail->applicant_photo) ? url($app->applicant->applicantDetail->applicant_photo) : '' }}"
                        class="img-fluid" alt="">
                </div>
            </div>
        </div>
    </div>

    <!-- End App Photo Modal -->
    <!-- Driver Photo -->
    <div class="modal fade" id="DriverPhotoModal" tabindex="-1" role="dialog" aria-labelledby="modal1Label"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    Driver's Photo
                    <button type="button" class="close" data-dismiss="modal"><span
                            aria-hidden="true">&times;</span><span class="sr-only">Close</span>

                    </button>

                </div>
                <div class="modal-body" style="padding:0;">
                    @if (!empty($app->driverinfo->photo) && empty($app->driverinfo->driver_is_owner))
                        <img src="{{ !empty($app->driverinfo->photo) ? url($app->driverinfo->photo) : '' }}"
                            class="img-fluid" alt="">
                    @elseif(empty($app->driverinfo->photo) &&
                            !empty($app->driverinfo->driver_is_owner) &&
                            $app->driverinfo->driver_is_owner == '1')
                        <img src="{{ !empty($app->applicant->applicantDetail->applicant_photo) ? url($app->applicant->applicantDetail->applicant_photo) : '' }}"
                            class="img-fluid" alt="">
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- End Driver Photo Modal -->

    <!-- Verify Modal -->

    <div class="modal fade" id="verifyModal" tabindex="-1" role="dialog" aria-labelledby=""
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #4785c7; padding: 10px 0;">
                    <legend style="color:#fff; text-align: center; ">Verify Application</legend>
                    <button style="color: #fff;" type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true" style="padding:10px 10px 0 0;">&times;</span>
                    </button>
                </div>
                <form id="verify_App_frm" action={{ route('admin.verify.application') }}>
                    {{ csrf_field() }}
                    <div class="modal-body" style="padding:0;">

                        <div class="row">
                            <div class="col-md-4 offset-md-1">
                                <label for="" class="label-form"> Sticker Type </label> <span>*</span> <br>
                                <small></small>
                            </div>
                            @php
                                $re = !empty($app->vehicleinfo->reg_number) ? $app->vehicleinfo->reg_number : '';
                                    $vehicle_type = !empty($app->vehicleinfo->vehicleType->type) ? $app->vehicleinfo->vehicleType->type : '';
                                    $stickerk = App\Models\ETypeStickerRegisterNumber::where('reg_number', $re)->first();
                                    
                                    $sticker_categories = App\Models\StickerCategory::query();
                                 

                                    if ($vehicle_type == 'e') {
                                        $sticker_categories = $sticker_categories->where('id', $stickerk->sticker_category_id ?? '');
                                    }

                                    if ($vehicle_type !== 'e') {
                                        
                                        $sticker_categories = $sticker_categories->where('type', '!=', 'e')->orWhereNull('type');
                                    }

                                    $sticker_categories = $sticker_categories->get();
                                
                            @endphp

                            <div class="col-md-7">
                                <select name="sticker_type" id="sticker_type" class="form-control in-form mandatory"
                                    required>
                                    {{-- <option selected disabled value=""> --Select One--</option> --}}

                                    @if (!$stickerk)
                                        <option selected disabled value=""> --Select One--</option>
                                    @endif
                                   @foreach ($sticker_categories as $sticker_categorie)
                                        <option value="{{ $sticker_categorie->value }}"
                                            {{ $sticker_categorie->value == $app->sticker_category ? 'selected' : '' }}>
                                            {{ $sticker_categorie->name }}</option>
                                    @endforeach

                                </select>
                                <div id="err_msg_sticker_type" class="" style="color:#bd2130;" hidden> <i
                                        class="fas fa-exclamation-triangle"></i> <span id="err_sticker_type"> </span>
                                </div>
                            </div>
                            <div class="col-md-4 offset-md-1">
                                <label for="" class="label-form"> Verify Date </label> <span>*</span> <br>
                                {{-- <label for="" class="label-form"> Delivery Date </label> <span>*</span> <br> --}}
                                <small></small>
                            </div>
                            <div class="col-md-7">
                                <input type="date" id="sticker_verify_date" value=""
                                    name="sticker_verify_date"   value="{{ $app->applicationNotify->sticker_verify_date ?? '' }}" class="form-control in-form" placeholder="" required>
                                <div id="err_msg_delDate" class="" style="color:#bd2130;" hidden> <i
                                        class="fas fa-exclamation-triangle"></i> <span id="err_delDate"> </span>
                                </div>
                            </div>

                        </div>

                    </div>
                    <input type="hidden" name="app_num" value="{{ $app->app_number }}">
                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" data-number="{{ $app->app_number }}"
                            class="btn btn-primary custm-btn" id="verify_App">Confirm Verify</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Verify Modal -->
    <!-- delivery Modal -->

    <div class="modal fade" id="myDeliveryeModal" tabindex="-1" role="dialog" aria-labelledby=""
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #4785c7; padding: 10px 0;">
                    <legend style="color:#fff; text-align: center; ">Delivery Application</legend>
                    <button style="color: #fff;" type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true" style="padding:10px 10px 0 0;">&times;</span>
                    </button>
                </div>
                <form id="delivery_App_frm" action={{ route('admin.delivery.application') }}>
                    {{ csrf_field() }}
                    <div class="modal-body" style="padding:0;">

                        <div class="row">
                            <div class="col-md-4 offset-md-1">
                                <label for="" class="label-form"> Sticker Type </label> <span>*</span> <br>
                                <small></small>
                            </div>
                            @php
                                $re = !empty($app->vehicleinfo->reg_number) ? $app->vehicleinfo->reg_number : '';
                                    $vehicle_type = !empty($app->vehicleinfo->vehicleType->type) ? $app->vehicleinfo->vehicleType->type : '';
                                    $stickerk = App\Models\ETypeStickerRegisterNumber::where('reg_number', $re)->first();
                                    
                                    $sticker_categories = App\Models\StickerCategory::query();                                   

                                    if ($vehicle_type == 'e') {
                                        $sticker_categories = $sticker_categories->where('id', $stickerk->sticker_category_id ?? '');
                                    }

                                    if ($vehicle_type !== 'e') {
                                        
                                        $sticker_categories = $sticker_categories->where('type', '!=', 'e')->orWhereNull('type');
                                    }

                                    $sticker_categories = $sticker_categories->get();
                                
                            @endphp

                            <div class="col-md-7">
                                <select name="sticker_type" id="sticker_type" class="form-control in-form mandatory sticker_type-delivery"
                                    required>
                                    {{-- <option selected disabled value=""> --Select One--</option> --}}

                                    @if (!$stickerk)
                                        <option selected disabled value=""> --Select One--</option>
                                    @endif
                                   @foreach ($sticker_categories as $sticker_categorie)
                                        <option value="{{ $sticker_categorie->value }}"
                                            {{ $sticker_categorie->value == $app->sticker_category ? 'selected' : '' }}>
                                            {{ $sticker_categorie->name }}</option>
                                    @endforeach

                                </select>
                                <div id="err_msg_sticker_type" class="" style="color:#bd2130;" hidden> <i
                                        class="fas fa-exclamation-triangle"></i> <span id="err_sticker_type"> </span>
                                </div>
                            </div>
                            <div class="col-md-4 offset-md-1">
                                <label for="" class="label-form"> Delivery Date </label> <span>*</span> <br>
                                
                                <small></small>
                            </div>
                            <div class="col-md-7">
                                <input type="date" id="sticker_delivery_date" 
                                    name="sticker_delivery_date"   value="{{ $app->applicationNotify->sticker_delivery_date ?? '' }}" class="form-control in-form" placeholder="" required>
                                <div id="err_msg_delDate" class="" style="color:#bd2130;" hidden> <i
                                        class="fas fa-exclamation-triangle"></i> <span id="err_delDate"> </span>
                                </div>
                            </div>  
                           
                                                    
                            <div class="col-md-4 offset-md-1">
                                <label for="" class="label-form"> Sticker For Year </label> <span>*</span> <br>
                                
                                <small></small>
                            </div>
                            <div class="col-md-7">
                                <input type="text" id="sticker_for_year"
                                    name="sticker_for_year"  value="{{ date('Y') }}" class="form-control in-form" placeholder="" required>
                                <input type="hidden" id="year"
                                    name="year"  value="{{ date('Y') }}" class="form-control in-form" placeholder="" required>
                                <div id="err_msg_delDate" class="" style="color:#bd2130;" hidden> <i
                                        class="fas fa-exclamation-triangle"></i> <span id="err_delDate"> </span>
                                </div>
                            </div>
                            <div class="col-md-4 offset-md-1">
                                <label for="" class="label-form"> Sticker Number </label> <span>*</span> <br>
                                
                                <small></small>
                            </div>
                            <div class="col-md-7">
                                <input type="text" id="sticker_number"
                                    name="sticker_number"   value="" class="form-control in-form delivery-sticker-number" placeholder="" required>
                                <div id="err_msg_delDate" class="" style="color:#bd2130;" hidden> <i
                                        class="fas fa-exclamation-triangle"></i> <span id="err_delDate"> </span>
                                </div>
                            </div>

                        </div>

                    </div>
                    <input type="hidden" name="app_num" value="{{ $app->app_number }}">
                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" data-number="{{ $app->app_number }}"
                            class="btn btn-primary custm-btn" id="delivery_App">Confirm Delivery</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- delivery Modal -->


@endsection


@section('admin-script')
    <link href="{{ asset('assets/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">

    <script src="{{ asset('assets/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/assets/admins/js/admin-script.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/assets/admins/js/review-blade.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/assets/admins/js/application-verify.js') }}"></script>
    <style>
        .img-magnifier-container {
            position: relative;
        }

        .img-magnifier-glass {
            position: absolute;
            border: 3px solid #000;
            border-radius: 50%;
            cursor: none;
            /*Set the size of the magnifier glass:*/
            width: 150px;
            height: 150px;
        }
        .sticker_type-delivery{
            pointer-events: none;
            cursor: not-allowed;
            background-color: #e9ecef;
        }
    </style>
    <script>
        function magnify(imgID, zoom) {
            var img, glass, w, h, bw;
            img = document.getElementById(imgID);
            if (img != null) {
                /*create magnifier glass:*/
                glass = document.createElement("DIV");
                glass.setAttribute("class", "img-magnifier-glass");
                /*insert magnifier glass:*/
                img.parentElement.insertBefore(glass, img);
                /*set background properties for the magnifier glass:*/
                glass.style.backgroundImage = "url('" + img.src + "')";
                glass.style.backgroundRepeat = "no-repeat";
                glass.style.backgroundSize = (img.width * zoom) + "px " + (img.height * zoom) + "px";
                bw = 3;
                w = glass.offsetWidth / 2;
                h = glass.offsetHeight / 2;
                /*execute a function when someone moves the magnifier glass over the image:*/
                glass.addEventListener("mousemove", moveMagnifier);
                img.addEventListener("mousemove", moveMagnifier);
                /*and also for touch screens:*/
                glass.addEventListener("touchmove", moveMagnifier);
                img.addEventListener("touchmove", moveMagnifier);

                function moveMagnifier(e) {
                    var pos, x, y;
                    /*prevent any other actions that may occur when moving over the image*/
                    e.preventDefault();
                    /*get the cursor's x and y positions:*/
                    pos = getCursorPos(e);
                    x = pos.x;
                    y = pos.y;
                    /*prevent the magnifier glass from being positioned outside the image:*/
                    if (x > img.width - (w / zoom)) {
                        x = img.width - (w / zoom);
                    }
                    if (x < w / zoom) {
                        x = w / zoom;
                    }
                    if (y > img.height - (h / zoom)) {
                        y = img.height - (h / zoom);
                    }
                    if (y < h / zoom) {
                        y = h / zoom;
                    }
                    /*set the position of the magnifier glass:*/
                    glass.style.left = (x - w) + "px";
                    glass.style.top = (y - h) + "px";
                    /*display what the magnifier glass "sees":*/
                    glass.style.backgroundPosition = "-" + ((x * zoom) - w + bw) + "px -" + ((y * zoom) - h + bw) + "px";
                }

                function getCursorPos(e) {
                    var a, x = 0,
                        y = 0;
                    e = e || window.event;
                    /*get the x and y positions of the image:*/
                    a = img.getBoundingClientRect();
                    /*calculate the cursor's x and y coordinates, relative to the image:*/
                    x = e.pageX - a.left;
                    y = e.pageY - a.top;
                    /*consider any page scrolling:*/
                    x = x - window.pageXOffset;
                    y = y - window.pageYOffset;
                    return {
                        x: x,
                        y: y
                    };
                }
            }
        }
    </script>
    <script>
        $(document).ready(function() {
            // magnify('docImg1',3);
            // magnify('docImg2',3);
            // magnify('docImg3',3);
            // magnify('docImg4',3);
            // magnify('docImg5',3);
            // magnify('docImg6',3);
            // magnify('docImg7',3);
            // magnify('docImg8',3);
            // magnify('docImg9',3);
            // magnify('docImg10',3);
            // magnify('docImg11',3);
            // magnify('docImg12',3);
            // magnify('docImg13',3);
            // magnify('docImg14',3);
            // magnify('docImg16',3);
            // magnify('docImg17',3);
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const select = document.getElementById('sticker_type');
            const priceInput = document.getElementById('sticker_amount');

            if (select && priceInput) {
             function updatePrice() {
                const selectedOption = select.options[select.selectedIndex];
                const price = selectedOption.getAttribute('data-price');
                priceInput.value = price ? price + ' ৳' : '';
            }

            select.addEventListener('change', updatePrice);

            // Set price initially on page load
            updatePrice();
            } else {
            // console.warn('sticker_type or sticker_amount element not found.');
        }
    });
    $(function(){
        const today = new Date().toISOString().split('T')[0];
        $('#sticker_delivery_date').val(today);

        $('#myDeliveryeModal').on('shown.bs.modal', function () {
            $('#sticker_for_year').focus();

            // Function to generate sticker number
            function generateStickerNumber() {
                const year = $('#sticker_for_year').val();
                const stickerType = $('#sticker_type').val();

                // Validate inputs
                if(!year || !stickerType) {
                    console.log('Year or sticker type is missing');
                    return;
                }

                $.ajax({
                    url: "{{route('admin.latest-serial')}}",
                    method: 'GET',
                    data: {year: year, sticker_category: stickerType},
                    success: function(res){
                        
                        if(res && res.serial) {
                            $('#year').val(res.year); 
                            const stickerSerial = `${stickerType}-${res.serial}`;
                            $('.delivery-sticker-number').val(stickerSerial);
                               $('#sticker_number').focus(); 
                        } else {
                            $('.delivery-sticker-number').val('');
                        }
                    },
                    error: function(e){
                        $('.delivery-sticker-number').val('');
                    }
                });
            }

            // Trigger on year blur
            $('#sticker_for_year').off('blur').on('blur', generateStickerNumber);
        });
    });
    </script>
@endsection
