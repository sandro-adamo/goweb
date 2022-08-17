@extends('layout.principal')

@php

 $representantes = Session::get('representantes');
 $grifes = Session::get('grifes');

 $faixa = $_GET["faixa"];
 $tipo = $_GET["tipo"];



@endphp

@section('title')
<i class="fa fa-users"></i> Dashboard Clientes para a grife {{$tipo}}
@append 

@section('conteudo')

<form action="" method="get"> 
@php



	
	
echo 'faixa '.$faixa;
echo 'tipo '.$tipo;
	

$query_1 = \DB::select("
 
	select * from (
	
	
			select cod_cliente, cliente, left(municipios,30) municipios, max(ult_compra) ult_compra, count(codgrife) grifes, 
			group_concat(codgrife,' ' order by codgrife) grife,
			group_concat(distinct ufs, ' ' order by ufs) ufs from (

				select cart.cod_cliente, cart.cliente, codgrife, 
				-- group_concat(distinct regiao, '' order by regiao) regioes, 
	
				group_concat(distinct left(municipio,10), ' ' order by municipio) municipios, 
				group_concat(distinct left(uf,10), ' ' order by uf) ufs ,  max(ult_compra) ult_compra
				
				from ds_carteira cart
				left join addressbook ab on ab.cliente = cart.cliente
				where $tipo > 0 and rep_carteira in ($representantes)				                          
				
				group by cart.cod_cliente, cart.cliente, codgrife

			) as fim group by cod_cliente, cliente,  left(municipios,30) 
		) as fim1 where grifes = $faixa

		
																																			
");
	
	echo count($query_1);
	
	
@endphp

<h6>
			
						
						
<div class="row">

		<div class="col-md-6">
		<div class="box box-body box-widget">
		 
		   <table class="table table-bordered" id="example3">
			 <thead>   
		 <tr>	
			 	
	 		<td colspan="5">clientes (subgrupos) com compras {{$tipo}}</td>
		
				</tr>
		  		
					<tr>	
					
					<td colspan="1" align="center">Cliente</td>
					<td colspan="1" align="center">Grifes compradas </td>
					<td colspan="1" align="center">UFs </td>
					<td colspan="1" align="center">Municipios </td>
					<td colspan="1" align="center">Ult compra Cliente </td>
					 </thead>
				
					</tr>

			  
			@foreach ($query_1 as $query1)

			  
				<tr>
					<td align="left"><a href="/det_subgrupo?pdv={{$query1->cod_cliente}}&codgrife={{$query1->grife}}">{{$query1->cliente}}</a></td>
					<td align="center">{{$query1->grife}}</td>
					<td align="center">{{$query1->ufs}}</td>
					<td align="center">{{$query1->municipios}}</td>
					<td align="center">{{$query1->ult_compra}}</td>
				
				</tr>
			@endforeach 
			
			</table>
			


		</div>

	</div>
	
	
	

</div>
	
	
	
	
</form>
</h6>
@stop