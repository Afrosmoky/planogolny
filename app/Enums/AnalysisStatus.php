<?php
namespace App\Enums;

enum AnalysisStatus: string
{
    case PROCESSING = 'processing';
    case FOUND = 'found';
    case NOT_FOUND = 'not_found';
    case DONE = 'done'; // ← to jest TEN status
}
