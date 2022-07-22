@extends('layouts.admin.app')

@section('content')
    {!! $htmlContent !!}

    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function () {
                $('#f1').validate({
                    rules:{
                        fromName: { required: true},
                        smtpHost: { required: true},
                        smtpPort: { required: true},
                        smtpEmail: { required: true, email:true},
                        smtpPass: { required: true},
                        adminEmail: {required: true, email: true}
                    },
                    messages:{
                        fromName: {required: '{{ __('admin.required') }}'},
                        title: { required: '{{ __('admin.required') }}' },
                        smtpHost: { required: '{{ __('admin.required') }}' },
                        smtpPort: { required: '{{ __('admin.required') }}' },
                        smtpEmail: { required: '{{ __('admin.required') }}', email:"Enter valid email" },
                        smtpPass: { required: '{{ __('admin.required') }}' },
                        adminEmail: { required: '{{ __('admin.required') }}', email:"Enter valid email" },
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
