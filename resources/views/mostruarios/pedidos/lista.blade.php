@extends('layout.principal')

@section('title')
<i class="fa fa-suitcase"></i> Mostruários
@append 

@section('conteudo')

@php
  $id_usuario = \Auth::id();
  
$numpedido = $_GET["referencia"];
  $id_usuario = \Auth::user()->id_addressbook;

 
                $detahepedido = \DB::select("select agrup, item, colmod
                from mostruarios
                left join itens on secundario = item
                where mostruarios.pc_cliente = ''
                and mostruarios.tipo = 'sm'
                and pedido = $numpedido
                group by agrup, item, colmod
                
                order by item asc");
                

@endphp







 
      <div class="box box-body box-widget">
        
        <h4 class="box-title"><b> Pedidos {{$numpedido}}</b></h4>
        <div class="col-ms-4">

          <table class="table table-condensed table-bordered">
        <tr>
          <td> <b>Agrupamento</b></td>
          <td> <b>Item</b></td>
          <td> <b>Coleção modelo</b></td>
          <td><b> </b></td>
       
        </tr>
         @foreach ($detahepedido as $detahepedido1)

                  <tr>  
                    <td align="middle" ><div>{{$detahepedido1->agrup}}</div></td>
                    <td align="middle">{{$detahepedido1->item}}</td>
                    <td align="middle">{{$detahepedido1->colmod}}</td>
                    <td>
                    <div>
        <a href="" class="zoom" data-value="{{$detahepedido1->item}}">
            <img width= "250" src="https://portal.goeyewear.com.br/teste999.php?referencia={{$detahepedido1->item}}" class="img-responsive">
        </a></div></td>
      
                    
  
                    



                  </tr>
                  @endforeach
</table>
         
      </div>
      </div>
    </div>

    @stop