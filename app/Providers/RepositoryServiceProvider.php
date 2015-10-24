<?php

namespace App\Providers;

use App\Models\InternetSession;
use App\Models\Key;
use App\Repositories\InternetSession\EloquentInternetSessionRepository;
use App\Repositories\InternetSession\InternetSessionRepositoryInterface;
use App\Repositories\Key\EloquentKeyRepository;
use App\Repositories\Key\KeyRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;

        // Key
        $app->bind(KeyRepositoryInterface::class, function ($app) {
            return new EloquentKeyRepository(
                new Key,
                $app->make(InternetSessionRepositoryInterface::class)
            );
        });

        // InternetSession
        $app->bind(InternetSessionRepositoryInterface::class, function ($app) {
            return new EloquentInternetSessionRepository(
                new InternetSession
            );
        });
    }
}
