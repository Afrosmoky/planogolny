<?php

declare(strict_types=1);

namespace Planogolny\Fulfillment\Listeners;

use Planogolny\Invoicing\Actions\DownloadInvoiceAction;
use Planogolny\Invoicing\DTO\InvoiceRequestDTO;
use Planogolny\Orders\Events\OrderPaid;
use Planogolny\Fulfillment\Actions\SendOrderFulfilledMailAction;
use Planogolny\Reporting\Actions\GenerateReportPdfAction;
use Planogolny\Invoicing\Actions\CreateInvoiceAction;

final class FulfillPaidOrderListener
{

    public function handle(OrderPaid $event): void
    {
        $order = $event->order;
        logger('FulfillmentPaidOrderListener activated');

        $reportPath = app(GenerateReportPdfAction::class)
            ->execute($order);
        logger('FulfillmentPaidOrderListener after generate report');

        $invoicePath = null;

        $invoiceId = app(CreateInvoiceAction::class)->execute(
            new InvoiceRequestDTO(
                orderId: $order->id,
                buyerEmail: $order->email,
                buyerName: $order->invoice_type === 'b2b'
                    ? $order->invoice_data['company_name']
                    : $order->invoice_data['first_name'].' '.$order->invoice_data['last_name'],
                buyerCity: $order->invoice_data['address']['city'],
                buyerAddressStreet: $order->invoice_data['address']['line'],
                buyerPostCode: $order->invoice_data['address']['postal_code'],
                buyerCountry: 'PL',
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


        app(SendOrderFulfilledMailAction::class)->execute(
            order: $order,
            reportPath: $reportPath->pdfBinary,
            invoicePath: $invoicePath->pdfBinary,
        );

        logger('FulfillmentPaidOrderListener after send report and invoice');
    }
}
