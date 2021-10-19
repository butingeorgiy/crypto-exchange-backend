<?php

namespace App\Providers;

use App\Services\AuthenticationService\Client as AuthenticationClient;
use App\Services\AuthenticationService\Drivers\BearerTokenDriver;
use App\Services\EmailConfirmationService\Drivers\EmailConfirmationDriver;
use App\Services\EmailConfirmationService\Drivers\EmailConfirmationDriverInterface;
use App\Services\SmsConfirmationService\Drivers\FakeSmsDriver;
use App\Services\SmsConfirmationService\Drivers\SmsConfirmationDriverInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AuthenticationClient::class, function () {
            return new AuthenticationClient(new BearerTokenDriver);
        });

        $this->app->bind(EmailConfirmationDriverInterface::class, function () {
            return new EmailConfirmationDriver;
        });

        $this->app->bind(SmsConfirmationDriverInterface::class, function () {
            return new FakeSmsDriver;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
