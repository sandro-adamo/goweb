@extends('layout.principal')
@section('conteudo')

@php

$representantes = Session::get('representantes');

 $agrup = $_GET["agrup"];


if($representantes==101815) 
	{$grifes="( 'AM', 'BC', 'BV', 'CT', 'SM', 'MC', 'CH', 'DU', 'AA', 'AZ', 'CL')";} 
		else {
			$grifes = Session::get('grifes'); }

echo $agrup;


$query_2 = \DB::select(" 

		select codgrife, agrup,  ci.id_compra pedido, pedido_dt, compras.obs, sum(qtde) qtde
		
        from compras_itens ci
		left join itens on ci.id_item = itens.id
        left join compras on compras.id = ci.id_compra
		
        where codgrife in $grifes and codtipoitem = 006 and substring(agrup,1,4) = '$agrup'
		and ci.status <> 'cancelado' 
		
        group by codgrife, agrup,  ci.id_compra, pedido_dt, compras.obs


");
			  
			
@endphp

<form action="" method="get"> 

<h6>

	
							
<div class="row"> 
	
		<div class="col-md-8">	
		<div class="box box-body">
			
	   <table class="table table-striped table-bordered compact" id="myTable">
		  <thead>	
			
		 <tr>	

	 		<td colspan="8">Detalhe Agrupamento {{$agrup}}</td>
		
				</tr>
		  			
					<tr>	
					<td colspan="1" align="center">dt_pedido</td>	
					<td colspan="1" align="center">pedido</td>					
					<td colspan="1" align="center">obs</td>
					<td colspan="1" align="center">colecoes</td>
					<td colspan="1" align="center">qtde</td>				
					</tr>
			  
			    </thead>
			  
				@foreach ($query_2 as $query2)
			  
				<tr>
					<td align="left">{{$query2->pedido_dt}}</td>
					<td align="left"><a href="/dkdet_item?item={{$query2->pedido}}">{{$query2->pedido}}</a></td>
					<td align="left">{{$query2->obs}}</td>
					<td></td>
					<td align="center">{{$query2->qtde}}</td>
					
					
				
				
				</tr>
			@endforeach 
			
			</table>
			
		</div>
	</div>	
</div>
</h6>			
	
</form>

@stop