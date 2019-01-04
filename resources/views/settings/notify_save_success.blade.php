@section('body_script')
    @if(session('success'))
    <script>
        $.notify(
            {
                message: "It'll take 10-15sec to update your setting. Please wait!"
            },
            {
                z_index: 999999,
                timer: 2000,
                type: "success"
            }
        );
    </script>
    @endif
@endsection