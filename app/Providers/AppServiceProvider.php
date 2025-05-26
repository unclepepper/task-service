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
            \App\Repositories\TaskRepository\TaskRepositoryInterface::class,
            \App\Repositories\TaskRepository\TaskRepository::class,
        );

        $this->app->bind(
            \App\Service\User\UserValidationRulesServiceInterface::class,
            \App\Service\User\UserValidationRulesService::class,
        );

        $this->app->bind(
            \App\Service\Task\TaskValidationRulesServiceInterface::class,
            \App\Service\Task\TaskValidationRulesService::class,
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
