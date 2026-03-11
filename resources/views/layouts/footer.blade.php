<script src="{{ mix('js/app.js') }}"></script>
<script>
    @if(session('success'))
        toastr.success('{{ session('success') }}');
    @endif
    
    @if(session('error'))
        toastr.error('{{ session('error') }}');
    @endif

    @if($errors->any())
        @foreach(array_reverse(array_unique($errors->all())) as $error)
            toastr.error('{{ $error }}')
        @endforeach
    @endif

    function notif(status, message) {
        switch (status) {
            case 'error':
                toastr.error(message);
                break;
            case 'warning': 
                toastr.warning(message);
                break;
            default:
                toastr.success(message);
                break;
        }
    }
</script>