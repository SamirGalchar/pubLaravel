@extends('layouts.admin.app')

@section('content')
    {!! $htmlContent !!}

    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function () {
                $('#f1').validate({
                    rules:{
                        subject: { required: true},
                        templates: { required: true},                        
                    },
                    messages:{
                        subject: {required: '{{ __('admin.required') }}'},
                        templates: { required: '{{ __('admin.required') }}' },                        
                    },
                    errorPlacement:function (error,element) {
                        if (element.attr("name") == "templates"){
                            //$("#er_msg_templates").html('');
                            error.insertAfter("#er_msg_templates");
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
