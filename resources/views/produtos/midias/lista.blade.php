@extends('produtos/painel/index')

@section('title')
<i class="fa fa-map"></i> Midias {{$item}}
@append 

@section('conteudo')


<h3>{{$item}}</h3>


<form action="" method="post" class="form-horizontal" enctype="multipart/form-data">
  @csrf
  <div class="row">
    <div class="col-md-12">
      <div class="box box-widget box-body">
        <div class="row">
          <div class="col-md-6">
            <input type="file" name="arquivo" class="form-control">
          </div>
          <div class="col-md-2">
            <button class="btn btn-default btn-flat"><i class="fa fa-upload"></i> Upload</button>
          </div>
          <div class="col-md-4">
          </div>
        </div>      
        <br>
      </div>
    </div>
  </div>

  <div class="row">

    @foreach ($midias as $midia) 

      @if ($midia->arquivo <> '')
        <div class="col-md-3" align="center"> 
          <img src="/storage/{{$midia->arquivo}}" class="img-responsive">
          <a href="/painel/midias/{{$midia->id}}/exluir" class="text-red"><i class="fa fa-trash"></i> Excluir</a>
        </div>
      @endif

    @endforeach

  </div>
</form>
@stop