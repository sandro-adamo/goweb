@extends('layout.principal')             
                     
@section('conteudo')

        
        <div class="row" align="center">

        <img src="/img/logogo.png" width="60">
        <h6>{{Auth::user()->nome}} <br><br><br>
        Bem vindo a versão 3.0 do Portal de produtos.</h6>
                    
		</div>


@php

$teste = Auth::user()->id_perfil;
echo $teste;



$carteira = array();

$representantes = Session::get('representantes');

if ($representantes == '99289' or $teste == '6') { 
  
  echo $representantes;
  

  if ($representantes == '') {

    $where = '';

  } else {

    $where = ' where rep IN ('.$representantes.') ';

  }

//dd($where);
 
   if (isset($representantes)) {
  
$carteira = \DB::select("

SELECT count(cliente) clientes, avg(grifes_2018) grifes_2018, avg(freq_media_2018) freq_media_2018, avg(grifes_13m) grifes_13m, avg(freq_media_13m) freq_media_13m,
sum(qtde_2018) qtde_2018, sum(qtde_13m) qtde_13m, sum(valor_2018) valor_2018, sum(valor_13m) valor_13m, 
sum(vencido) vencido

from (

SELECT  CARTEIRA.*, grifes_2018, freq_media_2018, grifes_13m, freq_media_13m, 
qtde_2018, valor_2018,  qtde_13m, valor_13m, vencido, em_aberto FROM (

select distinct ab.cliente
from carteira
left join addressbook ab on ab.id = carteira.cli
$where 
) AS CARTEIRA


/**VENDAS 2018**/
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
from vendas_13meses vds  
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
FROM vendas_13meses vds
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
FROM vendas_13meses vds
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

) as sele2

 ");
 
  } 


@endphp


    @foreach ($carteira as $cliente) 

   
   
     @endforeach


<table width="400" border="1">
  <tbody>
    <tr>
      <td width="100" align="left">ffffffff
      <a href="/carteira/carteira">Clientes ->  {{number_format($cliente->clientes, 0, ',', '.')}}</a></td>
      <td width="50" align="center"><b>2018</b></td>
      <td width="50" align="center"><b>13 meses</b></td>
      
    </tr>
    
    <tr>
		<td><a href="/carteira/det_grife">Grifes</a></td>
      <td align="center">{{number_format($cliente->grifes_2018, 3, ',', '.')}}</td>
      <td align="center">{{number_format($cliente->grifes_13m, 3, ',', '.')}}</td>
    </tr>
   
    <tr>
      <td>Frequencia</td>
      <td align="center">{{number_format($cliente->freq_media_2018, 3, ',', '.')}}</td>
      <td align="center">{{number_format($cliente->freq_media_13m, 3, ',', '.')}}</td>
      
    </tr>
    <tr>
      <td>Quant</td>
      <td align="center">{{number_format($cliente->qtde_2018, 0, ',', '.')}} </td>
      <td align="center">{{number_format($cliente->qtde_13m, 0, ',', '.')}} </td>
    </tr>
    <tr>
      <td>Valor</td>
      <td align="right">{{number_format($cliente->valor_2018, 2, ',', '.')}}</td>
      <td align="right">{{number_format($cliente->valor_13m, 2, ',', '.')}}</td>
    </tr>
        <tr>
      <td>Em aberto</td>
      <td>&nbsp;</td>
      <td align="right">{{number_format($cliente->vencido, 2, ',', '.')}}</td>
    </tr>
  </tbody>
</table>

</div>
@php
} else { echo '';}
@endphp
@stop