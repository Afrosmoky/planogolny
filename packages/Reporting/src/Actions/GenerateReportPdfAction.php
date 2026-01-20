<?php

namespace Planogolny\Reporting\Actions;

use Planogolny\Reporting\Actions\BuildReportDataAction;
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
        $order->load('analysis');

        $data = app(BuildReportDataAction::class)->execute($order->analysis()->getResults());

        $html = view('reports.placeholder', [
            'order' => $order,
            ...$data,
        ])->render();

        return new ReportPdfDTO(
            filename: 'raport-'.$order->report_number.'.pdf',
            pdfBinary: $this->renderer->render($html)
        );
    }
}
