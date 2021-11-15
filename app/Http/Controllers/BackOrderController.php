<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BackOrderController extends Controller
{
  

	public function atendeOrcamento(Request $request) {
		$id_rep = \Auth::user()->id_addressbook;

		$query = \DB::select("insert into atende_bo (id_rep, id_cliente, acao, novo_endereco) values ($id_rep, $request->id_cliente, '$request->acao', '$request->novo_endereco')");

		return redirect("/backorder");
	}


	public function listaBackOrder(Request $request) {

		$sql = '';
		$usuario = \Auth::user();

		if ($request->id_cliente) {

			//$sql  = ' and id_cliente = $request->id_cliente '; 


		}

		if ($usuario->id_perfil == 4) {
			$representante = \Session::get('representantes');
			$representantes = " id_representante IN ($representante) ";
			$rep1 = " id_rep in ($representante)  ";

		} elseif ($usuario->id_perfil == 6) {

			$representantes = " id_representante IN (";

			$listarep = \App\AddressBook::where('id_supervisor', $usuario->id_addressbook)->get();
			foreach ($listarep as $rep) {
				$representantes .= $rep->id . ',';

			}
			$representantes = substr($representantes,0, -1);
			$representantes .= ")";
			
			
			
			
			$rep1 = " id_rep in (";

			$listarep = \App\AddressBook::where('id_supervisor', $usuario->id_addressbook)->get();
			foreach ($listarep as $rep) {
				$rep1 .= $rep->id . ',';

			}
			$rep1 = substr($rep1,0, -1);
			$rep1 .= ")";

			

		} elseif ($usuario->id_perfil == 5) {
		

			$representantes = " id_representante IN (";

			$listarep = \App\AddressBook::where('id_diretor', $usuario->id_addressbook)->get();
			foreach ($listarep as $rep) {
				$representantes .= $rep->id . ',';

			}
			$representantes = substr($representantes,0, -1);
			$representantes .= ")";
			
			
			
			
			$rep1 = " id_rep in (";

			$listarep = \App\AddressBook::where('id_diretor', $usuario->id_addressbook)->get();
			foreach ($listarep as $rep) {
				$rep1 .= $rep->id . ',';

			}
			$rep1 = substr($rep1,0, -1);
			$rep1 .= ")";

			

		}
		
		
		
		else {

			$representantes = ' 123 ';
		}

		
		/**dd($representantes);**/
/*
		$grifes = \DB::select("select codgrife, grife, sum(qtd_aberto) as pecas, sum(total) as valor
from orcamentos_anal
left join itens on id_item = itens.id
where $representantes
group by codgrife, grife");
/*/
		$grifes = \DB::select("
		select codgrife, grife, 
sum(pecas) pecas, sum(valor) valor, sum(atende) atende, sum(atende_vlr) atende_valor 
from (

select *,
case when disp > pecas then pecas else disp end as atende,
case when disp > pecas then pecas*(valor/pecas) else disp*(valor/pecas) end as atende_vlr from (

select * from (
select codgrife, grife, addressbook.id, fantasia, grupo, subgrupo, id_item, sum(qtd_aberto) as pecas, sum(total) as valor, financeiro

from orcamentos_anal
left join itens on id_item = itens.id
left join addressbook on id_cliente = addressbook.id

where $representantes


group by codgrife, grife,addressbook.id, fantasia,  grupo, subgrupo, id_item, financeiro
limit 10
) as base

left join (
select curto, case when disponivel < 0 then 0 else disponivel end as disp
from saldos
) as saldos
on saldos.curto = base.id_item

) as fim
) as fim1

group by codgrife, grife limit 10");



		if (isset($request->grife)) {

			$grife = " and codgrife = '$request->grife' ";

		} else {

			$grife = " ";

		}

		if (isset($request->finan)) {

			$finan = " and financeiro = '$request->finan' ";

		} else {

			$finan = " ";

		}
		

		$clientes = array();


 		$clientes = \DB::select("

select distinct fim4.* from (
select * from (
select distinct cli, rep id_representante from carteira 
) as fim
where $representantes ) as cli

join (
select acao, diretoria, id_cliente id, grupo, subgrupo, fantasia, uf, municipio, bairro, ddd1, tel1, email1, financeiro, sum(aberto) aberto, sum(atende) atende from (

	select acao, diretoria, id_cliente, grupo, subgrupo, fantasia, uf, municipio, bairro, ddd1, tel1, email1, financeiro, codgrife, secundario, aberto, 
    case when aberto > disp then disp else aberto end as atende from (
		select acao, diretoria, id_cliente, grupo, subgrupo, fantasia, uf, municipio, bairro, ddd1, tel1, email1, financeiro, codgrife, secundario, sum(qtde) aberto, max(disp) disp 
        from (
			
            select codgrife, oa.* , grupo, subgrupo, fantasia, uf, municipio, bairro, ddd1, tel1, email1, financeiro, itens.secundario, 
            case when disp_vendas < 0 then 0 else disp_vendas end disp , oa.valor/oa.qtde as unit,
            	(select acao from atende_bo where id_cliente = oa.id_cliente limit 1) as acao,
            
            case when codgrife in ('mm','gu','st') then 'FASHION' 
            when codgrife not in ('AH','AT','BG','EV','NG','JO','HI','SP','TC','JM','PU','GO') then 'LUXO'
            else '' end as diretoria 
            
			from vendas_jde oa
				left join itens on itens.id = oa.id_item
				left join saldos on saldos.curto = itens.id
				left join addressbook ab on ab.id = oa.id_cliente
				-- left join atende_bo on atende_bo.id_cliente = ab.id
            where ab.financeiro not in ('ju') and prox_status = '515' -- and atende_bo.acao is null
			and $rep1


-- where $representantes $grife $finan $sql and id_cliente = '183569'
		
        ) as fim
		group by acao, diretoria, id_cliente, grupo, subgrupo, fantasia, uf, municipio, bairro, ddd1, tel1, email1, financeiro, codgrife, secundario
	) as fim2
) as fim3 
group by acao, diretoria, id_cliente, grupo, subgrupo, fantasia, uf, municipio, bairro, ddd1, tel1, email1, financeiro
) as fim4 
on fim4.id = cli.cli

order by aberto desc


 ");
		
		
		
		
		

		$financeiro = \DB::select("
			select 
case when financeiro = '' then 'ADIMPLENTE' else 'INADIMPLENTE ' end as status, financeiro,
sum(pecas) pecas, sum(valor) valor, sum(atende) atende, sum(atende_vlr) atende_valor from (

select *,
case when disp > pecas then pecas else disp end as atende,
case when disp > pecas then pecas*(valor/pecas) else disp*(valor/pecas) end as atende_vlr from (

select * from (
select codgrife, grife, addressbook.id, fantasia, grupo, subgrupo, id_item, sum(qtd_aberto) as pecas, sum(total) as valor, financeiro
from orcamentos_anal
left join itens on id_item = itens.id
left join addressbook on id_cliente = addressbook.id

where $representantes 

group by codgrife, grife,addressbook.id, fantasia,  grupo, subgrupo, id_item, financeiro
) as base

left join (
select curto, case when disponivel < 0 then 0 else disponivel end as disp
from saldos
) as saldos
on saldos.curto = base.id_item

) as fim
) as fim1

group by financeiro
");


		return view('comercial.backorder.lista')->with('clientes', $clientes)->with('grifes', $grifes)->with('financeiro', $financeiro);



	}


	public function detalhesCliente(Request $request, $id_cliente) {

		
		$usuario = \Auth::user();

		if ($usuario->id_perfil == 4) {

			$representantes = " id_representante = $usuario->id_addressbook ";
			$rep = " id_rep = $usuario->id_addressbook ";

		} elseif ($usuario->id_perfil == 6) {

			$representantes = " id_representante IN (";

			$listarep = \App\AddressBook::where('id_supervisor', $usuario->id_addressbook)->get();
			foreach ($listarep as $rep) {
				$representantes .= $rep->id . ',';

			}
			$representantes = substr($representantes,0, -1);
			$representantes .= ")";

		} else {

			$representantes = '92478';
		}

		$cliente = \App\AddressBook::find($id_cliente);

		$itens = \DB::select("
		
Select*
from(select nome, codgrife, secundario, diretoria, id_cliente id, id_rep, grupo, subgrupo, fantasia, uf, municipio, bairro, ddd1, tel1, email1, financeiro, sum(aberto) aberto, sum(atende) atende, sum(atende)*sum(valor) valor from (

	select nome, diretoria, id_cliente, id_rep, grupo, subgrupo, fantasia, uf, municipio, bairro, ddd1, tel1, email1, financeiro, codgrife, secundario, aberto, valor,
    case when aberto > disp then disp else aberto end as atende from (
		select nome, diretoria, id_cliente, id_rep, grupo, subgrupo, fantasia, uf, municipio, bairro, ddd1, tel1, email1, financeiro, codgrife, secundario, sum(qtde) aberto, max(disp) disp, unit as valor
        from (
			
            select abr.nome, codgrife, oa.* , ab.grupo, ab.subgrupo, ab.fantasia, ab.uf, ab.municipio, ab.bairro, ab.ddd1, ab.tel1, ab.email1, ab.financeiro, itens.secundario, 
            case when disp_vendas < 0 then 0 else disp_vendas end disp , oa.valor/oa.qtde as unit,
            
            case when codgrife in ('mm','gu','st') then 'FASHION' 
            when codgrife not in ('AH','AT','BG','EV','NG','JO','HI','SP','TC','JM','PU','GO') then 'LUXO'
            else '' end as diretoria 
            
			from vendas_jde oa
				left join itens on itens.id = oa.id_item
				left join saldos on saldos.curto = itens.id
				left join addressbook ab on ab.id = oa.id_cliente
               --  left join atende_bo on atende_bo.id_cliente = ab.id
				left join addressbook abr on abr.id = oa.id_rep
            -- where oa.id_cliente = '83306'
             where ab.financeiro not in ('ju') and prox_status = '515' and oa.id_cliente = $id_cliente 
			 and '$request->fornec' <> 0
			 and $rep
		
        ) as fim
		group by nome, diretoria, id_cliente, id_rep, grupo, subgrupo, fantasia, uf, municipio, bairro, ddd1, tel1, email1, financeiro, codgrife, secundario, unit
	) as fim2
) as fim3 
group by nome, codgrife, secundario, diretoria, id_cliente, id_rep, grupo, subgrupo, fantasia, uf, municipio, bairro, ddd1, tel1, email1, financeiro


order by codgrife, secundario) as base
-- where atende > 0

");


// 	$itens = \DB::select("select itens.secundario, itens.modelo, sum(qtd_aberto) as pecas, sum(unitario) as valor, dt_pedido
// from orcamentos_anal
// left join itens on id_item = itens.id
// left join addressbook on id_cliente = addressbook.id
// where id_cliente = $id_cliente and id_representante = $id_representante
// group by secundario, modelo, dt_pedido
// order by dt_pedido");



		return view('comercial.backorder.detalhes')->with('itens', $itens)->with('cliente', $cliente);

	}



	public function detalhesGrife($grife) {

		
		$usuario = \Auth::user();

		if ($usuario->id_perfil == 4) {

			$representantes = " and id_representante = $usuario->id_addressbook ";
			$rep = " and id_rep = $usuario->id_addressbook ";

		} elseif ($usuario->id_perfil == 6) {

			$representantes = " and id_representante IN (";

			$listarep = \App\AddressBook::where('id_supervisor', $usuario->id_addressbook)->get();
			foreach ($listarep as $rep) {
				$representantes .= $rep->id . ',';

			}
			$representantes = substr($representantes,0, -1);
			$representantes .= ")";

		} else {

			$representantes = '92478';
		}


		$itens = \DB::select("

select   secundario, colmod,
sum(pecas) pecas, sum(valor) valor, 
sum(atende_disp) atende_disp, sum(atende_transito) atende_transito, sum(atende_prod) atende_prod 
	from (

select *, 
case when (pecas-atende_disp-atende_transito) > producao then producao 
else (pecas-atende_disp-atende_transito) end as atende_prod 
	from (

select *, 
case when disp > pecas then pecas else disp end as atende_disp,

case when pecas-disp > 0 and pecas-disp > transito then transito 
	 when pecas-disp < 0 then 0 else pecas-disp end as atende_transito 


	from (

select * from (
select secundario, id_item, colmod, sum(qtd_aberto) as pecas, sum(total) as valor
from orcamentos_anal
left join itens on id_item = itens.id


where codgrife = '$grife'  $rep
group by id_item, secundario, colmod
) as base

left join (
	select curto, 
	case when disponivel < 0 then 0 else disponivel end as disp,
	case when (conf_montado+em_beneficiamento+cet+saldo_parte+qtd_rot_receb) < 0 then 0 else (conf_montado+em_beneficiamento+cet+saldo_parte+qtd_rot_receb) end as transito
	from saldos
) as saldos
on saldos.curto = base.id_item

left join (
	select id item, sum(producao+estoque) producao from producoes_sint group by id
	) as producoes
on producoes.item = base.id_item

) as fim
) as fim2
) as fim3
group by secundario, colmod order by pecas desc 


");


// 		$itens = \DB::select("select itens.secundario, itens.modelo, sum(qtd_aberto) as pecas, sum(unitario) as valor, dt_pedido
// from orcamentos_anal
// left join itens on id_item = itens.id
// left join addressbook on id_cliente = addressbook.id
// where id_cliente = $id_cliente and id_representante = $id_representante
// group by secundario, modelo, dt_pedido
// order by dt_pedido");



		return view('comercial.backorder.detalhes_grife')->with('itens', $itens);

	}


}
