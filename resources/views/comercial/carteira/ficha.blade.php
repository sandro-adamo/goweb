@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i> {{utf8_encode($cliente)}}
@append 

@section('conteudo')


@php

  $grifes_cli = \DB::select("select * from (
  select distinct cliente, cli, uf, municipio, bairro, '' status_fin
    from carteira cart
      left join addressbook ab on ab.id = cart.cli
  where cliente = '$cliente'
  ) as base


left join (
  select cli_jde, sum(AH) AH, SUM(AT) AT, SUM(BG) BG, SUM(EV) EV, SUM(JO) JO, SUM(HI) HI, SUM(SP) SP, SUM(TC) TC from (    
    select cli_jde, 
        case when grife_jde = 'AH' and qtde > 0 then '1' else 0 end as AH,
                case when grife_jde = 'AT' and qtde > 0 then '1' else 0 end as AT,
                case when grife_jde = 'BG' and qtde > 0 then '1' else 0 end as BG,
                case when (grife_jde = 'EV' or grife_jde = 'NG') and qtde > 0 then '1' else 0 end as EV,
                case when grife_jde = 'JO' and qtde > 0 then '1' else 0 end as JO,
                case when grife_jde = 'HI' and qtde > 0 then '1' else 0 end as HI,
                case when grife_jde = 'SP' and qtde > 0 then '1' else 0 end as SP,
                case when grife_jde = 'TC' and qtde > 0 then '1' else 0 end as TC
                
      from(
            select cli_jde, grife_jde, sum(qtde) qtde from (
      select cli_jde, grife_jde, sum(qtde) qtde from vendas_2017 where grife_jde in ('AH','AT','BG','EV','NG','JO','HI','SP','TC') group by cli_jde, grife_jde
            UNION ALL
            select cli_jde, grife_jde, sum(qtde) qtde from vendas_2018  where grife_jde in ('AH','AT','BG','EV','NG','JO','HI','SP','TC') group by cli_jde, grife_jde
            UNION ALL
            select cli_jde, grife_jde, sum(qtde) qtde from vendas_12meses where grife_jde in ('AH','AT','BG','EV','NG','JO','HI','SP','TC') group by cli_jde, grife_jde
            ) as fim group by cli_jde, grife_jde 

        ) as sele1
  ) as sele2 group by cli_jde

) as vds
on vds.cli_jde = base.cli ");


  $qtde_representante = \DB::select("  select  ( select rep from carteira  left join addressbook ab on ab.id = cli  
        where ab.cliente = sele2.cliente and sele2.grife_jde = carteira.grife limit 1 ) repres,
    
    grife_jde, cliente, sum(a) 'a2017', SUM(b) 'a2018', SUM(c) 'a12m' from (    
    select grife_jde, cliente,
        case when tipo = '2017' and qtde > 0 then qtde else 0 end as a,
                case when tipo = '2018' and qtde > 0 then qtde else 0 end as b,
                case when tipo = '12m' and qtde > 0 then qtde else 0 end as c                
                
      from(
        select grife_jde, tipo, cliente, sum(qtde) qtde from (
          select '2017' tipo, cliente, grife_jde, sum(qtde) qtde from vendas_2017 v17 left join addressbook ab on ab.id = v17.cli_jde where grife_jde in ('AH','AT','BG','EV','NG','JO','HI','SP','TC') group by tipo, cliente, grife_jde
          UNION ALL
          select '2018' tipo, cliente, grife_jde, sum(qtde) qtde from vendas_2018  v18 left join addressbook ab on ab.id = v18.cli_jde where grife_jde in ('AH','AT','BG','EV','NG','JO','HI','SP','TC') group by tipo, cliente, grife_jde
          UNION ALL
          select '12m' tipo, cliente, grife_jde, sum(qtde) qtde from vendas_12meses v12m left join addressbook ab on ab.id = v12m.cli_jde where grife_jde in ('AH','AT','BG','EV','NG','JO','HI','SP','TC') group by tipo, cliente, grife_jde
        ) as fim group by grife_jde, cliente, tipo

        ) as sele1 
  ) as sele2 
    where cliente = '$cliente'
        group by grife_jde, cliente

order by grife_jde");

  $grifes_mes = \DB::select("select cliente, grife_jde, 
  sum(a) jan, sum(b) fev, sum(c) mar, sum(d) abr, sum(e) mai, sum(f) jun, sum(g) jul, sum(h) ago, sum(i) 'set', sum(j) 'out', sum(k) nov, sum(l) dez  from (
    select cliente, grife_jde, 
/*
      case when mes = '1' then qtde else 0 end as a,
      case when mes = '2' then qtde else 0 end as b,
      case when mes = '3' then qtde else 0 end as c,
      case when mes = '4' then qtde else 0 end as d,
      case when mes = '5' then qtde else 0 end as e,
      case when mes = '6' then qtde else 0 end as f,
      case when mes = '7' then qtde else 0 end as g,
      case when mes = '8' then qtde else 0 end as h,
      case when mes = '9' then qtde else 0 end as i,
      case when mes = '10' then qtde else 0 end as j,
      case when mes = '11' then qtde else 0 end as k,
      case when mes = '12' then qtde else 0 end as l
*/
      case when month(date_sub(now(),interval +0 month)) = mes then qtde else 0 end as a,
      case when month(date_sub(now(),interval +1 month)) = mes then qtde else 0 end as b,
      case when month(date_sub(now(),interval +2 month)) = mes then qtde else 0 end as c,
      case when month(date_sub(now(),interval +3 month)) = mes then qtde else 0 end as d,
      case when month(date_sub(now(),interval +4 month)) = mes then qtde else 0 end as e,
      case when month(date_sub(now(),interval +5 month)) = mes then qtde else 0 end as f,
      case when month(date_sub(now(),interval +6 month)) = mes then qtde else 0 end as g,
      case when month(date_sub(now(),interval +7 month)) = mes then qtde else 0 end as h,
      case when month(date_sub(now(),interval +8 month)) = mes then qtde else 0 end as i,
      case when month(date_sub(now(),interval +9 month)) = mes then qtde else 0 end as j,
      case when month(date_sub(now(),interval +10 month)) = mes then qtde else 0 end as k,
      case when month(date_sub(now(),interval +11 month)) = mes then qtde else 0 end as l

      from (

      select base.cliente, grife_jde, mes, qtde  from (
        select distinct ab.cliente, grife
          from carteira cart
            left join addressbook ab on ab.id = cart.cli
        where /*rep = '93342' and*/ cliente = '$cliente'
        ) as base


      left join (
        select cliente, mes, grife_jde, sum(qtde) qtde from vendas_12meses v12m 
                left join addressbook ab on ab.id = v12m.cli_jde 
                where grife_jde in ('AH','AT','BG','EV','NG','JO','HI','SP','TC') 
                group by  cliente, ano, mes, grife_jde 
        ) as vds
      on vds.cliente = base.cliente and vds.grife_jde = base.grife
    ) as fim
) as fim2
group by cliente, grife_jde");


@endphp

 @if (\Auth::user()->id_perfil == 5 or \Auth::user()->id_perfil == 1) 
 <div class="row">
  <div class="col-md-12">
    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title">Dados comerciais</h3>
  			<td>option para inativar cliente</td>
  			
		  <td>
      <a href="/carteira/ficha_det?cliente={{$grifes_cli[0]->cliente}}">concorrentes</td></a>
      </div>
    </div>
  </div>
</div>
@endif


<h6>
<div class="row">
  <div class="col-md-9">
    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title">PDVS</h3>
      </div>
      <div class="box-body">
        <table class="table table-bordered table-condensed">
          <tr>
            <th>Código</th>
            <th>Razão</th>
            <th>UF</th>
            <th>Cidade</th>
            <th>Bairro</th>
            <th>Status</th>
            <th>AH</th>
            <th>AT</th>
            <th>BG</th>
            <th>EV</th>
            <th>JO</th>
            <th>HI</th>
            <th>SP</th>
            <th>TC</th>
          </tr>
          @foreach ($grifes_cli as $pdv)
            <tr>
              <td>{{$pdv->cli}}</td>
              <td>{{$pdv->cliente}}</td>
              <td>{{$pdv->uf}}</td>
              <td>{{$pdv->municipio}}</td>
              <td>{{$pdv->bairro}}</td>
              <td>{{$pdv->status_fin}}</td>
              <td align="center">@if ($pdv->AH == 1) <i class="fa fa-check text-green"></i> @else @endif</td>
              <td align="center">@if ($pdv->AT == 1) <i class="fa fa-check text-green"></i> @else @endif</td>
              <td align="center">@if ($pdv->BG == 1) <i class="fa fa-check text-green"></i> @else @endif</td>
              <td align="center">@if ($pdv->EV == 1) <i class="fa fa-check text-green"></i> @else @endif</td>
              <td align="center">@if ($pdv->JO == 1) <i class="fa fa-check text-green"></i> @else @endif</td>
              <td align="center">@if ($pdv->HI == 1) <i class="fa fa-check text-green"></i> @else @endif</td>
              <td align="center">@if ($pdv->SP == 1) <i class="fa fa-check text-green"></i> @else @endif</td>
              <td align="center">@if ($pdv->TC == 1) <i class="fa fa-check text-green"></i> @else @endif</td>
            </tr>              

          @endforeach
        </table>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title">Sub-Grupo</h3>
      </div>
      <div class="box-body">

        <table class="table table-bordered table-condensed">
          <tr>
            <th>Grifes</th>
            <th>2017</th>
            <th>2018</th>
            <th>12meses</th>
          </tr>


          @foreach ($qtde_representante as $linha)


            @if ($linha->repres == \Auth::user()->id_addressbook)
{{--             @if (\Auth::user()->id_perfil == 5 or \Auth::user()->id_perfil == 6)
 --}}
              <tr>
                <td>{{$linha->grife_jde}}</td>
                
                <td align="center">{{$linha->a2017}}</td>
                <td align="center">{{$linha->a2018}}</td>
                <td align="center">{{$linha->a12m}}</td>
              </tr>

            @else 


              <tr>
                <td>{{$linha->grife_jde}}</td>
                <td align="center">@if ($linha->a2017 > 0) <i class="fa fa-check text-green"></i> @endif</td>
                <td align="center">@if ($linha->a2018 > 0) <i class="fa fa-check text-green"></i> @endif</td>
                <td align="center">@if ($linha->a12m > 0) <i class="fa fa-check text-green"></i> @endif</td>
              </tr>

            @endif

          @endforeach 
        </table>

      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title">Pedidos</h3>
      </div>
      <div class="box-body">


      </div>
    </div>
  </div>
  
   <div class="col-md-3">
    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title">Trocas</h3>
      </div>
      <div class="box-body">


      </div>
    </div>
  </div>
  
  
   <div class="col-md-3">
    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title">Pedidos</h3>
      </div>
      <div class="box-body">


      </div>
    </div>
  </div>

@php
 

$jan = date("m-Y");
$fev = date("m-Y", strtotime("-1 months"));
$mar = date("m-Y", strtotime("-2 months"));
$abr = date("m-Y", strtotime("-3 months"));
$mai = date("m-Y", strtotime("-4 months"));
$jun = date("m-Y", strtotime("-5 months"));
$jul = date("m-Y", strtotime("-6 months"));
$ago = date("m-Y", strtotime("-7 months"));
$set = date("m-Y", strtotime("-8 months"));
$out = date("m-Y", strtotime("-9 months"));
$nov = date("m-Y", strtotime("-10 months"));
$dez = date("m-Y", strtotime("-11 months"));


@endphp


  <div class="col-md-9">
    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title">Qtde Ultimos 12 meses</h3>
      </div>
      <div class="box-body">

        <table class="table table-bordered table-condensed">
          <tr>
            <th>Grifes</th>
            <th align="center">{{$jan}}</th>
            <th align="center">{{$fev}}</th>
            <th align="center">{{$mar}}</th>
            <th align="center">{{$abr}}</th>
            <th align="center">{{$mai}}</th>
            <th align="center">{{$jun}}</th>
            <th align="center">{{$jul}}</th>
            <th align="center">{{$ago}}</th>
            <th align="center">{{$set}}</th>
            <th align="center">{{$out}}</th>
            <th align="center">{{$nov}}</th>
            <th align="center">{{$dez}}</th>
          </tr>


          @foreach ($grifes_mes as $linha)

            <tr>
              <td>{{$linha->grife_jde}}</td>


              <td align="center">@if ($linha->jan > 0) {{number_format($linha->jan,0)}} @else @endif</td>   
              <td align="center">@if ($linha->fev > 0) {{number_format($linha->fev,0)}} @else @endif</td>   
              <td align="center">@if ($linha->mar > 0) {{number_format($linha->mar,0)}} @else @endif</td>
      			  <td align="center">@if ($linha->abr > 0) {{number_format($linha->abr,0)}} @else @endif</td>   
      			  <td align="center">@if ($linha->mai > 0) {{number_format($linha->mai,0)}} @else @endif</td>   
      			  <td align="center">@if ($linha->jun > 0) {{number_format($linha->jun,0)}} @else @endif</td>   
      			  <td align="center">@if ($linha->jul > 0) {{number_format($linha->jul,0)}} @else @endif</td>   
      			  <td align="center">@if ($linha->ago > 0) {{number_format($linha->ago,0)}} @else @endif</td>   
      			  <td align="center">@if ($linha->set > 0) {{number_format($linha->set,0)}} @else @endif</td>   
      			  <td align="center">@if ($linha->out > 0) {{number_format($linha->out,0)}} @else @endif</td>   
      			  <td align="center">@if ($linha->nov > 0) {{number_format($linha->nov,0)}} @else @endif</td>   
      			  <td align="center">@if ($linha->dez > 0) {{number_format($linha->dez,0)}} @else @endif</td>   

            </tr>

          @endforeach 
        </table>

      </div>
    </div>
  </div>
</div>
</h6>
@stop