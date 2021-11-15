<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;

class Catalogo extends Model
{
    

	public function usuario() {
		return $this->belongsTo('App\Usuario', 'id_usuario');
	}

	public static function verificaModeloHabilitado($modelo) {

		
			$sessao = Session::get('novocatalogo');

			$catalogo = Self::where('codigo', $sessao["codigo"])->where('modelo', $modelo)->count();

			if ($catalogo > 0) {
				return true;
			} else {
				return false;
			}

	}



	public static function verificaItemHabilitado($item) {

		
			$sessao = Session::get('novocatalogo');

			$catalogo = Self::where('codigo', $sessao["codigo"])->where('item', $item)->count();

			if ($catalogo > 0) {
				return true;
			} else {
				return false;
			}

	}
}
