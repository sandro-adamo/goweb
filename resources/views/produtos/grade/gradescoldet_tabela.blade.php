@php

$representantes = Session::get('representantes');



$agrup = $_GET["agrup"];

	if (isset($_GET["colecao"])) {
		$colecao = $_GET["colecao"];
		echo $colecao;
	
	} else {
		$colecao = '';
	echo 'sem colecao';
	
	}


$query_2 = \DB::select(" 

select fornecedor, grife, codgrife, agrup, modelo, colecao,
	sum(novos) novos, sum(aa) aa, sum(a) a, 
	sum(itens) itens, sum(imediata) imediata, sum(futura) futura, sum(producao) producao, sum(esgotado) esgotado, 
	sum(am3cores) am3cores, sum(b2cores) b2cores, sum(c1cor) c1cor, sum(d0cor) d0cor 
from (

	select fornecedor, grife, codgrife, agrup, colecao, modelo,
	case when colecao = 'novo' then 1 else 0 end as novos,
	case when colecao = 'aa' then 1 else 0 end as aa, 
	case when colecao = 'a' then 1 else 0 end as a, 
	sum(itens) itens, sum(imediata) imediata, sum(futura) futura, sum(producao) producao, sum(esgotado) esgotado, 
	sum(am3cores) am3cores, sum(b2cores) b2cores, sum(c1cor) c1cor, sum(d0cor) d0cor 
	from (

		select fornecedor, grife, codgrife, agrup, modelo, clasmod, colmod, (itens) as itens, (imediata) imediata, (futura) futura, (producao) producao, (esgotado) esgotado,
			case when imediata+futura >= 3 then 1 else 0 end as am3cores,
			case when imediata+futura  = 2 then 1 else 0 end as b2cores,
			case when imediata+futura  = 1 then 1 else 0 end as c1cor,
			case when imediata+futura  = 0 then 1 else 0 end as d0cor,
			
			 case when colecao = 'novo' then 'novo'
			 when colecao <> 'novo' and clasmod in ('LINHA A++','LINHA A+','LINHA A','NOVO') then 'aa'
			 when colecao <> 'novo' and clasmod in ('LINHA A-') then 'a' else '' end as colecao
			
			
		from(

			select fornecedor, grife, codgrife, agrup, modelo, clasmod, colmod, colecao, sum(itens) as itens, sum(imediata) imediata, sum(futura) futura, sum(producao) producao, sum(esgotado) esgotado
			
			from (
			 
				select fornecedor, grife, codgrife, agrup, modelo, clasmod, colmod, colecao, 1 as itens,
					case when ultstatus = 'ENTREGA IMEDIATA' then 1 else 0 end as imediata,
					case when ultstatus like '%DIAS' then 1 else 0 end as futura,
					case when ultstatus like '%PROD%' then 1 else 0 end as producao,
					case when ultstatus like '%ESGOTADO%' then 1 else 0 end as esgotado
				from (
							
					select case when fornecedor like 'kering%' then 'kering' else 'go' end as fornecedor,
					grife, codgrife, itens.agrup, itens.modelo, itens.secundario, colmod, clasmod, ultstatus,
					case when (left(colmod,4) <= year(now()) and right(colmod,2) < month(now())) then 'lancado' else 'novo' end as colecao
					from itens 
					where itens.secundario not like '%semi%' and (clasmod like 'linha%' or clasmod like 'novo%') and codtipoitem = 006				 
					and agrup = '$agrup'
					 and codtipoarmaz not in ('o')
				) as fim2
			) as fim3 group by fornecedor, grife, codgrife, agrup, modelo, clasmod, colmod, colecao
		) as fim4 
	) as fim5 group by fornecedor, grife, codgrife, agrup, colecao, modelo
) as fim6 group by fornecedor, grife, codgrife, agrup, modelo, colecao
order by fornecedor, agrup, modelo


");

@endphp





<table class="table table-bordered" id="example3">
	<thead>
		<tr>
			<th width="5%">Status</th>
			<th width="8%">Modelo</th>
			<th width="8%">Clasmod</th>
			<th width="8%">Entrada</th>
			<th width="8%">Saida</th>
			<th width="5%">Genero</th>
			<th width="8%">Idade</th>
			<th width="15%">Material</th>
			<th width="15%">Fixacao</th>
			<th width="10%">Estilo</th>
			<th width="10%">Tamanho</th>
			<th width="5%">vds 30dd</th>
			<th width="5%">vds 180dd</th>
			<th width="5%">vds total</th>
			
			<th width="5%">etq disp</th>
			<th width="5%">etq tt</th>
			

		</tr>
	</thead>
	<tbody>
		@foreach ($query_2 as $catalogo)

		@php
		switch ($catalogo->futura) {
			case 'entradas':
			$formato = 'fa fa-plus-square text-green';
			
			break;
			case 'saidas':
			$formato = 'fa fa-minus-square text-red';
			
			break;             
			default:
			$formato = 'fa fa-check-square text-blue';

		}
		@endphp
		<tr>
			<td align="left" class="{{$formato}}"> {{$catalogo->futura}}</td>
			<td align="left">  <a href="/painel/{{$catalogo->agrup}}/{{$catalogo->modelo}}">{{$catalogo->modelo}}</a></td>
		
	<!--	<td align="left"> {{$catalogo->clasmod}}</td>
			<td align="left">{{$catalogo->entrada}}</td>
			<td align="left">{{$catalogo->saida}}</td>
			<td align="left">{{$catalogo->genero}}</td>
			<td align="left">{{$catalogo->idade}}</td>
			<td align="left">{{$catalogo->material}}</td>
			<td align="left">{{$catalogo->fixacao}}</td>
			<td align="left">{{$catalogo->estilo}}</td>
			<td align="left">{{$catalogo->tamanho}}</td>
			
			<td align="left">{{$catalogo->qtde_30dd}}</td>
			<td align="left">{{$catalogo->qtde_180dd}}</td>
			<td align="left">{{$catalogo->qtde_total}}</td>
			
			<td align="left">{{$catalogo->disp_vendas}}</td>
			<td align="left">{{$catalogo->etq_total}}</td>
-->

		</tr>
		@endforeach
	</tbody>
</table>
