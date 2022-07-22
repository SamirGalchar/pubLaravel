@extends('layouts.app')

@section('title','404 not found')

@section('content')
    @php $title = '404 Not Found'; @endphp
    @include('layouts.innerTitle',compact('title'))
    <section class="privacy_section py-70 black_bg">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <p class="text-center">Oops!</p>
                    <p class="font20 text-center">Something went wrong with your last action.</p>
                    <p class="font20 text-center">This may be a temporary error so try refreshing your page or try to visit us after 5-10 minutes. If the problem persists, take a screenshot and contact us on email <a href="mailto:info@thepropertyforsale.co.au" class="title_color">info@thepropertyforsale.co.au</a>.</p>
                </div>
                <div class="col-md-12 text-center">
                    <a class="signin_btn  text-center d-inline-block grey_color roboto_slab font18 semibold w-50" href="{{ url()->previous() }}">Go Back</a>
                </div>
            </div>
        </div>
    </section>
@endsection
