<?php

namespace Planogolny\Invoicing\DTO;

final readonly class InvoiceDTO
{
    public function __construct(
        public string $invoiceNumber,
        public string $pdfBinary
    ) {}
}
