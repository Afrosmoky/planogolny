<?php

namespace Planogolny\Payments\DTO;

final readonly class TpayTransactionDTO
{
    public function __construct(
        public int $orderId,
        public float $amount,
        public string $email,
        public string $returnUrl,
        public string $notifyUrl,
        public string $description,
    ) {}
}
