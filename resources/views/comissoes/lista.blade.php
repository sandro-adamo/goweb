@extends('layout.principal')

@section('title')
<i class="fa fa-money"></i> Comissões
@append 

@section('conteudo')


<div class="row">
  <div class="col-md-12">
    <div class="box box-widget box-body">
      <div class="row">
        <div class="col-md-6">
          <input type="text" name="pesquisa" class="form-control">
        </div>
        <div class="col-md-2">
          <button class="btn btn-default btn-flat"><i class="fa fa-search"></i> Pesquisar</button>
        </div>
        <div class="col-md-4">
        </div>
      </div>      
      <br>
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th width="5%">Periodo</th>
            <th width="20%">Representante</th>
            <th width="12%">Valor NF</th>
            <th width="12%">IRPJ</th>
            <th width="12%">Descontos</th>
            <th width="15%">Líquido</th>
            <th width="15%">Envio Nota Fiscal</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($comissao as $mes)
          <tr>
            <td align="center"><a href="/comissoes/{{$mes->ano}}/{{$mes->periodo}}">{{$mes->ano}}-{{$mes->periodo}}</a></td>
            <td>{{$mes->razao}}</td>
            <td align="right">{{number_format($mes->valor_nf,2,',','.')}}</td>
            <td align="right">{{number_format($mes->valor_irpj,2,',','.')}}</td>
            <td align="right">{{number_format($mes->valor_descontos,2,',','.')}}</td>
            <td align="right">{{number_format($mes->valor_liquido,2,',','.')}}</td>
            <td align="center">

              @if ($mes->ano == 2020 or $mes->ano == 2021)
                @if ($mes->notafiscal <> '')
                  <span class="text-green text-bold">Enviada</span>
                @else
                  @if ($mes->valor_nf > 0)
                    <span class="text-orange text-bold">Pendente</span>
                  @else 
                    <span class="text-blue text-bold">NF não exigida</span>
                  @endif
                @endif
              @endif 
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
</div>
@stop