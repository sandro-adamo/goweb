<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Painel extends Model
{

	protected $connection = 'brasil';

	public static function listaModelos($agrupamento, $filtros, $ordem = '') {


		$item = \DB::connection('go')->select("select base.*, custo_2019, moeda, trocas, recall, ifnull(vendas.vendas,0) vendas, ifnull(vendas.vda30dd,0) vda30dd, 
		ifnull(vendas.vda60dd,0) vda60dd,
		ifnull(vendas.vda90dd,0) vda90dd,
		ifnull(vendas.a_180dd,0) a_180dd,
				
		
		
		/*ifnull(saldos.sld_total,0) sld_total,*/ ifnull(orc.qtde_valido,0) orcamentos_valido, ifnull(orc.qtde,0) orcamentos , ifnull(saldo.brasil,0) brasil, ifnull(saldo.cet,0) cet, ifnull(saldo.etq,0) etq, ifnull(saldo.cep,0) cep, ifnull(saldo.saldo_manutencao, 0) saldo_manutencao,
			ifnull(saldo.brasil,0)+ifnull(saldo.cet,0)+ifnull(saldo.etq,0)+ ifnull(saldo.cep,0) totaletq,
			ifnull(saldo.mostruarios,0) mostruarios,
			
			
			(select count(id_item)
			from historicos
			left join itens on itens.id = id_item
			where itens.modelo = base.modelo
			and categoria <> 'caracteristica') as historico,
			(select sum(qtde)
			from compras_itens
			left join itens on compras_itens.item = itens.secundario
			where itens.modelo = base.modelo
			and id_compra = '201977'
			and status in ('aberto', 'enviado','confirmado')
			) as pedidoaberto

			from (

			select  agrup, grife, modelo, anomod, (select id from itens where modelo = a.modelo limit 1) as id_item,

			(select case when id is not null then 'sim' else 'nao' end from itens_adv where modelo = a.modelo and categoria = 'adv' limit 1) as adv,
			(select case when id is not null then 'sim' else 'nao' end from itens_adv where modelo = a.modelo and categoria = 'midia' limit 1) as midia,

			(select secundario from itens b where a.modelo = b.modelo and codtipoitem = '006'  
			and codtipoarmaz <> 'o'  and codtipoitem = '006'
			and colmod <>'COLEÇÃO EUROPA' and colitem <>'COLEÇÃO EUROPA'
			and colmod <> 'CANCELADO'
			order by secundario limit 1) as item,
			
			(select avg(valortabela) from itens b where a.modelo = b.modelo and codtipoarmaz <> 'o'  and codtipoitem = '006'
			and colmod <>'COLEÇÃO EUROPA' 
			and colmod <> 'CANCELADO') as valor, 
			
			(select avg(ultcusto) from itens b where a.modelo = b.modelo
			) as custo, 
			
			(select colmod from itens b where a.modelo = b.modelo 
			and codtipoarmaz <> 'o'  and codtipoitem = '006'
			and colmod <>'COLEÇÃO EUROPA' 
			and colmod <> 'CANCELADO'
			limit 1) as colecao, 
			
			(select clasmod from itens b where a.modelo = b.modelo 
			and codtipoarmaz <> 'o'  and codtipoitem = '006'
			and colmod <>'COLEÇÃO EUROPA' 
			and colmod <> 'CANCELADO'
			limit 1) as clasmod,
			
			count(secundario) as itens

			from itens a
			where a.codtipoarmaz <> 'o'  and codtipoitem = '006'
			and colmod <>'COLEÇÃO EUROPA' and colitem <>'COLEÇÃO EUROPA'
			and colmod <> 'CANCELADO' and a.agrup = '$agrupamento'  $filtros
			group by agrup, grife, modelo, anomod 
			) as base


			/**vendas sinteticas**/
			left join (select modelo, sum(ult_30dd) as vda30dd , sum(ult_60dd) as vda60dd , sum(ult_90dd) as vda90dd, sum(a_180dd) a_180dd, sum(vendastt) vendas from vendas_sint  group by modelo ) as vendas
			on vendas.modelo = base.modelo



			/**orcamentos**/
			left join (select b.modelo, sum(orcamentos.orctt) as qtde, sum(orcamentos.orcvalido) as qtde_valido from orcamentos left join itens b on b.id = orcamentos.curto group by b.modelo
			) as orc
			on orc.modelo = base.modelo


			/**saldos**/
			left join (
				select modelo, sum(brasil) brasil, sum(saldo_manutencao) saldo_manutencao, sum(cet) cet, sum(mostruarios) mostruarios, sum(etq) etq, sum(cep) cep
				from (
				select a.secundario, b.modelo, 
				sum(disponivel+em_beneficiamento) as brasil, sum(saldo_manutencao) as saldo_manutencao,
				sum(cet+(saldo_parte)) as cet, sum(saldo_most) as mostruarios,

				sum(estoque) as etq,
				sum(producao)  as cep

				from saldos a 
				left join itens b on b.id = a.curto
                left join producoes_sint on producoes_sint.id = a.curto
				group by a.secundario, b.modelo

				) as fim group by modelo
			) as saldo
			on saldo.modelo = base.modelo
			
			 left join (select avg(custo) custo_2019, moeda, modelo
			from custos_2019
			group by moeda, modelo
			) as custo_2019 on custo_2019.modelo = base.modelo
			
			left join (select sum(qtde) as trocas, modelo
			from trocas
			left join itens  bb on id_item = bb.id
			group by bb.modelo) as trocas on trocas.modelo = base.modelo
            
            left join (
            select modelo, case when recall.id is not null then 'sim' else 'nao' end as recall
            from recall 
            left join itens on itens.id = recall.id_item
            where  dt_libera = '0000-00-00' limit 1) as recall on recall.modelo = base.modelo
			

			$ordem");


		return $item;

	}

	public static function listaFavoritos() {

		$id_usuario = \Auth::id();

		$item = \DB::connection('go')->select("select base.*, custo_2019, moeda, trocas, recall, ifnull(vendas.vendas,0) vendas, ifnull(vendas.vda30dd,0) vda30dd, 
		ifnull(vendas.vda60dd,0) vda60dd,
		ifnull(vendas.vda90dd,0) vda90dd,
		ifnull(vendas.a_180dd,0) a_180dd,
				
		
		
		/*ifnull(saldos.sld_total,0) sld_total,*/ ifnull(orc.qtde_valido,0) orcamentos_valido, ifnull(orc.qtde,0) orcamentos , ifnull(saldo.brasil,0) brasil, ifnull(saldo.cet,0) cet, ifnull(saldo.etq,0) etq, ifnull(saldo.cep,0) cep, ifnull(saldo.saldo_manutencao, 0) saldo_manutencao,
			ifnull(saldo.brasil,0)+ifnull(saldo.cet,0)+ifnull(saldo.etq,0)+ ifnull(saldo.cep,0) totaletq,
			ifnull(saldo.mostruarios,0) mostruarios,
			
			
			(select count(id_item)
			from historicos
			left join itens on itens.id = id_item
			where itens.modelo = base.modelo
			and categoria <> 'caracteristica') as historico,
			(select sum(qtde)
			from compras_itens
			left join itens on compras_itens.item = itens.secundario
			where itens.modelo = base.modelo
			and id_compra = '201977'
			and status in ('aberto', 'enviado','confirmado')
			) as pedidoaberto

			from (

			select  agrup, grife, a.modelo, anomod, (select id from itens where modelo = a.modelo limit 1) as id_item,

			(select case when id is not null then 'sim' else 'nao' end from itens_adv where modelo = a.modelo and categoria = 'adv' limit 1) as adv,
			(select case when id is not null then 'sim' else 'nao' end from itens_adv where modelo = a.modelo and categoria = 'midia' limit 1) as midia,

			(select secundario from itens b where a.modelo = b.modelo and codtipoitem = '006'  
			and codtipoarmaz <> 'o'  and codtipoitem = '006'
			and colmod <>'COLEÇÃO EUROPA' and colitem <>'COLEÇÃO EUROPA'
			and colmod <> 'CANCELADO'
			order by secundario limit 1) as item,
			
			(select avg(valortabela) from itens b where a.modelo = b.modelo and codtipoarmaz <> 'o'  and codtipoitem = '006'
			and colmod <>'COLEÇÃO EUROPA' 
			and colmod <> 'CANCELADO') as valor, 
			
			(select avg(ultcusto) from itens b where a.modelo = b.modelo
			) as custo, 
			
			(select colmod from itens b where a.modelo = b.modelo 
			and codtipoarmaz <> 'o'  and codtipoitem = '006'
			and colmod <>'COLEÇÃO EUROPA' 
			and colmod <> 'CANCELADO'
			limit 1) as colecao, 
			
			(select clasmod from itens b where a.modelo = b.modelo 
			and codtipoarmaz <> 'o'  and codtipoitem = '006'
			and colmod <>'COLEÇÃO EUROPA' 
			and colmod <> 'CANCELADO'
			limit 1) as clasmod,
			
			count(secundario) as itens

			from itens a
			right join favoritos f on f.modelo = a.modelo and f.id_usuario = $id_usuario
			where a.codtipoarmaz <> 'o'  and codtipoitem = '006'
			and colmod <>'COLEÇÃO EUROPA' and colitem <>'COLEÇÃO EUROPA'
			and colmod <> 'CANCELADO'  
			group by agrup, grife, modelo, anomod 
			) as base


			/**vendas sinteticas**/
			left join (select modelo, sum(ult_30dd) as vda30dd , sum(ult_60dd) as vda60dd , sum(ult_90dd) as vda90dd, sum(a_180dd) a_180dd, sum(vendastt) vendas from vendas_sint  group by modelo ) as vendas
			on vendas.modelo = base.modelo



			/**orcamentos**/
			left join (select b.modelo, sum(orcamentos.orctt) as qtde, sum(orcamentos.orcvalido) as qtde_valido from orcamentos left join itens b on b.id = orcamentos.curto group by b.modelo
			) as orc
			on orc.modelo = base.modelo


			/**saldos**/
			left join (
				select modelo, sum(brasil) brasil, sum(saldo_manutencao) saldo_manutencao, sum(cet) cet, sum(mostruarios) mostruarios, sum(etq) etq, sum(cep) cep
				from (
				select a.secundario, b.modelo, 
				sum(disponivel+em_beneficiamento) as brasil, sum(saldo_manutencao) as saldo_manutencao,
				sum(cet+(saldo_parte)) as cet, sum(saldo_most) as mostruarios,

				sum(estoque) as etq,
				sum(producao)  as cep

				from saldos a 
				left join itens b on b.id = a.curto
                left join producoes_sint on producoes_sint.id = a.curto
				group by a.secundario, b.modelo

				) as fim group by modelo
			) as saldo
			on saldo.modelo = base.modelo
			
			 left join (select avg(custo) custo_2019, moeda, modelo
			from custos_2019
			group by moeda, modelo
			) as custo_2019 on custo_2019.modelo = base.modelo
			
			left join (select sum(qtde) as trocas, modelo
			from trocas
			left join itens  bb on id_item = bb.id
			group by bb.modelo) as trocas on trocas.modelo = base.modelo
            
            left join (
            select modelo, case when recall.id is not null then 'sim' else 'nao' end as recall
            from recall 
            left join itens on itens.id = recall.id_item
            where  dt_libera = '0000-00-00' limit 1) as recall on recall.modelo = base.modelo");


		return $item;

	}
	public static function listaItens($modelo) {

		$itens = \DB::connection('go')->select("
			select 1 as ordem, base.*, ifnull(vendas.vendas,0) vendas,ifnull(vendas.vda30dd,0) vda30dd,ifnull(vendas.vda60dd,0) vda60dd,
		ifnull(vendas.vda90dd,0) vda90dd,
		ifnull(vendas.a_180dd,0) a_180dd,
				 /*ifnull(saldos.sld_total,0) sld_total,*/ ifnull(orc.qtde_valido,0) orcamentos_valido, ifnull(orc.qtde,0) orcamentos , ifnull(saldo.brasil,0) brasil, ifnull(saldo.cet,0) cet, ifnull(saldo.etq,0) etq, ifnull(saldo.cep,0) cep, ifnull(saldo.saldo_manutencao,0) saldo_manutencao,
			ifnull(saldo.brasil,0)+ifnull(saldo.cet,0)+ifnull(saldo.etq,0)+ ifnull(saldo.cep,0)
			totaletq,
				
			disponivel-ifnull(orc.qtde_valido,0) as disponivel_venda,
			ifnull(saldo.mostruarios,0) mostruarios,
			custo_2019,
			moeda,
			trocas,
			(select count(id_item)
					
			from historicos
			where base.id = id_item
			and categoria <> 'caracteristica'
			) as historico,
			(select sum(qtde)
			from compras_itens
			
			where compras_itens.item = base.secundario
			and status in ('aberto', 'enviado','confirmado')
			and id_compra = '201977'
			) as pedidoaberto
			
			from (

			select id,grife,agrup, modelo, secundario, anomod, tamolho, colitem, clasitem , codstatusatual, statusatual, ean, descricao,
				(select avg(ultcusto) from itens b where a.secundario = b.secundario) as custo, 
			(select avg(valortabela) from itens b where a.secundario = b.secundario) as valor,
			(select case when id is not null then 'sim' else 'nao' end from recall where item = a.secundario and dt_libera = '0000-00-00' limit 1) as recall,

			(select case when id is not null then 'sim' else 'nao' end from itens_adv where secundario = a.secundario and categoria = 'midia' limit 1) as midia,
			(select case when id is not null then 'sim' else 'nao' end from itens_adv where secundario = a.secundario limit 1) as adv, fornecedor as fornecedor1, clasmod, codtipoarmaz  as tipoarmazenamento
			
			from itens a
			where a.codtipoarmaz <> 'o' and a.modelo = '$modelo'  
			and codtipoarmaz <> 'o'  and codtipoitem = '006'
			and colmod <>'COLE??O EUROPA'
			and colmod <> 'CANCELADO'
			group by id, grife,agrup, modelo, secundario, anomod, tamolho, colitem, clasitem, codstatusatual,statusatual
			) as base


			/**vendas sinteticas**/
			left join (select  sum(ult_30dd) as vda30dd , sum(ult_60dd) as vda60dd , sum(ult_90dd) as vda90dd,sum(a_180dd) a_180dd, sum(vendastt) vendas, curto
		    from vendas_sint  group by curto ) as vendas
			on vendas.curto = base.id


			
			/**orcamentos**/
		    left join (select b.id, sum(orcamentos.orctt) as qtde, sum(orcamentos.orcvalido) as qtde_valido from orcamentos 
		    left join itens b on b.id = orcamentos.curto group by b.id
		    ) as orc
		    on orc.id = base.id
			
			
			/**saldos**/
		    left join (
				select  b.id,
				sum(disponivel+em_beneficiamento) as brasil, sum(saldo_manutencao) as saldo_manutencao, sum(/*cet_benef*/+cet+(saldo_parte)) as cet, sum(saldo_most) as mostruarios,		    
				sum(estoque) as etq,
				sum(producao)  as cep,
				sum(disponivel) as disponivel

				from saldos a 
				left join itens b on b.id = a.curto
                left join producoes_sint on producoes_sint.id = a.curto
				group by b.id	
		    ) as saldo
		    on saldo.id = base.id
			
			left join 
            (select sum(qtde) trocas, id_item
			from trocas
			group by id_item) as trocas on trocas.id_item = base.id
			
			left join (
            select id_item, case when recall.id is not null then 'sim' else 'nao' end as recall
            from recall 
            where  dt_libera = '0000-00-00' ) as recall on recall.id_item = base.id
			
			 left join (select avg(custo) custo_2019, moeda, secundario
			from custos_2019
			group by moeda, secundario
			) as custo_2019 on custo_2019.secundario = base.secundario
			
			 
			
			
			
			union all		
		
			select 2 as ordem, null as id,null as grife,null as agrup,modelo as modelo,referencia as secundario,null as anomod,null as tamolho,null as colitem,null as clasitem,'NOVO' as codstatusatual,'NOVO' as statusatual,null as ean,referencia as descricao,0 as custo,0 as valor,null as recall,null as midia,null as adv,'' as fornecedor, '' as clasmod,'' as tipoarmazenamento, 0 as vendas,0 as vda30dd,0 as vda60dd,0 as vda90dd,0 as a_180dd,0 as orcamentos_valido,0 as orcamentos,0 as brasil,0 as cet,0 as etq,0 as cep,0 as saldo_manutencao,0 as totaletq,0 as disponivel_venda,0 as mostruarios,0 as custo_2019,0 as moeda,0 as trocas,null as historico, null as pedidoaberto
			from itens_novos
			where modelo = '$modelo'
			
			order by ordem, a_180dd desc, secundario
			
		"); 

		return $itens;

	}



	public static function listaItem($id) {

		$itens = \DB::connection('go')->select("
			select base.*, ifnull(vendas.vendas,0) vendas, ifnull(vendas.vda30dd,0) vda30dd, ifnull(vendas.vda60dd,0) vda60dd,
		ifnull(vendas.vda90dd,0) vda90dd,
		ifnull(vendas.a_180dd,0) a_180dd,
				 /*ifnull(saldos.sld_total,0) sld_total,*/ ifnull(orc.qtde_valido,0) orcamentos_valido , ifnull(orc.qtde,0) orcamentos , ifnull(saldo.brasil,0) brasil, ifnull(saldo.cet,0) cet, ifnull(saldo.etq,0) etq, ifnull(saldo.cep,0) cep, ifnull(saldo.saldo_manutencao,0) saldo_manutencao,
			ifnull(saldo.brasil,0)+ifnull(saldo.cet,0)+ifnull(saldo.etq,0)+ ifnull(saldo.cep,0)
			totaletq,
			ifnull(saldo.mostruarios,0) mostruarios,
			(select custo
			from custos_2019
			where custos_2019.secundario = base.secundario
			order by custo desc
			limit 1
			
			) as custo_2019,
			(select moeda
			from custos_2019
			where custos_2019.secundario = base.secundario
			order by custo desc
			limit 1
			
			) as moeda,
			(select sum(qtde)
			from trocas
			where trocas.secundario = base.secundario
			group by trocas.secundario) as trocas,
			'' as adv, '' as midia,
			(select sum(qtde)
			from compras_itens
			
			where compras_itens.item = base.secundario
			and status in ('aberto', 'enviado','confirmado')
			and id_compra = '201977'
			) as pedidoaberto

			from (

			select id,grife,agrup, modelo, secundario, anomod, tamolho, colitem, clasitem , codstatusatual, statusatual, ean, descricao, tamhaste, tamponte,  corarm1, codarm2, corhas1, corhas2,  corlente, tecnologia, classecontabil, ncm, 
				(select avg(ultcusto) from itens b where a.secundario = b.secundario) as custo, 
			(select avg(valortabela) from itens b where a.secundario = b.secundario) as valor,
			(select case when id is not null then 'sim' else 'nao' end from recall where item = a.secundario and dt_libera = '0000-00-00' limit 1) as recall 
			
			from itens a
			where  a.id = '$id' 
			group by id, grife,agrup, modelo, secundario, anomod, tamolho, colitem, clasitem, codstatusatual,statusatual
			) as base


			/**vendas sinteticas**/
			left join (select modelo, secundario, sum(ult_30dd) as vda30dd , sum(ult_60dd) as vda60dd , sum(ult_90dd) as vda90dd,sum(a_180dd) a_180dd, sum(vendastt) vendas 
		    from vendas_sint  group by modelo, secundario ) as vendas
			on vendas.secundario = base.secundario


			/**saldos do ultimo processa apagar
			left join (
			select modelo, secundario, sum(qtde_exist) qtde_exist, sum(disp_mont) disp_mont, sum(em_mont) em_mont, 
			sum(etq) etq,  sum(cep) cep, sum(cet_manufat) cet_manufat, sum(cet_acabado) cet_acabado,
			sum(qtde_exist+disp_mont+em_mont+etq+cep+cet_manufat+cet_acabado) as sld_total
			from jde_processa 
			where cast(data as date) =  (select cast(max(data) as date) from jde_processa )
			group by modelo, secundario
			) saldos
			on saldos.secundario = base.secundario**/
			
			/**orcamentos**/
		    left join (select b.modelo, b.secundario, sum(orcamentos.orctt) as qtde, sum(orcamentos.orcvalido) as qtde_valido from orcamentos 
		    left join itens b on b.id = orcamentos.curto group by b.modelo, b.secundario
		    ) as orc
		    on orc.secundario = base.secundario
			
			
			/**saldos**/
		    left join (
		    select b.modelo, b.secundario, sum(disponivel+em_beneficiamento) as brasil, sum(saldo_manutencao) as saldo_manutencao,
			sum(/*cet_benef*/+cet+(saldo_parte)) as cet,
		    sum(estoque) as etq, sum(producao) as cep,
			sum(saldo_most) as mostruarios
		    from saldos a left join itens b on b.id = a.curto 
			left join producoes_sint on producoes_sint.cod_sec = a.secundario
		    group by b.modelo, b.secundario
		    ) as saldo
		    on saldo.secundario = base.secundario

			order by vendas.a_180dd, secundario desc
		"); 
		

		return $itens[0];

	}


	public static function listaItensPainel($agrupamento, $filtros, $ordem = '') {

		$itens = \DB::connection('go')->select("
			select base.*, ifnull(vendas.vendas,0) vendas, ifnull(vendas.vda30dd,0) vda30dd, ifnull(vendas.vda60dd,0) vda60dd,
		ifnull(vendas.vda90dd,0) vda90dd,
		ifnull(vendas.a_180dd,0) a_180dd,
				 /*ifnull(saldos.sld_total,0) sld_total,*/ ifnull(orc.qtde_valido,0) orcamentos_valido , ifnull(orc.qtde,0) orcamentos , ifnull(saldo.brasil,0) brasil, ifnull(saldo.cet,0) cet, ifnull(saldo.etq,0) etq, ifnull(saldo.cep,0) cep, ifnull(saldo.saldo_manutencao,0) saldo_manutencao,
			ifnull(saldo.brasil,0)+ifnull(saldo.cet,0)+ifnull(saldo.etq,0)+ ifnull(saldo.cep,0)
			totaletq,
			ifnull(saldo.mostruarios,0) mostruarios,
			(select custo
			from custos_2019
			where custos_2019.secundario = base.secundario
			order by custo desc
			limit 1
			
			) as custo_2019,
			(select moeda
			from custos_2019
			where custos_2019.secundario = base.secundario
			order by custo desc
			limit 1
			
			) as moeda,
			(select sum(qtde)
			from trocas
			where trocas.secundario = base.secundario
			group by trocas.secundario) as trocas,
			(select count(id_item)
					
			from historicos
			where base.id = id_item
			and categoria <> 'caracteristica'
			) as historico,
			'' as adv, '' as midia,
			(select sum(qtde)
			from compras_itens
			
			where compras_itens.item = base.secundario
			and status in ('aberto', 'enviado','confirmado')
			and id_compra = '201977'
			) as pedidoaberto

			from (

			select id, id as id_item, 0 as itens,grife,agrup, secundario as  modelo, secundario, clasitem as clasmod,colitem as colecao, secundario as item, anomod, tamolho, colitem, clasitem, codstatusatual, statusatual,
				(select avg(ultcusto) from itens b where a.secundario = b.secundario) as custo, 
			(select avg(valortabela) from itens b where a.secundario = b.secundario) as valor
			
			from itens a
			where a.agrup = '$agrupamento' and codtipoitem = '006'
			and codtipoarmaz <> 'o'  
			and colmod <>'COLE??O EUROPA'
			and colmod <> 'CANCELADO'
			$filtros 
			group by id, grife,agrup, modelo, secundario, anomod, tamolho, colitem, clasitem , codstatusatual,statusatual
			) as base


			/**vendas sinteticas**/
			left join (select modelo, secundario,sum(ult_30dd) as vda30dd , sum(ult_60dd) as vda60dd , sum(ult_90dd) as vda90dd, sum(a_180dd) a_180dd, sum(vendastt) vendas 
		    from vendas_sint  group by modelo, secundario ) as vendas
			on vendas.secundario = base.secundario


			/**saldos do ultimo processa apagar
			left join (
			select modelo, secundario, sum(qtde_exist) qtde_exist, sum(disp_mont) disp_mont, sum(em_mont) em_mont, 
			sum(etq) etq,  sum(cep) cep, sum(cet_manufat) cet_manufat, sum(cet_acabado) cet_acabado,
			sum(qtde_exist+disp_mont+em_mont+etq+cep+cet_manufat+cet_acabado) as sld_total
			from jde_processa 
			where cast(data as date) =  (select cast(max(data) as date) from jde_processa )
			group by modelo, secundario
			) saldos
			on saldos.secundario = base.secundario**/
			
			/**orcamentos**/
		    left join (select b.modelo, b.secundario, sum(orcamentos.orctt) as qtde, sum(orcamentos.orcvalido) as qtde_valido from orcamentos 
		    left join itens b on b.id = orcamentos.curto group by b.modelo, b.secundario
		    ) as orc
		    on orc.secundario = base.secundario
			
			
			/**saldos**/
		    left join (
		    select b.modelo, b.secundario, sum(disponivel+em_beneficiamento) as brasil, sum(saldo_manutencao) as saldo_manutencao,
			sum(/*cet_benef*/+cet+(saldo_parte)) as cet,
		    sum(estoque) as etq, sum(producao) as cep,
			sum(saldo_most) as mostruarios
		    from saldos a 
			left join itens b on b.id = a.curto 
			left join producoes_sint on producoes_sint.cod_sec = a.secundario
		    group by b.modelo, b.secundario
		    ) as saldo
		    on saldo.secundario = base.secundario

			$ordem
		"); 

		return $itens;

	}


}
