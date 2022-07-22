@extends('layouts.admin.app')
@section('content')
@push('styles')
    <link rel="stylesheet" href="{{ asset('plugin/dropzone/dropzone-v5.css') }}" >
    <style>
        .dropzone {
            background: white;
            border-radius: 5px;
            border: 2px dashed rgb(0, 135, 247);
            border-image: none;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        li.sortable{
            display:inline-block;
        }
    </style>
@endpush

<div class="card">
    <div class="card-datatable table-responsive">
        <form action="{{route('admin.multiple-image.store')}}" method="POST" enctype="multipart/form-data" >
            @csrf
            <div class="dropzone mydropzone" id="selectimage">
                <div class="dz-message needsclick">    
                    Drop files here or click to upload.<BR>
                    <span class="note needsclick">(This is just a demo dropzone. Selected 
                        files are <STRONG>highlight</STRONG> actually uploaded.)</span>
                </div>                
            </div>
            
            <ul class="cvf_uploaded_files ui-sortable dropzone ps-0">                
            </ul>
            
            <input type="hidden" name="images" id="images" value="" />
            <input type="submit" />
            
        </form>
    </div>
</div>

@push('scripts')
<script src="{{ asset('plugin/dropzone/dropzone-v5.js') }}"></script>
@include('common.partial.imageOrder')

<script>
    let custId = 0;
    Dropzone.autoDiscover = false;
    $(document).ready(function () {
        //Drope Zone
        $("div#selectimage").dropzone({
            headers: {
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            },
            /*renameFile: function (file) {
                let newName = new Date().getTime() + '_' + file.name;
                return newName;
            },*/
            url: "{{ route('admin.multiple-image.dz-upload-images') }}",
            paramName: "myfile",
            params:{'cust_id':custId},
            parallelUploads:1,
            uploadMultiple: true,
            maxFilesize: 5, //MB
            addRemoveLinks: true,
            acceptedFiles: 'image/*',
            previewsContainer: '.mydropzone',
            //dictRemoveFile:'',
            accept: function (file, done){
                done(); // to allow process
                let filestr = $("#images").val();
                if (filestr != '')
                    filestr = filestr + '::' + file.name;
                else
                    filestr = file.name;
                $("#images").val(filestr);
            },
            removedfile: function (file) {
                $.post('{{ route("admin.multiple-image.dz-upload-remove") }}?file=' + file.name, function (retdata) {
                    //Remove file from input tag
                    let filesUploaded = $("#images").val();
                    //explode using jquery
                    let fileArr = filesUploaded.split("::");
                    if(jQuery.inArray(file.name, fileArr) !== -1){
                        //remove element form array
                        fileArr = jQuery.grep(fileArr, function(value) {
                            return value != file.name;
                        });
                        //implode using javascript
                        let fileStr = fileArr.join("::");
                        $("#images").val(fileStr);                        
                    }
                    //default
                    let _ref;
                    return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
                });                
            }
        });
    });
</script>
@endpush
@endsection
