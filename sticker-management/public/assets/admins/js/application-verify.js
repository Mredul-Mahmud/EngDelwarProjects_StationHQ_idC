$("#verify_App").on("click", function (e) {
  e.preventDefault();
  var form = $("#verify_App_frm")[0];

  var frmData = new FormData(form);
  let formAction = document.getElementById("verify_App_frm").action;
  console.log("Form Action:", formAction); // Debugging line

  if (sticker_verify_date != "" && sticker_type != null) {
    // if (sticker_delivery_date != "" && sticker_type != null) {
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
              } else if (response[0] == "fail" ) {
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
    if (!$("#sticker_verify_date").val()) {
      swal("Oops...", "Sticker delivery date is require!", "error");
    }
    if (!$("#sticker_type").val()) {
      swal("Oops...", "Sticker type is require!", "error");
    }
  }
});

$("#delivery_App").on("click", function (e) {
  e.preventDefault();
  var form = $("#delivery_App_frm")[0];

  var frmData = new FormData(form);
  let formAction = document.getElementById("delivery_App_frm").action;
  console.log("Form Action:", formAction); // Debugging line

  // if (sticker_approve_date != "" ) {
  if (sticker_delivery_date != "" && sticker_type != null) {
    swal({
      title: "Are you sure?",
      text: "Delivery this application!",
      type: "question",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, Delivery it!",
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
             //   console.log(response);
               if (response[1] == "success") {
                 swal("Delivery!", response[0], "success").then(function () {
                   $("[data-dismiss=modal]").trigger({ type: "click" });
                   location.reload();
                 });
               } else if (response[1] == "fail" || response[0] == "fail") {
                 var message = response[1] == "fail" ? response[0] : response[1];
                 swal("Error!", message, "warning").then(function () {
                   $("[data-dismiss=modal]").trigger({ type: "click" });
                 });
               } else if (response[1] == "not-now") {
                 swal("Not Approved!", response[0], "error").then(function () {
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
    if (!$("#sticker_delivery_date").val()) {
      swal("Oops...", "Sticker delivery date is require!", "error");
    }
    
    if (!$("#sticker_type").val()) {
      swal("Oops...", "Sticker type is require!", "error");
    }
  }
});

