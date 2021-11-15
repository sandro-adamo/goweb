@extends('layout.principal')

@section('title')
  <i class="fa fa-suitcase"></i> Devoluções de Mostruários
@append 

@section('conteudo')

@php
//$grifes = Session::get('grifes');
//echo 'grifes'.$grifes.'</br>';
@endphp


@if (isset($listas) && count($listas) > 0)


		@php
			$id_usuario = \Auth::id();
			
			$listas = \DB::select("select id_lista, status_lista, count(*) as itens, max(created_at) as data
											from devolucoes
											where id_usuario = $id_usuario
											group by id_lista,status_lista
											order by id_lista desc
											limit 5");

			$permite_nova = '';

			foreach ($listas as $lista) {


				if ($lista->status_lista <> 'Enviada') {
					$permite_nova =  ' disabled ';
				}

			}

		@endphp

		<div class="box box-body">
			<div class="row">
				<div class="col-md-12" align="right">
					
					<a href="/mostruarios/devolucoes/nova" class="btn btn-flat btn-success  {{$permite_nova}}" ><i class="fa fa-plus"></i> Nova Lista de Devolução</a>

				</div>
			</div>

			<br>


			<table class="table table-bordered">

				<tr>
					<th>status</th>
					<th>lista</th>
					<th>itens</th>
					<th>data</th>
					<th>excel</th>
				</tr>


				@foreach ($listas as $lista)

					<tr>
						<td><a href="/mostruarios/devolucoes/{{$lista->id_lista}}"> {{$lista->status_lista}}</a></td>
						<td>{{$lista->id_lista}}</td>
						<td>{{$lista->itens}}</td>
						<td>{{$lista->data}}</td>
						<td><a href="/mostruarios/devolucoes/{{$lista->id_lista}}/excel"> excel</a></td>
					</tr>

				@endforeach 

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