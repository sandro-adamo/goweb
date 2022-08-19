@extends('layout.principal')

@section('title')
<i class="fa fa-suitcase"></i> Dashboard Comercial
@append 

@section('conteudo')

<form action="" method="get"> 
@php

 $representantes = Session::get('representantes');
 $grifes = Session::get('grifes');
 $pedido = $_GET["pedido"];

	
  $where = ' where id_rep in ('.$representantes.') ';
  $where2 = ' where rep_comissao in ('.$representantes.') ';

 //echo $where; 
	

$query_vendas = \DB::select("
	-- identifica primeiro pedido e orcamentos    
	select ano, mes, vendas, -- id_rep, codgrife, financeiro,  
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
							and vds.pedido = $pedido
							
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
group by ano, mes , vendas
	order by ano, mes desc

");


$query_temp = \DB::select("
	select * from pedidos_jdes where ped_original = $pedido
");

	
	
	
$query_pedidos = \DB::select("
	select ped_original, sum(qtde) qtde, sum(valor) valor from pedidos_jdes where ped_original = $pedido group by ped_original
");


$query_grifes = \DB::select("
		select codgrife, sum(qtde) qtde, sum(valor) valor from pedidos_jdes where ped_original = $pedido group by codgrife
");

echo 'teste'.$where2;
	

@endphp
<h6>

<div class="row">

	
	
	<div class="col-md-12">
		<div class="box box-body box-widget">
			<table class="table table-bordered"> 
		 <tr>	
	 		<td colspan="8">VENDAS (filtro de datas)</td>
				</tr>
		  <tr>	
	 			<!--
	 		<td colspan="1" align="center">ANO / MES</td>		 	
		 	<td colspan="1" align="center">venda_total</td>
		 	<td colspan="1" align="center">venda_aberto</td>
			<td colspan="1" align="center">venda_orcamento</td>
			<td colspan="1" align="center">venda_pedido</td>
			  -->
			<td colspan="1" align="center">Pedido</td>
			   <!--
			<td colspan="1" align="center">vlr_pedido</td>
		 	<td colspan="1" align="center">primeiro_ped</td>
			<td colspan="1" align="center">atendimentos</td>
			<td>qtde entregas</td> 
			 
		 	<td colspan="1" align="center">vlr_so_aberto</td>
			<td colspan="1" align="center">vlr_so_faturado</td>
			  
			  <td colspan="1" align="center">Dupl Paga</td>
			  <td colspan="1" align="center">Comissao</td>
			  -->
			  
		 	

									
			
@foreach ($query_temp as $query1)
	<tr>

<td align="center" class="text-red"><a href="/comercial_ped?pedido={{$query1->ped_original}}">
{{$query1->ped_original}}</a></td>
		<td>{{$query1->pedido}}</td>
		<td>{{$query1->qtde}}</td>
		<td>{{$query1->valor}}</td>

</tr>
@endforeach 
				
			</table>
			<i><a href="">+</a></i>
		</div>
		
	</div>


	<div class="col-md-4">
		<div class="box box-body box-widget">
			<table class="table table-bordered"> 
		 <tr>	
	 		<td colspan="8">PEDIDOS (filtro de datas)</td>
				</tr>
		  <tr>	
	 		
	 		<td colspan="1" align="center">pedido</td>

	
		
			
			@foreach ($query_pedidos as $query3)
				<tr>
					<td align="center">{{$query3->ped_original}}</td>
					
				
				</tr>
			@endforeach 
			</table>
			<i><a href="">+</a></i>
		</div>
		
	</div>

	
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
		 	
	
		
			
			@foreach ($query_grifes as $query2)
				<tr>
					<td align="center">{{$query2->qtde}}</td>
				
					
					<td align="center">{{number_format($query2->valor,0)}}</td>
					
						
					<td align="center" class="text-red">
					{{$query2->codgrife}}</td>
					
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


	

</div>
</form>
</h6>
@stop