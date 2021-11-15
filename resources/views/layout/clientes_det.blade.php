@extends('layout.principal')

@php

 $representantes = Session::get('representantes');
 $grifes = Session::get('grifes');
 $codgrife = $_GET["codgrife"];

@endphp

@section('title')
<i class="fa fa-users"></i> Dashboard Clientes para a grife {{$codgrife}}
@append 

@section('conteudo')

<form action="" method="get"> 
@php

	/**

$status = $_GET["status"];
$clasmod = $_GET["clasmod"];
	
	
	
	if($status == 'a - mantem mala') { $status_ajust = "and statusatual not in ('ESGOTADO','em producao') and ultstatus not in ('ESGOTADO','em producao')"; } 
	elseif ($status == 'b - retornou') {$status_ajust = "and statusatual not in ('ESGOTADO','em producao') and ultstatus in ('ESGOTADO','em producao')"; }
	elseif ($status == 'c - mantem fora') {$status_ajust = "and statusatual in ('ESGOTADO','em producao') and ultstatus in ('ESGOTADO','em producao')"; }
	elseif ($status == 'd - retira') {$status_ajust = "and statusatual in ('ESGOTADO','em producao') and ultstatus not in ('ESGOTADO','em producao')"; }
	elseif ($status == 'disp') {$status_ajust = "and statusatual not in ('ESGOTADO','em producao')"; }
	else {}
	



	
	if($clasmod == 'a') { $and = "and clasmod in ('NOVO','LINHA A++','LINHA A+','LINHA A','EM ANALISE')" ;} 
	elseif ($clasmod == 'b') { $and = "and clasmod in ('LINHA A-')" ;}
	elseif ($clasmod == '') { $and = "and clasmod in ('NOVO','LINHA A++','LINHA A+','LINHA A','LINHA A-', 'EM ANALISE')" ;} 
	
	else {$and = "and 1=1"; }
	

	
  $where1 = ' where id_rep in ('.$representantes.') ';
  $where2 = ' where rep_comissao in ('.$representantes.') ';

 echo $agrup; 
 echo $status_ajust;
echo $clasmod; 
echo $and;
	
**/

$query_0 = \DB::select("select count(*) linhas from vendas_jdes");
	
	
	

$query_1 = \DB::select("
 

select codgrife, dias, sum(clientes) clientes, sum(pdvs) pdvs, sum(qtde) qtde, sum(cli_inad) cli_inad from (

	select codgrife, dias, case when financeiro > 0 then count(cliente) else 0 end as cli_inad, count(cliente) clientes, sum(pdvs) pdvs, sum(qtde) qtde from (
		select codgrife, dias, cliente, sum(financeiro) financeiro, count(id_cliente) pdvs, sum(qtde) qtde from (
		
			select codgrife, cliente, id_cliente, data_recente, dias_compra, financeiro,
			 case 
			 when dias_compra between 0 and 90 then 'a0a90' 
			 when dias_compra between 91 and 180 then 'b91a180'
			 when dias_compra between 181 and 270 then 'c181a270'
			 when dias_compra between 271 and 365 then 'd271a365' else 'erro' end as dias,
			 qtde
			 
			from (

				select *, 
					datediff(now(),
					(select max(dt_venda) from vendas_jdes vds1 left join addressbook ab1 on ab1.id = vds1.id_cliente
					where vds1.codgrife = fim1.codgrife 
					and ab1.cliente = fim1.cliente and ult_status not in ('980','984') and codgrife not in ('EP1','EP2','EP3','EP4','EP5') )) dias_compra,
					
					(select min(dt_venda) from vendas_jdes vds1 left join addressbook ab1 on ab1.id = vds1.id_cliente
					where vds1.codgrife = fim1.codgrife and ab1.cliente = fim1.cliente 
					and ult_status not in ('980','984') and codgrife not in ('EP1','EP2','EP3','EP4','EP5'))  data_recente
													
					from (
					
						select 
						case when codgrife in ('go','at') then 'AT' when codgrife in ('EV','NG') then 'EV' else codgrife end as codgrife, id_cliente, cliente, (select regiao from carteira 
						where rep in ($representantes) 
						and carteira.grife = vds.codgrife and cli = id_cliente and status = 1 limit 1) cart, sum(qtde) qtde, 
						case when financeiro in ('ju','in') then 1 else 0 end as financeiro
						
						from vendas_jdes vds
						left join addressbook ab on ab.id = id_cliente
						where ult_status not in ('980','984') and codgrife not in ('EP1','EP2','EP3','EP4','EP5') and datediff(now(),dt_venda) <= 365  
						
						and id_rep in ($representantes)																									
						group by codgrife, id_cliente, cliente, id_rep , financeiro
						
					) as fim1 
					where cart is not null and codgrife = '$codgrife'
				
				) as fim2 
			) as fim3 group by codgrife, dias, cliente
		) as fim4 group by codgrife, dias, financeiro
	) as fim5 group by codgrife, dias				
order by dias	                
																																					
");

	

	
@endphp

<h6>
			
						
						
<div class="row">

		<div class="col-md-4">
		<div class="box box-body box-widget">
		 
		<table class="table table-responsive table-striped" id="example1">
		 <tr>	
	 		<td colspan="5">clientes (subgrupos) com compras nos ultimos 12meses para a Grife {{$codgrife}}</td>
		
				</tr>
		  			
					<tr>	
					
					<td colspan="1" align="center">Periodo da ult compra</td>
					<td colspan="1" align="center">clientes</td>
					<td colspan="1" align="center">pdvs</td>
					<td colspan="1" align="center">qtde</td>
					<td colspan="1" align="center">cli_inadimp</td>
				
					</tr>
		@php
		$total_modelos = 0;	
		$total_inicial = 0;	
		
	
		@endphp	
			  
			  
			@foreach ($query_1 as $query1)
			  
			  
		@php
              $total_modelos += $query1->qtde;
			  $total_inicial += $query1->qtde;
						
						if($query1->dias=='a0a90') {$dias_ajust='0 a 90 dias';} 
						elseif ($query1->dias=='a0a90') {$dias_ajust='0 a 90 dias';}
						elseif ($query1->dias=='b91a180') {$dias_ajust='91 a 180 dias';}
						elseif ($query1->dias=='c181a270') {$dias_ajust='181 a 270 dias';}
						elseif ($query1->dias=='d271a365') {$dias_ajust='271 a 365 dias';}
						else {$dias_ajust=$query1->dias;}
						
			
				  
		@endphp	  
			  
			  
				<tr>
					<td align="left"><a href="/clientes_diasdet?dias={{$query1->dias}}&codgrife={{$query1->codgrife}}">{{$dias_ajust}}</a></td>
					<td align="center">{{$query1->clientes}}</td>
					<td align="center">{{$query1->pdvs}}</td>
					<td align="center">{{number_format($query1->qtde,0)}}</td>	
					<td align="center">{{number_format($query1->cli_inad,0)}}</td>	
				</tr>
			@endforeach 
			
			</table>
			
			TOTAL 

		</div>

	</div>
	
	
	

</div>
	
	
	
	
</form>
</h6>
@stop