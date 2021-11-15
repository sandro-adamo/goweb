@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i> Minha Carteira
@append 

@section('conteudo')



@php
$carteira = array();


  $representantes = Session::get('representantes');
  
  $sql = ' where ';

  if (isset($_GET["cliente"])) {
    $cliente = $_GET["cliente"];
    $sql .= " cod_cli = '$cliente' ";
  } else {
    $cliente = '';
    $sql = '';
  }
 
  if ($representantes == '') {

    $where = '';

  } else {

    $where = ' and rep IN ('.$representantes.') ';

  }

//dd($where);
 
   if (isset($where)) {
  
    $carteira = \DB::select("
		select a_receber.*, concat(ab.id,' - ',ab.fantasia) codcliente,
		case when dt_vencimento < now() then 'VENCIDO' else 'A_VENCER' end as 'status_fin'
		from a_receber 
		left join addressbook ab on ab.id = a_receber.cod_cli
		$sql 
order by codcliente, numero_doc, parcela");
 
  } 
  
echo count($carteira) . ' clientes'.'</br>';

echo $where;
echo $sql;


@endphp


<h6>
<div class="box box-body box-widget">

<table class="table table-bordered table-condensed compact" id="myTable">
  <thead>
    <tr>  
     <th width="30%">pdv</th>
      <th width="10%">num_doc</th>
       <th width="10%">parcela</th>
       <th width="10%">fin_pdv</th>
        <th width="10%">desc_fin</th>
        <th width="10%">dt_emissao_nf</th>
         <th width="10%">dt_vencimento</th>
          <th width="10%">status_fin</th>
           <th width="10%">valor_em_aberto</th>

  
    </tr>
  </thead>

  <tbody>
   @php
            $total_aberto = 0;


	@endphp


    @foreach ($carteira as $cliente) 


	@php

	  $total_aberto += $cliente->valor_em_aberto;
	

	@endphp
      <tr>
        <td>{{$cliente->codcliente}}</td>
         <td align='center'>{{$cliente->numero_doc}}</td>
         <td align='center'>{{$cliente->parcela}}</td>
         <td align='center'>{{$cliente->cod_status_pgt}}</td>
         <td align='center'>{{$cliente->desc_cod_status}}</td>
         <td align='center'>{{$cliente->dt_emissao_nf}}</td>
         <td align='center'>{{$cliente->dt_vencimento}}</td>
         <td align='center'>{{$cliente->status_fin}}</td>
         <td align='center'>{{number_format($cliente->valor_em_aberto, 2, ',', '.')}}</td>
      </tr>

   
    @endforeach
  </tbody>
   <tfoot>
          <tr>
            <th colspan="8">Total</th>
            <th style="text-align: center">{{number_format($total_aberto, 2, ',', '.')}}</th>
          </tr>
        </tfoot>
</table>
</h6>
</div>

@stop