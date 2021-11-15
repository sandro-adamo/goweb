@extends('layout.principal')

@php

 $representantes = Session::get('representantes');
 $grifes = Session::get('grifes');
 $codgrife = $_GET["codgrife"];
 $dias  = $_GET["dias"];

@endphp

@section('title')
<i class="fa fa-suitcase"></i> {{$codgrife}} -  {{$dias}}
@append 

@section('conteudo')

<form action="" method="get"> 
@php

	/**

$status = $_GET["status"];
$clasmod = $_GET["clasmod"];
	
	
	
	if($status == 'a - mantem mala') { $status_ajust = "and statusatual not in ('ESGOTADO','em producao') and ultstatus not in ('ESGOTADO','em producao')"; } 
	elseif ($status == 'b - retornou') {$status_ajust = "and statusatual not in ('ESGOTADO','em producao') and ultstatus in ('ESGOTADO','em producao')"; }
	elseif ($status == 'c - mantem fora') {$status_ajust = "and statusatual in ('ESGOTADO','em producao') and ultstatus in ('ESGOTADO','em producao')"; }
	elseif ($status == 'd - retira') {$status_ajust = "and statusatual in ('ESGOTADO','em producao') and ultstatus not in ('ESGOTADO','em producao')"; }
	elseif ($status == 'disp') {$status_ajust = "and statusatual not in ('ESGOTADO','em producao')"; }
	else {}
	



	
	if($clasmod == 'a') { $and = "and clasmod in ('NOVO','LINHA A++','LINHA A+','LINHA A','EM ANALISE')" ;} 
	elseif ($clasmod == 'b') { $and = "and clasmod in ('LINHA A-')" ;}
	elseif ($clasmod == '') { $and = "and clasmod in ('NOVO','LINHA A++','LINHA A+','LINHA A','LINHA A-', 'EM ANALISE')" ;} 
	
	else {$and = "and 1=1"; }
	

	
  $where1 = ' where id_rep in ('.$representantes.') ';
  $where2 = ' where rep_comissao in ('.$representantes.') ';

 echo $agrup; 
 echo $status_ajust;
echo $clasmod; 
echo $and;
	
**/
	

$query_1 = \DB::select("
 

select cliente,  cart, pdv, sum(qtde) qtde, sum(pdvs) pdvs, sum(pdvs_inad) pdvs_inad
from (
	
 select cliente,  data_recente, cart,  max(id_cliente) pdv, sum(qtde) qtde , count(id_cliente) pdvs,
 case when financeiro > 0 then count(id_cliente) else 0 end as pdvs_inad
 from (
    
		select codgrife, cliente, id_cliente, data_recente, dias_compra, cart, 
        case when financeiro in ('ju','in') then 1 else 0 end as financeiro,
        
		case 
		 when dias_compra between 0 and 90 then 'a0a90' 
         when dias_compra between 91 and 180 then 'b91a180'
		 when dias_compra between 181 and 270 then 'c181a270'
		 when dias_compra between 271 and 365 then 'd271a365' else 'erro' end as dias,
         qtde
         
		from (

			select *, 
                datediff(now(),
                (select max(dt_venda) from vendas_jdes vds1 left join addressbook ab1 on ab1.id = vds1.id_cliente
                where vds1.codgrife = fim1.codgrife 
                and ab1.cliente = fim1.cliente and ult_status not in ('980','984') and codgrife not in ('EP1','EP2','EP3','EP4','EP5') )) dias_compra,
                
				(select min(dt_venda) from vendas_jdes vds1 left join addressbook ab1 on ab1.id = vds1.id_cliente
                where vds1.codgrife = fim1.codgrife and ab1.cliente = fim1.cliente 
                and ult_status not in ('980','984') and codgrife not in ('EP1','EP2','EP3','EP4','EP5'))  data_recente
                                                
                from (
                
                    select financeiro,
                    case when codgrife in ('go','at') then 'AT' when codgrife in ('EV','NG') then 'EV' else codgrife end as codgrife, id_cliente, cliente, 
					(select regiao from carteira where -- rep in ($representantes) and 
                    carteira.grife = vds.codgrife and cli = id_cliente and status = 1 limit 1) cart, 
                    sum(qtde) qtde
					
                    from vendas_jdes vds
                    left join addressbook ab on ab.id = id_cliente
                    where ult_status not in ('980','984') and codgrife not in ('EP1','EP2','EP3','EP4','EP5') and datediff(now(),dt_venda) <= 365  
					and id_rep in ($representantes)
                    
					group by codgrife, id_cliente, cliente, id_rep 
                    
					) as fim1 where cart is not null and codgrife in $grifes and codgrife = '$codgrife'
            ) as fim2 
		) as fim3 where dias = '$dias' group by cliente, cart, financeiro
	) as fim4 group by cliente, cart, pdv																																                
");

	

	
@endphp

<h6>
<div class="row">

		<div class="col-md-4">
						 <div class="box box-widget box-body">
		
		    <div class="table-responsive">

        <table class="table table-bordered" id="example3">
          <thead>
		 <tr>	
	 		<td colspan="5">clientes com compras nos ultimos 12meses</td>
		
				</tr>
		  			
					<tr>	
					
					<td>form</td>
					<td colspan="1" align="center">clientes</td>
					<td colspan="1" align="center">qtde</td>
					<td colspan="1" align="center">regiao</td>
					<td colspan="1" align="center">pdvs</td>
					<td colspan="1" align="center">pdvs inad</td>
						
				
			</thead>	
					</tr>
		@php
		$total_modelos = 0;	
		$total_inicial = 0;	
		
	
		@endphp	
			  
			  
			@foreach ($query_1 as $query1)
			  
			  
		@php
              $total_modelos += $query1->qtde;
			  $total_inicial += $query1->qtde;
			
				  
		@endphp	  
			  
			  
				<tr>
					<td><a href="/clientes_form?cli={{$query1->cliente}}"><i class="fa fa-file"></i> </a></td>		
					<td align="left"><a href="/det_subgrupo?pdv={{$query1->pdv}}&codgrife={{$query1->cliente}}">{{$query1->cliente}}</a></td>

					<td align="left">{{number_format($query1->qtde,0)}}</td>

					<td align="left">{{$query1->cart}}</td>
					<td align="left">{{$query1->pdvs}}</td>
					<td align="left">{{$query1->pdvs_inad}}</td>

				</tr>
			@endforeach 
			 
			</table>
			
			TOTAL 
	</div>
		</div>

	</div>
	
	
	

</div>
	
	
	
	
</form>
</h6>
@stop