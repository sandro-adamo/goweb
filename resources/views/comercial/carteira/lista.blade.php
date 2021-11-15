@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i> Minha Carteira
@append 

@section('conteudo')



@php
$carteira = array();


  $representantes = Session::get('representantes');
echo 'representantes: '.$representantes;

  if ($representantes == '') {
echo $representantes;
   
    $where = '';

  } else {

    if (isset($_GET["q"])) {

      $busca = $_GET["q"];
      $where = " where rep IN ($representantes) and ab.cliente like '%$busca%' ";
	echo $representantes;

    } else {
      
      $where = ' where rep IN ('.$representantes.') ';
	echo $representantes;

    }

  }

echo $where;


//dd($where);
 
   if (isset($where)) {
  
    $carteira = \DB::select("

SELECT CARTEIRA.*, grifes_2018, freq_media_2018, grifes_13m, freq_media_13m, 
qtde_2018, valor_2018, qtde_13m, valor_13m, vencido, em_aberto FROM (

select distinct ab.cliente
from carteira
left join addressbook ab on ab.id = carteira.cli
$where
) AS CARTEIRA


/**VENDAS**/
LEFT JOIN (
select cliente, sum(qtde) qtde_2018, sum(valor) valor_2018
from vendas_2018 vds  
left join addressbook ab on vds.cli_jde = ab.id
group by cliente
) AS VENDAS
ON VENDAS.CLIENTE = CARTEIRA.CLIENTE

/**VENDAS 13m**/
LEFT JOIN (
select cliente, sum(qtde) qtde_13m, sum(valor) valor_13m
from vendas_12meses vds  
left join addressbook ab on vds.cli_jde = ab.id
group by cliente
) AS VENDAS13m
ON VENDAS13m.CLIENTE = CARTEIRA.CLIENTE


/**frequencia media por cliente 2018**/
LEFT JOIN (
select cliente, avg(compras) freq_media_2018 from (
/**frequencia por grife**/
select cliente, grife, count(mes) as compras from (
select distinct cliente, grife_jde grife, mes
FROM vendas_2018 vds
left join addressbook ab on ab.id = vds.cli_jde
where qtde > 5
) as sele1
group by cliente, grife
) as sele2
group by cliente
) AS FREQUENCIA
ON FREQUENCIA.CLIENTE = CARTEIRA.CLIENTE


/**grifes por cliente 2018**/
LEFT JOIN (
select cliente, count(grife) as grifes_2018 from (
select cliente, grife_jde grife
FROM vendas_2018 vds
left join addressbook ab on ab.id = vds.cli_jde
where qtde > 5
group by cliente, grife_jde 
) as sele1
group by cliente ) AS GRIFES
ON GRIFES.CLIENTE = CARTEIRA.CLIENTE


/**frequencia media por cliente 13m**/
LEFT JOIN (
select cliente, avg(compras) freq_media_13m from (
/**frequencia por grife**/
select cliente, grife, count(mes) as compras from (
select distinct cliente, grife_jde grife, mes
FROM vendas_12meses vds
left join addressbook ab on ab.id = vds.cli_jde
where qtde > 5
) as sele1
group by cliente, grife
) as sele2
group by cliente
) AS FREQUENCIA13
ON FREQUENCIA13.CLIENTE = CARTEIRA.CLIENTE


/**grifes por cliente 13m**/
LEFT JOIN (
select cliente, count(grife) as grifes_13m from (
select cliente, grife_jde grife
FROM vendas_12meses vds
left join addressbook ab on ab.id = vds.cli_jde
where qtde > 5
group by cliente, grife_jde 
) as sele1
group by cliente ) AS GRIFES13
ON GRIFES13.CLIENTE = CARTEIRA.CLIENTE


/**a receber**/
left join (
	select cliente, sum(vencido) as vencido, sum(em_aberto) em_aberto from (
	select cliente.cliente, 
	case when status_pgto = 'em aberto' then sum(valor_em_aberto) else 0 end as vencido,
	case when status_pgto <> 'em aberto' then sum(valor_em_aberto) else 0 end as em_aberto
	from a_receber
    left join addressbook cliente on cod_cli = cliente.id 
    group by cliente, status_pgto 
    ) as sele1
    group by cliente
) AS RECEBER
ON RECEBER.CLIENTE  = CARTEIRA.CLIENTE 


 ");
 
  } 
echo count($carteira) . ' clientes';

@endphp


<h6>
<div class="box box-body box-widget">

<table class="table table-bordered table-condensed">
  <thead>
    <tr>
      <th>CLIENTE</th>
      <th>Grif_2018</th>
      <th>Grif_13M</th>
      <th>Freq_2018</th>
      <th>Freq_13M</th>
	  <th>Qtde_2018</th>
     <th>Qtde_13m</th>
      <th>Valor_2018</th>
      <th>Valor_13m</th>
      <th>FIN VENCIDO GO</th>
  
    </tr>
  </thead>

  <tbody>
    @foreach ($carteira as $cliente) 
      <tr>
        <td><a href="/carteira/ficha?cliente={{$cliente->cliente}}">{{$cliente->cliente}} </a></td>
        <td><a href="">{{$cliente->grifes_2018}} </a> </td>
        <td><a href="">{{$cliente->grifes_13m}} </a> </td>
        
        <td><a href="">{{number_format($cliente->freq_media_2018, 2, ',', '.')}} </a> </td>
        <td><a href="">{{number_format($cliente->freq_media_13m, 2, ',', '.')}} </a> </td>
        
        <td><a href="">{{number_format($cliente->qtde_2018, 0, ',', '.')}} </a> </td>
        <td><a href="">{{number_format($cliente->qtde_13m, 0, ',', '.')}} </a> </td>
        <td><a href="">{{number_format($cliente->valor_2018, 2, ',', '.')}} </a> </td>
        <td><a href="">{{number_format($cliente->valor_13m, 2, ',', '.')}} </a> </td>
        <td><a href="/carteira/detalhes?cliente={{$cliente->cliente}}">{{number_format($cliente->vencido, 2, ',', '.')}} </a> </td>
      
        <td>
                    
          

        </td>
      </tr>

    @endforeach
  </tbody>
</table>
</h6>
</div>

@stop