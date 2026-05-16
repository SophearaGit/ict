@if ($paginator->hasPages())
    <div class="d-flex align-items-center justify-content-between mt-3">

        <p class="text-muted fs-3 mb-0">
            Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
        </p>

        <ul class="pagination mb-0">

            {{-- Previous --}}
            <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link rounded-circle me-1" href="{{ $paginator->previousPageUrl() }}" aria-label="Previous">
                    <i class="ti ti-chevron-left fs-4"></i>
                </a>
            </li>

            {{-- Page Numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled">
                        <span class="page-link rounded-circle me-1">{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li class="page-item {{ $page == $paginator->currentPage() ? 'active' : '' }}">
                            <a class="page-link rounded-circle me-1" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                <a class="page-link rounded-circle me-1" href="{{ $paginator->nextPageUrl() }}" aria-label="Next">
                    <i class="ti ti-chevron-right fs-4"></i>
                </a>
            </li>

        </ul>
    </div>
@endif
