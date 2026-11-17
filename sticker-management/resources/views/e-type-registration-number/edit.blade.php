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
                    <h3 class="pptitle">Add Register E-type Vehicle Number</h3>
                </div>
                <div class="panel-body" style="padding:10px 0;">
                    <div id="example-wrapper">
                        <div class="card">
                            <div class="card-body">
                                <form class="needs-validation" action={{ route('registration-number.update', $e_reg_number->id) }} method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="PUT">
                                    <div class="form-row">
                                        <div class="col-md-5 mb-3">
                                            <label for="validationCustom01">Sticker Type</label>
                                            <select class="form-control" name="e_type_sticker"
                                                id="exampleFormControlSelect1">
                                                <option>1</option>
                                                @foreach ($sticker_types as $sticker_type)
                                                    <option value="{{ $sticker_type->id }}" {{ $sticker_type->id == $e_reg_number->sticker_category_id ? 'selected' : '' }}>{{ $sticker_type->name }}
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
                                            <input type="text" class="form-control" value="{{ $e_reg_number->reg_number }}" name="reg_number" required>
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
    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation');
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
@endsection
