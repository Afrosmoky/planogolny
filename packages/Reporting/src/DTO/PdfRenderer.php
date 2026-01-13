<?php

namespace Planogolny\Reporting\DTO;

use Barryvdh\DomPDF\Facade\Pdf;

final class PdfRenderer
{
    /**
     * Renderuje HTML do PDF (binary)
     */
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
