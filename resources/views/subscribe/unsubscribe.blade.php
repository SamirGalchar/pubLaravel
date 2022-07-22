@extends('layouts.app')
@section('title',"Profile")
@section('content')

<section class="workig_process working_bg py-100">
    <div class="container">
        <div class="working_text">
            <h6 class="poppins font-40 color-e3e0e0 text-center lh-39 ls-1 mb-0 medium">{{ __('Unsubscribe') }}</h6>
        </div>
    </div>
</section>

<div class="bg-white p-80">
    <div class="container">
        <div class="row justify-content-center min_height">
            @include('user.partial.sidebar')
            <div class="col-lg-9">
                <div class="card my_card profile_card">
                    <div class="card-header text-center text-white">{{ __('Unsubscribe') }}</div>
                    <div class="card-body">
                        <form method="POST" id="unsubscribeForm" action="{{ route('user.unsubscribe-membership') }}">
                            @csrf
                            <input type="hidden" name="uid" value="{{ Auth::user()->id }}">
                            <div class="row mb-0">
                                <center>
                                    <div class="col-md-6">
                                        <button type="button" id="submitButton" class="btn btn-lg btn-danger">{{ __('Click Here To Unsubscribe Subscription') }}</button>
                                        <p class="barlow medium font-20 color-f2f3f4 lh-27 pt-4 ls_1">For fitness support, email us at <a class="text-white text-decoration-none" href="mailto:fitnessconsultant@cf-30workout.com">fitnessconsultant@cf-30workout.com</a></p>
                                    </div>
                                </center>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $("#submitButton").on('click', function() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to unsubscribe subscription!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Unsubscribe!'
        }).then((result) => {
            if (result.isConfirmed) {
                $('form#unsubscribeForm').submit();
                startLoaderAjax();
            }
        })
    });
</script>
@endpush

@endsection