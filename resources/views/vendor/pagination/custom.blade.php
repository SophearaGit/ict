@if ($paginator->hasPages())
    <nav class="ict-pagination" role="navigation" aria-label="Pagination Navigation">
        <ul class="ict-pagination-list">

            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li class="ict-page-item disabled" aria-disabled="true">
                    <span class="ict-page-link ict-page-arrow"><i class="fa-solid fa-chevron-left"></i></span>
                </li>
            @else
                <li class="ict-page-item">
                    <a class="ict-page-link ict-page-arrow" href="{{ $paginator->previousPageUrl() }}" rel="prev"
                        aria-label="Previous">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                </li>
            @endif

            {{-- Page numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="ict-page-item disabled" aria-disabled="true">
                        <span class="ict-page-link ict-page-dots">{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="ict-page-item active" aria-current="page">
                                <span class="ict-page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="ict-page-item">
                                <a class="ict-page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li class="ict-page-item">
                    <a class="ict-page-link ict-page-arrow" href="{{ $paginator->nextPageUrl() }}" rel="next"
                        aria-label="Next">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </li>
            @else
                <li class="ict-page-item disabled" aria-disabled="true">
                    <span class="ict-page-link ict-page-arrow"><i class="fa-solid fa-chevron-right"></i></span>
                </li>
            @endif

        </ul>
    </nav>
@endif
