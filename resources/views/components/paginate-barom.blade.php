@if ($paginator->hasPages())
    <div class="gridjs-footer">
        <div class="gridjs-pagination">
            <div role="status" aria-live="polite" class="gridjs-summary"
                title="Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}">
                Showing
                <b>{{ $paginator->firstItem() }}</b>
                to
                <b>{{ $paginator->lastItem() }}</b>
                of
                <b>{{ $paginator->total() }}</b>
                results
            </div>
            <div class="gridjs-pages">
                {{-- Previous --}}
                <button {{ $paginator->onFirstPage() ? 'disabled' : '' }}
                    @unless ($paginator->onFirstPage())
        onclick="window.location='{{ $paginator->previousPageUrl() }}'"
        @endunless>
                    Previous
                </button>
                {{-- Page Numbers --}}
                @foreach ($elements as $element)
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            <button
                                @if ($page == $paginator->currentPage()) class="gridjs-currentPage"
        disabled
        @else
        onclick="window.location='{{ $url }}'" @endif>
                                {{ $page }}
                            </button>
                        @endforeach
                    @endif
                @endforeach
                {{-- Next --}}
                <button {{ $paginator->hasMorePages() ? '' : 'disabled' }}
                    @if ($paginator->hasMorePages()) onclick="window.location='{{ $paginator->nextPageUrl() }}'" @endif>
                    Next
                </button>
            </div>
        </div>
    </div>
@endif
