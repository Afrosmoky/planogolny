<?php

namespace Planogolny\Payments\Actions;

use Planogolny\Payments\DTO\TpayTransactionDTO;
use Planogolny\Payments\Services\TpayClient;

final class CreateTpayTransactionAction
{
    public function __construct(
        protected TpayClient $client
    ) {}

    public function execute(TpayTransactionDTO $dto): array
    {
        return $this->client->createTransaction([
            'amount' => $dto->amount,
            'email' => $dto->email,
            'description' => $dto->description,
            'return_url' => $dto->returnUrl,
            'notify_url' => $dto->notifyUrl,
            'order_id' => (string) $dto->orderId,
        ]);
    }
}
