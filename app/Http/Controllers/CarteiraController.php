<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CarteiraController extends Controller
{
  

	public function atualizaFidelizados() {

		$reps = \DB::select("select rep from carteira group by rep");

		$query = \DB::select("truncate table fidelizados");


		foreach ($reps as $rep) {


			$fidelizados = \DB::select("select coddir, codsuper, cli, grife,	sit_cml_cli,	sit_cml_grife,	sit_fin,	
	ult_venda_cli,	ult_venda_grife,  qtde_ult_venda_cli,  qtde_ult_venda_grife, qtde_6meses,	qtde_12meses,	rep,	representante, regiao
	from (

	select coddir, codsuper, cli, razao,	fantasia,	grupo,	subgrupo,	uf,	municipio,	regiao,	endereco,	bairro,	grife,	sit_cml_cli,	sit_cml_grife,	sit_fin,	
	ult_venda_cli,	ult_venda_grife, 

		ifnull((select sum(qtde) 
		from vendas_jde vds left join itens on itens.id = vds.id_item 
	    where ult_status not in ('980','984') and vds.id_cliente = fim.cli 
	    and vds.dt_venda = fim.ult_venda_cli),0) as qtde_ult_venda_cli,
	    
	    ifnull((select sum(qtde) 
		from vendas_jde vds left join itens on itens.id = vds.id_item 
	    where ult_status not in ('980','984') and itens.codgrife = fim.grife and vds.id_cliente = fim.cli 
	    and vds.dt_venda = fim.ult_venda_grife),0) as qtde_ult_venda_grife,
	    
	    qtde_6meses,	qtde_12meses,	rep,	representante
	    
	from (

		select cli, codsuper, coddir, abc.razao, abc.fantasia, abc.grupo, abc.subgrupo, abc.uf, abc.municipio, regiao,  abc.endereco, abc.bairro, 
	    grife, abc.situacao sit_cml_cli, carteira.situacao sit_cml_grife,  abc.financeiro as sit_fin, 
		(select max(dt_venda) from vendas_jde vds left join itens on itens.id = vds.id_item where ult_status not in ('980','984') and vds.id_cliente = carteira.cli) as ult_venda_cli,
	    (select max(dt_venda) from vendas_jde vds left join itens on itens.id = vds.id_item where ult_status not in ('980','984') and itens.codgrife = carteira.grife and vds.id_cliente = carteira.cli) as ult_venda_grife,
	    ifnull((select sum(qtde) from vendas_jde vds left join itens on itens.id = vds.id_item where ult_status not in ('980','984') and itens.codgrife = carteira.grife and vds.id_cliente = carteira.cli and dt_venda >= CURDATE()-INTERVAL 180 DAY),0) qtde_6meses,
	    ifnull((select sum(qtde) from vendas_jde vds left join itens on itens.id = vds.id_item where ult_status not in ('980','984') and itens.codgrife = carteira.grife and vds.id_cliente = carteira.cli and dt_venda >= CURDATE()-INTERVAL 365 DAY),0) qtde_12meses,
	    rep, case when abr.nome = '' then abr.fantasia else abr.nome end as representante
	    
		from carteira 

		left join addressbook abc on abc.id = carteira.cli
	    left join addressbook abr on abr.id = carteira.rep
	    where grife in  ('AH','AT','BG','EV','HI','JO','SP','TC') 
	    and rep = $rep->rep
		 -- and cli = '18345'
	) as fim
	) as fim1
	order by cli, grife");

			if ($fidelizados) {

				$insert = "INSERT INTO `fidelizados`(`id_diretor`, `id_supervisor`, `id_rep`, `id_cliente`, `grife`, `situacao_cli`, `situacao_grife`, `situacao_fin`, `ult_venda_cli`, `ult_venda_grife`, `qtde_ult_venda_cli`, `qtde_ult_venda_grife`, `qtde_6meses`, `qtde_12meses`, `regiao`) VALUES ";

				foreach($fidelizados as $fidelizado) {

					if ($fidelizado->ult_venda_cli == '') {
						$ult_venda_cli = 'NULL';
					} else {
						$ult_venda_cli = "'".$fidelizado->ult_venda_cli."'" ;					
					}

					if ($fidelizado->ult_venda_grife == '') {
						$ult_venda_grife = 'NULL';
					} else {
						$ult_venda_grife = "'".$fidelizado->ult_venda_grife."'" ;					
					}


					$insert .= "( '$fidelizado->coddir', '$fidelizado->codsuper', '$fidelizado->rep', '$fidelizado->cli', '$fidelizado->grife', '$fidelizado->sit_cml_cli', '$fidelizado->sit_cml_grife', '$fidelizado->sit_fin', $ult_venda_cli, $ult_venda_grife, '$fidelizado->qtde_ult_venda_cli', '$fidelizado->qtde_ult_venda_grife', '$fidelizado->qtde_6meses', '$fidelizado->qtde_12meses', '$fidelizado->regiao' ),";


				}

				$insert = substr($insert,0,-1);

				$query = \DB::select($insert);
			}
		}

	}

	public function atualizaSituacaoCliente() {

        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);

		$clientes = \DB::select("select  cli, vdas_6m, vdas_12m, vdas_18m,
		case when (vdas_6m > 10 and vdas_12m = vdas_6m and vdas_6m > 10 and vdas_18m = vdas_6m) then 'novo'
		when vdas_6m > 10 and vdas_18m > 10 then 'fidelizado'
		when vdas_12m > 10 then 'nao_fidelizado'
		when vdas_18m > 10 then 'a_recuperar' else '' end as status_cml
		from (

		select cli ,
		ifnull((select sum(qtde) from vendas_jde as vendas left join itens on vendas.id_item = itens.id where vendas.id_cliente = cart.cli and dt_venda >= CURDATE()-INTERVAL 180 DAY),0) as vdas_6m,
		ifnull((select sum(qtde) from vendas_jde as vendas left join itens on vendas.id_item = itens.id where vendas.id_cliente = cart.cli and dt_venda >= CURDATE()-INTERVAL 360 DAY),0) as vdas_12m,
		ifnull((select sum(qtde) from vendas_jde as vendas left join itens on vendas.id_item = itens.id where vendas.id_cliente = cart.cli and dt_venda >= CURDATE()-INTERVAL 540 DAY),0) as vdas_18m
		

		from carteira cart
		-- where cli = 95406
			group by cli
		) as fim1");

		foreach ($clientes as $cliente) {

			$cli = \App\AddressBook::find($cliente->cli);

			if ($cli) {

				$atualiza = \DB::select("update addressbook set situacao = '$cliente->status_cml' where id = '$cliente->cli'");


			}


			

		}



	}  
	public function atualizaSituacaoGrife() {

        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);

		$clientes = \DB::select("select cli from carteira group by cli");

		foreach ($clientes as $cliente) {

			$grifes = \DB::select("select grife from carteira where cli = $cliente->cli group by grife");

			foreach ($grifes as $grife) {

				$situacao = \DB::select("
        select regiao, rep, cli, grife, codsuper, vdas_6m, vdas_12m, vdas_18m,
		case when (vdas_6m > 10 and vdas_12m = vdas_6m and vdas_6m > 10 and vdas_18m = vdas_6m) then 'novo'
		when vdas_6m > 10 and vdas_18m > 10 then 'fidelizado'
		when vdas_12m > 10 then 'nao_fidelizado'
		when vdas_18m > 10 then 'a_recuperar' else '' end as status_cml
		from (

		select * ,
		ifnull((select sum(qtde) from vendas_jde as vendas left join itens on vendas.id_item = itens.id where vendas.id_cliente = cart.cli and cart.grife = codgrife and dt_venda >= CURDATE()-INTERVAL 180 DAY),0) as vdas_6m,
		ifnull((select sum(qtde) from vendas_jde as vendas left join itens on vendas.id_item = itens.id where vendas.id_cliente = cart.cli and cart.grife = codgrife and dt_venda >= CURDATE()-INTERVAL 360 DAY),0) as vdas_12m,
		ifnull((select sum(qtde) from vendas_jde as vendas left join itens on vendas.id_item = itens.id where vendas.id_cliente = cart.cli and cart.grife = codgrife and dt_venda >= CURDATE()-INTERVAL 540 DAY),0) as vdas_18m
		

		from carteira cart
		 where cli = $cliente->cli and grife = '$grife->grife' 

		) as fim1");

				if ($situacao) {

					$desc_situacao = $situacao[0]->status_cml;
					$atualiza = \DB::select("update carteira set situacao = '$desc_situacao', updated_at = now() where cli = '$cliente->cli' and grife = '$grife->grife' ");


				}


			}

		}



	}  

	public function listaCarteira() {

		return view('comercial.carteira.carteira');


	}

	
	public function listaCarteira2() {

		return view('comercial.carteira.carteira2');


	}

	public function fichaCliente(Request $request) {


		$cliente = htmlentities($request->cliente);

		
		return view('comercial.carteira.ficha')->with('cliente', $cliente);

	}

	
	public function somaMedia() {

		return view('comercial.carteira.somamedia');


	}

	public function detalhesCliente(Request $request) {

		return view('comercial.carteira.cart_detcli');


	}
	
	public function cartDetcli(Request $request) {

		return view('comercial.carteira.cart_detcli');


	}
	
	public function finCli(Request $request) {

		return view('comercial.carteira.fin_cli');


	}
	
	public function finPdv(Request $request) {

		return view('comercial.carteira.fin_pdv');


	}
	
	public function fichaDet(Request $request) {

		return view('comercial.carteira.ficha_det');


	}

}

