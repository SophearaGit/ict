<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Thin wrapper around ABA PayWay's "Purchase" (checkout) API and the
 * "Transaction Detail" status-check API.
 *
 * Docs: https://developer.payway.com.kh/purchase-14530820e0
 *       https://developer.payway.com.kh/get-a-transaction-details-14530824e0
 */
class PayWayService
{
    protected string $apiUrl;
    protected string $apiKey;
    protected string $merchantId;

    public function __construct()
    {
        $this->apiUrl = rtrim(config('services.payway.api_url'), '/');
        $this->apiKey = (string) config('services.payway.api_key');
        $this->merchantId = (string) config('services.payway.merchant_id');
    }

    /**
     * Build the full set of form fields (including hash) needed to POST to
     * PayWay's purchase endpoint / feed into the checkout2-0.js widget.
     *
     * Only pass values you intend to actually send — every field must be
     * present (as an empty string when unused) because the hash is a fixed
     * concatenation defined by PayWay, not just of the fields you chose to
     * send.
     */
    public function buildPurchasePayload(array $params): array
    {
        $reqTime = now()->format('YmdHis');

        $fields = [
            'req_time' => $reqTime,
            'merchant_id' => $this->merchantId,
            'tran_id' => (string) $params['tran_id'],
            'amount' => (string) $params['amount'],
            'items' => $params['items'] ?? '',
            'shipping' => isset($params['shipping'])
                ? number_format((float) $params['shipping'], 2, '.', '')
                : '0.00',
            'firstname' => $params['firstname'] ?? '',
            'lastname' => $params['lastname'] ?? '',
            'email' => $params['email'] ?? '',
            'phone' => $params['phone'] ?? '',
            'type' => $params['type'] ?? 'purchase',
            'payment_option' => $params['payment_option'] ?? '',
            'return_url' => $params['return_url'] ?? '',
            'cancel_url' => $params['cancel_url'] ?? '',
            'continue_success_url' => $params['continue_success_url'] ?? '',
            'return_deeplink' => $params['return_deeplink'] ?? '',
            'currency' => $params['currency'] ?? 'USD',
            'custom_fields' => $params['custom_fields'] ?? '',
            'return_params' => $params['return_params'] ?? '',
            'payout' => $params['payout'] ?? '',
            'lifetime' => $params['lifetime'] ?? '',
            'additional_params' => $params['additional_params'] ?? '',
            'google_pay_token' => $params['google_pay_token'] ?? '',
            'skip_success_page' => $params['skip_success_page'] ?? '',
        ];

        $fields['hash'] = $this->hashPurchase($fields);

        return $fields;
    }

    /**
     * Hash order for the Purchase API is fixed by PayWay and is NOT the
     * same order as the request-parameter table in their docs. See:
     * https://developer.payway.com.kh/purchase-14530820e0#hash-generation
     */
    protected function hashPurchase(array $fields): string
    {
        $string =
            $fields['req_time'] .
            $fields['merchant_id'] .
            $fields['tran_id'] .
            $fields['amount'] .
            $fields['items'] .
            $fields['shipping'] .
            $fields['firstname'] .
            $fields['lastname'] .
            $fields['email'] .
            $fields['phone'] .
            $fields['type'] .
            $fields['payment_option'] .
            $fields['return_url'] .
            $fields['cancel_url'] .
            $fields['continue_success_url'] .
            $fields['return_deeplink'] .
            $fields['currency'] .
            $fields['custom_fields'] .
            $fields['return_params'] .
            $fields['payout'] .
            $fields['lifetime'] .
            $fields['additional_params'] .
            $fields['google_pay_token'] .
            $fields['skip_success_page'];

        return base64_encode(hash_hmac('sha512', $string, $this->apiKey, true));
    }

    /**
     * Query PayWay directly for the authoritative status of a transaction.
     * This is the source of truth — always confirm via this call (or the
     * push to return_url) rather than trusting anything the browser says.
     *
     * NOTE: PayWay rate-limits this endpoint to 10 requests/minute per
     * their docs, so don't poll it too aggressively from the frontend.
     */
    public function getTransactionDetail(string $tranId): array
    {
        $reqTime = now()->format('YmdHis');
        $hashString = $reqTime . $this->merchantId . $tranId;
        $hash = base64_encode(hash_hmac('sha512', $hashString, $this->apiKey, true));

        $response = Http::asJson()
            ->post("{$this->apiUrl}/api/payment-gateway/v1/payments/transaction-detail", [
                'req_time' => $reqTime,
                'merchant_id' => $this->merchantId,
                'tran_id' => $tranId,
                'hash' => $hash,
            ]);

        if (!$response->successful()) {
            Log::warning('PayWay transaction-detail request failed', [
                'tran_id' => $tranId,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'status' => null,
                'raw' => $response->json(),
            ];
        }

        $body = $response->json();
        $status = $body['data']['payment_status'] ?? null;

        return [
            'success' => true,
            'status' => $status, // e.g. APPROVED, PENDING, DECLINED
            'approved' => $status === 'APPROVED',
            'data' => $body['data'] ?? null,
            'raw' => $body,
        ];
    }

    public function checkoutJsUrl(): string
    {
        return config(
            'services.payway.checkout_js_url',
            'https://checkout.payway.com.kh/plugins/checkout2-0.js'
        );
    }
}
