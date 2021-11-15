@extends('layout.principal')

@section('title')
  <i class="fa fa-suitcase"></i> Devoluções de Mostruários
@append 

@section('conteudo')

@php
//$grifes = Session::get('grifes');
//echo 'grifes'.$grifes.'</br>';
@endphp


@if ($devolucoes)


		@php
			$id_usuario = \Auth::id();
			$id_representante = \Auth::user()->id_addressbook;

			$devolucoes_abertas = \DB::connection('goweb')->select("select * from devolucoes where id_cliente = '$id_representante' and situacao IN ('Aberta', 'Pendente')");

			// if (\Auth::user()->id_perfil <> 4) {
			// 	$listas = \DB::select("select  id_usuario,usuarios.nome, id_lista, status_lista, count(*) as itens, max(devolucoes.created_at) as data
			// 									from devolucoes
			// 									left join usuarios on id_usuario = usuarios.id
			// 									where devolucoes.status = 1 and situacao = 'DEVOLVER'
			// 									group by id_usuario, usuarios.nome, id_lista,status_lista
			// 									order by usuarios.nome, id_lista desc");
			// } else {
			// 	$listas = \DB::select("select id_usuario, usuarios.nome, id_lista, status_lista, count(*) as itens, max(devolucoes.created_at) as data
			// 									from devolucoes
			// 									left join usuarios on id_usuario = usuarios.id
			// 									where id_usuario = $id_usuario and devolucoes.status = 1  and situacao = 'DEVOLVER'
			// 									group by id_usuario, usuarios.nome, id_lista,status_lista
			// 									order by id_lista desc
			// 									limit 5");				
			// }
			 $permite_nova = '';

			// foreach ($listas as $lista) {


			 	if (($devolucoes_abertas && count($devolucoes_abertas)>0) ) {
			 		$permite_nova =  ' disabled ';
			 	}

			// }

		@endphp

		<div class="box box-body">
			<div class="row">
				<div class="col-md-12" align="right">
					
					<a href="/mostruarios/devolucoes/nova" class="btn btn-flat btn-primary" {{$permite_nova}}><i class="fa fa-plus"></i> Nova Lista de Devolução</a>

				</div>
			</div>

			<br>


			<table class="table table-bordered">
			<thead>
				<tr>
					<th>ID</th>
					<th>Data</th>
					<th>Data</th>
					<th>itens</th>
					<th>data</th>
					<th>excel</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($devolucoes as $devolucao)

					<tr>
						<td align="center"><a href="/mostruarios/devolucoes/{{$devolucao->id}}"> {{$devolucao->id}}</a></td>
						<td align="center">{{date('d/m/Y', strtotime($devolucao->created_at))}}</td>
						<td>{{$devolucao->situacao}}</td>
						<td>{{$devolucao->id_lista}}</td>
						<td>{{$devolucao->itens}}</td>
						<td>{{$devolucao->data}}</td>
						<td><a href="/mostruarios/devolucoes/{{$devolucao->id}}/excel"> excel</a></td>
					</tr>

				@endforeach 
			</tbody>
			</table>
		</div>

@else


<div class="col-md-12" align="center" style="margin-top: 150px;">

	<h2>Nenhum lista de devolução gerada</h2><br><br>

	<a href="/mostruarios/devolucoes/nova" class="btn btn-flat btn-success btn-lg"><i class="fa fa-plus"></i> Nova lista de devolução</a>

</div>


@endif




</h6>

@stop