@extends('layout.principal')

@section('title')
<i class="fa fa-shopping-cart"></i> Meus Pedidos
@append 

@section('conteudo')


<div class="box box-widget box-body">

  <div class="row">
    <div class="col-md-6">
      <input type="text" name="pesquisa" class="form-control">
    </div>
    <div class="col-md-2">
      <button class="btn btn-default btn-flat"><i class="fa fa-search"></i> Pesquisar</button>
    </div>

  </div>      

  <br>
  <table class="table table-striped table-bordered table-hovered">
    <thead>
      <tr>
       <th>Data</th>
       <th>Número</th>
       <th>Cliente</th>
       <th>Valor</th>
     </tr>
   </thead>
   <tbody>
    @foreach ($pedidos as $pedido)

      <tr>
       <td align="center">{{date('d/m/Y', strtotime($pedido->dt_venda))}}</td>
       <td align="center"><a href="/pedidos/{{$pedido->pedido}}"> {{$pedido->pedido}}</a></td>
       <td>{{$pedido->id_cliente}} - {{$pedido->razao}}</td>
       <td align="right">{{number_format($pedido->valor,2,',','.')}}</td>
     </tr>

    @endforeach
   </tbody>
 </table>

</div>

@stop