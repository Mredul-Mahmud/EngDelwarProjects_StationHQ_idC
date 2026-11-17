@extends('layouts.admin-master')
@section('admin-sidebar')
    @include('layouts.admin-sidebar')
@endsection
@section('content')
    <div class="content-area sticker-list-panel" style="padding-top: 15px;">
        <div class="container-fluid  pl-0 pr-0">
            <div class="panel panel-default">
                <div class="panel-heading"
                    style=" display: flex;justify-content: flex-start;align-items: center;    flex-flow: row wrap;">
                    <h3 class="pptitle">Edit Vehicle Type</h3>
                </div>
                <div class="panel-body" style="padding:10px 0;">
                    <div id="example-wrapper">
                        <div class="card">
                            <div class="card-body">
                                <form class="needs-validation" action={{ route('vehicle-type.update', $vehicle_type->id) }}
                                    method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="PUT">
                                    <div class="form-row">

                                        <div class="col-md-5 mb-3">
                                            <label for="validationCustom02">Name</label>
                                            <input type="text" class="form-control" value="{{ $vehicle_type->name }}"
                                                name="name" required>
                                            @if ($errors->has('name'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('name') }}
                                                </div>
                                            @endif
                                        </div>
                                        @if (auth()->user()->email == 'tiladmin@til.com')
                                            <div class="col-md-5 mb-3">
                                                <label for="validationCustom02">Type</label>
                                                <input type="text" class="form-control" name="type"
                                                    value="{{ $vehicle_type->type }}" required>
                                                @if ($errors->has('type'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('type') }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
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
@endsection
