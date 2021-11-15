@extends('layout.principal')

@php
 $representantes = Session::get('representantes');
 $grifes = Session::get('grifes');
 $pedido = $_GET["pedido"];


  $where = ' where id_rep in ('.$representantes.') ';
  $where2 = ' where rep_comissao in ('.$representantes.') ';
	

@endphp


@section('title')
<i class="fa fa-list-alt"></i> Detalhe da Pre-venda {{$pedido}}
@append 

@section('conteudo')

<form action="" method="get"> 
@php

$query_vendas = \DB::select("
select final3.*, concat(id_cliente,' - ',fantasia) cliente from (
	select ano, mes, vendas,id_cliente,  secundario, codgrife, -- id_rep, codgrife, financeiro,  
			sum(venda_total) venda_total, sum(venda_aberto) venda_aberto, sum(venda_pedido)-sum(vlr_so_cancelado) venda_pedido, 
            sum(venda_orcamento)+sum(vlr_so_cancelado) venda_orcamento,
            
            sum(vlr_pedido)-sum(vlr_so_cancelado) vlr_pedido,
            case when sum(atendimentos) < sum(vlr_so_cancelado) then sum(primeiro_ped) - sum(vlr_so_cancelado) else sum(primeiro_ped) end as primeiro_ped, 
            case when sum(atendimentos) >= sum(vlr_so_cancelado) then sum(atendimentos) - sum(vlr_so_cancelado) else sum(atendimentos) end as atendimentos,
            
            -- so
            sum(vlr_so_cancelado) vlr_so_cancelado, sum(vlr_so_aberto) vlr_so_aberto, sum(vlr_so_faturado) vlr_so_faturado
            
        
		
	from (
    
		select * from (
			select vendas, ano, mes, id_rep, id_item, financeiro, id_cliente, secundario, codgrife,
			sum(vlr_total) venda_total, sum(vlr_total)-sum(vlr_venda_ped)-sum(vlr_venda_orcamento) venda_aberto, sum(vlr_venda_ped) venda_pedido, sum(vlr_venda_orcamento) venda_orcamento, 
			ifnull(sum(valor),0) vlr_pedido, ifnull(sum(vlr_prim),0) primeiro_ped, ifnull(sum(valor)-sum(vlr_prim),0) atendimentos,
            -- so
            ifnull(sum(vlr_so_cancelado),0) vlr_so_cancelado, ifnull(sum(vlr_so_faturado),0) vlr_so_faturado, ifnull(sum(vlr_so_aberto),0) vlr_so_aberto
			from (
			 
				select * from (        
						select ano, mes, id_item, id_rep, vendas, financeiro, id_cliente, secundario, codgrife,
						
						sum(valor) vlr_total, sum(vlr_venda_aberto) vlr_venda_aberto, sum(vlr_venda_ped) vlr_venda_ped, sum(vlr_venda_orcamento) vlr_venda_orcamento, sum(vlr_venda_canc) vlr_venda_canc
						from (
							
							select year(dt_venda) ano, month(dt_venda) mes, id_item, id_rep, pedido vendas, valor, financeiro, id_cliente, secundario, codgrife,
								
                                case when concat(vds.ult_status,'/',vds.prox_status) in ('500/505','505/510','510/512') then vds.valor else 0 end as vlr_venda_aberto,		  
								case when concat(vds.ult_status,'/',vds.prox_status) in ('510/999','512/999','515/999','516/999') then vds.valor else 0 end as vlr_venda_ped,
								case when vds.prox_status in ('515','516') then vds.valor else 0 end as vlr_venda_orcamento,
								case when vds.ult_status in ('980','984') then vds.valor else 0 end as vlr_venda_canc
								
							from vendas_jde vds
							left join addressbook ab on ab.id = vds.id_cliente
                            left join itens on itens.id = vds.id_item
							$where and vds.ult_status not in ('980','984') and codtipoitem = 006
							and vds.pedido = '$pedido'
							
						) as fim0
						group by ano, mes, id_rep, vendas, financeiro, id_item, id_cliente
				) as fim


				left join (
				
					select  id_item item, ped_original, 
                    sum(valor) valor, sum(vlr_so_cancelado) vlr_so_cancelado, sum(vlr_so_faturado) vlr_so_faturado, sum(vlr_so_aberto) vlr_so_aberto, sum(vlr_prim) vlr_prim
					from (
						select id_item, ped_original, num_pedido, emissao, dt_prim, 
                        sum(valor) valor, sum(vlr_so_cancelado) vlr_so_cancelado, 
                        sum(vlr_so_faturado) vlr_so_faturado, sum(vlr_so_aberto) vlr_so_aberto,
						case when dt_prim = emissao then sum(valor) else 0 end as vlr_prim
						from (

							select id_item, pedido num_pedido, ped_original, dt_emissao emissao, valor,                            
								-- case when ped.ult_status in ('980','984') then valor else 0 end as vlr_so_cancelado,
                                case when ped.ult_status in ('902','904','912','914') then valor else 0 end as vlr_so_cancelado,
                                
								case when ped.ult_status like '6%' then valor else 0 end as vlr_so_faturado,
								case when (ped.ult_status like '5%') then valor else 0 end as vlr_so_aberto,
 								(select min(ped0.dt_emissao) from pedidos_jdes ped0 where ped0.ped_original = ped.ped_original) as dt_prim
								
								from pedidos_jde ped
								left join itens on itens.id = ped.id_item
								$where and ped.ult_status not in ('980','984')
														
                            
						) as fim
						group by id_item, ped_original, num_pedido, emissao, dt_prim
					) as fim2 group by id_item, ped_original
				
				) as final 
				on final.ped_original = vendas and final.item = fim.id_item
				
			) as final1
		group by vendas, ano, mes, id_rep, id_item, financeiro, id_cliente
		) as final
	) as final2
	group by ano, mes , vendas, id_cliente, id_item, secundario, codgrife
) as final3

left join (select id, fantasia, razao from addressbook ) as abc
on abc.id = id_cliente

order by codgrife, secundario desc


");


	

@endphp
<h6>

<div class="row">

	
	<div class="col-md-12">
		<div class="box box-body box-widget">
			<table class="table table-bordered"> 
		 <tr>	
	 		<td colspan="8">Detalhe do pedido {{$pedido}} do cliente {{$query_vendas[0]->cliente}} loja {{$query_vendas[0]->id_cliente}}</td>
				</tr>
		  <tr>	
	 		
	 		
			  <td colspan="1" align="center">Grife</td>
			<td colspan="1" align="left">Secundario</td>	
		 	<td colspan="1" align="center">venda_total</td>
		 	<td colspan="1" align="center">venda_aberto</td>
			<td colspan="1" align="center">venda_orcamento</td>
			<td colspan="1" align="center">venda_pedido</td>
			  
			<td colspan="1" align="center">vlr_pedido</td>
		 	<td colspan="1" align="center">primeiro_ped</td>
			<td colspan="1" align="center">atendimentos</td>
			  
		 	<td colspan="1" align="center">vlr_so_aberto</td>
			<td colspan="1" align="center">vlr_so_faturado</td>
			  
		 	
		
	
	
									
			
@foreach ($query_vendas as $query1)
	<tr>

<td align="center" class="text-red"><a href="/comercial_det_ped?pedido={{$query1->vendas}}">
{{$query1->codgrife}}</a></td>

<td align="left">{{$query1->secundario}}</td>
		
<td align="center" class="text-red">{{number_format($query1->venda_total,2)}}</td>											

<td align="center" class="text-red">{{number_format($query1->venda_aberto,2)}}</td>										
<td align="center" class="text-red">{{number_format($query1->venda_orcamento, 2, ',', '.')}}</td>
		
<td align="center" class="text-red">{{number_format($query1->venda_pedido, 2, ',', '.').' / '.number_format(($query1->venda_pedido/$query1->venda_total)*100,2).'%'}}</td>			

<td align="center" class="text-blue">{{number_format($query1->vlr_pedido,2)}}</td>										
<td align="center" class="text-blue">{{number_format($query1->primeiro_ped, 2, ',', '.').' / '.number_format(($query1->primeiro_ped/$query1->venda_total)*100,2).'%'}}</td>					
<td align="center" class="text-blue">{{number_format($query1->atendimentos, 2, ',', '.')}}</td>


<td align="center" class="text-green">{{number_format($query1->vlr_so_aberto, 2, ',', '.')}}</td>					
<td align="center" class="text-green">{{number_format($query1->vlr_so_faturado, 2, ',', '.').' / '.number_format(($query1->vlr_so_faturado/$query1->venda_total)*100,2).'%'}}</td>


					
</tr>
@endforeach 
				
			</table>
			<i><a href="">+</a></i>
		</div>
		
	</div>



	

	

</div>
</form>
</h6>
@stop