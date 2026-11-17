<script>
    $(document).ready(function() {

        $.ajax({
            url: "{{ route('update.expired.applications') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                console.log(response.message);
                console.log("Expired Application Response:", response);
            },
            error: function(error) {
                console.log("Expired Application Response:", error);
                console.log(error);
            }
        });

    });
</script>
