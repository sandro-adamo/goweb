@extends('layout.principal')


@php 

$representantes = Session::get('representantes');
$grifes = Session::get('grifes');

$pedido = $_GET["pedido"];	
$cliente = $_GET["cliente"];	
$grife = $_GET["grife"];
 
$cli = \DB::select(" select concat(razao,' - ',fantasia) nome from addressbook where id = '$cliente' ");


@endphp



@section('title')
<i class="fa fa-group"></i> {{$cli[0]->nome}}
@append 







@section('conteudo')
			  
			  
@php

$pedido_pecas = \DB::select("


select cod_grife, agrup, pedido, dt_venda, dt_emissao, 
sum(qtde) qtde

from pedidos_jde ped 
left join itens on itens.id = id_item
where ped.tipo_item in ('006') and  datediff(now(),dt_venda) <=365
and id_cliente = '$cliente' and  codgrife = '$grife'
and ult_status not in (980,984)

group by cod_grife, agrup, pedido, dt_venda, dt_emissao
 order by dt_emissao desc
																  
 ");


$pedido_mpdv = \DB::select("
select cod_grife, agrup, pedido, dt_venda, dt_emissao, ult_status, secundario,
sum(qtde) qtde

from pedidos_jde ped 
left join itens on itens.id = id_item
where ped.tipo_item in ('004') and  datediff(now(),dt_venda) <=365
and id_cliente = '$cliente' and  codgrife = '$grife'
and ult_status not in (980,984)

group by cod_grife, agrup, pedido, dt_venda, dt_emissao, secundario, ult_status
order by dt_emissao desc

");		  
		  		  
		  
@endphp
			  
			  
	



          <div class="row">
			<div class="box box-body box-widget">
				
				
				
             <div class="col-md-6">
	 	      <div class="table-responsive">
		

              <table class="table table-bordered">


                <thead>
		<tr>	
			<td colspan="6" align="center">Pedidos de pecas emitidos nos ultimos 12 meses</td>
		</tr>
                  <tr>
				    <th>grife</th>
                    <th>agrup</th>
                    <th>pedido</th>
                    <th>dt_emissao</th>
					<th>dt_venda</th>
					<th>qtde</th>
            
                  </tr>                
                </thead>
                <tbody>
                @foreach ($pedido_pecas as $pedidos_pecas)
                    <tr>
                      <td align="center">{{$pedidos_pecas->cod_grife}}</td>
					  <td align="center">{{$pedidos_pecas->agrup}}</td>
                      <td align="center">{{$pedidos_pecas->pedido}}</td>
                      <td align="center">{{$pedidos_pecas->dt_emissao}}</td>
					  <td align="center">{{$pedidos_pecas->dt_venda}}</td>
					  <td align="center">{{$pedidos_pecas->qtde}}</td>
                    </tr>
                @endforeach
                </tbody>
              </table>
            </div>
            </div>

            <div class="col-md-5">
              <div class="table-responsive">
		
              <table class="table table-bordered">

                <thead>
	<tr>	
			<td colspan="5" align="center">Pedidos de mpdvs nos ultimos 12 meses</td>
		</tr>
                  <tr>
				    <th>grife</th>
                    <th>item</th>
					   <th>pedido</th>
                    <th>data</th>
					   <th>status</th>
                    <th>qtde</th>
					  <th>foto</th>
					  
              
  
                  </tr>                
                </thead>
                <tbody>
                @foreach ($pedido_mpdv as $pedidos_mpdv)
                    <tr>
					  <td align="center">{{$pedidos_mpdv->cod_grife}} </td>
					  <td align="center">{{$pedidos_mpdv->secundario}}</td>
						 <td align="center">{{$pedidos_mpdv->pedido}}</td>
							 <td align="center">{{$pedidos_mpdv->dt_emissao}}</td>
						 <td align="center">{{$pedidos_mpdv->ult_status}}</td>
						 <td align="center">{{$pedidos_mpdv->qtde}}</td>
                 
                     
                    </tr>
                @endforeach
                </tbody>
              </table>
            </div>
            </div>

      
         </div>
          </div>

		  
		  
		  
		  
		  
		  
</form>
@stop