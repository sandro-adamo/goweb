<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DevolucaoItem extends Model
{
	
	protected $connection = 'goweb';    
	protected $table = 'devolucoes_itens';    



	public static function novaDevolucaoItem($id_devolucao, $status, $id_produto, $produto, $qtde, $unitario, $total, $tabela, $id_tabela, $obs) {

		$dev_item = new \App\DevolucaoItem();
		$dev_item->id_devolucao = $id_devolucao;
		$dev_item->status = $status;
		$dev_item->id_produto = $id_produto;
		$dev_item->produto = $produto;
		$dev_item->qtde = $qtde;
		$dev_item->unitario = $unitario;
		$dev_item->total = $total;
		$dev_item->tabela = $tabela;
		$dev_item->id_tabela = $id_tabela;
		$dev_item->obs = $obs;

		$dev_item->save();


		return $dev_item->id;

	}

}
