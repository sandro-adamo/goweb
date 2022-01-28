@extends('produtos/painel/index')
@php

$agrup = 'fe02 - ferrati (rx)';
$colecao = '2022 03';


if(isset($_GET["colecao"])){
	$colecao = $_GET["colecao"];
	$where = "where colecao = $colecao";

	$whereteste = "where agrup = '".$agrup."' and colecao = ".$colecao; 

} else { $where = "where 1=1" ; }
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
select * from (
select fornecedor, grife, codgrife, agrup, modelo, colecao, colmod, clasmod,
	sum(novos) novos, sum(aa) aa, sum(a) a, 
	sum(itens) itens, sum(imediata) imediata, sum(futura) futura, sum(producao) producao, sum(esgotado) esgotado, 
	sum(am3cores) am3cores, sum(b2cores) b2cores, sum(c1cor) c1cor, sum(d0cor) d0cor 
from (

	select fornecedor, grife, codgrife, agrup, colecao, modelo, colmod, clasmod,
	case when colecao = 'novo' then 1 else 0 end as novos,
	case when colecao = 'aa' then 1 else 0 end as aa, 
	case when colecao = 'a' then 1 else 0 end as a, 
	sum(itens) itens, sum(imediata) imediata, sum(futura) futura, sum(producao) producao, sum(esgotado) esgotado, 
	sum(am3cores) am3cores, sum(b2cores) b2cores, sum(c1cor) c1cor, sum(d0cor) d0cor 
	from (

		select fornecedor, grife, codgrife, agrup, modelo, clasmod, colmod, (itens) as itens, (imediata) imediata, (futura) futura, (producao) producao, (esgotado) esgotado,
			case when imediata+futura >= 3 then 1 else 0 end as am3cores,
			case when imediata+futura  = 2 then 1 else 0 end as b2cores,
			case when imediata+futura  = 1 then 1 else 0 end as c1cor,
			case when imediata+futura  = 0 then 1 else 0 end as d0cor,
			
			 case when colecao = 'novo' then 'novo'
			 when colecao <> 'novo' and clasmod in ('LINHA A++','LINHA A+','LINHA A','NOVO') then 'aa'
			 when colecao <> 'novo' and clasmod in ('LINHA A-') then 'a' else '' end as colecao
			
			
		from(

			select fornecedor, grife, codgrife, agrup, modelo, clasmod, colmod, colecao, sum(itens) as itens, sum(imediata) imediata, sum(futura) futura, sum(producao) producao, sum(esgotado) esgotado
			
			from (
			 
				select fornecedor, grife, codgrife, agrup, modelo, clasmod, colmod, colecao, 1 as itens,
					case when ultstatus = 'ENTREGA IMEDIATA' then 1 else 0 end as imediata,
					case when ultstatus like '%DIAS' then 1 else 0 end as futura,
					case when ultstatus like '%PROD%' then 1 else 0 end as producao,
					case when ultstatus like '%ESGOTADO%' then 1 else 0 end as esgotado
				from (
							
				    select case when fornecedor like 'kering%' then 'kering' else 'go' end as fornecedor,
					grife, codgrife, itens.agrup, itens.modelo, itens.secundario, colmod,  ultstatus,
					case when left(colmod,4) < year(now()) then 'lancado'
					when (left(colmod,4) = year(now()) and right(colmod,2) < month(now())) then 'lancado' else 'novo' end as colecao,
                    (select clasmod from itens iclas where iclas.id = itens.id and clasmod  not in ('','.','colecao europra','cancelado') order by clasmod limit 1) clasmod
					from itens 
					where itens.secundario not like '%semi%' and (clasmod like 'linha%' or clasmod like 'novo%') and codtipoitem = 006				 
					and codgrife in ('AH','AI','FE',  'AT','BG','EV','JO','HI','SP','TC','JM','NG','GU','MM','ST','AM','MC','CT','BC','BV','SM') 
					and codtipoarmaz not in ('o')
					and agrup = 'fe02 - ferrati (rx)'
				) as fim2
			) as fim3 group by fornecedor, grife, codgrife, agrup, modelo, clasmod, colmod, colecao
		) as fim4 
	) as fim5 group by fornecedor, grife, codgrife, agrup, colecao, modelo, colmod, clasmod
) as fim6 
$where
group by fornecedor, grife, codgrife, agrup, modelo, colecao, colmod, clasmod
) as modelos

	left join (select modelo mod_saldo, sum(disponivel) disponivel, sum(conf_montado+em_beneficiamento+saldo_parte) beneficiamento, sum(cet) cet, sum(etq+cep) cep, sum(saldo_most) most,

sum(disponivel+conf_montado+em_beneficiamento+saldo_parte+cet+etq+cep) total

    from saldos left join itens on itens.id = saldos.curto where agrup = '$agrup'
    group by modelo ) as saldos
    on saldos.mod_saldo = modelo

	left join (select modelo mod_vda, sum(qtde) qtde_vda from vendas_jde vds left join itens on itens.id = id_item 
    where ult_status not in (980,984) and agrup = '$agrup' 
    group by modelo) as vendas
    on vendas.mod_vda =  modelo

order by fornecedor, agrup, modelo
");


@endphp



<div class="row">

  <div class="col-md-12">

 
    <!-- row -->
    <div class="row">
		<div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
				
				<li  class="active"><a href="#Painel" data-toggle="tab">Painel</a></li>
				
				<li><a href="#Tabela" data-toggle="tab">Tabela</a></li>
				<li><a href="#Grade" data-toggle="tab">Grade</a></li>
				
				<li><a href="#Representantes" data-toggle="tab">Representantes</a></li>
				<li><a href="#Mediasugest" data-toggle="tab">Mediasugest</a></li>
				<li><a href="#Timeline_lancamentos" data-toggle="tab">Timeline_lancamentos</a></li>
				<li><a href="#Estoques" data-toggle="tab">Estoques</a></li>
				<li><a href="#Clientes" data-toggle="tab">Clientes</a></li>
				

				
<div class="tab-content">
	
	
	
<!-- aba geral inicio -->	
<div class="active tab-pane" id="Painel">
	<div class="col-md-12">
		<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
		</ul>
	</div>
</div>
		  
				  
	  

<div class="tab-pane" id="Tabela">
	<div class="col-md-12">		 
		<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
		</ul>
	</div>
</div>
				  
				  

	
<div class="tab-pane" id="Grade">
<!-- The timeline -->
<!-- timeline time label -->
<div class="col-md-12">
<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
</ul>
</div>
</div>
				  
				  
				  
				  

<div class="tab-pane" id="qualidade">
<div class="col-md-12">
<ul class="timeline">
</ul>
</div>
</div>
				
	
			  
				  
<div class="tab-pane" id="apontamentos">
<!-- The timeline -->
<!-- timeline time label -->
<div class="col-md-12">
<!-- The time line -->
<ul class="timeline">
</ul>
</div>
</div>

				  
<div class="tab-pane" id="reprocessos">
<!-- The timeline -->

<!-- timeline time label -->
<div class="col-md-12">

<!-- The time line -->
<ul class="timeline">
</ul>
</div>
</div>
	
	

<div class="tab-pane" id="data_producao">
<!-- The timeline -->

<!-- timeline time label -->
<div class="col-md-12">

<!-- The time line -->
<ul class="timeline">

</ul>
</div>
</div>     	  				  
				  				  				  				  				  				  
</div> 
</div>
</div>		  
</div> 
</div>
</div>

@include('produtos.painel.modal.genero')
@include('produtos.painel.modal.caracteristica')
@stop