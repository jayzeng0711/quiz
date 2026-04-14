<?php

namespace App\Providers;

use App\Services\Payment\EcpayGateway;
use App\Services\Payment\MockGateway;
use App\Services\Payment\PaymentGatewayService;
use App\Services\Payment\StripeGateway;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PaymentGatewayService::class, function ($app) {
            return new PaymentGatewayService(
                mock:   $app->make(MockGateway::class),
                stripe: $app->make(StripeGateway::class),
                ecpay:  $app->make(EcpayGateway::class),
            );
        });
    }

    public function boot(): void {}
}
