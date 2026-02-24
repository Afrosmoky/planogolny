<?php

declare(strict_types=1);

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
            'raw_body' => $request->getContent(),
            'parsed' => $request->all(),
            'headers' => [
                'content-type' => $request->header('Content-Type'),
                'x-jws-signature' => $request->header('X-JWS-Signature'),
                'x-tpay-signature' => $request->header('X-Tpay-Signature'),
            ],
        ]);
        return new self(
            transactionId: (string) $request->input('tr_id'),
            merchantTransactionId: (string) $request->input('tr_crc'),
            amount: (int) $request->input('tr_amount'),
            status: (string) $request->input('tr_status'),
            signature: (string) $request->header('X-Tpay-Signature')
        );
    }
}
