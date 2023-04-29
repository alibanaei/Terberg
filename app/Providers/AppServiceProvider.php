<?php

namespace App\Providers;

use App\Actions\FactoryActions\OptionFactory;
use App\Actions\FactoryActions\OrderFactory;
use App\Actions\FactoryActions\ProductFactory;
use App\Actions\FactoryActions\ResourceFactory;
use App\Actions\FactoryActions\ServiceFactory;
use App\Http\Controllers\API\OptionController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ServiceController;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->when(OrderController::class)
            ->needs(ResourceFactory::class)
            ->give(OrderFactory::class);

        $this->app->when(ProductController::class)
            ->needs(ResourceFactory::class)
            ->give(ProductFactory::class);

        $this->app->when(ServiceController::class)
            ->needs(ResourceFactory::class)
            ->give(ServiceFactory::class);

        $this->app->when(OptionController::class)
            ->needs(ResourceFactory::class)
            ->give(OptionFactory::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
