<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Analysis;
use Illuminate\Http\JsonResponse;

class AnalysisStatusController
{
    public function __invoke(Analysis $analysis): JsonResponse
    {
        abort_unless($analysis->session_id === session()->getId(), 403);

        return response()->json([
            'id' => $analysis->id,
            'status' => $analysis->status,
            'ready_for_report' => $analysis->result
                ? ($analysis->result->meta['ready_for_report'] ?? false)
                : false,
        ]);
    }
}
