<?php

namespace App\Providers;

use App\Domain\Repository\TransactionRepositoryInterface;
use App\Domain\Repository\UserBalanceRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Infrastructure\Repository\EloquentTransactionRepository;
use App\Infrastructure\Repository\EloquentUserBalanceRepository;
use App\Infrastructure\Repository\EloquentUserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(UserBalanceRepositoryInterface::class, EloquentUserBalanceRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class, EloquentTransactionRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
