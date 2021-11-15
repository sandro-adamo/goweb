<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Troca extends Model
{


	public function pedidos() {

		return $this->hasMany('App\TrocaJDE', 'id_troca_item', 'id_troca_item')->whereNotIn('ult_status', [ '984','980']);

	}

}
