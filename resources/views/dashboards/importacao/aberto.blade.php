@extends('layout.principal')
@section('conteudo')

@php




$query_2 = \DB::select(" 
select *
from (
	select pedido, tipo, ref_go, concat(trim(ref_despachante),' ',trim(ref_nac_01)) ref, ult_prox, desc_status, group_concat(distinct left(fornecedor,20),' ') fornecedor,  
		group_concat(distinct tipoitem,' ') tipoitem, group_concat(distinct codgrife,' ') codgrife, group_concat(distinct linha,' ') linha,
		case when CHAR_LENGTH(group_concat(distinct colmod,' ')) > 26 then concat('...',right(group_concat(distinct colmod,' '),26)) else group_concat(distinct colmod,' ') end as colmod, 
		sum(qtde) qtde, sum(atende) atende, sum(itens_trans) itens_trans
		from (
		
		select *, case when orcamentos > qtde then qtde else orcamentos end as atende from (
		
			select pedido, tipo, ref_go, ref_despachante, ref_nac_01, ult_prox, desc_status, secundario, cod_item, codtipoitem, tipoitem, id_pai,
			item_pai, tipo_pai, agrupador, codgrife, colmod, fornecedor, linha,
			ifnull((select sum(orcamento_bloq+orcamento_liber) from go_storage.sintetico_estoque sint where sint.id_item = final.id_pai),0) orcamentos,
			ifnull((select sum(itens_trans) from go_storage.sintetico_estoque sint where sint.id_item = final.id_pai),0) itens_trans,
					/**	ifnull((select sum(qtde) qtde_aberto from go.vendas_jde vds
						where ult_status not in ('980') and tipo_item = 006 and prox_status = 515 and vds.id_item = final.id_pai),0) as orcamentos,
						
					**/
			sum(qtde) qtde
		
			from (

				select base.*, tipo_pai, agrupador,
				case when item_pai is null then secundario else item_pai end as item_pai,
				case when id_pai is null then cod_item else id_pai end as id_pai
				from (

					select pedido, tipo, ref_go, ref_despachante, ref_nac_01,  concat(ult_status, ' / ',prox_status) ult_prox, imp.secundario, cod_item, codtipoitem,
						
					case 
					when prox_status = 230 then 'ped_inserido' 
					when prox_status = 280 then 'PL_recebido' 
					when prox_status = 345 then 'confirmado' 
					when prox_status = 350 then 'li_solicitado'
					when prox_status = 355 then 'li_deferida'
					when prox_status = 359 then 'emb_autorizado'
					when prox_status = 365 then 'booking'
					when prox_status = 369 then 'chegada_Br'
					when prox_status = 375 then 'removido'
					when prox_status = 379 then 'registrado'
					when prox_status = 385 then 'nf_emitida'
					when prox_status = 390 then 'carregada'
					when prox_status = 400 then 'chegou_TO' else '' end as desc_status,
					
					case  when codtipoitem = 006 then 'PECA' 
					when (left(imp.secundario,3) = 'FR ' or left(imp.secundario,6) = 'PONTE ') then 'FRENTE' 
					when left(imp.secundario,2) IN ('LE','LD','HE','HD','PL','SC','BL') then 'ACESSORIOS'
					else 'OUTROS' end as tipoitem, qtde_sol qtde
					 
					from importacoes_pedidos imp 
					left join itens on itens.id = cod_item		
					where ref_go not in ('LA200501','QGKI17-7B') and ult_status not in (980) and prox_status not in (999,400)
					-- and ref_go = '908211007-m'
					
				) as base 

				left join (select * from itens_estrutura   ) as estrutura
				on estrutura.id_filho = cod_item
			
			) as final

			left join (select id, secundario codsec, agrup, codgrife, colmod, fornecedor, left(linha,3) linha from itens ) item
			on item.id = final.id_pai
			
			group by pedido, tipo, ref_go, ref_despachante, ref_nac_01, ult_prox, desc_status, secundario, cod_item, codtipoitem, tipoitem, id_pai,
			item_pai, tipo_pai, agrupador, codgrife, colmod, fornecedor, linha
		
		) as final1
		) as final2
		group by pedido, tipo, ref_go, ref_despachante, ref_nac_01, ult_prox, desc_status
) as base1

 left join (select * from compras_registros ) as reg
 on reg.id_pedido = base1.pedido  and reg.tipo_pedido = base1.tipo

left join (select * from compras_infos ) as infos
 on infos.id_pedido = base1.pedido  and infos.tipo_pedido = base1.tipo

");
			  
			
@endphp

<form action="" method="get"> 

<h6>

	
							
<div class="row"> 
	
		<div class="col-md-15">	
		<div class="box box-body">

	<div class="table-responsive">		
	   <table class="table table-striped table-bordered compact" id="myTable">
		  <thead>	
			
		 <tr>	

	 		<td colspan="15">Importações em aberto </td>
		
				</tr>
		  			
					<tr>
					<td colspan="1" align="center">aaa</td>
					<td colspan="1" align="center">ult_prox status</td>
					<td colspan="1" align="center">Pedido</td>	
					<td colspan="1" align="center">Invoice</td>				
					<td colspan="1" align="center">ref desp</td>
					<td colspan="1" align="center">conex</td>
					<td colspan="1" align="center">fornecedor</td>
					
					<td colspan="1" align="center">Tipo_item</td>
					<td colspan="1" align="center">Grifes </td>
					<td colspan="1" align="center">Colecoes</td>
					<td colspan="1" align="center">Linhas</td>
					<td colspan="1" align="center">qtde pecas</td>
					<td colspan="1" align="center">atende BO</td>
					<td colspan="1" align="center">itens CET</td>
						
					<td colspan="1" align="center">itens CEP</td>
					<td colspan="1" align="center">dt perdimento</td>
						
					<td colspan="1" align="center">IMPOSTOS</td>
					<td colspan="1" align="center">ICMS</td>
					<td colspan="1" align="center">TOTAL NAC</td>
						
						
					
					
					
				
					</tr>
			    </thead>
			  
			@foreach ($query_2 as $query2)
			  
			<tr>
			<td align="left"><a href="/titulos_form?tipo={{$query2->tipo}}&pedido={{$query2->pedido}}">{{$query2->tipo.' '.$query2->pedido}}</a></td>
				
			<td align="center">{{$query2->ult_prox}} - {{$query2->desc_status}}</td>
			<td align="left"><a href="/dsimportdet?tipo={{$query2->tipo}}&pedido={{$query2->pedido}}">{{$query2->tipo.' '.$query2->pedido}}</a></td>	
			<td align="left">{{$query2->ref_go}}</td>
			<td align="center">{{$query2->ref}}</td>
			<td align="center">{{$query2->doc_agrup}}</td>
			<td align="left">{{$query2->fornecedor}}</td>
			<td align="center">{{$query2->tipoitem}}</td>
			<td align="center">{{$query2->codgrife}}</td>
			<td align="center">{{$query2->colmod}}</td>
			<td align="center">{{$query2->linha}}</td>
			<td align="center">{{number_format($query2->qtde)}}</td>	
			<td align="center">{{number_format($query2->atende)}}</td>
			<td align="center">{{number_format($query2->itens_trans)}}</td>
			<td align="center">{{$query2->impostos}}</td>
			<td align="center">{{$query2->icms}}</td>
			<td></td>
			<td></td>
			<td></td>
			</tr>
			@endforeach 
			

		</table>
			
		</div>
			</div>
	</div>	
	
	</div>
	</h6>				
	</form>


@stop