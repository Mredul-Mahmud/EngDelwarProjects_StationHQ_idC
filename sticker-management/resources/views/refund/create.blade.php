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
                    {{-- <h3 class="pptitle">Add Vehicle Type</h3> --}}
                </div>
                <div>
                    <div id="example-wrapper">
                        <div class="card">
                            <div class="card-body">
                                <form class="needs-validation" action={{ route('admin.refund.store') }} method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" class="form-control" name="application_id" value={{ $application_id }}>
                                    <div class="form-row">

                                        <div class="col-md-5 mb-3">
                                            <label for="validationCustom02">Reason</label>
                                            <input type="text" class="form-control" name="reason" required>
                                            @if ($errors->has('reason'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('reason') }}
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="col-md-5 mb-3">
                                            <label for="validationCustom02">Refund by</label>
                                            <input type="text" class="form-control" name="refund_by" >
                                            @if ($errors->has('refund_by'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('refund_by') }}
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="col-md-2 mb-3 mt-1">
                                            <button class="btn btn-primary mt-4" type="submit">Save</button>
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
