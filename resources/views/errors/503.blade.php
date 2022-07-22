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
                    <p class="font20 text-center">Sorry! it is currently down for maintenance.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
