<?php
namespace App\Http\Controllers;

use App\Models\Analysis;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Planogolny\Orders\Actions\CreateOrderAction;
use Planogolny\Orders\DTO\OrderDTO;
use Planogolny\Orders\Models\Order;
use Planogolny\Payments\Actions\CreateTpayTransactionAction;
use Planogolny\Payments\DTO\TpayTransactionDTO;

final class PaymentController
{
    public function start(
        int $analysisId,
        CreateOrderAction $createOrder,
        CreateTpayTransactionAction $createTpayTransaction,
        Request $request
    ): RedirectResponse {

//        dd([
//            'analysisId' => $analysisId,
//            'all' => $request->all(),
//            'invoice_type' => $request->input('invoice_type'),
//            'invoice_data' => $request->input('invoice_data'),
//        ]);

        $order = $createOrder->execute(
            new OrderDTO(
                //email: 'placeholder@planogolny.info', // później z formularza
                email: "szulcpiotr@icloud.com",
                addressText: "Analysis #{$analysisId}",
                amount: 100,
                currency: 'PLN',
                paymentProvider: 'TPay',
                invoiceType: $request->input('invoice_type'),
                invoiceData: $request->input('invoice_data'),
            )
        );

        $transaction = $createTpayTransaction->execute(
            new TpayTransactionDTO(
                orderId: (string) $order->id,
                amount: $order->amount,
                email: $order->email,
                returnUrl: route('payment.success', $order->id),
                notifyUrl: route('tpay.webhook'),
                description: 'Raport – Plan Ogólny',
            )
        );

        /**
         * 3️⃣ Redirect do TPay
         */
        return redirect()->away($transaction['redirectUrl']);
        //return redirect('payment.success');
    }

    public function success(Order $order): \Inertia\Response|\Inertia\ResponseFactory
    {
        if (! $order->isPaid()) {
            return inertia('Payment/Processing', [
                'orderId' => $order->id,
            ]);
        }

        return inertia('Payment/Success', [
            'orderId' => $order->id,
            'downloadUrl' => route('report.download', $order->id),
        ]);
    }

    public function checkout(Analysis $analysis)
    {
        return inertia('Payment/Checkout', [
            'analysisId' => $analysis->id
        ]);
    }
}
