@extends('layout.principal')

@section('title')
<i class="fa fa-shopping-cart"></i> Detalhes da Nota Fiscal
@append 

@section('conteudo')


<div class="row">
  <div class="col-md-9">

    <div class="box box-widget">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-user"></i> Cliente</h3>
        </div>
        <div class="box-body">      
          <span class="lead">{{$capa[0]->id_cliente}} - {{$capa[0]->razao}} - {{$capa[0]->subgrupo}}</span>
        </div>
		
    </div>


    @if ($comissoes && count($comissoes) > 0)
    <div class="box box-widget">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-money"></i> Comissões</h3>
        </div>
        <div class="box-body">  
          <table class="table table-bordered">    
            <thead>
              <tr>
                <th>Data Nota Fiscal</th>
                <th>Nota Fiscal</th>
                <th>Valor Nota</th>
                <th>Mês/Ano Comissão</th>
                <th>Valor Comissão</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($comissoes as $comissao)
                <tr>
                  <td width="20%" align="center">{{date('d/m/Y', strtotime($comissao->data_nota))}}</td>
                  <td width="20%" align="center">{{$comissao->nota}}</td>
                  <td width="20%" align="right">{{number_format($comissao->valor_nota,2,',','.')}}</td>
                  <td width="20%" align="center"><a href="/comissoes/{{$comissao->ano}}/{{$comissao->periodo}}?nota={{$comissao->nota}}">{{$comissao->periodo}} {{$comissao->ano}}</a> </td>
                  <td width="20%" align="right">{{number_format($comissao->valor_comissao,2,',','.')}}</td>
                </tr>
              @endforeach 
            </tbody>
          </table>
        </div>
    </div>
    @endif

    <div class="box box-widget">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-list"></i> Itens</h3>
        </div>
        <div class="box-body">      
          <table class="table table-bordered" id="example3">
            <thead>
              <tr>
                <th width="1%"></th>
                <th width="15%">Status</th>
              <th width="20%">Pedido</th>
                <th width="40%">Item</th>
                <th width="5%">Quantidade</th>
                <th width="15%">Unitário</th>
                <th width="15%">Total</th>

              </tr>
            </thead>
            <tbody>
              @foreach ($itens as $item)
              <tr>
                <td align="center">{{$item->linha}}</td>
                <td align="center">{{$item->status}}</td>
                <td align="center"><a href="/vendas/{{$item->pedido}}">{{$item->pedido}}</a></td>
                <td align="left">{{$item->item}}</td>
                <td align="center">{{$item->qtde}}</td>
                <td align="right">{{number_format($item->unitario,2,',','.')}}</td>
                <td align="right">{{number_format($item->total,2,',','.')}}</td>


              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
    </div>


    <div class="box box-widget">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-barcode"></i> Faturas</h3>
        </div>
        <div class="box-body">      
          <table class="table table-bordered">
            <thead>
              <tr>
                <th width="10%">Status</th>
                <th width="10%">Titulo</th>
                <th width="10%">Parcela</th>
                <th width="20%">Vencimento</th>
                <th width="20%">Pagamento</th>
                <th width="15%">Valor Parcela</th>
                <th width="15%">Valor Pago</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($titulos as $titulo)
              <tr>
                <td align="center">{{$titulo->status}}</td>
                <td align="center">{{$titulo->titulo}}</td>
                <td align="center">{{$titulo->parcela}}</td>
                <td align="center">{{$titulo->dt_vencimento}}</td>
                <td align="center">{{$titulo->dt_pagto}}</td>
                <td align="right">{{number_format($titulo->valor_parcela,2,',','.')}}</td>
                <td align="right">{{number_format($titulo->valor_pago,2,',','.')}}</td>


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
          <div class="row">
            <div class="col-md-12">
              <small>Pedido</small>
              <h3 class="pull-right">@if ($capa[0]->nf_legal) {{$capa[0]->nf_legal}} @endif</h3>
            </div>
            <div class="col-md-12">
              <small>Data Venda</small>
              <h3 class="pull-right">@if ($capa[0]->dt_emissao) {{date('d/m/Y', strtotime($capa[0]->dt_emissao))}} @endif</h3>
            </div>
          </div>
        </div>
      </div>
      
      <div class="box box-widget">  
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-file-o"></i> Resumo da Venda</h3>
        </div>
        <div class="box-body">

          <div class="row">
            <div class="col-md-12">
              <small>Valor vendido</small>
              <h3 class="pull-right">@if (isset($capa[0]->venda)) {{number_format($capa[0]->venda,2,',','.')}} @endif</h3>
            </div>
            <div class="col-md-12">
              <small>Valor faturado</small>
              <h3 class="pull-right">@if (isset($capa[0]->faturado)) {{number_format($capa[0]->faturado,2,',','.')}} @endif</h3>
            </div>
            <div class="col-md-12">
              <small>Valor orçamento</small>
              <h3 class="pull-right">@if (isset($capa[0]->orcamento)) {{number_format($capa[0]->orcamento,2,',','.')}} @endif</h3>
            </div>
            <div class="col-md-12">
              <small>Valor recebido</small>
              <h3 class="pull-right">-</h3>
            </div>
            <div class="col-md-12">
              <small>Valor atraso</small>
              <h3 class="pull-right">-</h3>
            </div>
        </div>
      </div>
    </div>
      
      <div class="box box-widget">  
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-truck"></i> Transporte</h3>
        </div>
        <div class="box-body">

          <table class="table table-bordered">
            <tr>
              <td><label>Transportadora:</label></td>
              <td>{{$capa[0]->transportadora}}</td>
            </tr>

            @if (isset($rastreio[0]->rastreio))
            <tr>
              <td><label>Rastreio:</label></td>
              <td>{{$rastreio[0]->rastreio}}</td>
            </tr>
            <tr>
              <td><label>Data:</label></td>
              <td>{{$rastreio[0]->data}}</td>
            </tr>
            <tr>
              <td><label>Evento:</label></td>
              <td>{{$rastreio[0]->status}}</td>
            </tr>
            <tr>
              <td><label>Entregue:</label></td>
              <td>{{$rastreio[0]->entregue}}</td>
            </tr>
            @endif
          </table>

        </div>
      </div>

    </div>
</div>
@stop