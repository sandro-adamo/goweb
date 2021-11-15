@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i> Perfis de Acesso
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
          <a href="/perfis/novo" class="btn btn-flat btn-default pull-right">Novo Perfil</a>
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
          @foreach ($perfis as $perfil)
          <tr>
            <td><a href="/perfis/{{$perfil->id}}">{{$perfil->descricao}}</a></td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
</div>
@stop