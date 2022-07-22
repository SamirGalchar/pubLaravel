@extends('layouts.admin.app')

@section('content')
    <div class="row">
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
            <div class="card text-center my-3">
                <a href="{{ route('admin.users.index') }}">
                    <div class="card-body">
                        <p class="card-text">
                            <img src="{{ asset('assets/images/User-Group-icon.png') }}" alt="" class="img-fluid" width="50" height="50" />
                            <h4>{{ __('Users') }}</h4>
                        </p>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
            <div class="card text-center my-3">
                <a href="{{ route('admin.videos.index') }}">
                    <div class="card-body">
                        <p class="card-text">
                            <img src="{{ asset('assets/images/videos.png') }}" alt="" class="img-fluid" width="50" height="50" />
                            <h4>Videos</h4>
                        </p>
                    </div>
                </a>
            </div>
        </div>        
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
            <div class="card text-center my-3">
                <a href="{{ route('admin.membership.index') }}">
                    <div class="card-body">
                        <p class="card-text">
                            <img src="{{ asset('assets/images/plans.png') }}" alt="" class="img-fluid" width="50" height="50" />
                            <h4>Plan for Subscriber</h4>
                        </p>
                    </div>
                </a>
            </div>
        </div>        
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
            <div class="card text-center my-3">
                <a href="{{ route('admin.pages.index') }}">
                    <div class="card-body">
                        <p class="card-text">
                            <img src="{{ asset('assets/images/cmspage.png') }}" alt="" class="img-fluid" width="50" height="50" />
                            <h4>CMS Page</h4>
                        </p>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
            <div class="card text-center my-3">
                <a href="{{ route('admin.email-template.index') }}">
                    <div class="card-body">
                        <p class="card-text">
                            <img src="{{ asset('assets/images/email_template1.png') }}" alt="" class="img-fluid" width="50" height="50" />
                            <h4>Email Template</h4>
                        </p>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
            <div class="card text-center my-3">
                <a href="{{ route('admin.coupons.index') }}">
                    <div class="card-body">
                        <p class="card-text">
                            <img src="{{ asset('assets/images/coupons.png') }}" alt="" class="img-fluid" width="50" height="50" />
                            <h4>Coupons</h4>
                        </p>
                    </div>
                </a>
            </div>
        </div>
        
        
    </div>    
@endsection
