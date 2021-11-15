<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Anexo;
use App\Cliente;
use App\Carteira;
use App\Historico;
use App\NovoCadastro;
use App\AddressBook;

class ClienteController extends Controller
{


	public function gravaMotivoNaoVenda(Request $request) {

		if (isset($request->grife)) {

			foreach ($request->grife as $grife) {

				$campo_motivo = 'motivos'.$grife;
				
				
				$motivo = $request->$campo_motivo;
				
				
				$id_usuario = \Auth::id();

				$insert = \DB::select("insert into pesquisa_naovenda (usuario, cliente, grife, motivo, atendimento, obs) 
				values ($id_usuario, '$request->cliente', '$grife', '$motivo', '$request->atendimento', '$request->obs')");
		

			}

		}
		return redirect()->back();

	}

	public function excluirHistorico($id) {

		$historico = Historico::find($id);

		if ($historico) {
			if ($historico->id_usuario == \Auth::id()) {
				$delete = \DB::select("delete from historicos where id = $id");
			}
		}

		return redirect()->back();


	}


	public function listaClientesSituacao() {

		$id_rep = \Auth::user()->id_addressbook;

		
		
		$clientes = \DB::select("select cli, ab.razao, ab.situacao
									from carteira
									left join addressbook ab on cli = ab.id

									where rep = $id_rep

									group by cli, ab.razao, ab.situacao ");

		return view('clientes.situacao')->with('clientes', $clientes);

		
	}


	public function inserirAnexo(Request $request) {

		$path = $request->file('foto')->store('clientes');

		$anexo = new Anexo();
		$anexo->id_usuario = \Auth::id();
		$anexo->tabela = 'addressbook';
		$anexo->id_tabela = $request->id_tabela;
		$anexo->tipo = 'Foto';
		$anexo->caminho = $path;
		$anexo->save();

		return redirect()->back();
	}

	public function insereVisita(Request $request) {

		$id_usuario = \Auth::id();


		$visita = \DB::select("insert into site.visitas (id_usuario, id_cliente, id_representante, dt_solicitacao, status, grife, obs) values ($id_usuario, $request->id_cliente, $request->rep,  now(), 'Solicitada', '$request->grife', '$request->motivo')");


		$historico = new \App\Historico();
		$historico->id_usuario = \Auth::id();

		$historico->tabela = 'clientes';
		$historico->id_tabela = $request->id_cliente;
		$historico->categoria = 'historico';
		$historico->historico = 'Solicitada a visita do representante '.$request->rep.' para grife '.$request->grife;
		$historico->save();


		return redirect()->back();
	}


	public function listaClientes(Request $request) {

		$representantes = \Session::get('representantes');
		$clientes = array();
		
		
		if($representantes==96395) {
		$where = "where 1=1"; } else {
		$where = "where rep IN ($representantes) and carteira.status = 1 and dt_fim >= now()";
		}
		

        $novos_cadastros = NovoCadastro::whereIn('situacao', ['Novo', 'Pendente'])->where('id_rep', \Auth::user()->id_addressbook)->get();

		if ($request->busca) {
			$clientes = \DB::select("select ab.id, ab.razao, ab.cnpj, ab.grupo, ab.subgrupo, ab.municipio, ab.uf, ab.cliente, ab.financeiro, ab.cod_cliente
				from carteira
				left join addressbook ab on cli = ab.id
				-- where rep IN ($representantes) 
				$where
				and ( ab.id = '$request->busca' or  ab.razao like '%$request->busca%' or ab.fantasia like '%$request->busca%'  or ab.grupo like '%$request->busca%' or ab.subgrupo like '%$request->busca%'  or ab.municipio like '$request->busca%'  or ab.uf = '$request->busca')
				group by  ab.id, ab.razao, ab.cnpj, ab.grupo, ab.subgrupo, ab.municipio, ab.uf
				limit 210 ");
		} else {
			$clientes = \DB::select("select ab.id, ab.razao, ab.cnpj, ab.grupo, ab.subgrupo, ab.municipio, ab.uf, ab.cliente, ab.financeiro,  ab.cod_cliente
				from carteira
				left join addressbook ab on cli = ab.id
				$where
				-- where rep IN ($representantes)
				group by  ab.id, ab.razao, ab.cnpj, ab.grupo, ab.subgrupo, ab.municipio, ab.uf
				limit 210 ");			
		}


		return view('clientes.lista')->with('clientes', $clientes)->with('novos_cadastros', $novos_cadastros);

	}

	public function detalhesCliente($id) {
		
		$id = str_replace('_subst_', '/', $id);
		
		
		$representantes = \Session::get('representantes');
		

		$tipo = 'pdv';

		// procura como subgrupo
		$subgrupo = Cliente::where('subgrupo', $id)->where('subgrupo', '<>', '.')->where('subgrupo', '<>',  '')->first();
		$grupo = Cliente::where('grupo', $id)->where('grupo', '<>', '.')->where('grupo', '<>',  '')->first();

		if ($subgrupo) {
			$cliente = $subgrupo;
			$tipo = 'grupo';
		} elseif ($grupo) {
			$cliente = $grupo;
			$tipo = 'grupo';
		} else {
			$cliente = Cliente::find($id);
			$tipo = 'pdv';
		}

		
	
			$lojas = array();
		
		// if (trim($cliente->subgrupo) <> '' and trim($cliente->subgrupo) <> '.') {
			
			// $lojas = Cliente::where('subgrupo', $cliente->subgrupo)->where('subgrupo', '<>', '.')->get();
			// $lojas  = \DB::select("
			// select *
			// from (
			// 			select distinct grupo, cliente, ab.id pdv, municipio, razao, uf, ddd1,ddd2, tel1, tel2, email1, fantasia, endereco
			// 			from carteira
			// 			left join addressbook ab on cli = ab.id
			// 			where cliente =  '$cliente->cliente'
			// 			) as fim	

			// left join (
			// 		select cod_cli cli, sum(vencido) vencido, sum(a_vencer) a_vencer from (
			// 			select cod_cli,  
			// 			case when dt_vencimento < now() then valor_em_aberto else 0 end as vencido,
			// 			case when dt_vencimento >= now() then valor_em_aberto else 0 end as a_vencer
			// 			from a_receber left join addressbook ab on ab.id = a_receber.cod_cli 
			// 			) as sele1
			// 		group by cod_cli 
			// ) as receber
			// 	on receber.cli = fim.pdv


			// left join (
			// 		select cli_jde cli, sum(valor_17) valor_17, sum(valor_18) valor_18, sum(valor_19) valor_19 , 0 as valor_20 from (
			// 			select cli_jde, sum(valor) valor_17, 0 as valor_18, 0 as valor_19 from vendas_2017 group by cli_jde
			// 			union all
			// 			select cli_jde, 0 as valor_17, sum(valor) valor_18, 0 as valor_19 from vendas_2018 group by cli_jde
			// 			union all
			// 			select cli_jde, 0 as valor_17, 0 valor_18, sum(valor) as valor_19 from vendas_12meses where ano = '2019' group by cli_jde
			// 		) as fimv
			// 		group by cli_jde
			// ) as vendas
			// on vendas.cli = fim.pdv
			//  ");

		$grifes = array();

		// $grifes = \DB::select("
		// select *
		// 	from (
		// 		select grupo, cliente from (
		// 			select grupo, cliente, ab.id pdv
		// 			from carteira
		// 			left join addressbook ab on cli = ab.id
		// 			where ab.cliente = '$cliente->cliente'
		// 			group by grupo, cliente, ab.id, municipio ) as fim
		// 		group by grupo, cliente) as fim2	

		// 		left join (
		// 	select cli, grife_jde, grife, sum(v2017) v2017, sum(v2018) v2018, sum(v2019) v2019, sum(v2020) v2020 from (
		// 		select cli, grife_jde, grife,
  //               case when ano = '2017' then qtde else 0 end as v2017,
  //               case when ano = '2018' then qtde else 0 end as v2018,
  //               case when ano = '2019' then qtde else 0 end as v2019,
  //               case when ano = '2020' then qtde else 0 end as v2020
  //               from (
                
		// 			select cliente cli, ano, grife_jde as grife,
		// 			case 
		// 			when grife_jde = 'AH' then 'ANA HICKMANN' 
		// 			when grife_jde = 'AT' then 'ATITUDE' 
		// 			when grife_jde = 'BG' then 'BULGET' 
		// 			when grife_jde = 'EV' then 'EVOKE' 
		// 			when grife_jde = 'HI' then 'HICKMANN' 
		// 			when grife_jde = 'JO' then 'JOLIE' 
		// 			when grife_jde = 'SP' then 'SPEEDO' 
		// 			when grife_jde = 'TC' then 'T-CHARGE' 
		// 			when grife_jde = 'AM' then 'ALEXANDER MCQUEEN' 
		// 			when grife_jde = 'BV' then 'BOTTEGA VENETA' 
		// 			when grife_jde = 'CT' then 'CARTIER' 
		// 			when grife_jde = 'GU' then 'GUCCI' 
		// 			when grife_jde = 'MC' then 'MCQ' 
		// 			when grife_jde = 'MM' then 'MONT BLANC' 
		// 			when grife_jde = 'SM' then 'STELLA MCCARTNEY' 
		// 			when grife_jde = 'ST' then 'SAINT LAURENT' 
		// 			else grife_jde end as grife_jde, sum(qtde) qtde 
		// 				from vendas_cml
		// 				group by cli, grife_jde, ano
		// 		) as vds1
		// 	) as vds2
  //           group by cli, grife_jde, grife
		// ) as vendas
		// on vendas.cli = fim2.cliente");

		if (\Auth::user()->id_perfil == 4) {
			$reps = "where  rep in ($representantes)";
		} else {
			$reps = "";
		}
		
		$grifes = \DB::select("select codgrife, grife, codrep, rep, situacao, 
	sum(qtde_2020) as qtde_2020, 
	sum(qtde_2019) as qtde_2019, 
    sum(qtde_2018) as qtde_2018,
    sum(valor_2020) as valor_2020,
    sum(valor_2019) as valor_2019,
    sum(valor_2018) as valor_2018
	from (
	select base.codgrife, base.grife, carteira.rep as codrep, trim(rep.nome) as rep,  carteira.situacao, vda.item, vda.dt_venda, vda.qtde, vda.valor,
		case when year(dt_venda) = '2020' then vda.qtde else 0 end as qtde_2020,
		case when year(dt_venda) = '2019' then vda.qtde else 0 end as qtde_2019,
		case when year(dt_venda) = '2018' then vda.qtde else 0 end as qtde_2018,
		case when year(dt_venda) = '2020' then vda.valor else 0 end as valor_2020,
		case when year(dt_venda) = '2019' then vda.valor else 0 end as valor_2019,
		case when year(dt_venda) = '2018' then vda.valor else 0 end as valor_2018
	from (
				
		select
			case when codigo = 'NG' then 'EV' else codigo end as codgrife,
			case when codigo = 'NG' then 'EVOKE' else valor end as grife
				from caracteristicas
				where campo = 'grife' 
	) as base
	left join carteira on base.codgrife = carteira.grife and cli = '$cliente->id'
	left join addressbook rep on carteira.rep = rep.id
	left join vendas_jde vda on carteira.cli = vda.id_cliente and carteira.grife = (select codgrife from itens where itens.id = vda.id_item) 
	$reps
) as fim
-- where codgrife in ('AH', 'AT','BG', 'EV', 'HI', 'NG', 'SP', 'TC','JO', 'JM')
group by codgrife, grife, codrep, rep, situacao
order by codgrife");		
		
		
		
		$historicos = Historico::where('tabela', 'clientes')->where('id_tabela', $cliente->id)->orderBy('created_at', 'desc')->get();

	    
		
		$financeiro = array();
		
// 		$financeiro = \DB::select("
// 			select sum(Futuro) Futuro, sum(Atual) Atual, sum(vencidott) vencidott, sum(venc_1_30) venc_1_30, sum(venc_31_60) venc_31_60, sum(venc_61_90) venc_61_90, sum(venc_91_120) venc_91_120, 
// 	sum(venc_121_150) venc_121_150, sum(venc_151_999) venc_151_999, sum(venc_999) venc_999, sum(limite) limite
// from (
// 	select 
// 		case when dt_vencimento > date(now()) then sum(valor_em_aberto) end as 'Futuro',
// 		case when dt_vencimento =  date(now()) then sum(valor_em_aberto) end as 'Atual',
// 		case when dt_vencimento <  date(now()) then sum(valor_em_aberto) end as 'vencidott',
// 		case when dt_vencimento >= date_sub(date(now()), INTERVAL 30 DAY) and dt_vencimento <=  date_sub(date(now()), INTERVAL 1 DAY) then sum(valor_em_aberto) end as 'venc_1_30',
// 		case when dt_vencimento >= date_sub(date(now()), INTERVAL 60 DAY) and dt_vencimento <=  date_sub(date(now()), INTERVAL 31 DAY) then sum(valor_em_aberto) end as 'venc_31_60',
// 		case when dt_vencimento >= date_sub(date(now()), INTERVAL 90 DAY) and dt_vencimento <=  date_sub(date(now()), INTERVAL 61 DAY) then sum(valor_em_aberto) end as 'venc_61_90', 
//         case when dt_vencimento >= date_sub(date(now()), INTERVAL 120 DAY) and dt_vencimento <=  date_sub(date(now()),INTERVAL 91 DAY) then sum(valor_em_aberto) end as 'venc_91_120',
//         case when dt_vencimento >= date_sub(date(now()), INTERVAL 150 DAY) and dt_vencimento <=  date_sub(date(now()), INTERVAL 121 DAY) then sum(valor_em_aberto) end as 'venc_121_150', 
//         case when dt_vencimento >= date_sub(date(now()), INTERVAL 999 DAY) and dt_vencimento <=  date_sub(date(now()), INTERVAL 151 DAY) then sum(valor_em_aberto) end as 'venc_151_999',
//         case when dt_vencimento <= date_sub(date(now()), INTERVAL 999 DAY) then sum(valor_em_aberto) end as 'venc_999'
// 		, sum(ifnull(limite_cred,0)) limite
		
// 	from a_receber
//     left join addressbook ab on a_receber.cod_cli = ab.id
    
// 	where cliente = '$cliente->cliente'
// 	group by dt_vencimento
// ) as base
// "
// );

		$financeiro = \DB::select("select sum(Futuro) Futuro, sum(Atual) Atual, sum(vencidott) vencidott, sum(venc_1_30) venc_1_30, sum(venc_31_60) venc_31_60, sum(venc_61_90) venc_61_90, sum(venc_91_120) venc_91_120, 
	sum(venc_121_150) venc_121_150, sum(venc_151_999) venc_151_999, sum(venc_999) venc_999, sum(limite) limite
from (
	select 
		case when dt_vencimento > date(now()) then sum(valor_aberto) end as 'Futuro',
		case when dt_vencimento =  date(now()) then sum(valor_aberto) end as 'Atual',
		case when dt_vencimento <  date(now()) then sum(valor_aberto) end as 'vencidott',
		case when dt_vencimento >= date_sub(date(now()), INTERVAL 30 DAY) and dt_vencimento <=  date_sub(date(now()), INTERVAL 1 DAY) then sum(valor_aberto) end as 'venc_1_30',
		case when dt_vencimento >= date_sub(date(now()), INTERVAL 60 DAY) and dt_vencimento <=  date_sub(date(now()), INTERVAL 31 DAY) then sum(valor_aberto) end as 'venc_31_60',
		case when dt_vencimento >= date_sub(date(now()), INTERVAL 90 DAY) and dt_vencimento <=  date_sub(date(now()), INTERVAL 61 DAY) then sum(valor_aberto) end as 'venc_61_90', 
        case when dt_vencimento >= date_sub(date(now()), INTERVAL 120 DAY) and dt_vencimento <=  date_sub(date(now()),INTERVAL 91 DAY) then sum(valor_aberto) end as 'venc_91_120',
        case when dt_vencimento >= date_sub(date(now()), INTERVAL 150 DAY) and dt_vencimento <=  date_sub(date(now()), INTERVAL 121 DAY) then sum(valor_aberto) end as 'venc_121_150', 
        case when dt_vencimento >= date_sub(date(now()), INTERVAL 999 DAY) and dt_vencimento <=  date_sub(date(now()), INTERVAL 151 DAY) then sum(valor_aberto) end as 'venc_151_999',
        case when dt_vencimento <= date_sub(date(now()), INTERVAL 999 DAY) then sum(valor_aberto) end as 'venc_999'
		, sum(ifnull(limite_cred,0)) limite
		
	from titulos
    left join addressbook ab on titulos.id_cliente = ab.id
    
	where ab.id = '$cliente->id' and titulos.tipo in ('RH', 'RI')
	group by dt_vencimento
) as base");

		$financeiro_analit = array();
	// 	$financeiro_analit = \DB::select("
	// 		select 
              
	// 		  case when dt_vencimento < now() then 'vencido' when dt_vencimento >= now() then 'a_vencer' else '' end as status_parcela,
 //              dt_vencimento, numero_nf, parcela, pedido_origem, dt_emissao_nf, valor_em_aberto
				
 //                from a_receber 
 //                left join addressbook ab on ab.id = a_receber.cod_cli
 //                where cliente = '$cliente->cliente'
                
 //                order by dt_vencimento asc

	// "
	// );
		
		
 		
		
	    $trocas = \DB::select("
		select sum(qtde) qtde, sum(itens.valortabela*qtde) valor_solic
        from trocas 
        left join addressbook ab on ab.id = trocas.id_cliente
        left join itens on itens.id = trocas.id_item
        where cliente = '$cliente->cliente' ");
			
		$trocasdet = array();
// 		$trocasdet = \DB::connection('goweb')->
// 			select("
// 		select cliente, sum(qtde_pend_devolucao) qtde_pend_devolucao, sum(valor_pend_devolucao) valor_pend_devolucao, sum(qtde_pend_interacao) qtde_pend_interacao,
// 		sum(valor_pend_interacao) valor_pend_interacao from (
// 	select cliente, status_trocas, 
// 	case when status_trocas = 'pend_devolucao'  then sum(qtde) else 0 end as qtde_pend_devolucao, 
// 	case when status_trocas = 'pend_devolucao'  then sum(valor) else 0 end as valor_pend_devolucao, 
// 	case when status_trocas = 'pend_interacao'  then sum(qtde) else 0 end as qtde_pend_interacao, 
// 	case when status_trocas = 'pend_interacao'  then sum(valor) else 0 end as valor_pend_interacao

// 	from (	
// 		select 
// 		case when subgrupo <> '' then subgrupo when grupo <> '' then grupo else concat(trim(clientes.id),' - ',trim(razao))  end as cliente, 
// 		case when id_status_caso = 135 then 'pend_interacao' when (laudo not in ('001','002','003') and tr.antecipado = 1) and ult_status not in ('999','970') then 'pend_devolucao' else '' end as status_trocas,
// 		qtde, qtde*venda valor

// 		from trocas_itens tr
// 		left join clientes on clientes.id = tr.id_cliente
// 		left join produtos on produtos.id = tr.id_produto
// 	) as fim
// 	where status_trocas in ('pend_devolucao','pend_interacao') and cliente = '$cliente->cliente'
// 	group by cliente, status_trocas
// ) as fim2
// group by cliente ");

		$orcamentos = \DB::select("select grife, sum(qtde) as qtde, sum(valor) as valor, sum(atende_qtde) as atende_qtde,sum(atende_valor) as atende_valor
from (
	select grife, item, qtde, disponivel, valor,
		case 
			when disponivel < 0 then 0 
			when qtde > disponivel then disponivel 
		else qtde end as atende_qtde,
		case 
			when disponivel < 0 then 0 
			when qtde > disponivel then disponivel * (valor/qtde)
		else qtde * (valor/qtde) end as atende_valor
	from vendas_jde
	left join saldos on saldos.curto = id_item
	left join itens on itens.id = id_item
	where ult_status = '510' and prox_status = '515' and id_cliente = $cliente->id and id_rep in ($representantes)
) as base
group by grife");

		$trocasdet = \DB::select("
		select id_cliente, sum(pend_interacao) as pend_interacao,  sum(pend_fiscal) as pend_fiscal, sum(pend_devolucao) as pend_devolucao, count(*) as trocas,sum(abertas) as abertas,sum(concluidas) as concluidas,sum(inadimplentes) as inadimplentes, ((sum(antecipados) / count(*)) *100) as perc_antecipacao
from (
	select id_cliente,
		case when (nf_origem = '' or nf_consumidor = 1 or outro_codigo = 1 or substituto = '') then 1 else 0 end as pend_interacao,
		case when (nf_origem <> '' and nf_consumidor = 0 and outro_codigo = 0 and substituto <> '') and (dt_recebida is not null or dt_recebida <> '') and (laudo = '' or laudo is null or laudo = '0')  then 1 else 0 end as pend_fiscal,
		case when  dt_recebida is null then 1 else 0 end as pend_devolucao,
		case when id_status_caso = '999' then 1 else 0 end as concluidas,
		case when id_status_caso <> '999' then 1 else 0 end as abertas,
		case when bloqueio = 'IN' then 1 else 0 end as inadimplentes,
		case when antecipado = 1 then 1 else 0 end antecipados
		
	from trocas
	where id_cliente = $cliente->id
) as base
group by id_cliente ");

	
		$trocas_vendas = \DB::select("
select (trocas / vendas) * 100 as perc_trocas_vendas
from (
	select sum(qtde) as vendas,
		(select count(*) from trocas where trocas.id_cliente = vendas_jde.id_cliente) as trocas
	from vendas_jde
	where id_cliente = $cliente->id and ult_status not in ('980', '984')
) as base");
		

		$trocas_pecas = \DB::select("
select secundario,  count(*) qtde
from trocas
where id_cliente = $cliente->id
group by secundario
order by count(*) desc
limit 5");

		$trocas_grifes = \DB::select("
select grife, count(*) as qtde
from trocas
left join itens on id_item = itens.id
where id_cliente = $cliente->id
group by grife
order by count(*) desc
limit 5");
		
		$vendas = \DB::select("select 
	pedido, 
	dt_venda, 
    ifnull(sum(qtde_vendida),0) as qtde_vendida, 
    ifnull(sum(valor_vendido),0) as valor_vendido,
    ifnull(sum(qtde_cancelada),0) as qtde_cancelada, 
    ifnull(sum(valor_cancelada),0) as valor_cancelada,
    ifnull(sum(qtde_orcamento),0) as qtde_orcamento, 
    ifnull(sum(valor_orcamento),0) as valor_orcamento,
    ifnull(sum(qtde_faturada),0) as qtde_faturada,
    ifnull(sum(valor_faturada),0) as valor_faturada
from (
	select vda.pedido, vda.dt_venda, vda.item, vda.ult_status, vda.prox_status, vda.qtde as qtde_vendida, vda.valor as valor_vendido,
		case when vda.ult_status in ('980','984') or ped.ult_status in ('980','984') then vda.qtde end as qtde_cancelada,
		case when vda.ult_status in ('980','984') or ped.ult_status in ('980','984') then vda.valor end as valor_cancelada,
		case when (vda.ult_status = '510' and vda.prox_status = '515') or ped.ult_status in ('904', '902') then vda.qtde end as qtde_orcamento,
		case when (vda.ult_status = '510' and vda.prox_status = '515') or ped.ult_status in ('904', '902')  then vda.valor end as valor_orcamento,
		case when nf.ult_status = '620' and nf.prox_status = '999' then nf.qtde end as qtde_faturada,
		case when nf.ult_status = '620' and nf.prox_status = '999' then nf.total end as valor_faturada
		
	from vendas_jde vda
    left join pedidos_jde ped on ped.ped_original = vda.pedido and ped.tipo_original = vda.tipo and ped.linha_original = vda.linha
    left join notas_jde nf on nf.ped_original = ped.pedido and nf.tipo_original = ped.tipo and nf.linha_original = ped.linha
	where vda.id_cliente = $id and vda.id_rep in ($representantes) 
	order by vda.dt_venda desc
) as base
group by pedido desc, dt_venda");
		return view('clientes.detalhes')
					->with('tipo', $tipo)
					->with('cliente', $cliente)
					->with('lojas', $lojas)
					->with('historicos', $historicos)
					->with('financeiro', $financeiro)
					->with('financeiro_analit', $financeiro_analit)
					->with('trocas', $trocas)
					->with('trocasdet', $trocasdet)
					->with('trocas_vendas', $trocas_vendas)
					->with('trocas_grifes', $trocas_grifes)
					->with('trocas_pecas', $trocas_pecas)
					->with('orcamentos', $orcamentos)
					->with('vendas', $vendas)
			
					->with('grifes', $grifes);

	}


	public function detalhesGrupo($grupo) {
		

		$tipo = 'pdv';

		// procura como subgrupo
		$subgrupo = array();
		$grupo = Cliente::where('grupo', $grupo)->first();

		if ($subgrupo) {
			$cliente = $subgrupo;
			$tipo = 'grupo';
		} elseif ($grupo) {
			$cliente = $grupo;
			$tipo = 'grupo';
		} else {
			$cliente = Cliente::find($id);
			$tipo = 'pdv';
		}

		
	
			$lojas = array();
		
		// if (trim($cliente->subgrupo) <> '' and trim($cliente->subgrupo) <> '.') {
			
			// $lojas = Cliente::where('subgrupo', $cliente->subgrupo)->where('subgrupo', '<>', '.')->get();
			// $lojas  = \DB::select("
			// select *
			// from (
			// 			select distinct grupo, cliente, ab.id pdv, municipio, razao, uf, ddd1,ddd2, tel1, tel2, email1, fantasia, endereco
			// 			from carteira
			// 			left join addressbook ab on cli = ab.id
			// 			where cliente =  '$cliente->cliente'
			// 			) as fim	

			// left join (
			// 		select cod_cli cli, sum(vencido) vencido, sum(a_vencer) a_vencer from (
			// 			select cod_cli,  
			// 			case when dt_vencimento < now() then valor_em_aberto else 0 end as vencido,
			// 			case when dt_vencimento >= now() then valor_em_aberto else 0 end as a_vencer
			// 			from a_receber left join addressbook ab on ab.id = a_receber.cod_cli 
			// 			) as sele1
			// 		group by cod_cli 
			// ) as receber
			// 	on receber.cli = fim.pdv


			// left join (
			// 		select cli_jde cli, sum(valor_17) valor_17, sum(valor_18) valor_18, sum(valor_19) valor_19 , 0 as valor_20 from (
			// 			select cli_jde, sum(valor) valor_17, 0 as valor_18, 0 as valor_19 from vendas_2017 group by cli_jde
			// 			union all
			// 			select cli_jde, 0 as valor_17, sum(valor) valor_18, 0 as valor_19 from vendas_2018 group by cli_jde
			// 			union all
			// 			select cli_jde, 0 as valor_17, 0 valor_18, sum(valor) as valor_19 from vendas_12meses where ano = '2019' group by cli_jde
			// 		) as fimv
			// 		group by cli_jde
			// ) as vendas
			// on vendas.cli = fim.pdv
			//  ");

		$grifes = array();

		// $grifes = \DB::select("
		// select *
		// 	from (
		// 		select grupo, cliente from (
		// 			select grupo, cliente, ab.id pdv
		// 			from carteira
		// 			left join addressbook ab on cli = ab.id
		// 			where ab.cliente = '$cliente->cliente'
		// 			group by grupo, cliente, ab.id, municipio ) as fim
		// 		group by grupo, cliente) as fim2	

		// 		left join (
		// 	select cli, grife_jde, grife, sum(v2017) v2017, sum(v2018) v2018, sum(v2019) v2019, sum(v2020) v2020 from (
		// 		select cli, grife_jde, grife,
  //               case when ano = '2017' then qtde else 0 end as v2017,
  //               case when ano = '2018' then qtde else 0 end as v2018,
  //               case when ano = '2019' then qtde else 0 end as v2019,
  //               case when ano = '2020' then qtde else 0 end as v2020
  //               from (
                
		// 			select cliente cli, ano, grife_jde as grife,
		// 			case 
		// 			when grife_jde = 'AH' then 'ANA HICKMANN' 
		// 			when grife_jde = 'AT' then 'ATITUDE' 
		// 			when grife_jde = 'BG' then 'BULGET' 
		// 			when grife_jde = 'EV' then 'EVOKE' 
		// 			when grife_jde = 'HI' then 'HICKMANN' 
		// 			when grife_jde = 'JO' then 'JOLIE' 
		// 			when grife_jde = 'SP' then 'SPEEDO' 
		// 			when grife_jde = 'TC' then 'T-CHARGE' 
		// 			when grife_jde = 'AM' then 'ALEXANDER MCQUEEN' 
		// 			when grife_jde = 'BV' then 'BOTTEGA VENETA' 
		// 			when grife_jde = 'CT' then 'CARTIER' 
		// 			when grife_jde = 'GU' then 'GUCCI' 
		// 			when grife_jde = 'MC' then 'MCQ' 
		// 			when grife_jde = 'MM' then 'MONT BLANC' 
		// 			when grife_jde = 'SM' then 'STELLA MCCARTNEY' 
		// 			when grife_jde = 'ST' then 'SAINT LAURENT' 
		// 			else grife_jde end as grife_jde, sum(qtde) qtde 
		// 				from vendas_cml
		// 				group by cli, grife_jde, ano
		// 		) as vds1
		// 	) as vds2
  //           group by cli, grife_jde, grife
		// ) as vendas
		// on vendas.cli = fim2.cliente");

		if (\Auth::user()->id_perfil == 4) {
			$reps = "where  grupo = '$grupo'";
		} else {
			$reps = "";
		}
		
		$grifes = \DB::select("select codgrife, grife, codrep, rep, situacao, 
	sum(qtde_2021) as qtde_2021, 
	sum(qtde_2020) as qtde_2020, 
	sum(qtde_2019) as qtde_2019, 
    sum(qtde_2018) as qtde_2018,
	sum(valor_2021) as valor_2021,
    sum(valor_2020) as valor_2020,
    sum(valor_2019) as valor_2019,
    sum(valor_2018) as valor_2018
	from (
	select base.codgrife, base.grife, carteira.rep as codrep, trim(rep.nome) as rep,  carteira.situacao, vda.item, vda.dt_venda, vda.qtde, vda.valor,
		case when year(dt_venda) = '2021' then vda.qtde else 0 end as qtde_2021,
		case when year(dt_venda) = '2020' then vda.qtde else 0 end as qtde_2020,
		case when year(dt_venda) = '2019' then vda.qtde else 0 end as qtde_2019,
		case when year(dt_venda) = '2018' then vda.qtde else 0 end as qtde_2018,
		case when year(dt_venda) = '2021' then vda.valor else 0 end as valor_2021,
		case when year(dt_venda) = '2020' then vda.valor else 0 end as valor_2020,
		case when year(dt_venda) = '2019' then vda.valor else 0 end as valor_2019,
		case when year(dt_venda) = '2018' then vda.valor else 0 end as valor_2018
	from (
				
		select
			case when codigo = 'NG' then 'EV' else codigo end as codgrife,
			case when codigo = 'NG' then 'EVOKE' else valor end as grife
				from caracteristicas
				where campo = 'grife' 
	) as base
	left join carteira on base.codgrife = carteira.grife and cli = '$cliente->id'
	left join addressbook rep on carteira.rep = rep.id
	left join vendas_jde vda on carteira.cli = vda.id_cliente and carteira.grife = (select codgrife from itens where itens.id = vda.id_item) 
	$reps
) as fim
-- where codgrife in ('AH', 'AT','BG', 'EV', 'HI', 'NG', 'SP', 'TC','JO', 'JM')
group by codgrife, grife, codrep, rep, situacao
order by codgrife");		
		
		
		
		$historicos = Historico::where('tabela', 'clientes')->where('id_tabela', $cliente->id)->orderBy('created_at', 'desc')->get();

	    
		
		$financeiro = array();
		
// 		$financeiro = \DB::select("
// 			select sum(Futuro) Futuro, sum(Atual) Atual, sum(vencidott) vencidott, sum(venc_1_30) venc_1_30, sum(venc_31_60) venc_31_60, sum(venc_61_90) venc_61_90, sum(venc_91_120) venc_91_120, 
// 	sum(venc_121_150) venc_121_150, sum(venc_151_999) venc_151_999, sum(venc_999) venc_999, sum(limite) limite
// from (
// 	select 
// 		case when dt_vencimento > date(now()) then sum(valor_em_aberto) end as 'Futuro',
// 		case when dt_vencimento =  date(now()) then sum(valor_em_aberto) end as 'Atual',
// 		case when dt_vencimento <  date(now()) then sum(valor_em_aberto) end as 'vencidott',
// 		case when dt_vencimento >= date_sub(date(now()), INTERVAL 30 DAY) and dt_vencimento <=  date_sub(date(now()), INTERVAL 1 DAY) then sum(valor_em_aberto) end as 'venc_1_30',
// 		case when dt_vencimento >= date_sub(date(now()), INTERVAL 60 DAY) and dt_vencimento <=  date_sub(date(now()), INTERVAL 31 DAY) then sum(valor_em_aberto) end as 'venc_31_60',
// 		case when dt_vencimento >= date_sub(date(now()), INTERVAL 90 DAY) and dt_vencimento <=  date_sub(date(now()), INTERVAL 61 DAY) then sum(valor_em_aberto) end as 'venc_61_90', 
//         case when dt_vencimento >= date_sub(date(now()), INTERVAL 120 DAY) and dt_vencimento <=  date_sub(date(now()),INTERVAL 91 DAY) then sum(valor_em_aberto) end as 'venc_91_120',
//         case when dt_vencimento >= date_sub(date(now()), INTERVAL 150 DAY) and dt_vencimento <=  date_sub(date(now()), INTERVAL 121 DAY) then sum(valor_em_aberto) end as 'venc_121_150', 
//         case when dt_vencimento >= date_sub(date(now()), INTERVAL 999 DAY) and dt_vencimento <=  date_sub(date(now()), INTERVAL 151 DAY) then sum(valor_em_aberto) end as 'venc_151_999',
//         case when dt_vencimento <= date_sub(date(now()), INTERVAL 999 DAY) then sum(valor_em_aberto) end as 'venc_999'
// 		, sum(ifnull(limite_cred,0)) limite
		
// 	from a_receber
//     left join addressbook ab on a_receber.cod_cli = ab.id
    
// 	where cliente = '$cliente->cliente'
// 	group by dt_vencimento
// ) as base
// "
// );

		$financeiro = \DB::select("select sum(Futuro) Futuro, sum(Atual) Atual, sum(vencidott) vencidott, sum(venc_1_30) venc_1_30, sum(venc_31_60) venc_31_60, sum(venc_61_90) venc_61_90, sum(venc_91_120) venc_91_120, 
	sum(venc_121_150) venc_121_150, sum(venc_151_999) venc_151_999, sum(venc_999) venc_999, sum(limite) limite
from (
	select 
		case when dt_vencimento > date(now()) then sum(valor_aberto) end as 'Futuro',
		case when dt_vencimento =  date(now()) then sum(valor_aberto) end as 'Atual',
		case when dt_vencimento <  date(now()) then sum(valor_aberto) end as 'vencidott',
		case when dt_vencimento >= date_sub(date(now()), INTERVAL 30 DAY) and dt_vencimento <=  date_sub(date(now()), INTERVAL 1 DAY) then sum(valor_aberto) end as 'venc_1_30',
		case when dt_vencimento >= date_sub(date(now()), INTERVAL 60 DAY) and dt_vencimento <=  date_sub(date(now()), INTERVAL 31 DAY) then sum(valor_aberto) end as 'venc_31_60',
		case when dt_vencimento >= date_sub(date(now()), INTERVAL 90 DAY) and dt_vencimento <=  date_sub(date(now()), INTERVAL 61 DAY) then sum(valor_aberto) end as 'venc_61_90', 
        case when dt_vencimento >= date_sub(date(now()), INTERVAL 120 DAY) and dt_vencimento <=  date_sub(date(now()),INTERVAL 91 DAY) then sum(valor_aberto) end as 'venc_91_120',
        case when dt_vencimento >= date_sub(date(now()), INTERVAL 150 DAY) and dt_vencimento <=  date_sub(date(now()), INTERVAL 121 DAY) then sum(valor_aberto) end as 'venc_121_150', 
        case when dt_vencimento >= date_sub(date(now()), INTERVAL 999 DAY) and dt_vencimento <=  date_sub(date(now()), INTERVAL 151 DAY) then sum(valor_aberto) end as 'venc_151_999',
        case when dt_vencimento <= date_sub(date(now()), INTERVAL 999 DAY) then sum(valor_aberto) end as 'venc_999'
		, sum(ifnull(limite_cred,0)) limite
		
	from titulos
    left join addressbook ab on titulos.id_cliente = ab.id
    
	$reps
	group by dt_vencimento
) as base");

		$financeiro_analit = array();
	// 	$financeiro_analit = \DB::select("
	// 		select 
              
	// 		  case when dt_vencimento < now() then 'vencido' when dt_vencimento >= now() then 'a_vencer' else '' end as status_parcela,
 //              dt_vencimento, numero_nf, parcela, pedido_origem, dt_emissao_nf, valor_em_aberto
				
 //                from a_receber 
 //                left join addressbook ab on ab.id = a_receber.cod_cli
 //                where cliente = '$cliente->cliente'
                
 //                order by dt_vencimento asc

	// "
	// );
		
		
 		
		
	    $trocas = \DB::select("
		select sum(qtde) qtde, sum(itens.valortabela*qtde) valor_solic
        from trocas 
        left join addressbook ab on ab.id = trocas.id_cliente
        left join itens on itens.id = trocas.id_item
        $reps ");
			
		$trocasdet = array();
// 		$trocasdet = \DB::connection('goweb')->
// 			select("
// 		select cliente, sum(qtde_pend_devolucao) qtde_pend_devolucao, sum(valor_pend_devolucao) valor_pend_devolucao, sum(qtde_pend_interacao) qtde_pend_interacao,
// 		sum(valor_pend_interacao) valor_pend_interacao from (
// 	select cliente, status_trocas, 
// 	case when status_trocas = 'pend_devolucao'  then sum(qtde) else 0 end as qtde_pend_devolucao, 
// 	case when status_trocas = 'pend_devolucao'  then sum(valor) else 0 end as valor_pend_devolucao, 
// 	case when status_trocas = 'pend_interacao'  then sum(qtde) else 0 end as qtde_pend_interacao, 
// 	case when status_trocas = 'pend_interacao'  then sum(valor) else 0 end as valor_pend_interacao

// 	from (	
// 		select 
// 		case when subgrupo <> '' then subgrupo when grupo <> '' then grupo else concat(trim(clientes.id),' - ',trim(razao))  end as cliente, 
// 		case when id_status_caso = 135 then 'pend_interacao' when (laudo not in ('001','002','003') and tr.antecipado = 1) and ult_status not in ('999','970') then 'pend_devolucao' else '' end as status_trocas,
// 		qtde, qtde*venda valor

// 		from trocas_itens tr
// 		left join clientes on clientes.id = tr.id_cliente
// 		left join produtos on produtos.id = tr.id_produto
// 	) as fim
// 	where status_trocas in ('pend_devolucao','pend_interacao') and cliente = '$cliente->cliente'
// 	group by cliente, status_trocas
// ) as fim2
// group by cliente ");

		$orcamentos = \DB::select("select grife, sum(qtde) as qtde, sum(valor) as valor, sum(atende_qtde) as atende_qtde,sum(atende_valor) as atende_valor
from (
	select grife, item, qtde, disponivel, valor,
		case 
			when disponivel < 0 then 0 
			when qtde > disponivel then disponivel 
		else qtde end as atende_qtde,
		case 
			when disponivel < 0 then 0 
			when qtde > disponivel then disponivel * (valor/qtde)
		else qtde * (valor/qtde) end as atende_valor
	from vendas_jde
	left join addressbook on id_cliente = addressbook.id
	left join saldos on saldos.curto = id_item
	left join itens on itens.id = id_item
	where ult_status = '510' and prox_status = '515' and id_cliente = $cliente->id and grupo = '$grupo'
) as base
group by grife");

		$trocasdet = \DB::select("
		select id_cliente, sum(pend_interacao) as pend_interacao,  sum(pend_fiscal) as pend_fiscal, sum(pend_devolucao) as pend_devolucao, count(*) as trocas,sum(abertas) as abertas,sum(concluidas) as concluidas,sum(inadimplentes) as inadimplentes, ((sum(antecipados) / count(*)) *100) as perc_antecipacao
from (
	select id_cliente,
		case when (nf_origem = '' or nf_consumidor = 1 or outro_codigo = 1 or substituto = '') then 1 else 0 end as pend_interacao,
		case when (nf_origem <> '' and nf_consumidor = 0 and outro_codigo = 0 and substituto <> '') and (dt_recebida is not null or dt_recebida <> '') and (laudo = '' or laudo is null or laudo = '0')  then 1 else 0 end as pend_fiscal,
		case when  dt_recebida is null then 1 else 0 end as pend_devolucao,
		case when id_status_caso = '999' then 1 else 0 end as concluidas,
		case when id_status_caso <> '999' then 1 else 0 end as abertas,
		case when bloqueio = 'IN' then 1 else 0 end as inadimplentes,
		case when antecipado = 1 then 1 else 0 end antecipados
		
	from trocas
	left join addressbook on id_cliente = addressbook.id
	$reps
) as base
group by id_cliente ");

	
		$trocas_vendas = \DB::select("
select (sum(trocas) / sum(vendas)) * 100 as perc_trocas_vendas
from (
	select qtde as vendas,
		(select count(*) from trocas where trocas.id_cliente = vendas_jde.id_cliente) as trocas
	from vendas_jde
	left join addressbook on id_cliente = addressbook.id
	where grupo = '$grupo' and ult_status not in ('980', '984')
) as base");
		

		$trocas_pecas = \DB::select("
select secundario,  count(*) qtde
from trocas
left join addressbook on id_cliente = addressbook.id

$reps
group by secundario
order by count(*) desc
limit 5");

		$trocas_grifes = \DB::select("
select grife, count(*) as qtde
from trocas
left join addressbook on id_cliente = addressbook.id

left join itens on id_item = itens.id
$reps
group by grife
order by count(*) desc
limit 5");
		
		$vendas = \DB::select("select 
	pedido, 
	dt_venda, 
    ifnull(sum(qtde_vendida),0) as qtde_vendida, 
    ifnull(sum(valor_vendido),0) as valor_vendido,
    ifnull(sum(qtde_cancelada),0) as qtde_cancelada, 
    ifnull(sum(valor_cancelada),0) as valor_cancelada,
    ifnull(sum(qtde_orcamento),0) as qtde_orcamento, 
    ifnull(sum(valor_orcamento),0) as valor_orcamento,
    ifnull(sum(qtde_faturada),0) as qtde_faturada,
    ifnull(sum(valor_faturada),0) as valor_faturada
from (
	select vda.pedido, vda.dt_venda, vda.item, vda.ult_status, vda.prox_status, vda.qtde as qtde_vendida, vda.valor as valor_vendido,
		case when vda.ult_status in ('980','984') or ped.ult_status in ('980','984') then vda.qtde end as qtde_cancelada,
		case when vda.ult_status in ('980','984') or ped.ult_status in ('980','984') then vda.valor end as valor_cancelada,
		case when (vda.ult_status = '510' and vda.prox_status = '515') or ped.ult_status in ('904', '902') then vda.qtde end as qtde_orcamento,
		case when (vda.ult_status = '510' and vda.prox_status = '515') or ped.ult_status in ('904', '902')  then vda.valor end as valor_orcamento,
		case when nf.ult_status = '620' and nf.prox_status = '999' then nf.qtde end as qtde_faturada,
		case when nf.ult_status = '620' and nf.prox_status = '999' then nf.total end as valor_faturada
		
	from vendas_jde vda
    left join pedidos_jde ped on ped.ped_original = vda.pedido and ped.tipo_original = vda.tipo and ped.linha_original = vda.linha
    left join notas_jde nf on nf.ped_original = ped.pedido and nf.tipo_original = ped.tipo and nf.linha_original = ped.linha
   	left join addressbook on vda.id_cliente = addressbook.id

	where  grupo = '$grupo'
	order by vda.dt_venda desc
) as base
group by pedido desc, dt_venda");
		return view('clientes.detalhes')
					->with('tipo', $tipo)
					->with('cliente', $cliente)
					->with('lojas', $lojas)
					->with('historicos', $historicos)
					->with('financeiro', $financeiro)
					->with('financeiro_analit', $financeiro_analit)
					->with('trocas', $trocas)
					->with('trocasdet', $trocasdet)
					->with('trocas_vendas', $trocas_vendas)
					->with('trocas_grifes', $trocas_grifes)
					->with('trocas_pecas', $trocas_pecas)
					->with('orcamentos', $orcamentos)
					->with('vendas', $vendas)
			
					->with('grifes', $grifes);

	}


	public function detalhesGrupo_old($grupo) {
		
		//$id = str_replace('_subst_', '/', $id);
		
		
		$representantes = \Session::get('representantes');
		

		$tipo = 'pdv';

		// procura como subgrupo
		$subgrupo = Cliente::where('subgrupo', $grupo)->where('subgrupo', '<>', '.')->where('subgrupo', '<>',  '')->first();
		$grupo = Cliente::where('grupo', $grupo)->where('grupo', '<>', '.')->where('grupo', '<>',  '')->first();

		if ($subgrupo) {
			$cliente = $subgrupo;
			$tipo = 'grupo';
		} elseif ($grupo) {
			$cliente = $grupo;
			$tipo = 'grupo';
		} else {
			$cliente = Cliente::find($id);
			$tipo = 'pdv';
		}

		
	
			$lojas = array();
		
		// if (trim($cliente->subgrupo) <> '' and trim($cliente->subgrupo) <> '.') {
			
			// $lojas = Cliente::where('subgrupo', $cliente->subgrupo)->where('subgrupo', '<>', '.')->get();
			// $lojas  = \DB::select("
			// select *
			// from (
			// 			select distinct grupo, cliente, ab.id pdv, municipio, razao, uf, ddd1,ddd2, tel1, tel2, email1, fantasia, endereco
			// 			from carteira
			// 			left join addressbook ab on cli = ab.id
			// 			where cliente =  '$cliente->cliente'
			// 			) as fim	

			// left join (
			// 		select cod_cli cli, sum(vencido) vencido, sum(a_vencer) a_vencer from (
			// 			select cod_cli,  
			// 			case when dt_vencimento < now() then valor_em_aberto else 0 end as vencido,
			// 			case when dt_vencimento >= now() then valor_em_aberto else 0 end as a_vencer
			// 			from a_receber left join addressbook ab on ab.id = a_receber.cod_cli 
			// 			) as sele1
			// 		group by cod_cli 
			// ) as receber
			// 	on receber.cli = fim.pdv


			// left join (
			// 		select cli_jde cli, sum(valor_17) valor_17, sum(valor_18) valor_18, sum(valor_19) valor_19 , 0 as valor_20 from (
			// 			select cli_jde, sum(valor) valor_17, 0 as valor_18, 0 as valor_19 from vendas_2017 group by cli_jde
			// 			union all
			// 			select cli_jde, 0 as valor_17, sum(valor) valor_18, 0 as valor_19 from vendas_2018 group by cli_jde
			// 			union all
			// 			select cli_jde, 0 as valor_17, 0 valor_18, sum(valor) as valor_19 from vendas_12meses where ano = '2019' group by cli_jde
			// 		) as fimv
			// 		group by cli_jde
			// ) as vendas
			// on vendas.cli = fim.pdv
			//  ");

		$grifes = array();

		// $grifes = \DB::select("
		// select *
		// 	from (
		// 		select grupo, cliente from (
		// 			select grupo, cliente, ab.id pdv
		// 			from carteira
		// 			left join addressbook ab on cli = ab.id
		// 			where ab.cliente = '$cliente->cliente'
		// 			group by grupo, cliente, ab.id, municipio ) as fim
		// 		group by grupo, cliente) as fim2	

		// 		left join (
		// 	select cli, grife_jde, grife, sum(v2017) v2017, sum(v2018) v2018, sum(v2019) v2019, sum(v2020) v2020 from (
		// 		select cli, grife_jde, grife,
  //               case when ano = '2017' then qtde else 0 end as v2017,
  //               case when ano = '2018' then qtde else 0 end as v2018,
  //               case when ano = '2019' then qtde else 0 end as v2019,
  //               case when ano = '2020' then qtde else 0 end as v2020
  //               from (
                
		// 			select cliente cli, ano, grife_jde as grife,
		// 			case 
		// 			when grife_jde = 'AH' then 'ANA HICKMANN' 
		// 			when grife_jde = 'AT' then 'ATITUDE' 
		// 			when grife_jde = 'BG' then 'BULGET' 
		// 			when grife_jde = 'EV' then 'EVOKE' 
		// 			when grife_jde = 'HI' then 'HICKMANN' 
		// 			when grife_jde = 'JO' then 'JOLIE' 
		// 			when grife_jde = 'SP' then 'SPEEDO' 
		// 			when grife_jde = 'TC' then 'T-CHARGE' 
		// 			when grife_jde = 'AM' then 'ALEXANDER MCQUEEN' 
		// 			when grife_jde = 'BV' then 'BOTTEGA VENETA' 
		// 			when grife_jde = 'CT' then 'CARTIER' 
		// 			when grife_jde = 'GU' then 'GUCCI' 
		// 			when grife_jde = 'MC' then 'MCQ' 
		// 			when grife_jde = 'MM' then 'MONT BLANC' 
		// 			when grife_jde = 'SM' then 'STELLA MCCARTNEY' 
		// 			when grife_jde = 'ST' then 'SAINT LAURENT' 
		// 			else grife_jde end as grife_jde, sum(qtde) qtde 
		// 				from vendas_cml
		// 				group by cli, grife_jde, ano
		// 		) as vds1
		// 	) as vds2
  //           group by cli, grife_jde, grife
		// ) as vendas
		// on vendas.cli = fim2.cliente");

		if (\Auth::user()->id_perfil == 4) {
			$reps = "where  rep in ($representantes)";
		} else {
			$reps = "";
		}
		
		$grifes = \DB::select("select codgrife, grife, codrep, rep, situacao, 
	sum(qtde_2020) as qtde_2020, 
	sum(qtde_2019) as qtde_2019, 
    sum(qtde_2018) as qtde_2018,
    sum(valor_2020) as valor_2020,
    sum(valor_2019) as valor_2019,
    sum(valor_2018) as valor_2018
	from (
	select base.codgrife, base.grife, carteira.rep as codrep, trim(rep.nome) as rep,  carteira.situacao, vda.item, vda.dt_venda, vda.qtde, vda.valor,
		case when year(dt_venda) = '2020' then vda.qtde else 0 end as qtde_2020,
		case when year(dt_venda) = '2019' then vda.qtde else 0 end as qtde_2019,
		case when year(dt_venda) = '2018' then vda.qtde else 0 end as qtde_2018,
		case when year(dt_venda) = '2020' then vda.valor else 0 end as valor_2020,
		case when year(dt_venda) = '2019' then vda.valor else 0 end as valor_2019,
		case when year(dt_venda) = '2018' then vda.valor else 0 end as valor_2018
	from (
		select codigo as codgrife, valor as grife
		from caracteristicas
		where campo = 'grife' 
	) as base
	left join carteira on base.codgrife = carteira.grife and cli = '$cliente->id'
	left join addressbook rep on carteira.rep = rep.id
	left join vendas_jde vda on carteira.cli = vda.id_cliente and carteira.grife = (select codgrife from itens where itens.id = vda.id_item) 
	$reps
) as fim
where codgrife in ('AH', 'AT','BG', 'EV', 'HI', 'NG', 'SP', 'TC','JO', 'JM')
group by codgrife, grife, codrep, rep, situacao
order by codgrife");		
		
		
		
		$historicos = Historico::where('tabela', 'clientes')->where('id_tabela', $cliente->id)->orderBy('created_at', 'desc')->get();

	    
		
		$financeiro = array();
		
		$financeiro = \DB::select("
			select sum(Futuro) Futuro, sum(Atual) Atual, sum(vencidott) vencidott, sum(venc_1_30) venc_1_30, sum(venc_31_60) venc_31_60, sum(venc_61_90) venc_61_90, sum(venc_91_120) venc_91_120, 
	sum(venc_121_150) venc_121_150, sum(venc_151_999) venc_151_999, sum(venc_999) venc_999, sum(limite) limite
from (
	select 
		case when dt_vencimento > date(now()) then sum(valor_em_aberto) end as 'Futuro',
		case when dt_vencimento =  date(now()) then sum(valor_em_aberto) end as 'Atual',
		case when dt_vencimento <  date(now()) then sum(valor_em_aberto) end as 'vencidott',
		case when dt_vencimento >= date_sub(date(now()), INTERVAL 30 DAY) and dt_vencimento <=  date_sub(date(now()), INTERVAL 1 DAY) then sum(valor_em_aberto) end as 'venc_1_30',
		case when dt_vencimento >= date_sub(date(now()), INTERVAL 60 DAY) and dt_vencimento <=  date_sub(date(now()), INTERVAL 31 DAY) then sum(valor_em_aberto) end as 'venc_31_60',
		case when dt_vencimento >= date_sub(date(now()), INTERVAL 90 DAY) and dt_vencimento <=  date_sub(date(now()), INTERVAL 61 DAY) then sum(valor_em_aberto) end as 'venc_61_90', 
        case when dt_vencimento >= date_sub(date(now()), INTERVAL 120 DAY) and dt_vencimento <=  date_sub(date(now()),INTERVAL 91 DAY) then sum(valor_em_aberto) end as 'venc_91_120',
        case when dt_vencimento >= date_sub(date(now()), INTERVAL 150 DAY) and dt_vencimento <=  date_sub(date(now()), INTERVAL 121 DAY) then sum(valor_em_aberto) end as 'venc_121_150', 
        case when dt_vencimento >= date_sub(date(now()), INTERVAL 999 DAY) and dt_vencimento <=  date_sub(date(now()), INTERVAL 151 DAY) then sum(valor_em_aberto) end as 'venc_151_999',
        case when dt_vencimento <= date_sub(date(now()), INTERVAL 999 DAY) then sum(valor_em_aberto) end as 'venc_999'
		, sum(ifnull(limite_cred,0)) limite
		
	from a_receber
    left join addressbook ab on a_receber.cod_cli = ab.id
    
	where cliente = '$cliente->cliente'
	group by dt_vencimento
) as base
"
);

		
		$financeiro_analit = \DB::select("
			select 
              
			  case when dt_vencimento < now() then 'vencido' when dt_vencimento >= now() then 'a_vencer' else '' end as status_parcela,
              dt_vencimento, numero_nf, parcela, pedido_origem, dt_emissao_nf, valor_em_aberto
				
                from a_receber 
                left join addressbook ab on ab.id = a_receber.cod_cli
                where cliente = '$cliente->cliente'
                
                order by dt_vencimento asc

	"
	);
		
		
 		
		
	    $trocas = \DB::select("
		select sum(qtde) qtde, sum(itens.valortabela*qtde) valor_solic
        from trocas 
        left join addressbook ab on ab.id = trocas.id_cliente
        left join itens on itens.id = trocas.id_item
        where cliente = '$cliente->cliente' ");
			
		$trocasdet = array();
		$trocasdet = \DB::select("
		select id_cliente, sum(pend_interacao) as pend_interacao,  sum(pend_fiscal) as pend_fiscal, sum(pend_devolucao) as pend_devolucao
from (
	select id_cliente,
		case when (nf_origem = '' or nf_consumidor = 1 or outro_codigo = 1 or substituto = '') then 1 else 0 end as pend_interacao,
		case when (nf_origem <> '' and nf_consumidor = 0 and outro_codigo = 0 and substituto <> '') and (dt_recebida is not null or dt_recebida <> '') and (laudo = '' or laudo is null or laudo = '0')  then 1 else 0 end as pend_fiscal,
		case when (dt_nf_remessa < date_sub(date(now()), interval 90 day) and dt_recebida is null) then 1 else 0 end as pend_devolucao
	from trocas
	where id_cliente = $cliente->id
) as base
group by id_cliente ");

	
		
		
		return view('grupos.detalhes')
					->with('tipo', $tipo)
					->with('cliente', $cliente)
					->with('lojas', $lojas)
					->with('historicos', $historicos)
					->with('financeiro', $financeiro)
					->with('financeiro_analit', $financeiro_analit)
					->with('trocas', $trocas)
					->with('trocasdet', $trocasdet)
			
					->with('grifes', $grifes);

	}


	public function gravaHistorico(Request $request) {

		$historico = new \App\Historico();
		$historico->id_usuario = \Auth::id();

		$historico->tabela = 'clientes';
		$historico->id_tabela = $request->id_cliente;
		$historico->categoria = 'historico';
		$historico->historico = $request->historico;
		$historico->save();


		return redirect()->back();

	}

	public function novoCliente() {

		$representantes = \Session::get('representantes');

		$cliente = new NovoCadastro();


		return view('clientes.novo')->with('cliente', $cliente);

	}


    public function detalhesNovoCliente($id) {

        $novo_cadastro = NovoCadastro::where('id', $id)->where('id_rep', \Auth::user()->id_addressbook)->first();

		return view('clientes.novo')->with('novo_cadastro', $novo_cadastro);

    }

	public function gravaCliente(Request $request) {


        if ($request->id && $request->id <> '') {
            $novo = NovoCadastro::find($request->id);

        } else {
            $novo = new NovoCadastro();
            $novo->id_usuario = \Auth::id();
            $novo->id_rep = \Auth::user()->id_addressbook;
            $novo->situacao = 'Novo';            
        }



		$novo->cnpj = $request->cnpj;
		$novo->ie = $request->ie;
		$novo->tipo = 1; //$request->tipo;
		$novo->fantasia = strtoupper($request->fantasia);
		$novo->razao = strtoupper($request->razao);

        $novo->inicio_atividade = $request->dt_inicio;
        $novo->suframa = $request->suframa;

		$novo->cep = $request->cep;
		$novo->endereco = strtoupper($request->endereco);
		$novo->numero = strtoupper($request->numero);
		$novo->complemento = strtoupper($request->complemento);
		$novo->bairro = strtoupper($request->bairro);
		$novo->cidade = strtoupper($request->cidade);
		$novo->estado = strtoupper($request->estado);

		$novo->proprietario = strtoupper($request->proprietario);
		$novo->celular_proprietario = strtoupper($request->celular_proprietario);
		$novo->email_proprietario = strtoupper($request->email_proprietario);

        $novo->gerente = strtoupper($request->gerente);
		$novo->celular_gerente = strtoupper($request->celular_gerente);
		$novo->email_gerente = strtoupper($request->email_gerente);

		$novo->financeiro = strtoupper($request->financeiro);
		$novo->celular_financeiro = strtoupper($request->celular_financeiro);
		$novo->email_financeiro = strtoupper($request->email_financeiro);

		$novo->obs = strtoupper($request->obs);

        $addressbook = AddressBook::where('cnpj', $request->cnpj)->first();
        $checa_novo = NovoCadastro::where('cnpj', $request->cnpj)->where('id', '<>', $request->id)->first();

        if ($addressbook || $checa_novo) {

            $request->session()->flash('alert-warning', 'CNPJ já existe no cadastro geral.');
            return redirect()->back();

        }

		$novo->save();


        return redirect('/clientes');

	}

	public function vendasDet(Request $request) {

		return view('clientes.vendas_det');

	}
	
	public function vendasPed(Request $request) {

		return view('clientes.vendas_ped');

	}
	
	public function pedidosDet(Request $request) {

		return view('clientes.pedidos_det');

	}
	
	public function pedidosPed(Request $request) {

		return view('clientes.pedidos_ped');

	}

	public function notasDet(Request $request) {

		return view('clientes.notas_det');

	}
	
	public function notasPed(Request $request) {

		return view('clientes.notas_ped');

	}
	
	
	public function listaFidelizados(Request $request) {

		return view('clientes.fidelizados');

	}
	
	public function listaFidelizados_cli(Request $request) {

		return view('clientes.fidelizados_cli');

	}

	
	public function gravaStatusGrife(Request $request) {

		$id_usuario = \Auth::id();


		$visita = new \App\ClienteVisita();
		$visita->id_usuario = \Auth::id();
		$visita->cliente = $request->cliente;
		$visita->grife = $request->grife;
		$visita->status = $request->status;
		$visita->situacao = $request->situacao;
		$visita->obs = $request->obs;		
		$visita->save();



		if ($request->opcoes) {

			foreach ($request->opcoes as $opcao) {

				$query = \DB::select("insert into clientes_motivos (id_visita, id_usuario, id_motivo, obs) values ( $visita->id, $id_usuario , '$opcao', '$request->obs') ");

			}
		}
		//$query = \DB::select("insert into clientes_grife (id_usuario, cliente, grife, status, situacao) values ( $id_usuario , '$request->cliente', '$request->grife', '$request->status' , '$request->situacao') ");

		return redirect()->back();



	}


}
