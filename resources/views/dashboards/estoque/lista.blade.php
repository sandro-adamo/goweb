@extends('layout.principal')
@section('conteudo')


@php

$representantes = Session::get('representantes');


if($representantes==101815) 
	{$grifes="( 'AM', 'BC', 'BV', 'CT', 'SM', 'MC', 'CH', 'DU', 'AA', 'AZ', 'CL')";} 
		else {
			$grifes = Session::get('grifes'); }

$query_1 = \DB::select(" 
select distinct colmod_a colmod, itens_disp, orca, disp, cet, etq, prod, etq_total_vendas, pre_compras, most, reservas_estrat, manut , atual,  ultimo, penultimo,  antipenultimo
from (

	select anomod, case when colmod_a < year(now()) then concat(anomod, ' TOTAL') when colmod_a is null then concat(anomod, ' TOTAL')  else colmod_a end as colmod_a, 
	sum(itens_disp) itens_disp, sum(orca) orca, sum(disp) disp, sum(cet) cet, sum(etq) etq, sum(prod) prod, sum(etq_total_vendas) etq_total_vendas, 
	sum(compras) pre_compras, sum(most) most, sum(reservas_estrat) reservas_estrat, sum(manut) manut ,sum(atual) atual, sum(ultimo) ultimo, 
	sum(penultimo) penultimo, sum(antipenultimo) antipenultimo
	from (

		select case when sint.colmod_a = ' <= 2016' then left(sint.colmod_a,8) else left(sint.colmod_a,4) end as anomod, 
		case when sint.colmod_a < year(now()) then left(sint.colmod_a,4) else sint.colmod_a end as colmod_a, sint.genero,
		sum(orcamento_liber+orcamento_bloq) orca, sum(disponivel) disp, sum(cet) cet, sum(etq) etq, sum(cep) prod, sum(etq_total_vendas)etq_total_vendas, sum(compras) compras, 
		sum(mostruarios) most, sum(reservas_estrat) reservas_estrat, sum(manutencao) manut
		,sum(atual) atual,sum(ultimo) ultimo, sum(penultimo) penultimo, sum(antipenultimo) antipenultimo, sum(itens_disp) itens_disp

		from go_storage.sintetico_estoque sint
		group by sint.colmod_a, sint.genero
	) as fim  group by anomod, colmod_a with rollup
) as fim1 order by colmod_a
");




$query_2 = \DB::select(" 
select distinct classif, case when clasmod is null then classif else clasmod end as clasmod, itens_disp, orca, disp, cet, etq, prod, etq_total_vendas, pre_compras, most, reservas_estrat, manut , atual,  ultimo, penultimo,  antipenultimo
from (

	select classif, case when clasmod is null then classif else clasmod end as clasmod, sum(itens_disp) itens_disp,
	sum(orca) orca, sum(disp) disp, sum(cet) cet, sum(etq) etq, sum(prod) prod, sum(etq_total_vendas) etq_total_vendas, sum(compras) pre_compras, 
	sum(most) most, sum(reservas_estrat) reservas_estrat, sum(manut) manut
	,sum(atual) atual, sum(ultimo) ultimo, sum(penultimo) penultimo, sum(antipenultimo) antipenultimo
	from (
    
		select case when clasmod in ('NOVO','LINHA A++','LINHA A+','LINHA A') then 'A - TOTAL' 
         when clasmod in ('LINHA A-', 'COLECAO B') then 'B - TOTAL'  when clasmod in ('PROMOCIONAL C') then 'C - TOTAL' else clasmod end as classif, 
         clasmod,
        sum(orcamento_liber+orcamento_bloq) orca, sum(disponivel) disp, sum(cet) cet, sum(etq) etq, sum(cep) prod, sum(etq_total_vendas)etq_total_vendas, sum(compras) compras, 
        sum(mostruarios) most, sum(reservas_estrat) reservas_estrat, 
        sum(manutencao) manut,sum(atual) atual,sum(ultimo) ultimo, sum(penultimo) penultimo, sum(antipenultimo) antipenultimo, sum(itens_disp) itens_disp

		from go_storage.sintetico_estoque sint
		group by clasmod  
) as fim 
group by classif, clasmod with rollup 
) as fim1


");



$query_3 = \DB::select(" 

	select  case when agrup is null then concat('____',grife, ' TOTAL') else agrup end as agrup, sum(itens_disp) itens_disp,
	sum(orca) orca, sum(disp) disp, sum(cet) cet, sum(etq) etq, sum(prod) prod, sum(etq_total_vendas) etq_total_vendas, sum(compras) pre_compras, 
	sum(most) most, sum(reservas_estrat) reservas_estrat, sum(manut) manut
	,sum(atual) atual, sum(ultimo) ultimo, sum(penultimo) penultimo, sum(antipenultimo) antipenultimo
	from (
    
		select sint.codgrife grife, case when sint.tecnologia = 'CLIP ON' then concat(left(sint.agrup,4),' - CLIP ON') else sint.agrup end as agrup, 
        sint.genero,
        sum(orcamento_liber+orcamento_bloq) orca, sum(disponivel) disp, sum(cet) cet, sum(etq) etq, sum(cep) prod, sum(etq_total_vendas)etq_total_vendas, sum(compras) compras, 
        sum(mostruarios) most, sum(reservas_estrat) reservas_estrat, sum(manutencao) manut
        ,sum(atual) atual,sum(ultimo) ultimo, sum(penultimo) penultimo, sum(antipenultimo) antipenultimo, sum(itens_disp) itens_disp

		from go_storage.sintetico_estoque sint
		group by sint.codgrife, sint.agrup, sint.genero, sint.tecnologia
) as fim 
group by grife, agrup with rollup 

");
			  
			
@endphp

<form action="" method="get"> 

<h6>

	
							
<div class="row"> 
	
	<div class="col-md-12">	
	
	   <div class="box box-body">	
	   <table class="table table-striped table-bordered compact" id="myTable">
		  <thead>	
			
		 <tr>	

	 		<td colspan="12">Estoque Colecao</td>
			 <td colspan="4">Vendas</td>
		
				</tr>
		  			
					<tr>	
						
					<td colspan="1" align="center">Colmod</td>
					<td colspan="1" align="center">Itens_disp</td>
					<td colspan="1" align="center">BO</td>
					<td colspan="1" align="center">Disp_vda</td>
					<td colspan="1" align="center">CET</td>
					<td colspan="1" align="center">ETQ</td>
					<td colspan="1" align="center">CEP</td>
					<td colspan="1" align="center">Etq Vda</td>
					<td colspan="1" align="center">Pre compra</td>
					<td colspan="1" align="center">Most</td>
					<td colspan="1" align="center">Reservas</td>
					<td colspan="1" align="center">Manut</td>
						
					<td colspan="1" align="center">atual</td>
					<td colspan="1" align="center">ultimo</td>
					<td colspan="1" align="center">penultimo</td>
					<td colspan="1" align="center">antipenultimo</td>
					
				
					</tr>
			    </thead>
			  
		  
		   
			@foreach ($query_1 as $query1)
		   
		   
			  
				<tr>
				<td align="left"><a href="/dkdet_comprasagrup?colmod={{$query1->colmod}}">{{$query1->colmod}}</a></td>
				<td align="center">{{number_format($query1->itens_disp)}}</td>
				<td align="center">{{number_format($query1->orca)}}</td>
				<td align="center">{{number_format($query1->disp)}}</td>
				<td align="center">{{number_format($query1->cet)}}</td>
				<td align="center">{{number_format($query1->etq)}}</td>
				<td align="center"><a href="/dkdet_orcagrup?agrup={{$query1->colmod}}">{{number_format($query1->prod)}}</a></td>
				<td align="center">{{number_format($query1->etq_total_vendas)}}</td>	
				<td align="center">{{number_format($query1->pre_compras)}}</td>	
				<td align="center">{{number_format($query1->most)}}</td>	
				<td align="center">{{number_format($query1->reservas_estrat)}}</td>	
				<td align="center">{{number_format($query1->manut)}}</td>
				
				<td align="center">{{number_format($query1->atual)}}</td>	
				<td align="center">{{number_format($query1->ultimo)}}</td>	
				<td align="center">{{number_format($query1->penultimo)}}</td>	
				<td align="center">{{number_format($query1->antipenultimo)}}</td>		
				</tr>
			@endforeach 
			
		
		   
			</table>
			
		</div>

		
		
	<div class="box box-body">	
	   <table class="table table-striped table-bordered compact" id="myTable">
		  <thead>	
			
		 <tr>	

	 		<td colspan="12">Estoques Linhas</td>
			<td colspan="4">Vendas</td>
		
				</tr>
		  			
					<tr>	
							
					<td colspan="1" align="center">Clasmod</td>
					<td colspan="1" align="center">Itens_disp</td>
					<td colspan="1" align="center">BO</td>
					<td colspan="1" align="center">Disp_vda</td>
					<td colspan="1" align="center">CET</td>
					<td colspan="1" align="center">ETQ</td>
					<td colspan="1" align="center">CEP</td>
					<td colspan="1" align="center">Etq Vda</td>
					<td colspan="1" align="center">Pre compra</td>
					<td colspan="1" align="center">Most</td>
					<td colspan="1" align="center">Reservas</td>
					<td colspan="1" align="center">Manut</td>
						
					<td colspan="1" align="center">atual</td>
					<td colspan="1" align="center">ultimo</td>
					<td colspan="1" align="center">penultimo</td>
					<td colspan="1" align="center">antipenultimo</td>
				
					
				
					</tr>
			    </thead>
			  
		  
		   
			@foreach ($query_2 as $query2)
		   
		   
			  
				<tr>
			
				<td align="left"><a href="/dkdet_comprasagrup?agrup={{$query2->clasmod}}">{{$query2->clasmod}}</a></td>
				<td align="center">{{number_format($query2->itens_disp)}}</td>
				<td align="center">{{number_format($query2->orca)}}</td>
				<td align="center">{{number_format($query2->disp)}}</td>
				<td align="center">{{number_format($query2->cet)}}</td>
				<td align="center">{{number_format($query2->etq)}}</td>
				<td align="center"><a href="/dkdet_orcagrup?agrup={{$query2->clasmod}}">{{number_format($query2->prod)}}</a></td>
				<td align="center">{{number_format($query2->etq_total_vendas)}}</td>	
				<td align="center">{{number_format($query2->pre_compras)}}</td>	
				<td align="center">{{number_format($query2->most)}}</td>	
				<td align="center">{{number_format($query2->reservas_estrat)}}</td>	
				<td align="center">{{number_format($query2->manut)}}</td>
				
				<td align="center">{{number_format($query2->atual)}}</td>	
				<td align="center">{{number_format($query2->ultimo)}}</td>	
				<td align="center">{{number_format($query2->penultimo)}}</td>	
				<td align="center">{{number_format($query2->antipenultimo)}}</td>	
				</tr>
			@endforeach 
			
		
		   
			</table>
			<td></td> 
		</div>
		
		
		
		
	

	<div class="box box-body">	
	   <table class="table table-striped table-bordered compact" id="myTable">
		  <thead>	
			
		 <tr>	

	 		<td colspan="12">Estoques Grifes</td>
			<td colspan="4">Vendas</td>
		
				</tr>
		  			
					<tr>	
							
					<td colspan="1" align="center">Agrup</td>
					<td colspan="1" align="center">Itens_disp</td>
					<td colspan="1" align="center">BO</td>
					<td colspan="1" align="center">Disp_vda</td>
					<td colspan="1" align="center">CET</td>
					<td colspan="1" align="center">ETQ</td>
					<td colspan="1" align="center">CEP</td>
					<td colspan="1" align="center">Etq Vda</td>
					<td colspan="1" align="center">Pre compra</td>
					<td colspan="1" align="center">Most</td>
					<td colspan="1" align="center">Reservas</td>
					<td colspan="1" align="center">Manut</td>
						
					<td colspan="1" align="center">atual</td>
					<td colspan="1" align="center">ultimo</td>
					<td colspan="1" align="center">penultimo</td>
					<td colspan="1" align="center">antipenultimo</td>
				
					
				
					</tr>
			    </thead>
			  
		  
		   
			@foreach ($query_3 as $query3)
		   
		   
			  
				<tr>
			
				<td align="left"><a href="/dsetq_agrup?agrup={{$query3->agrup}}">{{$query3->agrup}}</a></td>
				<td align="center">{{number_format($query3->itens_disp)}}</td>
				<td align="center">{{number_format($query3->orca)}}</td>
				<td align="center">{{number_format($query3->disp)}}</td>
				<td align="center">{{number_format($query3->cet)}}</td>
				<td align="center">{{number_format($query3->etq)}}</td>
				<td align="center"><a href="/dkdet_orcagrup?agrup={{$query3->agrup}}">{{number_format($query3->prod)}}</a></td>
				<td align="center">{{number_format($query3->etq_total_vendas)}}</td>	
				<td align="center">{{number_format($query3->pre_compras)}}</td>	
				<td align="center">{{number_format($query3->most)}}</td>	
				<td align="center">{{number_format($query3->reservas_estrat)}}</td>	
				<td align="center">{{number_format($query3->manut)}}</td>
				
				<td align="center">{{number_format($query3->atual)}}</td>	
				<td align="center">{{number_format($query3->ultimo)}}</td>	
				<td align="center">{{number_format($query3->penultimo)}}</td>	
				<td align="center">{{number_format($query3->antipenultimo)}}</td>	
				</tr>
			@endforeach 
			
		
		   
			</table>
			<td></td> 
		</div>
		
			
	</div>	
</div>
</h6>			
	
</form>

@stop