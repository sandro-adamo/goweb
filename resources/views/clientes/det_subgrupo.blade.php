@extends('layout.principal')

@section('title')
<i class="fa fa-suitcase"></i> Dashboard Comercial
@append 

@section('conteudo')

<form action="" method="get"> 
@php

 $representantes = Session::get('representantes');
 $grifes = Session::get('grifes');

	
$pdv = $_GET["pdv"];
	
 
if (isset($_GET["pdv1"])) 
	{ $wherex = 'where id = $pdv'; } 
	else {
	$wherex = 'where cliente = $_GET["pdv1"]'; }



  $where = ' where id_rep in ('.$representantes.') ';
  $where2 = ' where rep_comissao in ('.$representantes.') ';
	

	
	
$query_cli = \DB::select("
		select cliente from addressbook where cod_cliente = '$pdv'
");
	
	
$cli = $query_cli[0]->cliente;
	
	
echo $cli;	
	
	

	
$query_vendas = \DB::select("
	
	select ano, rep_cart, sum(qtde) qtde , sum(valor) valor from (

	select year(dt_venda) ano, (qtde) qtde , (valor) valor 
	, (select rep from carteira cart where cart.status = 1 and cart.grife = cod_grife and cart.cli = id_cliente order by dt_fim desc, dt_inicio desc limit 1 ) as rep_cart

		from vendas_jde vds
		left join addressbook ab on ab.id = vds.id_cliente
		where prox_status not in ('980','984')
		and cod_cliente = '$pdv'
	) as final
where rep_cart in ($representantes)
group by ano, rep_cart
");

	
$query_grifes = \DB::select("
select cod_grife, rep_cart, sum(qtde) qtde , sum(valor) valor , sum(qtde_18) qtde_18,  sum(qtde_19) qtde_19, sum(qtde_20) qtde_20, sum(qtde_21) qtde_21
from (

    select cod_grife, qtde , valor,
    case when year(dt_venda) = 2018 then qtde else 0 end as qtde_18,
    case when year(dt_venda) = 2019 then qtde else 0 end as qtde_19,
    case when year(dt_venda) = 2020 then qtde else 0 end as qtde_20,
    case when year(dt_venda) = 2021 then qtde else 0 end as qtde_21,
    (select rep from carteira cart where cart.status = 1 and cart.grife = cod_grife and cart.cli = id_cliente order by dt_fim desc, dt_inicio desc limit 1 ) as rep_cart
    
	from vendas_jde vds
	left join addressbook ab on ab.id = vds.id_cliente
	where prox_status not in ('980','984')
	and cod_cliente = '$pdv'
) as fim
	where rep_cart in ($representantes)  
group by cod_grife, rep_cart
");


$query_lojas = \DB::select("
		select * from addressbook where cod_cliente = '$pdv'
");
	

	
$query_vendas1 = \DB::select("

-- identifica primeiro pedido e orcamentos   
select final3.*, concat(id_cliente,' - ',fantasia) cliente from (
	select ano, mes, vendas,id_cliente,  -- id_rep, codgrife, financeiro,  
			sum(venda_total) venda_total, sum(venda_aberto) venda_aberto, sum(venda_pedido)-sum(vlr_so_cancelado) venda_pedido, 
            sum(venda_orcamento)+sum(vlr_so_cancelado) venda_orcamento,
            sum(vlr_pedido)-sum(vlr_so_cancelado) vlr_pedido,
            case when sum(atendimentos) < sum(vlr_so_cancelado) then sum(primeiro_ped) - sum(vlr_so_cancelado) else sum(primeiro_ped) end as primeiro_ped, 
            case when sum(atendimentos) >= sum(vlr_so_cancelado) then sum(atendimentos) - sum(vlr_so_cancelado) else sum(atendimentos) end as atendimentos,
            
            -- so
            sum(vlr_so_cancelado) vlr_so_cancelado, sum(vlr_so_aberto) vlr_so_aberto, sum(vlr_so_faturado) vlr_so_faturado
		
	from (
    
		select * from (
			select vendas, ano, mes, id_rep, codgrife, financeiro, id_cliente,
			sum(vlr_total) venda_total, sum(vlr_total)-sum(vlr_venda_ped)-sum(vlr_venda_orcamento) venda_aberto, sum(vlr_venda_ped) venda_pedido, sum(vlr_venda_orcamento) venda_orcamento, 
			ifnull(sum(valor),0) vlr_pedido, ifnull(sum(vlr_prim),0) primeiro_ped, ifnull(sum(valor)-sum(vlr_prim),0) atendimentos,
            -- so
            ifnull(sum(vlr_so_cancelado),0) vlr_so_cancelado, ifnull(sum(vlr_so_faturado),0) vlr_so_faturado, ifnull(sum(vlr_so_aberto),0) vlr_so_aberto
			from (
			 
				select * from (        
						select ano, mes, codgrife, id_rep, vendas, financeiro, id_cliente,
						
						sum(valor) vlr_total, sum(vlr_venda_aberto) vlr_venda_aberto, sum(vlr_venda_ped) vlr_venda_ped, sum(vlr_venda_orcamento) vlr_venda_orcamento, sum(vlr_venda_canc) vlr_venda_canc
						from (
							
							select year(dt_venda) ano, month(dt_venda) mes, codgrife, id_rep, pedido vendas, valor, financeiro, id_cliente,
								
                                case when concat(vds.ult_status,'/',vds.prox_status) in ('500/505','505/510','510/512') then vds.valor else 0 end as vlr_venda_aberto,		  
								case when concat(vds.ult_status,'/',vds.prox_status) in ('510/999','512/999','515/999','516/999') then vds.valor else 0 end as vlr_venda_ped,
								case when vds.prox_status in ('515','516') then vds.valor else 0 end as vlr_venda_orcamento,
								case when vds.ult_status in ('980','984') then vds.valor else 0 end as vlr_venda_canc,
                                (select rep from carteira cart where cart.status = 1 and cart.grife = codgrife and cart.cli = id_cliente order by dt_fim desc, dt_inicio desc limit 1 ) as rep_cart
								
							from vendas_jdes vds
							left join addressbook ab on ab.id = vds.id_cliente
							where  vds.ult_status not in ('980','984') 
							and cod_cliente = '$pdv'
							
						) as fim0
                        where rep_cart in ($representantes)

						group by ano, mes, id_rep, vendas, financeiro, codgrife, id_cliente
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
								where ped.ult_status not in ('980','984')
														
                            
						) as fim
						group by codgrife, ped_original, num_pedido, emissao, dt_prim
					) as fim2 group by grife, ped_original
				
				) as final 
				on final.ped_original = vendas and final.grife = fim.codgrife
				
			) as final1
		group by vendas, ano, mes, id_rep, codgrife, financeiro, id_cliente
		) as final
	) as final2
	group by ano, mes , vendas, id_cliente
) as final3

left join (select id, fantasia, razao from addressbook ) as abc
on abc.id = id_cliente

order by vendas desc

");
	

@endphp
<h6>

	
	
	
	
	
	
<div class="row">

	
	<div class="col-md-3">
		<div class="box box-body box-widget">
			<table class="table table-bordered"> 
		 <tr>	
	 		<td colspan="8">VENDAS (filtro de datas)</td>
				</tr>
		  <tr>	
	 		
	 		<td colspan="1" align="center">ano</td>	
			<td colspan="1" align="center">qtde</td>	
		 	<td colspan="1" align="center">valor</td>
							
			
@foreach ($query_vendas as $query1)
	<tr>

		
<td align="center" class="text-red">{{$query1->ano}}</td>			
<td align="center" class="text-red">{{number_format($query1->qtde, 0, ',', '.')}}</td>								
<td align="center" class="text-red">{{number_format($query1->valor, 2, ',', '.')}}</td>

</tr>
@endforeach 
				
			</table>
			<i><a href="">+</a></i>
		</div>
		
	</div>


	
	
		<div class="col-md-6">
		<div class="box box-body box-widget">
			<table class="table table-bordered"> 
		 <tr>	
	 		<td colspan="8">GRIFES (filtro de datas)</td>
				</tr>
		  <tr>	
	 		
	 		<td colspan="1" align="center">GRIFES</td>
			<td colspan="1" align="center">VLR TOTAL</td>
		 	<td colspan="1" align="center">QTDE TOTAL</td>
		 	
			  <td colspan="1" align="center">QTDE 18</td>
			  <td colspan="1" align="center">QTDE 19</td>
			  <td colspan="1" align="center">QTDE 20</td>
			  <td colspan="1" align="center">QTDE 21</td>
		 	
	
		
			
			@foreach ($query_grifes as $query3)
				<tr>
				<td align="center">{{$query3->cod_grife}}</td>
				<td align="center" class="text-red">{{number_format($query3->valor, 2, ',', '.')}}</td>
				<td align="center" class="text-red">{{number_format($query3->qtde, 0, ',', '.')}}</td>			
		
<td align="center" class="text-red">{{number_format($query3->qtde_18, 0, ',', '.')}}</td>			
<td align="center" class="text-red">{{number_format($query3->qtde_19, 0, ',', '.')}}</td>			
<td align="center" class="text-red">{{number_format($query3->qtde_20, 0, ',', '.')}}</td>			
<td align="center" class="text-red">{{number_format($query3->qtde_21, 0, ',', '.')}}</td>			

					
				
				

				</tr>
			@endforeach 
			</table>
			<i><a href="">+</a></i>
		</div>
		
	</div>

	
	
	
	
	<div class="col-md-6">
		<div class="box box-body box-widget">
			<table class="table table-bordered"> 
		 <tr>	
	 		<td colspan="8">lojas</td>
				</tr>
		  <tr>	
	 		
	 		<td colspan="1" align="center">CODCLI</td>	
		 	<td colspan="1" align="center">Fantasia</td>
		 	<td colspan="1" align="center">Razao Social</td>
			<td colspan="1" align="center">UF</td>
			<td colspan="1" align="center">Munucipio</td>
			<td colspan="1" align="center">Endereco</td>
			<td colspan="1" align="center">CNPJ</td>
		 	<td colspan="1" align="center">Financeiro</td>
	
		
			
			@foreach ($query_lojas as $query2)
				<tr>
					<td align="center">{{$query2->id}}</td>
				
					<td align="center">{{$query2->fantasia}}</td>
					<td align="center">{{$query2->razao}}</td>
					<td align="center">{{$query2->uf}}</td>
					<td align="center">{{$query2->municipio}}</td>
					<td align="center">{{$query2->endereco}}</td>
					<td align="center">{{$query2->cnpj}}</td>
					<td align="center"><a href="">{{$query2->financeiro}}</a></td>
					

				</tr>
			@endforeach 
			</table>
			<i><a href="">+</a></i>
		</div>
		
	</div>
	


	
		
	<div class="col-md-12">
		<div class="box box-body box-widget">
			<table class="table table-bordered"> 
		 <tr>	
	 		<td colspan="8">VENDAS (filtro de datas)</td>
				</tr>
		  <tr>	
	 		
	 		<td colspan="1" align="center">Pre-venda</td>	
			<td colspan="1" align="left">Cliente</td>
			<td colspan="1" align="left">ano mes</td>	
		 	<td colspan="1" align="center">venda_total</td>
		 	<td colspan="1" align="center">venda_aberto</td>
			<td colspan="1" align="center">venda_orcamento</td>
			<td colspan="1" align="center">venda_pedido</td>
			  
			<td colspan="1" align="center">vlr_pedido</td>
		 	<td colspan="1" align="center">primeiro_ped</td>
			<td colspan="1" align="center">atendimentos</td>
			  
		 	<td colspan="1" align="center">vlr_so_aberto</td>
			<td colspan="1" align="center">vlr_so_faturado</td>
			  
		 	<td colspan="1" align="center">Dupl Paga</td>
			<td colspan="1" align="center">Comissao</td>
	
	
									
			
@foreach ($query_vendas1 as $query1)
	<tr>

<td align="center" class="text-red"><a href="/comercial_det_ped?pedido={{$query1->vendas}}">
{{$query1->vendas}}</a></td>
<td align="left">{{$query1->ano}} / {{$query1->mes}}</td>
<td align="left">{{$query1->cliente}}</td>
		
<td align="center" class="text-red">{{number_format($query1->venda_total,2)}}</td>											

<td align="center" class="text-red">{{number_format($query1->venda_aberto,2)}}</td>										
<td align="center" class="text-red">{{number_format($query1->venda_orcamento, 2, ',', '.')}}</td>
		
<td align="center" class="text-red">{{number_format($query1->venda_pedido, 2, ',', '.').' / '.number_format(($query1->venda_pedido/$query1->venda_total)*100,2).'%'}}</td>			

<td align="center" class="text-blue">{{number_format($query1->vlr_pedido,2)}}</td>										
<td align="center" class="text-blue">{{number_format($query1->primeiro_ped, 2, ',', '.').' / '.number_format(($query1->primeiro_ped/$query1->venda_total)*100,2).'%'}}</td>					
<td align="center" class="text-blue">{{number_format($query1->atendimentos, 2, ',', '.')}}</td>


<td align="center" class="text-green">{{number_format($query1->vlr_so_aberto, 2, ',', '.')}}</td>					
<td align="center" class="text-green">{{number_format($query1->vlr_so_faturado, 2, ',', '.').' / '.number_format(($query1->vlr_so_faturado/$query1->venda_total)*100,2).'%'}}</td>

<td></td>
<td></td>
					
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