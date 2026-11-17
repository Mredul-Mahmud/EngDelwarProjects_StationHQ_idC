@extends('layouts.admin-master')
@section('admin-sidebar')
    @include('layouts.admin-sidebar')
@endsection
@section('content')
    <div class="content-area" style="padding-top: 15px;">
        <div class="container-fluid pl-0 pr-0">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @elseif (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @elseif (session('info'))
                <div class="alert alert-info">
                    {{ session('info') }}
                </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <form id="filterForm" action="{{ route('new-payment-report.list') }}" method="GET">
                        <table>
                            <tr>
                                <td>
                                    <input type="text" name="applicant_name" value="{{ $applicant_name }}"
                                        class="form-control" placeholder="Name">
                                </td>
                                <td>
                                    <input type="text" name="ba" value="{{ $ba }}" class="form-control"
                                        placeholder="BA">
                                </td>
                                <td>
                                    <input type="text" name="rank" value="{{ $rank }}" class="form-control"
                                        placeholder="Rank">
                                </td>
                                <td>
                                    <input type="text" name="reg_no" value="{{ $reg_no }}" class="form-control"
                                        placeholder="Reg. No.">
                                </td>
                                <td>
                                    <input type="text" name="phone" value="{{ $phone }}" class="form-control"
                                        placeholder="Phone">
                                </td>

                                <td>
                                    <input type="text" name="Vehicle_Type" value="{{ $Vehicle_Type }}"
                                        class="form-control" placeholder="Vehicle Type">
                                </td>

                                <td>
                                    <select name="sticker_type" id="sticker_type" class="form-control">
                                        <option value=""> Sticker Type </option>
                                        @if (isset($sticker_categories))
                                            @foreach ($sticker_categories as $stickerType)
                                                <option value="{{ $stickerType->value }}"> {{ $stickerType->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>

                                <td>
                                    <input type="text" name="present_address" value="{{ $present_address }}"
                                        class="form-control" placeholder="Address">
                                </td>

                            </tr>
                        </table>
                        <div class="row mb-3">
                            <div class="col-md-2">
                                <label class="font-weight-bold mb-1">Approved From Date:</label>
                                <input class="form-control form-control-sm" type="date" name="approve_from_date"
                                    placeholder="dd-mm-yy" value="{{ $approve_from_date ?? '' }}" autocomplete="off">
                            </div>
                            <div class="col-md-2">
                                <label class="font-weight-bold mb-1">Approved To Date:</label>
                                <input class="form-control form-control-sm" type="date" name="approve_to_date"
                                    placeholder="dd-mm-yy" value="{{ $approve_to_date ?? '' }}" autocomplete="off">
                            </div>

                            <div class="col-md-2">
                                <label class="font-weight-bold mb-1">Payment From Date:</label>
                                <input class="form-control form-control-sm" type="date" name="payment_from_date"
                                    placeholder="dd-mm-yy" value="{{ $payment_from_date ?? '' }}" autocomplete="off">
                            </div>
                            <div class="col-md-2">
                                <label class="font-weight-bold mb-1">Payment To Date:</label>
                                <input class="form-control form-control-sm" type="date" name="payment_to_date"
                                    placeholder="dd-mm-yy" value="{{ $payment_to_date ?? '' }}" autocomplete="off">
                            </div>


                        </div>
                        <button type="submit" class="btn btn-info"> Search </button>
                    </form>
                </div>
            </div>

            <div class="mt-3">



                <button id='' data-toggle="modal" data-target="#myDeliveryeModal" class="btn btn-success"></i>All
                    Delivery</button>

                <!-- delivery Modal -->

                <div class="modal fade" id="myDeliveryeModal" tabindex="-1" role="dialog" aria-labelledby=""
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color: #4785c7; padding: 10px 0;">
                                <legend style="color:#fff; text-align: center; ">All Application Delivery</legend>
                                <button style="color: #fff;" type="button" class="close" data-dismiss="modal"
                                    aria-label="Close">
                                    <span aria-hidden="true" style="padding:10px 10px 0 0;">&times;</span>
                                </button>
                            </div>
                            <form id="delivery_App_frm" action="{{ route('admin.applicationDelivery') }}">
                                {{ csrf_field() }}
                                <div class="modal-body" style="padding:0;">
                                    {{-- <div class="alert alert-info" style="margin: 10px;">
                                        <strong>Smart Delivery:</strong> Applications will be automatically selected based on your current search criteria.
                                        <small class="d-block mt-1">
                                            <span id="delivery-info">Loading...</span>
                                        </small>
                                    </div> --}}
                                    <div class="row">
                                        <div class="col-md-4 offset-md-1">
                                            <label for="" class="label-form"> Delivery Date </label>
                                            <span>*</span> <br>

                                            <small></small>
                                        </div>
                                        <div class="col-md-7">
                                            <input type="date" id="sticker_delivery_date" class="form-control in-form"
                                                name="sticker_delivery_date" value="{{ old('sticker_delivery_date') }}"
                                                required>
                                            <div id="err_msg_delDate" class="" style="color:#bd2130;" hidden> <i
                                                    class="fas fa-exclamation-triangle"></i> <span id="err_delDate">
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 offset-md-1">
                                            <label for="" class="label-form"> Sticker For Year </label>
                                            <span>*</span> <br>

                                            <small></small>
                                        </div>
                                        <div class="col-md-7">
                                            <input type="text" id="sticker_for_year" name="sticker_for_year"
                                                value="{{ date('Y') }}" class="form-control in-form" placeholder=""
                                                required>
                                            <div id="err_msg_delDate" class="" style="color:#bd2130;" hidden> <i
                                                    class="fas fa-exclamation-triangle"></i> <span id="err_delDate">
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer mt-3">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    <button type="submit" data-number="" class="btn btn-primary custm-btn"
                                        id="delivery_App">Confirm Delivery</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- delivery Modal -->
            </div>
            <!-- delivery Modal -->
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="pptitle">New Payment & Approved Applications</h3>
            </div>
            <div class="panel-body" style="padding:10px 0;">
                <div id="example-wrapper-2" class="table-responsive">

                    <table id="table" class="table table-bordered dt-responsive"
                        style="text-align: center; width:95%;">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name, NID</th>
                                <th scope="col">Type</th>
                                <th scope="col">Personal No</th>
                                <th scope="col">Rank</th>
                                <th scope="col">Reg. No.</th>
                                <th scope="col">Phone No.</th>
                                <th scope="col">Date</th>
                                <th scope="col">Vehicle Type</th>
                                <!-- <th scope="col">Glass Type</th> -->
                                <th scope="col">Sticker Type</th>

                                <th scope="col">Approved Date</th>
                                <th scope="col">Payment Date</th>
                                <th scope="col">Payment Status</th>
                                <th scope="col">Ammount</th>
                                <th scope="col">Action</th>
                                <!-- <th scope="col">Present Address</th> -->
                            </tr>
                        </thead>
                    </table>
                </div>




            </div>

        </div>


    </div>


@endsection

@section('admin-script')
    <script>
        $(function() {
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                aLengthMenu: [
                    [10, 25, 50, 100, 200, -1],
                    [10, 25, 50, 100, 200, "All"]
                ],
                iDisplayLength: 10,
                ajax: {
                    url: '{{ route('new_approved_payment_report.datatable') }}',
                    data: function(d) {
                        // Get current form values
                        var formData = $('#filterForm').serializeArray();
                        $.each(formData, function(i, field) {
                            d[field.name] = field.value;
                        });
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'id',
                        searchable: false
                    },
                    {
                        data: 'applicant_name',
                        name: 'applicant.name'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'BA_no',
                        name: 'BA_no'
                    },
                    {
                        data: 'Rank_id',
                        name: 'Rank_id'
                    },
                    {
                        data: 'Reg_number',
                        name: 'vehicleinfo.reg_number'
                    },
                    {
                        data: 'phone_number',
                        name: 'applicant.phone'
                    },
                    {
                        data: 'app_date',
                        name: 'app_date',
                        type: 'date-dd-mmm-yyyy',
                        targets: 0
                    },
                    {
                        data: 'vehicleType',
                        name: 'vehicleinfo.vehicleType.name'
                    },
                    // {
                    //     data: 'glass_type',
                    //     name: 'glass_type'
                    // },
                    {
                        data: 'sticker_category',
                        name: 'sticker_category'
                    },
                    {
                        data: 'sticker_approve_date',
                        name: 'sticker_approve_date'
                    },
                    {
                        data: 'payment_date',
                        name: 'payment_date'
                    },
                    {
                        data: 'payment_status',
                        name: 'payment_status'
                    },
                    {
                        data: 'credit',
                        name: 'credit'
                    },
                    // {
                    //     data: 'address',
                    //     name: 'address'
                    // },
                    {
                          data: 'action',
                          name: 'action'
                     },
                ],
                order: [
                    [0, "desc"]
                ]
            });

            // Handle form submission for filtering
            $('#filterForm').on('submit', function(e) {
                e.preventDefault();

                // Reload DataTable with new filter data
                table.ajax.reload();
            });

            // Handle All Delivery button click
            $('button[data-target="#myDeliveryeModal"]').on('click', function() {
                // Get current search parameters
                var formData = $('#filterForm').serializeArray();
                var hasSearchCriteria = false;
                var searchCriteriaList = [];

                // Check if there are any search criteria
                $.each(formData, function(i, field) {
                    if (field.value && field.value.trim() !== '') {
                        hasSearchCriteria = true;
                        searchCriteriaList.push(field.name + ': ' + field.value);
                    }
                });

                // Determine delivery option automatically
                var deliveryOption = hasSearchCriteria ? 'searched' : 'all';
                var deliveryInfo = '';

                if (hasSearchCriteria) {
                    deliveryInfo = 'Will deliver only applications matching your search criteria (' +
                        searchCriteriaList.length + ' filters applied)';
                } else {
                    deliveryInfo = 'No search filters applied - will deliver all approved applications';
                }

                // Update the info text
                $('#delivery-info').text(deliveryInfo);

                // Add hidden inputs to delivery form
                $('#delivery_App_frm').find('input[name^="search_"]')
                    .remove(); // Remove existing search params

                // Add delivery option
                $('#delivery_App_frm').append('<input type="hidden" name="delivery_option" value="' +
                    deliveryOption + '">');

                // Add search parameters
                $.each(formData, function(i, field) {
                    if (field.value && field.value.trim() !== '') {
                        $('#delivery_App_frm').append('<input type="hidden" name="' + field.name +
                            '" value="' + field.value + '">');
                    }
                });
            });

        });
    </script>
@endsection
