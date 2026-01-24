<?php

use App\Http\Controllers\AnalysisStatusController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\PaymentController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Planogolny\Orders\Models\Order;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;

//Route::get('/', function () {
//    return Inertia::render('Welcome', [
//        'canLogin' => Route::has('login'),
//        'canRegister' => Route::has('register'),
//        'laravelVersion' => Application::VERSION,
//        'phpVersion' => PHP_VERSION,
//    ]);
//});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

RateLimiter::for('analysis-start', function ($request) {
    return Limit::perMinutes(10, 5)->by(
        $request->ip()
    );
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/', [AnalysisController::class, 'form']);
Route::post('/analysis/start', [AnalysisController::class, 'start'])
    ->middleware('throttle:analysis-start');

Route::get('/analysis/{analysis}', [AnalysisController::class, 'processing'])->name('analysis.processing');
Route::get('/analysis/{analysis}/result', [AnalysisController::class, 'result'])->name('analysis.result');

Route::get('/payment/{analysis}', [PaymentController::class, 'checkout'])->name('payment.checkout');

Route::post('/payment/{analysisId}/payment/start', [PaymentController::class, 'start'])->name('payment.start');

Route::get('/payment/{order}/success', [PaymentController::class, 'success'])->name('payment.success');

Route::get('/analysis/{analysis}/status', AnalysisStatusController::class)
    ->name('analysis.status');

Route::get('/payment/{order}/status', function (Order $order) {
    return response()->json([
        'status' => $order->status, // created | paid | completed
    ]);
})->name('payment.status');

Route::get('/reports/{order}', function ($orderId) {
    return Storage::download("reports/report-{$orderId}.pdf");
})->name('report.download');

require __DIR__.'/auth.php';
