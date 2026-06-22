<?php
namespace App\Services;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use KHQR\BakongKHQR;
use KHQR\Helpers\KHQRData;
use KHQR\Models\MerchantInfo;
class BakongService
{
    protected string $baseUrl;
    protected string $token;
    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.bakong.api_url', 'https://api-bakong.nbc.gov.kh'), '/');
        $this->token = config('services.bakong.token', '');
    }
    /*──────────────────────────────────────────
     | 1. Generate a static KHQR string
     |    (personal @bkrt account — no amount)
     |    Returns ['qr' => string, 'md5' => string]
     ──────────────────────────────────────────*/
    public function generateKHQR(
        string $merchantId,
        string $merchantName,
        string $city,
        float $amount,
        string $currency,
        string $description = '',
        string $transactionId = ''
    ): array {
        try {
            $merchantInfo = new MerchantInfo(
                bakongAccountID: $merchantId,
                merchantName: $merchantName,
                merchantCity: $city,
                merchantID: 'ICT001',
                acquiringBank: 'Bakong',
                accountInformation: null,
                currency: KHQRData::CURRENCY_USD,
            );
            $response = BakongKHQR::generateMerchant($merchantInfo);
            $qr = $response->data['qr'];
            return [
                'success' => true,
                'qr' => $qr,
                'md5' => $response->data['md5'],
                'transaction_id' => $transactionId,
                'currency' => $currency,
            ];

        } catch (\Throwable $e) {
            Log::error('KHQR Generate Error', ['message' => $e->getMessage()]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    /*──────────────────────────────────────────
     | 2. Verify payment by short hash
     |    (8-char "External trnx. ref" from receipt)
     ──────────────────────────────────────────*/
    public function checkTransactionByShortHash(string $hash, float $amount, string $currency): array
    {
        try {
            $response = Http::withHeaders($this->authHeaders())
                ->timeout(15)
                ->asJson()  // ← force proper JSON encoding
                ->post("{$this->baseUrl}/v1/check_transaction_by_short_hash", [
                    'hash' => $hash,
                    'amount' => (float) $amount,
                    'currency' => $currency,
                ]);
            return $response->json() ?? [];
        } catch (\Throwable $e) {
            Log::error('Bakong checkTransactionByShortHash error', ['error' => $e->getMessage()]);
            return ['responseCode' => 1, 'responseMessage' => $e->getMessage(), 'data' => null];
        }
    }
    /*──────────────────────────────────────────
     | 3. Renew token via API
     ──────────────────────────────────────────*/
    public function renewToken(string $email): array
    {
        try {
            $response = Http::timeout(15)
                ->post("{$this->baseUrl}/v1/renew_token", ['email' => $email]);
            return $response->json() ?? [];
        } catch (\Throwable $e) {
            Log::error('Bakong renewToken error', ['error' => $e->getMessage()]);
            return ['responseCode' => 1, 'responseMessage' => $e->getMessage()];
        }
    }
    /*──────────────────────────────────────────
     | 4. Check if a Bakong account ID exists
     ──────────────────────────────────────────*/
    public function checkAccount(string $accountId): bool
    {
        try {
            $response = Http::withHeaders($this->authHeaders())
                ->timeout(10)
                ->post("{$this->baseUrl}/v1/check_bakong_account", ['accountId' => $accountId]);
            $json = $response->json();
            return ($json['responseCode'] ?? 1) === 0;
        } catch (\Throwable $e) {
            Log::error('Bakong checkAccount error', ['error' => $e->getMessage()]);
            return false;
        }
    }
    /*──────────────────────────────────────────
     | Helpers
     ──────────────────────────────────────────*/
    private function authHeaders(): array
    {
        return [
            'Authorization' => "Bearer {$this->token}",
            'Content-Type' => 'application/json',
        ];
    }
    public function checkTransactionByExternalRef(string $externalRef): array
    {
        try {
            $response = Http::withHeaders($this->authHeaders())
                ->timeout(15)
                ->asJson()
                ->post("{$this->baseUrl}/v1/check_transaction_by_external_ref", [
                    'externalRef' => $externalRef,
                ]);
            return $response->json() ?? [];
        } catch (\Throwable $e) {
            Log::error('Bakong checkTransactionByExternalRef error', ['error' => $e->getMessage()]);
            return ['responseCode' => 1, 'responseMessage' => $e->getMessage(), 'data' => null];
        }
    }
}
