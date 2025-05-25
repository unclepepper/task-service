<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\UserRepository\UserRepositoryInterface::class,
            \App\Repositories\UserRepository\UserRepository::class,
        );

        $this->app->bind(
            \App\Service\User\UserValidationRulesServiceInterface::class,
            \App\Service\User\UserValidationRulesService::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
