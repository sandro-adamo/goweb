@extends('layout.principal')
@section('conteudo')


@php

$representantes = Session::get('representantes');

 $colecao = $_GET["colecao"];
 $agrup = $_GET["agrup"];


if($representantes==101815) 
	{$grifes="( 'AM', 'BC', 'BV', 'CT', 'SM', 'MC', 'CH', 'DU', 'AA', 'AZ', 'CL')";} 
		else {
			$grifes = Session::get('grifes'); }


$query_1 = \DB::select(" 
select distinct fornecedor, secundario, colmod, itens_disp, orca, disp, cet, etq, prod, etq_total_vendas, pre_compras, most, reservas_estrat, manut , atual,  ultimo, penultimo,  antipenultimo
from (

	select fornecedor, secundario, anomod, case when colmod < year(now()) then concat(anomod, ' TOTAL') when colmod is null then concat(anomod, ' TOTAL')  else colmod end as colmod, 
	sum(itens_disp) itens_disp, sum(orca) orca, sum(disp) disp, sum(cet) cet, sum(etq) etq, sum(prod) prod, sum(etq_total_vendas) etq_total_vendas, 
	sum(compras) pre_compras, sum(most) most, sum(reservas_estrat) reservas_estrat, sum(manut) manut ,sum(atual) atual, sum(ultimo) ultimo, 
	sum(penultimo) penultimo, sum(antipenultimo) antipenultimo
	from (

		select substring(fornecedor,10,10) fornecedor, secundario, case when sint.colmod = ' <= 2016' then left(sint.colmod,8) else left(sint.colmod,4) end as anomod, 
		case when sint.colmod < year(now()) then left(sint.colmod,4) else sint.colmod end as colmod, sint.genero,
		sum(orcamento) orca, sum(disponivel) disp, sum(cet) cet, sum(etq) etq, sum(cep) prod, sum(etq_total_vendas)etq_total_vendas, sum(compras) compras, 
		sum(mostruarios) most, sum(reservas_estrat) reservas_estrat, sum(manutencao) manut
		,sum(atual) atual,sum(ultimo) ultimo, sum(penultimo) penultimo, sum(antipenultimo) antipenultimo, sum(itens_disp) itens_disp

		from go_storage.sintetico_estoque sint left join itens on itens.id = sint.id_item where sint.agrup = '$agrup'
		group by sint.colmod, sint.genero, substring(fornecedor,10,10), secundario
	) as fim where colmod = '$colecao' 
	group by fornecedor, anomod, colmod, secundario
) as fim1 order by fornecedor, secundario
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

	 		<td colspan="12">Estoque Colecao</td>
			 <td colspan="4">Vendas</td>
		
				</tr>
		  			
					<tr>	
						
					<td colspan="1" align="center">Colmod</td>
						<td></td>
					<td colspan="1" align="center">Itens_disp</td>
					<td colspan="1" align="center">BO</td>
					<td colspan="1" align="center">Disp_vda</td>
					<td colspan="1" align="center">CET</td>
					<td colspan="1" align="center">ETQ</td>
					<td colspan="1" align="center">CEP</td>
					<td colspan="1" align="center">Etq Vda</td>
					<td colspan="1" align="center">Pre compra</td>
					<td colspan="1" align="center">Most</td>
					<td colspan="1" align="center">Reservas</td>
					<td colspan="1" align="center">Manut</td>
						
					<td colspan="1" align="center">atual</td>
					<td colspan="1" align="center">ultimo</td>
					<td colspan="1" align="center">penultimo</td>
					<td colspan="1" align="center">antipenultimo</td>
					
				
					</tr>
			    </thead>
			  
		  
		   
			@foreach ($query_1 as $query1)
		   
		   
			  
				<tr>
				<td align="left"><a href="/dsetq_colecao?colecao={{$query1->colmod}}">{{$query1->secundario}}</a></td>
				<td align="center">{{$query1->fornecedor}}</td>
				<td align="center">{{number_format($query1->itens_disp)}}</td>
				<td align="center">{{number_format($query1->orca)}}</td>
				<td align="center">{{number_format($query1->disp)}}</td>
				<td align="center">{{number_format($query1->cet)}}</td>
				<td align="center">{{number_format($query1->etq)}}</td>
				<td align="center"><a href="/dkdet_orcagrup?agrup={{$query1->colmod}}">{{number_format($query1->prod)}}</a></td>
				<td align="center">{{number_format($query1->etq_total_vendas)}}</td>	
				<td align="center">{{number_format($query1->pre_compras)}}</td>	
				<td align="center">{{number_format($query1->most)}}</td>	
				<td align="center">{{number_format($query1->reservas_estrat)}}</td>	
				<td align="center">{{number_format($query1->manut)}}</td>
				
				<td align="center">{{number_format($query1->atual)}}</td>	
				<td align="center">{{number_format($query1->ultimo)}}</td>	
				<td align="center">{{number_format($query1->penultimo)}}</td>	
				<td align="center">{{number_format($query1->antipenultimo)}}</td>		
				</tr>
			@endforeach 
			
		
		   
			</table>
			
		</div>

				
	</div>	
</div>
</h6>			
	
</form>

@stop