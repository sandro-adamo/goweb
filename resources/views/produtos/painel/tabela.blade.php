@extends('produtos/painel/index')

@section('title')
<i class="fa fa-group"></i> Tabela
@append 

@section('conteudo')



@php

  $modelos = \DB::select("select modelo
from (

select itens.*,  
vendas.ult_30dd, vendas.a_180dd, vendas.vendastt, saldos.br, saldos.cet, saldos.etq, saldos.cep
from (
select modelo, colmod, clasmod, valortabela, ultcusto, mediacusto, secundario, statusatual
from itens ) as itens


left join (
select secundario, sum(ult_30dd) ult_30dd, sum(a_180dd) a_180dd, sum(vendastt) vendastt from vendas_sint group by secundario
) as vendas
on vendas.secundario = itens.secundario


left join (
select secundario, (sum(disp_vendas)+sum(conf_montado)+sum(em_montagem)+sum(em_beneficiamento)) as br, sum(cet_mont) cet, sum(etq) etq, sum(cep) cep
from saldos group by secundario
) as saldos
on saldos.secundario = itens.secundario

) as sele2

where modelo = 'ah6254'

group by modelo ");


@endphp
<div class="box box-body box-widget">
<table class="table table-bordered"> 

@foreach ($modelos as $modelo)

  @php


    $status = \DB::select("select modelo, colmod, clasmod, valortabela, avg(ultcusto) ultcusto, avg(mediacusto) mdcusto, statusatual, 
count(secundario) as itens, sum(ifnull(ult_30dd,0)) ult30dd, sum(ifnull(a_180dd,0)) a_180dd, sum(ifnull(vendastt,0)) vendastt,
sum(ifnull(br,0)) br, sum(ifnull(cet,0)) cet, sum(ifnull(etq,0)) etq, sum(ifnull(cep,0)) cep
from (

select itens.*,  
vendas.ult_30dd, vendas.a_180dd, vendas.vendastt, saldos.br, saldos.cet, saldos.etq, saldos.cep
from (
select modelo, colmod, clasmod, valortabela, ultcusto, mediacusto, secundario, statusatual
from itens ) as itens


left join (
select secundario, sum(ult_30dd) ult_30dd, sum(a_180dd) a_180dd, sum(vendastt) vendastt from vendas_sint group by secundario
) as vendas
on vendas.secundario = itens.secundario


left join (
select secundario, (sum(disp_vendas)+sum(conf_montado)+sum(em_montagem)+sum(em_beneficiamento)) as br, sum(cet_mont) cet, sum(etq) etq, sum(cep) cep
from saldos group by secundario
) as saldos
on saldos.secundario = itens.secundario

) as sele2

where modelo = 'ah6254'

group by modelo, colmod, clasmod, valortabela,  statusatual");

    $totalclas1 = 0;
    $totalclas2 = 0;
    $totalclas3 = 0;
    $totalclas4 = 0;
    $totalclas5 = 0;


    $totalbr = 0;
    $totalcet = 0;
    $totaletq = 0;
    $totalcep = 0;

    $totalv30dd = 0;
    $totalv180dd = 0;
    $totalvenda = 0;


  @endphp

  <thead>
    <tr>
      <th> MODELO </th>
      <th> {{$modelo->modelo}} </th>
      <th style="text-align: center;"> ITENS </th>
      <th style="text-align: center;"> ESTOQUES </th>
      <th style="text-align: center;"> VENDAS </th>
    </tr>
  </thead>


  <tr>
    <td width="10%"><img src="https://portal.goeyewear.com.br/teste999.php?referencia={{$modelo->modelo}}" class="img-responsive" width="130"></td>
    <td>
      {{$status[0]->colmod}}<br>
      {{$status[0]->clasmod}}<br>
      {{$status[0]->valortabela}}<br>
      {{$status[0]->ultcusto}}<br>
    </td>


    <td>
      <table width="100%">
        <thead>
          <tr>
            <th></th>
            <th style="text-align: center;">A++</th>
            <th style="text-align: center;">A+</th>
            <th style="text-align: center;">A</th>
            <th style="text-align: center;">A-</th>
            <th style="text-align: center;">OTH</th>
          </tr>
        </thead>
        <tbody>
        @foreach ($status as $linha)

          @php
            $totalclas1 += 0;
            $totalclas2 += 0;
            $totalclas3 += 0;
            $totalclas4 += 0;
            $totalclas5 += 0;
          @endphp


          <tr>
            <td>{{$linha->statusatual}}</td>
            <td align="center">{{$linha->br}}</td>
            <td align="center">{{$linha->cet}}</td>
            <td align="center">{{$linha->etq}}</td>
            <td align="center">{{$linha->cep}}</td>
            <td align="center">{{$linha->cep}}</td>
          </tr>
        @endforeach
        </tbody>
        <tfoot>
          <tr>
            <th>TOTAL</th>
            <th style="text-align: center;">{{$totalclas1}}</th>
            <th style="text-align: center;">{{$totalclas2}}</th>
            <th style="text-align: center;">{{$totalclas3}}</th>
            <th style="text-align: center;">{{$totalclas4}}</th>
            <th style="text-align: center;">{{$totalclas5}}</th>
          </tr>
        </tfoot>
      </table>
    </td>

    <td>
      <table width="100%">
        <thead>
          <tr>
            <th style="text-align: center;">BR</th>
            <th style="text-align: center;">CET</th>
            <th style="text-align: center;">ETQ</th>
            <th style="text-align: center;">CEP</th>
          </tr>
        </thead>
        <tbody>
        @foreach ($status as $linha)

          @php
            $totalbr += $linha->br;
            $totalcet += $linha->cet;
            $totaletq += $linha->etq;
            $totalcep += $linha->cep;
          @endphp

          <tr>
            <td align="center">{{$linha->br}}</td>
            <td align="center">{{$linha->cet}}</td>
            <td align="center">{{$linha->etq}}</td>
            <td align="center">{{$linha->cep}}</td>
          </tr>
        @endforeach
        </tbody>
        <tfoot>
          <tr>
            <th style="text-align: center;">{{$totalbr}}</th>
            <th style="text-align: center;">{{$totalcet}}</th>
            <th style="text-align: center;">{{$totaletq}}</th>
            <th style="text-align: center;">{{$totalcep}}</th>
          </tr>
        </tfoot>
      </table>
    </td>

    <td>

      <table width="100%">
        <thead>
          <tr>
            <th style="text-align: center;">30dd</th>
            <th style="text-align: center;">180dd</th>
            <th style="text-align: center;">TOTAL</th>
          </tr>
        </thead>
        <tbody>
        @foreach ($status as $linha)

          @php
            $totalv30dd += $linha->ult30dd;
            $totalv180dd += $linha->a_180dd;
            $totalvenda += $linha->vendastt;
          @endphp


          <tr>
            <td align="center">{{$linha->ult30dd}}</td>
            <td align="center">{{$linha->a_180dd}}</td>
            <td align="center">{{$linha->vendastt}}</td>
          </tr>
        @endforeach
        </tbody>
        <tfoot>
          <tr>
            <th style="text-align: center;">{{$totalv30dd}}</th>
            <th style="text-align: center;">{{$totalv180dd}}</th>
            <th style="text-align: center;">{{$totalvenda}}</th>
          </tr>
        </tfoot>

      </table>      

    </td>
  </tr>
@endforeach
</table>
</div>
@stop