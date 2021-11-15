@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i> Detalhamento das Notas @if (isset($_GET["situacao"])) {{$_GET["situacao"]}} @endif
@append 

@section('conteudo')

@php
    $representantes = Session::get('representantes');
	$ano 			= $_GET["ano"];    
 	$mes 			= $_GET["mes"];    
  	
  	echo 'ano: '.$ano.'</br>';
    echo 'mes: '.$mes.'</br>';
    echo 'rep: '.$representantes;
    
    $where = ' where rep_comissao in ('.$representantes.') ';

   
   
    if (isset($_GET["ano"])) {
    $situacao = $_GET["ano"];
    

    $query1 = \DB::select("
	select num_nf_legal, pedido, data_pedido, data_venda, num_mobile, codcli, razao, desc_status, cond_pag, tabela_desc, 
	case when data_nf = '0001-01-01' then '' else data_nf end as data_nf, 
sum(qtde) qtde, sum(valor) vlr_pedido

from portal_notas
	left join addressbook ab on portal_notas.codcli = ab.id
	$where  
	and year(data_nf) = '$ano' and month(data_nf) = '$mes' 
	-- and desc_status not in ('Backorder', 'cancelado') 
group by num_nf_legal, pedido, data_pedido, data_venda, num_mobile, codcli, razao, desc_status, cond_pag, tabela_desc, data_nf
	order by data_nf desc, num_mobile desc, pedido desc
");
  }

@endphp
<h6>
<div class="row">
  <div class="col-md-12">
    <div class="box box-widget box-body">
      <div class="row">
       
        <div class="col-md-4">
        </div>
      </div>      
      <br>
     <table class="table table-bordered table-condensed compact" id="myTable">
        <thead>
          <tr>
          	<th>NF Legal</th>
			<th>Data Pedido</th>
			<th>Data Venda</th>
			<th>Num Mobile</th>
			<th>Cod Cliente</th>
			<th>Razao Social</th>
			<th>Status Pedido</th>
			<th>Cond Pgto</th>
			<th>Tab desconto</th>
			<th>Vlr pedido</th>
        	<th>Data NF</th>
         
          </tr>
        </thead>
        <tbody> 

          @if ($query1)
        
			  @php

			  $total_ped = 0;

			  @endphp

            @foreach ($query1 as $linha)
              
               @php

              $total_ped += $linha->vlr_pedido;


            @endphp

              <tr>
				<td align="center" class="text-red"><a href="/clientes/pedidos_ped?pedido={{$linha->num_nf_legal}}">{{$linha->num_nf_legal}}</a></td>
                <td>{{$linha->data_pedido}}</td>                
                <td>{{$linha->data_venda}}</td>                
                
                <td>{{$linha->num_mobile}}</td>
                <td>{{$linha->codcli}}</td>
                <td>{{$linha->razao}}</td>
                <td>{{$linha->desc_status}}</td>
          
                <td>{{$linha->cond_pag}}</td>
                <td>{{$linha->tabela_desc}}</td>
                <td>{{number_format($linha->vlr_pedido, 2, ',', '.')}}</td>
                <td>{{$linha->data_nf}}</td>
              </tr>


            @endforeach


          @endif
        </tbody>           
        <td><b>TOTAL</b></td>
         <td></td>
         <td></td>

         <td></td>
         <td></td>
         <td></td>
         <td></td>
         <td></td>
		        <td></td>
         <td><div><b>{{number_format(@$total_ped, 2, ',', '.')}}</b></div></td>
      </table>
      
   
 
 </div>
</div>
	</div>
</h6>
@stop