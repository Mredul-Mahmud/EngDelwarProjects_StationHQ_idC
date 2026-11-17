@extends('layouts.customer-master')
@section('content')
<div class="col-md-10" id="content_term_condition" style="margin-top:0px; ">  	
        <div class="content-area" style="padding-top: 0px;">
        <div class="container-fluid  pl-0 pr-0" >
            @if (session('error'))

            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <script>
                $(document).ready(function() {
                    setTimeout(function() {
                        $('.alert').alert('close');
                    }, 3000); 
                });
            </script>
        
        @endif
        	@if(!empty($app))
                        @if($applicant_role == 'def' )
                                  @include('forms.apply-form-def-edit')
                        @elseif($applicant_role == 'non-def')
                                 @include('forms.apply-form-non-def-edit')
                        @endif
            @endif
        </div>
       </div>
</div>
@endsection
@section('script')
 <script type="text/javascript" src="{{asset('assets/js/applyform.js') }}"></script>

@endsection