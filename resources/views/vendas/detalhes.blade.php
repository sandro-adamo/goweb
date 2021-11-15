@extends('layout.principal')

@section('title')
<i class="fa fa-shopping-cart"></i> Detalhes da Venda
@append 

@section('conteudo')


<div class="row">
  <div class="col-md-9">


    @if ($suspensoes && count($suspensoes) > 0)

      <div class="callout callout-warning">
        <p class="lead">Pedido Suspenso</p>
        <ul>

        @foreach ($suspensoes as $suspensao)

          <li>{{$suspensao->suspensao}}</li>
        @endforeach
        </ul>
      </div>
    @endif


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
                <th width="10%">Status</th>
                <th width="10%">SO</th>
                <th width="15%">NF</th>
				        <th width="20%">Representante</th>  
                <th width="25%">Item</th>
                <th width="5%">Quantidade</th>
                <th width="10%">Valor</th>

              </tr>
            </thead>
            <tbody>
              @foreach ($itens as $item)
              <tr>
                <td align="center">{{$item->linha/1000}}</td>
                <td align="center">{{$item->status}}</td>

                @if ($item->status == 'Cancelado')

                  <td align="center" colspan="2">{{$item->motivo}}</td>

                @else 

                  <td align="center">{{$item->pedido_so}}</td>
                  <td align="center"><a href="/notas/{{$item->nf_legal}}">{{$item->nf_legal}}</a></td>

                @endif

				        <td align="center">{{$item->repres}}</td>
                <td align="left">{{$item->item}}</td>
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
          <div class="row">
            <div class="col-md-12">
              <small>Pedido</small>
              <h3 class="pull-right">@if ($capa[0]->pedido) {{$capa[0]->pedido}} @endif</h3>
            </div>
            <div class="col-md-12">
              <small>Data Venda</small>
              <h3 class="pull-right">@if ($capa[0]->dt_venda) {{date('d/m/Y', strtotime($capa[0]->dt_venda))}} @endif</h3>
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
              <h3 class="pull-right">@if ($capa[0]->venda) {{number_format($capa[0]->venda,2,',','.')}} @endif</h3>
            </div>
            <div class="col-md-12">
              <small>Valor faturado</small>
              <h3 class="pull-right">@if ($capa[0]->faturado) {{number_format($capa[0]->faturado,2,',','.')}} @endif</h3>
            </div>
            <div class="col-md-12">
              <small>Valor orçamento</small>
              <h3 class="pull-right">@if ($capa[0]->orcamento) {{number_format($capa[0]->orcamento,2,',','.')}} @endif</h3>
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
</div>
@stop