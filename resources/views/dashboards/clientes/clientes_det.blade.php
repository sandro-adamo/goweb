@extends('layout.principal')

@php

 $representantes = Session::get('representantes');
 $grifes = Session::get('grifes');
 $codgrife = $_GET["codgrife"];
 $id_perfil = \Auth::user()->id_perfil;

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



$query_1 = \DB::select("
	
	select periodo, count(cliente) clientes, sum(pdvs) pdvs,
		case when  periodo =  'a - ultimos 7 dias' then sum(v7)
		when periodo = 'b - entre 7 e 30 dias' then sum(v30)
		when periodo = 'c - entre 30 e 120 dias' then sum(v120)
		when periodo = 'd - entre 120 e 180 dias' then sum(v180)
		when periodo = 'e - entre 180 e 365 dias' then sum(v365) 
		when periodo = 'f - > 365 dias' then sum(vtotal)-sum(v365) 
		when periodo = 'g - sem compra' then 0
		else 0 end as qtde

	 from (
				select cliente, sum(pdvs) pdvs, sum(v7) v7, sum(v30) v30, sum(v120) v120, sum(v180) v180, sum(v365) v365, sum(vtotal) vtotal,
				case 
				when vtotal > 0 and v365 = 0 then 'f - > 365 dias'
				when v365 > 0 and v180 = 0 then 'e - entre 180 e 365 dias'
				when v180 > 0 and v120 = 0 then 'd - entre 120 e 180 dias'
				when v120 > 0 and v30 = 0 then 'c - entre 30 e 120 dias'
				when v30 > 0 and v7 = 0 then 'b - entre 7 e 30 dias'
				when v7 > 0 then 'a - ultimos 7 dias' else 'g - sem compra' end as periodo
				from (
					select cliente, count(codcli) pdvs, sum(v7) v7, sum(v30) v30, sum(v120) v120, sum(v180) v180, sum(v365) v365, sum(vtotal) vtotal from (
						select cliente, codcli, sum(v7) v7, sum(v30) v30, sum(v120) v120, sum(v180) v180, sum(v365) v365, sum(vtotal) vtotal
						from ds_carteira cart
						where rep_carteira in ($representantes) and codgrife = '$codgrife'  
						group by cliente, codcli
					) as fim0 group by cliente
				) as fim1 group by cliente

	) as fim2 group by periodo order by periodo

");
	
	
	
	
if ($id_perfil == '1' or $id_perfil == '5' or $id_perfil == '6') {	

$query_2 = \DB::select("
	

select faixa_populacao, count(municipio) municipios, count(municipio)-sum(sem_cli) com_cli, sum(sem_cli) sem_cli, sum(clientes) clientes, sum(pdvs) pdvs, sum(qtde) qtde from (

	select base.*, clientes, pdvs, qtde, valor, case when clientes is null then 1 else 0 end as sem_cli from (
    
        select uf, municipio, 
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

) as final1 group by faixa_populacao																																					
        
");

	
} 
	
@endphp

<h6>
			
						
						
<div class="row">
		<div class="col-md-5">
		<div class="box box-body box-widget">
		 
		<table class="table table-responsive table-striped" id="example1">
		 <tr>	
	 		<td colspan="6">clientes (subgrupos) com compras nos ultimos 12meses para a Grife </td>
		
				</tr>
		  			
					<tr>	
					
					<td colspan="1" align="center">Periodo da ult compra</td>
					<td colspan="1" align="center">clientes</td>
					<td colspan="1" align="center">pdvs</td>
					<td colspan="1" align="center">qtde pecas</td>
					
					
				
					</tr>
			  
			  
			@foreach ($query_1 as $query1)
			  
				<tr>
					<td align="left"><a href="/cliente_diasdet?dias={{$query1->periodo}}&codgrife={{$codgrife}}">{{$query1->periodo}}</a></td>
					<td align="center">{{$query1->clientes}}</td>
					<td align="center">{{$query1->pdvs}}</td>
					<td align="center">{{number_format($query1->qtde,0)}}</td>	
					
					
				</tr>
			@endforeach 
			
			</table>

		</div>

	</div>

	
@php if ($id_perfil == '1' or $id_perfil == '5' or $id_perfil == '6') {	
@endphp 
	
		<div class="col-md-7">
		<div class="box box-body box-widget">
		 
		<table class="table table-responsive table-striped" id="example1">
		 <tr>	
	 		<td colspan="7">Faixa de municipios com compras nos ultimos 12meses para a Grife {{$codgrife}}</td>
		
				</tr>
		  			
					<tr>	
					
					<td colspan="1" align="left">Faixa Pop</td>
					<td colspan="1" align="center">Municipios</td>
					<td colspan="1" align="center">Com Clientes</td>	
					<td colspan="1" align="center">Sem Clientes</td>
					<td colspan="1" align="center">Clientes</td>
					<td colspan="1" align="center">Pdvs</td>  
					<td colspan="1" align="center">Qtde</td> 
					
				
					</tr>
			  
			  
			@foreach ($query_2 as $query2)
			  
				<tr>
						
					<td align="left"><a href="/cliente_faixa?faixa={{$query2->faixa_populacao}}&codgrife={{$codgrife}}">
					{{$query2->faixa_populacao}}</a></td>
					
					<td align="center">{{number_format($query2->municipios,0)}}</td>
					<td align="center">{{number_format($query2->com_cli,0)}}</td>
					<td align="center">{{$query2->sem_cli}}</td>
					<td align="center">{{number_format($query2->clientes,0)}}</td>	
					<td align="center">{{number_format($query2->pdvs,0)}}</td>	
			<td align="center">{{number_format($query2->qtde,0)}}</td>	
			
				</tr>
			@endforeach 
			
			</table>

		</div>

	</div>


	@php } @endphp
	

</div>
	
	
	
	
</form>
</h6>
@stop