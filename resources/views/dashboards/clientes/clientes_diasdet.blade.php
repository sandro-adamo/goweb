@extends('layout.principal')

@php

 $representantes = Session::get('representantes');
 $grifes = Session::get('grifes');
 $codgrife = $_GET["codgrife"];

 $dias  = $_GET["dias"];
 $dias1  = $_GET["dias"];

@endphp

@section('title')
<i class="fa fa-suitcase"></i> {{$codgrife}}   
@append 

@section('conteudo')

<form action="" method="get"> 
@php
	

	if($dias == "a - ultimos 7 dias") 				{$where = "v7 > 0"; $where2 = "v7 > 0";} 
	elseif ($dias == "b - entre 7 e 30 dias") 		{$where = "v30 > 0 and v7 = 0"; $where2 = "v30 > 0";} 	
	elseif ($dias == "c - entre 30 e 120 dias") 	{$where = "v120 > 0 and v30 = 0"; $where2 = "v120 > 0";} 	
	elseif ($dias == "d - entre 120 e 180 dias") 	{$where = "v180 > 0 and v120 = 0"; $where2 = "v180 > 0";} 
	elseif ($dias == "e - entre 180 e 365 dias") 	{$where = "v365 > 0 and v180 = 0"; $where2 = "v365 > 0";} 
	elseif ($dias == "f - > 365 dias") 				{$where = "vtotal > 0 and v365 = 0";  $where2 = "vtotal > 0";} 
	elseif ($dias == "g - sem compra") 				{$where = "vtotal = 0 "; $where2 = "vtotal > 0"; } 
	
	else {}
	
echo $representantes;
echo $where2;

							
						
$query_1 = \DB::select("						
select fim.*, grifes.grifes, grifes.dt_venda from (
	select cod_cliente, cliente, ult_compra, regioes, left(municipios,20) municipios, sum(inadimplentes) inadimplentes, sum(pdvs) pdvs, 
	case 
	when '$where2' =  'v7 > 0' then sum(v7)
	when '$where2' = 'v30 > 0' then sum(v30)
    when '$where2' = 'v120 > 0 ' then sum(v120)
	when '$where2' = 'v180 > 0' then sum(v180)
	when '$where2' = 'v365 > 0' then sum(v365) 
	when '$where2' = 'vtotal > 0' then sum(vtotal)
	when '$where2' = 'g - sem compra' then 0
	else 0 end as qtde
	
	from (

			select cod_cliente, cliente, ult_compra,  group_concat(distinct regiao, '' order by regiao ) regioes, municipios, sum(inadimplentes) inadimplentes, sum(pdvs) pdvs, sum(qtde) qtde,
            sum(v7) v7, sum(v30) v30, sum(v120) v120, sum(v180) v180, sum(v365) v365, sum(vtotal) vtotal
			from (
	 
				select cod_cliente, cliente, ult_compra, group_concat(distinct regiao, '') regiao, count(codcli) pdvs ,sum(inadimplentes) inadimplentes, sum(vtotal) qtde,  group_concat(distinct municipio, '' order by municipio ) municipios,
                sum(v7) v7, sum(v30) v30, sum(v120) v120, sum(v180) v180, sum(v365) v365, sum(vtotal) vtotal
				from(
					
					select cart.cod_cliente, cart.cliente, max(ult_compra) ult_compra, codcli, group_concat(distinct regiao, '') regiao, left(municipio,10) municipio, case when cart.financeiro in ('in','ju') then 1 else 0 end as inadimplentes,
                    sum(v7) v7, sum(v30) v30, sum(v120) v120, sum(v180) v180, sum(v365) v365, sum(vtotal) vtotal
					
					from ds_carteira cart left join addressbook ab on ab.id = codcli
					where rep_carteira in ($representantes) and codgrife = '$codgrife'  
					group by cart.cod_cliente, cart.cliente, codcli, left(municipio,10) , cart.financeiro
                    
				) as fim0 group by cod_cliente, cliente, ult_compra
				
			) as fim1 group by cod_cliente, cliente, ult_compra, municipios
		) as fim2 
		  
	    where $where
	
	   group by cod_cliente, cliente , ult_compra, regioes, left(municipios,20)
) as fim

left join (

select cliente, max(dt_venda) dt_venda, group_concat(distinct codgrife, ' ' order by codgrife) grifes from (
	select cliente, codgrife, max(dt_venda) dt_venda from (
		
        select cliente, codgrife, sum(v7) v7, sum(v30) v30, sum(v120) v120, sum(v180) v180, sum(v365) v365, sum(vtotal) vtotal,
			(        
			select  max(dt_venda) dt_venda from vendas_jdes vds left join addressbook ab on vds.id_cliente = ab.id  
			where vds.codgrife = cart1.codgrife and ab.cliente = cart1.cliente  and dt_venda <= '2021-05-31'
			
			) as dt_venda

		from ds_carteira cart1 where rep_carteira in ($representantes) and codgrife <> '$codgrife'
		group by cliente , codgrife

    ) as fim1 where $where2
	
    group by cliente, codgrife
) as fim2 group by cliente

) as grifes on grifes.cliente = fim.cliente	      
	    
");
			
	


echo ' - '. count($query_1); 
	
	
@endphp


<div class="row">

		<div class="col-md-8">
			<div class="box box-widget box-body">
				<div class="table-responsive">

				<table class="table table-bordered" id="example3">
				<thead>
				<tr>	
				<td colspan="10">CLIENTES QUE COMPRARAM A GRIFE {{$codgrife}} NO PERIODO ( {{$dias}} )</td>

				</tr>

				<tr>	

				<td>form</td>
				<td colspan="1" align="center">clientes</td>				
				<td colspan="1" align="center">pdvs ativos</td>
				<td colspan="1" align="center">pdvs inad</td>
				<td colspan="1" align="center">qtde pcs no periodo de {{$codgrife}}</td>
				<td colspan="1" align="center">ult data que comprou {{$codgrife}}</td>
				<td colspan="1" align="center">regiao</td>
				<td colspan="1" align="center">municipios</td>
		{{-- 
				<td colspan="1" align="center">minhas grifes periodo selecionado</td>
				<td colspan="1" align="center">dt ult_compra das minhas grifes</td>
--}}
				</thead>	
				</tr>


				@foreach ($query_1 as $query1)

				<tr>
				<td><a href="/cliente_form?cli={{$query1->cliente}}"><i class="fa fa-file"></i> </a></td>		
				<td align="left"><a href="/det_subgrupo?pdv={{$query1->cod_cliente}}&codgrife={{$query1->cliente}}">{{$query1->cliente}}</a></td>
				<td align="center">{{$query1->pdvs}}</td>
				<td align="center">{{$query1->inadimplentes}}</td>
				<td align="center">{{number_format($query1->qtde,0)}}</td>
				<td align="center">{{$query1->ult_compra}}</td>
				<td align="center">{{$query1->regioes}}</td>
				<td align="left">{{$query1->municipios}}</td>
			{{--		
				<td align="left">{{$query1->grifes}}</td>
				<td align="left">{{$query1->dt_venda}}</td>
					--}}
				</tr>
				@endforeach 

				</table>
				</div>
			</div>
		</div>

	
	
	
</div>	
	
	
	
</form>
</h6>
@stop




