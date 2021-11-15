<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CicloColecao extends Model
{

	protected $table = 'ciclos';
	public $timestamps = false;

	public static function verificaCiclo($modelo, $colecao) {

		$ciclo = Self::where('modelo', $modelo)->where('colecao', $colecao)->count();

		if ($ciclo > 0) {
			return true;
		} 

		return false;


	}

    
}
