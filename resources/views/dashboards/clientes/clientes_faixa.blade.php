@extends('layout.principal')

@php

 $representantes = Session::get('representantes');
 $grifes = Session::get('grifes');
 $codgrife = $_GET["codgrife"];
 $faixa = $_GET["faixa"];

/**
	
  $where1 = ' where id_rep in ('.$representantes.') ';
  $where2 = ' where rep_comissao in ('.$representantes.') ';
**/


@endphp

@section('title')
<i class="fa fa-users"></i> Dashboard Clientes para a grife {{$codgrife}} 
@append 

@section('conteudo')

<form action="" method="get"> 
@php

	

$query_2 = \DB::select("
	
select *, (populacao/pdvs) pop_pdv from (

	select base.*, clientes, pdvs, qtde, valor, case when clientes is null then 1 else 0 end as sem_cli from (
    
        select uf, municipio, populacao, renda_pc,
        case when populacao > 1000000 then 'a - 1.000.000'
        when populacao > 700000 then 'b - 700.000'
        when populacao > 400000 then 'c - 400.000'
        when populacao > 200000 then 'd - 200.000'
        when populacao > 100000 then 'e - 100.000'
	     when populacao > 50000 then 'f - 50.000'
        else 'g - < 50.000' end as faixa_populacao 
    
        from ibge -- where uf = 'sp'
	   
    ) as base
    
    left join (    
		select uf, municipio, count(cliente) clientes, sum(pdvs) pdvs, sum(qtde) qtde, sum(valor) valor from (
			select uf, municipio, cliente, count(id_cliente) pdvs, sum(qtde) qtde, sum(valor) valor from (
				select uf, municipio, cliente, id_cliente, sum(qtde) qtde, sum(valor) valor
				from vendas_jde vds 
				left join addressbook ab on ab.id = vds.id_cliente
				where datediff(now(),vds.dt_venda) <= 365 and codgrupo not in ('AYD','BUA','AUB') 
				and cod_grife = '$codgrife' and id_rep in ($representantes)
				group by uf, municipio, cliente, id_cliente
			) as fim1 group by uf, municipio, cliente
		) as fim2 group by uf, municipio
	) as final
    on final.municipio = base.municipio and final.uf = base.uf
													
  ) as final1
where faixa_populacao = '$faixa'

order by uf, municipio
        
");

	

	
@endphp

<h6>
			
						
						
<div class="row">
		<div class="col-md-7">
		<div class="box box-body box-widget">
		 
		<table class="table table-responsive table-striped" id="example1">
		 <tr>	
	 		<td colspan="7">Faixa de municipios com compras nos ultimos 12meses para a Grife {{$codgrife}}</td>
		
				</tr>
		  			
					<tr>	
					
					<td colspan="1" align="left">UF - Municipio</td>
					<td colspan="1" align="center">Clientes</td>
					<td colspan="1" align="center">Pdvs</td>	
					<td colspan="1" align="center">Populacao</td>
					<td colspan="1" align="center">Renda PC</td>
					<td colspan="1" align="center">qtde</td>  
					<td colspan="1" align="center">valor</td>
					<td colspan="1" align="center">pessoas por pdv</td>
					
				
					</tr>
			  
			  
			@foreach ($query_2 as $query2)
			  
				<tr>
						
					<td align="left"><a href="/clientes_faixa?faixa={{$query2->faixa_populacao}}&codgrife={{$codgrife}}">
					{{$query2->uf}} - {{$query2->municipio}}</a></td>
					
					<td align="center">{{number_format($query2->clientes,0)}}</td>
					<td align="center">{{number_format($query2->pdvs,0)}}</td>
					<td align="center">{{number_format($query2->populacao,0)}}</td>	
					<td align="center">{{number_format($query2->renda_pc,0)}}</td>	
					<td align="center">{{number_format($query2->qtde,0)}}</td>	
					<td align="center">{{number_format($query2->valor,2)}}</td>	
					<td align="center">{{number_format($query2->pop_pdv,0)}}</td>	
			
			
				</tr>
			@endforeach 
			
			</table>

		</div>

	</div>


	
	

</div>
	
	
	
	
</form>
</h6>
@stop