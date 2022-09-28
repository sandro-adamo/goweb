<?php

namespace App\Providers;

use App\Observers\UsuarioObserver;
use App\Usuario;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        // $usuario = \App\Usuario::retornaUser();

        Usuario::observe(UsuarioObserver::class);
    
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
