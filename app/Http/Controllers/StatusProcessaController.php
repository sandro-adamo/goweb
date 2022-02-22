<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StatusProcessa;

class StatusProcessaController extends Controller
{
    
	public function excluiProcessamento($id) {


		$processamentos = \DB::select("delete from processa where processamento = '$id'");		

		return redirect('produtos/status/processamentos');

	}


	public function verProcessamentos() {

		$processamentos = \DB::select("select date(data) as data, processamento
											from processa
											group by date(data), processamento
											order by data desc
											limit 15");

		// $processamentos = StatusProcessa::groupBy('processamento',\DB::raw('date(data)'))
		// 									->select('processamento',\DB::raw('date(data) as data'))
											
		// 									->orderBy('data', 'desc')
		// 									->get();

		return view('produtos.status.processa.lista')->with('processamentos', $processamentos);

	}


	public function detalhesProcessamento($processamento) {

		error_reporting(E_ALL);
		ini_set('display_errors', 'On');
		//ini_set('memory_limit', -1);

		$classificacao = \DB::select("select clas_m3, meta*(pparc/total) meta,  PD,  P15,  P30,  PP,  PE,  PT,   ID,  I15,  I30,  IP, IE,  IT,   NOVO_ORC, processamento

from (

select clas_m3, 
(select sum(ifnull(meta,0))/4 from metas   where ano = year(now()) and mes = month(now())) as meta,

(select sum(potencial3) from processa where  processamento = '$processamento' and clas_m3 like 'linha%' and status3 in ('DISPONIVEL','IMPORTACAO 15 DIAS', 'IMPORTACAO 30 DIAS')
) as total, 


SUM(PD) PD, SUM(P15) P15, SUM(P30) P30, SUM(PP) PP, SUM(PE) PE, SUM(PD)+SUM(P15)+SUM(P30) PT,  
SUM(ID) ID, SUM(I15) I15, SUM(I30) I30, SUM(IP) IP, SUM(IE) IE, SUM(ID)+SUM(I15)+SUM(I30) IT, 
SUM(PD) +SUM(P15) +SUM(P30) PPARC, 
SUM(NOVO_ORC) NOVO_ORC, processamento from (


select  clas_m3,
case when status3 = 'ENTREGA IMEDIATA' then sum(potencial3) else 0 end as PD,
case when status3 = 'DISPONIVEL EM 15 DIAS' then sum(potencial3) else 0 end as P15,
case when status3 = 'DISPONIVEL EM 30 DIAS' then sum(potencial3) else 0 end as P30,
case when status3 = 'EM PRODUCAO' 	then sum(potencial3) else 0 end as PP,
case when status3 like '%ESGOTADO%' 	then sum(potencial3) else 0 end as PE,
sum(potencial3) PT,

case when status3 = 'ENTREGA IMEDIATA' then count(secundario) else 0 end as ID,
case when status3 = 'DISPONIVEL EM 15 DIAS' then count(secundario) else 0 end as I15,
case when status3 = 'DISPONIVEL EM 30 DIAS' then count(secundario) else 0 end as I30,
case when status3 = 'EM PRODUCAO' then count(secundario) else 0 end as IP,
case when status3 like '%ESGOTADO%' then count(secundario) else 0 end as IE,
count(secundario) IT, 

case when clas_m3 like 'linha%' then (novo_orc) else 0 end as novo_orc, processamento


from processa
where processamento = '$processamento' 

group by clas_m3, status3, secundario, novo_orc
) as sele1

group by clas_m3

) as sele2

order by clas_m3");

		$agrupamento = \DB::select("select agrup, meta,  PD,  P15,  P30,  PP,  PE,  PT,   ID,  I15,  I30,  IP, IE,  IT,   NOVO_ORC, processamento

from (

			select agrup,  
		(select sum(ifnull(meta,0))/4 from metas   where metas.agrup = sele1.agrup and ano = year(now()) and mes = month(now()) ) as meta,
		
			SUM(PD) PD, SUM(P15) P15, SUM(P30) P30, SUM(PP) PP, SUM(PE) PE, SUM(PV) PV, 
			SUM(PD)+SUM(P15)+SUM(P30) PT,  
			SUM(ID) ID, SUM(I15) I15, SUM(I30) I30, SUM(IP) IP, SUM(IE) IE, SUM(IV) IV, 
			SUM(ID)+SUM(I15)+SUM(I30) IT, 
			SUM(NOVO_ORC) NOVO_ORC, processamento from (

			select agrup, 
			case when status3 = 'ENTREGA IMEDIATA' then sum(potencial3) else 0 end as PD,
			case when status3 = 'DISPONIVEL EM 15 DIAS' then sum(potencial3) else 0 end as P15,
			case when status3 = 'DISPONIVEL EM 30 DIAS' then sum(potencial3) else 0 end as P30,
			case when status3 = 'EM PRODUCAO' 	then sum(potencial3) else 0 end as PP,
			case when status3 = 'ESGOTADO' 	then sum(potencial3) else 0 end as PE,
			case when status3 = '' 			then sum(potencial3) else 0 end as PV,
			sum(potencial3) PT,

			case when status3 = 'ENTREGA IMEDIATA' then count(secundario) else 0 end as ID,
			case when status3 = 'DISPONIVEL EM 15 DIAS' then count(secundario) else 0 end as I15,
			case when status3 = 'DISPONIVEL EM 30 DIAS' then count(secundario) else 0 end as I30,
			case when status3 = 'EM PRODUCAO' then count(secundario) else 0 end as IP,
			case when status3 = 'ESGOTADO' then count(secundario) else 0 end as IE,
			case when status3 = '' then count(secundario) else 0 end as IV,
			count(secundario) IT, 

			case when clas_m3 like 'linha%' then (novo_orc) else 0 end as novo_orc, processamento


			from processa
			where processamento = '$processamento' and clas_m3 like 'LINHA%'

			group by agrup, status3,secundario, novo_orc, clas_m3

			) as sele1

			group by agrup
) as sele2
order by agrup");
		

		//$agrupamento = \DB::select("select * from processa where processamento = '$id'");

		//die($processa);
		return view('produtos.status.processa.detalhes')->with('classificacao', $classificacao)
														->with('agrupamento', $agrupamento);

	}
	
	public function editaProcessamento(Request $request, $processamento) {


		$sql = " processamento = '$processamento' and agrup = '$request->agrup' ";

		if (isset($request->status) && $request->status != '') {
			$status = $request->status;
			$sql .= " and status3 = '$status' ";
		}
		
		
		

 		$itens = \DB::select("select id, modelo, secundario, clas_m3, clas_i3, col_m, col_i, 
saldo_disponivel as saldo_disp, 
 novo_orc as orc, qtde_most, status3, 
 potencial pot_clas, pot_most, fator_disp pot_disp, meta_indice pot_meta, potencial1 pot1, potencial3 pot3, processamento,saldo_30dias,
 
(select dt_confirmacao
from producoes_hist
where qtd_pedido>qtd_enviada
and estoque+producao>0
and cod_sec = secundario
order by dt_relatorio desc
limit 1) as dt_confirmacao,
(select estoque+producao
from producoes_hist
where qtd_pedido>qtd_enviada
and estoque+producao>0
and cod_sec = secundario
order by dt_relatorio desc
limit 1) as qtd_prod
 from processa 

 where 
 $sql

 and col_m Not like '%2020%' 

 order by dt_confirmacao desc");


 		return view('produtos.status.processa.edita')->with('itens', $itens);

	}
	
	

	
	public function atualizastatusitens() {


        error_reporting(E_ALL);
        ini_set('display_errors', 'On');
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);


        $processa = \DB::select("select processamento  from processa
			order by data desc limit 1");

        if ($processa) {

    		$processamento = $processa[0]->processamento; 
    		
    		$statusatual = \DB::connection('go')->select("
    select secundario, id_item, dt_ultimo_st as dataultstatus,
    case 
    when ultimo_st = 'DISPONÍVEL EM 15 DIAS'  then '15D'
    when ultimo_st = 'DISPONÍVEL EM 30 DIAS' then '30D'
    when ultimo_st = 'EM PRODUÇÃO' then 'PROD'
    when ultimo_st = 'ENTREGA IMEDIATA' then 'DISP'
    when ultimo_st = 'ESGOTADO' then 'ESGOT'
    else 0 end as 'codultstatus',

    case 
    when ultimo_st = 'DISPONÍVEL EM 15 DIAS'  then 'DISPONÍVEL EM 15 DIAS'
    when ultimo_st = 'DISPONÍVEL EM 30 DIAS' then 'DISPONÍVEL EM 30 DIAS'
    when ultimo_st = 'EM PRODUÇÃO' then 'EM PRODUÇÃO'
    when ultimo_st = 'ENTREGA IMEDIATA' then 'ENTREGA IMEDIATA'
    when ultimo_st = 'ESGOTADO' then 'ESGOTADO'
    else 0 end as 'ultstatus',

    case 
    when status3 = 'AGUARDAR IMPORTACAO 15 DIAS'  then 'DISPONÍVEL EM 15 DIAS'
    when status3 = 'IMPORTACAO 15 DIAS' then 'DISPONÍVEL EM 15 DIAS'
    when status3 = 'AGUARDAR IMPORTACAO 30 DIAS' then 'DISPONÍVEL EM 30 DIAS'
    when status3 = 'IMPORTACAO 30 DIAS' then 'DISPONÍVEL EM 30 DIAS'
    when status3 = 'AGUARDAR PRODUCAO' then 'EM PRODUÇÃO'
    when status3 = 'PRODUCAO' then 'EM PRODUÇÃO'
    when status3 = 'DISPONIVEL' then 'ENTREGA IMEDIATA'
    when status3 = 'ESGOTADO' then 'ESGOTADO'
    else 0 end as 'statusatual',

      case 
    when status3 = 'AGUARDAR IMPORTACAO 15 DIAS'  then '15D'
    when status3 = 'IMPORTACAO 15 DIAS' then '15D'
    when status3 = 'AGUARDAR IMPORTACAO 30 DIAS' then '30D'
    when status3 = 'IMPORTACAO 30 DIAS' then '30D'
    when status3 = 'AGUARDAR PRODUCAO' then 'PROD'
    when status3 = 'PRODUCAO' then 'PROD'
    when status3 = 'DISPONIVEL' then 'DISP'
    when status3 = 'ESGOTADO' then 'ESGOT'
    else 0 end as 'codstatusatual',
    CURRENT_DATE() as 'datastatusatual'


    from processa
    where processamento = '$processamento' 
    and grife not in ('.', 'ALEXANDER MCQUEEN', 'ALTUZARRA', 'AZZEDINE', 'BOTTEGA VENETA', 'BOUCHERON', 'BRIONI', 'CARTIER', 'CHRISTOPHER KANE', 'DINIZ', 'FAUSE HATEN', 'GIAN FRANCO FERRE',
    'GO', 'MATERIAL INSTITUCIONAL', 'MCQ', 'OUTROS MATERIAIS', 'POMELLATO', 'STELLA MCCARTNEY', 'TOMAS MAIER', 'TRACK E FIELD', 'gucci', 'saint laurent', 'montblanc')
    order by secundario asc");
	

            if (count($statusatual) > 0) {
                $total_sql = count($statusatual);
                    $client = \App\JDE::connect();

                
                $index = 0;
                foreach ($statusatual as $statusatual1) {
                    $index++;
                    $query = "UPDATE `itens` SET datastatusatual = '$statusatual1->datastatusatual',dataultstatus = '$statusatual1->dataultstatus', codstatusatual = '$statusatual1->codstatusatual', statusatual = '$statusatual1->statusatual', ultstatus = '$statusatual1->ultstatus', codultstatus = '$statusatual1->codultstatus'  where id = '$statusatual1->id_item' "; 
    				echo $query.'<br>';

                    \DB::connection('go')->insert($query);



                    $result = $client->itemUpdate( array( 
                      "codItemCurto"=> $statusatual1->id_item,
                      "codstatusatutal" => $statusatual1->codstatusatual,
                      "filial" => '    01020000'));

                }
            }

        }


    }	
	
	
	
	
	
	
	// CORRETA DE STATUS

	
public function atualizaprocessa1() {


error_reporting(E_ALL);
ini_set('display_errors', 'On');
ini_set('memory_limit', -1);
        $processa = \DB::connection('go')->select("


select Id_item,
    Fornecedor,
    Agrupamento,
    Tipo,
    Grife,
    Modelo,
    Cod_sec,
    Primario,
    CASE
        WHEN Existente IS NULL THEN 0
        ELSE Existente
    END AS 'Existente',
    CASE
        WHEN Pre_pedido IS NULL THEN 0
        ELSE Pre_pedido
    END AS 'Pre_pedido',
    CASE
        WHEN Em_separacao IS NULL THEN 0
        ELSE Em_separacao
    END AS 'Em_separacao',
    CASE
        WHEN Saldo_parte IS NULL THEN 0
        ELSE Saldo_parte
    END AS 'Saldo_parte',
    CASE
        WHEN Beneficiamento IS NULL THEN 0
        ELSE Beneficiamento
    END AS 'Beneficiamento',
    Col_mod,
    Col_item,
    Clas_mod,
    Clas_item,
    Idade,
    Genero,
    Material,
    Valor,
    CASE
        WHEN Orcamentos IS NULL THEN 0
        ELSE Orcamentos
    END AS 'Orcamentos',
    CASE
        WHEN ETQ IS NULL THEN 0
        ELSE ETQ
    END AS 'ETQ',
    CASE
        WHEN Cep IS NULL THEN 0
        ELSE Cep
    END AS 'Cep',
    CASE
        WHEN Cet IS NULL THEN 0
        ELSE Cet
    END AS 'Cet',
    CASE
        WHEN Manutencao IS NULL THEN 0
        ELSE Manutencao
    END AS 'Manutencao',
    CASE
        WHEN status_atual = 'ESGOTADO' THEN 9
        WHEN status_atual = 'EM PRODUÇÃO' THEN 8
        WHEN status_atual = 'DISPONIVEL EM 30 DIAS' THEN 4
        WHEN status_atual = 'DISPONIVEL EM 15 DIAS' THEN 3
        WHEN status_atual = 'ENTREGA IMEDIATA' THEN 1
        ELSE 0
    END AS ind_status_atual,
    Status_atual,
    datastatusatual AS 'dt_ultimo_st',
    pot_geral_mes,
    pot_red_mes,
    pot_redi_mes,
    '0' AS qtd_grife,
    CASE
        WHEN Saldo_most IS NULL THEN 0
        ELSE Saldo_most
    END AS 'Saldo_most',
    '0' AS Pot_most,
    chave_utilizada,
    regra_utilizada,
    pot_mes_utilizado,
    potencial,
    prox_status,
    CASE
        WHEN prox_status = 'ESGOTADO' THEN 9
        WHEN prox_status = 'EM PRODUCAO' THEN 8
        WHEN prox_status = 'DISPONIVEL EM 30 DIAS' THEN 4
        WHEN prox_status = 'DISPONIVEL EM 15 DIAS' THEN 3
        WHEN prox_status = 'ENTREGA IMEDIATA' THEN 1
        ELSE 0
    END AS ind_prox_status,
    CASE
        WHEN
            prox_status IN ('DISPONIVEL EM 30 DIAS' , 'EM PRODUCAO',
                'DISPONIVEL EM 15 DIAS',
                'ENTREGA IMEDIATA')
                AND fornecedor <> 'kering'
                AND col_mod < ('2020 08')
                AND liberacao_tablet = 'i'
        THEN
            'Liberar tablet'
        WHEN
            prox_status IN ('ESGOTADO')
                AND fornecedor <> 'kering'
                AND col_mod < ('2019 01')
                AND liberacao_tablet <> 'i'
        THEN
            'Retirar do tablet'
        ELSE 'Manter'
    END AS 'Acao tablet',
    fator_disp,
    meta_indice,
    novo_orc,
    potencial1,
    potencial3 as 'potencial3',
    prox_status AS status3,
    clas_mod2 AS clas_m3,
    clas_item2 AS clas_i3,
    prox_status AS status2,
    clas_mod2 AS clas_m2,
    clas_item2 AS clas_i2,
    '0' AS 'pot_most2',
    Liberacao_tablet,
    CASE
        WHEN Saldo_trocas IS NULL THEN 0
        ELSE Saldo_trocas
    END AS 'Saldo_trocas',
    CASE
        WHEN Saldo_disponivel IS NULL THEN 0
        ELSE Saldo_disponivel
    END AS 'Saldo_disponivel',
    CASE
        WHEN Saldo_15dias IS NULL THEN 0
        ELSE Saldo_15dias
    END AS 'Saldo_15dias',
    CASE
        WHEN Saldo_30dias IS NULL THEN 0
        ELSE Saldo_30dias
    END AS 'Saldo_30dias',
    CASE
        WHEN Saldo_producao IS NULL THEN 0
        ELSE Saldo_producao
    END AS 'Saldo_producao',
    armazenamento
FROM
    (SELECT 
        (potencial * meta_indice * IFNULL(fator_disp, 1)) AS potencial1,
            CASE
                WHEN (potencial * meta_indice * IFNULL(fator_disp, 1)) - existente > 0 THEN (potencial * meta_indice * IFNULL(fator_disp, 1)) - existente
                ELSE 0
            END AS 'novo_orc',
            final.*,
            IFNULL(selefator.fator_disp, 1) fator_disp
    FROM
        (SELECT 
        *,
            CASE
                
                WHEN col_mod > '2022 01' THEN 'EM PRODUCAO'
                WHEN col_item > '2022 01' THEN 'EM PRODUCAO'
				
                
                WHEN
                    (clas_mod IN ('COLECAO B' , 'PROMOCIONAL C')
                        AND (GREATEST(saldo_disponivel, 0)+ GREATEST(saldo_15dias, 0) + 
                     GREATEST(saldo_30dias, 0) + GREATEST(saldo_producao, 0)) < 10
                        AND grife NOT IN ('evoke' , 'jolie', 't-charge'))
                THEN
                    'ESGOTADO'
              

                WHEN
                    ((saldo_disponivel) > (potencial3))
                        AND (saldo_disponivel) > 5
                THEN
                    'ENTREGA IMEDIATA'
                WHEN
                    ((saldo_15dias) > (potencial3))
                        AND (saldo_15dias) > 5
                THEN
                    'DISPONIVEL EM 15 DIAS'

                WHEN ((saldo_30dias) > (potencial3)) 
                        AND (saldo_30dias) > 5
                THEN
                    'DISPONIVEL EM 30 DIAS'

               

                WHEN    (saldo_disponivel) >0
                        AND grife IN ('puma')
                THEN
                    'DISPONIVEL EM 30 DIAS'
           
                WHEN
                    ((saldo_producao) > (potencial3))
                        AND (saldo_producao) > 5
                THEN
                    'EM PRODUCAO'
                
                ELSE 'ESGOTADO'
            END AS prox_status
    FROM
        (SELECT 
        *,
            CASE
                WHEN
                    potencial2 < (potencial * 0.2)
                        AND potencial2 > 0
                THEN
                    potencial2
                ELSE potencial * 0.5
            END AS potencial3,

            CASE
                WHEN
                    potencial2 * 0.5 < (potencial * 0.2)
                        AND potencial2 * 0.5 > 0
                THEN
                    potencial2 * 0.2
                ELSE potencial * 0.2
            END AS potencial3bg
    FROM
        (SELECT 
        *,
            CASE
                WHEN chave_geral IS NOT NULL THEN 'chave geral'
                WHEN
                    (chave_geral IS NULL
                        AND chave_red IS NOT NULL)
                THEN
                    'chave reduzida'
                WHEN
                    (chave_geral IS NULL
                        AND chave_red IS NULL
                        AND chave_redi IS NOT NULL)
                THEN
                    'chave reduzida item'
                ELSE 'erro'
            END AS chave_utilizada,
            CASE
                WHEN chave_geral IS NOT NULL THEN chave_geral
                WHEN
                    (chave_geral IS NULL
                        AND chave_red IS NOT NULL)
                THEN
                    chave_red
                WHEN
                    (chave_geral IS NULL
                        AND chave_red IS NULL
                        AND chave_redi IS NOT NULL)
                THEN
                    chave_redi
                ELSE 'erro'
            END AS regra_utilizada,
            CASE
                WHEN chave_geral IS NOT NULL THEN pot_geral_mes
                WHEN
                    (chave_geral IS NULL
                        AND chave_red IS NOT NULL)
                THEN
                    pot_red_mes
                WHEN
                    (chave_geral IS NULL
                        AND chave_red IS NULL
                        AND chave_redi IS NOT NULL)
                THEN
                    pot_redi_mes
                ELSE 0
            END AS pot_mes_utilizado,
            CASE
                WHEN chave_geral IS NOT NULL THEN (pot_geral_mes / 4)
                WHEN
                    (chave_geral IS NULL
                        AND chave_red IS NOT NULL)
                THEN
                    (pot_red_mes / 4)
                WHEN
                    (chave_geral IS NULL
                        AND chave_red IS NULL
                        AND chave_redi IS NOT NULL)
                THEN
                    (pot_redi_mes / 4)
                ELSE 5
            END AS potencial,
            Existente - (em_separacao + pre_pedido + orcamentos) AS Saldo_disponivel,
            CASE
                WHEN Existente - (em_separacao + pre_pedido + orcamentos) > 0 THEN (beneficiamento + saldo_parte)
                WHEN Existente - (em_separacao + pre_pedido + orcamentos) <= 0 THEN (Existente + beneficiamento + saldo_parte) - (em_separacao + pre_pedido + orcamentos)
                ELSE 0
            END AS Saldo_15dias,
            CASE
                WHEN (Existente + beneficiamento + saldo_parte) - (em_separacao + pre_pedido + orcamentos) > 0 THEN cet
                WHEN (Existente + beneficiamento + saldo_parte) - (em_separacao + pre_pedido + orcamentos) <= 0 THEN (Existente  +beneficiamento + saldo_parte + cet) - (em_separacao + pre_pedido + orcamentos)
                ELSE 0
            END AS Saldo_30dias,
            CASE
                WHEN (Existente  + beneficiamento + saldo_parte + cet) - (em_separacao + pre_pedido + orcamentos) > 0 THEN cep + etq
                WHEN (Existente + beneficiamento + saldo_parte + cet) - (em_separacao + pre_pedido + orcamentos) <= 0 THEN (Existente  + beneficiamento + saldo_parte + cet + cep + etq) - (em_separacao + pre_pedido + orcamentos)
                ELSE 0
            END AS Saldo_producao
    FROM
        (SELECT 
        itens.datastatusatual AS datastatusatual,
            itens.id AS Id_item,
            itens.agrup AS Agrupamento,
            itens.grife AS Grife,
            itens.modelo AS Modelo,
            itens.secundario AS Cod_sec,
            itens.colmod AS Col_mod,
            itens.colitem AS Col_item,
            itens.clasmod AS Clas_mod,
            itens.clasitem AS Clas_item,
            clasmod.clas_mod AS Clas_mod2,
            clasmod.clas_item AS Clas_item2,
            itens.idade AS Idade,
            itens.genero AS Genero,
            itens.material AS Material,
            itens.statusatual AS Status_atual,
            CASE
                WHEN itens.grife IN ('ALEXANDER MCQUEEN' , 'ALTUZARRA', 'BOTTEGA VENETA', 'BOUCHERON', 'BRIONI', 'CARTIER', 'CHRISTOPHER KANE', 'GUCCI', 'MCQ', 'POMELLATO', 'PUMA', 'SAINT LAURENT', 'STELLA MCCARTNEY', 'TOMAS MAIER', 'AZZEDINE', 'MONTBLANC') THEN 'Kering'
                ELSE 'China'
            END AS Fornecedor,
            itens.codtipoarmaz AS Liberacao_tablet,
            ifnull((existente + conf_montado),0) AS Existente,
            ifnull(res_definitiva,0) AS Em_separacao,
            ifnull(res_temporaria,0) AS Pre_pedido,
            ifnull(saldo_manutencao,0) AS Manutencao,
            ifnull(saldo_most,0) AS Saldo_most,
			ifnull(cet,0) AS Cet,
            IFNULL(cep, 0) AS Cep,
			IFNULL(etq, 0)+ifnull(nao_passivel,0)+ifnull(cet_li,0) AS ETQ,
            ifnull(em_beneficiamento,0) AS Beneficiamento,
            ifnull(passivel,0) AS Saldo_parte,
            ifnull(saldo_trocas,0) AS Saldo_trocas,
            IFNULL(orcamentos.orcvalido, 0) AS Orcamentos,
            IFNULL(selemeta.meta_indice, 0) meta_indice,
            selesug_geral.chave AS chave_geral,
            IFNULL(selesug_geral.mdv_mensal, 0) AS pot_geral_mes,
            selesug_red.chave AS chave_red,
            IFNULL(selesug_red.mdv_mensal, 0) AS pot_red_mes,
            selesug_redi.chave AS chave_redi,
            IFNULL(selesug_redi.mdv_mensal, 0) AS pot_redi_mes,
            tipoitem AS 'Tipo',
            primario AS 'primario',
            valortabela AS 'Valor',
            codtipoarmaz AS 'Armazenamento',
            dataultstatus,
            potencial2
    FROM
        go.itens
    LEFT JOIN go.saldos ON itens.id = saldos.curto
    LEFT JOIN go.orcamentos ON itens.id = orcamentos.curto
    LEFT JOIN (SELECT 
        itens.id AS Id1_item,
            CASE
                WHEN
                    (itens.clasmod = 'novo'
                        OR itens.clasmod = 'Add sales cat S5 codes here')
                THEN
                    'LINHA A'
                ELSE itens.clasmod
            END AS Clas_mod,
            CASE
                WHEN
                    (itens.clasitem = 'novo'
                        OR itens.clasitem = 'Add sales cat S4 codes here')
                THEN
                    'LINHA A'
                ELSE itens.clasitem
            END AS Clas_item
    FROM
        itens) AS clasmod ON clasmod.Id1_item = itens.id
  
    left join ( select curto, saldo_passivel as passivel, pecas_sem_complemento as nao_passivel from pecaspassiveis) as passiveis on  passiveis.curto = itens.id
    LEFT JOIN (SELECT 
        agrup,
            SUM(meta) meta_ano,
            SUM(meta_mes) meta_mes,
            ((((SUM(meta_mes) / SUM(meta)) * 100) * 100) / 8.33) / 100 meta_indice
    FROM
        (SELECT 
        *,
            CASE
                WHEN ano = YEAR(NOW()) AND mes = MONTH(NOW()) THEN meta
                ELSE 0
            END AS meta_mes
    FROM
        metas
    WHERE
        agrup <> '') AS sele1
    WHERE
        ano = YEAR(NOW())
    GROUP BY agrup) AS selemeta ON selemeta.agrup = itens.agrup
    LEFT JOIN (SELECT 
        *
    FROM
        sugestoes) AS selesug_geral ON (selesug_geral.agrup = itens.agrup
        AND selesug_geral.genero = itens.genero
        AND selesug_geral.idade = itens.idade
        AND selesug_geral.material = itens.material
        AND selesug_geral.clas_mod = clasmod.clas_mod
        AND selesug_geral.clas_item = clasmod.clas_item)
    LEFT JOIN (SELECT 
        *
    FROM
        sugestoes) AS selesug_red ON (selesug_red.agrup = itens.agrup
        AND selesug_red.genero = ''
        AND selesug_red.idade = ''
        AND selesug_red.material = ''
        AND selesug_red.clas_mod = clasmod.clas_mod
        AND selesug_red.clas_item = clasmod.clas_item)
    LEFT JOIN (SELECT 
        *
    FROM
        sugestoes) AS selesug_redi ON (selesug_redi.agrup = itens.agrup
        AND selesug_redi.genero = ''
        AND selesug_redi.idade = ''
        AND selesug_redi.material = ''
        AND selesug_redi.clas_mod = clasmod.clas_mod
        AND selesug_redi.clas_item = 'linha a')
    LEFT JOIN (SELECT 
        curto,
            secundario,
            ult_30dd,
            ult_60dd,
            FORMAT((ult_30dd / 4), 0) AS potencial2
    FROM
        vendas_sint) AS potencial2 ON potencial2.curto = itens.id
    WHERE
        codtipoitem = '006'
        
        
       
        ) AS Base
    WHERE
        grife NOT IN ('ALEXANDER MCQUEEN' , 'ALTUZARRA', 'BOTTEGA VENETA', 'BOUCHERON', 'BRIONI', 'CARTIER', 'CHRISTOPHER KANE', 'GUCCI', 'MCQ', 'POMELLATO', 'PUMA', 'SAINT LAURENT', 'STELLA MCCARTNEY', 'TOMAS MAIER', 'AZZEDINE', 'MONTBLANC')
        ) AS chave) AS potencial) AS final
    LEFT JOIN (SELECT 
        agrup, status, IFNULL(fator, 1) fator_disp
    FROM
        fator_status) AS selefator ON (selefator.agrup = final.agrupamento
        AND selefator.status = final.prox_status)) AS fina2
WHERE
    Liberacao_tablet <> 'o'


");
	

	$processamento2 = date("YmdHis");
	

        if (count($processa) > 0) {
            $total_sql = count($processa);

            //\DB::connection('go')->select("truncate table itens");
            $index = 0;
            foreach ($processa as $processamento) {
                $index++;
                $query = "INSERT INTO `processa`(`processamento`, `id_item`, `fornecedor`, `agrup`, `tipo`, `grife`, `modelo`, `secundario`, `primario`, `existente`, `pre_pedido`, `em_separacao`, `saldo_partes`, `beneficiamento`, `col_m`, `col_i`, `clas_m`, `clas_i`, `idade`, `genero`, `material`, `valor`, `orcamento`, `etq`, `cep`, `cet`, `manutencao`, `ind_ultimo_st`, `ultimo_st`, `dt_ultimo_st`,  `pot_geral_mes`, `pot_red_mes`, `pot_redi_mes`, `qtde_grife`, `qtde_most`, `pot_most`, `chave_utilizada`, `regra_utilizada`, `pot_mes_utilizado`, `potencial`, `status_atual`, `ind_status_atual`, `acao_tablet`, `fator_disp`, `meta_indice`, `novo_orc`, `potencial1`, `potencial3`, `status3`, `clas_m3`, `clas_i3`, `status2`, `clas_m2`, `clas_i2`, `pot_most2`,`liberacao_tablet`, `saldo_trocas`, `saldo_disponivel`, `saldo_15dias`, `saldo_30dias`, `saldo_producao`, `armazenamento`) VALUES ('$processamento2',";    

                foreach ($processamento as $coluna => $valor) {

                    $valor2 = addslashes($valor);
                    $query .= "'$valor2',";

                }        
                $query = substr($query, 0, -1);
                $query .= ')';
                
//echo $query;
                \DB::connection('go')->insert($query);

            }
            if ($total_sql != $processa) {
                echo 'erro, contagem nao bate';
            }
        } else {
            echo 'erro';
        }
	$var = "<script>javascript:history.back(-2)</script>";
echo $var;
    }	

	

	

	public function alteraStatus(Request $request) {

		$id = "1";
		

		$secundario = $request->secundario;
		$processamento = $request->processamento;
		$status3 = $request->status3;
		if ($request->saldo30>0){
			$saldo30 = ", saldo_30dias = '".$request->saldo30."'";
		}
		else { $saldo30 ="";
			
		}
		
		$update = "update processa set status3 = '$status3' $saldo30 where processamento = '$processamento' and secundario = '$secundario' ";
	
		
		\DB::connection('go')->insert($update);
		
		$var = "<script>javascript:history.back();self.location.reload()</script>";
echo $var;
		
	}

	



	public function uploadArquivo2(Request $request) {
error_reporting(E_ALL);
ini_set('display_errors',1);
//ini_set('memory_limit',-1);

		$processamento2 = $request->processamento;
		//$uploadfile = $uploaddir . basename($_FILES['arquivo']);

		$path = $request->file('arquivo')->store('processa');
		$uploadFile = '/var/www/html/portal-gestao/storage/app/'.$path;
//dd($uploadFile	);
		$erros = array();

		if (file_exists($uploadFile)) {
			


			$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");

			$spreadsheet = $reader->load($uploadFile);

			$sheet = $spreadsheet->getActiveSheet()->toArray();

			$i = 1;


			foreach ($sheet as $linha) {

				if ($i >= 2) {    

					$coluna1 = $linha[0];
					$coluna2 = $linha[1];

    				
		                // verifica se o item existe no cadastro
					if ($coluna1 <> '') {
						$item = \DB::select("select* from itens where secundario = '$coluna1' limit 1");

						
						if ($item)

							if ($coluna2 == 'ENTREGA IMEDIATA' or $coluna2 == 'DISPONIVEL EM 15 DIAS' or $coluna2 == 'DISPONIVEL EM 30 DIAS' or $coluna2 == 'EM PRODUCAO' or $coluna2 == 'ESGOTADO') {
							

								$item = \DB::select("update processa set status3 = '$coluna2' where secundario = '$coluna1' and processamento = '$processamento2'");
							

							} 
						else {

								$erros = '[ ' . $coluna2 . ' ] - Status não correto';

								dd( $erros ) ;
							}

						} else {

							$erros = '[ ' . $coluna1 . ' ] - Item não existe ';

							dd( $erros ) ;
						}

						
					}

				$i++;
											


				}


			}
		else{
			dd('não achou o arquivo no servidor');
		}

		
			
		



		
		

		return redirect()->back();


	}

}

