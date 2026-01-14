<?php

namespace Planogolny\Payments\DTO;

use Illuminate\Http\Request;

final readonly class TpayWebhookDTO
{
    public function __construct(
        public string $transactionId,
        public string $merchantTransactionId,
        public int $amount,
        public string $status,
        public string $signature,
    ) {}

    public static function fromRequest(Request $request): self
    {
        info('TPAY WEBHOOK DEBUG', [
            // surowe body (najważniejsze)
            'raw_body' => $request->getContent(),

            // sparsowane dane (jak Laravel je widzi)
            'parsed' => $request->all(),

            // tylko nagłówki istotne dla TPay
            'headers' => [
                'content-type' => $request->header('Content-Type'),
                'x-jws-signature' => $request->header('X-JWS-Signature'),
                'x-tpay-signature' => $request->header('X-Tpay-Signature'),
            ],
        ]);
        return new self(
            transactionId: (string) $request->input('transaction_id'),
            merchantTransactionId: (string) $request->input('$merchantTransactionId'),
            amount: (int) $request->input('amount'),
            status: (string) $request->input('status'),
            signature: (string) $request->header('X-Tpay-Signature')
        );
    }
}
