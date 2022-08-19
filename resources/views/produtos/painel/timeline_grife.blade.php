@extends('layout.principal')

@section('title')
<i class="fa fa-suitcase"></i> Dashboard Agrupamento
@append 

@section('conteudo')

<form action="" method="get"> 
@php

 $representantes = Session::get('representantes');
 $grifes = Session::get('grifes');

$grife = $_GET["grife"];	
$agrup = substr($grife,0,5);
	
  $where = ' where id_rep in ('.$representantes.') ';
  $where2 = ' where rep_comissao in ('.$representantes.') ';

 echo $grife; 
	echo $agrup; 

	

$query_1 = \DB::select("
select codgrife, agrup, status,
	sum(atual) atual, sum(total) total, sum(mod_inicial) mod_inicial, 
    case when status <> 'entradas no ano' then sum(mes01)*-1 else sum(mes01) end as mes01,  
    case when status <> 'entradas no ano' then sum(mes02)*-1 else sum(mes02) end as mes02,  
    case when status <> 'entradas no ano' then sum(mes03)*-1 else sum(mes03) end as mes03,  
    case when status <> 'entradas no ano' then sum(mes04)*-1 else sum(mes04) end as mes04,  
    case when status <> 'entradas no ano' then sum(mes05)*-1 else sum(mes05) end as mes05,  
    case when status <> 'entradas no ano' then sum(mes06)*-1 else sum(mes06) end as mes06,  
    case when status <> 'entradas no ano' then sum(mes07)*-1 else sum(mes07) end as mes07,  
    case when status <> 'entradas no ano' then sum(mes08)*-1 else sum(mes08) end as mes08,  
    case when status <> 'entradas no ano' then sum(mes09)*-1 else sum(mes09) end as mes09,  
    case when status <> 'entradas no ano' then sum(mes10)*-1 else sum(mes10) end as mes10,  
    case when status <> 'entradas no ano' then sum(mes11)*-1 else sum(mes11) end as mes11,  
    case when status <> 'entradas no ano' then sum(mes12)*-1 else sum(mes12) end as mes12
    
	from (

		select codgrife, agrup, status, 
		case when mes = '00' then sum(modelos) else 0 end as atual,
		case when mes = '01' then sum(modelos) else 0 end as mes01,
		case when mes = '02' then sum(modelos) else 0 end as mes02,
		case when mes = '03' then sum(modelos) else 0 end as mes03,
		case when mes = '04' then sum(modelos) else 0 end as mes04,
		case when mes = '05' then sum(modelos) else 0 end as mes05,
		case when mes = '06' then sum(modelos) else 0 end as mes06,
		case when mes = '07' then sum(modelos) else 0 end as mes07,
		case when mes = '08' then sum(modelos) else 0 end as mes08,
		case when mes = '09' then sum(modelos) else 0 end as mes09,
		case when mes = '10' then sum(modelos) else 0 end as mes10,
		case when mes = '11' then sum(modelos) else 0 end as mes11,
		case when mes = '12' then sum(modelos) else 0 end as mes12,
        sum(modelos) total, sum(mod_inicial) mod_inicial

		from (

			select codgrife, agrup, status, mes, count(modelo) modelos, case when status = 'entradas no ano' then 0 else count(modelo) end as mod_inicial  from (
				select *,
				case 
				when col_fim is null and left(col_ini,4) < year(now()) then 'Sem previsao de saida'
				when left(col_ini,4) < year(now()) and left(col_fim,4) = year(now()) then  'Saidas no ano' 
				when left(col_ini,4) < year(now()) and left(col_fim,4) < year(now()) 
                and (clasmod in ('LINHA A','LINHA A+','LINHA A++','NOVO','LINHA A-','EM ANALISE') or clas_data in ('LINHA A','LINHA A+','LINHA A++','NOVO','LINHA A-','EM ANALISE') ) then 'Saidas atrasadas' 
				when left(col_ini,4) < year(now()) and left(col_fim,4) > year(now()) then  'saidas prox ano'
				when left(col_ini,4) < year(now()) and (left(col_fim,4) < year(now()) or col_fim is null) then 'Modelos na data' 
				when left(col_ini,4) = year(now()) then 'Entradas no ano' 
				else '' end as status,
	
				
				case
                when left(col_ini,4) = year(now()) then right(col_ini,2) 
				when left(col_fim,4) = year(now()) then right(col_fim,2) else '00' end as mes
				
				from(    

					select distinct codgrife, agrup, modelo, clasmod, colmod col_ini, 
						(select colecao from ciclos where ciclos.modelo = itens.modelo order by created_at desc limit 1 ) col_fim,
						(select clas_m from processa where year(date(data)) < year(now()) and id_item = itens.id order by data desc limit 1) as clas_data
					
					from itens 
					
					where codtipoarmaz not in ('o') and itens.secundario not like '%semi%' and clasmod not in ('cancelado','colecao europa') and codtipoitem = 006
					and agrup = '$grife'  
					

				) as fim0
				where (clasmod in ('LINHA A','LINHA A+','LINHA A++','NOVO','LINHA A-','EM ANALISE')  or  clas_data in ('LINHA A','LINHA A+','LINHA A++','NOVO','LINHA A-','EM ANALISE'))
			) as fim1 group by codgrife, agrup, status, mes
		) as fim2 group by codgrife, agrup, status, mes
	) as fim3 group by codgrife, agrup, status
");

	
							
							
							
							
							
$query_2 = \DB::select("							
select codgrife, agrup, divergencia_semanal, sum(itens_a) 'itens_am', sum(itens_aa) itens_a, sum(itens) itens ,
case when divergencia_semanal in ('a - mantem mala','b - retornou') then sum(itens_a)  else 0 end as 'itens_amd', 
case when divergencia_semanal in ('a - mantem mala','b - retornou') then sum(itens_aa) else 0 end as 'itens_ad',
case when divergencia_semanal in ('a - mantem mala','b - retornou') then sum(itens) else 0 end as 'itens_d' 

from (

	select codgrife,agrup, clasmod, divergencia_semanal, datastatusatual,
	case when clasmod = 'linha a-' then count(secundario) else 0 end as 'itens_a',
	case when clasmod <> 'linha a-' then count(secundario) else 0 end as 'itens_aa',
    count(secundario) itens
	from (

		select codgrife, agrup, modelo, secundario, clasmod, colmod, ultstatus, dataultstatus, statusatual, datastatusatual,
		
		case 
		when ultstatus in ('EM PRODUCAO','ESGOTADO') and statusatual not in ('EM PRODUCAO','ESGOTADO') then 'b - retornou'
		when ultstatus in ('EM PRODUCAO','ESGOTADO') and statusatual  in ('EM PRODUCAO','ESGOTADO') then 'c - mantem fora'
		
		when ultstatus not in ('EM PRODUCAO','ESGOTADO') and statusatual not in ('EM PRODUCAO','ESGOTADO') then 'a - mantem mala'
		when ultstatus not in ('EM PRODUCAO','ESGOTADO') and statusatual  in ('EM PRODUCAO','ESGOTADO') then 'd - retira'
		else 'erro' end as divergencia_semanal
		
		
		from itens 
		where codtipoitem = 006 and codtipoarmaz not in ('o','i')
		and clasmod not in ('COLECAO B','PROMOCIONAL C') and agrup = '$grife'
		and ((left(colmod,4) <= year(now())) or (left(colmod,4) <= year(now()) and right(colmod,2) <= month(now())))
		
	) as fim group by codgrife,agrup, clasmod, divergencia_semanal, datastatusatual

) as fim1 group by codgrife, agrup, divergencia_semanal
order by codgrife, agrup, divergencia_semanal





");
	

	
	
@endphp

<h6>
<div class="row">

		<div class="col-md-10">
		<div class="box box-body box-widget">
		 
				<table class="table table-responsive table-striped" id="example1">
		 <tr>	
	 		<td colspan="4">Timeline do Agrupamento {{$grife}} </td>
			 <td colspan="14">MODELOS DISPONIVEIS + PRODUCAO</td>
				</tr>
		  <tr>	
	 		
	 	
		 	<td colspan="1" align="center">clasmod</td>
			<td colspan="1" align="center">modelos</td>
			
			<td colspan="1" align="center">01</td>
			<td colspan="1" align="center">02</td>
			<td colspan="1" align="center">03</td>
			<td colspan="1" align="center">04</td>
			<td colspan="1" align="center">05</td>
		 	<td colspan="1" align="center">06</td>
			<td colspan="1" align="center">07</td>
			<td colspan="1" align="center">08</td>
			<td colspan="1" align="center">09</td>
			<td colspan="1" align="center">10</td>
			<td colspan="1" align="center">11</td>
			<td colspan="1" align="center">12</td>
	
			  </tr>
		@php
		$total_modelos = 0;	
		$total_inicial = 0;	
		
		$total_mes01 = 0;
		$total_mes02 = 0;
		$total_mes03 = 0;
		$total_mes04 = 0;
		$total_mes05 = 0;
		$total_mes06 = 0;
		$total_mes07 = 0;
		$total_mes08 = 0;
		$total_mes09 = 0;
		$total_mes10 = 0;
		$total_mes11 = 0;
		$total_mes12 = 0;

	
		@endphp	
			  
			  
			@foreach ($query_1 as $query1)
			  
			  
		@php
              $total_modelos += $query1->total;
			  $total_inicial += $query1->mod_inicial;
			
			  $total_mes01 += $query1->mes01;
			  $total_mes02 += $query1->mes02;
			  $total_mes03 += $query1->mes03;
			  $total_mes04 += $query1->mes04;
			  $total_mes05 += $query1->mes05;
			  $total_mes06 += $query1->mes06;
			  $total_mes07 += $query1->mes07;
			  $total_mes08 += $query1->mes08;
			  $total_mes09 += $query1->mes09;
			  $total_mes10 += $query1->mes10;
			  $total_mes11 += $query1->mes11;
			  $total_mes12 += $query1->mes12;
			  
			  
			  
			  
				  
		@endphp	  
			  
			  
				<tr>
					
				
					<td align="left">{{$query1->status}}</td>
					<td align="center">{{number_format($query1->mod_inicial, 0, ',', '.')}}</td>
					<td align="center">{{$query1->mes01}}</td>
					<td align="center">{{$query1->mes02}}</td>
					<td align="center">{{$query1->mes03}}</td>
					<td align="center"><a href="{{$query1->status}}">{{$query1->mes04}}</a></td>
					<td align="center">{{$query1->mes05}}</td>
					<td align="center">{{$query1->mes06}}</td>
					<td align="center">{{$query1->mes07}}</td>
					<td align="center">{{$query1->mes08}}</td>
					<td align="center">{{$query1->mes09}}</td>
					<td align="center">{{$query1->mes10}}</td>
					<td align="center">{{$query1->mes11}}</td>
					<td align="center">{{$query1->mes12}}</td>
					
	
				</tr>
			@endforeach 
				<tr>
				<td>TOTAL</td>
				<td align="center">{{number_format($total_inicial, 0, ',', '.')}}</td>	
				<td align="center">{{$total_mes01}}</td>
				<td align="center">{{$total_mes02}}</td>
				<td align="center">{{$total_mes03}}</td>
				<td align="center">{{$total_mes04}}</td>
				<td align="center">{{$total_mes05}}</td>
				<td align="center">{{$total_mes06}}</td>
				<td align="center">{{$total_mes07}}</td>
				<td align="center">{{$total_mes08}}</td>
				<td align="center">{{$total_mes09}}</td>
				<td align="center">{{$total_mes10}}</td>
				<td align="center">{{$total_mes11}}</td>
				<td align="center">{{$total_mes12}}</td>
				</tr>
				
				
				<tr><td>TOTAL ACUMULADO</td>
<td align="center"></td>

<td align="center">{{$total_mes01+$total_inicial}}</td>
<td align="center">{{$total_mes01+$total_mes02+$total_inicial}}</td>
<td align="center">{{$total_mes01+$total_mes02+$total_mes03+$total_inicial}}</td>
<td align="center">{{$total_mes01+$total_mes02+$total_mes03+$total_mes04+$total_inicial}}</td>
<td align="center">{{$total_mes01+$total_mes02+$total_mes03+$total_mes04+$total_mes05+$total_inicial}}</td>
<td align="center">{{$total_mes01+$total_mes02+$total_mes03+$total_mes04+$total_mes05+$total_mes06+$total_inicial}}</td>
<td align="center">{{$total_mes01+$total_mes02+$total_mes03+$total_mes04+$total_mes05+$total_mes06+$total_mes07+$total_inicial}}</td>
<td align="center">{{$total_mes01+$total_mes02+$total_mes03+$total_mes04+$total_mes05+$total_mes06+$total_mes07+$total_mes08+$total_inicial}}</td>
<td align="center">{{$total_mes01+$total_mes02+$total_mes03+$total_mes04+$total_mes05+$total_mes06+$total_mes07+$total_mes08+$total_mes09+$total_inicial}}</td>		
<td align="center">{{$total_mes01+$total_mes02+$total_mes03+$total_mes04+$total_mes05+$total_mes06+$total_mes07+$total_mes08+$total_mes09+$total_mes10+$total_inicial}}</td>		

<td align="center">{{$total_mes01+$total_mes02+$total_mes03+$total_mes04+$total_mes05+$total_mes06+$total_mes07+$total_mes08+$total_mes09+$total_mes10+$total_mes11+$total_inicial}}</td>

<td align="center">{{$total_mes01+$total_mes02+$total_mes03+$total_mes04+$total_mes05+$total_mes06+$total_mes07+$total_mes08+$total_mes09+$total_mes10+$total_mes11+$total_mes12+$total_inicial}}</td>							

</tr>
							
			</table>
</div>
	
</div>
	
	
	
	



	<div class="col-md-4">
		<div class="box box-body box-widget">
		 
				<table class="table table-responsive table-striped" id="example1">
		 <tr>	
	 		<td colspan="4">Divergencia semanal</td>
			
				</tr>
		  <tr>	
	 		
	 	
		
			<td colspan="1" align="center">divergencia</td>
			<td colspan="1" align="center">itens</td>
			<td colspan="1" align="center">itens A</td>
			<td colspan="1" align="center">itens A-</td>

	
			  </tr>
		@php
		$total_itens = 0;	
		$total_itensd = 0;	
					
		$total_a = 0;	
		$total_ad = 0;	
					
		$total_am = 0;	
		$total_amd = 0;	
		
	
		@endphp	
			  
			  
			@foreach ($query_2 as $query2)
			  
			  
				@php
					
		$total_itens += $query2->itens;	
		$total_itensd += $query2->itens_d;	
					
		$total_a += $query2->itens_a;	
		$total_ad += $query2->itens_ad;	
					
		$total_am += $query2->itens_am;	
		$total_amd += $query2->itens_amd;	
					
					
					 
				@endphp	  

			  
				<tr>
				
					<td align="left">{{$query2->divergencia_semanal}}</td>
					<td align="center"><a href="/timeline_det?agrup={{$grife}}&status={{$query2->divergencia_semanal}}&clasmod=''">{{$query2->itens}}</a></td>
					<td align="center"><a href="/timeline_det?agrup={{$grife}}&status={{$query2->divergencia_semanal}}&clasmod=a">{{$query2->itens_a}}</td>
					<td align="center"><a href="/timeline_det?agrup={{$grife}}&status={{$query2->divergencia_semanal}}&clasmod=b">{{$query2->itens_am}}</td>
	
				</tr>
			@endforeach 
				<tr>
					
					
				<td>TOTAL</td>
					<td align="center">{{$total_itens}}</td>
					<td align="center">{{$total_a}}</td>
					<td align="center">{{$total_am}}</td>	
				</tr>
				
					
					
				<tr>
					<td class="text-green">TOTAL DISPONIVEL</td>
					<td align="center" class="text-green"><a href="/timeline_det?agrup={{$agrup}}&status=disp&clasmod=''">{{$total_itensd}}</a></td>
					<td align="center" class="text-green"><a href="/timeline_det?agrup={{$grife}}&status=disp&clasmod=a">{{$total_ad}}</a></td>
					<td align="center" class="text-green"><a href="/timeline_det?agrup={{$grife}}&status=disp&clasmod=b">{{$total_amd}}</a></td>
					
				</tr>
			
			</table>
		</div>
	
	</div>
	
</div>
	
	
	
	
</form>
</h6>
@stop