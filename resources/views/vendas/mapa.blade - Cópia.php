@extends('layout.principal')

@section('title')
<i class="fa fa-map"></i> Mapa de Vendas
@append 

@section('conteudo')



@php

  $query1 = \DB::select("
	select grife, count(modelo) modelos from (
		select grife, modelo from itens where codtipoitem = '006' and codtipoarmaz not in ('o') and secundario not like '%semi%' and colmod = '2021 01' group by grife, modelo
	) as fim group by grife
  ");


  if (isset($_GET["grife"])) {

    $grife = $_GET["grife"];
    $query2 = \DB::select("
	select grife, agrup, count(modelo) modelos from (
		select grife, agrup, modelo from itens where codtipoitem = '006' and codtipoarmaz not in ('o') and secundario not like '%semi%' and grife = '$grife' and colmod = '2021 01' group by grife, agrup, modelo
	) as fim group by grife, agrup ");
  }


if (isset($_GET["agrup"])) {

    $agrup = $_GET["agrup"];
    $query3 = \DB::select("
	select grife, agrup, genero, count(modelo) modelos from (
		select grife, agrup, modelo, genero from itens where codtipoitem = '006' and codtipoarmaz not in ('o') and secundario not like '%semi%' and grife = '$grife' and agrup = '$agrup' and colmod = '2021 01' group by grife, agrup, modelo, genero
	) as fim group by grife, agrup, genero ");
  }


  if (isset($_GET["genero"])) {

    $genero = $_GET["genero"];
    $query4 = \DB::select("
	select grife, agrup, genero, material, count(modelo) modelos from (
		select grife, agrup, modelo, genero, material from itens where codtipoitem = '006' and codtipoarmaz not in ('o') and secundario not like '%semi%' and grife = '$grife' and agrup = '$agrup' and genero = '$genero' and colmod = '2021 01' group by grife, agrup, modelo, genero, material
	) as fim group by grife, agrup, genero, material");
  }


if (isset($_GET["material"])) {

    $material = $_GET["material"];
    $query5 = \DB::select("
	select grife, agrup, genero, material, idade, count(modelo) modelos from (
		select grife, agrup, modelo, genero, material, idade from itens where codtipoitem = '006' and codtipoarmaz not in ('o') and secundario not like '%semi%' and grife = '$grife' and agrup = '$agrup' and genero = '$genero' and material = '$material' and colmod = '2021 01' group by grife, agrup, modelo, genero, material, idade
	) as fim group by grife, agrup, genero, material, idade ");
  }


if (isset($_GET["idade"])) {

    $idade = $_GET["idade"];
    $query6 = \DB::select("
	select grife, agrup, genero, material, idade, fornecedor, count(modelo) modelos from (
		select grife, agrup, modelo, genero, material, idade, fornecedor from itens where codtipoitem = '006' and codtipoarmaz not in ('o') and secundario not like '%semi%' and grife = '$grife' and agrup = '$agrup' and genero = '$genero' and material = '$material' and idade = '$idade' and colmod = '2021 01' group by grife, agrup, modelo, genero, material, idade, fornecedor
	) as fim group by grife, agrup, genero, material, idade, fornecedor ");
  }

if (isset($_GET["fornecedor"])) {

    $fornecedor = $_GET["fornecedor"];
    $query7 = \DB::select("

	select grife, agrup, genero, material, idade, fornecedor, modelo, count(id) itens from itens 
	where codtipoitem = '006' and codtipoarmaz not in ('o') and secundario not like '%semi%' and colmod = '2021 01' and agrup = '$agrup' and genero = '$genero' and grife = '$grife' and material = '$material' 
	and idade = '$idade' and fornecedor = '$fornecedor'
	group by grife, agrup, genero, material, idade, fornecedor, modelo ");

  }

@endphp



<div class="row">
  <div class="col-md-2">
    <div class="box box-body box-widget">
    <table class="table table-bordered table-hover">

    @foreach ($query1 as $linha)

            <tr class="danger">
              <td><a href="/mapa?grife={{$linha->grife}}">{{$linha->grife}} -> {{$linha->modelos}}</a><br></td>
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
                <td><a href="/mapa?grife={{$linha->grife}}&agrup={{$linha->agrup}}">{{$linha->agrup}} -> {{$linha->modelos}}</a></td>
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
		  <td> {{$_GET["agrup"]}}</td>
		  
      @foreach ($query3 as $linha)

            <table class="table table-bordered">
              <tr>
                <td><a href="/mapa?grife={{$linha->grife}}&agrup={{$linha->agrup}}&genero={{$linha->genero}}">{{$linha->genero}} -> {{$linha->modelos}}</a></td>
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
		 <td> {{$_GET["genero"]}}</td>
		  
      @foreach ($query4 as $linha)

            <table class="table table-bordered">
              <tr>
                <td><a href="/mapa?grife={{$linha->grife}}&agrup={{$linha->agrup}}&genero={{$linha->genero}}&material={{$linha->material}}">{{$linha->material}} -> {{$linha->modelos}}</a></td>
              </tr>
            </table>
            <br>

      @endforeach 
      </div>
    </div>
  @endif 

	
 @if (isset($query5))
    <div class="col-md-2">
      <div class="box box-body box-widget">
		   <td> {{$_GET["material"]}}</td>
      @foreach ($query5 as $linha)

            <table class="table table-bordered">
				
              <tr>
                <td><a href="/mapa?grife={{$linha->grife}}&agrup={{$linha->agrup}}&genero={{$linha->genero}}&material={{$linha->material}}&idade={{$linha->idade}}">{{$linha->idade}} ->  {{$linha->modelos}}</a></td>
              </tr>
            </table>
            <br>

      @endforeach 
      </div>
    </div>
  @endif 

	
@if (isset($query6))
    <div class="col-md-2">
      <div class="box box-body box-widget">
		   <td> {{$_GET["idade"]}}</td>
		  
      @foreach ($query6 as $linha)

            <table class="table table-bordered">
				
              <tr>
                <td><a href="/mapa?grife={{$linha->grife}}&agrup={{$linha->agrup}}&genero={{$linha->genero}}&material={{$linha->material}}&idade={{$linha->idade}}&fornecedor={{$linha->fornecedor}}">{{$linha->fornecedor}} ->  {{$linha->modelos}}</a></td>
              </tr>
            </table>
            <br>

      @endforeach 
      </div>
    </div>
  @endif 

	
@if (isset($query7))
    <div class="col-md-1">
      <div class="box box-body box-widget">
		  
      @foreach ($query7 as $linha)

            <table class="table table-bordered">
				
              <tr>
                <td><a href="/mapa?grife={{$linha->grife}}&agrup={{$linha->agrup}}&genero={{$linha->genero}}&material={{$linha->material}}&idade={{$linha->idade}}&fornecedor={{$linha->fornecedor}}&modelo={{$linha-> modelo}}">{{$linha->modelo}} ->  {{$linha->itens}}</a></td>
              </tr>
            </table>
            <br>

      @endforeach 
      </div>
    </div>
  @endif 	
	
	
</div>


@stop