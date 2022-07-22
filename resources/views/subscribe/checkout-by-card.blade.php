@extends('layouts.app')

@section('title',"Checkout")

@section('content')

<section class="workig_process working_bg py-100">
    <div class="container">
        <div class="working_text">
            <h6 class="poppins font-40 color-e3e0e0 text-center lh-39 ls-1 mb-0 medium">Checkout</h6>
        </div>
    </div>    
</section>

<div class="bg-white p-80">
    <div class="bg-white p-80">
        <div class="container">
            <div class="row">
                @include('user.partial.sidebar')
                <div class="col-12 col-lg-9 profile_card">
                    @if(count($plan) > 0)    
                        @php
                            $plan = $plan[0];            
                        @endphp
                        <section class="investment_section">
                            <form id="subscriberForm" name="subscriberForm" method="post" action="{{ route('user.subscribe') }}">
                                {{ csrf_field() }}
                                <input type="hidden" name="plan_price" id="plan_price" value="{{ $plan['price'] }}">
                                <input type="hidden" name="plan_id" id="plan_id" value="{{ $plan['id'] }}">
                                <input type="hidden" name="plan_name" id="plan_name" value="{{ $plan['name'] }}">
                                <div class="container px-0">
                                    <div class="row justify-content-center mx-0 w-100 ">
                                        <div class="col-12 px-0 my_card">
                                            <h1 class="font36 medium  card-header text-center text-white">Card Details</h1>
                                            <br>
                                            <div class="row steps_form1 mx-0 w-100 justify-content-center card_deatail_spacing">
                                                <div class="col-12 col-md-10 form-group">
                                                    <label class="text-white font16 font-weight-normal mb-2">Credit Card Number<span>*</span></label>
                                                    <input type="text" class="form-control login_form_field" placeholder="Credit Card Number" name="card_number" id="card_number" value="{{ old("card_number") }}">
                                                </div>
                                                <div class="col-12 col-md-10 form-group">
                                                    <label class="text-white font16 font-weight-normal mb-2">Name On Card<span>*</span></label>
                                                    <input type="text" class="form-control login_form_field" placeholder="Name On Card" name="card_name" id="card_name" value="{{ old("card_name") }}">
                                                </div>
                                                <div class="col-12 col-md-5 form-group">
                                                    <label class="text-white font16 font-weight-normal mb-2">Validity Month<span>*</span></label>
                                                    <select class="form-select login_form_field" id="expiry_month" name="expiry_month">
                                                        <option value="">Validity Month</option>
                                                        <?php
                                                        for ($i = 1; $i <= 12; $i++) {
                                                            echo "<option value='" . $i . "'>" . $i . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-12 col-md-5 form-group">
                                                    <label class="text-white font16 font-weight-normal mb-2">Validity Year<span>*</span></label>
                                                    <select class="form-select login_form_field" id="expiry_year" name="expiry_year">
                                                        <option value="">Validity Year</option>
                                                        <?php
                                                        for ($i = 0; $i <= 30; $i++) {
                                                            $year = date('Y', strtotime('+' . $i . ' years'));
                                                            echo "<option value='" . $year . "'>" . $year . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-12 col-md-10 form-group">
                                                    <label class="text-white font16 font-weight-normal mb-2">CVV / Security Number<span>*</span></label>
                                                    <input type="text" class="form-control login_form_field" placeholder="CVV / Security Number" name="cvv" id="cvv" value="{{ old("cvv") }}">
                                                </div>
                                                <div class="col-12 col-md-10 form-group">
                                                    <button type="submit" class="signin_btn btn_style text-center d-inline-block grey_color roboto_slab  font-18 text-white medium"> Proceed to Subscribe </button>
                                                </div>                                                    
                                            </div>
                                        </div>
                                        <!-- Right -->
                                        <div class="col-12 px-0 mt-3 my_card profile_card">
                                            <div class="row steps_form1 mx-0 w-100 privacy_section justify-content-center">
                                                <h1 class="card-header text-center text-white">Subscription Details</h1>
                                                <br>
                                                <div class="col-12 col-md-8 col-lg-8 col-xxl-7 plan_detail_sec">

                                                    <div class="row justify-content-center">
                                                        <div class="col-4 col-md-4 pt-4">
                                                            <h4 class="text-white font-25">Plan Name</h4>
                                                        </div>
                                                        <div class="col-8 col-md-8 pt-4">
                                                            <label class="text-white font16 font-weight-normal mb-2">{{ $plan['name'] ?? '-' }}</label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-4 col-md-4 pt-4">
                                                            <h4 class="text-white font-25">Validity</h4>
                                                        </div>
                                                        <div class="col-8 col-md-8 pt-4">
                                                            <label class="text-white font16 font-weight-normal mb-2">{{ $plan['validity'] ?? '-' }} Months</label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-4 col-md-4 pt-4">
                                                            <h4 class="text-white font-25">Description</h4>
                                                        </div>
                                                        <div class="col-8 col-md-8 pt-4">
                                                            <label class="text-white font16 font-weight-normal mb-2">{!! $plan['description'] ?? '-' !!}</label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-4 col-md-4 pt-4">
                                                            <h4 class="text-white font-25">Price</h4>
                                                        </div>
                                                        <div class="col-8 col-md-8 pt-4">
                                                            <label class="text-white font16 font-weight-normal mb-2">${{ $plan['price'] }}/monthly for {{ $plan['validity'] }} months</label>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>

                                        </div>
                                        <!-- End: Right -->
                                    </div>
                                </div>
                            </form>
                        </section>
                    @else
                    <section class="investment_section">                        
                        <div class="col-12 px-0 mt-3 my_card profile_card">
                            <div class="row steps_form1 mx-0 w-100 privacy_section justify-content-center">
                                <h1 class="card-header text-center text-white">Subscription</h1>
                                <br>
                                <div class="col-12 col-md-8 col-lg-8 col-xxl-7 plan_detail_sec">
                                    <h3 class="text-center text-white">{{ __('No Records Found') }}</h3>                                        
                                </div>
                            </div>
                        </div>                      
                    </section>    
                    @endif
                </div>
            </div>   
        </div>
    </div>    
</div>
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js" integrity="sha512-37T7leoNS06R80c8Ulq7cdCDU5MNQBwlYoy1TX/WUsLFC2eYNqtKlV0QjH7r8JpG/S0GUMZwebnVFLPd6SU5yg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>
    <script>
        $(document).ready(function () {
            /*$.validator.addMethod("creditcard", function(value, element, param) {
                if (/[^0-9-]+/.test(value)) {
                        return false;
                }
                value = value.replace(/\D/g, "");
                var validTypes = 0x0001 | 0x0002 | 0x0004 | 0x0008 | 0x0010 | 0x0020 | 0x0040 | 0x0080;

                if (validTypes & 0x0001 && /^(5[12345])/.test(value)) { //mastercard
                        return value.length == 16;
                }
                if (validTypes & 0x0002 && /^(4)/.test(value)) { //visa
                        return value.length == 16;
                }
                if (validTypes & 0x0004 && /^(3[47])/.test(value)) { //amex
                        return value.length == 15;
                }
                if (validTypes & 0x0008 && /^(3(0[012345]|[68]))/.test(value)) { //dinersclub
                        return value.length == 14;
                }
                if (validTypes & 0x0010 && /^(2(014|149))/.test(value)) { //enroute
                        return value.length == 15;
                }
                if (validTypes & 0x0020 && /^(6011)/.test(value)) { //discover
                        return value.length == 16;
                }
                if (validTypes & 0x0040 && /^(3)/.test(value)) { //jcb
                        return value.length == 16;
                }
                if (validTypes & 0x0040 && /^(2131|1800)/.test(value)) { //jcb
                        return value.length == 15;
                }
                /!*if (validTypes & 0x0080) { //unknown
                        return true;
                }*!/
                return false;
            }, "Please enter a valid credit card number.");*/

            $('#card_number').on('keypress change', function () {
                $(this).val(function (index, value) {
                    return value.replace(/\W/gi, '').replace(/(.{4})/g, '$1 ');
                });
            });
            $.validator.addMethod ("expiry_month_Call", function(value, element) {
                value = parseInt(value, 10);
                var year  = $("#expiry_year option:selected" ).val(),
                    currentMonth = new Date().getMonth() + 1,
                    currentYear  = new Date().getFullYear();
                if (value < 0 || value > 12) {
                    return false;
                }
                if (year == '') {
                    return true;
                }
                year = parseInt(year, 10);
                if (year > currentYear || (year == currentYear && value > currentMonth)) {
                    //element.updateStatus('expiry_year', 'VALID');
                    return true;
                } else {
                    return false;
                }
            },'{{ addslashes("Please select valildate expire month") }}');
            $.validator.addMethod ("expiry_year_Call", function(value, element) {
                value = parseInt(value, 10);
                var month  = $("#expiry_month option:selected" ).val(),
                    currentMonth = new Date().getMonth() + 1,
                    currentYear  = new Date().getFullYear();
                if (value < currentYear || value > currentYear + 100) {
                    return false;
                }
                if (month == '') {
                    return false;
                }
                month = parseInt(month, 10);
                if (value > currentYear || (value == currentYear && month > currentMonth)) {
                    //validator.updateStatus('expiry_month', 'VALID');
                    return true;
                } else {
                    return false;
                }
            },'{{ config("Please select valildate expire year") }}');
            $.validator.addMethod ("cvv_Call", function(value, element) {
                return this.optional( element ) || /^[0-9]{3,4}$/.test( value );
            },'{{ addslashes(config("Please select valildate expire year")) }}');

            $("#subscriberForm").validate({
                errorElement:'span',
                errorClass:'step-error',
                rules: {
                    card_number:{required: true, creditcard:true},
                    card_name:{required: true},
                    expiry_month:{required: true},
                    expiry_year:{required: true},
                    cvv:{required: true,cvv_Call: true},
                },
                messages: {
                    card_number: {required: "Please enter valid card number", },
                    card_name:{required: "Please enter name"},
                    expiry_month:{required: "Please select expiry month"},
                    expiry_year:{required: "Please select expiry year"},
                    cvv:{required: "Please enter cvv",cvv_Call: "Please enter valid cvv"},
                },
                submitHandler: function(form, event) {
                    $("#subscriberForm")[0].submit();                   
                    //event.preventDefault();
                    startLoaderAjax();
                }
                
            });

        });

    </script>
    <!-- Investment Options end -->
@endpush    
@endsection
