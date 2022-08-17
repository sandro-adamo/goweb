@extends('layout.principal')

@section('title')
<i class="fa fa-suitcase"></i> Dashboard Comercial
@append 

@section('conteudo')

<form action="" method="get"> 
@php

 $representantes = Session::get('representantes');
 $grifes = Session::get('grifes');

$item = $_GET["item"];	
echo $item;
	
	
  $where = ' where id_rep in ('.$representantes.') ';
  $where2 = ' where rep_comissao in ('.$representantes.') ';

 //echo $where; 
	

$query_1 = \DB::select("
select id_cliente cod_cliente, fantasia, uf, municipio, dt_emissao, secundario, sum(qtde) qtde 
	from notas_jde nfs 
	left join itens on itens.id = nfs.id_item 
    left join addressbook ab on ab.id = nfs.id_cliente
    where datediff(now(),dt_emissao) > 5 and secundario = '$item'  
	group by id_cliente, fantasia, uf, municipio, dt_emissao
    order by dt_emissao desc, uf, municipio, secundario
");


	
	
$query_2 = \DB::select("
	             
	select uf,  sum(qtde) qtde 
	from notas_jde nfs 
	left join itens on itens.id = nfs.id_item 
    left join addressbook ab on ab.id = nfs.id_cliente
    where datediff(now(),dt_emissao) > 5 and secundario = '$item'
	group by uf
    order by uf
");


$query_3 = \DB::select("
		select id_cliente, sum(qtde) qtde 
	from vendas_jde vds 
	left join itens on itens.id = vds.id_item where secundario = '$item' group by id_cliente
");

echo 'teste'.$where2;
	

@endphp
<h6>

<div class="row">

		<div class="col-md-6">
		<div class="box box-body box-widget">
			<table class="table table-bordered"> 
		 <tr>	
	 		<td colspan="8">Clientes que compraram o item {{$item}} por data de fauramento</td>
				</tr>
		  <tr>	
	 		
	 		<td colspan="1" align="center">cliente</td>
		 	<td colspan="1" align="center">QTDE</td>
		 	
	
		
			
			@foreach ($query_1 as $query1)
				<tr>
					<td align="center"><a href="/itens_clientes_cliente?cliente={{$query1->cod_cliente}}">{{$query1->cod_cliente}}</a></td>
					<td align="center">{{$query1->fantasia}}</td>
					<td align="center">{{$query1->uf}}</td>
					<td align="center">{{$query1->municipio}}</td>
					<td align="center">{{$query1->dt_emissao}}</td>
					<td align="center" class="text-red">
					{{number_format($query1->qtde, 0, ',', '.')}}</td>
					

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
	 		<td colspan="8">Clientes que compraram o item {{$item}} por UF</td>
				</tr>
		  <tr>		 		
	 		<td colspan="1" align="center">UF</td>	
		 	<td colspan="1" align="center">QTDE</td>

			@foreach ($query_2 as $query2)
		  <tr>
					<td align="center"><a href="/itens_clientes_uf?uf={{$query2->uf}}&item={{$item}}">{{$query2->uf}}</a></td>
					<td align="center" class="text-green">{{number_format($query2->qtde, 0, ',', '.')}}</td>

		  </tr>
			@endforeach 
			</table>
			<i><a href="">+</a></i>
		</div>
		
	</div>
	




	

</div>
</form>
</h6>
@stop