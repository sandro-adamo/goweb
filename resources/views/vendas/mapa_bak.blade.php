@extends('layout.principal')

@section('title')
<i class="fa fa-map"></i> Mapa de Vendas
@append 

@section('conteudo')



@php

  $query1 = \DB::select("select grife from itens group by grife");

  if (isset($_GET["grife"])) {

    $grife = $_GET["grife"];
    $query2 = \DB::select("select grife, agrup from itens where grife = '$grife' group by grife, agrup");

  }

  if (isset($_GET["agrup"])) {

    $agrup = $_GET["agrup"];
    $query3 = \DB::select("select grife, agrup, modelo from itens where agrup = '$agrup' group by grife, agrup, modelo limit 10");

  }

  if (isset($_GET["modelo"])) {

    $modelo = $_GET["modelo"];
    $query4 = \DB::select("select grife, agrup, modelo, secundario from itens where modelo = '$modelo' group by grife, agrup, modelo, secundario limit 10");

  }

@endphp



<div class="row">
 
	<div class="col-md-2">
    <div class="box box-body box-widget">
    <table class="table table-bordered table-hover">

    @foreach ($query1 as $linha)

            <tr class="danger">
              <td><a href="/mapa?grife={{$linha->grife}}">{{$linha->grife}}</a><br></td>
            </tr>
            


    @endforeach 
    </table>
    </div>
  </div>


  @if (isset($query2))
    <div class="col-md-2">
      <div class="box box-body box-widget">
      @foreach ($query2 as $linha)

            <table class="table table-bordered">
              <tr>
                <td><a href="/mapa?grife={{$linha->grife}}&agrup={{$linha->agrup}}">{{$linha->agrup}}</a></td>
              </tr>
            </table>
            <br>


      @endforeach 
      </div>
    </div>
  @endif 


  @if (isset($query3))
    <div class="col-md-2">
      <div class="box box-body box-widget">
      @foreach ($query3 as $linha)

            <table class="table table-bordered">
              <tr>
                <td><a href="/mapa?grife={{$linha->grife}}&agrup={{$linha->agrup}}&modelo={{$linha->modelo}}">{{$linha->modelo}}</a></td>
              </tr>
            </table>
            <br>
      @endforeach 
      </div>
    </div>
  @endif 




  @if (isset($query4))
    <div class="col-md-2">
      <div class="box box-body box-widget">
      @foreach ($query4 as $linha)

            <table class="table table-bordered">
              <tr>
                <td><a href="/mapa?grife={{$linha->grife}}&agrup={{$linha->agrup}}&modelo={{$linha->modelo}}&item={{$linha->secundario}}">{{$linha->secundario}}</a></td>
              </tr>
            </table>
            <br>

      @endforeach 
      </div>
    </div>
  @endif 


	
	
	
</div>


@stop