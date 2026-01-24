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
use Planogolny\Reporting\Actions\BuildReportDataAction;

final class PaymentController
{
    public function start(
        int $analysisId,
        CreateOrderAction $createOrder,
        CreateTpayTransactionAction $createTpayTransaction,
        Request $request
    ): \Illuminate\Http\JsonResponse
    {

//        dd([
//            'analysisId' => $analysisId,
//            'all' => $request->all(),
//            'invoice_type' => $request->input('invoice_type'),
//            'invoice_data' => $request->input('invoice_data'),
//        ]);

        $order = $createOrder->execute(
            new OrderDTO(
                analysisId: $analysisId,
                email: $request->input('invoice_data.email'),
                addressText: "Analysis #{$analysisId}",
                amount: 49.99,
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
        return response()->json([
            'redirect_url' => $transaction['redirectUrl'],
        ]);
        //return redirect()->away($transaction['redirectUrl']);
        //return redirect(route('payment.success', $order->id));
    }

    public function success(Order $order): \Inertia\Response|\Inertia\ResponseFactory
    {
        if (! $order->isPaid()) {
            return inertia('Payment/Processing', [
                'orderId' => $order->id,
            ]);
        }

        $order->load('analysis');

        //abort_unless($order->analysis->session_id === session()->getId(), 403);

        $analysis = $order->analysis;
        $result = $analysis?->result;

        $data = app(BuildReportDataAction::class)->execute($result);

        return inertia('Payment/Success', [
            'orderId' => $order->id,
            'status' => $order->status,
            'report' => [
                'order' => [
                    'reportNumber' => $order->report_number,
                    'address' => $order->analysis->address,
                    'lat' => $order->analysis->lat,
                    'lng' => $order->analysis->lng,
                ],
                'surroundings' => $data['surroundings']->toArray(),
                'legalConstraints' => $data['legalConstraints']->toArray(),
                'finalSummary' => $data['finalSummary']->toArray(),
                'landUseProbabilities' => $data['landUseProbabilities'],
            ],
        ]);
    }

    public function checkout(Analysis $analysis)
    {
        return inertia('Payment/Checkout', [
            'analysisId' => $analysis->id
        ]);
    }
}
