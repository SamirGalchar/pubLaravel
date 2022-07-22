@extends('layouts.app')
@section('title',"Reset Password")
@section('content')
<div class="reset_sec center_book_bg">
    <div class="container">
        <div class="row justify-content-center min_height">
            <div class="col-md-6">
                <div class="card my_card">
                    <div class="card-header text-center text-white">{{ __('Reset Password') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('password.update') }}" id="loginForm">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end text-white">{{ __('Email Address') }}</label>
                                <div class="col-md-6">
                                    <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" autocomplete="email" autofocus>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end text-white">{{ __('Password') }}</label>
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-end text-white">{{ __('Confirm Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary btn_style font-18 text-white medium">
                                        {{ __('Reset Password') }}
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
    <script>
        $(document).ready(function () {
            $("#loginForm").validate({
                errorElement:'span',
                errorClass:'step-error',
                rules: {
                    email: {
                        required: true,
                        email: true,
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
                    email: {
                        required: "Please enter email",
                        email: "Please enter a valid email address",
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
                }
            });
        });
    </script>
@endpush
@endsection
