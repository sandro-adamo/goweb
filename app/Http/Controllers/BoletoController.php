<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CustomerLedger;

class BoletoController extends Controller
{
 

	public function geraBoleto($titulo, $tipo, $parcela) {


		$boleto = \DB::select("select
    tit.dt_vencimento as vencimento, 
    tit.status as status,
    tit.titulo as titulo,
    tit.tipo as tipo,
    tit.parcela as parcela,
    cliente.razao as razao,
    cliente.cnpj as cnpj,
    cliente.endereco as endereco,
    cliente.numero as numero,
    cliente.complemento as complemento,
    cliente.bairro as bairro,
    cliente.cep as cep,
    cliente.municipio as cidade,
    cliente.uf as estado,
    1 as inst_pagto,
    tit.id_conta_bancaria as id_banco, 
    dt_vencimento as vencimento, 
    bol.nosso_numero as nosso_numero, 
    tit.documento as documento,  
    tit.dt_emissao as dt_documento,
    tit.dt_emissao as dt_processamento,
    tit.valor_parcela as valor,
    conta.id_banco as banco2,
    conta.correspondente as banco,
    -- ayan8 as banco,
    conta.agencia as agencia,
    conta.conta as conta,
    conta.conta_dv as dv_conta,
    conta.carteira as carteira,
    0 as mora
from go.titulos tit
left join go.contas_bancarias conta on tit.id_conta_bancaria = conta.id
left join go.boletos bol on tit.titulo = bol.titulo and tit.tipo = bol.tipo and tit.parcela = bol.parcela
left join go.addressbook cliente on tit.id_cliente = cliente.id


where  
tit.titulo = $titulo and tit.tipo = '$tipo' and tit.parcela = '$parcela' ");

		if ($boleto) {

			$boleto = $boleto[0];

            if (trim($boleto->nosso_numero) == '') {
                dd('boleto nao disponivel');
            }


            if ($boleto->banco2 == '643' && $boleto->banco == '643' ) {

                if (\Auth::user()->admin <> 1) {
                   dd("em manutencao");
                }
                return view('financeiro/boletos/boleto_pine')->with('boleto', $boleto);

            } 


            if ($boleto->banco2 == '707' && $boleto->banco == '237' ) {

                if (\Auth::user()->admin <> 1) {
                   dd("em manutencao");
                }
                return view('financeiro/boletos/boleto_daycoval')->with('boleto', $boleto);

            } 


            if ($boleto->banco2 == '107' && $boleto->banco == '237' ) {

                return view('financeiro/boletos/boleto_bbm')->with('boleto', $boleto);

            } 



            if ($boleto->banco2 == '611' && $boleto->banco == '237' ) {

                dd('teste');

                return view('financeiro/boletos/boleto_paulista')->with('boleto', $boleto);

            } 

			if ($boleto->status == 'P' && \Auth::user()->admin <> 1) {

				dd('ja esta pago');

			}

			if ($boleto->banco2 == '237' && $boleto->banco == '237' ) {

				return view('financeiro/boletos/boleto_bradesco')->with('boleto', $boleto);

			}

			if ($boleto->banco == '341' ) {

				return view('financeiro/boletos/boleto_itau')->with('boleto', $boleto);

			}

            if ($boleto->banco == '320' ) {


                return view('financeiro/boletos/boleto_ccb')->with('boleto', $boleto);

            }


            if ($boleto->banco == '033' ) {


                return view('financeiro/boletos/boleto_santander')->with('boleto', $boleto);

            }

		}


	}



    public function geraBoleto2($titulo, $tipo, $parcela) {


        $boleto = \DB::connection('jde_py')->select("select
    rpurdt,
    CAST((DATEADD(year, rpurdt / 1000, 0) + DATEADD(day,rpurdt % 1000, 0) - 1 ) as date) as vencimento, 
    rppst as status,
    rpdoc as titulo,
    rpdct as tipo,
    rpsfx as parcela,
    abalph as razao,
    abtax as cnpj,
    aladd1 as endereco,
    aladd2 as numero,
    aladd3 as complemento,
    aladd4 as bairro,
    aladdz as cep,
    alcty1 as cidade,
    aladds as estado,
    rpryin as inst_pagto,
    rpurab as id_banco, 
    CAST((DATEADD(year, rpddj / 1000, 0) + DATEADD(day,rpddj % 1000, 0) - 1 ) as date) as vencimento, 
    f7603b1.cibbdn as nosso_numero, 
    concat(substring(rpvinv,2,8), substring(rpsfx,2,2)) as documento,  
    CAST((DATEADD(year, rpdivj / 1000, 0) + DATEADD(day,rpdivj % 1000, 0) - 1 ) as date) as dt_documento,
    rpdivj as dt_processamento,
    rpag/100 as valor,
    ayan8 as banco2,
    ayan8bk as banco,
    -- ayan8 as banco,
    aytnst as agencia,
    aycbnk as conta,
    aychkd as dv_conta,
    AYBACS as carteira,
    cijmsb as mora
   -- ,f0030.*, rpurdt, rpryin, rpurab, f7603b1.* 
from crpdta.f03b11 
-- left join proddta.f0030 on aybktp = 'G' and ayaid = CONCAT(REPLICATE('0', 8 - LEN(rpurab)) , rpurab)
left join crpdta.f550030 on aybktp = 'G' and ayaid = rpurab -- CONCAT(REPLICATE('0', 8 - LEN(rpurab)) , rpurab)
left join crpdta.f7603b1 on cidoc = rpdoc and cidct = rpdct and cikco = rpkco and cisfx = rpsfx
-- left join prodctl.f0005 on drsy = '55' and drrt= 'BA' and rtrim(ltrim(drky)) = rtrim(ltrim(ayan8))
left join crpdta.f0101 on rpan8 = aban8
left join crpdta.f0116 on alan8 = aban8 


where  
rpdoc = $titulo and rpdct = '$tipo' and rpsfx = '$parcela' ");

        if ($boleto) {

            $boleto = $boleto[0];



            if ($boleto->banco2 == '643' && $boleto->banco == '643' ) {

                if (\Auth::user()->admin <> 1) {
                   dd("em manutencao");
                }
                return view('financeiro/boletos/boleto_pine')->with('boleto', $boleto);

            } 


            if ($boleto->banco2 == '707' && $boleto->banco == '237' ) {

                if (\Auth::user()->admin <> 1) {
                   dd("em manutencao");
                }
                return view('financeiro/boletos/boleto_daycoval')->with('boleto', $boleto);

            } 


            if ($boleto->banco2 == '107' && $boleto->banco == '237' ) {

                if (\Auth::user()->admin <> 1) {
                   // dd("em manutencao");
                }
                return view('financeiro/boletos/boleto_bbm')->with('boleto', $boleto);

            } 



            if ($boleto->banco2 == '611' && $boleto->banco == '237' ) {

                dd('teste');

                return view('financeiro/boletos/boleto_paulista')->with('boleto', $boleto);

            } 

            if ($boleto->status == 'P' && \Auth::user()->admin <> 1) {

                dd('ja esta pago');

            }

            if ($boleto->banco2 == '237' && $boleto->banco == '237' ) {

                return view('financeiro/boletos/boleto_bradesco')->with('boleto', $boleto);

            }

            if ($boleto->banco == '341' ) {

                return view('financeiro/boletos/boleto_itau')->with('boleto', $boleto);

            }

            if ($boleto->banco == '320' ) {


                return view('financeiro/boletos/boleto_ccb')->with('boleto', $boleto);

            }


            if ($boleto->banco == '033' ) {


                return view('financeiro/boletos/boleto_santander')->with('boleto', $boleto);

            }

        }


    }


}
