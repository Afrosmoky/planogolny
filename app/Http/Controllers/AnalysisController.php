<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\AnalysisStatus;
use App\Models\Analysis;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Planogolny\Analysis\Jobs\RunAnalysisJob;

final class AnalysisController
{
    public function form(): \Inertia\Response
    {
        return Inertia::render('Landing/ParcelForm', []);
    }

    public function start(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'captcha_token' => ['required', 'string'],
            'lat' => ['required', 'numeric'],
            'lng' => ['required', 'numeric'],
            'address' => ['required', 'string'],
        ]);

        $response = Http::asForm()->post(
            'https://hcaptcha.com/siteverify',
            [
                'secret' => config('services.hcaptcha.secret'),
                'response' => $request->captcha_token,
                'remoteip' => $request->ip(),
            ]
        );

        if (!($response->json('success') ?? false)) {
            abort(422, 'Captcha verification failed');
        }

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

    public function processing(Analysis $analysis): \Inertia\Response|\Inertia\ResponseFactory
    {
        return inertia('Analysis/Processing', [
            'analysisId' => $analysis->id,
            'status' => $analysis->status,
        ]);
    }

}
