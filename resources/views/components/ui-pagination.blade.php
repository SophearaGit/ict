@if ($paginator->hasPages())
    <div class="d-flex align-items-center justify-content-end py-1 flex-wrap">

        {{-- Rows Per Page --}}
        <p class="mb-0 fs-2">Rows per page:</p>

        <form method="GET" class="ms-2 me-4">
            <select name="per_page" onchange="this.form.submit()" class="form-select w-auto py-1 pe-7 ps-2 border-0">

                @foreach ([5, 10, 25] as $size)
                    <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>
                        {{ $size }}
                    </option>
                @endforeach
            </select>
        </form>

        {{-- Showing Text --}}
        <p class="mb-0 fs-2">
            {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}
            of {{ $paginator->total() }}
        </p>

        {{-- Pagination Buttons --}}
        <nav class="ms-4">
            <ul class="pagination justify-content-center mb-0">

                {{-- Previous --}}
                <li class="page-item p-1 {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link border-0 rounded-circle text-dark fs-6 round-32 d-flex align-items-center justify-content-center"
                        href="{{ $paginator->previousPageUrl() ?? '#' }}">
                        <i class="ti ti-chevron-left"></i>
                    </a>
                </li>

                {{-- Next --}}
                <li class="page-item p-1 {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                    <a class="page-link border-0 rounded-circle text-dark fs-6 round-32 d-flex align-items-center justify-content-center"
                        href="{{ $paginator->nextPageUrl() ?? '#' }}">
                        <i class="ti ti-chevron-right"></i>
                    </a>
                </li>

            </ul>
        </nav>

    </div>
@endif
