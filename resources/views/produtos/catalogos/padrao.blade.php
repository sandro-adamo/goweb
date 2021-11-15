@extends('produtos/painel/index')

@section('title')
  <i class="fa fa-list"></i> Novo
@append 

@section('conteudo')
<div class="row">

@if (Session::has('alert-warning'))
  <div class="callout callout-warning">{{Session::get("alert-warning")}}</div>
@endif

<form action="/catalogo/padrao/{{$tipo}}/exporta" class="form-horizontal" method="post">
  @csrf
<div class="col-md-6 col-md-offset-3">
  <div class="box box-widget">
    <div class="box-header with-border">
      <h3 class="box-title">Novo Catálogo - {{$tipo}}</h3>
    </div>
    <div class="box-body">

      <div class="form-group">
        <label class="col-md-3 control-label">Agrupamento</label>
        <div class="col-md-8">
          <select name="agrup" class="form-control">
            @foreach ($agrupamentos as $agrupamento)
              <option value="{{$agrupamento->codagrup}}">{{$agrupamento->agrup}}</option>
            @endforeach
          </select>
        </div>  
      </div>


      <div class="form-group">
        <label class="col-md-3 control-label">Descricão</label>
        <div class="col-md-8">
          <textarea name="descricao" rows="4" class="form-control"></textarea>
        </div>  
      </div>


    </div>
    <div class="box-footer">
      <button type="submit" class="btn btn-primary btn-flat pull-right">Gerar Catálogo</button>
    </div>
  </div>
</div>
</div>
@stop