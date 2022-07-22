@extends('layouts.admin.app')

@section('content')

    <div class="card">
        <div class="card-datatable table-responsive">
            
            <div class="create_proposals_table">
                <div class="reset_all_btn d-block d-md-flex justify-content-between">
                    <div id="reportrange" class="cust_cls_for_daterang Poppins fw-semibold" style="background: #fff; cursor: pointer; width: 100%;border-radius: 12px;padding-left:25px;text-align:left">
                        <i class="fa fa-calendar"></i>&nbsp;
                        <span class="Poppins fw-semibold">Select Date</span> 
                        <i class="fa fa-caret-down"></i>
                    </div>
                    <a href="#" type="reset" class="reset_btn Poppins text-center text-decoration-none   fw-semibold text-white letter-spc-1">Reset</a>
                    <button type="button" id="exportSelectedContacts" class="mb-4 admin-dwl-btn reset_btn Poppins fw-semibold text-white letter-spc-1">Download</button>
                </div>                                        
                <table id="coupons_datatable" class="table table-striped row-border dt-responsive nowrap" data-cascade="true" style="width:100%;" width="100%" cellpadding="50">
                    <thead bgcolor="#eeeff6">
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
                                        if($coupon['validity_type'] == 'onetime'):
                                            echo 'Onetime';
                                        elseif($coupon['validity_type'] == 'unlimited'):
                                            echo 'Unlimited';
                                        else:
                                            echo 'Limited<br>';
                                            if($coupon['limit_type'] == 'numbers'):
                                               echo '<span class="font-14 nunito-sans fw-bold color737373">For '.$coupon['limit_numbers'].' Users</span>';
                                            endif;
                                            if($coupon['limit_type'] == 'date'):
                                               echo '<span class="font-14 nunito-sans fw-bold color737373">Valid upto '.$coupon['limit_date'].'</span>';
                                            endif;
                                        endif;                                        
                                        ?>
                                    </td>
                                    <td class="font-16 nunito-sans fw-bold color363565">
                                        {{ $coupon['discount'] }}
                                        @if($coupon['discount_type'] == 'percentage') % @else USD $ @endif
                                    </td>
                                    <td class="font-16 nunito-sans fw-bold color363565">
                                        Karina Mcdowell<br>
                                        Karina Mcdowell<br>
                                        Karina Mcdowell<br>
                                        Karina Mcdowell<br>
                                        Karina Mcdowell<br>
                                    </td>
                                    <td class="font-16 nunito-sans fw-bold color363565">{{ $coupon['notification_email'] }}</td>
                                    <td class="font-16 nunito-sans fw-bold color363565">{{ $coupon['status'] }}</td>
                                    <td class="font-16 nunito-sans fw-bold color363565">{{ $coupon['created_at'] }}</td>
                                    
                                    
                                                                       
                                    <td class="font-15 roboto fw-light color858997 text-start"></td> 
                                    
                                    
                                    <td class="font-16 nunito-sans fw-bold color363565" >
                                        <a href="{{ route('admin.coupons.edit',$coupon['id']) }}" class="btn btn-xs btn-info"><i class="fas fa-pencil-alt"></i></a>
                                        <a href="javascript:delRec('{{ $coupon['id'] }}')"  class="btn btn-xs btn-danger delete" data-id="{{ $coupon['id'] }}" title="Delete"><i class="far fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif                                                                        
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>

    
    
    @push('scripts')
    
        <script src="https://socialsolutions.live/assets/js/jquery.dataTables.min.js"></script>
        <script src="https://socialsolutions.live/assets/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://socialsolutions.live/assets/js/dataTables.responsive.min.js"></script>
        <script src="https://socialsolutions.live/assets/js/responsive.bootstrap4.min.js"></script>
        
        <script>
            function delRec(id){
                if(id>0 && confirm("Are You sure want to delete!")){
                    var tableId = $('table').attr('id');
                    var page_id = $(this).data("id");
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('admin.coupons.store') }}"+'/'+id,
                        success: function (data) {
                            window.LaravelDataTables[tableId].draw();
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                }
            }
            
            $(document).ready(function () {                       
                
                $('#coupons_datatable').DataTable({
                    "ordering": true,
                    "order": [[ 0, "desc" ]],
                    "pageLength": 10,
                    "searching": true,
                    "bLengthChange": false,
                    "bInfo": false,
                    pagingType: "full_numbers",
                    'columnDefs': [{
                            'targets': [10], // column index (start from 0)
                            'orderable': false, // set orderable false for selected columns
                    },{
                        "targets": [ 0 ],
                        "visible": false,
                        "searchable": false
                    },
                    {
                        "targets": [ 8 ],
                        "autoWidth": false
                    }],    
                    language: {
                        searchPlaceholder: "Search orders by Name, Email, Company",
                        paginate: {
                            first: false,
                            last: false,
                            next: "<i class='fas fa-chevron-right'></i>",
                            previous: "<i class='fas fa-chevron-left'></i>"
                        },
                        sEmptyTable:"No record found!",
                    },
                });
                $('#coupons_datatable td\\:fist-child').addClass('fooicon-plus');
                
            });
        </script>
    @endpush
@endsection
