<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Item;
use App\Favorito;

class PainelController extends Controller
{

	public function cet_aberto(Request $request) {
		$item = $request->item;

		$cet = \DB::select("select * from importacoes_pedidos where secundario like '%$item%'
		and ult_status not in  ('980','400')");
		//dd($cet);
		
		return view('produtos.painel.cet')->with('cet', $cet);


	}

	public function checaFavoritos() {

		$id_usuario = \Auth::id();

		$checa = Favorito::where('id_usuario', $id_usuario)->get();

		return response()->json($checa);


	}

	public function addFavoritos(Request $request) {

		$id_usuario = \Auth::id();
		$item = $request->item;

		$checa = Favorito::where('modelo', $item)->where('id_usuario', $id_usuario)->get();

		if ($checa && count($checa) > 0) {


		} else {

			$query = \DB::select("insert into favoritos (id_usuario, modelo, item) values ($id_usuario, '$item',  '$item') ");

		}



	}


	public function delFavoritos(Request $request) {

		$id_usuario = \Auth::id();
		$item = $request->item;

		$checa = Favorito::where('modelo', $item)->where('id_usuario', $id_usuario)->get();

		if ($checa && count($checa) > 0) {

			$query = \DB::select("delete from favoritos where modelo = '$item' and id_usuario = '$id_usuario' ");			

		}

	}

	public function verCampanhas($item) {

		$campanhas = \DB::select("select itens_adv.*, itens.statusatual from itens_adv 
									left join itens on itens.secundario = itens_adv.secundario
									where itens_adv.secundario = '$item' and categoria = 'adv' ");

		if (!$campanhas) {
	
			$campanhas = \DB::select("select itens_adv.*, itens.statusatual from itens_adv 
									left join itens on itens.secundario = itens_adv.secundario 
									where itens_adv.modelo = '$item' and categoria = 'adv' ");

		}

		return view('produtos.campanhas.lista')->with('item', $item)->with('campanhas', $campanhas);

	}



	public function verMidias($item) {

		$midias = \DB::select("select itens_adv.*, itens.statusatual from itens_adv 
									left join itens on itens.secundario = itens_adv.secundario
									where itens_adv.secundario = '$item' and categoria = 'midia'");

		if (!$midias) {
	
			$midias = \DB::select("select itens_adv.*, itens.statusatual from itens_adv 
									left join itens on itens.secundario = itens_adv.secundario 
									where itens_adv.modelo = '$item' and categoria = 'midia'");

		}

		return view('produtos.midias.lista')->with('item', $item)->with('midias', $midias);

	}


	public function uploadFoto(Request $request, $item) {

		$produto = Item::where('secundario', $item)->first();

		$path = $request->file('arquivo')->store('uploads/campanhas');

		$campanha = \DB::select("insert into itens_adv (id_item, modelo, secundario, arquivo, categoria) values ($produto->id, '$produto->modelo', '$item', '$path', 'adv') ");

		return redirect("/painel/campanhas/".$item);

	}



	public function uploadFotoMidia(Request $request, $item) {

		$produto = Item::where('secundario', $item)->first();

		$path = $request->file('arquivo')->store('uploads/midias');

		$campanha = \DB::select("insert into itens_adv (id_item, modelo, secundario, arquivo, categoria) values ($produto->id, '$produto->modelo', '$item', '$path', 'midia') ");

		return redirect("/painel/midias/".$item);

	}

	public function gravaHistorico(Request $request) {




    	$historico = new \App\ItemHistorico();
    	$historico->id_usuario = \Auth::id();
    	$historico->id_item = $request->id_item;
    	$historico->categoria = $request->categoria;
    	$historico->historico = $request->historico;
    	$historico->nova_data_producao = $request->data;
		$historico->pedido_fabrica = $request->numeropedido;


		if (isset($request->arquivo) <> '') { 
			$path = $request->file('arquivo')->store('uploads/historico');
	    	$historico->arquivo = $path;
	    }

    	$historico->save();

    	$request->session()->flash("alert-success", 'Historico registrado com sucesso');


    	return redirect()->back();
	}

	public function tabela() {


		return view('produtos.painel.tabela');


	}

	public function marcas() {

		$marcas = Item::where('grife', '<>', ".")
							->whereIn('codgrife', ['AH','BG'])
							
							->select('codgrife', 'grife')
							->groupBy('codgrife', 'grife')
							->orderBy('grife')
							->get();

		return view('produtos.painel.marcas')->with('marcas', $marcas);

	}

 	public function search(Request $request) {

		$grifes = \App\Permissao::getPermissao( \Auth::id() , 'grifes');

 		$modelo = Item::where('modelo', $request->busca)
 						->whereIn('codgrife', $grifes)
 						->first();


 		if ($modelo) {

	 		return redirect('/painel/'.$modelo->agrup.'/'.$modelo->modelo);

 		} else {


	 		$itens = \DB::select("select * from itens 
	 								where (modelo like '%$request->busca%' or descricao like '%$request->busca%') ");
	 		


	 		Item::where('modelo', 'LIKE', '%'.$request->busca.'%')
	 						->whereIn('codgrife', $grifes)
	 						->take(30)->get();
	 		return view('produtos.painel.search')->with('itens',$itens);

 		}



 	}


	public function agrupamentos() {
 
		$usuario = \Auth::user();

		$grifes = \App\Permissao::getPermissao($usuario->id, 'grifes');
		// dd($grifes);

		$string_grifes = '';
		foreach ($grifes as $grife) {
			$string_grifes = $string_grifes . "'" . $grife . "',";
		}

$string_grifes = substr($string_grifes,0,-1);
 		//dd($string_grifes);
		
			//$grifes = array("AH");
		$agrupamentos = \DB::connection('go')->select("select codagrup,agrup, codgrife, grife, linha from itens 
							where agrup <> '.'
							and codgrife in ($string_grifes)
							and codtipoitem ='006'
							and anomod<>'CANCELADO'
							and colmod <>'CANCELADO'
							
							group by codagrup,agrup, codgrife, grife, linha
							order By agrup");

		 if ( \Auth::user()->admin == 1 ) {
		 $colecoes =  \DB::connection('go')->select("select anomod from itens where anomod <> 'cancelado' and anomod >2015 group by anomod order by anomod desc");

		 
        } else {
            $colmod = \App\Permissao::getPermissao( \Auth::id(), 'colecoes');            

            $string_colmod = '';
		foreach ($colmod as $colmods) {
			$string_colmod = $string_colmod . "'" . $colmods . "',";
		}

$string_colmod = substr($string_colmod,0,-1);


$colecoes =  \DB::connection('go')->select("select anomod from itens where colmod in ($string_colmod) 
and  anomod > 2015
and  anomod not in ('.','cancelado','em branco') group by anomod order by anomod desc");

        }
        
		





		// $agrupamentos = Item::where('agrup', '!=', ".")
		// 					//->whereIn('codgrife', $grifes )
		// 					->where('codtipoitem', '006')
		// 					->where('anomod','!=','CANCELADO')
		// 					->where('colmod','!=','CANCELADO')
		// 					->select('codagrup','agrup', 'codgrife', 'grife', 'linha')
		// 					->groupby('codagrup','agrup', 'codgrife', 'grife', 'linha')
		// 					->orderBy('agrup')
		// 					->get();

							
		return view('produtos.painel.agrupamentos')->with('agrupamentos', $agrupamentos)->with('colecoes', $colecoes);

	}


	public function modelos(Request $request, $agrupamento) {
		

		$filtros = $request->all();
		$sql = '';
		$orderby = '';

		foreach ($filtros as $campo => $valor) {

			if ($campo == 'preco_de' and $valor <> '') {
				$sql .= " and valortabela >= $valor ";
			}
			if ($campo == 'preco_ate' and $valor <> '') {
				$sql .= " and valortabela <= $valor ";
			}


			if ($campo == 'ordem') {
				$ordem = explode(',', $valor);
				$orderby = 'order by ' . $ordem[0] . ' ' . $ordem[1];
			}
			if ($campo <> 'ordem' and $campo <> 'show' and $campo <> 'preco_de' and $campo <> 'preco_ate') {

				$valores = explode(',', $valor);
				$sql .= ' AND '.$campo.' IN (';
				$total = count($valores);
				$index = 0;
				foreach ($valores as $valor) {
					$index++;
					if ($index == $total) {
						$sql .="'$valor'";
					} else {
						$sql .="'$valor',";
					}
				}
				$sql .= ')';

			} 

		}

		if ($request->show == 'item') {
			$modelos = \App\Painel::listaItensPainel($agrupamento, $sql, $orderby);
		} else {
			$modelos = \App\Painel::listaModelos($agrupamento, $sql, $orderby);
		}

		$totais = array(
						"total_etq_brasil" => 0,
						"total_etq_china"  => 0,
						"total_etq_transito"  => 0,
						"total_etq_producao"  => 0,	
						"total_etq"  => 0,
						"total_vda_180"  => 0,
						"total_vda_total"  => 0,
						"total_vda_media"  => 0,
						"total_vda_orcamento"  => 0,	
		                "mostruarios"  => 0);	

		foreach ($modelos as $modelo) {

			$totais["total_etq_brasil"]   += $modelo->brasil;
			$totais["total_etq_china"]    += $modelo->cet;
			$totais["total_etq_transito"] += $modelo->etq;
			$totais["total_etq_producao"] += $modelo->cep;
			$totais["total_etq"] += ($modelo->brasil + $modelo->cet + $modelo->etq + $modelo->cep);

			$totais["total_vda_180"]   		+= $modelo->a_180dd;
			$totais["total_vda_total"]   	+= $modelo->vendas;
			$totais["total_vda_media"]   	+= $modelo->a_180dd;
			$totais["total_vda_orcamento"]  += $modelo->orcamentos;
			$totais["mostruarios"]  += $modelo->mostruarios;

		}


		$filtro_ano = Item::where('agrup', $agrupamento)
								//->where('anomod', '>=', '2015')
								->groupBy('anomod')
								->orderBy('anomod','desc')
								->take(6)
								->get(['anomod']);

		$filtro_colecao = Item::where('agrup', $agrupamento)
								->where('anomod', $request["anomod"])
								//->whereIn('colmod', \App\Permissao::getPermissao( \Auth::id(), 'colecoes'))
								->groupBy('colmod')
								->orderBy('colmod','desc')
								->take(10)
								->get(['colmod']);

		$filtro_genero = Item::where('agrup', $agrupamento)
								->where('codgenero','<>','')							
								->groupBy('genero')
								->orderBy('genero')
								->take(10)
								->get(['genero']);		

		$filtro_material = Item::where('agrup', $agrupamento)
								->where('material','<>','')							
								->groupBy('material')
								->orderBy('material')
								->take(10)
								->get(['material']);	

		$filtro_idade = Item::where('agrup', $agrupamento)
								->where('idade','<>','')							
								->groupBy('idade')
								->orderBy('idade')
								->take(10)
								->get(['idade']);		

		$array_ano = explode(',', $request["anomod"]);

		$filtro_clas = Item::where('agrup', $agrupamento)
								//->where('anomod', $request["anomod"])
								->whereIn('anomod', $array_ano)
								->where('clasmod','<>','')							
								->groupBy('codclasmod','clasmod')
								->orderBy('clasmod')
								->take(10)
								->get(['codclasmod','clasmod']);	
		

		$filtro_fixacao = Item::where('agrup', $agrupamento)
								->where('fixacao','<>','')							
								->groupBy('fixacao')
								->orderBy('fixacao')
								->take(10)
								->get(['fixacao']);		

		$filtro_fornecedores = Item::where('agrup', $agrupamento)
								->where('fornecedor','<>','')							
								->groupBy('fornecedor')
								->orderBy('fornecedor')
							->take(10)
								->get(['fornecedor']);
//			$filtro_fornecedores = \DB::select( "Select *
//			from
//			(select case when nome is null then razao else nome end as 'fornecedor' , razao
//			from itens 
//			left join addressbook ad on ad.id = itens.codfornecedor where agrup = '$agrupamento' group by nome, razao) as base
//			where fornecedor <> ''
//			and fornecedor is not null " );

		$filtro_status = Item::where('agrup', $agrupamento)
								->groupBy('codstatusatual')
								->orderBy('codstatusatual')
								->take(10)
								->get(['codstatusatual']);										
//		$agrupamentos = \DB::select("select codagrup, agrup, grife from itens where agrup <> '.' group by codagrup, agrup, grife  order by agrup");							

		//return response()->json($modelos);
		if (count($modelos) > 0) {
			return view('produtos.painel.modelos')->with('modelos', $modelos)
												->with('filtro_ano', $filtro_ano)
												->with('filtro_genero', $filtro_genero)
												->with('filtro_material', $filtro_material)
												->with('filtro_idade', $filtro_idade)
												->with('filtro_clas', $filtro_clas)
												->with('filtro_fixacao', $filtro_fixacao)
												->with('filtro_fornecedores', $filtro_fornecedores)
												->with('filtro_status', $filtro_status)
												->with('totais', $totais)
												->with('filtro_colecao', $filtro_colecao);
		} else {
			return view('produtos.painel.modelos')->with('modelos', array())
												->with('filtro_ano', $filtro_ano)
												->with('filtro_genero', $filtro_genero)
												->with('filtro_material', $filtro_material)
												->with('filtro_idade', $filtro_idade)
												->with('filtro_clas', $filtro_clas)
												->with('filtro_fixacao', $filtro_fixacao)
												->with('filtro_fornecedores', $filtro_fornecedores)
												->with('filtro_status', $filtro_status)
												->with('totais', $totais)
												->with('filtro_colecao', $filtro_colecao);
		}
	}



	public function favoritos(Request $request) {

		$agrupamento = '';
		$filtros = $request->all();
		$sql = '';
		$orderby = '';

		foreach ($filtros as $campo => $valor) {

			if ($campo <> 'ordem' and $campo <> 'show') {

				$valores = explode(',', $valor);
				$sql .= ' AND '.$campo.' IN (';
				$total = count($valores);
				$index = 0;
				foreach ($valores as $valor) {
					$index++;
					if ($index == $total) {
						$sql .="'$valor'";
					} else {
						$sql .="'$valor',";
					}
				}
				$sql .= ')';

			} else {

				if ($campo == 'ordem') {
					$ordem = explode(',', $valor);
					$orderby = 'order by ' . $ordem[0] . ' ' . $ordem[1];
				}

			}

		}

		// if ($request->show == 'item') {
		// 	$modelos = \App\Painel::listaItensPainel($agrupamento, $sql, $orderby);
		// } else {
		// 	$modelos = \App\Painel::listaModelos($agrupamento, $sql, $orderby);
		// }
		$sql = '';
		$orderby = '';
		$modelos = \App\Painel::listaFavoritos();
//dd($modelos);
		$totais = array(
						"total_etq_brasil" => 0,
						"total_etq_china"  => 0,
						"total_etq_transito"  => 0,
						"total_etq_producao"  => 0,	
						"total_etq"  => 0,
						"total_vda_180"  => 0,
						"total_vda_total"  => 0,
						"total_vda_media"  => 0,
						"total_vda_orcamento"  => 0,	
		                "mostruarios"  => 0);	

		foreach ($modelos as $modelo) {

			$totais["total_etq_brasil"]   += $modelo->brasil;
			$totais["total_etq_china"]    += $modelo->cet;
			$totais["total_etq_transito"] += $modelo->etq;
			$totais["total_etq_producao"] += $modelo->cep;
			$totais["total_etq"] += ($modelo->brasil + $modelo->cet + $modelo->etq + $modelo->cep);

			$totais["total_vda_180"]   		+= $modelo->a_180dd;
			$totais["total_vda_total"]   	+= $modelo->vendas;
			$totais["total_vda_media"]   	+= $modelo->a_180dd;
			$totais["total_vda_orcamento"]  += $modelo->orcamentos;
			$totais["mostruarios"]  += $modelo->mostruarios;

		}


		$filtro_ano = array();

		$filtro_colecao = array();

		$filtro_genero = array();		

		$filtro_material = array();	

		$filtro_idade = array();		

		$array_ano = array();

		$filtro_clas = array();		

		$filtro_fixacao = array();		

		$filtro_fornecedores = array();		

		$filtro_status = array();										
//		$agrupamentos = \DB::select("select codagrup, agrup, grife from itens where agrup <> '.' group by codagrup, agrup, grife  order by agrup");							

		//return response()->json($modelos);
		if (count($modelos) > 0) {
			return view('produtos.painel.modelos')->with('modelos', $modelos)
												->with('filtro_ano', $filtro_ano)
												->with('filtro_genero', $filtro_genero)
												->with('filtro_material', $filtro_material)
												->with('filtro_idade', $filtro_idade)
												->with('filtro_clas', $filtro_clas)
												->with('filtro_fixacao', $filtro_fixacao)
												->with('filtro_fornecedores', $filtro_fornecedores)
												->with('filtro_status', $filtro_status)
												->with('totais', $totais)
												->with('filtro_colecao', $filtro_colecao);
		} else {
			return view('produtos.painel.modelos')->with('modelos', array())
												->with('filtro_ano', $filtro_ano)
												->with('filtro_genero', $filtro_genero)
												->with('filtro_material', $filtro_material)
												->with('filtro_idade', $filtro_idade)
												->with('filtro_clas', $filtro_clas)
												->with('filtro_fixacao', $filtro_fixacao)
												->with('filtro_fornecedores', $filtro_fornecedores)
												->with('filtro_status', $filtro_status)
												->with('totais', $totais)
												->with('filtro_colecao', $filtro_colecao);
		}
	}


	public function itens($agrupamento, $modelo) {

		// $modelo = \DB::select("select recall.*, custo_2019.*, caracteristica.*, trocas.*,
		// base.*,ifnull(vendas.vendas,0) vendas, ifnull(vendas.vda30dd,0) vda30dd, 
		// ifnull(vendas.vda60dd,0) vda60dd,
		// ifnull(vendas.vda90dd,0) vda90dd,
		// ifnull(vendas.a_180dd,0) a_180dd,
				
		
		
		// /*ifnull(saldos.sld_total,0) sld_total,*/ ifnull(orc.qtde_valido,0) orcamentos_valido, ifnull(orc.orcamentos,0) orcamentos , ifnull(saldo.brasil,0) brasil, ifnull(saldo.cet,0) cet, ifnull(saldo.etq,0) etq, ifnull(saldo.cep,0) cep, ifnull(saldo.saldo_manutencao, 0) saldo_manutencao,
		// 	ifnull(saldo.brasil,0)+ifnull(saldo.cet,0)+ifnull(saldo.etq,0)+ ifnull(saldo.cep,0) totaletq,
		// 	ifnull(saldo.mostruarios,0) mostruarios,
			
			
			
  //           tipoitem,
		// 	(select sum(qtde)
		// 	from compras_itens
		// 	left join itens on compras_itens.item = itens.secundario
		// 	where itens.modelo = base.modelo
		// 	and status in ('aberto', 'enviado','confirmado')
		// 	) as pedidoaberto,qtde_compra

		// 	from (

		// 	select  agrup, grife, modelo, anomod, (select id from itens where modelo = a.modelo limit 1) as id_item,


		// 	(select secundario from itens b where a.modelo = b.modelo and codtipoitem = '006'  order by secundario limit 1) as item,
		// 	(select id from itens b where a.modelo = b.modelo and codtipoitem = '006'  order by secundario limit 1) as id,
			
            
            
                       
		// 	count(secundario) as itens

		// 	from itens a
		// 	where a.codtipoarmaz <> 'o'  and codtipoitem = '006'
		// 	and colmod <>'COLE??O EUROPA'
		// 	and colmod <> 'CANCELADO' and a.modelo = '$modelo'
		// 	group by agrup, grife, modelo, anomod, codmod 
		// 	) as base


		// 	/**vendas sinteticas**/
		// 	left join (select modelo, sum(ult_30dd) as vda30dd , sum(ult_60dd) as vda60dd , sum(ult_90dd) as vda90dd, sum(a_180dd) a_180dd, sum(vendastt) vendas from vendas_sint  group by modelo ) as vendas
		// 	on vendas.modelo = base.modelo


		// 	/**orcamentos**/
		// 	left join (select b.modelo, sum(orcamentos.orctt) as orcamentos, sum(orcamentos.orcvalido) as qtde_valido from orcamentos left join itens b on b.id = orcamentos.curto group by b.modelo
		// 	) as orc
		// 	on orc.modelo = base.modelo


		// 	/**saldos**/
		// 	left join (
		// 		select modelo, sum(brasil) brasil, sum(saldo_manutencao) saldo_manutencao, sum(cet) cet, sum(mostruarios) mostruarios, sum(etq) etq, sum(cep) cep
		// 		from (
		// 		select a.secundario, b.modelo, 
		// 		sum(disponivel+em_beneficiamento) as brasil, sum(saldo_manutencao) as saldo_manutencao,
		// 		sum(cet+(saldo_parte)) as cet, sum(saldo_most) as mostruarios,

		// 		sum(estoque) as etq,
		// 		sum(producao)  as cep

		// 		from saldos a 
		// 		left join itens b on b.id = a.curto
  //               left join producoes_sint on producoes_sint.id = a.curto
		// 		group by a.secundario, b.modelo

		// 		) as fim group by modelo
		// 	) as saldo
		// 	on saldo.modelo = base.modelo
            
  //           left join (
  //            select modelo, avg(valortabela) as valor, avg(ultcusto) as custo, colmod as colecao, clasmod, tipoitem, linha, 
  //           fornecedor as fornecedor1, genero, idade, material, fixacao, estilo, tamolho, tamhaste, tamponte, tecnologia,
		// 	codfornecedor
  //           from itens
            
		// 	where codtipoarmaz <> 'o'  and codtipoitem = '006'
		// 	and colmod <>'COLE??O EUROPA'
		// 	and colmod <> 'CANCELADO'
		// 	group by  modelo, colmod, clasmod, tipoitem, linha, 
  //           fornecedor, genero, idade, material, fixacao, estilo, tamolho, tamhaste, tamponte, tecnologia,
		// 	codfornecedor
		// 	order by codfornecedor desc) as caracteristica on caracteristica.modelo = base.modelo
            
  //           left join (select avg(custo) custo, moeda, modelo
		// 	from custos_2019
			
  //           group by moeda, modelo
			
		// 	) as custo_2019 on custo_2019.modelo = base.modelo
  //           left join 
  //           (select sum(qtde) as trocas, modelo
		// 	from trocas
		// 	left join itens  bb on id_item = bb.id
			
			
		// 	group by bb.modelo) as trocas on trocas.modelo = base.modelo
            
  //           left join (
  //           select modelo, case when recall.id is not null then 'sim' else 'nao' end as recall
  //           from recall 
  //           left join itens on itens.id = recall.id_item
  //           where  dt_libera = '0000-00-00' limit 1) as recall on recall.modelo = base.modelo

  //            left join (
		// 	select modelo, sum(qtde) as qtde_compra
		// 	from compras_itens
		// 	left join itens on compras_itens.item = itens.secundario
		// 	where status = 'aberto' and pedido_dt > '2020-06-01'
		// 	group by modelo
		// 	) as compras on compras.modelo = base.modelo
  //           limit 1");

		$modelo = \DB::select("select*, fornecedor as 'fornecedor2' , codfornecedor as codfornecedor1 from painel_modelo
			where modelo = '$modelo' ");

		
		$modelo = $modelo[0];
	
		

		
		
		
		
		
		
		
		$itens    = \App\Painel::listaItens($modelo->modelo);

		// ciclo de colecoes
		// $colecoes = \App\Item::where('agrup', $modelo->agrup)
		// 								->where('colmod', '>=', $modelo->colmod)
		// 								->where('colmod', '<>', '.')
		// 								->where('colmod', '<>', '')
		// 								->groupBy('colmod')
		// 								->get(['colmod']);


		$colecoes = \App\Caracteristica::where('campo', 'colmod')->where('valor', '>=', $modelo->colecao)->orderBy('valor')->get();

		return view('produtos.painel.itens')->with('modelo', $modelo)->with('itens', $itens)->with('colecoes', $colecoes)->with('catalogo', $modelo);		

	}


	public function item($agrupamento, $modelo, $item) {

		$item    = Item::where('secundario', $item)->first();
		

		if(isset($item->id))
		{
		$sql = "id_item = '$item->id'";
			$item_id = $item->id;
		}
			else 
			{
					$sql = "modelo = '$modelo'";
				$item_id = '4520';
			}
			
		
		
		$historicos = \DB::select("select historicos.*, usuarios.* from historicos 
		left join usuarios on usuarios.id = historicos.id_usuario 
		left join itens on id_item = itens.id			
		
		where $sql order by historicos.id desc");
			

		$catalogo    = \App\Painel::listaItem($item_id);

		return view('produtos.painel.item')->with('item', $item)->with('historicos', $historicos)->with('catalogo', $catalogo);		

	}
	
	
	public function dsrep($agrupamento, $modelo, $item) {

		$item    = Item::where('secundario', $item)->first();
		

		if(isset($item->id))
		{
		$sql = "id_item = '$item->id'";
			$item_id = $item->id;
		}
			else 
			{
					$sql = "modelo = '$modelo'";
				$item_id = '4520';
			}
			
		
		
		$historicos = \DB::select("select historicos.*, usuarios.* from historicos 
		left join usuarios on usuarios.id = historicos.id_usuario 
		left join itens on id_item = itens.id			
		
		where $sql order by historicos.id desc");
			

		$catalogo    = \App\Painel::listaItem($item_id);

		return view('dashboards.representante.rep_det')->with('item', $item)->with('historicos', $historicos)->with('catalogo', $catalogo);		

	}
	
	
	
	
	
	
	
	public function exportaSalesReport1(Request $request, $agrupamento) {
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		ini_set('memory_limit', -1);
		ini_set('max_execute_time', -1);
		
		$itens = \DB::select("
		 select 
case
when item.grife in ('DINIZ', 'GO') then 'Z_OTHER'
when item.grife in ('EVOKE', 'EVOKE NOVOS NEGÓCIOS' ) then 'EVOKE'
else item.grife end as Grif, 


case 
when item.grife in ('DINIZ', 'GO') then 'Z_OTHER'
when item.agrup in ('AT01 - ATITUDE (SL)', 'PA01 - PANICO (SL)','MMA01 - MMA (SL)') then 'AT01 - ATITUDE (SL)' 
when item.agrup in ('AT02 - ATITUDE (RX)', 'MMA02 - MMA (RX)') then 'AT02 - ATITUDE (RX)' 
when item.agrup in ('EV01 - EVOKE (SL)', 'EVN01 - EVOKE N. NEG (SL)' ) then 'EV01 - EVOKE (SL)'
when item.agrup in ('EV02 - EVOKE (RX)', 'EVN02 - EVOKE N. NEG (RX)' ) then 'EV02 - EVOKE (RX)'
else item.agrup end as Type, 
 item.modelo Model, item.secundario Item, 

item.colmod Model_colection, 
case 
when (item.colmod in( '','.') and item.clasmod in ('linha a')) then '2019' 
when substring(item.colmod,1,4) <= '2015' then '<=2015' 
else substring(item.colmod,1,4) end as Model_colection_year,

case when substring(item.colmod,1,4) <= '2017' then '' else item.colmod end as Model_colection_res,
item.colitem Item_colection, 

case when item.clasmod = 'Add sales cat S5 codes here' then 'PROMOCIONAL C' else item.clasmod end as Model_clas, 

item.clasitem Item_clas, item.genero Gender, 
item.idade Age, item.fixacao Lenses_construction, 

vda.ult_30dd  last_30dd,  vda.ult_60dd  last_60dd,  vda.ult_90dd  last_90dd,
vda.ult_120dd last_120dd, vda.ult_150dd last_150dd, vda.ult_180dd last_180dd,
vda.ult_210dd last_210dd, vda.ult_240dd last_240dd, vda.ult_270dd last_270dd,
vda.ult_300dd last_300dd, vda.ult_330dd last_330dd, vda.ult_360dd last_360dd,
vda.vendastt total,

sld.disp_vendas as Availability, 

conf_montado+em_beneficiamento+saldo_parte as Factoring_BR, 

qtd_rot_receb+cet as In_transit, 

ifnull((select sum(estoque)  from producoes_sint pe where pe.cod_sec = item.secundario
),0)as Stock_Factory,

ifnull((select sum(producao) from producoes_sint pe where pe.cod_sec = item.secundario
),0) as In_production,

/**mudar oara producoes_sint
etq as Stock_Factory, 
cep as In_production,
**/


case when substring(item.colmod,1,4) <= '2015' then 0 else saldo_manutencao end as Maintenance,
saldo_trocas as Estrategy_reserve,
saldo_most as Showcases, 

case when substring(item.colmod,1,4) <= '2015' 
then sld.disp_vendas+saldo_trocas+saldo_most+conf_montado+em_beneficiamento+saldo_parte+qtd_rot_receb+cet
else sld.disp_vendas+saldo_trocas+saldo_most+saldo_manutencao+conf_montado+em_beneficiamento+saldo_parte+qtd_rot_receb+cet
end as Stock_total

from go.itens item
left join go.vendas_sint vda 	on vda.curto 	= item.id
left join go.saldos sld 		on sld.curto	= item.id




where 
	-- item.fornecedor = 'WENZHOU ZHONGMIN GLASSES CQ LTDA' 
	item.codfornecedor = 102606
	and item.codtipoitem = '006' 
and item.agrup = '$agrupamento' 

");


		$spreadsheet = new Spreadsheet();

		$sheet = $spreadsheet->getActiveSheet();		

		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('E')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('F')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('G')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('H')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('I')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('J')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('K')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('L')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('M')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('N')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('O')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('P')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('Q')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('R')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('T')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('U')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('V')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('W')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('X')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('Y')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('Z')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AA')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AB')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AC')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AD')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AE')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AF')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AG')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AH')->setAutoSize(true);
		
		
		
		$sheet->setCellValue('A1', 'Grif')
	            ->setCellValue('B1', 'Type')
	            ->setCellValue('C1', 'Model')
	            ->setCellValue('D1', 'Item')
	            ->setCellValue('E1', 'Model_colection')
	            ->setCellValue('F1', 'Model_colection_year')
	            ->setCellValue('G1', 'Item_colection')
	            ->setCellValue('H1', 'Model_clas')
	            ->setCellValue('I1', 'Item_clas')
	            ->setCellValue('J1', 'Gender')
				->setCellValue('K1', 'Age')
				->setCellValue('L1', 'Lenses_construction')
				->setCellValue('M1', 'last_30dd')
				->setCellValue('N1', 'last_60dd')
				->setCellValue('O1', 'last_90dd')
				->setCellValue('P1', 'last_120dd')
				->setCellValue('Q1', 'last_150dd')
				->setCellValue('R1', 'last_180dd')
				->setCellValue('S1', 'last_210dd')
				->setCellValue('T1', 'last_240dd')
				->setCellValue('U1', 'last_270dd')
				->setCellValue('V1', 'last_300dd')
				->setCellValue('W1', 'last_330dd')
				->setCellValue('X1', 'last_360dd')
				->setCellValue('Y1', 'Total')
				->setCellValue('Z1', 'Availability')
				->setCellValue('AA1', 'Factoring_BR')
	            ->setCellValue('AB1', 'In_transit')
	            ->setCellValue('AC1', 'Stock_Factory')
	            ->setCellValue('AD1', 'In_production')
	            ->setCellValue('AE1', 'Maintenance')
	            ->setCellValue('AF1', 'Estrategy_reserve')
	            ->setCellValue('AG1', 'Showcases')
	            ->setCellValue('AH1', 'Stock_total');
		

	    $index = 2;

		foreach ($itens as $item) {

			
				
			$sheet->setCellValue('A'.$index, $item->Grif)
		            ->setCellValue('B'.$index, $item->Type)
		            ->setCellValue('C'.$index, $item->Model)
		            ->setCellValue('D'.$index, $item->Item)
		            ->setCellValue('E'.$index, $item->Model_colection)
		            ->setCellValue('F'.$index, $item->Model_colection_year)
		            ->setCellValue('G'.$index, $item->Item_colection)
		            ->setCellValue('H'.$index, $item->Model_clas)
		            ->setCellValue('I'.$index, $item->Item_clas)
		            ->setCellValue('J'.$index, $item->Gender)
					->setCellValue('K'.$index, $item->Age)
					->setCellValue('L'.$index, $item->Lenses_construction)
					->setCellValue('M'.$index, $item->last_30dd)
					->setCellValue('N'.$index, $item->last_60dd)
					->setCellValue('O'.$index, $item->last_90dd)
					->setCellValue('P'.$index, $item->last_120dd)
					->setCellValue('Q'.$index, $item->last_150dd)
					->setCellValue('R'.$index, $item->last_180dd)
					->setCellValue('S'.$index, $item->last_210dd)
					->setCellValue('T'.$index, $item->last_240dd)
					->setCellValue('U'.$index, $item->last_270dd)
					->setCellValue('V'.$index, $item->last_300dd)
					->setCellValue('W'.$index, $item->last_330dd)
					->setCellValue('X'.$index, $item->last_360dd)
					->setCellValue('Y'.$index, $item->total)
					->setCellValue('Z'.$index, $item->Availability)
					->setCellValue('AA'.$index, $item->Factoring_BR)
		            ->setCellValue('AB'.$index, $item->In_transit)
		            ->setCellValue('AC'.$index, $item->Stock_Factory)
		            ->setCellValue('AD'.$index, $item->In_production)
		            ->setCellValue('AE'.$index, $item->Maintenance)
		            ->setCellValue('AF'.$index, $item->Estrategy_reserve)
		            ->setCellValue('AG'.$index, $item->Showcases)
		            ->setCellValue('AH'.$index, $item->Stock_total);
				
				
			$index++;

		}            
		
		$nome = 'salesreport_'.date("Y-m-d").'_'.$agrupamento.'.xlsx';
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$nome.'"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// // If you're serving to IE over SSL, then the following may be needed
		// header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		// header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		// header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		// header ('Pragma: public'); // HTTP/1.0


		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');



	}

	
	public function exportaPrecosugerido(Request $request, $agrupamento) {
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		ini_set('memory_limit', -1);
		ini_set('max_execute_time', -1);
		
		$sugerido = \DB::select("
		select Agrupamento, Modelo, Cod_Secundario, Num_Curto, EAN, Col_Item, Valor,

case when Valor<30 then 'VENDA NÃO AUTORIZADA'
when Agrupamento like 'ah0%' and Valor_Sugerido <298 then replace(298.00,'.',',') 
when Agrupamento like 'hi0%' and Valor_Sugerido <258 then replace(258.00,'.',',')
when Agrupamento like 'ev0%' and Valor_Sugerido <298 then replace(298.00,'.',',')
when Agrupamento like 'tc0%' and Valor_Sugerido <298 then replace(298.00,'.',',')
when Valor>20 and Valor_Sugerido<98 then replace(98.00,'.',',') else replace(format(Valor_Sugerido,2),'.',',') end as Sugerido,statusatual


from(
select agrup as Agrupamento, modelo as Modelo, secundario as Cod_Secundario, id as Num_Curto, ean as EAN, colitem as Col_Item, valortabela as Valor, 

case when right(CEILING(valortabela * markup),1) = 1 then (CEILING(valortabela * markup)-3) 
 when right(CEILING(valortabela * markup),1) = 2 then (CEILING(valortabela * markup)+3) 
 when right(CEILING(valortabela * markup),1) = 3 then (CEILING(valortabela * markup)+2) 
 when right(CEILING(valortabela * markup),1) = 4 then (CEILING(valortabela * markup)+1) 
 when right(CEILING(valortabela * markup),1) = 6 then (CEILING(valortabela * markup)+2) 
 when right(CEILING(valortabela * markup),1) = 7 then (CEILING(valortabela * markup)+1) 
 when right(CEILING(valortabela * markup),1) = 9 then (CEILING(valortabela * markup)-1) 
 when right(CEILING(valortabela * markup),1) = 0 then (CEILING(valortabela * markup)-2) else
CEILING(valortabela * markup) end as Valor_Sugerido,statusatual

from(
SELECT grife, linha, fornecedor, agrup, modelo, secundario, id, primario, ean, colitem, valortabela, 
case when linha = 'solar' then 2.5 else 2.8 end as markup, statusatual

from go.itens
where
itens.codtipoarmaz not like 'i' and itens.tipoarmaz not like 'obsoleto%' and itens.tipoitem not like 'frente%' and itens.tipoitem not like '%semi%' 
and itens.agrup = '$agrupamento' 
and itens.colmod <> 'cancelado'
order by itens.agrup, itens.modelo, itens.secundario asc)base
)base_sug


");


		$spreadsheet = new Spreadsheet();

		$sheet = $spreadsheet->getActiveSheet();		

		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('E')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('F')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('G')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('H')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('I')->setAutoSize(true);
		
		
		
		$sheet->setCellValue('A1', 'Agrupamento')
	            ->setCellValue('B1', 'Modelo')
	            ->setCellValue('C1', 'Cod_secundario')
	            ->setCellValue('D1', 'Num_curto')
	            ->setCellValue('E1', 'Ean')
	            ->setCellValue('F1', 'Colecao_item')
	            ->setCellValue('G1', 'Valor')
	            ->setCellValue('H1', 'Sugerido')
	            ->setCellValue('I1', 'Status atual');
		

	    $index = 2;

		foreach ($sugerido as $sugerido1) {

			
				
			$sheet->setCellValue('A'.$index, $sugerido1->Agrupamento)
		            ->setCellValue('B'.$index, $sugerido1->Modelo)
		            ->setCellValue('C'.$index, $sugerido1->Cod_Secundario)
		            ->setCellValue('D'.$index, $sugerido1->Num_Curto)
		            ->setCellValue('E'.$index, $sugerido1->EAN)
		            ->setCellValue('F'.$index, $sugerido1->Col_Item)
		            ->setCellValue('G'.$index, 'R$ '.$sugerido1->Valor)
		            ->setCellValue('H'.$index, 'R$ '.$sugerido1->Sugerido)
		            ->setCellValue('I'.$index, $sugerido1->statusatual);
				
				
			$index++;

		}            
		
		$nome = 'precosugerido_'.date("Y-m-d").'_'.$agrupamento.'.xlsx';
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$nome.'"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// // If you're serving to IE over SSL, then the following may be needed
		// header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		// header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		// header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		// header ('Pragma: public'); // HTTP/1.0


		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');



	}
	
	
	
		public function exportaPrecosugeridod(Request $request, $agrupamento) {
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		ini_set('memory_limit', -1);
		ini_set('max_execute_time', -1);
		
		$sugeridod = \DB::select("
		select Agrupamento, Modelo, Cod_Secundario, Num_Curto, EAN, Col_Item, Valor,

case when Valor<30 then 'VENDA NÃO AUTORIZADA'
when Agrupamento like 'ah0%' and Valor_Sugerido <298 then replace(298.00,'.',',') 
when Agrupamento like 'hi0%' and Valor_Sugerido <258 then replace(258.00,'.',',')
when Agrupamento like 'ev0%' and Valor_Sugerido <298 then replace(298.00,'.',',')
when Agrupamento like 'tc0%' and Valor_Sugerido <298 then replace(298.00,'.',',')
when Valor>20 and Valor_Sugerido<98 then replace(98.00,'.',',') else replace(format(Valor_Sugerido,2),'.',',') end as Sugerido,statusatual


from(
select agrup as Agrupamento, modelo as Modelo, secundario as Cod_Secundario, id as Num_Curto, ean as EAN, colitem as Col_Item, valortabela as Valor, 

case when right(CEILING(valortabela * markup),1) = 1 then (CEILING(valortabela * markup)-3) 
 when right(CEILING(valortabela * markup),1) = 2 then (CEILING(valortabela * markup)+3) 
 when right(CEILING(valortabela * markup),1) = 3 then (CEILING(valortabela * markup)+2) 
 when right(CEILING(valortabela * markup),1) = 4 then (CEILING(valortabela * markup)+1) 
 when right(CEILING(valortabela * markup),1) = 6 then (CEILING(valortabela * markup)+2) 
 when right(CEILING(valortabela * markup),1) = 7 then (CEILING(valortabela * markup)+1) 
 when right(CEILING(valortabela * markup),1) = 9 then (CEILING(valortabela * markup)-1) 
 when right(CEILING(valortabela * markup),1) = 0 then (CEILING(valortabela * markup)-2) else
CEILING(valortabela * markup) end as Valor_Sugerido,statusatual

from(
SELECT grife, linha, fornecedor, agrup, modelo, secundario, id, primario, ean, colitem, valortabela, 
case when linha = 'solar' then 2.5 else 2.8 end as markup, statusatual

from go.itens
where
itens.codtipoarmaz not like 'i' and itens.tipoarmaz not like 'obsoleto%' and itens.tipoitem not like 'frente%' and itens.tipoitem not like '%semi%' 
and itens.agrup = '$agrupamento' and statusatual not in ('esgotado','em producao')
and itens.colmod <> 'cancelado'
order by itens.agrup, itens.modelo, itens.secundario asc)base
)base_sug


");


		$spreadsheet = new Spreadsheet();

		$sheet = $spreadsheet->getActiveSheet();		

		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('E')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('F')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('G')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('H')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('I')->setAutoSize(true);
		
		
		
		$sheet->setCellValue('A1', 'Agrupamento')
	            ->setCellValue('B1', 'Modelo')
	            ->setCellValue('C1', 'Cod_secundario')
	            ->setCellValue('D1', 'Num_curto')
	            ->setCellValue('E1', 'Ean')
	            ->setCellValue('F1', 'Colecao_item')
	            ->setCellValue('G1', 'Valor')
	            ->setCellValue('H1', 'Sugerido')
	            ->setCellValue('I1', 'Status atual');
		

	    $index = 2;

		foreach ($sugeridod as $sugerido1) {

			
				
			$sheet->setCellValue('A'.$index, $sugerido1->Agrupamento)
		            ->setCellValue('B'.$index, $sugerido1->Modelo)
		            ->setCellValue('C'.$index, $sugerido1->Cod_Secundario)
		            ->setCellValue('D'.$index, $sugerido1->Num_Curto)
		            ->setCellValue('E'.$index, $sugerido1->EAN)
		            ->setCellValue('F'.$index, $sugerido1->Col_Item)
		            ->setCellValue('G'.$index, 'R$ '.$sugerido1->Valor)
		            ->setCellValue('H'.$index, 'R$ '.$sugerido1->Sugerido)
		            ->setCellValue('I'.$index, $sugerido1->statusatual);
				
				
			$index++;

		}            
		
		$nome = 'precosugerido_'.date("Y-m-d").'_'.$agrupamento.'.xlsx';
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$nome.'"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// // If you're serving to IE over SSL, then the following may be needed
		// header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		// header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		// header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		// header ('Pragma: public'); // HTTP/1.0


		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');



	}
	
	
	public function imprimir($modelo) {

		//$modelo = Item::where('modelo', $modelo)->orderBy('codtipoarmaz', 'asc')->first();
		$itens    = \App\Painel::listaItens($modelo);

		$constructor = [
		        'mode' => '',
		        'format' => 'A4',
		        'default_font_size' => 0,
		        'default_font' => '',
		        'margin_left' => 0,
		        'margin_right' => 0,
		        'margin_top' => 0,
		        'margin_bottom' => 0,
		        'margin_header' => 0,
		        'margin_footer' => 0,
		        'orientation' => 'P',
		    ];

		$mpdf = new \Mpdf\Mpdf($constructor);

		$agrup = $itens[0]->agrup;

		$rank = \Db::select("SET @rank=0");
		$rank = \Db::select("
								select * from (
								select *, @rank:=@rank+1 AS rank from (
									select  modelo, sum(ult_180dd) as ult180
									from vendas_sint
									where agrup like '$agrup'
									group by modelo
									order by sum(ult_180dd) desc
								) as sele1
								) as sele2
								where modelo = '$modelo';");

		if (empty($rank[0]->rank)){
			$rank2 = 0;
		} else {
			$rank2 = $rank[0]->rank;
		}
		


		$html = '';	


		$foto_modelo = app('App\Http\Controllers\ItemController')->consultaFotoModelo($modelo);
		$html .= '<div align="center"><img src="/'.$foto_modelo.'" style="margin:0;padding:0; height:250px;"> Rank '.$rank2.'</div>';

		foreach ($itens as $item) {
		if (empty($item->vda30dd)) {
			$vda30dd = '0';
		} else {
			$vda30dd = $item->vda30dd;
			
		}
			
			if (empty($item->vda60dd)) {
			$vda60dd = '0';
		} else {
			$vda60dd = $item->vda60dd;
			
		}
			
			if (empty($item->a_180dd)) {
			$a_180dd = '0';
		} else {
			$a_180dd = $item->a_180dd;
			
		}
		
			
		if (empty($item->brasil)) {
			$etq_brasil = '0';
		} else {
			$etq_brasil = $item->brasil;
			
		}
		
			

			$html .= '<table width="80%" border="0">';
			$foto = app('App\Http\Controllers\ItemController')->consultaFoto($item->secundario);
			$html .= '<tr>';				
			
			$html .= '<td valign="top"><img src="/'.$foto.'" style="margin:0;padding:0; height:250px;"></td>';

				
					$html .= '<td valign="middle" width="15%">'.$item->secundario.'</td>';
					$html .= '<td valign="middle" width="20%"><b>0 a 30d:</b> '.$vda30dd.'</td>';
					$html .= '<td valign="middle" width="20%"><b>31 a 60d:</b> '.$vda60dd.'</td>';
					$html .= '<td valign="middle" width="20%"><b>0 a 180tt:</b> '.$a_180dd.'</td>';
				$html .= '<td valign="middle" width="20%"><b>etq br:</b> '.$etq_brasil.'</td>';
			
			$html .= '</tr>';
			$html .= '</table>';
						
						
			
		
			
			
			
		}
//		return view('produtos.painel.itens')->with('modelo', $modelo)->with('itens', $itens)->with('colecoes', $colecoes);		

		//$rodape = '<div align="center">
						//<small align="center"><b>Created by:</b> '.\Auth::user()->nome.' <b> in </b> '.date("d/m/Y H:i:s").'</small>
					//</div>';
		// Write some HTML code:
		//$mpdf->SetHTMLFooter($rodape);
		//$mpdf->WriteHTML($stylesheet,1);
		$mpdf->WriteHTML($html,2);

		// Output a PDF file directly to the browser
		$mpdf->Output();		


	}
	public function modelosEstilo(Request $request, $agrupamento) {
		

		$filtros = $request->all();
		$sql = '';
		$orderby = '';

		foreach ($filtros as $campo => $valor) {

			if ($campo == 'preco_de' and $valor <> '') {
				$sql .= " and valortabela >= $valor ";
			}
			if ($campo == 'preco_ate' and $valor <> '') {
				$sql .= " and valortabela <= $valor ";
			}


			if ($campo == 'ordem') {
				$ordem = explode(',', $valor);
				$orderby = 'order by ' . $ordem[0] . ' ' . $ordem[1];
			}
			if ($campo <> 'ordem' and $campo <> 'show' and $campo <> 'preco_de' and $campo <> 'preco_ate') {

				$valores = explode(',', $valor);
				$sql .= ' AND '.$campo.' IN (';
				$total = count($valores);
				$index = 0;
				foreach ($valores as $valor) {
					$index++;
					if ($index == $total) {
						$sql .="'$valor'";
					} else {
						$sql .="'$valor',";
					}
				}
				$sql .= ')';

			} 

		}

		if ($request->show == 'item') {
			$modelos = \App\Painel::listaItensPainel($agrupamento, $sql, $orderby);
		} else {
			$modelos = \App\Painel::listaModelosEstilo($agrupamento, $sql, $orderby);
		}

		$totais = array(
						"total_etq_brasil" => 0,
						"total_etq_china"  => 0,
						"total_etq_transito"  => 0,
						"total_etq_producao"  => 0,	
						"total_etq"  => 0,
						"total_vda_180"  => 0,
						"total_vda_total"  => 0,
						"total_vda_media"  => 0,
						"total_vda_orcamento"  => 0,	
		                "mostruarios"  => 0);	

		foreach ($modelos as $modelo) {

			$totais["total_etq_brasil"]   += $modelo->brasil;
			$totais["total_etq_china"]    += $modelo->cet;
			$totais["total_etq_transito"] += $modelo->etq;
			$totais["total_etq_producao"] += $modelo->cep;
			$totais["total_etq"] += ($modelo->brasil + $modelo->cet + $modelo->etq + $modelo->cep);

			$totais["total_vda_180"]   		+= $modelo->a_180dd;
			$totais["total_vda_total"]   	+= $modelo->vendas;
			$totais["total_vda_media"]   	+= $modelo->a_180dd;
			$totais["total_vda_orcamento"]  += $modelo->orcamentos;
			$totais["mostruarios"]  += $modelo->mostruarios;

		}


		$filtro_ano = Item::where('agrup', $agrupamento)
								//->where('anomod', '>=', '2015')
								->groupBy('anomod')
								->orderBy('anomod','desc')
								->take(6)
								->get(['anomod']);

		$filtro_colecao = Item::where('agrup', $agrupamento)
								->where('anomod', $request["anomod"])
								//->whereIn('colmod', \App\Permissao::getPermissao( \Auth::id(), 'colecoes'))
								->groupBy('colmod')
								->orderBy('colmod','desc')
								->take(10)
								->get(['colmod']);

		$filtro_genero = Item::where('agrup', $agrupamento)
								->where('codgenero','<>','')							
								->groupBy('genero')
								->orderBy('genero')
								->take(10)
								->get(['genero']);		

		$filtro_material = Item::where('agrup', $agrupamento)
								->where('material','<>','')							
								->groupBy('material')
								->orderBy('material')
								->take(10)
								->get(['material']);	

		$filtro_idade = Item::where('agrup', $agrupamento)
								->where('idade','<>','')							
								->groupBy('idade')
								->orderBy('idade')
								->take(10)
								->get(['idade']);		

		$array_ano = explode(',', $request["anomod"]);

		$filtro_clas = Item::where('agrup', $agrupamento)
								//->where('anomod', $request["anomod"])
								->whereIn('anomod', $array_ano)
								->where('clasmod','<>','')							
								->groupBy('codclasmod','clasmod')
								->orderBy('clasmod')
								->take(10)
								->get(['codclasmod','clasmod']);	
		

		$filtro_fixacao = Item::where('agrup', $agrupamento)
								->where('fixacao','<>','')							
								->groupBy('fixacao')
								->orderBy('fixacao')
								->take(10)
								->get(['fixacao']);		


		
	$filtro_fornecedores = Item::where('agrup', $agrupamento)
								->where('fornecedor','<>','')							
							->groupBy('fornecedor')
							->orderBy('fornecedor')
								->take(10)
   							->get(['fornecedor']);
//			$filtro_fornecedores = \DB::select( "Select *
//			from
//			(select case when nome is null then razao else nome end as 'fornecedor' , razao
//			from itens 
//			left join addressbook ad on ad.id = itens.codfornecedor where agrup = '$agrupamento' group by nome, razao) as base
//			where fornecedor <> ''
//			and fornecedor is not null " );

		$filtro_status = Item::where('agrup', $agrupamento)
								->groupBy('codstatusatual')
								->orderBy('codstatusatual')
								->take(10)
								->get(['codstatusatual']);										
//		$agrupamentos = \DB::select("select codagrup, agrup, grife from itens where agrup <> '.' group by codagrup, agrup, grife  order by agrup");							

		//return response()->json($modelos);
		if (count($modelos) > 0) {
			return view('produtos.painel.modelos')->with('modelos', $modelos)
												->with('filtro_ano', $filtro_ano)
												->with('filtro_genero', $filtro_genero)
												->with('filtro_material', $filtro_material)
												->with('filtro_idade', $filtro_idade)
												->with('filtro_clas', $filtro_clas)
												->with('filtro_fixacao', $filtro_fixacao)
												->with('filtro_fornecedores', $filtro_fornecedores)
												->with('filtro_status', $filtro_status)
												->with('totais', $totais)
												->with('filtro_colecao', $filtro_colecao);
		} else {
			return view('produtos.painel.modelos')->with('modelos', array())
												->with('filtro_ano', $filtro_ano)
												->with('filtro_genero', $filtro_genero)
												->with('filtro_material', $filtro_material)
												->with('filtro_idade', $filtro_idade)
												->with('filtro_clas', $filtro_clas)
												->with('filtro_fixacao', $filtro_fixacao)
												->with('filtro_fornecedores', $filtro_fornecedores)
												->with('filtro_status', $filtro_status)
												->with('totais', $totais)
												->with('filtro_colecao', $filtro_colecao);
		}
	}

	


}
