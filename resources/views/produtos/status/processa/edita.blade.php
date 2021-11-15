@extends('layout.principal')

@section('title')
  <i class="fa fa-edit"></i> Editar Processamento
@append 

@section('conteudo')

<div class="box box-widget">

	<div class="box-header with-border">
		<form action="">
			<div class="row">
				<div class="col-md-3">
					<small>Processamento</small>

				</div>
				<div class="col-md-5">
					<small>Agrupamento</small>
					<input type="text" class="form-control" name="agrup">
				</div>
				<div class="col-md-4">
					<small>Status</small>
					<input type="text" class="form-control" name="status">
				</div>
			</div>
		</form>
	</div>
	<div class="box-body">

		<table class="table table-striped table-bordered table-condensed compact" id="myTadble"> 		
		<thead>

			<tr>
				<td rowspan="2">Modelo</td>
				<td rowspan="1">Modelo</td>
				<td rowspan="1">Item</td>
				<td colspan="2">Classificação</td>
				<td colspan="2">Coleção</td>
				<td rowspan="2">Disp.</td>
				<td rowspan="2">Orç.</td>
				<td rowspan="2">Most</td>
				<td rowspan="2">Status3</td>
				<td colspan="6">Potencial</td>
				<td colspan="2">CAT</td>
				<td rowspan="1"></td>
			</tr>

			<tr>
				
				<td>Modelo</td>
				<td>Item</td>
				<td>Modelo</td>
				<td>Item</td>
				<td>Clas</td>
				<td>Clas</td>
				<td>CLass</td>
				<td>Most</td>
				<td>Disp</td>
				<td>Meta</td>
				<td>Pot1</td>
				<td>Pot3</td>
				<td>Qtd prod</td>
				<td>Data Prod</td>
				<td>Ação</td>
			</tr>						
		</thead>
		<tbody>	

			@foreach ($itens as $item)
			<tr>
				<td id="foto" align="center" style="min-height:60px;">
               
                <a href="" class="zoom" data-value="{{$item->secundario}}"><img src="https://portal.goeyewear.com.br/teste999.php?referencia={{$item->secundario}}" style="max-height: 60px;" class="img-responsive"></a>
                
              </td>
				<td align="center" width="10%"><?=$item->modelo?></td>
				<td align="center" width="10%">{{$item->secundario}}</td>
				<td align="center" width="8%"><?=$item->clas_m3?></td>

				<td align="center" width="8%"><?=$item->clas_i3?></td>
				<td align="center" width="8%"><?=$item->col_m?></td>
				<td align="center" width="8%"><?=$item->col_i?></td>
				<td align="center" width="5%"><?=number_format($item->saldo_disp,0)?></td>
				<td align="center" width="5%"><?=number_format($item->orc,0)?></td>
				<td align="center" width="5%"><?=number_format($item->qtde_most,0)?></td>
				<td align="center" width="5%"><?=$item->status3?></td>
				<td align="center" width="5%"><?=$item->pot_clas?></td>

				<td align="center" width="5%"><?=number_format($item->pot_most,0)?></td>
				<td align="center" width="5%"><?=number_format($item->pot_disp,0)?></td>
				<td align="center" width="5%"><?=number_format($item->pot_meta,0)?></td>
				<td align="center" width="5%"><?=number_format($item->pot1,0)?></td>
				<td align="center" width="5%"><?=number_format($item->pot3,0)?></td>
				
				<td align="center" width="5%"><?=number_format($item->qtd_prod,0)?></td>
				<td align="center" width="5%">{{$item->dt_confirmacao,0}}</td>
			
				<td align="center">
					<a href="/produtos/status/editastatus3?secundario={{$item->secundario}}&processamento={{$item->processamento}}&status3=DISPONIVEL">D</a>
					
					<a href="/produtos/status/editastatus3?secundario={{$item->secundario}}&processamento={{$item->processamento}}&status3=AGUARDAR IMPORTACAO 15 DIAS">15</a>
				
				<a href="/produtos/status/editastatus3?secundario={{$item->secundario}}&processamento={{$item->processamento}}&status3=AGUARDAR IMPORTACAO 30 DIAS&saldo30={{$item->saldo_30dias+$item->qtd_prod}}">30</a>
				
				<a href="/produtos/status/editastatus3?secundario={{$item->secundario}}&processamento={{$item->processamento}}&status3=AGUARDAR PRODUCAO">P</a>
				
				<a href="/produtos/status/editastatus3?secundario={{$item->secundario}}&processamento={{$item->processamento}}&status3=ESGOTADO">E</a></td>
				
					
				</td>
				
			</tr>			


			@endforeach

		</tbody>
		</table>
	</div>
</div>
@stop