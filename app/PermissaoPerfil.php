<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermissaoPerfil extends Model
{

 
	protected $table = 'permissoes';

	public static function verificaPermissao($id_perfil, $campo, $valor = '') {

		$permissoes = self::where('tabela', 'perfis')->where('id_tabela', $id_perfil)->where('chave', $campo)->first();

		if ($permissoes) {

			$valores = explode(',', $permissoes->valor);

			if (in_array($valor, $valores)) {
				return true;
			} else {
				return false;
			}

		}

	}



	public static function getPermissao($id_perfil, $campo) {

		$permissoes = self::where('tabela', 'perfis')->where('id_tabela', $id_perfil)->where('chave', $campo)->first(['valor']);

		if ($permissoes) {
			return $permissoes;
		} else {
			return false;
		}

	}


	public static function setPermissao($id_perfil, $campo, $valor) {

		$permissao = self::where('tabela', 'perfis')->where('id_tabela', $id_perfil)->where('chave', $campo)->first();

		if ($permissao) {
			$permissao->valor = $valor;
			$permissao->save();
		} else {
			$permissao = new PermissaoUsuario();
			$permissao->tabela = 'perfis';
			$permissao->id_tabela = $id_perfil;
			$permissao->chave = $campo;
			$permissao->valor = $valor;
			$permissao->save();
		}

		return true;

	}

    //
}
