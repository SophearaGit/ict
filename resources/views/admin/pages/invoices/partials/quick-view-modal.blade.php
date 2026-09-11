{{-- Shared quick-view modal shell. @include this once per page anywhere a
     "View Invoice" trigger sets data-bs-toggle="modal"
     data-bs-target="#quickInvoiceModal" data-invoice-id="{id}" — the modal
     fetches admin.pages.invoices.partials.quick-view for that id and drops
     it into the body. Read-only, no state to manage beyond the fetch. --}}
<div class="modal fade" id="quickInvoiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" id="quickInvoiceModalBody">
                <div class="text-center py-5 text-muted">
                    <span class="spinner-border spinner-border-sm me-2"></span> Loading…
                </div>
            </div>
        </div>
    </div>
</div>

@once
    @push('scripts')
        <script>
            (function() {
                var modalEl = document.getElementById('quickInvoiceModal');
                if (!modalEl) return;

                var bodyEl = document.getElementById('quickInvoiceModalBody');
                var loadingHtml = '<div class="text-center py-5 text-muted">' +
                    '<span class="spinner-border spinner-border-sm me-2"></span> Loading…</div>';
                var errorHtml = '<div class="text-center py-5 text-danger">' +
                    'Couldn\'t load this invoice. Please try again.</div>';

                // Placeholder swapped for the real id at fetch time — avoids
                // needing a second route just to build the URL client-side.
                var urlTemplate = @json(route('admin.invoices.quick-view', ['invoice' => '__ID__']));

                modalEl.addEventListener('show.bs.modal', function(event) {
                    var trigger = event.relatedTarget;
                    var invoiceId = trigger ? trigger.getAttribute('data-invoice-id') : null;

                    if (!invoiceId) {
                        bodyEl.innerHTML = errorHtml;
                        return;
                    }

                    bodyEl.innerHTML = loadingHtml;

                    fetch(urlTemplate.replace('__ID__', invoiceId), {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(function(res) {
                            if (!res.ok) throw new Error('Request failed');
                            return res.text();
                        })
                        .then(function(html) {
                            bodyEl.innerHTML = html;
                        })
                        .catch(function() {
                            bodyEl.innerHTML = errorHtml;
                        });
                });
            })();
        </script>
    @endpush
@endonce
