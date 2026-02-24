<?php

declare(strict_types=1);

namespace Planogolny\Fulfillment\Mail;

use Illuminate\Mail\Mailable;
use Planogolny\Orders\Models\Order;
use Illuminate\Support\Facades\Storage;

final class OrderFulfilledMail extends Mailable
{
    public function __construct(
        public readonly Order $order,
        public readonly string $reportPath,
        public readonly ?string $invoicePath
    ) {}

    public function build(): self
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

        foreach (config('attachments.planning_documents') as $doc) {
            if (Storage::exists($doc['path'])) {
                $mail->attach(
                    Storage::path($doc['path']),
                    ['as' => $doc['name']]
                );
            }
        }

        return $mail;
    }
}
