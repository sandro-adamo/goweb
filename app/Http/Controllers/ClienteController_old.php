<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;
use App\Carteira;
use App\Historico;
use App\NovoCadastro;

class ClienteController extends Controller
{

	public function listaClientes() {

		$representantes = \Session::get('representantes');

		 $clientes = \DB::select("
select *
from (
		select grupo, cliente, count(pdv) as pdvs, left(group_concat(distinct ' ',municipio ),30) municipios from (
				select grupo, cliente, ab.id pdv, municipio
				from carteira
				left join addressbook ab on cli = ab.id
				where rep IN ($representantes)
				-- where rep IN ('47989') 
				group by grupo, cliente, ab.id, municipio ) as fim
		group by grupo, cliente
		
	) as fim2	

	left join (
			select cliente cli, sum(vencido) vencido, sum(a_vencer) a_vencer from (
				select cliente,  
				case when dt_vencimento < now() then valor_em_aberto else 0 end as vencido,
				case when dt_vencimento >= now() then valor_em_aberto else 0 end as a_vencer
				from a_receber left join addressbook ab on ab.id = a_receber.cod_cli ) as sele1
			group by cliente 
	) as receber
		on receber.cli = fim2.cliente

	left join (
			select cli, sum(valor_17) valor_17, sum(valor_18) valor_18, sum(valor_19) valor_19, sum(valor_20) valor_20 from (
				select cliente cli,
				case when ano = 2017 then valor else 0 end valor_17,
				case when ano = 2018 then valor else 0 end valor_18,
				case when ano = 2019 then valor else 0 end valor_19,
				case when ano = 2020 then valor else 0 end valor_20			
				from vendas_cml
			) as fimv
			group by cli
	) as vendas
	on vendas.cli = fim2.cliente    
");

		// $clientes = Cliente::where('tipo', 'CI')->orWhere('tipo', 'C')->orderBy('id')->take(15)->get();


		return view('clientes.lista')->with('clientes', $clientes);

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

		if (trim($cliente->subgrupo) <> '' and trim($cliente->subgrupo) <> '.') {
			
			// $lojas = Cliente::where('subgrupo', $cliente->subgrupo)->where('subgrupo', '<>', '.')->get();
			$lojas  = \DB::select("
			select *
			from (
						select distinct grupo, cliente, ab.id pdv, municipio, razao, uf, ddd1,ddd2, tel1, tel2, email1, fantasia, endereco
						from carteira
						left join addressbook ab on cli = ab.id
						where cliente =  '$cliente->cliente'
						) as fim	

			left join (
					select cod_cli cli, sum(vencido) vencido, sum(a_vencer) a_vencer from (
						select cod_cli,  
						case when dt_vencimento < now() then valor_em_aberto else 0 end as vencido,
						case when dt_vencimento >= now() then valor_em_aberto else 0 end as a_vencer
						from a_receber left join addressbook ab on ab.id = a_receber.cod_cli 
						) as sele1
					group by cod_cli 
			) as receber
				on receber.cli = fim.pdv


			left join (
					select cli_jde cli, sum(valor_17) valor_17, sum(valor_18) valor_18, sum(valor_19) valor_19 , 0 as valor_20 from (
						select cli_jde, sum(valor) valor_17, 0 as valor_18, 0 as valor_19 from vendas_2017 group by cli_jde
						union all
						select cli_jde, 0 as valor_17, sum(valor) valor_18, 0 as valor_19 from vendas_2018 group by cli_jde
						union all
						select cli_jde, 0 as valor_17, 0 valor_18, sum(valor) as valor_19 from vendas_12meses where ano = '2019' group by cli_jde
					) as fimv
					group by cli_jde
			) as vendas
			on vendas.cli = fim.pdv
			 ");
		
		} elseif (trim($cliente->grupo) <> '' and trim($cliente->grupo) <> '.') {
			//$lojas = Cliente::where('grupo', $cliente->grupo)->get();
			$lojas  = \DB::select("
			select *
			from (
						select distinct grupo, cliente, ab.id pdv, municipio, razao, uf, ddd1,ddd2, tel1, tel2, email1, fantasia, endereco
						from carteira
						left join addressbook ab on cli = ab.id
						where cliente =  '$cliente->cliente'
						) as fim	

			left join (
					select cod_cli cli, sum(vencido) vencido, sum(a_vencer) a_vencer from (
						select cod_cli,  
						case when dt_vencimento < now() then valor_em_aberto else 0 end as vencido,
						case when dt_vencimento >= now() then valor_em_aberto else 0 end as a_vencer
						from a_receber left join addressbook ab on ab.id = a_receber.cod_cli 
						) as sele1
					group by cod_cli 
			) as receber
				on receber.cli = fim.pdv


			left join (
					select cli_jde cli, sum(valor_17) valor_17, sum(valor_18) valor_18, sum(valor_19) valor_19 , 0 as valor_20 from (
						select cli_jde, sum(valor) valor_17, 0 as valor_18, 0 as valor_19 from vendas_2017 group by cli_jde
						union all
						select cli_jde, 0 as valor_17, sum(valor) valor_18, 0 as valor_19 from vendas_2018 group by cli_jde
						union all
						select cli_jde, 0 as valor_17, 0 valor_18, sum(valor) as valor_19 from vendas_12meses where ano = '2019' group by cli_jde
					) as fimv
					group by cli_jde
			) as vendas
			on vendas.cli = fim.pdv
			");
			
		} else {
			$lojas = array();
		}

		
			
		$grifes = \DB::select("
		select *
			from (
				select grupo, cliente from (
					select grupo, cliente, ab.id pdv
					from carteira
					left join addressbook ab on cli = ab.id
					where ab.cliente = '$cliente->subgrupo'
					group by grupo, cliente, ab.id, municipio ) as fim
				group by grupo, cliente) as fim2	

				left join (
				select cliente cli, case 
				when grife_jde = 'AH' then 'ANA HICKMANN' 
				when grife_jde = 'AT' then 'ATITUDE' 
                when grife_jde = 'BG' then 'BULGET' 
                when grife_jde = 'EV' then 'EVOKE' 
                when grife_jde = 'HI' then 'HICKMANN' 
                when grife_jde = 'JO' then 'JOLIE' 
                when grife_jde = 'SP' then 'SPEEDO' 
                when grife_jde = 'TC' then 'T-CHARGE' 
                when grife_jde = 'PU' then 'PUMA' else grife_jde end as grife_jde, sum(qtde) qtde 
					from vendas_cml
					group by cli, grife_jde
				) as vendas
				on vendas.cli = fim2.cliente");
		

		
		
		
		
		$historicos = Historico::where('tabela', 'clientes')->where('id_tabela', $cliente->id)->orderBy('created_at', 'desc')->get();

	    
		
		
		
		$financeiro = \DB::select("
			select sum(Futuro) Futuro, sum(Atual) Atual, sum(venc_1_30) venc_1_30, sum(venc_31_60) venc_31_60, sum(venc_61_90) venc_61_90, sum(venc_91_120) venc_91_120, 
	sum(venc_121_150) venc_121_150, sum(venc_151_999) venc_151_999, sum(venc_999) venc_999
from (
	select 
		case 
			when dt_vencimento > date(now()) then sum(valor_em_aberto) end as 'Futuro',
		case 
			when dt_vencimento =  date(now()) then sum(valor_em_aberto) end as 'Atual',
		case
			when dt_vencimento >= date_sub(date(now()), INTERVAL 30 DAY) and dt_vencimento <=  date_sub(date(now()), INTERVAL 1 DAY) then sum(valor_em_aberto) end as 'venc_1_30',
		case
			when dt_vencimento >= date_sub(date(now()), INTERVAL 60 DAY) and dt_vencimento <=  date_sub(date(now()), INTERVAL 31 DAY) then sum(valor_em_aberto) end as 'venc_31_60',
		case
			when dt_vencimento >= date_sub(date(now()), INTERVAL 90 DAY) and dt_vencimento <=  date_sub(date(now()), INTERVAL 61 DAY) then sum(valor_em_aberto) end as 'venc_61_90', 
        case    
			when dt_vencimento >= date_sub(date(now()), INTERVAL 120 DAY) and dt_vencimento <=  date_sub(date(now()),INTERVAL 91 DAY) then sum(valor_em_aberto) end as 'venc_91_120',
        case    
			when dt_vencimento >= date_sub(date(now()), INTERVAL 150 DAY) and dt_vencimento <=  date_sub(date(now()), INTERVAL 121 DAY) then sum(valor_em_aberto) end as 'venc_121_150', 
        case    
			when dt_vencimento >= date_sub(date(now()), INTERVAL 999 DAY) and dt_vencimento <=  date_sub(date(now()), INTERVAL 151 DAY) then sum(valor_em_aberto) end as 'venc_151_999',
        case    
			when dt_vencimento <= date_sub(date(now()), INTERVAL 999 DAY) then sum(valor_em_aberto) end as 'venc_999'
		
	from a_receber 
	where cod_cli = $cliente->id
	group by dt_vencimento
) as base");

		
		
		
		
	    $trocas = \DB::select("select * from trocas where id_cliente = '$cliente->id' order by data_troca desc");
		
		$trocas1 = \DB::select("select * from trocas where id_cliente = '$cliente->id' order by data_troca desc");

		
		
		
		
		return view('clientes.detalhes')
					->with('tipo', $tipo)
					->with('cliente', $cliente)
					->with('lojas', $lojas)
					->with('historicos', $historicos)
					->with('financeiro', $financeiro)
					->with('trocas', $trocas)
					->with('trocas1', $trocas1)
					->with('grifes', $grifes)
						;

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

		$cliente = new Cliente();


		return view('clientes.novo')->with('cliente', $cliente);

	}


	public function gravaCliente(Request $request) {

		$novo = new NovoCadastro();
		$novo->id_usuario = \Auth::id();
		$novo->cnpj = $request->cnpj;
		$novo->ie = $request->ie;
		$novo->tipo = 1; //$request->tipo;
		$novo->fantasia = strtoupper($request->fantasia);
		$novo->razao_social = strtoupper($request->razao_social);

		$novo->cep = $request->cep;
		$novo->endereco = strtoupper($request->endereco);
		$novo->numero = strtoupper($request->numero);
		$novo->complemento = strtoupper($request->complemento);
		$novo->bairro = strtoupper($request->bairro);
		$novo->cidade = strtoupper($request->cidade);
		$novo->estado = strtoupper($request->estado);



		$novo->obs = strtoupper($request->obs);

		$novo->save();

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
