<?php

namespace Planogolny\Orders\Actions;

use Planogolny\Orders\DTO\OrderDTO;
use Planogolny\Orders\Enums\OrderStatus;
use Planogolny\Orders\Models\Order;
use Planogolny\Orders\Exceptions\OrderException;
final readonly class CreateOrderAction
{
    public function execute(OrderDTO $dto): Order
    {
        try {
            return Order::create([
                'email' => $dto->email,
                'address_text' => $dto->addressText,
                'amount' => $dto->amount,
                'currency' => $dto->currency,
                'payment_provider' => $dto->paymentProvider,
                'status' => OrderStatus::CREATED,
                'invoice_type' => $dto->invoiceType,
                'invoice_data' => $dto->invoiceData,
            ]);
        } catch (\Throwable $e) {
            throw new OrderException('Failed to create order: ' . $e->getMessage(), 0, $e);
        }
    }
}
