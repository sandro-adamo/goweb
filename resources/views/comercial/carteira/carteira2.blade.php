
@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i> Minha Carteira
@append 

@section('conteudo')


@php
//$carteira = array();


  $representantes = Session::get('representantes');
  $grifes = Session::get('grifes');

	echo $representantes;
	echo $grifes;

 
  if ($representantes == '') {

    $where = '';

  } else {

    $where = ' where rep IN ('.$representantes.') ';

  }
	$sql_cliente = ''; 
  if (isset($_GET["q"])) {
  	$cli = $_GET["q"];
  
	$sql_cliente = " and cliente like '%$cli%' ";
echo $sql_cliente;
  }
 


   if (isset($where)) {
    $carteira = \DB::select("
		select cliente, ab.grupo, subgrupo, id, razao, fantasia, uf, municipio, financeiro, 0 as a2017
		from (
			select distinct cli	from carteira

			$where $sql_cliente 
		
		) as base

		left join (select * from addressbook ) as ab
		on ab.id = base.cli

		order by cliente, uf, municipio 

");
 
  } 
  
//echo count($carteira) . ' clientes'.'</br>';

echo 'where'.$where.'</br>';
echo 'sql_cliente'.$sql_cliente;

@endphp


<h6>
<div class="box box-body box-widget">
uuuuuuu
<table class="table table-bordered table-condensed compact" id="myTable">
  <thead>
    <tr>
     <th width="5%">Det</th>
      <th width="20%">CLIENTE</th>
	  <th width="20%">GRUPO</th>
	  <th width="20%">SUBGRUPO</th>
	  <th width="10%">UF</th>
	  <th width="10%">MUNICIPIO</th>
		 <th width="10%">FANTASIA</th>
    </tr>
  </thead>

  <tbody>
   
	@php
            $total_2017 = 0;

	@endphp


    @foreach ($carteira as $cliente) 


	@php

	  $total_2017 += $cliente->a2017;
	  
	  
if ($cliente->a2017 >= 0)
{ $icone = '<i class="fa fa-file-text-o text-green"></i>';} 
	else  { $icone = '<i class="fa fa-file-text-o text-red"></i>';}

	@endphp
   
   

      <tr>
	<td> 
	 <a href="/carteira/cart_detcli?cliente={{$cliente->cliente}}"><i class="fa fa-users"></i></a> 
	 <a href="/carteira/fin_cli?cliente={{$cliente->cliente}}"><i class="fa fa-file-text-o"></i></a>
  	 <a href="/carteira/ficha?cliente={{$cliente->cliente}}">{!!$icone!!}</a>
    </td>
       
        <td>{{$cliente->cliente}}</td>
		<td>{{$cliente->grupo}}</td>
		  <td>{{$cliente->subgrupo}}</td>
		  <td>{{$cliente->uf}}</td>
		  <td>{{$cliente->municipio}}</td>
		  <td>{{$cliente->fantasia}}</td>
      </tr>

    @endforeach
  </tbody>
        <tfoot>
          <tr>
            <th colspan="2">Total</th>
            <th style="text-align: center">{{number_format($total_2017, 0, ',', '.')}}</th>
          </tr>
        </tfoot>
  
</table>
</h6>
</div>

@stop