@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i> Minha Carteira
@append 

@section('conteudo')



@php
$carteira = array();


  $representantes = Session::get('representantes');


 
  if ($representantes == '') {

    $where = '';

  } else {

    $where = ' where repres IN ('.$representantes.') ';

  }

//dd($where);
 
   if (isset($where)) {
  
    $carteira = \DB::select("
select cliente, lojas pdvs, sum(valor) total, 
    sum(m01) m01,    sum(m02) m02,    sum(m03) m03,    sum(m04) m04,    sum(m05) m05,    sum(m06) m06,    
    sum(m07) m07,    sum(m08) m08,    sum(m09) m09,    sum(m10) m10,    sum(m11) m11,    sum(m12) m12,sum(valor) total, 
    sum(valor/12)/lojas as md_loja
    
    from (
		select vds.cliente, valor, lojas,
		case when month(date_sub(now(),interval +0 month)) = mes then valor else 0 end as m01,
            case when month(date_sub(now(),interval +1 month)) = mes then valor else 0 end as m02,
            case when month(date_sub(now(),interval +2 month)) = mes then valor else 0 end as m03,
            case when month(date_sub(now(),interval +3 month)) = mes then valor else 0 end as m04,
            case when month(date_sub(now(),interval +4 month)) = mes then valor else 0 end as m05,
            case when month(date_sub(now(),interval +5 month)) = mes then valor else 0 end as m06,
            case when month(date_sub(now(),interval +6 month)) = mes then valor else 0 end as m07,
            case when month(date_sub(now(),interval +7 month)) = mes then valor else 0 end as m08,
            case when month(date_sub(now(),interval +8 month)) = mes then valor else 0 end as m09,
            case when month(date_sub(now(),interval +9 month)) = mes then valor else 0 end as m10,
            case when month(date_sub(now(),interval +10 month)) = mes then valor else 0 end as m11,
            case when month(date_sub(now(),interval +11 month)) = mes then valor else 0 end as m12
			
			from vendas_cml vds
left join clientes_pdv cli on vds.cliente = cli.cliente

	$where and tipo = '12m'

	) as sele1

	group by cliente, lojas
order by total desc ");
 
  } 
  
echo count($carteira) . ' clientes'.'</br>';

// echo $where;
$periodo = '';
  if (date('m') <= 1) {
    $periodo = 'Jan'.date('Y')-1;
  }
  if (date('m') <= 4) {
    $ano = date('Y');
    $periodo = 'Jan' . $ano;
  }
  
  

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




<h6>
<div class="box box-body box-widget">
<div>Relatorio SomaMedia ultimos 12 meses em R$</div>
<table class="table table-bordered table-condensed compact" id="myTable">
  <thead>
    <tr>
      <th width="150">CLIENTE</th>
      <th>pdvs</th>
      <th>total</th>
      <th>md pdv ano</th>
      <th>{{$jan}}</th>
      <th>{{$fev}}</th>
      <th>{{$mar}}</th>
      <th>{{$abr}}</th>
      <th>{{$mai}}</th>
      <th>{{$jun}}</th>
      <th>{{$jul}}</th>
      <th>{{$ago}}</th>
      <th>{{$set}}</th>
      <th>{{$out}}</th>
      <th>{{$nov}}</th>
      <th>{{$dez}}</th>
    
     
     
  
    </tr>
  </thead>

  <tbody>
    @foreach ($carteira as $cliente) 
      <tr>
        <td><a href="/carteira/ficha/{{$cliente->cliente}}">{{$cliente->cliente}} </a></td>
        <td align="center">{{$cliente->pdvs}} </a></td>
        <td align="center">{{number_format($cliente->total, 0, ',', '.')}}</td>
        <td align="center">{{number_format($cliente->md_loja, 0, ',', '.')}} </td>
        <td align="center">{{number_format($cliente->m01, 0, ',', '.')}} </td>
        <td align="center">{{number_format($cliente->m02, 0, ',', '.')}} </td>
        <td align="center">{{number_format($cliente->m03, 0, ',', '.')}} </td>
        <td align="center">{{number_format($cliente->m04, 0, ',', '.')}} </td>
        <td align="center">{{number_format($cliente->m05, 0, ',', '.')}} </td>
        <td align="center">{{number_format($cliente->m06, 0, ',', '.')}} </td>
        <td align="center">{{number_format($cliente->m07, 0, ',', '.')}} </td>
        <td align="center">{{number_format($cliente->m08, 0, ',', '.')}} </td>
        <td align="center">{{number_format($cliente->m09, 0, ',', '.')}} </td>
        <td align="center">{{number_format($cliente->m10, 0, ',', '.')}} </td>
        <td align="center">{{number_format($cliente->m11, 0, ',', '.')}} </td>
        <td align="center">{{number_format($cliente->m12, 0, ',', '.')}} </td>
        
        
        

      </tr>

    @endforeach
  </tbody>
</table>
</h6>
</div>

@stop