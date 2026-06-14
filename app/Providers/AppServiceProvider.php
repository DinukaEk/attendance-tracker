<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.tailwind-dark');
        Paginator::defaultSimpleView('vendor.pagination.tailwind-dark');
    }
}