<div class="center">
    <div class="btn-group">
        <a href="{{ route('admin.case-studies.show',$id) }}" class="btn btn-xs btn-info"><i class="fas fa-desktop"></i></a>
        <a href="javascript:delRec('{{ $id }}')"  class="btn btn-xs btn-danger delete" data-id="{{ $id }}" title="Delete"><i class="far fa-trash-alt"></i></a>
    </div>
</div>
