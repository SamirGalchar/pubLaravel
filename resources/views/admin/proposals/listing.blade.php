@extends('layouts.admin.app')

@section('content')
    <div class="card">
        <div class="card-datatable table-responsive">
            {!! $dataTable->table(['class'=>'table-bordered table table-striped'],false) !!}
        </div>
    </div>
    {!! $dataTable->scripts() !!}
    @push('scripts')
        <script>
            function delRec(id){
                if(id>0 && confirm("Are You sure want to delete!")){
                    var tableId = $('table').attr('id');
                    var page_id = $(this).data("id");
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('admin.proposals.store') }}"+'/'+id,
                        success: function (data) {
                            window.LaravelDataTables[tableId].draw();
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
