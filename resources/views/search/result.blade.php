@extends('layout.principal')

@section('title')
<i class="fa fa-list"></i> Produtos
@append 

@section('conteudo')

<div class="row">

  @foreach ($itens as $modelo)

  <div class="col-md-3">
    <div class="box box-widget">
      <a href="/painel/{{$modelo->agrup}}/{{$modelo->modelo}}"><img src="https://portal.goeyewear.com.br/teste999.php?referencia={{$modelo->secundario}}" class="img-responsive"></a>

      <div class="box-body">
        <small>{{$modelo->grife}}</small> <br>
        <span style="font-size: 22px;">{{$modelo->secundario}}</span> 
      </div>
    </div>
  </div>
  
  @endforeach
  <div class="col-md-12" align="center">{{ $itens->links() }}</div>
</div>
@stop