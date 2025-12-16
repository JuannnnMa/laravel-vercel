<?php

namespace App\Providers;

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
        // Share academic years with navbar
        view()->composer('layouts.componentes.navbar', function ($view) {
            $view->with('globalAnios', \App\Models\AnioAcademico::orderBy('fecha_inicio', 'desc')->get());
            $view->with('globalAnioActual', anio_actual());
        });
    }
}
