<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemHistorico extends Model
{
    protected $table = 'historicos';


    public static function gravaHistorico($id_item, $categoria, $texto, $data = '1900-01-01', $numeropedido = '') {

        
    	$historico = new ItemHistorico();
    	$historico->id_usuario = \Auth::id();
    	$historico->id_item = $id_item;
    	$historico->categoria = $categoria;
    	$historico->historico = $texto;
        $historico->nova_data_producao = $data;
        $historico->pedido_fabrica = $numeropedido;
    	$historico->save();

    	return true;

    }


    public function usuario() {

        return $this->belongsTo('App\Usuario', 'id_usuario');

    }
}
