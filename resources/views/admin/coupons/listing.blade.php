@extends('layouts.admin.app')

@push('styles')
<style>
    /* 8 june 2022 */
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #fafafa;
    }

    .admin_search_btn {
        background-color: #02BC77;
        width: 100%;
        height: 42px;
        line-height: 38px;
        border: 0px;
        border-radius: 5px;
    }

    .search_field_s {
        height: 42px;
    }

    #coupons_datatable_wrapper .row .col-md-6 {
        max-width: 99%;
        width: 100%;
        -webkit-box-flex: 0;
        -ms-flex: 0 0 99%;
        flex: 0 0 99%;
    }

    #coupons_datatable_wrapper .row {
        margin: 0px;
        justify-content: center;
    }

    #coupons_datatable_filter {
        width: 100%;
    }

    .default-style div.dataTables_wrapper div.dataTables_filter label {
        justify-content: start;
        font-size: 0px;
        background-color: #fff;
    }

    div.dataTables_wrapper div.dataTables_filter input {
        width: 100%;
        height: 42px;
        font-size: 16px;
    }

    .color-4E5155 {
        color: #4E5155;
    }
    .more_btn{cursor: pointer;}
    .user_list{font-size: 12px;color: #4E5155;cursor: pointer;}
</style>
@endpush

@section('content')

<div class="card">
    <div class="card-datatable table-responsive">
        <div class="create_proposals_table">
            <div class="row mx-0 w-100">
                <div class="col-12">
                    <div class="dt-buttons">
                        <a href="{{ route('admin.coupons.create') }}" class="dt-button buttons-create btn btn-success mx-2" tabindex="0" aria-controls="page-table">
                            <span><i class="fa fa-plus"></i> Create</span>
                        </a>
                        <a href="{{ route('admin.coupons.index') }}" class="dt-button buttons-reset mx-1 btn btn-danger" tabindex="0" aria-controls="page-table">
                            <span><i class="fa fa-undo"></i>Reset</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="reset_all_btn d-block d-md-flex justify-content-between">
                <form class="row w-100 mx-0" action="{{ route('admin.coupons.index') }}" method="get">
                    <div class="col-12 col-xl-8">
                        <input type="text" name="search" value="{{ $search }}" class="form-control search_field_s w-100 me-2" placeholder="Search by User Name, Email, Phone" aria-label="Search">
                    </div>
                    <div class="col-12 col-xl-3">
                        <select name="validity_type" class="form-select search_field_s form-control w-100" aria-label="Default select example">
                            <option value="">Type</option>
                            <option value="onetime" @if($validity_type == "onetime") selected @endif>Onetime</option>
                            <option value="unlimited" @if($validity_type == "unlimited") selected @endif>Unlimited</option>
                            <option value="limited" @if($validity_type == "limited") selected @endif>Limited</option>
                        </select>
                    </div>
                    <div class="col-12 col-xl-1">
                        <button type="submit" id="exportSelectedContacts" class="mb-4 w-100 admin-dwl-btn admin_search_btn nunito-sans text-white letter-spc-1">Search</button>
                    </div>
                </form>

            </div>
            <table id="coupons_datatable" class="table table-striped row-border dt-responsive nowrap" data-cascade="true" style="width:100%;" width="100%" cellpadding="50">
                <thead bgcolor="#ffffff">
                    <tr>
                        <th class="all nunito-sans fw-bold font-16 color363565">#</th>
                        <th class="all nunito-sans fw-bold font-16 color363565">No</th>
                        <th data-breakpoints="all" class="min-tablet-p nunito-sans fw-bold font-16 color363565">Coupon Code</th>
                        <th data-breakpoints="all" class="min-deskktop nunito-sans fw-bold font-16 color363565">Type</th>
                        <th data-breakpoints="all" class="min-deskktop  nunito-sans fw-bold font-16 color363565">Discount</th>
                        <th data-breakpoints="all" class="min-deskktop  nunito-sans fw-bold font-16 color363565">Users</th>
                        <th class="all nunito-sans fw-bold font-16 color363565">Notification Email</th>
                        <th class="all nunito-sans fw-bold font-16 color363565">Status</th>
                        <th class="all nunito-sans fw-bold font-16 color363565">Crated At</th>
                        <th class="min-tablet-p nunito-sans fw-bold font-16 color363565"></th>
                        <th class="all nunito-sans fw-bold font-16 color363565">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($coupons) > 0)
                        @foreach($coupons as $coupon)
                            <tr bgcolor="#ffffff" id="main-row-{{$coupon['id']}}">
                                <td class="font-16 nunito-sans fw-bold color363565">{{ $coupon['id'] }}</td>
                                <td class="font-16 nunito-sans fw-bold color363565">{{ $loop->iteration }}</td>
                                <td class="font-16 nunito-sans fw-bold color363565">{{ $coupon['coupon_code'] }}</td>
                                <td class="font-16 nunito-sans fw-bold color363565">
                                    <?php
                                    if ($coupon['validity_type'] == 'onetime') :
                                        echo 'Onetime';
                                    elseif ($coupon['validity_type'] == 'unlimited') :
                                        echo 'Unlimited';
                                    else :
                                        echo 'Limited<br>';
                                        if ($coupon['limit_type'] == 'numbers') :
                                            echo '<span class="font-14 nunito-sans fw-bold color737373">For ' . $coupon['limit_numbers'] . ' Users</span>';
                                        endif;
                                        if ($coupon['limit_type'] == 'date') :
                                            echo '<span class="font-14 nunito-sans fw-bold color737373">Valid upto ' . date('d/m/Y', strtotime($coupon['limit_date'])) . '</span>';
                                        endif;
                                    endif;
                                    ?>
                                </td>
                                <td class="font-16 nunito-sans fw-bold color363565">
                                    {{$coupon['discount']}}<?php if($coupon['discount_type'] == 'percentage'){echo '%';}else{echo ' USD $';}  ?>
                                </td>
                                <td class="font-16 nunito-sans fw-bold color363565">
                                    <?php
                                        $users = App\Models\CouponUser::select('users.name','users.email','users.phone')
                                                ->leftJoin('users', 'users.id', '=', 'coupon_users.user_id')
                                                ->where('coupon_users.coupon_id',$coupon['id'])->get()->toArray();
                                    ?>
                                    <a href="javascript:void(0);" data-toggle="modal" class="color-4E5155">{{ count($users) }}</a>
                                    @if($users)
                                        <span>...<a data-toggle="collapse" href="#show_more_user_{{ $coupon['id'] }}" role="button" aria-expanded="false" aria-controls="show_more_user_{{ $coupon['id'] }}" style="font-size:10px;color:#4E5155" class="more_btn"><b>SHOW</b></a></span>                            
                                        <div class="collapse multi-collapse user_list text-dark welcomeDiv text-uppercase" id="show_more_user_{{ $coupon['id'] }}">
                                            @foreach($users as $user)
                                                <a href="javascript:void(0);" data-toggle="modal" class="user_detail_open color-4E5155" data-name="{{ $user['name'] }}" data-email="{{ $user['email'] }}" data-phone="{{ $user['phone'] }}">
                                                    {{ $user['name'] }}
                                                </a><br>                                
                                            @endforeach    
                                        </div>
                                    @endif    
                                </td>
                                <td class="font-16 nunito-sans fw-bold color363565">{{ $coupon['notification_email'] }}</td>
                                <td class="font-16 nunito-sans fw-bold color363565">{{ $coupon['status'] }}</td>
                                <td class="font-16 nunito-sans fw-bold color363565">{{ date('d/m/Y', strtotime($coupon['created_at'])) }}</td>
                                <td class="font-15 roboto fw-light color858997 text-start"></td>
                                <td class="font-16 nunito-sans fw-bold color363565">
                                    <a href="{{ route('admin.coupons.edit',$coupon['id']) }}" class="btn btn-xs btn-info"><i class="fas fa-pencil-alt"></i></a>
                                    <a href="javascript:delRec('{{ $coupon['id'] }}')" class="btn btn-xs btn-danger delete" data-id="{{ $coupon['id'] }}" title="Delete"><i class="far fa-trash-alt"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

    </div>
</div>
<!-- Model -->
<div class="modal fade market_square_pop_up" id="market_square_popup" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-0 border-0">
            <div class="modal-body">
                <div class="mt-4 mb-2 px-4">
                    <button type="button" class="close font-weight-bold font20" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="d-block pb-3 border-bottom mb-4">User Details</h3>
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-3">
                            <p class="d-block mb-2"><b>User name :</b></p>
                        </div>
                        <div class="col-12 col-sm-12 col-md-9">
                            <p class="d-block mb-2" id="model_name"> abc</p>
                        </div>
                        <div class="col-12 col-sm-12 col-md-3">
                            <p class="d-block mb-2"><b>Email :</b></p>
                        </div>
                        <div class="col-12 col-sm-12 col-md-9">
                            <p class="d-block mb-2" id="model_email"> abc@gmail.com</p>
                        </div>
                        <div class="col-12 col-sm-12 col-md-3">
                            <p class="d-block mb-2"><b>Phone :</b></p>
                        </div>
                        <div class="col-12 col-sm-12 col-md-9">
                            <p class="d-block mb-2" id="model_phone"> 9696969696</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    $(document).ready(function() {
        $(".user_detail_open").click(function() {
            let name = $(this).data('name');
            let email = $(this).data('email');
            let phone = $(this).data('phone');
            $("#model_name").html(name);
            $("#model_email").html(email);
            $("#model_phone").html(phone);
            $('#market_square_popup').modal('show');
        });
        
        /*$(".more_btn").on('click', function(){
            $(this).html('<b>HIDE</b>');
        });*/
        
    });

    function delRec(id) {
        if (id > 0 && confirm("Are You sure want to delete!")) {
            $.ajax({
                type: "DELETE",
                url: "{{ route('admin.coupons.store') }}" + '/' + id,
                success: function(data) {
                    let row = "#main-row-"+id;
                    $(row).remove();
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        }
    }

    $(document).ready(function() {

        $('#coupons_datatable').DataTable({
            "ordering": true,
            "order": [
                [0, "desc"]
            ],
            "pageLength": 10,
            "searching": true,
            "bLengthChange": false,
            "bInfo": false,
            pagingType: "full_numbers",
            'columnDefs': [{
                    'targets': [10], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                }, {
                    "targets": [0, 9],
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [8],
                    "autoWidth": false
                }
            ],
            language: {
                searchPlaceholder: "Coupon Code, Discount, Notification Email, Status ",
                paginate: {
                    first: false,
                    last: false,
                    // next: "<i class='fas fa-chevron-right'></i>",
                    // previous: "<i class='fas fa-chevron-left'></i>"
                },
                sEmptyTable: "No record found!",
            },
        });
        // $('#coupons_datatable td\\:fist-child').addClass('fooicon-plus');

    });
</script>

@endpush

@endsection