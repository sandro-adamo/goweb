@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i> Perfil de Vendas
@append 

@section('conteudo')

<form action="" method="get">
<div class="row">
  <div class="col-md-12">
    <div class="box box-widget box-body">
      <div class="row">
        <div class="col-md-6">

          {{$perfil[0]->cliente->razao}}
        </div>
        <div class="col-md-2">
          <button class="btn btn-default btn-flat"><i class="fa fa-search"></i> Pesquisar</button>
        </div>
        <div class="col-md-4">
        </div>
      </div>      
      <br>
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>Descrição</th>
          </tr>
        </thead>
        <tbody>


        </tbody>
      </table>
    </div>
</div>
</div>
@stop