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
		$grifes = \Session::get('grifes');

		
		
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

    	$query = \DB::select("
		
	select FORMAT(sum(ind12),3) ind12, FORMAT(sum(ind18),3) ind18 from (
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
		
		//dd('manutencao');
		
		$usuario = \App\Usuario::where('id_addressbook', $request->id)->first();
		
		if($usuario->id==498 or $usuario->id==1529){
		return redirect()->back();	
		}
		//230270 47406
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

		
		
		
		
		
		
		

// SEGUNDA ABA
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
					and year(dt_venda) = (select max(ano) from fechamentos) 
					
					and month(dt_venda) = (select max(mes) from fechamentos) + 1
                    and dt_venda <= date(now())  
					
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
					
					and year(dt_venda) = (select max(ano) from fechamentos) 
					and month(dt_venda) = (select max(mes) from fechamentos) + 1
                    and dt_venda <= date(now())
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


		
		

// QUINTA ABA
		$spreadsheet->createSheet();	

		$sheet = $spreadsheet->getActiveSheet(4);		

	    $query5 = \DB::select("


	select *, concat(id_cli,' - ',fantasia_cli) cliente, concat(id_repres,' - ',fantasia_rep) representante from (
		select id_rep, id_cliente, pc_cliente, num_venda, dt_venda, dt_emissao_venda, condpag, desconto, 
        
		sum(qtde_venda) qtde_venda, 					sum(vlr_venda) vlr_venda, 

		sum(qtde_venda_aberto) qtde_venda_aberto, 		sum(vlr_venda_aberto) vlr_venda_aberto, 

		sum(qtde_venda_ped) qtde_venda_emitida, 		sum(vlr_venda_ped) vlr_venda_emitida, 
		sum(qtde_venda_orcamento) qtde_venda_orcamento, sum(vlr_venda_orcamento) vlr_venda_orcamento, 
		sum(qtde_venda_canc) qtde_venda_canc, 			sum(vlr_venda_canc) vlr_venda_canc,

		sum(qtde_so_faturado) qtde_so_faturado,  		sum(vlr_so_faturado) vlr_so_faturado, 
		
        case when sum(qtde_venda)=sum(qtde_venda_aberto) then 0 else sum(qtde_so_aberto) end as qtde_so_aberto,  			
        case when sum(qtde_venda)=sum(qtde_venda_aberto) then 0 else sum(vlr_so_aberto) end as vlr_so_aberto, 
        
        
        sum(qtde_so_reaberto) qtde_so_reaberto,  		sum(vlr_so_reaberto) vlr_so_reaberto, 
		sum(qtde_so_cancelado) qtde_so_cancelado,  		sum(vlr_so_cancelado) vlr_so_cancelado,
        
        -- colunas para o relatorio
        sum(vlr_so_reaberto)+sum(vlr_venda_orcamento) sq_orcamento,
        sum(vlr_venda_ped)-sum(vlr_so_reaberto) sq_emitido
        
        
        
        

		from (

				select vds.id, vds.linha,  vds.id_rep, vds.id_cliente, vds.pc_cliente, vds.id_item, vds.pedido num_venda, vds.dt_emissao dt_emissao_venda, vds.dt_venda dt_venda, 
				vds.ult_status ult_status_venda, vds.prox_status prox_status_venda, itens.codgrife, vds.condpag, vds.desconto, vds.qtde qtde_venda, vds.valor vlr_venda,
				
				case when concat(vds.ult_status,'/',vds.prox_status) in ('500/505','505/510','510/512') then vds.qtde else 0 end as qtde_venda_aberto,
				case when concat(vds.ult_status,'/',vds.prox_status) in ('500/505','505/510','510/512') then vds.valor else 0 end as vlr_venda_aberto,
		  
				case when concat(vds.ult_status,'/',vds.prox_status) in ('510/999','512/999','515/999','516/999') then vds.qtde else 0 end as qtde_venda_ped,
				case when concat(vds.ult_status,'/',vds.prox_status) in ('510/999','512/999','515/999','516/999') then vds.valor else 0 end as vlr_venda_ped,
			   
				case when vds.prox_status in ('515','516') then vds.qtde else 0 end as qtde_venda_orcamento,
				case when vds.prox_status in ('515','516') then vds.valor else 0 end as vlr_venda_orcamento,
				
				case when vds.ult_status in ('980','984') then vds.qtde else 0 end as qtde_venda_canc,
				case when vds.ult_status in ('980','984') then vds.valor else 0 end as vlr_venda_canc
			

					, ifnull((select sum(so.qtde)  from pedidos_jde so where so.id_item = vds.id_item and so.ped_original = vds.pedido and so.linha_original = vds.linha and so.ult_status in ('980','984')),0) qtde_so_cancelado
					, ifnull((select sum(so.valor) from pedidos_jde so where so.id_item = vds.id_item and so.ped_original = vds.pedido and so.linha_original = vds.linha and so.ult_status in ('980','984')),0) vlr_so_cancelado

					, ifnull((select sum(so.qtde)  from pedidos_jde so where so.id_item = vds.id_item and so.ped_original = vds.pedido and so.linha_original = vds.linha and so.ult_status like '6%'),0) qtde_so_faturado
					, ifnull((select sum(so.valor) from pedidos_jde so where so.id_item = vds.id_item and so.ped_original = vds.pedido and so.linha_original = vds.linha and so.ult_status like '6%'),0) vlr_so_faturado

					, ifnull((select sum(so.qtde)  from pedidos_jde so where so.id_item = vds.id_item and so.ped_original = vds.pedido and so.linha_original = vds.linha and (so.ult_status like '5%')),0) qtde_so_aberto            
					, ifnull((select sum(so.valor) from pedidos_jde so where so.id_item = vds.id_item and so.ped_original = vds.pedido and so.linha_original = vds.linha and (so.ult_status like '5%')),0) vlr_so_aberto

					, ifnull((select sum(so.qtde)  from pedidos_jde so where so.id_item = vds.id_item and so.ped_original = vds.pedido and so.linha_original = vds.linha and (so.ult_status in ('902','904','912','914'))),0) qtde_so_reaberto            
					, ifnull((select sum(so.valor) from pedidos_jde so where so.id_item = vds.id_item and so.ped_original = vds.pedido and so.linha_original = vds.linha and (so.ult_status in ('902','904','912','914'))),0) vlr_so_reaberto


					from vendas_jde as vds
					left join itens on vds.id_item = itens.id
					
					where  itens.codtipoitem = '006' and vds.ult_status not in ('980','984') and vds.id_rep in ($representantes) 
                    
					and year(vds.dt_venda) = (select max(ano) from fechamentos) 
				    and month(vds.dt_venda) = (select max(mes) from fechamentos) + 1 
					and vds.dt_venda <= date(now())
		) as fim
		group by id_rep, id_cliente, pc_cliente, num_venda, dt_venda, dt_emissao_venda, condpag, desconto
	) as fim2

	left join (select id id_repres, razao razao_rep, fantasia fantasia_rep, nome nome_rep from addressbook abr ) as abr
    on abr.id_repres = fim2.id_rep
    
    left join (select id id_cli, razao razao_cli, fantasia fantasia_cli, nome nome_cli from addressbook abc ) as abc
    on abc.id_cli = fim2.id_cliente


order by dt_emissao_venda desc
");
		
		$spreadsheet->setActiveSheetIndex(4)
		->setCellValue('A1', 'MAPA ANALITICO DE VENDAS')
		->setCellValue('I3', '||')	
		->setCellValue('J3', 'PRE-VENDA TOTAL')
		->setCellValue('L3', '||')	
		->setCellValue('M3', 'VENDAS')
		->setCellValue('P3', '||')
		->setCellValue('Q3', 'PEDIDOS');
		
		
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
		$spreadsheet->setActiveSheetIndex(4)->getColumnDimension('Q')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(4)->getColumnDimension('R')->setAutoSize(true);
	
		
		
	/**	$spreadsheet->getActiveSheet()->getStyle('F:S')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); **/

	/** id_rep	id_cliente	pc_cliente	num_venda	dt_venda	dt_emissao_venda	codgrife	condpag	desconto	
	qtde_venda	vlr_venda	
	
	qtde_venda_emitida	vlr_venda_emitida	
	qtde_venda_orcamento	vlr_venda_orcamento	
	qtde_venda_canc	vlr_venda_canc	
	
	qtde_so_faturado	vlr_so_faturado	
	qtde_so_aberto	vlr_so_aberto	
	qtde_so_cancelado	vlr_so_cancelado
	**/
		
		$spreadsheet->setActiveSheetIndex(4)
					->setCellValue('B4', 'Cliente')
					->setCellValue('C4', 'pc_cliente')
					->setCellValue('D4', 'num_venda')
					->setCellValue('E4', 'dt_venda')
					->setCellValue('F4', 'dt_emissao_venda')
					->setCellValue('G4', 'condpag')
					->setCellValue('H4', 'desconto')
								
								->setCellValue('I4', '||')
					->setCellValue('J4', 'qtde_venda')
					->setCellValue('K4', 'vlr_venda')

								->setCellValue('L4', '||')
					->setCellValue('M4', 'vlr_venda_aberto')
					->setCellValue('N4', 'vlr_venda_emitida')
					->setCellValue('O4', 'vlr_venda_orcamento')		
				
								->setCellValue('P4', '||')
					->setCellValue('Q4', 'vlr_so_faturado')
					->setCellValue('R4', 'vlr_so_aberto')
	
					->setCellValue('S4', 'Representante')
					;

	    $index = 5;

		
		foreach ($query5 as $item) {

			
			$spreadsheet->setActiveSheetIndex(4)
						->setCellValue('B'.$index, $item->cliente)
						->setCellValue('C'.$index, $item->pc_cliente)				
						->setCellValue('D'.$index, $item->num_venda)
						->setCellValue('E'.$index, $item->dt_venda)
						->setCellValue('F'.$index, $item->dt_emissao_venda)
						->setCellValue('G'.$index, $item->condpag)
						->setCellValue('H'.$index, $item->desconto)	

				->setCellValue('I'.$index, '||')				
						->setCellValue('J'.$index, $item->qtde_venda)
						->setCellValue('K'.$index, $item->vlr_venda)

				->setCellValue('L'.$index, '||')				
						->setCellValue('M'.$index, $item->vlr_venda_aberto)
						->setCellValue('N'.$index, $item->sq_emitido)
						->setCellValue('O'.$index, $item->sq_orcamento)

				->setCellValue('P'.$index, '||')
						->setCellValue('Q'.$index, $item->vlr_so_faturado)
						->setCellValue('R'.$index, $item->vlr_so_aberto)
				
						->setCellValue('S'.$index, $item->representante)
						;
			$index++;

		}        


		$spreadsheet->getActiveSheet()->setTitle('mapa_analitico');
		// FIM DA QUINTA ABA

		
		
// SEXTA ABA
		$spreadsheet->createSheet();	

		$sheet = $spreadsheet->getActiveSheet(5);		

	    $query6 = \DB::select("
		select * from (
			select pedido, cod_cliente, codigo, suspensao, nome_rep, sum(valor) valor from (
				
                select vendas.pedido, id_rep, case when abr.nome = '' then abr.fantasia else abr.nome end as repres, id_cliente cod_cliente, codgrife, (valor) valor,
				suspensoes.codigo, suspensoes.suspensao, case when abr.nome = '' then abr.fantasia else abr.nome end as nome_rep

				from vendas_jde as vendas
				left join itens on vendas.id_item = itens.id
				left join addressbook abr on abr.id = vendas.id_rep
				left join suspensoes on vendas.pedido = suspensoes.pedido and suspensoes.tipo = vendas.tipo

				where  ult_status not in ('980','984') and suspensoes.codigo is not null and id_rep in ($representantes)

				and year(dt_venda) = (select max(ano) from fechamentos) 
					-- and month(dt_venda) = (select max(mes) from fechamentos) + 1
				and dt_venda <= date(now())
                
			) as fim
			group by pedido, cod_cliente, codigo, suspensao, nome_rep
            
		) as base

		left join (select id, razao, fantasia, grupo, subgrupo, uf, municipio, endereco, numero, bairro, cep, financeiro, cadastro, email1, email2, ddd1, tel1, ddd2, tel2 from addressbook ) as ab
		on ab.id = base.cod_cliente ");


		
		$spreadsheet->setActiveSheetIndex(5)
		->setCellValue('A1', 'Pedidos Suspensos');	
			
		$spreadsheet->setActiveSheetIndex(5)->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(5)->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(5)->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(5)->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(5)->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(5)->getColumnDimension('G')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(5)->getColumnDimension('H')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(5)->getColumnDimension('I')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(5)->getColumnDimension('J')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(5)->getColumnDimension('K')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(5)->getColumnDimension('L')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(5)->getColumnDimension('M')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(5)->getColumnDimension('N')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(5)->getColumnDimension('O')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(5)->getColumnDimension('P')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(5)->getColumnDimension('Q')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(5)->getColumnDimension('R')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(5)->getColumnDimension('S')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(5)->getColumnDimension('T')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(5)->getColumnDimension('U')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(5)->getColumnDimension('V')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(5)->getColumnDimension('W')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(5)->getColumnDimension('X')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(5)->getColumnDimension('Y')->setAutoSize(true);
	
		/**	$spreadsheet->getActiveSheet()->getStyle('F:S')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); **/
		
		
		$spreadsheet->setActiveSheetIndex(5)
				->setCellValue('A4', 'pedido')	
				->setCellValue('B4', 'codcli')
	            ->setCellValue('C4', 'Razao_Social')
	            ->setCellValue('D4', 'Fantasia')
	            ->setCellValue('E4', 'Grupo')
	            ->setCellValue('F4', 'Subgrupo')
	            ->setCellValue('G4', 'UF')
	            ->setCellValue('H4', 'Municipio')
	            ->setCellValue('I4', 'Endereco')
	            ->setCellValue('J4', 'Numero')
				->setCellValue('K4', 'Bairro')
				->setCellValue('L4', 'CEP')
				->setCellValue('M4', 'Regiao')
				->setCellValue('N4', 'Email1')
				->setCellValue('O4', 'Email2')
				->setCellValue('P4', 'DDD1')
				->setCellValue('Q4', 'Tel1')
				->setCellValue('R4', 'DDD2')
				->setCellValue('S4', 'Tel2')
				->setCellValue('T4', 'Cadastro')
				->setCellValue('U4', 'Financeiro')	
				->setCellValue('V4', 'Tipo_suspensao')	
				->setCellValue('W4', 'Suspensao')	
				->setCellValue('X4', 'valor')
				->setCellValue('Y4', 'Rep')
					;


	    $index = 5;

		
		foreach ($query6 as $item) {

			
			$spreadsheet->setActiveSheetIndex(5)
					->setCellValue('A'.$index, $item->pedido)
					->setCellValue('B'.$index, $item->cod_cliente)
					->setCellValue('C'.$index, $item->razao)
					->setCellValue('D'.$index, $item->fantasia)
					->setCellValue('E'.$index, $item->grupo)
					->setCellValue('F'.$index, $item->subgrupo)
					->setCellValue('G'.$index, $item->uf)
					->setCellValue('H'.$index, $item->municipio)
					->setCellValue('I'.$index, $item->endereco)
					->setCellValue('J'.$index, $item->numero)
					->setCellValue('K'.$index, $item->bairro)
					->setCellValue('L'.$index, $item->cep)
					->setCellValue('M'.$index, $item->uf) /**mudar para regiao**/
					->setCellValue('N'.$index, $item->email1)
					->setCellValue('O'.$index, $item->email2)
					->setCellValue('P'.$index, $item->ddd1)
					->setCellValue('Q'.$index, $item->tel1)
					->setCellValue('R'.$index, $item->ddd2)
					->setCellValue('S'.$index, $item->tel2)
					->setCellValue('T'.$index, $item->cadastro)
					->setCellValue('U'.$index, $item->financeiro)
					->setCellValue('V'.$index, $item->codigo)
					->setCellValue('W'.$index, $item->suspensao)
					->setCellValue('X'.$index, $item->valor)
					->setCellValue('Y'.$index, $item->nome_rep)
				;
$index++;

		}        


		$spreadsheet->getActiveSheet()->setTitle('pedidos_suspensos');
		// FIM DA SEXTA ABA

		

		// SETIMA ABA
		$spreadsheet->createSheet();	

		$sheet = $spreadsheet->getActiveSheet(6);		

	    $query7 = \DB::select("

		select * from (
			select codgrife, id_rep, repres, nome_rep, sum(valor) valor_total, 
            (sum(col_2021)/sum(valor))*100 col_2021, (sum(col_2020)/sum(valor))*100 col_2020, (sum(col_2019)/sum(valor))*100 col_2019, 
            (sum(col_2018)/sum(valor))*100 col_2018, (sum(col_menor_2017)/sum(valor))*100 col_menor_2017
            from (
				select  vendas.pedido, id_rep, case when abr.nome = '' then abr.fantasia else abr.nome end as repres, id_cliente cod_cliente, 
				case when abr.nome = '' then abr.fantasia else abr.nome end as nome_rep,valor, 
                colmod, 
                case when left(colmod,4) = 2021 then valor else 0 end as col_2021,
                case when left(colmod,4) = 2020 then valor else 0 end as col_2020,
                case when left(colmod,4) = 2019 then valor else 0 end as col_2019,
                case when left(colmod,4) = 2018 then valor else 0 end as col_2018,
                case when left(colmod,4) <= 2017 then valor else 0 end as col_menor_2017,
                codgrife
 
				from vendas_jde as vendas
				left join itens on vendas.id_item = itens.id
				left join addressbook abr on abr.id = vendas.id_rep

				where  ult_status not in ('980','984')   and id_rep in ($representantes)

				and year(dt_venda) = (select max(ano) from fechamentos) 
						-- and month(dt_venda) = (select max(mes) from fechamentos) + 1
				and dt_venda <= date(now())
			) as fim
            group by codgrife, id_rep, repres, nome_rep
		) as base


left join (
	select id_rep rep, grife, count(itens) itens_mala,
	(sum(col_2021)/count(itens))*100 mala_2021, (sum(col_2020)/count(itens))*100 mala_2020, (sum(col_2019)/count(itens))*100 mala_2019, 
	(sum(col_2018)/count(itens))*100 mala_2018, (sum(col_menor_2017)/count(itens))*100 mala_menor_2017
	from (

			select id_rep, itens.id itens, codgrife grife, 
			case when left(colmod,4) = 2021 then 1 else 0 end as col_2021,
			case when left(colmod,4) = 2020 then 1 else 0 end as col_2020,
			case when left(colmod,4) = 2019 then 1 else 0 end as col_2019,
			case when left(colmod,4) = 2018 then 1 else 0 end as col_2018,
			case when left(colmod,4) <= 2017 then 1 else 0 end as col_menor_2017

			from malas 
			left join itens on malas.id_item = itens.id
			where local = 'mala'
			
	) as fim
	group by id_rep, grife
) as mala
on mala.rep = base.id_rep and mala.grife = base.codgrife

order by codgrife, col_2020 desc
		
		");


		
		$spreadsheet->setActiveSheetIndex(6)
		->setCellValue('A1', 'Distribuicoes por colecoes');	
			
		$spreadsheet->setActiveSheetIndex(6)->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(6)->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(6)->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(6)->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(6)->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(6)->getColumnDimension('G')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(6)->getColumnDimension('H')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(6)->getColumnDimension('I')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(6)->getColumnDimension('J')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(6)->getColumnDimension('K')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(6)->getColumnDimension('L')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(6)->getColumnDimension('M')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(6)->getColumnDimension('N')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(6)->getColumnDimension('O')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(6)->getColumnDimension('P')->setAutoSize(true);
		
		
		/**	$spreadsheet->getActiveSheet()->getStyle('F:S')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); **/
		
		
		$spreadsheet->setActiveSheetIndex(6)
				->setCellValue('A4', 'codgrife')	
				->setCellValue('B4', 'id_rep')
	            ->setCellValue('C4', 'nome_rep')
	            ->setCellValue('D4', 'valor_total')
	            ->setCellValue('E4', 'col_2021')
	            ->setCellValue('F4', 'col_2020')
	            ->setCellValue('G4', 'col_2019')
	            ->setCellValue('H4', 'col_2018')
	            ->setCellValue('I4', 'col_menor_2017')
			
			->setCellValue('J4', 'itens_mala')	
			->setCellValue('K4', 'mala_2021')	
			->setCellValue('L4', 'mala_2020')	
			->setCellValue('M4', 'mala_2019')	
			->setCellValue('N4', 'mala_2018')	
			->setCellValue('O4', 'mala_menor_2017')	
	        		;


	    $index = 5;

		
		foreach ($query7 as $item) {

			
			$spreadsheet->setActiveSheetIndex(6)
					->setCellValue('A'.$index, $item->codgrife)
					->setCellValue('B'.$index, $item->id_rep)
					->setCellValue('C'.$index, $item->nome_rep)
					->setCellValue('D'.$index, $item->valor_total)
					->setCellValue('E'.$index, $item->col_2021)
					->setCellValue('F'.$index, $item->col_2020)
					->setCellValue('G'.$index, $item->col_2019)
					->setCellValue('H'.$index, $item->col_2018)
					->setCellValue('I'.$index, $item->col_menor_2017)
				->setCellValue('J'.$index, $item->itens_mala)
				->setCellValue('K'.$index, $item->mala_2021)
				->setCellValue('L'.$index, $item->mala_2020)
				->setCellValue('M'.$index, $item->mala_2019)
				->setCellValue('N'.$index, $item->mala_2018)
				->setCellValue('O'.$index, $item->mala_menor_2017)
				
				;
$index++;

		}        


		$spreadsheet->getActiveSheet()->setTitle('perfil_vda');
		// FIM DA SETIMA ABA
		
		
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
		
		

		$usuario = \App\Usuario::where('id_addressbook', $request->id)->first();
		if($usuario->id==498 or $usuario->id==1529){
		return redirect()->back();	
		}
    	$sql = '';

		$limite = '1';

	    	if ($usuario->id_perfil == 5) {

	    		$sql = ' and diretor = '.$usuario->id_addressbook;
				$sql1 = ' fid.id_diretor = '.$usuario->id_addressbook;
				$representantes = \Session::get('representantes');
				$grifes = \Session::get('grifes');
			
				$limite = '1';

	    	}


	    	if ($usuario->id_perfil == 6) {

	    		$sql = ' and supervisor = '.$usuario->id_addressbook;
				$sql1 = ' fid.id_supervisor = '.$usuario->id_addressbook;
				$representantes = \Session::get('representantes');
			$grifes = \Session::get('grifes');
				$limite = '1';

	    	}

	    	if ($usuario->id_perfil == 4) {

	    		$sql = ' and repres = '.$usuario->id_addressbook;
				$sql1 = ' fid.id_rep = '.$usuario->id_addressbook;
				$representantes = \Session::get('representantes');
			$grifes = \Session::get('grifes');
				
				$limite = '1';

	    	}	
		
		
		if ($usuario->id_perfil == 1) {

	    		$sql = ' and repres = '.$usuario->id_addressbook;
				$sql1 = ' fid.id_rep = '.$usuario->id_addressbook;
				$representantes = \Session::get('representantes');
			$grifes = \Session::get('grifes');
		
			$limite = '1';

	    	}	
	   
/**	 dd($grifes); **/
	 		

$query = \DB::select("


select ab.*, fim4.* from (
	select cli, group_concat(distinct regiao) regiao, grife, 
    sum(qtde_2018) qtde_2sem_2018, sum(qtde_2019) qtde_2019, sum(qtde_2020) qtde_2020, sum(qtde_2021) qtde_2021,
    sum(vlr_2018) vlr_2sem_2018, sum(vlr_2019) vlr_2019, sum(vlr_2020) vlr_2020, sum(vlr_2021) vlr_2021 
    from (
		select cli, regiao, grife,
        case when ano = 2018 then qtde else 0 end as qtde_2018,
        case when ano = 2019 then qtde else 0 end as qtde_2019,
        case when ano = 2020 then qtde else 0 end as qtde_2020,
		case when ano = 2021 then qtde else 0 end as qtde_2021,
		
        case when ano = 2018 then valor else 0 end as vlr_2018,
        case when ano = 2019 then valor else 0 end as vlr_2019,
        case when ano = 2020 then valor else 0 end as vlr_2020,
		case when ano = 2021 then valor else 0 end as vlr_2021
         
		
        from (
        
			select cli, ano, regiao, grife, sum(qtde) qtde, sum(valor) valor from (
					select * from (
					select distinct cli, grife, regiao from carteira
					where rep in ($representantes) and grife not in ('EP1','EP2','EP3','EP4','EP5','EP6') 
					) as base


					left join (

							select codgrife, abc.id cod_cliente, year(dt_venda) ano , sum(qtde) qtde, sum(valor) valor
							from vendas_jde as vendas
							left join itens on vendas.id_item = itens.id
							left join addressbook abc on abc.id = vendas.id_cliente		
							where  ult_status not in ('980','984') 
						 	and codgrife in $grifes
							and codgrife not in ('EP1','EP2','EP3','EP4','EP5','EP6') 
							group by codgrife, abc.id, year(dt_venda)

					) as vds
					on vds.codgrife = base.grife and vds.cod_cliente = base.cli
			) as fim group by cli, ano, regiao, grife
		) as fim2
	) as fim3 group by cli, grife
) as fim4
        
left join (select id, razao, cnpj, fantasia, grupo, subgrupo, uf, municipio, endereco, numero, bairro, cep, financeiro, cadastro, email1, email2, ddd1, tel1, ddd2, tel2  from addressbook ) as ab
on ab.id = fim4.cli

 ");

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
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('X')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('Y')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('Z')->setAutoSize(true);

		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AA')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AB')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AC')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AD')->setAutoSize(true);

		
		

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
				->setCellValue('L4', 'Regiao')
				->setCellValue('M4', 'Email1')
				->setCellValue('N4', 'Email2')
				->setCellValue('O4', 'DDD1')
				->setCellValue('P4', 'Tel1')
				->setCellValue('Q4', 'DDD2')
				->setCellValue('R4', 'Tel2')
				->setCellValue('S4', 'Cadastro')
				->setCellValue('T4', 'Financeiro')
				->setCellValue('U4', 'cnpj')
				->setCellValue('V4', 'Qt_2Sem_18')
				->setCellValue('W4', 'Qt_2019')
				->setCellValue('X4', 'Qt_2020')
				->setCellValue('Y4', 'Qt_2021')
				->setCellValue('z4', 'grife')
			
			->setCellValue('AA4', 'vlr_2sem_2018')
			->setCellValue('AB4', 'vlr_2019')
			->setCellValue('AC4', 'vlr_2020')
			->setCellValue('AD4', 'vlr_2021')
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
					->setCellValue('L'.$index, $item->regiao) /**mudar para regiao**/
					->setCellValue('M'.$index, $item->email1)
					->setCellValue('N'.$index, $item->email2)
					->setCellValue('O'.$index, $item->ddd1)
					->setCellValue('P'.$index, $item->tel1)
					->setCellValue('Q'.$index, $item->ddd2)
					->setCellValue('R'.$index, $item->tel2)
					->setCellValue('S'.$index, $item->cadastro)
					->setCellValue('T'.$index, $item->financeiro)
					->setCellValue('U'.$index, $item->cnpj)
					->setCellValue('V'.$index, $item->qtde_2sem_2018)
					->setCellValue('W'.$index, $item->qtde_2019)
					->setCellValue('X'.$index, $item->qtde_2020)
					->setCellValue('Y'.$index, $item->qtde_2021)
					->setCellValue('Z'.$index, $item->grife)
					->setCellValue('AA'.$index, $item->vlr_2sem_2018)
					->setCellValue('AB'.$index, $item->vlr_2019)
					->setCellValue('AC'.$index, $item->vlr_2020)
					->setCellValue('AD'.$index, $item->vlr_2021)
				
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



select cli, razao, fantasia, grupo, subgrupo, uf, municipio, cadastro, financeiro, ah, at, bg, ev, hi, jm, jo, sp, tc, am, bc, bv, cl, ct, gu, mc, mm, pu, sm, st from (
select cli,
	case when  ah < 0 then 'comprou'  else ah end as  'ah', 
	case when  at < 0 then 'comprou'  else at end as  'at', 
	case when  bg < 0 then 'comprou'  else bg end as  'bg',
	case when  ev < 0 then 'comprou'  else ev end as  'ev',
	case when  hi < 0 then 'comprou'  else hi end as  'hi',
    case when  jm < 0 then 'comprou'  else jm end as  'jm',
	case when  jo < 0 then 'comprou'  else jo end as  'jo',
	case when  sp < 0 then 'comprou'  else sp end as  'sp',
	case when  tc < 0 then 'comprou'  else tc end as  'tc',
    
    case when  am < 0 then 'comprou'  else am end as  'am',
    case when  bc < 0 then 'comprou'  else bc end as  'bc',
    case when  bv < 0 then 'comprou'  else bv end as  'bv',
    case when  cl < 0 then 'comprou'  else cl end as  'cl',
    case when  ct < 0 then 'comprou'  else ct end as  'ct',
    case when  gu < 0 then 'comprou'  else gu end as  'gu',
    case when  mc < 0 then 'comprou'  else mc end as  'mc',
    case when  mm < 0 then 'comprou'  else mm end as  'mm',
    case when  pu < 0 then 'comprou'  else pu end as  'pu',
    case when  sm < 0 then 'comprou'  else sm end as  'sm',
    case when  st < 0 then 'comprou'  else st end as  'st'
    

from (

	select cli,  
		sum(ah) 'ah', sum(at) 'at', sum(bg) 'bg', sum(ev) 'ev', sum(hi) 'hi', sum(jm) 'jm', sum(jo) 'jo', sum(sp) 'sp', sum(tc) 'tc',
		sum(am) 'am', sum(bc) 'bc', sum(bv) 'bv', sum(cl) 'cl', sum(ct) 'ct', sum(gu) 'gu', sum(mc) 'mc', sum(mm) 'mm', sum(pu) 'pu',  sum(sm) 'sm', sum(st) 'st'
    
    from (
		select cli, 
			case when grife_cli = 'AH' then qtde_ajust else 0 end as 'AH',
			case when grife_cli = 'AT' then qtde_ajust else 0 end as 'AT',
			case when grife_cli = 'BG' then qtde_ajust else 0 end as 'BG',
			case when grife_cli IN ('EV','NG') then qtde_ajust else 0 end as 'EV',
			case when grife_cli = 'HI' then qtde_ajust else 0 end as 'HI',
            case when grife_cli = 'JM' then qtde_ajust else 0 end as 'JM',
			case when grife_cli = 'JO' then qtde_ajust else 0 end as 'JO',
			case when grife_cli = 'SP' then qtde_ajust else 0 end as 'SP',
			case when grife_cli = 'TC' then qtde_ajust else 0 end as 'TC',
            
            case when grife_cli = 'AM' then qtde_ajust else 0 end as 'AM',
            case when grife_cli = 'BC' then qtde_ajust else 0 end as 'BC',
            case when grife_cli = 'BV' then qtde_ajust else 0 end as 'BV',
            case when grife_cli = 'CL' then qtde_ajust else 0 end as 'CL',
            case when grife_cli = 'CT' then qtde_ajust else 0 end as 'CT',
            case when grife_cli = 'GU' then qtde_ajust else 0 end as 'GU',
            case when grife_cli = 'MC' then qtde_ajust else 0 end as 'MC',
            case when grife_cli = 'MM' then qtde_ajust else 0 end as 'MM',
            case when grife_cli = 'PU' then qtde_ajust else 0 end as 'PU',
            case when grife_cli = 'SM' then qtde_ajust else 0 end as 'SM',
            case when grife_cli = 'ST' then qtde_ajust else 0 end as 'ST'
            
			
			from (
					select *, case when grife_cli = grife_rep then qtde else qtde*-1 end as qtde_ajust
					from (
						select * from (
							select distinct cli from carteira
							where rep in ($representantes) and grife not in ('EP1','EP2','EP3','EP4','EP5','EP6')
						) as base_cli

						left join (select distinct cli cli_grif, grife grife_cli from carteira )  as base_grif
						on base_grif.cli_grif = base_cli.cli
								
						left join (select distinct cli cli_rep, grife grife_rep from carteira where rep in ($representantes) and dt_fim >= now()
						)  as base_rep
						on base_rep.cli_rep = base_cli.cli and base_rep.grife_rep = base_grif.grife_cli

							left join (
									select codgrife, abc.id cod_cliente,  sum(qtde) qtde
									from vendas_jde as vendas
									left join itens on vendas.id_item = itens.id
									left join addressbook abc on abc.id = vendas.id_cliente	
                                    
									where  ult_status not in ('980','984') and codgrife not in ('EP1','EP2','EP3','EP4','EP5','EP6')
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
      
	left join ( select id, razao, fantasia, grupo, subgrupo, uf, municipio, cadastro, financeiro from addressbook ) as ab
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
		
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('P')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('Q')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('R')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('S')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('T')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('U')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('V')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('W')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('X')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('Y')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('Z')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('AA')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('AB')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('AC')->setAutoSize(true);

		$spreadsheet->setActiveSheetIndex(1)
				->setCellValue('A1', 'COMPRAS POR GRIFES DESDE 2o. SEMESTRE 2018');
	  

		$spreadsheet->setActiveSheetIndex(1)->setCellValue('A4', 'codcli')
	            ->setCellValue('B4', 'razao')
	            ->setCellValue('C4', 'fantasia')
	            ->setCellValue('D4', 'grupo')
	            ->setCellValue('E4', 'subgrupo')
	            ->setCellValue('F4', 'uf')
	            ->setCellValue('G4', 'municipio')
				            ->setCellValue('H4', 'cadastro')
				            ->setCellValue('I4', 'financeiro')
			
	            ->setCellValue('J4', 'ah')
	            ->setCellValue('K4', 'at')
	            ->setCellValue('L4', 'bg')
	            ->setCellValue('M4', 'ev')
				->setCellValue('N4', 'hi')
				->setCellValue('O4', 'jm')
				->setCellValue('P4', 'jo')
				->setCellValue('Q4', 'sp')
				->setCellValue('R4', 'tc')
			
				->setCellValue('S4', 'am')
				->setCellValue('T4', 'bc')
				->setCellValue('U4', 'bv')
				->setCellValue('V4', 'cl')
				->setCellValue('W4', 'ct')
				->setCellValue('X4', 'gu')
				->setCellValue('Y4', 'mc')
				->setCellValue('Z4', 'mm')
				->setCellValue('AA4', 'pu')
				->setCellValue('AB4', 'sm')
				->setCellValue('AC4', 'st')
			
			;

	    $index = 5;

		foreach ($query2 as $item) {

			
			$spreadsheet->setActiveSheetIndex(1)->setCellValue('A'.$index, $item->cli)
					->setCellValue('B'.$index, $item->razao)
					->setCellValue('C'.$index, $item->fantasia)
					->setCellValue('D'.$index, $item->grupo)
					->setCellValue('E'.$index, $item->subgrupo)
					->setCellValue('F'.$index, $item->uf)
					->setCellValue('G'.$index, $item->municipio)
				
					->setCellValue('H'.$index, $item->cadastro)
					->setCellValue('I'.$index, $item->financeiro)
				
					->setCellValue('J'.$index, $item->ah)
					->setCellValue('K'.$index, $item->at)
					->setCellValue('L'.$index, $item->bg)
					->setCellValue('M'.$index, $item->ev)
					->setCellValue('N'.$index, $item->hi)
					->setCellValue('O'.$index, $item->jm)
				
					->setCellValue('P'.$index, $item->jo)
					->setCellValue('Q'.$index, $item->sp)
					->setCellValue('R'.$index, $item->tc)
				
				->setCellValue('S'.$index, $item->am)
				->setCellValue('T'.$index, $item->bc)
				->setCellValue('U'.$index, $item->bv)
				->setCellValue('V'.$index, $item->cl)
				->setCellValue('W'.$index, $item->ct)
				->setCellValue('X'.$index, $item->gu)
				->setCellValue('Y'.$index, $item->mc)
				->setCellValue('Z'.$index, $item->mm)
				->setCellValue('AA'.$index, $item->pu)
				->setCellValue('AB'.$index, $item->sm)
				->setCellValue('AC'.$index, $item->st)
				
			;
			$index++;

		}        


$spreadsheet->getActiveSheet()->setTitle('analitico');




		$spreadsheet->createSheet();	
		// Add some data
		// Rename worksheet
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet

		$sheet = $spreadsheet->getActiveSheet(2);		

		$query3 = \DB::select("
select cli, razao, fantasia, grupo, subgrupo, uf, municipio, regiao, ah, at, bg, ev, hi, jm, jo, sp, tc, am, bc, bv, cl, ct, gu, mc, mm, pu, sm, st 
from (
select cli, regiao,

	case when  ah < 0 then 'comprou'  else ah end as  'ah', 
	case when  at < 0 then 'comprou'  else at end as  'at', 
	case when  bg < 0 then 'comprou'  else bg end as  'bg',
	case when  ev < 0 then 'comprou'  else ev end as  'ev',
	case when  hi < 0 then 'comprou'  else hi end as  'hi',
    case when  jm < 0 then 'comprou'  else jm end as  'jm',
	case when  jo < 0 then 'comprou'  else jo end as  'jo',
	case when  sp < 0 then 'comprou'  else sp end as  'sp',
	case when  tc < 0 then 'comprou'  else tc end as  'tc',
    
    case when  am < 0 then 'comprou'  else am end as  'am',
    case when  bc < 0 then 'comprou'  else bc end as  'bc',
    case when  bv < 0 then 'comprou'  else bv end as  'bv',
    case when  cl < 0 then 'comprou'  else cl end as  'cl',
    case when  ct < 0 then 'comprou'  else ct end as  'ct',
    case when  gu < 0 then 'comprou'  else gu end as  'gu',
    case when  mc < 0 then 'comprou'  else mc end as  'mc',
    case when  mm < 0 then 'comprou'  else mm end as  'mm',
    case when  pu < 0 then 'comprou'  else pu end as  'pu',
    case when  sm < 0 then 'comprou'  else sm end as  'sm',
    case when  st < 0 then 'comprou'  else st end as  'st'

from (

	select cli, regiao,  
	sum(ah) 'ah', sum(at) 'at', sum(bg) 'bg', sum(ev) 'ev', sum(hi) 'hi', sum(jm) 'jm', sum(jo) 'jo', sum(sp) 'sp', sum(tc) 'tc',
	sum(am) 'am', sum(bc) 'bc', sum(bv) 'bv', sum(cl) 'cl', sum(ct) 'ct', sum(gu) 'gu', sum(mc) 'mc', sum(mm) 'mm', sum(pu) 'pu',  sum(sm) 'sm', sum(st) 'st'
	
	from (
		select cli, regiao,
			case when grife_cli = 'AH' then qtde_ajust else 0 end as 'AH',
			case when grife_cli = 'AT' then qtde_ajust else 0 end as 'AT',
			case when grife_cli = 'BG' then qtde_ajust else 0 end as 'BG',
			case when grife_cli IN ('EV','NG') then qtde_ajust else 0 end as 'EV',
			case when grife_cli = 'HI' then qtde_ajust else 0 end as 'HI',
            case when grife_cli = 'JM' then qtde_ajust else 0 end as 'JM',
			case when grife_cli = 'JO' then qtde_ajust else 0 end as 'JO',
			case when grife_cli = 'SP' then qtde_ajust else 0 end as 'SP',
			case when grife_cli = 'TC' then qtde_ajust else 0 end as 'TC',
            
            case when grife_cli = 'AM' then qtde_ajust else 0 end as 'AM',
            case when grife_cli = 'BC' then qtde_ajust else 0 end as 'BC',
            case when grife_cli = 'BV' then qtde_ajust else 0 end as 'BV',
            case when grife_cli = 'CL' then qtde_ajust else 0 end as 'CL',
            case when grife_cli = 'CT' then qtde_ajust else 0 end as 'CT',
            case when grife_cli = 'GU' then qtde_ajust else 0 end as 'GU',
            case when grife_cli = 'MC' then qtde_ajust else 0 end as 'MC',
            case when grife_cli = 'MM' then qtde_ajust else 0 end as 'MM',
            case when grife_cli = 'PU' then qtde_ajust else 0 end as 'PU',
            case when grife_cli = 'SM' then qtde_ajust else 0 end as 'SM',
            case when grife_cli = 'ST' then qtde_ajust else 0 end as 'ST'
			
			from (
					select *, case when grife_cli = grife_rep then qtde else qtde*-1 end as qtde_ajust
					from (
						select * from (
							select  cli, regiao from carteira
							where rep in ($representantes) and grife not in ('EP1','EP2','EP3','EP4','EP5','EP6')
							group by  cli, regiao
						) as base_cli

						left join (select distinct cli cli_grif, grife grife_cli from carteira )  as base_grif
						on base_grif.cli_grif = base_cli.cli
								
						left join (select distinct cli cli_rep, grife grife_rep from carteira where rep in ($representantes) and dt_fim >= now()
						)  as base_rep
						on base_rep.cli_rep = base_cli.cli and base_rep.grife_rep = base_grif.grife_cli

							left join (
									select codgrife, abc.id cod_cliente,  sum(qtde) qtde
									from vendas_jde as vendas
									left join itens on vendas.id_item = itens.id
									left join addressbook abc on abc.id = vendas.id_cliente	
                                    
									where  ult_status not in ('980','984') and codgrife not in ('EP1','EP2','EP3','EP4','EP5','EP6')
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
		
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('P')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('Q')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('R')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('S')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('T')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('U')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('V')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('W')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('X')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('Y')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('Z')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(1)->getColumnDimension('AA')->setAutoSize(true);

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
				->setCellValue('N4', 'jm')			
				->setCellValue('O4', 'jo')
				->setCellValue('P4', 'sp')
				->setCellValue('Q4', 'tc')
			
				->setCellValue('R4', 'am')
				->setCellValue('S4', 'bc')
				->setCellValue('T4', 'bv')
				->setCellValue('U4', 'cl')
				->setCellValue('V4', 'ct')
				->setCellValue('W4', 'gu')
				->setCellValue('X4', 'mc')
				->setCellValue('Y4', 'mm')
				->setCellValue('Z4', 'pu')
				->setCellValue('AA4', 'sm')
				->setCellValue('AB4', 'st')
		;

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
					->setCellValue('N'.$index, $item->jm)
					->setCellValue('O'.$index, $item->jo)
					->setCellValue('P'.$index, $item->sp)
					->setCellValue('Q'.$index, $item->tc)
				
				->setCellValue('R'.$index, $item->am)
				->setCellValue('S'.$index, $item->bc)
				->setCellValue('T'.$index, $item->bv)
				->setCellValue('U'.$index, $item->cl)
				->setCellValue('V'.$index, $item->ct)
				->setCellValue('W'.$index, $item->gu)
				->setCellValue('X'.$index, $item->mc)
				->setCellValue('Y'.$index, $item->mm)
				->setCellValue('Z'.$index, $item->pu)
				->setCellValue('AA'.$index, $item->sm)
				->setCellValue('AB'.$index, $item->st)
				;
			$index++;

		}        


		$spreadsheet->getActiveSheet()->setTitle('ult_4meses');



		
				
		
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


