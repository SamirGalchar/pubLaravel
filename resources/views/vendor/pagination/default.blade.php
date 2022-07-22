@if ($paginator->hasPages())
    <nav aria-label="Page navigation example offer_paginantion_list">
        <ul class="pagination justify-content-center">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled page-item" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <a href="javascript:void(0);" class="page-link raleway fw-bold font18 color323232" rel="prev" aria-label="@lang('pagination.previous')">
                        <span aria-hidden="true"><img src="{{ asset('front/images/pagination-left-arrow.png') }}" class="d-arrow-image"><img src="{{ asset('front/images/pagination-left-hover-arrow.png') }}" class="h-arrow-image"></span>
                    </a>    
                </li>
            @else
                <li class="page-item">
                    <a href="{{ $paginator->previousPageUrl() }}" class="page-link raleway fw-bold font18 color323232" rel="prev" aria-label="@lang('pagination.previous')">
                        <span aria-hidden="true"><img src="{{ asset('front/images/pagination-left-arrow.png') }}" class="d-arrow-image"><img src="{{ asset('front/images/pagination-left-hover-arrow.png') }}" class="h-arrow-image"></span>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="disabled" aria-disabled="true"><span>{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active">
                                <a class="page-link raleway fw-bold font18 color323232" aria-current="page" href="javascript:void(0);">{{ $page }}</a>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link raleway fw-bold font18 color323232" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                        <span aria-hidden="true"><img src="{{ asset('front/images/pagination-right-arrow.png') }}" class="d-arrow-image"><img src="{{ asset('front/images/pagination-right-hover-arrow.png') }}" class="h-arrow-image"></span>
                    </a>
                </li>                
            @else
                <li class="page-item disabled">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" aria-disabled="true" aria-label="@lang('pagination.next')">
                        <span aria-hidden="true"><img src="{{ asset('front/images/pagination-right-arrow.png') }}" class="d-arrow-image"><img src="{{ asset('front/images/pagination-right-hover-arrow.png') }}" class="h-arrow-image"></span>
                    </a>
                </li>                
            @endif
        </ul>
    </nav>
@endif
