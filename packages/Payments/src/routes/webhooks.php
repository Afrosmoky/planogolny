<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Planogolny\Payments\Actions\HandleTpayWebhookAction;
use Planogolny\Payments\DTO\TpayWebhookDTO;

Route::post('api/webhooks/tpay', function (Request $request) {
    $result = app(HandleTpayWebhookAction::class)->execute(
        TpayWebhookDTO::fromRequest($request)
    );

    if ($result === true) {
        return response('TRUE', 200)
            ->header('Content-Type', 'text/plain');
    }

    return response('FALSE', 200)
        ->header('Content-Type', 'text/plain');
})->name('tpay.webhook');
//Route::post('api/webhooks/tpay', function () {
//    return response()->json(['webhook' => 'ok']);
//});
