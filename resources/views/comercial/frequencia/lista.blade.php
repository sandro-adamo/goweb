@extends('layout.principal')

@section('title')
<i class="fa fa-line-chart"></i> Frequência por Cliente
@append 

@section('conteudo')


@php
  $representantes = Session::get('representantes');
  $grifes = Session::get('grifes');


  $clientes = \DB::select("select cliente, avg(grifes13) grifes13, avg(grifes18) grifes18,  avg(meses13) meses13, avg(meses18) meses18 from (

select carteira.*, grifes13, meses18, grifes18, meses13 from (
select  cliente, rep
from carteira
left join addressbook ab on ab.id = carteira.cli
where carteira.rep in ($representantes) and grife in $grifes
group by cliente, rep
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

group by cliente");

@endphp


<div class="box box-body box-widget">

  <table class="table table-bordered">
    <tr>
      <th width="60%">Cliente</th>
      <th>Grifes13</th>
      <th>Grifes18</th>
      <th>Freq13</th>
      <th>Freq18</th>
    </tr>

    @foreach ($clientes as $cliente)
      <tr>
        <td>{{$cliente->cliente}}</td>
        <td align="center">{{number_format($cliente->grifes13,1)}}</td>
        <td align="center">
          {{number_format($cliente->grifes18,1)}}
          @if ($cliente->grifes13 == $cliente->grifes18)  @elseif ($cliente->grifes13 < $cliente->grifes18) <i class="fa fa-sort-asc text-green"></i> @else <i class="fa fa-sort-desc text-red"></i> @endif
        </td>
        <td align="center">{{number_format($cliente->meses13,1)}}</td>
        <td align="center">{{number_format($cliente->meses18,1)}}</td>
      </tr>
    @endforeach
  </table>

</div>

@stop