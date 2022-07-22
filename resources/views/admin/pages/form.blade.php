@extends('layouts.admin.app')

@section('content')
    {!! $htmlContent !!}

    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function () {
                $('#f1').validate({
                    rules:{
                        short_description: {required: true},
                        title: { required: true },
                        heading: { required: true },
                        long_description: { required: true }
                    },
                    messages:{
                        short_description: {required: '{{ __('admin.required') }}'},
                        title: { required: '{{ __('admin.required') }}' },
                        heading: { required: '{{ __('admin.required') }}' },
                        long_description: { required: '{{ __('admin.required') }}' }
                    },
                    errorPlacement:function (error,element) {
                        if (element.attr("name") == "long_description"){
                            $("#er_msg_long_description").html('');
                            error.appendTo("#er_msg_long_description");
                        }
                        else{
                            error.insertAfter(element);
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection
