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
        setlocale(LC_TIME, 'uz_UZ');
        Carbon::setLocale('ru');
        Paginator::useBootstrapFive();
    }
}
