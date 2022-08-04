@extends('produtos/painel/index')
@php

$agrup = $_GET["agrup"];
$grife = 'ah';

if(isset($_GET["colecao"]))

{

	$colecao = $_GET["colecao"];
	$where1 = "where colecao = $colecao";

	$whereteste = "where agrup = '".$agrup."' and colecao = ".$colecao; 
} 

	elseif (isset($_GET["cores"]))
{
	$cores = $_GET["cores"];

	$where1 = "where cores = $cores" ; 

}

 else { 
	$where1 = "where 1=1" ; 

}
;

@endphp

@section('titulo') {{$agrup}} @append

@section('title')
  <i class="fa fa-list"></i> Produtos
@append 

@section('conteudo')


@php


$query = \DB::select("select * from itens where modelo = 'ah6254' ");
$data = '2021-01-01';





$modelos = \DB::select("

select ciclo, fornecedor, forn, codgrife, codgrife grife, agrup, modelo, colecao, colmod, clasmod, genero, estilo, idade, valortabela, mediacusto,
sum(disponivel) disponivel, sum(beneficiamento) beneficiamento, sum(cet) cet, sum(cep) cep, sum(most) most, sum(total) total,
sum(atual)atual, sum(ultimo)ultimo, sum(mes_sem)mes_sem, sum(mes_ano)mes_ano, sum(qtde_total) qtde_total,
sum(novos_atual) novos_atual, sum(novos_prox) novos_prox, sum(aa) aa, sum(a) a, 
sum(itens) itens, sum(imediata) imediata, sum(futura) futura, sum(producao) producao, 
sum(am3cores) am3cores, sum(b2cores) b2cores, sum(c1cor) c1cor, sum(d0cor) d0cor 
from (

	select ciclo, fornecedor, forn, codgrife, agrup, colecao, modelo, colmod, clasmod, genero, estilo, idade, valortabela,mediacusto,
	case when colecao = 'novo_prox' then 1 else 0 end as novos_prox,
	case when colecao = 'novo_atual' then 1 else 0 end as novos_atual,
    
	case when colecao = 'aa' then 1 else 0 end as aa, case when colecao = 'a' then 1 else 0 end as a, 
	sum(itens) itens, sum(imediata) imediata, sum(futura) futura, sum(producao) producao, sum(am3cores) am3cores, sum(b2cores) b2cores, sum(c1cor) c1cor, sum(d0cor) d0cor,
    sum(disponivel) disponivel, sum(beneficiamento) beneficiamento, sum(cet) cet, sum(cep) cep, sum(most) most, sum(total) total,
    sum(atual)atual, sum(ultimo)ultimo, sum(mes_sem)mes_sem, sum(mes_ano)mes_ano, sum(qtde_total) qtde_total

	from (
    
		select ciclo, fornecedor, forn, codgrife, agrup, modelo, clasmod, colmod, genero, estilo, idade, valortabela, mediacusto, 
        (itens) as itens, (imediata) imediata, (futura) futura, (producao) producao, 
        (disponivel) disponivel, (beneficiamento) beneficiamento, (cet) cet, (cep) cep, (most) most, (total) total,
        atual, ultimo, mes_sem, mes_ano, qtde_total,

		case when imediata+futura >= 3 then 1 else 0 end as am3cores,
		case when imediata+futura  = 2 then 1 else 0 end as b2cores,
		case when imediata+futura  = 1 then 1 else 0 end as c1cor,
		case when imediata+futura  = 0 then 1 else 0 end as d0cor,
		
        case when colecao = 'novo_prox' then 'novo_prox' 
		when colecao = 'novo_atual' then 'novo_atual' 
		when left(colecao,4) <> 'novo' and clasmod in ('LINHA A++','LINHA A+','LINHA A','NOVO') then 'aa'
		when left(colecao,4) <> 'novo' and clasmod in ('LINHA A-') then 'a' else '' end as colecao	
		
		from(


			select ciclo, fornecedor, forn, codgrife, agrup, modelo, clasmod, colmod, colecao, genero, estilo, idade, valortabela, mediacusto,
			count(secundario) as itens, sum(itens_imed) imediata, sum(itens_trans) futura, sum(itens_prod) producao,
            sum(disponivel) disponivel, sum(beneficiamento) beneficiamento, sum(cet) cet, sum(cep) cep, sum(most) most, sum(total) total,
            sum(atual)atual, sum(ultimo)ultimo, sum(mes_sem)mes_sem, sum(mes_ano)mes_ano, sum(qtde_total_jde) qtde_total
			from (

				select ciclo, left(fornecedor,10) forn, case when fornecedor like 'kering%' then 'kering' else 'go' end as fornecedor,
				codgrife, agrup, modelo, secundario, colmod,
                
                case when left(colmod,4) < year(now()) then 'lancado'
				when (left(colmod,4) = year(now()) and right(colmod,2) <= month(now())) then 'lancado' 
				when (left(colmod,4) = year(now()) and right(colmod,2) > month(now())) then 'novo_atual'
				else 'novo_prox' end as colecao,
                
				(select clasmod from itens iclas where left(agrup,5) = '$agrup'  and  iclas.id = id_item and clasmod  not in ('','.','colecao europra','cancelado') order by clasmod limit 1) clasmod,
                (select genero from itens iclas where left(agrup,5) = '$agrup'  and  iclas.id = id_item and genero  not in ('','.') order by genero limit 1) genero,
				(select estilo from itens iclas where left(agrup,5) = '$agrup'  and  iclas.id = id_item and estilo  not in ('','.') order by estilo limit 1) estilo,

				(select idade from itens iclas where left(agrup,5) = '$agrup'  and  iclas.id = id_item and idade  not in ('','.') order by idade limit 1) idade,
                
				(select valortabela from itens iclas where left(agrup,5) = '$agrup'  and  iclas.id = id_item and valortabela  not in ('','.') order by valortabela desc limit 1) valortabela,

				0 as  mediacusto,

				itens_imed, itens_trans, itens_prod, 
                (disponivel) disponivel, (conferencia+montagem) beneficiamento, (cet) cet, (etq+cep) cep, (mostruarios) most,
				(disponivel+conferencia+montagem+cet+etq+cep) total, 
                atual, ultimo, mes_sem, mes_ano, qtde_total_jde
				
				from go_storage.sintetico_estoque
				where secundario not like '%semi%' and (clasmod like 'linha%' or clasmod like 'novo%') 
				and codtipoarmaz not in ('o')
				and left(agrup,5) = '$agrup' 
                
			) as base group by ciclo, forn, fornecedor, codgrife, agrup, modelo, clasmod, colmod, colecao, genero, estilo, idade, valortabela, mediacusto
		) as base1 
	) as base2 $where1
    group by ciclo, fornecedor, forn, codgrife, agrup, colecao, modelo, colmod, clasmod, genero, estilo, idade, valortabela, mediacusto
) as base3 group by ciclo, fornecedor, forn, codgrife, agrup, modelo, colecao, colmod, clasmod, genero, estilo, idade, valortabela, mediacusto
order by modelo

");




		


/** query importacao **/

$importacoes = \DB::select(" 
select * from (	
    select pedido, tipo, sum(qtde_sol) qtde_sol from (
		select pedido, tipo, colecao, sum(qtde_sol) qtde_sol from (
		
			select pedido, tipo, qtde_sol, colmod, clasmod,
			
			case when left(colmod,4) < year(now()) then 'lancado'
			when (left(colmod,4) = year(now()) and right(colmod,2) <= month(now())) then 'lancado' 
            when (left(colmod,4) = year(now()) and right(colmod,2) > month(now())) then 'novo_atual'
				 

			
			when (left(colmod,4) < year(now()) and clasmod in ('LINHA A++','LINHA A+','LINHA A','NOVO')) then 'aa'
			when (left(colmod,4) < year(now()) and clasmod in ('LINHA A-')) then 'a'
			
			when ((left(colmod,4) = year(now()) and right(colmod,2) < month(now())) and clasmod in ('LINHA A++','LINHA A+','LINHA A','NOVO')) then 'aa'
			when ((left(colmod,4) = year(now()) and right(colmod,2) < month(now())) and clasmod in ('LINHA A-')) then 'a'
			else '' end as colecao		
            
			from (
                
					select pedido, tipo, qtde_sol, 
                    ifnull(itenspc.colmod,itenspt.colmod) colmod,
                    ifnull(itenspc.clasmod,itenspt.clasmod) clasmod

						from importacoes_pedidos ped
						left join itens_estrutura ie on ie.id_filho = ped.cod_item
						left join itens itenspc on itenspc.id = ped.cod_item
						left join itens itenspt on itenspt.id = ie.id_pai
						
						where  (left(itenspc.agrup,5) = '$agrup' or left(itenspt.agrup,5) = '$agrup') and 
						ref_go not in ('LA200501','QGKI17-7B') and 
						ult_status not in (980) and prox_status not in (999,400)
and (
						itenspc.codtipoitem = 006 
						or ((left(ped.secundario,3) = 'FR ' or left(ped.secundario,6) = 'PONTE ')) )
			) as base0 
		) as base1  $where1
        group by pedido, tipo, colecao
	) as base2 group by pedido, tipo
) as base3




	left join (
	select pedido, tipo, ref_go, concat(trim(ref_despachante),' ',trim(ref_nac_01)) ref, ult_prox, desc_status, group_concat(distinct left(fornecedor,20),' ') fornecedor,  
	group_concat(distinct tipoitem,' ') tipoitem, group_concat(distinct codgrife,' ') codgrife, group_concat(distinct linha,' ') linha,
	case when CHAR_LENGTH(group_concat(distinct colmod,' ')) > 26 then concat('...',right(group_concat(distinct colmod,' '),26)) else group_concat(distinct colmod,' ') end as colmod, 
	sum(qtde) qtde, sum(atende) atende, sum(itens_trans) itens_trans
	from (
    
	select *, case when orcamentos > qtde then qtde else orcamentos end as atende from (
    
		select pedido, tipo, ref_go, ref_despachante, ref_nac_01, ult_prox, desc_status, secundario, cod_item, codtipoitem, tipoitem, id_pai,
		item_pai, tipo_pai, agrupador, codgrife, colmod, fornecedor, linha,
		ifnull((select sum(orcamento_bloq+orcamento_liber) from go_storage.sintetico_estoque sint where sint.id_item = final.id_pai),0) orcamentos,
        ifnull((select sum(itens_trans) from go_storage.sintetico_estoque sint where sint.id_item = final.id_pai),0) itens_trans,
				/**	ifnull((select sum(qtde) qtde_aberto from go.vendas_jde vds
					where ult_status not in ('980') and tipo_item = 006 and prox_status = 515 and vds.id_item = final.id_pai),0) as orcamentos,
					
				**/
		sum(qtde) qtde
    
		from (

			select base.*, tipo_pai, agrupador,
			case when item_pai is null then secundario else item_pai end as item_pai,
			case when id_pai is null then cod_item else id_pai end as id_pai
			from (

				select pedido, tipo, ref_go, ref_despachante, ref_nac_01,  concat(ult_status, ' / ',prox_status) ult_prox, imp.secundario, cod_item, codtipoitem,
					
				case 
				when prox_status = 230 then 'ped_inserido' 
				when prox_status = 280 then 'PL_recebido' 
				when prox_status = 345 then 'confirmado' 
				when prox_status = 350 then 'li_solicitado'
				when prox_status = 355 then 'li_deferida'
				when prox_status = 359 then 'emb_autorizado'
				when prox_status = 365 then 'booking'
				when prox_status = 369 then 'chegada_Br'
				when prox_status = 375 then 'removido'
				when prox_status = 379 then 'registrado'
				when prox_status = 385 then 'nf_emitida'
				when prox_status = 390 then 'carregada'
				when prox_status = 400 then 'chegou_TO' else '' end as desc_status,
				
				case  when codtipoitem = 006 then 'PECA' 
				when (left(imp.secundario,3) = 'FR ' or left(imp.secundario,6) = 'PONTE ') then 'FRENTE' 
				when left(imp.secundario,2) IN ('LE','LD','HE','HD','PL','SC','BL') then 'ACESSORIOS'
				else 'OUTROS' end as tipoitem, qtde_sol qtde
				 
				from importacoes_pedidos imp 
				left join itens on itens.id = cod_item		
				where ref_go not in ('LA200501','QGKI17-7B') and ult_status not in (980) and prox_status not in (999,400)
				-- and ref_go = '908211007-m'
				
				and (
                codtipoitem = 006 
				or ((left(imp.secundario,3) = 'FR ' or left(imp.secundario,6) = 'PONTE ')) )
				
			) as base 

			left join (select * from itens_estrutura   ) as estrutura
			on estrutura.id_filho = cod_item
		
        ) as final

		left join (select itens.id, secundario codsec, agrup, codgrife, colmod, forn.desc_fornecedor fornecedor, left(linha,3) linha 
        from itens left join fornecedores forn on forn.codfornecedor = itens.codfornecedor ) item
		on item.id = final.id_pai
		
        group by pedido, tipo, ref_go, ref_despachante, ref_nac_01, ult_prox, desc_status, secundario, cod_item, codtipoitem, tipoitem, id_pai,
		item_pai, tipo_pai, agrupador, codgrife, colmod, fornecedor, linha
	
    ) as final1
    ) as final2
	group by pedido, tipo, ref_go, ref_despachante, ref_nac_01, ult_prox, desc_status
 
) as final1 
on final1.pedido = base3.pedido and final1.tipo = base3.tipo 

");





/** query nacionalizacao **/



$nacionalizacoes = \DB::select(" 
    select pedido, tipo, sum(qtde_sol) qtde_sol from (
		select pedido, tipo, colecao, sum(qtde_sol) qtde_sol from (
		
			select pedido, tipo, qtde_sol, colmod, clasmod,
		
			case when left(colmod,4) < year(now()) then 'lancado'
			when (left(colmod,4) = year(now()) and right(colmod,2) <= month(now())) then 'lancado' 
            when (left(colmod,4) = year(now()) and right(colmod,2) > month(now())) then 'novo_atual'

			
			when (left(colmod,4) < year(now()) and clasmod in ('LINHA A++','LINHA A+','LINHA A','NOVO')) then 'aa'
			when (left(colmod,4) < year(now()) and clasmod in ('LINHA A-')) then 'a'
			
			when ((left(colmod,4) = year(now()) and right(colmod,2) < month(now())) and clasmod in ('LINHA A++','LINHA A+','LINHA A','NOVO')) then 'aa'
			when ((left(colmod,4) = year(now()) and right(colmod,2) < month(now())) and clasmod in ('LINHA A-')) then 'a'
			else '' end as colecao		
            
			from (
                
					select pedido, tipo, qtde_sol, 
                    ifnull(itenspc.colmod,itenspt.colmod) colmod,
                    ifnull(itenspc.clasmod,itenspt.clasmod) clasmod

						from importacoes_pedidos ped
						left join itens_estrutura ie on ie.id_filho = ped.cod_item
						left join itens itenspc on itenspc.id = ped.cod_item
						left join itens itenspt on itenspt.id = ie.id_pai
						
						where  (left(itenspc.agrup,5) = '$agrup' or left(itenspt.agrup,5) = '$agrup') and 
						ref_go not in ('LA200501','QGKI17-7B') and 
						ult_status not in (980) -- and prox_status not in (999,400)
						and ( itenspc.codtipoitem = 006 
						or ((left(ped.secundario,3) = 'FR ' or left(ped.secundario,6) = 'PONTE ')) )
			) as base0 
		) as base1  $where1

        group by pedido, tipo, colecao
	) as base2 group by pedido, tipo

");






/** compras **/

$compras = \DB::select(" 

select pedido, dt_emissao, obs, condicao_pagamento, valor_total,
adiantamento, venc_adiantamento, moeda , agrup, sum(qtde) qtde
    
     from (

	select c.id pedido, c.dt_emissao, c.obs, condicao_pagamento, (valor_total) valor_total,
    ct.valor adiantamento, ct.vencimento venc_adiantamento, ct.moeda , itens.agrup,
    
			 
			case when left(colmod,4) < year(now()) then 'lancado'
			when (left(colmod,4) = year(now()) and right(colmod,2) <= month(now())) then 'lancado' 
            when (left(colmod,4) = year(now()) and right(colmod,2) > month(now())) then 'novo_atual'
 
			
			when (left(colmod,4) < year(now()) and clasmod in ('LINHA A++','LINHA A+','LINHA A','NOVO')) then 'aa'
			when (left(colmod,4) < year(now()) and clasmod in ('LINHA A-')) then 'a'
			
			when ((left(colmod,4) = year(now()) and right(colmod,2) < month(now())) and clasmod in ('LINHA A++','LINHA A+','LINHA A','NOVO')) then 'aa'
			when ((left(colmod,4) = year(now()) and right(colmod,2) < month(now())) and clasmod in ('LINHA A-')) then 'a'
			else '' end as colecao	,
            sum(qtde) qtde
    
    
	from compras c
	left join compras_itens ci on ci.id_compra = c.id
	left join compras_titulos ct on ct.id_pedido = c.id
	left join itens itens on itens.id = ci.id_item
    
	where  ci.status not in ('cancelado') and left(agrup,5) = '$agrup'
    
    group by c.id, c.dt_emissao, c.obs, condicao_pagamento, valor_total,
    ct.valor, ct.vencimento , ct.moeda , itens.agrup, colmod, clasmod

) as fim $where1 

group by pedido, dt_emissao, obs, condicao_pagamento, valor_total,
adiantamento, venc_adiantamento, moeda , agrup






");



@endphp



<div class="row">
{{$where1}}
	
  <div class="col-md-12">

    <!-- row -->
    <div class="row">
		<div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
				
				<li  class="active"><a href="#Painel" data-toggle="tab">Painel</a></li>
				
				<li><a href="#Tabela" data-toggle="tab">Tabela</a></li>
				<li><a href="#Grade" data-toggle="tab">Grade</a></li>
				<li><a href="#Visual" data-toggle="tab">Visual</a></li>
				
				<li><a href="#Representantes" data-toggle="tab">Representantes</a></li>
				<li><a href="#Mediasugest" data-toggle="tab">Mediasugest</a></li>
				<li><a href="#Timeline_lancamentos" data-toggle="tab">Timeline_lancamentos</a></li>
				<li><a href="#Estoques" data-toggle="tab">Estoques</a></li>
				<li><a href="#Importacoes" data-toggle="tab">Importacoes</a></li>
				<li><a href="#Nacionalizacoes" data-toggle="tab">Nacionalizacoes</a></li>
				<li><a href="#Compras" data-toggle="tab">Compras</a></li>
				<li><a href="#Piramide" data-toggle="tab">Piramide</a></li>
				<li><a href="#Best" data-toggle="tab">Best Selers</a></li>
				
				

				
<div class="tab-content">
		
<!-- aba geral inicio -->	
<div class="active tab-pane" id="Painel">
<div class="col-md-12">
<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">


<br>	
<div class="col-md-12">
		
		@foreach ($modelos as $catalogo)
		
      <div class="col-sm-2">
        <div class="box box-widget">
         
			<div  class="box-header with-border" style="font-size:12px; padding: 15px 15px 15px 15px;"> 
				
          		<b><a href="/painel/{{$catalogo->agrup}}/{{$catalogo->modelo}}/{{$catalogo->modelo}}" class="text-black">{{$catalogo->modelo}}</a></b>
          		<span class="pull-center">{{$catalogo->valortabela}}</span>
			 	<span class="pull-right">{{$catalogo->mediacusto}}</span>
			</div>



        @php
          $foto = app('App\Http\Controllers\ItemController')->consultaFoto($catalogo->modelo);
        @endphp

          <div id="foto" align="center" style="min-height: 180px; max-height: 180px;">
            <a href="" class="zoom" data-value="{{$catalogo->modelo}}"><img src="/{{$foto}}" class="img-responsive"></a>   
          </div>
			
			
			 
		  <br>
		  <table width="100%"  style="font-size:12px;" style="text-align: center;">
			<tr>
				<td>
					<table class="table table-condensed table-bordered table2"  style="text-align: center;">
						<tr>
							<td>Itens:</td>
						</tr>
					</table>
				</td>

				
			 	<td>
					<table class="table table-condensed table-bordered table2"  style="text-align: center;">
						<tr>
							
							<td>{{number_format($catalogo->imediata)}}</td>
						</tr>
					</table>

				</td>
		  
				<td>
					<table class="table table-condensed table-bordered table2" style="text-align: center;">
						<tr>
						
							<td>{{number_format($catalogo->futura)}}</td>
						</tr>
					</table>
				</td>
				<td>
					<table class="table table-condensed table-bordered table2" style="text-align: center;">
						<tr>
							<td>{{number_format($catalogo->producao)}}</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
			
	
			
			<table width="100%" style="font-size:12px;" style="text-align: center;">
								<tr>

								
									<td>
										<table class="table table-condensed table-bordered table2" style="text-align: center;">
											<tr>
												<td><i class="fa fa-calendar-plus-o text-green"></i></td>
												<td>{{$catalogo->colmod}}</td>
											</tr>
										</table>
									</td>
									<td>
										<table class="table table-condensed table-bordered table2" style="text-align: center;">
											<tr>
												<td><i class="fa fa-calendar-minus-o text-red"></i></td>
												<td>{{$catalogo->ciclo}}</td>
											</tr>
										</table>
									</td>
									

								</tr>
							</table>
			
<!--	
		<br>
		 <a title="Com estoque sem vinculo" href="" class="zoom" data-value="{{$catalogo->modelo}}"><i class="fa fa-chain-broken text-red fa-3x" style="position:absolute; top:200px; left:5%; opacity:0.8;" ></i> </a>
					
		 <a title="Revisar item"  href="" class="zoom" data-value="{{$catalogo->modelo}}"><i class="fa fa-warning text-orange fa-2x" style="position:absolute; top:50px; left:5%; opacity:0.8;" ></i></a>
		
-->
			  <br>
					
							<table width="100%" style="font-size:12px;" style="text-align: center;">
								<tr>

								 <td>
										<table class="table table-condensed table-bordered table2"  style="text-align: center;">
											<tr>
												<td align="center"><img src="/img/brasil.png" width="15"></i></td>
												<td>{{number_format($catalogo->disponivel)}}</td>
											</tr>
										</table>

									</td>
									<td>
										<table class="table table-condensed table-bordered table2" style="text-align: center;">
											<tr>
											<td align="center"><img src="/img/to.png" width="15"></i></td>
												<td>{{number_format($catalogo->beneficiamento)}}</td>
											</tr>
										</table>
									</td>
									<td>
										<table class="table table-condensed table-bordered table2" style="text-align: center;">
											<tr>
												<td><i class="fa fa-truck text-green"></i></td>
												<td>0</td>
											</tr>
										</table>
									</td>
									

								</tr>
							</table>
	
<!-- segunda linha -->
					
							<table width="100%" style="font-size:12px;" style="text-align: center;">
								<tr>

								 <td>
										<table class="table table-condensed table-bordered table2"  style="text-align: center;">
											<tr>
												<td><i class="fa fa-plane text-blue"></i></td>
												<td>{{number_format($catalogo->cet)}}</td>
											</tr>
										</table>

									</td>
									
									<td>
										<table class="table table-condensed table-bordered table2" style="text-align: center;">
											<tr>
												<td><i class="fa fa-industry text-purple"></i></td>
												<td>{{number_format($catalogo->cep)}}</td>
											</tr>
										</table>
									</td>
									<td>
										<table class="table table-condensed table-bordered table2" style="text-align: center;">
											<tr>
												<td>Tt</td>
												<td>{{number_format($catalogo->total)}}</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
	<!-- terceira linha -->
					
							<table width="100%" style="font-size:12px;" style="text-align: center;">
								<tr>

								 <td>
										<table class="table table-condensed table-bordered table2"  style="text-align: center;">
											<tr>
												<td><i class="fa fa-suitcase text-blue"></i></td>
												<td>{{number_format($catalogo->most)}}</td>
											</tr>
										</table>

									</td>
									<td>
										<table class="table table-condensed table-bordered table2" style="text-align: center;">
											<tr>
												<td><i class="fa fa-suitcase text-red"></i></td>
												<td>0</td>
											</tr>
										</table>
									</td>
									<td>
										<table class="table table-condensed table-bordered table2" style="text-align: center;">
											<tr>
												<td><i class="fa fa-recycle text-purple"></i></td>
												<td>0</td>
											</tr>
										</table>
									</td>									
								</tr>
							</table>
	
	<!-- terceira linha -->
					<br>
							<table width="100%" style="font-size:12px;" style="text-align: center;">
								<tr>

								 <td>
										<table class="table table-condensed table-bordered table2"  style="text-align: center;">
											<tr>
												<td><i class="fa fa-shopping-cart text-green"></i></td>
												<td>{{number_format($catalogo->atual)}}</td>
												<td>{{number_format($catalogo->ultimo)}}</td>
												<td>{{number_format($catalogo->mes_sem)}}</td>
												<td>{{number_format($catalogo->mes_ano)}}</td>
											</tr>
										</table>

									</td>
									
																	
								</tr>
							</table>

	</div> 
  </div>
     
	@endforeach
	
  </div>


</ul>
</div>
</div>
		  
				  

	  
	  

	  
<div class="tab-pane" id="Tabela">
<!-- The timeline -->

<!-- timeline time label -->
<div class="col-md-12">

<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
<h6>
<table class="table table-bordered" id="example3">
	<thead>
		<tr>
			<th width="5%">Status</th>
			<th width="5%">Modelo</th>
			<th width="5%">Entrada</th>
			<th width="5%">Saida</th>
			<th width="5%">Clasmod</th>
			

			<th width="5%">Genero</th>
			<th width="5%">Idade</th>
			
			<th width="5%">Estilo</th>
			
			<th width="5%">Material</th>
			<th width="5%">Fixacao</th>
			<th width="5%">Tamanho</th>
			
			<th width="5%">Valor</th>
			
			<th width="5%">imediata</th>
			<th width="5%">cet</th>
			<th width="5%">cep</th>
			
			<th width="5%">cet</th>
			<th width="5%">cep tt</th>
			<th width="5%">most</th>
			

		</tr>
	</thead>
	<tbody>
		@foreach ($modelos as $catalogo)

		@php
		
		switch ($catalogo->modelo) {
			case 'entradas':
			$formato = 'fa fa-plus-square text-green';
			
			break;
			case 'saidas':
			$formato = 'fa fa-minus-square text-red';
			
			break;             
			default:
			$formato = 'fa fa-check-square text-blue';

		}
		@endphp
		<tr>
			<td align="left" class="{{$formato}}"> {{$catalogo->imediata}}</td>
			<td align="left">  <a href="/painel/{{$catalogo->agrup}}/{{$catalogo->modelo}}">{{$catalogo->modelo}}</a></td>			
			<td align="left"> {{$catalogo->colmod}}</td>
			<td></td>
			<td align="left"> {{$catalogo->clasmod}}</td>
			<td align="left"> {{$catalogo->genero}}</td>
			<td align="left"> {{$catalogo->idade}}</td>
			<td align="left"> {{$catalogo->forn}}</td>
			<td></td><td></td><td></td>
			<td align="left"> {{number_format($catalogo->valortabela,2)}}</td>
			
			<td align="left"> {{$catalogo->imediata}}</td>			
			<td align="left"> {{$catalogo->cet}}</td>
			<td align="left"> {{$catalogo->cep}}</td>
			<td align="left"> {{$catalogo->cep}}</td>
			<td align="left"> {{$catalogo->cep}}</td>
			<td align="left"> {{$catalogo->most}}</td>
			
			
		</tr>
		@endforeach
	</tbody>
</table>
	</h6>
</ul>
</div>
</div>

				  
				  

	

	
<div class="tab-pane" id="Visual">
<!-- The timeline -->
<h6>
<!-- timeline time label -->
<div class="col-md-12">
		
		@foreach ($modelos as $catalogo)
		
      <div class="col-sm-2">
        <div class="box box-widget">
         
			<div  class="box-header with-border" style="font-size:12px; padding: 15px 15px 15px 15px;"> 
				
          		<b><a href="/painel/{{$catalogo->agrup}}/{{$catalogo->modelo}}/{{$catalogo->modelo}}" class="text-black">{{$catalogo->modelo}} </a></b>
				<span class="pull-right">{{($catalogo->colmod)}}</span>
				<br>

<table class="table table-condensed table-bordered table2" style="text-align: center;">
	<tr>
	<td>{{($catalogo->clasmod)}}</td>
	<td>{{number_format($catalogo->mediacusto,2)}}</td>
	<td>{{number_format($catalogo->valortabela,2)}}</td>
	</tr>
	
	<tr>
	<td>{{($catalogo->idade)}}</td>
	<td>{{($catalogo->estilo)}}</td>
	<td>{{($catalogo->genero)}}</td>
	</tr>
</table>
				

          		
			 	
				
			</div>



        @php
          $foto = app('App\Http\Controllers\ItemController')->consultaFoto($catalogo->modelo);
        @endphp

          <div id="foto" align="center" style="min-height: 180px; max-height: 180px;">
            <a href="" class="zoom" data-value="{{$catalogo->modelo}}"><img src="/{{$foto}}" class="img-responsive"></a>   			
			</div>
							
	</div> 
  </div>
     
	@endforeach
	
  </div>
	</h6>
</div>
	
	
	
	
	
	
	
	
	
				  
				  
				  
				  
<div class="tab-pane" id="Grade">
                <!-- The timeline -->
              
<!-- timeline time label -->
<div class="col-md-12">
		 
 <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

<div class="row">

  <div class="col-md-4">
    <span class="lead">Grade Ideal</span>
    <div class="box box-widget">
      <div class="box-header with-border bg-gray"> 
        <b>agrup</b>
        <span class="pull-right"><b></b></span>
      </div>
      <div align="center" style="min-height: 100px;margin-top: 30px;">


        @php
          $foto = app('App\Http\Controllers\ItemController')->consultaFoto('ah');
        @endphp
		  

        <a href="" class="zoom" data-value="ah">
           <!-- <img src="/{{$foto}}" class="img-responsive"> -->
			<img src="/img/marcas/ah.png" style="max-height: 100px;" class="img-responsive">
        </a>

		  
      </div>
		
		
	
		<div class="box-body">

        <div class="row">
          <div class="col-sm-6 col-md-6">
             <table class="table table-bordered" style="text-align: left;">
          <tr>
            <td class="text-danger"><i class="fa fa-chain-broken"></i><b> FALTA VINCULOsssss</td>
    <!--         <td class="text-danger">grife</b> -->
              
            </td>
          </tr> </table>
          </div>
          
        </div>
        @php
    
      $mesesforn = 2;
   
@endphp     
       
<div class="row" style="padding-bottom: 2px;">
    <div class="col-md-12">
        <table width="100%">
            <tr>
              <td>
                <table class="table table-condensed table-bordered table2" style="text-align: center;">
                    <tr>					
                      <td><i class="fa fa-heartbeat text-red"></i></td>
                      
                      <td>E</td>
            <!--           <td>grife</td> -->
                    </tr>
                </table>
        </td>
    </tr>
</table>
</div>
</div>

	  
	  
	  
	  
<div class="row" style="padding-bottom: 2px;">
    <div class="col-md-12">
        <table width="100%">
            <tr>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-shopping-cart text-green"></i></td>
                            
                            <td>
                              
                   
                            
                               
                            </td>
                            
                        </tr>
                    </table>

                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-line-chart text-blue"></i></td>
                            <td>codgrife</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>

							
				<td><i class="fa fa-heartbeat text-red"></i></td>
							
							
                        </tr>
						
						
                    </table>
                </td>
				
		<td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
						
                        <tr>
                            <td><i class="fa fa-hourglass-3 text-purple"></i></td>
                            <td>grife</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</div>

        <div class="row">
    <div class="col-md-12">
        <table width="100%" style="text-align: center;">
            <tr>
                <td>
                    <table class="table table-condensed table-bordered table2"  style="text-align: center;">
                        <tr>
                            <td align="center"><img src="/img/brasil.png" width="15"></i></td>
                            <td>modelos_grade</td>
                        </tr>
                    </table>

                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-plane text-blue"></i></td>
                            <td>modelos_entra</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td align="center"><img src="/img/china.png" width="15"></i></td>
                            <td>modelos_sai</td>
                        </tr>
                    </table>
                </td>
               
                
               
				
            </tr>






        </table>


    </div>
</div>

       

      </div>
		
				
		
	
      <div class=" box-body">
        <div class="row">
          <div class="col-md-6">
           
        
          </div>
          <div class="col-md-6" align="right"> </div>
        </div>
		  
		  
        <table class="table table-bordered" style="text-align: left;">
         

		<tr>
			<td><i class="fa fa-cube"></i> Adulto</td>
			<td></td>
			<td>ideal </td>
			<td>atual </td>
			<td>30dd </td>
			<td>60dd </td>
			<td>180dd </td>
		</tr>     

				<tr>
					<td><i class="fa fa-th"></i> Gender</td>
					<td>TOTAL</td>
					<td>x</td>
					<td>x </td>
					<td>x</td>
					<td>x </td>
					<td>x </td>
				</tr>  

					<tr>
						<td></td>
						<td><i class="fa fa-female text-red"></i>   Female</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr>  

					<tr>
						<td></td>
						<td><i class="fa fa-male text-blue"></i>   Male</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr>  

		  
					<tr>
						<td></td>
						<td><i class="fa fa-female text-yellow"></i><i class="fa fa-male text-yellow"></i> Unissex</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr>  
			
			    <tr><td></td></tr>
				<tr>
					<td><i class="fa fa-th"></i> Age</td>
					<td>TOTAL</td>
					<td>y </td>
					<td>y </td>
					<td>y </td>
					<td>y </td>
					<td>y </td>
				</tr>  

					<tr>
						<td></td>
						<td><i class="fa fa-female text-red"></i>   Adult</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr>  

					<tr>
						<td></td>
						<td><i class="fa fa-male text-blue"></i>   Young</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr>  

		  
					<tr>
						<td></td>
						<td><i class="fa fa-female text-red"></i> Kids</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr>  
		  
			<tr><td></td></tr>
				<tr>
					<td><i class="fa fa-th"></i> Material</td>
					<td>TOTAL</td>
					<td>z </td>
					<td>z </td>
					<td>z </td>
					<td>z </td>
					<td>z </td>
				</tr>  

					<tr>
						<td></td>
						<td><i class="fa fa-female text-red"></i>   Metal</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr>  

					<tr>
						<td></td>
						<td><i class="fa fa-male text-blue"></i>   Acetate</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr>  

		  
					<tr>
						<td></td>
						<td><i class="fa fa-female text-red"></i> Plastic</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr> 
			<tr><td></td></tr>
				<tr>
					<td><i class="fa fa-th"></i> Fix</td>
					<td>TOTAL</td>
					<td>w </td>
					<td>w </td>
					<td>w </td>
					<td>w </td>
					<td>w </td>
				</tr>  

					<tr>
						<td></td>
						<td><i class="fa fa-female text-red"></i>   Full</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr>  

					<tr>
						<td></td>
						<td><i class="fa fa-male text-blue"></i>   Nylon</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr>  

		  
					<tr>
						<td></td>
						<td><i class="fa fa-female text-red"></i> Ballgrif</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr> 
			<tr><td></td></tr>
				<tr>
					<td><i class="fa fa-th"></i> Style</td>
					<td>TOTAL</td>
					<td>q </td>
					<td>q </td>
					<td>q </td>
					<td>q </td>
					<td>q </td>
				</tr>  

					<tr>
						<td></td>
						<td><i class="fa fa-female text-red"></i>   Casual</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr>  

					<tr>
						<td></td>
						<td><i class="fa fa-male text-blue"></i>   Fashion</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr>  

		  
					<tr>
						<td></td>
						<td><i class="fa fa-female text-red"></i> Sport</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr> 
					
					<tr>
						<td></td>
						<td><i class="fa fa-female text-red"></i> Luxury</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr> 
			
			<tr><td></td></tr>
				<tr>
					<td><i class="fa fa-th"></i> Size</td>
					<td>TOTAL</td>
					<td>e </td>
					<td>e </td>
					<td>e</td>
					<td>e</td>
					<td>e </td>
				</tr>  

					<tr>
						<td></td>
						<td><i class="fa fa-female text-red"></i>   40-50</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr>  

					<tr>
						<td></td>
						<td><i class="fa fa-male text-blue"></i>   51-53</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr>  

		  
					<tr>
						<td></td>
						<td><i class="fa fa-female text-red"></i> 54-56</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr> 
					
					<tr>
						<td></td>
						<td><i class="fa fa-female text-red"></i> >=57</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr> 
        </table>
  
       
      </div>
    </div>
  </div>



















<!-- comeca itens -->

  <div class=" box-body">
  <div class="col-md-8">
    <span class="lead">Lancamentos</span>
    <div class="row">
      @foreach ($query as $catalogo)

        @php
          switch ($catalogo->statusatual) {
            case 'DISP':
              $cor = 'green';
              break;
            case 'ESGOT':
              $cor = 'red';
              break;
            case '15D':
              $cor = 'blue';
              break;
            case '30D':
              $cor = 'yellow';
              break;
            case 'PROD':
              $cor = 'purple';
              break;              
            default:
              $cor = 'blue';

          }
        @endphp

      <div class="col-md-6">
        <div class="box box-widget">
			<div  class="box-header with-border" style="font-size:16px; padding: 12px 10px 12px 10px;"> 
			  <b> <td><a href="/produtos/gradescoldet/{{$catalogo->agrup}}?colecao={{$catalogo->colmod}}">{{$catalogo->colmod}}+999</a></td>
 </b>
		<!--	  <span class="pull-right">{{$catalogo->colmod}}</span> -->
			</div>

			
			  @if ($catalogo->statusatual > 0 )
 	<!--					<br>
						<table class="table table-bordered" style="text-align: left;">
					  <tr>
						  <td class=""><i class="fa fa-chain"></i><b> Itens</td>
						<td class="">{{$catalogo->atual}} </b>
							</td>
					  </tr> </table>
 	-->	
		
            @endif
              
				

		
		@if ($catalogo->statusatual > 0 and  $catalogo->statusatual < 1)
		<br><br>
		 <a title="Com estoque sem vinculo" href="" class="zoom" data-value="{{$catalogo->colecao}}"><i class="fa fa-chain-broken text-red fa-3x" style="position:absolute; top:200px; left:5%; opacity:0.8;" ></i> </a>
				
			@endif
            
		@if($catalogo->statusatual < 1 and  $catalogo->statusatual < 1)
		
		 <a title="Revisar item"  href="" class="zoom" data-value="{{$catalogo->colmod}}"><i class="fa fa-warning text-orange fa-2x" style="position:absolute; top:200px; left:5%; opacity:0.8;" ></i></a>
		
		@endif
		
          <div class="box-body">
           <!-- linha 452--> 
			  
			  
<div class="row">
    <div class="col-md-12">
        <table width="100%" style="text-align: center;">
            <tr>

			 <td>
                    <table class="table table-condensed table-bordered table2"  style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-suitcase text-green"></i></td>
                            <td>999</td>
                        </tr>
                    </table>

                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-check-square text-blue"></i></td>
                            <td>0</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-plus-square text-green"></i></td>
                            <td>0</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-minus-square text-red"></i></td>
                            <td>0</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</div>
			  
			  




<div class="row">
    <div class="col-md-12">
        <table width="100%" style="text-align: center;">
            <tr>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-female text-red"></i></td>
                            <td>fem</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-male text-blue"></i></td>
                            <td>mas</td>
                        </tr>
                    </table>
                </td>
				
				  <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-female text-red"></i><i class="fa fa-male text-yellow"></i></td>
                            <td>unis</td>
                        </tr>
                    </table>
                </td> 				
            </tr>
        </table>
    </div>
</div>



<div class="row">
    <div class="col-md-12">
        <table width="100%" style="text-align: center;">
            <tr>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>Adult</b></td>
                            <td>adul</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>Young</b></td>
                            <td>yo</td>
                        </tr>
                    </table>
                </td>
				  <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>Kids</b></td>
                            <td>inf</td>
                        </tr>
                    </table>
                </td> 				
            </tr>
        </table>
    </div>
</div>

			  
			  
<div class="row">
    <div class="col-md-12">
        <table width="100%" style="text-align: center;">
            <tr>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>Metal</b></td>
                            <td></td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>Acetate</b></td>
                            <td> </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>Plastic</b></td>
                            <td> </td>
                        </tr>
                    </table>
                </td>
 				
            </tr>
        </table>
    </div>
</div>

			
			  
<div class="row">
    <div class="col-md-12">
        <table width="100%" style="text-align: center;">
            <tr>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>Full</b></td>
                            <td> }</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>Nylon</b></td>
                            <td> </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>Ballgriff</b></td>
                            <td> </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</div>		
			  
			  
			  
<div class="row">
    <div class="col-md-12">
        <table width="100%" style="text-align: center;">
            <tr>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>Casual</b></td>
                            <td> </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>Fashion</b></td>
                            <td> </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>Sport</b></td>
                            <td> </td>
                        </tr>
                    </table>
                </td>
				 <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>Luxury</b></td>
                            <td> </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</div>		
			  
			  
			  
<div class="row">
    <div class="col-md-12">
        <table width="100%" style="text-align: center;">
            <tr>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>40-50</b></td>
                            <td> </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>51-53</b></td>
                            <td> </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>54-56</b></td>
                            <td> </td>
                        </tr>
                    </table>
                </td>
 				<td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>>=57</b></td>
                            <td> </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</div>			  
			  
			  
			  
			  
			  
			  
		
          </div>
        </div>
      </div>
      @endforeach

     

    </div>
  </div>

</div>
</div>

					   </ul>
				  </div>
</div>
				  
				  
				  
				  

<div class="tab-pane" id="clientes">
<div class="col-md-12">
<ul class="timeline">


@php

$fotos = \DB::select("select * from itens where modelo = 'ah6254' ");

	$result = count($fotos);
	echo 'resultado'.$result;
	
@endphp
	
	

<div class="col-md-12">
<span class="lead">Modelos</span>
<div class="row">		
		
		@foreach ($fotos as $catalogo)
		
		
		


<div class="row">

      <div class="col-md-2">
        <div class="box box-widget">
          <div  class="box-header with-border" style="font-size:14px; padding: 12px 10px 12px 10px;"> 
          <b><a href="/painel/{{$catalogo->agrup}}/{{$catalogo->modelo}}/{{$catalogo->modelo}}" class="text-black">{{$catalogo->modelo}}</a></b>
          <span class="pull-right">  {{$catalogo->modelo}}</span>
			  <span class="pull-right">{{$catalogo->colmod}}</span>
			 
          
                
			
			</div>



        @php
          $foto = app('App\Http\Controllers\ItemController')->consultaFoto($catalogo->modelo);
        @endphp

          <div id="foto" align="center" style="min-height: 100px; max-height: 100px;">
            <a href="" class="zoom" data-value="{{$catalogo->modelo}}"><img src="/{{$foto}}" class="img-responsive"></a>

                  
          </div>
			
			
			 
			<br>
			<table class="table table-bordered" style="text-align: left;">
          <tr>
			  <td class=""><i class="fa fa-chain"></i><b> Itens</td>
            <td class="">{{$catalogo->valortabela}} </b>
				</td>
          </tr> </table>
      
              
				

		
		
		<br><br>
		 <a title="Com estoque sem vinculo" href="" class="zoom" data-value="{{$catalogo->modelo}}"><i class="fa fa-chain-broken text-red fa-3x" style="position:absolute; top:200px; left:5%; opacity:0.8;" ></i> </a>
				
			
            
		
		
		 <a title="Revisar item"  href="" class="zoom" data-value="{{$catalogo->modelo}}"><i class="fa fa-warning text-orange fa-2x" style="position:absolute; top:50px; left:5%; opacity:0.8;" ></i></a>
		

		
          <div class="box-body">
           <!-- linha 452--> 
			  
			  
<div class="row">
    <div class="col-md-12">
        <table width="100%" style="text-align: center;">
            <tr>

			 <td>
                    <table class="table table-condensed table-bordered table2"  style="text-align: center;">
                        <tr>
                            <td align="center"><img src="/img/brasil.png" width="15"></i></td>
                            <td>{{number_format($catalogo->valortabela)}}</td>
                        </tr>
                    </table>

                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-plane text-blue"></i></td>
                            <td>{{number_format($catalogo->valortabela)}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-industry text-purple"></i></td>
                            <td>{{number_format($catalogo->valortabela)}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-warning text-yellow"></i></td>
                            <td>{{number_format($catalogo->valortabela)}}</td>
                        </tr>
                    </table>
                </td>
 				
            </tr>




        </table>


    </div>
</div>
			  
			  
			  

          </div>
        </div>
      </div>
      @endforeach

     

    </div>
  </div>

</div>


</ul>
</div>
</div>
				
	
	  
	  
	  
	  
	  
	  
	  
				  
				  
<div class="tab-pane" id="Importacoes">
                <!-- The timeline -->
              
                  <!-- timeline time label -->
                  <div class="col-md-12">
		 
        <!-- The time line -->
        <ul class="timeline">

<div class="tab-pane" id="Tabela">
<!-- The timeline -->

<!-- timeline time label -->
<div class="col-md-12">

<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

<div class="row"> 
	
<div class="col-md-15">	
<div class="box box-body">

	<div class="table-responsive">		
	   <table class="table table-striped table-bordered compact" id="myTable">
		  <thead>	
			
		 <tr>	

	 		<td colspan="15">Importações em aberto </td>
		
				</tr>
		  			
					<tr>
					
					<td colspan="1" align="center">ult_prox status</td>
					<td colspan="1" align="center">Pedido</td>	
					<td colspan="1" align="center">Invoice</td>				
					<td colspan="1" align="center">ref desp</td>
					<td colspan="1" align="center">conex</td>
					<td colspan="1" align="center">fornecedor</td>
					
					<td colspan="1" align="center">Tipo_item</td>
					<td colspan="1" align="center">Grifes </td>
					<td colspan="1" align="center">Colecoes</td>
					<td colspan="1" align="center">Linhas</td>
					
					<td colspan="1" align="center">qtde filtro</td>
					<td colspan="1" align="center">qtde pecas</td>
					<td colspan="1" align="center">atende BO</td>
					<td colspan="1" align="center">itens CET</td>
						
					<td colspan="1" align="center">itens CEP</td>
					<td colspan="1" align="center">dt perdimento</td>

						
						
					
					
					
				
					</tr>
			    </thead>
			  
			@foreach ($importacoes as $query2)
			  
			<tr>
		
				
			<td align="center">{{$query2->ult_prox}} - {{$query2->desc_status}}</td>
			<td align="left"><a href="/dsimportdet?tipo={{$query2->tipo}}&pedido={{$query2->pedido}}">{{$query2->tipo.' '.$query2->pedido}}</a></td>	
			<td align="left">{{$query2->ref_go}}</td>
			<td align="center">{{$query2->ref}}</td>
			<td align="center"></td>
			<td align="left">{{$query2->fornecedor}}</td>
			<td align="center">{{$query2->tipoitem}}</td>
			<td align="center">{{$query2->codgrife}}</td>
			<td align="center">{{$query2->colmod}}</td>
			<td align="center">{{$query2->linha}}</td>
						<td align="center">{{number_format($query2->qtde_sol)}}</td>
			<td align="center">{{number_format($query2->qtde)}}</td>	
			<td align="center">{{number_format($query2->atende)}}</td>
			<td align="center">{{number_format($query2->itens_trans)}}</td>


			<td></td>
			<td></td>
			</tr>
			@endforeach 
			

		</table>
			
		</div>
			</div>
		</div>	
	
		</div>
	</ul>
	</div>
	</div>
</ul>
</div>
</div>









<div class="tab-pane" id="Nacionalizacoes">
                <!-- The timeline -->
              
                  <!-- timeline time label -->
                  <div class="col-md-12">
		 
        <!-- The time line -->
        <ul class="timeline">

<div class="tab-pane" id="Tabela">
<!-- The timeline -->

<!-- timeline time label -->
<div class="col-md-12">

<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

<div class="row"> 
	
<div class="col-md-15">	
<div class="box box-body">

	<div class="table-responsive">		
	   <table class="table table-striped table-bordered compact" id="myTable">
		  <thead>	
			
		 <tr>	

	 		<td colspan="15">Nacionalizacoes</td>
		
				</tr>
		  			
					<tr>
					
					<td colspan="1" align="center">pedido</td>
					<td colspan="1" align="center">tipo</td>	
					<td colspan="1" align="center">qtde</td>	
						
					
						
						
					
					
					
				
					</tr>
			    </thead>
			  
			@foreach ($nacionalizacoes as $query2)
			  
			<tr>
		
				
	
			<td align="left"><a href="/dsimportdet?tipo={{$query2->tipo}}&pedido={{$query2->pedido}}">{{$query2->tipo.' '.$query2->pedido}}</a></td>	
			<td align="left">{{$query2->tipo}}</td>
			<td align="center">{{number_format($query2->qtde_sol)}}</td>


			</tr>
			@endforeach 
			

		</table>
			
		</div>
			</div>
		</div>	
	
		</div>
	</ul>
	</div>
	</div>
</ul>
</div>
</div>








<div class="tab-pane" id="Compras">
                <!-- The timeline -->
              
                  <!-- timeline time label -->
                  <div class="col-md-12">
		 
        <!-- The time line -->
        <ul class="timeline">

<div class="tab-pane" id="Tabela">
<!-- The timeline -->

<!-- timeline time label -->
<div class="col-md-12">

<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

<div class="row"> 
	
<div class="col-md-15">	
<div class="box box-body">

	<div class="table-responsive">		
	   <table class="table table-striped table-bordered compact" id="myTable">
		  <thead>	
			
		 <tr>	

	 		<td colspan="15">Compras</td>
		
				</tr>
		  			
					<tr>
					
					<td colspan="1" align="center">pedido</td>
					<td colspan="1" align="center">dt_emissao</td>	
					<td colspan="1" align="center">obs</td>	
						
					<td colspan="1" align="center">condicao_pagamento</td>
					<td colspan="1" align="center">qtde</td>		
					<td colspan="1" align="center">valor_total</td>	
					<td colspan="1" align="center">valor adiantamento</td>	
					
					<td colspan="1" align="center">venc adiantamento</td>
					<td colspan="1" align="center">moeda</td>	
					</tr>
			    </thead>
			  
			@foreach ($compras as $query2)
			  
			<tr>
		
				
	
			<td align="left"><a href="/compras/{{$query2->pedido}}">{{$query2->pedido}}</a></td>	
			<td align="left">{{$query2->dt_emissao}}</td>
			<td align="left">{{$query2->obs}}</td>
			<td align="left">{{$query2->condicao_pagamento}}</td>
			<td align="center">{{number_format($query2->qtde)}}</td>
			<td align="center">{{number_format($query2->valor_total)}}</td>
			<td align="center">{{number_format($query2->adiantamento)}}</td>
			<td align="left">{{$query2->venc_adiantamento}}</td>
			<td align="left">{{$query2->moeda}}</td>

			</tr>
			@endforeach 
			

		</table>
			
		</div>
			</div>
		</div>	
	
		</div>
	</ul>
	</div>
	</div>
</ul>
</div>
</div>





<div class="tab-pane" id="Piramide">
                <!-- The timeline -->
              
                  <!-- timeline time label -->
                  <div class="col-md-12">
		 
        <!-- The time line -->
        <ul class="timeline">

<div class="tab-pane" id="Tabela">
<!-- The timeline -->

<!-- timeline time label -->
<div class="col-md-12">

<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

<div class="row"> 
	
<div class="col-md-15">	
<div class="box box-body">

			<div class="padd-80">
				<div class="row">
				<div class="col-lg-4 col-lg-offset-4 col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-12">
				<td colspan="1" align="center">linha1</td>
				</div>
				</div>
			</div>
	
	
	
			<div class="padd-80">
				<div class="row">
				<div class="col-lg-4 col-lg-offset-2 col-md-4 col-md-offset-2 col-sm-6 col-sm-offset-3 col-xs-12">	
				<td colspan="1" align="center">linha2</td>		
				</div>
				<div class="col-lg-4 col-lg-offset-0 col-md-4 col-md-offset-0 col-sm-6 col-sm-offset-3 col-xs-12">
				<td colspan="1" align="center">linha2</td>
				</div>
				</div>
			</div>
	
			

			<div class="padd-80">
				<div class="row">
				<div class="col-lg-4 col-lg-offset-0 col-md-4 col-md-offset-0 col-sm-6 col-sm-offset-3 col-xs-12">
					<td colspan="1" align="center">linha3</td>
				</div>
				<div class="col-lg-4 col-lg-offset-0 col-md-4 col-md-offset-0 col-sm-6 col-sm-offset-3 col-xs-12">
					<td colspan="1" align="center">linha3</td>
				</div>
				<div class="col-lg-4 col-lg-offset-0 col-md-4 col-md-offset-0 col-sm-6 col-sm-offset-3 col-xs-12">
					<td colspan="1" align="center">linha3</td>
				</div>
				</div>
			</div>


	
	
	
	
	
	
	
			</div>
		</div>	
	
		</div>
	</ul>
	</div>
	</div>
</ul>
</div>
</div>




				  
<div class="tab-pane" id="reprocessos">
                <!-- The timeline -->
              
                  <!-- timeline time label -->
                  <div class="col-md-12">
		 
        <!-- The time line -->
        <ul class="timeline">

@php

  $datas = \DB::select("select date(historicos.created_at) as data
from historicos
left join itens on id_item = itens.id			
where secundario LIKE  'ah6254 a01%'
			
group by date(historicos.created_at)
order by date(historicos.created_at) desc
		
			");

@endphp


@foreach ($datas as $data)

      <li class="time-label">
            <span class="bg-blue">
              {{date("d/m/Y", strtotime($data->data))}}
            </span>
      </li>

    @php

      $reprocessos = \DB::select("select historicos.*, usuarios.nome
from historicos 
left join usuarios on id_usuario = usuarios.id
left join itens on id_item = itens.id			
where secundario LIKE  'ah6254 a01%' and date(historicos.created_at) = '$data->data'
			and categoria = 'reprocesso'
			order by historicos.created_at desc
			");

    @endphp

      @foreach ($reprocessos as $reprocesso)

			<li>

            <i class="fa fa-envelope bg-blue"></i>
			  

            <div class="timeline-item">
              <span class="time"><i class="fa fa-clock-o"></i> {{$reprocesso->created_at}}</span>

              <h3 class="timeline-header"><a href="#">{{$reprocesso->nome}}</a> alterou uma {{$reprocesso->categoria}}</h3>

              <div class="timeline-body">
                {!!$reprocesso->historico!!}
                @if ($reprocesso->arquivo <> '')

                  @php
                    $arquivo = explode('.', $reprocesso->arquivo);
                  @endphp

                  @if (isset($arquivo[1]) && (strtolower($arquivo[1]) == 'jpg' or strtolower($arquivo[1]) == 'jpeg' )) 

                    <img src="/storage/{{$reprocesso->arquivo}}" class="img-responsive">

                  @else

                    <br>Arquivo: <a href="/storage/{{$reprocesso->arquivo}}" target="_blank">{{$historico->arquivo}}</a>

                  @endif
                  

                @endif
              </div>
				


              <div class="timeline-footer">
                <a href="/historico/{{$reprocesso->id}}/deleta" class="btn btn-danger btn-xs">Delete</a>
              </div>
            </div>

          </li>
        @endforeach



@endforeach
					   </ul>
				  </div>
					   </div>			

             <div class="tab-pane" id="data_producao">
                <!-- The timeline -->
              
                  <!-- timeline time label -->
                  <div class="col-md-12">
     
        <!-- The time line -->
        <ul class="timeline">

@php

  $datas = \DB::select("select date(historicos.created_at) as data
from historicos
left join itens on id_item = itens.id     
where secundario LIKE  'ah6254 a01%'
      and categoria = 'data_producao'
group by date(historicos.created_at)
order by date(historicos.created_at) desc
    
      ");

@endphp


@foreach ($datas as $data)

      <li class="time-label">
            <span class="bg-blue">
              {{date("d/m/Y", strtotime($data->data))}}
            </span>
      </li>

    @php

      $data_producao = \DB::select("select historicos.*, usuarios.nome
from historicos 
left join usuarios on id_usuario = usuarios.id
left join itens on id_item = itens.id     
where secundario LIKE  'ah6254 a01%' and date(historicos.created_at) = '$data->data'
      and categoria = 'data_producao'
      order by historicos.created_at desc
      ");

    @endphp

      @foreach ($data_producao as $data_producao1)

      <li>

            <i class="fa fa-envelope bg-blue"></i>
        

            <div class="timeline-item">
              <span class="time"><i class="fa fa-clock-o"></i> {{$data_producao1->created_at}}</span>

              <h3 class="timeline-header"><a href="#">{{$data_producao1->nome}}</a> alterou uma {{$data_producao1->categoria}}</h3>

              <div class="timeline-body">
                {!!$data_producao1->historico!!}<br>
                <b>Nova data entrega</b> {!!$data_producao1->nova_data_producao!!}<br>
                <b>Pedido fábrica</b> {!!$data_producao1->pedido_fabrica!!}
                @if ($data_producao1->arquivo <> '')

                  @php
                    $arquivo = explode('.', $data_producao1->arquivo);
                  @endphp

                  @if (isset($arquivo[1]) && (strtolower($arquivo[1]) == 'jpg' or strtolower($arquivo[1]) == 'jpeg' )) 

                    <img src="/storage/{{$data_producao1->arquivo}}" class="img-responsive">

                  @else

                    <br>Arquivo: <a href="/storage/{{$data_producao1->arquivo}}" target="_blank">{{$historico->arquivo}}</a>

                  @endif
                  

                @endif
              </div>
        


              <div class="timeline-footer">
                <a href="/historico/{{$data_producao1->id}}/deleta" class="btn btn-danger btn-xs">Delete</a>
              </div>
            </div>

          </li>
        @endforeach

        

@endforeach
             </ul>
          </div>
             </div>     	  				  
				  				  				  
				  				  				  				  
				  				  				  				  				  				  
</div> <!-- div tab content -->

  </div>

</div>

@include('produtos.painel.modal.genero')
@include('produtos.painel.modal.caracteristica')
@stop