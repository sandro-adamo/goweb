<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PedidoJDE extends Model
{
    protected $table = 'pedidos_jde';

    public function exemplo(){
        return $this->hasMany('App\Exemplo', 'id_pedido', 'pedido');
    }

}
