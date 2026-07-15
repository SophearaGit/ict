@if ($paginator->hasPages())
    <div class="row align-items-center border-top mx-0 pt-3">
        <div class="col-sm-6">
            <div class="dataTables_info">
                Showing {{ $paginator->firstItem() }}
                to {{ $paginator->lastItem() }}
                of {{ $paginator->total() }} entries
            </div>
        </div>
        <div class="col-sm-6">
            <div class="dataTables_paginate paging_numbers">
                <ul class="pagination justify-content-end mb-0">
                    {{-- Previous --}}
                    @if ($paginator->onFirstPage())
                        <li class="paginate_button page-item disabled">
                            <span class="page-link">&laquo;</span>
                        </li>
                    @else
                        <li class="paginate_button page-item">
                            <a class="page-link" href="{{ $paginator->previousPageUrl() }}">
                                &laquo;
                            </a>
                        </li>
                    @endif
                    {{-- Page Numbers --}}
                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <li class="paginate_button page-item disabled">
                                <span class="page-link">{{ $element }}</span>
                            </li>
                        @endif
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                <li
                                    class="paginate_button page-item {{ $page == $paginator->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $url }}">
                                        {{ $page }}
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    @endforeach
                    {{-- Next --}}
                    @if ($paginator->hasMorePages())
                        <li class="paginate_button page-item">
                            <a class="page-link" href="{{ $paginator->nextPageUrl() }}">
                                &raquo;
                            </a>
                        </li>
                    @else
                        <li class="paginate_button page-item disabled">
                            <span class="page-link">&raquo;</span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
@endif
