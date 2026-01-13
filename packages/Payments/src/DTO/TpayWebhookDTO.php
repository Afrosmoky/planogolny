<?php

namespace Planogolny\Payments\DTO;

use Illuminate\Http\Request;

final readonly class TpayWebhookDTO
{
    public function __construct(
        public string $transactionId,
        public string $orderId,
        public int $amount,
        public string $status,
        public string $signature,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            transactionId: (string) $request->input('transaction_id'),
            orderId: (string) $request->input('order_id'),
            amount: (int) $request->input('amount'),
            status: (string) $request->input('status'),
            signature: (string) $request->header('X-Tpay-Signature')
        );
    }
}
