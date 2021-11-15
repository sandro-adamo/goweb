@extends('layout.principal')

@section('title')
<i class="fa fa-suitcase"></i> Pedidos de Mostruários
@append 

@section('conteudo')

<form action="" method="get" class="form-horizontal">

<div class="row">
  <div class="col-md-12">
    <div class="box box-widget box-body">

      <div class="form-group">
        <label class="col-md-2 control-label">Representante</label>
        <div class="col-md-2">
          <input type="text" name="filial" class="form-control" placeholder="Filial" @if (isset($_GET["filial"])) value="{{$_GET["filial"]}}" @endif>
        </div>
        <div class="col-md-2">
          <input type="text" name="razao" class="form-control" placeholder="Razão" @if (isset($_GET["filial"])) value="{{$_GET["razao"]}}" @endif>
        </div>
        <div class="col-md-2">
          <input type="text" name="codigo" class="form-control" placeholder="Código" @if (isset($_GET["filial"])) value="{{$_GET["codigo"]}}" @endif>
        </div>
        <div class="col-md-2">
          <input type="text" name="cnpj" class="form-control" placeholder="CNPJ" @if (isset($_GET["cnpj"])) value="{{$_GET["cnpj"]}}" @endif>
        </div>
      </div>


      <div class="form-group">
        <label class="col-md-2 control-label">Pedidos</label>
        <div class="col-md-2">
          <input type="date" name="ped_inicio" class="form-control" placeholder="Data Inicio" @if (isset($_GET["ped_inicio"])) value="{{$_GET["ped_inicio"]}}" @endif>
        </div>
        <div class="col-md-2">
          <input type="date" name="ped_fim" class="form-control" placeholder="Data Fim" @if (isset($_GET["ped_fim"])) value="{{$_GET["ped_fim"]}}" @endif>
        </div>
        <div class="col-md-2">
          <input type="text" name="num_pedido" class="form-control" placeholder="Nº Pedido" @if (isset($_GET["num_pedido"])) value="{{$_GET["num_pedido"]}}" @endif>
        </div>
      </div>

      <div class="form-group">
        <label class="col-md-2 control-label">NS</label>
        <div class="col-md-2">
          <input type="date" name="ns_inicio" class="form-control" placeholder="Data Inicio" @if (isset($_GET["ns_inicio"])) value="{{$_GET["ns_inicio"]}}" @endif>
        </div>
        <div class="col-md-2">
          <input type="date" name="ns_fim" class="form-control" placeholder="Data Fim" @if (isset($_GET["ns_fim"])) value="{{$_GET["ns_fim"]}}" @endif>
        </div>
        <div class="col-md-2">
          <input type="text" name="num_ns" class="form-control" placeholder="Nº NS" @if (isset($_GET["num_ns"])) value="{{$_GET["num_ns"]}}" @endif>
        </div>
      </div>


      <div class="form-group">
        <label class="col-md-2 control-label">NF</label>
        <div class="col-md-2">
          <input type="date" name="nf_inicio" class="form-control" placeholder="Data Inicio" @if (isset($_GET["nf_inicio"])) value="{{$_GET["nf_inicio"]}}" @endif>
        </div>
        <div class="col-md-2">
          <input type="date" name="nf_fim" class="form-control" placeholder="Data Fim" @if (isset($_GET["nf_fim"])) value="{{$_GET["nf_fim"]}}" @endif>
        </div>
        <div class="col-md-2">
          <input type="text" name="num_nf" class="form-control" placeholder="Nº NF" @if (isset($_GET["num_nf"])) value="{{$_GET["num_nf"]}}" @endif>
        </div>

      </div>



      <div class="form-group">
        <label class="col-md-2 control-label">Status</label>
        <div class="col-md-4">
          <select name="status" class="form-control">
            <option>Pedido Emitido</option>
            <option>Em separação</option>
            <option>Faturado</option>
            <option>Enviado</option>
            <option>Conferência Realizada</option>
            <option>Pendências Recebimento</option>
            <option>Não Conferido</option>
          </select>
        </div>

        <div class="col-md-2">
          <button class="btn btn-default btn-flat"><i class="fa fa-search"></i> Pesquisar</button>
        </div>
      </div>

      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>Representante</th>
            <th>Filial</th>
            <th>Pedido</th>
            <th>Tipo</th>
            <th>Data Pedido</th>
            <th>NS</th>
            <th>NF</th>
            <th>Data NF</th>
            <th># peças</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($pedidos as $pedido)
          <tr>
            <td>{{$pedido->razao}}</td>
            <td align="center">{{$pedido->filial}}</td>
            <td align="center"><a href="/mostruarios/{{$pedido->pedido}}">{{$pedido->pedido}}</a></td>
            <td align="center">{{$pedido->tipo}}</td>
            <td align="center">{{$pedido->dt_pedido}}</td>
            <td align="center">{{$pedido->ns}}</td>
            <td align="center">{{$pedido->nf}}</td>
            <td align="center">{{$pedido->dt_nf}}</td>
            <td align="center">{{$pedido->pecas}}</td>
            <td align="center">{{$pedido->status}}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
</form>
@stop