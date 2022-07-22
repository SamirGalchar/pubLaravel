@extends('layouts.admin.app')
@php
    extract($info);
    
    $marketing_name   =  (!empty($marketing_name)) ? unserialize($marketing_name) : "";
    $marketing_price =  (!empty($marketing_price)) ? unserialize($marketing_price) : "";
    
@endphp

@section('content')

    <div class="container-fluid p-0">
        <div class="card mb-4">
            <div class="card-header"><h5 class="m-0">Proposal Detail</h5></div>
            <div class="card-body">
                <form class="form-horizontal frmValidate" name="f1" id="f1" method="POST" action="javascript:void(0);" enctype="multipart/form-data" novalidate="novalidate">
                    
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" >Agent Name:&nbsp;</label>
                        <div class="col-lg-6 col-12">
                            <p class="form-control">{{ $user_name }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" >Sub Title:&nbsp;</label>
                        <div class="col-lg-6 col-12">
                            <p class="form-control">{{ $sub_title }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" >Title:&nbsp;</label>
                        <div class="col-lg-6 col-12">
                            <p class="form-control">{{ $title }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" >Address:&nbsp;</label>
                        <div class="col-lg-6 col-12">
                            <p class="form-control">{{ $address }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" >Description:&nbsp;</label>
                        <div class="col-lg-6 col-12">
                            <p class="form-control">{{ $description }}</p>
                        </div>
                    </div>
                    <div class="clearfix"></div><div class="space-4"></div><div class="form-group row mb-0">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left">Photo:&nbsp;</label>                        
                        <div class="col-lg-5 col-12 my-0 py-0 mb-4">
                            <div>
                                @php
                                    $path = public_path('uploads/case-studies/');
                                @endphp
                                @if(file_exists($path.$image_name))
                                    <img src="{{ asset('uploads/case-studies/'.$image_name ) }}" width="300px" height="150px">
                                @endif
                            </div>
                        </div>                        
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" >Price Guide:&nbsp;</label>
                        <div class="col-lg-6 col-12">
                            <p class="form-control">{{ $price_guide }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" >Sold Guide:&nbsp;</label>
                        <div class="col-lg-6 col-12">
                            <p class="form-control">{{ $sold_price }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" >Days On Market:&nbsp;</label>
                        <div class="col-lg-6 col-12">
                            <p class="form-control">{{ $day_on_market }}</p>
                        </div>
                    </div>                    
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" >Sales Strategy:&nbsp;</label>
                        @if(!empty($marketing_name))
                            @php
                                $size = count($marketing_name);
                            @endphp
                            <div class="row col-lg-6 col-12">
                            @for ($i = 0; $i < $size; $i++)                                
                                    <div class="col-lg-6 col-12">
                                        <p class="form-control">{{ $marketing_name[$i] }}</p>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <p class="form-control">{{ $marketing_price[$i] }}</p>
                                    </div>                                    
                            @endfor
                            </div>
                        @endif
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" >Total Profit:&nbsp;</label>
                        <div class="col-lg-6 col-12">
                            <p class="form-control">{{ ($total_profit) ? $total_profit : 0 }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" >Return To Investment:&nbsp;</label>
                        <div class="col-lg-6 col-12">
                            <p class="form-control">{{ ($return_to_investment) ? $return_to_investment : 0 }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" >Created At:&nbsp;</label>
                        <div class="col-lg-6 col-12">
                            <p class="form-control">{{ date('d/M/Y', strtotime($created_at)) }}</p>
                        </div>
                    </div>
                    
                    <div class="form-group row py-3">
                        <div class="py-3 col-lg-7 text-center">
                            <a href="{{ route('admin.proposals.index') }}" class="btn btn-info">&nbsp;<i class="fa fa-backward bigger-110"></i>&nbsp;Back</a>
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>

@endsection
