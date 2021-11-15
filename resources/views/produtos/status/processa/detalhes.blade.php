@extends('layout.principal')

@section('title')
  <i class="fa fa-gears"></i> Processamento de Status
@append 

@section('conteudo')

<a href="/produtos/status/uploadarquivo" class="text-blue uploadArquivo" data-value=""> <i class="fa fa-upload"></i> Upload edita status</a>

<div class="box box-body box-widget">
	<table class="table table-bordered table-striped table-condensed">

		<thead>
			<tr>
				<th rowspan="2">Classificação</th>
				<th rowspan="2">Meta</th>
				<th colspan="6">Potêncial</th>
				<th colspan="6">Itens</th>
				<th rowspan="2">Novos BO</th>
			</tr>

			<tr>
				<th> Imed </th>
				<th> 15dd </th>
				<th> 30dd </th>
				<th> Tt Disp </th>
				<th> Prod </th>
				<th> Esgot </th>
				<th> Imed </th>
				<th> 15dd </th>
				<th> 30dd </th>
				<th> Tt Disp </th>
				<th> Prod </th>
				<th> Esgot </th>
			</tr>


			</tr>
		</thead>

		<tbody>		


		@php

			
			$total_meta = 0;

			$total_pd = 0;
			$total_p15 = 0;
			$total_p30 = 0;
			$total_pt = 0;
			$total_pp = 0;
			$total_pe = 0;

			$total_id = 0;
			$total_i15 = 0;
			$total_i30 = 0;
			$total_it = 0;
			$total_ip = 0;
			$total_ie = 0;

			$total_orc = 0;

		@endphp


		@foreach ($classificacao as $clas) 

			@php

				$total_meta += $clas->meta;

				$total_pd += $clas->meta;
				$total_p15 += $clas->P15;
				$total_p30 += $clas->P30;
				$total_pt += $clas->PT;
				$total_pp += $clas->PP;
				$total_pe += $clas->PE;

				$total_id += $clas->ID;
				$total_i15 += $clas->I15;
				$total_i30 += $clas->I30;
				$total_it += $clas->IT;
				$total_ip += $clas->IP;
				$total_ie += $clas->IE;

				$total_orc += $clas->NOVO_ORC;

			@endphp


			<tr>
				<td>{{$clas->clas_m3}}</td>
				<td align="right">{{number_format($clas->meta,0,'.','.')}}</td>

				<td align="right">{{number_format($clas->PD,0,'.','.')}}</td>
				<td align="right">{{number_format($clas->P15,0,'.','.')}}</td>
				<td align="right">{{number_format($clas->P30,0,'.','.')}}</td>
				<td align="right" class="text-bold">{{number_format($clas->PT,0,'.','.')}}</td>
				<td align="right">{{number_format($clas->PP,0,'.','.')}}</td>
				<td align="right">{{number_format($clas->PE,0,'.','.')}}</td>

				<td align="right">{{number_format($clas->ID,0,'.','.')}}</td>
				<td align="right">{{number_format($clas->I15,0,'.','.')}}</td>
				<td align="right">{{number_format($clas->I30,0,'.','.')}}</td>
				<td align="right" class="text-bold">{{number_format($clas->IT,0,'.','.')}}</td>
				<td align="right">{{number_format($clas->IP,0,'.','.')}}</td>
				<td align="right">{{number_format($clas->IE,0,'.','.')}}</td>

				<td align="right">{{number_format($clas->NOVO_ORC,0,'.','.')}}</td>
			</tr>		

		@endforeach
		</tbody>

		<tfoot>
			<tr>
				<th style="text-align: right !important;">TOTAIS</th>
				<th style="text-align: right !important;">{{number_format($total_meta,0,'.','.')}}</th>

				<th style="text-align: right !important;">{{number_format($total_pd,0,'.','.')}}</th>
				<th style="text-align: right !important;">{{number_format($total_p15,0,'.','.')}}</th>
				<th style="text-align: right !important;">{{number_format($total_p30,0,'.','.')}}</th>
				<th style="text-align: right !important;">{{number_format($total_pt,0,'.','.')}}</th>
				<th style="text-align: right !important;">{{number_format($total_pp,0,'.','.')}}</th>
				<th style="text-align: right !important;">{{number_format($total_pe,0,'.','.')}}</th>

				<th style="text-align: right !important;">{{number_format($total_id,0,'.','.')}}</th>
				<th style="text-align: right !important;">{{number_format($total_i15,0,'.','.')}}</th>
				<th style="text-align: right !important;">{{number_format($total_i30,0,'.','.')}}</th>
				<th style="text-align: right !important;">{{number_format($total_it,0,'.','.')}}</th>
				<th style="text-align: right !important;">{{number_format($total_ip,0,'.','.')}}</th>
				<th style="text-align: right !important;">{{number_format($total_ie,0,'.','.')}}</th>

				<th style="text-align: right !important;">{{number_format($total_orc,0,'.','.')}}</th>

			</tr>

		</tfoot>
</table>
</div>



<div class="box box-body box-widget">


		<table class="table table-striped table-bordered table-condensed"> 		
		<thead style="font-weight: bold; background: #23364A; color: #FFF;">
			
				<td rowspan="2">Agrupamento</td>
				<td rowspan="2">Meta</td>
			
				<td colspan="6">Potencial Linha A</td>
				<td rowspan="2">Orc semana</td>
				<td colspan="6">Itens</td>
				
			</tr>			
			<tr>
				<td>Imed</td>
				<td>15dd</td>
				<td>30dd</td>
				<td>Tt Disp</td>
				<td>Prod</td>
				<td>Esgot</td>
				
				
				<td>Imed</td>
				<td>15dd</td>
				<td>30dd</td>
				<td>Tt Disp</td>
				
				<td>Prod</td>
				<td>Esgot</td>
				
			</tr>						
		</thead>
		<tbody>	

		@php
			$total_meta = 0;

			$total_pd = 0;
			$total_p15 = 0;
			$total_p30 = 0;
			$total_pt = 0;
			$total_pp = 0;
			$total_pe = 0;

			$total_id = 0;
			$total_i15 = 0;
			$total_i30 = 0;
			$total_it = 0;
			$total_ip = 0;
			$total_ie = 0;

			$total_orc = 0;

		@endphp

		@foreach ($agrupamento as $agrup)

			@php

				$total_meta += $agrup->meta;

				$total_pd += $agrup->meta;
				$total_p15 += $agrup->P15;
				$total_p30 += $agrup->P30;
				$total_pt += $agrup->PT;
				$total_pp += $agrup->PP;
				$total_pe += $agrup->PE;

				$total_id += $agrup->ID;
				$total_i15 += $agrup->I15;
				$total_i30 += $agrup->I30;
				$total_it += $agrup->IT;
				$total_ip += $agrup->IP;
				$total_ie += $agrup->IE;

				$total_orc += $agrup->NOVO_ORC;


			@endphp

			<tr>
				
				<td align="left" width="20%"><a href=""><?=$agrup->agrup?></a></td>
				<td align="center" width="5%"><?=number_format($agrup->meta,0)?></td>

				<td align="center" width="5%"><?=number_format($agrup->PD,0)?></td>
				<td align="center" width="5%"><?=number_format($agrup->P15,0)?></td>
				<td align="center" width="5%"><?=number_format($agrup->P30,0)?></td>
				<td align="center" width="5%"><b><?=number_format($agrup->PT,0)?></b></td>
				<td align="center" width="5%"><?=number_format($agrup->PP,0)?></td>
				<td align="center" width="5%"><?=number_format($agrup->PE,0)?></td>
				
				<td align="center"><a href="principal.php?centro=_produtos/detalhe-status&agrup=<?=$agrup->agrup?>&tipo=PD"><?=number_format($agrup->NOVO_ORC,0)?></a></td>

				<td align="center" width="5%"><a href="/produtos/status/processamentos/{{$agrup->processamento}}/edita?agrup=<?=$agrup->agrup?>&status=DISPONIVEL"><?=number_format($agrup->ID,0)?></a></td>
				<td align="center" width="5%"><a href="/produtos/status/processamentos/{{$agrup->processamento}}/edita?agrup=<?=$agrup->agrup?>&status=AGUARDAR IMPORTACAO 15 DIAS"><?=number_format($agrup->I15,0)?></a></td>
				<td align="center" width="5%"><a href="/produtos/status/processamentos/{{$agrup->processamento}}/edita?agrup=<?=$agrup->agrup?>&status=AGUARDAR IMPORTACAO 30 DIAS"><?=number_format($agrup->I30,0)?></a></td>
				<td align="center" width="5%"><B><?=number_format($agrup->IT,0)?></B></td>
				<td align="center" width="5%"><a href="/produtos/status/processamentos/{{$agrup->processamento}}/edita?agrup=<?=$agrup->agrup?>&status=AGUARDAR PRODUCAO"><?=number_format($agrup->IP,0)?></a></td>
				<td align="center" width="5%"><a href="/produtos/status/processamentos/{{$agrup->processamento}}/edita?agrup=<?=$agrup->agrup?>&status=ESGOTADO"><?=number_format($agrup->IE,0)?></a></td>
				
				
				
			
				
				

				
			</tr>			


		@endforeach

			<tr style="font-weight: bold">
				<td align="left" width="20%">TOTAL</td>
				<td align="center" width="5%"><?=number_format($total_meta,0)?></td>
				<td align="center" width="5%"><?=number_format($total_pd,0)?></td>

				<td align="center" width="5%"><?=number_format($total_p15,0)?></td>
				<td align="center" width="5%"><?=number_format($total_p30,0)?></td>
				<td align="center" width="5%"><?=number_format($total_pt,0)?></td>
				<td align="center" width="5%"><?=number_format($total_pp,0)?></td>
				<td align="center" width="5%"><?=number_format($total_pe,0)?></td>
				<td align="center" width="5%"><?=number_format($total_id,0)?></td>
				

				<td align="center" width="5%"><?=number_format($total_i15,0)?></td>
				<td align="center" width="5%"><?=number_format($total_i30,0)?></td>
				<td align="center" width="5%"><?=number_format($total_it,0)?></td>
				<td align="center" width="5%"><?=number_format($total_ip,0)?></td>
				<td align="center" width="5%"><?=number_format($total_ie,0)?></td>
				<td align="center" width="5%"><?=number_format($total_orc,0)?></td>
				

			</tr>
		</tbody>
		</table>

</div>

<form action="/produtos/status/uploadarquivo" method="post" enctype="multipart/form-data">
  @csrf
<div class="modal fade" id="modalUploadArquivo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Upload atualiza status3 do processa</h4><BR>
         <h6 class="modal-title" id="myModalLabel"> Arquivo em XML - Coluna 1 - Código Secundário / Coluna 2 - Status Modificado</h6><BR>
      </div>
      <div class="modal-body">
        <input type="text" name="processamento" id="{{$classificacao[0]->processamento}}" value="{{$classificacao[0]->processamento}}">
        <input type="file" name="arquivo" class="form-control">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>  
</form>    

@stop