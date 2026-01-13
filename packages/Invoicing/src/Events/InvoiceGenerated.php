<?php

namespace Planogolny\Invoicing\Events;

use Planogolny\Invoicing\DTO\InvoiceDTO;
use Planogolny\Orders\Models\Order;

final class InvoiceGenerated
{
    public function __construct(
        public readonly Order $order,
        public readonly InvoiceDTO $invoicePdf
    ) {}
}
