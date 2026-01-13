<?php
namespace Planogolny\Reporting\Actions;

use Illuminate\Support\Facades\Mail;
use Planogolny\Invoicing\DTO\InvoiceDTO;
use Planogolny\Orders\Models\Order;
use Planogolny\Reporting\DTO\ReportPdfDTO;
use Planogolny\Reporting\Mail\ReportMail;

final class SendReportEmailAction
{
    public function execute(
        Order $order,
        ReportPdfDTO $report,
        ?InvoiceDTO $invoice = null
    ): void {
        Mail::to($order->email)->send(
            new ReportMail($report, $invoice)
        );
    }
}
