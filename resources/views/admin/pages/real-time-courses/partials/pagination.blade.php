<nav>
    <ul class="pagination justify-content-center mb-0">
        {{-- Previous Page Link --}}
        <li class="page-item {{ $courses->onFirstPage() ? 'disabled' : '' }}">
            <a class="page-link mx-1 rounded" href="{{ $courses->previousPageUrl() ?? '#' }}" a ria-label="Previous">
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor"
                    class="bi bi-chevron-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z" />
                </svg>
            </a>
        </li>
        {{-- Page Numbers --}}
        @foreach ($courses->getUrlRange(1, $courses->lastPage()) as $page => $url)
            <li class="page-item {{ $courses->currentPage() == $page ? 'active' : '' }}">
                <a class="page-link mx-1 rounded" href="{{ $url }}">{{ $page }}</a>
            </li>
        @endforeach
        {{-- Next Page Link --}}
        <li class="page-item {{ $courses->hasMorePages() ? '' : 'disabled' }}">
            <a class="page-link mx-1 rounded" href="{{ $courses->nextPageUrl() ?? '#' }}" aria-label="Next">
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor"
                    class="bi bi-chevron-right" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z" />
                </svg>
            </a>
        </li>
    </ul>
</nav>
