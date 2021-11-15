@extends('layout.principal')

@section('title')
<i class="fa fa-suitcase"></i> Dashboard Agrupamento
@append 

@section('conteudo')

<form action="" method="get"> 
@php

 $representantes = Session::get('representantes');
 $grifes = Session::get('grifes');

$agrup = $_GET["agrup"];
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
	

	

$query_1 = \DB::select("
 
	select fim1.*, saldo.* from (
				select codgrife, agrup, modelo, clasmod, colmod, count(secundario) itens , favoritos,
                (select material from itens itensc where itensc.modelo = fim.modelo and material <> ''  order by material desc limit 1) as material,
                (select genero from itens itensc where itensc.modelo = fim.modelo and genero <> ''  order by material desc limit 1) as genero,
                (select idade from itens itensc where itensc.modelo = fim.modelo and idade <> ''  order by material desc limit 1) as idade,
                (select sum(qtde) from vendas_jde vds left join itens itensc on itensc.id = vds.id_item where itensc.modelo = fim.modelo and ult_status not in ('980','984')  and datediff(now(),dt_venda) <= 60) vds_60dd,
                (select sum(qtde) from vendas_jde vds left join itens itensc on itensc.id = vds.id_item where itensc.modelo = fim.modelo and ult_status not in ('980','984')  and datediff(now(),dt_venda) <= 7) vds_7dd
                from ( 
					select distinct codgrife, itens.agrup, itens.modelo, itens.secundario, clasmod, colmod ,
					(select id_usuario from favoritos fav where fav.modelo = itens.modelo and  id_usuario = '488' limit 1) favoritos
						
					from itens 
					left join processa on processa.id_item = itens.id and date(processa.data) >= '2021-05-20'																												
					
					where codtipoarmaz not in ('o','i') and itens.secundario not like '%semi%' 
					and clasmod not in ('cancelado','colecao europa','promocional c') 
					and codtipoitem = 006 and itens.secundario not like 'KIT 50 %'and codtipoarmaz not in ('o','i')
					and itens.secundario not like 'semi%' 
                    and (processa.ultimo_st not in ('ESGOTADO','EM PRODUCAO') and processa.status3 in ('ESGOTADO','EM PRODUCAO'))
					and left(itens.agrup,5) = '$agrup'  
					-- and (left(colmod,4) <= year(now()) and right(colmod,2) <= month(now()))
					
					
				) as fim 
				group by codgrife, agrup, modelo, clasmod, colmod
			) as fim1

left join (select modelo, sum(disp_vendas) disp_vendas, sum(conf_montado+em_beneficiamento+saldo_parte) montagem, sum(cet) cet, sum(saldo_most) mostruarios  
			from saldos left join itens itensc on curto = itensc.id  group by modelo) as saldo
on saldo.modelo = fim1.modelo     

order by colmod desc, fim1.modelo
	
	             
");

	
echo 'teste' .count($query_1);
	
@endphp

<h6>
<div class="row">

		<div class="col-md-10">
		<div class="box box-body box-widget">
		 
		<table class="table table-responsive table-striped" id="example3">
						<thead>
		 <tr>	
	 		<td colspan="4">Timeline do Agrupamento {{$agrup}} </td>
			 <td colspan="14">MODELOS DISPONIVEIS + PRODUCAO</td>
				</tr>
		  			
					<tr>	
				
					<td colspan="1" align="center">fav</td>
					<td colspan="1" align="center">modelo</td>
					<td colspan="1" align="center">colmod</td>
					<td colspan="1" align="center">calsmod</td>
					<td colspan="1" align="center">itens</td>
					<td colspan="1" align="center">genero</td>
					<td colspan="1" align="center">idade</td>
					<td colspan="1" align="center">material</td>
					<td colspan="1" align="center">saldos</td>
					<td colspan="1" align="center">montagem</td>
					<td colspan="1" align="center">cet</td>
					<td colspan="1" align="center">most</td>
				    <td colspan="1" align="center">vds 7dd</td>
						<td colspan="1" align="center">vds 60dd</td>
					</tr>
							</thead>
		@php
		$total_modelos = 0;	
		$total_inicial = 0;	
		
	
		@endphp	
			  
			  
			@foreach ($query_1 as $query1)
			  
			  
		@php
              $total_modelos += $query1->itens;
			  $total_inicial += $query1->itens;
	
          $foto = app('App\Http\Controllers\ItemController')->consultaFoto($query1->modelo);

				  
		@endphp	  
			  
			  
				<tr>
				<td align="center">{{$query1->favoritos}}</td>
						
         
						
					<td align="left"> <a href="/painel/{{$query1->agrup}}/{{$query1->modelo}}">{{$query1->modelo}}</a></td>
					<td align="center">{{$query1->colmod}}</td>
					<td align="center">{{$query1->clasmod}}</td>
					<td align="center">{{$query1->itens}}</td>
					<td align="center">{{$query1->genero}}</td>
					<td align="center">{{$query1->idade}}</td>
					<td align="center">{{$query1->material}}</td>
					<td align="center">{{$query1->disp_vendas}}</td>
					<td align="center">{{$query1->montagem}}</td>
					<td align="center">{{$query1->cet}}</td>
					<td align="center">{{$query1->mostruarios}}</td>
					<td align="center">{{$query1->vds_7dd}}</td>
					<td align="center">{{$query1->vds_60dd}}</td>
					
					
	
				</tr>
			@endforeach 
			
			</table>
		</div>
	</div>
</div>
	
	
	
	
</form>
</h6>
@stop