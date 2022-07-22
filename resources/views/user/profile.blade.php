@extends('layouts.app')
@section('title',"Profile")
@section('content')

<section class="workig_process working_bg py-100">
    <div class="container">
        <div class="working_text">
            <h6 class="poppins font-40 color-e3e0e0 text-center lh-39 ls-1 mb-0 medium">Profile</h6>
        </div>
    </div>    
</section>

<div class="bg-white p-80">
    <div class="container">
        <div class="row justify-content-center min_height">
            @include('user.partial.sidebar')
            <div class="col-lg-9">
                <div class="card my_card profile_card">
                    <div class="card-header text-center text-white">{{ __('Profile') }}</div>
                    <div class="card-body">
                        <form method="POST" id="profileUpdate" action="{{ route('user.profile-update') }}" >
                            @csrf
                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end text-white">{{ __('Name') }}<span>*</span></label>
                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" value="{{ ($user['name']) ? $user['name'] : '' }}" autofocus>                                
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end text-white">{{ __('Email Address') }}<span>*</span></label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="email" value="{{ ($user['email']) ? $user['email'] : '' }}">                                
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="phone" class="col-md-4 col-form-label text-md-end text-white">{{ __('Phone') }}<span>*</span></label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="phone" value="{{ ($user['phone']) ? $user['phone'] : '' }}">                                
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary btn_style font-18 text-white medium">{{ __('Update') }}</button>
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
    <script src="https://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>
    <script>
        $.validator.addMethod("PHONE",function(value,element){
            return this.optional(element) || /([0-9]{10})|(\([0-9]{3}\)\s+[0-9]{3}\-[0-9]{4})/.test(value);
        },"Please enter valid phone number");
        $(document).ready(function () {
            /* Validations for form */
            $("#profileUpdate").validate({
                errorElement:'span',
                errorClass:'step-error',
                rules: {
                    name:"required",
                    email:{
                        required: true,
                        email: true,
                        remote: {url: "{{ route('check-email') }}",type: "post",data:{'_token': '{{ csrf_token() }}','id':'{{$user["id"]}}' }}
                    },
                    phone:{
                        PHONE:true,                        
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
                },                    
            }); 
        });
    </script>
@endpush

@endsection