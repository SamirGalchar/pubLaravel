<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Fit-30') }} | @yield('title')</title>
    <meta name="author" content="{{ config('app.name', 'Fit-30') }}">
    <meta name="keywords" content="{{ config('app.name', 'Fit-30') }}">
    <meta name="title" content="{{ config('app.name', 'Fit-30') }}">
    <meta name="description" content="{{ config('app.name', 'Fit-30') }}">

    <meta property="og:title" content="{{ config('app.name', 'Fit-30') }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ URL::current() }}" />
    <meta property="og:image" content="{{ asset('front/images/og-image.png') }}" />
    <meta property="og:image:width" content="37" />
    <meta property="og:image:height" content="37" />
    <meta property="og:site_name" content="{{ config('app.name', 'Fit-30') }}" />
    <meta property="og:description" content="{{ config('app.name', 'Fit-30') }}" />
    
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@100;300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    
    @stack('css-links')    
    <link href="{{ asset('front/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('front/css/custom.css') }}" rel="stylesheet">
    @stack('css')
    
</head>
<body class="blue_bg">
    <header class="header   py-4 py-md-2 py-xxl-3">
        <div class="container">
            <div class="header_flex d-flex align-items-center justify-content-between">
            <div class="head_text">
                <a href="{{ route('home') }}" class="text-decoration-none">
                    <img src="{{ asset('front/images/workout_logo.png') }}" class="img-fluid">
                </a>
            </div>
            <nav class="navbar navbar-expand-md navbar-light  py-0">                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @if(Auth::check() && Auth::user()->role=='user')
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle profile_drop_down font-18 color-011e36 medium" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a href="{{ route('user.profile') }}" class="dropdown-item @if(request()->routeIs('user.profile')) active @endif">{{ __('Profile') }}</a>
                                    @if(Auth::user()->isPaid == 'Yes')
                                        <a href="{{ route('user.videos') }}" class="dropdown-item @if(request()->routeIs('user.videos')) active @endif">{{ __('Workout Videos') }}</a>
                                        <?php /* ?><a href="{{ route('user.unsubscribe') }}" class="dropdown-item @if(request()->routeIs('user.unsubscribe')) active @endif">{{ __('Unsubscribe') }}</a><?php */ ?>
                                    @else
                                        <a href="{{ route('user.free-trial') }}" class="dropdown-item @if(request()->routeIs('user.free-trial')) active @endif">{{ __('Free Trial') }}</a>
                                        <a href="{{ route('user.plans') }}" class="dropdown-item @if(request()->routeIs('user.plans') || request()->routeIs('user.purchase')) active @endif">{{ __('Workout Videos') }}</a>
                                    @endif                                    
                                    <a href="{{ route('user.change-password') }}" class="dropdown-item @if(request()->routeIs('user.change-password')) active @endif">{{ __('Change Password') }}</a>
                                   
                                    @php
                                        $subscription = App\Models\Subscriber::where(['user_id'=>Auth::user()->id, 'status'=>'active'])->get()->toArray();                    
                                        if($subscription):
                                            $toDate = \Carbon\Carbon::createFromFormat('Y-m-d', $subscription[0]['end_at']);
                                            $fromDate = \Carbon\Carbon::createFromFormat('Y-m-d', $subscription[0]['subscribed_at']);
                                            $diff_in_days = $toDate->diffInDays($fromDate);                    
                                        else:   
                                            $diff_in_days = 0;
                                        endif;
                                    @endphp
                                    @if($diff_in_days > 28)
                                        <a href="{{ route('user.unsubscribe') }}" class="@if(request()->routeIs('user.unsubscribe')) active @endif dropdown-item">Unsubscribe</a>
                                    @endif    
                                    <a href="https://fit-30.online/contact/" class="dropdown-item" target="_blank">Contact </a>
                                    <a class="dropdown-item" href="javascript:void();" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @else
                        <?php /* ?>
                            @if (Route::has('login'))
                                <li class="nav-item login_btn hvr-grow">
                                    <a class="nav-link font-18 text-white fw-bold "  href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item register_btn hvr-grow">
                                    <a class="nav-link font-18 text-white fw-bold" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        <?php */ ?>
                        @endif
                    </ul>
                </div>
            </nav>
            </div>            
        </div>
    </header>    
    <!-- header end -->
    
    @include('layouts.flash-message')
    @yield('content')
    
    <!-- footer-section --->
    <section class="enquiry_sec py-4 py-xl-2 py-xxl-3">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-12 col-xl-12 col-xxl-10">
                    <div class="row">
                        <div class="contact_info">
                            <ul class="list-unstyled list-inline px-34 align-items-md-center justify-content-between d-flex mb-0 flex-column flex-md-row">
                                <li class="list-inline-item">
                                    <p class="barlow medium font-20 color-f2f3f4 text-center text-md-end lh-27 ls_1 mb-0">
                                        All Rights Reserved © Cobra Sports Performance Limited 2017-{{ date('Y') }}
                                    </p>
                                </li>
                                <li class="list-inline-item">
                                    <ul class="list-unstyled list-inline mt-3 mb-lg-0 text-center text-md-end">
                                        <li class="list-inline-item"><a href="https://www.facebook.com/groups/381382130770638/" target="_blank"><img src="{{ asset('front/images/facebook.png') }}"></a></li>
                                        <li class="list-inline-item"><a href="https://www.instagram.com/cobraforce1/" target="_blank"><img src="{{ asset('front/images/instagram.png') }}"></a></li>
                                    </ul>
                                </li>                                   
                                <?php /*?>
                                <li class="list-inline-item">
                                    <p class="barlow medium font-20 color-f2f3f4 lh-27 ls_1 mb-xl-2 mb-xxl-3">For any enquiries about student enrolment,<br>eMail us:</p>
                                    <a href="mailto:{{ env('INFO_EMAIL') }}" class="barlow medium ls_1 font-18 color-f2f3f4 text-decoration-none d-flex align-items-center ">
                                        <div class="image">
                                            <img src="{{ asset('front/images/mail_icon.png') }}" alt="mail_icon.png" class="img-fluid d_icon">
                                            <img src="{{ asset('front/images/mail_icon_h.png') }}" alt="mail_icon_h.png" class="img-fluid h_icon">
                                        </div>
                                        <p class="mb-0 ps-3 text-decoration-underline">{{ env('INFO_EMAIL') }}</p>
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <div class="vl"></div>
                                </li>
                                <li class="list-inline-item">
                                    <p class="barlow medium font-20 color-f2f3f4 lh-27 ls_1 mb-xl-2 mb-xxl-3">For teaching purposes and other enquiries,<br>eMail us:</p>
                                    <a href="mailto:{{ env('INFO_EMAIL') }}" class="barlow medium ls_1 font-18 color-f2f3f4 text-decoration-none d-flex align-items-center">
                                        <div class="image">
                                            <img src="{{ asset('front/images/mail_icon.png') }}" alt="mail_icon.png" class="img-fluid d_icon">
                                            <img src="{{ asset('front/images/mail_icon_h.png') }}" alt="mail_icon_h.png" class="img-fluid h_icon">
                                        </div>
                                        <p class="mb-0 ps-3 text-decoration-underline">{{ env('INFO_EMAIL') }}</p>
                                    </a>
                                </li>
                                <?php */?>
                            </ul>                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- footer end -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    
    <script>
        const SITE_IMG_JS = "{{ asset('front/images/') }}";
    </script>    
    <script src="{{ asset('plugin/function/customFunction.js') }}"></script> 
    
    @stack('scripts')    
</body>
</html>
