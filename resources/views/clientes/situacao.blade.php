@extends('layout.principal')

@section('title')
<i class="fa fa-money"></i> Clientes
@append 

@section('conteudo')

<div class="row">
  <div class="col-md-12">
    <div class="box box-widget box-body">

      <table class="table table-bordered">
        <tr>
          <th width="20%">Novo</th>
          <th width="20%">Novo</th>
          <th width="20%">Novo</th>
          <th width="20%">Novo</th>
          <th width="20%">Novo</th>
        </tr>

        @foreach ($clientes as $cliente)

        <tr>
          <td>
            <div class="row">
              <div class="col-md-12">
                {{$cliente->razao}}
              </div>
            </div>
          </td>

        </tr>
        @endforeach
      </table>

@stop