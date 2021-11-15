@extends('layout.principal')


@php
$representantes = Session::get('representantes');
$grifes = Session::get('grifes');
$rep = $_GET["rep"];	

  $where = ' where id_rep in ('.$representantes.') ';
  $where2 = ' where rep_comissao in ('.$representantes.') ';	

	
@endphp


@section('title')
<i class="fa fa-suitcase"></i> Dashboard Comercial {{$rep}}
@append 

@section('conteudo')

<form action="" method="get"> 
@php

 
$query_1 = \DB::select("
select fim1.*  , nome_dir, nome_super, nome_rep, clientes, qtde_vda, qtde_mala, qtde_devolver, itens.a, itens.b, itens.c+itens.d prom, itens.itens
from (
	select * ,
    (select group_concat(distinct regiao,'' order by regiao) from carteira cart where cart.rep = rg.an8 and cart.grife = rg.grife and cart.status = 1 and dt_fim >= now() and regiao <> '' limit 1) regiao,
	(select count(id) from carteira cart where cart.rep = rg.an8 and cart.grife = rg.grife and cart.status = 1 and dt_fim >= now()) carteira,
	(select distinct codsuper from carteira cart where cart.rep = rg.an8 and cart.grife = rg.grife and cart.status = 1 and dt_fim >= now()) codsuper,
    (select distinct coddir from carteira cart where cart.rep = rg.an8 and cart.grife = rg.grife and cart.status = 1 and dt_fim >= now()) coddir

    from repXgrife rg
    
    where rg.an8 = '$rep' and rg.an8 in ($representantes)
	
) as fim1

left join (select id, case when nome = '' then fantasia else nome end as nome_rep from addressbook ) as abr
on abr.id = fim1.an8
    
left join (select id, case when nome = '' then fantasia else nome end as nome_dir from addressbook ) as abd
on abd.id = fim1.coddir

left join (select id, case when nome = '' then fantasia else nome end as nome_super from addressbook ) as abs
on abs.id = fim1.codsuper


left join (
	select id_rep, codgrife, count(id_cliente) clientes, sum(qtde_vda) qtde_vda from ( 
		select id_rep, id_cliente, codgrife, sum(qtde) qtde_vda 
		from vendas_jdes where id_rep = '$rep' and ult_status not in (980,984) and datediff(now(),dt_venda) <= 60
		group by id_rep, id_cliente, codgrife
	) as fim group by id_rep, codgrife
) as vds 
on vds.id_rep = fim1.an8 and vds.codgrife = fim1.grife



left join (
select id_rep, codgrife, sum(qtde) qtde_mala, sum(devolver) qtde_devolver from (
	select id_rep, itens.codgrife, ml.qtde, id_item, case when statusatual = 'esgotado' then qtde else 0 end as devolver  
	from malas ml left join itens on itens.id = ml.id_item 
	where ml.id_rep = $rep and ml.local = 'mala'
	) as mala group by id_rep, codgrife
) as most 
on most.id_rep = fim1.an8 and most.codgrife = fim1.grife



left join (
	select id_rep, codgrife, count(id_item) itens, sum(qtde_vda) qtde_vda1, sum(a) a, sum(b) b, sum(c) c, sum(d) d from ( 
		select id_rep, id_item, clasmod,  codgrife,
        case when clasmod = 'linha a-' then 1 else 0 end as b,
        case when clasmod in ('colecao b', 'promocional c') then 1 else 0 end as c,
        case when clasmod in ('linha a','linha a+','linha a++', 'novo') then 1 else 0 end as a,
        case when clasmod not in ('linha a','linha a+','linha a++', 'novo', 'colecao b', 'promocional c','linha a-') then 1 else 0 end as d,
        sum(qtde) qtde_vda 
		from vendas_jde vds 
        left join itens on itens.id = vds.id_item 
        where id_rep = '$rep' and ult_status not in (980,984) and datediff(now(),dt_venda) <= 60 
		group by id_rep, id_item, clasmod, codgrife
	) as fim group by id_rep, codgrife
) as itens
on itens.id_rep = fim1.an8 and itens.codgrife = fim1.grife



order by grife
");

	

@endphp
<h6>
	<div class="col-md-4">
<div class="box box-body box-widget">
<table class="table table-bordered"> 

		<ul class="nav nav-stacked">
		<li><a href="#">Repres<span class="pull-right badge bg-blue">{{$query_1[0]->nome_rep}}</span></a></li>
		<li><a href="#">Diretor<span class="pull-right badge bg-blue">{{$query_1[0]->nome_dir}}</span></a></li>
		<li><a href="#">Supervisor<span class="pull-right badge bg-blue">{{$query_1[0]->nome_super}}</span></a></li>
		<li><a href="#">Regiao<span class="pull-right badge bg-blue">{{$query_1[0]->regiao}}</span></a></li>
			
		</ul>	

</table>
</div>
		
				
</div>
			
			
			
<div class="row">

		<div class="col-md-10">
		<div class="box box-body box-widget">
			<table class="table table-bordered"> 
		 <tr>	
	 		<td colspan="6">Dados do representante </td>
			 <td colspan="7">Dados da Grife {{$rep}} </td>
				</tr>
		  <tr>	
	 		
				<td colspan="1" align="center">codrep</td>
				<td colspan="1" align="center">Vendas ult 60 dias</td>
				<td colspan="1" align="center">Pdvs_carteira</td>
				<td colspan="1" align="center">Pdvs ult 60 dias</td>
				<td colspan="1" align="center">Pcs Mala</td>
				<td colspan="1" align="center">repor mala</td>
				<td colspan="1" align="center">esgotado</td>
			<td colspan="1" align="center">skus vds ult 60dd</td>
			<td colspan="1" align="center">a+ / a</td>
			<td colspan="1" align="center">a-</td>
			<td colspan="1" align="center">b/c</td>

			
			@foreach ($query_1 as $query1)
				<tr>
					<td align="left"><a href="/rep_grife_det?rep={{$query1->an8}}">{{$query1->grife}}</a></td>
				
					
					
					<td align="center">{{number_format($query1->qtde_vda, 0, ',', '.')}}</td>
					<td align="center">{{number_format($query1->carteira, 0, ',', '.')}}</td>
					<td align="center">{{number_format($query1->clientes, 0, ',', '.')}}</td>
					<td align="center">{{number_format($query1->qtde_mala, 0, ',', '.')}}</td>
					<td></td>
					<td align="center" class="text-red">{{number_format($query1->qtde_devolver, 0, ',', '.')}}</td>
					<td align="center">{{number_format($query1->itens, 0, ',', '.')}}</td>
					<td align="center">{{number_format($query1->a, 0, ',', '.')}}</td>
					<td align="center">{{number_format($query1->b, 0, ',', '.')}}</td>
					<td align="center">{{number_format($query1->prom, 0, ',', '.')}}</td>

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