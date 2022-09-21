<?php

namespace App\Observers;

use App\Usuario;
use Illuminate\Support\Facades\Log;

class UsuarioObserver
{
    /**
     * Handle the usuario "created" event.
     *
     * @param  \App\Usuario  $usuario
     * @return void
     */
    public function created(Usuario $usuario)
    {
        //
    }

    /**
     * Handle the usuario "updated" event.
     *
     * @param  \App\Usuario  $usuario
     * @return void
     */
    public function updated(Usuario $usuario)
    {
        //
    }

    /**
     * Handle the usuario "deleted" event.
     *
     * @param  \App\Usuario  $usuario
     * @return void
     */
    public function deleted(Usuario $usuario)
    {
        //
    }

    /**
     * Handle the usuario "restored" event.
     *
     * @param  \App\Usuario  $usuario
     * @return void
     */
    public function restored(Usuario $usuario)
    {
        //
    }

    /**
     * Handle the usuario "force deleted" event.
     *
     * @param  \App\Usuario  $usuario
     * @return void
     */
    public function forceDeleted(Usuario $usuario)
    {
        //
    }

    public function updating(Usuario $usuario)
    {
        echo 'ORIGINAL: ' . $usuario->getOriginal('status') . PHP_EOL;
        echo 'NOVO: ' . $usuario->status . PHP_EOL;
        if($usuario->getOriginal('status') != $usuario->status){
        Log::channel('acessos')->info("Coluna 'status' do usuario {$usuario->id} foi modificada de {$usuario->getOriginal('status')} para {$usuario->status} .");
      }
    }

    public function saving(Usuario $usuario)
    {
        echo 'ORIGINAL: ' . $usuario->getOriginal('status') . PHP_EOL;
        echo 'NOVO: ' . $usuario->status . PHP_EOL;
        if($usuario->getOriginal('status') != $usuario->status){
            Log::channel('acessos')->info("Coluna 'status' do usuario {$usuario->id} foi modificada de {$usuario->getOriginal('status')} para {$usuario->status} .");
        }
    }

}
