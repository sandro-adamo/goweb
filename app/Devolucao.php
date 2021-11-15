<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Devolucao extends Model
{
	
	protected $connection = 'goweb';    
	protected $table = 'devolucoes';    




	public static function novaDevolucao($id_usuario, $id_cliente, $tipo, $origem, $situacao, $solicitante, $volumes, $email) {


		$devolucao = new Devolucao();
		$devolucao->id_usuario = $id_usuario;
		$devolucao->id_cliente = $id_cliente;
		$devolucao->tipo = $tipo;
		$devolucao->origem = $origem;
		$devolucao->situacao = $situacao;
		$devolucao->solicitante = $solicitante;
		$devolucao->volumes = $volumes;
		$devolucao->email = $email;
		$devolucao->save();


		return $devolucao->id;

	}



}
