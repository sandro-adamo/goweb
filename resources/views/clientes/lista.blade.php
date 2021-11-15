@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i> Clientes
@append 

@section('conteudo')

<form action="" method="get">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-widget box-body">
				<div class="row">
					<div class="col-xs-9 col-md-6">
						<input type="text" name="busca" autofocus="" class="form-control" placeholder="Buscar clientes">
					</div>
					<div class="col-xs-3 col-md-2">
						<button class="btn btn-flat btn-default btn-block"><i class="fa fa-search"></i> <span class="hidden-xs">Pesquisar</span></button>
					</div>

					<div class="col-xs-3 col-md-4 pull-right">
						<a href="/clientes/novo" class="btn btn-flat btn-primary pull-right"><i class="fa fa-plus"></i> <span class="hidden-xs">Novo Cadastro</span></a>
					</div>
				</div>      
				<br>


				<div class="table-responsive">

					<table class="table table-bordered table-hover">
						<thead>
							<tr>
								<th width="5%">Código</th>
								<th width="3%">fin</th>
								<th width="30%">Razão</th>			 
								<th width="10%">CNPJ</th>      
								<th width="20%">Grupo</th>
								<th width="15%">Município</th>
								<th width="3%">UF</th>
							</tr>
						</thead>
						<tbody>

							@if ($clientes && count($clientes) > 0)

								@foreach ($clientes as $pessoa)

									@php

									if ($pessoa->cliente <> ''){
									$string_cliente = str_replace('/', '_subst_', $pessoa->cliente);
									} else {

									$string_cliente = '';
									}



									@endphp


									<tr>
										<td align="center"><a href="/clientes/pdv/{{$pessoa->id}}">{{$pessoa->id}}</a></td>
										<td align="center">{{$pessoa->financeiro}}</td>
										<td>{{$pessoa->razao}}</td>
										<td align="center">{{$pessoa->cnpj}}</td>
										<td align="center"><a href="/det_subgrupo?pdv={{$pessoa->cod_cliente}}">{{$pessoa->cliente}}</a></td>
										<td align="center">{{$pessoa->municipio}}</td>
										<td align="center">{{$pessoa->uf}}</td>

									</tr>
								@endforeach 

							@else 
								<tr>
									<td colspan="9" align="center">Nenhum registro encontrato.</td>  
								</tr>
							@endif
						</tbody>
					</table>

					{{-- {{ $clientes->links() }} --}}
				</div>
			</div>
		</div>
	</div>
</form>
@stop