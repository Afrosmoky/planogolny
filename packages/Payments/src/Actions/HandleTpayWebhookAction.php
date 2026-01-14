<?php

namespace Planogolny\Payments\Actions;

use Planogolny\Orders\Actions\MarkOrderAsPaidAction;
use Planogolny\Orders\Models\Order;
use Planogolny\Payments\DTO\TpayWebhookDTO;
use Planogolny\Payments\Services\TpayClient;

final class HandleTpayWebhookAction
{
    public function __construct(
        protected TpayClient $client,
        protected MarkOrderAsPaidAction $markOrderAsPaid,
    ) {}

    public function execute(TpayWebhookDTO $dto): void
    {
        info('TPAY webhook run');
        $rawBody = request()->getContent();
        $signature = request()->header('X-JWS-Signature');

        if (! $this->client->verifySignature($rawBody, $signature)) {
            abort(403, 'Invalid TPay signature');
        }
        info('TPAY signature OK');
        info('TPAY status', ['status' => $dto->status]);

        if ($dto->status !== 'SUCCESS') {
            return;
        }

        $order = Order::findOrFail((int) $dto->merchantTransactionId);

        $this->markOrderAsPaid->execute(
            order: $order,
            paymentProvider: 'tpay',
            externalPaymentId: $dto->transactionId
        );
    }
}
