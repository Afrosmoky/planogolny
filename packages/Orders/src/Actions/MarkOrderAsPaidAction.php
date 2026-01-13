<?php

namespace Planogolny\Orders\Actions;
use Illuminate\Contracts\Events\Dispatcher;
use Planogolny\Orders\Enums\OrderStatus;
use Planogolny\Orders\Events\OrderPaid;
use Planogolny\Orders\Models\Order;

final class MarkOrderAsPaidAction
{
    public function __construct(
        protected Dispatcher $events
    ) {}

    public function execute(
        Order $order,
        string $paymentProvider,
        string $externalPaymentId
    ): Order {
        if ($order->status === OrderStatus::PAID) {
            return $order;
        }

        $order->update([
            'status' => OrderStatus::PAID,
            'payment_provider' => $paymentProvider,
            'external_payment_id' => $externalPaymentId,
        ]);
        info('Order marked as paid');
        $this->events->dispatch(new OrderPaid($order));

        return $order;
    }
}

