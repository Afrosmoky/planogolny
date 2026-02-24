<?php

declare(strict_types=1);

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
                'analysis_id' => $dto->analysisId,
                'report_number' => $this->generateReportNumber(),
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

    private function generateReportNumber(): string
    {
        $lastNumber = Order::lockForUpdate()
            ->whereNotNull('report_number')
            ->orderByDesc('id')
            ->value('report_number');

        $next = 1;

        if ($lastNumber) {
            $next = (int) str_replace('PLAN-2026-', '', $lastNumber) + 1;
        }

        return 'PLAN-2026-' . str_pad($next, 6, '0', STR_PAD_LEFT);
    }
}
