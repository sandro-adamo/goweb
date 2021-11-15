@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i> Detalhamento do Pre-Pedido @if (isset($_GET["pedido"])) {{$_GET["pedido"]}} @endif
@append 

@section('conteudo')

@php
    $representantes = Session::get('representantes');
	$pedido 			= $_GET["pedido"];    
 	
  	
  	echo 'pedido: '.$pedido.'</br>';
    
    echo 'rep: '.$representantes;
    
    $where = ' where rep_comissao in ('.$representantes.') ';

   
   
    if (isset($_GET["pedido"])) {
    $pedido = $_GET["pedido"];
    

    $query1 = \DB::select("
select * from portal_pedidos  where pedido_original = '$pedido' order by pedido  ");
 
  }

@endphp
<h6>
<div class="row">
  <div class="col-md-12">
    <div class="box box-widget box-body">
      <div class="row">
        <div class="col-md-6">
          <input type="text" name="pesquisa" class="form-control">
        </div>
        <div class="col-md-2">
          <button class="btn btn-default btn-flat"><i class="fa fa-search"></i> Pesquisar</button>
        </div>
        <div class="col-md-4">
        </div>
      </div>      
      <br>
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
			<th>Pedido Gerado</th>
        	<th>Grife</th>
        	<th>desc_status</th>
        	<th>num_nf_legal</th>
			<th>titulo</th>
        	<th>Qtde</th>
        	<th>Valor</th>
         
          </tr>
        </thead>
        <tbody> 

          @if ($query1)
        
			  @php

			  $qtde_ped = 0;
			   $total_ped = 0;
			 

			  @endphp

            @foreach ($query1 as $linha)
              
               @php

              $qtde_ped += $linha->qtde;
               $total_ped += $linha->valor;
              


            @endphp

              <tr>
 
              <td>{{$linha->pedido}}</td>
              <td>{{$linha->grife}}</td>
              <td>{{$linha->desc_status}}</td>
              <td>{{$linha->num_nf_legal}}</td>
			  <td>{{$linha->titulo}}</td>
              <td>{{$linha->qtde}}</td>
              <td>{{$linha->valor}}</td>
              
              </tr>


            @endforeach


          @endif
        </tbody>           
        <td><b>TOTAL</b></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><div>{{number_format(@$qtde_ped, 0, ',', '.')}}</div></td>
         <td><div>{{number_format(@$total_ped, 2, ',', '.')}}</div></td>
      </table>
      
   
 
 </div>
</div>
</h6>
@stop