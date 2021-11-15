<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;

class OrcamentosController extends Controller
{
    

public function listaOrcamentos() {

	$orcamentos = \DB::select("
	select fim2.*, qtde-atende nao_atende from (
		select modelo, id_item, item, qtde, saldo, case when saldo > qtde then qtde else saldo end as atende from (
			select *, 
			case when ( select sum(saldos.disponivel) 
				from saldos where saldos.curto = fim.id_item) < 0 then 0 else (select sum(saldos.disponivel) from saldos where saldos.curto = fim.id_item) end as saldo 
				from ( select modelo, id_item, item, sum(qtde) qtde 
				from vendas_jde vds
				left join itens on itens.id = vds.id_item
				where prox_status = '515' -- and item = 'BG1593 02A               '
				group by modelo, id_item, item
			) as fim
		) as fim1
	) as fim2 
	order by nao_atende desc limit 10
	");

	
	return view('produtos.orcamentos.lista')->with('orcamentos', $orcamentos);
	
	}
	
	
	public function listaItens() {

	$itens = \DB::select("
	select modelo, curto, itens.secundario item, sum(saldos.disponivel+saldos.em_beneficiamento) saldo
	from saldos  left join itens on itens.id = saldos.curto 
	where modelo = 'bg1593'
	group by curto, modelo, itens.secundario
	");

	
	return view('produtos.orcamentos.orc_item')->with('itens', $itens);
	
	}
    
}
