<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Titulo;



class TituloController extends Controller
{

	public function listaTitulos(Request $request) {

        $sql = '';
        $sql2 = '';

		if (isset($request->id_cliente)) {
			$sql .= " and dup.id_cliente = '$request->id_cliente'";
		}


		if (isset($request->status)) {
			if ($request->status == 'PAGO') {
				$sql2 .= " situacao = 'Paga' and ";
            } 
            else if ($request->status == 'ABERTO'){
				$sql2 .= " situacao = 'Aberta' and ";				
            }
            else if ($request->status == 'VENCIDO'){
				$sql2 .= " situacao = 'Vencida' and ";				
			}
		}

		if (isset($request->titulo)) {
			$sql .= ' and dup.titulo = '.$request->titulo;
		}

		if (isset($request->busca)) {
			$sql .= " and (razao like '$request->busca%' or addressbook.id = '$request->busca' )";
		}

		if (isset($request->venc_inicio) && isset($request->venc_fim)) {
			$sql .= " and (dt_vencimento between '$request->venc_inicio' and '$request->venc_fim' )";
		}

		if (isset($request->pagto_inicio) && isset($request->pagto_fim)) {
			$sql .= " and (dt_pagto between '$request->pagto_inicio' and '$request->pagto_fim' )";
		}

		$representante = \Session::get('representantes');
//		$id_representante = \Session::get('representantes');
//		$id_representante = \Auth::user()->id_addressbook;
		//$titulos = Titulo::where('tipo','RI')->take(10)->get();

		if ($sql <> '') {
			// $titulos = \DB::select("select titulo,status,  parcela, dt_emissao, dt_vencimento, id_cliente, razao, valor_parcela,
			// 					case 
			// 						when trim(titulos.situacao) = 'APROVADO' then 'Em Aberto' 
			// 						when trim(titulos.situacao) = 'PAGO' then 'Pago' 
			// 						else titulos.situacao
			// 					end as situacao
			// 				from titulos 
			// 				left join carteira on id_cliente = cli
			// 				left join addressbook on id_cliente = addressbook.id
			// 				where titulos.tipo = 'RI' and rep = $id_representante $sql
			// 				group by titulo, status, titulos.situacao, parcela, dt_emissao, dt_vencimento, id_cliente, razao, valor_parcela");

			// atual

			$titulos = \DB::select("
select *
from (
	select id_rep, fim.tipo,titulo,parcela,status,fim.situacao,dt_emissao,id_cliente,fim.razao, dt_vencimento, dt_pagto, valor_parcela, (((valor_parcela - ((valor_titulos-valor_pedido)/parcelas)) * proporcao)/100) as valor_parcela_rep, comissao, ab.tipo_comissao, acordo,
	id_conta, cod_branco, agencia, conta, conta_dv, carteira, correspondente, banco, nosso_numero
from (
	select *, (valor_rep/valor_pedido)*100 as proporcao, 
		(select sum(valor_parcela) from titulos where base.titulo = titulos.titulo and titulos.tipo = base.tipo) as valor_titulos,
		(select count(*) from titulos where base.titulo = titulos.titulo and titulos.tipo = base.tipo) as parcelas
	from (
		select dup.tipo, dup.titulo,dup.parcela, id_rep,
			dup.status,   
			case 
				when trim(dup.status) = 'P' then 'Paga' 
				when dt_vencimento < date_sub(now(), interval 5 day) then 'Vencida'
				else 'Aberta' 
			end as situacao,
			dup.dt_emissao, dt_vencimento, dt_pagto, dup.id_cliente, razao, valor_parcela, sum(ped.valor) as valor_rep,
			(select sum(valor) from pedidos_jde ped2 where ped2.pedido = ped.pedido and ped2.tipo = 'SO' and ped2.ult_status not in ('980', '984')) as valor_pedido
			,(select concat(ano,' ', periodo) from comissoes com where com.fatura = dup.titulo and com.parcela = dup.parcela and com.id_rep = ped.id_rep limit 1) as comissao
			,(select acordo from pagamentos pag where dup.titulo = pag.titulo and dup.parcela = pag.parcela and dup.tipo = pag.tipo and dup.id_cliente = pag.id_cliente limit 1) as acordo,
			conta.id as id_conta, conta.id_banco as cod_branco, agencia, conta, conta_dv, carteira, correspondente, banco, nosso_numero

		from titulos dup
		left join pedidos_jde ped on dup.ped_original = ped.pedido and dup.tipo_original = ped.tipo and ult_status not in ('980', '984')
        left join contas_bancarias conta on id_conta_bancaria = conta.id and conta.status = 1
        left join boletos on dup.titulo = boletos.titulo and dup.tipo = boletos.tipo /*and dup.companhia = boletos.companhia*/ and dup.parcela = boletos.parcela
		-- left join carteira on dup.id_cliente = cli
		left join addressbook on dup.id_cliente = addressbook.id
		where dup.tipo = 'RI'  and acordo = '' and id_rep in ($representante) and valor_parcela > 1 $sql
		group by id_rep, dup.titulo, dup.status, dup.situacao, dup.parcela, dt_emissao, dt_vencimento, dt_pagto, id_cliente, razao, valor_parcela, ped.pedido, dup.tipo, 		comissao, conta.id, conta.id_banco, agencia, conta, conta_dv, carteira, correspondente, banco, nosso_numero
	) as base
) as fim
left join addressbook ab on ab.id = id_rep
) as fim
where $sql2 (acordo <> 'AC' or acordo is null)");
						

// 			$query_nova = "select base.*
// 		from (
// 	select 
//         ac1.id_acordo, 
//     	pag.id_cliente,
//         pag.titulo,
//         pag.parcela,
//         pag.tipo,
//         dup.dt_vencimento,
//         pag.dt_contabil as dt_pagamento,
//         dup.valor_parcela,
//         ((ac1.total-ac1.bruto) / ac1.total ) *100 as perc_juros,
//   		(ac1.total - ac1.bruto) / ac1.parcelas as valor_juros_parcela,
//         pag.valor * -1 as valor_pago, 
//         ac1.parcelas, ac1.bruto, ac1.multa, ac1.juros, ac1.desconto, ac1.total

//     from pagamentos pag 
//     left join acordos ac1 on ac1.titulo = pag.titulo and pag.id_cliente = ac1.id_cliente
//     left join acordos_parcelas ac2 on ac1.id_acordo = ac2.id_acordo and ac2.parcela = pag.parcela and pag.id_cliente = ac2.id_cliente
//     left join titulos dup on pag.titulo = dup.titulo and pag.parcela = dup.parcela and pag.tipo = dup.tipo 
//     where 
//         pag.tipo = 'AC'
//         and pag.dt_contabil between '2020-06-01' and '2020-06-30'
// 		and ac1.id_acordo = 901140
// ) as base";

// 			$titulos = \DB::select("select id_rep, fim.tipo,titulo,parcela,status,fim.situacao,dt_emissao,id_cliente,fim.razao, dt_vencimento, dt_pagto, valor_parcela, (((valor_parcela - ((valor_titulos-valor_pedido)/parcelas)) * proporcao)/100) as valor_parcela_rep2, comissao, ab.tipo_comissao, 		
// 	acordo,
//     valor_pago,
//     valor_titulos,
//     valor_pedido,
// 	(valor_titulos-valor_pedido) as valor_imposto, 
// 	(((valor_titulos-valor_pedido) / valor_titulos) * 100) as perc_imposto,
//     (valor_pago * (((valor_titulos-valor_pedido) / valor_titulos) * 100)) / 100 as pago_imposto,
//     valor_pago - ((valor_pago * (((valor_titulos-valor_pedido) / valor_titulos) * 100)) / 100) as pago_sem_imposto,
//     ((valor_pago - ((valor_pago * (((valor_titulos-valor_pedido) / valor_titulos) * 100)) / 100)) * proporcao) / 100 as valor_parcela_rep
// from (
//     select *, (valor_rep/valor_pedido)*100 as proporcao, 
//         (select sum(valor_parcela) from titulos where base.titulo = titulos.titulo and titulos.tipo = base.tipo) as valor_titulos,
//         (select count(*) from titulos where base.titulo = titulos.titulo and titulos.tipo = base.tipo) as parcelas
//     from (
//         select
//             pag.titulo,
//             pag.parcela,
//             pag.tipo,
//             pag.id_cliente,
//             ped.id_rep,
//             dup.status,   
//             pag.acordo,
//             case 
//                 when trim(dup.status) = 'P' then 'Paga' 
//                 when dup.dt_vencimento < date_sub(now(), interval 5 day) then 'Vencida'
//                 else 'Aberta' end as situacao,
//             dup.dt_emissao, dt_vencimento, dt_pagto,  razao, valor_parcela, sum(ped.valor) as valor_rep, pag.valor * -1 as valor_pago,
//             (select sum(valor) from pedidos_jde ped2 where ped2.pedido = ped.pedido and ped2.tipo = 'SO' and ped2.ult_status not in ('980', '984')) as valor_pedido
//             ,(select concat(ano,' ', periodo) from comissoes com where com.fatura = dup.titulo and com.parcela = dup.parcela and com.id_rep = ped.id_rep limit 1) as comissao
//         from pagamentos pag
//         left join titulos dup on pag.titulo = dup.titulo and pag.tipo = dup.tipo and pag.parcela = dup.parcela
//         left join pedidos_jde ped on dup.ped_original = ped.pedido and dup.tipo_original = ped.tipo and ult_status not in ('980', '984')
//         left join addressbook cli on dup.id_cliente = cli.id

//         where 
//             pag.acordo <> 'AC'
//             and dup.acordo <> 'AC'
//             and dup.valor_parcela > 1
//             -- and pag.dt_contabil between '2020-06-01' and '2020-06-30'
//             and pag.tipo = 'RI'
//             and ped.id_rep in ($representante)
//             $sql

//         group by ped.id_rep, pag.titulo, dup.status, dup.situacao, pag.parcela, dup.dt_emissao, dup.dt_vencimento, dup.dt_pagto, pag.id_cliente, cli.razao, dup.valor_parcela, ped.pedido, dup.tipo, comissao, pag.acordo, pag.valor
//     ) as base
// ) as fim
// left join addressbook ab on ab.id = id_rep");

		} else {
			$titulos = array();
		}

		return view('financeiro.lista')->with('titulos', $titulos);

	}


	public function detalhesTitulo($id) {

		$dados = explode('_', $id);
		$titulo = $dados[0];
		$tipo = $dados[1];
		$parcela = $dados[2];

		$titulo = \DB::select("select * from titulos where titulo = $titulo and tipo = '$tipo' and parcela = '$parcela'");


		return view('financeiro.detalhes1')->with('titulo', $titulo);


	}

}
