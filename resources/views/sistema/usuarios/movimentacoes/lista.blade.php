@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i> Movimentações
@append 

@section('conteudo')

<div class="row">

@if (Session::has("alert-success"))
  <div class="callout callout-success">{{Session::get('alert-success')}}</div>
@endif
  <div class="col-md-12">
    <div class="box box-widget box-body">
      <div class="row">
        <div class="col-md-6">
       
        <span class="pull-left"><a href="/usuarios/movimentacoes/nova" class="btn btn-success btn-flat"><i ></i> Nova movimentação</a>
        <div class="col-md-4">
         
        </div>
      </div>
      <br>
      
        <table class="table table-bordered table-striped" id="myTable">
        <thead>
          <tr>
			      <th>Id Movimentação</th>
            <th>Tipo</th>
            <th>Origem</th>
            <th>Destino</th>
            <th>Grife</th>
            <th>Ultima obs</th>
            <th>Dt solicitação</th>
            <th>Dt atualização</th>
            <th>Status</th>
            <th>Responsável</th>

          </tr>
        </thead>
        <tbody>
          @foreach ($movimentacoes as $movimentacao)
          <tr>
            <td><span class="pull-right"><a href="/usuarios/movimentacoes/historico/{{$movimentacao->id_movimentacao}}" ><i ></i> {{$movimentacao->id_movimentacao}}</a>
			     </td>
            <td>{{$movimentacao->tipo}}</td>
            <td>{{$movimentacao->id_origem.' - '.$movimentacao->nome_origem}}</td>
            <td>{{$movimentacao->id_destino.' - '.$movimentacao->nome_destino}}</td>
            <td>{{$movimentacao->codgrife}}</td>
            <td>{{$movimentacao->obs}}</td>
            <td>{{$movimentacao->dt_created}}</td>
            <td>{{$movimentacao->dt_updated}}</td>
            <td>{{$movimentacao->status}}</td>
            <td>{{$movimentacao->responsavel}}</td>


          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    
  </div>
</div>
@stop
