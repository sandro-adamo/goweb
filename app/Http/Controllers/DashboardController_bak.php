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

    public function carteira() {

    	$id_rep = \Auth::user()->id_addressbook;
    	$representantes = \Session::get('representantes');

    	$carteira = \DB::select("select situacao, count(*) as clientes
from (
	select cli
	from carteira
	where rep  in ($representantes)
	group by cli
) as base
left join addressbook on cli = addressbook.id
group by situacao");

    	return response()->json($carteira);


    }


    public function vendas(Request $request) {

    	if ($request->periodo) {
    		$periodo = explode('-', $request->periodo);
    		$ano = $periodo[1];
    		$mes = $periodo[0];
    	} else {
    		$ano = date('Y');
    		$mes = date('m');
    	}

    	$representantes = \Session::get('representantes');
    	$id_rep = \Auth::user()->id_addressbook;

    	$vendas = \DB::select("select fim3.* from (
	select 
	sum(vlr1) 'd1', 
	sum(vlr2) 'd2', 
	sum(vlr3) 'd3', 
	sum(vlr4) 'd4', 
	sum(vlr5) 'd5', 
	sum(vlr6) 'd6', 
	sum(vlr7) 'd7', 
	sum(vlr8) 'd8', 
	sum(vlr9) 'd9', 
	sum(vlr10) 'd10', 
	sum(vlr11) 'd11', 
	sum(vlr12) 'd12', 
	sum(vlr13) 'd13', 
	sum(vlr14) 'd14', 
	sum(vlr15) 'd15', 
	sum(vlr16) 'd16', 
	sum(vlr17) 'd17', 
	sum(vlr18) 'd18', 
	sum(vlr19) 'd19', 
	sum(vlr20) 'd20', 
	sum(vlr21) 'd21', 
	sum(vlr22) 'd22', 
	sum(vlr23) 'd23', 
	sum(vlr24) 'd24', 
	sum(vlr25) 'd25', 
	sum(vlr26) 'd26', 
	sum(vlr27) 'd27', 
	sum(vlr28) 'd28', 
	sum(vlr29) 'd29', 
	sum(vlr30) 'd30', 
	sum(vlr31) 'd31'

	from (
		select totais, id_rep, nome,
			(select distinct group_concat(grife, ' ' order by grife) from repXgrife where an8 = id_rep) as grifes,
		case when suspensao = 'IN' then valor else 0 end as INAD, 
		case when dia = 1  then valor else 0 end as vlr1,
		case when dia = 2  then valor else 0 end as vlr2,
		case when dia = 3  then valor else 0 end as vlr3,
		case when dia = 4  then valor else 0 end as vlr4,
		case when dia = 5  then valor else 0 end as vlr5,
		case when dia = 6  then valor else 0 end as vlr6,
		case when dia = 7  then valor else 0 end as vlr7,
		case when dia = 8  then valor else 0 end as vlr8,
		case when dia = 9  then valor else 0 end as vlr9,
		case when dia = 10  then valor else 0 end as vlr10,
		case when dia = 11  then valor else 0 end as vlr11,
		case when dia = 12  then valor else 0 end as vlr12,
		case when dia = 13  then valor else 0 end as vlr13,
		case when dia = 14  then valor else 0 end as vlr14,
		case when dia = 15  then valor else 0 end as vlr15,
		case when dia = 16  then valor else 0 end as vlr16,
		case when dia = 17  then valor else 0 end as vlr17,
		case when dia = 18  then valor else 0 end as vlr18,
		case when dia = 19  then valor else 0 end as vlr19,
		case when dia = 20  then valor else 0 end as vlr20,
		case when dia = 21  then valor else 0 end as vlr21,
		case when dia = 22  then valor else 0 end as vlr22,
		case when dia = 23  then valor else 0 end as vlr23,
		case when dia = 24  then valor else 0 end as vlr24,
		case when dia = 25  then valor else 0 end as vlr25,
		case when dia = 26  then valor else 0 end as vlr26,
		case when dia = 27  then valor else 0 end as vlr27,
		case when dia = 28  then valor else 0 end as vlr28,
		case when dia = 29  then valor else 0 end as vlr29,
		case when dia = 30  then valor else 0 end as vlr30,
		case when dia = 31  then valor else 0 end as vlr31,
		case when suspensao <> 'IN' then valor else 0 end as valor
		
		from (

			select 'Totais' as totais, id_rep, case when nome is null then razao else nome end as nome, day(dt_venda) dia, suspensao, sum(qtde) qtde, sum(valor) valor  
			
			from vendas_jde as vendas
			left join itens on vendas.id_item = itens.id
			left join addressbook ab on vendas.id_rep = ab.id
			where itens.id is not null and  year(dt_venda) = $ano and month(dt_venda) = $mes 
			and ult_status not in ('980','984') and codgrife in ('AH','AT','BG','EV','NG','JO','HI','SP','TC','JM','PU') 
			and id_rep  in ($representantes)       
			group by id_rep, nome, fantasia, day(dt_venda), suspensao
			
		) as fim
	) as fim2
) as fim3
");

    	return response()->json($vendas);


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

echo 'rep: '.$id_usuario;

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

	
	
	
	
	
// exporta excel teeste para Sandro // 	
	
	
	
    public function exportaExcel(Request $request) {
    	ini_set('display_errors', 1);
    	ini_set('memory_limit', -1);
		ini_set('max_execution_time', -1);
		
		
		
		$usuario = \App\Usuario::where('id_addressbook', $request->id)->first();
    	$sql = '';

		$limite = '1';

	    	if ($usuario->id_perfil == 5) {

	    		$sql = ' and diretor = '.$usuario->id_addressbook;
				$sql1 = ' fid.id_diretor = '.$usuario->id_addressbook;
				$representantes = \Session::get('representantes');
				
				$limite = '1';

	    	}


	    	if ($usuario->id_perfil == 6) {

	    		$sql = ' and supervisor = '.$usuario->id_addressbook;
				$sql1 = ' fid.id_supervisor = '.$usuario->id_addressbook;
				$representantes = \Session::get('representantes');
				$limite = '1';

	    	}

	    	if ($usuario->id_perfil == 4) {

	    		$sql = ' and repres = '.$usuario->id_addressbook;
				$sql1 = ' fid.id_rep = '.$usuario->id_addressbook;
				$representantes = \Session::get('representantes');
				$limite = '1';

	    	}	
	   
		if ($usuario->id_perfil == 1) {

	    		$sql = ' and repres = '.$usuario->id_addressbook;
				$sql1 = ' fid.id_rep = '.$usuario->id_addressbook;
				$representantes = \Session::get('representantes');
				$limite = '1';

	    	}	
	   

		

		$spreadsheet = new Spreadsheet();

// PRIMEIRA ABA // 

		$sheet = $spreadsheet->getActiveSheet(0);		

	
	    $query1 = \DB::select("
					select uf,  avg(grifes) grifes from (	
                        select abc.*, grifes from (
							select cod_cliente, count(codgrife) grifes from (
								select codgrife, abc.id cod_cliente,  sum(qtde) qtde
								from vendas_jde as vendas
								left join itens on vendas.id_item = itens.id
								left join addressbook abc on abc.id = vendas.id_cliente	
								
								where  ult_status not in ('980','984') 
								and TIMESTAMPDIFF(MONTH, dt_venda,  NOW()) <= 1
							    and id_rep in ($representantes)
	
								group by codgrife, abc.id
							) as fim1  where qtde > 10 
                            group by cod_cliente
						) as fim2
                        
                        left join (select id, razao, fantasia, uf, municipio, grupo, subgrupo from addressbook ) abc
                        on abc.id = fim2.cod_cliente
					) as fim3  
                    -- where uf in ('pr','sc','rs')
                    group by uf");
		
		$spreadsheet->setActiveSheetIndex(0)
		->setCellValue('A1', 'Grifes por UF ultimo mes');	

 
	    $index = 5;
		
		$spreadsheet->setActiveSheetIndex(0)
			->setCellValue('A4', 'uf')
			->setCellValue('B4', 'grifes');




	    foreach ($query1 as $item) {
			
			$spreadsheet->setActiveSheetIndex(0)
					->setCellValue('A'.$index, $item->uf)
					->setCellValue('B'.$index, $item->grifes);
					
			$index++;

	    }

    


	    $query1 = \DB::select("
					select uf,  avg(grifes) grifes from (	
                        select abc.*, grifes from (
							select cod_cliente, count(codgrife) grifes from (
								select codgrife, abc.id cod_cliente,  sum(qtde) qtde
								from vendas_jde as vendas
								left join itens on vendas.id_item = itens.id
								left join addressbook abc on abc.id = vendas.id_cliente	
								
								where  ult_status not in ('980','984') 
								and TIMESTAMPDIFF(MONTH, dt_venda,  NOW()) <= 4
								and id_rep in ($representantes)
								 
								group by codgrife, abc.id
							) as fim1  where qtde > 10 
                            group by cod_cliente
						) as fim2
                        
                        left join (select id, razao, fantasia, uf, municipio, grupo, subgrupo from addressbook ) abc
                        on abc.id = fim2.cod_cliente
					) as fim3  
                    -- where uf in ('pr','sc','rs')
                    group by uf");

		$spreadsheet->setActiveSheetIndex(0)
		->setCellValue('E1', 'Grifes por UF ultimos 4 meses');	

		
		$spreadsheet->setActiveSheetIndex(0)
		->setCellValue('E4', 'uf')
		->setCellValue('F4', 'grifes');

		
	    $index = 5;

	    foreach ($query1 as $item) {
			
			$spreadsheet->setActiveSheetIndex(0)
					->setCellValue('E'.$index, $item->uf)
					->setCellValue('F'.$index, $item->grifes);
					
			$index++;

	    }
		
		
		
		
		$query1 = \DB::select("
					select uf,  avg(grifes) grifes from (	
                        select abc.*, grifes from (
							select cod_cliente, count(codgrife) grifes from (
								select codgrife, abc.id cod_cliente,  sum(qtde) qtde
								from vendas_jde as vendas
								left join itens on vendas.id_item = itens.id
								left join addressbook abc on abc.id = vendas.id_cliente	
								
								where  ult_status not in ('980','984') 
								and TIMESTAMPDIFF(MONTH, dt_venda,  NOW()) <= 12
								and id_rep in ($representantes)
								 
								group by codgrife, abc.id
							) as fim1  where qtde > 10 
                            group by cod_cliente
						) as fim2
                        
                        left join (select id, razao, fantasia, uf, municipio, grupo, subgrupo from addressbook ) abc
                        on abc.id = fim2.cod_cliente
					) as fim3  
                    -- where uf in ('pr','sc','rs')
                    group by uf");

		$spreadsheet->setActiveSheetIndex(0)
		->setCellValue('I1', 'Grifes por UF ultimos 12 meses');	

		
		$spreadsheet->setActiveSheetIndex(0)
		->setCellValue('I4', 'uf')
		->setCellValue('J4', 'grifes');

		
	    $index = 5;

	    foreach ($query1 as $item) {
			
			$spreadsheet->setActiveSheetIndex(0)
					->setCellValue('I'.$index, $item->uf)
					->setCellValue('J'.$index, $item->grifes);
					
			$index++;

	    }
		
		
		
		$spreadsheet->getActiveSheet()->setTitle('grifes');
		
		
// FIM DA PRIMEIRA ABA 

		
		
		
		
		
		
		

// SEGUDNA ABA
		$spreadsheet->createSheet();	

		$sheet = $spreadsheet->getActiveSheet(1);		


	    $query2 = \DB::select("
		select codgrife, sum(m1) m1, sum(m2) m2, sum(m3) m3, sum(m4) m4, sum(m5) m5, sum(m6) m6, sum(m7) m7, sum(m8) m8, sum(m9) m9, sum(m10) m10, sum(m11) m11, sum(m12) m12
		from (
		
			select codgrife, 
			case when meses = 1 then 1 else 0 end as m1,
			case when meses = 2 then 1 else 0 end as m2,
			case when meses = 3 then 1 else 0 end as m3,
			case when meses = 4 then 1 else 0 end as m4,
			case when meses = 5 then 1 else 0 end as m5,
			case when meses = 6 then 1 else 0 end as m6,
			case when meses = 7 then 1 else 0 end as m7,
			case when meses = 8 then 1 else 0 end as m8,
			case when meses = 9 then 1 else 0 end as m9,
			case when meses = 10 then 1 else 0 end as m10,
			case when meses = 11 then 1 else 0 end as m11,
			case when meses = 12 then 1 else 0 end as m12			
			from (
			
				select cod_cliente, codgrife, count(mes) meses from (
					select codgrife, abc.id cod_cliente, month(dt_venda) mes, sum(qtde) qtde
					from vendas_jde as vendas
					left join itens on vendas.id_item = itens.id
					left join addressbook abc on abc.id = vendas.id_cliente	
					
					where  ult_status not in ('980','984') and id_rep in ($representantes) 
					and TIMESTAMPDIFF(MONTH, dt_venda,  NOW()) <= 8

					group by codgrife, abc.id, month(dt_venda)
				) as fim1  where qtde > 10 
				group by cod_cliente, codgrife
			) as fim2
		) as fim3
		group by codgrife");


		$spreadsheet->setActiveSheetIndex(1)
		->setCellValue('B3', 'CLIENTES POR QUANTIDADE DE COMPRAS NOS ULTIMOS 8 MESES');	

		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('B')->setAutoSize(true); 
		$spreadsheet->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	
		$spreadsheet->getActiveSheet()->getStyle('A1:Z1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
		$spreadsheet->getActiveSheet()->getStyle('A1:Z1')->getFill()->getStartColor()->setARGB('blue');

		
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
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('N')->setAutoSize(true);
		
		$spreadsheet->setActiveSheetIndex(1)
			
					->setCellValue('B4', 'Grife')
					->setCellValue('C4', 'Comprou 1x')
					->setCellValue('D4', 'Comprou 2x')
					->setCellValue('E4', 'Comprou 3x')
					->setCellValue('F4', 'Comprou 4x')
					->setCellValue('G4', 'Comprou 5x')
					->setCellValue('H4', 'Comprou 6x')
					->setCellValue('I4', 'Comprou 7x')
					->setCellValue('J4', 'Comprou 8x')
					->setCellValue('K4', 'Comprou 9x')
					->setCellValue('L4', 'Comprou 10x')
					->setCellValue('M4', 'Comprou 11x')
					->setCellValue('N4', 'Comprou 12x');

		
	    $index = 5;

		foreach ($query2 as $item) {

			
			$spreadsheet->setActiveSheetIndex(1)
					->setCellValue('B'.$index, $item->codgrife)
					->setCellValue('C'.$index, $item->m1)
					->setCellValue('D'.$index, $item->m2)
					->setCellValue('E'.$index, $item->m3)
					->setCellValue('F'.$index, $item->m4)
					->setCellValue('G'.$index, $item->m5)
					->setCellValue('H'.$index, $item->m6)
					->setCellValue('I'.$index, $item->m7)
					->setCellValue('J'.$index, $item->m8)
					->setCellValue('K'.$index, $item->m9)
					->setCellValue('L'.$index, $item->m10)
					->setCellValue('M'.$index, $item->m11)
					->setCellValue('N'.$index, $item->m12);
			$index++;

		}        


		
		
		
		
		
		
			    $query2 = \DB::select("
		select codgrife, sum(m1) m1, sum(m2) m2, sum(m3) m3, sum(m4) m4, sum(m5) m5, sum(m6) m6, sum(m7) m7, sum(m8) m8, sum(m9) m9, sum(m10) m10, sum(m11) m11, sum(m12) m12
		from (
		
			select codgrife, 
			case when meses = 1 then 1 else 0 end as m1,
			case when meses = 2 then 1 else 0 end as m2,
			case when meses = 3 then 1 else 0 end as m3,
			case when meses = 4 then 1 else 0 end as m4,
			case when meses = 5 then 1 else 0 end as m5,
			case when meses = 6 then 1 else 0 end as m6,
			case when meses = 7 then 1 else 0 end as m7,
			case when meses = 8 then 1 else 0 end as m8,
			case when meses = 9 then 1 else 0 end as m9,
			case when meses = 10 then 1 else 0 end as m10,
			case when meses = 11 then 1 else 0 end as m11,
			case when meses = 12 then 1 else 0 end as m12			
			from (
			
				select cod_cliente, codgrife, count(mes) meses from (
					select codgrife, abc.id cod_cliente, month(dt_venda) mes, sum(qtde) qtde
					from vendas_jde as vendas
					left join itens on vendas.id_item = itens.id
					left join addressbook abc on abc.id = vendas.id_cliente	
					
					where  ult_status not in ('980','984') and id_rep in ($representantes) 
					and TIMESTAMPDIFF(MONTH, dt_venda,  NOW()) <= 12

					group by codgrife, abc.id, month(dt_venda)
				) as fim1  where qtde > 10 
				group by cod_cliente, codgrife
			) as fim2
		) as fim3
		group by codgrife");


		$spreadsheet->setActiveSheetIndex(1)
		->setCellValue('B28', 'CLIENTES POR QUANTIDADE DE COMPRAS NOS ULTIMOS 12 MESES');	

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
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('N')->setAutoSize(true);
		
		$spreadsheet->setActiveSheetIndex(1)
			
					->setCellValue('B29', 'Grife')
					->setCellValue('C29', 'Comprou 1x')
					->setCellValue('D29', 'Comprou 2x')
					->setCellValue('E29', 'Comprou 3x')
					->setCellValue('F29', 'Comprou 4x')
					->setCellValue('G29', 'Comprou 5x')
					->setCellValue('H29', 'Comprou 6x')
					->setCellValue('I29', 'Comprou 7x')
					->setCellValue('J29', 'Comprou 8x')
					->setCellValue('K29', 'Comprou 9x')
					->setCellValue('L29', 'Comprou 10x')
					->setCellValue('M29', 'Comprou 11x')
					->setCellValue('N29', 'Comprou 12x');

		
	    $index = 30;

		foreach ($query2 as $item) {

			
			$spreadsheet->setActiveSheetIndex(1)
					->setCellValue('B'.$index, $item->codgrife)
					->setCellValue('C'.$index, $item->m1)
					->setCellValue('D'.$index, $item->m2)
					->setCellValue('E'.$index, $item->m3)
					->setCellValue('F'.$index, $item->m4)
					->setCellValue('G'.$index, $item->m5)
					->setCellValue('H'.$index, $item->m6)
					->setCellValue('I'.$index, $item->m7)
					->setCellValue('J'.$index, $item->m8)
					->setCellValue('K'.$index, $item->m9)
					->setCellValue('L'.$index, $item->m10)
					->setCellValue('M'.$index, $item->m11)
					->setCellValue('N'.$index, $item->m12);
			$index++;

		}        

		
		
		
		
		$spreadsheet->getActiveSheet()->setTitle('frequencia');
		
		// FIM DA SEGUNDA ABA

		
		
		
		
		
		
		
		
		
	    // TERCEIRA ABA
		$spreadsheet->createSheet();	

		$sheet = $spreadsheet->getActiveSheet(2);		

	    $query3 = \DB::select("select * from (
	select  coddir, codsuper, repres, grifes,
	(sum(INAD)) Suspenso,
	(sum(valor)) Total_Vendido,
	(sum(vlr1)) 'd1', 
	(sum(vlr2)) 'd2', 
	(sum(vlr3)) 'd3', 
	(sum(vlr4)) 'd4', 
	(sum(vlr5)) 'd5', 
	(sum(vlr6)) 'd6', 
	(sum(vlr7)) 'd7', 
	(sum(vlr8)) 'd8', 
	(sum(vlr9)) 'd9', 
	(sum(vlr10)) 'd10', 
	(sum(vlr11)) 'd11', 
	(sum(vlr12)) 'd12', 
	(sum(vlr13)) 'd13', 
	(sum(vlr14)) 'd14', 
	(sum(vlr15)) 'd15', 
	(sum(vlr16)) 'd16', 
	(sum(vlr17)) 'd17', 
	(sum(vlr18)) 'd18', 
	(sum(vlr19)) 'd19', 
	(sum(vlr20)) 'd20', 
	(sum(vlr21)) 'd21', 
	(sum(vlr22)) 'd22', 
	(sum(vlr23)) 'd23', 
	(sum(vlr24)) 'd24', 
	(sum(vlr25)) 'd25', 
	(sum(vlr26)) 'd26', 
	(sum(vlr27)) 'd27', 
	(sum(vlr28)) 'd28', 
	(sum(vlr29)) 'd29', 
	(sum(vlr30)) 'd30', 
	(sum(vlr31)) 'd31'

	from (
		select repres, coddir, codsuper,
		(select distinct group_concat(grife, ' ' order by grife) from repXgrife where an8 = id_rep) as grifes,
		case when suspensao > 0 then valor else 0 end as INAD, 
		case when dia = 1 then valor else 0 end as vlr1,
		case when dia = 2 then valor else 0 end as vlr2,
		case when dia = 3 then valor else 0 end as vlr3,
		case when dia = 4 then valor else 0 end as vlr4,
		case when dia = 5 then valor else 0 end as vlr5,
		case when dia = 6 then valor else 0 end as vlr6,
		case when dia = 7 then valor else 0 end as vlr7,
		case when dia = 8 then valor else 0 end as vlr8,
		case when dia = 9 then valor else 0 end as vlr9,
		case when dia = 10 then valor else 0 end as vlr10,
		case when dia = 11 then valor else 0 end as vlr11,
		case when dia = 12 then valor else 0 end as vlr12,
		case when dia = 13 then valor else 0 end as vlr13,
		case when dia = 14 then valor else 0 end as vlr14,
		case when dia = 15 then valor else 0 end as vlr15,
		case when dia = 16 then valor else 0 end as vlr16,
		case when dia = 17 then valor else 0 end as vlr17,
		case when dia = 18 then valor else 0 end as vlr18,
		case when dia = 19 then valor else 0 end as vlr19,
		case when dia = 20 then valor else 0 end as vlr20,
		case when dia = 21 then valor else 0 end as vlr21,
		case when dia = 22 then valor else 0 end as vlr22,
		case when dia = 23 then valor else 0 end as vlr23,
		case when dia = 24 then valor else 0 end as vlr24,
		case when dia = 25 then valor else 0 end as vlr25,
		case when dia = 26 then valor else 0 end as vlr26,
		case when dia = 27 then valor else 0 end as vlr27,
		case when dia = 28 then valor else 0 end as vlr28,
		case when dia = 29 then valor else 0 end as vlr29,
		case when dia = 30 then valor else 0 end as vlr30,
		case when dia = 31 then valor else 0 end as vlr31,
		 valor 
		
		from (
        
				select id_rep, cod_cliente, repres, dia, suspensao, sum(valor) valor from (
					
                    select id_rep, case when abr.nome = '' then abr.fantasia else abr.nome end as repres, id_cliente cod_cliente, 
					case when dayname(dt_venda) = 'Saturday' then day(date_sub(dt_venda, interval 1 day))
					when dayname(dt_venda) = 'Sunday' then day(date_sub(dt_venda, interval 2 day)) else day(dt_venda) end as dia,
					(valor) valor,
                    (select count(suspensao) from suspensoes where vendas.pedido = suspensoes.pedido and tipo = 'SQ' limit 1) as suspensao
					from vendas_jde as vendas
					left join itens on vendas.id_item = itens.id
					left join addressbook abr on abr.id = vendas.id_rep
					
					where  ult_status not in ('980','984') and id_rep in ($representantes)
					and year(dt_venda) = (select max(ano) from fechamentos) and month(dt_venda) = (select max(mes) from fechamentos) + 1
                    and dt_venda < date(now())  
					) as fim2
                    
					group by id_rep, repres, cod_cliente, dia, suspensao
		) as fim
        
        left join (select distinct rep, 
        case when abd.nome = '' then abd.fantasia else abd.nome end as coddir, 
        case when abs.nome = '' then abs.fantasia else abs.nome end as codsuper 
        from carteira left join addressbook abd on abd.id = coddir left join addressbook abs on abs.id = codsuper) cart
        
        on cart.rep = id_rep
        
	) as fim2
    group by repres, grifes , coddir, codsuper with rollup
) as fim3
where (repres is null and grifes is null and coddir is null and codsuper is null) or (repres is not null and grifes is not null and coddir is not null and codsuper is not null) 
order by coddir is null, coddir , codsuper, repres
");

		
		
		$spreadsheet->setActiveSheetIndex(2)
				->setCellValue('A4', 'DIRETOR')
				->setCellValue('B4', 'SUPERVISOR')
				->setCellValue('C4', 'repres')
	            ->setCellValue('D4', 'grifes')
	            ->setCellValue('E4', 'suspensao')
				->setCellValue('F4', 'Total_Vendido')
				->setCellValue('G4', '1')
				->setCellValue('H4', '2')
				->setCellValue('I4', '3')
				->setCellValue('J4', '4')
				->setCellValue('K4', '5')
				->setCellValue('L4', '6')
				->setCellValue('M4', '7')
				->setCellValue('N4', '8')
				->setCellValue('O4', '9')
				->setCellValue('P4', '10')
				->setCellValue('Q4', '11')
				->setCellValue('R4', '12')
				->setCellValue('S4', '13')
				->setCellValue('T4', '14')
				->setCellValue('U4', '15')
				->setCellValue('V4', '16')
				->setCellValue('W4', '17')
				->setCellValue('X4', '18')
				->setCellValue('Y4', '19')
				->setCellValue('Z4', '20')
				->setCellValue('AA4', '21')
				->setCellValue('AB4', '22')
				->setCellValue('AC4', '23')
				->setCellValue('AD4', '24')
				->setCellValue('AE4', '25')
				->setCellValue('AF4', '26')
				->setCellValue('AG4', '27')
				->setCellValue('AH4', '28')
				->setCellValue('AI4', '29')
				->setCellValue('AJ4', '30')
	            ->setCellValue('AK4', '31');


		
		
		
	    $index = 5;

		foreach ($query3 as $item) {

			
			$spreadsheet->setActiveSheetIndex(2)
			->setCellValue('A'.$index, $item->coddir)
			->setCellValue('B'.$index, $item->codsuper)
			->setCellValue('C'.$index, $item->repres)
			->setCellValue('D'.$index, $item->grifes)
			->setCellValue('E'.$index, $item->Suspenso)
			->setCellValue('F'.$index, $item->Total_Vendido)
			->setCellValue('G'.$index, $item->d1)
			->setCellValue('H'.$index, $item->d2)
			->setCellValue('I'.$index, $item->d3)
			->setCellValue('J'.$index, $item->d4)
			->setCellValue('K'.$index, $item->d5)
			->setCellValue('L'.$index, $item->d6)
			->setCellValue('M'.$index, $item->d7)
			->setCellValue('N'.$index, $item->d8)
			->setCellValue('O'.$index, $item->d9)
			->setCellValue('P'.$index, $item->d10)
			->setCellValue('Q'.$index, $item->d11)
			->setCellValue('R'.$index, $item->d12)
			->setCellValue('S'.$index, $item->d13)
			->setCellValue('T'.$index, $item->d14)
			->setCellValue('U'.$index, $item->d15)
			->setCellValue('V'.$index, $item->d16)
			->setCellValue('W'.$index, $item->d17)
			->setCellValue('X'.$index, $item->d18)
			->setCellValue('Y'.$index, $item->d19)
			->setCellValue('Z'.$index, $item->d20)
			->setCellValue('AA'.$index, $item->d21)
			->setCellValue('AB'.$index, $item->d22)
			->setCellValue('AC'.$index, $item->d23)
			->setCellValue('AD'.$index, $item->d24)
			->setCellValue('AE'.$index, $item->d25)
			->setCellValue('AF'.$index, $item->d26)
			->setCellValue('AG'.$index, $item->d27)
			->setCellValue('AH'.$index, $item->d28)
			->setCellValue('AI'.$index, $item->d29)
			->setCellValue('AJ'.$index, $item->d30)
			->setCellValue('AK'.$index, $item->d31);
$index++;

		}        


		$spreadsheet->getActiveSheet()->setTitle('mapa_diario');
// FIM DA TERCEIRA ABA


		
		
		
		
		
		
		
// QUARTA ABA
		$spreadsheet->createSheet();	

		$sheet = $spreadsheet->getActiveSheet(3);		

	    $query4 = \DB::select("
select * from (
	select coddir, codsuper, repres, grifes, 
	(sum(INAD)) Suspenso,
	(sum(valor)) Total_Vendido,
	(sum(AH)) AH, 
	(sum(AT)) AT, 
	(sum(BG)) BG, 
	(sum(EV)) EV, 
	(sum(HI)) HI, 
	(sum(JO)) JO, 
	(sum(SP)) SP, 
	(sum(TC)) TC, 
	(sum(JM)) JM, 
	(sum(PU)) PU, 
	(sum(FASHION)) FASHION, 
	(sum(LUXO)) LUXO

	from (
		select repres, coddir, codsuper,
		(select distinct group_concat(grife, ' ' order by grife) from repXgrife where an8 = id_rep) as grifes,
		case when suspensao > 0 then valor else 0 end as INAD, 
		case when codgrife = 'AH' then valor else 0 end as AH,
        case when codgrife = 'AT' then valor else 0 end as AT,
        case when codgrife = 'BG' then valor else 0 end as BG,
        case when codgrife IN ('EV','NG') then valor else 0 end as EV,
        case when codgrife = 'HI' then valor else 0 end as HI,
        case when codgrife = 'JO' then valor else 0 end as JO,
        case when codgrife = 'SP' then valor else 0 end as SP,
        case when codgrife = 'TC' then valor else 0 end as TC,
        case when codgrife = 'JM' then valor else 0 end as JM,
        case when codgrife = 'PU' then valor else 0 end as PU,
        case when codgrife in ('GU','MM','ST') then valor else 0 end as FASHION,
        case when codgrife in ('AM', 'MC','AZ','AA','CT','BC','BV','SM') then valor else 0 end as LUXO,
		valor 
		
		from (
        
				select id_rep, cod_cliente, repres, codgrife, suspensao, sum(valor) valor from (
					
                    select id_rep, case when abr.nome = '' then abr.fantasia else abr.nome end as repres, id_cliente cod_cliente, codgrife,
                    (valor) valor,
                    (select count(suspensao) from suspensoes where vendas.pedido = suspensoes.pedido and tipo = 'SQ' limit 1) as suspensao
					from vendas_jde as vendas
					left join itens on vendas.id_item = itens.id
					left join addressbook abr on abr.id = vendas.id_rep
					
					where  ult_status not in ('980','984') and id_rep in ($representantes)
					and year(dt_venda) = (select max(ano) from fechamentos) and month(dt_venda) = (select max(mes) from fechamentos) + 1
                    and dt_venda < date(now())
					) as fim2
					group by id_rep, repres, cod_cliente, codgrife, suspensao
		) as fim
                left join (select distinct rep, 
        case when abd.nome = '' then abd.fantasia else abd.nome end as coddir, 
        case when abs.nome = '' then abs.fantasia else abs.nome end as codsuper 
        from carteira left join addressbook abd on abd.id = coddir left join addressbook abs on abs.id = codsuper) cart
        
        on cart.rep = id_rep
	) as fim2
    group by repres, grifes, coddir, codsuper with rollup
) as fim3
where (repres is null and grifes is null and coddir is null and codsuper is null) or (repres is not null and grifes is not null and coddir is not null and codsuper is not null)
order by coddir is null, coddir , codsuper, repres ");


		
		$spreadsheet->setActiveSheetIndex(3)
		->setCellValue('A1', 'MAPA DE VENDAS POR GRIFES');	

			
		$spreadsheet->setActiveSheetIndex(3)->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(3)->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(3)->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(3)->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(3)->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(3)->getColumnDimension('G')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(3)->getColumnDimension('H')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(3)->getColumnDimension('I')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(3)->getColumnDimension('J')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(3)->getColumnDimension('K')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(3)->getColumnDimension('L')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(3)->getColumnDimension('M')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(3)->getColumnDimension('N')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(3)->getColumnDimension('O')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(3)->getColumnDimension('P')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(3)->getColumnDimension('Q')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(3)->getColumnDimension('R')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(3)->getColumnDimension('S')->setAutoSize(true);
	
			$spreadsheet->getActiveSheet()->getStyle('F:S')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

		
		
		
		
		$spreadsheet->setActiveSheetIndex(3)
					->setCellValue('B4', 'DIRETOR')
					->setCellValue('C4', 'SUPERVISOR')
					->setCellValue('D4', 'REPRES')
					->setCellValue('E4', 'GRIFES')
					->setCellValue('F4', 'SUSPENSO')
					->setCellValue('G4', 'TOTAL_VENDIDO')
					->setCellValue('H4', 'AH')
					->setCellValue('I4', 'AT')
					->setCellValue('J4', 'BG')
					->setCellValue('K4', 'EV')	
					->setCellValue('L4', 'HI')
					->setCellValue('M4', 'JO')
					->setCellValue('N4', 'SP')
					->setCellValue('O4', 'TC')
					->setCellValue('P4', 'JM')
					->setCellValue('Q4', 'PU')
					->setCellValue('R4', 'FASHION')
					->setCellValue('S4', 'LUXO');


	    $index = 5;

		
		foreach ($query4 as $item) {

			
			$spreadsheet->setActiveSheetIndex(3)
						->setCellValue('B'.$index, $item->coddir)
						->setCellValue('C'.$index, $item->codsuper)				
						->setCellValue('D'.$index, $item->repres)
						->setCellValue('E'.$index, $item->grifes)
						->setCellValue('F'.$index, $item->Suspenso)
						->setCellValue('G'.$index, $item->Total_Vendido)
						->setCellValue('H'.$index, $item->AH)
						->setCellValue('I'.$index, $item->AT)
						->setCellValue('J'.$index, $item->BG)
						->setCellValue('K'.$index, $item->EV)
						->setCellValue('L'.$index, $item->HI)
						->setCellValue('M'.$index, $item->JO)
						->setCellValue('N'.$index, $item->SP)
						->setCellValue('O'.$index, $item->TC)
						->setCellValue('P'.$index, $item->JM)
						->setCellValue('Q'.$index, $item->PU)
						->setCellValue('R'.$index, $item->FASHION)
						->setCellValue('S'.$index, $item->LUXO);
$index++;

		}        


		$spreadsheet->getActiveSheet()->setTitle('mapa_grifes');
		// FIM DA QUARTA ABA


		$spreadsheet->setActiveSheetIndex(0);

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="exporta.xlsx"');
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
	
	
	

	
	
	
	
	
	
	
// INICIO DO EXPORTA EXCEL DA PAGINA DASHBOARD REPRESENTANTES 
	
	

    public function exportaClientes(Request $request) {

    	ini_set('display_errors', 1);
    	ini_set('memory_limit', -1);
		ini_set('max_execution_time', -1);
		
		//dd($request->all());

		$usuario = \App\Usuario::where('id_addressbook', $request->id)->first();
    	$sql = '';

		$limite = '1';

	    	if ($usuario->id_perfil == 5) {

	    		$sql = ' and diretor = '.$usuario->id_addressbook;
				$sql1 = ' fid.id_diretor = '.$usuario->id_addressbook;
				$representantes = \Session::get('representantes');
			
				$limite = '1';

	    	}


	    	if ($usuario->id_perfil == 6) {

	    		$sql = ' and supervisor = '.$usuario->id_addressbook;
				$sql1 = ' fid.id_supervisor = '.$usuario->id_addressbook;
				$representantes = \Session::get('representantes');
			
				$limite = '1';

	    	}

	    	if ($usuario->id_perfil == 4) {

	    		$sql = ' and repres = '.$usuario->id_addressbook;
				$sql1 = ' fid.id_rep = '.$usuario->id_addressbook;
				$representantes = \Session::get('representantes');
			
				
				$limite = '1';

	    	}	
		
		
		if ($usuario->id_perfil == 1) {

	    		$sql = ' and repres = '.$usuario->id_addressbook;
				$sql1 = ' fid.id_rep = '.$usuario->id_addressbook;
				$representantes = \Session::get('representantes');
			
		
			$limite = '1';

	    	}	
	   
/**	 dd($representantes); **/
		
		

$query = \DB::select("

select ab.*, fim4.* from (
	select cli, sum(qtde_2018) qtde_2sem_2018, sum(qtde_2019) qtde_2019, sum(qtde_2020) qtde_2020 from (
		select cli, 
        case when ano = 2018 then qtde else 0 end as qtde_2018,
        case when ano = 2019 then qtde else 0 end as qtde_2019,
        case when ano = 2020 then qtde else 0 end as qtde_2020
        from (
        
			select cli, ano, sum(qtde) qtde from (
					select * from (
					select distinct cli, grife from carteira
					 where rep in ($representantes) 
					
					) as base


					left join (

							select codgrife, abc.id cod_cliente, year(dt_venda) ano , sum(qtde) qtde
							from vendas_jde as vendas
							left join itens on vendas.id_item = itens.id
							left join addressbook abc on abc.id = vendas.id_cliente		
							where  ult_status not in ('980','984') and id_rep in ($representantes) 
							group by codgrife, abc.id, year(dt_venda)

					) as vds
					on vds.codgrife = base.grife and vds.cod_cliente = base.cli
			) as fim group by cli, ano
		) as fim2
	) as fim3 group by cli
) as fim4
        
left join (select id, razao, fantasia, grupo, subgrupo, uf, municipio, endereco, numero, bairro, cep, financeiro, cadastro, email1, email2, ddd1, tel1, ddd2, tel2  from addressbook ) as ab
on ab.id = fim4.cli ");

		$spreadsheet = new Spreadsheet();

		$sheet = $spreadsheet->getActiveSheet(0);		

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
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('S')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('T')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('U')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('V')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('W')->setAutoSize(true);

		$spreadsheet->setActiveSheetIndex(0)
		->setCellValue('A1', 'COMPARATIVO ANUAL DESDE 2o. SEMESTRE 2018');	
		
		
		
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('A4', 'codcli')
	            ->setCellValue('B4', 'Razao_Social')
	            ->setCellValue('C4', 'Fantasia')
	            ->setCellValue('D4', 'Grupo')
	            ->setCellValue('E4', 'Subgrupo')
	            ->setCellValue('F4', 'UF')
	            ->setCellValue('G4', 'Municipio')
	            ->setCellValue('H4', 'Endereco')
	            ->setCellValue('I4', 'Numero')
				->setCellValue('J4', 'Bairro')
				->setCellValue('K4', 'CEP')
				->setCellValue('L4', 'Email1')
			->setCellValue('M4', 'Email2')
			->setCellValue('N4', 'DDD1')
			->setCellValue('O4', 'Tel1')
			->setCellValue('P4', 'DDD2')
			->setCellValue('Q4', 'Tel2')
			->setCellValue('R4', 'Cadastro')
			->setCellValue('S4', 'Financeiro')
			->setCellValue('T4', 'Qt_2Sem_18')
			->setCellValue('U4', 'Qt_2019')
			->setCellValue('V4', 'Qt_2020')
			;

	    $index = 5;

		foreach ($query as $item) {

				
			
			$spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$index, $item->cli)
					->setCellValue('B'.$index, $item->razao)
					->setCellValue('C'.$index, $item->fantasia)
					->setCellValue('D'.$index, $item->grupo)
					->setCellValue('E'.$index, $item->subgrupo)
					->setCellValue('F'.$index, $item->uf)
					->setCellValue('G'.$index, $item->municipio)
					->setCellValue('H'.$index, $item->endereco)
					->setCellValue('I'.$index, $item->numero)
					->setCellValue('J'.$index, $item->bairro)
					->setCellValue('k'.$index, $item->cep)
				->setCellValue('L'.$index, $item->email1)
				->setCellValue('M'.$index, $item->email2)
				->setCellValue('N'.$index, $item->ddd1)
				->setCellValue('O'.$index, $item->tel1)
				->setCellValue('P'.$index, $item->ddd2)
				->setCellValue('Q'.$index, $item->tel2)
				->setCellValue('R'.$index, $item->cadastro)
				->setCellValue('S'.$index, $item->financeiro)
				->setCellValue('T'.$index, $item->qtde_2sem_2018)
				->setCellValue('U'.$index, $item->qtde_2019)
				->setCellValue('V'.$index, $item->qtde_2020)
				;
			$index++;

		}            
		$spreadsheet->getActiveSheet()->setTitle('sintetico');


		
		
$spreadsheet->createSheet();	
		// Add some data
		// Rename worksheet
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet

		
$sheet = $spreadsheet->getActiveSheet(1);		
		
$query2 = \DB::select("
select cli, razao, fantasia, grupo, subgrupo, uf, municipio, ah, at, bg, ev, hi, jo, sp, tc from (
select cli,
	case when  ah < 0 then 'comprou'  else ah end as  'ah', 
	case when  at < 0 then 'comprou'  else at end as  'at', 
	case when  bg < 0 then 'comprou'  else bg end as  'bg',
	case when  ev < 0 then 'comprou'  else ev end as  'ev',
	case when  hi < 0 then 'comprou'  else hi end as  'hi',
	case when  jo < 0 then 'comprou'  else jo end as  'jo',
	case when  sp < 0 then 'comprou'  else sp end as  'sp',
	case when  tc < 0 then 'comprou'  else tc end as  'tc'

from (

	select cli,  sum(ah) 'ah', sum(at) 'at', sum(bg) 'bg', sum(ev) 'ev', sum(hi) 'hi', sum(jo) 'jo', sum(sp) 'sp', sum(tc) 'tc'  from (
		select cli, 
			case when grife_cli = 'AH' then qtde_ajust else 0 end as 'AH',
			case when grife_cli = 'AT' then qtde_ajust else 0 end as 'AT',
			case when grife_cli = 'BG' then qtde_ajust else 0 end as 'BG',
			case when grife_cli = 'EV' then qtde_ajust else 0 end as 'EV',
			case when grife_cli = 'HI' then qtde_ajust else 0 end as 'HI',
			case when grife_cli = 'JO' then qtde_ajust else 0 end as 'JO',
			case when grife_cli = 'SP' then qtde_ajust else 0 end as 'SP',
			case when grife_cli = 'TC' then qtde_ajust else 0 end as 'TC'
			
			from (
					select *, case when grife_cli = grife_rep then qtde else qtde*-1 end as qtde_ajust
					from (
						select * from (
							select distinct cli from carteira
							where rep in ($representantes) 
						) as base_cli

						left join (select distinct cli cli_grif, grife grife_cli from carteira )  as base_grif
						on base_grif.cli_grif = base_cli.cli
								
						left join (select distinct cli cli_rep, grife grife_rep from carteira where rep in ($representantes) 
						)  as base_rep
						on base_rep.cli_rep = base_cli.cli and base_rep.grife_rep = base_grif.grife_cli

							left join (
									select codgrife, abc.id cod_cliente,  sum(qtde) qtde
									from vendas_jde as vendas
									left join itens on vendas.id_item = itens.id
									left join addressbook abc on abc.id = vendas.id_cliente	
                                    
									where  ult_status not in ('980','984') 
									 -- and TIMESTAMPDIFF(MONTH, dt_venda,  NOW()) <= 12
									group by codgrife, abc.id
							) as vds
							on vds.codgrife = base_grif.grife_cli and vds.cod_cliente = base_grif.cli_grif
					) as fim1
				) as fim2
			) as fim3
            group by cli
		) as fim4
      ) as fim5      
      
	left join ( select id, razao, fantasia, grupo, subgrupo, uf, municipio from addressbook ) as ab
	on ab.id = fim5.cli 
		
		");


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
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('N')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('O')->setAutoSize(true);

		$spreadsheet->setActiveSheetIndex(1)
				->setCellValue('A1', 'COMPRAS POR GRIFES DESDE 2o. SEMESTRE 2018');
	  

		$spreadsheet->setActiveSheetIndex(1)->setCellValue('A4', 'codcli')
	            ->setCellValue('B4', 'razao')
	            ->setCellValue('C4', 'fantasia')
	            ->setCellValue('D4', 'grupo')
	            ->setCellValue('E4', 'subgrupo')
	            ->setCellValue('F4', 'uf')
	            ->setCellValue('G4', 'municipio')
	            ->setCellValue('H4', 'ah')
	            ->setCellValue('I4', 'at')
	            ->setCellValue('J4', 'bg')
	            ->setCellValue('K4', 'ev')
				->setCellValue('L4', 'hi')
				->setCellValue('M4', 'jo')
				->setCellValue('n4', 'sp')
				->setCellValue('o4', 'tc');

	    $index = 5;

		foreach ($query2 as $item) {

			
			$spreadsheet->setActiveSheetIndex(1)->setCellValue('A'.$index, $item->cli)
					->setCellValue('B'.$index, $item->razao)
					->setCellValue('C'.$index, $item->fantasia)
					->setCellValue('D'.$index, $item->grupo)
					->setCellValue('E'.$index, $item->subgrupo)
					->setCellValue('F'.$index, $item->uf)
					->setCellValue('G'.$index, $item->municipio)
					->setCellValue('H'.$index, $item->ah)
					->setCellValue('I'.$index, $item->at)
					->setCellValue('J'.$index, $item->bg)
					->setCellValue('K'.$index, $item->ev)
					->setCellValue('L'.$index, $item->hi)
					->setCellValue('M'.$index, $item->jo)
					->setCellValue('N'.$index, $item->sp)
					->setCellValue('O'.$index, $item->tc);
			$index++;

		}        


$spreadsheet->getActiveSheet()->setTitle('analitico');




		$spreadsheet->createSheet();	
		// Add some data
		// Rename worksheet
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet

		$sheet = $spreadsheet->getActiveSheet(2);		

		$query3 = \DB::select("
select cli, razao, fantasia, grupo, subgrupo, uf, municipio, regiao, ah, at, bg, ev, hi, jo, sp, tc from (
select cli, regiao,
	case when  ah < 0 then 'comprou'  else ah end as  'ah', 
	case when  at < 0 then 'comprou'  else at end as  'at', 
	case when  bg < 0 then 'comprou'  else bg end as  'bg',
	case when  ev < 0 then 'comprou'  else ev end as  'ev',
	case when  hi < 0 then 'comprou'  else hi end as  'hi',
	case when  jo < 0 then 'comprou'  else jo end as  'jo',
	case when  sp < 0 then 'comprou'  else sp end as  'sp',
	case when  tc < 0 then 'comprou'  else tc end as  'tc'

from (

	select cli, regiao,  sum(ah) 'ah', sum(at) 'at', sum(bg) 'bg', sum(ev) 'ev', sum(hi) 'hi', sum(jo) 'jo', sum(sp) 'sp', sum(tc) 'tc'  from (
		select cli, regiao,
			case when grife_cli = 'AH' then qtde_ajust else 0 end as 'AH',
			case when grife_cli = 'AT' then qtde_ajust else 0 end as 'AT',
			case when grife_cli = 'BG' then qtde_ajust else 0 end as 'BG',
			case when grife_cli = 'EV' then qtde_ajust else 0 end as 'EV',
			case when grife_cli = 'HI' then qtde_ajust else 0 end as 'HI',
			case when grife_cli = 'JO' then qtde_ajust else 0 end as 'JO',
			case when grife_cli = 'SP' then qtde_ajust else 0 end as 'SP',
			case when grife_cli = 'TC' then qtde_ajust else 0 end as 'TC'
			
			from (
					select *, case when grife_cli = grife_rep then qtde else qtde*-1 end as qtde_ajust
					from (
						select * from (
							select  cli, regiao from carteira
							where rep in ($representantes)
							group by  cli, regiao
						) as base_cli

						left join (select distinct cli cli_grif, grife grife_cli from carteira )  as base_grif
						on base_grif.cli_grif = base_cli.cli
								
						left join (select distinct cli cli_rep, grife grife_rep from carteira where rep in ($representantes) 
						)  as base_rep
						on base_rep.cli_rep = base_cli.cli and base_rep.grife_rep = base_grif.grife_cli

							left join (
									select codgrife, abc.id cod_cliente,  sum(qtde) qtde
									from vendas_jde as vendas
									left join itens on vendas.id_item = itens.id
									left join addressbook abc on abc.id = vendas.id_cliente	
                                    
									where  ult_status not in ('980','984') 
									and TIMESTAMPDIFF(MONTH, dt_venda,  NOW()) <= 4
									group by codgrife, abc.id
							) as vds
							on vds.codgrife = base_grif.grife_cli and vds.cod_cliente = base_grif.cli_grif
					) as fim1
				) as fim2
			) as fim3
            group by cli, regiao
		) as fim4
      ) as fim5      
      
	left join ( select id, razao, fantasia, grupo, subgrupo, uf, municipio from addressbook ) as ab
	on ab.id = fim5.cli ");


		
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
				->setCellValue('A1', 'COMPRAS POR GRIFES NOS ULTIMOS 4 MESES');
	  

		$spreadsheet->setActiveSheetIndex(2)->setCellValue('A4', 'codcli')
	            ->setCellValue('B4', 'razao')
	            ->setCellValue('C4', 'fantasia')
	            ->setCellValue('D4', 'grupo')
	            ->setCellValue('E4', 'subgrupo')
	            ->setCellValue('F4', 'uf')
	            ->setCellValue('G4', 'municipio')
	            ->setCellValue('H4', 'regiao')
	            ->setCellValue('I4', 'ah')
	            ->setCellValue('J4', 'at')
	            ->setCellValue('K4', 'bg')
	            ->setCellValue('L4', 'ev')
				->setCellValue('M4', 'hi')
				->setCellValue('N4', 'jo')
				->setCellValue('O4', 'sp')
				->setCellValue('P4', 'tc');

	    $index = 5;

		foreach ($query3 as $item) {

			
			$spreadsheet->setActiveSheetIndex(2)->setCellValue('A'.$index, $item->cli)
					->setCellValue('B'.$index, $item->razao)
					->setCellValue('C'.$index, $item->fantasia)
					->setCellValue('D'.$index, $item->grupo)
					->setCellValue('E'.$index, $item->subgrupo)
					->setCellValue('F'.$index, $item->uf)
					->setCellValue('G'.$index, $item->municipio)
					->setCellValue('H'.$index, $item->regiao)
					->setCellValue('I'.$index, $item->ah)
					->setCellValue('J'.$index, $item->at)
					->setCellValue('K'.$index, $item->bg)
					->setCellValue('L'.$index, $item->ev)
					->setCellValue('M'.$index, $item->hi)
					->setCellValue('N'.$index, $item->jo)
					->setCellValue('O'.$index, $item->sp)
					->setCellValue('P'.$index, $item->tc);
			$index++;

		}        


		$spreadsheet->getActiveSheet()->setTitle('ult_4meses');

		
		
		$spreadsheet->createSheet();	

		$sheet = $spreadsheet->getActiveSheet(3);		

		$query4 = \DB::select("
select cli, razao, fantasia, grupo, subgrupo, uf, municipio, ah, at, bg, ev, hi, jo, sp, tc from (
select cli,
	case when  ah < 0 then 'comprou'  else ah end as  'ah', 
	case when  at < 0 then 'comprou'  else at end as  'at', 
	case when  bg < 0 then 'comprou'  else bg end as  'bg',
	case when  ev < 0 then 'comprou'  else ev end as  'ev',
	case when  hi < 0 then 'comprou'  else hi end as  'hi',
	case when  jo < 0 then 'comprou'  else jo end as  'jo',
	case when  sp < 0 then 'comprou'  else sp end as  'sp',
	case when  tc < 0 then 'comprou'  else tc end as  'tc'

from (

	select cli,  sum(ah) 'ah', sum(at) 'at', sum(bg) 'bg', sum(ev) 'ev', sum(hi) 'hi', sum(jo) 'jo', sum(sp) 'sp', sum(tc) 'tc'  from (
		select cli, 
			case when grife_cli = 'AH' then qtde_ajust else 0 end as 'AH',
			case when grife_cli = 'AT' then qtde_ajust else 0 end as 'AT',
			case when grife_cli = 'BG' then qtde_ajust else 0 end as 'BG',
			case when grife_cli = 'EV' then qtde_ajust else 0 end as 'EV',
			case when grife_cli = 'HI' then qtde_ajust else 0 end as 'HI',
			case when grife_cli = 'JO' then qtde_ajust else 0 end as 'JO',
			case when grife_cli = 'SP' then qtde_ajust else 0 end as 'SP',
			case when grife_cli = 'TC' then qtde_ajust else 0 end as 'TC'
			
			from (
					select *, case when grife_cli = grife_rep then qtde else qtde*-1 end as qtde_ajust
					from (
						select * from (
							select distinct cli from carteira
							where rep in ($representantes) 
						) as base_cli

						left join (select distinct cli cli_grif, grife grife_cli from carteira )  as base_grif
						on base_grif.cli_grif = base_cli.cli
								
						left join (select distinct cli cli_rep, grife grife_rep from carteira where rep in ($representantes) 
						)  as base_rep
						on base_rep.cli_rep = base_cli.cli and base_rep.grife_rep = base_grif.grife_cli

							left join (
									select codgrife, abc.id cod_cliente,  sum(qtde) qtde
									from vendas_jde as vendas
									left join itens on vendas.id_item = itens.id
									left join addressbook abc on abc.id = vendas.id_cliente	
                                    
									where  ult_status not in ('980','984') 
									and TIMESTAMPDIFF(MONTH, dt_venda,  NOW()) <= 4
									group by codgrife, abc.id
							) as vds
							on vds.codgrife = base_grif.grife_cli and vds.cod_cliente = base_grif.cli_grif
					) as fim1
				) as fim2
			) as fim3
            group by cli
		) as fim4
      ) as fim5      
      
	left join ( select id, razao, fantasia, grupo, subgrupo, uf, municipio from addressbook ) as ab
	on ab.id = fim5.cli");


		
		$spreadsheet->setActiveSheetIndex(3)->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(3)->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(3)->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(3)->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(3)->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(3)->getColumnDimension('G')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(3)->getColumnDimension('H')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(3)->getColumnDimension('I')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(3)->getColumnDimension('J')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(3)->getColumnDimension('K')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(3)->getColumnDimension('L')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(3)->getColumnDimension('M')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(3)->getColumnDimension('N')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(3)->getColumnDimension('O')->setAutoSize(true);

		$spreadsheet->setActiveSheetIndex(3)
				->setCellValue('A1', 'COMPRAS POR GRIFES NOS ULTIMOS 12 MESES');
	  

		$spreadsheet->setActiveSheetIndex(3)->setCellValue('A4', 'codcli')
	            ->setCellValue('B4', 'razao')
	            ->setCellValue('C4', 'fantasia')
	            ->setCellValue('D4', 'grupo')
	            ->setCellValue('E4', 'subgrupo')
	            ->setCellValue('F4', 'uf')
	            ->setCellValue('G4', 'municipio')
	            ->setCellValue('H4', 'ah')
	            ->setCellValue('I4', 'at')
	            ->setCellValue('J4', 'bg')
	            ->setCellValue('K4', 'ev')
				->setCellValue('L4', 'hi')
				->setCellValue('M4', 'jo')
				->setCellValue('n4', 'sp')
				->setCellValue('o4', 'tc');

	    $index = 5;

		foreach ($query4 as $item) {

			
			$spreadsheet->setActiveSheetIndex(3)->setCellValue('A'.$index, $item->cli)
					->setCellValue('B'.$index, $item->razao)
					->setCellValue('C'.$index, $item->fantasia)
					->setCellValue('D'.$index, $item->grupo)
					->setCellValue('E'.$index, $item->subgrupo)
					->setCellValue('F'.$index, $item->uf)
					->setCellValue('G'.$index, $item->municipio)
					->setCellValue('H'.$index, $item->ah)
					->setCellValue('I'.$index, $item->at)
					->setCellValue('J'.$index, $item->bg)
					->setCellValue('K'.$index, $item->ev)
					->setCellValue('L'.$index, $item->hi)
					->setCellValue('M'.$index, $item->jo)
					->setCellValue('N'.$index, $item->sp)
					->setCellValue('O'.$index, $item->tc);
			$index++;

		}        


		$spreadsheet->getActiveSheet()->setTitle('ult_12meses');


		
		
		
$spreadsheet->createSheet();	
		// Add some data
		// Rename worksheet
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet

		
$sheet = $spreadsheet->getActiveSheet(4);		
		
$query21 = \DB::select("
select cli, razao, fantasia, grupo, subgrupo, uf, municipio, ano, ah, at, bg, ev, hi, jo, sp, tc from (
select cli, ano,
	case when  ah < 0 then 'comprou'  else ah end as  'ah', 
	case when  at < 0 then 'comprou'  else at end as  'at', 
	case when  bg < 0 then 'comprou'  else bg end as  'bg',
	case when  ev < 0 then 'comprou'  else ev end as  'ev',
	case when  hi < 0 then 'comprou'  else hi end as  'hi',
	case when  jo < 0 then 'comprou'  else jo end as  'jo',
	case when  sp < 0 then 'comprou'  else sp end as  'sp',
	case when  tc < 0 then 'comprou'  else tc end as  'tc'

from (

	select cli, ano,  sum(ah) 'ah', sum(at) 'at', sum(bg) 'bg', sum(ev) 'ev', sum(hi) 'hi', sum(jo) 'jo', sum(sp) 'sp', sum(tc) 'tc'  from (
		select cli, ano,
			case when grife_cli = 'AH' then qtde_ajust else 0 end as 'AH',
			case when grife_cli = 'AT' then qtde_ajust else 0 end as 'AT',
			case when grife_cli = 'BG' then qtde_ajust else 0 end as 'BG',
			case when grife_cli = 'EV' then qtde_ajust else 0 end as 'EV',
			case when grife_cli = 'HI' then qtde_ajust else 0 end as 'HI',
			case when grife_cli = 'JO' then qtde_ajust else 0 end as 'JO',
			case when grife_cli = 'SP' then qtde_ajust else 0 end as 'SP',
			case when grife_cli = 'TC' then qtde_ajust else 0 end as 'TC'
			
			from (
					select *, case when grife_cli = grife_rep then qtde else qtde*-1 end as qtde_ajust
					from (
						select * from (
							select distinct cli from carteira
                            -- where cli = 8110
						 	where rep in ($representantes) 
						) as base_cli

						left join (select distinct cli cli_grif, grife grife_cli from carteira )  as base_grif
						on base_grif.cli_grif = base_cli.cli
								
						left join (select distinct cli cli_rep, grife grife_rep from carteira  where rep in ($representantes) 
						)  as base_rep
						on base_rep.cli_rep = base_cli.cli and base_rep.grife_rep = base_grif.grife_cli

							left join (
									select codgrife, abc.id cod_cliente, year(dt_venda) ano,  sum(qtde) qtde
									from vendas_jde as vendas
									left join itens on vendas.id_item = itens.id
									left join addressbook abc on abc.id = vendas.id_cliente	
                                    
									where  ult_status not in ('980','984') 
									 -- and TIMESTAMPDIFF(MONTH, dt_venda,  NOW()) <= 12
									group by codgrife, abc.id, year(dt_venda)
							) as vds
							on vds.codgrife = base_grif.grife_cli and vds.cod_cliente = base_grif.cli_grif
					) as fim1
				) as fim2
			) as fim3
			where ano is not null 
            group by cli, ano
		) as fim4
      ) as fim5      
      
	left join ( select id, razao, fantasia, grupo, subgrupo, uf, municipio from addressbook ) as ab
	on ab.id = fim5.cli 
		
		");


		$spreadsheet->setActiveSheetIndex(4)->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(4)->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(4)->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(4)->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(4)->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(4)->getColumnDimension('G')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(4)->getColumnDimension('H')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(4)->getColumnDimension('I')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(4)->getColumnDimension('J')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(4)->getColumnDimension('K')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(4)->getColumnDimension('L')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(4)->getColumnDimension('M')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(4)->getColumnDimension('N')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(4)->getColumnDimension('O')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(4)->getColumnDimension('P')->setAutoSize(true);

		$spreadsheet->setActiveSheetIndex(4)
				->setCellValue('A1', 'COMPRAS POR GRIFES E ANO DESDE 2o. SEMESTRE 2018');
	  

		$spreadsheet->setActiveSheetIndex(4)->setCellValue('A4', 'codcli')
	            ->setCellValue('B4', 'razao')
	            ->setCellValue('C4', 'fantasia')
	            ->setCellValue('D4', 'grupo')
	            ->setCellValue('E4', 'subgrupo')
	            ->setCellValue('F4', 'uf')
	            ->setCellValue('G4', 'municipio')
				->setCellValue('H4', 'ano')
	            ->setCellValue('I4', 'ah')
	            ->setCellValue('J4', 'at')
	            ->setCellValue('K4', 'bg')
	            ->setCellValue('L4', 'ev')
				->setCellValue('M4', 'hi')
				->setCellValue('N4', 'jo')
				->setCellValue('O4', 'sp')
				->setCellValue('P4', 'tc');

	    $index = 5;

		foreach ($query21 as $item) {

			
			$spreadsheet->setActiveSheetIndex(4)->setCellValue('A'.$index, $item->cli)
					->setCellValue('B'.$index, $item->razao)
					->setCellValue('C'.$index, $item->fantasia)
					->setCellValue('D'.$index, $item->grupo)
					->setCellValue('E'.$index, $item->subgrupo)
					->setCellValue('F'.$index, $item->uf)
					->setCellValue('G'.$index, $item->municipio)
					->setCellValue('H'.$index, $item->ano)
					->setCellValue('I'.$index, $item->ah)
					->setCellValue('J'.$index, $item->at)
					->setCellValue('K'.$index, $item->bg)
					->setCellValue('L'.$index, $item->ev)
					->setCellValue('M'.$index, $item->hi)
					->setCellValue('N'.$index, $item->jo)
					->setCellValue('O'.$index, $item->sp)
					->setCellValue('P'.$index, $item->tc);
			$index++;

		}        


$spreadsheet->getActiveSheet()->setTitle('analitico_ano');
		
		
				
		
/** fim das planilhas e retorna a planilha 0 **/		
		
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


