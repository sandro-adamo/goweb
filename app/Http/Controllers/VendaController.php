<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\PedidoItem;

class VendaController extends Controller
{
    
	public function listaPedidos_det(Request $request) {

		return view('pedidos.lista_det');

	}

	public function vinculaPedido($data, Request $request) {

		$query = \DB::select("update pedidos_itens set pedido = '$request->pedido_jde' where date(created_at) = '$data' ");

		return redirect('/pedidos');

	}
	
	
	public function listaVendas(Request $request) {

		$id_rep = \Auth::user()->id_addressbook;
		$representante = \Session::get('representantes');

		// $web = \DB::connection('goweb')->select("select dt_emissao as dt_venda, id_pedido as pedido, razao, id_cliente, sum(pedidosweb_itens.total) as valor 
		// 		from pedidosweb
		// 		left join pedidosweb_itens on id_pedido = pedidosweb.id
		// 		left join clientes on id_cliente = clientes.id
		// 		where pedido_jde is null and pedidosweb.status = 'PROCESSANDO'
		// 		group by dt_emissao, id_pedido, razao, id_cliente");

		$sql = '';

		if ($request->inicio and $request->fim) {
			$sql .= " and (dt_venda >= '$request->inicio' and dt_venda <= '$request->fim') ";
		}


		if ($request->ano and $request->mes) {
			$sql .= " and (ano = '$request->ano' and mes = '$request->mes') ";
		}

		if ($request->busca) {
			$sql .= " and (cli.id = '$request->busca' or cli.razao like '$request->busca%' or cli.municipio like '$request->busca%' or cli.grupo like '$request->busca%' or cli.subgrupo like '$request->busca%' or pc_cliente = '$request->busca') ";
		}

		if ($request->status) {
			if ($request->status == 'cancelado') {
				$sql .= " and (ult_status in ('980','984')) ";
			} else {
				$sql .= " and (ult_status not in ('980','984'))";
			}
		} 


		$web = array();

		if ($sql <> '') {
			$vendas = \DB::select("select left(subgrupo,20) subgrupo, pedido, dt_venda, pc_cliente, id_cliente, razao,  condpag, desconto, sum(pecas) as pecas, sum(valor) as valor, sum(orcamento) as orcamento,
					(select codigo from suspensoes sus where sus.pedido = base.pedido and sus.tipo = 'SQ' limit 1) as financeiro

	from (
		select subgrupo, pedido, dt_venda, pc_cliente, cli.id as id_cliente, cli.razao, cli.financeiro, condpag, desconto, qtde as pecas, valor,
			case when ult_status = '510' and prox_status = '515' then valor else 0 end as orcamento
		from vendas_jde
		left join addressbook cli on id_cliente = cli.id
	where --  ult_status not in ('980','984') and 
	id_rep in ($representante) $sql 
	) as base
	group by subgrupo, pedido, dt_venda, pc_cliente, id_cliente, razao, financeiro, condpag, desconto
	order by dt_venda");
	
		
		
		} else {
			
			$vendas = \DB::select("select left(subgrupo,20) subgrupo, pedido, dt_venda, pc_cliente, id_cliente, razao,  condpag, desconto, sum(pecas) as pecas, sum(valor) as valor, sum(orcamento) as orcamento,
					(select codigo from suspensoes sus where sus.pedido = base.pedido and sus.tipo = 'SQ' limit 1) as financeiro

from (
	select subgrupo, pedido, dt_venda, pc_cliente, cli.id as id_cliente, cli.razao, cli.financeiro, condpag, desconto, qtde as pecas, valor,
		case when ult_status = '510' and prox_status = '515' then valor else 0 end as orcamento
	from vendas_jde
	left join addressbook cli on id_cliente = cli.id
where -- ult_status not in ('980','984') and 
id_rep in ($representante)  and month(dt_venda) = month(now()) and year(dt_venda) = year(now()) 
) as base
group by subgrupo, pedido, dt_venda, pc_cliente, id_cliente, razao, financeiro, condpag, desconto
order by dt_venda");			
		}


		return view('vendas.lista')->with("vendas",$vendas)->with("web",$web);

	}

	
	
	public function detalhesVenda($id) {

		$id_rep = \Auth::user()->id_addressbook;
		$representante = \Session::get('representantes');


		$capa = \DB::select("
	select ab.subgrupo, ab.razao, sq.pedido,sq.id_cliente, sq.dt_venda, sum(sq.valor) as venda, ifnull(sum(nf.total),0) as faturado,
		ifnull((select sum(valor) from vendas_jde orc where orc.pedido = sq.pedido and orc.id_rep = $id_rep and orc.ult_status = '510' and orc.prox_status = '515'),0) as orcamento
		/*(select sum(valor_pago) 
			from titulos rec 
			left join pedidos_jde ped on rec.ped_original = ped.pedido and rec.tipo_original = ped.tipo
			left join vendas ven on ped.ped_original = ven.pedido and ped.tipo_original = ven.tipo and ped.linha_original = ven.linha
			where ven.pedido = sq.pedido and ven.tipo = sq.tipo and rec.tipo in ('RH', 'RI')) as recebido*/

	from vendas_jde sq
	left join pedidos_jde so on so.ped_original = sq.pedido and so.tipo_original = sq.tipo and so.linha_original = sq.linha
	left join notas_jde nf on nf.ped_original = so.pedido and nf.tipo_original = so.tipo and nf.linha_original = so.linha
	left join addressbook ab on sq.id_cliente = ab.id
	where sq.id_rep in ($representante)  and sq.pedido = $id
	group by ab.subgrupo, ab.razao,sq.dt_venda,  sq.pedido,sq.id_cliente, sq.tipo");


		$suspensoes = \DB::select("select * from suspensoes where pedido = $id and tipo = 'SQ'");


		$itens = \DB::select("
	select sq.*, nf.nf_legal,  case when (ar.nome = '' or ar.nome is null) then ar.fantasia else ar.nome end as repres,
		case 
			when so.ult_status >= '540' and so.ult_status < '560' then 'Separação'
			when so.ult_status >= '560' and so.ult_status < '580' then 'Embalagem'
			when so.ult_status >= '580' and so.ult_status < '620' then 'Faturamento'
			when sq.ult_status = '505' or sq.ult_status = '512' then 'Emitido'
			when sq.ult_status = '510' and sq.prox_status = '515' then 'Orçamento'
			when nf.ult_status = '620' and sq.prox_status = '999' then 'Faturado'
			when sq.ult_status in ('980','984') or so.ult_status in ('980','984') then 'Cancelado'
			else 'Outros'
		end as status, so.ult_status as ult2, sq.ult_status as ult1, so.pedido as pedido_so, udc.descricao1 as motivo
	from vendas_jde sq
	left join pedidos_jde so on so.ped_original = sq.pedido and so.tipo_original = sq.tipo and so.linha_original = sq.linha
	left join notas_jde nf on nf.ped_original = so.pedido and nf.tipo_original = so.tipo and nf.linha_original = so.linha
	left join udc on sq.motivo = udc.codigo and udc.produto = '42' and udc.codigos = 'RR'
 	left join addressbook ab on sq.id_cliente = ab.id
	left join addressbook ar on sq.id_rep = ar.id
	where sq.id_rep in ($representante) and sq.pedido = $id
	order by sq.linha");

		$comissoes = \DB::select("select base.id_rep, data_nota, nf_legal as nota, total_nota as valor_nota, sum(valor) as valor, ano, periodo, sum(comissao) as valor_comissao,
				case when sum(comissao) > 0 then 'Apurada' else 'Não Apurada' end as status

from (
	select sq.id_rep, nf.dt_emissao as data_nota, substr(nf_legal,3,7) nf_legal, sum(total) as total_nota
	from vendas_jde sq
	left join pedidos_jde so on so.ped_original = sq.pedido and so.tipo_original = sq.tipo and so.linha_original = sq.linha
	left join notas_jde nf on nf.ped_original = so.pedido and nf.tipo_original = so.tipo and nf.linha_original = so.linha
    left join itens on nf.id_item = itens.id
	where sq.id_rep in ($representante)  and sq.pedido = $id and nf.id is not null and itens.codtipoitem = '006'
	group by sq.id_rep, nf_legal,nf.dt_emissao 
) as base
left join comissoes on nota = base.nf_legal and comissoes.id_rep = base.id_rep
where comissoes.id is not null

group by base.id_rep,data_nota, nf_legal, total_nota, ano, periodo");

		if (isset($capa) && count($capa) > 0){

			return view('vendas.detalhes')->with("capa",$capa)->with("itens",$itens)->with('comissoes', $comissoes)->with('suspensoes', $suspensoes);

		} else {

			return redirect('/vendas');

		}


	}


	public function detalhesPedido($id) {

		$id_rep = \Auth::user()->id_addressbook;

		$itens = \DB::select("select *
				from vendas 
				left join addressbook on id_cliente = addressbook.id
				where pedido = '$id' and id_rep = $id_rep
				order by linha  ");

		return view('pedidos.detalhes')->with('itens', $itens);

	}

	public function importarPedido(Request $request) {


		$uploaddir = '/var/www/html/portalgo/storage/uploads/';
		$uploadfile = $uploaddir . basename($_FILES['arquivo']['name']);
		$erros = array();


		if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $uploadfile)) {

		    if (file_exists($uploadfile)) {

		        $handle = fopen($uploadfile, "r"); 

		        $linha = 1;

		        while (($line = fgetcsv($handle, 100000, ";")) !== FALSE) {

		            if ($linha >= 2) {   

		            	$pedido = $line[0];
		            	$ean = $line[1];
		            	$item = Item::where('ean', $ean)->first();

		            	if ($item) {

		            		//$checa_registro = PedidoItem::where('id_pedido', $pedido)->where('id_item', $item->id)->get();

		            		//if ($checa_registro) {

		            			//$erros[] = 'Pedido nº '.$pedido.' já existe';

		            		//} else {

				            	$pedido_item = new PedidoItem();
				            	$pedido_item->id_pedido = $pedido;
				            	$pedido_item->nome = $line[4];
				            	$pedido_item->email = $line[3];
				            	$pedido_item->id_item = $item->id;
				            	$pedido_item->item = $item->secundario;
				            	$pedido_item->qtde = $line[2];
				            	$pedido_item->unitario = $item->valortabela;
				            	$pedido_item->total = $line[2] * $item->valortabela;
				            	$pedido_item->created_by = 1;
				            	$pedido_item->save();

				            //}
 
			            } else {

			            	$erros[] = 'Produto '.$ean.' não existe';

			            }



		            }

		            $linha++;


		        }


		    }

		}


		if (count($erros) > 0) {

			$error = '<ul>';
		    foreach ($erros as $erro) {
		      $error .= '<li>'.$erro.'</li>';
		    }
			$error .= '</ul>';

		    $request->session()->flash('alert-danger', $error );

		}
		return redirect('/pedidos/');

	}


}
