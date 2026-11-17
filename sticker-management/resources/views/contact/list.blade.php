@extends('layouts.admin-master')
@section('admin-sidebar')
@include('layouts.admin-sidebar')
@endsection
@section('content')
    <div class="content-area sms-panel" style="padding-top: 15px;">
        <div class="container-fluid  pl-0 pr-0" >
            <div class="panel panel-default">
                <b>MIL OFFRS Contact List</b>
                <a href="{{ route('contact.add') }}" class="btn btn-primary btn-sm pull-right"> Add New Contact Number</a>
            </div>
            @if(session('success'))
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{session('success')}}
                </div>
            @endif
            <div class="panel-body">
             <div id="example-wrapper">
                <table id="example" class="table table-bordered dt-responsive" >
                    <thead>
                    <tr>
                        <th scope="col">SL</th>
                        <th scope="col">Title</th>                        
                        <th scope="col">Short Responsible</th>                     
                        <th scope="col">Phone</th>                     
                        <th scope="col">Tel</th>                     
                        <th scope="col">Civil Tel</th>                     
                        <th scope="col">Position</th>
                        <th scope="col">Other</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="action">Action</th>
                    </tr>
                    </thead>
                        @foreach ($contacts as $key => $contact)
                            <tr>
                                <td> {{ ++$key }} </td>
                                <td> {{ $contact->title ?? '' }}  </td>
                                <td> {{ $contact->sub_title ?? ''}}  </td>
                                <td> {{ $contact->phone ?? ''}}  </td>
                                <td> {{ $contact->tel ?? ''}}  </td>
                                <td> {{ $contact->civil_tel ?? ''}}  </td>
                                <td> {{ $contact->position ?? ''}}  </td>
                                <td> {{ $contact->other ?? ''}}  </td>
                             
                                
                                <td class="text-center"> {{ $contact->status == 1?'Enable':'Disable' }} </td>
                                <td class="text-center"> 
                                    <a href="{{ route('contact.edit', $contact->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="{{ route('contact.delete', $contact->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Do you want to delete ?')">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    <tbody>
                    </tbody>
                </table>
            </div>
            </div>
        </div>

       </div>
        

    </div>

@endsection
@section('admin-script')
<link href="{{asset('assets/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet">

<script src="{{asset('assets/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>
 {{-- <script type="text/javascript" src="{{asset('/assets/admins/js/admin-script.js')}}"></script> --}}
@endsection