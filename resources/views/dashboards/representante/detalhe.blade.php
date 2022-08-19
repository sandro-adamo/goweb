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


$repres = \DB::select("
select * from addressbook where fantasia like 'm ilton%'

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
			
			painel
			
			
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