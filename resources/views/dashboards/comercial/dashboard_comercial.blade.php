@extends('layout.principal')

@section('title')
<i class="fa fa-suitcase"></i> Dashboard Comercial
@append 

@section('conteudo')

<form action="" method="get"> 
@php

 $representantes = Session::get('representantes');
 $grifes = Session::get('grifes');

  $where = ' where id_rep in ('.$representantes.') ';
  $where2 = ' where rep_comissao in ('.$representantes.') ';

 // echo $where; 

	
	
$query_faturamentos = \DB::select("
	select sum(total) valor_faturamento 
	from notas_jde nfs left join itens on itens.id = nfs.id_item where id_rep in ($representantes) and ult_status not in ('997','996','998')  
	and year(dt_emissao) = year(now()) and month(dt_emissao) = month(now()) and codtipoitem = 006
");
	

		
$query_emissao = \DB::select("
	select sum(valor) valor_emissao from pedidos_jdes where id_rep in ($representantes) and ult_status not in ('980','984')  
	and year(dt_emissao) = year(now()) and month(dt_emissao) = month(now()) 
");

	

$query_vendas = \DB::select("
	-- identifica primeiro pedido e orcamentos    
	select ano, mes, -- vendas, -- id_rep, codgrife, financeiro,  
			sum(venda_total) venda_total, sum(venda_aberto) venda_aberto, sum(venda_pedido)-sum(vlr_so_cancelado) venda_pedido, 
            sum(venda_orcamento)+sum(vlr_so_cancelado) venda_orcamento,
            
            sum(vlr_pedido)-sum(vlr_so_cancelado) vlr_pedido,
            case when sum(atendimentos) < sum(vlr_so_cancelado) then sum(primeiro_ped) - sum(vlr_so_cancelado) else sum(primeiro_ped) end as primeiro_ped, 
            case when sum(atendimentos) >= sum(vlr_so_cancelado) then sum(atendimentos) - sum(vlr_so_cancelado) else sum(atendimentos) end as atendimentos,
            
            -- so
            sum(vlr_so_cancelado) vlr_so_cancelado, sum(vlr_so_aberto) vlr_so_aberto, sum(vlr_so_faturado) vlr_so_faturado
            
        
		
	from (
    
		select * from (
			select vendas, ano, mes, id_rep, codgrife, financeiro, 
	
			sum(vlr_total) venda_total, sum(vlr_total)-sum(vlr_venda_ped)-sum(vlr_venda_orcamento) venda_aberto, sum(vlr_venda_ped) venda_pedido, sum(vlr_venda_orcamento) venda_orcamento, 
			ifnull(sum(valor),0) vlr_pedido, ifnull(sum(vlr_prim),0) primeiro_ped, ifnull(sum(valor)-sum(vlr_prim),0) atendimentos,
            -- so
            ifnull(sum(vlr_so_cancelado),0) vlr_so_cancelado, ifnull(sum(vlr_so_faturado),0) vlr_so_faturado, ifnull(sum(vlr_so_aberto),0) vlr_so_aberto
			from (
			 
				select * from (        
						select ano, mes, codgrife, id_rep, vendas, financeiro, 
						
						sum(valor) vlr_total, sum(vlr_venda_aberto) vlr_venda_aberto, sum(vlr_venda_ped) vlr_venda_ped, sum(vlr_venda_orcamento) vlr_venda_orcamento, sum(vlr_venda_canc) vlr_venda_canc
						from (
							
							select year(dt_venda) ano, month(dt_venda) mes, codgrife, id_rep, pedido vendas, valor, financeiro,
								
                                case when concat(vds.ult_status,'/',vds.prox_status) in ('500/505','505/510','510/512') then vds.valor else 0 end as vlr_venda_aberto,		  
								case when concat(vds.ult_status,'/',vds.prox_status) in ('510/999','512/999','515/999','516/999') then vds.valor else 0 end as vlr_venda_ped,
								case when vds.prox_status in ('515','516') then vds.valor else 0 end as vlr_venda_orcamento,
								case when vds.ult_status in ('980','984') then vds.valor else 0 end as vlr_venda_canc
								
							from vendas_jdes vds
							left join addressbook ab on ab.id = vds.id_cliente
							$where and vds.ult_status not in ('980','984') 
							and year(dt_venda) >= 2020 
							and dt_venda >= CONCAT(YEAR(DATE_ADD(NOW(), INTERVAL -3 MONTH)), '-', MONTH(DATE_ADD(NOW(), INTERVAL -3 MONTH)), '-01')

							
						) as fim0
						group by ano, mes, id_rep, vendas, financeiro, codgrife
				) as fim


				left join (
				
					select  grife, ped_original, 
                    sum(valor) valor, sum(vlr_so_cancelado) vlr_so_cancelado, sum(vlr_so_faturado) vlr_so_faturado, sum(vlr_so_aberto) vlr_so_aberto, sum(vlr_prim) vlr_prim
					from (
						select codgrife grife, ped_original, num_pedido, emissao, dt_prim, 
                        sum(valor) valor, sum(vlr_so_cancelado) vlr_so_cancelado, 
                        sum(vlr_so_faturado) vlr_so_faturado, sum(vlr_so_aberto) vlr_so_aberto,
						case when dt_prim = emissao then sum(valor) else 0 end as vlr_prim
						from (

							select codgrife, pedido num_pedido, ped_original, dt_emissao emissao, valor,                            
								-- case when ped.ult_status in ('980','984') then valor else 0 end as vlr_so_cancelado,
                                case when ped.ult_status in ('902','904','912','914') then valor else 0 end as vlr_so_cancelado,
                                
								case when ped.ult_status like '6%' then valor else 0 end as vlr_so_faturado,
								case when (ped.ult_status like '5%') then valor else 0 end as vlr_so_aberto,
 								(select min(ped0.dt_emissao) from pedidos_jdes ped0 where ped0.ped_original = ped.ped_original) as dt_prim
								
								from pedidos_jdes ped
								$where and ped.ult_status not in ('980','984')
														
                            
						) as fim
						group by codgrife, ped_original, num_pedido, emissao, dt_prim
					) as fim2 group by grife, ped_original
				
				) as final 
				on final.ped_original = vendas and final.grife = fim.codgrife
				
			) as final1
		group by vendas, ano, mes, id_rep, codgrife, financeiro
		) as final


) as final2
group by ano, mes -- , vendas
	order by ano desc, mes desc

");


	
	
	
$query_pedidos = \DB::select("
	-- identifica primeiro pedido e orcamentos    
	select dia, -- vendas, -- id_rep, codgrife, financeiro,  
			sum(venda_total) venda_total, sum(venda_aberto) venda_aberto, sum(venda_pedido)-sum(vlr_so_cancelado) venda_pedido, 
            sum(venda_orcamento)+sum(vlr_so_cancelado) venda_orcamento,
            
            sum(vlr_pedido)-sum(vlr_so_cancelado) vlr_pedido,
            case when sum(atendimentos) < sum(vlr_so_cancelado) then sum(primeiro_ped) - sum(vlr_so_cancelado) else sum(primeiro_ped) end as primeiro_ped, 
            case when sum(atendimentos) >= sum(vlr_so_cancelado) then sum(atendimentos) - sum(vlr_so_cancelado) else sum(atendimentos) end as atendimentos,
            
            -- so
            sum(vlr_so_cancelado) vlr_so_cancelado, sum(vlr_so_aberto) vlr_so_aberto, sum(vlr_so_faturado) vlr_so_faturado
            
        
		
	from (
    
		select * from (
			select vendas, dia, id_rep, codgrife, financeiro, 
			sum(vlr_total) venda_total, sum(vlr_total)-sum(vlr_venda_ped)-sum(vlr_venda_orcamento) venda_aberto, sum(vlr_venda_ped) venda_pedido, sum(vlr_venda_orcamento) venda_orcamento, 
			ifnull(sum(valor),0) vlr_pedido, ifnull(sum(vlr_prim),0) primeiro_ped, ifnull(sum(valor)-sum(vlr_prim),0) atendimentos,
            -- so
            ifnull(sum(vlr_so_cancelado),0) vlr_so_cancelado, ifnull(sum(vlr_so_faturado),0) vlr_so_faturado, ifnull(sum(vlr_so_aberto),0) vlr_so_aberto
			from (
			 
				select * from (        
						select dia, codgrife, id_rep, vendas, financeiro, 
						
						sum(valor) vlr_total, sum(vlr_venda_aberto) vlr_venda_aberto, sum(vlr_venda_ped) vlr_venda_ped, sum(vlr_venda_orcamento) vlr_venda_orcamento, sum(vlr_venda_canc) vlr_venda_canc
						from (
							
							select day(dt_venda) dia, codgrife, id_rep, pedido vendas, valor, financeiro,
								
                                case when concat(vds.ult_status,'/',vds.prox_status) in ('500/505','505/510','510/512') then vds.valor else 0 end as vlr_venda_aberto,		  
								case when concat(vds.ult_status,'/',vds.prox_status) in ('510/999','512/999','515/999','516/999') then vds.valor else 0 end as vlr_venda_ped,
								case when vds.prox_status in ('515','516') then vds.valor else 0 end as vlr_venda_orcamento,
								case when vds.ult_status in ('980','984') then vds.valor else 0 end as vlr_venda_canc
								
							from vendas_jdes vds
							left join addressbook ab on ab.id = vds.id_cliente
							$where and vds.ult_status not in ('980','984') 
							 and year(dt_venda) = year(now()) and month(dt_venda) = month(now())
							
						) as fim0
						group by dia, id_rep, vendas, financeiro, codgrife
				) as fim


				left join (
				
					select  grife, ped_original, 
                    sum(valor) valor, sum(vlr_so_cancelado) vlr_so_cancelado, sum(vlr_so_faturado) vlr_so_faturado, sum(vlr_so_aberto) vlr_so_aberto, sum(vlr_prim) vlr_prim
					from (
						select codgrife grife, ped_original, num_pedido, emissao, dt_prim, 
                        sum(valor) valor, sum(vlr_so_cancelado) vlr_so_cancelado, 
                        sum(vlr_so_faturado) vlr_so_faturado, sum(vlr_so_aberto) vlr_so_aberto,
						case when dt_prim = emissao then sum(valor) else 0 end as vlr_prim
						from (

							select codgrife, pedido num_pedido, ped_original, dt_emissao emissao, valor,                            
								-- case when ped.ult_status in ('980','984') then valor else 0 end as vlr_so_cancelado,
                                case when ped.ult_status in ('902','904','912','914') then valor else 0 end as vlr_so_cancelado,
                                
								case when ped.ult_status like '6%' then valor else 0 end as vlr_so_faturado,
								case when (ped.ult_status like '5%') then valor else 0 end as vlr_so_aberto,
 								(select min(ped0.dt_emissao) from pedidos_jdes ped0 where ped0.ped_original = ped.ped_original) as dt_prim
								
								from pedidos_jdes ped
								$where and ped.ult_status not in ('980','984')
														
                            
						) as fim
						group by codgrife, ped_original, num_pedido, emissao, dt_prim
					) as fim2 group by grife, ped_original
				
				) as final 
				on final.ped_original = vendas and final.grife = fim.codgrife
				
			) as final1
		group by vendas, dia, id_rep, codgrife, financeiro
		) as final


) as final2
group by dia -- , vendas
	order by dia desc
");

	

	
	
$query_teste1 = \DB::select("
	select * from itens where modelo = 'ah1020'
");


$query_teste2 = \DB::select("
		select * from itens where modelo = 'ah1020'
");

$query_faturas = \DB::select("
		select * from itens where modelo = 'ah1020'
	");
	

@endphp
<h6>

<div class="row">

<!--	
	<div class="col-md-12">	O QUE ESTA SENDO PROCESSADO NO MES - PRE-VENDAS ACUMULADAS </div>
	
				<div class="col-lg-3 col-xs-6 col-md-3" style="width: 20%">

				  <!-- small box -->
	<!--			 <div class="small-box bg-aqua">

					<div title="Pedidos Gerados no mes vigente" class="inner">
					  <h3>@if ($query_emissao) {{number_format($query_emissao[0]->valor_emissao,2,',','.')}} @endif</h3>
					  <p>Pedidos gerados no mês</p>
					</div>
					<div class="icon">
					 <small> <i class="fa fa-television"></i></small>
					</div>

					<a title="Vendas do mês atual" href="/vendas?ano={{date('Y')}}&mes={{date('m')}}" class="small-box-footer">Mais informações <i class="fa fa-arrow-circle-right"></i></a>
				  </div>
				</div>
	
	
	
	
			<div class="col-lg-3 col-xs-6 col-md-3" style="width: 20%">

				  <!-- small box -->
	<!--			  <div class="small-box bg-green">

					<div title="Faturamentos no mes vigente" class="inner">
					  <h3>@if ($query_faturamentos) {{number_format($query_faturamentos[0]->valor_faturamento,2,',','.')}} @endif</h3>
					  <p>Faturados no mês</p>
					</div>
					<div class="icon">
					<small><i class="fa fa-money"></i></small>  
					</div>

					<a title="Vendas do mês atual" href="/vendas?ano={{date('Y')}}&mes={{date('m')}}" class="small-box-footer">Mais informações <i class="fa fa-arrow-circle-right"></i></a>
				  </div>
			</div>
 --> 
	
<!-- fim dos cubos --> 	
	
	
	
		
	
	
	
	
	
	
	
	<div class="col-md-12">	
	QUAL O STATUS DAS PRE-VENDAS EFETUADAS NO MES
		</div>	
	<div class="col-md-12">
		<div class="box box-body box-widget">
			<table class="table table-bordered"> 

		<tr>	
	 		<td colspan="1" align="center"> </td>	
	 		<td colspan="1" align="center">ANO / MES</td>		 	
		 	<td colspan="1" align="center">Venda_total</td>
		 	<td colspan="1" align="center">Geração pedido</td>
			<td colspan="1" align="center">Orcamento</td>
			<td colspan="1" align="center">Pedido Gerado</td>
		 	<td colspan="1" align="center">Pedido em separação</td>
			<td colspan="1" align="center">Pedido faturado</td>			  
  			
			  
			  
	@php
	$total_vendas = 0;
	$total_aberto = 0;
	$total_orcamento = 0;
	$total_pedido = 0;
	$total_so_aberto = 0;
	$total_so_faturado = 0;



	@endphp
						
			
@foreach ($query_vendas as $query1)
	
			  @php
              $total_vendas += $query1->venda_total;
			  $total_aberto += $query1->venda_aberto;
              $total_orcamento += $query1->venda_orcamento;
			  $total_pedido += $query1->venda_pedido;
			  $total_so_aberto += $query1->vlr_so_aberto;
			  $total_so_faturado += $query1->vlr_so_faturado;
			  
			  @endphp
			  		  
<tr>

<td align="center" class="text-red">
	<a href="/comercial_rep?ano={{$query1->ano}}&mes={{$query1->mes}}"><i class="fa fa-users"> | </i> </a>
	<a href="/comercial_det?ano={{$query1->ano}}&mes={{$query1->mes}}"><i class="fa fa-file-text-o">  | </i> </a>
		
</td>
<td>{{$query1->ano.' / '.$query1->mes}}</td>
<td align="center" class="text-red">{{number_format($query1->venda_total,0)}}</td>											
<td align="center" class="text-red">{{number_format($query1->venda_aberto,0)}}</td>										
<td align="center" class="text-red">
	{{' '.number_format($query1->venda_orcamento, 0, ',', '.')}}</i></td>		
<td align="center" class="text-red"><a href="/comercial_vda_det?ano={{$query1->ano}}&mes={{$query1->mes}}">
	{{number_format($query1->venda_pedido, 0, ',', '.').' / '.number_format(($query1->venda_pedido/$query1->venda_total)*100,2).'%'}}</a></td>		

<td align="center" class="text-green">{{number_format($query1->vlr_so_aberto, 0, ',', '.')}}</td>					
<td align="center" class="text-green">{{number_format($query1->vlr_so_faturado, 0, ',', '.').' / '.number_format(($query1->vlr_so_faturado/$query1->venda_total)*100,2).'%'}}</td>

<td></td>

</tr>
@endforeach 
		
		
				<td align="center"><STRONG>TOTAIS</STRONG></td>
				<td align="left"><a href="/comercial_hist"><i class="fa fa-arrow-down"></i> HISTORICO</a></td>
				<td align="center">{{number_format($total_vendas, 0, ',', '.')}}</td>			
				<td align="center">{{number_format($total_aberto, 0, ',', '.')}}</td>
				<td align="center"><a href="/orcamento_dash">{{number_format($total_orcamento, 0, ',', '.')}}</a>
				</td>
				<td align="center"><a href="/comercial_vda">{{number_format($total_pedido, 0, ',', '.')}}</a></td>
				<td align="center">{{number_format($total_so_aberto, 0, ',', '.')}}</td>
				<td align="center">{{number_format($total_so_faturado, 0, ',', '.')}}</td>
				
			</table>
		</div>	
	</div>


		
	
<!-- seguna table por dia -->	
	
	
	<div class="col-md-10">
		<div class="box box-body box-widget">
			<table class="table table-bordered"> 
		 <tr>	
	 		<td colspan="8">Pedidos do mes vigente</td>
				</tr>
		  <tr>	
	 		
	 		<td colspan="1" align="center">DIA</td>		 	
		 	<td colspan="1" align="center">Venda_total</td>
		 	<td colspan="1" align="center">Geração pedido</td>
			<td colspan="1" align="center">Orcamento</td>
			<td colspan="1" align="center">Pedido Gerado</td>
		 	<td colspan="1" align="center">Pedido em separação</td>
			<td colspan="1" align="center">Pedido faturado</td>	
			  
	
		
			
			@foreach ($query_pedidos as $query3)
					<tr>
<!--
<td align="center" class="text-red"><a href="/comercial_det?dia={{$query3->dia}}&mes={{$query3->dia}}">
{{$query3->dia}}</a></td>
-->
<td align="center" class="text-red">{{$query3->dia}}</td>
<td align="center" class="text-red">{{number_format($query3->venda_total,0)}}</td>											
<td align="center" class="text-red">{{number_format($query3->venda_aberto,0)}}</td>										
<td align="center" class="text-red">{{number_format($query3->venda_orcamento, 0, ',', '.')}}</td>		
<td align="center" class="text-red"><a href="/comercial_vda_det?ano={{$query3->dia}}&mes={{$query3->dia}}">
	{{number_format($query3->venda_pedido, 0, ',', '.').' / '.number_format(($query3->venda_pedido/$query3->venda_total)*100,2).'%'}}</a></td>			

<td align="center" class="text-green">{{number_format($query3->vlr_so_aberto, 0, ',', '.')}}</td>					
<td align="center" class="text-green">{{number_format($query3->vlr_so_faturado, 0, ',', '.').' / '.number_format(($query3->vlr_so_faturado/$query3->venda_total)*100,2).'%'}}</td>

<td></td>
<td></td>
</tr>
			@endforeach 
			</table>
			<i><a href=""></a></i>
		</div>
		
	</div>

<!--	
	<div class="col-md-4">
		<div class="box box-body box-widget">
			<table class="table table-bordered"> 
		 <tr>	
	 		<td colspan="8">FATUAMENTOS (filtro de datas)</td>
				</tr>
		  <tr>	
	 		
	 		<td colspan="1" align="center">ANO / MES</td>	
		 	<td colspan="1" align="center">QTDE</td>
		 	<td colspan="1" align="center">VLR FATURADO</td>
		 	<td colspan="1" align="center"></td>
		 	
	
		
			
			@foreach ($query_faturas as $query2)
				<tr>
					<td align="center">{{$query2->valortabela.' / '.$query2->valortabela}}</td>
				
					
					<td align="center">{{number_format($query2->valortabela,0)}}</td>
					
						
					<td align="center" class="text-red"><a href="/clientes/notas_det?ano={{$query2->valortabela}}&mes={{$query2->valortabela}}">
					{{number_format($query2->valortabela, 2, ',', '.')}}</a></td>
					
					<td></td>

				</tr>
			@endforeach 
			</table>
			<i><a href="">+</a></i>
		</div>
		
	</div>
	


		
				
	<div class="col-md-3">
		<div class="box box-body box-widget">
		recebimentos
		</div>
	</div>
	
	<div class="col-md-3">
		<div class="box box-body box-widget">
		devolucoes
		</div>
	</div>

	
	<div class="col-md-3">
		<div class="box box-body box-widget">
		orcamentos
		</div>
	</div>

-->
	

</div>
</form>
</h6>
@stop