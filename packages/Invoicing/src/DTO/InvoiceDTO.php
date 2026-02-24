<?php

declare(strict_types=1);

namespace Planogolny\Invoicing\DTO;

final readonly class InvoiceDTO
{
    public function __construct(
        public string $invoiceNumber,
        public string $pdfBinary
    ) {}
}
