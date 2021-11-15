<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permissao extends Model
{

 
	protected $table = 'permissoes';

	public static function verificaPermissaoPerfil($id_perfil, $campo, $valor = '') {

		$permissoes = self::where('tabela', 'perfis')->where('id_tabela', $id_perfil)->where('chave', $campo)->first();

		if ($permissoes) {

			$valores = explode(',', $permissoes->valor);

			if (in_array($valor, $valores)) {
				return true;
			} else {
				return false;
			}

		}

		return false;
	}



	public static function getPermissao($id, $campo) {

		$usuario = \App\Usuario::find($id);

		$permissoes_usuario = self::where('tabela', 'usuarios')->where('id_tabela', $id)->where('chave', $campo)->first(['valor']);

		if ($permissoes_usuario) {

			$permissoes = explode(',',$permissoes_usuario->valor);

			return $permissoes;

		} else {

			$permissoes_perfil = self::where('tabela', 'perfis')->where('id_tabela', $usuario->id_perfil)->where('chave', $campo)->first(['valor']);

			if ($permissoes_perfil) {
				$permissoes = explode(',',$permissoes_perfil->valor);
				return $permissoes;
			}
			
			return false;

		}

	}


	public static function setPermissao($tabela, $id, $campo, $valor) {

		$permissao = self::where('tabela', $tabela)->where('id_tabela', $id_usuario)->where('chave', $campo)->first();

		if ($permissao) {
			$permissao->valor = $valor;
			$permissao->save();
		} else {
			$permissao = new Permissao();
			$permissao->tabela = $tabela;
			$permissao->id_tabela = $id;
			$permissao->chave = $campo;
			$permissao->valor = $valor;
			$permissao->save();
		}

		return true;

	}

}
