<?php

namespace Planogolny\Orders\Actions;

use Planogolny\Orders\Enums\OrderStatus;
use Planogolny\Orders\Models\Order;
use Planogolny\Orders\Events\OrderPaid;
use Planogolny\Orders\Exceptions\OrderException;

final class MarkOrderAsCompletedAction
{
    public function execute(Order $order): Order
    {
        if ($order->status === OrderStatus::COMPLETED) {
            return $order;
        }

        $order->update([
            'status' => OrderStatus::COMPLETED,
        ]);

        return $order;
    }
}
