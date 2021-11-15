@extends('layout.principal')

@section('title')
<i class="fa fa-money"></i> Financeiro
@append 

@section('conteudo')

<div class="row">
  <div class="col-md-12">
    <div class="box box-widget box-body">


      <div class="row">

        <div class="col-md-2">
          <button type="button" data-toggle="modal" data-target="#modalFiltros" class="btn btn-default btn-flat"><i class="fa fa-filter"></i> Filtros</button>
        </div>

      </div>      
      <br>
      

      <div class="table-respo2nsive">

      <table class="table table-striped  table-condensed table-bordered "  id="example3" >
        <thead>
          <tr>
            <th width="5%">Status</th>
            <th width="5%">Titulo</th>
            {{-- <th width="5%">Parcela</th> --}}
            <th width="30%">Cliente</th>
            <th width="12%">Vencimento</th>
            <th width="5%">Pagamento</th>
            <th width="10%">Valor Total Boleto</th>
            <th width="10%">Participacao do Rep</th>
            @if (isset($titulos[0]->tipo_comissao) && $titulos[0]->tipo_comissao <> 'I')
              <th width="10%">Comissão</th>
            @endif

            @if (\Auth::user()->id_perfil == 1)
              <th width="10%"></th>
            @endif
          </tr>
        </thead>
        <tbody>

          @php
            $total_parcelas = 0;
            $total_rep = 0;
          @endphp

          @if (isset($titulos) && count($titulos) > 0)
            @foreach ($titulos as $titulo)

              @php
                $total_parcelas += $titulo->valor_parcela;


                if ($titulo->comissao <> '') {
                  $split_comissao = explode(' ', $titulo->comissao);
                  $ano_comissao = $split_comissao[0];
                  $mes_comissao = $split_comissao[1];

                } else {
                  $ano_comissao = '';
                  $mes_comissao = '';          
                }

                $representante = \Session::get('representantes');
                $listarep = explode(',', $representante);

                $mostra = 0;
                if (isset($listarep) && count($listarep) > 0) {
                  foreach ($listarep as $rep) {
                    if ($titulo->id_rep == $rep) {
                      $mostra = 1;
                      $total_rep += $titulo->valor_parcela_rep;
                    } 
                  }

                }
              @endphp
              <tr>
                <td align="center" 
                    @if ($titulo->situacao == 'Paga') 
                      class="text-green text-bold" 
                    @elseif ($titulo->situacao == 'Vencida') 
                      class="text-orange text-bold" 
                    @else 
                      class="text-purple text-bold" 
                    @endif> {{$titulo->situacao}}
                  </td>
			
                <td align="center">
                  <a href="/financeiro_det?titulo={{$titulo->titulo}}&parcela={{$titulo->parcela}}&tipo={{$titulo->tipo}}&valor_parcela={{$titulo->valor_parcela}}">
{{$titulo->titulo}}/{{$titulo->parcela}}</a>
                </td> 	
                <td>{{$titulo->id_cliente}} - {{$titulo->razao}}</td>
                <td align="center">{{date('d/m/Y', strtotime($titulo->dt_vencimento))}}</td>
                <td align="center">@if ($titulo->dt_pagto <> '') {{date('d/m/Y', strtotime($titulo->dt_pagto))}} @endif</td>
                <td align="right">{{number_format($titulo->valor_parcela, 2,',','.')}}</td>
                <td align="right">@if ($mostra == 1)  {{number_format($titulo->valor_parcela_rep, 2,',','.')}} @endif</td>
                @if (isset($titulos[0]->tipo_comissao) && $titulos[0]->tipo_comissao <> 'I')
                  <td align="center">@if ($titulo->comissao <> '') <a href="/comissoes/{{$ano_comissao}}/{{$mes_comissao}}?fatura={{$titulo->titulo}}&parcela={{$titulo->parcela}}">{{$titulo->comissao}}</a>@endif</td>
                @endif

                @if (\Auth::user()->id_perfil == 1)
                  @if ($titulo->id_conta <> '')
                    <td><a href="/financeiro/{{$titulo->titulo}}/{{$titulo->tipo}}/{{$titulo->parcela}}/boleto" target="_blank">Boleto {{$titulo->banco}}</a></td>
                  @endif
                @endif
                

              </tr>

            @endforeach
          @else
            <tr>
              <td colspan="7" align="center"> Nenhum registro para exibir. Tente fazer uma consulta</td>
            </tr>
          @endif
        </tbody>
        <tfoot>
            <tr>
              <td colspan="5" class="text-bold" align="right"> TOTAL</td>
              <td class="text-bold" align="right">{{number_format($total_parcelas, 2,',','.')}}</td>
              <td class="text-bold" align="right">{{number_format($total_rep, 2,',','.')}}</td>
              @if (isset($titulos[0]->tipo_comissao) && $titulos[0]->tipo_comissao <> 'I')
                <td align="center"></td>
              @endif
            </tr>
        </tfoot>
      </table>
    </div>
    </div>
</div>
</div>




<form action="" method="get" class="form-horizontal">
<div class="modal fade" id="modalFiltros" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-filter"></i> Filtros</h4>
      </div>
      <div class="modal-body">


        <div class="form-group">
          <label class="col-md-2 control-label">Situação</label>
          <div class="col-md-4">
            <select name="status" class="form-control" >
              <option value="">Todos</option>
              <option value="ABERTO" @if (isset($_GET["status"]) && $_GET["status"] == 'ABERTO') selected @endif >Aberto</option>
              <option value="PAGO" @if (isset($_GET["status"]) && $_GET["status"] == 'PAGO') selected @endif>Pago</option>
              <option value="VENCIDO" @if (isset($_GET["status"]) && $_GET["status"] == 'VENCIDO') selected @endif>Vencido</option>
            </select>
          </div>
        </div>

        
        <div class="form-group">
          <label class="col-md-2 control-label">Titulo</label>
          <div class="col-md-4">
            <input type="number" name="titulo" class="form-control" @if (isset($_GET["titulo"])) value="{{$_GET["titulo"]}}" @endif>
          </div>
        </div>      

        <div class="form-group">
          <label class="col-md-2 control-label">Cliente</label>
          <div class="col-md-9">
            <input type="text" name="busca" class="form-control" @if (isset($_GET["busca"])) value="{{$_GET["busca"]}}" @endif>
          </div>
        </div>      
        <div class="form-group">
          <label class="col-md-2 control-label">Vencimento</label>
          <div class="col-md-4">
            <input type="date" name="venc_inicio" class="form-control" @if (isset($_GET["venc_inicio"])) value="{{$_GET["venc_inicio"]}}" @endif>
          </div>
          <label class="col-md-1 control-label">até</label>
          <div class="col-md-4">
            <input type="date" name="venc_fim" class="form-control" @if (isset($_GET["venc_fim"])) value="{{$_GET["venc_fim"]}}" @endif>
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-2 control-label">Pagamento</label>
          <div class="col-md-4">
            <input type="date" name="pagto_inicio" class="form-control" @if (isset($_GET["pagto_inicio"])) value="{{$_GET["pagto_inicio"]}}" @endif>
          </div>
          <label class="col-md-1 control-label">até</label>
          <div class="col-md-4">
            <input type="date" name="pagto_fim" class="form-control" @if (isset($_GET["pagto_fim"])) value="{{$_GET["pagto_fim"]}}" @endif>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary btn-flat">Filtrar</button>
      </div>
    </div>
  </div>
</div>
</form>
@stop