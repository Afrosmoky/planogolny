<?php
namespace App\Http\Controllers;

use App\Enums\AnalysisStatus;
use App\Models\Analysis;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Planogolny\Analysis\Jobs\RunAnalysisJob;

final class AnalysisController
{
    public function form(): \Inertia\Response
    {
        return Inertia::render('Landing/ParcelForm');
    }

    public function start(Request $request)
    {
        $analysis = Analysis::create([
            'address' => $request->address,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'status' => AnalysisStatus::PROCESSING,
            'session_id' => session()->getId()
        ]);

        RunAnalysisJob::dispatch($analysis->id);

        return redirect()->route('analysis.processing', $analysis);
    }

    public function processing(Analysis $analysis)
    {
//        dd($analysis);
        return inertia('Analysis/Processing', [
            'analysisId' => $analysis->id,
            'status' => $analysis->status,
        ]);
    }

//    public function result(int $analysisId)
//    {
//        return Inertia::render('Analysis/Result', [
//            'analysisId' => $analysisId,
//            'preview' => [
//                'jednorodzinna' => 50,
//                'wielorodzinna' => 20,
//                'uslugowa' => 10,
//                'przemyslowa' => 0,
//                'zielone' => 20,
//            ],
//        ]);
//    }
}
