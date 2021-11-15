<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StatusProcessa;

class StatusProcessaKeringController extends Controller
{
	
    public function atualizaprocessa() {


        error_reporting(E_ALL);
        ini_set('display_errors', 'On');
        ini_set('memory_limit', -1);
        $processa = \DB::connection('go')->select("
            select 
            Id_item,
            Fornecedor,
            Agrupamento, 
            Tipo,
            Grife, 
            Modelo, 
            Cod_sec, 
            Primario,
            case when Existente is null 
            then 0 else Existente end as 'Existente',
            case when Pre_pedido is null 
            then 0 else Pre_pedido end as 'Pre_pedido',

            case when Em_separacao is null 
            then 0 else Em_separacao end as 'Em_separacao',
            case when Saldo_parte is null 
            then 0 else Saldo_parte end as 'Saldo_parte', 
            case when Beneficiamento is null 
            then 0 else Beneficiamento end as 'Beneficiamento', 
            Col_mod, 
            Col_item, 
            Clas_mod, 
            Clas_item, 
            Idade, 
            Genero, 


            Material, 
            Valor,
            case when Orcamentos is null 
            then 0 else Orcamentos end as 'Orcamentos',
            case when ETQ is null 
            then 0 else ETQ end as 'ETQ',
            case when Cep is null 
            then 0 else Cep end as 'Cep', 
            case when Cet is null 
            then 0 else Cet end as 'Cet',
            case when Manutencao is null 
            then 0 else Manutencao end as 'Manutencao', 
            case 
            when status_atual = 'ESGOTADO'           then 9
            when status_atual = 'EM PRODUCAO'              then 8
            when status_atual = 'DISPONIVEL EM 30 DIAS'    then 4
            when status_atual = 'DISPONIVEL EM 15 DIAS'   then 3
            when status_atual = 'ENTREGA IMEDIATA'             then 1
            else 0  end as ind_status_atual,
            (select statusatual
            from itens
            where itens.id = fina2.Id_item
            
            limit 1) as Status_atual, 

            (select datastatusatual
            from itens
            where itens.id = fina2.Id_item
                  
            ) as 'dt_ultimo_st',
            pot_geral_mes,
            pot_red_mes, 
            pot_redi_mes,
            '0' as qtd_grife,
            case when Saldo_most is null 
            then 0 else Saldo_most end as 'Saldo_most', 
            '0'as Pot_most,
            chave_utilizada, 
            regra_utilizada, 
            pot_mes_utilizado, 
            potencial, 
            prox_status, 
            case 
            when prox_status = 'ESGOTADO'            then 9
            when prox_status = 'EM PRODUCAO'           then 8
            when prox_status = 'DISPONIVEL EM 30 DIAS'     then 4
            when prox_status = 'DISPONIVEL EM 15 DIAS'   then 3
            when prox_status = 'ENTREGA IMEDIATA'          then 1
            else 0  end as ind_prox_status,

            case 

            when prox_status in ('DISPONIVEL EM 30 DIAS', 'EM PRODUCAO', 'DISPONIVEL EM 15 DIAS', 'ENTREGA IMEDIATA') 
            and liberacao_tablet = 'i' then 'P'

            when prox_status in ('ESGOTADO') and col_mod < concat(year(current_timestamp)-1,' ',month(current_timestamp))
            and (liberacao_tablet <> 'I' or liberacao_tablet <> 'O') then 'I'
            else liberacao_tablet end as 'Acao tablet',

            fator_disp, 
            meta_indice,
            novo_orc, 
            potencial1, 
            potencial1 as potencial3, 
            prox_status as status3, 
            clas_mod2 as clas_m3, 
            clas_item2 as clas_i3,
            prox_status as status2, 
            clas_mod2 as clas_m2, 
            clas_item2 as clas_i2,
            '0' as 'pot_most2',
            Liberacao_tablet,
            case when Saldo_trocas is null 
            then 0 else Saldo_trocas end as 'Saldo_trocas',
            case when Saldo_disponivel is null 
            then 0 else Saldo_disponivel end as 'Saldo_disponivel',
            case when Saldo_15dias is null 
            then 0 else Saldo_15dias end as 'Saldo_15dias',
            case when Saldo_30dias is null 
            then 0 else Saldo_30dias end as 'Saldo_30dias',
            case when Saldo_producao is null 
            then 0 else Saldo_producao end as 'Saldo_producao',
            armazenamento


            from(
            select 
            (potencial*meta_indice*/**ifnull(pot_most,1)***/ifnull(fator_disp,1)) as potencial1, 
            case when (potencial*meta_indice*/**ifnull(pot_most,1)***/ifnull(fator_disp,1))-existente > 0 then (potencial*meta_indice*
            /**ifnull(pot_most,1)***/ifnull(fator_disp,1))-existente
            else 0 end as 'novo_orc',
            final.*, ifnull(selefator.fator_disp,1) fator_disp 

            from(
            select *,  


            case 

            /**REGRAS KERING**/
            
            when  col_mod >= '2021 02' then 'EM PRODUCAO'
            when  col_item >= '2021 02' then 'EM PRODUCAO'
            when grife in ('ALEXANDER MCQUEEN','ALTUZARRA','BOTTEGA VENETA','BOUCHERON','BRIONI','CARTIER','CHRISTOPHER KANE','MCQ','POMELLATO','STELLA MCCARTNEY','TOMAS MAIER', 'AZZEDINE', 'CHLOE','DUNHILL') and col_mod >= '2021 01' then 'EM PRODUCAO'
            when  (saldo_disponivel)  >= 1  then 'ENTREGA IMEDIATA'
            when  (saldo_30dias)  >= 1  then 'DISPONIVEL EM 15 DIAS'
            when col_item = '2020 08' then 'DISPONIVEL EM 30 DIAS'
            when col_mod = '2021 01' then 'DISPONIVEL EM 30 DIAS'
            
            -- when  (Saldo_producao)  >= 10   then 'EM PRODUCAO'
            
           
            else 'ESGOTADO' end as prox_status

            from(select * ,
            case 
            when chave_geral is not null then 'chave geral'
            when (chave_geral is null and chave_red is not null ) then 'chave reduzida'
            when (chave_geral is null and chave_red is null  and chave_redi is not null) then 'chave reduzida item'
            else 'erro' end as chave_utilizada,

            case 
            when chave_geral is not null then chave_geral
            when (chave_geral is null and chave_red is not null ) then chave_red
            when (chave_geral is null and chave_red is null  and chave_redi is not null) then chave_redi 
            else 'erro' end as regra_utilizada,

            case 
            when chave_geral is not null then pot_geral_mes
            when (chave_geral is null and chave_red is not null ) then pot_red_mes
            when (chave_geral is null and chave_red is null  and chave_redi is not null) then pot_redi_mes 
            else 0 end as pot_mes_utilizado,

            case 
            when chave_geral is not null then (pot_geral_mes/4)
            when (chave_geral is null and chave_red is not null ) then (pot_red_mes/4)
            when (chave_geral is null and chave_red is null  and chave_redi is not null) then (pot_redi_mes/4)
            else 5 end as potencial,
            Existente - (em_separacao + pre_pedido + orcamentos) AS Saldo_disponivel,
            CASE
                WHEN Existente - (em_separacao + pre_pedido + orcamentos) > 0 THEN (beneficiamento + saldo_parte)
                WHEN Existente - (em_separacao + pre_pedido + orcamentos) <= 0 THEN (Existente + beneficiamento + saldo_parte) - (em_separacao + pre_pedido + orcamentos)
                ELSE 0
            END AS Saldo_15dias,
            CASE
                WHEN (Existente + beneficiamento + saldo_parte) - (em_separacao + pre_pedido + orcamentos) > 0 THEN cet
                WHEN (Existente + beneficiamento + saldo_parte) - (em_separacao + pre_pedido + orcamentos) <= 0 THEN (Existente + beneficiamento + saldo_parte + cet) - (em_separacao + pre_pedido + orcamentos)
                ELSE 0
            END AS Saldo_30dias,
            CASE
                WHEN (Existente + beneficiamento + saldo_parte + cet) - (em_separacao + pre_pedido + orcamentos) > 0 THEN cep
                WHEN (Existente + beneficiamento + saldo_parte + cet) - (em_separacao + pre_pedido + orcamentos) <= 0 THEN (Existente + beneficiamento + saldo_parte + cet + cep) - (em_separacao + pre_pedido + orcamentos)
                ELSE 0
            END AS Saldo_producao

            from (


            select 
            itens.datastatusatual as datastatusatual,
            itens.id as Id_item, 
            itens.agrup as Agrupamento, 
            itens.grife as Grife, 
            itens.modelo as Modelo, 
            itens.secundario as Cod_sec, 

            itens.colmod  as Col_mod, 
            itens.colitem as Col_item,
            clasmod.clas_mod  as clas_mod2, 
            clasmod.clas_item as clas_item2, 

            itens.clasmod  as Clas_mod, 
            itens.clasitem as Clas_item, 

            itens.idade as Idade, 
            itens.genero as Genero, 
            itens.material as Material, 
            itens.statusatual as Status_atual, 
            case when itens.grife in ('ALEXANDER MCQUEEN','ALTUZARRA','BOTTEGA VENETA','BOUCHERON','BRIONI','CARTIER','CHRISTOPHER KANE','GUCCI','MCQ',
            'POMELLATO','PUMA','SAINT LAURENT','STELLA MCCARTNEY','TOMAS MAIER', 'AZZEDINE', 'MONTBLANC', 'CHLOE','DUNHILL') then 'Kering' else 'China' end as Fornecedor,
            itens.codtipoarmaz as Liberacao_tablet,
            (existente+conf_montado) as Existente, 
            res_definitiva as Em_separacao, 
            res_temporaria as Pre_pedido, 
            saldo_manutencao as Manutencao, 
            saldo_most as Saldo_most, 
            case when grife in ('ALEXANDER MCQUEEN','ALTUZARRA','BOTTEGA VENETA','BOUCHERON','BRIONI','CARTIER',
            'CHRISTOPHER KANE','MCQ','PUMA', 'POMELLATO','STELLA MCCARTNEY','TOMAS MAIER', 'AZZEDINE', 'CHLOE','DUNHILL') then cet 
            else cet end as 'Cet',
            ifnull(producoes_sint.producao,0) as Cep, 
            ifnull(producoes_sint.estoque,0) as ETQ, 
            em_beneficiamento as Beneficiamento, 
            saldo_parte as Saldo_parte, 
            saldo_trocas as Saldo_trocas,

            ifnull(orcamentos.orcvalido,0) as Orcamentos,


            ifnull(selemeta.meta_indice,0) meta_indice,

            /**verifica se encontra geral, caso nao reduzido, caso nao, mod+a, caso nao aa**/
            selesug_geral.chave as chave_geral,
            ifnull(selesug_geral.mdv_mensal,0) as pot_geral_mes,

            selesug_red.chave as chave_red,
            ifnull(selesug_red.mdv_mensal,0) as pot_red_mes,

            selesug_redi.chave as chave_redi,
            ifnull (selesug_redi.mdv_mensal,0) as pot_redi_mes,
            tipoitem as 'Tipo',
            primario as 'primario',
            valortabela as 'Valor',
            codtipoarmaz as 'Armazenamento',
            dataultstatus

            from go.itens 
            left join go.saldos on itens.id = saldos.curto
            left join go.orcamentos on itens.id = orcamentos.curto

            left join(
            select itens.id as Id1_item, 
            case when (itens.clasmod = 'novo' or itens.clasmod ='Add sales cat S5 codes here') then 'LINHA A' else itens.clasmod end as Clas_mod, 

            case when (itens.clasitem = 'novo' or itens.clasitem ='Add sales cat S4 codes here') then 'LINHA A' else itens.clasitem end as Clas_item

            from itens) as clasmod on clasmod.Id1_item = itens.id

            left join (
            select ifnull(sum(producao),0) as producao, 
            ifnull(sum(estoque),0) as estoque,  
            cod_sec
            from producoes_sint 
            group by cod_sec) as producoes_sint on itens.secundario = producoes_sint.cod_sec


            /**calcula sazonalidade do mes para a semana  **/
            /**define mes/semana  **/

            left join (
            select agrup, sum(meta) meta_ano, sum(meta_mes) meta_mes, ((((sum(meta_mes)/sum(meta))*100)*100)/8.33)/100 meta_indice from (
            select *,
            case when ano = year(now()) and mes = month(now()) then meta else 0 end as meta_mes
            from metas 
            where agrup <> ''
            ) as sele1
            where ano = year(now())
            group by agrup 
            ) as selemeta
            on selemeta.agrup = itens.agrup


            /**CALCULA SUGESTOES**/
            /**sugestao venda mensal chave geral**/
            left join (
            select * from sugestoes 
            ) as selesug_geral
            on (selesug_geral.agrup     = itens.agrup
            and selesug_geral.genero        = itens.genero
            and selesug_geral.idade         = itens.idade
            and selesug_geral.material      = itens.material
            and selesug_geral.clas_mod      = clasmod.clas_mod
            and selesug_geral.clas_item     = clasmod.clas_item)


            /**sugestao venda mensal chave reduzida**/
            left join (
            select * from sugestoes 
            ) as selesug_red
            on (selesug_red.agrup = itens.agrup
            and selesug_red.genero      = ''
            and selesug_red.idade       = ''
            and selesug_red.material    = ''
            and selesug_red.clas_mod    = clasmod.clas_mod
            and selesug_red.clas_item   = clasmod.clas_item)

            /**sugestao venda mensal chave reduzida clasi=A**/
            left join (
            select * from sugestoes 
            ) as selesug_redi
            on (selesug_redi.agrup = itens.agrup
            and selesug_redi.genero     = ''
            and selesug_redi.idade      = ''
            and selesug_redi.material   = ''
            and selesug_redi.clas_mod   = clasmod.clas_mod
            and selesug_redi.clas_item  = 'linha a')
            where codtipoitem = '006'
           
            
            and grife in ('ALEXANDER MCQUEEN','ALTUZARRA','BOTTEGA VENETA','BOUCHERON','BRIONI','CARTIER','CHRISTOPHER KANE','GUCCI','MCQ', 'POMELLATO','SAINT LAURENT','STELLA MCCARTNEY','TOMAS MAIER', 'AZZEDINE', 'MONTBLANC','PUMA')
            


            ) as Base 

            ) as chave 
            ) as final

            /** VARIAVEL DISPONIBILIDADE jde_status**/
            left join (
            select agrup, status, ifnull(fator,1) fator_disp from fator_status 

            ) as selefator
            on (selefator.agrup     = final.agrupamento 
            and selefator.status = final.prox_status)


            ) as fina2
            
           

            order by agrupamento, modelo, cod_sec asc
            ");


$processamento2 = date("YmdHis");


if (count($processa) > 0) {
    $total_sql = count($processa);

            //\DB::connection('go')->select("truncate table itens");
    $index = 0;
    foreach ($processa as $processamento) {
        $index++;
        $query = "INSERT INTO `processa_kering`(`processamento`, `id_item`, `fornecedor`, `agrup`, `tipo`, `grife`, `modelo`, `secundario`, `primario`, `existente`, `pre_pedido`, `em_separacao`, `saldo_partes`, `beneficiamento`, `col_m`, `col_i`, `clas_m`, `clas_i`, `idade`, `genero`, `material`, `valor`, `orcamento`, `etq`, `cep`, `cet`, `manutencao`, `ind_ultimo_st`, `ultimo_st`, `dt_ultimo_st`,  `pot_geral_mes`, `pot_red_mes`, `pot_redi_mes`, `qtde_grife`, `qtde_most`, `pot_most`, `chave_utilizada`, `regra_utilizada`, `pot_mes_utilizado`, `potencial`, `status_atual`, `ind_status_atual`, `acao_tablet`, `fator_disp`, `meta_indice`, `novo_orc`, `potencial1`, `potencial3`, `status3`, `clas_m3`, `clas_i3`, `status2`, `clas_m2`, `clas_i2`, `pot_most2`,`liberacao_tablet`, `saldo_trocas`, `saldo_disponivel`, `saldo_15dias`, `saldo_30dias`, `saldo_producao`, `armazenamento`) VALUES ('$processamento2',";    

        foreach ($processamento as $coluna => $valor) {

            $valor2 = addslashes($valor);
            $query .= "'$valor2',";

        }        
        $query = substr($query, 0, -1);
        $query .= ')';

        echo $query;
        \DB::connection('go')->insert($query);

    }
    if ($total_sql != $processa) {
        echo 'erro, contagem nao bate';
    }
} else {
    echo 'erro';
}



    $processa1 = \DB::select("select processamento  from processa_kering

     order by data desc limit 1");
$processamento = $processa1[0]->processamento; 


$statusatual = \DB::connection('go')->select("
    select secundario, id_item, 
    dt_ultimo_st as dataultstatus,
    case 
    when ultimo_st = 'AGUARDAR IMPORTACAO 15 DIAS'  then '15D'
    when ultimo_st = 'IMPORTACAO 15 DIAS' then '15d'
    when ultimo_st = 'AGUARDAR IMPORTACAO 30 DIAS' then '30D'
    when ultimo_st = 'IMPORTACAO 30 DIAS' then '30D'
    when ultimo_st = 'AGUARDAR PRODUCAO' then 'PROD'
    when ultimo_st = 'PRODUCAO' then 'PROD'
    when ultimo_st = 'DISPONIVEL' then 'DISP'
    when ultimo_st = 'ESGOTADO' then 'ESGOT'
    else 0 end as 'codultstatus',

    case 
    when ultimo_st = 'AGUARDAR IMPORTACAO 15 DIAS'  then 'AGUARDAR IMPORTAÇÃO 15 DIAS'
    when ultimo_st = 'IMPORTACAO 15 DIAS' then 'AGUARDAR IMPORTAÇÃO 15 DIAS'
    when ultimo_st = 'AGUARDAR IMPORTACAO 30 DIAS' then 'AGUARDAR IMPORTAÇÃO 30 DIAS'
    when ultimo_st = 'IMPORTACAO 30 DIAS' then 'AGUARDAR IMPORTAÇÃO 30 DIAS'
    when ultimo_st = 'AGUARDAR PRODUCAO' then 'AGUARDAR PRODUÇÃO'
    when ultimo_st = 'PRODUCAO' then 'AGUARDAR PRODUÇÃO'
    when ultimo_st = 'DISPONIVEL' then 'DISPONÍVEL'
    when ultimo_st = 'ESGOTADO' then 'ESGOTADO'
    else 0 end as 'ultstatus',

    case 
    when status3 = 'AGUARDAR IMPORTACAO 15 DIAS'  then 'AGUARDAR IMPORTAÇÃO 15 DIAS'
    when status3 = 'IMPORTACAO 15 DIAS' then 'AGUARDAR IMPORTAÇÃO 15 DIAS'
    when status3 = 'AGUARDAR IMPORTACAO 30 DIAS' then 'AGUARDAR IMPORTAÇÃO 30 DIAS'
    when status3 = 'IMPORTACAO 30 DIAS' then 'AGUARDAR IMPORTAÇÃO 30 DIAS'
    when status3 = 'AGUARDAR PRODUCAO' then 'AGUARDAR PRODUÇÃO'
    when status3 = 'PRODUCAO' then 'AGUARDAR PRODUÇÃO'
    when status3 = 'DISPONIVEL' then 'DISPONÍVEL'
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
    CURRENT_DATE() as 'datastatusatual',
    armazenamento, acao_tablet


    from processa_kering
    where processamento = $processamento
	and (ultimo_st <> status3 or liberacao_tablet <> armazenamento)
	
    
	
    order by secundario asc");



if (count($statusatual) > 0) {
    $total_sql = count($statusatual);

    $client = \App\JDE::connect();

    $index = 0;
    foreach ($statusatual as $statusatual1) {
        $index++;
        $query1 = "UPDATE `itens` SET 
        datastatusatual = '$statusatual1->datastatusatual',
        dataultstatus = '$statusatual1->dataultstatus', 
        codstatusatual = '$statusatual1->codstatusatual', 
        statusatual = '$statusatual1->statusatual', 
        ultstatus = '$statusatual1->ultstatus', 
        codultstatus = '$statusatual1->codultstatus', 
		
		codtipoarmaz = '$statusatual1->acao_tablet'
        where id = '$statusatual1->id_item' "; 
        echo $query1.'<br>';
//dd($query);
        \DB::connection('go')->insert($query1);


        $result = $client->itemUpdate( array( 

          "codItemCurto"=> $statusatual1->id_item,
          "codstatusatutal" => $statusatual1->codstatusatual,
		  "codtipoarmazenamento" => $statusatual1->acao_tablet,
          "filial" => '    01020000',


      ));


    }
}

$var = "<script>javascript:history.back(-2)</script>";
echo $var;




}	





}
