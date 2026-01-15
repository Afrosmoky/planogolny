<?php

namespace Planogolny\Payments\Services;

use Illuminate\Support\Facades\Http;
use Jose\Component\Core\JWK;
use Jose\Component\Core\AlgorithmManager;
use Jose\Component\KeyManagement\JWKFactory;
use Jose\Component\Signature\JWSVerifier;
use Jose\Component\Signature\Serializer\CompactSerializer;
use Jose\Component\Signature\Algorithm\ES256;

final readonly class TpayClient
{
    public function __construct(
        private string $baseUrl,
        private string $clientId,
        private string $clientSecret,
        private string $env,
    ) {}

    private function getAccessToken(): string
    {
        $response = Http::asForm()->post($this->baseUrl . '/oauth/auth', [
            'grant_type' => 'client_credentials',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ]);

        if (! $response->successful()) {
            throw new \RuntimeException(
                'TPay auth error: ' . $response->body()
            );
        }

        return $response['access_token'];
    }

    public function createTransaction(array $payload): array
    {
//        if($this->env === 'dev') {
//            return [
//                'transactionId' => 'TPAY_TX_' . uniqid(),
//                'redirectUrl' => 'https://secure.tpay.com/redirect/placeholder',
//            ];
//        }
        $token = $this->getAccessToken();
        info("Notification url: ".$payload['notify_url']);
        info("Success url: ".$payload['return_url']);
        $response = Http::withToken($token)
            ->acceptJson()
            ->post($this->baseUrl . '/transactions', [
                'merchantTransactionId' => (string) $payload['order_id'],
                'amount' => $payload['amount'],          // np. 1.00 albo 0.10
                'description' => $payload['description'],
                'payer' => [
                    'email' => $payload['email'],
                    'name'  => $payload['name'] ?? null,
                ],
                'callbacks' => [
                    'notification' => [
                        'url' => $payload['notify_url'],
                    ],
                    'payerUrls' => [
                        'success' => $payload['return_url'],
                        'error'   => $payload['return_url'],
                    ],
                ],
            ]);

        if (! $response->successful()) {
            throw new \RuntimeException(
                'TPay transaction error: ' . $response->body()
            );
        }

        return [
            'transactionId' => $response['transactionId'],
            'redirectUrl'   => $response['transactionPaymentUrl'],
        ];
    }

    public function verifySignature(string $rawBody, ?string $signature): bool
    {
        if (app()->environment(['local', 'testing'])) {
            return true;
        }

        if (! $signature) {
            return false;
        }

        try {
            $publicKeyPem = file_get_contents(
                storage_path('app/tpay/notifications-jws.pem')
            );

            $jwk = JWKFactory::createFromKey($publicKeyPem);

            // ✅ TO JEST KLUCZOWA POPRAWKA
            $algorithmManager = new AlgorithmManager([
                new ES256(),
            ]);

            $verifier = new JWSVerifier($algorithmManager);

            $serializer = new CompactSerializer();
            $jws = $serializer->unserialize($signature);

            return $verifier->verifyWithKey(
                $jws,
                $jwk,
                0,
                $rawBody
            );

        } catch (\Throwable $e) {
            report($e);
            return false;
        }
    }
}
