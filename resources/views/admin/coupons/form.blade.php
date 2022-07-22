@extends('layouts.admin.app')

@push('styles')
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="{{ asset('plugin/bootstrap-datepicker/bootstrap-datepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('plugin/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" rel="stylesheet">
    <style>
        label.red,span.red{color:red;}
    </style>
@endpush

@section('content')

    <div class="container-fluid p-0">
        <div class="card mb-4">
            <div class="card-header"><h5 class="m-0">Add Coupon</h5></div>
            <div class="card-body">
                <form class="form-horizontal frmValidate" name="f1" id="f1" method="POST" action="{{ route('admin.coupons.store') }}" enctype="multipart/form-data" novalidate="novalidate">
                    @csrf
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" for="coupon_code"> Coupon Code:<span class="red">*</span>&nbsp;</label>
                        <div class="col-lg-5 col-12"><input type="text" class="form-control" name="coupon_code" id="coupon_code" value="{{ old('coupon_code') }}" placeholder="Coupon Code"></div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" for="discount_type"> Discount Type :<span class="red">*</span>&nbsp;</label>
                        <div class="col-lg-5 col-12">
                            <select name="discount_type" id="discount_type" class="custom-select select2-demo valid">
                                <option value="fixed_price" @if(old('discount_type')=="fixed_price") selected @endif >Fixed Price</option>
                                <option value="percentage" @if(old('discount_type')=="percentage") selected @endif >Percentage</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" for="discount"> Discount:<span class="red">*</span>&nbsp;</label>
                        <div class="col-lg-4 col-10">
                            <input type="text" class="form-control" name="discount" id="discount" value="{{ old('discount') }}">
                        </div>
                        <div class="col-lg-1 col-2" style="margin-top:5px;padding-right:0px;padding-left:0px;font-size:16px;"><span id="discount_type_row">USD $</span></div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" for="validity_type"> Validity Type :<span class="red">*</span>&nbsp;</label>
                        <div class="col-lg-5 col-12">
                            <select name="validity_type" id="validity_type" class="custom-select select2-demo valid">
                                <option value="">Select Validity Type</option>
                                <option value="onetime" @if(old('validity_type')=="onetime") selected @endif >Onetime</option>
                                <option value="unlimited" @if(old('validity_type')=="unlimited") selected @endif>Unlimited</option>
                                <option value="limited" @if(old('validity_type')=="limited") selected @endif>Limited</option>
                            </select>
                        </div>
                    </div>
                    
                    <div id="validity_type_row" @if(old('validity_type')=="limited") style="display:show;" @else style="display:none;" @endif>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" for="limit_type"> Limit Type :<span class="red">*</span>&nbsp;</label>
                            <div class="col-lg-5 col-12">
                                <select name="limit_type" id="limit_type" class="custom-select select2-demo valid">
                                    <option value="numbers" @if(old('limit_type')=="numbers") selected @endif >Numbers of times coupon will be used</option>
                                    <option value="date" @if(old('limit_type')=="date") selected @endif >Date upto coupon is valid</option>                                
                                </select>
                            </div>
                        </div>    
                    </div>                    
                    <div class="form-group row" id="limit_numbers_row" @if(old('limit_type')=="numbers") style="display:show;" @else style="display:none;" @endif>
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" for="limit_numbers"> Numbers:<span class="red">*</span>&nbsp;</label>
                        <div class="col-lg-5 col-12"><input type="text" class="form-control" name="limit_numbers" id="limit_numbers" value="{{ old('limit_numbers') }}"></div>
                    </div>                    
                    <div class="form-group row" id="limit_date_row" @if(old('limit_type')=="date") style="display:show;" @else style="display:none;" @endif>
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" for="limit_date"> Date:<span class="red">*</span>&nbsp;</label>
                        <div class="col-lg-5 col-12"><input type="text" class="form-control" name="limit_date" id="limit_date" value="{{ old('limit_date') }}"></div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" for="notification_email">Notification Email:</label>
                        <div class="col-lg-5 col-12"><input type="text" class="form-control" name="notification_email" id="notification_email" value="{{ old('notification_email') }}" placeholder="Notification Email"></div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2 text-lg-right col-12 text-left" for="status"> Status :<span class="red">*</span>&nbsp;</label>
                        <div class="col-lg-5 col-12">
                            <select name="status" id="status" class="custom-select select2-demo valid">
                                <option value="">Select Status</option>
                                <option value="active" @if(old('status')=="active") selected @endif>Active</option>
                                <option value="inactive" @if(old('status')=="inactive") selected @endif>Inactive</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group row py-3">
                        <div class="py-3 col-lg-7 text-center">
                            <button name="btnpage" id="btnpage" class="btn btn-success" type="submit">&nbsp;<i class="fa fa-check bigger-110"></i>&nbsp;Submit </button>&nbsp; &nbsp; &nbsp;
                            <a href="{{ route('admin.coupons.index') }}" class="btn btn-danger">&nbsp;<i class="fa fa-times bigger-110"></i>&nbsp;Cancel</a>
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('plugin/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
        <script src="{{ asset('plugin/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
        <script type="text/javascript">
            let isRtl = $('html').attr('dir') === 'rtl';
            $('#limit_date').datepicker({
                clearBtn: true,
                format: "yyyy-mm-dd",
                orientation: isRtl ? 'auto right' : 'auto left'
            });
            $(document).ready(function () {
                $("#discount_type").on('change', function(){
                    let html = "";
                    if($(this).val() == "fixed_price"){
                        html = `USD $`;
                    } else {
                        html = `%`;
                    }
                    $("#discount_type_row").html(html);
                });
                $("#validity_type").on('change', function(){
                    if($(this).val() == "limited"){
                        $('#validity_type_row').show();
                        $('#limit_numbers_row').show();
                    } else {
                        $('#validity_type_row').hide();
                        $('#limit_numbers_row').hide();
                        $('#limit_date_row').hide();
                    }
                });
                $("#limit_type").on('change', function(){
                    if($(this).val() == "numbers"){
                        $('#limit_numbers_row').show();
                    } else {
                        $('#limit_numbers_row').hide();
                    }
                    if($(this).val() == "date"){
                        $('#limit_date_row').show();
                    } else {
                        $('#limit_date_row').hide();
                    }
                });
                $('#f1').validate({
                    rules:{
                        coupon_code: {
                            required: true,
                            remote: {url: "{{ route('admin.check-coupon-code') }}",type: "post",data:{'_token': '{{ csrf_token() }}' }}
                        },
                        discount_type: {required: true},
                        discount: {required: true, number:true,},
                        validity_type: { required: true },
                        limit_numbers: { required: true, number: true },                        
                        limit_date: { required: true },                        
                        notification_email: { email: true },                        
                        status: { required: true },                        
                    },
                    messages:{
                        coupon_code: {
                            required: 'Coupon code is required',
                            remote: "This coupon code is already used, please try with different coupon code."
                        },
                        discount_type: {required: 'Discount type is required'},
                        discount: {required: 'Discount field is required'},
                        validity_type: {required: "Validity type is required"},
                        limit_numbers: { required: "Numbers field is required" },                        
                        limit_date: { required: "Date field is required" },                        
                        status: {required: 'Status is required'},
                    },
                    errorElement:'label',
                    errorClass:'red',
                });
            });
        </script>
    @endpush
@endsection
