@extends('layout.principal')

@section('title')
  <i class="fa fa-gears"></i> Devolução de Mostruários
@append 

@section('conteudo')

@php
$grifes = Session::get('grifes');

$id_usuario = \Auth::id();

// $status_lista = \DB::select("select status_lista
// 								from devolucoes
// 								where id_usuario = $id_usuario and status_lista <> 'Concluída'
// 								group by status_lista
// 								limit 1");

//echo 'grifes'.$grifes.'</br>';
@endphp

@if (Session::has('alert-warning'))
	<div class="callout callout-warning">{{Session::get('alert-warning')}}</div>
@endif


	@if  (isset($devolucao) && ($devolucao->status == 'Iniciada' or $devolucao->id_devolucao == '') )

	<div class="box box-body box-widget">

			@if (Session::has('alert'))
			<div class="callout callout-warning">{{Session::get('alert')}}</div>
			@endif

			<form method="post" action="/mostruarios/devolucoes/confere" class="confereMostruario" id="frmConfereMostruario">
				@csrf
				<div class="col-md-3">
					<input type="text" name="referencia" id="referencia" autofocus  required="" class="form-control input-lg" value=""  placeholder="Referência" />
				</div>        
				<div class="col-md-2">
					<input type="submit" value="Pesquisa" class="btn btn-lg btn-default">
				</div>
				<div class="col-md-7" align="right">

					@if (isset($itens) && count($itens) > 0)
					
						<a href=""  data-toggle="modal" data-target="#modalConfirmaDev" class="btn btn-lg btn-success">Finalizar Devolução</a>

					@endif

				</div>

			</form>

	</div>
	@else
	<div class="box box-body box-widget">

		<div class="row">
			<div class="col-md-2 text-bold ">Situação: <span class="text-green">{{$devolucao->situacao}}</span></div>
			<div class="col-md-3"></div>

		</div>
		<div class="row">
			<div class="col-md-6"><b>Status:</b> {{$devolucao->status}}</div>
			<div class="col-md-3"></div>

		</div>

	</div>
	@endif 


	<div class="row">


		<div class="col-md-4">
		@if (isset($itens) && count($itens) > 0 && ($devolucao->status == 'Iniciada' or $devolucao->id_devolucao == ''))

			<div class="box box-widget" align="center"> 
					@if(isset($itens[0]->Situacao_Peca) && $itens[0]->Situacao_Peca=='DEVOLVER')
						<div class="box-header with-border bg-red text-bold" align="center">
							<span style="font-size: 24px; text-align: center;"><i class="fa fa-close"></i> <span class="situacaoPeca">{{$itens[0]->Situacao_Peca}}</span></span> 
						</div>
					@else 
						<div class="box-header with-border bg-green text-bold" align="center">
							<span style="font-size: 24px; text-align: center;"><i class="fa fa-check"></i> <span class="situacaoPeca">{{$itens[0]->Situacao_Peca}}</span></span> 
						</div> 
					@endif

					@if (isset($itens[0]->Situacao_Peca) && isset($itens) && count($itens) > 0)
						<!-- FOTO -->
						<img class='img-responsive' src="https://portal.goeyewear.com.br/teste999.php?referencia={{$itens[0]->secundario}}" alt='Photo' style="max-height: 280px;" class="img-responsive" > 
						<span style="font-size: 24px;"> {{$itens[0]->secundario}}</span>


					<div class="box-footer"> 
						@if($itens[0]->Situacao_Peca <> 'DEVOLVER')
							<a class="btn btn-danger btn-block" data-toggle="modal" data-target="#modalDevManual"> Devolver mesmo assim</a>
						@endif
					</div>
					@endif
			</div>

		@endif

		@if (isset($devolucao) and ($devolucao->status == 'Iniciada' or $devolucao->id_devolucao == ''))

			@php
				$total_resumo = 0;
			@endphp


			<div class="box box-widget">
				<div class="box-header with-border">
					<h3 class="box-title"><i class="fa fa-list"></i> Resumo da Devolução</h3>
				</div>
				<div class="box-body">
					<table class="table table-condensed table-bordered">

						<tr>
							<th>Agrupamento</th>
							<th>Quantidade</th>
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




		@endif
		</div>


		<div class="col-md-8">



			@php

				$linha = 0;

			@endphp


			@if (isset($itens) && count($itens) > 0)

			<div class="box box-widget">

				<div class="box-header with-border">
					<h3 class="box-title"><i class="fa fa-list"></i> Peças para devolução</h3>

					<span class="pull-right"><a href="/mostruarios/devolucoes/excel" class=""><i class="fa fa-file-o"></i> Exportar Excel</a>
				</div>
				<div class="box-body">


					<table class="table table-condensed table-bordered" id="example1">
					<thead>
						<tr>
							<th width="1%"></th>
							<th>Situação</th>
							<th>Peça</th>
							<th>Motivo</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						
						@foreach ($itens as $peca)

							@if ($peca->acao == 'DEVOLVER')
							@php
								$linha++;
							@endphp
							<tr>
								<td width="1%" align="center">{{$linha}}</td>
								<td width="25%" align="center">@if ($peca->situacao == 'DEVOLVER') <span class="text-red text-bold">{{$peca->situacao}}</span> @else <span class="text-green text-bold">{{$peca->situacao}}</span> @endif</td>
								<td width="25%">{{$peca->secundario}}</td>
								<td width="25%" align="center">{{$peca->obs}}</td>
								<td width="25%" align="center">

									@if (isset($devolucao) && ($devolucao->status == 'Iniciada' or $devolucao->id_devolucao == '')) 
										<a href="/mostruarios/devolucoes/{{$peca->id}}/excluir" class="text-red"> <i class="fa fa-trash"></i> Excluir </a>
									@endif
									
								</td>
							</tr>
							@endif 

						@endforeach 
					</tbody>
					</table>
				</div>
			</div>

			@endif

		</div>
	</div>



<form action="/mostruarios/devolucoes/enviar" method="post" class="form-horizontal">

@csrf
<div class="modal fade" id="modalConfirmaDev" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-upload"></i> Enviar Devolução</h4>
      </div>
      <div class="modal-body">

{{--       	<div class="callout callout-warning">
      		<span class="lead"> Após o envio da lista de devolução, não será possível o envio e retirada de itens da devolução!</span>
      	</div> --}}

      	<div class="form-group">
      		<label class="col-md-8 control-label lead">Informe quantos volumes (caixas) ?</label>
      		<div class="col-md-2">
      			<input type="number" required="" name="volumes" value="" class="form-control" max="10">
      		</div>
      	</div>
		@php
			$total_resumo = 0;
		@endphp


		<table class="table table-condensed table-bordered">

			<tr> 
				<th colspan="2" class="bg-info" style="text-align: center;">
					Resumo da Devolução
				</th>
			</tr>
			<tr>
				<th>Agrupamento</th>
				<th>Quantidade</th>
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

      	<span><input type="checkbox" name="confirmo" value="1" required> Declaro ter conferido as peças listadas bem como as quantidades acima.</span><br>

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Enviar Devolução</button>
      </div>
    </div>
  </div>
</div>
</form>




<form action="/mostruarios/devolucoes/confere" method="post" class="form-horizontal">
	@csrf

<div class="modal fade" id="modalDevManual" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Devolução Manual</h4>
      </div>
      <div class="modal-body">

      	<div class="form-group">

      		<label class="col-md-2 control-label">Item</label>
      		<div class="col-md-4">

      			@if (isset($itens[0]->secundario))
      		<input type="hidden" name="devolver" value="1">
      		<input type="text" name="referencia" value="{{$itens[0]->secundario}}" class="form-control" readonly="">
      			@endif
	      	</div>	
	    </div>

      	<div class="form-group">

      		<label class="col-md-2 control-label">Motivo</label>
      		<div class="col-md-4">

      			<select name="motivo" class="form-control">

      				<option value="">-- Selecione --</option>

      				<option>Não desejo</option>
      				<option>Duplicidade</option>
      				<option>Não vendo a grife</option>
      				<option>Problema técnico</option>

      			</select>

      		</div>

      	</div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Voltar</button>
        <button type="submit" class="btn btn-danger">Devolver</button>
      </div>
    </div>
  </div>
</div>
</form>
{{-- <embed src="/sounds/beep.wav" autostart="false" width="0" height="0" id="beep" enablejavascript="true">
 --}}
@stop