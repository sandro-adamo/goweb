@extends('layout.principal')

@section('title')
<i class="fa fa-suitcase"></i> Dashboard Comercial
@append 

@section('conteudo')

<form action="" method="get"> 
@php


	
 $representantes = Session::get('representantes');
 $grifes = Session::get('grifes');

	
if(isset($_GET["status"])){
    $where = "where id_rep in ($representantes) and financeiro = 'in'";
} else {
	$where = "where id_rep in ($representantes) ";
	};
	
	
	
	
	

  $where2 = ' where rep_comissao in ('.$representantes.') ';

 //echo $where; 
	

$querya = \DB::select("
select *, fantasia, representante, status_cliente from (
			select pedido, dt_venda,  id_rep, id_cliente, sum(valor) valor, sum(qtde) qtde
			from vendas_jdes vds
			left join addressbook ab on ab.id = vds.id_cliente
			$where and vds.ult_status not in ('980','984') 
			and vds.prox_status in ('515','516') 
            group by pedido, dt_venda,  id_rep, id_cliente
		) as base



left join (select id, case when nome ='' then fantasia else nome end as representante from addressbook ) as ar on ar.id = base.id_rep
left join (select id, fantasia,case when financeiro in ('in','ju') then 'inadimplente'  when financeiro in ('ac') then 'acordo' else '' end as status_cliente  from addressbook ) as ac on ac.id = base.id_cliente
");


	
	


$query_faturas = \DB::select("
		select * from itens where modelo = 'ah1020'
");

	

@endphp
<h6>

<div class="row">

	
	
	<div class="col-md-9">
		<div class="box box-body box-widget">
			<table class="table table-bordered"> 
		 <tr>	
	 		<td colspan="8">VENDAS (filtro de datas)</td>
				</tr>
		  <tr>	
	 		
	 		<td colspan="1" align="center">id_rep</td>		 	
		 	<td colspan="1" align="center">representante</td>
		 	<td colspan="1" align="center">pedido</td>
			<td colspan="1" align="center">dt_venda</td>
			<td colspan="1" align="center">fantasia</td>			  
			<td colspan="1" align="center" class="text-red">status_cliente</td>
			  <td colspan="1" align="center" >qtde</td>
			  <td colspan="1" align="center" >valor</td>
			  
			  
		 	

									
			
@foreach ($querya as $query1)
	<tr>

<td align="left">
{{$query1->id_rep}}</td>
<td align="left">{{$query1->representante}}</td>
<td align="left"><a href="">{{$query1->pedido}}</a></td>
<td align="left">{{$query1->dt_venda}}</td>
<td align="left">{{$query1->fantasia}}</td>
<td align="left" class="text-red">{{$query1->status_cliente}}</td>
											
<td align="center">{{number_format($query1->qtde, 2, ',', '.')}}</td>												
<td align="center">{{number_format($query1->valor, 2, ',', '.')}}</td>	

</tr>
@endforeach 
				
			</table>
			<i><a href="">+</a></i>
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