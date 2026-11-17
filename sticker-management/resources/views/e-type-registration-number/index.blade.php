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
                    <h3 class="pptitle">All E-type Vehicle Registation Number</h3>
                    <a href="{{ route('registration-number.create') }}" class="btn btn-primary"
                        style="overflow: hidden; margin: 0 25px;"><i class="fas fa-plus"></i> &nbsp; Add Register E-type
                        Vehicle Number</a>
                </div>
                <div class="panel-body" style="padding:10px 0;">
                    <div id="example-wrapper">
                        <table id="e-type-sticker" class="table table-bordered dt-responsive" style="text-align: center;">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Sticker Type</th>
                                    <th scope="col">Reg Number</th>
                                    <th scope="col">Created By</th>
                                    <th scope="col">Updated By</th>
                                    <th scope="col" class="action">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($reg_numbers))
                                    <?php $sl = 1; ?>
                                    @foreach ($reg_numbers as $key => $reg_number)
                                        <tr id="sticker-{{ $reg_number->id }}">
                                            <td scope="row"> <b class="serial">{{ $sl++ }} </b></td>
                                            <td>{{ $reg_number->stickerCategory->name }}</td>
                                            <td>{{ $reg_number->reg_number }}</td>
                                            <td>{{ !empty($reg_number->created_by) ? $reg_number->created_by : '' }} <br>
                                                {{ \Carbon\Carbon::parse($reg_number->created_at)->toDayDateTimeString() }}
                                            </td>
                                            <td>{{ !empty($reg_number->updated_by) ? $reg_number->updated_by : '' }}<br>
                                                {{ !empty($reg_number->updated_by) ? \Carbon\Carbon::parse($reg_number->updated_at)->toDayDateTimeString() : '' }}
                                            </td>
                                            <td class="action">
                                                <a class="btn btn-info edit-sticker"
                                                    href="{{ route('registration-number.edit', $reg_number->id) }}"><i
                                                        class="fas fa-edit"></i></a>
                                                <button class="btn btn-danger delete-sticker"
                                                    href="{{ route('registration-number.destroy', $reg_number->id) }}"
                                                    onclick="event.preventDefault();
                                                    document.getElementById('delete-sticker').submit();">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                                <form id="delete-sticker"
                                                    action="{{ route('registration-number.destroy', $reg_number->id) }}"
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
                    </div>
                </div>
            </div>

        </div>


    </div>

@endsection
@section('admin-script')
<script>
    $(document).ready(function() {
        $('#e-type-sticker').DataTable({
            responsive: true,
            autoWidth: false,  
            columnDefs: [
                { orderable: false, targets: -1 }
            ],
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "No entries available",
                zeroRecords: "No matching records found",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "→",
                    previous: "←"
                }
            }
        });
    });
</script>
    {{-- <link href="{{ asset('assets/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">

    <script src="{{ asset('assets/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script>
        $("#add_e_type_reg_number_form").on("submit", function(e) {
            e.preventDefault();
            var form = $("#add_e_type_reg_number_form");
            var formData = form.serialize();
            var url = "/add/admin";
            var type = "post";
            $.ajax({
                type: type,
                url: url,
                data: new FormData($("#add_e_type_reg_number_form")[0]),
                processData: false,
                contentType: false,
                success: function(response) {
                    var table = $("#example").DataTable();
                    var idx = table.rows().count();
                    idx++;
                    var rowNode = (table.row
                        .add([
                            '<b class="serial">' + idx + "</b>",
                            response[1]["name"],
                            response[1]["email"],
                            response[1]["role"],
                            response[1]["created_at"],
                            '<button class="btn btn-info edit-admin mr-1" data-name="' +
                            response[1]["name"] +
                            '" data-email="' +
                            response[1]["email"] +
                            '" data-role="' +
                            response[1]["role"] +
                            '" data-id="' +
                            response[1]["id"] +
                            '" data-toggle="modal" data-target="#edit_template_modal"><i class="fas fa-edit"></i></button>' +
                            '<button class="btn btn-danger delete-admin ml-0" data-id="' +
                            response[1]["id"] +
                            '" data-toggle="modal" data-target="#delete_template_modal"><i class="fas fa-trash-alt"></i></button>',
                        ])
                        .order([0, "dsc"])
                        .draw()
                        .node().id = "sms-" + idx);
                    $(rowNode).css("color", "green").animate({
                        color: "red"
                    });
                    swal("Congratulation!", response[0], "success").then(function() {
                        $("#AddAdmin_Form")[0].reset();
                        $("[data-dismiss=modal]").trigger({
                            type: "click"
                        });
                        $(".print-error-msg").css("display", "none");
                    });
                },
                error: function(response) {
                    printSMSErrorMsg(response.responseJSON);
                    swal("Oops...", "Something went wrong!", "error");
                },
            });
        });

        $(document).on("click", ".edit-admin", function() {
            $("input.name").val($(this).data("name"));
            $("input.email").val($(this).data("email"));
            $("input.role").val($(this).data("role"));

            id = $(this).data("id");
            tr = $(this).parent().parent();
            tr_id = tr.attr("id");
            sl = $("#" + tr_id + " .serial").text();
        });

        $("#UpdateAdmin_Form").on("submit", function(e) {
            e.preventDefault();
            var form = $("#UpdateAdmin_Form");
            var formData = form.serialize();
            var table = $("#example").DataTable();
            var url = "/update/admin/" + id;
            var type = "post";
            $.ajax({
                type: type,
                url: url,
                data: new FormData($("#UpdateAdmin_Form")[0]),
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response[1]["id"] == id) {
                        var row_data = [
                            '<b class="serial">' + sl + "</b>",
                            response[1]["name"],
                            response[1]["email"],
                            response[1]["role"],
                            response[1]["created_at"],
                            '<button class="btn btn-info edit-admin mr-1" data-name="' +
                            response[1]["name"] +
                            '" data-email="' +
                            response[1]["email"] +
                            '" data-role="' +
                            response[1]["role"] +
                            '" data-id="' +
                            response[1]["id"] +
                            '" data-toggle="modal" data-target="#edit_template_modal"><i class="fas fa-edit"></i></button><button class="btn btn-danger delete-admin ml-0" data-id="' +
                            response[1]["id"] +
                            '" data-toggle="modal" data-target="#delete_template_modal"><i class="fas fa-trash-alt"></i></button>',
                        ];
                        table
                            .row("tr#" + tr_id)
                            .data(row_data)
                            .draw();
                        swal("Congratulation!", response[0], "success").then(function() {
                            $("#UpdateAdmin_Form")[0].reset();
                            $("[data-dismiss=modal]").trigger({
                                type: "click"
                            });
                        });
                    }
                },
                error: function(response) {
                    printSMSErrorMsg(response.responseJSON);
                    swal("Oops...", "Something went wrong!", "error");
                },
            });
        });
        $(document).on("click", ".delete-admin", function() {
            var tr = $(this).parents("tr");
            var tr_id = tr.attr("id");
            // alert(tr_id+ ' '+ 'tr#'+tr_id);
            var id = $(this).data("id");
            swal({
                title: "Are you sure?",
                text: "You want to delete this Admin!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
                showLoaderOnConfirm: true,
                preConfirm: function() {
                    return new Promise(function(resolve) {
                        $.ajax({
                                url: "/delete/admin",
                                type: "post",
                                data: {
                                    _token: CSRF_TOKEN,
                                    id: id,
                                },
                                dataType: "json",
                            })
                            .done(function(response) {
                                swal("Congratulation!", response[0], "success").then(
                                    function() {
                                        var table = $("#example").DataTable();
                                        table
                                            .row("tr#" + tr_id)
                                            .remove()
                                            .draw();
                                    });
                            })
                            .fail(function(response) {
                                swal("Oops...", "Something went wrong!", "error");
                            });
                    });
                },
                allowOutsideClick: false,
            });
        });
    </script> --}}
@endsection
