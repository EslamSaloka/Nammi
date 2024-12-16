<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
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
        //
        include_once(app_path("Helper/Main.php"));
        if(request()->is("*api*")) {
            App::setLocale(request()->header("accept-language","en"));
        }
    }
}
