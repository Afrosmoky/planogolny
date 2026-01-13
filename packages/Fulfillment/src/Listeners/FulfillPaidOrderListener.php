<?php

namespace Planogolny\Fulfillment\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Planogolny\Invoicing\Actions\DownloadInvoiceAction;
use Planogolny\Invoicing\DTO\InvoiceRequestDTO;
use Planogolny\Orders\Events\OrderPaid;
use Planogolny\Fulfillment\Actions\SendOrderFulfilledMailAction;
use Planogolny\Reporting\Actions\GenerateReportPdfAction;
use Planogolny\Invoicing\Actions\CreateInvoiceAction;

final class FulfillPaidOrderListener //implements ShouldQueue
{
    //use InteractsWithQueue;

    public function handle(OrderPaid $event): void
    {
        $order = $event->order;
        logger('FulfillmentPaidOrderListener activated');
        // 1️⃣ Generuj raport
        $reportPath = app(GenerateReportPdfAction::class)
            ->execute($order);
        logger('FulfillmentPaidOrderListener after generate report');
        // 2️⃣ Generuj fakturę (jeśli są dane)
        $invoicePath = null;

//        if ($order->hasInvoiceData()) {
//            $invoiceId = app(CreateInvoiceAction::class)
//                ->execute(
//                    order: $order,
//                    invoiceData: $order->invoice_data,
//                    type: $order->invoice_type
//                );
//        }

        $invoiceId = app(CreateInvoiceAction::class)->execute(
            new InvoiceRequestDTO(
                orderId: $order->id,
                buyerEmail: $order->email,
//                buyerName: $order->invoice_data['first_name'],
                buyerName: $order->invoice_type === 'b2b'
                    ? $order->invoice_data['company_name']
                    : $order->invoice_data['first_name'].' '.$order->invoice_data['last_name'],
                buyerCity: $order->invoice_data['address']['city'],
                buyerAddressStreet: $order->invoice_data['address']['line'],
                buyerPostCode: $order->invoice_data['address']['postal_code'],
                buyerCountry: 'PL',
//                buyerTaxNumber: $order->invoice_data['nip'],
                buyerTaxNumber:  $order->invoice_type === 'b2b'
                    ? $order->invoice_data['nip']
                    : ' ',
                amount: $order->amount,
                currency: $order->currency,
                description: 'Raport planistyczny – PlanOgólny.info',
            )
        );
        logger('FulfillmentPaidOrderListener after generate invoice');
        logger('FulfillmentPaidOrderListener before download invoice. Downloading invoice with id: '.$invoiceId);
        if($invoiceId) {
            $invoicePath = app(DownloadInvoiceAction::class)
                ->execute($invoiceId);
        }

        // 3️⃣ Wyślij JEDNEGO maila
        app(SendOrderFulfilledMailAction::class)->execute(
            order: $order,
            reportPath: $reportPath->pdfBinary,
            invoicePath: $invoicePath->pdfBinary,
        );
        logger('FulfillmentPaidOrderListener after send report and invoice');
    }
}
