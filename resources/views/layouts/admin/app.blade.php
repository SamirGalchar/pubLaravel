<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="default-style">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    {{--<script src="{{ asset('js/app.js') }}" defer></script>--}}

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900" rel="stylesheet">

    <!-- Icon fonts -->
    <link rel="stylesheet" href="{{ asset('/assets/vendor/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/vendor/fonts/ionicons.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/vendor/fonts/linearicons.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/vendor/fonts/open-iconic.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/vendor/fonts/pe-icon-7-stroke.css') }}">

    <!-- Core stylesheets -->
    <link rel="stylesheet" href="{{ asset('/assets/vendor/css/rtl/bootstrap.css') }}" class="theme-settings-bootstrap-css">

    <link rel="stylesheet" href="{{ asset('/assets/vendor/css/rtl/appwork.css') }}" class="theme-settings-appwork-css">
    <link rel="stylesheet" href="{{ asset('/assets/vendor/css/rtl/theme-corporate.css') }}" class="theme-settings-theme-css">
    <link rel="stylesheet" href="{{ asset('/assets/vendor/css/rtl/colors.css') }}" class="theme-settings-colors-css">
    <link rel="stylesheet" href="{{ asset('/assets/vendor/css/rtl/uikit.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/css/demo.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-sortable/bootstrap-sortable.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-sortable/bootstrap-sortable.css') }}">
    @stack('styles')

    <script src="{{ asset('/assets/vendor/js/material-ripple.js') }}"></script>
    <script src="{{ asset('/assets/vendor/js/layout-helpers.js') }}"></script>

    <!-- Core scripts -->
    <script src="{{ asset('/assets/vendor/js/pace.js') }}"></script>
    <script src="{{ asset('/plugin/js/jquery.min.js') }}"></script>

    <script src="{{ asset('/plugin/validator/jquery.validate.js') }}"></script>
    <script src="{{ asset('/plugin/validator/additional-methods.js') }}"></script>

    <!-- Libs -->
    <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}">

    <script src="{{ asset('/plugin/ckeditor/ckeditor.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        SITE_IMG_JS = '{{ asset('front/images/') }}';
    </script>
</head>
<body>
<div class="page-loader">
    <div class="bg-primary"></div>
</div>
<div class="layout-wrapper layout-1 layout-without-sidenav">
    <div class="layout-inner">
        <div id="menu_area" class="menu-area">
            <nav class="navbar navbar-expand-xl bg-secondary">
                <a href="{{ route('admin.dashboard') }}" class="navbar-brand app-brand demo py-0 mr-4 text-white ">
                    <img src="{{ asset('assets/images/logo-admin.png') }}" height="40" width="40">
                    <?php /*?><span class="app-brand-text demo font-weight-normal ml-2 app-brand-logo ui-w-100">{{ config('app.name', 'Laravel') }}</span><?php */?>
                </a>
                <button class="navbar-toggler text-big" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active"><a href="{{ route('admin.dashboard') }}" class="nav-link">Home <span class="sr-only">(current)</span></a></li>
                        <li class="nav-item"><a href="{{ route('admin.users.index') }}" class="nav-link">Users</a></li>
                        <li class="nav-item"><a href="{{ route('admin.videos.index') }}" class="nav-link">Videos</a></li>
                        <li class="nav-item"><a href="{{ route('admin.membership.index') }}" class="nav-link">Subscription</a></li>
                        <li class="nav-item"><a href="{{ route('admin.pages.index') }}" class="nav-link">CMS Pages</a></li>
                        <li class="nav-item"><a href="{{ route('admin.email-template.index') }}" class="nav-link">Email Template</a></li>                        
                        <li class="nav-item"><a href="{{ route('admin.coupons.index') }}" class="nav-link">Coupons</a></li>
                    </ul>
                    <div class="demo-navbar-user nav-item dropdown">
                        <a class="nav-link text-white dropdown-toggle" href="#" data-toggle="dropdown">
                          <span class="d-inline-flex flex-lg-row-reverse align-items-center align-middle">
                            {{--<img src="assets/img/avatars/1.png" alt class="d-block ui-w-30 rounded-circle">--}}
                            <span class="px-1 mr-lg-2 ml-2 ml-lg-0">{{ auth()->user()->name }}</span>
                          </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="{{ route('admin.profile') }}" class="dropdown-item">
                                <i class="ion ion-ios-person text-lightest"></i> &nbsp; My profile</a>
                            <a href="{{ route('admin.changePassword') }}" class="dropdown-item">
                                <i class="ion ion-md-settings text-lightest"></i> &nbsp; Change Password</a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}" class="dropdown-item"
                                   onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    <i class="ion ion-ios-log-out text-danger"></i> &nbsp; Log Out
                                </a>
                            </form>                            
                        </div>
                    </div>
                </div>
            </nav>
        </div>
        <div class="layout-container">
            <div class="layout-content">
                <div class="container-fluid flex-grow-1 container-p-y">
                    @include('layouts.admin.flash-message')
                    @if($currentUser = auth()->user())@include('layouts.admin.breadcrumbs')@endif
                    @yield('content')
                </div>
                <!-- Layout footer -->
                <nav class="layout-footer footer bg-footer-theme">
                    <div class="container-fluid d-flex flex-wrap justify-content-between text-center container-p-x pb-3 bg-secondary text-white ">
                        <div class="w-100 pt-3">
                            <span class="footer-text font-weight-bolder text-white">{{ config('app.name', 'Laravel') }}</span> &copy; {{ (date("Y")-1) }}-{{ (date("Y")) }}</span>
                        </div>
                    </div>
                </nav>
                <!-- / Layout footer -->
            </div>
        </div>
    </div>
</div>
<!-- Core scripts -->
<script src="{{ asset('/assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('/assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('/assets/vendor/js/sidenav.js') }}"></script>

<!-- Libs -->
<script src="{{ asset('/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('plugin/function/customFunction.js') }}"></script>
<script>
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
</script>
<script src="{{ asset('assets/vendor/libs/datatables/datatables.js') }}"></script>
<script src="{{ asset('plugin/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('/vendor/datatables/buttons.server-side.js') }}"></script>

@stack('scripts')

</body>
</html>
