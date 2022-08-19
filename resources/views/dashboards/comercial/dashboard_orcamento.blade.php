@extends('layout.principal')

@section('title')
<i class="fa fa-suitcase"></i> Orcamentos Total
@append 

@section('conteudo')

<form action="" method="get"> 
@php

 $representantes = Session::get('representantes');
 $grifes = Session::get('grifes');

  $where = ' where id_rep in ('.$representantes.') ';
  $where2 = ' where rep_comissao in ('.$representantes.') ';

 //echo $where; 
	

$querya = \DB::select("
select fantasia, nome, fim2.* from (
	select id_rep, sum(valor_inadimplente) valor_inadimplente, 
	sum(valor_acordo) valor_acordo, 
	sum(valor)-sum(valor_inadimplente)-sum(valor_acordo) valor_adimplente, 
	sum(valor) valor 
	from (

		select id_rep, 
			case when financeiro in ('in','ju') then valor else 0 end as valor_inadimplente, 
			case when financeiro in ('ac') then valor else 0 end as valor_acordo,
			valor from (
        
			select DATEDIFF(now(), dt_venda) AS dias, id_rep, valor, financeiro
            
			from vendas_jdes vds
			left join addressbook ab on ab.id = vds.id_cliente
			where id_rep in ($representantes)  
			and vds.ult_status not in ('980','984') and vds.prox_status in ('515','516')
            
		) as fim
	) as fim1
    group by id_rep
) as fim2

left join (select id, fantasia, nome from addressbook ) as ab
on ab.id = id_rep");


	
	
$queryb = \DB::select("

select dias, sum(total) total, sum(boleto_minimo) boleto_minimo, sum(inadimplente) inadimplente, sum(adimplente) adimplente,
sum(atende_disponivel) atende_disponivel, sum(atende_15dd) atende_15dd, sum(atende_30dd) atende_30dd, sum(atende_60dd) atende_60dd,
sum(sem_previsao) sem_previsao
from ( 

	select pedido, dias, sum(valor) total, case when sum(valor) < 300 then sum(valor) else 0 end as boleto_minimo, 
	sum(inadimplente) inadimplente, 
	case when sum(valor_valido) > 300 then sum(adimplente) else 0 end as adimplente,  
	case when sum(valor_valido) > 300 then sum(atende_disponivel) else 0 end as atende_disponivel, 
	case when sum(valor_valido) > 300 then sum(atende_15dd-atende_disponivel) else 0 end as atende_15dd, 
	case when sum(valor_valido) > 300 then sum(atende_30dd-atende_15dd) else 0 end as atende_30dd,
	case when sum(valor_valido) > 300 then sum(atende_60dd-atende_30dd)  else 0 end as atende_60dd, 

	case when sum(valor_valido) > 300 then sum(valor_valido)-sum(atende_60dd) else 0 end as sem_previsao


	from (
		select *,	
			case when financeiro in ('in','ju') then valor else 0 end as inadimplente,
			case when financeiro in ('ac') then valor else 0 end as acordo,
			case when financeiro not in ('in','ju') then valor else 0 end as adimplente
			from (
					select *,
					case when qtde_valida >= disponivel then disponivel*valor_unitario else qtde_valida*valor_unitario end as atende_disponivel,
					case when qtde_valida >= (disponivel+benefic) then (disponivel+benefic)*valor_unitario else qtde_valida*valor_unitario end as atende_15dd,
					case when qtde_valida >= (disponivel+benefic+transito) then (disponivel+benefic+transito)*valor_unitario else qtde_valida*valor_unitario end as atende_30dd,
					case when qtde_valida >= (disponivel+benefic+transito+producao) then (disponivel+benefic+transito+producao)*valor_unitario else qtde_valida*valor_unitario end as atende_60dd
					
					from (            
					
						select pedido, id_item, 
						case when financeiro not in ('in','ju') then qtde else 0 end as qtde_valida,
						case when financeiro not in ('in','ju') then valor else 0 end as valor_valido,
				
						case when DATEDIFF(now(), dt_venda) <= 30 then ' 0 a 30 dias'
						when DATEDIFF(now(), dt_venda) <= 60 then ' 31 a 60 dias'
						when DATEDIFF(now(), dt_venda) <= 90 then ' 61 a 90 dias'
						when DATEDIFF(now(), dt_venda) <= 120 then ' 91 a 120 dias'
                        when DATEDIFF(now(), dt_venda) <= 150 then '121 a 150 dias'
                        when DATEDIFF(now(), dt_venda) <= 180 then '151 a 180 dias'
						else 'maior 180 dias' end as dias, 
						id_rep, valor, financeiro, qtde, valor_unitario,
						case when disponivel < 0 then 0 else disponivel end as disponivel,
						conf_montado+em_beneficiamento+saldo_parte benefic, cet transito, etq+saldos.cep producao
							
						from vendas_jde vds
						left join addressbook ab on ab.id = vds.id_cliente
						left join saldos on saldos.curto = vds.id_item
						 where  id_rep in ($representantes)   and --  pedido in (180063, 164829, 179661) and
						vds.ult_status not in ('980','984') and vds.prox_status in ('515','516')
			) as fim
			
		) as fim1
	) as fim2 group by pedido, dias	
) as fim3 group by dias order by dias

");


$query_faturas = \DB::select("
		select * from itens where modelo = 'ah1020'
");

// echo 'teste'.$where2;
	

@endphp
<h6>

<div class="row">

	
	
	<div class="col-md-9">
		<div class="box box-body box-widget">
			<table class="table table-bordered"> 
		 <tr>	
	 		<td colspan="8">  </td>
				</tr>
		  <tr>	
	 		
	 		<td colspan="1" align="center"></td>		 	
		 	<td colspan="1" align="center">fantasia</td>
		 	<td colspan="1" align="center">nome</td>
			<td colspan="1" align="center" >valor total</td>
			<td colspan="1" align="center" class="text-green">valor_adimplente</td>
			<td colspan="1" align="center" class="text-yellow">valor_acordo</td>				  
			<td colspan="1" align="center" class="text-red">valor_inadimplente</td>
			  
			  
		 	

									
			
@foreach ($querya as $query1)
	<tr>

<td align="left">{{$query1->id_rep}}</td>
<td align="left">{{$query1->fantasia}}</td>
<td align="left">{{$query1->nome}}</td>

											
<td align="center" class="text-red"><a href="/orcamento_det">
	{{number_format($query1->valor, 2, ',', '.')}}</a></td>	
											
<td align="center" class="text-green">
	{{number_format($query1->valor_adimplente, 2, ',', '.')}}</td>	
									
<td align="center" class="text-yellow">
{{number_format($query1->valor_acordo, 2, ',', '.')}}	</td>			

<td align="center" class="text-red">	
	{{number_format($query1->valor_inadimplente, 2, ',', '.')}}</td>	
</tr>
@endforeach 
				
			</table>
			<i><a href="">+</a></i>
		</div>
		
	</div>


	<div class="col-md-10">
		<div class="box box-body box-widget">
			<table class="table table-bordered"> 
		 <tr>	
	 		<td colspan="10">Orcamentos em aberto por data venda</td>
				</tr>
		  <tr>	
					<tr>
	<td colspan="1" align="center"><b></b></td>
	<td colspan="4" align="center"><b>Vlr Orcamentos</b></td>
	<td colspan="5" align="center"><b>Atende Adimplentes+Acordos</b></td>
				</tr></0>
			<td colspan="1" align="center">dias</td>
	 		<td colspan="1" align="center">total_orcamento</td>
			<td colspan="1" align="center">inadimplente</td>
			<td colspan="1" align="center">boleto minimo</td>
		 	<td colspan="1" align="center">adimplente</td>
			<td colspan="1" align="center">atende disponivel</td>
			<td colspan="1" align="center">atende 15 dias</td>
			<td colspan="1" align="center">atende 30 dias</td>
			<td colspan="1" align="center">atende 60 dias</td>
			<td colspan="1" align="center">sem previsao</td>
	
	@php
	$total_vendas = 0;
	$total_aberto = 0;
	$total_orcamento = 0;
	$total_adimplemte = 0;
	$total_so_aberto = 0;
	$total_so_faturado = 0;
					
	$total_disponivel = 0;
	$total_15dd = 0;
	$total_30dd = 0;
	$total_60dd = 0;
	$total_sem_previsao = 0;
					
	@endphp
					
			
			@foreach ($queryb as $query3)
					
	
	  @php
	  $total_vendas += $query3->total;
	  $total_aberto += $query3->inadimplente;
	  $total_orcamento += $query3->boleto_minimo;
	  $total_adimplemte += $query3->adimplente;
	  $total_so_aberto += $query3->atende_disponivel;
	  $total_so_faturado += $query3->atende_15dd;
					
	$total_disponivel += $query3->atende_disponivel;
	$total_15dd += $query3->atende_15dd;
	$total_30dd += $query3->atende_30dd;
	$total_60dd += $query3->atende_60dd;
	$total_sem_previsao += $query3->sem_previsao;
					
			  
	@endphp
				<tr>
					<td align="center">{{$query3->dias}}</td>
					<td align="center" class="text-black">{{number_format($query3->total,2)}}</td>
					<td align="center" class="text-red">{{number_format($query3->inadimplente,2)}}</td>
					<td align="center" class="text-yellow">{{number_format($query3->boleto_minimo,2)}}</td>
					<td align="center" class="text-green">{{number_format($query3->adimplente,2)}}</td>
					
					<td align="center" class="text-black">{{number_format($query3->atende_disponivel,2)}}</td>
					<td align="center" class="text-black">{{number_format($query3->atende_15dd,2)}}</td>
					<td align="center" class="text-black">{{number_format($query3->atende_30dd,2)}}</td>
					<td align="center" class="text-black">{{number_format($query3->atende_60dd,2)}}</td>
					<td align="center" class="text-black">{{number_format($query3->sem_previsao,2)}}</td>
			
				</tr>
			@endforeach 
					
	<td align="center"><STRONG>TOTAIS</STRONG></td>
	<td align="center">{{number_format($total_vendas, 2, ',', '.')}}</td>			
	<td align="center"><a href="/orcamento_det?status=IN">{{number_format($total_aberto, 2, ',', '.')}}</td>
	<td align="center">{{number_format($total_orcamento, 2, ',', '.')}}</td>
	<td align="center">{{number_format($total_adimplemte, 2, ',', '.')}}</td>

	<td align="center">{{number_format($total_disponivel, 2, ',', '.')}}</td>
	<td align="center">{{number_format($total_15dd, 2, ',', '.')}}</td>
	<td align="center">{{number_format($total_30dd, 2, ',', '.')}}</td>
	<td align="center">{{number_format($total_60dd, 2, ',', '.')}}</td>
	<td align="center">{{number_format($total_sem_previsao, 2, ',', '.')}}</td>
					
			</table>
			<i><a href="">+</a></i>
		</div>
		
	</div>

		<!--
	<div class="col-md-4">
		<div class="box box-body box-widget">
			<table class="table table-bordered"> 
		 <tr>	
	 		<td colspan="8">FATUAMENTOS (filtro de datas)</td>
				</tr>
		  <tr>	
	 		
	 		<td colspan="1" align="center">ANO / MES</td>	
		 	<td colspan="1" align="center">QTDE</td>
		 	<td colspan="1" align="center">VLR FATURADO</td>
		 	<td colspan="1" align="center"></td>
		 	
	
		
			
			@foreach ($query_faturas as $query2)
				<tr>
					<td align="center">{{$query2->valortabela.' / '.$query2->valortabela}}</td>
				
					
					<td align="center">{{number_format($query2->valortabela,0)}}</td>
					
						
					<td align="center" class="text-red"><a href="/clientes/notas_det?ano={{$query2->valortabela}}&mes={{$query2->valortabela}}">
					{{number_format($query2->valortabela, 2, ',', '.')}}</a></td>
					
					<td></td>

				</tr>
			@endforeach 
			</table>
			<i><a href="">+</a></i>
		</div>
		
	</div>
	


		
	<div class="col-md-3">
		<div class="box box-body box-widget">
		recebimentos
		</div>
	</div>
	
	<div class="col-md-3">
		<div class="box box-body box-widget">
		devolucoes
		</div>
	</div>

	
	<div class="col-md-3">
		<div class="box box-body box-widget">
		orcamentos
		</div>
	</div>

-->
	

</div>
</form>
</h6>
@stop