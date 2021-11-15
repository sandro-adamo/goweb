@extends('layout.principal')

@section('title')
<i class="fa fa-dashboard"></i> Dashboard
@append 

@section('conteudo')


@php
$sql = '';

  $representantes = Session::get('representantes');
  $grifes = Session::get('grifes');
	$ano = $_GET["ano"];	
	$mes = $_GET["mes"];	

@endphp

  
@php
     

     $query = \DB::select("
		select dev.pedido, dev.dt_emissao, dev.id_cliente, fantasia, razao, sum(dev.qtde)*-1 qtde_dev, sum(dev.valor)*-1 valor_dev
		from devolucoes dev
		left join pedidos_jde ped on ped.pedido = dev.ped_original and ped.linha = dev.linha_original and dev.tipo_original = ped.tipo
		left join addressbook ab on ab.id = dev.id_cliente 
        
		where dev.tipo_original = 'so'
		and ped.id_rep in ($representantes)
		and year(dev.dt_emissao) = $ano
		and month(dev.dt_emissao) = $mes
		and tipo_item = 006
        group by dev.pedido, dev.dt_emissao, dev.id_cliente, fantasia, razao "
	  );
	  
    @endphp

   
<div class="row">
  <div class="col-md-6">


      <div class="box-body">
        <div class="row">
       DEVOLUCOES DO MES 
          
        </div>
        <h6>
        <table class="table table-bordered table-condensed">
          <tr>
            <th colspan="1">Pedido</th>
			   <th colspan="1">dt_emissao</th>
			   <th colspan="1">codcli</th>
			   <th colspan="1">fantasia</th>
			   <th colspan="1">razao social</th>
			   <th colspan="1">qtde</th>
			  <th colspan="1">valor</th>
       
          </tr>
          @foreach ($query as $a)

            <tr>
<td>{{$a->pedido}}</td>
<td>{{$a->dt_emissao}}</td>
<td>{{$a->id_cliente}}</td>
<td>{{$a->fantasia}}</td>
<td>{{$a->razao}}</td>
<td>{{$a->qtde_dev}}</td>
<td>{{$a->valor_dev}}</td>
             
            </tr>


          @endforeach 
        </table>
        </h6>
      </div>
    </div>

</div>

@stop