<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Troca;



class EstimativaComercialController extends Controller
{

	public function geraEstimativa() {

		// define("_JPGRAPH_PATH", '../../jpgraph/src/'); 

// include_once("/var/www/html/portalgo/public/jpgraph/src/jpgraph.php"); 
// require_once ('/var/www/html/portalgo/public/jpgraph/src/jpgraph_bar.php');

		$mpdf = new \Mpdf\Mpdf([
							'orientation' => 'L',
							'margin_left' => 3,
							'margin_right' => 1,
							'margin_top' => 1,
							'margin_bottom' => 1,
							'margin_header' => 1,
							'margin_footer' => 1
						]);
		//$mpdf->useOddEven = 1;
		$mpdf->useGraphs = true;	

/**		$estimativa = \DB::select("select *, 
 cast((((a_distribuir + bloqueio_cobranca + bloqueio_comercial + backorder + pedidos) / meta)*100) as decimal(10,1)) as realizado,
 meta - (a_distribuir + bloqueio_cobranca + bloqueio_comercial + backorder + pedidos) as a_realizar, 
 0 as a_realizar_dia
from (

	select divisao,substr(diretoria,5,20) as diretoria2, supervisor, sum(a_distribuir) as a_distribuir, sum(bloqueio_cobranca) as bloqueio_cobranca, sum(bloqueio_comercial) as bloqueio_comercial, sum(backorder) as backorder, sum(pedidos) as pedidos, 
	 sum(backorder) + sum(pedidos) as total_venda, sum(a_distribuir) + sum(bloqueio_cobranca) + sum(bloqueio_comercial) + sum(backorder) + sum(pedidos) as total_venda_estimativa, meta

	from (

		select cart.coddir, abd.razao as diretor, cart.codsuper, abr.id as id_rep, abr.nome as rep, abc.id as id_cliente, vda.ult_status, vda.prox_status,
			case  
				-- when item.codgrife = 'PU' then 'PUMA'
				when abs.id = 161315 or abs.id = 52040 then 'NOVOS NEGOCIOS'
				when abs.nome <> '' then abs.nome 
				when abs.razao is null then 'DIRETO' 
			else abs.razao 
			end as supervisor,
			
			-- 0 as bloqueio_cobranca,
			-- 0 as bloqueio_comercial,
			
			case when sus.codigo in ('IN', 'C1', 'AA', 'AH', 'C2', 'CA', 'CI', 'DT', 'HS' , 'JU', 'M1', 'M2', 'MN', 'MX', 'NV','PR', 'XX') then vda.valor else 0 end as bloqueio_cobranca,
			case when sus.codigo in ('AG', 'BM', 'CP','LX' ,'RS') then vda.valor else 0 end as bloqueio_comercial,

			vda.pedido, vda.tipo, vda.linha, vda.item,
			case when ((vda.ult_status = '505' and vda.prox_status = '510') or (vda.ult_status = '510' and vda.prox_status = '512')) and sus.id is null then vda.valor else 0 end as a_distribuir,
			case when vda.prox_status = '515' and sus.id is null then vda.valor else 0 end as backorder,
			case when vda.prox_status not in ('515', '512', '510') and vda.ult_status not in ('510','505') and sus.id is null then vda.valor else 0 end as pedidos,

			case 
				-- when item.codgrife = 'PU' then 'i - PUMA'
				when cart.coddir = 86087 then 'a - NACIONAL'
				when cart.coddir = 97198 and item.codgrife not in ('BV', 'CT', 'GU', 'MC', 'SM', 'ST','AA', 'AZ', 'BC', 'AM', 'MM') then 'b - PRIME'
				when cart.coddir = 97198 and item.codgrife in ('BV', 'CT', 'GU', 'MC', 'SM', 'ST','AA', 'AZ', 'BC', 'AM', 'MM') then 'g - PREMIUM'
				when cart.coddir = 97198 and item.codgrife in ('PU') then 'h - PUMA'
				when vda.id_rep = 10 and item.codgrife in ('BV', 'CT', 'GU', 'MC', 'SM', 'ST','AA', 'AZ', 'BC', 'AM', 'MM') then 'h - DIRETO'
				when cart.coddir = 89614 then 'c - NOVOS CANAIS'
				when cart.coddir = 161324 then 'd - ATITUDE POINT'
				when cart.coddir is null then 'e - DIRETO'
				when cart.coddir = 94787 then 'f - LUXO'
			end as diretoria,
			-- metas.valor as meta,
			case 
				when cart.codsuper = 89562 then 2170233.98 
				when cart.codsuper = 91531 then 3782407.78 
				when cart.codsuper = 100489 then 3968427.84 
				when cart.codsuper = 101240 then 1798193.86 
				when cart.codsuper = 230269 then 341036.77 
				when cart.codsuper = 230270 then 341036.77 
				
				when cart.codsuper = 161321 then 840236.00 
				when cart.codsuper = 52040 or codsuper = 161315 then 450000.00 
				when cart.codsuper = 161327 then 306865.00 
				
				when cart.codsuper = 161319 then 404267.00 
				when cart.codsuper = 161311 and item.codgrife in ('BV', 'CT', 'GU', 'MC', 'SM', 'ST','AA', 'AZ', 'BC', 'AM', 'MM') then 801115.00 
				
				when cart.coddir = 97198 and item.codgrife not in ('BV', 'CT', 'GU', 'MC', 'SM', 'ST','AA', 'AZ', 'BC', 'AM', 'MM') then 2207028.67 

			else 0 end as meta,
			case when item.codgrife in ('BV', 'CT', 'GU', 'MC', 'SM', 'ST','AA', 'AZ', 'BC', 'AM', 'MM') then 'KERING' else 'GO' end as divisao,
			
			case  
				when abc.uf in ('AL','BA','CE','MA','PB','PE','PI','RN','SE') then 'Nordeste'
				when abc.uf in ('DF','GO','MT','MS','RR') then 'Centro-Oeste'
				when abc.uf in ('AC','AP','AM','PA','RO','TO') then 'Norte'
				when abc.uf in ('ES','MG','RJ','SP') then 'Sudeste'
				when abc.uf in ('PR','RS','SC') then 'Sul'
			end as regiao

	 
		from vendas_jde vda
		left join itens item on vda.id_item = item.id
		left join carteira cart on vda.id_rep = cart.rep and vda.id_cliente = cart.cli and cart.grife = item.codgrife  and vda.dt_venda between cart.dt_inicio and cart.dt_fim
		left join addressbook abs on cart.codsuper = abs.id
		left join addressbook abd on cart.coddir   = abd.id
		left join addressbook abc on vda.id_cliente = abc.id
		left join addressbook abr on vda.id_rep = abr.id
		left join suspensoes sus on vda.pedido = sus.pedido and vda.tipo = sus.tipo
		-- left join metas on metas.id_addressbook = abd.id and metas.ano = year(dt_venda) and metas.mes = month(dt_venda)

		where 
			dt_venda between '20200601' and '20200630'  
            and vda.ult_status not in ('980','984') 
            -- and abs.id = 101240 -- and abr.id = 90215
			-- and abs.id = 91531 -- and id_rep = 213355
			-- and sus.id is null
			-- and vda.pedido <> 140880
		) as base
	group by divisao,diretoria, supervisor,meta -- , id_rep, rep 
	-- order by  divisao, diretoria
) as fim");


		
**/		
		
		$estimativa = \DB::select("
select divisao, coddir, diretoria2, codsuper, supervisor,

sum(a_distribuir) a_distribuir, sum(bloqueio_cobranca) bloqueio_cobranca, sum(bloqueio_comercial) bloqueio_comercial, sum(backorder) backorder, sum(pedidos) pedidos, 
sum(total_venda) total_venda, sum(total_venda_estimativa) total_venda_estimativa, 
avg(meta) meta,  
sum(realizado) realizado, 
avg(meta)-sum(a_distribuir + bloqueio_cobranca + bloqueio_comercial + backorder + pedidos) as a_realizar,
(avg(meta)-sum(a_distribuir + bloqueio_cobranca + bloqueio_comercial + backorder + pedidos))/ DATEDIFF(LAST_DAY(NOW()), CURDATE()) a_realizar_dia

from (

	select meta3.*, substr(diretoria,5,20) as diretoria2, 
	case when abs.nome <> '' then nome else fantasia end as supervisor, 
	 cast((((a_distribuir + bloqueio_cobranca + bloqueio_comercial + backorder + pedidos) / meta)*100) as decimal(10,1)) as realizado
	 
	from (

		select divisao,
		
		coddir,
		
			case 
			when coddir = 86087 then 'a - NACIONAL'
			when coddir = 97198 and codgrife not in ('BV', 'CT', 'GU', 'MC', 'SM', 'ST','AA', 'AZ', 'BC', 'AM', 'MM') then 'b - PRIME'
			when coddir = 97198 and codgrife in ('BV', 'CT', 'GU', 'MC', 'SM', 'ST','AA', 'AZ', 'BC', 'AM', 'MM') then 'g - PREMIUM'
			when coddir = 97198 and codgrife in ('PU') then 'h - PUMA'
			when id_rep = 10 and codgrife in ('BV', 'CT', 'GU', 'MC', 'SM', 'ST','AA', 'AZ', 'BC', 'AM', 'MM') then 'h - DIRETO'
			when coddir = 89614 then 'c - NOVOS CANAIS'
			when coddir = 161324 then 'd - ATITUDE POINT'
			when coddir is null then 'e - DIRETO'
			when coddir = 94787 then 'f - LUXO'
			end as diretoria,

		codsuper,  id_cliente, codgrife, pedido,
		sum(a_distribuir) as a_distribuir ,sum(bloqueio_cobranca) as bloqueio_cobranca , 
		sum(bloqueio_comercial) as bloqueio_comercial , sum(backorder) as backorder , sum(pedidos) as pedidos , 
		sum(backorder) + sum(pedidos) as total_venda , sum(a_distribuir) + sum(bloqueio_cobranca) + sum(bloqueio_comercial) + sum(backorder) + sum(pedidos) as total_venda_estimativa
	 , ifnull((select sum(valor) from metas where codsuper = id_addressbook and ano = 2020 and mes = 7 and divisao = agrup),0) meta

		from (

			select id_cliente, codgrife, dt_venda, prox_status, ult_status, pedido,
			case when id_rep is not null then id_rep else 0 end as id_rep,
			
			case 
			when coddir is null then ( select coddir from carteira cart1 where id_rep = cart1.rep  limit 1 )
			when id_rep is not null then coddir else 0 end as coddir, 
			
			case when codsuper = 161315 or codsuper = 52040 then 52040 when codsuper is null then ( select codsuper from carteira cart1 where id_rep = cart1.rep  limit 1 )
			when id_rep is not null then codsuper else 0 end as codsuper, uf, sus, sus1, sus2, case when sus1 > 0 then valor else 0 end as bloqueio_cobranca, case when sus2 > 0 then valor else 0 end as bloqueio_comercial,

			case when ((ult_status = '505' and prox_status = '510') or (ult_status = '510' and prox_status = '512')) and sus = 0 then valor else 0 end as a_distribuir,
			case when prox_status = '515' and sus = 0 then valor else 0 end as backorder,
			case when prox_status not in ('515', '512', '510') and ult_status not in ('510','505') and sus = 0 then valor else 0 end as pedidos,
			case when codgrife in ('BV', 'CT', 'GU', 'MC', 'SM', 'ST','AA', 'AZ', 'BC', 'AM', 'MM') then 'KERING' else 'GO' end as divisao
			

			from (
				select id_cliente, codgrife, dt_venda, prox_status, ult_status, sus, sus1, sus2, qtde, valor, pedido,
				case when (id_rep not in (10, 0)) then id_rep when (rep_1 is null and (id_rep in (10,0) or id_rep is null)) then rep_0 when (id_rep in (10,0) or id_rep is null) then rep_1 else id_rep end as id_rep
				
				from (

					select *,
					case when id_rep not in (10,0) then id_rep else (select rep from carteira cart where vda.id_cliente = cart.cli and cart.grife = vda.codgrife and status = 1 and vda.dt_venda between cart.dt_inicio and cart.dt_fim limit 1) end as rep_1,
					(select rep from carteira cart where vda.id_cliente = cart.cli and cart.grife = vda.codgrife and vda.dt_venda between cart.dt_inicio and cart.dt_fim order by cart.dt_fim desc, dt_inicio desc limit 1) rep_0 
					
					from (
						select id_rep, codgrife, id_cliente, dt_venda, prox_status, ult_status, sus, sus1, sus2, pedido, sum(valor) valor, sum(qtde) qtde from (
							select  vda.id_rep, codgrife, id_cliente, dt_venda, prox_status, ult_status, pedido,
							(select count(id) from suspensoes sus where vda.pedido = sus.pedido and vda.tipo = sus.tipo ) as sus,
							(select count(id) from suspensoes sus where vda.pedido = sus.pedido and vda.tipo = sus.tipo and sus.codigo in ('IN', 'C1', 'AA', 'AH', 'C2', 'CA', 'CI', 'DT', 'HS' , 'JU', 'M1', 'M2', 'MN', 'MX', 'NV','PR', 'XX') ) as sus1,
							(select count(id) from suspensoes sus where vda.pedido = sus.pedido and vda.tipo = sus.tipo and sus.codigo in ('AG', 'BM', 'CP','LX' ,'RS') ) as sus2,
							
							(valor) valor, (qtde) qtde
							from vendas_jde vda
							left join itens item on vda.id_item = item.id
							where vda.ult_status not in ('980','984') and dt_venda between '20200701' and '20200731' 
							-- and id_cliente = 130673
			
						) as fim group by id_rep, codgrife, id_cliente, dt_venda, prox_status, ult_status, sus, sus1, sus2, pedido
					) as vda
				) as base
			) as base1


			left join ( select grife, rep, coddir, codsuper from carteira /*where status = 1*/ group by grife, rep, coddir, codsuper ) as diret
			on diret.grife = codgrife and id_rep = rep

			left join ( select id, uf from addressbook ) ab
			on id_cliente = ab.id
 
		) as base2
		group by coddir, codsuper, meta, id_cliente, codgrife, id_rep, pedido
		
	) as meta3
	left join ( select id, nome, fantasia, razao from addressbook ) abs on codsuper = abs.id

) as fim4
where coddir is not null
group by divisao, coddir, diretoria2, codsuper, supervisor
order by divisao, diretoria2, supervisor
");

		
		
		
		
		
		$html = '
			<html>
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	
			<style>
				body {
					font-family: sans-serif;
					font-size: 8px;
				}
				p {	margin: 0pt; }
				table.items {
					border: 0.1mm solid #000000;
				}
				td { vertical-align: top; }
				.items td {
					border: 0.1mm solid #000000;
				}
				t1able thead td { 
					background-color: #EEEEEE;
					text-align: left;
					font-weight:  bold;
					border: 0.1mm solid #000000;
					font-variant: small-caps;
				}
				.teste { 
					background-color: #EEEEEE;
					font-weight:  bold;
					border: 0.1mm solid #000000;
					font-variant: small-caps;
				}
				.center {
					text-align: center;
				}
				.items td.blanktotal {
					background-color: #EEEEEE;
					border: 0.1mm solid #000000;
					background-color: #FFFFFF;
					border: 0mm none #000000;
					border-top: 0.1mm solid #000000;
					border-right: 0.1mm solid #000000;
				}
				.items td.totals {
					text-align: right;
					border: 0.1mm solid #000000;
				}
				.items td.cost {
					text-align: "." center;
				}
				.verde {
					background-color: #3CB371 !important;

				}
				.azul { 
					background-color: #1E90FF;
				}
				.laranja {
					background-color: 	#F4A460;
				}
				.bege { 
					background-color: #FFDEAD;
				}
				.cinza {
					background-color: #808080;
				}
				.azul-escuro {
					background-color: #4169E1;
				}
				.azul-claro {
					background-color: #87CEFA;
				}
			</style>
			</head>
			<body>';



		$nacional = array();
		$prime = array();
		$nacional_bloqueio_a_distribuir = 0;
		$nacional_bloqueio_cobranca = 0;
		$nacional_bloqueio_comercial = 0;
		$nacional_bloqueio_bo = 0;
		$nacional_bloqueio_pedidos = 0;
		$nacional_bloqueio_venda = 0;
		$nacional_bloqueio_venda_estimativa = 0;
		$nacional_bloqueio_meta = 0;
		$nacional_bloqueio_perc_realizado = 0;
		$nacional_bloqueio_a_realizar = 0;
		$nacional_bloqueio_a_realizar_dia = 0;

		$prime_bloqueio_a_distribuir = 0;
		$prime_bloqueio_cobranca = 0;
		$prime_bloqueio_comercial = 0;
		$prime_bloqueio_bo = 0;
		$prime_bloqueio_pedidos = 0;
		$prime_bloqueio_venda = 0;
		$prime_bloqueio_venda_estimativa = 0;
		$prime_bloqueio_meta = 0;
		$prime_bloqueio_perc_realizado = 0;
		$prime_bloqueio_a_realizar = 0;
		$prime_bloqueio_a_realizar_dia = 0;

		$novoscanais_bloqueio_a_distribuir = 0;
		$novoscanais_bloqueio_cobranca = 0;
		$novoscanais_bloqueio_comercial = 0;
		$novoscanais_bloqueio_bo = 0;
		$novoscanais_bloqueio_pedidos = 0;
		$novoscanais_bloqueio_venda = 0;
		$novoscanais_bloqueio_venda_estimativa = 0;
		$novoscanais_bloqueio_meta = 0;
		$novoscanais_bloqueio_perc_realizado = 0;
		$novoscanais_bloqueio_a_realizar = 0;
		$novoscanais_bloqueio_a_realizar_dia = 0;

		$luxo_bloqueio_a_distribuir = 0;
		$luxo_bloqueio_cobranca = 0;
		$luxo_bloqueio_comercial = 0;
		$luxo_bloqueio_bo = 0;
		$luxo_bloqueio_pedidos = 0;
		$luxo_bloqueio_venda = 0;
		$luxo_bloqueio_venda_estimativa = 0;
		$luxo_bloqueio_meta = 0;
		$luxo_bloqueio_perc_realizado = 0;
		$luxo_bloqueio_a_realizar = 0;
		$luxo_bloqueio_a_realizar_dia = 0;

		$premium_bloqueio_a_distribuir = 0;
		$premium_bloqueio_cobranca = 0;
		$premium_bloqueio_comercial = 0;
		$premium_bloqueio_bo = 0;
		$premium_bloqueio_pedidos = 0;
		$premium_bloqueio_venda = 0;
		$premium_bloqueio_venda_estimativa = 0;
		$premium_bloqueio_meta = 0;
		$premium_bloqueio_perc_realizado = 0;
		$premium_bloqueio_a_realizar = 0;
		$premium_bloqueio_a_realizar_dia = 0;


		$kdireto_bloqueio_a_distribuir = 0;
		$kdireto_bloqueio_cobranca = 0;
		$kdireto_bloqueio_comercial = 0;
		$kdireto_bloqueio_bo = 0;
		$kdireto_bloqueio_pedidos = 0;
		$kdireto_bloqueio_venda = 0;
		$kdireto_bloqueio_venda_estimativa = 0;
		$kdireto_bloqueio_meta = 0;
		$kdireto_bloqueio_perc_realizado = 0;
		$kdireto_bloqueio_a_realizar = 0;
		$kdireto_bloqueio_a_realizar_dia = 0;


		$puma_bloqueio_a_distribuir = 0;
		$puma_bloqueio_cobranca = 0;
		$puma_bloqueio_comercial = 0;
		$puma_bloqueio_bo = 0;
		$puma_bloqueio_pedidos = 0;
		$puma_bloqueio_venda = 0;
		$puma_bloqueio_venda_estimativa = 0;
		$puma_bloqueio_meta = 0;
		$puma_bloqueio_perc_realizado = 0;
		$puma_bloqueio_a_realizar = 0;
		$puma_bloqueio_a_realizar_dia = 0;


		$direto_bloqueio_a_distribuir = 0;
		$direto_bloqueio_cobranca = 0;
		$direto_bloqueio_comercial = 0;
		$direto_bloqueio_bo = 0;
		$direto_bloqueio_pedidos = 0;
		$direto_bloqueio_venda = 0;
		$direto_bloqueio_venda_estimativa = 0;
		$direto_bloqueio_meta = 0;
		$direto_bloqueio_perc_realizado = 0;
		$direto_bloqueio_a_realizar = 0;
		$direto_bloqueio_a_realizar_dia = 0;


		$puma_bloqueio_a_distribuir = 0;
		$puma_bloqueio_cobranca = 0;
		$puma_bloqueio_comercial = 0;
		$puma_bloqueio_bo = 0;
		$puma_bloqueio_pedidos = 0;
		$puma_bloqueio_venda = 0;
		$puma_bloqueio_venda_estimativa = 0;
		$puma_bloqueio_meta = 0;
		$puma_bloqueio_perc_realizado = 0;
		$puma_bloqueio_a_realizar = 0;
		$puma_bloqueio_a_realizar_dia = 0;

		$totalgo_bloqueio_a_distribuir = 0;
		$totalgo_bloqueio_cobranca = 0;
		$totalgo_bloqueio_comercial = 0;
		$totalgo_bloqueio_bo = 0;
		$totalgo_bloqueio_pedidos = 0;
		$totalgo_bloqueio_venda = 0;
		$totalgo_bloqueio_venda_estimativa = 0;
		$totalgo_bloqueio_meta = 0;
		$totalgo_bloqueio_perc_realizado = 0;
		$totalgo_bloqueio_a_realizar = 0;
		$totalgo_bloqueio_a_realizar_dia = 0;


		$totalkering_bloqueio_a_distribuir = 0;
		$totalkering_bloqueio_cobranca = 0;
		$totalkering_bloqueio_comercial = 0;
		$totalkering_bloqueio_bo = 0;
		$totalkering_bloqueio_pedidos = 0;
		$totalkering_bloqueio_venda = 0;
		$totalkering_bloqueio_venda_estimativa = 0;
		$totalkering_bloqueio_meta = 0;
		$totalkering_bloqueio_perc_realizado = 0;
		$totalkering_bloqueio_a_realizar = 0;
		$totalkering_bloqueio_a_realizar_dia = 0;

		$totalgokering_bloqueio_a_distribuir = 0;
		$totalgokering_bloqueio_cobranca = 0;
		$totalgokering_bloqueio_comercial = 0;
		$totalgokering_bloqueio_bo = 0;
		$totalgokering_bloqueio_pedidos = 0;
		$totalgokering_bloqueio_venda = 0;
		$totalgokering_bloqueio_venda_estimativa = 0;
		$totalgokering_bloqueio_meta = 0;
		$totalgokering_bloqueio_perc_realizado = 0;
		$totalgokering_bloqueio_a_realizar = 0;
		$totalgokering_bloqueio_a_realizar_dia = 0;


		$atp_bloqueio_a_distribuir = 0;
		$atp_bloqueio_cobranca = 0;
		$atp_bloqueio_comercial = 0;
		$atp_bloqueio_bo = 0;
		$atp_bloqueio_pedidos = 0;
		$atp_bloqueio_venda = 0;
		$atp_bloqueio_venda_estimativa = 0;
		$atp_bloqueio_meta = 0;
		$atp_bloqueio_perc_realizado = 0;
		$atp_bloqueio_a_realizar = 0;
		$atp_bloqueio_a_realizar_dia = 0;



		foreach ($estimativa as $linha) {

			if ($linha->divisao == 'GO') {

				$totalgo_bloqueio_a_distribuir += $linha->a_distribuir;
				$totalgo_bloqueio_cobranca += $linha->bloqueio_cobranca;
				$totalgo_bloqueio_comercial += $linha->bloqueio_comercial;
				$totalgo_bloqueio_bo += $linha->backorder;
				$totalgo_bloqueio_pedidos += $linha->pedidos;
				$totalgo_bloqueio_venda += $linha->total_venda;
				$totalgo_bloqueio_venda_estimativa += $linha->total_venda_estimativa;
				$totalgo_bloqueio_meta += $linha->meta;
				$totalgo_bloqueio_perc_realizado += $linha->realizado;
				$totalgo_bloqueio_a_realizar += $linha->a_realizar;
				$totalgo_bloqueio_a_realizar_dia += $linha->a_realizar_dia;

			}

			if ($linha->divisao == 'KERING') {

				$totalkering_bloqueio_a_distribuir += $linha->a_distribuir;
				$totalkering_bloqueio_cobranca += $linha->bloqueio_cobranca;
				$totalkering_bloqueio_comercial += $linha->bloqueio_comercial;
				$totalkering_bloqueio_bo += $linha->backorder;
				$totalkering_bloqueio_pedidos += $linha->pedidos;
				$totalkering_bloqueio_venda += $linha->total_venda;
				$totalkering_bloqueio_venda_estimativa += $linha->total_venda_estimativa;
				$totalkering_bloqueio_meta += $linha->meta;
				$totalkering_bloqueio_perc_realizado += $linha->realizado;
				$totalkering_bloqueio_a_realizar += $linha->a_realizar;
				$totalkering_bloqueio_a_realizar_dia += $linha->a_realizar_dia;

			}

			$totalgokering_bloqueio_a_distribuir += $linha->a_distribuir;
			$totalgokering_bloqueio_cobranca += $linha->bloqueio_cobranca;
			$totalgokering_bloqueio_comercial += $linha->bloqueio_comercial;
			$totalgokering_bloqueio_bo += $linha->backorder;
			$totalgokering_bloqueio_pedidos += $linha->pedidos;
			$totalgokering_bloqueio_venda += $linha->total_venda;
			$totalgokering_bloqueio_venda_estimativa += $linha->total_venda_estimativa;
			$totalgokering_bloqueio_meta += $linha->meta;
			$totalgokering_bloqueio_perc_realizado += $linha->realizado;
			$totalgokering_bloqueio_a_realizar += $linha->a_realizar;
			$totalgokering_bloqueio_a_realizar_dia += $linha->a_realizar_dia;


			if ($linha->diretoria2 == 'NACIONAL') {
				$nacional[] = $linha;
				$nacional_bloqueio_a_distribuir += $linha->a_distribuir;
				$nacional_bloqueio_cobranca += $linha->bloqueio_cobranca;
				$nacional_bloqueio_comercial += $linha->bloqueio_comercial;
				$nacional_bloqueio_bo += $linha->backorder;
				$nacional_bloqueio_pedidos += $linha->pedidos;
				$nacional_bloqueio_venda += $linha->total_venda;
				$nacional_bloqueio_venda_estimativa += $linha->total_venda_estimativa;
				$nacional_bloqueio_meta += $linha->meta;
				$nacional_bloqueio_perc_realizado += $linha->realizado;
				$nacional_bloqueio_a_realizar += $linha->a_realizar;
				$nacional_bloqueio_a_realizar_dia += $linha->a_realizar_dia;

			} 


			if ($linha->diretoria2 == 'PRIME') {
				$prime[] = $linha;
				$prime_bloqueio_a_distribuir += $linha->a_distribuir;
				$prime_bloqueio_cobranca += $linha->bloqueio_cobranca;
				$prime_bloqueio_comercial += $linha->bloqueio_comercial;
				$prime_bloqueio_bo += $linha->backorder;
				$prime_bloqueio_pedidos += $linha->pedidos;
				$prime_bloqueio_venda += $linha->total_venda;
				$prime_bloqueio_venda_estimativa += $linha->total_venda_estimativa;
				$prime_bloqueio_meta += $linha->meta;
				$prime_bloqueio_perc_realizado += $linha->realizado;
				$prime_bloqueio_a_realizar += $linha->a_realizar;
				$prime_bloqueio_a_realizar_dia += $linha->a_realizar_dia;
			} 

			if ($linha->diretoria2 == 'DIRETO') {

				if ($linha->divisao == 'KERING') {

					$kdireto_bloqueio_a_distribuir += $linha->a_distribuir;
					$kdireto_bloqueio_cobranca += $linha->bloqueio_cobranca;
					$kdireto_bloqueio_comercial += $linha->bloqueio_comercial;
					$kdireto_bloqueio_bo += $linha->backorder;
					$kdireto_bloqueio_pedidos += $linha->pedidos;
					$kdireto_bloqueio_venda += $linha->total_venda;
					$kdireto_bloqueio_venda_estimativa += $linha->total_venda_estimativa;
					$kdireto_bloqueio_meta += $linha->meta;
					$kdireto_bloqueio_perc_realizado += $linha->realizado;
					$kdireto_bloqueio_a_realizar += $linha->a_realizar;
					$kdireto_bloqueio_a_realizar_dia += $linha->a_realizar_dia;

				} else {


					$direto_bloqueio_a_distribuir += $linha->a_distribuir;
					$direto_bloqueio_cobranca += $linha->bloqueio_cobranca;
					$direto_bloqueio_comercial += $linha->bloqueio_comercial;
					$direto_bloqueio_bo += $linha->backorder;
					$direto_bloqueio_pedidos += $linha->pedidos;
					$direto_bloqueio_venda += $linha->total_venda;
					$direto_bloqueio_venda_estimativa += $linha->total_venda_estimativa;
					$direto_bloqueio_meta += $linha->meta;
					$direto_bloqueio_perc_realizado += $linha->realizado;
					$direto_bloqueio_a_realizar += $linha->a_realizar;
					$direto_bloqueio_a_realizar_dia += $linha->a_realizar_dia;
				}

			} 

			if ($linha->diretoria2 == 'NOVOS CANAIS') {
				$novoscanais_bloqueio_a_distribuir += $linha->a_distribuir;
				$novoscanais_bloqueio_cobranca += $linha->bloqueio_cobranca;
				$novoscanais_bloqueio_comercial += $linha->bloqueio_comercial;
				$novoscanais_bloqueio_bo += $linha->backorder;
				$novoscanais_bloqueio_pedidos += $linha->pedidos;
				$novoscanais_bloqueio_venda += $linha->total_venda;
				$novoscanais_bloqueio_venda_estimativa += $linha->total_venda_estimativa;
				$novoscanais_bloqueio_meta += $linha->meta;
				$novoscanais_bloqueio_perc_realizado += $linha->realizado;
				$novoscanais_bloqueio_a_realizar += $linha->a_realizar;
				$novoscanais_bloqueio_a_realizar_dia += $linha->a_realizar_dia;
			}


			if ($linha->diretoria2 == 'ATITUDE POINT') {
				$atp_bloqueio_a_distribuir += $linha->a_distribuir;
				$atp_bloqueio_cobranca += $linha->bloqueio_cobranca;
				$atp_bloqueio_comercial += $linha->bloqueio_comercial;
				$atp_bloqueio_bo += $linha->backorder;
				$atp_bloqueio_pedidos += $linha->pedidos;
				$atp_bloqueio_venda += $linha->total_venda;
				$atp_bloqueio_venda_estimativa += $linha->total_venda_estimativa;
				$atp_bloqueio_meta += $linha->meta;
				$atp_bloqueio_perc_realizado += $linha->realizado;
				$atp_bloqueio_a_realizar += $linha->a_realizar;
				$atp_bloqueio_a_realizar_dia += $linha->a_realizar_dia;
			} 



			if ($linha->diretoria2 == 'LUXO') {
				$luxo_bloqueio_a_distribuir += $linha->a_distribuir;
				$luxo_bloqueio_cobranca += $linha->bloqueio_cobranca;
				$luxo_bloqueio_comercial += $linha->bloqueio_comercial;
				$luxo_bloqueio_bo += $linha->backorder;
				$luxo_bloqueio_pedidos += $linha->pedidos;
				$luxo_bloqueio_venda += $linha->total_venda;
				$luxo_bloqueio_venda_estimativa += $linha->total_venda_estimativa;
				$luxo_bloqueio_meta += $linha->meta;
				$luxo_bloqueio_perc_realizado += $linha->realizado;
				$luxo_bloqueio_a_realizar += $linha->a_realizar;
				$luxo_bloqueio_a_realizar_dia += $linha->a_realizar_dia;
			} 


			if ($linha->diretoria2 == 'PREMIUM') {
				$premium_bloqueio_a_distribuir += $linha->a_distribuir;
				$premium_bloqueio_cobranca += $linha->bloqueio_cobranca;
				$premium_bloqueio_comercial += $linha->bloqueio_comercial;
				$premium_bloqueio_bo += $linha->backorder;
				$premium_bloqueio_pedidos += $linha->pedidos;
				$premium_bloqueio_venda += $linha->total_venda;
				$premium_bloqueio_venda_estimativa += $linha->total_venda_estimativa;
				$premium_bloqueio_meta += $linha->meta;
				$premium_bloqueio_perc_realizado += $linha->realizado;
				$premium_bloqueio_a_realizar += $linha->a_realizar;
				$premium_bloqueio_a_realizar_dia += $linha->a_realizar_dia;
			} 


			if ($linha->diretoria2 == 'PUMA') {
				$puma_bloqueio_a_distribuir += $linha->a_distribuir;
				$puma_bloqueio_cobranca += $linha->bloqueio_cobranca;
				$puma_bloqueio_comercial += $linha->bloqueio_comercial;
				$puma_bloqueio_bo += $linha->backorder;
				$puma_bloqueio_pedidos += $linha->pedidos;
				$puma_bloqueio_venda += $linha->total_venda;
				$puma_bloqueio_venda_estimativa += $linha->total_venda_estimativa;
				$puma_bloqueio_meta += $linha->meta;
				$puma_bloqueio_perc_realizado += $linha->realizado;
				$puma_bloqueio_a_realizar += $linha->a_realizar;
				$puma_bloqueio_a_realizar_dia += $linha->a_realizar_dia;
			} 

		}
		if(($totalgokering_bloqueio_venda_estimativa)>0 && ($totalgokering_bloqueio_meta)>0){
		$perc_meta = ($totalgokering_bloqueio_venda_estimativa/$totalgokering_bloqueio_meta)*100;
		}
		else{
			$perc_meta = 0;
		}
		
		$html .= '<div width="35%" style="float: left;"><img src="grafico.php?id='.$perc_meta.'" ></div>';
		$html .= '<div width="65%" style="float: right;clear;"><img src="grafico2.php?id=1" ></div>';

		// $html .= '
		// 	<table id="tbl_1"><tbody>
		// 	<tr><td></td><td><b>Female</b></td><td><b>Male</b></td></tr>
		// 	<tr><td>35 - 44</td><td><b>4</b></td><td><b>2</b></td></tr>
		// 	<tr><td>45 - 54</td><td><b>5</b></td><td><b>7</b></td></tr>
		// 	<tr><td>55 - 64</td><td><b>21</b></td><td><b>18</b></td></tr>
		// 	<tr><td>65 - 74</td><td><b>11</b></td><td><b>14</b></td></tr>
		// 	<tr><td>75 - 84</td><td><b>10</b></td><td><b>10</b></td></tr>
		// 	<tr><td>85 - 94</td><td><b>2</b></td><td><b>1</b></td></tr>
		// 	<tr><td>95 - 104</td><td><b>1</b></td><td><b></b></td></tr>
		// 	<tr><td>TOTAL</td><td>54</td><td>52</td></tr>
		// 	</tbody></table>

		// 	<jpgraph table="tbl_1" type="bar" title="New subscriptions" label-y="% patients" label-x="Age group" series="cols" data-row-end="-1" show-values="1" width="600" legend-overlap="1" hide-grid="1" hide-y-axis="1" />
		// 	';

		$html .= '
			<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="5">
			<thead>';
		$html .= '
					<tr>
						<td width="15%" rowspan="2"></td>
						<td width="6%" align="center" class="teste center bege">A Digitar</td>
						<td width="6%" align="center" class="teste center bege">A Distribuir</td>
						<td width="6%" align="center" class="teste center bege">Bloqueio Cobrança</td>
						<td width="6%" align="center" class="teste center bege">Bloqueio Comercial</td>
						<td width="6%" align="center" class="teste center verde">Backorder</td>
						<td width="6%" align="center" class="teste center verde">Pedidos</td>
						<td width="6%" align="center" class="teste verde">TOTAL VENDA</td>
						<td width="12%" align="center" class="teste center verde">TOTAL VENDA +  A DIGITAR</td>
						<td width="8%" class="teste center azul">TOTAL VENDA + ESTIMATIVA</td>
						<td width="8%" class="teste center azul">META</td>
						<td width="8%" class="teste center azul">% REALIZADO</td>
						<td width="8%" class="teste center laranja">A REALIZAR</td>
						<td width="8%" class="teste center laranja">A REALIZAR / DIA</td>

					</tr>';


		$html .= '
					<tr style="font-size=8px;">
						<td class="teste center"><small>A</small></td>
						<td class="teste center"><small>B</small></td>
						<td class="teste center"><small>C</small></td>
						<td class="teste center"><small>D</small></td>
						<td class="teste center"><small>E</small></td>
						<td class="teste center"><small>F</small></td>
						<td class="teste center"><small>G = E + F</small></td>
						<td class="teste center"><small>H = G + A</small></td>
						<td class="teste center"><small>H = A+B+C+D+E+F</small></td>
						<td class="teste center"><small>I</small></td>
						<td class="teste center"><small>J = H/I</small></td>
						<td class="teste center"><small>K = I-H</small></td>
						<td class="teste center"><small>K/14</small></td>

					</tr>';



		$html .= '
					<tr>
						<td class="teste" width="15%" ><b>NACIONAL</b></td>
						<td class="teste" width="8%" align="right"></td>
						<td class="teste" width="8%" align="right">'.number_format($nacional_bloqueio_a_distribuir,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($nacional_bloqueio_cobranca,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($nacional_bloqueio_comercial,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($nacional_bloqueio_bo,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($nacional_bloqueio_pedidos,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($nacional_bloqueio_venda,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($nacional_bloqueio_venda,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($nacional_bloqueio_venda_estimativa,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($nacional_bloqueio_meta,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format(($nacional_bloqueio_venda_estimativa/$nacional_bloqueio_meta)*100,1,',','.').'%</td>
						<td class="teste" width="8%" align="right">'.number_format($nacional_bloqueio_a_realizar,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($nacional_bloqueio_a_realizar_dia,0,',','.').'</td>

					</tr>';

		$html .= '</thead>
				  <tbody>';

		foreach ($nacional as $linha) {


				$html .= '<tr>
						<td>'.$linha->supervisor.'</td>
						<td align="right">-</td>
						<td align="right">'.number_format($linha->a_distribuir,0,',','.').'</td>
						<td align="right">'.number_format($linha->bloqueio_cobranca,0,',','.').'</td>
						<td align="right">'.number_format($linha->bloqueio_comercial,0,',','.').'</td>
						<td align="right">'.number_format($linha->backorder,0,',','.').'</td>
						<td align="right">'.number_format($linha->pedidos,0,',','.').'</td>
						<td align="right">'.number_format($linha->total_venda,0,',','.').'</td>
						<td align="right">'.number_format($linha->total_venda,0,',','.').'</td>
						<td align="right">'.number_format($linha->total_venda_estimativa,0,',','.').'</td>
						<td align="right">'.number_format($linha->meta,0,',','.').'</td>
						<td align="right">'.number_format($linha->realizado,1,',','.').'%</td>
						<td align="right">'.number_format($linha->a_realizar,0,',','.').'</td>
						<td align="right">'.number_format($linha->a_realizar_dia,0,',','.').'</td>
					  </tr>';


		}
					
		$html .= '
					<tr>
						<td class="teste" width="15%" ><b>PRIME</b></td>
						<td class="teste" width="8%" align="right"></td>
						<td class="teste" width="8%" align="right">'.number_format($prime_bloqueio_a_distribuir,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($prime_bloqueio_cobranca,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($prime_bloqueio_comercial,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($prime_bloqueio_bo,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($prime_bloqueio_pedidos,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($prime_bloqueio_venda,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($prime_bloqueio_venda,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($prime_bloqueio_venda_estimativa,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($prime_bloqueio_meta,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format(($prime_bloqueio_venda_estimativa/$prime_bloqueio_meta)*100,1,',','.').'%</td>
						<td class="teste" width="8%" align="right">'.number_format($prime_bloqueio_a_realizar,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($prime_bloqueio_a_realizar_dia,0,',','.').'</td>

					</tr>';


		foreach ($prime as $linha) {


				$html .= '<tr>
						<td>'.substr($linha->supervisor,0,25).'</td>
						<td align="right">-</td>
						<td align="right">'.number_format($linha->a_distribuir,0,',','.').'</td>
						<td align="right">'.number_format($linha->bloqueio_cobranca,0,',','.').'</td>
						<td align="right">'.number_format($linha->bloqueio_comercial,0,',','.').'</td>
						<td align="right">'.number_format($linha->backorder,0,',','.').'</td>
						<td align="right">'.number_format($linha->pedidos,0,',','.').'</td>
						<td align="right">'.number_format($linha->total_venda,0,',','.').'</td>
						<td align="right">'.number_format($linha->total_venda,0,',','.').'</td>
						<td align="right">'.number_format($linha->total_venda_estimativa,0,',','.').'</td>
						<td align="right">'.number_format($linha->meta,0,',','.').'</td>
						<td align="right">'.number_format($linha->realizado,1,',','.').'%</td>
						<td align="right">'.number_format($linha->a_realizar,0,',','.').'</td>
						<td align="right">'.number_format($linha->a_realizar_dia,0,',','.').'</td>
					  </tr>';


		}

		$html .= '
					<tr>
						<td class="teste" width="15%" ><b>NOVOS CANAIS</b></td>
						<td class="teste" align="right">-</td>
						<td class="teste" align="right">'.number_format($novoscanais_bloqueio_a_distribuir,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($novoscanais_bloqueio_cobranca,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($novoscanais_bloqueio_comercial,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($novoscanais_bloqueio_bo,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($novoscanais_bloqueio_pedidos,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($novoscanais_bloqueio_venda,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($novoscanais_bloqueio_venda,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($novoscanais_bloqueio_venda_estimativa,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($novoscanais_bloqueio_meta,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format(($novoscanais_bloqueio_venda_estimativa/$novoscanais_bloqueio_meta)*100,1,',','.').'%</td>
						<td class="teste" width="8%" align="right">'.number_format($novoscanais_bloqueio_a_realizar,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($novoscanais_bloqueio_a_realizar_dia,0,',','.').'</td>


					</tr>';

		$html .= '
					<tr>
						<td class="teste" width="15%" ><b>ATITUDE POINT</b></td>
						<td class="teste" align="right">-</td>
						<td class="teste" align="right">'.number_format($atp_bloqueio_a_distribuir,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($atp_bloqueio_cobranca,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($atp_bloqueio_comercial,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($atp_bloqueio_bo,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($atp_bloqueio_pedidos,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($atp_bloqueio_venda,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($atp_bloqueio_venda,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($atp_bloqueio_venda_estimativa,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($atp_bloqueio_meta,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format(($atp_bloqueio_venda_estimativa/$atp_bloqueio_meta)*100,1,',','.').'%</td>
						<td class="teste" width="8%" align="right">'.number_format($atp_bloqueio_a_realizar,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($atp_bloqueio_a_realizar_dia,0,',','.').'</td>
					</tr>';

		$html .= '
					<tr>
						<td class="teste" width="15%" ><b>DIRETO</b></td>
						<td class="teste" align="right">-</td>
						<td class="teste" align="right">'.number_format($direto_bloqueio_a_distribuir,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($direto_bloqueio_cobranca,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($direto_bloqueio_comercial,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($direto_bloqueio_bo,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($direto_bloqueio_pedidos,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($direto_bloqueio_venda,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($direto_bloqueio_venda,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($direto_bloqueio_venda_estimativa,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($direto_bloqueio_meta,0,',','.').'</td>
						<td class="teste" width="8%" align="right">0%</td>
						<td class="teste" width="8%" align="right">'.number_format($direto_bloqueio_a_realizar,0,',','.').'</td>
						<td class="teste" width="8%" align="right">'.number_format($direto_bloqueio_a_realizar_dia,0,',','.').'</td>

					</tr>';

		$html .= '

					<tr>
						<td width="15%" class="cinza" ><b>TOTAL GO</b></td>
						<td class="teste bege" align="right"></td>
						<td class="teste bege" align="right">'.number_format($totalgo_bloqueio_a_distribuir,0,',','.').'</td>
						<td class="teste bege" width="8%" align="right">'.number_format($totalgo_bloqueio_cobranca,0,',','.').'</td>
						<td class="teste bege" width="8%" align="right">'.number_format($totalgo_bloqueio_comercial,0,',','.').'</td>
						<td class="teste verde" width="8%" align="right">'.number_format($totalgo_bloqueio_bo,0,',','.').'</td>
						<td class="teste verde" width="8%" align="right">'.number_format($totalgo_bloqueio_pedidos,0,',','.').'</td>
						<td class="teste verde" width="8%" align="right">'.number_format($totalgo_bloqueio_venda,0,',','.').'</td>
						<td class="teste verde" width="8%" align="right">'.number_format($totalgo_bloqueio_venda,0,',','.').'</td>
						<td class="teste azul" width="8%" align="right">'.number_format($totalgo_bloqueio_venda_estimativa,0,',','.').'</td>
						<td class="teste azul" width="8%" align="right">'.number_format($totalgo_bloqueio_meta,0,',','.').'</td>
						<td class="teste azul" width="8%" align="right">'.number_format(($totalgo_bloqueio_venda_estimativa/$totalgo_bloqueio_meta)*100,1,',','.').'%</td>
						<td class="teste laranja" width="8%" align="right">'.number_format($totalgo_bloqueio_a_realizar,0,',','.').'</td>
						<td class="teste laranja" width="8%" align="right">'.number_format($totalgo_bloqueio_a_realizar_dia,0,',','.').'</td>

					</tr>
					<tr>
						<td colspan="14"><br><br></td>
					</tr>';

		$html .= '
					<tr>
						<td class="teste azul" width="15%" ><b>LUXO</b></td>
						<td class="teste azul-claro" align="right">-</td>
						<td class="teste azul-claro" align="right">'.number_format($luxo_bloqueio_a_distribuir,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($luxo_bloqueio_cobranca,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($luxo_bloqueio_comercial,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($luxo_bloqueio_bo,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($luxo_bloqueio_pedidos,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($luxo_bloqueio_venda,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($luxo_bloqueio_venda,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($luxo_bloqueio_venda_estimativa,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($luxo_bloqueio_meta,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format(($luxo_bloqueio_venda_estimativa/$luxo_bloqueio_meta)*100,1,',','.').'%</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($luxo_bloqueio_a_realizar,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($luxo_bloqueio_a_realizar_dia,0,',','.').'</td>

					</tr>';

		$html .= '
					<tr>
						<td class="teste azul" width="15%" ><b>PREMIUM</b></td>
						<td class="teste azul-claro" align="right">-</td>
						<td class="teste azul-claro" align="right">'.number_format($premium_bloqueio_a_distribuir,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($premium_bloqueio_cobranca,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($premium_bloqueio_comercial,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($premium_bloqueio_bo,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($premium_bloqueio_pedidos,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($premium_bloqueio_venda,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($premium_bloqueio_venda,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($premium_bloqueio_venda_estimativa,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($premium_bloqueio_meta,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format(($premium_bloqueio_venda_estimativa/$premium_bloqueio_meta)*100,1,',','.').'%</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($premium_bloqueio_a_realizar,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($premium_bloqueio_a_realizar_dia,0,',','.').'</td>

					</tr>';

		$html .= '
					<tr>
						<td class="teste azul" width="15%" ><b>DIRETO</b></td>
						<td class="teste azul-claro" align="right">-</td>
						<td class="teste azul-claro" align="right">'.number_format($kdireto_bloqueio_a_distribuir,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($kdireto_bloqueio_cobranca,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($kdireto_bloqueio_comercial,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($kdireto_bloqueio_bo,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($kdireto_bloqueio_pedidos,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($kdireto_bloqueio_venda,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($kdireto_bloqueio_venda,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($kdireto_bloqueio_venda_estimativa,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($kdireto_bloqueio_meta,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($kdireto_bloqueio_perc_realizado,0,',','.').'%</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($kdireto_bloqueio_a_realizar,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($kdireto_bloqueio_a_realizar_dia,0,',','.').'</td>

					</tr>';

		$html .= '
					<tr>
						<td class="teste azul" width="15%" ><b>PUMA</b></td>
						<td class="teste azul-claro" align="right">-</td>
						<td class="teste azul-claro" align="right">'.number_format($puma_bloqueio_a_distribuir,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($puma_bloqueio_cobranca,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($puma_bloqueio_comercial,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($puma_bloqueio_bo,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($puma_bloqueio_pedidos,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($puma_bloqueio_venda,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($puma_bloqueio_venda,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($puma_bloqueio_venda_estimativa,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($puma_bloqueio_meta,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($puma_bloqueio_perc_realizado,0,',','.').'%</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($puma_bloqueio_a_realizar,0,',','.').'</td>
						<td class="teste azul-claro" width="8%" align="right">'.number_format($puma_bloqueio_a_realizar_dia,0,',','.').'</td>

					</tr>';

		$html .= '
					<tr>
						<td class="teste azul-escuro" width="15%" ><b>TOTAL KERING</b></td>
						<td class="teste azul" align="right">-</td>
						<td class="teste azul" align="right">'.number_format($totalkering_bloqueio_a_distribuir,0,',','.').'</td>
						<td class="teste azul" width="8%" align="right">'.number_format($totalkering_bloqueio_cobranca,0,',','.').'</td>
						<td class="teste azul" width="8%" align="right">'.number_format($totalkering_bloqueio_comercial,0,',','.').'</td>
						<td class="teste azul" width="8%" align="right">'.number_format($totalkering_bloqueio_bo,0,',','.').'</td>
						<td class="teste azul" width="8%" align="right">'.number_format($totalkering_bloqueio_pedidos,0,',','.').'</td>
						<td class="teste azul" width="8%" align="right">'.number_format($totalkering_bloqueio_venda,0,',','.').'</td>
						<td class="teste azul" width="8%" align="right">'.number_format($totalkering_bloqueio_venda,0,',','.').'</td>
						<td class="teste azul" width="8%" align="right">'.number_format($totalkering_bloqueio_venda_estimativa,0,',','.').'</td>
						<td class="teste azul" width="8%" align="right">'.number_format($totalkering_bloqueio_meta,0,',','.').'</td>
						<td class="teste azul" width="8%" align="right">'.number_format(($totalkering_bloqueio_venda_estimativa/$totalkering_bloqueio_meta)*100,1,',','.').'%</td>
						<td class="teste azul" width="8%" align="right">'.number_format($totalkering_bloqueio_a_realizar,0,',','.').'</td>
						<td class="teste azul" width="8%" align="right">'.number_format($totalkering_bloqueio_a_realizar_dia,0,',','.').'</td>

					</tr>
					<tr>
						<td colspan="14"><br><br></td>
					</tr>';


		$html .= '<tr>
						<td width="15%" class="cinza" ><b>TOTAL GO + KERING</b></td>
						<td class="teste bege" align="right"></td>
						<td class="teste bege" align="right">'.number_format($totalgokering_bloqueio_a_distribuir,0,',','.').'</td>
						<td class="teste bege" width="8%" align="right">'.number_format($totalgokering_bloqueio_cobranca,0,',','.').'</td>
						<td class="teste bege" width="8%" align="right">'.number_format($totalgokering_bloqueio_comercial,0,',','.').'</td>
						<td class="teste verde" width="8%" align="right">'.number_format($totalgokering_bloqueio_bo,0,',','.').'</td>
						<td class="teste verde" width="8%" align="right">'.number_format($totalgokering_bloqueio_pedidos,0,',','.').'</td>
						<td class="teste verde" width="8%" align="right">'.number_format($totalgokering_bloqueio_venda,0,',','.').'</td>
						<td class="teste verde" width="8%" align="right">'.number_format($totalgokering_bloqueio_venda,0,',','.').'</td>
						<td class="teste azul" width="8%" align="right">'.number_format($totalgokering_bloqueio_venda_estimativa,0,',','.').'</td>
						<td class="teste azul" width="8%" align="right">'.number_format($totalgokering_bloqueio_meta,0,',','.').'</td>
						<td class="teste azul" width="8%" align="right">'.number_format(($totalgokering_bloqueio_venda_estimativa/$totalgokering_bloqueio_meta)*100,1,',','.').'%</td>
						<td class="teste laranja" width="8%" align="right">'.number_format($totalgokering_bloqueio_a_realizar,0,',','.').'</td>
						<td class="teste laranja" width="8%" align="right">'.number_format($totalgokering_bloqueio_a_realizar_dia,0,',','.').'</td>

					</tr>';

		$html .= '</table>';		

		$html .= '</body></html>';



		$rodape = '';

		// Write some HTML code:
		$mpdf->SetHTMLFooter($rodape);
		//$mpdf->WriteHTML($stylesheet,1);
		$mpdf->WriteHTML($html);
		$mpdf->allow_charset_conversion=true; 
		$mpdf->charset_in='UTF-8';
		// Output a PDF file directly to the browser
		$fileName = "Estimativa Comercial - ".date("d/m/Y");
		// Output a PDF file directly to the browser
		$mpdf->Output($fileName.".pdf","I");	
			
	}


}
