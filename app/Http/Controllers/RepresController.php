<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Item;

class RepresController extends Controller
{
	

	
	
	
	public static function listaReps() {


	$listareps = \DB::connection('go')->select("

		select * from (
		select id id_rep, codigo id_ssa, tipo, nome, fantasia, razao, uf, municipio, grupo, subgrupo, cadastro, flag_cadastro, sit_representante,
			tipo_comissao, diretoria
			from addressbook ab
			where tipo in ('re','ri') 
		) as base


		left join (
			select rep_cart, count(cli) clientes, sum(cli_ativos) cli_ativos, min(dt_inicio) dt_inicio, max(dt_fim) dt_fim from  (
				select rep rep_cart, cli,
				case when status = 1 then 1 else 0 end as cli_ativos,
				min(dt_inicio) dt_inicio, max(dt_fim) dt_fim 
				from carteira
				group by rep, status, cli
				) as fim group by rep_cart
			) as cart
		on cart.rep_cart = base.id_rep


		left join (select id_rep rep_most, sum(qtde) qtde_most from malas group by id_rep) as malas
		on malas.rep_most = base.id_rep


		left join (select id_rep rep_vda, sum(qtde) qtde_vda from vendas_jde where datediff(now(),dt_venda) <= 30 group by id_rep) as vendas
		on vendas.rep_vda = base.id_rep
		");

		
	return view('dashboards.representante.lista')->with('listareps', $listareps);
	}
	

	
	
	
}
