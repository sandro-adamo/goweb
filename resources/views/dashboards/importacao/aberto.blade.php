@extends('layout.principal')
@section('conteudo')

@php




$query_2 = \DB::select("

select * from (
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
				where ref_go not in ('LA200501','QGKI17-7B') and ult_status not in (980) 
				and prox_status not in (999) 
                and concat(ult_status,prox_status) not in ('999400')
				and left (gl_clas,2) not in ('CC','CF','DP','EE','ME','MM','OC','PR','UC','SW','VD')
			
			) as base 

			left join (select * from itens_estrutura   ) as estrutura
			on estrutura.id_filho = cod_item
		
        ) as final

			left join (select itens.id, secundario codsec, agrup, codgrife, colmod, desc_fornecedor fornecedor, left(linha,3) linha from itens left join fornecedores forn on forn.codfornecedor = itens.codfornecedor ) item
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
		<div class="col-md-12">
          <div class="nav-tabs-custom">
            	
	
	
	<ul class="nav nav-tabs">
		<li  class="active"><a href="#Aberto" data-toggle="tab">Aberto</a></li>
	
			<li><a href="#Removido" data-toggle="tab">Removido</a></li>
			<li><a href="#Li_solicitado" data-toggle="tab">Li_solicitado</a></li>


		
		
		
<div class="tab-content">
	

	
<div class="active tab-pane" id="Aberto">	
<div class="col-md-12">
<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
	<table class="table table-striped table-bordered compact" id="myTable">
		  <thead>	
			  <tr><td colspan="15">Importações em aberto </td></tr>
		  			
					<tr>
					<td>det</td>
					<td colspan="1" align="center">Pedido</td>
					<td colspan="1" align="center">ult_prox status</td>						
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
					<td colspan="1" align="center">impostos</td>
					<td colspan="1" align="center">icms</td>

					</tr>
			    </thead>
			  
			@foreach ($query_2 as $query2)
			 
			<tr>
			<td align="left"><a href="/import_form/?tipo={{$query2->tipo}}&pedido={{$query2->pedido}}" target="_blank">
				<i class="fa fa-file-text-o"></i></a></td>
			<td align="left"><a href="/dsimportdet/{{$query2->tipo}}/{{$query2->pedido}}">{{$query2->tipo.' '.$query2->pedido}}</a></td>
			<td align="center">{{$query2->ult_prox}} - {{$query2->desc_status}}</td>
	
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
	
			</tr>
			@endforeach 
		</table>
		</ul>
	</div>
</div>	



		


<div class="active tab-pane" id="Removido">	
<div class="col-md-12">
<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
	<table class="table table-striped table-bordered compact" id="myTable">
		  <thead>	
			  <tr><td colspan="15">Cargas removidas</td></tr>
		  			
					<tr>
					<td>det</td>
					<td colspan="1" align="center">Pedido</td>
					<td colspan="1" align="center">ult_prox status</td>						
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
						
					<td colspan="1" align="center">impostos</td>
					<td colspan="1" align="center">icms</td>
						
				
					
				
					</tr>
			    </thead>
			  
			@foreach ($query_2 as $query3)
				
				@php if ($query3->desc_status=="removido") 
				
			{ @endphp
		
			<tr>
			<td align="left"><a href="/import_form/?tipo={{$query3->tipo}}&pedido={{$query3->pedido}}" target="_blank">
				<i class="fa fa-file-text-o"></i></a></td>
			<td align="left"><a href="/dsimportdet/{{$query3->tipo}}/{{$query3->pedido}}">{{$query3->tipo.' '.$query3->pedido}}</a></td>
			<td align="center">{{$query3->ult_prox}} - {{$query3->desc_status}}</td>
	
			<td align="left">{{$query3->ref_go}}</td>
			<td align="center">{{$query3->ref}}</td>
			<td align="center">{{$query3->doc_agrup}}</td>
			<td align="left">{{$query3->fornecedor}}</td>
			<td align="center">{{$query3->tipoitem}}</td>
			<td align="center">{{$query3->codgrife}}</td>
			<td align="center">{{$query3->colmod}}</td>
			<td align="center">{{$query3->linha}}</td>
			<td align="center">{{number_format($query3->qtde)}}</td>	
			<td align="center">{{number_format($query3->atende)}}</td>
			<td align="center">{{number_format($query3->itens_trans)}}</td>
			<td align="center">{{$query3->impostos}}</td>
			<td align="center">{{$query3->icms}}</td>
	
			</tr>
			@php ;} else  { @endphp
		<tr></tr>
			@php  ;} @endphp
			
			@endforeach 
			

		</table>
	
		</ul>
		</div>	
	</div>

	
	
	
<div class="tab-pane" id="Li_solicitado">
<div class="col-md-12">
<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">	
	<table class="table table-striped table-bordered compact" id="myTable">
		  <thead>				
			 <tr><td colspan="15">Aguardando pgto Embarque</td></tr>
		  			
					<tr>
					<td>det</td>
					<td colspan="1" align="center">Pedido</td>
					<td colspan="1" align="center">ult_prox status</td>						
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
						
					<td colspan="1" align="center">impostos</td>
					<td colspan="1" align="center">icms</td>
						
				
					
				
					</tr>
			    </thead>
			  
			@foreach ($query_2 as $query4)
				
				@php if ($query4->desc_status=="li_solicitado" or $query4->desc_status=="li_deferida" or $query4->desc_status=="booking")
				
			{ @endphp
		
			<tr>
			<td align="left"><a href="/import_form/?tipo={{$query4->tipo}}&pedido={{$query4->pedido}}" target="_blank">
				<i class="fa fa-file-text-o"></i></a></td>
			<td align="left"><a href="/dsimportdet/{{$query4->tipo}}/{{$query4->pedido}}">{{$query4->tipo.' '.$query4->pedido}}</a></td>
			<td align="center">{{$query4->ult_prox}} - {{$query4->desc_status}}</td>
	
			<td align="left">{{$query4->ref_go}}</td>
			<td align="center">{{$query4->ref}}</td>
			<td align="center">{{$query4->doc_agrup}}</td>
			<td align="left">{{$query4->fornecedor}}</td>
			<td align="center">{{$query4->tipoitem}}</td>
			<td align="center">{{$query4->codgrife}}</td>
			<td align="center">{{$query4->colmod}}</td>
			<td align="center">{{$query4->linha}}</td>
			<td align="center">{{number_format($query4->qtde)}}</td>	
			<td align="center">{{number_format($query4->atende)}}</td>
			<td align="center">{{number_format($query4->itens_trans)}}</td>
			<td align="center">{{$query4->impostos}}</td>
			<td align="center">{{$query4->icms}}</td>
	
			</tr>
			@php ;} else  { @endphp
		<tr></tr>
			@php  ;} @endphp
			
			@endforeach 
			

		</table>
	
		</ul>
		</div>	
		</div>
	
	
	
</ul>		
		
</div>
</div>	
</div>
		
		
	</h6>				
	</form>


	
@stop