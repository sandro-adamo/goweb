@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i> <i class="fa fa-key"></i> Autorizações
@append 

@section('conteudo')


<div class="row">
  <div class="col-md-12">
    <div class="box box-widget box-body">
      <div class="row">
        <div class="col-md-6">
          <input type="text" name="pesquisa" class="form-control">
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
            <th>Item</th>
            <th>Coleção</th>
            <th>Classificação</th>
            <th>Valor</th>
            <th colspan="2">Ação</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($autorizacoes as $autorizacao)
          <tr>
            <td>{{$autorizacao->secundario}}</td>
            <td>{{$autorizacao->colmod}}</td>
            <td>{{$autorizacao->clasmod}}</td>
            <td align="right">{{$autorizacao->valor}}</td>
            <td align="center"><a href="/autorizacoes/{{$autorizacao->id}}/autoriza/"> <i class="fa fa-check text-green"></i> Aprovar </a></td>
            <td align="center"><a href=""> <i class="fa fa-close text-red"></i> Reprovar </a></td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
</div>
@stop