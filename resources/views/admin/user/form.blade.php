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
                        isActive: { required: true },
                    },
                    messages:{
                        name: {required: '{{ __('admin.required') }}'},
                        email: { required: '{{ __('admin.required') }}' },
                        isActive: { required: '{{ __('admin.required') }}' },
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
