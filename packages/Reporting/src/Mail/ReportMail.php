<?php
namespace Planogolny\Reporting\Mail;

use Illuminate\Mail\Mailable;
use Planogolny\Invoicing\DTO\InvoiceDTO;
use Planogolny\Reporting\DTO\ReportPdfDTO;

final class ReportMail extends Mailable
{
    public function __construct(
        protected ReportPdfDTO $report,
        protected ?InvoiceDTO $invoice = null
    ) {}

    public function build(): self
    {
        $mail = $this
            ->subject('Twój raport planistyczny – PlanOgólny.info')
            ->view('emails.report');

        $mail->attachData(
            $this->report->pdfBinary,
            $this->report->filename,
            ['mime' => 'application/pdf']
        );

        if ($this->invoice) {
            $mail->attachData(
                $this->invoice->pdfBinary,
                'faktura-'.$this->invoice->invoiceNumber.'.pdf',
                ['mime' => 'application/pdf']
            );
        }

        return $mail;
    }
}
