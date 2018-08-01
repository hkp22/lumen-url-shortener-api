<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Link;
use App\Observers\LinkObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Link::observe(LinkObserver::class);
    }
}
