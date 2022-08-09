@extends('layout.principal')
@section('conteudo')

@php




$query_2 = \DB::select("

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
				where ref_go not in ('LA200501','QGKI17-7B') and ult_status not in (980) 
				and prox_status not in (999) 
				and ((imp.tipo = 'op' and gl_clas  in ('AG01','rv01','pa01','mp01' )) or imp.tipo = 'oi')

              --   and concat(ult_status,prox_status) not in ('999400')
			  -- 	and left (gl_clas,2) not in ('CC','CF','DP','EE','ME','MM','OC','PR','UC','SW','VD')
					
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

");
			  
			
@endphp

<div class="row"> 
<div class="col-md-6">
	<!-- if taxa_calculo -->
	<!-- novo controler insere tabela compras_registros e salva na linha compras_infos -->
	
	<h3>recalcular</h3>
	<form action="/import_form/gravareg" method="post" class="form-horizontal"> @csrf
				<input type="hidden" id="id_info" name="id_info" size="50" value={{$query_2[0]->id_info}}>
				<input type="hidden" id="acao" name="acao" size="50" value='update'>
		
		<td>Dolar</td>
		<td><input type="text" id="dolar1" name="dolar1" size="8" value='' ></td>
		<td><input type="text" id="dolar2" name="dolar2" size="8" value='' ></td>
		<td><input type="text" id="dolar3" name="dolar3" size="8" value='' ></td>
	
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
			<li><a href="#Kering" data-toggle="tab">Perdimento</a></li>
			<li><a href="#Kering" data-toggle="tab">Sem ped_jde</a></li>
            </ul>
			    
			  
<div class="tab-content">

	
	
		
		
	
<div class="active tab-pane" id="aberto">	
<div class="box-header with-border">
	 <tr><td>Cargas em aberto</td></tr>
	<h6> 
	<table class="table table-striped table-bordered compact" id="myTable">
		<thead>	
					<tr>
					<td>det</td>
						<td>det</td>
					<td colspan="1" align="center">Pedido</td>
					<td colspan="1" align="center">ult_prox status</td>						
					<td colspan="1" align="center">Invoice</td>				
					<td colspan="1" align="center">ref desp</td>
					<td colspan="1" align="center">tipo</td>
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
				
				
				<form action="/import_form/grava" method="post" class="form-horizontal"> @csrf
				<input type="hidden" id="id_info" name="id_info" size="50" value={{$query2->id_info}}>		
				<input type="hidden" id="acao" name="acao" size="50" value={{$query2->acao}} >
				<input type="hidden" id="pedido" name="pedido" size="50" value={{$query2->pedido}} >
				<input type="hidden" id="tipo" name="tipo" size="50" value={{$query2->tipo}} >
				
				
				
			<td>	<button type="submit"><i class="fa fa-refresh"></i></button>	</td>

				<td align="left">
					<a href="/import_form/?tipo={{$query2->tipo}}&pedido={{$query2->pedido}}" target="_blank">
					<i class="fa fa-file-text-o"></i></a>
				</td>
					
					
			<td align="left"><a href="/dsimportdet/{{$query2->tipo}}/{{$query2->pedido}}">{{$query2->tipo.' '.$query2->pedido}}</a></td>
			<td align="center">{{$query2->ult_prox}} - {{$query2->desc_status}}</td>
	
			<td align="left">{{$query2->ref_go}}</td>
			<td align="center">{{$query2->ref}}</td>
				
					<td>
							  <select class="form-control" name="tipo_agrup" >	
							  <option value="{{$query2->tipo_agrup}}">{{$query2->tipo_agrup}}</option> 
							  <option value="FR">FR</option>
							  <option value="AC">AC</option>
						      </select>
							  
						  </td>
					
					
			<td><input type="text" id="doc_agrup" name="doc_agrup" size="8" value='{{$query2->doc_agrup}}' ></td>
				
			<td align="left">{{$query2->fornecedor}}</td>
			<td align="center">{{$query2->tipoitem}}</td>
			<td align="center">{{$query2->codgrife}}</td>
			<td align="center">{{$query2->colmod}}</td>
			<td align="center">{{$query2->linha}}</td>
			<td align="center">{{number_format($query2->qtde)}}</td>	
			<td align="center">{{number_format($query2->atende)}}</td>
			<td align="center">{{number_format($query2->itens_trans)}}</td>
			<td align="center">{{$query2->impostos_nac}}</td>
			<td align="center">{{$query2->icms_nac}}</td>
				
	
			</tr>
				</form>	
			@endforeach 
		</table>
		</h6>	
	</div>
</div>	

	
	

<div class="tab-pane" id="removido">	
<div class="box-header with-border">
	 <tr><td>Cargas removidas</td></tr>
	<h6>
	<table class="table table-striped table-bordered compact" id="myTable">
		  <thead>	
			 
		  			
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
			<td align="center">{{$query3->impostos_nac}}</td>
			<td align="center">{{$query3->icms_nac}}</td>
	
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
	 <tr><td colspan="15">Transito</td></tr>
	<h6>
	<table class="table table-striped table-bordered compact" id="myTable1">
		  <thead>				
			
		  		
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
				
				@php if ($query4->desc_status=="li_deferida" or $query4->desc_status=="booking")
				
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
			<td align="center">{{$query4->impostos_nac}}</td>
			<td align="center">{{$query4->icms_nac}}</td>
	
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

	<table class="table table-striped table-bordered compact" id="myTable">
		  <thead>				
			 <tr><td colspan="15">Embarque</td></tr>
		  			
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
			  
			@foreach ($query_2 as $query5)
				
				@php if ($query5->desc_status=="li_solicitado")
				
			{ @endphp
		
			<tr>
			<td align="left"><a href="/import_form/?tipo={{$query5->tipo}}&pedido={{$query5->pedido}}" target="_blank">
				<i class="fa fa-file-text-o"></i></a></td>
			<td align="left"><a href="/dsimportdet/{{$query5->tipo}}/{{$query5->pedido}}">{{$query5->tipo.' '.$query5->pedido}}</a></td>
			<td align="center">{{$query5->ult_prox}} - {{$query5->desc_status}}</td>
	
			<td align="left">{{$query5->ref_go}}</td>
			<td align="center">{{$query5->ref}}</td>
			<td align="center">{{$query5->doc_agrup}}</td>
			<td align="left">{{$query5->fornecedor}}</td>
			<td align="center">{{$query5->tipoitem}}</td>
			<td align="center">{{$query5->codgrife}}</td>
			<td align="center">{{$query5->colmod}}</td>
			<td align="center">{{$query5->linha}}</td>
			<td align="center">{{number_format($query5->qtde)}}</td>	
			<td align="center">{{number_format($query5->atende)}}</td>
			<td align="center">{{number_format($query5->itens_trans)}}</td>
			<td align="center">{{$query5->impostos_nac}}</td>
			<td align="center">{{$query5->icms_nac}}</td>
	
			</tr>
			@php ;} else  { @endphp
		
			@php  ;} @endphp
			
			@endforeach 
			

		</table>
		</div>	
		</div>	
	
	
	
	
<div class="tab-pane" id="Kering">
<div class="box-header with-border">
	<table class="table table-striped table-bordered compact" id="myTable">
		  <thead>				
			 <tr><td colspan="15">Kering</td></tr>
		  			
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
			<td align="center">{{$query6->doc_agrup}}</td>
			<td align="left">{{$query6->fornecedor}}</td>
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
	

		</div>	
	</div>	

	
</div>	
			  
</div>	
</div>
</div>


	
@stop