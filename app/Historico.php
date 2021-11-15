<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Historico extends Model
{
    protected $table = 'historicos';


    public function usuario() {

        return $this->belongsTo('App\Usuario', 'id_usuario');

    }
}
