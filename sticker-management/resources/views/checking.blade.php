@extends('layouts.admin-master')
@section('admin-sidebar')
    {{-- @include('layouts.admin-sidebar') --}}
@endsection
@section('style')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('assets/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection
@section('checking_content')
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
    <div class="container p-4 mt-5">
        <form action="{{ url('/checking') }}" method="GET">
            {{-- @csrf --}}
            {{ csrf_field() }}
            <div class="row">

                {{-- Applicant BA --}}
                <div class="col-md-6 col-sm-12 mb-3">
                    <label class="d-flex align-items-center">
                        <input type="checkbox" id="BA_check" class="check_box_select mr-2" name="BA_check" value="1"
                            {{ isset($applicant_BA) && $applicant_BA != '' ? 'checked' : '' }}
                            data-input="{{ $applicant_BA ?? '' }}">
                        <span>Applicant BA</span>
                    </label>
                    <input type="text" name="BA" id="BA_inp" class="form-control" placeholder="Enter BA"
                        value="{{ $applicant_BA ?? '' }}"
                        {{ isset($applicant_BA) && $applicant_BA != '' ? '' : 'readonly' }}>
                </div>

                {{-- Phone Number --}}
                <div class="col-md-6 col-sm-12 mb-3">
                    <label class="d-flex align-items-center">
                        <input type="checkbox" class="phone_check check_box_select mr-2" name="phone_check" value="1"
                            {{ isset($applicant_phone) && $applicant_phone != '' ? 'checked' : '' }}
                            data-input="{{ $applicant_phone ?? '' }}">
                        <span>Phone Number</span>
                    </label>
                    <input type="text" name="phone" class="form-control phone_inp" placeholder="Enter Phone"
                        value="{{ $applicant_phone ?? '' }}"
                        {{ isset($applicant_phone) && $applicant_phone != '' ? '' : 'readonly' }}>
                </div>

                {{-- Vehicle Reg No --}}
                <div class="col-md-6 col-sm-12 mb-3">
                    <label class="d-flex align-items-center">
                        <input type="checkbox" class="reg_check check_box_select mr-2" name="reg_check" value="1"
                            {{ isset($reg_no) && $reg_no != '' ? 'checked' : '' }} data-input="{{ $reg_no ?? '' }}">
                        <span>Vehicle Reg No</span>
                    </label>
                    <input type="text" name="reg_no" class="form-control reg_inp" placeholder="Enter Reg No"
                        value="{{ $reg_no ?? '' }}" {{ isset($reg_no) && $reg_no != '' ? '' : 'readonly' }}>
                </div>

                {{-- Submit Button --}}
                <div class="col-md-4 offset-md-4 col-sm-12 mt-3">
                    <button type="submit" class="btn btn-primary btn-block" style="background-color:#0098dd;">
                        Search
                    </button>
                </div>

            </div>
        </form>
    </div>

    <div class="panel panel-default">


        <div class="panel-body" style="padding:10px 0;">
            <div id="example-wrapper" style="overflow-x:auto;">
                <table id="table" class="table table-bordered responsive" border="1" style="text-align: center;">
                    <thead style="background: #000;">
                        <tr>

                            <th scope="col">Name, NID</th>
                            <th scope="col">BA</th>
                            <th scope="col">Rank</th>
                            <th scope="col">Phone No.</th>
                            <th scope="col">Apply Date</th>

                            <th scope="col">Vehicle Type</th>

                            <th scope="col">Stkr. Type</th>
                            <th scope="col">Stkr. No</th>
                            <th scope="col">Reg. No.</th>
                            <th scope="col" class="ata">Status</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('admin-script')
    <script type="text/javascript" src="{{ asset('/assets/admins/js/admin-script.js') }}"></script>

    <script src="{{ asset('assets/bootstrap-datepicker/dist/js/datepicker.min.js') }}"></script>
    <script>
        $(function() {
            //Date picker
            $('.from_date,.to_date').datepicker({
                autoclose: true,
                format: 'dd-mm-yyyy'
            });
        });
    </script>
    <script>
        $(function() {
            $('#table').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{{ route('checking-result') }}',
                    data: function(d) {
                        d.applicant_name = '{{ $applicant_name }}';
                        d.applicant_BA = '{{ $applicant_BA }}';
                        d.applicant_Rank = '{{ $applicant_Rank }}';
                        d.glass_type = '{{ $glass_type }}';
                        d.applicant_phone = '{{ $applicant_phone }}';
                        d.reg_no = '{{ $reg_no }}';
                        d.applicant_nid_number = '{{ $applicant_nid_number }}';
                        d.sticker_no = '{{ $sticker_no }}';
                        d.sticker_type = '{{ $sticker_type }}';
                        d.vehicle_type = '{{ $vehicle_type }}';
                        d.house = '{{ $house }}';
                        d.road = '{{ $road }}';
                        d.area = '{{ $area }}';
                        d.date_from = '{{ $date_from }}';
                        d.date_to = '{{ $date_to }}';
                        d.year = '{{ $year }}';
                    }
                },
                columns: [{
                        data: 'applicant_name',
                        name: 'applicant.name'
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
                        data: 'phone_number',
                        name: 'applicant.phone'
                    },
                    {
                        data: 'app_date',
                        name: 'app_date'
                    },

                    {
                        data: 'vehicleType',
                        name: 'vehicleType'
                    },

                    {
                        data: 'sticker_category',
                        name: 'sticker_category'
                    },
                    {
                        data: 'sticker_number',
                        name: 'sticker_number'
                    },
                    {
                        data: 'Reg_number',
                        name: 'Reg_number'
                    },
                    {
                        data: 'app_status',
                        name: 'app_status'
                    },
                ],
                order: [
                    [5, "desc"]
                ]
            });

        });
    </script>
    <script type="text/javascript" src="{{ asset('/assets/admins/js/print-for-home.js') }}"></script>
@endsection
