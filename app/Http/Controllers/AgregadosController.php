<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Item;

class AgregadosController extends Controller
{


	
	public function exportaSalesReport(Request $request, $agrupamento) {
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		ini_set('memory_limit', -1);
		ini_set('max_execute_time', -1);
		
		$itens = \DB::select("
		 select 
case
when item.grife in ('DINIZ', 'GO') then 'Z_OTHER'
when item.grife in ('EVOKE', 'EVOKE NOVOS NEGÓCIOS' ) then 'EVOKE'
else item.grife end as Grif, 


case 
when item.grife in ('DINIZ', 'GO') then 'Z_OTHER'
when item.agrup in ('AT01 - ATITUDE (SL)', 'PA01 - PANICO (SL)','MMA01 - MMA (SL)') then 'AT01 - ATITUDE (SL)' 
when item.agrup in ('AT02 - ATITUDE (RX)', 'MMA02 - MMA (RX)') then 'AT02 - ATITUDE (RX)' 
when item.agrup in ('EV01 - EVOKE (SL)', 'EVN01 - EVOKE N. NEG (SL)' ) then 'EV01 - EVOKE (SL)'
when item.agrup in ('EV02 - EVOKE (RX)', 'EVN02 - EVOKE N. NEG (RX)' ) then 'EV02 - EVOKE (RX)'
else item.agrup end as Type, 
 item.modelo Model, item.secundario Item, 

item.colmod Model_colection, 
case 
when (item.colmod in( '','.') and item.clasmod in ('linha a')) then '2019' 
when substring(item.colmod,1,4) <= '2015' then '<=2015' 
else substring(item.colmod,1,4) end as Model_colection_year,

case when substring(item.colmod,1,4) <= '2017' then '' else item.colmod end as Model_colection_res,
item.anoitem Item_colection, 

case when item.clasmod = 'Add sales cat S5 codes here' then 'PROMOCIONAL C' else item.clasmod end as Model_clas, 

item.clasitem Item_clas, item.genero Gender, 
item.idade Age, item.fixacao Lenses_construction, 

vda.ult_30dd  last_30dd,  vda.ult_60dd  last_60dd,  vda.ult_90dd  last_90dd,
vda.ult_120dd last_120dd, vda.ult_150dd last_150dd, vda.ult_180dd last_180dd,
vda.ult_210dd last_210dd, vda.ult_240dd last_240dd, vda.ult_270dd last_270dd,
vda.ult_300dd last_300dd, vda.ult_330dd last_330dd, vda.ult_360dd last_360dd,
vda.vendastt total,

sld.disp_vendas as Availability, 

conf_montado+em_beneficiamento+saldo_parte as Factoring_BR, 

qtd_rot_receb+cet as In_transit, 

ifnull((select sum(estoque)  from producoes_sint pe where pe.cod_sec = item.secundario
),0)as Stock_Factory,

ifnull((select sum(producao) from producoes_sint pe where pe.cod_sec = item.secundario
),0) as In_production,

/**mudar oara producoes_sint
etq as Stock_Factory, 
cep as In_production,
**/


case when substring(item.colmod,1,4) <= '2015' then 0 else saldo_manutencao end as Maintenance,
saldo_trocas as Estrategy_reserve,
saldo_most as Showcases, 

case when substring(item.colmod,1,4) <= '2015' 
then sld.disp_vendas+saldo_trocas+saldo_most+conf_montado+em_beneficiamento+saldo_parte+qtd_rot_receb+cet
else sld.disp_vendas+saldo_trocas+saldo_most+saldo_manutencao+conf_montado+em_beneficiamento+saldo_parte+qtd_rot_receb+cet
end as Stock_total

from go.itens item
left join go.vendas_sint vda 	on vda.curto 	= item.id
left join go.saldos sld 		on sld.curto	= item.id




where item.fornecedor = 'WENZHOU ZHONGMIN GLASSES CQ LTDA' and item.codtipoitem = '006' 
and item.agrup = '$agrupamento' 

");


		$spreadsheet = new Spreadsheet();

		$sheet = $spreadsheet->getActiveSheet();		

		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('E')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('F')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('G')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('H')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('I')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('J')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('K')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('L')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('M')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('N')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('O')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('P')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('Q')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('R')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('T')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('U')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('V')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('W')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('X')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('Y')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('Z')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AA')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AB')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AC')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AD')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AE')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AF')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AG')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('AH')->setAutoSize(true);
		
		
		
		$sheet->setCellValue('A1', 'Grif')
	            ->setCellValue('B1', 'Type')
	            ->setCellValue('C1', 'Model')
	            ->setCellValue('D1', 'Item')
	            ->setCellValue('E1', 'Model_colection')
	            ->setCellValue('F1', 'Model_colection_year')
	            ->setCellValue('G1', 'Item_colection')
	            ->setCellValue('H1', 'Model_clas')
	            ->setCellValue('I1', 'Item_clas')
	            ->setCellValue('J1', 'Gender')
				->setCellValue('K1', 'Age')
				->setCellValue('L1', 'Lenses_construction')
				->setCellValue('M1', 'last_30dd')
				->setCellValue('N1', 'last_60dd')
				->setCellValue('O1', 'last_90dd')
				->setCellValue('P1', 'last_120dd')
				->setCellValue('Q1', 'last_150dd')
				->setCellValue('R1', 'last_180dd')
				->setCellValue('S1', 'last_210dd')
				->setCellValue('T1', 'last_240dd')
				->setCellValue('U1', 'last_270dd')
				->setCellValue('V1', 'last_300dd')
				->setCellValue('W1', 'last_330dd')
				->setCellValue('X1', 'last_360dd')
				->setCellValue('Y1', 'Total')
				->setCellValue('Z1', 'Availability')
				->setCellValue('AA1', 'Factoring_BR')
	            ->setCellValue('AB1', 'In_transit')
	            ->setCellValue('AC1', 'Stock_Factory')
	            ->setCellValue('AD1', 'In_production')
	            ->setCellValue('AE1', 'Maintenance')
	            ->setCellValue('AF1', 'Estrategy_reserve')
	            ->setCellValue('AG1', 'Showcases')
	            ->setCellValue('AH1', 'Stock_total');
		

	    $index = 2;

		foreach ($itens as $item) {

			
				
			$sheet->setCellValue('A'.$index, $item->Grif)
		            ->setCellValue('B'.$index, $item->Type)
		            ->setCellValue('C'.$index, $item->Model)
		            ->setCellValue('D'.$index, $item->Item)
		            ->setCellValue('E'.$index, $item->Model_colection)
		            ->setCellValue('F'.$index, $item->Model_colection_year)
		            ->setCellValue('G'.$index, $item->Item_colection)
		            ->setCellValue('H'.$index, $item->Model_clas)
		            ->setCellValue('I'.$index, $item->Item_clas)
		            ->setCellValue('J'.$index, $item->Gender)
					->setCellValue('K'.$index, $item->Age)
					->setCellValue('L'.$index, $item->Lenses_construction)
					->setCellValue('M'.$index, $item->last_30dd)
					->setCellValue('N'.$index, $item->last_60dd)
					->setCellValue('O'.$index, $item->last_90dd)
					->setCellValue('P'.$index, $item->last_120dd)
					->setCellValue('Q'.$index, $item->last_150dd)
					->setCellValue('R'.$index, $item->last_180dd)
					->setCellValue('S'.$index, $item->last_210dd)
					->setCellValue('T'.$index, $item->last_240dd)
					->setCellValue('U'.$index, $item->last_270dd)
					->setCellValue('V'.$index, $item->last_300dd)
					->setCellValue('W'.$index, $item->last_330dd)
					->setCellValue('X'.$index, $item->last_360dd)
					->setCellValue('Y'.$index, $item->total)
					->setCellValue('Z'.$index, $item->Availability)
					->setCellValue('AA'.$index, $item->Factoring_BR)
		            ->setCellValue('AB'.$index, $item->In_transit)
		            ->setCellValue('AC'.$index, $item->Stock_Factory)
		            ->setCellValue('AD'.$index, $item->In_production)
		            ->setCellValue('AE'.$index, $item->Maintenance)
		            ->setCellValue('AF'.$index, $item->Estrategy_reserve)
		            ->setCellValue('AG'.$index, $item->Showcases)
		            ->setCellValue('AH'.$index, $item->Stock_total);
				
				
			$index++;

		}            
		
		$nome = 'salesreport_'.date("Y-m-d").'_'.$agrupamento.'.xlsx';
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$nome.'"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// // If you're serving to IE over SSL, then the following may be needed
		// header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		// header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		// header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		// header ('Pragma: public'); // HTTP/1.0


		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');



	}

	
	public static function listaAgregados () {


		$modeloagregado = \DB::connection('go')->select("select modelo,  sum(vda_30)vda30dd, sum(vda_60)vda60dd ,sum(vda_90)vda90dd,sum(a_180dd) a_180dd, sum(vendas)vendas, sum(disponivel) as brasil, sum(orcamento) as orcamentos,
sum(etq)etq, sum(cep)cep, sum(cet) as cet,sum(saldo_manutencao) as saldo_manutencao, 
(select secundario from itens b where base3.modelo = b.modelo 
			and ((b.secundario like 'estojo%' and b.modelo <> 'estojo') or (b.secundario like 'tag%' ))
			order by secundario asc limit 1) as item, agrup,
sum(ttvinc) ttvinc,
(select count(c.agrup) from itens c where LEFT(base3.modelo,4) = LEFT(c.agrup,4) and c.codtipoarmaz not in ('o','i') and c.codtipoitem = '006' ) as ttagrup



from(

select base2.agrup, base2.modelo,  vda_30, vda_60,vda_90,base2.a_180dd, vendas,
sum(disponivel) as disponivel,  sum(cet) as cet,sum(saldo_manutencao) as saldo_manutencao,
case when sum(orctt) is null then 0 else sum(orctt) end as orcamento,
case when sum(producao) is null then 0 else sum(producao) end as cep,
case when sum(estoque) is null then 0 else sum(estoque) end as etq,
sum(ttvinc) ttvinc


from(
select  agrup, modelo,  Agregado, id_agregado,
sum(vda_30) vda_30,
 sum(vda_60) vda_60,
sum(vda_90) vda_90,
sum(a_180dd) a_180dd,
sum(vendas) vendas,
count(peca) as ttvinc


from(
select b.modelo as modelo, b.secundario as Agregado,a.secundario as peca,
 case when vendas_sint.ult_30dd is null then 0 else vendas_sint.ult_30dd end as vda_30,
 case when vendas_sint.ult_60dd is null then 0 else vendas_sint.ult_60dd end as vda_60,
 case when vendas_sint.ult_90dd is null then 0 else vendas_sint.ult_90dd end as vda_90,
 case when  vendas_sint.a_180dd is null then 0 else  vendas_sint.a_180dd end as  a_180dd,
 case when  vendas_sint.vendastt is null then 0 else  vendas_sint.vendastt end as vendas,
 b.id as id_agregado, b.agrup


from itens a
left join vendas_sint on a.id = vendas_sint.curto
left join agregados on a.id = agregados.num_curto and ((agregados.agregado like 'estojo%' and agregados.agregado <> 'ESTOJO') or (agregados.agregado like 'tag%' and agregados.agregado <> 'tag'))
left join itens b on b.id = agregados.curto_agregado

where 
a.codtipoarmaz <> 'o' AND a.codtipoarmaz <> 'i'
and  b.secundario IS NOT NULL) as base
group by modelo,  Agregado, id_agregado, base.agrup) as base2

left join saldos on saldos.curto = id_agregado
left join orcamentos on orcamentos.curto = id_agregado
left join producoes_sint on producoes_sint.id = id_agregado

group by base2.modelo,  vda_30, vda_60,vda_90,base2.a_180dd, vendas, base2.agrup) as base3

where modelo<>'estojo' and modelo<>'cartao' and modelo<>'nao apagar'
group by base3.modelo, agrup

order by   base3.modelo asc
	

			");
		
		

return view('produtos.agregados.agregados')->with('modeloagregado', $modeloagregado);
		

	}
	
	public static function listaAgregadosItens($modelo) {


		$itensagregado = \DB::connection('go')->select("
			select bb.agrup, bb.modelo, bb.secundario, tipoitem, grife, linha, codstatusatual, valortabela as valor, clasitem, anoitem, ean, descricao, material, tamolho as altura, 
tamponte as profundidade, statusatual, fornecedor, tamhaste as largura, ultcusto, 
base2.vda_30  as  vda30dd,
case when (base2.vda_30 is null or disponivel is null) then 2
else disponivel/base2.vda_30 end as mesesestoque,

base2.vda_60 vda60dd, base2.vda_90 vda90dd, base2.a_180dd, vendas, 
case when ttvinc is null then 0 else ttvinc end as ttvinc,
(select count(c.agrup) from itens c where LEFT(bb.modelo,4) = LEFT(c.agrup,4) and c.codtipoarmaz not in ('o','i') and c.codtipoitem = '006' ) as ttagrup,
disponivel brasil , 

cet, saldo_manutencao,
case when orctt is null then 0 else orctt end as orcamentos,
case when producao is null then 0 else producao end as cep,
case when estoque is null then 0 else estoque end as etq

from itens bb
left join saldos on saldos.curto = bb.id
left join orcamentos on orcamentos.curto = bb.id
left join producoes_sint on producoes_sint.id = bb.id

left join (select  modelo,  Agregado, id_agregado,
sum(vda_30) vda_30,
 sum(vda_60) vda_60,
sum(vda_90) vda_90,
sum(a_180dd) a_180dd,
sum(vendas) vendas,
count(peca) as ttvinc


from(
select b.modelo as modelo, b.secundario as Agregado,a.secundario as peca,
 case when vendas_sint.ult_30dd is null then 0 else vendas_sint.ult_30dd end as vda_30,
 case when vendas_sint.ult_60dd is null then 0 else vendas_sint.ult_60dd end as vda_60,
 case when vendas_sint.ult_90dd is null then 0 else vendas_sint.ult_90dd end as vda_90,
 case when  vendas_sint.a_180dd is null then 0 else  vendas_sint.a_180dd end as  a_180dd,
 case when  vendas_sint.vendastt is null then 0 else  vendas_sint.vendastt end as vendas,
 b.id as id_agregado


from itens a
left join vendas_sint on a.id = vendas_sint.curto
left join agregados on a.id = agregados.num_curto 
left join itens b on b.id = agregados.curto_agregado

where 
a.codtipoarmaz <> 'o' AND a.codtipoarmaz <> 'i'
and  b.secundario IS NOT NULL
and b.modelo = '$modelo'

) as base
group by modelo,  Agregado, id_agregado)as base2 on base2.id_agregado = bb.id


where bb.modelo =  '$modelo'
						
		"); 
		

		

		
		$modeloagregado = \DB::connection('go')->select("select modelo,  sum(vda_30)vda30dd, sum(vda_60)vda60dd ,sum(vda_90)vda90dd,sum(a_180dd) a_180dd, sum(vendas)vendas, sum(disponivel) as brasil, sum(orcamento) as orcamentos,
sum(etq)etq, sum(cep)cep, sum(cet) as cet,sum(saldo_manutencao) as saldo_manutencao, 
(select secundario from itens b where base3.modelo = b.modelo 
			and (b.secundario like 'estojo%' or b.secundario like 'tag%') and b.secundario <> 'estojo' and b.codtipoarmaz not in ('o','i')
			order by secundario asc limit 1) as secundario, 
          
            agrup, 
base3.tipoitem, base3.grife, base3.linha, sum(ttvinc) ttvinc,
(select count(c.agrup) from itens c where LEFT(base3.modelo,4) = LEFT(c.agrup,4) and c.codtipoarmaz not in ('o','i') and c.codtipoitem = '006' ) as ttagrup

from(

select base2.agrup, base2.modelo,  vda_30, vda_60,vda_90,base2.a_180dd, vendas,
sum(disponivel) as disponivel,  sum(cet) as cet,sum(saldo_manutencao) as saldo_manutencao,
case when sum(orctt) is null then 0 else sum(orctt) end as orcamento,
case when sum(producao) is null then 0 else sum(producao) end as cep,
case when sum(estoque) is null then 0 else sum(estoque) end as etq,
 base2.tipoitem, base2.grife, base2.linha, sum(ttvinc) ttvinc

from(
select  agrup, modelo,  Agregado, id_agregado,
sum(vda_30) vda_30,
 sum(vda_60) vda_60,
sum(vda_90) vda_90,
sum(a_180dd) a_180dd,
sum(vendas) vendas, base.tipoitem, base.grife, base.linha,
count(peca) as ttvinc


from(
select b.modelo as modelo, b.secundario as Agregado,a.secundario as peca,
 case when vendas_sint.ult_30dd is null then 0 else vendas_sint.ult_30dd end as vda_30,
 case when vendas_sint.ult_60dd is null then 0 else vendas_sint.ult_60dd end as vda_60,
 case when vendas_sint.ult_90dd is null then 0 else vendas_sint.ult_90dd end as vda_90,
 case when  vendas_sint.a_180dd is null then 0 else  vendas_sint.a_180dd end as  a_180dd,
 case when  vendas_sint.vendastt is null then 0 else  vendas_sint.vendastt end as vendas,
 b.id as id_agregado, b.agrup, b.tipoitem, b.grife, b.linha


from itens a
left join vendas_sint on a.id = vendas_sint.curto
left join agregados on a.id = agregados.num_curto and (agregados.agregado like 'estojo%' or agregados.agregado like 'tag%') and agregados.agregado <> 'estojo'
left join itens b on b.id = agregados.curto_agregado

where 
a.codtipoarmaz <> 'o' AND a.codtipoarmaz <> 'i'
and  b.secundario IS NOT NULL
and b.modelo = '$modelo'


) as base
group by modelo,  Agregado, id_agregado, base.agrup, base.tipoitem, base.grife, base.linha) as base2

left join saldos on saldos.curto = id_agregado
left join orcamentos on orcamentos.curto = id_agregado
left join producoes_sint on producoes_sint.id = id_agregado

group by base2.modelo,  vda_30, vda_60,vda_90,base2.a_180dd, vendas, base2.agrup, base2.tipoitem, base2.grife, base2.linha) as base3

group by base3.modelo, agrup, base3.tipoitem, base3.grife, base3.linha

order by   base3.modelo asc
			
			

			");
				//dd($modeloagregado[0]);

		return view('produtos.agregados.agregadositens')->with('modeloagregado', $modeloagregado)->with('itensagregado', $itensagregado);
		

	}


}
