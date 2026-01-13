<?php

namespace Planogolny\Invoicing\Listeners;

use Planogolny\Invoicing\Events\InvoiceGenerated;
use Planogolny\Orders\Events\OrderPaid;
use Planogolny\Orders\Models\Order;
use Planogolny\Invoicing\Actions\CreateInvoiceAction;
use Planogolny\Invoicing\Actions\DownloadInvoiceAction;
use Planogolny\Invoicing\DTO\InvoiceRequestDTO;

final class HandleInvoiceGenerated
{
    public function __construct(
        private CreateInvoiceAction $createInvoice,
        private DownloadInvoiceAction $downloadInvoice
    ) {}

    public function handle(InvoiceGenerated $event): void
    {
        $order = $event->order;

        $invoicePdf = $event->invoicePdf;

        // send mail Action(invoice and pdf report) to  customer and portal owner

        $invoiceNumber = $this->createInvoice->execute(
            new InvoiceRequestDTO(
                orderId: $order->id,
                buyerEmail: $order->email,
                buyerName: $order->name,
                buyerCity: $order->invoice_data['buyerCity'],
                buyerAddressStreet: $order->invoice_data['buyerAddressStreet'],
                buyerPostCode: $order->invoice_data['buyerPostCode'],
                buyerTaxNumber: $order->invoice_data['buyerTaxNumber'],
                amount: $order->amount,
                currency: $order->currency,
                description: 'Raport planistyczny – PlanOgólny.info',
            )
        );


        $invoicePdf = $this->downloadInvoice->execute($invoiceNumber);

    }
}

