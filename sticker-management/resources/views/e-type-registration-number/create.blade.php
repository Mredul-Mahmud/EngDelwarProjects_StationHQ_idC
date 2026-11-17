@extends('layouts.admin-master')
@section('admin-sidebar')
    @include('layouts.admin-sidebar')
@endsection
@section('content')
    <div class="content-area sticker-list-panel" style="padding-top: 15px;">
        <div class="container-fluid  pl-0 pr-0">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="panel panel-default">
                <div class="panel-heading"
                    style=" display: flex;justify-content: flex-start;align-items: center;    flex-flow: row wrap;">
                    <h3 class="pptitle">Add Register E-type Vehicle Number</h3>
                </div>
                <div>
                    <div id="example-wrapper">
                        <div class="card">
                            <div class="card-body">
                                <form class="needs-validation" action={{ route('registration-number.store') }} method="POST">
                                    {{ csrf_field() }}
                                    <div class="form-row">
                                        <div class="col-md-5 mb-3">
                                            <label for="validationCustom01">Sticker Type</label>
                                            <select class="form-control" name="e_type_sticker"
                                                id="exampleFormControlSelect1">
                                                <option>1</option>
                                                @foreach ($sticker_types as $sticker_type)
                                                    <option value="{{ $sticker_type->id }}">{{ $sticker_type->name }}
                                                    </option>
                                                @endforeach

                                            </select>
                                            @if ($errors->has('e_type_sticker'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('e_type_sticker') }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-5 mb-3">
                                            <label for="validationCustom02">Registration Number</label>
                                            <input type="text" class="form-control" name="reg_number" required>
                                            @if ($errors->has('reg_number'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('e_type_sticker') }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-2 mb-3 mt-1">
                                            <button class="btn btn-primary mt-4" type="submit">Submit form</button>
                                        </div>
                                    </div>

                                </form>
                            </div>
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
@endsection
