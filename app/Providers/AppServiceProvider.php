<?php

namespace App\Providers;

use App\Contracts\ResolvesContainerStateContract;
use App\Contracts\StoresEventsContract;
use App\Contracts\UpdatesStaleContainersContract;
use App\Services\ResolveContainerStateService;
use App\Services\StoreEventService;
use App\Services\UpdateStaleContainerStatesService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ResolvesContainerStateContract::class, ResolveContainerStateService::class);
        $this->app->bind(StoresEventsContract::class, StoreEventService::class);
        $this->app->bind(UpdatesStaleContainersContract::class, UpdateStaleContainerStatesService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
