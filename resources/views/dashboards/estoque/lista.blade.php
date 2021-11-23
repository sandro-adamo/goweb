@extends('layout.principal')
@section('conteudo')

@php

$representantes = Session::get('representantes');


if($representantes==101815) 
	{$grifes="( 'AM', 'BC', 'BV', 'CT', 'SM', 'MC', 'CH', 'DU', 'AA', 'AZ', 'CL')";} 
		else {
			$grifes = Session::get('grifes'); }

echo $grifes;

$query_1 = \DB::select(" 

select colmod, sum(compras) compras, sum(qtde_recebido) qtde_recebido, sum(qtde_transito) qtde_transito, sum(total_embarcado) total_embarcado, sum(falta_embarcar) falta_embarcar,
	sum(disponivel) disponivel, sum(orcamentos) orcamentos, 
	sum(vendas_0a30DD) vendas_0a30DD, sum(vendas_0a60DD) vendas_0a60DD, sum(vendas_total) vendas_total,
	sum(mostruarios) mostruarios, sum(aberto_kering) aberto_kering, sum(alocado_kering) alocado_kering, sum(ajuste_go) ajuste_go
from (

 	select case when left(colmod,4) < year(now()) then left(colmod,4) 
    when right(colmod,2) in ('01','02','03','04','05','06') then concat(left(colmod,4), '  SS') else concat(left(colmod,4), ' FW') 
     end as colmod, 
    
    sum(compras) compras, sum(qtde_recebido) qtde_recebido, sum(qtde_transito) qtde_transito, sum(total_embarcado) total_embarcado, sum(falta_embarcar) falta_embarcar,
	sum(disponivel) disponivel, sum(orcamentos) orcamentos, 
	sum(vendas_0a30DD) vendas_0a30DD, sum(vendas_0a60DD) vendas_0a60DD, sum(vendas_total) vendas_total,
	sum(mostruarios) mostruarios, sum(aberto) aberto_kering, sum(alocado) alocado_kering, sum(ajuste_go) ajuste_go
	
	from go_storage.ds_kering 
    where codgrife in $grifes
	
	group by colmod
) as fim group by colmod order by left(colmod,4) desc, right(colmod,2) asc
");


		
$query_2 = \DB::select(" 

	select grife, agrup, sum(itens_disp) itens_disp,
	sum(orca) orca, sum(disp) disp, sum(cet) cet, sum(etq) etq, sum(prod) prod, sum(etq_total_vendas) etq_total_vendas, sum(compras) pre_compras, 
	sum(most) most, sum(reservas_estrat) reservas_estrat, sum(manut) manut
	,sum(atual) atual, sum(ultimo) ultimo, sum(penultimo) penultimo, sum(antipenultimo) antipenultimo
	from (
    
		select sint.grife, case when sint.tecnologia = 'CLIP ON' then concat(left(sint.agrup,4),' - CLIP ON') else sint.agrup end as agrup, itens.genero,
        sum(orcamento) orca, sum(disponivel) disp, sum(cet) cet, sum(etq) etq, sum(cep) prod, sum(etq_total_vendas)etq_total_vendas, sum(compras) compras, 
        sum(mostruarios) most, sum(reservas_estrat) reservas_estrat, sum(manutencao) manut
        ,sum(atual) atual,sum(ultimo) ultimo, sum(penultimo) penultimo, sum(antipenultimo) antipenultimo, sum(itens_disp) itens_disp

		from go_storage.sintetico_estoque sint
		left join itens on itens.id = sint.id_item
		
        where codtipoarmaz not in ('o')
        and secundario not like '%semi%'  
		and itens.clasmod in ('LINHA A++','LINHA A+','LINHA A','NOVO') 

		group by sint.grife, sint.agrup, itens.genero, sint.tecnologia
) as fim 
group by grife, agrup
with rollup 

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

	 		<td colspan="12">Compras Kering</td>
		
				</tr>
		  			
					<tr>	
						
					<td colspan="1" align="center">Colecao</td>				
					<td colspan="1" align="center">Compras</td>
					<td colspan="1" align="center">Recebido</td>
					<td colspan="1" align="center">Transito</td>
					<td colspan="1" align="center">Embarcado</td>
					<td colspan="1" align="center">Falta embarcar</td>
					<td colspan="1" align="center">Disponivel</td>
					<td colspan="1" align="center">Orcamentos</td>
					<td colspan="1" align="center">Vds 0a30</td>
					<td colspan="1" align="center">aberto K</td>
					<td colspan="1" align="center">alocado K</td>
					<td colspan="1" align="center">ajuste go</td>
					
				
					</tr>
			    </thead>
			  
		  
		   
			@foreach ($query_1 as $query1)
		   
		   
			  
				<tr>
				<td align="left"><a href="/dkdet_agrup?agrup={{$query1->colmod}}">{{$query1->colmod}}</a></td>
				<td align="center"><a href="/dkdet_comprasagrup?agrup={{$query1->colmod}}">{{number_format($query1->compras)}}</a></td>
				<td align="center">{{number_format($query1->qtde_recebido)}}</td>
				<td align="center">{{number_format($query1->qtde_transito)}}</td>
				<td align="center">{{number_format($query1->total_embarcado)}}</td>
				<td align="center">{{number_format($query1->falta_embarcar)}}</td>
				<td align="center">{{number_format($query1->disponivel)}}</td>
				<td align="center"><a href="/dkdet_orcagrup?agrup={{$query1->colmod}}">{{number_format($query1->orcamentos)}}</a></td>
				<td align="center">{{number_format($query1->vendas_0a30DD)}}</td>	
				<td align="center">{{number_format($query1->aberto_kering)}}</td>	
				<td align="center">{{number_format($query1->alocado_kering)}}</td>	
				<td align="center">{{number_format($query1->ajuste_go)}}</td>	
				</tr>
			@endforeach 
			
		
		   
			</table>
			
		</div>

	

		<div class="box box-body">	
	   <table class="table table-striped table-bordered compact" id="myTable">
		  <thead>	
			
		 <tr>	

	 		<td colspan="12">Estoques GO</td>
		
				</tr>
		  			
					<tr>	
						
					<td colspan="1" align="center">Grife</td>				
					<td colspan="1" align="center">Agrup</td>
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
					
				
					</tr>
			    </thead>
			  
		  
		   
			@foreach ($query_2 as $query2)
		   
		   
			  
				<tr>
				<td align="left"><a href="/dkdet_agrup?grife={{$query2->grife}}">{{$query2->grife}}</a></td>
				<td align="center"><a href="/dkdet_comprasagrup?agrup={{$query2->agrup}}">{{number_format($query2->compras)}}</a></td>
				<td align="center">{{number_format($query2->itens_disp)}}</td>
				<td align="center">{{number_format($query2->orca)}}</td>
				<td align="center">{{number_format($query2->disp)}}</td>
				<td align="center">{{number_format($query2->cet)}}</td>
				<td align="center">{{number_format($query2->etq)}}</td>
				<td align="center"><a href="/dkdet_orcagrup?agrup={{$query2->agrup}}">{{number_format($query2->prod)}}</a></td>
				<td align="center">{{number_format($query2->etq_total_vendas)}}</td>	
				<td align="center">{{number_format($query2->pre_compras)}}</td>	
				<td align="center">{{number_format($query2->most)}}</td>	
				<td align="center">{{number_format($query2->reservas_estrat)}}</td>	
				<td align="center">{{number_format($query2->manut)}}</td>	
				</tr>
			@endforeach 
			
		
		   
			</table>
			<td></td> 
		</div>
		
			
	</div>	
</div>
</h6>			
	
</form>

@stop