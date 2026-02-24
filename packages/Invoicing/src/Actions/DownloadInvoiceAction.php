<?php

declare(strict_types=1);

namespace Planogolny\Invoicing\Actions;

use Planogolny\Invoicing\Services\IngInvoiceApi;
use Planogolny\Invoicing\DTO\InvoiceDTO;
use Planogolny\Invoicing\Exceptions\InvoiceException;

final readonly class DownloadInvoiceAction
{
    public function __construct(
        private IngInvoiceApi $api
    ) {}

    public function execute(string $invoiceId): InvoiceDTO
    {
        try {
            $invoice = $this->api->downloadInvoicePdf($invoiceId);

            return $invoice;
        } catch (\Throwable $e) {
            throw new InvoiceException('Invoice download failed: ' . $e->getMessage(), 0, $e);
        }
    }
}
