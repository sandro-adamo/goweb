@extends('layout.principal')

@section('title')
<i class="fa fa-list"></i> Detalhes do Pedido
@append 

@section('conteudo')

<form action="/perfis/grava" method="post" class="form-horizontal">
@csrf
<div class="row">
  <div class="col-md-9">


    <div class="box box-widget">
      <div class="box-body">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th> Item</th>
              <th> Quantidade</th>
              <th> Valor</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($itens as $item)

            <tr @if ($item->ult_status == '510' and $item->prox_status == '515') class="bg-warning" @endif>
              <td>{{$item->item}}</td>
              <td align="center">{{$item->qtde}}</td>
              <td align="right">{{number_format($item->valor,2,',','.')}}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
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
            {{$itens[0]->pedido}}
          </div>
        </div>

        <div class="box-footer" align="center">
        </div>

      </div>
    </div>
  </div> 
</div>
</form>

@stop