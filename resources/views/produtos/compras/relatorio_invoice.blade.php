@extends('layout.principal')

@section('titulo', 'Volume estoque') 
<i class="fa fa-dashboard">Relatório invoice</i> 
@section('title')
@append 

@section('conteudo')


        <div class="box box-body box-widget">   
			<h2>Invoice {{$sempedido[0]->invoice}}</h2>
            
			</br>
<div class="col-md-6">
	<h3 align="center">Itens sem pedido</h3>
   		<table class="table table-condensed table-bordered" >
			<thead>
		
	
        </tr> 
			</thead>
		
			<thead>
		<tr class="bg-primary">
        <th align="center">Item</th>
		<th align="center">Qtd sem pedido</th>
        </tr> 
			</thead>
	   <tbody>
		@foreach ($sempedido as $item)
		<tr >
		<td align="center">{{$item->item}}</td>
		<td align="center">{{$item->qtd}}</td>
		
	
		</tr>
		@endforeach
			<tr>
		   	<td align="center"><b>TOTAL</b></td>
		<td align="center"><b>{{$sempedidototal[0]->tt}}</b></td>
		   </tr>
	</tbody>
		
	</table>
	</div>

<div class="col-md-6">
	<h3 align="center">Itens com custo divergente</h3>
   		<table class="table table-condensed table-bordered" >
			<thead>
		
	
        </tr> 
			</thead>
		
			<thead>
		<tr class="bg-primary">
        <th align="center">Item</th>
		<th align="center">Custo pedido</th>
		<th align="center">Custo invoice</th>
		<th align="center">Diferença</th>
        </tr> 
			</thead>
	   <tbody>
		@foreach ($veririfcavalor as $itemvalor)
		<tr >
		<td align="center">{{$itemvalor->secundario}}</td>
		<td align="center">{{number_format($itemvalor->custo_pedido,2)}}</td>
		<td align="center">{{$itemvalor->custo_invoice}}</td>
		<td align="center">{{$itemvalor->custo_invoice-$itemvalor->custo_pedido}}</td>
		
	
		</tr>
		@endforeach
			
	</tbody>
		
	</table>
	</div>

</div>
		


							 
							 
							 
@stop