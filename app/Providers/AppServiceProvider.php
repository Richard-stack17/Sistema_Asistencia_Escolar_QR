<?php

namespace App\Providers;

use App\Models\Estudiante;
use App\Observers\EstudianteObserver;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\URL;
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
        App::setLocale('es');

        Estudiante::observe(EstudianteObserver::class);

        if (app()->environment('production')) {
            Artisan::call('storage:link');
        }
        if (env('APP_ENV') === 'production') {
        URL::forceScheme('https');
    }
    }
}
