@if ($message = Session::get('success'))
    <div class="alert flash-message text-center alert-block alert-dismissible fade show mb-0" role="alert">
        <strong class="grey_color">{{ $message }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($message = Session::get('error'))
    <div class="alert flash-message text-center alert-block alert-dismissible fade show mb-0" role="alert">
        <strong class="grey_color">{{ $message }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if ($message = Session::get('warning'))
    <div class="alert flash-message text-center alert-block alert-dismissible fade show mb-0" role="alert">
        <strong class="grey_color">{{ $message }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($message = Session::get('info'))
    <div class="alert flash-message text-center alert-block alert-dismissible fade show mb-0" role="alert">
        <strong class="grey_color">{{ $message }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


@if($errors->any())
    <div class="alert flash-message text-center alert-dismissible fade show mb-0" role="alert">
        @foreach ($errors->all() as $error)
            {{ $error }}<br>
        @endforeach
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div id="alertDiv" class="text-center"></div>
