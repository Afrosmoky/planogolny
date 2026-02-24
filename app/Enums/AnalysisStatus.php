<?php

declare(strict_types=1);

namespace App\Enums;

enum AnalysisStatus: string
{
    case PROCESSING = 'processing';
    case FOUND = 'found';
    case NOT_FOUND = 'not_found';
    case READY = 'ready';
}
