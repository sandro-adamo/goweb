<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;

class Movimentacao extends Model
{

    protected $table = 'movimentacoes';


    public function produto() {

    	return $this->belongsTo('App\Produto', 'id_produto');

    }
  


    public function usuario() {

    	return $this->belongsTo('App\Usuario', 'id_usuario');

    }
  
}
