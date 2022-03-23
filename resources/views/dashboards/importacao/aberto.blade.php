@extends('layout.principal')
@section('conteudo')

@php




$query_2 = \DB::select(" 
select * from (
	select pedido, tipo, ref_go, concat(trim(ref_despachante),' ',trim(ref_nac_01)) ref, ult_prox, desc_status, group_concat(distinct left(fornecedor,20),' ') fornecedor,  
	group_concat(distinct tipoitem,' ') tipoitem, group_concat(distinct codgrife,' ') codgrife, group_concat(distinct linha,' ') linha,
	case when CHAR_LENGTH(group_concat(distinct colmod,' ')) > 26 then concat('...',right(group_concat(distinct colmod,' '),26)) else group_concat(distinct colmod,' ') end as colmod, 
	sum(qtde) qtde, sum(atende) atende 

	from (

	select *, case when orcamentos > qtde then qtde else orcamentos end as atende from (
		select pedido, tipo, ref_go, ref_despachante, ref_nac_01, ult_prox, desc_status, secundario, cod_item, codtipoitem, tipoitem, id_pai,
		item_pai, tipo_pai, id_filho, tipo_filho, agrupador, codgrife, colmod, fornecedor,  item, linha,
		
			ifnull((select sum(qtde) qtde_aberto
			from go.vendas_jde vds
			left join go.itens on itens.id = vds.id_item
			where ult_status not in ('980') and codtipoitem = 006 and prox_status = 515 
			and vds.item = final.item
			),0) as orcamentos,
		sum(qtde) qtde
		
		from (
			select *, case when item_pai is null then secundario else item_pai end as item 
			from (

				select pedido, tipo, ref_go, ref_despachante, ref_nac_01, 
				concat(ult_status, ' / ',prox_status) ult_prox, imp.secundario, cod_item, codtipoitem,
				
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
				where ref_go not in ('LA200501','QGKI17-7B') and 
				ult_status not in (980) and prox_status not in (999,400)


				
			) as base 

				left join (select * from itens_estrutura   ) as estrutura
				on estrutura.id_filho = cod_item
		) as final

		left join (select secundario codsec, agrup, codgrife, colmod, fornecedor, left(linha,3) linha from itens ) item
		on item.codsec = final.item        
		
		where  tipoitem in ('FRENTE','PECA', 'ACESSORIOS', 'MPDV','AGREGADOS')


	group by pedido, tipo, ref_go, ref_despachante, ref_nac_01, ult_prox, desc_status, secundario, cod_item, codtipoitem, tipoitem, id_pai,
	item_pai, tipo_pai, id_filho, tipo_filho, agrupador, codgrife, colmod, fornecedor, linha

	) as final1

	) as final2
	group by pedido, tipo, ref_go, ref_despachante, ref_nac_01, ult_prox, desc_status
  
) as final

	left join (select * from exemplos ) as ex
    on ex.id_pedido = final.pedido

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

	 		<td colspan="12">Importações em aberto </td>
		
				</tr>
		  			
					<tr>
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
					
					
				
					</tr>
			    </thead>
			  
			@foreach ($query_2 as $query2)
			  
			<tr>
			<td align="center">{{$query2->ult_prox}} - {{$query2->desc_status}}</td>
			<td align="left"><a href="/titulos_form?tipo={{$query2->tipo}}&pedido={{$query2->pedido}}">{{$query2->tipo.' '.$query2->pedido}}</a></td>	
			<td align="left"><a href="/dsimportdet?tipo={{$query2->tipo}}&pedido={{$query2->pedido}}">{{$query2->tipo.' '.$query2->pedido}}</a></td>	
			<td align="left">{{$query2->ref_go}}</td>
			<td align="center">{{$query2->ref}}</td>
			<td></td>
			<td align="left">{{$query2->fornecedor}}</td>
			<td align="center">{{$query2->tipoitem}}</td>
			<td align="center">{{$query2->codgrife}}</td>
			<td align="center">{{$query2->colmod}}</td>
			<td align="center">{{$query2->linha}}</td>
			<td align="center">{{number_format($query2->qtde)}}</td>	
			<td align="center">{{number_format($query2->atende)}}</td>

			</tr>
			@endforeach 
			

		</table>
			
		</div>
	</div>	
	
	</div>
	</h6>				
	</form>


@stop