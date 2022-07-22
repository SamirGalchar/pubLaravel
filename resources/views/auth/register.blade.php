@extends('layouts.app')
@section('title',"Register")
@section('content')

<div class="registration_sec">
    <div class="container">
        <div class="row justify-content-center min_height">
            <div class="col-md-6">
                <div class="card regi_form">
                    <div class="card-header text-center text-white">{{ __('Register - For Beginners') }}</div>
                    <div class="card-body">
                        <form id="registrationForm" method="POST" action="{{ route('register') }}" >
                            @csrf
                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end text-white">{{ __('Name') }}<span>*</span></label>
                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autocomplete="name" autofocus>
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end text-white">{{ __('Email Address') }}<span>*</span></label>
                                <div class="col-md-6">
                                    <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="phone" class="col-md-4 col-form-label text-md-end text-white">{{ __('Phone') }}</label>
                                <div class="col-md-6">
                                    <input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}">                                
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end text-white">{{ __('Password') }}<span>*</span></label>
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-end text-white">{{ __('Confirm Password') }}<span>*</span></label>
                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                                </div>
                            </div>
                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary register_btn font-18 text-white medium">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js" integrity="sha512-37T7leoNS06R80c8Ulq7cdCDU5MNQBwlYoy1TX/WUsLFC2eYNqtKlV0QjH7r8JpG/S0GUMZwebnVFLPd6SU5yg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>
    <script>
        $.validator.addMethod("PHONE",function(value,element){
            return this.optional(element) || /([0-9]{10})|(\([0-9]{3}\)\s+[0-9]{3}\-[0-9]{4})/.test(value);
        },"Please enter valid phone number");
        $(document).ready(function () {    
            $("#registrationForm").validate({
                errorElement:'span',
                errorClass:'step-error',
                rules: {
                    name:"required",
                    email: {
                        required: true,
                        email: true,
                        remote: {url: "{{ route('check-email') }}",type: "post",data:{'_token': '{{ csrf_token() }}' }}
                    },
                    phone:{
                        PHONE:true,                            
                    },
                    password: {
                        required: true,
                        minlength:8,
                    },
                    password_confirmation: {
                        required: true,
                        minlength:8,
                        equalTo:"#password"
                    },
                },
                messages: {
                    name:"The name field is required.",
                    email: {
                        required: "The email field is required.",
                        email: "Please enter a valid email address",
                        remote: "This email is already registered, please login."
                    },
                    phone:{
                        //required: "The phone field is required.",
                        //remote: "This number is already registered, please login."
                    },
                    password:{
                        required: "The password field is required.",
                        minlength: "Password should be minimum 8 characters",
                    },
                    password_confirmation:{
                        required: "Repeat password is required",
                        minlength: "Repeat password should be minimum 8 characters",
                        equalTo:"Please repeat same password"
                    },
                },
                submitHandler: function(form, event) {
                    $("#registrationForm")[0].submit();                   
                    startLoaderAjax();
                }
            });                
        });    
    </script>
@endpush

@endsection
