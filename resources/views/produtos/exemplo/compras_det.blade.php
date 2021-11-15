@extends('produtos/painel/index')

@section('title')
<i class="fa fa-list"></i> Novo
@append 

@section('conteudo')

@php

$item = $_GET["item"];


$query = DB::select("
select pedido, modelo_go, cod_sec
,(select qtd_pedido from producoes_hist b where b.pedido = a.pedido and b.cod_sec = a.cod_sec order by timestamp desc limit 1) qtd_pedido
,(select dt_entrega from producoes_hist b where b.pedido = a.pedido and b.cod_sec = a.cod_sec order by timestamp desc limit 1) ult_dt_entrega
,(select qtd_enviada from producoes_hist b where b.pedido = a.pedido and b.cod_sec = a.cod_sec order by timestamp desc limit 1) qtd_enviada
,(select status from producoes_hist b where b.pedido = a.pedido and b.cod_sec = a.cod_sec order by timestamp desc limit 1) status
from producoes_hist a 
where cod_sec = '$item'
and pedido not in ('FPBFE18124607','GPBFE19034608','FPBFE18124605','FIBEK18063022','GIBEK18073026','FIBEK18063025','GIBEK18073029','GPBFG19066168' )
group by pedido, modelo_go, cod_sec");


$query2 = DB::select("
	
select timestamp, pedido, modelo_go, cod_sec, qtd_pedido, dt_entrega, qtd_enviada, status
from producoes_hist a 
where cod_sec = '$item'

order by pedido desc, timestamp desc, cod_sec asc");


/**echo $query[0]->colmod;**/

@endphp

 <td id="foto" align="center" style="min-height:60px;">
               
                
                <a href="" class="zoom" data-value="{{ $query[0]->cod_sec }}"><img src="https://portal.goeyewear.com.br/teste999.php?referencia={{ $query[0]->cod_sec }}" style="max-height: 120px;" class="img-responsive"></a>
              </td>

              <td><h3>
              <?php echo $item?></h3></td>
<div class="col-md-12">

 
  <div class="row" >       
    <div class="col-md-5">
      <div class="box box-widget">

        			
			<div class="box-header with-border" align="center">
          <h3 class="box-title" align="center">
                <b>Todos os pedidos
			</br>Orders all</b>
          </h3> 
        </div>         

        <div class="box-body">

          

          <table class="box-body table-responsive table-striped">
            <tr align="center">
              
				
			  
			  <td align="center" width="3%" align="center"><b>Pedido </b></td>
              <td align="center" width="3%"><b>Qtd Pedido </b></td>
              <td align="center" width="3%"><b>Enviada </b></td>
              <td align="center" width="3%"><b>Dt entrega </b></td>
				<td align="center" width="3%"><b>Status </b></td>
				
              </tr>
			   <tr align="center">
              
			  
			  
			 
              <td align="center" width="3%" ><b>Orders</b></td>
              <td align="center" width="15%"><b>Qtt orders</b></td>
              <td align="center" width="15%"><b>Qtt Ship</b></td>
              <td align="center" width="20%"><b>Delivery date</b></td>
			  <td align="center" width="20%"><b>Status</b></td>
				
              </tr>

            @php  
            $totala = 0;
            $totalb = 0;
            
            @endphp



            @foreach ($query as $dados) 

            @php 	
            $totala += $dados->qtd_pedido;
            $totalb += $dados->qtd_enviada;
            
            @endphp


            <tr>

             
          
              <td align="center"><small>{{ $dados->pedido }}</small></td>
              <td align="center"><small>{{ number_format($dados->qtd_pedido,0) }}</small></td>
              <td align="center"><small>{{ number_format($dados->qtd_enviada,0) }}</small></td>
              <td align="center"><small>{{ $dados->ult_dt_entrega }}</small></td>
				<td align="center"><small>{{ $dados->status }}</small></td>
              

            </tr>

            @endforeach
            <tr style="text-align: center; font-weight: bold;">
              <td></td>
              
              
              <td width="7%"><b>TOTAL</b></td>
              <td width="3%" align="center">{{$totala}}</td>
              <td width="3%" align="center">{{$totalb}}</td>
              <td></td>
              
            </tr>
          </table>

        </div>	


      </div>
    </div>

  
  
  
  
  
  
  
  
  
  
  
  
  

 
  <div class="row" >       
    <div class="col-md-5">
      <div class="box box-widget">

        <div class="box-header with-border" align="center">
          <h3 class="box-title" align="center">
                <b>Pedidos semana a semana
			</br>Orders week by week</b>
          </h3> 
        </div>         

        <div class="box-body">

          

          <table class="box-body table-responsive table-striped">
            <tr align="center">
              
				
			 
			  <td align="center" width="3%"><b>Dt_relatorio</b> </td>
              <td align="center" width="3%" align="center"><b>Pedido </b></td>
              <td align="center" width="3%"><b>Qtd Pedido </b></td>
              <td align="center" width="3%"><b>Enviada </b></td>
              <td align="center" width="3%"><b>Dt entrega </b></td>
			  <td align="center" width="3%"><b>Status </b></td>
				
              </tr>
			   <tr align="center">
              
			  
			  
			  <td align="center" width="15%"><b>Report date</b></td>
              <td align="center" width="3%" ><b>Orders</b></td>
              <td align="center" width="15%"><b>Qtt orders</b></td>
              <td align="center" width="15%"><b>Qtt Ship</b></td>
              <td align="center" width="20%"><b>Delivery date</b></td>
			  <td align="center" width="20%"><b>Delivery date</b></td>
				
              </tr>
			  
			  

            @php  
            $totala = 0;
            $totalb = 0;
            
            @endphp



            @foreach ($query2 as $dados2) 

            @php 	
            $totala += $dados->qtd_pedido;
            $totalb += $dados->qtd_enviada;
            
            @endphp


            <tr>

              

              
               <td align="center"><small>{{ substr($dados2->timestamp,0, -9) }}</small></td>            
              <td align="center"><small>{{ $dados2->pedido }}</small></td>
              <td align="center"><small>{{ number_format($dados2->qtd_pedido,0) }}</small></td>
              <td align="center"><small>{{ number_format($dados2->qtd_enviada,0) }}</small></td>
              <td align="center"><small>{{ $dados2->dt_entrega }}</small></td>
				<td align="center"><small>{{ $dados2->status }}</small></td>
              

            </tr>

            @endforeach
<!--
            <tr style="text-align: center; font-weight: bold;">
              <td></td>
              <td></td>
              
              <td width="7%"><b>TOTAL</b></td>
              <td width="3%" align="center">{{$totala}}</td>
              <td width="3%" align="center">{{$totalb}}</td>
              <td></td>
-->
              
            </tr>
          </table>

        </div>	


      </div>
    </div>
  </div>


</div>

  @stop