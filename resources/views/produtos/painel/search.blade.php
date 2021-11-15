@extends('produtos/painel/index')

@section('title')
  <i class="fa fa-search"></i> Search
@append 

@section('conteudo')
<div class="row">

  @foreach ($itens as $item) 
    <div class="col-md-2" > 
      <div class="box box-body box-widget" style="height: 220px;"> 
        <div style="max-height: 180px; height: 180px; min-height: 180px;">
          <a href="/painel/{{$item->agrup}}/{{$item->modelo}}">
          <img src="https://portal.goeyewear.com.br/teste999.php?referencia={{$item->secundario}}" class="img-responsive">
          </a>
        </div> 
        {{$item->secundario}}
      </div>
    </div>
  @endforeach

</div>
@stop