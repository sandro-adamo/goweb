@extends('produtos/painel/index')

@section('title')
  <i class="fa fa-list"></i> Produtos1
@append 

@section('conteudo')
<div class="row">


@php

	$agrup = $_GET["agrup"];

	$itensagregado1 = 1;
	$itensagregado = 0;

	
	
	if (isset($_GET["colecao"])) {
		$colecao = $_GET["colecao"];
		echo $colecao;
	
	} else {
		$colecao = '';
	echo 'sem colecao';
	
	}
 
	
@endphp


	
	<!-- comeca itens -->

<div class="col-md-12">
	<span class="lead">Lancamentos  <td>    </td> </span>

	<div class="box box-widget">
		<div class="box-header with-border">
			<h3 class="box-title"><i class="fa fa-list"></i>
			
				@php
					if (isset($_GET["view"])) {
						if ($_GET["view"] == 'grade') {
							echo count($gradeslista);
						}
						if ($_GET["view"] == 'painel') {
							echo count($gradeslista);
						}
				
						if ($_GET["view"] == 'tabela') {
							echo count($gradeslista);
						}

					} else {
						echo '';						
					}
				@endphp
				
			 Modelos </h3>
	
			<a href="/gradescoldet_tabela?agrup={{$agrup}}&colecao={{$colecao}}&view=tabela" class="btn btn-flat btn-default btn-sm pull-right" @if (isset($_GET["view"]) && $_GET["view"] == 'tabela') disabled 
			@endif>Tabela</a>
			
			<a href="/gradescoldet_grade?agrup={{$agrup}}&colecao={{$colecao}}&view=lista" class="btn btn-flat btn-default btn-sm pull-right" @if (isset($_GET["view"]) && $_GET["view"] == 'grade') disabled 
			@endif>Grade</a>
			
			<a href="/gradescoldet_painel?agrup={{$agrup}}&colecao={{$colecao}}&view=lista" class="btn btn-flat btn-default btn-sm pull-right" @if (isset($_GET["view"]) && $_GET["view"] == 'painel') disabled 
			@endif>Painel</a>

			
		</div>
		<div class="box-body">      

			
			@if (isset($_GET["view"]))
				
				@if ($_GET["view"] == 'grade')
					@include('produtos.grades.gradescoldet_grade')
				@endif
				
				@if ($_GET["view"] == 'painel')
					@include('produtos.grades.gradescoldet_painel')
				@endif

				@if ($_GET["view"] == 'tabela')
					@include('produtos.grades.gradescoldet_tabela')
				@endif

			@else
				@include('produtos.grades.gradescoldet_painel')
			@endif
		</div>

	</div>

</div>






@stop