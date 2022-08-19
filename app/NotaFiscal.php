<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotaFiscal extends Model
{

	protected $table = 'notas_jde';


	public function rastreamento() {

		return $this->belongsTo('App\Rastreamento',  'nf_legal',  'nf_legal');

	}

}
