@extends('layout.principal')

@section('titulo', 'Painel de Pré pedidos') 

@section('title')
<i class="fa fa-object-group"></i> Painel de Pré pedidos
@append 

@section('conteudo') 

<div class="row">
  @if (count($agrupamentos) > 0)
  @foreach ($agrupamentos as $agrupamento)
  <div class="col-md-3">
    <div class="box box-widget box-body" style="min-height: 220px; height: 220px" align="center">
      <div style="height: 80px;">
        <img src="/img/marcas/{{$agrupamento->grife}}.png" width="180px" style="max-height: 60px;" class="img-responsive">
      </div>


      @if ( \Auth::user()->admin == 1 ) 
      <tr>
       <a href="">
        <i class="fa fa-object-group" title="Sem quantidade">
        Sem quantidade</a></i>
      </tr>

      <a href="">
        <i  class="fa fa-object-group" title="Sem cores">
        Sem cores</a></i>
      </tr>

      <tr>
       <a href="">
        <i class="fa fa-object-group" title="Apenas modelos">
        Sugestões</a></i>
      </tr>
	  
	  <tr>
       <a href="">
        <i class="fa fa-object-group" title="Apenas modelos">
        necessidades</a></i>
      </tr>
	  
	  <tr>
       <a href="">
        <i class="fa fa-object-group" title="Apenas modelos">
        Redesign</a></i>
      </tr>
	   
	  
	  	   
      @endif
	  
	  
	  
	  
	  
	  
	  
      <center><a href="/painel/{{$agrupamento->agrupamento}}"><span class="label bg-green">{{$agrupamento->agrupamento}}</span></a></center>
      @php
	  $colecoes = \DB::select("Select col_mod from compras_modelos where agrupamento = '$agrupamento->agrupamento'  group by col_mod, grife order by col_mod desc
		");
	  @endphp
        @foreach ($colecoes as $ano)

     
	   <a href="/painel/<?=urldecode($agrupamento->agrupamento)?>/?colmod={{$ano->col_mod}}"><span class="label bg-blue">{{$ano->col_mod}}</span></a>
		
		
          @endforeach
        </br>
        
      </div>
    </div>
    @endforeach
    @endif
  </div>
  @stop