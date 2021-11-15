<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsuarioPermissao extends Model
{
 
	protected $table = 'permissoes';

	public static function verificaPermissao($tabela, $id_tabela, $campo, $valor = '') {

		$permissoes = self::where('tabela', $tabela)->where('id_tabela', $id_tabela)->where('chave', $campo)->where('valor', $valor)->count();

		if ($permissoes > 0) {
			return true;
		} else {
			return false;
		}

	}



	public static function getPermissao($tabela, $id_tabela, $campo) {

		$permissoes = self::where('tabela', $tabela)->where('id_tabela', $id_tabela)->where('chave', $campo)->first(['valor']);

		if ($permissoes) {
			return $permissoes;
		} else {
			return false;
		}

	}
}
