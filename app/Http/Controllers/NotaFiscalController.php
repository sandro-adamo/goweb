<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class NotaFiscalController extends Controller
{
    	
	public function listaNotasFiscais(Request $request) {
		ini_set('display_errors',1);

		$representantes = \Session::get('representantes');

		$dt_inicio = date('Y').'-'.date('m').'-01';
		$dt_fim = date('Y').'-'.date('m').'-31';
		$sql = '';

		if ($request->inicio and $request->fim) {
			$sql .= " and (dt_emissao >= '$request->inicio' and dt_emissao <= '$request->fim') ";
		}

		if ($request->busca) {
			$sql .= " and (addressbook.id = '$request->busca' or addressbook.razao like '$request->busca%' or addressbook.grupo like '$request->busca%' or addressbook.subgrupo like '$request->busca%' ) ";
		}

		if ($sql == '') { 
			$notas = \DB::select("select nf_legal, dt_emissao, id_cliente, razao, sum(total) as valor, (select pedidos_jde.ped_original from pedidos_jde where pedidos_jde.pedido = notas_jde.ped_original limit 1) as pedido, subgrupo
	from notas_jde
	left join addressbook on notas_jde.id_cliente = addressbook.id
	left join itens on id_item = itens.id
	where id_rep in ($representantes) and codtipoitem = '006' and prox_status in (610,617,620,999) and dt_emissao between '$dt_inicio' and '$dt_fim' 
	group by nf_legal, dt_emissao, id_cliente, razao, ped_original
	order by dt_emissao desc");

		} else {

			$notas = \DB::select("select nf_legal, dt_emissao, id_cliente, razao, sum(total) as valor, (select pedidos_jde.ped_original from pedidos_jde where pedidos_jde.pedido = notas_jde.ped_original limit 1) as pedido, subgrupo
	from notas_jde
	left join addressbook on notas_jde.id_cliente = addressbook.id
	left join itens on id_item = itens.id
	where id_rep in ($representantes) and codtipoitem = '006' and prox_status in (610,617,620,999) $sql
	group by nf_legal, dt_emissao, id_cliente, razao, ped_original");

		}
		return view('notas.lista')->with('notas', $notas);

	}


    public function detalhesNotaFiscal($numero) {

    	$capa = \DB::select("select notas_jde.*, cli.razao, cli.grupo, cli.subgrupo, transp.fantasia as transportadora, (select pedidos_jde.ped_original from pedidos_jde where pedidos_jde.pedido = notas_jde.ped_original limit 1) as pedido 
    						from notas_jde 
    						left join addressbook cli on id_cliente = cli.id
    						left join addressbook transp on id_transportadora = transp.id
    						where nf_legal = '$numero' limit 1");
    	$comissoes = array();
    	$itens = \DB::select("select *, 0 as status, (select pedidos_jde.ped_original from pedidos_jde where pedidos_jde.pedido = notas_jde.ped_original limit 1) as pedido from notas_jde where nf_legal = '$numero' order by linha");

    	$titulos = \DB::select("select * from titulos where documento = '$numero' order by parcela");

    	$nf_legal = substr($numero,2,7);

    	$transporte = \DB::select("select * from rastreamentos where nota_fiscal = '$nf_legal' ");
    	return view('notas.detalhes')
				    			->with('capa', $capa)
				    			->with('itens', $itens)
				    			->with('titulos', $titulos)
				    			->with('rastreio', $transporte)
				    			->with('comissoes', $comissoes);

    }

}
