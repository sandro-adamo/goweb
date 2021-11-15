@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i> Usuários
@append 

@section('conteudo')
<form action="" method="get">
<div class="row">
  <div class="col-md-12">
    <div class="box box-widget box-body">
      <div class="row">
        <div class="col-md-6">
          <input type="text" autofocus="" name="pesquisa" class="form-control">
        </div>
        <div class="col-md-2">
          <button class="btn btn-default btn-flat"><i class="fa fa-search"></i> Pesquisar</button>
        </div>
        <div class="col-md-4">
          <a href="/usuarios/novo" class="btn btn-flat btn-default pull-right">Novo Usuário</a>
        </div>
      </div>
      <br>
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
			      <th>AN8</th>
            <th>Nome</th>
            <th>Perfil</th>
            <th>E-Mail</th>
            <th>Último Acesso</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($usuarios as $usuario)
          <tr>
			  <td>{{$usuario->id_addressbook}}</td>
            <td><a href="/usuarios/{{$usuario->id}}">{{$usuario->nome}}</a></td>
            <td>@if (isset($usuario->perfil->descricao)) {{$usuario->perfil->descricao}} @endif</td>
            <td>{{$usuario->email}}</td>
            <td>{{$usuario->ultacesso}}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="col-md-12" align="center">{{$usuarios->links()}}</div>
  </div>
</div>

@stop
