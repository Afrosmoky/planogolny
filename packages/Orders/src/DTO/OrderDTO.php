<?php

namespace Planogolny\Orders\DTO;

final readonly class OrderDTO
{
    public function __construct(
        public int $analysisId,
        public string $email,
        public string $addressText,
        public int $amount,
        public string $currency = 'PLN',
        public string $paymentProvider = 'TPay',
        public string $invoiceType,
        public array $invoiceData,
    ) {}
}
