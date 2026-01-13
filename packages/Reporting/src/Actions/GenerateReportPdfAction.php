<?php

namespace Planogolny\Reporting\Actions;

use Planogolny\Orders\Models\Order;
use Planogolny\Reporting\DTO\ReportPdfDTO;
use Planogolny\Reporting\Services\PdfRenderer;

final class GenerateReportPdfAction
{
    public function __construct(
        protected PdfRenderer $renderer
    ) {}

    public function execute(Order $order): ReportPdfDTO
    {
        $html = view('reports.placeholder', [
            'order' => $order,
        ])->render();

        return new ReportPdfDTO(
            filename: 'raport-planogolny-'.$order->id.'.pdf',
            pdfBinary: $this->renderer->render($html)
        );
    }
}
