@extends('layout.principal')
@section('conteudo')

@php




$query_2 = \DB::select("

	select *,
	(impostos_nac/taxa_nac)*taxa1 prev_imposto,
	(icms_nac/taxa_nac)*taxa1 prev_icms,
	((impostos_nac/taxa_nac)*taxa1)+((icms_nac/taxa_nac)*taxa1) prev_total,
	case when status_pgto = 'pago' then 'green' when status_pgto = 'parcial' then 'orange' when status_pgto = 'aberto' then 'red' else '' end as coloremb, 

	case when status_pgto is null then '' else 'money' end as clasmoney,

	ifnull(valor_titulo,0)-ifnull(valor_pago,0)  embarque


	from (
	select *,
		case when infos.id is null then 'insert' else 'update' end as acao , infos.id id_info

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
					where dt_pedido >= '20220101' and ref_go not in ('LA200501','QGKI17-7B') 
					and ult_status not in (980) 
					and prox_status not in (999) 
					and ((imp.tipo = 'op' and gl_clas  in ('AG01','rv01','pa01','mp01' )) or imp.tipo = 'oi')

				  -- and concat(ult_status,prox_status) not in ('999400')
				  -- and left (gl_clas,2) not in ('CC','CF','DP','EE','ME','MM','OC','PR','UC','SW','VD')

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


	 left join (select * from compras_infos ) as infos
	 on infos.id_pedido = base1.pedido  and infos.tipo_pedido = base1.tipo
	) as base2

	left join (select moeda, taxa1 from compras_registros  order by created_at desc limit 1 ) as moeda
	on moeda.moeda = base2.moeda_nac


		left join ( 

			select *, case when valor_pago is null then 'aberto' when valor_pago < valor_titulo then 'parcial' else 'pago' end as status_pgto
			from (
				select id_pedido, origem, ct.tipo tipo_tit, max(ct.valor) valor_titulo, sum(cp.valor) valor_parcelas, sum(cg.valor_pago) valor_pago
				from compras_titulos ct
					left join compras_parcelas cp on cp.id_titulo = ct.numero
					left join compras_pagamentos cg on cg.id_parcela = cp.id
					where ct.tipo = 'EMBARQUE'
				group by id_pedido, origem, ct.tipo
                ) as fim
			) as ct
            
	on ct.id_pedido = base2.pedido and ct.origem = base2.tipo


");
			  

$query_r = \DB::select("select taxa1, taxa2, taxa3 from compras_registros order by created_at desc limit 1");


@endphp

				
<div class="row"> 
	<div class="col-md-6">
	<!-- if taxa_calculo -->
	<!-- novo controler insere tabela compras_registros e salva na linha compras_infos -->
	
	
	<h3>recalcular</h3>
	<form action="/import_form/gravareg" method="post" class="form-horizontal"> @csrf
		
		<input type="hidden" id="id_pedido" name="id_pedido" size="50" value=100201>
		<input type="hidden" id="tipo_pedido" name="tipo_pedido" size="50" value='OI'>
		<input type="hidden" id="moeda" name="moeda" size="50" value='dolar'>
		
		<td>Dolar</td>
		<td><input type="text" id="taxa1" name="taxa1" size="8" value='{{$query_r[0]->taxa1}}' ></td>
		<td><input type="text" id="taxa2" name="taxa2" size="8" value='{{$query_r[0]->taxa2}}' ></td>
		<td><input type="text" id="taxa3" name="taxa3" size="8" value='{{$query_r[0]->taxa3}}' ></td>
	
		<td>Euro</td>
		<td><input type="text" id="euro1" name="euro1" size="8" value='' ></td>
		<td><input type="text" id="euro2" name="euro2" size="8" value='' ></td>
		<td><input type="text" id="euro3" name="euro3" size="8" value='' ></td>
		
		
	<button type="submit" class="btn btn-primary"><i class="fa fa-refresh"></i> Gravar</button>	
	</form>
	</div>
</div>




</br>







<div class="row"> 
	<div class="col-md-18">
     <div class="nav-tabs-custom">
           
			<ul class="nav nav-tabs">
			<li  class="active"><a href="#aberto" data-toggle="tab">Aberto</a></li>
			<li><a href="#removido" data-toggle="tab">Removido</a></li>
			<li><a href="#Transito" data-toggle="tab">Transito</a></li>
			<li><a href="#Embarque" data-toggle="tab">Embarque</a></li>
			<li><a href="#Kering" data-toggle="tab">Kering</a></li>
			<li><a href="#perdimento" data-toggle="tab">Perdimento</a></li>
			<li><a href="#Kering" data-toggle="tab">Sem ped_jde</a></li>
			<li><a href="#leadtime" data-toggle="tab">leadtime</a></li>
			<li><a href="#documentos" data-toggle="tab">documentos</a></li>
			<li><a href="#periodos" data-toggle="tab">periodos</a></li>
            </ul>
			    
			  
<div class="tab-content">

	

		
		
	
<div class="active tab-pane" id="aberto">	
<div class="box-header with-border">
	 <tr><td>Cargas em aberto</td>
				<td><i class="text-right">Botao adiciona invoice</i></td>
	</tr>
	<h6> 
	<table class="tabela2 table-striped table-bordered compact">
		<thead>	
					<tr>
					
					<td></td>
					<td colspan="1" align="center">Pedido</td>
					<td colspan="1" align="center">ult_prox status</td>						
					<td colspan="1" align="center">Invoice</td>				
					<td colspan="1" align="center">Agrup</td>
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
					<td colspan="1" align="center">obs</td>
   		
					</tr>
			 </thead>
			  
			@foreach ($query_2 as $query2)
	
		
			
			<tr>
				
				
				<form action="/import_form/grava" method="post" class="form-horizontal"> @csrf
				<input type="hidden" id="id_info" name="id_info" size="50" value={{$query2->id_info}}>		
				<input type="hidden" id="acao" name="acao" size="50" value={{$query2->acao}} >
				<input type="hidden" id="pedido" name="pedido" size="50" value={{$query2->pedido}} >
				<input type="hidden" id="tipo" name="tipo" size="50" value={{$query2->tipo}} >
				
				
				
		<!-- 	<td><button type="submit"><i class="fa fa-refresh"></i></button>	</td> -->
					
			<td><i class="fa fa-{{$query2->clasmoney}} text-{{$query2->coloremb}}"></i></td>	
			
			<td align="left">
				<a href="/import_form/?tipo={{$query2->tipo}}&pedido={{$query2->pedido}}" target="_blank">
					<i class="fa fa-file-text-o"></i>
				</a>
				<a href="/dsimportdet/{{$query2->tipo}}/{{$query2->pedido}}">{{$query2->tipo.' '.$query2->pedido}}</a>
			</td>

					
			<td align="center">{{$query2->ult_prox}} - {{$query2->desc_status}}</td>
	
			<td align="left">{{$query2->ref_go}}</td>
			
				
					<td>{{$query2->tipo_agrup}} - {{$query2->doc_agrup}}
							 <!--  <select class="form-control" name="tipo_agrup" >	
							  <option value="{{$query2->tipo_agrup}}">{{$query2->tipo_agrup}}</option> 
							  <option value="FR">FR</option>
							  <option value="AC">AC</option>
						      </select>
							-->
							  
						  </td>
					
					
		
				<!--	<td> <input type="text" id="doc_agrup" name="doc_agrup" size="8" value='{{$query2->doc_agrup}}' ></td> -->

				
			<td align="left">{{$query2->fornecedor}}</td>
			<td align="center">{{$query2->tipoitem}}</td>
			<td align="left">{{$query2->codgrife}}</td>
			<td align="left">{{$query2->colmod}}</td>
			<td align="left">{{$query2->linha}}</td>
			<td align="center">{{number_format($query2->qtde)}}</td>	
			<td align="center">{{number_format($query2->atende)}}</td>
			<td align="center">{{number_format($query2->itens_trans)}}</td>
			<td align="center">{{number_format($query2->prev_imposto,2)}}</td>
			<td align="center">{{number_format($query2->prev_icms,2)}}</td>
			<td align="left">{{$query2->obs_invoice}}</td>
	

			</tr>
				</form>	
			@endforeach 
		</table>
		</h6>	
	</div>
</div>	

	
	

<div class="tab-pane" id="removido">	
<div class="box-header with-border">
	 <tr><td>Cargas removidas (379 / 375 - removido)</td></tr>
	<h6>
	<table class="tabela2 table-striped table-bordered compact">
		  <thead>	
			 
		  			
					<tr>
					
					<td colspan="1" align="center" widht="30%">Pedido</td>
										
					<td colspan="1" align="center">Invoice</td>				
					<td colspan="1" align="center">Perdimento</td>
					<td colspan="1" align="center">conex</td>
					<td colspan="1" align="center">fornecedor</td>
					
					<td colspan="1" align="center">Tipo_item</td>
					<td colspan="1" align="center">Grifes </td>
					<td colspan="1" align="center">Colecoes</td>
					<td colspan="1" align="center">Linhas</td>
					<td colspan="1" align="center">qtde pecas</td>
					<td colspan="1" align="center">atende BO</td>
					<td colspan="1" align="center">itens CET</td>
						
					<td colspan="1" align="center">Prev impostos</td>
					<td colspan="1" align="center">Prev icms</td>
					<td colspan="1" align="center">Prev Total</td>
						<td colspan="1" align="center">Obs</td>
					</tr>
			    </thead>
			  
			@foreach ($query_2 as $query3)
				
				@php if ($query3->desc_status=="removido") 
				
			{ @endphp
	
			<tr>
			<td align="left"><a href="/import_form/?tipo={{$query3->tipo}}&pedido={{$query3->pedido}}" target="_blank">
				<i class="fa fa-file-text-o"></i></a>
			<a href="/dsimportdet/{{$query3->tipo}}/{{$query3->pedido}}">{{$query3->tipo.' '.$query3->pedido}}</a></td>
			
	
			<td align="left">{{$query3->ref_go}}</td>
			<td align="center">{{$query3->dt_perdimento}}</td>
			<td>{{$query3->tipo_agrup}} - {{$query3->doc_agrup}}</td>
			<td align="left">{{$query3->fornecedor}}</td>
			<td align="center">{{$query3->tipoitem}}</td>
			<td align="center">{{$query3->codgrife}}</td>
			<td align="center">{{$query3->colmod}}</td>
			<td align="center">{{$query3->linha}}</td>
			<td align="center">{{number_format($query3->qtde)}}</td>	
			<td align="center">{{number_format($query3->atende)}}</td>
			<td align="center">{{number_format($query3->itens_trans)}}</td>
			<td align="center">{{number_format($query3->impostos_nac,2)}}</td>
			<td align="center">{{number_format($query3->icms_nac,2)}}</td>
			<td align="center">{{number_format($query3->prev_total,2)}}</td>
			<td align="left">{{$query3->obs_invoice}}</td>

			</tr>
	
			@php ;} else  { @endphp
	
			@php  ;} @endphp
			
			@endforeach 
			

		</table>
		</h6>
		</div>	
	</div>

	
	
	
<div class="tab-pane" id="Transito">
<div class="box-header with-border">
	 <tr><td colspan="15">Transito ( 359 / 365 - booking)</td></tr>
	<h6>
	<table class="tabela2 table-striped table-bordered compact" id="myTable3">
		  <thead>				
			
		  		
					<tr><td>det</td>
			
					<td colspan="1" align="center">Pedido</td>
					<td colspan="1" align="center">ult_prox status</td>						
					<td colspan="1" align="center">Invoice</td>				
				
					<td colspan="1" align="center">conex</td>
					<td colspan="1" align="center">fornecedor</td>
					
					<td colspan="1" align="center">Tipo_item</td>
					<td colspan="1" align="center">Grifes </td>
					<td colspan="1" align="center">Colecoes</td>
					<td colspan="1" align="center">Linhas</td>
					<td colspan="1" align="center">qtde pecas</td>
					<td colspan="1" align="center">atende BO</td>
					<td colspan="1" align="center">itens CET</td>
						
					<td colspan="1" align="center">Prev Chegada</td>
					<td colspan="1" align="center">Trans Int</td>
					<td colspan="1" align="center">obs chegada</td>
						
				
					
				
					</tr>
			    </thead>
			  
			@foreach ($query_2 as $query4)
				
				@php if ($query4->desc_status=="booking")
				
			{ @endphp

			<tr>
			<td><i class="fa fa-{{$query4->clasmoney}} text-{{$query4->coloremb}}"></i></td>
			
			<td align="left"><a href="/import_form/?tipo={{$query4->tipo}}&pedido={{$query4->pedido}}" target="_blank">
				<i class="fa fa-file-text-o"></i></a>
			<a href="/dsimportdet/{{$query4->tipo}}/{{$query4->pedido}}">{{$query4->tipo.' '.$query4->pedido}}</a></td>
				
				
			<td align="center">{{$query4->ult_prox}}</td>
	
			<td align="left">{{$query4->ref_go}}</td>
			
			<td>{{$query4->tipo_agrup}} - {{$query4->doc_agrup}}</td>
			<td align="left">{{$query4->fornecedor}}</td>
			<td align="center">{{$query4->tipoitem}}</td>
			<td align="center">{{$query4->codgrife}}</td>
			<td align="center">{{$query4->colmod}}</td>
			<td align="center">{{$query4->linha}}</td>
			<td align="center">{{number_format($query4->qtde)}}</td>	
			<td align="center">{{number_format($query4->atende)}}</td>
			<td align="center">{{number_format($query4->itens_trans)}}</td>
			<td align="center">{{$query4->dt_prev_chegada}}</td>
			<td align="center">{{$query4->an8_agente_int}}</td>
				<td align="center">{{$query4->obs_chegada}}</td>
	
			</tr>
		
			@php ;} else  { @endphp
	
			@php  ;} @endphp
			
			@endforeach 	

		</table>
		</h6>
		</div>	
		</div>
	






<div class="tab-pane" id="Embarque">
<div class="box-header with-border">
<h6> 
	<tr><td colspan="15">Aguardando Embarque (359 / 355 - li_deferida | embarque) </td></tr>
	<table class="tabela2 table-striped table-bordered compact" >
		
		  <thead>				
			 
		  			
					<tr>
						
					<td>det</td>
					<td colspan="1" align="center">Pedido</td>
					<td colspan="1" align="center">ult_prox status</td>						
					<td colspan="1" align="center">Invoice</td>				
					<td colspan="1" align="center">Dt embarquep</td>
					<td colspan="1" align="center">conex</td>
					<td colspan="1" align="center">fornecedor</td>
					
					<td colspan="1" align="center">Tipo_item</td>
					<td colspan="1" align="center">Grifes </td>
					<td colspan="1" align="center">Colecoes</td>
					<td colspan="1" align="center">Linhas</td>
					<td colspan="1" align="center">qtde pecas</td>
					<td colspan="1" align="center">atende BO</td>
					<td colspan="1" align="center">itens CET</td>	
					<td colspan="1" align="center">valor Embarque</td>
					
						
				
					
				
					</tr>
			    </thead>
			  
			@foreach ($query_2 as $query5)
				
				@php if ($query5->desc_status=="li_deferida" or $query5->desc_status=="li_solicitado")
				
			{ @endphp
		
			<tr>
				<td><i class="fa fa-{{$query5->clasmoney}} text-{{$query5->coloremb}}"></i></td>	
			<td align="left"><a href="/import_form/?tipo={{$query5->tipo}}&pedido={{$query5->pedido}}" target="_blank">
				<i class="fa fa-file-text-o"></i></a>
			<a href="/dsimportdet/{{$query5->tipo}}/{{$query5->pedido}}">{{$query5->tipo.' '.$query5->pedido}}</a></td>
				
			<td align="center">{{$query5->ult_prox}} - {{$query5->desc_status}}</td>
	
			<td align="left">{{$query5->ref_go}}</td>
			<td align="center">{{$query5->dt_emb_int}}</td>
			<td>{{$query5->tipo_agrup}} - {{$query5->doc_agrup}}</td>
			<td align="left">{{$query5->fornecedor}}</td>
			<td align="center">{{$query5->tipoitem}}</td>
			<td align="center">{{$query5->codgrife}}</td>
			<td align="center">{{$query5->colmod}}</td>
			<td align="center">{{$query5->linha}}</td>
			<td align="center">{{number_format($query5->qtde)}}</td>	
			<td align="center">{{number_format($query5->atende)}}</td>
			<td align="center">{{number_format($query5->itens_trans)}}</td>
			<td align="center">{{$query5->embarque}}</td>
	
	
			</tr>
			@php ;} else  { @endphp
		
			@php  ;} @endphp
			
			@endforeach 
			

		</table>
		</h6> 
		</div>	
		</div>	
	


	
	
	
<div class="tab-pane" id="Kering">
<div class="box-header with-border">
	<h6>  
		<td colspan="15">Kering</td>
		
	<table class="tabela2 table-striped table-bordered compact">
		  <thead>				
			
		  			
					<tr>
					<td>det</td>
					<td colspan="1" align="center">Pedido</td>
					<td colspan="1" align="center">ult_prox status</td>						
					<td colspan="1" align="center">Invoice</td>				
					<td colspan="1" align="center">ref desp</td>
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
			  
			@foreach ($query_2 as $query6)
				
				@php if ($query6->fornecedor=="KERING ")
				
			{ @endphp
		
			<tr>
			<td align="left"><a href="/import_form/?tipo={{$query6->tipo}}&pedido={{$query6->pedido}}" target="_blank">
				<i class="fa fa-file-text-o"></i></a></td>
			<td align="left"><a href="/dsimportdet/{{$query6->tipo}}/{{$query6->pedido}}">{{$query6->tipo.' '.$query6->pedido}}</a></td>
			<td align="center">{{$query6->ult_prox}} - {{$query6->desc_status}}</td>
	
			<td align="left">{{$query6->ref_go}}</td>
			<td align="center">{{$query6->ref}}</td>
			
		
			<td align="center">{{$query6->tipoitem}}</td>
			<td align="center">{{$query6->codgrife}}</td>
			<td align="center">{{$query6->colmod}}</td>
			<td align="center">{{$query6->linha}}</td>
			<td align="center">{{number_format($query6->qtde)}}</td>	
			<td align="center">{{number_format($query6->atende)}}</td>
			<td align="center">{{number_format($query6->itens_trans)}}</td>
			<td align="center">{{$query6->impostos_nac}}</td>
			<td align="center">{{$query6->icms_nac}}</td>
	
			</tr>
			@php ;} else  { @endphp
			<tr></tr>
			@php  ;} @endphp
			
			@endforeach 
			

		</table>
		<h6> 

		</div>	
	</div>	


	
	
<div class="tab-pane" id="perdimento">	
<div class="box-header with-border">
	 <tr><td>Datas para perdimento</td></tr>
	<h6>
	<table class="tabela2 table-striped table-bordered compact" id="myTable6">
		  <thead>	
			 
		  			
					<tr>
					<td colspan="1" align="center" widht="30%">Dt perdimento</td>
					<td colspan="1" align="center" widht="30%">Pedido</td>										
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
						
					<td colspan="1" align="center">Prev impostos</td>
					<td colspan="1" align="center">Prev icms</td>
					<td colspan="1" align="center">Prev Total</td>
						<td colspan="1" align="center">Obs</td>
					</tr>
			    </thead>
			  
			@foreach ($query_2 as $query3)
				
				@php if ($query3->dt_perdimento<>"") 
				
			{ @endphp
	
			<tr>
				<td align="left">{{$query3->dt_perdimento}}</td>
			<td align="left"><a href="/import_form/?tipo={{$query3->tipo}}&pedido={{$query3->pedido}}" target="_blank">
				<i class="fa fa-file-text-o"></i></a>
			<a href="/dsimportdet/{{$query3->tipo}}/{{$query3->pedido}}">{{$query3->tipo.' '.$query3->pedido}}</a></td>
			
	
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
			<td align="center">{{number_format($query3->impostos_nac,2)}}</td>
			<td align="center">{{number_format($query3->icms_nac,2)}}</td>
			<td align="center">{{number_format($query3->prev_total,2)}}</td>
			<td align="left">{{$query3->obs_invoice}}</td>

			</tr>
	
			@php ;} else  { @endphp
	
			@php  ;} @endphp
			
			@endforeach 
			

		</table>
		</h6>
		</div>	
	</div>
	
	
	
	
	
<div class="tab-pane" id="leadtime">	
<div class="box-header with-border">
	 <tr><td>Cargas removidas (379 / 375 - removido)</td></tr>
	<h6>
	<table class="tabela2 table-striped table-bordered compact" id="myTable7">
		  <thead>	
			 
		  			
					<tr>
					
					<td colspan="1" align="center" widht="30%">Pedido</td>
										
					<td colspan="1" align="center">invoice</td>				
					<td colspan="1" align="center">solic_LI</td>	
						<td colspan="1" align="center">defer_LI</td>	
						<td colspan="1" align="center">autor_emb</td>	
						<td colspan="1" align="center">embarque</td>	
						<td colspan="1" align="center">prev_chegada</td>	
						<td colspan="1" align="center">chegada</td>	
						<td colspan="1" align="center">remocao</td>	
						<td colspan="1" align="center">registro_DI</td>	
						<td colspan="1" align="center">prev_tr_nac</td>	
						<td colspan="1" align="center">carregamento</td>	
						<td colspan="1" align="center">entrega_fabr</td>
						<td colspan="1" align="center">perdimento</td>
					
						
					</tr>
			    </thead>
			  
			@foreach ($query_2 as $query3)
				
				@php if ($query3->desc_status=="li_deferida" or $query3->desc_status=="booking")
				
			{ @endphp
	
			<tr>
			<td align="left"><a href="/import_form/?tipo={{$query3->tipo}}&pedido={{$query3->pedido}}" target="_blank">
				<i class="fa fa-file-text-o"></i></a>
			<a href="/dsimportdet/{{$query3->tipo}}/{{$query3->pedido}}">{{$query3->tipo.' '.$query3->pedido}}</a></td>
			
	
			<td align="left">{{$query3->dt_invoice}}</td>
			<td align="center">{{$query3->dt_sol_li}}</td>
			<td align="center">{{$query3->dt_def_li}}</td>
			<td align="left">{{$query3->dt_sol_li}}</td>
			<td align="center">{{$query3->dt_sol_li}}</td>
			<td align="center">{{$query3->dt_aut_embarque}}</td>
			<td align="center">{{$query3->dt_emb_int}}</td>
			<td align="center">{{$query3->dt_prev_chegada}}</td>
			<td align="left">{{$query3->dt_chegada}}</td>
			<td align="center">{{$query3->dt_remocao}}</td>
			<td align="center">{{$query3->dt_registro}}</td>
			<td align="center">{{$query3->dt_prev_embnac}}</td>
			<td align="center">{{$query3->dt_emb_nac}}</td>
				
				<td align="left">{{$query3->dt_recebimento}}</td>
			<td align="center">{{$query3->dt_perdimento}}</td>
	
			</tr>
	
			@php ;} else  { @endphp
	
			@php  ;} @endphp
			
			@endforeach 
			

		</table>
		</h6>
		</div>	
	</div>
	
	
	
	<div class="tab-pane" id="documentos">	
<div class="box-header with-border">
	 <tr><td>Cargas removidas (379 / 375 - removido)</td></tr>
	<h6>
	<table class="tabela2 table-striped table-bordered compact" id="myTable8">
		  <thead>	
			 
		  			
					<tr>
					
					<td colspan="1" align="center" widht="30%">Pedido</td>							
					<td colspan="1" align="center">invoice</td>				
					<td colspan="1" align="center">ref_processo</td>	
					<td colspan="1" align="center">ok</td>	
					<td colspan="1" align="center">sx</td>	
					<td colspan="1" align="center">awb</td>	
					<td colspan="1" align="center">di</td>	
					<td colspan="1" align="center">protocolo</td>	
					<td colspan="1" align="center">pre_nf</td>	
					<td colspan="1" align="center">NF</td>	
					<td colspan="1" align="center">periodo</td>	
				
					</tr>
			    </thead>
			  
			@foreach ($query_2 as $query3)
				
				@php if ($query3->desc_status=="li_deferida" or $query3->desc_status=="booking")
				
			{ @endphp
	
			<tr>
			<td align="left"><a href="/import_form/?tipo={{$query3->tipo}}&pedido={{$query3->pedido}}" target="_blank">
				<i class="fa fa-file-text-o"></i></a>
			<a href="/dsimportdet/{{$query3->tipo}}/{{$query3->pedido}}">{{$query3->tipo.' '.$query3->pedido}}</a></td>
			
	
			<td align="left">{{$query3->dt_invoice}}</td>
			<td align="center">{{$query3->dt_sol_li}}</td>
			<td align="center">{{$query3->dt_def_li}}</td>
			<td align="left">{{$query3->dt_sol_li}}</td>
			<td align="center">{{$query3->dt_sol_li}}</td>
			<td align="center">{{$query3->dt_aut_embarque}}</td>
			<td align="center">{{$query3->dt_emb_int}}</td>
			<td align="center">{{$query3->dt_prev_chegada}}</td>
			<td align="left">{{$query3->dt_chegada}}</td>
			<td align="center">{{$query3->dt_remocao}}</td>
			<td align="center">{{$query3->dt_registro}}</td>
			<td align="center">{{$query3->dt_prev_embnac}}</td>
			<td align="center">{{$query3->dt_emb_nac}}</td>
				
				<td align="left">{{$query3->dt_recebimento}}</td>
			<td align="center">{{$query3->dt_perdimento}}</td>
	
			</tr>
	
			@php ;} else  { @endphp
	
			@php  ;} @endphp
			
			@endforeach 
			

		</table>
		</h6>
		</div>	
	</div>
	
	
	
</div>			  
</div>	
</div>
</div>


	
@stop