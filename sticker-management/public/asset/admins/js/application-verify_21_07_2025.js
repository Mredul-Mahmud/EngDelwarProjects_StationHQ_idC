$("#verify_App").on("click", function (e) {
    e.preventDefault();
    var form = $("#verify_App_frm")[0];

    var frmData = new FormData(form);
    let formAction = document.getElementById('verify_App_frm').action;

    if (sticker_delivery_date != "" && sticker_type != null) {
        swal({
            title: "Are you sure?",
            text: "Verify this application!",
            type: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Verify it!",
            showLoaderOnConfirm: true,
            preConfirm: function () {
                return new Promise(function () {
                    $.ajax({
                        url: formAction,
                        type: "POST",
                        enctype: "multipart/form-data",
                        data: frmData,
                        dataType: "json",
                        contentType: false,
                        processData: false,
                        cache: false,
                    })
                        .done(function (response) {
                            console.log(response);
                            if (response[0] == "success") {

                                swal("Verified!", response[1], "success").then(function () {
                                    $("[data-dismiss=modal]").trigger({ type: "click" });
                                    location.reload();
                                });
                            } else if (response[0] == "fail") {
                                swal("Error!", response[1], "warning").then(function () {
                                    $("[data-dismiss=modal]").trigger({ type: "click" });
                                });
                            } else if (response[0] == "not-now") {
                                swal("Not Approved!", response[1], "error").then(function () {
                                    $("[data-dismiss=modal]").trigger({ type: "click" });
                                });
                            }
                        })
                        .fail(function (response) {
                            swal("Oops...", "Something went wrong!", "error");
                        });
                });
            },
            allowOutsideClick: false,
        });
    } else {
        if (sticker_delivery_date == "") {
            swal("Oops...", "Sticker delivery date is require!", "error");
        }
        if ("#sticker_type" == null) {
            swal("Oops...", "Sticker type is require!", "error");
        }
    }
});