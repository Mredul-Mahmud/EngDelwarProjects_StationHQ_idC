@extends('layouts.admin-master')
@section('admin-sidebar')
    @include('layouts.admin-sidebar')
@endsection
@section('content')
    <div class="content-area" style="padding-top: 15px;">
        <div class="container-fluid  pl-0 pr-0">
            @if (!empty($app))
                @if ($app->type == 'def')
                    @include('forms.apply-form-def-edit')
                @elseif($app->type == 'non-def')
                    @include('forms.apply-form-non-def-edit')
                @endif
            @endif
        </div>
    </div>
@endsection
@section('admin-script')
    <script type="text/javascript" src="{{ asset('assets/js/applyform.js') }}"></script>

    <script>
        $(function() {
            const hasStickerNumber = {{ !empty($app) && !empty($app->sticker_number) ? 'true' : 'false' }};
            if(!hasStickerNumber){
                const currentYear = new Date().getFullYear();
                $('#sticker_for_year').val(currentYear);
            }

            // $('#myDeliveryeModal').on('shown.bs.modal', function() {
            //     $('#sticker_for_year').focus();

            // Function to generate sticker number
            function generateStickerNumber() {
                const year = $('#sticker_for_year').val();
                const stickerType = $('#sticker_category').val();

                // Validate inputs
                if (!year || !stickerType) {
                    console.log('Year or sticker type is missing');
                    return;
                }

                $.ajax({
                    url: "{{ route('admin.latest-serial') }}",
                    method: 'GET',
                    data: {
                        year: year,
                        sticker_category: stickerType
                    },
                    success: function(res) {

                        if (res && res.serial) {
                            $('#year').val(res.year);
                            const stickerSerial = `${stickerType}-${res.serial}`;
                            $('.delivery-sticker-number').val(stickerSerial);
                            // alert('Generated Sticker Number: ' + stickerSerial);
                            $('#sticker_number').focus();
                        } else {
                            $('.delivery-sticker-number').val('');
                        }
                    },
                    error: function(e) {
                        $('.delivery-sticker-number').val('');
                    }
                });
            }

        

            // Trigger on year blur
            $('#sticker_for_year').off('blur').on('blur', generateStickerNumber);

            
        });
        // });
    </script>
@endsection
