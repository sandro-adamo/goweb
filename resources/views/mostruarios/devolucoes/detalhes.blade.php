@extends('layout.principal')

@section('title')
  <i class="fa fa-gears"></i> Devolução de Mostruários
@append 

@section('conteudo')

<form class="form-horizontal">
<div class="row">
	<div class="col-md-9">

		<div class="box box-widget">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-edit"></i> Dados da devolução</h3>
			</div>
			<div class="box-body">

			</div>

		</div>

		<div class="box box-widget">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-list"></i> Itens</h3>
			</div>
			<div class="box-body">
				<table class="table table-bordered">
				@foreach ($itens as $item)
					<tr>
						<td>{{$item->produto}}</td>
					</tr>
				@endforeach
				</table>
			</div>

		</div>

	</div>

	<div class="col-md-3">


		<div class="box box-widget">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-gears"></i> Controle</h3>
			</div>
			<div class="box-body">

				<div class="form-group">
					<label class="col-md-3 control-label">ID</label>
					<div class="col-md-8">
						<input type="text" name="id_devolucao" class="form-control" readonly="" value="{{$devolucao->id}}">
					</div>
				</div>
			</div>

		</div>



		<div class="box box-widget">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-file-o"></i> Anexos</h3>
			</div>
			<div class="box-body">

			</div>

		</div>

	</div>
</div>
</form>

@stop