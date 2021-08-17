<?php

namespace VCComponent\Laravel\Review\Providers;

use Illuminate\Support\ServiceProvider;
use VCComponent\Laravel\Review\Repositories\ReviewInterface;
use VCComponent\Laravel\Review\Repositories\ReviewReponsitory;

class ReviewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
        $this->publishes([
            __DIR__ . '/../../config/review.php' => config_path('review.php'),
        ], 'config');
    }

    /**
     * Register any package services
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ReviewInterface::class, ReviewReponsitory::class);
    }
}
