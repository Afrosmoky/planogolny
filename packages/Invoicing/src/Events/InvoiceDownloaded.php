<?php

namespace Planogolny\Invoicing\Events;

use Illuminate\Foundation\Events\Dispatchable;

final class InvoiceDownloaded
{
    use Dispatchable;

    public function __construct(
        public readonly string $invoiceId
    ) {}
}
