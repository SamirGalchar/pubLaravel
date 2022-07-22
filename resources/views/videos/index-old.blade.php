@extends('layouts.app')
@section('title',"Workout Videos")
@section('content')

<section class="workig_process working_bg py-100">
    <div class="container">
        <div class="working_text">
            <h6 class="poppins font-40 color-e3e0e0 text-center lh-39 ls-1 mb-0 medium">Workout Videos</h6>
        </div>
    </div>    
</section>

<section class="video_sec bg-white p-80">
    <div class="container">
        <div class="row">
            @include('user.partial.sidebar')
            <div class="col-12 col-lg-9 my_card">
                <div class="row">
                    <div class="col-12">
                        <h6 class="card-header text-center text-white">Workout Videos</h6>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-12 col-md-11">
                        <div class="col-12">
                            <br><br>
                            <h6 class="text-center text-white">You will get a new workout video every week.</h6>
                        </div>
                        <div class="row py-60 ">
                            @if(count($videos)>0)
                                @foreach($videos as $video)
                                    <div class="col-12 col-md-12 col-lg-12 mb-37">
                                        <div class="sts_video_box w-100 bg-white">                                        
                                            <iframe width="100%" height="270" src="{{ $video['link'] }}&amp;badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479" title="{{ $video['name'] }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="margin-top:20px;"></iframe>
                                            <div class="video_box_text p-46 text-center text-md-start ">
                                                <h6 class="poppins semibold font-18 color-08284d lh-24 mb-13">{{ $video['name'] }}</h6>                                                
                                                <p class="karla regular font-15 color-4a617c  mb-0">{{ ( strlen($video['description']) > 1000 ) ?  substr($video['description'],0, 1000).'...' : substr($video['description'],0, 1000) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <h3 class="text-center text-white">{{ __('No Records Found') }}</h3>
                            @endif
                        </div>
                    </div>
                </div>
                @if(Auth::user()->isPaid == 'Yes')
                    <div class="row justify-content-center">
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
</section>

@push('scripts')
    <script src="https://player.vimeo.com/api/player.js"></script>
@endpush

@endsection
