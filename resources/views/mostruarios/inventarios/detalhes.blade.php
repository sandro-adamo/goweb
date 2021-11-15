@extends('layout.principal')

@section('title')
  <i class="fa fa-gears"></i> Novo 
  			@if ($acao=='Recebendo'){{'Recebimento'}}@endif
            @if ($acao=='Enviando'){{'Envio'}}@endif
            @if ($acao=='inventario'){{'Inventário'}}@endif
  <h5><div class="text-red text-bold">Apenas finalize depois de ler TODAS as peças que estão com a empresa de representação.</div></h5>
  


@append 

@section('conteudo')

@php

$id_rep = \Auth::user()->id_addressbook;
$grifes = Session::get('grifes');
if ($itens && count($itens) > 0) {
	$id_inventario = $itens[0]->id_inventario;
	$status = $itens[0]->status;
	echo $status;
} else {
	$id_inventario = '';
	$status = '';
}



if (Session::has('alert-danger') ) {
	$erros = Session::get('alert-danger');
	echo '<div class="callout callout-danger"><ul>';
	foreach ($erros as $erro) {
		echo '<li>'.$erro.'</li>';
	}
	echo '</div>';

}



if (Session::has('alert-success') ) {
	$erros = Session::get('alert-success');
	echo '<div class="callout callout-success"><ul>';
	foreach ($erros as $erro) {
		echo '<li>'.$erro.'</li>';
	}
	echo '</div>';

}


@endphp

@if (Session::has('alert-warning'))

    <div class="callout callout-warning"><h3><i class="fa fa-warning"></i> Erro {!!Session::get('alert-warning')!!}</div></h3>

@endif


	<div class="row">


		<div class="col-md-4">



@if (isset($_GET["duplicado"]))
	
	<div class="callout callout-warning">
		<h3>Este item já foi inserido, deseja inserir novamente?</h3>


		<br>

		<a href="/mostruarios/inventarios/confere/{{$acao}}?referencia={{$_GET["referencia"]}}&duplica=1" class="btn btn-success"><i class="fa fa-check"></i> Sim</a>
		<a href="/mostruarios/inventarios/detalhes/{{$acao}}/{{$id_inventario}}" class="btn btn-danger"><i class="fa fa-close"></i> Não</a>
	</div>

@else 
<div class="box box-body box-widget">


	<form method="post" action="/mostruarios/inventarios/confere/{{$acao}}" class="confereMostruario" id="frmConfereMostruario">
		@csrf
		<div class="col-md-9">
			@if (count($itens) == 0 or (isset($itens[0]->status) && $itens[0]->status=="Iniciada"))

			<input type="text" name="referencia" id="referencia" autofocus  required="" class="form-control input-lg" value=""  placeholder="ex: AH6254 A01" />

			@if (Session::has('alert-warning'))
				<span class="text-red text-bold">{{Session::get('alert-warning')}}</span>
			@endif
		</div>        
		<div class="col-md-3">
			
			<button type="submit" class="btn btn-lg btn-default"><i class="fa fa-search"></i></button>
			@endif
		</div>
{{-- 		<div class="col-md-7">

			@if (isset($itens) && count($itens) > 0)

			<a href=""  data-toggle="modal" data-target="#modalConfirmaDev" class="btn btn-lg btn-primary">Finalizar Devolução</a>

			@endif

		</div> --}}

	</form>

</div>
@endif

		@if(isset($itens[0]->status) && $itens[0]->status=="Iniciada")

			<div class="box box-widget" align="center"> 
				
					@if(isset($itens[0]->situacao) && $itens[0]->situacao=='DEVOLVER')
						<div class="box-header with-border bg-red text-bold" align="center">
							<span style="font-size: 24px; text-align: center;"><i class="fa fa-close"></i> <span class="situacaoPeca">{{$itens[0]->situacao}}</span></span> 
						</div>
					@else 
						<div class="box-header with-border bg-green text-bold" align="center">
							<span style="font-size: 24px; text-align: center;"><i class="fa fa-check"></i> <span class="situacaoPeca">{{$itens[0]->situacao}}</span></span> 
						</div> 
					@endif


					@if (isset($itens[0]->situacao) && isset($itens) && count($itens) > 0)
						<!-- FOTO -->
						<img class='img-responsive' src="https://portal.goeyewear.com.br/teste999.php?referencia={{$itens[0]->item}}" alt='Photo' style="max-height: 280px;" class="img-responsive" > 
						<span style="font-size: 24px;"> {{$itens[0]->item}}</span>


					<div class="box-footer"> 
						@if($itens[0]->situacao <> 'DEVOLVER')
							

							<span ><a href="/mostruarios/inventarios/altera/{{$itens[0]->id}}/{{$itens[0]->id_inventario}}/DEVOLVER" class="box-header with-border bg-red text-bold"><i ></i> Devolver mesmo assim</a></span>
						@else
							<span ><a href="/mostruarios/inventarios/altera/{{$itens[0]->id}}/{{$itens[0]->id_inventario}}/MANTER" class="box-header with-border bg-green text-bold"><i ></i> Manter mesmo assim</a></span>
						@endif
						@endif

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

					<h3 class="box-title"><i class="fa fa-list"></i> Inventário</h3>
					@if (isset($itens[0]->status) && $itens[0]->status=="Iniciada")
					
					<button type="button" class="btn btn-xs btn-default pull-right btn-flat " id="btnImporta"><i class="fa fa-upload"></i> Import Inventário</button>
					
					<button type="button" class="btn btn-xs btn-danger pull-right btn-flat " id="btnImportaDevolucao" data-toggle="modal" data-target="#modalImportaDevolucao"><i class="fa fa-upload"></i> Import Devolução</button>
					
					<button type="button" class="btn btn-xs btn-success pull-right btn-flat " id="btnImportaManter" data-toggle="modal" data-target="#modalImportaManter"><i class="fa fa-upload"></i> Import Manter</button>

					</div>
					@endif

					<div class="box-header with-border">

					<span class="pull-right"><a href="/mostruarios/inventarios/{{$id_inventario}}/exportainventario" class="btn btn-xs btn-default"><i class="fa fa-file-o"></i> Exportar Inventário</a>

					<span class="pull-right"><a href="/mostruarios/inventarios/{{$id_inventario}}/exportadevolucao" class="btn btn-xs btn-danger"><i class="fa fa-file-o"></i> Exportar Devolução</a>


				</div>

				<div class="box-body">



					<table class="table table-condensed table-bordered" id="example1">
					<thead>
						<tr>
							<th width="1%"></th>
							<th>Situação</th>
							<th>Peça</th>
							<th>Motivo</th>
							<th>Status Atual</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						
						@foreach ($itens as $peca)

							@php
								$linha++;
							@endphp
							<tr>
								<td width="1%" align="center">{{$linha}}</td>
								<td width="25%" align="center">@if ($peca->acao == 'DEVOLVER')
									
								  <span class="pull-center"><a href="/mostruarios/inventarios/altera/{{$peca->id}}/{{$peca->id_inventario}}/MANTER" class="text-red text-bold"><i ></i> {{$peca->acao}} </a>
								
								 @else
								 @if (isset($itens[0]->status) && $itens[0]->status=="Iniciada")

								  

								

								  <span class="pull-center"><a href="/mostruarios/inventarios/altera/{{$peca->id}}/{{$peca->id_inventario}}/DEVOLVER" class="text-green text-bold"><i ></i> {{$peca->acao}} </a>

								  @else
								 <span class="pull-center"><a href="/mostruarios/inventarios/altera/{{$peca->id}}/{{$peca->id_inventario}}/MANTER" class="text-green text-bold"><i ></i> {{$peca->acao}} </a>

								   @endif</td>@endif
								<td width="25%">{{$peca->item}}</td>
								<td width="25%" align="center">{{$peca->motivo}}</td>
								<td width="25%" align="center">{{$peca->statusatual}}</td>
								<td width="25%" align="center">
									 @if (isset($itens[0]->status) && $itens[0]->status=="Iniciada")
									<a href="/mostruarios/inventarios/{{$peca->id}}/excluir/{{$acao}}" class="text-red"> <i class="fa fa-trash"></i> Excluir </a>	
									@endif								
								</td>
							</tr>

						@endforeach 
					</tbody>
					</table>
				</div>
			</div>

			@endif

		</div>
	</div>
	<div class="row">
		<div class="col-md-12">


			<div class="row">
				<div class="col-md-12">
					<div class="box box-body box-widget">
						<table class="table table-bordered table-condensed">
						<tr>
							<th>Agrupamento</th>
							<th>Manter2</th>
							<th>Devolver</th>
							<th>Total</th>
						</tr>			
						@foreach ($resumo as $linha)
							<tr>
								<td>{{$linha->agrup}}</td>
								<td align="center">{{$linha->manter}}</td>
								<td align="center">{{$linha->devolver}}</td>
								<td align="center">{{$linha->manter+$linha->devolver}}</td>
							</tr>
						@endforeach 
						</table>
						<div class="row">
							<div class="col-md-12">
								  
								@if (isset($itens[0]->status) && $itens[0]->status=="Iniciada")
								<button class="btn btn-success btn-flat pull-right" data-toggle="modal" data-target="#modalConfirmaDev">Finalizar Inventário</button>
								@endif
							</div>			
						</div>	
					</div>
				</div>
			</div> 

		</div>
	</div>

@if (isset($itens) && count($itens) > 0 )


<form action="/mostruarios/inventarios/altera" method="post" class="form-horizontal">
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
      		<input type="hidden" name="id_linha" value="{{$itens[0]->id}}">
      		<label class="col-md-2 control-label">Item</label>
      		<div class="col-md-4">

      			@if (isset($itens[0]->item))
      				<input type="hidden" name="devolver" value="1">
      				<input type="text" name="referencia" value="{{$itens[0]->item}}" class="form-control" readonly="">
      			@endif
	      	</div>	
	    </div>

      	<div class="form-group">

      		<label class="col-md-2 control-label">Motivo</label>
      		<div class="col-md-4">

      			<select name="motivo" class="form-control" required="">

      				<option value="">-- Selecione --</option>

      				<option>Não desejo</option>
      				<option>Duplicidade</option>
      				<option>Não vendo a grife</option>
      				<option>Problema técnico</option>
      				<option>Outros</option>

      			</select>

      		</div>

      	</div>


      	<div class="form-group">

      		<label class="col-md-2 control-label">Observações</label>
      		<div class="col-md-4">

      			<textarea name="obs" class="form-control"></textarea>

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




<form action="/mostruarios/inventarios/enviar" method="post" class="form-horizontal">
<input type="hidden" name="id_inventario" value="{{$id_inventario}}">
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
      			<input type="number" required="" name="volumes" value="" class="form-control" max="20">
      		</div>
      	</div>
		@php
			$total_resumo = 0;
			$total_manter = 0;
			$total_devolver = 0;
		@endphp


		<table class="table table-condensed table-bordered">

			<tr> 
				<th colspan="4" class="bg-info" style="text-align: center;">
					Resumo da Devolução
				</th>
			</tr>
			<tr>
				<th>Agrupamento</th>
				<th>Manter</th>
				<th>Devolver</th>
				<th>Total</th>
			</tr>


			@foreach ($resumo as $lista)
				@php

					$total_resumo += $lista->manter+$lista->devolver;
					$total_manter += $lista->manter;
					$total_devolver += $lista->devolver;
				@endphp

				<tr>
					<td>{{$lista->agrup}}</td>
					<td align="center">{{$lista->manter}}</td>
					<td align="center">{{$lista->devolver}}</td>
					<td align="center">{{$lista->manter+$lista->devolver}}</td>
				</tr>

			@endforeach 

				<tr>
					<th style="text-align: right;">TOTAL</th>
					<th style="text-align: center;">{{$total_manter}}</th>
					<th style="text-align: center;">{{$total_devolver}}</th>
					<th style="text-align: center;">{{$total_resumo}}</th>
				</tr>
		</table>

      	<span><input type="checkbox" name="confirmo" value="1" required> Declaro ter conferido as peças listadas bem como as quantidades acima.</span><br>
      	<span><input type="checkbox" name="confirmo" value="1" required> Estou de acordo em manter <b>{{$total_manter}}</b> peças no mostruário e devolverei <b>{{$total_devolver}}</b> peças para Kenerson. </span><br>
      	<span><input type="checkbox" name="confirmo" value="1" required> Declaro ter lido todas as peças que estão em posse de minha empresa de representação. </span><br>

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Enviar Inventário</button>
      </div>
    </div>
  </div>
</div>
</form>

<form action="/mostruarios/inventarios/importa/{{$acao}}" id="frmImporta" method="post" enctype="multipart/form-data">
    @csrf 
<div class="modal fade" id="modalImportaItens" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Importar Inventário </h4>
      </div>
      <div class="modal-body">
        
        <label><h4>Formato excel</h4> </br>Coluna 1 -Código secundário - Exemplo: AH6254 A01 </br>  Caso a empresa de representação tenha duas peças da mesma referência, ele deve estar em duas linhas diferentes.
    </label>
        <input type="file" name="arquivo" required="" id="arquivo" class="form-control">
        <input type="hidden" name="id_inventario" value="{{$id_inventario}}">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-flat btn-primary"><i class="fa fa-upload"></i> Importar</button>
      </div>
    </div>
  </div>
</div>
</form>







<form action="/mostruarios/inventarios/importaDevolucao/{{$acao}}" id="frmImportaDevolucao" method="post" enctype="multipart/form-data">
    @csrf 
<div class="modal fade" id="modalImportaDevolucao" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Importar Devolução </h4>
      </div>
      <div class="modal-body">
        
        <label><h4>Formato excel</h4> </br>Coluna 1 -Código secundário - Exemplo: AH6254 A01 </br>  Caso a empresa de representação tenha duas peças da mesma referência, ele deve estar em duas linhas diferentes.
    </label>
        <input type="file" name="arquivo" required="" id="arquivo" class="form-control">
        <input type="hidden" name="id_inventario" value="{{$id_inventario}}">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-flat btn-primary"><i class="fa fa-upload"></i> Importar</button>
      </div>
    </div>
  </div>
</div>
</form>

<form action="/mostruarios/inventarios/importaManter/{{$acao}}" id="frmImportaManter" method="post" enctype="multipart/form-data">
    @csrf 
<div class="modal fade" id="modalImportaManter" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Importar Manter </h4>
      </div>
      <div class="modal-body">
        
        <label><h4>Formato excel</h4> </br>Coluna 1 -Código secundário - Exemplo: AH6254 A01 </br>  Caso a empresa de representação tenha duas peças da mesma referência, ele deve estar em duas linhas diferentes.
    </label>
        <input type="file" name="arquivo" required="" id="arquivo" class="form-control">
        <input type="hidden" name="id_inventario" value="{{$id_inventario}}">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-flat btn-primary"><i class="fa fa-upload"></i> Importar</button>
      </div>
    </div>
  </div>
</div>
</form>

<form action="/mostruarios/inventarios/altera" id="frmAltera" method="post" class="form-horizontal">
    @csrf 
<div class="modal fade" id="modalAltera" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Altera Situação </h4>
      </div>
      <div class="modal-body">
   
      	<div class="form-group">
      		<input type="hidden" name="id_linha" id="id_linha">
      		<label class="col-md-2 control-label">Item {{$peca->id}}</label>
      		<div class="col-md-4">
  				<input type="hidden" name="devolver" value="1">
  				<input type="text" name="referencia" id="referencia"  class="form-control" readonly="" value = "">
	      	</div>	
	    </div>

      	<div class="form-group">

      		<label class="col-md-2 control-label">Motivo</label>
      		<div class="col-md-4">

      			<select name="motivo" class="form-control" required="">

      				<option value="">-- Selecione --</option>

      				<option>Não desejo</option>
      				<option>Duplicidade</option>
      				<option>Não vendo a grife</option>
      				<option>Problema técnico</option>
      				<option>Outros</option>

      			</select>

      		</div>

      	</div>


      	<div class="form-group">

      		<label class="col-md-2 control-label">Observações</label>
      		<div class="col-md-4">

      			<textarea name="obs" class="form-control"></textarea>

      		</div>

      	</div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-flat btn-primary"><i class="fa fa-save"></i> Salvar</button>
      </div>
    </div>
  </div>
</div>
</form>
@endif

@stop