<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{

	protected $table = 'pedidos_jde';

	public function notafiscal() {

		return $this->belongsTo('App\NotaFiscal','pedido','ped_original');

	}

}
