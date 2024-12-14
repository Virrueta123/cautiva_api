<?php

namespace App\Providers;

use Greenter\See;
use Greenter\Ws\Services\SunatEndpoints;
use Illuminate\Support\ServiceProvider;

class GreenterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(See::class, function ($app) {
            $see = new See();
            $see->setCertificate(file_get_contents(config('greenter.cert')));
            $see->setService(SunatEndpoints::FE_BETA);
            $see->setClaveSOL(config('greenter.ruc_sol'), config('greenter.username_sol'), config('greenter.password_sol'));
            return $see;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
