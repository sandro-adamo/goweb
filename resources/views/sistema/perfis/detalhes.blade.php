@extends('layout.principal')

@section('title')
<i class="fa fa-list"></i> Cadastro de Perfil
@append 

@section('conteudo')

<form action="/perfis/grava" method="post" class="form-horizontal">
@csrf
<div class="row">
  <div class="col-md-9">

    @if (Session::has('alert'))
      <p class="callout callout-success">{{Session::get('alert')}}</p>
    @endif
    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-file-o"></i> Dados do Perfil</h3>
      </div>
      <div class="box-body">


        <div class="form-group">
          <label class="col-md-2 control-label">Descrição</label>
          <div class="col-md-8">
            <input type="text" name="descricao" class="form-control" value="{{ $perfil->descricao }}">
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-2 control-label">Home</label>
          <div class="col-md-4">
            <input type="text" name="home" class="form-control" value="{{ $perfil->home }}">
          </div>
        </div>


      </div>
    </div>



    <div class="row">

      <div class="col-md-4">
        @include('sistema.perfis.acessos')  
      </div>

      <div class="col-md-8">
        @include('sistema.perfis.permissoes')
      </div>
      
      

    </div>


  </div>
</div>
<div class="col-md-3">
    <div class="box box-widget">
     <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-gears"></i> Controle</h3>
      </div>
      <div class="box-body">

        <div class="form-group">
          <label class="col-md-4 control-label">ID</label>
          <div class="col-md-7">
            <input type="text" name="id_perfil" readonly class="form-control" value="{{$perfil->id}}">
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-4 control-label">Status</label>
          <div class="col-md-7">
            <select name="status" class="form-control">
              <option value="1">Sim</option>
              <option value="0">Não</option>
            </select>
          </div>
        </div>

        <div class="box-footer" align="center">
          <button class="btn btn-flat btn-default">Gravar</button>
        </div>

      </div>
    </div>
  </div> 
</div>
</form>

@stop