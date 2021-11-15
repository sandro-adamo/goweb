@extends('layout.principal')

@section('title')
<i class="fa fa-suitcase"></i> Dashboard Comercial
@append 

@section('conteudo')

<form action="" method="get"> 
@php

 $representantes = Session::get('representantes');
 $grifes = Session::get('grifes');
	
 $titulo = $_GET["titulo"];
 $parcela = $_GET["parcela"];
 $tipo = $_GET["tipo"];
 $valor_pago = $_GET["valor_parcela"];
	

  $where = ' where id_rep in ('.$representantes.') ';
  $where2 = ' where rep_comissao in ('.$representantes.') ';

 //echo $where; 
	

$query_vendas = \DB::select("	
select  nome, id_cliente, titulo, parcela, ped_original, cod_grife, id_rep, sum(valor_pedido) valor_pedido, max(valor_total) valor_total, max(valor_pago) valor_pago,
(sum(valor_pedido)/max(valor_total))*100 perc_pedido, (sum(valor_pedido)/max(valor_total))*max(valor_pago) as perc_pago from (

	select ab.fantasia nome, tit.id_cliente, tit.titulo, tit.parcela, tit.ped_original, cod_grife, ped.id_rep, (ped.valor) valor_pedido, (valor_pago) valor_pago,
		(select sum(ped1.valor) from pedidos_jde ped1 where tipo_item = 006 and ped1.pedido = ped.pedido) valor_total
		
	from titulos tit  
	left join pedidos_jde ped on ped.pedido = tit.ped_original
	left join addressbook ab on ab.id = ped.id_rep
	
	where tipo_item = 006 and titulo = $titulo and parcela = $parcela and tit.tipo = '$tipo'
) as FIM
group by nome, id_cliente, titulo, parcela, ped_original, cod_grife, id_rep
order by cod_grife
	
");

	
$query_pedidos = \DB::select("
select ped_original, id_rep, sum(valor_pedido) valor_pedido, max(valor_total) valor_total, max(valor_pago) valor_pago,
(sum(valor_pedido)/max(valor_total))*100 perc_pedido, (sum(valor_pedido)/max(valor_total))*max(valor_pago) as perc_pago from (

	select tit.id_cliente, tit.titulo, tit.parcela, tit.ped_original, cod_grife, ped.id_rep, (ped.valor) valor_pedido, (valor_pago) valor_pago,
		(select sum(ped1.valor) from pedidos_jde ped1 where tipo_item = 006 and ped1.pedido = ped.pedido) valor_total
		
	from titulos tit  
	left join pedidos_jde ped on ped.pedido = tit.ped_original
	
	
	where tipo_item = 006 and titulo = $titulo and parcela = $parcela and tit.tipo = '$tipo'
) as FIM
group by ped_original, id_rep
order by ped_original
");

	
$query_faturas = \DB::select("
select id_cliente, titulo, parcela, cod_grife, id_rep, sum(valor_pedido) valor_pedido, max(valor_total) valor_total, max(valor_pago) valor_pago,
(sum(valor_pedido)/max(valor_total))*100 perc_pedido, (sum(valor_pedido)/max(valor_total))*max(valor_pago) as perc_pago from (

	select tit.id_cliente, tit.titulo, tit.parcela, cod_grife, ped.id_rep, (ped.valor) valor_pedido, (valor_pago) valor_pago,
		(select sum(ped1.valor) from pedidos_jde ped1 where tipo_item = 006 and ped1.pedido = ped.pedido) valor_total
		
	from titulos tit  
	left join pedidos_jde ped on ped.pedido = tit.ped_original
	where tipo_item = 006 and titulo = $titulo and parcela = $parcela and tit.tipo = '$tipo'
) as FIM
group by id_cliente, titulo, parcela, cod_grife, id_rep
order by cod_grife
");

echo 'teste'.$where2;
	

@endphp
<h6>

<div class="row">

		<div class="col-md-4">
		<div class="box box-body box-widget">
			<table class="table table-bordered"> 
	
		<tr>	
		
	 		<td colspan="6"><b>TITULO:</b> {{$titulo}} <b>PARCELA:</b> {{$parcela}}  <b>VALOR PAGO:</b> {{$valor_pago}}</td>
				
		 </tr>
			
				
		  <tr>	
	 		<td colspan="1" align="center">REPRESENTANTE</td>
		 	<td colspan="1" align="center">perc_pedido</td>
			<td colspan="1" align="center">Proporcao Pago</td>
			    <td colspan="1" align="center">Porporcao s/ imp</td>
		  </tr>
		
			
			@foreach ($query_vendas as $query1)
				<tr>
					<td align="left">{{$query1->nome}}</td>
					<td align="center">{{number_format($query1->perc_pedido,2)}}%</td>
					<td align="center">{{number_format($query1->perc_pago,2)}}</td>
					
					<td></td>
				</tr>
			@endforeach 
			</table>
			<i><a href="">+</a></i>
		</div>
		
	</div>

	
	
	
	
	
	<div class="col-md-4">
		<div class="box box-body box-widget">
			<table class="table table-bordered"> 
		 <tr>	
	 		<td colspan="8">pedidos (filtro de datas)</td>
				</tr>
		  <tr>	
	 		
	 		<td colspan="1" align="center">Grife</td>	
		 	<td colspan="1" align="center">vlr total Pedido</td>
		 	<td colspan="1" align="center">Porporcao boleto</td>
			<td colspan="1" align="center">Porporcao s/ imp</td>
		 	
	
		
			
			@foreach ($query_faturas as $query2)
				<tr>
					<td align="center">{{$query2->cod_grife}}</td>
				
					
					<td align="center">{{number_format($query2->valor_pedido,2)}}</td>
					<td align="center">{{number_format($query2->perc_pago,2)}}</td>
					<td></td>
				</tr>
			@endforeach 
			</table>
			<i><a href="">+</a></i>
		</div>
		
	</div>
	


	
	
	
	
	
	
	
	<div class="col-md-4">
		<div class="box box-body box-widget">
			<table class="table table-bordered"> 
		 <tr>	
	 		<td colspan="8">pedido_original (filtro de datas)</td>
				</tr>
		  <tr>	
			  	<td colspan="1" align="center">pedido_original</td>
			    	<td colspan="1" align="center">dt ped_original</td>
	 		<td colspan="1" align="center">repres</td>	
	 		<td colspan="1" align="center">perc</td>		
			  <td colspan="1" align="center">valor</td>		

	
	
									
			
@foreach ($query_pedidos as $query3)
	<tr>

					<td align="center">{{$query3->ped_original}}</td>
		<td></td>
					<td align="center">{{$query3->id_rep}}</td>
		
					<td align="center">{{number_format($query3->perc_pedido,2)}}%</td>
					<td align="center">{{number_format($query3->perc_pago,2)}}</td>
					
</tr>
@endforeach 
				
			</table>
			<i><a href="">+</a></i>
		</div>
		
	</div>



		
				
	<div class="col-md-3">
		<div class="box box-body box-widget">
		outras parcelas desse titulo
		</div>
	</div>
	
	<div class="col-md-3">
		<div class="box box-body box-widget">
		y
		</div>
	</div>

	
	<div class="col-md-3">
		<div class="box box-body box-widget">
		z
		</div>
	</div>


	

</div>
</form>
</h6>
@stop