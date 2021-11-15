@extends('layout.principal')

@section('title')
  <i class="fa fa-gears"></i> Gera Devolucao  /   <i class="fa fa-file-excel-o"></i> exporta excel lista 
@append 

@section('conteudo')

@php
//$grifes = Session::get('grifes');
//echo 'grifes'.$grifes.'</br>';
@endphp


@if ($listas)
<h6>
<div class="table-responsive">
	<div class="col-xs-12">

		@if (Session::has('alert'))
		<div class="callout callout-warning">{{Session::get('alert')}}</div>
		@endif

		<form method="post" action="">
			@csrf
			<div class="col-md-3">
				<input type="text" name="referencia" id="referencia" autofocus  required="" class="form-control" value=""  placeholder="referencia" />
			</div>        
			<div class="col-md-2">
				<input type="submit" value="Pesquisa" class="btn btn-flat btn-primary" onclick="PlaySound()">
			</div>
		</form>

	</div>
</div>

<br>

<div class="row">
	<div class="col-md-4">

		@if (isset($item))
			<div class="table-responsive">
					<!-- FOTO -->
					<img class='img-responsive' src="https://portal.goeyewear.com.br/teste999.php?referencia={{$item[0]->secundario}}" alt='Photo' width="250"> 
					{{$item[0]->secundario}}


					<table class="table table-hover">
						<tr>
							<th>Situacao_Peca</th>
							<td>
							@if($item[0]->Situacao_Peca==' D E V O L V E R ')
								<span class="bg-red">{{$item[0]->Situacao_Peca}}</span> 
								@else 
								<span class="text-green">{{$item[0]->Situacao_Peca}}</span>
							@endif
							</td>
						</tr>
					</table>
			</div>
		@endif

	</div>

	<div class="col-md-7">

		@php
			$id_usuario = \Auth::id();
			
			$listas = \DB::select("select id_lista, count(*) as itens, max(created_at) as data
											from devolucoes
											where id_usuario = $id_usuario and status = 1
											group by id_lista
											order by id_lista desc
											limit 5");

		@endphp

		<h3>Listas de devolucao</h3>
		<div class="box box-body">
			<table class="table table-condensed table-bordered">

				<tr>
					<th>status</th>
					<th>lista</th>
					<th>itens</th>
					<th>data</th>
					<th>excel</th>
				</tr>


				@foreach ($listas as $lista)

					<tr>
						<td>Em Aberto</td>
						<td>{{$lista->id_lista}}</td>
						<td>{{$lista->itens}}</td>
						<td>{{$lista->data}}</td>
						<td>excel</td>
						<td align="center"><a href=""> Finalizar </a></td>
						<td align="center"><a href="" class="text-red"> Cancelar </a></td>
					</tr>

				@endforeach 

			</table>
		</div>


		@php
			$id_usuario = \Auth::id();
			
			$pecas_devolucao = \DB::select("select *
											from devolucoes
											where id_usuario = $id_usuario and situacao = 'DEVOLVER' and status = 1
											order by created_at desc
											limit 5");

		@endphp

		<h3>Relacao de pecas para devolucao   //   total</h3>
		<div class="box box-body">
			<table class="table table-condensed table-bordered">

				<tr>
					<th>edit</th>
					<th>peca</th>
					<th>data</th>
					<th>d</th>
					<th>e</th>
				</tr>

				
				@foreach ($pecas_devolucao as $peca)
					<tr>
						<th>edit</th>
						<th>{{$peca->secundario}}</th>
						<th>{{$peca->created_at}}</th>
						<th>d</th>
						<td align="center"><a href="" class="text-red"> Excluir </a></td>
					</tr>

				@endforeach 
			</table>
		</div>

	</div>
</div>
@else


<div class="col-md-12" align="center" style="margin-top: 150px;">

	<h2>Nenhum lista de devolução gerada</h2><br><br>

	<a href="/mostruarios/devolucoes/nova" class="btn btn-flat btn-success btn-lg"><i class="fa fa-plus"></i> Nova lista de devolução</a>

</div>


@endif




</h6>

@stop