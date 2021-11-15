@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i> Detalhamento das Vendas @if (isset($_GET["situacao"])) {{$_GET["situacao"]}} @endif
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



 



     select pedido, rep_capa, rep_comissao, data_venda, num_mobile, codcli, razao, financeiro, cond_pag, tabela_desc, sum(vlr_pedido) vlr_pedido, sum(vlr_backorder) vlr_backorder from (
     
        select pedido, rep_capa, rep_comissao, data_venda, num_mobile, codcli, razao, financeiro, cond_pag, tabela_desc, desc_status, 
        case when desc_status = 'SO Gerado' then valor else 0 end as vlr_pedido,
        case when desc_status <> 'SO Gerado' then valor else 0 end as vlr_backorder
        
			from portal_vendas 
			left join addressbook ab on portal_vendas.codcli = ab.id
			
		$where  and year(data_venda) = '$ano' and month(data_venda) = '$mes' and desc_status not in ('cancelado')
		
        ) as fim
        
         group by pedido, rep_capa, rep_comissao, data_venda, num_mobile, codcli, razao ,  financeiro, cond_pag, tabela_desc

   order by data_venda desc, num_mobile desc
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
          	<th>Pre-Pedido JDE</th>
			<th>Data Venda</th>
		
			<th>Num Mobile</th>
			<th>Cod Cliente</th>
			<th>Razao Social</th>
			<th>Financeiro</th>
			
			<th>Cond Pgto</th>
			<th>Tab desconto</th>
			<th>Vlr pedido</th>
       		<th>Vlr backorder</th>
         
          </tr>
        </thead>
        <tbody> 

          @if ($query1)
        
			  @php

			  $total_ped = 0;
			  $total_bac = 0;

			  @endphp

            @foreach ($query1 as $linha)
              
               @php

              $total_ped += $linha->vlr_pedido;
              $total_bac += $linha->vlr_backorder;


            @endphp

              <tr>
				<td align="center" class="text-red"><a href="/clientes/vendas_ped?pedido={{$linha->pedido}}">{{$linha->pedido}}</a></td>
                <td>{{$linha->data_venda}}</td>                
                <td>{{$linha->num_mobile}}</td>
                <td>{{$linha->codcli}}</td>
                <td>{{$linha->razao}}</td>
     
                <td>{{$linha->financeiro}}</td>
          
                <td>{{$linha->cond_pag}}</td>
                <td>{{$linha->tabela_desc}}</td>
                <td>{{number_format($linha->vlr_pedido, 2, ',', '.')}}</td>
                <td>{{number_format($linha->vlr_backorder, 2, ',', '.')}}</td>
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
 
		  <td><div><b>{{number_format(@$total_ped+$total_bac, 2, ',', '.')}}</b></div></td>
         <td><div><b>{{number_format(@$total_ped, 2, ',', '.')}}</b></div></td>
         <td><div><b>{{number_format(@$total_bac, 2, ',', '.')}}</b></div></td>
      </table>
      
   
 
 </div>
</div>
</h6>
@stop