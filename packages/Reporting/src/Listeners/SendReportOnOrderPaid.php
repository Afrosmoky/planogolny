<?php

namespace Planogolny\Reporting\Listeners;

use Planogolny\Orders\Events\OrderPaid;
use Planogolny\Reporting\Actions\GenerateReportPdfAction;
use Planogolny\Reporting\Actions\SendReportEmailAction;

final class SendReportOnOrderPaid
{
    public function __construct(
        protected GenerateReportPdfAction $generateReport,
        protected SendReportEmailAction $sendEmail
    ) {}

    public function handle(OrderPaid $event): void
    {
        if (config('ing.ing_invoicing')) {
            return; // czekamy na InvoiceGenerated
        }
        logger('Listener hitted');
        $order = $event->order;

        $report = $this->generateReport->execute($order);

        $this->sendEmail->execute($order, $report);
    }
}
