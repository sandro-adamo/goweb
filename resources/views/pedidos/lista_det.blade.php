@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i> Detalhamento dos pedidos 

@if (isset($_GET["situacao"])) {{$_GET["situacao"]}} @endif
@append 

@section('conteudo')

@php


	$data 			= $_GET["data"];    
 	
    echo 'data: '.$data;

   
    if (isset($_GET["data"])) {
    $data = $_GET["data"];
    

    $query1 = \DB::select("
		select pedidos_itens.*, itens.ean
		from pedidos_itens 
		left join itens on itens.secundario = pedidos_itens.item  
   where date(pedidos_itens.created_at) = '$data'
    ");
  }

@endphp

<div class="row">
  <div class="col-md-12">
    <div class="box box-widget box-body">


      @if ($query1[0]->pedido == '')

      <form action="/pedidos/{{$data}}/vincular" method="post" class="form-horizontal">
        @csrf
      <div class="form-group">
        <label class="col-md-1 control-label">Pedido</label>
        <div class="col-md-2">
          <input type="text" name="pedido_jde" class="form-control">
        </div>
        <div class="col-md-2">
          <button class="btn btn-flat btn-default" type="submit">Vincular</button>
        </div>
      </div>
      </form>
      @endif


      <br>
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
           <th></th>
            <th>numero</th>
            <th>ean</th>
            <th>item</th>
            <th>qtde</th>
            <th>valor</th>
            <th>cliente</th>
            <th>email</th>
            <th>Reserva GO</th>
            <th>Pedido GO</th>
            
          </tr>
        </thead>
        <tbody> 

          @if ($query1)

            @foreach ($query1 as $linha)

              <tr>
               	<td><a href=""><i class="fa fa-trash"></i></a></td>
                <td>{{$linha->id_pedido}}</td>
                <td>{{$linha->id_pedido}}</td>
                <td>{{$linha->item}}</td>
                <td>{{$linha->qtde}}</td>
                <td>{{$linha->total}}</td>
                <td>{{$linha->nome}}</td>
                <td>{{$linha->email}}</td>
                <td>Reserva Num STCOS-0001</td>
                <td>Pedido nao gerado</td>
              </tr>


            @endforeach


          @endif
        </tbody>
      </table>
    </div>
</div>
@stop