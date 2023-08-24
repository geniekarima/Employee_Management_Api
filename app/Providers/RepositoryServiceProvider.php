<?php

namespace App\Providers;
use App\Services\Repositories\AuthRepository;
use App\Services\Interface\AuthInterface;
use App\Services\Repositories\OwnerEmployeeRepository;
use App\Services\Interface\OwnerEmployeeInterface;
use App\Services\Repositories\EmployeeProfileRepository;
use App\Services\Interface\EmployeeProfileInterface;


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
        EmployeeProfileInterface::class => EmployeeProfileRepository::class,
    ];
}
