<div class="modal fade" id="cropModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crop Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img id="cropperImage" style="max-width:100%;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="cropBtn">Crop</button>
            </div>
        </div>
    </div>
</div>

{{-- <script>
    document.querySelectorAll('.resize-image').forEach(function(input){
    input.addEventListener('change', function(event){
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e){
            const img = new Image();
            img.src = e.target.result;

            img.onload = function(){
                const maxWidth = 400;  // রিসাইজ width
                const maxHeight = 400; // রিসাইজ height

                let width = img.width;
                let height = img.height;

                // aspect ratio বজায় রাখা
                if (width > height) {
                    if (width > maxWidth) {
                        height *= maxWidth / width;
                        width = maxWidth;
                    }
                } else {
                    if (height > maxHeight) {
                        width *= maxHeight / height;
                        height = maxHeight;
                    }
                }

                const canvas = document.createElement('canvas');
                canvas.width = width;
                canvas.height = height;

                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);

                canvas.toBlob(function(blob) {
                    // blob কে File বানানো
                    const newFile = new File([blob], file.name, { type: 'image/jpeg' });

                    // input এর files replace করা
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(newFile);
                    input.files = dataTransfer.files;
                }, 'image/jpeg', 0.9);
            };
        };
        reader.readAsDataURL(file);
    });
});

</script> --}}
{{-- <script>
    document.querySelectorAll('.resize-image').forEach(function(input) {
        input.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.src = e.target.result;
                img.onload = function() {
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');

                    // ইমেজ রিসাইজ চাইলে এখানে সেট করো
                    const maxWidth = 800;
                    const maxHeight = 800;
                    let width = img.width;
                    let height = img.height;

                    if (width > height) {
                        if (width > maxWidth) {
                            height *= maxWidth / width;
                            width = maxWidth;
                        }
                    } else {
                        if (height > maxHeight) {
                            width *= maxHeight / height;
                            height = maxHeight;
                        }
                    }

                    canvas.width = width;
                    canvas.height = height;
                    ctx.drawImage(img, 0, 0, width, height);

                    let quality = 0.9;

                    function compress() {
                        canvas.toBlob(function(blob) {
                            if (blob.size / 1024 > 300 && quality > 0.1) {
                                quality -= 0.1;
                                compress();
                            } else {
                                const newFile = new File([blob], file.name.replace(
                                    /\.\w+$/, '.jpg'), {
                                    type: 'image/jpeg'
                                });
                                const dataTransfer = new DataTransfer();
                                dataTransfer.items.add(newFile);
                                input.files = dataTransfer.files;

                                console.log("Old size:", (file.size / 1024).toFixed(2) +
                                    " KB");
                                console.log("New size:", (newFile.size / 1024).toFixed(
                                    2) + " KB");
                            }
                        }, 'image/jpeg', quality);
                    }
                    compress();
                };
            };
            reader.readAsDataURL(file);
        });
    });
</script> --}}

{{-- <script>
    function readURL(input) {
        imgId = "#prev_" + $(input).attr("id");
        if (input.files && input.files[0]) {
            const file = input.files[0];

            // compress করা ফাইলই ব্যবহার করবো
            if (file.size / 1024 <= 250) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $(imgId).attr("src", e.target.result);
                    $(imgId).attr("hidden", false);
                    $(imgId).css({
                        display: "inline-block",
                        height: "auto",
                        padding: "10px 0px",
                        "max-width": "70%",
                        width: "auto",
                        "max-height": "200px",
                    });
                };
                reader.readAsDataURL(file);
                $(input).siblings("div.err_msg").attr("hidden", true);
            } else {
                $(input).val("");
                $(imgId).attr("hidden", true);
                $(input)
                    .siblings("div.err_msg")
                    .find("span")
                    .text("File size exceeds 250 KB. Please reduce file size less than 250 KB");
                $(input).siblings("div.err_msg").attr("hidden", false);
            }
        }
    }

    // compress করার পর readURL আবার কল
    document.querySelectorAll('.resize-image').forEach(function(input) {
        input.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.src = e.target.result;
                img.onload = function() {
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');
                    canvas.width = img.width;
                    canvas.height = img.height;
                    ctx.drawImage(img, 0, 0);

                    let quality = 0.9;

                    function compress() {
                        canvas.toBlob(function(blob) {
                            if (blob.size / 1024 > 250 && quality > 0.1) {
                                quality -= 0.1;
                                compress();
                            } else {
                                const newFile = new File([blob], file.name.replace(
                                    /\.\w+$/, '.jpg'), {
                                    type: 'image/jpeg'
                                });
                                const dataTransfer = new DataTransfer();
                                dataTransfer.items.add(newFile);
                                input.files = dataTransfer.files;

                                console.log("Old size:", (file.size / 1024).toFixed(2) +
                                    " KB");
                                console.log("New size:", (newFile.size / 1024).toFixed(
                                    2) + " KB");

                                // এখন compressed file preview দেখাও
                                readURL(input);
                            }
                        }, 'image/jpeg', quality);
                    }
                    compress();
                };
            };
            reader.readAsDataURL(file);
        });
    });
</script> --}}

<script>
    function readURL(input) {
        const imgId = "#prev_" + $(input).attr("id");
        if (input.files && input.files[0]) {
            const file = input.files[0];
            if (file.size / 1024 <= 250) {
               
                var reader = new FileReader();
                reader.onload = function(e) {
                    $(imgId).attr("src", e.target.result);
                    $(imgId).attr("hidden", false);
                    $(imgId).css({
                        display: "inline-block",
                        height: "auto",
                        padding: "10px 0px",
                        "max-width": "70%",
                        width: "auto",
                        "max-height": "200px",
                    });
                };
                reader.readAsDataURL(file);
                $(input).siblings("div.err_msg").attr("hidden", true);
            } else {
              
                $(input).val("");
                $(imgId).attr("hidden", true);
                $(input)
                    .siblings("div.err_msg")
                    .find("span")
                    .text("File size exceeds 250 KB. Please reduce file size less than 250 KB");
                $(input).siblings("div.err_msg").attr("hidden", false);
            }
        }
    }

    document.querySelectorAll('.resize-image').forEach(function(input) {
        input.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (!file) return;

            if (file.size / 1024 <= 250) {
               
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                input.files = dataTransfer.files;
                readURL(input);
            } else {
               
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = new Image();
                    img.src = e.target.result;
                    img.onload = function() {
                        const canvas = document.createElement('canvas');
                        const ctx = canvas.getContext('2d');
                        canvas.width = img.width;
                        canvas.height = img.height;
                        ctx.drawImage(img, 0, 0);

                        let quality = 0.9;

                        function compress() {
                            canvas.toBlob(function(blob) {
                                if (blob.size / 1024 > 250 && quality > 0.1) {
                                    quality -= 0.1;
                                    compress();
                                } else {
                                    const newFile = new File([blob], file.name.replace(
                                        /\.\w+$/, '.jpg'), {
                                        type: 'image/jpeg'
                                    });
                                    const dataTransfer = new DataTransfer();
                                    dataTransfer.items.add(newFile);
                                    input.files = dataTransfer.files;

                                    console.log("Old size:", (file.size / 1024).toFixed(
                                        2) + " KB");
                                    console.log("New size:", (newFile.size / 1024)
                                        .toFixed(2) + " KB");

                                    readURL(input);
                                }
                            }, 'image/jpeg', quality);
                        }
                        compress();
                    };
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
