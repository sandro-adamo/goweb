<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompraItem extends Model
{
	protected $table = 'compras_itens';

	public $timestamps = false;

	public function produto() {

		return $this->belongsTo('App\Item', 'item', 'secundario');

	}

}
