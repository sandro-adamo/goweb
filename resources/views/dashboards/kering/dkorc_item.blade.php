@extends('layout.principal')
@section('conteudo')

@php

$representantes = Session::get('representantes');

 $item = $_GET["item"];


if($representantes==101815) 
	{$grifes="( 'AM', 'BC', 'BV', 'CT', 'SM', 'MC', 'CH', 'DU', 'AA', 'AZ', 'CL')";} 
		else {
			$grifes = Session::get('grifes'); }

echo $item;


$query_2 = \DB::select(" 

                select abc.id codcli, cliente, fantasia, uf, municipio, financeiro, cod_risco, min(dt_venda) min_venda, max(dt_venda) max_venda, sum(qtde) qtde
                
				from vendas_jde vds
				left join itens on itens.id = vds.id_item and codtipoitem = 006
                left join addressbook abc on abc.id = id_cliente
				where ult_status not in ('980','984') and prox_status <= 515
				and secundario in ('$item')
				group by abc.id, cliente, fantasia, uf, municipio, financeiro, cod_risco

");
			  
			
@endphp

<form action="" method="get"> 

<h6>

	
							
<div class="row"> 
	
		<div class="col-md-11">	
		<div class="box box-body">
			
	   <table class="table table-striped table-bordered compact" id="myTable">
		  <thead>	
			
		 <tr>	

	 		<td colspan="11">Detalhe Agrupamento {{$item}}</td>
		
				</tr>
		  			
					<tr>	
					<td colspan="1" align="center">codcli</td>	
					<td colspan="1" align="center">cliente</td>					
					<td colspan="1" align="center">fantasia</td>
					<td colspan="1" align="center">uf</td>
					<td colspan="1" align="center">municipio</td>				
					<td colspan="1" align="center">financeiro</td>				
					<td colspan="1" align="center">cod_risco</td>				
					<td colspan="1" align="center">min_data</td>				
					<td colspan="1" align="center">max_data</td>				
					<td colspan="1" align="center">qtde</td>				
					</tr>
			  
			    </thead>
			  
				@foreach ($query_2 as $query2)
			  
				<tr>
					<td align="left"><a href="/dkdet_item?item={{$query2->codcli}}">{{$query2->codcli}}</a></td>
					<td align="left"><a href="/dkdet_item?item={{$query2->codcli}}">{{$query2->cliente}}</a></td>
					<td align="left">{{$query2->fantasia}}</td>
					<td align="center">{{$query2->uf}}</td>
					<td align="center">{{$query2->municipio}}</td>
					<td align="center">{{$query2->financeiro}}</td>
					<td align="center">{{$query2->cod_risco}}</td>
					<td align="center">{{$query2->min_venda}}</td>
					<td align="center">{{$query2->max_venda}}</td>
					<td align="center">{{$query2->qtde}}</td>					
				</tr>
			@endforeach 
			
			</table>
			
		</div>
	</div>	
</div>
</h6>			
	
</form>

@stop