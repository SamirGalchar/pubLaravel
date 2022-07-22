@extends('layouts.admin.app')

@section('content')
    {!! $htmlContent !!}

    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function () {
                $('#f1').validate({
                    rules:{
                        name: {required: true},
                        link: {required: true},
                        free_trail: { required: true },                        
                        phase: { required: true },                        
                        status: { required: true },                        
                    },
                    messages:{
                        name: {required: '{{ __('admin.required') }}'},
                        link: {required: '{{ __('admin.required') }}'},
                        free_trail: {required: '{{ __('admin.required') }}'},
                        phase: {required: '{{ __('admin.required') }}'},
                        status: { required: '{{ __('admin.required') }}' },                        
                    },                    
                });
            });
        </script>
    @endpush
@endsection
