@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i> Detalhe Grife
@append 

@section('conteudo')



@php
$carteira = array();


  $representantes = Session::get('representantes');

  if ($representantes == '') {

    $where = '';

  } else {

    $where = ' where rep IN ('.$representantes.') ';

  }

//dd($where);
 
   if (isset($where)) {
  
    $carteira = \DB::select("
SELECT grife,  avg(freq_media_2018) freq_media_2018, avg(freq_media_13m) freq_media_13m, 
sum(qtde_2018) qtde_2018, sum(qtde_13m) qtde_13m, sum(valor_2018) valor_2018, sum(valor_13m) valor_13m, 
count(clientes_2018) clientes_2018, count(clientes_13m) clientes_13m

from (

SELECT  CARTEIRA.*,  freq_media_2018,freq_media_13m, 
qtde_2018, valor_2018,  qtde_13m, valor_13m,  clientes_2018, clientes_13m FROM (

select distinct ab.cliente, grife
from carteira
left join addressbook ab on ab.id = carteira.cli
$where 

) AS CARTEIRA


/**VENDAS 2018**/
LEFT JOIN (
select cliente, grife_jde, sum(qtde) qtde_2018, sum(valor) valor_2018
from vendas_2018 vds  
left join addressbook ab on vds.cli_jde = ab.id
group by cliente, grife_jde
) AS VENDAS
ON VENDAS.CLIENTE = CARTEIRA.CLIENTE AND VENDAS.GRIFE_JDE = CARTEIRA.GRIFE


/**VENDAS 13m**/
LEFT JOIN (
select cliente, grife_jde, sum(qtde) qtde_13m, sum(valor) valor_13m
from vendas_13meses vds  
left join addressbook ab on vds.cli_jde = ab.id
group by cliente, grife_jde
) AS VENDAS13m
ON VENDAS13m.CLIENTE = CARTEIRA.CLIENTE AND VENDAS13m.GRIFE_JDE = CARTEIRA.GRIFE


/**clientes por grife 2018**/
LEFT JOIN (
select cliente, grife, count(cliente) as clientes_2018 from (
select cliente, grife_jde grife
FROM vendas_2018 vds
left join addressbook ab on ab.id = vds.cli_jde

where qtde > 5
group by cliente, grife_jde 
) as sele1
group by cliente, grife ) AS GRIFES
ON GRIFES.grife = CARTEIRA.grife and GRIFES.cliente = CARTEIRA.cliente


/**clientes por grife 13m**/
LEFT JOIN (
select cliente, grife, count(cliente) as clientes_13m from (
select cliente, grife_jde grife
FROM vendas_13meses vds
left join addressbook ab on ab.id = vds.cli_jde
where qtde > 5
group by cliente, grife_jde 
) as sele1
group by cliente, grife ) AS GRIFES13m
ON GRIFES13m.grife = CARTEIRA.grife  and GRIFES13m.cliente = CARTEIRA.cliente


LEFT JOIN (
/**frequencia por grife**/
select cliente, grife, count(mes) as freq_media_2018 from (
select distinct cliente, grife_jde grife, mes
FROM vendas_2018 vds
left join addressbook ab on ab.id = vds.cli_jde
where qtde > 5
) as sele1
group by cliente, grife
) AS FREQUENCIA
ON FREQUENCIA.CLIENTE = CARTEIRA.CLIENTE AND FREQUENCIA.GRIFE = CARTEIRA.GRIFE


/**frequencia media por cliente 13m**/
LEFT JOIN (
/**frequencia por grife**/
select cliente, grife, count(mes) as freq_media_13m from (
select distinct cliente, grife_jde grife, mes
FROM vendas_13meses vds
left join addressbook ab on ab.id = vds.cli_jde
where qtde > 5
) as sele1
group by cliente, grife
) AS FREQUENCIA13
ON FREQUENCIA13.CLIENTE = CARTEIRA.CLIENTE AND FREQUENCIA13.GRIFE = CARTEIRA.GRIFE


) as sele2

group by grife ");
 
  } 
echo count($carteira) . ' clientes';

@endphp


<h6>
<div class="box box-body box-widget">

<table class="table table-bordered table-condensed">
  <thead>
    <tr align="center">
      <th>Grife</th> 
      <th>Clientes 2018</th>  
      <th>Clientes 13m</th>    
      <th>Freq_2018</th>
      <th>Freq_13M</th>
	  <th>Qtde_2018</th>
      <th>Qtde_13m</th>
      <th>Valor_2018</th>
      <th>Valor_13m</th>  
    </tr>
  </thead>

  <tbody>
    @foreach ($carteira as $cliente) 
      <tr>
        <td align="center"><a href="">{{$cliente->grife}} </a></td>
        <td align="center"><a href="">{{number_format($cliente->clientes_2018, 0, ',', '.')}} </a> </td>
        <td align="center"><a href="">{{number_format($cliente->clientes_13m, 0, ',', '.')}} </a> </td>
        
        <td align="center"><a href="">{{number_format($cliente->freq_media_2018, 3, ',', '.')}} </a> </td>
        <td align="center"><a href="">{{number_format($cliente->freq_media_13m, 3, ',', '.')}} </a> </td>
        
        <td align="center"><a href="">{{number_format($cliente->qtde_2018, 0, ',', '.')}} </a> </td>
        <td align="center"><a href="">{{number_format($cliente->qtde_13m, 0, ',', '.')}} </a> </td>
        
        <td align="center"><a href="">{{number_format($cliente->valor_2018, 2, ',', '.')}} </a> </td>
        <td align="center"><a href="">{{number_format($cliente->valor_13m, 2, ',', '.')}} </a> </td>
      </tr>

    @endforeach
  </tbody>
</table>
</h6>
</div>

@stop