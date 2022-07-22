@extends('layouts.app')
@section('title',"Home")
@section('content')

    <!---wesite-in-process---->
    <section class="workig_process working_bg py-70">
        <div class="container">
            <div class="working_text">
                <h2 class="barlow font-27 color-e3e0e0 text-center lh-39 ls-1 mb-0 medium">Website in the process of being revamped.<br>Sorry for the inconvenience caused.</h2>
            </div>
            <div class="loader">
                <div class="loading mx-auto"></div>
            </div>
        </div>    
    </section>

    <!--about-us-->
    <section class="about_us py-82 bg-white">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10 col-xl-8 col-xxl-10">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="about_us_img text-center text-lg-start">
                                <img src="{{ asset('front/images/abou_us_image.png') }}" alt="abou_us_image" class="img-fluid">
                            </div>
                        </div>
                        <div class="col-12 col-lg-6 my-auto">
                            <div class="about_us_text">
                                <p class="barlow medium font-18 color-011123 ls-1 lh-27">Fit-30 Hong Kong offers a personalised British education with outstanding teachers and facilities. HISHK champions an ambitious education believing that there are no limits to what we can achieve for ourselves and for others.</p>
                                <p class="barlow medium font-18 color-011123 ls-1 lh-27 mb-0">We enable every student to develop skills needed for the 21st century so that they can succeed academically, socially and personally.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
