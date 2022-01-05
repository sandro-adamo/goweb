@extends('layout.principal')
@section('conteudo')

@php

$representantes = Session::get('representantes');

 $item = $_GET["item"];


if($representantes==101815) 
	{$grifes="( 'AM', 'BC', 'BV', 'CT', 'SM', 'MC', 'CH', 'DU', 'AA', 'AZ', 'CL')";} 
		else {
			$grifes = Session::get('grifes'); }

echo $item;


$query_1 = \DB::select(" 

		select codgrife, agrup, colmod, primario, secundario item, ci.id_compra pedido, pedido_dt, compras.obs, sum(qtde) qtde
		
        from compras_itens ci
		left join itens on ci.id_item = itens.id
        left join compras on compras.id = ci.id_compra
		
        where codgrife in $grifes and codtipoitem = 006 and secundario = '$item'
		and ci.status not in ('cancelado') and ci.id_compra <> '202442'
		
        group by codgrife, agrup, colmod, primario, secundario, ci.id_compra, pedido_dt, compras.obs
		order by pedido_dt

");



$query_2 = \DB::select(" 

	select pedido, dt_pedido, prox_status, ult_status, tipo, qtde_sol, ref_go, ref_despachante, tipo_ref_nac, ref_nac_01, ref_nac_02

	from importacoes_pedidos imp 
	left join itens on imp.cod_item = itens.id
	where tipo_linha = 'bs' and ult_status not in (980) 
	and codgrife in $grifes and imp.secundario = '$item'
order by dt_pedido

");




$query_3 = \DB::select(" 
		select pedido, dt_pedido, tipo, concat(ref_go,ref_despachante) obs, ref_nac_01, concat(ult_status, ' / ',prox_status) ult_prox, imp.secundario,             
		case 
		when prox_status = 230 then 'ped_inserido' 
		when prox_status = 280 then 'PL_recebido' 
		when prox_status = 345 then 'confirmado' 
        when prox_status = 350 then 'li_solicitado'
        when prox_status = 355 then 'li_deferida'
        when prox_status = 359 then 'emb_autorizado'
        when prox_status = 365 then 'booking'
        when prox_status = 369 then 'chegada_Br'
        when prox_status = 375 then 'removido'
        when prox_status = 379 then 'registrado'
        when prox_status = 385 then 'nf_emitida'
        when prox_status = 390 then 'carregada'
        when prox_status = 400 then 'chegou_TO' else '' end as desc_status, qtde_sol qtde
			 
			from importacoes_pedidos imp 
			left join itens on itens.id = cod_item		
			where ref_go not in ('LA200501','QGKI17-7B') and ult_status not in (980) and prox_status not in (999,400)
			and itens.secundario = '$item'
            
	union all 
    
    
	select c.id pedido, c.dt_emissao dt_pedido, '' tipo, c.obs, '' ref_nac_1, '' ult_prox, itens.secundario, 'pre-embarque' as desc_status, sum(qtde) pre_embarque
	from compras_itens ci
	left join compras c on c.id = ci.id_compra
	left join itens on ci.item = itens.secundario
	where c.tipo = 'pre-embarque' and itens.secundario = '$item'
	group by c.id, c.dt_emissao,  c.obs, itens.secundario
");

		  
			
@endphp

<form action="" method="get"> 

<h6>
						
<div class="row"> 
	
		<div class="col-md-5">	
		<div class="box box-body">
			
	   <table class="table table-striped table-bordered compact" id="myTable">
		  <thead>	
			
		 <tr>	

	 		<td colspan="5">PEDIDOS DE COMPRA {{$item}}</td>
		
				</tr>
		  			
					<tr>	
						
					<td colspan="1" align="center">Item</td>
					<td colspan="1" align="center">Pedido</td>
					<td colspan="1" align="center">Data</td>
					<td colspan="1" align="center">qtde</td>
								
					</tr>
			    </thead>
			  
			@foreach ($query_1 as $query1)
			  
				<tr>
				<td align="left"><a href="/dsimportdet?tipo={{$query1->item}}&pedido={{$query1->item}}">{{$query1->item}}</a></td>
				<td align="center">{{$query1->pedido}}</td>
					<td align="center">{{$query1->pedido_dt}}</td>
				<td align="center">{{number_format($query1->qtde)}}</td>
				
				</tr>
			@endforeach 
			
			</table>
			
		</div>
	</div>	
</div>
	

	
							
<div class="row"> 
	
		<div class="col-md-5">	
		<div class="box box-body">
			
	   <table class="table table-striped table-bordered compact" id="myTable">
		  <thead>	
			
		 <tr>	

	 		<td colspan="5">RECEBIMENTOS IMPORTACAO {{$item}}</td>
		
				</tr>
		  			
					<tr>	
						
					<td colspan="1" align="center">Item</td>
					<td colspan="1" align="center">Pedido</td>
					<td colspan="1" align="center">Data</td>
					<td colspan="1" align="center">qtde</td>
					
					
				
					</tr>
			    </thead>
			  
			@foreach ($query_2 as $query2)
			  
				<tr>
				<td align="left"><a href="/dsimportdet?tipo={{$query2->pedido}}&pedido={{$query2->pedido}}">{{$query2->pedido}}</a></td>
				<td align="center">{{$query2->pedido}}</td>
					<td align="center">{{$query2->dt_pedido}}</td>
				<td align="center">{{number_format($query2->qtde_sol)}}</td>
				
				</tr>
			@endforeach 
			
			</table>
			
		</div>
	</div>	
</div>
	

	
	
	
<div class="row"> 
	
		<div class="col-md-5">	
		<div class="box box-body">
			
	   <table class="table table-striped table-bordered compact" id="myTable">
		  <thead>	
			
		 <tr>	

	 		<td colspan="5">CET {{$item}}</td>
		
				</tr>
		  			
					<tr>	
						
					
					<td colspan="1" align="center">Pedido</td>
					<td colspan="1" align="center">data</td>
					
					<td colspan="1" align="center">tipo</td>
					<td colspan="1" align="center">status</td>
					<td colspan="1" align="center">desc status</td>
					<td colspan="1" align="center">qtde</td>
					
					
				
					</tr>
			    </thead>
			  
			@foreach ($query_3 as $query3)
			  
				<tr>
				<td align="left"><a href="/dsimportdet?tipo={{$query3->tipo}}&pedido={{$query3->pedido}}">{{$query3->tipo.' '.$query3->pedido}}</a></td>
				<td align="center">{{$query3->dt_pedido}}</td>
			
				<td align="center">{{$query3->tipo}}</td>
				<td align="center">{{$query3->ult_prox}}</td>
				<td align="center">{{$query3->desc_status}}</td>
				<td align="center">{{number_format($query3->qtde)}}</td>
				
				</tr>
			@endforeach 
			
			</table>
			
		</div>
	</div>	
</div>	
	
	
	
	
</h6>			
	
</form>

@stop