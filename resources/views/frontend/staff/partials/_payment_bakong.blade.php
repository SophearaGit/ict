<div class="section-card" id="payment-section">
    <h3 class="section-title">
        <i class="fa-solid fa-credit-card"></i> PAYMENT INFORMATION
    </h3>

    <div class="payment-grid">

        {{-- ── Left: Fee summary ─────────────────────────────── --}}
        <div class="payment-fee-box">
            <p class="label">Registration Fee</p>
            <h2 class="fee-amount">${{ number_format($invoice->total_amount ?? ($registrationFee ?? 0), 2) }}</h2>
            @if (isset($invoice) && $invoice->discount > 0)
                <p style="color:#2a9d5c; font-size:13px;">
                    Discount: -${{ number_format($invoice->discount, 2) }}
                </p>
            @endif
        </div>

        {{-- ── Middle: Method selector ───────────────────────── --}}
        <div class="payment-method-box">
            <p class="label">Payment Method <span class="required">*</span></p>

            <label class="method-option">
                <input type="radio" name="payment_method" value="bakong" id="pm-bakong" required>
                <img src="{{ asset('frontend/asset/images/bakong-logo.png') }}" alt="Bakong" class="pm-logo"
                    style="height:24px;">
                <span>Bakong (KHQR)</span>
            </label>

            <label class="method-option" style="margin-top:10px;">
                <input type="radio" name="payment_method" value="cash" id="pm-cash">
                <img src="{{ asset('frontend/asset/images/cash-icon.png') }}" alt="Cash" class="pm-logo"
                    style="height:24px;">
                <span>Cash</span>
            </label>
        </div>

        {{-- ── Right: Dynamic payment details ───────────────── --}}
        <div class="payment-details-box">

            {{-- BAKONG panel --}}
            <div id="panel-bakong" class="payment-panel" style="display:none;">

                <div id="qr-loading" style="display:none; text-align:center; padding:20px;">
                    <i class="fa-solid fa-spinner fa-spin fa-2x"></i>
                    <p style="margin-top:8px;">Generating QR…</p>
                </div>

                <div id="qr-container" style="display:none; text-align:center;">
                    <p style="font-size:13px; color:#555; margin-bottom:8px;">
                        Scan with ABA, ACLEDA, Wing, or any KHQR-enabled app
                    </p>
                    <img id="qr-image" src="" alt="KHQR"
                        style="width:180px; height:180px; border:1px solid #ddd; border-radius:8px;">
                    <p style="margin-top:6px; font-size:12px; color:#999;">
                        Expires in <span id="qr-countdown" style="font-weight:600; color:#e88;">10:00</span>
                    </p>
                    <p id="qr-status" style="margin-top:8px; font-weight:600;"></p>
                </div>

                <div id="qr-expired" style="display:none; text-align:center; color:#e55; padding:16px;">
                    <i class="fa-solid fa-clock fa-2x"></i>
                    <p style="margin-top:8px;">QR expired.</p>
                    <a href="#" id="btn-refresh-qr" style="color:#3777ff;">Generate new QR</a>
                </div>

                <div id="payment-success-badge" style="display:none; text-align:center; color:#2a9d5c; padding:16px;">
                    <i class="fa-solid fa-circle-check fa-2x"></i>
                    <p style="margin-top:6px; font-weight:600;">Payment confirmed!</p>
                    <p id="success-from-account" style="font-size:12px; color:#555;"></p>
                </div>

                {{-- Hidden fields for form submission --}}
                <input type="hidden" name="bakong_transaction_id" id="bakong_transaction_id">
                <input type="hidden" name="bakong_hash" id="bakong_hash">
                <input type="hidden" name="bakong_md5" id="bakong_md5">
                <input type="hidden" name="bakong_paid" id="bakong_paid" value="0">
            </div>

            {{-- CASH panel --}}
            <div id="panel-cash" class="payment-panel" style="display:none;">
                <p style="color:#555;">Please pay at the school office and upload your receipt below.</p>
                <label style="display:block; margin-top:12px; font-size:13px;">
                    Receipt (if available)
                    <input type="file" name="cash_receipt" accept="image/*,.pdf"
                        style="display:block; margin-top:6px;">
                </label>
            </div>

        </div>{{-- /payment-details-box --}}
    </div>{{-- /payment-grid --}}
</div>

@push('scripts')
    <script>
        (function() {
            /* ── Config from Blade ── */
            const GENERATE_URL = @json(route('staff.bakong.generate-qr'));
            const CHECK_URL = @json(route('staff.bakong.check-payment'));
            const CSRF = @json(csrf_token());
            const INVOICE_ID = @json($invoice->id ?? null);

            /* ── DOM refs ── */
            const pmBakong = document.getElementById('pm-bakong');
            const pmCash = document.getElementById('pm-cash');
            const panelBakong = document.getElementById('panel-bakong');
            const panelCash = document.getElementById('panel-cash');
            const qrLoading = document.getElementById('qr-loading');
            const qrContainer = document.getElementById('qr-container');
            const qrImage = document.getElementById('qr-image');
            const qrExpired = document.getElementById('qr-expired');
            const qrStatus = document.getElementById('qr-status');
            const qrCountdown = document.getElementById('qr-countdown');
            const successBadge = document.getElementById('payment-success-badge');
            const successFrom = document.getElementById('success-from-account');
            const fieldTxnId = document.getElementById('bakong_transaction_id');
            const fieldHash = document.getElementById('bakong_hash');
            const fieldMd5 = document.getElementById('bakong_md5');
            const fieldPaid = document.getElementById('bakong_paid');

            let pollingTimer = null;
            let countdownTimer = null;
            let currentMd5 = null;
            let expiresAt = null;

            /* ── Method switch ── */
            pmBakong.addEventListener('change', () => {
                panelBakong.style.display = 'block';
                panelCash.style.display = 'none';
                generateQR();
            });
            pmCash.addEventListener('change', () => {
                panelCash.style.display = 'block';
                panelBakong.style.display = 'none';
                stopAll();
            });
            document.getElementById('btn-refresh-qr').addEventListener('click', e => {
                e.preventDefault();
                generateQR();
            });

            /* ── Generate QR ── */
            async function generateQR() {
                if (!INVOICE_ID) {
                    alert('Invoice not found. Please save the registration first.');
                    return;
                }
                stopAll();
                resetUI();
                qrLoading.style.display = 'block';

                try {
                    const res = await apiFetch(GENERATE_URL, {
                        invoice_id: INVOICE_ID,
                        currency: 'USD'
                    });
                    const data = await res.json();
                    if (!data.success) throw new Error(data.message ?? 'QR generation failed');

                    currentMd5 = data.md5;
                    expiresAt = new Date(data.expires_at);
                    qrImage.src = data.qr_image;
                    fieldMd5.value = data.md5;

                    qrLoading.style.display = 'none';
                    qrContainer.style.display = 'block';

                    startCountdown();
                    startPolling();
                } catch (err) {
                    qrLoading.style.display = 'none';
                    qrContainer.style.display = 'block';
                    qrStatus.textContent = '⚠️ ' + err.message;
                    qrStatus.style.color = '#e55';
                }
            }

            /* ── Poll every 3 s ── */
            function startPolling() {
                pollingTimer = setInterval(async () => {
                    if (!currentMd5) return;
                    try {
                        const res = await apiFetch(CHECK_URL, {
                            md5: currentMd5
                        });
                        const data = await res.json();

                        if (data.status === 'success') {
                            stopAll();
                            onSuccess(data);
                        } else if (data.status === 'failed') {
                            stopAll();
                            qrStatus.textContent = '❌ ' + (data.message ??
                                'Payment failed. Please try again.');
                            qrStatus.style.color = '#e55';
                        } else if (data.status === 'expired') {
                            stopAll();
                            qrContainer.style.display = 'none';
                            qrExpired.style.display = 'block';
                        }
                        // 'pending' → keep polling
                    } catch (e) {
                        console.warn('Bakong poll error', e);
                    }
                }, 3000);
            }

            /* ── Countdown ── */
            function startCountdown() {
                countdownTimer = setInterval(() => {
                    const rem = Math.max(0, expiresAt - Date.now());
                    const m = Math.floor(rem / 60000);
                    const s = Math.floor((rem % 60000) / 1000);
                    qrCountdown.textContent = `${m}:${String(s).padStart(2, '0')}`;
                    if (rem === 0) {
                        stopAll();
                        qrContainer.style.display = 'none';
                        qrExpired.style.display = 'block';
                    }
                }, 1000);
            }

            /* ── Success ── */
            function onSuccess(data) {
                fieldTxnId.value = data.transaction_id ?? '';
                fieldHash.value = data.hash ?? '';
                fieldPaid.value = '1';

                qrContainer.style.display = 'none';
                successBadge.style.display = 'block';
                if (data.from_account) {
                    successFrom.textContent = 'Paid from: ' + data.from_account;
                }
            }

            /* ── Helpers ── */
            function stopAll() {
                clearInterval(pollingTimer);
                clearInterval(countdownTimer);
                pollingTimer = countdownTimer = null;
            }

            function resetUI() {
                [qrContainer, qrLoading, qrExpired, successBadge].forEach(el => el.style.display = 'none');
                qrStatus.textContent = '';
                fieldPaid.value = '0';
                currentMd5 = null;
            }

            function apiFetch(url, body) {
                return fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify(body),
                });
            }

            /* ── Guard form submit ── */
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', e => {
                    if (pmBakong.checked && fieldPaid.value !== '1') {
                        e.preventDefault();
                        alert('Please complete the Bakong payment before submitting.');
                    }
                });
            });
        })();
    </script>
@endpush
