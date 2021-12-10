@extends('layout.principal')
@section('conteudo')

@php

$representantes = Session::get('representantes');


if($representantes==101815) 
	{$grifes="( 'AM', 'BC', 'BV', 'CT', 'SM', 'MC', 'CH', 'DU', 'AA', 'AZ', 'CL')";} 
		else {
			$grifes = Session::get('grifes'); }

echo $grifes;

$query_1 = \DB::select(" 

select colmod, sum(compras) compras, sum(qtde_recebido) qtde_recebido, sum(qtde_transito) qtde_transito, sum(total_embarcado) total_embarcado, sum(falta_embarcar) falta_embarcar,
	sum(disponivel) disponivel, sum(orcamentos) orcamentos, 
	sum(vendas_0a30DD) vendas_0a30DD, sum(vendas_0a60DD) vendas_0a60DD, sum(vendas_total) vendas_total,
	sum(mostruarios) mostruarios, sum(aberto_kering) aberto_kering, sum(alocado_kering) alocado_kering, sum(ajuste_go) ajuste_go
from (

 	select case when left(colmod,4) < year(now()) then left(colmod,4) 
    when right(colmod,2) in ('01','02','03','04','05','06') then concat(left(colmod,4), '  SS') else concat(left(colmod,4), ' FW') 
     end as colmod, 
    
    sum(compras) compras, sum(qtde_recebido) qtde_recebido, sum(qtde_transito) qtde_transito, sum(total_embarcado) total_embarcado, sum(falta_embarcar) falta_embarcar,
	sum(disponivel) disponivel, sum(orcamentos) orcamentos, 
	sum(vendas_0a30DD) vendas_0a30DD, sum(vendas_0a60DD) vendas_0a60DD, sum(vendas_total) vendas_total,
	sum(mostruarios) mostruarios, sum(aberto) aberto_kering, sum(alocado) alocado_kering, sum(ajuste_go) ajuste_go
	
	from go_storage.ds_kering 
    where codgrife in $grifes
	
	group by colmod
) as fim group by colmod order by left(colmod,4) desc, right(colmod,2) asc
");


$query_2 = \DB::select(" 

	select codgrife, left(agrup,5) agrup, sum(compras) compras, sum(qtde_recebido) qtde_recebido, sum(qtde_transito) qtde_transito, sum(total_embarcado) total_embarcado, sum(falta_embarcar) falta_embarcar,
	sum(disponivel) disponivel, sum(orcamentos) orcamentos, 
	sum(vendas_0a30DD) vendas_0a30DD, sum(vendas_0a60DD) vendas_0a60DD, sum(vendas_total) vendas_total,
	sum(mostruarios) mostruarios, sum(aberto) aberto_kering, sum(alocado) alocado_kering, sum(ajuste_go) ajuste_go
	
	from go_storage.ds_kering where codgrife in $grifes
	
	group by  codgrife, left(agrup,5) 

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

	 		<td colspan="12">Compras Kering</td>
		
				</tr>
		  			
					<tr>	
						
					<td colspan="1" align="center">Colecao</td>				
					<td colspan="1" align="center">Compras</td>
					<td colspan="1" align="center">Recebido</td>
					<td colspan="1" align="center">Transito</td>
					<td colspan="1" align="center">Embarcado</td>
					<td colspan="1" align="center">Falta embarcar</td>
					<td colspan="1" align="center">Disponivel</td>
					<td colspan="1" align="center">Orcamentos</td>
					<td colspan="1" align="center">Vds 0a30</td>
					<td colspan="1" align="center">aberto K</td>
					<td colspan="1" align="center">alocado K</td>
					<td colspan="1" align="center">ajuste go</td>
					
				
					</tr>
			    </thead>
			  
		  
		   
			@foreach ($query_1 as $query1)
		   
		   
			  
				<tr>
				<td align="left"><a href="/dkdet_agrup?agrup={{$query1->colmod}}">{{$query1->colmod}}</a></td>
				<td align="center"><a href="/dkdet_comprasagrup?agrup={{$query1->colmod}}">{{number_format($query1->compras)}}</a></td>
				<td align="center">{{number_format($query1->qtde_recebido)}}</td>
				<td align="center">{{number_format($query1->qtde_transito)}}</td>
				<td align="center">{{number_format($query1->total_embarcado)}}</td>
				<td align="center">{{number_format($query1->falta_embarcar)}}</td>
				<td align="center">{{number_format($query1->disponivel)}}</td>
				<td align="center"><a href="/dkdet_orcagrup?agrup={{$query1->colmod}}">{{number_format($query1->orcamentos)}}</a></td>
				<td align="center">{{number_format($query1->vendas_0a30DD)}}</td>	
				<td align="center">{{number_format($query1->aberto_kering)}}</td>	
				<td align="center">{{number_format($query1->alocado_kering)}}</td>	
				<td align="center">{{number_format($query1->ajuste_go)}}</td>	
				</tr>
			@endforeach 
			
		
		   
			</table>
			
		</div>

	

		<div class="box box-body">	
	   <table class="table table-striped table-bordered compact" id="myTable">
		  <thead>	
			
		 <tr>	

	 		<td colspan="12">Compras Kering</td>
		
				</tr>
		  			
					<tr>	
						
					<td colspan="1" align="center">Agrup</td>				
					<td colspan="1" align="center">Compras</td>
					<td colspan="1" align="center">Recebido</td>
					<td colspan="1" align="center">Transito</td>
					<td colspan="1" align="center">Embarcado</td>
					<td colspan="1" align="center">Falta embarcar</td>
					<td colspan="1" align="center">Disponivel</td>
					<td colspan="1" align="center">Orcamentos</td>
					<td colspan="1" align="center">Vds 0a30</td>
					<td colspan="1" align="center">aberto K</td>
					<td colspan="1" align="center">alocado K</td>
					<td colspan="1" align="center">ajuste go</td>
					
				
					</tr>
			    </thead>
			  
		  
		   
			@foreach ($query_2 as $query2)
		   
		   
			  
				<tr>
				<td align="left"><a href="/dkdet_agrup?agrup={{$query2->agrup}}">{{$query2->agrup}}</a></td>
				<td align="center"><a href="/dkdet_comprasagrup?agrup={{$query2->agrup}}">{{number_format($query2->compras)}}</a></td>
				<td align="center">{{number_format($query2->qtde_recebido)}}</td>
				<td align="center">{{number_format($query2->qtde_transito)}}</td>
				<td align="center">{{number_format($query2->total_embarcado)}}</td>
				<td align="center">{{number_format($query2->falta_embarcar)}}</td>
				<td align="center">{{number_format($query2->disponivel)}}</td>
				<td align="center"><a href="/dkdet_orcagrup?agrup={{$query2->agrup}}">{{number_format($query2->orcamentos)}}</a></td>
				<td align="center">{{number_format($query2->vendas_0a30DD)}}</td>	
				<td align="center">{{number_format($query2->aberto_kering)}}</td>	
				<td align="center">{{number_format($query2->alocado_kering)}}</td>	
				<td align="center">{{number_format($query2->ajuste_go)}}</td>	
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