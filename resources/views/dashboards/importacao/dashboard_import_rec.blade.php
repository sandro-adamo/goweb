@extends('layout.principal')
@section('conteudo')

@php

$representantes = Session::get('representantes');

$base = \DB::select("select distinct data_base from ds_carteira");



if($representantes==101815) 
	{$grifes="( 'AM', 'BC', 'BV', 'CT', 'SM', 'MC', 'CH', 'DU', 'AA', 'AZ', 'CL')";} 
		else {
			$grifes = Session::get('grifes'); }



$query_2 = \DB::select(" 

select pedido, tipo, ref_go, ref_despachante, ref_nac_01, ult_prox, fornecedor, 
group_concat(distinct tipoitem,' ') tipoitem, group_concat(distinct codgrife,' ') codgrife,
case when CHAR_LENGTH(group_concat(distinct colmod,' ')) > 26 then concat('...',right(group_concat(distinct colmod,' '),26)) else group_concat(distinct colmod,' ') end as colmod, 
sum(qtde) qtde from (

	select * from (
		select *, case when item_pai is null then secundario else item_pai end as item 
		from (

			select pedido, tipo, ref_go, ref_despachante, ref_nac_01, 
            concat(ult_status, ' / ',prox_status) ult_prox, imp.secundario, cod_item, codtipoitem,
			
			case  when codtipoitem = 006 then 'PECA' 
				 when (left(imp.secundario,3) = 'FR ' or left(imp.secundario,6) = 'PONTE ') then 'FRENTE' 
				 when left(imp.secundario,2) IN ('LE','LD','HE','HD','PL','SC','BL') then 'ACESSORIOS'
				 else 'OUTROS' end as tipoitem, qtde_sol qtde
			 
			from importacoes_pedidos imp 
			left join itens on itens.id = cod_item		
			where ref_go not in ('LA200501') and 
			ult_status not in (980) and prox_status in (999,400)
			
		) as base 

			left join (select * from itens_estrutura   ) as estrutura
			on estrutura.id_filho = cod_item
	) as final

	left join (select secundario codsec, agrup, codgrife, colmod, fornecedor from itens ) item
	on item.codsec = final.item        

) as final1 
group by pedido, tipo, ref_go, ref_despachante, ref_nac_01, ult_prox, fornecedor


");
			  
			
@endphp

<form action="" method="get"> 

<h6>

	
							
<div class="row"> 
	
		<div class="col-md-12">	
		<div class="box box-body">
			
	   <table class="table table-striped table-bordered compact" id="myTable">
		  <thead>	
			
		 <tr>	

	 		<td colspan="10">Importações em aberto </td>
		
				</tr>
		  			
					<tr>	
					<td colspan="1" align="center">Pedido</td>	
					<td colspan="1" align="center">Invoice</td>				
					<td colspan="1" align="center">desc1</td>
					<td colspan="1" align="center">desc2</td>
					<td colspan="1" align="center">fornecedor</td>
					<td colspan="1" align="center">ult_prox status</td>
					<td colspan="1" align="center">Tipo_item</td>
					<td colspan="1" align="center">Grifes </td>
					<td colspan="1" align="center">Colecoes</td>
					<td colspan="1" align="center">qtde pecas</td>
					
				
					</tr>
			    </thead>
			  
			@foreach ($query_2 as $query2)
			  
				<tr>
				<td align="left"><a href="/dsimportdet/{{$query2->tipo}}/{{$query2->pedido}}">{{$query2->tipo.' '.$query2->pedido}}</a></td>	
				<td align="left"><a href="/dsimportdet?ref_go={{$query2->ref_go}}">{{$query2->ref_go}}</a></td>
				<td align="center">{{$query2->ref_despachante}}</td>
				<td align="center">{{$query2->ref_nac_01}}</td>
				<td align="left">{{$query2->fornecedor}}</td>
				<td align="center">{{$query2->ult_prox}}</td>
				<td align="center">{{$query2->tipoitem}}</td>
				<td align="center">{{$query2->codgrife}}</td>
				<td align="center">{{$query2->colmod}}</td>
				<td align="center">{{number_format($query2->qtde)}}</td>	
				
					
				</tr>
			@endforeach 
			
			</table>
			
		</div>
	</div>	
</div>
</h6>			
	
</form>

@stop