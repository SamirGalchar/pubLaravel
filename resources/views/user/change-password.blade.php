@extends('layouts.app')
@section('title',"Change Password")
@section('content')

<section class="workig_process working_bg py-100">
    <div class="container">
        <div class="working_text">
            <h6 class="poppins font-40 color-e3e0e0 text-center lh-39 ls-1 mb-0 medium">Change Password</h6>
        </div>
    </div>    
</section>

<div class="bg-white p-80">
    <div class="container">
        <div class="row justify-content-center min_height">
            @include('user.partial.sidebar')
            <div class="col-lg-9">
                <div class="card my_card profile_card">
                    <div class="card-header text-center text-white">{{ __('Change Password') }}</div>

                    <div class="card-body">
                        <form method="POST" id="changePassword" action="{{ route('user.change-password') }}">
                            @csrf
                            <div class="row mb-3">
                                <label for="old_password" class="col-md-4 col-form-label text-md-end text-white">{{ __('Old Password') }}<span>*</span></label>
                                <div class="col-md-6">
                                    <input id="old_password" type="password" class="form-control @error('old_password') is-invalid @enderror" name="old_password">
                                    @error('old_password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
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
                                    <button type="submit" class="btn btn-primary btn_style font-18 text-white medium">{{ __('Change Password') }}</button>
                                </div>
                            </div>                        
                        </form>
                        @if(Auth::user()->isPaid == 'Yes')
                            <div class="row justify-content-center mt-5">
                                <div class="col-12 col-sm-12 col-md-10 col-lg-5">
                                    <p class="barlow medium font-20 color-f2f3f4 lh-27  ls_1 text-center">For fitness support, email us at 
                                        <a class="text-white text-decoration-none" href="mailto:fitnessconsultant@cf-30workout.com">fitnessconsultant@cf-30workout.com</a>
                                        <br>
                                        <span style="font-size:6px;"><a class="text-white text-decoration-none" href="{{ route('user.unsubscribe') }}">Unsubscribe Videos</a></span>
                                    </p>                        
                                </div>
                            </div>
                        @endif
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
            $("#changePassword").validate({
                errorElement:'span',
                errorClass:'step-error',
                rules: {
                    old_password: {
                        required: true,
                        minlength:8,
                        remote:{ url:"{{ route('user.check-old-password') }}", type:"post", data:{'_token':'{{ csrf_token() }}'} },
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
                    old_password:{
                        required: "The old password field is required.",
                        minlength: "Password should be minimum 8 characters",
                        remote: "Password not match, please try again."
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
            });                
        }); 
    </script>
@endpush

@endsection