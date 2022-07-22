@extends('layouts.admin.app')

@section('content')
    {!! $htmlContent !!}

    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function () {
                $.validator.addMethod('minStrict', function (value, el, param) {
                    return value > param;
                },'{{ __('Value should be greater then zero.') }}');
                $('#f1').validate({
                    rules:{
                        name: {required: true},
                        sort_description: { required: true },
                        price: { required: true,number:true, minStrict:0 },
                        description: { required: true },
                        validity: { required: true,number:true, minStrict:0 },
                    },
                    messages:{
                        name: {required: '{{ __('admin.required') }}'},
                        sort_description: { required: '{{ __('admin.required') }}' },
                        price: { required: '{{ __('admin.required') }}' },
                        description: { required: '{{ __('admin.required') }}' },
                        validity: { required: '{{ __('admin.required') }}' },
                    },
                });
            });
        </script>
    @endpush
@endsection
