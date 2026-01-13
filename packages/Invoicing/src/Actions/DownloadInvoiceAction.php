<?php

namespace Planogolny\Invoicing\Actions;

use Planogolny\Invoicing\Services\IngInvoiceApi;
use Planogolny\Invoicing\DTO\InvoiceDTO;
use Planogolny\Invoicing\Exceptions\InvoiceException;
use Planogolny\Invoicing\Events\InvoiceDownloaded;

final readonly class DownloadInvoiceAction
{
    public function __construct(
        private IngInvoiceApi $api
    ) {}

    public function execute(string $invoiceId): InvoiceDTO
    {
        try {
            $invoice = $this->api->downloadInvoicePdf($invoiceId);

            InvoiceDownloaded::dispatch($invoice->invoiceNumber);

            return $invoice;
        } catch (\Throwable $e) {
            throw new InvoiceException('Invoice download failed: ' . $e->getMessage(), 0, $e);
        }
    }
}
