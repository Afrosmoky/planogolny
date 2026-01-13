<?php

namespace Planogolny\Reporting\DTO;
final readonly class ReportPdfDTO
{
    public function __construct(
        public string $filename,
        public string $pdfBinary
    ) {}
}
