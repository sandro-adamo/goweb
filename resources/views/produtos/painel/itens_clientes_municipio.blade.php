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
$uf = $_GET["uf"];
$municipio = $_GET["municipio"];
	
echo $item;
echo $uf;
echo $municipio;
	
	
  $where = ' where id_rep in ('.$representantes.') ';
  $where2 = ' where rep_comissao in ('.$representantes.') ';

 //echo $where; 
	

$query_1 = \DB::select("
select id_cliente cod_cliente, fantasia, uf, municipio, endereco, bairro,dt_emissao, secundario, sum(qtde) qtde 
	from notas_jde nfs 
	left join itens on itens.id = nfs.id_item 
    left join addressbook ab on ab.id = nfs.id_cliente
    where datediff(now(),dt_emissao) > 5 and secundario = '$item' and uf = '$uf' and municipio = '$municipio'
	group by id_cliente, fantasia, uf, municipio, dt_emissao
    order by dt_emissao desc, uf, municipio, secundario, endereco, bairro
");

	

@endphp
<h6>

<div class="row">

		<div class="col-md-10">
		<div class="box box-body box-widget">
			<table class="table table-bordered"> 
		 <tr>	
	 		<td colspan="8">Clientes que compraram o item {{$item}} por data de fauramento</td>
				</tr>
		  <tr>	
	 		
	 		<td colspan="1" align="center">codigo</td>
		 	<td colspan="1" align="center">fantasia</td>
			  <td colspan="1" align="center">uf</td>
			  <td colspan="1" align="center">municipio</td>
			  <td colspan="1" align="center">endereco</td>
			  <td colspan="1" align="center">bairro</td>
			  <td colspan="1" align="center">dt_compra</td>
			  <td colspan="1" align="center">qtde</td>
		 	
	
		
			
			@foreach ($query_1 as $query1)
				<tr>
					<td align="center"><a href="/itens_clientes_cliente?cliente={{$query1->cod_cliente}}">{{$query1->cod_cliente}}</td>
					<td align="center">{{$query1->fantasia}}</td>
					<td align="center">{{$query1->uf}}</td>
					<td align="center">{{$query1->municipio}}</td>
					<td align="center">{{$query1->endereco}}</td>
					<td align="center">{{$query1->bairro}}</td>
					<td align="center">{{$query1->dt_emissao}}</td>
					<td align="center" class="text-red">
					{{number_format($query1->qtde, 0, ',', '.')}}</td>
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