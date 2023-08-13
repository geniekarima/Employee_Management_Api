<?php

namespace App\Providers;
use App\Services\Repositories\AuthRepository;
use App\Services\Interface\AuthInterface;
use App\Services\Repositories\OwnerEmployeeRepository;
use App\Services\Interface\OwnerEmployeeInterface;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
    public $bindings = [
        AuthInterface::class => AuthRepository::class,
        OwnerEmployeeInterface::class => OwnerEmployeeRepository::class,
    ];
}
