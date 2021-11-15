@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i> Atualização em Massa
@append 

@section('conteudo')

<form action="/produtos/atualizacao" method="post" enctype="multipart/form-data">
  @csrf
<div class="row">
  <div class="col-md-12">
    <div class="box box-widget box-body">
      <div class="row">
        <div class="col-md-6">
          <input type="file" name="arquivo" class="form-control">
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-flat btn-default pull-right">Importar</button>
        </div>
      </div>      
      <br>

    </div>
  </div>
</div>
</form>
@stop