@extends('produtos/painel/index')

@section('titulo')
  <i class="fa fa-book"></i> Products Catalog
@append 


@section('conteudo')

<div class="row">


@foreach ($catalogos as $catalogo)

<div class="col-md-3">
  <div class="box box-widget">
    <div class="box-body">

      <div class="imagem" style="height: 150px; min-height: 150px; max-height: 150px;" align="center">
        <img src="https://portal.goeyewear.com.br/teste999.php?referencia=22" class="img-responsive" style="height: 150px; min-height: 150px; max-height: 150px;">
      </div>

      <div class="row">
        <div class="col-md-12">  
          <div  style="height: 30px; min-height: 30px;">
            <h3><a href="/catalogo/{{$catalogo->codigo}}/">{{$catalogo->titulo}}</a></h3>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">  
          <div  style="height: 30px; min-height: 30px;">{{$catalogo->descricao}}</div>
        </div>
      </div>      

      <div class="row">
        <div class="col-md-6">  
          <a href="/catalogo/{{$catalogo->codigo}}/edita"><i class="fa fa-edit"></i> Editar</a>
        </div>
        <div class="col-md-6" align="right">          
          <a href="/catalogo/{{$catalogo->codigo}}/exclui" class="text-red"><i class="fa fa-trash"></i> Excluir</a>
        </div>
      </div>
    </div>
  </div>
</div>

@endforeach

</div>
@stop