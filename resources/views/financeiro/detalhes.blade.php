@extends('layout.principal')

@section('title')
<i class="fa fa-money"></i> Detalhes do Titulo
@append 

@section('conteudo')

<div class="row">
  <div class="col-md-12">
    <div class="box box-widget box-body">
      {{$titulo[0]->titulo}}
      {{$titulo[0]->tipo}}
    </div>
  </div>
</div>

@stop