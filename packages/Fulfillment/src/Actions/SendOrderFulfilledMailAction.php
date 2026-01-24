<?php

namespace Planogolny\Fulfillment\Actions;

use Planogolny\Orders\Models\Order;
use Illuminate\Support\Facades\Mail;
use Planogolny\Fulfillment\Mail\OrderFulfilledMail;

final class SendOrderFulfilledMailAction
{
    public function execute(
        Order $order,
        string $reportPath,
        ?string $invoicePath = null
    ): void {
        Mail::to($order->email)
            ->bcc(config('mail.order_notification'))
            ->send(
            new OrderFulfilledMail(
                order: $order,
                reportPath: $reportPath,
                invoicePath: $invoicePath
            )
        );
    }
}
