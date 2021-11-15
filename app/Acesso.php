<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Acesso extends Model
{


	public static function verificaAcessoPerfil($id_perfil, $rota) {

		$permissoes = self::where('tabela', 'perfis')->where('id_tabela', $id_perfil)->where('rota', $rota)->first();

		if ($permissoes) {
			return true;
		} else {
			return false;
		}

		return false;
	}


	public static function verificaAcesso($rota) {

		$usuario = \Auth::user();


		if ($usuario->admin == 1) {

			return true;

		} else {

			$acesso_usuario = Self::where('tabela', 'usuarios')->where('id_tabela', $usuario->id)->where('rota', $rota)->first();

			if ($acesso_usuario) {
	
				return true;
	
			} else {

				$acesso_perfil = Self::where('tabela', 'perfis')->where('id_tabela', $usuario->id_perfil)->where('rota', $rota)->first();

				if ($acesso_perfil) {

					return true;

				}
	
				return false;

			}


		}



	}




	public static function setAcesso($tabela, $id_usuario, $rota) {

		$permissao = self::where('tabela', $tabela)->where('id_tabela', $id_usuario)->where('rota', $rota)->first();

		if (!$permissao) {
			$permissao = new Acesso();
			$permissao->tabela = $tabela;
			$permissao->id_tabela = $id_usuario;
			$permissao->rota = $rota;
			$permissao->save();
		}

		return true;

	}


}
