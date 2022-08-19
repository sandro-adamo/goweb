@extends('layout.principal')

@php

 $representantes = Session::get('representantes');
 $grifes = Session::get('grifes');
 $codgrife = $_GET["codgrife"];

 

@endphp

@section('title')
<i class="fa fa-suitcase"></i> {{$codgrife}}   
@append 

@section('conteudo')

<form action="" method="get"> 
@php

	
$status1 = $_GET["statuscli"];

echo $status1;
	
	
	if ($status1 == "Nao_Fidelizados"){$where = "where (v365 > 0 and v180 = 0)";}	
	elseif ($status1 == "A_Recuperar"){$where = "where (vtotal > 0 and v365 = 0)";}	
	else {}
	
	
	echo $where;
	
	
						
$query_1 = \DB::select("						


select *, case when v7 > 0 then v7 when v30 > 0 then v30 when v120 > 0 then v120 when v180 > 0 then v180 when v365 > 0 then v365 else vtotal end as qtde from (
		select cod_cliente, cliente, codgrife,sum(v7) v7,sum(v30) v30, sum(v120) v120, sum(v180) v180, sum(v365) v365, sum(vtotal) vtotal,
        sum(pnovo_7dd) pnovo_7dd, sum(pnovo_30dd) pnovo_30dd, sum(pnovo_120dd) pnovo_120dd, sum(pnovo_180dd) pnovo_180dd, sum(pfidelizados) pfidelizados,
        sum(pn_fidelizados) pn_fidelizados, sum(precuperados) precuperados, sum(pa_recuperar) pa_recuperar, max(ult_compra) ult_compra from (
        
			select cod_cliente, cliente, codgrife, codcli, sum(v7) v7,sum(v30) v30, sum(v120) v120, sum(v180) v180, sum(v365) v365, sum(vtotal) vtotal,
					case when v7 > 0 and vtotal = v7 then 1 else 0 end as pnovo_7dd,
					case when v30 > v7 and vtotal = v30 then 1 else 0 end as pnovo_30dd,
                    case when v120 > v30 and vtotal = v120 then 1 else 0 end as pnovo_120dd,			
					case when v180 > v120 and vtotal = v180 then 1 else 0 end as pnovo_180dd,
			
					case when v180 > 0 and v365 > v180 then 1 else 0 end as pfidelizados,
					case when v365 > 0 and v180 = 0 then 1 else 0 end as pn_fidelizados,
					case when v180 = v365 and v180 < vtotal and v180 > 0 then 1 else 0 end as precuperados, 
					case when vtotal > 0 and v365 = 0 then 1 else 0 end as pa_recuperar,
					max(ult_compra) ult_compra
	
			from ds_carteira cart
			where rep_carteira in ($representantes)  and flag_cadastro <> '1 - desativado' and codgrife = '$codgrife'
            
			group by cod_cliente, cliente, codgrife, codcli , v7, v30, v120, v180, v365, vtotal
            
		) as fim0 
	
	group by cod_cliente, cliente, codgrife
) as fim1  $where 

");
			
	


echo ' - '. count($query_1); 
	
	
@endphp


<div class="row">

		<div class="col-md-8">
			<div class="box box-widget box-body">
				<div class="table-responsive">

				<table class="table table-bordered" id="example3">
				<thead>
				<tr>	
				<td colspan="10">CLIENTES QUE COMPRARAM A GRIFE {{$codgrife}} NO PERIODO ( {{$status1}} )</td>

				</tr>

				<tr>	

				<td>form</td>
				<td colspan="1" align="center">clientes</td>				
				<td colspan="1" align="center">pdvs ativos</td>
				<td colspan="1" align="center">pdvs inad</td>
				<td colspan="1" align="center">qtde pcs no periodo de {{$codgrife}}</td>
				<td colspan="1" align="center">ult data que comprou {{$codgrife}}</td>
				<td colspan="1" align="center">regiao</td>
				<td colspan="1" align="center">municipios</td>

				</thead>	
				</tr>


				@foreach ($query_1 as $query1)

				<tr>
				<td><a href="/cliente_form?cli={{$query1->cliente}}"><i class="fa fa-file"></i> </a></td>		
				<td align="left"><a href="/det_subgrupo?pdv={{$query1->cod_cliente}}&codgrife={{$query1->cliente}}">{{$query1->cliente}}</a></td>
				<td></td>
					<td></td><td>{{number_format($query1->qtde,0)}}</td>
					<td>{{$query1->ult_compra}}</td><td></td><td></td>
				</tr>
				@endforeach 

				</table>
				</div>
			</div>
		</div>
</div>	
	
	
	
</form>
</h6>



@stop




