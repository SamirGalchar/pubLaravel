@extends('layouts.admin.app')
@php
    extract($info);
    
    $timeline_date = ($timeline_date) ? unserialize($timeline_date) : "";
    $timeline_heading = ($timeline_heading) ? unserialize($timeline_heading) : "";
    $timeline_descriprion = ($timeline_descriprion) ? unserialize($timeline_descriprion) : "";    
    $pricing_heading = ($pricing_heading) ? unserialize($pricing_heading) : "";    
    $pricing_price = ($pricing_price) ? unserialize($pricing_price) : "";    
    
@endphp

@section('content')
<style>
    .font-weight-bold u{
      color:#26B4FF;
    }
</style>

    <div class="container-fluid p-0">
        <div class="card mb-4">
            <div class="card-header"><h5 class="m-0">Proposal Detail</h5></div>
            <div class="card-body">
                <form class="form-horizontal frmValidate" name="f1" id="f1" method="POST" action="javascript:void(0);" enctype="multipart/form-data" novalidate="novalidate">
                    
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" >Status:&nbsp;</label>
                        <div class="col-lg-6 col-12">
                            <select class="custom-select" onchange="isActive(<?php echo $id; ?>)" id="proposalStatus">
                                <option @if($isActive == "active") selected @endif  value="active">Active</option>                                
                                <option @if($isActive == "inactive") selected @endif value="inactive">Deactive</option>                                
                            </select>
                        </div>
                    </div>
                    
                    <h4 class="font-weight-bold"><u>Property Details</u></h4>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" >Agent Name:&nbsp;</label>
                        <div class="col-lg-6 col-12">
                            <p class="form-control">{{ $user_name }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" >Property Address:&nbsp;</label>
                        <div class="col-lg-6 col-12">
                            <p class="form-control">{{ $address }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" >Price&nbsp;</label>
                        <div class="col-lg-6 col-12">
                            <p class="form-control">{{ $price }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" >Type&nbsp;</label>
                        <div class="col-lg-6 col-12">
                            <p class="form-control">{{ $type }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" >Status&nbsp;</label>
                        <div class="col-lg-6 col-12">
                            <p class="form-control">{{ $status }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" >Bed&nbsp;</label>
                        <div class="col-lg-6 col-12">
                            <p class="form-control">{{ $bedrooms }}</p>
                        </div>                        
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" >Bath&nbsp;</label>
                        <div class="col-lg-6 col-12">
                            <p class="form-control">{{ $bathrooms }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" >Car&nbsp;</label>
                        <div class="col-lg-6 col-12">
                            <p class="form-control">{{ $car }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" >Suburb&nbsp;</label>
                        <div class="col-lg-6 col-12">
                            <p class="form-control">{{ $suburb }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" >Google Maps&nbsp;</label>
                        <div class="col-lg-6 col-12">
                            <p class="form-control">{{ $google_map }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" >Heading&nbsp;</label>
                        <div class="col-lg-6 col-12">
                            <p class="form-control">{{ $heading }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" >Description&nbsp;</label>
                        <div class="col-lg-6 col-12">
                            <p class="form-control">{{ $description }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" >Features&nbsp;</label>
                        <div class="col-lg-6 col-12">
                            <p class="form-control">{{ $features }}</p>
                        </div>
                    </div>                    
                    <div class="form-group row mb-0">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left">Photos:&nbsp;</label>                        
                        <div class="col-lg-5 col-12 my-0 py-0 mb-4">
                            <div>
                            @if(count($propopsalImages) > 0)    
                                @php
                                    $path = public_path('uploads/proposal/');
                                @endphp
                                @foreach($propopsalImages as $image_name)
                                    @if(file_exists($path.$image_name['name']) && !empty($image_name['name']))
                                        <img src="{{ asset('uploads/proposal/'.$image_name['name'] ) }}" width="60" height="60">
                                    @endif
                                @endforeach
                            @else
                                <br><br>
                            @endif
                            </div>
                        </div>                        
                    </div>   
                    
                    <h4 class="font-weight-bold"><u>Similar Sales</u></h4>
                    <div class="form-group row mb-0">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left">Similar Sales:&nbsp;</label>                        
                        <div class="col-lg-5 col-12 my-0 py-0 mb-4">
                            <div>
                                @if(count($sSalesImages) > 0)    
                                    @foreach($sSalesImages as $image_name)
                                        <img src="{{ asset('uploads/similar-sales/'.$image_name ) }}" width="60" height="60">                                    
                                    @endforeach
                                @else
                                    <br><br>
                                @endif
                            </div>
                        </div>                        
                    </div>
                    
                    <h4 class="font-weight-bold"><u>Case Studies</u></h4>
                    <div class="form-group row mb-0">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left">Case Studies:&nbsp;</label>                        
                        <div class="col-lg-5 col-12 my-0 py-0 mb-4">
                            <div>
                                @if(count($cStudyImages) > 0)    
                                    @foreach($cStudyImages as $image_name)
                                        <img src="{{ asset('uploads/case-studies/'.$image_name ) }}" width="60" height="60">                                    
                                    @endforeach
                                @else
                                    <br><br>
                                @endif
                            </div>
                        </div>                        
                    </div>   
                    
                    <h4 class="font-weight-bold"><u>Property Timeline</u></h4>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left">Property Timeline:&nbsp;</label>
                        @if(!empty($timeline_date))
                            @php
                                $size = count($timeline_date);
                            @endphp
                            <div class="row col-lg-8 col-12">
                            @for ($i = 0; $i < $size; $i++)                                
                                <div class="row col-lg-12 col-12">
                                    <div class="col-lg-6 col-12">
                                        <p class="form-control">{{ (isset($timeline_date[$i])) ? date('d/M/Y', strtotime($timeline_date[$i])) : ""  }}</p>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <p class="form-control">{{ (isset($timeline_heading[$i])) ? $timeline_heading[$i] : "" }}</p>
                                    </div>   
                                    <div class="col-lg-6 col-12">
                                        <p class="form-control">{{ (isset($timeline_descriprion[$i])) ? $timeline_descriprion[$i] : "" }}</p>
                                    </div>
                                </div>    
                            @endfor
                            </div>
                        @endif
                    </div>
                    
                    <h4 class="font-weight-bold"><u>Property Pricing</u></h4>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" >Sale Price:&nbsp;</label>
                        <div class="col-lg-6 col-12">
                            <p class="form-control">{{ $sale_price }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" >Price Range:&nbsp;</label>
                        <div class="col-lg-2 col-12">
                            <p class="form-control">{{ $price_range_from }}</p>
                        </div>
                        <label class="col-form-label col-lg-1 text-lg-center col-12 text-center" >TO&nbsp;</label>
                        <div class="col-lg-2 col-12">
                            <p class="form-control">{{ $price_range_to }}</p>
                        </div>
                    </div>                    
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left">Pricing:&nbsp;</label>
                        @if(!empty($pricing_heading))
                            @php
                                $size = count($pricing_heading);
                            @endphp
                            <div class="row col-lg-8 col-12">
                            @for ($i = 0; $i < $size; $i++)                                   
                                <div class="col-lg-6 col-12">
                                    <p class="form-control">{{ (isset($pricing_heading[$i])) ? $pricing_heading[$i] : ""  }}</p>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <p class="form-control">{{ (isset($pricing_price[$i])) ? $pricing_price[$i] : ""  }}</p>
                                </div>                                
                            @endfor
                            </div>
                        @endif
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" >Total Marketing Cost:&nbsp;</label>
                        <div class="col-lg-6 col-12">
                            <p class="form-control">{{ $total_marketing_cost }}</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" >Commission:&nbsp;</label>
                        <div class="col-lg-6 col-12">
                            <p class="form-control">{{ $commission }}</p>
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

    @push('scripts')
        <script>
            function isActive(id){
                if(id>0 && confirm("Are You sure want to change status!")){     
                    let status = $("#proposalStatus").val();
                    let param = {'_token':'{{ csrf_token() }}','id':id, 'status':status};
                    console.log(param);
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.changeProposalStatus') }}",
                        async:false,
                        data: param,                        
                        success: function (data) {
                            if(data){
                                alert("Status updated successfully");
                            } else {
                                alert("Status not updated, Please try again");
                            }
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                }
            }
        </script>
    @endpush

@endsection
