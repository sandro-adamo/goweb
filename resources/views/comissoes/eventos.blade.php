@extends('layout.principal')

@section('title')
<i class="fa fa-money"></i> Detalhes da Comissão 
@append 

@section('conteudo')

@php
  $total_valor = 0;
  $total_comissao = 0;
  $id_rep = \Auth::user()->id_addressbook;
  $representante = \Session::get('representantes');

  $descontos = \DB::select("select ifnull(sum(valor),0) as descontos from descontos where id_rep in ($representante) and ano = $ano and periodo = $periodo and tipo  in ('Adiantamento','Leitor', 'Antecipação') ");
  $impostos = \DB::select("select ifnull(sum(valor),0) as impostos from descontos where id_rep in ($representante) and ano = $ano and periodo = $periodo and tipo in ('Imposto') ");

  foreach ($comissao as $mes) {
    $total_valor += $mes->valor;
    $total_comissao += $mes->comissao;    
  }
@endphp

@if (Session::has("alert-success"))
  <div class="callout callout-success">{{Session::get('alert-success')}}</div>
@endif

<div class="row">
  <div class="col-md-8">

    <div class="box box-body box-widget">
      <div class="row">
        <div class="col-md-2">
          <label>Ano:</label>{{$ano}}
        </div>
        <div class="col-md-2">
          <label>Mês:</label>{{$periodo}}
        </div>


      </div>

    </div>

    @if (isset($comissao[0]->tipo_comissao) && trim($comissao[0]->tipo_comissao) == 'I')     
    <div class="box box-body box-widget">
      <table class="table table-bordered">
        <tr>
          <td align="right"><label class="pull-left">Faturamento no mês</label><br><a href="?tipo=Faturamento"><h4>{{number_format($resumo[0]->Faturamento,2,',','.')}}</h4></a></td>
          <td align="right"><label class="pull-left">Devoluções no mês</label><br><a href="?tipo=Devolucao"><h4>{{number_format($resumo[0]->Devolucao,2,',','.')}}</h4></a></td>
          <td align="right"><label class="pull-left">Inadimplências</label><br><a href="?tipo=Inadimplencia"><h4>{{number_format($resumo[0]->Inadimplencia,2,',','.')}}</h4></a></td>
          <td align="right"><label class="pull-left">Estorno Inadimplência</label><br><a href="?tipo=Estorno"><h4>{{number_format($resumo[0]->Estorno,2,',','.')}}</h4></a></td>
        </tr>
      </table>
    </div>
    @endif 

  </div>
  <div class="col-md-4">
    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title">Resumo</h3>
      </div>
      <div class="box-body">
        <table class="table table-bordered">
          <tr>
            <td width="80%" class="text-bold">Valor da Nota Fiscal</td>
            <td width="20%" class="text-bold" align="right">{{number_format($total_comissao,2,',','.')}}</td>
          </tr>
          <tr>
            <td width="80%"><a href="" data-toggle="modal" data-target="#modalDescontos"> Descontos</a></td>
            <td width="20%" align="right">@if (isset($descontos[0]->descontos)) {{number_format($descontos[0]->descontos,2,',','.')}} @endif</td>
          </tr>
          <tr>
            <td width="80%">IRPJ</td>
            <td width="20%" align="right">@if (isset($impostos[0]->impostos)) {{number_format($impostos[0]->impostos,2,',','.')}} @endif</td>
          </tr>
          <tr>
            <td width="80%">Valor Liquido a Receber</td>
            <td width="20%" align="right">{{ number_format(($total_comissao-$descontos[0]->descontos-$impostos[0]->impostos),2,',', '.') }} </td>
          </tr>
        </table>
      </div>
      <div class="box-footer">

        @if ($comissao[0]->notafiscal == '')
          <a href="" class="btn btn-flat btn-primary pull-right" data-toggle="modal" data-target="#modalNotaFiscal"><i class="fa fa-upload"></i> Enviar Nota Fiscal</a>
        @else 
          Nota fiscal enviada
        @endif
      </div>
  </div>
  </div>
</div>

<div class="row">

  <div class="col-md-12">
    <div class="table-responsive">
    <div class="box box-widget box-body">
      <a href="/exportaComissoesFat/{{$ano}}/{{$periodo}}" class="btn btn-flat btn-sm btn-default pull-right"><i class="fa fa-file-o"></i> Exportar Excel</a>    
      <br><br>
      <table class="table table-striped table-bordered" id="example3">
        <thead>
          <tr>
            {{-- <th>Periodo</th> --}}
            <th>Cliente</th>
            <th>Data Fatura</th>
            {{-- <th>Data Vencimento</th> --}}
            <th>Data Pagamento</th>
            <th>Nota</th>
            <th>Fatura</th>
            {{-- <th>Parcela</th> --}}
            <th>Valor</th>
            <th>%</th>
            <th>Comissão</th>
            <th>Descrição</th>
			  <th>Observacoes</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($comissao as $mes)


          <tr>
            {{-- <td align="center">{{$mes->referencia}}</td> --}}
            <td>{{$mes->razao}}</td>
            <td align="center">@if ($mes->dt_fatura <> '') {{date('d/m/Y', strtotime($mes->dt_fatura))}} @endif</td>
            {{-- <td align="center">@if ($mes->dt_vencimento <> '') {{date('d/m/Y', strtotime($mes->dt_vencimento))}} @endif</td> --}}
            <td align="center">@if ($mes->dt_pagamento <> '') {{date('d/m/Y', strtotime($mes->dt_pagamento))}} @endif</td>
            <td align="center"><a href="">{{$mes->nota}}</a></td>
            <td align="center"><a href="/financeiro?titulo={{$mes->fatura}}">{{$mes->fatura}} @if ($mes->parcela <> '') - {{$mes->parcela}} @endif</a></td>
            {{-- <td align="center">{{$mes->parcela}}</td> --}}
            <td align="right">{{number_format($mes->valor,2,',','.')}}</td>
            <td align="center">{{$mes->percentual}}</td>
            <td align="right">{{number_format($mes->comissao,2,',','.')}}</td>
            <td>{{$mes->processo}}</td>
			<td><a href=>{{$mes->observacoes}}</td></a>
          </tr>
          @endforeach
        </tbody>
        <tr>
          <td colspan="5"></td>
          <td align="right" class="text-bold">{{number_format($total_valor,2,',','.')}}</td>
          <td align="center" class="text-bold">@if ($total_comissao > 0) {{number_format((($total_comissao/$total_valor)*100),2)}} @endif</td>
          <td align="right" class="text-bold">{{number_format($total_comissao,2,',','.')}}</td>
          <td></td>
        </tr>
      </table>
      </div>
    </div>
</div>

@php

  $descontos = \DB::select("select * from descontos where id_rep = $id_rep and ano = $ano and periodo = $periodo and tipo  in ('Adiantamento','Leitor', 'Antecipação')");
@endphp

<form action="/comissoes/nf/upload" method="post" class="form-horizontal" enctype="multipart/form-data">
  @csrf
<div class="modal fade" id="modalNotaFiscal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Enviar Nota Fiscal</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="ano" value="{{$ano}}">
        <input type="hidden" name="periodo" value="{{$periodo}}">
        <div class="form-group">
          <label class="col-md-2 control-label">DANFE</label>
          <div class="col-md-6">
            <input type="file" required="" name="arquivo" class="form-control">
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-success btn-flat"><i class="fa fa-upload"></i> Enviar</button>
      </div>
    </div>
  </div>
</div>
</form>

<div class="modal fade" id="modalDescontos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Descontos</h4>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <thead>
            <tr>
                <th>Descrição</th>
                <th>Valor</th>
            </tr>
          </thead>
          <tbody>
          @foreach ($descontos as $desconto)
            <tr>
              <td width="75%">{{$desconto->descricao}}</td>
              <td align="right">{{number_format($desconto->valor,2,',','.')}}</td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>
@stop