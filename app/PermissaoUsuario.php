<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermissaoUsuario extends Model
{

 
	protected $table = 'permissoes';

	public static function verificaPermissao($id_usuario, $campo, $valor = '') {

		$permissoes = self::where('tabela', 'usuarios')->where('id_tabela', $id_usuario)->where('chave', $campo)->first();

		if ($permissoes) {

			$valores = explode(',', $permissoes->valor);

			if (in_array($valor, $valores)) {
				return true;
			} else {
				return false;
			}

		} else {

			$usuario = \App\Usuario::find($id_usuario);


			if ($usuario) {
				$permissoes = self::where('tabela', 'perfis')->where('id_tabela', $usuario->id_perfil)->where('chave', $campo)->first();
				if ($permissoes) {

					$valores = explode(',', $permissoes->valor);

					if (in_array($valor, $valores)) {
						return true;
					} else {
						return false;
					}

				}
			}
		}

		return false;
	}



	public static function getPermissao($id_usuario, $campo) {

		$permissoes = self::where('tabela', 'usuarios')->where('id_tabela', $id_usuario)->where('chave', $campo)->first(['valor']);

		if ($permissoes) {
			return $permissoes;
		} else {
			return false;
		}

	}


	public static function setPermissao($id_usuario, $campo, $valor) {

		$permissao = self::where('tabela', 'usuarios')->where('id_tabela', $id_usuario)->where('chave', $campo)->first();

		if ($permissao) {
			$permissao->valor = $valor;
			$permissao->save();
		} else {
			$permissao = new PermissaoUsuario();
			$permissao->tabela = 'usuarios';
			$permissao->id_tabela = $id_usuario;
			$permissao->chave = $campo;
			$permissao->valor = $valor;
			$permissao->save();
		}

		return true;

	}

}
