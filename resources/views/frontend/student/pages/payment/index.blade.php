@extends('frontend.layouts.new.master')
@section('page_title', 'Complete Payment')
@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500&display=swap');

        :root {
            --bg: #F7F8FA;
            --surface: #FFFFFF;
            --border: #E4E7EC;
            --text: #101828;
            --muted: #667085;
            --accent: #0057FF;
            --accent-lt: #EEF3FF;
            --success: #027A48;
            --success-bg: #ECFDF3;
            --danger: #B42318;
            --danger-bg: #FEF3F2;
            --warning: #B54708;
            --warning-bg: #FFFAEB;
            --radius: 14px;
            --mono: 'JetBrains Mono', monospace;
            --sans: 'Inter', sans-serif;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--sans);
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        .pay-wrap {
            max-width: 860px;
            margin: 48px auto;
            padding: 0 16px 64px;
        }

        /* Header */
        .pay-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 32px;
        }

        .pay-header-icon {
            width: 44px;
            height: 44px;
            background: var(--accent-lt);
            border-radius: 10px;
            display: grid;
            place-items: center;
        }

        .pay-header-icon svg {
            color: var(--accent);
        }

        .pay-header h1 {
            font-size: 20px;
            font-weight: 700;
            line-height: 1.2;
        }

        .pay-header p {
            font-size: 13px;
            color: var(--muted);
            margin-top: 2px;
        }

        /* Card */
        .pay-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
        }

        /* Two-col layout */
        .pay-body {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 420px;
        }

        @media (max-width: 640px) {
            .pay-body {
                grid-template-columns: 1fr;
            }
        }

        /* Left: invoice details */
        .pay-details {
            padding: 32px;
            border-right: 1px solid var(--border);
        }

        @media (max-width: 640px) {
            .pay-details {
                border-right: none;
                border-bottom: 1px solid var(--border);
            }
        }

        .pay-details h2 {
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: var(--muted);
            margin-bottom: 20px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid var(--border);
            font-size: 14px;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: var(--muted);
            white-space: nowrap;
        }

        .detail-value {
            font-weight: 500;
            text-align: right;
        }

        .amount-value {
            font-family: var(--mono);
            font-size: 22px;
            font-weight: 700;
            color: var(--accent);
        }

        /* Status badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-badge .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .status-badge.waiting {
            background: var(--warning-bg);
            color: var(--warning);
        }

        .status-badge.waiting .dot {
            background: var(--warning);
            animation: pulse 1.5s infinite;
        }

        .status-badge.success {
            background: var(--success-bg);
            color: var(--success);
        }

        .status-badge.success .dot {
            background: var(--success);
        }

        .status-badge.failed {
            background: var(--danger-bg);
            color: var(--danger);
        }

        .status-badge.failed .dot {
            background: var(--danger);
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: .3;
            }
        }

        /* Countdown */
        .countdown-wrap {
            margin-top: 24px;
            padding: 14px 16px;
            background: var(--bg);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .countdown-label {
            font-size: 13px;
            color: var(--muted);
        }

        .countdown-time {
            font-family: var(--mono);
            font-size: 18px;
            font-weight: 700;
            color: var(--text);
        }

        .countdown-time.urgent {
            color: var(--danger);
        }

        /* Right: QR panel */
        .pay-qr {
            padding: 32px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 16px;
        }

        /* Skeleton */
        .qr-skeleton {
            width: 220px;
            height: 220px;
            border-radius: 12px;
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.4s infinite;
        }

        .qr-skeleton-text {
            width: 140px;
            height: 14px;
            border-radius: 6px;
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.4s infinite .2s;
        }

        @keyframes shimmer {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        /* QR frame */
        .qr-frame {
            position: relative;
            padding: 12px;
            border: 2px solid var(--border);
            border-radius: 14px;
            background: #fff;
        }

        .qr-frame img {
            display: block;
            width: 196px;
            height: 196px;
            border-radius: 6px;
        }

        /* Overlays */
        .pay-overlay {
            display: none;
            position: absolute;
            inset: 0;
            border-radius: 12px;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 8px;
            font-size: 13px;
            font-weight: 600;
        }

        .pay-overlay.show {
            display: flex;
        }

        .pay-overlay.success-ov {
            background: rgba(236, 253, 243, .93);
            color: var(--success);
        }

        .pay-overlay.failed-ov {
            background: rgba(254, 243, 242, .93);
            color: var(--danger);
        }

        .pay-overlay svg {
            width: 36px;
            height: 36px;
        }

        .qr-hint {
            font-size: 12px;
            color: var(--muted);
            text-align: center;
            line-height: 1.6;
            max-width: 210px;
        }

        /* Hash input area */
        .hash-form {
            width: 100%;
            max-width: 260px;
        }

        .hash-form-label {
            font-size: 12px;
            color: var(--muted);
            text-align: center;
            margin-bottom: 8px;
            line-height: 1.5;
        }

        .hash-form-label strong {
            color: var(--text);
        }

        .hash-input-row {
            display: flex;
            gap: 6px;
        }

        .hash-input {
            flex: 1;
            padding: 10px 12px;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-family: var(--mono);
            font-size: 14px;
            outline: none;
            transition: border-color .15s;
            color: var(--text);
            text-transform: lowercase;
            letter-spacing: .05em;
        }

        .hash-input:focus {
            border-color: var(--accent);
        }

        .btn-verify {
            padding: 10px 14px;
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: opacity .15s;
        }

        .btn-verify:hover {
            opacity: .88;
        }

        .btn-verify:disabled {
            opacity: .5;
            cursor: not-allowed;
        }

        /* Footer */
        .pay-footer {
            padding: 16px 32px;
            border-top: 1px solid var(--border);
            background: var(--bg);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .pay-footer-brand {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: var(--muted);
        }

        .btn-refresh {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--surface);
            font-size: 13px;
            font-weight: 500;
            color: var(--text);
            cursor: pointer;
            transition: background .15s, border-color .15s;
        }

        .btn-refresh:hover {
            background: var(--bg);
            border-color: #c5cad3;
        }

        /* Alerts */
        .pay-alert {
            display: none;
            margin: 16px 32px 0;
            padding: 14px 16px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            align-items: flex-start;
            gap: 10px;
        }

        .pay-alert.show {
            display: flex;
        }

        .pay-alert.success {
            background: var(--success-bg);
            color: var(--success);
        }

        .pay-alert.danger {
            background: var(--danger-bg);
            color: var(--danger);
        }

        .pay-alert svg {
            flex-shrink: 0;
            margin-top: 1px;
        }

        /* Receipt sample */
        .receipt-sample {
            width: 100%;
            max-width: 260px;
            margin-bottom: 4px;
        }

        .receipt-sample-toggle {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            color: var(--accent);
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
            margin: 0 auto 8px;
            font-family: var(--sans);
        }

        .receipt-sample-toggle:hover {
            opacity: .8;
        }

        .receipt-sample-body {
            display: none;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 10px;
            font-size: 12px;
        }

        .receipt-sample-body.open {
            display: block;
        }

        .receipt-sample-top {
            padding: 10px 12px 8px;
            border-bottom: 1px dashed var(--border);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .receipt-sample-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #1a3a5c;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .receipt-sample-rows {
            padding: 4px 12px 8px;
        }

        .receipt-sample-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            border-bottom: 1px solid var(--border);
            color: var(--muted);
        }

        .receipt-sample-row:last-child {
            border-bottom: none;
        }

        .receipt-sample-row.highlight {
            background: #FFF8E6;
            margin: 0 -12px;
            padding: 6px 12px;
            border-bottom: none;
        }

        .receipt-sample-row.highlight .rs-label {
            color: #92400e;
            font-weight: 600;
        }

        .receipt-sample-row.highlight .rs-value {
            font-family: var(--mono);
            color: #92400e;
            font-weight: 700;
            letter-spacing: .05em;
        }

        .rs-value {
            font-weight: 500;
            color: var(--text);
        }
    </style>
    <div class="pay-wrap">
        {{-- Header --}}
        <div class="pay-header">
            <div class="pay-header-icon">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="7" height="7" rx="1" />
                    <rect x="14" y="3" width="7" height="7" rx="1" />
                    <rect x="3" y="14" width="7" height="7" rx="1" />
                    <path d="M14 14h3v3m0 4h4v-4m-4 0h-3v4" />
                </svg>
            </div>
            <div>
                <h1>Scan to Pay</h1>
                <p>Open your Bakong app, scan the QR, then enter the <strong>Bakong hash #</strong> from your receipt</p>
            </div>
        </div>
        {{-- Card --}}
        <div class="pay-card">
            <div class="pay-body">
                {{-- Left: details --}}
                <div class="pay-details">
                    <h2>Payment Details</h2>
                    <div class="detail-row">
                        <span class="detail-label">Course</span>
                        <span class="detail-value">{{ $invoice->course->title }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Invoice</span>
                        <span class="detail-value"
                            style="font-family:var(--mono);font-size:13px">{{ $invoice->invoice_code }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Amount</span>
                        <span class="detail-value amount-value">${{ number_format($invoice->total_amount, 2) }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Status</span>
                        <span class="detail-value">
                            <span id="status-badge" class="status-badge waiting">
                                <span class="dot"></span>
                                <span id="status-text">Waiting</span>
                            </span>
                        </span>
                    </div>
                    <div class="countdown-wrap">
                        <span class="countdown-label">QR expires in</span>
                        <span id="countdown" class="countdown-time">10:00</span>
                    </div>
                </div>
                {{-- Right: QR + hash form --}}
                <div class="pay-qr">
                    {{-- Skeleton --}}
                    <div id="qr-skeleton">
                        <div class="qr-skeleton"></div>
                        <div class="qr-skeleton-text" style="margin:14px auto 0"></div>
                    </div>
                    {{-- QR frame --}}
                    <div id="qr-frame-wrap" style="display:none">
                        <div class="qr-frame">
                            <img id="qr-image" alt="KHQR Code">
                            <div class="pay-overlay success-ov" id="overlay-success">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="m9 12 2 2 4-4" />
                                </svg>
                                Payment Confirmed
                            </div>
                            <div class="pay-overlay failed-ov" id="overlay-failed">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="m15 9-6 6m0-6 6 6" />
                                </svg>
                                Verification Failed
                            </div>
                        </div>
                        <p class="qr-hint" style="margin-top:12px">
                            Open <strong>Bakong</strong> or any KHQR app and scan.<br>
                            Make sure to pay <strong>${{ number_format($invoice->total_amount, 2) }}</strong>
                        </p>
                    </div>
                    {{-- Hash form ← ADD THIS BACK --}}
                    {{-- Hash form --}}
                    <div class="hash-form" id="hash-form" style="display:none">

                        {{-- Sample receipt toggle --}}
                        <div class="receipt-sample">
                            <button class="receipt-sample-toggle" onclick="toggleSample(this)" type="button">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 16v-4m0-4h.01" />
                                </svg>
                                Where do I find the Bakong hash?
                            </button>
                            <div class="receipt-sample-body">
                                <div class="receipt-sample-top">
                                    <div class="receipt-sample-icon">
                                        <svg width="14" height="14" fill="none" stroke="white" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <rect x="3" y="3" width="7" height="7" rx="1" />
                                            <rect x="14" y="3" width="7" height="7" rx="1" />
                                            <rect x="3" y="14" width="7" height="7" rx="1" />
                                            <path d="M14 14h3v3m0 4h4v-4m-4 0h-3v4" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div style="font-weight:600;font-size:13px;color:var(--text)">-0.01 USD</div>
                                        <div style="color:var(--muted);font-size:11px">ICTCenter</div>
                                    </div>
                                </div>
                                <div class="receipt-sample-rows">
                                    <div class="receipt-sample-row">
                                        <span class="rs-label">Trx. ID</span>
                                        <span class="rs-value"
                                            style="font-size:11px;color:var(--muted)">56175214148</span>
                                    </div>
                                    <div class="receipt-sample-row highlight">
                                        <span class="rs-label">Bakong hash #</span>
                                        <span class="rs-value">bea22256</span>
                                    </div>
                                    <div class="receipt-sample-row">
                                        <span class="rs-label">Bank</span>
                                        <span class="rs-value">Bakong</span>
                                    </div>
                                    <div class="receipt-sample-row">
                                        <span class="rs-label">Amount</span>
                                        <span class="rs-value">0.01 USD</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <p class="hash-form-label">
                            Enter the <strong>Bakong hash #</strong> from your ABA receipt (8 characters, e.g. <code
                                style="font-family:var(--mono);font-size:11px;background:var(--bg);padding:1px 5px;border-radius:4px">bea22256</code>)
                        </p>
                        <div class="hash-input-row">
                            <input id="hash-input" class="hash-input" type="text" maxlength="8"
                                placeholder="e.g. bea22256" autocomplete="off" spellcheck="false">
                            <button class="btn-verify" id="btn-verify" onclick="submitHash()">
                                Verify
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Alerts --}}
            <div id="alert-success" class="pay-alert success">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"
                    viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" />
                    <path d="m9 12 2 2 4-4" />
                </svg>
                <span>Payment confirmed! Enrolling you in the course — redirecting shortly…</span>
            </div>
            <div id="alert-error" class="pay-alert danger" style="margin-bottom:0">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"
                    viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" />
                    <path d="m15 9-6 6m0-6 6 6" />
                </svg>
                <span id="alert-error-text"></span>
            </div>
            {{-- Footer --}}
            <div class="pay-footer" style="margin-top:16px">
                <div class="pay-footer-brand">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                    </svg>
                    Secured by Bakong · National Bank of Cambodia
                </div>
                <button class="btn-refresh" onclick="regenerateQr()">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                        viewBox="0 0 24 24">
                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
                        <path d="M3 3v5h5" />
                    </svg>
                    New QR
                </button>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        let timer = null;
        let seconds = 600;
        let isDone = false;
        generateQr();

        function generateQr() {
            isDone = false;
            resetUI();
            startCountdown();
            $.ajax({
                url: "{{ route('bakong.generate-qr') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    invoice_id: '{{ $invoice->id }}'
                },
                success(res) {
                    if (!res.success) {
                        showError(res.message ?? 'Unable to generate QR.');
                        return;
                    }
                    showQr(res.qr_image);
                    document.getElementById('hash-form').style.display = '';
                },
                error(xhr) {
                    showError(xhr.responseJSON?.message ?? 'Unable to generate QR.');
                }
            });
        }

        function regenerateQr() {
            if (isDone) return;
            stopCountdown();
            seconds = 600;
            generateQr();
        }

        function submitHash() {
            const hash = document.getElementById('hash-input').value.trim().toLowerCase();
            if (hash.length !== 8) {
                showError('Please enter the 8-character <strong>Bakong hash</strong> from your receipt.');
                return;
            }
            const btn = document.getElementById('btn-verify');
            btn.disabled = true;
            btn.textContent = 'Verifying…';
            document.getElementById('alert-error').classList.remove('show');
            $.ajax({
                url: "{{ route('bakong.verify-hash') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    hash,
                    invoice_id: '{{ $invoice->id }}'
                },
                success(res) {
                    if (res.status === 'success') {
                        onSuccess();
                    } else {
                        btn.disabled = false;
                        btn.textContent = 'Verify';
                        showError(res.message ?? 'Verification failed. Please check your hash and try again.');
                        document.getElementById('overlay-failed').classList.add('show');
                        setTimeout(() => {
                            document.getElementById('overlay-failed').classList.remove('show');
                        }, 2000);
                    }
                },
                error() {
                    btn.disabled = false;
                    btn.textContent = 'Verify';
                    showError('Network error. Please try again.');
                }
            });
        }
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('hash-input')?.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') submitHash();
            });
        });

        function onSuccess() {
            if (isDone) return;
            isDone = true;
            stopCountdown();
            setStatus('success', 'Paid');
            document.getElementById('overlay-success').classList.add('show');
            document.getElementById('alert-success').classList.add('show');
            document.getElementById('hash-form').style.display = 'none';
            setTimeout(() => {
                window.location.href = '{{ route('student.dashboard') }}';
            }, 2500);
        }

        function onExpired() {
            isDone = false;
            setStatus('failed', 'Expired');
            showError('QR code expired. Click <strong>New QR</strong> to generate a new one.');
        }

        function startCountdown() {
            stopCountdown();
            renderCountdown();
            timer = setInterval(() => {
                seconds--;
                renderCountdown();
                if (seconds <= 0) {
                    stopCountdown();
                    onExpired();
                }
            }, 1000);
        }

        function stopCountdown() {
            clearInterval(timer);
            timer = null;
        }

        function renderCountdown() {
            const m = String(Math.floor(seconds / 60)).padStart(2, '0');
            const s = String(seconds % 60).padStart(2, '0');
            const el = document.getElementById('countdown');
            el.textContent = `${m}:${s}`;
            el.classList.toggle('urgent', seconds <= 60);
        }

        function resetUI() {
            document.getElementById('qr-skeleton').style.display = '';
            document.getElementById('qr-frame-wrap').style.display = 'none';
            document.getElementById('hash-form').style.display = 'none';
            document.getElementById('qr-image').src = '';
            document.getElementById('overlay-success').classList.remove('show');
            document.getElementById('overlay-failed').classList.remove('show');
            document.getElementById('alert-success').classList.remove('show');
            document.getElementById('alert-error').classList.remove('show');
            document.getElementById('hash-input').value = '';
            document.getElementById('btn-verify').disabled = false;
            document.getElementById('btn-verify').textContent = 'Verify';
            setStatus('waiting', 'Waiting');
        }

        function showQr(src) {
            document.getElementById('qr-image').src = src;
            document.getElementById('qr-skeleton').style.display = 'none';
            document.getElementById('qr-frame-wrap').style.display = '';
        }

        function showError(msg) {
            document.getElementById('alert-error-text').innerHTML = msg;
            document.getElementById('alert-error').classList.add('show');
        }

        function setStatus(type, label) {
            document.getElementById('status-badge').className = `status-badge ${type}`;
            document.getElementById('status-text').textContent = label;
        }

        function toggleSample(btn) {
            const body = btn.nextElementSibling;
            const open = body.classList.toggle('open');
            btn.querySelector('svg').style.transform = open ? 'rotate(0deg)' : '';
            btn.childNodes[1].textContent = open ? ' Hide sample receipt' : ' Where do I find the Bakong hash?';
        }
    </script>
@endpush
