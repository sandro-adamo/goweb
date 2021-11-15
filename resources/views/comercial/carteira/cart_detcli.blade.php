@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i> Minha Carteira
@append 

@section('conteudo')



@php
$carteira = array();


  $representantes = Session::get('representantes');
  
  $sql = ' where ';

  if (isset($_GET["cliente"])) {
    $cliente = $_GET["cliente"];
    $sql .= " cliente = '$cliente' ";
  } else {
    $cliente = '';
    $sql = '';
  }
 
  if ($representantes == '') {

    $where = '';

  } else {

    $where = ' and rep IN ('.$representantes.') ';

  }

//dd($where);
 
   if (isset($where)) {
  
    $carteira = \DB::select("
select cli, concat(cli,' - ',fantasia) nomecli, sum(a) 'a2017', sum(b) 'b2018', sum(c) 'c2019', sum(d) 'd12m', max(vencido) vencido, max(a_vencer) a_vencer  from (

	select clientes.cli, cliente, fantasia, 
		case when tipo = '2017' then valor else 0 end as a,
        case when tipo = '2018' then valor else 0 end as b,
        case when tipo = '2019' then valor else 0 end as c,
        case when tipo = '12m' then valor else 0 end as d, 
        vencido, a_vencer
        
    from (
    SELECT distinct cli, cliente, fantasia
		FROM carteira
        left join addressbook ad on ad.id = carteira.cli

		$sql
    ) as clientes    
        
	
    left join (        
		select '2017' tipo, cli_jde, sum(valor) valor  from vendas_2017 where grife_jde not in ('BC','BV','CT','DZ','GU','MC','PU','SM','ST') group by cli_jde
		union all
		select '2018' tipo, cli_jde, sum(valor) valor  from vendas_2018 where grife_jde not in ('BC','BV','CT','DZ','GU','MC','PU','SM','ST') group by cli_jde
		union all
		select '2019' tipo, cli_jde, sum(valor) valor  from vendas_12meses where ano = '2019' and grife_jde not in ('BC','BV','CT','DZ','GU','MC','PU','SM','ST') group by cli_jde
		union all
		select '12m' tipo, cli_jde, sum(valor) valor  from vendas_12meses where grife_jde not in ('BC','BV','CT','DZ','GU','MC','PU','SM','ST') group by cli_jde			
    ) as vendas
    on vendas.cli_jde = clientes.cli
    
    
    left join (
		select cod_cli, sum(vencido) vencido, sum(a_vencer) a_vencer from (
			select cod_cli,  
			case when dt_vencimento < now() then valor_em_aberto else 0 end as vencido,
			case when dt_vencimento >= now() then valor_em_aberto else 0 end as a_vencer
			from a_receber  ) as sele1
		group by cod_cli 
	)  as receber
	on receber.cod_cli = clientes.cli
    
    
) as fim

group by cli, concat(cli,' - ',fantasia)
 ");
 
  } 
  
echo count($carteira) . ' clientes'.'</br>';

echo $where;
echo $sql;


@endphp


<h6>
<div class="box box-body box-widget">

<table class="table table-bordered table-condensed compact" id="myTable">
  <thead>
    <tr>
     <th width="5%">Det</th>
   
      <th width="30%">pdv</th>
      <th width="7%"># 2017</th>
      <th width="7%"># 2018</th>
      <th width="7%"># 2019</th>
      <th width="7%"># 12Meses</th>
      <th width="7%">R$ vencido</th>
      <th width="7%">R$ vencer</th>
  
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
	  

	@endphp
   
      <tr><td><a href="/carteira/fin_pdv?cliente={{$cliente->cli}}"><i class="fa fa-file-text-o"></i></a>
       </td>
        
        <td>{{$cliente->nomecli}}</td>
        <td align='center'>{{$cliente->a2017}}</td>
        <td align='center'>{{$cliente->b2018}}</td>
        
        <td align='center'>{{$cliente->c2019}}</td>
        <td align='center'>{{$cliente->d12m}} </td>
        
         <td align='center'><a href="/carteira/detalhes?cliente={{$cliente->cli}}">{{number_format($cliente->vencido, 2, ',', '.')}} </a> </td>
         <td align='center'><a href="/carteira/detalhes?cliente={{$cliente->cli}}">{{number_format($cliente->a_vencer, 2, ',', '.')}} </a> </td>

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