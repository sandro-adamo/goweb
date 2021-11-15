@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i> Importações 
@append 

@section('conteudo')
@if (Session::has('alert-success'))
  <div class="callout callout-success">{{Session::get("alert-success")}}</div>
@endif 
@if (Session::has('alert-warning'))
  <div class="callout callout-warning">{{Session::get('alert-warning')}}</div>
@endif
<div class="row">
  <div class="col-md-12">
    <div class="box box-widget box-body">
      <div class="row">
       
       
<!--
        <div class="col-md-12">
          <a href="/compras/invoice/importa" id="btnImportaArquivo" class="btn btn-flat btn-default pull-right">Importar Arquivo</a>
        </div>
-->
      </div>      
      <br>
       <table class="table table-bordered table-striped" id="myTable">
        <thead>
          <tr>
			  <th>Item</th>
			  <th>Invoice</th>
            <th>Pedido</th>
            <th>Tipo</th>
            <th>Qtd</th>
			
            <th>Dt Invoice</th>
			<th>Ult status</th>
			<th>Prox status</th>

            



          </tr>
        </thead>
        <tbody>
          @foreach ($cet as $cets)
          <tr>
			  <td> {{$cets->secundario}}</a></td>
            <td> {{$cets->ref_go}}</a></td>
            <td> {{$cets->pedido}}</a></td>
            <td>{{$cets->tipo}}</td>
            <td>{{number_format($cets->qtde_sol,0)}}</td>
			 <td>{{$cets->dt_pedido}}</td>
			<td>{{$cets->ult_status}}</td>
			 <td>{{$cets->prox_status}}</td>
            
           

          </tr>  
            @endforeach
        </tbody>
      </table>
    </div>
</div>






@stop