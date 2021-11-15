
@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i> Minha Carteira
@append 

@section('conteudo')


@php
//$carteira = array();


  $representantes = Session::get('representantes');
  $grifes = Session::get('grifes');

	echo $representantes;
	echo $grifes;

 
  if ($representantes == '') {

    $where = '';

  } else {

    $where = ' where rep IN ('.$representantes.') ';

  }
	$sql_cliente = ''; 
  if (isset($_GET["q"])) {
  	$cli = $_GET["q"];
  
	$sql_cliente = " and cliente like '%$cli%' ";
echo $sql_cliente;
  }
 


   if (isset($where)) {
    $carteira = \DB::select("
select *, (select distinct an8 from concorrentes cc left join addressbook abc on abc.id = cc.an8  where abc.cliente = fim2.cliente limit 1) cc 

from (
	select cliente, sum(a) 'a2017', sum(b) 'b2018', sum(c) 'c2019', sum(d) 'd12m'  from (

		select clientes.cli, cliente,
			case when tipo = '2017' then qtde else 0 end as a,
			case when tipo = '2018' then qtde else 0 end as b,
			case when tipo = '2019' then qtde else 0 end as c,
			case when tipo = '12m' then qtde else 0 end as d
			
		from (
		SELECT distinct cli, cliente
			FROM carteira
			left join addressbook ad on ad.id = carteira.cli
			$where $sql_cliente
  
	 
		) as clientes    
			
		
		left join (        
			select '2017' tipo, cli_jde, sum(qtde) qtde  from vendas_2017 where grife_jde in $grifes group by cli_jde
			union all
			select '2018' tipo, cli_jde, sum(qtde) qtde  from vendas_2018 where grife_jde in $grifes group by cli_jde
			union all
			select '2019' tipo, cli_jde, sum(qtde) qtde  from vendas_12meses where ano = '2019' and grife_jde in $grifes  group by cli_jde
			union all
			select '12m' tipo, cli_jde, sum(qtde) qtde  from vendas_12meses where grife_jde in $grifes group by cli_jde			
		) as vendas
		on vendas.cli_jde = clientes.cli
		
	) as fim

	group by cliente
) as fim2

left join (
		select cliente cli, sum(vencido) vencido, sum(a_vencer) a_vencer from (
			select cliente,  
			case when dt_vencimento < now() then valor_em_aberto else 0 end as vencido,
			case when dt_vencimento >= now() then valor_em_aberto else 0 end as a_vencer
			from a_receber left join addressbook ab on ab.id = a_receber.cod_cli ) as sele1
		group by cliente 
	)  as receber
	on receber.cli = fim2.cliente
 ");
 
  } 
  
//echo count($carteira) . ' clientes'.'</br>';

//echo $where;

@endphp


<h6>
<div class="box box-body box-widget">

<table class="table table-bordered table-condensed compact" id="myTable">
  <thead>
    <tr>
     <th width="5%">Det</th>
      <th width="40%">CLIENTE</th>
      <th width="7%" align="center"># 2017</th>
      <th width="7%" align="center"># 2018</th>
      <th width="7%" align="center"># 2019</th>
      <th width="7%" align="center"># 12Meses</th>
      <th width="7%" align="center">R$ vencido</th>
      <th width="7%" align="center">R$ vencer</th>
  
    </tr>
  </thead>

  <tbody>
   
	@php
            $total_2017 = 0;
            $total_2018 = 0;
            $total_2019 = 0;
            $total_12m = 0;
            $total_vencido = 0;
            $total_a_vencer = 0;

	@endphp


    @foreach ($carteira as $cliente) 


	@php

	  $total_2017 += $cliente->a2017;
	  $total_2018 += $cliente->b2018;
	  $total_2019 += $cliente->c2019;
	  $total_12m += $cliente->d12m;
	  $total_vencido += $cliente->vencido;
	  $total_a_vencer += $cliente->a_vencer;
	  
	  
if ($cliente->cc > 0)
{ $icone = '<i class="fa fa-file-text-o text-green"></i>';} 
	else  { $icone = '<i class="fa fa-file-text-o text-red"></i>';}

	@endphp
   
   

      <tr>
	<td> 
	 <a href="/carteira/cart_detcli?cliente={{$cliente->cliente}}"><i class="fa fa-users"></i></a> 
	 <a href="/carteira/fin_cli?cliente={{$cliente->cliente}}"><i class="fa fa-file-text-o"></i></a>
  	 <a href="/carteira/ficha?cliente={{$cliente->cliente}}">{!!$icone!!}</a>
    </td>
       
        <td>{{$cliente->cliente}}</td>
        <td align="center">{{number_format($cliente->a2017, 0, ',', '.')}}</td>
        <td align="center">{{number_format($cliente->b2018, 0, ',', '.')}}</td>        
        <td align="center">{{number_format($cliente->c2019, 0, ',', '.')}}</td>
        <td align="center">{{number_format($cliente->d12m, 0, ',', '.')}} </td>
        
         <td><a href="/carteira/detalhes?cliente={{$cliente->cliente}}">{{number_format($cliente->vencido, 2, ',', '.')}} </a> </td>
         <td><a href="/carteira/detalhes?cliente={{$cliente->cliente}}">{{number_format($cliente->a_vencer, 2, ',', '.')}} </a> </td>

      </tr>

    @endforeach
  </tbody>
        <tfoot>
          <tr>
            <th colspan="2">Total</th>
            <th style="text-align: center">{{number_format($total_2017, 0, ',', '.')}}</th>
            <th style="text-align: center">{{number_format($total_2018, 0, ',', '.')}}</th>
            <th style="text-align: center">{{number_format($total_2019, 0, ',', '.')}}</th>
            <th style="text-align: center">{{number_format($total_12m, 0, ',', '.')}}</th>
            <th style="text-align: center">{{number_format($total_vencido, 2, ',', '.')}}</th>
            <th style="text-align: center">{{number_format($total_a_vencer, 2, ',', '.')}}</th>
            
          </tr>
        </tfoot>
  
</table>
</h6>
</div>

@stop