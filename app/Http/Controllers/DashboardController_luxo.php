<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class DashboardController extends Controller
{
    public function carrega() {

    	return view('layout.dashboard');

    }




    public function frequencia() {
		
		$id_usuario = \Auth::user()->id_addressbook;

    	if (\Auth::user()->id_perfil == 5) {

    		$sql = ' and diretor = '.$id_usuario;

    	} elseif (\Auth::user()->id_perfil == 6) {

    		$sql = ' and supervisor = '.$id_usuario;

    	} elseif (\Auth::user()->id_perfil == 4) {

    		$sql = ' and repres = '.$id_usuario;

    	} else {
    		$sql = '';
    	}



    	$query = \DB::select("
		select tipo as tempo, sum(a1) a1, sum(a2) a2, sum(a3) a3, sum(a4) a4, sum(a5) a5, sum(a6) a6 from ( 
	select tipo, 
	case when meses between 1 and 1.9999 then sum(clientes) else 0 end as a1 ,
	case when meses between 2 and 2.9999 then sum(clientes) else 0 end as a2 ,
	case when meses between 3 and 3.9999 then sum(clientes) else 0 end as a3 ,
	case when meses between 4 and 4.9999 then sum(clientes) else 0 end as a4 ,
	case when meses between 5 and 5.9999 then sum(clientes) else 0 end as a5 ,
	case when meses between 6 and 12 then sum(clientes) else 0 end as a6
    
	from (


		select tipo, meses, count(cliente) clientes from (
			select tipo, cliente, avg(meses) meses from (
				select tipo, cliente, grife_jde, count(mes) meses from (
					select tipo, cliente, grife_jde, mes
					from vendas_cml
					where tipo in ('2018') $sql
					group by tipo, cliente, grife_jde, mes
				) as sele1
				group by tipo, cliente, grife_jde
			) as sele2 group by tipo, cliente
		) as sele3 group by tipo, meses
	) as sele4 group by tipo, meses
) as sele5 group by tipo

UNION ALL

select tipo as tempo, sum(a1) a1, sum(a2) a2, sum(a3) a3, sum(a4) a4, sum(a5) a5, sum(a6) a6 from ( 
	select tipo, 
	case when meses between 1 and 1.9999 then sum(clientes) else 0 end as a1 ,
	case when meses between 2 and 2.9999 then sum(clientes) else 0 end as a2 ,
	case when meses between 3 and 3.9999 then sum(clientes) else 0 end as a3 ,
	case when meses between 4 and 4.9999 then sum(clientes) else 0 end as a4 ,
	case when meses between 5 and 5.9999 then sum(clientes) else 0 end as a5 ,
	case when meses between 6 and 12 then sum(clientes) else 0 end as a6 

	from (


		select tipo, meses, count(cliente) clientes from (
			select tipo, cliente, avg(meses) meses from (
				select tipo, cliente, grife_jde, count(mes) meses from (
					select tipo, cliente, grife_jde, mes
					from vendas_cml
					where tipo in ('12m') $sql
					group by tipo, cliente, grife_jde, mes
				) as sele1
				group by tipo, cliente, grife_jde
			) as sele2 group by tipo, cliente
		) as sele3 group by tipo, meses
	) as sele4 group by tipo, meses
) as sele5 group by tipo


");


    	return response()->json($query);


    }



    public function indFrequencia() {

    	$id_usuario = \Auth::user()->id_addressbook;

    	if (\Auth::user()->id_perfil == 5) {

    		$sql = ' and diretor = '.$id_usuario;

    	} elseif (\Auth::user()->id_perfil == 6) {

    		$sql = ' and supervisor = '.$id_usuario;

    	} elseif (\Auth::user()->id_perfil == 4) {

    		$sql = ' and repres = '.$id_usuario;

    	} else {
    		$sql = '';
    	}

    	$query = \DB::select("select FORMAT(sum(ind12),3) ind12, FORMAT(sum(ind18),3) ind18 from (
	select avg(meses) ind12, 0 ind18 from (
		select grife_jde, avg(meses) meses from (
			select cliente, grife_jde, count(mes) meses from (
				select  cliente, grife_jde, mes
					from vendas_cml
						where tipo in ('12m') $sql
				group by  cliente, grife_jde, mes 
			) as sele1 group by cliente, grife_jde
		) as sele2 group by grife_jde
	) as sele3
    
UNION ALL

	select  0 ind12, avg(meses) ind18 from (
		select grife_jde, avg(meses) meses from (
			select cliente, grife_jde, count(mes) meses from (
				select  cliente, grife_jde, mes
					from vendas_cml
						where tipo in ('2018') $sql
				group by  cliente, grife_jde, mes 
			) as sele1 group by cliente, grife_jde
		) as sele2 group by grife_jde
	) as sele3
) as sele4");


    	return response()->json($query);


    }



    public function indGrife() {

    	$id_usuario = \Auth::user()->id_addressbook;

    	$sql = '';

    	if (\Auth::user()->id_perfil == 5) {

    		$sql = ' and diretor = '.$id_usuario;

    	}


    	if (\Auth::user()->id_perfil == 6) {

    		$sql = ' and supervisor = '.$id_usuario;

    	}

    	if (\Auth::user()->id_perfil == 4) {

    		$sql = ' and repres = '.$id_usuario;

    	}
    	
    	$query = \DB::select("select FORMAT(sum(ind12),3) ind12, FORMAT(sum(ind18),3) ind18 from (

		select avg(grifes) ind12, 0 as ind18 from (	
            select tipo, cliente, count(grife_jde) grifes from ( 
				select tipo, cliente, grife_jde
					from vendas_cml
					where tipo in ('12m') $sql
				group by tipo, cliente, grife_jde 
			) as sele1 group by tipo, cliente
		) as sele2

UNION ALL
			
		select 0 AS ind12, avg(grifes) ind18 from (	
            select tipo, cliente, count(grife_jde) grifes from ( 
				select tipo, cliente, grife_jde
					from vendas_cml
					where tipo in ('2018') $sql
				group by tipo, cliente, grife_jde 
			) as sele1 group by tipo, cliente
		) as sele2
) as fim");


    	return response()->json($query);


    }


    public function grifeMensal() {

		$id_usuario = \Auth::user()->id_addressbook;
    	$sql = '';

    	if (\Auth::user()->id_perfil == 5) {

    		$sql = ' and diretor = '.$id_usuario;

    	}


    	if (\Auth::user()->id_perfil == 6) {

    		$sql = ' and supervisor = '.$id_usuario;

    	}

    	if (\Auth::user()->id_perfil == 4) {

    		$sql = ' and repres = '.$id_usuario;

    	}	
		

    	$query = \DB::select("
		
select grife_jde grife, sum(a1) a1, sum(a2) a2, sum(a3) a3, sum(a4) a4, sum(a5) a5, sum(a6) a6, sum(a7) a7, sum(a8) a8, sum(a9) a9, sum(a10) a10, sum(a11) a11, sum(a12) a12, sum(a12) a13 from(                    
			select grife_jde,
            case when mes = 1 then clientes else 0 end as a1,
            case when mes = 2 then clientes else 0 end as a2,
            case when mes = 3 then clientes else 0 end as a3,
            case when mes = 4 then clientes else 0 end as a4,
            case when mes = 5 then clientes else 0 end as a5,
            case when mes = 6 then clientes else 0 end as a6,
            case when mes = 7 then clientes else 0 end as a7,
            case when mes = 8 then clientes else 0 end as a8,
            case when mes = 9 then clientes else 0 end as a9,
            case when mes = 10 then clientes else 0 end as a10,
            case when mes = 11 then clientes else 0 end as a11,
            case when mes = 12 then clientes else 0 end as a12
            
            
             from (
            
				select grife_jde, mes, ano, count(cliente) clientes from (
                    select grife_jde, cliente,  mes, ano
					from vendas_cml
					where tipo in ('12m') $sql
					group by grife_jde, cliente,  mes, ano
				) as sele1 group by grife_jde,  mes, ano
			) as sele2
        ) as sele3 group by grife_jde");


    	return response()->json($query);


    }


    public function orcamentosMensal() {

    	$grifes = \Session::get('grifes');
    	$sql = '';

		$id_usuario = \Auth::user()->id_addressbook;

    	if (\Auth::user()->id_perfil == 5) {

    		$sql = ' and id_diretor = '.$id_usuario;

    	}


    	if (\Auth::user()->id_perfil == 6) {

    		$sql = ' and id_supervisor = '.$id_usuario;

    	}

    	if (\Auth::user()->id_perfil == 4) {

    		$sql = ' and id_representante = '.$id_usuario;

    	}	

    	$query = \DB::select("select grife, sum(mes1) jan, sum(mes2) fev, sum(mes3) mar, sum(mes4) abr, sum(mes5) mai, sum(mes6) jun, sum(mes7) jul, sum(mes8) ago,  sum(mes9) spt, sum(mes10) oct, sum(mes11) nov, sum(mes12) dez from (
	select grife, 
		case when mes = 1 then qtde else 0 end as mes1,
        case when mes = 2 then qtde else 0 end as mes2,
        case when mes = 3 then qtde else 0 end as mes3,
        case when mes = 4 then qtde else 0 end as mes4,
        case when mes = 5 then qtde else 0 end as mes5,
        case when mes = 6 then qtde else 0 end as mes6,
        case when mes = 7 then qtde else 0 end as mes7,
        case when mes = 8 then qtde else 0 end as mes8,
        case when mes = 9 then qtde else 0 end as mes9,
        case when mes = 10 then qtde else 0 end as mes10,
        case when mes = 11 then qtde else 0 end as mes11,
        case when mes = 12 then qtde else 0 end as mes12 from (
        
		select grife, mes, sum(qtd_aberto) qtde from (
			select oa.*, month(dt_pedido) as mes, id_diretor, id_supervisor, itens.secundario, 
				case when itens.grife like 'evok%' then 'EV' else itens.codgrife end as grife
			from orcamentos_anal oa
			left join addressbook abr on abr.id = oa.id_representante
			left join itens on itens.id = oa.id_item
            where codgrife in ('AH','AT','BG','EV','HI','JO','SP','TC','NG') $sql 
		) as sele1 group by grife, mes
	) as sele2 
) as sele3
group by grife");


    	return response()->json($query);



    }

    public function exportaClientes() {

    	ini_set('display_errors', 1);
    	ini_set('memory_limit', -1);
		ini_set('max_execution_time', -1);
		
    	$sql = '';

			$id_usuario = \Auth::user()->id_addressbook;

	    	if (\Auth::user()->id_perfil == 5) {

	    		$sql = ' and diretor = '.$id_usuario;
				$limite = '15000';

	    	}


	    	if (\Auth::user()->id_perfil == 6) {

	    		$sql = ' and supervisor = '.$id_usuario;
				$limite = '15000';

	    	}

	    	if (\Auth::user()->id_perfil == 4) {

	    		$sql = ' and repres = '.$id_usuario;
				$limite = '15000';

	    	}	
	   

		$query = \DB::select("
select *,  ( select concat(ano,' / ',mes) from vendas_cml vds where vds.cliente = sele4.nome order by ano desc, mes desc limit 1  ) as ult_data from (
		
select superv, cliente as nome, sum(grifes12) grifes12, sum(meses12) meses12, sum(qtde12) qtde12, sum(valor12) valor12,
        sum(grifes18) grifes18, sum(meses18) meses18, sum(qtde18) qtde18, sum(valor18) valor18 from (

  select superv,cliente, count(grife_jde) grifes12, avg(meses) meses12, sum(qtde) qtde12, sum(valor) valor12,
    0 grifes18, 0 meses18, 0 qtde18, 0 valor18
    from (
    select superv,cliente, grife_jde, count(mes) as meses, sum(qtde) qtde, sum(valor) valor from (
      select distinct supervisor, ab.fantasia superv, vds.cliente, case when grife_jde in ('EV','NG') then 'EV' else grife_jde end as grife_jde, mes, qtde, valor 
      from vendas_cml vds
      left join addressbook ab on ab.id = vds.supervisor 
      where vds.tipo = '12m' and grife_jde in ('AH','AT','BG','EV','HI','JO','SP','TC','NG') $sql
    ) as sele1 group by superv,cliente, grife_jde
  ) as sele2 group by superv,cliente
    
union all
 
  select superv, cliente, 0 grifes12, 0 meses12, 0 qtde12, 0 valor12,
    count(grife_jde) grifes18, avg(meses) meses18, sum(qtde) qtde18, sum(valor) valor18 from (
    select superv, cliente, grife_jde, count(mes) as meses, sum(qtde) qtde, sum(valor) valor from (
      select distinct supervisor, ab.fantasia superv,  vds.cliente, case when grife_jde in ('EV','NG') then 'EV' else grife_jde end as grife_jde, mes, qtde, valor 
      from vendas_cml vds
      left join addressbook ab on ab.id = vds.supervisor 
      where vds.tipo = '2018' and grife_jde in ('AH','AT','BG','EV','HI','JO','SP','TC','NG') $sql
    ) as sele1 group by superv,cliente, grife_jde
  ) as sele2 group by superv,cliente
) as sele3 group by superv,cliente ) as sele4 order by ult_data asc ");

		$spreadsheet = new Spreadsheet();

		$sheet = $spreadsheet->getActiveSheet(0);		

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


		$spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Nome')
	            ->setCellValue('B1', 'Grifes12')
	            ->setCellValue('C1', 'Meses12')
	            ->setCellValue('D1', 'Qtde12')
	            ->setCellValue('E1', 'Valor12')
	            ->setCellValue('F1', 'Grifes18')
	            ->setCellValue('G1', 'Meses18')
	            ->setCellValue('H1', 'Qtde18')
	            ->setCellValue('I1', 'Valor18')
				->setCellValue('J1', 'ult_data')
				->setCellValue('K1', 'superv');

	    $index = 2;

		foreach ($query as $item) {

			
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$index, $item->nome)
					->setCellValue('B'.$index, $item->grifes12)
					->setCellValue('C'.$index, $item->meses12)
					->setCellValue('D'.$index, $item->qtde12)
					->setCellValue('E'.$index, $item->valor12)
					->setCellValue('F'.$index, $item->grifes18)
					->setCellValue('G'.$index, $item->meses18)
					->setCellValue('H'.$index, $item->qtde18)
					->setCellValue('I'.$index, $item->valor18)
					->setCellValue('J'.$index, $item->ult_data)
					->setCellValue('k'.$index, $item->superv);
			$index++;

		}            
		$spreadsheet->getActiveSheet()->setTitle('sintetico');

		$spreadsheet->createSheet();	
		// Add some data
		// Rename worksheet
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet

		$sheet = $spreadsheet->getActiveSheet(1);		

		$query2 = \DB::select("select *, 
 ( select concat(ano,' / ',mes) from vendas_cml vds where vds.cliente = sele4.cliente 
	order by ano desc, mes desc limit 1  ) as ult_data FROM (

select superv, cliente, ano, mes, sum(AH) AH, sum(AT) AT, sum(BG) BG, sum(EV) EV, sum(HI) HI, sum(JO) JO, sum(SP) SP, sum(TC) TC 
from (
	select superv, cliente,  ano, mes, 
		case when grife_jde = 'AH' then qtde else 0 end as AH,
        case when grife_jde = 'AT' then qtde else 0 end as AT,
        case when grife_jde = 'BG' then qtde else 0 end as BG,
        case when grife_jde = 'EV' then qtde else 0 end as EV,
        case when grife_jde = 'HI' then qtde else 0 end as HI,
        case when grife_jde = 'JO' then qtde else 0 end as JO,
        case when grife_jde = 'SP' then qtde else 0 end as SP,
        case when grife_jde = 'TC' then qtde else 0 end as TC
        
			from (
        
		select superv, cliente,  grife_jde, ano, mes, sum(qtde) qtde, sum(valor) valor from (
			
			select distinct  ab.fantasia superv, vds.cliente, case when grife_jde in ('EV','NG') then 'EV' else grife_jde end as grife_jde, ano, mes, qtde, valor 
			from vendas_cml vds
            left join addressbook ab on ab.id = vds.supervisor 
			left join addressbook ab1 on ab1.cliente = vds.cliente 
			where vds.tipo in ('12m','2018') and grife_jde in ('AH','AT','BG','EV','HI','JO','SP','TC','NG') $sql
	
		) as sele1 group by superv, cliente,  grife_jde, ano, mes
	) as sele2 group by superv, cliente,  ano, mes, grife_jde
) as sele3 group by superv, cliente,  ano, mes	
) as sele4
order by cliente, ano, mes");


		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('G')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('H')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('I')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('J')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('K')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('L')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('M')->setAutoSize(true);



		$spreadsheet->setActiveSheetIndex(1)->setCellValue('A1', 'Nome')
	            ->setCellValue('B1', 'Ano')
	            ->setCellValue('C1', 'Mes')
	            ->setCellValue('D1', 'AH')
	            ->setCellValue('E1', 'AT')
	            ->setCellValue('F1', 'BG')
	            ->setCellValue('G1', 'EV')
	            ->setCellValue('H1', 'HI')
	            ->setCellValue('I1', 'JO')
	            ->setCellValue('J1', 'SP')
	            ->setCellValue('K1', 'TC')
				->setCellValue('L1', 'ult_data')
				->setCellValue('M1', 'superv');

	    $index = 2;

		foreach ($query2 as $item) {

			
			$spreadsheet->setActiveSheetIndex(1)->setCellValue('A'.$index, $item->cliente)
					->setCellValue('B'.$index, $item->ano)
					->setCellValue('C'.$index, $item->mes)
					->setCellValue('D'.$index, $item->AH)
					->setCellValue('E'.$index, $item->AT)
					->setCellValue('F'.$index, $item->BG)
					->setCellValue('G'.$index, $item->EV)
					->setCellValue('H'.$index, $item->HI)
					->setCellValue('I'.$index, $item->JO)
					->setCellValue('J'.$index, $item->SP)
					->setCellValue('K'.$index, $item->TC)
					->setCellValue('L'.$index, $item->ult_data)
					->setCellValue('M'.$index, $item->superv);
			$index++;

		}        


		$spreadsheet->getActiveSheet()->setTitle('analitico');



		$spreadsheet->createSheet();	
		// Add some data
		// Rename worksheet
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet

		$sheet = $spreadsheet->getActiveSheet(2);		

		$query3 = \DB::select("select 
		cli, sup,  razao, fantasia, cliente, uf, municipio, 
sum(AH) AH, sum(AT) AT, sum(BG) BG, sum(EV) EV, sum(HI) HI, sum(JO) JO, sum(SP) SP, sum(TC) TC
from (

	select cli, repres, sup, dir, supervisor, diretor,  razao, fantasia, cliente, uf, municipio,
	case when grife = 'AH' then ifnull(qtde,0) else 0 end as AH,
	case when grife = 'AT' then ifnull(qtde,0) else 0 end as AT,
	case when grife = 'BG' then ifnull(qtde,0) else 0 end as BG,
	case when grife IN ('EV','NG') then ifnull(qtde,0) else 0 end as EV,
	case when grife = 'HI' then ifnull(qtde,0) else 0 end as HI,
	case when grife = 'JO' then ifnull(qtde,0) else 0 end as JO,
	case when grife = 'SP' then ifnull(qtde,0) else 0 end as SP,
	case when grife = 'TC' then ifnull(qtde,0) else 0 end as TC,
	

	qtde qtde_total

	from (


select * from (
		select distinct cli_jde, cart.* from (
			select distinct cli_jde from vendas_2018  
			union all 
			select distinct cli_jde from vendas_12meses where ano <> 2018
		) as sele1

		join (select cart.repres, cart.sup, cart.dir, cart.supervisor, cart.diretor,  cart.cli, cart.grife,
		ab.razao, ab.fantasia, ab.cliente, ab.uf, ab.municipio 
		from _carteira cart
		left join addressbook ab on ab.id = cart.cli
        where 1=1 $sql ) as cart
		on cart.cli = sele1.cli_jde
) as cart1

	left join (
	select cli_jde as clijde, grife_jde , sum(qtde) qtde, sum(valor) valor
	from vendas_12meses where ano = '2019' and mes in ('1','2','3','4','5')  
	group by cli_jde, grife_jde
	) as v12m
	on cart1.cli = v12m.clijde and v12m.grife_jde = cart1.grife



) as fim2

) as fim group by cli, sup, razao, fantasia, cliente, uf, municipio

limit $limite");


		
		$spreadsheet->setActiveSheetIndex(2)->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(2)->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(2)->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(2)->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(2)->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(2)->getColumnDimension('G')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(2)->getColumnDimension('H')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(2)->getColumnDimension('I')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(2)->getColumnDimension('J')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(2)->getColumnDimension('K')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(2)->getColumnDimension('L')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(2)->getColumnDimension('M')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(2)->getColumnDimension('N')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(2)->getColumnDimension('O')->setAutoSize(true);
	

		$spreadsheet->setActiveSheetIndex(2)
				->setCellValue('A1', 'COMPRAS DOS ULTIMOS 4 MESES DE PDVs QUE COMPRARAM EM 2018 OU 2019');
	            
		$spreadsheet->setActiveSheetIndex(2)
				->setCellValue('A2', 'cli')
	            ->setCellValue('B2', 'sup')	           
	            ->setCellValue('C2', 'razao')
	            ->setCellValue('D2', 'fantasia')
	            ->setCellValue('E2', 'cliente')
	            ->setCellValue('F2', 'uf')
	            ->setCellValue('G2', 'municipio')
	            ->setCellValue('H2', 'AH')
				->setCellValue('I2', 'AT')
				->setCellValue('J2', 'BG')
				->setCellValue('K2', 'EV')
				->setCellValue('L2', 'HI')
				->setCellValue('M2', 'JO')
				->setCellValue('N2', 'SP')
				->setCellValue('O2', 'TC');

	    $index = 3;

		foreach ($query3 as $item) {

			
			$spreadsheet->setActiveSheetIndex(2)->setCellValue('A'.$index, $item->cli)
					->setCellValue('B'.$index, $item->sup)
				
					->setCellValue('C'.$index, $item->razao)
					->setCellValue('D'.$index, $item->fantasia)
					->setCellValue('E'.$index, $item->cliente)
					->setCellValue('F'.$index, $item->uf)
					->setCellValue('G'.$index, $item->municipio)
					->setCellValue('H'.$index, $item->AH)
					->setCellValue('I'.$index, $item->AT)
					->setCellValue('J'.$index, $item->BG)
					->setCellValue('K'.$index, $item->EV)
					->setCellValue('L'.$index, $item->HI)
					->setCellValue('M'.$index, $item->JO)
					->setCellValue('N'.$index, $item->SP)
					->setCellValue('O'.$index, $item->TC);
			$index++;

		}        


		$spreadsheet->getActiveSheet()->setTitle('ult_4meses');


		$spreadsheet->setActiveSheetIndex(0);

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="indicadores.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0


		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');






    }

}
