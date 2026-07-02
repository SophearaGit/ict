<div class="text-center py-5">
    <i class="bi bi-inbox text-muted" style="font-size: 2.5rem;"></i>
    <p class="text-muted mt-2 mb-0">No reports found.</p>
    @if (request()->anyFilled(['search', 'sort', 'year', 'month', 'week', 'status']))
        <a href="{{ route('admin.staff-report.index') }}" class="btn btn-sm btn-link">Clear filters</a>
    @endif
</div>
