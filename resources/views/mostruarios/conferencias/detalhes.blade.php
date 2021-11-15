@extends('layout.principal')

@section('title')
  <i class="fa fa-gears"></i> Nova Devolução de Mostruários
@append 

@section('conteudo')

@php
//$grifes = Session::get('grifes');
//echo 'grifes'.$grifes.'</br>';
@endphp


<div class="box box-body box-widget">
	<div class="col-xs-12">

		@if (Session::has('alert'))
		<div class="callout callout-warning">{{Session::get('alert')}}</div>
		@endif

		<form method="post" action="/mostruarios/conferencias/confere">
			@csrf
			<input type="hidden" name="id_lista" value="{{$id_lista}}">
			<div class="col-md-3">
				<input type="text" name="referencia" id="referencia" autofocus  required="" class="form-control" value=""  placeholder="referencia" />
			</div>        
			<div class="col-md-2">
				<input type="submit" value="Pesquisa" class="btn btn-flat btn-primary" onclick="PlaySound()">
			</div>
			<div class="col-md-7" align="right">

				@php
					$id_usuario = \Auth::id();
					
					$status_lista = \DB::select("select status_lista
													from conferencias
													where id_usuario = $id_usuario  and id_lista = '$id_lista'
													group by status_lista
													limit 1");

				@endphp

				@if ($status_lista)
					@if (isset($status_lista[0]->status_lista) && $status_lista[0]->status_lista == 'Em Aberto')
						<a href="/mostruarios/conferencias/{{$id_lista}}/finalizar" class="btn btn-flat btn-success">Finalizar Devolução</a>
					@elseif  (isset($status_lista[0]->status_lista) && $status_lista[0]->status_lista == 'Aguardando Conferência')
						<a href="" class="btn btn-flat btn-warning" data-toggle="modal" data-target="#modalConfirmaDev">Enviar Lista de Devolução</a>
					@else

					@endif
				@endif
			</div>

		</form>

	</div>
</div>

<br>

<div class="row">
	<div class="col-md-4">

		@if (isset($itens) && count($itens) > 0)
			<div class="table-responsive" align="center">
					<!-- FOTO -->
					<img class='img-responsive' src="https://portal.goeyewear.com.br/teste999.php?referencia={{$itens[0]->secundario}}" alt='Photo' width="250"> 
					<span style="font-size: 24px;"> {{$itens[0]->secundario}}</span>


					<table class="table table-hover">
						<tr>
							<td align="center">
							@if($itens[0]->Situacao_Peca=='DEVOLVER')
								<span style="font-size: 24px;" class="bg-red">{{$itens[0]->Situacao_Peca}}</span> 
							@else 
								<span style="font-size: 24px;" class="bg-green">{{$itens[0]->Situacao_Peca}}</span> 
							@endif
							</td>
						</tr>
					</table>
			</div>
		@endif

	</div>

	<div class="col-md-8">

		@php
			$id_usuario = \Auth::id();
			
			$resumo = \DB::select("select agrup, count(*) as qtde
                                 from conferencias
                                 left join itens on id_item = itens.id
                                 where id_usuario = $id_usuario and id_lista = $id_lista and status = 1 -- and situacao = 'DEVOLVER'
                                 group by agrup");
			$total_resumo = 0;
		@endphp


		<div class="box box-widget">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-list"></i> Resumo da Devolução</h3>
			</div>
			<div class="box-body">
				<table class="table table-condensed table-bordered">

					<tr>
						<th>agrupamento</th>
						<th>qtde</th>
					</tr>


					@foreach ($resumo as $lista)
						@php

							$total_resumo += $lista->qtde;
						@endphp

						<tr>
							<td>{{$lista->agrup}}</td>
							<td align="center">{{$lista->qtde}}</td>
						</tr>

					@endforeach 

						<tr>
							<th style="text-align: right;">TOTAL</th>
							<th style="text-align: center;">{{$total_resumo}}</th>
						</tr>
				</table>
			</div>
		</div>


		@php
			$id_usuario = \Auth::id();
			
			$pecas_devolucao = \DB::select("select *
											from conferencias
											where id_usuario = $id_usuario  and status = 1 and id_lista = '$id_lista'
											order by created_at desc
											limit 5");

		@endphp


		@if (isset($pecas_devolucao) && count($pecas_devolucao) > 0)

		<div class="box box-widget">

			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-list"></i> Relacão de pecas para devolução</h3>
			</div>
			<div class="box-body">
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
							<td align="center"><a href="/mostruarios/conferencias/{{$id_lista}}/{{$peca->id}}/excluir" class="text-red"> Excluir </a></td>
						</tr>

					@endforeach 
				</table>
			</div>
		</div>

		@endif

	</div>
</div>


<div class="modal fade" id="modalConfirmaDev" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Confirma Devolução</h4>
      </div>
      <div class="modal-body">

		@php
			$id_usuario = \Auth::id();
			
			$resumo = \DB::select("select agrup, count(*) as qtde
                                 from conferencias
                                 left join itens on id_item = itens.id
                                 where id_usuario = $id_usuario and id_lista = $id_lista and status = 1 and situacao = 'DEVOLVER'
                                 group by agrup");
			$total_resumo = 0;
		@endphp


		<div class="box box-widget">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-list"></i> Resumo da Devolução</h3>
			</div>
			<div class="box-body">
				<table class="table table-condensed table-bordered">

					<tr>
						<th>agrupamento</th>
						<th>qtde</th>
					</tr>


					@foreach ($resumo as $lista)
						@php

							$total_resumo += $lista->qtde;
						@endphp

						<tr>
							<td>{{$lista->agrup}}</td>
							<td align="center">{{$lista->qtde}}</td>
						</tr>

					@endforeach 

						<tr>
							<th style="text-align: right;">TOTAL</th>
							<th style="text-align: center;">{{$total_resumo}}</th>
						</tr>
				</table>
			</div>
		</div>
      	<span class="lead">Declaro ter conferido as peças listadas bem como as quantidades acima.</span><br>

      	<a href="/mostruarios/conferencias/{{$id_lista}}/excel"> Conferir lista analítica</a>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Mais tarde</button>
        <button type="submit" class="btn btn-success" data-toggle="modal" data-target="#modalConfirmaEnvio">Sim</button>
      </div>
    </div>
  </div>
</div>


<form action="/mostruarios/conferencias/{{$id_lista}}/confirmar" method="post">
	@csrf

<div class="modal fade" id="modalConfirmaEnvio" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Confirma Envio</h4>
      </div>
      <div class="modal-body">

      	<span class="lead">Confirma envio?</span>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Voltar</button>
        <button type="submit" class="btn btn-success">Sim</button>
      </div>
    </div>
  </div>
</div>
</form>

@stop