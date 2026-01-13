<?php

namespace Planogolny\Invoicing\DTO;

final readonly class InvoiceRequestDTO
{
    public function __construct(
        public string $orderId,
        public string $buyerEmail,
        public string $buyerName,
        public string $buyerCity,
        public string $buyerAddressStreet,
        public string $buyerPostCode,
        public string $buyerCountry = 'PL',
        public string $buyerTaxNumber,
        public int $amount,
        public string $currency = 'PLN',
        public string $description
    ) {}
}
