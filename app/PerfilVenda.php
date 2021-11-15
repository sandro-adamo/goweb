<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;

class PerfilVenda extends Model
{
    
    protected $connection = 'goweb';
    protected $table = 'perfil_vendas';

    public function cliente() {

    	return $this->belongsTo('App\AddressBook', 'id_cliente', 'id');

    }

}
