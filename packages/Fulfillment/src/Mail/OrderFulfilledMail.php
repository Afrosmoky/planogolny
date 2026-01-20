<?php

namespace Planogolny\Fulfillment\Mail;

use Illuminate\Mail\Mailable;
use Planogolny\Orders\Models\Order;

final class OrderFulfilledMail extends Mailable
{
    public function __construct(
        public readonly Order $order,
        public readonly string $reportPath,
        public readonly ?string $invoicePath
    ) {}

    public function build()
    {
        $this->order->load('analysis');
        $mail = $this->subject('Raport i faktura')
            ->view('emails.report', [
                'order' => $this->order,
            ])
            ->attachData($this->reportPath, 'raport.pdf');

        if ($this->invoicePath) {
            $mail->attachData($this->invoicePath, 'faktura.pdf');
        }

        return $mail;
    }
}
