
<section class="content-header justify-content-between align-items-center w-100 d-flex mb-4">
    @php $route = Route::currentRouteName() @endphp
    @php $index = substr($route, 0, strrpos($route, '.') + 1) . 'index' @endphp
    <ol class="breadcrumb mb-0" aria-label="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('admin.dashboard.index') }}</a></li>
        @if (strpos($route, 'admin.root') === false && Route::has($index))
            @php $isIndex = strpos($route, 'index') !== false @endphp
            @php $parent_text= __($isIndex ? $route : $index) @endphp
            <li class="{{ $isIndex ? 'active' : '' }} breadcrumb-item">
                @if($isIndex)
                    {{ empty($t) ? $parent_text : $t }}
                @else
                    <a href="{{ route($index) }}">{{ $parent_text }}</a>
                @endif
            </li>
            @if(!$isIndex)<li class="active breadcrumb-item">{{ empty($t) ? __($route) : $t }}</li>@endif
        @endif
    </ol>
    @if (isset($link))
    <a href="{{ $link }}" class="btn btn-primary btn-round d-block">
        <span class="ion ion-md-add"></span>&nbsp; Create {{ __(Route::getCurrentRoute()->getName()) }}</a>
    @endif
</section>
