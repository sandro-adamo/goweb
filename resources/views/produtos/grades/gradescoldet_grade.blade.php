
<table class="table table-bordered" id="example3">
	<thead>
		<tr>
			<th width="5%">Status</th>
			<th width="8%">Modelo</th>
			<th width="8%">Clasmod</th>
			<th width="8%">Entrada</th>
			<th width="8%">Saida</th>
			<th width="5%">Genero</th>
			<th width="8%">Idade</th>
			<th width="15%">Material</th>
			<th width="15%">Fixacao</th>
			<th width="10%">Estilo</th>
			<th width="10%">Tamanho</th>
			<th width="5%">vds 30dd</th>
			<th width="5%">vds 180dd</th>
			<th width="5%">vds total</th>
			
			<th width="5%">etq disp</th>
			<th width="5%">etq tt</th>
			

		</tr>
	</thead>
	<tbody>
		@foreach ($itensagregado as $catalogo)

		@php
		switch ($catalogo->status_mala) {
			case 'entradas':
			$formato = 'fa fa-plus-square text-green';
			
			break;
			case 'saidas':
			$formato = 'fa fa-minus-square text-red';
			
			break;             
			default:
			$formato = 'fa fa-check-square text-blue';

		}
		@endphp
		<tr>
			<td align="left" class="{{$formato}}"> {{$catalogo->status_mala}}</td>
			<td align="left">  <a href="/painel/{{$catalogo->agrup}}/{{$catalogo->modelo}}">{{$catalogo->modelo}}</a></td>
			<td align="left"> {{$catalogo->clasmod}}</td>
			<td align="left">{{$catalogo->entrada}}</td>
			<td align="left">{{$catalogo->saida}}</td>
			<td align="left">{{$catalogo->genero}}</td>
			<td align="left">{{$catalogo->idade}}</td>
			<td align="left">{{$catalogo->material}}</td>
			<td align="left">{{$catalogo->fixacao}}</td>
			<td align="left">{{$catalogo->estilo}}</td>
			<td align="left">{{$catalogo->tamanho}}</td>
			
			<td align="left">{{$catalogo->qtde_30dd}}</td>
			<td align="left">{{$catalogo->qtde_180dd}}</td>
			<td align="left">{{$catalogo->qtde_total}}</td>
			
			<td align="left">{{$catalogo->disp_vendas}}</td>
			<td align="left">{{$catalogo->etq_total}}</td>


		</tr>
		@endforeach
	</tbody>
</table>
