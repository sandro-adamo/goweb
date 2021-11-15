@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i> 
@append 

@section('conteudo')


@php
  
  $representantes = Session::get('representantes');
  
  $sql = ' where ';

  if (isset($_GET["cliente"])) {
    $cliente = $_GET["cliente"];
    $sql .= " cliente = '$cliente' ";
  } else {
    $cliente = '';
    $sql = '';
    
    
    
  }
 
  if ($representantes == '') {

    $where = '';

  } else {

    $where = ' and rep IN ('.$representantes.') ';

  }

echo $cliente;

  $grifes_cli = \DB::select("
select cliente, grife, agrup, sum(a2017) a2017, sum(a2018) a2018 from (
	select cliente,  ab.id cli, cc.* ,
    case when ano = '2017' then qtde else 0 end as 'a2017',
    case when ano = '2018' then qtde else 0 end as 'a2018'
    
	from concorrentes cc
	left join addressbook ab on ab.id = cc.an8
    where cliente = '$cliente'
    ) as fim
    
    group by cliente, grife, agrup
  ");


$grifes_pdv = \DB::select("
select cliente, cli, fantasia, grife, agrup, sum(a2017) a2017, sum(a2018) a2018 from (
	select cliente, fantasia, ab.id cli, cc.* ,
    case when ano = '2017' then qtde else 0 end as 'a2017',
    case when ano = '2018' then qtde else 0 end as 'a2018'
    
	from concorrentes cc
	left join addressbook ab on ab.id = cc.an8
    where cliente = '$cliente'
    ) as fim
    
    group by cliente, cli, fantasia, grife, agrup
  ");


@endphp



<h6>
<div class="row">
  <div class="col-md-4">
    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title">Cliente - Subgrupo</h3>
      </div>
      <div class="box-body">
        <table class="table table-bordered table-condensed compact" id="myTable">
          <tr>
             <tbody>
            <th>grife</th>
            <th>agrup</th>
            <th>2017</th>
            <th>2018</th>
          </tr>
    @php
            $total_2017 = 0;
            $total_2018 = 0;
	@endphp
    
                 @foreach ($grifes_cli as $pdv)
                 
@php

	  $total_2017 += $pdv->a2017;
	   $total_2018 += $pdv->a2018;

	@endphp                 
            <tr>
             
              <td align="center">{{$pdv->grife}}</td>
              <td align="center">{{$pdv->agrup}}</td>
              <td align="center">{{$pdv->a2017}}</td>
              <td align="center">{{$pdv->a2018}}</td>
             </tr>              

          @endforeach
				<tfoot>
					<tr>
					<th colspan="2">Total</th>
					<th style="text-align: center">{{number_format($total_2017, 0, ',', '.')}}</th>
					<th style="text-align: center">{{number_format($total_2018, 0, ',', '.')}}</th>
					</tr>
				</tfoot>
        </table>
       
          </tbody>

      </div>
    </div>
  </div>
  

<div class="col-md-7">
    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title">PDVS</h3>
      </div>
      <div class="box-body">
        <table class="table table-bordered table-condensed compact" id="myTable">
          <tr>
             <tbody>
            <th>codcli</th>
             <th>fantasia</th>
            <th>grife</th>
            <th>agrup</th>
            <th>2017</th>
            <th>2018</th>
          </tr>
    @php
            $total_2017 = 0;
            $total_2018 = 0;
	@endphp
    
                 @foreach ($grifes_pdv as $pdv1)
                 
@php

	  $total_2017 += $pdv1->a2017;
	   $total_2018 += $pdv1->a2018;

	@endphp                 
            <tr>
             <td>{{$pdv1->cli}}</td>
             <td>{{$pdv1->fantasia}}</td>
              <td align="center">{{$pdv1->grife}}</td>
              <td align="center">{{$pdv1->agrup}}</td>
              <td align="center">{{$pdv1->a2017}}</td>
              <td align="center">{{$pdv1->a2018}}</td>
             </tr>              

          @endforeach
				<tfoot>
					<tr>
					<th colspan="4">Total</th>
					<th style="text-align: center">{{number_format($total_2017, 0, ',', '.')}}</th>
					<th style="text-align: center">{{number_format($total_2018, 0, ',', '.')}}</th>
					</tr>
				</tfoot>
        </table>
       
          </tbody>

      </div>
    </div>
  </div>  
</div>
</h6>
@stop