<?php

namespace Planogolny\Reporting\Listeners;

use Planogolny\Invoicing\Events\InvoiceGenerated;
use Planogolny\Reporting\Actions\GenerateReportPdfAction;
use Planogolny\Reporting\Actions\SendReportEmailAction;

final class SendReportOnInvoiceGenerated
{
    public function __construct(
        protected GenerateReportPdfAction $generateReport,
        protected SendReportEmailAction $sendEmail
    ) {}

    public function handle(InvoiceGenerated $event): void
    {
        $order = $event->order;

        $report = $this->generateReport->execute($order);

        $this->sendEmail->execute(
            order: $order,
            report: $report,
            invoice: $event->invoicePdf
        );
    }
}
