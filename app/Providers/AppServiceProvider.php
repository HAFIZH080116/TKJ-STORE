<?php

namespace App\Providers;

use App\Services\CartService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::useBootstrapFive();

        View::composer('partials.navbar-user', function ($view) {
            $view->with('cartQty', app(CartService::class)->totalQuantity());
        });

        View::composer('partials.sidebar-admin', function ($view) {
            $view->with('pendingOrdersCount', \App\Models\Transaksi::where('status', 'pending')->count());
        });
    }
}
