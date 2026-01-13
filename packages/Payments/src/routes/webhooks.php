<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Planogolny\Payments\Actions\HandleTpayWebhookAction;
use Planogolny\Payments\DTO\TpayWebhookDTO;

Route::post('api/webhooks/tpay', function (Request $request) {
    app(HandleTpayWebhookAction::class)->execute(
        TpayWebhookDTO::fromRequest($request)
    );

    return response()->json(['ok' => true]);
})->name('tpay.webhook');
//Route::post('api/webhooks/tpay', function () {
//    return response()->json(['webhook' => 'ok']);
//});
