<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        setlocale(LC_TIME, 'uz_UZ.UTF-8');
        Carbon::setLocale('uz');
        Paginator::useBootstrapFive();
    }
}
