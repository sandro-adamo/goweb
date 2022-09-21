@extends('layout.principal')
@section('conteudo')

@php


$query_1 = \DB::select("select ifnull(max(id_pedido)+1,900000) prox from compras_infos where id_pedido like '900%' ");

$query_r = \DB::select("select taxa1, taxa2, taxa3 from compras_registros order by created_at desc limit 1");



$query_dados = \DB::select("	
																								   
select * from (
	select pedido, tipo, invoice,  acao_capa, vinculo_infos, ped_jde, tipo_jde, desc_status,
	group_concat(distinct tipoitem) tipoitem, group_concat(distinct fornecedor) fornecedor, group_concat(distinct agrup) agrup, 
	group_concat(distinct colmod) colmod, sum(orcamentos) orcamentos, sum(itens_trans) itens_trans, sum(itens_prod) itens_prod, sum(mostruarios) mostruarios,
	sum(qtde) qtde

	from (
        select * from (
            select distinct pedido, tipo, ref_go invoice, 
            case when codtipoitem = '006' then 'peca' else 'parte' end as tipoitem,
            case when codtipoitem = '006' then cod_item else id_pai end as coditem,

			case when infostemp.id_pedido is not null and infosjde.id_pedido is not null then 'ERRO'
			when infosjde.id_pedido is not null then 'update' when infostemp.id_pedido is not null then 'vincular'
			else 'insert' end as acao_capa,


            case when infosjde.id is not null then infosjde.id else infostemp.id end as vinculo_infos,
            '' ped_jde, '' tipo_jde, 
            case 
				when prox_status = 230 then concat(ult_status, ' / ',prox_status , ' - ped_inserido' )
				when prox_status = 280 then concat(ult_status, ' / ',prox_status ,' - PL_recebido' )
				when prox_status = 345 then concat(ult_status, ' / ',prox_status ,' - confirmado' )
				when prox_status = 350 then concat(ult_status, ' / ',prox_status ,' - li_solicitado')
				when prox_status = 355 then concat(ult_status, ' / ',prox_status ,' - li_deferida')
				when prox_status = 359 then concat(ult_status, ' / ',prox_status ,' - emb_autorizado')
				when prox_status = 365 then concat(ult_status, ' / ',prox_status ,' - booking')
				when prox_status = 369 then concat(ult_status, ' / ',prox_status ,' - chegada_Br')
				when prox_status = 375 then concat(ult_status, ' / ',prox_status ,' - removido')
				when prox_status = 379 then concat(ult_status, ' / ',prox_status ,' - registrado')
				when prox_status = 385 then concat(ult_status, ' / ',prox_status ,' - nf_emitida')
				when prox_status = 390 then concat(ult_status, ' / ',prox_status ,' - carregada')
				when prox_status = 400 then concat(ult_status, ' / ',prox_status ,' - chegou_TO') else '' end as desc_status,
			qtde_sol qtde
            
			from importacoes_pedidos imp
				left join compras_infos infosjde on infosjde.id_pedido = imp.pedido  and infosjde.tipo_pedido = imp.tipo
				left join compras_infos infostemp on infostemp.invoice_temp = imp.ref_go
				left join itens on itens.id = imp.cod_item
				left join itens_estrutura estrutura on  estrutura.id_filho = imp.cod_item
                
			where dt_pedido >= '20220101' and ref_go not in ('LA200501','QGKI17-7B') 
			and ult_status not in (980)  and prox_status not in (999) 
			and ((imp.tipo = 'op' and gl_clas  in ('AG01','rv01','pa01','mp01' )) or imp.tipo = 'oi')
		) as fim1	
    
			left join (select id_item, fornecedor, left(agrup,5) agrup, 
			case when left(colmod,4) = year(now()) then colmod else left(colmod,4) end as colmod, 
			orcamento_liber+orcamento_bloq orcamentos,itens_trans, itens_prod, 
			mostruarios
            from go_storage.sintetico_estoque ) sint
            on sint.id_item = fim1.coditem
	) as fim2 group by pedido, tipo, invoice,  acao_capa, vinculo_infos, ped_jde, tipo_jde, desc_status
    
    
    union all 
    
    select distinct ci.id_pedido pedido, ci.tipo_pedido tipo, ci.invoice_temp invoice,
            case when imp.ref_go is not null then 'pedido' else 'aguardar' end as acao_capa, 
            ci.id vinculo_infos,
            imp.pedido ped_jde, imp.tipo tipo_jde, '' desc_status, '' tipoitem, '' fornecedor, '' agrup, '' colmod, 0 orcamentos, 0 itens_trans,
            0 itens_prod, 0 mostruarios, 0 as qtde
            
            from compras_infos ci
            left join importacoes_pedidos imp on ci.num_temp = imp.ref_go	
            where ci.tipo_pedido = 'new'
	) as baseaberto
    
    left join (select * from compras_infos ) infos
	on infos.id = baseaberto.vinculo_infos		
	");
					
					

@endphp


				
<div class="row">	

	<div class="col-md-10">
	<!-- if taxa_calculo -->
	<!-- novo controler insere tabela compras_registros e salva na linha compras_infos -->
	
	
	<h3>Simular cambio</h3>
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
		
		
	<button type="submit" class="btn btn-primary"><i class="fa fa-refresh"></i> Recalcular</button>	
	</form>
	</div> <!-- col-md-6 -->
	</br>
	
	
	
	
 <div class="col-md-12">
  <div class="nav-tabs-custom">
           
	  
		<ul class="nav nav-tabs">
		  <li class="active"><a href="#teste" data-toggle="tab">teste</a></li>
		  <li><a href="#geral" data-toggle="tab">Geral</a></li>
		  <li><a href="#removido" data-toggle="tab">Removido</a></li>
		  <li><a href="#transito" data-toggle="tab">Transito</a></li>
		  <li><a href="#embarque" data-toggle="tab">Embarque</a></li>
		  <li><a href="#perdimento" data-toggle="tab" class='text-red'>Perdimento</a></li>
		  <li><a href="#sem_pedido" data-toggle="tab" class='text-yellow'>Sem pedido JDE</a></li>
		  <li><a href="#documentos" data-toggle="tab">Documentos</a></li>
		  <li><a href="#leadtime" data-toggle="tab">Leadtime</a></li>
		  <li><a href="#kering" data-toggle="tab" class='text-blue'>Kering</a></li>
		</ul>


<div class="tab-content">

		
					
					
<div class="active tab-pane" id="teste">
	<form action="/import_form/gravatemp" method="post" class="form-horizontal">
	@csrf
		<input type="hidden" id="pedido" name="pedido" size="50" value={{$query_1[0]->prox}}>
		
		<input type="hidden" id="acao" name="acao" size="50" value=insnew >
		<input type="hidden" id="tipo" name="tipo" size="50" value='new' >	
	
		
		<input type="text" id="invoice_temp" name="invoice_temp" size="30" required>
		
		<td align="left"><button type="submit"><i class="fa fa-refresh text-green">Adiciona Invoice</i></button>
	</form>	
	
<h6> 
	<tr><td colspan="4">teste </td></tr>
	<table class="tabela2 table-striped table-bordered compact" >
	<thead>				
		<tr>

		<td colspan="1" align="center">Tipo Pedido</td>
		<td>Invoice</td>
			
		<td>TP</td>
		<td>Conex</td>
		<td colspan="1" align="center">fornecedor</td>						
		<td colspan="1" align="center">agrupamento</td>	
		<td colspan="1" align="center">colecao</td>	
			
		<td colspan="1" align="center">Qtde</td>
		<td colspan="1" align="center">Atende</td>
		<td colspan="1" align="center">Status</td>
		</tr>
	</thead>

		@foreach ($query_dados as $queryd)

		
		<tr>

			<form action="/import_form/atualizareg" method="post" class="form-horizontal"> @csrf
				
			<input type="hidden" id="acao_capa" name="acao_capa" size="50" value={{$queryd->acao_capa}} >
			<input type="hidden" id="id_temp" name="id_temp" size="50" value={{$queryd->vinculo_infos}} >	
			<input type="hidden" id="pedido" name="pedido" size="50" value={{$queryd->pedido}} >
			<input type="hidden" id="tipo_pedido" name="tipo_pedido" size="50" value={{$queryd->tipo}} >


			


			
		@php if($queryd->acao_capa=='ERRO'){ @endphp		
		<td>
			<a href="/import_form/?tipo={{$queryd->tipo}}&pedido={{$queryd->pedido}}" target="_blank">
				<span class="fa fa-exclamation-circle text-red btn-xs"></span></a>
		</td>
		 
		@php } elseif ($queryd->acao_capa=='update'){ @endphp	
		<td>
			<a href="/import_form/?tipo={{$queryd->tipo}}&pedido={{$queryd->pedido}}" target="_blank">
				<span><span class="fa fa-list-alt text-green"></span></span></a>
		</td>	
			
		@php } elseif ($queryd->acao_capa=='aguardar'){ @endphp	
		<td>
			<a href="/import_form/?tipo={{$queryd->tipo}}&pedido={{$queryd->pedido}}" target="_blank">
			<span class="fa fa-list text-yellow"></span></a>
		</td>	
		
		@php } elseif ($queryd->acao_capa=='insert'){ @endphp	
		<td>
			<a href="/import_form/?tipo={{$queryd->tipo}}&pedido={{$queryd->pedido}}" target="_blank">
			<span class="fa fa-file-o text-blue"></span></a>
		</td>
						
		@php } elseif ($queryd->acao_capa=='vincular'){ @endphp		
		<td><button type="submit"><i class="fa fa-chain-broken text-blue"></i></button></td> 
				
		@php } elseif ($queryd->acao_capa=='pedido'){ @endphp		
		<td><button type="submit"><i class="fa fa-chain-broken text-gray"></i></button></td>		
				
		@php } else {$a=0; }
			
				
		@endphp
		
		<td></td>		
		<td align="left">{{$queryd->tipo.' '.$queryd->pedido}}</td>
		<td align="left">{{$queryd->invoice}}</td>
		<td align="left">{{$queryd->tipo_agrup}}</td>
		<td align="left">{{$queryd->doc_agrup}}</td>	
				
				
		
		<td align="left">{{$queryd->fornecedor}}</td>			
		<td align="left">{{$queryd->agrup}}</td>
		<td align="left">{{$queryd->colmod}}</td>
				
		<td align="center">{{number_format($queryd->qtde,0,',','.')}}</td>
		<td align="center">{{number_format($queryd->atende,0,',','.')}}</td>
		<td align="center">{{$queryd->desc_status}}</td>

		</tr></form>
		@endforeach 
	</table>
	</h6> 
</div>	<!-- active tab-pane 	-->	
					
					
			
		  
					

			  
				</div> 		<!-- tab-content 		-->
			</div>			<!-- nav-tabs-custom 	-->		
		</div> <!-- col-md-12 -->

</div>		<!-- row -->		
@stop