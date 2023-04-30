<?php

namespace App\Providers;

use App\Services\FactoryService\Implementations\OptionFactoryService;
use App\Services\FactoryService\Implementations\OrderFactoryService;
use App\Services\FactoryService\Implementations\ProductFactoryService;
use App\Services\FactoryService\Implementations\ServiceFactoryService;
use App\Services\FactoryService\Interfaces\ResourceFactoryService;
use App\Services\RepositoryService\Implementations\OptionRepository;
use App\Services\RepositoryService\Implementations\OrderRepository;
use App\Services\RepositoryService\Implementations\ProductRepository;
use App\Services\RepositoryService\Implementations\ServiceRepository;
use App\Services\RepositoryService\Interfaces\RepositoryService;
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
        // binding Resource Factories for Repository Classes

        $this->app->when(OrderRepository::class)
            ->needs(ResourceFactoryService::class)
            ->give(OrderFactoryService::class);

        $this->app->when(ProductRepository::class)
            ->needs(ResourceFactoryService::class)
            ->give(ProductFactoryService::class);

        $this->app->when(ServiceRepository::class)
            ->needs(ResourceFactoryService::class)
            ->give(ServiceFactoryService::class);

        $this->app->when(OptionRepository::class)
            ->needs(ResourceFactoryService::class)
            ->give(OptionFactoryService::class);


        // binding Repositories for Controller Classes

        $this->app->when(OrderController::class)
            ->needs(RepositoryService::class)
            ->give(OrderRepository::class);

        $this->app->when(ProductController::class)
            ->needs(RepositoryService::class)
            ->give(ProductRepository::class);

        $this->app->when(ServiceController::class)
            ->needs(RepositoryService::class)
            ->give(ServiceRepository::class);

        $this->app->when(OptionController::class)
            ->needs(RepositoryService::class)
            ->give(OptionRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
