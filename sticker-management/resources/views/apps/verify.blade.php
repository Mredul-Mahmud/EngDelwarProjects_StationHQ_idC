@extends('layouts.admin-master')
@section('admin-sidebar')
    @include('layouts.admin-sidebar')
@endsection
@section('content')
    <div class="content-area" style="padding-top: 15px;">

        <div class="container-fluid pl-0 pr-0">

            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('verify_application', $def) }}" method="GET">
                        <table>
                            <tr>
                                <td>
                                    <input type="text" name="applicant_name" value="{{ $applicant_name }}" class="form-control" placeholder="Name">
                                </td>
                                <td>
                                    <input type="text" name="ba" value="{{ $ba }}" class="form-control" placeholder="BA">
                                </td>
                                <td>
                                    <input type="text" name="rank" value="{{ $rank }}" class="form-control" placeholder="Rank">
                                </td>
                                <td>
                                    <input type="text" name="reg_no" value="{{ $reg_no }}" class="form-control" placeholder="Reg. No.">
                                </td>
                                <td>
                                    <input type="text" name="phone" value="{{ $phone }}" class="form-control" placeholder="Phone">
                                </td>
                                <td>
                                    <input type="text" name="date" value="{{ $date }}" class="form-control" placeholder="Date">
                                </td>
                                <td>
                                    <input type="text" name="Vehicle_Type" value="{{ $Vehicle_Type }}" class="form-control" placeholder="Vehicle Type">
                                </td>
                                <td>
                                    <select name="sticker_type" id="sticker_type" class="form-control">
                                        <option value=""> Sticker Type </option>
                                        @if(isset($stickerTypes) && $stickerTypes!='')
                                            @foreach($stickerTypes as $stickerType)
                                                <option value="{{$stickerType->value}}"> {{$stickerType->name}} </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="present_address" value="{{ $present_address }}" class="form-control" placeholder="Address">
                                </td>
                                <td>
                                    <button class="btn btn-info"> Search </button>
                                </td>
                            </tr>
                        </table>
                    </form>

                    @if (auth()->user()->role == 'super-admin')
                        
                      
                             <button id='' data-toggle="modal" data-target="#allApprovedModal"
                            class="btn btn-success"></i>All Approved</button>

                       
                    @endif
                    <!-- approve Modal -->

                    <div class="modal fade" id="allApprovedModal" tabindex="-1" role="dialog" aria-labelledby=""
                        aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header" style="background-color: #4785c7; padding: 10px 0;">
                                    <legend style="color:#fff; text-align: center; ">All Application Approve</legend>
                                    <button style="color: #fff;" type="button" class="close" data-dismiss="modal"
                                        aria-label="Close">
                                        <span aria-hidden="true" style="padding:10px 10px 0 0;">&times;</span>
                                    </button>
                                </div>
                                @if ($def == 'def')
                                    <form id="delivery_App_frm" action="{{ route('admin.all-application-approved-def') }}">
                                    <
                                @elseif($def == 'non-def')
                                    <form id="delivery_App_frm"
                                        action="{{ route('admin.all-application-approved-non-def') }}">
                                @endif

                                {{ csrf_field() }}
                                <div class="modal-body" style="padding:0;">

                                    <div class="row">
                                        <div class="col-md-4 offset-md-1">
                                            <label for="" class="label-form"> Approve Date </label>
                                            <span>*</span> <br>

                                            <small></small>
                                        </div>
                                        <div class="col-md-7">
                                            <input type="date" id="sticker_approve_date" class="form-control in-form"
                                                name="sticker_approve_date" value="{{ old('sticker_approve_date') }}"
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
                                        id="delivery_App">Confirm Approve</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- approve Modal -->
                </div>
            </div>

	@if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif


            <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="pptitle">Approved Applications</h3>
            </div>
         <div class="panel-body" style="padding:10px 0;">
             <div id="example-wrapper">

                <table id="table" class="table table-bordered dt-responsive" style="text-align: center;">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name, NID</th>
                        <th scope="col">Personal No</th>
                        <th scope="col">Rank</th>
                        <th scope="col">Reg. No.</th>
                        <th scope="col">Phone No.</th>
                        <th scope="col">Apply Date</th>
                        <th scope="col">Vehicle Type</th>
                        {{-- <th scope="col">Glass Type</th> --}}
                        <th scope="col">Sticker Type</th>
                        <th scope="col">Present Address</th>
                    </tr>
                    </thead>
                </table>
            </div>
            </div>
        </div>

       </div>
        

    </div>
  

@endsection

@section('admin-script')
    <script>

        $(function() {
            $('#table').DataTable({
                processing: true,
                serverSide: true,
                aLengthMenu: [
                    [10,25, 50, 100, 200, -1],
                    [10,25, 50, 100, 200, "All"]
                ],
                iDisplayLength: 10,
                ajax: {
                    url: '{{ route('verify_application.datatable') }}',
                    data: function (d) {
                        d.def = '{{ $def }}';
                        d.applicant_name = '{{ $applicant_name }}';
                        d.ba = '{{ $ba }}';
                        d.rank = '{{ $rank }}';
                        d.reg_no = '{{ $reg_no }}';
                        d.phone = '{{ $phone }}';
                        d.date = '{{ $date }}';
                        d.Vehicle_Type = '{{ $Vehicle_Type }}';
                        d.glass_type = '{{ $glass_type }}';
                        d.sticker_type = '{{ $sticker_type }}';
                        d.present_address = '{{ $present_address }}';
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'id', searchable: false},
                    {data: 'applicant_name', name: 'applicant.name'},
                    {data: 'BA_no', name: 'BA_no'},
                    {data: 'Rank_id', name: 'Rank_id'},
                    {data: 'Reg_number', name: 'vehicleinfo.reg_number'},
                    {data: 'phone_number', name: 'applicant.phone'},
                    {data: 'app_date', name: 'app_date',type: 'date-dd-mmm-yyyy', targets: 0},
                    {data: 'vehicleType', name: 'vehicleinfo.vehicleType.name'},
                    // {data: 'glass_type', name: 'glass_type'},
                    {data: 'sticker_category', name: 'sticker_category'},
                    {data: 'address', name: 'address'},
                ],
                order:[[0,"desc"]]
            });

        });

    </script>
@endsection
