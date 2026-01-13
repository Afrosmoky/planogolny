<?php

namespace Planogolny\Reporting\Services;

use Barryvdh\DomPDF\Facade\Pdf;

class PdfRenderer
{
    public function render(string $html): string
    {
        $pdf = Pdf::loadHTML($html)
            ->setPaper('a4')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true)
            ->setOption('defaultFont', 'DejaVu Sans');

        return $pdf->output();
    }
}
