	@extends('produtos/painel/index')
  @php

$secundario = $_GET["secundario"];
@endphp

<title>CET - $secundario </title>
@section('title')
<i class="fa fa-list"></i> CET
@append 

@section('conteudo')
@include('produtos.painel.modal.caracteristica')	
@php




	  
	  $query2 = DB::select("select*,
case when status = 280 and tipo = 'Oi' then 'PL Recebido'
when status = 350 and tipo = 'Oi' then 'LI solicitada'
when status = 355 and tipo = 'Oi' then 'LI deferida'
when status = 359 and tipo = 'Oi' then 'Embarque autorizado'
when status = 365 and tipo = 'Oi' then 'Em trânsito internacional'
when status = 369 and tipo = 'Oi' then 'Chegada confirmada'
when status = 375 and tipo = 'Oi' then 'Carga removida'
when status = 379 and tipo = 'Oi' then 'DI Registrada'
when status = 385 and tipo = 'Oi' then 'NF emitida'
when status = 390 and tipo = 'Oi' then 'Em trânsito nacional'
when status = 400 and tipo = 'Oi' then 'Entregue TO'
when  tipo = 'OP' then 'Verificar com importação(comexport)'
else 'sem definição status' end as 'descricao_status'

from cet_aberto

      where secundario = '$secundario'
	  order by dt_emissao asc
	  
	 

");



@endphp
	  <div class="box-body">
      <title>CET - Importação</title>

          <h5><b>{{$query2[0]->secundario}}</b></h5>     
<a href="" class="zoom" data-value="{{$query2[0]->secundario}}"><img src="https://portal.goeyewear.com.br/teste999.php?referencia={{$query2[0]->secundario}}" style="max-height: 100px;" class="img-responsive"></a>
          <table class="box-body table-responsive table-striped">
            <tr align="center">
              
				<td width="5%" align="center"><b>Data</b>
			  <td width="3%"><b>Item</b></td>
              
			  <td width="3%"><b>Quantidade</b></td>
              <td width="3%"><b>Tipo</b></td>
              
              <td width="3%"><b>Numero pedido</b></td>
              <td width="3%"><b>Invoice</b></td>
              <td width="3%"><b>Status</b></td>
              <td width="3%"><b>Descrição</b></td>       	
                             	
            </tr>
	  
	   @foreach ($query2 as $dados) 
	  
	  <tr align="center">
              
              <td width="3%" align="center">{{$dados->dt_emissao}}</td>
              <td width="3%">{{$dados->secundario}}</td>               	
              <td width="4%">{{$dados->qtd}}</td>               	
              <td width="4%">{{$dados->tipo}}</td> 
              <td width="2%">{{$dados->oi}}</td>               	
              <td width="2%">{{$dados->invoice}}</td>  
              <td width="2%">{{$dados->status}}</td>  
              <td width="2%">{{$dados->descricao_status}}</td>  
              

                             	
            </tr>
	  @endforeach
</table>

        </div>	

  @stop