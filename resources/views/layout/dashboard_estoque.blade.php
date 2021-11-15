@extends('layout.principal')

@section('title')
<i class="fa fa-dashboard"></i> Dashboard Importação
@append 

@section('conteudo')

<form action="" method="get"> 
@php

	if (isset($_GET["dolar"])) {
		$sql_dolar = $_GET["dolar"];
		echo 'valor'.$sql_dolar;
	} 

	$query1 = \DB::select("select *  from (
select  num_invoice, num_oi, format(sum(qtde_ok),2) qtde_invoice, format(sum(atende),2) qtde_atende, format(sum(vlr_atende),2) vlr_atende
 from (

select  num_invoice, num_oi, sum(qtde_ok) qtde_ok, sum(atende) atende, sum(vlr_md*atende) vlr_atende from (

select grife, num_invoice,num_oi,  iditem, item_ok, qtde_ok, 
case when qtde_ok > orctt then orctt else qtde_ok end as atende, vlr_md from (

	select grife, item_ok, iditem, num_invoice, num_oi, vlr_md, ifnull(qtde_ok,0) qtde_ok, ifnull(orctt,0) orctt from (
		
        select itens.id iditem, grife, item_ok, num_invoice, num_oi, SUM(QTDE_SOL_OK) qtde_ok
		from importacoes_analitica
        left join itens on itens.secundario = importacoes_analitica. ITEM_OK
			where fornecedor like 'kering%' 
        
		group by itens.id, grife, item_ok, num_invoice, num_oi
	) as imp



	left join (
	select id_item, sum(qtd_aberto) orctt, sum(total) vlr_total , sum(total)/sum(qtd_aberto) vlr_md from orcamentos_anal group by id_item ) as orc
	on orc.id_item = imp.iditem

) as fim ) as fim2 group by grife, num_invoice, num_oi
) as fim3 group by num_invoice, num_oi
) as fim4

left join ( select num_oi numoi,  moeda_oi, vlr_total_oi, 
case when moeda_oi = 'usd' then format((vlr_total_oi*3.95) *0.445,2) when moeda_oi = 'eur' then format((vlr_total_oi*4.45) *0.445,2)  else format((4) *0.445,2) end as vlr_nacionalizacao_R$ 
from importacoes_sintetica ) as nac
on nac.numoi = fim4.num_oi

order by num_invoice");

@endphp

<div class="row">

	<div class="col-md-6">
		<div class="box box-body box-widget">
			<input type="text" name="dolar" placeholder="dolar">
			<input type="text" name="euro" placeholder="euro">
			<br>
			<input type="text" name="grife" placeholder="grife">
			<input type="text" name="item" placeholder="item">
			<input type="text" name="num_oi" placeholder="numero_oi">

			<button type="submit" name="pesquisar">Pesquisar</button>

			<table class="table table-bordered"> 
			@foreach ($query1 as $query)
				<tr>
					<td align="center">{{$query->num_invoice}}</td>
					<td align="center">{{$query->num_oi}}</td>
					<td align="center">{{$query->qtde_invoice}}</td>
					<td align="center">{{$query->qtde_atende}}</td>
					<td align="right">{{$query->vlr_atende}}</td>
				</tr>
			@endforeach 
			</table>
		</div>
	</div>

	<div class="col-md-3">
		<div class="box box-body box-widget">
		</div>
	</div>

	<div class="col-md-3">
		<div class="box box-body box-widget">
		</div>
	</div>
</div>
</form>

@stop