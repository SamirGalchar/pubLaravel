@extends('layouts.admin.app')

@section('content')
    {!! $htmlContent !!}

    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function () {
                $('#f1').validate({
                    rules:{
                        name: {required: true},
                        email: { required: true,email:true },
                    },
                    messages:{
                        name: { required: '{{ __('admin.required') }}' },
                        email: { required: '{{ __('admin.required') }}' },
                    },
                });
            });
        </script>
    @endpush
@endsection

