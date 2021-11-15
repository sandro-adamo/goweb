@extends('layout.principal')

@section('title')
<i class="fa fa-dashboard"></i> Dashboard
@append 

@section('conteudo')


@php
$sql = '';

  $representantes = Session::get('representantes');
  $grifes = Session::get('grifes');
	$ano2 = $_GET["ano"];	
	$mes2 = $_GET["mes"];	

@endphp

  
@php

     $query = \DB::select("
		select ab.cod_cliente, vendas.pedido, dt_emissao, ab.fantasia, suspensoes.suspensao, cart.regiao,
		case when abr.nome = '' then abr.fantasia else abr.nome end as rep, sum(valor) as bloqueados

		from vendas_jde vendas
		left join suspensoes on vendas.pedido = suspensoes.pedido and suspensoes.tipo = vendas.tipo
		left join addressbook ab on ab.id = vendas.id_cliente
        left join addressbook abr on abr.id = vendas.id_rep
		left join carteira cart on cart.cli = ab.id and grife = vendas.cod_grife and status = 1
        
		
		where id_rep in ($representantes) and 
		ult_status not in ('980','984') and suspensoes.codigo is not null and ano = $ano2
		and mes = $mes2 and tipo_item = 006 

		group by ab.cod_cliente, vendas.pedido, dt_emissao , ab.fantasia, suspensoes.suspensao, abr.nome, abr.fantasia, cart.regiao ");
	  
@endphp

<form action="" method="get">    
<div class="row">
  <div class="col-md-3">


      <div class="box-body">
        <div class="row">
       BLOQUEADOS DO MES 
          
        </div>
        <h6>
       <table class="table table-responsive" id="example1">
				<thead>
          <tr>
            <th colspan="1">Pedido</th>
			   <th colspan="1">emissao</th>
			   <th colspan="1">fantasia</th>
			   <th colspan="1">suspensao</th>
			  <th colspan="1">valor</th>
			  <th colspan="1">repres</th>
			   <th colspan="1">regiao</th>
       </thead>
          </tr>
          @foreach ($query as $a)

<tr>
	<td>{{$a->pedido}}</td>
	<td>{{$a->dt_emissao}}</td>
	<td><a href="{{$a->cod_cliente}}">{{$a->fantasia}}</a></td>
	<td>{{$a->suspensao}}</td>
	<td>{{$a->bloqueados}}</td>  
	<td>{{$a->rep}}</td>
	<td>{{$a->regiao}}</td>
</tr>


          @endforeach 
        </table>
        </h6>
      </div>
    </div>
</div>
</form>
@stop