@extends('layout.principal')

@php

$pedido = $_GET["pedido"];
$tipo = $_GET["tipo"];

@endphp

@section('title')
<i class="fa fa-file-text-o"></i> {{$tipo}} - {{$pedido}}</i> 
@append 

@section('conteudo')
	


@php
	$result = \DB::select("select id from compras_infos 
	where (id_pedido = '$pedido' or num_temp = '$pedido') ");
		
			if(count($result)==1){
			$id_info = 	$result[0]->id;
			$acao = "update";
			
			
			}else{
			$acao = "insert";
			$id_info = 	0;
	
			}
	

if($acao=="update") {

	$query_1 = \DB::select("
	

		select *, ifnull(volumes,0) volumes1, ifnull(peso_bruto,0) peso_bruto1, ifnull(peso_liquido,0) peso_liquido1, 
		ifnull(cubagem_m3,0) cubagem_m31, 0 as cubagem_m39,
		ifnull(invoice,num_temp) invoice1
		from (

			select * from (
				select * 
				from compras_infos ci 
				where ci.id = $id_info
			) as info


					left join (
					select ped.pedido num_pedido, ped.tipo tipo_pedido1, ped.dt_pedido, ped.ref_go invoice, concat(ped.ult_status,' ', ped.prox_status) ult_prox_ped,
					ped.moeda moeda_pedido, sum(ped.qtde_sol) qtde_ped, sum(ped.vlr_total) vlr_pedido,
					Ref_Nac_01 num_di, ref_nac_02 data_di,
					nf.prenota, nf.dt_emissao dt_nf, concat(nf.ult_status, ' ', nf.prox_status) status_nf, sum(nf.qtde) qtde_nf, sum(nf.total) vlr_nf, sum(nf.icms) icms_nf, sum(nf.ipi) ipi_nf,
					group_concat(distinct codtipoitem order by codtipoitem) tipo_produto, group_concat(distinct codgrife order by codgrife) grifes, 
					case 
					when ped.prox_status = 230 then 'ped_inserido' 
					when ped.prox_status = 280 then 'PL_recebido' 
					when ped.prox_status = 345 then 'confirmado' 
					when ped.prox_status = 350 then 'li_solicitado'
					when ped.prox_status = 355 then 'li_deferida'
					when ped.prox_status = 359 then 'emb_autorizado'
					when ped.prox_status = 365 then 'booking'
					when ped.prox_status = 369 then 'chegada_Br'
					when ped.prox_status = 375 then 'removido'
					when ped.prox_status = 379 then 'registrado'
					when ped.prox_status = 385 then 'nf_emitida'
					when ped.prox_status = 390 then 'carregada'
					when ped.prox_status = 400 then 'chegou_TO' else '' end as desc_status

						from importacoes_pedidos ped 
						left join importacoes_notas nf on nf.ped_original = ped.pedido and nf.tipo_original = ped.tipo and nf.linha_original = ped.linha
						left join itens on ped.cod_item = itens.id
						where ped.pedido = '$pedido' and ped.tipo = '$tipo'

						group by ped.pedido, ped.tipo , ped.dt_pedido, ped.ref_go ,  concat(ped.ult_status,' ', ped.prox_status),
						Ref_Nac_01, ref_nac_02,
						nf.prenota, nf.dt_emissao ,  concat(nf.ult_status, ' ', nf.prox_status) , ped.moeda , ped.prox_status

					) as pedido
					on info.id_pedido = pedido.num_pedido 

				) as final

			");

		} else {
		$query_1 = \DB::select("
		
select num_pedido,
tipo_pedido tipo_pedido1,  dt_pedido, num_temp invoice,  ult_prox_ped,  moeda_pedido, 
 qtde_ped,  vlr_pedido,  num_di,  data_di,  prenota,  dt_nf,  status_nf,  qtde_nf,  vlr_nf,  icms_nf,  ipi_nf, 
 tipo_produto,  grifes,  desc_status,  id,  id_pedido,   tipo_pedido, tipo_agrup,  doc_agrup, 
dt_chegada, dt_emb_int, dt_perdimento, dt_registro, dt_aut_embarque, dt_remocao, dt_emb_nac, dt_recebimento, 
 created_at, dt_prev_embnac, dt_prev_chegada, dt_invoice, tipo_carga,  cubagem_m3,  volumes,  peso_bruto, obs_invoice, 
dt_sol_li, dt_def_li, ref_processo, an8_agente_int, protocolo_di, moeda_nac, taxa_nac, icms_nac, impostos_nac, 
base_imposto, base_icms, taxas_op, num_awb, obs_chegada, obs_transito, 
an8_agente_nac, 

num_invoice_tr, dt_invoice_td, num_nf_tr, dt_nf_tr, ref_comex, valor_total_tr, desc_dupl_1, valor_dupl_1, venc_dupl_1, desc_dupl_2, valor_dupl_2, 
venc_dupl_2, desc_dupl_3, valor_dupl_3, venc_dupl_3, taxa_cambio_fat, taxa_cambio_lc, nf_complementar, valor_nfc, 
venc_nfc, data_pgto_nfc, vlr_requisicao, peso_liquido, num_temp, 
volumes volumes1, peso_bruto peso_bruto1, peso_liquido peso_liquido1, cubagem_m3 cubagem_m31, cubagem_m3 cubagem_m39,
ifnull(invoice,num_temp) invoice1



from (
	select *, ifnull(volumes,0) volumes1, ifnull(peso_bruto,0) peso_bruto1, ifnull(peso_liquido,0) peso_liquido1, ifnull(cubagem_m3,0) cubagem_m31, 0 as cubagem_m39

	from (
		select ped.pedido num_pedido, ped.tipo tipo_pedido1, ped.dt_pedido, ped.ref_go invoice, concat(ped.ult_status,' ', ped.prox_status) ult_prox_ped,
		ped.moeda moeda_pedido, sum(ped.qtde_sol) qtde_ped, sum(ped.vlr_total) vlr_pedido,
		Ref_Nac_01 num_di, ref_nac_02 data_di,
		nf.prenota, nf.dt_emissao dt_nf, concat(nf.ult_status, ' ', nf.prox_status) status_nf, sum(nf.qtde) qtde_nf, sum(nf.total) vlr_nf, sum(nf.icms) icms_nf, sum(nf.ipi) ipi_nf,
		group_concat(distinct codtipoitem order by codtipoitem) tipo_produto, group_concat(distinct codgrife order by codgrife) grifes, 
	case 
	when ped.prox_status = 230 then 'ped_inserido' 
	when ped.prox_status = 280 then 'PL_recebido' 
	when ped.prox_status = 345 then 'confirmado' 
	when ped.prox_status = 350 then 'li_solicitado'
	when ped.prox_status = 355 then 'li_deferida'
	when ped.prox_status = 359 then 'emb_autorizado'
	when ped.prox_status = 365 then 'booking'
	when ped.prox_status = 369 then 'chegada_Br'
	when ped.prox_status = 375 then 'removido'
	when ped.prox_status = 379 then 'registrado'
	when ped.prox_status = 385 then 'nf_emitida'
	when ped.prox_status = 390 then 'carregada'
	when ped.prox_status = 400 then 'chegou_TO' else '' end as desc_status

		from importacoes_pedidos ped 
		left join importacoes_notas nf on nf.ped_original = ped.pedido and nf.tipo_original = ped.tipo and nf.linha_original = ped.linha
		left join itens on ped.cod_item = itens.id
		where ped.pedido = '$pedido' and ped.tipo = '$tipo'

		group by ped.pedido, ped.tipo , ped.dt_pedido, ped.ref_go ,  concat(ped.ult_status,' ', ped.prox_status),
		Ref_Nac_01, ref_nac_02,
		nf.prenota, nf.dt_emissao ,  concat(nf.ult_status, ' ', nf.prox_status) , ped.moeda , ped.prox_status
        
	) as final


		left join (select * from compras_infos ) as ci
		on ci.id_pedido = final.num_pedido and ci.tipo_pedido = final.tipo_pedido1

) as base1

		");



		} ;





		$query_titulos = \DB::select("select * from compras_titulos where id_pedido =  '$pedido' and origem = '$tipo'");
		
		$query_parcelas = \DB::select("
		select cp.*, ct.tipo tipo_ct 
		from compras_titulos ct
		left join compras_parcelas cp on cp.id_titulo = ct.numero
		where ct.id_pedido = $pedido and origem = '$tipo'  
		");


		$query_pagamentos = \DB::select("
		select cg.*, ct.tipo tipo_ct
		from compras_titulos ct
		left join compras_parcelas cp on cp.id_titulo = ct.numero
		left join compras_pagamentos cg on cg.id_parcela = cp.id
		where ct.id_pedido = $pedido and origem = '$tipo'  		
		");


		$query_4 = \DB::select("	
		
		select * from (
		select itempedido, cod_item,  case when codtipoitem = '006' then cod_item else id_pai end as coditem, qtde
        from (
			select ip.secundario itempedido, cod_item, codtipoitem, qtde_sol qtde
            -- , itens.secundario, agrup, codgrife, modelo, 0 as ref, fornecedor, colmod, 0 ult_prox, 0 as atende , tipoitem, qtde_sol qtde
			from importacoes_pedidos ip
			left join itens on ip.cod_item = itens.id
            
			where pedido = '$pedido' and tipo = '$tipo'
        ) as base
        
        left join (select * from itens_estrutura   ) as estrutura
		on estrutura.id_filho = base.cod_item
   		) as final 
   
		left join (select * from go_storage.sintetico_estoque ) as sint
		on sint.id_item = coditem
		");




		$query_5 = \DB::select("select * from compras_docs where pedido = $id_info");


@endphp
		


<div class="row">

   <div class="col-md-11">
	  
          <div class="nav-tabs-custom">
           
			  
			  
			<ul class="nav nav-tabs">
              <li class="active"><a href="#dados" data-toggle="tab">Dados</a></li>
			  <li><a href="#detalhes" data-toggle="tab">Detalhes</a></li>
              <li><a href="#financeiro" data-toggle="tab">financeiro</a></li>
              <li><a href="#timeline" data-toggle="tab">Timeline</a></li>
              <li><a href="#settings" data-toggle="tab" class='text-red'>Validacoes</a></li>
			  <li><a href="#documentos" data-toggle="tab" class='text-green'>documentos</a></li>
			  <li><a href="#documentos" data-toggle="tab" class='text-green'>OL/OG</a></li>
				
            </ul>
			  
			  
			  
<div class="tab-content">
						
			
	
				
				
				
				
              <div class="active tab-pane" id="dados">
				  		    	  
				<div class="box-header with-border">	  
					 <h3 class="box-title">Pedido JDE </h3>
					 <h6>
					 <table class="table table-bordered table-condensed">			

						<tr class="card-header bg-info text-center">         
						  <td><b>Dt Emissao</b></td>
						  <td><b>Tipo produto</b></td>
						  <td><b>Grifes</b></td>
						  <td><b>Ult/Prox Status</b></td>
						  <td><b>Obs pedido</b></td>
					
						</tr>

						<tr class="text-center">
							<td>{{$query_1[0]->dt_pedido}}</td>
							<td>{{$query_1[0]->tipo_produto}}</td>
							<td>{{$query_1[0]->grifes}}</td>
							<td>{{$query_1[0]->ult_prox_ped}} - {{$query_1[0]->desc_status}}</td>
							<td>{{$query_1[0]->num_pedido}}</td>
							
						</tr>	
					</table>
						 
						 
						 
						 
						 
						 <table class="table table-bordered table-condensed">			

						<tr class="card-header bg-info text-center">         
						  <td><b>Fornecedor</b></td>
						  <td><b>Status Geral</b></td>
						  <td><b>qtde</b></td>
						  <td><b>valor</b></td>
						
						</tr>

						<tr class="text-center">
						
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						</tr>	
					</table>
					</h6>
				</div>
				
			  
				  
				
                <!-- Post -->
                <div class="post clearfix">
			
				<form action="/import_form/grava" method="post" class="form-horizontal">
				@csrf
					<input type="hidden" id="id_info" name="id_info" size="50" value={{$id_info}}>
					<input type="hidden" id="acao" name="acao" size="50" value={{$acao}} >
					<input type="hidden" id="pedido" name="pedido" size="50" value={{$pedido}} > 
					<input type="hidden" id="tipo" name="tipo" size="50" value={{$tipo}} >	
					<input type="hidden" id="num_temp" name="num_temp" size="50" value={{$query_1[0]->num_temp}} >	

				


					<div class="box box-danger">
					  <h3 class="box-title">Documentacao embarque</h3>
						<th>{{$acao}} - {{$id_info}}  </th>
					<h6>
					  <table class="table table-bordered table-condensed">
						  
						<tr  class="card-header bg-info text-center">
						  <td><b>Num Invoice</b></td>
						  <td><b>Dt emissao</b></td>
						  <td><b>Cubagem</b></td>
						  <td><b>Volumes</b></td>
						  <td><b>Peso Bruto</b></td>
						  <td><b>Peso Liquido</b></td>
						  <td><b>Obs Invoice</b></td>
						</tr>


						<tr class="text-center">
						  <td>{{$query_1[0]->invoice1}}</td>
						  <td><input type="date" id="dt_invoice" name="dt_invoice" size="10" value={{$query_1[0]->dt_invoice}} ></td>
						  <td><input type="number" step="any" id="cubagem_m3" name="cubagem_m3" size="5" value={{$query_1[0]->cubagem_m31}} ></td>
						  <td><input type="number" step="any" id="volumes" name="volumes" size="5" value={{$query_1[0]->volumes1}}></td>
						  <td><input type="number" step="any" id="peso_bruto" name="peso_bruto" size="10" value={{$query_1[0]->peso_bruto1}} ></td>
						<td><input type="number" step="any" id="peso_liquido" name="peso_liquido" size="10" value={{$query_1[0]->peso_liquido1}} ></td>	
						  
							<td><input type="text" id="obs_invoice" name="obs_invoice" size="35" value='{{$query_1[0]->obs_invoice}}' ></td>
						</tr>
								</h6>
					</table>
						  
					  
					 <table class="table table-bordered table-condensed">
						 	<h6>
						 <tr  class="card-header bg-info text-center">      
						  
						  <td><b>Tipo Agrup	</b></td>
						  <td><b>Num Agrup	</b></td>
						  <td><b>Tipo Carga</b></td>
						  <td><b>Ref processo</b></td>
						  <td><b>Numero OK</b></td>
						  <td><b>Numero SX</b></td>
						</tr>		  

						<tr class="text-center">
						  <td>
							  <select class="form-control" name="tipo_agrup" >	
							  <option value="{{$query_1[0]->tipo_agrup}}">{{$query_1[0]->tipo_agrup}}</option> 
							  <option value=" "></option>
							  <option value="FR">FR</option>
							  <option value="AC">AC</option>
						      </select>
							  
						  </td>
							
						  <td><input type="text" id="doc_agrup" name="doc_agrup" size="20" value='{{$query_1[0]->doc_agrup}}' ></td>
						  <td><input type="text" id="tipo_carga" name="tipo_carga" size="20" value='{{$query_1[0]->tipo_carga}}'></td>
						  <td size="20">{{$query_1[0]->ref_processo}}</td>
						  <td size="20">{{$query_1[0]->num_pedido}}</td>
						  <td size="20">{{$query_1[0]->num_pedido}}</td>
						</tr>
					  </table> 
			</h6>
					  </div> 
					
					
					
				<div class="box box-warning">
				<h3 class="box-title">Transito</h3>
				<h6>
				 <table class="table table-bordered table-condensed">
					<tr  class="card-header bg-info text-center">
					  <td><b>Dt solicitacao LI</b></td>
					  <td><b>Dt deferimento LI</b></td>
					  <td><b>Transp Internacional</b></td>
					  <td><b>Dt aut Embarque</b></td>
					 <td><b>Obs Transito</b></td>
					 <td class="text-red"><b>Perdimento</b></td>
					</tr>



					<tr class="text-center">
					  <td><input type="date" id="dt_sol_li" name="dt_sol_li" size="10" value={{$query_1[0]->dt_sol_li}} ></td>
					  <td><input type="date" id="dt_def_li" name="dt_def_li" size="10" value={{$query_1[0]->dt_def_li}} ></td>

						  <td>@php 
						  $fornecedor1 = \DB::select("select id, fantasia from addressbook where nome like '%junior%'");
						  @endphp
						  <select class="form-control" name="an8_agente_int" >			 
						  <option value="{{$query_1[0]->an8_agente_int}}">{{$query_1[0]->an8_agente_int}}</option>
							@foreach ($fornecedor1 as $forn1)
						  <option value="{{$forn1->id}} - {{$forn1->fantasia}}">{{$forn1->id}} - {{$forn1->fantasia}}</option>
							@endforeach
						  </select>
						  </td>

					  <td><input type="date" id="dt_aut_embarque" name="dt_aut_embarque" size="10" value={{$query_1[0]->dt_aut_embarque}} ></td>
					  <td>
					
					<input type="text" id="obs_transito" name="obs_transito" size="10" value='{{$query_1[0]->obs_transito}}'></td>											
						
					<td>{{$query_1[0]->dt_perdimento}}</td>
						
					</tr>

					
					</table>
					
					

					<table class="table table-bordered table-condensed">
					 <tr  class="card-header bg-info text-center">      
					  <td><b>Num HAWB</b></td>
					  <td><b>Dt Embarque</b></td>
					  <td><b>Dt Previsao Chegada</b></td>
					  <td><b>Dt Chegada</b></td>
					  <td><b>Obs Chegada</b></td>
					  <td><b>Dt remocao</b></td>
					  <td><b>Vlr requisicao</b></td>
					  
					</tr>		  

					<tr class="text-center">
					<td><input type="text" id="num_awb" name="num_awb" size="20" value='{{$query_1[0]->num_awb}}'></td>
					<td><input type="date" id="dt_emb_int" name="dt_emb_int" size="20" value={{$query_1[0]->dt_emb_int}} ></td>
					<td><input type="date" id="dt_prev_chegada" name="dt_prev_chegada" size="20" value={{$query_1[0]->dt_prev_chegada}} ></td>
					<td><input type="date" id="dt_chegada" name="dt_chegada" size="20" value={{$query_1[0]->dt_chegada}} ></td>
					<td><input type="text" id="obs_chegada" name="obs_chegada" size="20" value='{{$query_1[0]->obs_chegada}}'></td>
					<td><input type="date" id="dt_remocao" name="dt_remocao" size="20" value={{$query_1[0]->dt_remocao}} ></td>
					<td><input type="text" id="vlr_requisicao" name="vlr_requisicao" size="20" value={{$query_1[0]->vlr_requisicao}} ></td>
			
					</tr>
</h6>
				  </table>
					
				 </div>
					
					
			
			
				
					
					<div class="box box-info">
					  <h3 class="box-title">Nacionalizacao</h3>
					  <h6>
					 <table class="table table-bordered table-condensed">


						 <tr  class="card-header bg-info text-center">
						  <td><b>Numero DI ->jde</b></td>
						  <td><b>Dt registro DI  ->jde</b></td>
						  <td><b>Num protocolo DI</b></td>
						  <td><b>Cambio registro ex dt_di</b></td>
							 <td><b>Taxa Cambio Efet R$</b></td> 
						</tr>


						<tr class="text-center">
							<td>{{$query_1[0]->num_di}}</td>
						  <td><input type="date" id="dt_registro" name="dt_registro" size="20" value={{$query_1[0]->dt_registro}} ></td>
						  <td><input type="text" id="protocolo_di" name="protocolo_di" size="20" value='{{$query_1[0]->protocolo_di}}' ></td>
							<td>{{$query_1[0]->data_di}}</td>
							<td></td>
						</tr>

						  </table>
						  
						  <table class="table table-bordered table-condensed">


							 <tr  class="card-header bg-info text-center">
							  <td><b>Moeda Cambio</b></td>
							  <td><b>Taxa Calculo</b></td>
							  <td><b>Imposto calculo R$</b></td>
							  <td><b>ICMS calculo R$</b></td>
							  
							  <td><b>Base Imposto Est</b></td>
							  <td><b>Base ICMS Est</b></td>
							</tr>


							<tr class="text-center">

							<td><input type="text" id="moeda_nac" name="moeda_nac" size="20" value='{{$query_1[0]->moeda_nac}}' ></td>

							<td><input type="text" id="taxa_nac" name="taxa_nac" size="20" value={{$query_1[0]->taxa_nac}} ></td>

							<td><input type="text" id="impostos_nac" name="impostos_nac" size="20" value={{$query_1[0]->impostos_nac}}></td>
								
							<td><input type="text" id="icms_nac" name="icms_nac" size="20" value={{$query_1[0]->icms_nac}} >	</td>
								
								
								<td>{{$query_1[0]->base_imposto}}</td>
								<td>{{$query_1[0]->base_icms}}</td>
							</tr>

						  </table>
						  
						  <table class="table table-bordered table-condensed">


								 <tr  class="card-header bg-info text-center">
								  <td><b>Sim 1 = trang</b></td>
							      <td><b>sim 2</b></td>
								  <td><b>sim3</b></td>
								 
								</tr>


								<tr class="text-center">
								
									 
									<td>{{$query_1[0]->data_di}}</td>
									<td>{{$query_1[0]->data_di}}</td>
									<td>{{$query_1[0]->num_di}}</td>
								</tr>

								  </table>
						  
						  
						<table class="table table-bordered table-condensed">

						 <tr  class="card-header bg-info text-center">

						  <td><b>Num Pre-NF (NI)</b></td>
						  <td><b>Num NF legal</b></td>
						  <td><b>Dt emissao NF</b></td>
						  <td><b>Qtde NF</b></td>
						  <td><b>Valor NF</b></td>
						  
						 </tr>

						<tr class="text-center">
						<td>{{$query_1[0]->prenota}}</td>
						 <td></td>
						 <td></td>
						 <td></td>
						 <td></td> 
					
						</tr>

						  </table>
						  
						<table class="table table-bordered table-condensed">

						 <tr  class="card-header bg-info text-center">

						  <td><b>Dt prev Transp Nac</b></td>
						  <td><b>Transporte Nac</b></td>
							 <td><b>Periodo registro</b></td>
						  <td><b>Dt Carregamento</b></td>
						  <td><b>Dt entrega Fabrica</b></td>

						 </tr>

						<tr class="text-center">
							<td><input type="date" id="dt_prev_embnac" name="dt_prev_embnac" size="20" value={{$query_1[0]->dt_prev_embnac}} ></td>
						  <td>@php 
						  $fornecedor2 = \DB::select("select id, fantasia from addressbook where nome like '%junior%'");
						  @endphp
						  <select class="form-control" name="an8_agente_nac" >			 
						  <option value="{{$query_1[0]->an8_agente_nac}}">{{$query_1[0]->an8_agente_nac}}</option>
							@foreach ($fornecedor2 as $forn2)
						  <option value="{{$forn2->id}} - {{$forn2->fantasia}}">{{$forn2->id}} - {{$forn2->fantasia}}</option>
							@endforeach
						  </select>
						  </td>	
							<td><b>Periodo</b></td>
							<td><input type="date" id="dt_emb_nac" name="dt_emb_nac" size="20" value={{$query_1[0]->dt_emb_nac}} ></td>
							<td><input type="date" id="dt_recebimento" name="dt_recebimento" size="20" value={{$query_1[0]->dt_recebimento}} ></td>
						</tr>		 
					  </table>
					  </h6>
					  </div>
		
					
					
					
				
					<div class="box box-success">
					  <h3 class="box-title">Trading OP</h3>
						   <h6>
					 <table class="table table-bordered table-condensed">

						<tr  class="card-header bg-info text-center">
						  <td><b>num_invoice Trading</b></td>
						  <td><b>dt_invoice_Trade</b></td>
						  <td><b>num_nf</b></td>
						  <td><b>dt_nf</b></td>
						  <td><b>ref_comex</b></td>
						  <td><b>valor_total</b></td>
						</tr>	 


						<tr class="text-center">
						<td><input type="text" id="num_invoice_tr" name="num_invoice_tr" size="5" value={{$query_1[0]->num_invoice_tr}} ></td>
						<td><input type="date" id="dt_invoice_td" name="dt_invoice_td" size="5" value={{$query_1[0]->dt_invoice_td}} ></td>
						<td><input type="text" id="num_nf_tr" name="num_nf_tr" size="5" value={{$query_1[0]->num_nf_tr}} ></td>	
						<td><input type="date" id="dt_nf_tr" name="dt_nf_tr" size="5" value={{$query_1[0]->dt_nf_tr}} ></td>	
						<td><input type="text" id="ref_comex" name="ref_comex" size="5" value={{$query_1[0]->ref_comex}} ></td>	
						<td><input type="text" id="valor_total_tr" name="valor_total_tr" size="5" value={{$query_1[0]->valor_total_tr}} ></td>	  
						</tr>

					</table>


						

					
						<table class="table table-bordered table-condensed">
						 <tr  class="card-header bg-info text-center">
						  <td><b>taxa_cambio_fat</b></td>
						  <td><b>taxa_cambio_lc</b></td>
						  <td><b>nf_complementar</b></td>
						  <td><b>valor_nfc</b></td>
						  <td><b>venc_nfc</b></td>
						  <td><b>dt_pgto_nfc</b></td>
						</tr>	 

						<tr class="text-center">
						<td><input type="text" id="taxa_cambio_fat" name="taxa_cambio_fat" size="5" value={{$query_1[0]->taxa_cambio_fat}} ></td>
						<td><input type="text" id="taxa_cambio_lc" name="taxa_cambio_lc" size="5" value={{$query_1[0]->taxa_cambio_lc}} ></td>
						<td><input type="text" id="nf_complementar" name="nf_complementar" size="5" value={{$query_1[0]->nf_complementar}} ></td>	
						<td><input type="text" id="valor_nfc" name="valor_nfc" size="5" value={{$query_1[0]->valor_nfc}} ></td>
						<td><input type="date" id="venc_nfc" name="venc_nfc" size="5" value={{$query_1[0]->venc_nfc}} ></td>
						<td><input type="date" id="data_pgto_nfc" name="data_pgto_nfc" size="5" value={{$query_1[0]->data_pgto_nfc}} ></td>	  
						</tr>

						
					  </table>	
						
						
							   
							   
							 
						<table class="table table-bordered table-condensed">
						 <tr  class="card-header bg-info text-center">
						  <td><b>Documento</b></td>
						  <td><b>Num documento</b></td>
						  <td><b>valor</b></td>
						  <td><b>vencimento</b></td>
						</tr>	 

						<tr class="text-center">
						<td>Duplicata1</td>
						<td><input type="text" id="desc_dupl_1" name="desc_dupl_1" size="5" value={{$query_1[0]->desc_dupl_1}} ></td>
						<td><input type="text" id="valor_dupl_1" name="valor_dupl_1" size="5" value={{$query_1[0]->valor_dupl_1}} ></td>
						<td><input type="date" id="venc_dupl_1" name="venc_dupl_1" size="5" value={{$query_1[0]->venc_dupl_1}} ></td>
						</tr>
						
						<tr class="text-center">
						<td>Duplicata2</td>
						<td><input type="text" id="desc_dupl_2" name="desc_dupl_2" size="5" value={{$query_1[0]->desc_dupl_2}} ></td>
						<td><input type="text" id="valor_dupl_2" name="valor_dupl_2" size="5" value={{$query_1[0]->valor_dupl_2}} ></td>
						<td><input type="date" id="venc_dupl_2" name="venc_dupl_2" size="5" value={{$query_1[0]->venc_dupl_2}} ></td> 
						</tr>
						
						<tr class="text-center">
						<td>Duplicata3</td>
						<td><input type="text" id="desc_dupl_3" name="desc_dupl_3" size="5" value={{$query_1[0]->desc_dupl_3}} ></td>
						<td><input type="text" id="valor_dupl_3" name="valor_dupl_3" size="5" value={{$query_1[0]->valor_dupl_3}} ></td>
						<td><input type="date" id="venc_dupl_3" name="venc_dupl_3" size="5" value={{$query_1[0]->venc_dupl_3}} ></td>
						</tr>

						
					  </table>	  
							   
							   
							   
							   
							   
						</div>	
					
					
				<button type="submit" class="btn btn-primary"><i class="fa fa-refresh"></i> Enviar</button>	
				</form>
				</div>	                               
              </div>
				<!-- /.fim dados -->
				

				
				
				
				
	<div class="tab-pane" id="detalhes">									
		
		<h6>
		
		<table class="tabela2 table-striped table-bordered compact">
		 		<thead>	
				<tr>
					<td>Foto</td>	
					<td colspan="1" align="center">Produto</td>	
					<td colspan="1" align="center">Secundario</td>
					<td colspan="1" align="center">Colmod</td>
					<td colspan="1" align="center">Clasmod</td>
					<td colspan="1" align="center">Statual atual</td>	
					<td colspan="1" align="center">Qtde pecas</td>
					<td colspan="1" align="center">Atende</td>
					<td colspan="1" align="center">Mostruarios</td>
					<td colspan="1" align="center">disponivel</td>
				</tr>
				</thead>
			  
			@foreach ($query_4 as $query4)
			  
				<tr>
					<td id="foto" align="center" style="min-height:60px;"> 
						<a href="" class="zoom" data-value="{{$query4->secundario}}">
						<img src="https://portal.goeyewear.com.br/teste999.php?referencia={{$query4->secundario}}" style="max-height: 60px;" class="img-responsive"></a>
					</td>					
					<td align="left">{{$query4->itempedido}}</td>	
					<td align="left"><a href="/painel/{{$query4->agrup}}/{{$query4->modelo}}">{{$query4->secundario}}</a></td>
					<td align="center">{{$query4->colmod}}</td>
					<td align="left">{{$query4->clasmod}}</td>
					<td align="left">{{$query4->statusatual}}</td>
					<td align="center">{{number_format($query4->qtde)}}</td>	
					<td align="center">{{number_format($query4->orcamento_liber,0)}}</td>
					<td align="center">{{number_format($query4->mostruarios,0)}}</td>
					<td align="center">{{number_format($query4->disponivel,0)}}</td>
				</tr>
			@endforeach 
			
			</table></h6>	
	</div>			
	<!--fim detalhes -->				
				
				
				
				
				
              <!-- /.tab-pane -->
              <div class="tab-pane" id="timeline">
                <!-- The timeline -->
                <ul class="timeline timeline-inverse">
                  <!-- timeline time label -->
                  <li class="time-label">
                        <span class="bg-red">
                          10 Feb. 2014
                        </span>
                  </li>
                  <!-- /.timeline-label -->
                  <!-- timeline item -->
                  <li>
                    <i class="fa fa-envelope bg-blue"></i>

                    <div class="timeline-item">
                      <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

                      <h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>

                      <div class="timeline-body">
                        Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                        weebly ning heekya handango imeem plugg dopplr jibjab, movity
                        jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                        quora plaxo ideeli hulu weebly balihoo...
                      </div>
                      <div class="timeline-footer">
                        <a class="btn btn-primary btn-xs">Read more</a>
                        <a class="btn btn-danger btn-xs">Delete</a>
                      </div>
                    </div>
                  </li>
                  <!-- END timeline item -->
                  <!-- timeline item -->
                  <li>
                    <i class="fa fa-user bg-aqua"></i>

                    <div class="timeline-item">
                      <span class="time"><i class="fa fa-clock-o"></i> 5 mins ago</span>

                      <h3 class="timeline-header no-border"><a href="#">Sarah Young</a> accepted your friend request
                      </h3>
                    </div>
                  </li>
                  <!-- END timeline item -->
                  <!-- timeline item -->
                  <li>
                    <i class="fa fa-comments bg-yellow"></i>

                    <div class="timeline-item">
                      <span class="time"><i class="fa fa-clock-o"></i> 27 mins ago</span>

                      <h3 class="timeline-header"><a href="#">Jay White</a> commented on your post</h3>

                      <div class="timeline-body">
                        Take me to your leader!
                        Switzerland is small and neutral!
                        We are more like Germany, ambitious and misunderstood!
                      </div>
                      <div class="timeline-footer">
                        <a class="btn btn-warning btn-flat btn-xs">View comment</a>
                      </div>
                    </div>
                  </li>
                  <!-- END timeline item -->
                  <!-- timeline time label -->
                  <li class="time-label">
                        <span class="bg-green">
                          3 Jan. 2014
                        </span>
                  </li>
                  <!-- /.timeline-label -->
                  <!-- timeline item -->
                  <li>
                    <i class="fa fa-camera bg-purple"></i>

                    <div class="timeline-item">
                      <span class="time"><i class="fa fa-clock-o"></i> 2 days ago</span>

                      <h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos</h3>

                      <div class="timeline-body">
                       ima
                      </div>
                    </div>
                  </li>
                  <!-- END timeline item -->
                  <li>
                    <i class="fa fa-clock-o bg-gray"></i>
                  </li>
                </ul>
              </div>
              <!-- /.tab-pane -->

			  
			  
			  
			  
			  
			 
			 
			  
              <div class="tab-pane" id="financeiro">			  		
					  
				<section class="content">					  
					
					<div class="row">
											
						<div class="col-md-12">
												
							<a class="btn btn-default btn-flat pull-right" href="" class="pull-center" data-toggle="modal" 
							data-target="#modalcadastraPagamento">Cadastrar Pagamentos</a>
							
							<a  class="btn btn-default btn-flat pull-right" href="" class="pull-center" data-toggle="modal" 
							data-target="#modalcadastraparcela">Cadastrar Parcelas</a> 
							
							<a  class="btn btn-default btn-flat pull-right" href="" class="pull-center" data-toggle="modal" 
							data-target="#modalcadastraTitulo">Cadastrar Titulos</a>

						</div>
						
				


								<!-- embarque -->				
							<div class="col-md-6">
										<div class="box-header with-border">
											  <h3 class="box-title">FINANCEIRO EMBARQUE colapse?</h3>
											</div>
 									<table class="table table-condensed table-bordered">
											<tr class="bg-primary">
												<td colspan="3" align="center"><small><b>Titulos Embarque</b></small></td>
											</tr>    
												<tr>
												<td align="center">Descricao</td>
												<td align="center">Valor</td>
												<td>vencemmanto</td>
												
											</tr>  											
											@foreach ($query_titulos as $titulos)
							
											@php if ($titulos->tipo=="EMBARQUE")  { @endphp
										
												<tr>
													<td align="left">{{$titulos->id}}</td>
													<td align="center">{{$titulos->valor}}</td>
													<td align="center">{{$titulos->vencimento}}</td>
													
												</tr>                

											
											@php ;} else  { @endphp  @php  ;} @endphp
										
											@endforeach
											</table> 

										  <!-- About Me Box -->
										  <div class="box box-primary">																				  
											<table class="table table-condensed table-bordered">
											<tr class="bg-primary">
												<td colspan="3" align="center"><small><b>Parcelas Embarque</b></small></td>
											</tr>    
												<tr>
												<td align="center">Descricao</td>
												<td align="center">Valor</td>
												<td align="center">Vencimento</td>
												<td></td>
											</tr>  
												
											@foreach ($query_parcelas as $parcelas)
											@php if ($parcelas->tipo_ct=="EMBARQUE")  { @endphp
											<tr>
												<td align="center">{{$parcelas->id_titulo}}</td>
												<td align="center">{{$parcelas->valor}}</td>
												<td align="center">{{$parcelas->vencimento}}</td>
											
												
											</tr>  
												@php ;} else  { @endphp  @php  ;} @endphp
											@endforeach 
										
										</table> 										
										</div><!-- /.fecha parcelas -->	
								
								
								
										<!-- pagamentos -->
										  <div class="box box-primary">																				  
											<table class="table table-condensed table-bordered">
											<tr class="bg-primary">
												<td colspan="3" align="center"><small><b>Pagamentos Embarque</b></small></td>
											</tr>    
												<tr>
												<td align="center">id</td>
												<td align="center">Valor</td>
												<td align="center">cambio</td>
											</tr>  											
											@foreach ($query_pagamentos as $pagamentos)
											@php if ($pagamentos->tipo_ct=="EMBARQUE")  { @endphp
												
											<tr>
												<td align="center">{{$pagamentos->id}}</td>
												<td align="center">{{$pagamentos->valor_pago}}</td>
												<td align="center">{{$pagamentos->cambio}}</td>
												
											</tr>                
											@php ;} else  { @endphp  @php  ;} @endphp
											@endforeach 
										
									</table> 										
									</div><!-- /.fecha a segunda caixa -->				
							</div><!-- /.fecha bloco EMBARQUES -->
										
								
						
						
										  			  
						<!-- 2o Box POS_EMBARQUES-->			  
							<div class="col-md-6">
										<div class="box-header with-border">
											  <h3 class="box-title">FINANCEIRO POS-EMBARQUE</h3>
											</div>
 									<table class="table table-condensed table-bordered">
											<tr class="bg-primary">
												<td colspan="3" align="center"><small><b>Titulos Pos-Embarque</b></small></td>
											</tr>    
												<tr>
												<td align="center">Descricao</td>
												<td align="center">Valor</td>
												<td></td>
											</tr>  											
											@foreach ($query_titulos as $titulos)
							
											@php if ($titulos->tipo=="POS_EMBARQUE")  { @endphp
										
												<tr>
													<td align="left">{{$titulos->id}}</td>
													<td align="center">{{$titulos->valor}}</td>
													<td align="center">{{$titulos->vencimento}}</td>
												</tr>                

											
											@php ;} else  { @endphp  @php  ;} @endphp
										
											@endforeach
											</table> 

										  <!-- About Me Box -->
										  <div class="box box-primary">																				  
											<table class="table table-condensed table-bordered">
											<tr class="bg-primary">
												<td colspan="3" align="center"><small><b>Parcelas Pos-Embarque</b></small></td>
											</tr>    
												<tr>
												<td align="center">Descricao</td>
												<td align="center">Valor</td>
												<td align="center">Vencimento</td>
											</tr>  
												
											@foreach ($query_parcelas as $parcelas)
											@php if ($parcelas->tipo_ct=="POS_EMBARQUE")  { @endphp
											<tr>
												<td align="center">{{$parcelas->id_titulo}}</td>
												<td align="center">{{$parcelas->valor}}</td>
												<td align="center">{{$parcelas->vencimento}}</td>
											</tr>  
												@php ;} else  { @endphp  @php  ;} @endphp
											@endforeach 
										
										</table> 										
										</div><!-- /.fecha parcelas -->	
								
								
								
										<!-- pagamentos -->
										  <div class="box box-primary">																				  
											<table class="table table-condensed table-bordered">
											<tr class="bg-primary">
												<td colspan="3" align="center"><small><b>Pagamentos Pos-Embarque</b></small></td>
											</tr>    
												<tr>
												<td align="center">id</td>
												<td align="center">Valor</td>
												<td align="center">cambio</td>
											</tr>  											
											@foreach ($query_pagamentos as $pagamentos)
											@php if ($pagamentos->tipo_ct=="POS_EMBARQUE")  { @endphp
												
											<tr>
												<td align="center">{{$pagamentos->id}}</td>
												<td align="center">{{$pagamentos->valor_pago}}</td>
												<td align="center">{{$pagamentos->cambio}}</td>
												
											</tr>                
											@php ;} else  { @endphp  @php  ;} @endphp
											@endforeach 
										
									</table> 										
									</div><!-- /.fecha a segunda caixa -->				
							</div><!-- /.fecha bloco POS_EMBARQUES -->
						
								 
				</div> <!-- /.row -->
				</section>	
				  
				  
		
		<!-- /.modal lanca titulos -->				  
			<form action="/dsimportdet/cadastratitulo" id="frmcadastratitulo" class="form-horizontal" method="post">
						@csrf 
			 <div class="modal fade" id="modalcadastraTitulo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
					   <input type="hidden" name="id_pedido" id="id_pedido" value="{{$pedido}}">
					   <input type="hidden" name="tipo_pedido" id="tipo_pedido" value="{{$tipo}}">

					  <div class="modal-dialog" role="document">
						<div class="modal-content">
						  <div class="modal-header bg-primary">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Cadastro Pagamento</h4>
						  </div>
						  <div class="modal-body">

						  <div class="form-group">

						   <label class="col-md-3 control-label">Tipo de pagamento</label>   
							  <div class="col-md-8">
							<select  name="tipo_pagamento" class="form-control" required>

							 <option value="EMBARQUE" > EMBARQUE </option>
							 <option value="POS_EMBARQUE" > POS_EMBARQUE </option>

							 </select>
							  </div>        
							  </div>

							  <div class="form-group">
								<label class="col-md-3 control-label">Data Emiss??o</label>
								<div class="col-md-4">
								  <small>Data Emiss??o</small>
								  <input type="date" name="dt_emissao"  id="dt_emissao" class="form-control" required >
								</div>     

								<div class="col-md-4">
								  <small>Data Vencimento</small>
								  <input type="date" name="dt_vencimento" id="dt_vencimento"   class="form-control" required>
								</div>        
							  </div>

							  <div class="form-group">
								<label class="col-md-3 control-label">Moeda</label>
								<div class="col-md-5">
								  <select name="moeda" id="moeda" class="form-control" required>
									<option value=""> @lang('padrao.selecione') </option>
									<option value="USD"> USD </option>
									<option value="EUR"> EUR </option>
									<option value="BRL"> BRL </option>
								  </select>
								</div>        
							  </div>

							  <div class="form-group">
								<label class="col-md-3 control-label">Valor</label>
								<div class="col-md-5">
								  <input name="valor" type="decimal"  required>
								</div>        
							  </div>
						  <div class="form-group">
								<label class="col-md-3 control-label">Criar parcelas</label>
								<div class="col-md-5">
								  <select name="criar_parcela" id="criar_parcela" class="form-control" required>
									<option value="">Selecione </option>
									<option value="parcela_unica"> Parcela ??nica </option>
									<option value="multiplas"> Multiplas parcelas </option>

								  </select>
								</div>        
							  </div>

							  <div class="form-group">
								<label class="col-md-3 control-label">Observa????o</label>
								<div class="col-md-8">
									<textarea name="obs" class="form-control"></textarea>
								</div>        
							  </div>


						  </div>
						  <div class="modal-footer">
							<button type="button" class="btn btn-flat btn-default" data-dismiss="modal">@lang('padrao.cancelar')</button>
							<button type="submit" class="btn btn-flat btn-primary">@lang('padrao.salvar') </button>
						  </div>
						</div>
					  </div>
					</div>
					</form> 
          <!-- /.fim modal titulos -->
				  
				  
				  
				  
				  
				  
				  
			<!-- /.modal lanca pagamentos -->				  
					<form action="/dsimportdet/cadastrapagamento" id="frmcadastraPagamento" class="form-horizontal" method="post">
						@csrf 


					<div class="modal fade" id="modalcadastraPagamento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
					   <input type="hidden" name="id_pedido" id="id_pedido" value="{{$pedido}}">
					   <input type="hidden" name="tipo_pedido" id="tipo_pedido" value="{{$tipo}}">
					
						

					  <div class="modal-dialog" role="document">
						<div class="modal-content">
						  <div class="modal-header bg-primary">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Cadastro Pagamento</h4>
						  </div>
						  <div class="modal-body">

						  <div class="form-group">

						   <label class="col-md-3 control-label">Tipo de pagamento</label>   
							  <div class="col-md-8">
					
									<select  name="id_parcela" class="form-control" required>
										@foreach ($query_parcelas as $parcelas)	
									 <option value="{{$parcelas->id}}" >{{$parcelas->id}}.{{$parcelas->id_titulo}}</option>
										@endforeach
									</select>

							  </div>        
							  </div>

							  <div class="form-group">
								<label class="col-md-3 control-label">Data Emiss??o</label>
								<div class="col-md-4">
								  <small>Data Emiss??o</small>
								  <input type="date" name="dt_emissao"  id="dt_emissao" class="form-control" required >
								</div>     

								<div class="col-md-4">
								  <small>Data Vencimento</small>
								  <input type="date" name="dt_vencimento" id="dt_vencimento"   class="form-control" required>
								</div>        
							  </div>

							  <div class="form-group">
								<label class="col-md-3 control-label">Moeda</label>
								<div class="col-md-5">
								  <select name="moeda" id="moeda" class="form-control" required>
									<option value=""> @lang('padrao.selecione') </option>
									<option value="USD"> USD </option>
									<option value="EUR"> EUR </option>
									<option value="BRL"> BRL </option>
								  </select>
								</div>        
							  </div>

							  <div class="form-group">
								<label class="col-md-3 control-label">Valor</label>
								<div class="col-md-5">
								  <input name="valor" type="decimal"  required>
								</div> 
								  
							  </div>
						  <div class="form-group">
								<label class="col-md-3 control-label">Criar parcelas</label>
								<div class="col-md-5">
								  <select name="criar_parcela" id="criar_parcela" class="form-control" required>
									<option value="">Selecione </option>
									<option value="parcela_unica"> Parcela ??nica </option>
									<option value="multiplas"> Multiplas parcelas </option>

								  </select>
								</div>        
							  </div>

							  <div class="form-group">
								<label class="col-md-3 control-label">Observa????o</label>
								<div class="col-md-8">
									<textarea name="obs" class="form-control"></textarea>
								</div>        
							  </div>


						  </div>
						  <div class="modal-footer">
							<button type="button" class="btn btn-flat btn-default" data-dismiss="modal">@lang('padrao.cancelar')</button>
							<button type="submit" class="btn btn-flat btn-primary">@lang('padrao.salvar') </button>
						  </div>
						</div>
					  </div>
					</div>
					</form> 
          <!-- /.fim modal -->	  
				  
				  
				  
				  
				  
				  
				  
				  
				  
				  
				  
				  
				
	</div>
	<!-- /.tab-pane -->

	
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				<div class="tab-pane" id="settings">
               
					
					<section class="content">
					<div class="row">
										  
							<div class="col-md-5">
										<div class="box-header with-border">
											  <h3 class="box-title">Pedido OI</h3>
											</div>
 									<table class="table table-condensed table-bordered">
											<tr class="bg-primary">
												<td colspan="3" align="center"><small><b>Embarque</b></small></td>
											</tr>    
												<tr>
												<td align="center">Descricao</td>
												<td align="center">Valor</td>
												<td align="center">Vencimento</td>
											</tr>  											

											<tr>
												<td align="center">Duplicata 1</td>
												<td align="center">valor 1</td>
												<td align="center">venc 1</td>
												
											</tr>                

											<tr>
												<td align="center">Duplicata2</td>
												<td align="center">valor 2</td>
												<td align="center">venc 2</td>
											</tr>  
											
											<tr>
												<td align="center">Duplicata 3</td>
												<td align="center">valor 3</td>												
												<td align="center">venc 3</td>
											</tr>  
										
									</table> 

										  <!-- About Me Box -->
										  <div class="box box-primary">																				  
											<table class="table table-condensed table-bordered">
											<tr class="bg-primary">
												<td colspan="3" align="center"><small><b>Parcelas</b></small></td>
											</tr>    
												<tr>
												<td align="center">Descricao</td>
												<td align="center">Valor</td>
												<td align="center">Vencimento</td>
											</tr>  											

											<tr>
												<td align="center">Duplicata 1</td>
												<td align="center">valor 1</td>
												<td align="center">venc 1</td>
												
											</tr>                

											<tr>
												<td align="center">Duplicata2</td>
												<td align="center">valor 2</td>
												<td align="center">venc 2</td>
											</tr>  
											
											<tr>
												<td align="center">Duplicata 3</td>
												<td align="center">valor 3</td>												
												<td align="center">venc 3</td>
											</tr>  
										
									</table> 										
										  </div><!-- /.fecha a segunda caixa -->	
											
											
							</div><!-- /.fecha a col3 -->
										
										  
										  
										  
							 <!-- 1o Box -->			  
							<div class="col-md-5">
 									<table class="table table-condensed table-bordered">
											<tr class="bg-primary">
												<td colspan="3" align="center"><small><b>Pedido trading</b></small></td>
											</tr>    
												<tr>
												<td align="center">Descricao</td>
												<td align="center">Valor</td>
												<td align="center">Vencimento</td>
											</tr>  											

											<tr>
												<td align="center">Duplicata 1</td>
												<td align="center">valor 1</td>
												<td align="center">venc 1</td>
												
											</tr>                

											<tr>
												<td align="center">Duplicata2</td>
												<td align="center">valor 2</td>
												<td align="center">venc 2</td>
											</tr>  
											
											<tr>
												<td align="center">Duplicata 3</td>
												<td align="center">valor 3</td>												
												<td align="center">venc 3</td>
											</tr>  
										
									</table> 
								

								  <!-- About Me Box -->
								  <div class="box box-primary">
									<div class="box-header with-border">
									  <h3 class="box-title">About Me</h3>
									</div>

									<!-- /.box-header -->
									<div class="box-body">									
									  <p>
										<span class="label label-danger">UI Design</span>
										<span class="label label-success">Coding</span>									
									  </p>
									</div>											
								  </div><!-- /.fecha a segunda caixa -->	
											
											
							</div>
						
						<div class="col-md-2">
										  
										<div class="box box-primary">
											<div class="box-body box-profile">										
											texto1
											</div>	
											  texto2
										  </div>
										 

										  <!-- About Me Box -->
										  <div class="box box-primary">
											<div class="box-header with-border">
											  <h3 class="box-title">About Me</h3>
											</div>
											  
											<!-- /.box-header -->
											<div class="box-body">									
											  <p>
												<span class="label label-danger">UI Design</span>
												<span class="label label-success">Coding</span>									
											  </p>
											</div>											
										  </div><!-- /.fecha a segunda caixa -->
								
								
										 
							</div> <!-- /.fecha coljna 5 -->
								 
				</div> <!-- /.row -->
				</section>									 
 	 
              </div><!-- /.fecha aba settings -->
              

				
				
						<!-- general form elements -->
			<div class="tab-pane" id="documentos">

				<form action="/import_form/documento/upload" method="post" class="form-horizontal" enctype="multipart/form-data">
				@csrf
				<input type="hidden" name="pedido" value="{{$pedido}}">
				<div class="box-body">

					<div class="col-md-3">	
					<label for="exampleInputFile">Tipo</label>
					<input type="text" name="tipo">
					<input type="hidden" name="id_info" value="{{$id_info}}">
					</div>

					<div class="col-md-3">	
					<label for="exampleInputFile">Origem</label>
					<input type="text" name="origem">
					</div>

					<div class="col-md-5">
					<label for="exampleInputFile">Upload</label>
					<input type="file" name="arquivo" class="form-control">
					</div>

				</div>

				<div class="box-footer">
				<button type="submit" class="btn btn-primary">Submit</button>
				</div>

				</form>



				<table class="table table-condensed table-bordered">
				<tr class="bg-primary">
					<td colspan="8" align="center"><small><b>Arquivos na pasta</b></small></td>
				</tr>    

					<tr>
					<td>ID Info</td>
					<td>Local</td>
					<td>Tipo Arquivo</td>
					<td>Origem</td>
					<td>Data</td>
					<td>User</td>

					<td>extensao</td>
					<td>arquivo</td>
					</tr>
					@foreach ($query_5 as $docs)
					<tr>

					<td>{{$docs->pedido}}</td>
					<td><a href="{{$docs->path}}">{{$docs->path}}</a></td>
					<td>{{$docs->tipo_arquivo}}</td>
					<td>{{$docs->origem}}</td>
					<td>{{$docs->data}}</td>
					<td>{{$docs->usuario}}</td>
					<td>{{$docs->extensao}}</td>
					<td>{{$docs->arquivo}}</td>
					</tr>
					@endforeach	
				</table> 											


				</div>
		
				
				
				
		
				
				
				
				<div class="tab-pane" id="timeline">
                <form class="form-horizontal">
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Name</label>

                    <div class="col-sm-10">
                      <input type="email" class="form-control" id="inputName" placeholder="Name">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                    <div class="col-sm-10">
                      <input type="email" class="form-control" id="inputEmail" placeholder="Email">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Name</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="inputName" placeholder="Name">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputExperience" class="col-sm-2 control-label">Experience</label>

                    <div class="col-sm-10">
                      <textarea class="form-control" id="inputExperience" placeholder="Experience"></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputSkills" class="col-sm-2 control-label">Skills</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="inputSkills" placeholder="Skills">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-danger">Submit</button>
                    </div>
                  </div>
                
              </div>
			 
              <!-- /.tab-pane -->
				
			
					
					

         
				
					
					
					
					
					
				
				
				
				
            </div>
            <!-- /.tab-content -->
			  
			  
			  
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->

</div>
@include('produtos.painel.modal.uploadFoto')
@stop
