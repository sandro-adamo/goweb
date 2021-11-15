@extends('produtos/painel/index')

@section('title')
  <i class="fa fa-list"></i> Novo
@append 

@section('conteudo')
<div class="row">

<form action="/catalogo/novo" class="form-horizontal" method="post">
  @csrf
<div class="col-md-6 col-md-offset-3">
  <div class="box box-widget">
    <div class="box-header with-border">
      <h3 class="box-title">Novo Catálogo</h3>
    </div>
    <div class="box-body">

      <div class="form-group">
        <label class="col-md-2 control-label">Titulo</label>
        <div class="col-md-9">
          <input type="text" name="titulo" required="" autofocus="" class="form-control">
        </div>  
      </div>

      <div class="form-group">
        <label class="col-md-2 control-label"></label>
        <div class="col-md-9">
          <input type="checkbox" name="publico" value="1"> Público
        </div>  
      </div>


      <div class="form-group">
        <label class="col-md-2 control-label">Descricão</label>
        <div class="col-md-9">
          <textarea name="descricao" rows="4" class="form-control"></textarea>
        </div>  
      </div>


    </div>
    <div class="box-footer">
      <button type="submit" class="btn btn-primary btn-flat pull-right">Montar Catálogo</button>
    </div>
  </div>
</div>
</div>
@stop