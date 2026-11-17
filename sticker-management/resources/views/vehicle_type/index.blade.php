@extends('layouts.admin-master')
@section('style')
    <style>
    .pagination li {
        padding: 3px
    }
    </style>

@endsection
@section('admin-sidebar')
    @include('layouts.admin-sidebar')
@endsection
@section('content')
    <div class="content-area sticker-list-panel" style="padding-top: 15px;">
        <div class="container-fluid  pl-0 pr-0">
            <div class="panel panel-default">
                <div class="panel-heading"
                    style=" display: flex;justify-content: flex-start;align-items: center;    flex-flow: row wrap;">
                    <h3 class="pptitle">All Vehicle Type</h3>
                    <a href="{{ route('vehicle-type.create') }}" class="btn btn-primary"
                        style="overflow: hidden; margin: 0 25px;"><i class="fas fa-plus"></i> &nbsp; Add Vehicle Type</a>
                </div>
                <div class="panel-body" style="padding:10px 0;">
                    <div id="example-wrapper">
                        <table  class="table table-bordered dt-responsive" style="text-align: center;">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Vihicle Type</th>
                                    <th scope="col">Created By</th>
                                    <th scope="col">Updated By</th>
                                    <th scope="col" class="action">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($vehicle_types))
                                    <?php $sl = 1; ?>
                                    @foreach ($vehicle_types as $key => $vehicle_type)
                                        <tr id="sticker-{{ $vehicle_type->id }}">
                                            <td scope="row"> <b class="serial">{{ $sl++ }} </b></td>
                                            <td>{{ $vehicle_type->name }}</td>
                                            <td>{{ !empty($vehicle_type->created_by) ? $vehicle_type->created_by : '' }} <br>
                                                {{ \Carbon\Carbon::parse($vehicle_type->created_at)->toDayDateTimeString() }}
                                            </td>
                                            <td>{{ !empty($vehicle_type->updated_by) ? $vehicle_type->updated_by : '' }}<br>
                                                {{ !empty($vehicle_type->updated_by) ? \Carbon\Carbon::parse($vehicle_type->updated_at)->toDayDateTimeString() : '' }}
                                            </td>
                                            <td class="action">
                                                <a class="btn btn-info edit-sticker"
                                                    href="{{ route('vehicle-type.edit', $vehicle_type->id) }}"><i
                                                        class="fas fa-edit"></i></a>
                                                <button class="btn btn-danger delete-sticker"
                                                    href="{{ route('vehicle-type.destroy', $vehicle_type->id) }}"
                                                    onclick="event.preventDefault();
                        document.getElementById('delete-sticker').submit();"><i
                                                        class="fas fa-trash-alt"></i></button>
                                                <form id="delete-sticker"
                                                    action="{{ route('vehicle-type.destroy', $vehicle_type->id) }}"
                                                    method="POST" style="display: none;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    {{ csrf_field() }}
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach

                                    
                                @endif

                            </tbody>
                        </table>
                        {{ $vehicle_types->links() }}
                    </div>
                </div>
            </div>

        </div>


    </div>

@endsection
@section('admin-script')

@endsection
