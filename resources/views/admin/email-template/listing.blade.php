@extends('layouts.admin.app')

@section('content')
    <div class="card">
        <div class="card-datatable table-responsive">
            {!! $dataTable->table(['class'=>'table-bordered table table-striped'],false) !!}
        </div>
    </div>
    {!! $dataTable->scripts() !!}
    @push('scripts')        
    @endpush
@endsection
