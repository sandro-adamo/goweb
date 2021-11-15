@extends('layout.principal')

@section('title')
<i class="fa fa-line-chart"></i> Frequência por Representante
@append 

@section('conteudo')


@php
  $representantes = Session::get('representantes');
  $grifes = Session::get('grifes');

  if (isset($_GET["supervisor"])) {


    $sql = ' ar.id_supervisor = ' . $_GET["supervisor"];


  } else {

    $sql = "carteira.rep in ($representantes)";

  }


  $representantes = \DB::select("select rep, fantasia, avg(grifes13) grifes13, avg(grifes18) grifes18,  avg(meses13) meses13, avg(meses18) meses18 from (

select carteira.*, grifes13, meses18, grifes18, meses13 from (
select  ab.cliente, rep, ar.fantasia
from carteira
left join addressbook ab on ab.id = carteira.cli
left join addressbook ar on ar.id = carteira.rep
where $sql and grife in $grifes
group by ab.cliente, rep, ar.fantasia
) as carteira


left join (
select  cliente, count(grife_jde) grifes13 from (
select grife_jde, cliente
from vendas_13meses v13
left join addressbook ab on ab.id = v13.cli_jde
where grife_jde in $grifes
group by grife_jde, cliente
) as sele1
group by cliente
) as vda13
on vda13.cliente = carteira.cliente 

left join (
select  cliente, count(grife_jde) grifes18 from (
select grife_jde, cliente
from vendas_2018 v18
left join addressbook ab on ab.id = v18.cli_jde
where grife_jde in $grifes
group by grife_jde, cliente
) as sele1
group by cliente
) as vda18
on vda18.cliente = carteira.cliente 


left join (
select cliente, avg(meses) meses13 from (
select cliente, grife_jde, count(mes) meses from ( 
select cliente, mes, grife_jde
from vendas_13meses v13 
left join addressbook ab on ab.id = v13.cli_jde
where grife_jde in $grifes 
group by cliente, mes, grife_jde  ) as sele1
group by cliente, grife_jde ) as sele2 
group by cliente ) as freq13
on freq13.cliente = carteira.cliente 


left join (
select cliente, avg(meses) meses18 from (
select cliente, grife_jde, count(mes) meses from ( 
select cliente, mes, grife_jde
from vendas_2018 v18 
left join addressbook ab on ab.id = v18.cli_jde
where grife_jde in $grifes
group by cliente, mes, grife_jde  ) as sele1
group by cliente, grife_jde ) as sele2 
group by cliente  ) as freq18
on freq18.cliente = carteira.cliente 

) as fim

group by rep, fantasia");


@endphp

{{count($representantes)}}
<div class="box box-body box-widget">

  @if (\Auth::user()->id_perfil == 5) 
  <form action="" method="get"> 
    @php

      $id_diretor = \Auth::user()->id_addressbook;

      $supervisores = \DB::select("select a1.id_supervisor, (select razao from addressbook a2 where a2.id = a1.id_supervisor limit 1) as razao
  from addressbook a1
  where  a1.id_supervisor <> 0 and  a1.id_diretor = $id_diretor
  group by a1.id_supervisor")

    @endphp
    <div class="row">
      <div class="col-md-4">
        <select name="supervisor" class="form-control">
          @foreach($supervisores as $supervisor)
            <option value="{{$supervisor->id_supervisor}}" @if (isset($_GET["supervisor"]) && $_GET["supervisor"] == $supervisor->id_supervisor) selected=" " @endif>{{$supervisor->razao}}</option>
          @endforeach 
        </select>
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-flat btn-default" >Pesquisar</button>
      </div>
    </div>

  </form>
  @endif

  <br>
  <table class="table table-bordered">
    <tr>
      <th width="60%">Representante</th>
      <th colspan="2"></th>
      <th>Grifes18</th>
      <th>Grifes13</th>
      <th>Freq13</th>
      <th>Freq18</th>
    </tr>

    @foreach ($representantes as $representante)
      <tr>
        <td>{{$representante->fantasia}}</td>
        <td align="center"><a href="/comercial/frequencia/clientes?representante={{$representante->rep}}"> <i class="fa fa-group"></i></a></td>
        <td align="center"><a href="/comercial/frequencia/grifes?supervisor={{$representante->rep}}"> <i class="fa fa-tag"></i></a></td>
        <td align="center">{{number_format($representante->grifes13,2)}}</td>
        <td align="center">{{number_format($representante->grifes18,2)}}@if ($representante->grifes13 == $representante->grifes18)  @elseif ($representante->grifes13 < $representante->grifes18) <i class="fa fa-sort-asc text-green"></i> @else <i class="fa fa-sort-desc text-red"></i> @endif</td>
        <td align="center">{{number_format($representante->meses13,2)}}</td>
        <td align="center">{{number_format($representante->meses18,2)}}</td>
      </tr>
    @endforeach
  </table>

</div>

@stop