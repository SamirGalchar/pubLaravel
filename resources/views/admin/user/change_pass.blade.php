@extends('layouts.admin.app')

@section('content')
    {!! $htmlContent !!}
    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function () {
                $('#f1').validate({
                    rules:{
                        oldPassword: {
                            required: true,
                            remote: {
                                url: "{{ route('admin.checkPassword') }}",
                                data: { '_token' : "{{csrf_token()}}" },
                                type: "post"
                            }
                        },
                        newPassword: { 
                            required: true,
                            minlength:6,
                        },
                        confirmPassword: {
                            required: true,
                            minlength:6,
                            equalTo: "#newPassword"
                        }
                    },
                    messages:{
                        oldPassword: {
                            required: "{{ __('admin.required') }}",
                            remote: "Password not match, please try again."
                        },
                        newPassword: {
                            required: "{{ __('admin.required') }}",
                            minlength: "Password should be minimum 6 characters",
                        },
                        confirmPassword: {
                            required: "{{ __('admin.required') }}",
                            minlength: "Password should be minimum 6 characters",
                        }
                    },
                });
            });
        </script>
    @endpush
@endsection
