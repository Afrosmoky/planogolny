<?php

namespace Planogolny\Invoicing\Actions;

use Planogolny\Invoicing\DTO\InvoiceRequestDTO;
use Planogolny\Invoicing\Services\IngInvoiceApi;
use Planogolny\Invoicing\Exceptions\InvoiceException;
use Planogolny\Invoicing\Events\InvoiceCreated;

final readonly class CreateInvoiceAction
{
    public function __construct(
        private IngInvoiceApi $api
    ) {}

    public function execute(InvoiceRequestDTO $dto): string
    {
        try {
            $invoiceId = $this->api->createInvoice($dto);

            InvoiceCreated::dispatch($invoiceId);

            return $invoiceId;
        } catch (\Throwable $e) {
            throw new InvoiceException('Invoice creation failed: ' . $e->getMessage(), 0, $e);
        }
    }
}
