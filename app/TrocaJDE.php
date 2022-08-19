<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrocaJDE extends Model
{

	protected $table = 'trocas_jde';
	public $timestamps = false;

	public function notafiscal() {


		return $this->belongsTo('App\NotaFiscal', 'pedido', 'ped_original');

	}


}
