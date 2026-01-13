<?php

namespace Planogolny\Payments;

use Illuminate\Support\ServiceProvider;
use Planogolny\Payments\Services\TpayClient;
use Illuminate\Support\Facades\Route;

final class PaymentsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(TpayClient::class, function () {
            return new TpayClient(
                baseUrl: config('tpay.base_url'),
                clientId: config('tpay.client_id'),
                clientSecret: config('tpay.client_secret'),
                env: config('tpay.env'),
            );
        });
    }

    public function boot(): void
    {
        logger('PaymentServiceProvider booted. Directory: '.dirname(__DIR__).'/src/routes/webhooks.php');
        Route::middleware('api')
            ->group(dirname(__DIR__).'/src/routes/webhooks.php');
    }
}
