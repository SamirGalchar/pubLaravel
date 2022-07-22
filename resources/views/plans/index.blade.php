@extends('layouts.app')
@section('title',"Subscribe")
@section('content')

    <!---wesite-in-process---->
    <section class="workig_process working_bg py-100">
        <div class="container">
            <div class="working_text">
                <h6 class="poppins font-40 color-e3e0e0 text-center lh-39 ls-1 mb-0 medium">Subscribe</h6>
            </div>            
        </div>    
    </section>

   <section class="plans_sec bg-white p-80">
       <div class="container">
           <div class="row">
                @include('user.partial.sidebar')
                <div class="col-12 col-lg-9 my_card profile_card">
                    <div class="row">
                        <div class="col-12">
                            <h6 class="card-header text-center text-white">Subscription</h6>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-12 col-lg-11">
                            <div class="row py-60">                                
                                @if(count($plans) > 0)
                                    @foreach($plans as $plan)
                                        <div class="col-12 col-md-12">
                                            <div class="plan_box bg-white">
                                                <h6 class="font-30 text-white poppins fw-bold plan_heading text-center mb-0">{{ $plan['name'] }}</h6>
                                                <div class="box_text text-center  ">
                                                    <p class="font-24 color-4a617c poppins medium mb-md-2 mb-xxl-3">{!! $plan['sort_description'] !!}</p>
                                                    <p class="font-30 color-08284d poppins fw-bold">USD ${{ $plan['price'] }}/monthly for {{ $plan['validity'] }} months</p>
                                                    <p class="font-16 color-4a617c poppins fw-normal">{!! $plan['description'] !!}</p>
                                                    <div class="plan_box_btn">                                                        
                                                        <form method="post" action="{{ route('user.purchase') }}" id="purchaseForm">
                                                            <div class="row mb-3">
                                                                <label class="col-md-4 col-form-label text-md-end" for="coupon_code">Coupon</label>
                                                                <div class="col-12 col-md-6">
                                                                    <input type="text" class="form-control" name="coupon_code" id="coupon_code" value="{{ old('coupon_code') }}" placeholder="Enter Coupon code here" style="color:#000 !important;" />
                                                                </div>
                                                            </div>
                                                            @csrf
                                                            <button type="submit" class="text-decoration-none medium font-18 poppins text-white plan_btn d-inline-block">Subscribe Now</button>
                                                        </form>                                                        
                                                    </div>
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
                    </div>
                </div>
           </div>
       </div>
   </section>
    
    @push('scripts')
    <script>
        $("#purchaseForm").submit(function(){
            startLoaderAjax();
        });                
    </script> 
    @endpush    

@endsection
