<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Console\Commands\VaccinationState;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                VaccinationState::class,
            ]);
        }
    }
}
