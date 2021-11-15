@extends('layout.principal')

@section('title')
<i class="fa fa-suitcase"></i> Dashboard Comercial
@append 

@section('conteudo')

<form action="" method="get"> 
@php

 $representantes = Session::get('representantes');
 $grifes = Session::get('grifes');

$cliente = $_GET["cliente"];
	

echo $cliente;

	
	
  $where = ' where id_rep in ('.$representantes.') ';
  $where2 = ' where rep_comissao in ('.$representantes.') ';

 //echo $where; 
	

$query_1 = \DB::select("
select * from addressbook where id = '$cliente' ");

@endphp
<h6>

<div class="row">

		<div class="col-md-10">
		<div class="box box-body box-widget">
			<table class="table table-bordered"> 
		 <tr>	
	 		<td colspan="8">Detalhe do cliente</td>
				</tr>
		  <tr>	
	 		
	 		<td colspan="1" align="center">codigo</td>
		 	<td colspan="1" align="center">fantasia</td>
			  <td colspan="1" align="center">uf</td>
			  <td colspan="1" align="center">municipio</td>
			  <td colspan="1" align="center">endereco</td>
			  <td colspan="1" align="center">bairro</td>
			  <td colspan="1" align="center">ddd</td>
			  <td colspan="1" align="center">tel</td>
			
		 	
	
		
			
			@foreach ($query_1 as $query1)
				<tr>
					<td align="center">{{$query1->id}}</td>
					<td align="center">{{$query1->fantasia}}</td>
					<td align="center">{{$query1->uf}}</td>
					<td align="center">{{$query1->municipio}}</td>
					<td align="center">{{$query1->endereco.' , '.$query1->numero}}</td>
					<td align="center">{{$query1->bairro}}</td>
					<td align="center">{{$query1->ddd1}}</td>
					<td align="center">{{$query1->tel1}}</td>
		
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