@extends('layout.principal')

@section('title')
  <i class="fa fa-gears"></i> Pesquisa Status
@append 

@section('conteudo')

<div class="table-responsive">
	<div class="col-xs-12">

		@if (Session::has('alert'))
		<div class="callout callout-warning">{{Session::get('alert')}}</div>
		@endif

		<form method="get" action="">
			<div class="col-md-5">
				<input type="text" name="referencia" id="referencia" autofocus  required="" class="form-control" value=""  placeholder="referencia" />
			</div>        
			<div class="col-md-2">
				<input type="submit" value="Pesquisa" class="btn btn-flat btn-primary" onclick="PlaySound()">
			</div>
		</form>

	</div>
</div>

<br>



@php
	$item = array();
	$modelo = array();
	
	if(isset($_GET["referencia"])) {
		$referencia = $_GET["referencia"];
		$item = \DB::select("select 
case when clasmod like 'li%' then 'MANTER' else 'DEVOLVER' end as st_peca, modelo, secundario, statusatual, datastatusatual, ultstatus, dataultstatus
from itens where secundario = '$referencia'");

		$modelo = $item[0]->modelo;
		$modelo = \DB::
		
		select("select * from itens where modelo = '$modelo'");
	}
	$id_usuario = \Auth::user()->id_addressbook;

	$processamento = \App\StatusProcessa::orderBy('data', 'desc')->select('processamento')->first();


	$geral = \DB::select("select statusatual as status_atual, count(mostruarios.cod_curto) as itens
								from mostruarios
								/*left join processa on processa.id_item = mostruarios.cod_curto and processamento = '$processamento->processamento'*/
								left join itens on cod_curto = itens.id
								where mostruarios.AN8 = '$id_usuario'
								group by statusatual");


	$divergencia = \DB::select("select acao, ind_status_atual, statusatual as status_atual,  ind_ultimo_status, ultstatus as ultimo_st, count(itens) AS itens  from (

			select 
			case 
			when atual.indice = 9 and ultimo.indice = 9 	 then 'e_devolver'
			 when atual.indice <= 5 and ultimo.indice <= 5 then 'a_manter_venda'
			 when atual.indice <= 5 and ultimo.indice > 5  then 'b_retornar_venda'
			 when atual.indice > 5 and ultimo.indice <= 5  then 'c_tirar_venda'
			 when atual.indice > 5 and ultimo.indice > 5   then 'd_manter_fora' else 'o_outro'
			end as acao, 

			atual.indice as ind_status_atual, statusatual, ultimo.indice as ind_ultimo_status, ultstatus,  mostruarios.cod_curto AS itens

			from mostruarios
			/*left join processa on processa.id_item = mostruarios.cod_curto and processamento = $processamento->processamento*/
			left join itens on cod_curto = itens.id
			left join ind_status atual on codstatusatual = atual.id_status
			left join ind_status ultimo on codultstatus = ultimo.id_status



			WHERE atual.indice <> ultimo.indice and mostruarios.AN8 = '$id_usuario'

			) as sele1

			group by acao, ind_status_atual, status_atual, ind_ultimo_status, ultimo_st
			order by acao, ind_status_atual, ind_ultimo_status");

@endphp


@if (isset($_GET["referencia"]))
<div class="table-responsive">
	<div class="col-xs-4">

		<!-- FOTO -->
		<img class='img-responsive' src="https://portal.goeyewear.com.br/teste999.php?referencia={{$item[0]->secundario}}" alt='Photo' width="250"> 
		{{$item[0]->secundario}}


		<table class="table table-hover">
			<tr>                 
				<th>ULTIMO</th>
				<th>DATA</th>                 
			</tr>
			<tr>
				<td>{{$item[0]->statusatual}}</td>
				<td>{{$item[0]->datastatusatual}}</td>
				<td>{{-- {{$item[0]->st_peca}} --}}</td>
			</tr>
			<tr>                 
				<th></th>
				<th></th>
			</tr>
			<tr>                 
				<th>Penultimo</th>
				<th></th>                 
			</tr>
			<tr>	
				<td>{{$item[0]->ultstatus}}</td>
				<!--		<td>{{$item[0]->dataultstatus}}</td>-->
				<td>2018-12-07</td>
			</tr>
		</table>
	</div>







	<div class="col-md-7">
		<div class="box box-body">
			<table class="table table-condensed table-bordered">

				<tr>
					<th>ITEM</th>
					<th>FOTO</th>
					<th></th>
					<th>ULTIMO STATUS</th>
					<th>VALOR</th>
				</tr>
				
				@foreach ($modelo as $modelos)
				
				<tr>	
					<td align="center" valign="middle">  {{$modelos->secundario}}</td>

					<td align="left"><img class='img-responsive' src="https://portal.goeyewear.com.br/teste999.php?referencia={{$modelos->secundario}}" alt='Photo' width="100"> </td>
					<td VALIGN="MIDDLE"><form method="get" action="">
						<input type="hidden" name="referencia" autofocus class="form-control" value="{{$modelos->secundario}}" />

						<i class=""></i> 
						<input type="submit" value="Verificar Status" class="pull-right btn btn-flat btn-primary" onclick="PlaySound()">
					</form></td>
					<td VALIGN="MIDDLE">{{$modelos->statusatual}}</td>
					<td align="left">R${{$modelos->valortabela}}</td>
					
					

				</tr>
				@endforeach


			</table>

		</div> 
	</div>

@endif
	
</div>


@stop