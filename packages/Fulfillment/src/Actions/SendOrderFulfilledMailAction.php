<?php

declare(strict_types=1);

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
        $mail = Mail::to($order->email);

        if ($bcc = config('mail.order_notification')) {
            $mail->bcc($bcc);
        }

        $mail->send(
            new OrderFulfilledMail(
                order: $order,
                reportPath: $reportPath,
                invoicePath: $invoicePath
            )
        );
    }
}
