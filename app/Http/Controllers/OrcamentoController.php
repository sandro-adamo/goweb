<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;

class OrcamentoController extends Controller
{
    

	public function listaOrcamentos() {

		$orcamentos = \DB::connection('jde')->select("
    
select cliente, sum(qtde_aberto) qtde_aberto, sum(atende) atende, sum(atende)/sum(qtde_aberto)*100 perc_atende, sum(vlr_aberto) vlr_aberto, sum(vlr_atende) vlr_atende from (
        select cliente, qtde_aberto, disponivel, atende, vlr_unit*qtde_aberto vlr_aberto, vlr_unit*atende vlr_atende from (
    
            select num_linha, numero_pedido, tipo_ped, cliente, codref, referencia, qtde_aberto, disponivel,  vlr_unit,
            case when disponivel > qtde_aberto then qtde_aberto else disponivel end as atende from (
                

                select 
                a.sdlnid as num_linha,
                a.sddoco as numero_pedido,
                a.sddcto as tipo_ped,
                a.sdvr01 as pedido_cliente,
                a.sdan8  as cliente,
                a.sditm as codref,
                a.sdlitm as referencia,
                c.shhold as cod_susp,
                d.drdl01 as desc_cod_susp,
                a.sdlttr as 'Últ status',
                e.drdl01 as 'Desc últ status',
                a.sdnxtr as 'Próx status',
                f.drdl01 as 'Desc próx status',
                (DATEADD(year, a.sdtrdj / 1000, 0) + DATEADD(day, a.sdtrdj % 1000, 0) - 1) as dt_pedido,
                a.sduorg/10000 as qtde_pedida,
                a.sdsoqs/10000 as 'Qtde enviada',
                a.sdsobk/10000 as 'Qtde não atendida',
                a.sdsocn/10000 as 'Qtde cancelada',
                a.sdqtyt/10000 as 'Qtde liberada',
                a.sduorg/10000-a.sdqtyt/10000 as qtde_aberto,
                a.sduprc/10000 as vlr_unit,
                a.sdaexp/100 as 'Preço total',
                a.sdptc  as cod_pgto, 
                o.DRDL01 as desc_cond_pgto

                from proddta.f4211 a
                left join proddta.f0101 b on b.aban8 = a.sdan8
                left join proddta.f4201 c on c.shdoco = a.sddoco and c.shdcto = a.sddcto
                left join prodctl.f0005 d on d.drsy = '42' and d.drrt = 'HC' and rtrim(ltrim(d.drky)) = rtrim(ltrim(c.shhold))
                left join prodctl.f0005 e on e.drsy = '40' and e.drrt = 'AT' and rtrim(ltrim(e.drky)) = rtrim(ltrim(a.sdlttr))
                left join prodctl.f0005 f on f.drsy = '40' and f.drrt = 'AT' and rtrim(ltrim(f.drky)) = rtrim(ltrim(a.sdnxtr))
                left join prodctl.f0005 g on g.drsy = '41' and g.drrt = 'S1' and rtrim(ltrim(g.drky)) = rtrim(ltrim(a.sdsrp1))
                left join prodctl.f0005 h on h.drsy = '41' and h.drrt = 'S2' and rtrim(ltrim(h.drky)) = rtrim(ltrim(a.sdsrp2))
                left join prodctl.f0005 i on i.drsy = '41' and i.drrt = 'S3' and rtrim(ltrim(i.drky)) = rtrim(ltrim(a.sdsrp3))
                left join proddta.f4102 j on j.ibmcu = a.sdmcu and j.ibitm = a.sditm
                left join prodctl.f0005 k on k.drsy = '41' and k.drrt = '08' and rtrim(ltrim(k.drky)) = rtrim(ltrim(j.ibsrp8))
                
                left join proddta.f42160 l on l.sddoco = a.sddoco and l.sddcto = a.sddcto and l.sdkcoo = a.sdkcoo and l.sdlnid = a.sdlnid
                
                left join proddta.f0101 m on m.aban8 = l.sdslsm
                left join prodctl.f0005 n on n.drsy = '01' and n.drrt = '03' and rtrim(ltrim(n.drky)) = rtrim(ltrim(m.abac03))
                left join prodctl.f0005 o on a.sdptc = ltrim(o.drky) and o.drrt = 'PG' and o.drsy = '55'


                where
                -- DATEADD(year, a.sdtrdj / 1000, 0) + DATEADD(day, a.sdtrdj % 1000, 0) - 1  between '2019-10-01 00:00:00.000' and '2019-10-31 00:00:00.000' and 
                (a.sddcto = 'SQ' and a.sdnxtr <> '999' and a.sdsrp1 = '006' and a.sdlttr <> '980' and a.sdlnty <> 'BX' )
                and (a.sdlttr = '510' and a.sdnxtr = '515') 
                -- and a.sdsrp1 = '006'
                -- and sdlitm like 'ah62%'
                -- and a.sddoco = '34696'

            ) as selebase 



            /** SALDO PARA ATENDIMENTO **/
            left join (
                select coditem, 
                case when sum(disponivel) < 0 then 0 else sum(disponivel) end as disponivel 

                from (
                    select
                    limcu as 'Filial',
                    ibitm coditem, 
                    iblitm as item,
                    imdsc1 as 'Desc item',
                    lilocn as 'Local',
                    lipbin as 'Local P-S',
                    lilots as 'Status local',
                    lipqoh/10000 as 'Existente',
                    lipqoh/10000-lipcom/10000-lihcom/10000 as 'Disponivel',
                    liot1p/10000 as 'OutrosPV1',
                    lipcom/10000 as 'ReservaTemporaria',
                    lihcom/10000 as 'ReservaDefinitiva',
                    lipbck/10000 as 'NaoAtendido',
                    lipreq/10000 as 'RecebCompra',
                    liot1a/10000 as 'OutrosPC1',
                    liqwbo/10000 as 'RecebProd',
                    liqowo/10000 as 'ReservaProd',
                    imuom1 as 'UM'


                    from proddta.f41021 
                    inner join proddta.f4102 on ibmcu = limcu and ibitm = liitm
                    inner join proddta.f4101 on imitm = liitm


                    where lilocn in ('LOG_IMPORTADO','LOG_NACIONAL', 'ORCAMENTO_NAC', 'ORCAMENTO_IMP','')
                ) as selea
                group by coditem
                ) as sele2
            on sele2.coditem = selebase.codref
    ) as fim3
    ) as fim4

where atende > 3

    group by cliente order by perc_atende desc, qtde_aberto desc");

		return view('vendas.orcamentos.lista')->with('orcamentos', $orcamentos);
	}
    


}
