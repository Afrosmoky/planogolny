<?php

namespace Planogolny\Reporting\Events;

use Illuminate\Foundation\Events\Dispatchable;

final class ReportGenerated
{
    use Dispatchable;

    public function __construct(
        public readonly int $orderId,
        public readonly string $reportPath
    ) {}
}
