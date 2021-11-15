<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class ReportController extends Controller
{

		public function VolumeEstoque() {
			$volume = \DB::select("select Agrupamento, b_c as 'BC', 2018_a as 'a2018_a', 2018_19_a as 'a2018_19_a', 2019_1 as a2019_1, 
2020_01 a2020_01, 2020_08 a2020_08 ,2021_01 a2021_01, 2021_08 a2021_08,
(2019_1+2020_01+2020_08+2021_01)as Sld_total, Meta_1_semestre Meta_1_semestre, 
(2019_1+2020_01+2020_08+2021_01)-Meta_1_semestre as Sld_1_fim_semestre,
Meta_2_semestre Meta_2_semestre,
(2019_1+2020_01+2020_08+2021_01+2021_08)-(Meta_1_semestre+meta_2_semestre) as  Sld_2_fim_semestre
from(
Select agrupamento, sum(b_c) as 'B_C', sum(menor_2018_a) as '2018_a', sum(2018_19_a) as '2018_19_a',sum(2019_1) as '2019_1',
sum(2020_01) as 2020_01, sum(2020_08) as 2020_08, sum(2021_01) as 2021_01, sum(2021_08) as 2021_08,
ifnull((select sum(meta) as '1_semestre' from metas where ano = 2021 and mes in (0) and agrup = agrupamento),0) as Meta_1_semestre,

(select sum(meta) as '1_semestre' from metas where ano = 2021 and mes in ('11','12') and agrup = agrupamento) as Meta_2_semestre
from(
select Agrupamento, 
case when coleção = 'B/C' then sum(estoque_total_real) else 0 end as 'B_C',
case when coleção = '<2018 a-' then sum(estoque_total_real) else 0 end as 'menor_2018_a',
case when coleção = '2018/19 a-' then sum(estoque_total_real) else 0 end as '2018_19_a',
case when coleção = '2019' then sum(estoque_total_real) else 0 end as '2019_1',

case when coleção = '2020 01' then sum(ifnull(estoque_total_real,0)) else 0 end as '2020_01',
case when coleção = '2020 08' then sum(ifnull(estoque_total_real,0)) else 0 end as '2020_08',
case when coleção = '2021 01' then sum(ifnull(estoque_total_real,0)) else 0 end as '2021_01',
case when coleção = '2021 08' then sum(ifnull(estoque_total_real,0)) else 0 end as '2021_08'
from(


select itens.id Num_Curto, itens.grife Grife, itens.linha Linha, itens.agrup Agrupamento, itens.modelo Modelo, itens.secundario Cod_Secundario, 
itens.primario Cod_Primario, itens.ean EAN_GTIN, itens.valortabela Valor, 
itens.anomod Ano_Modelo, 
case 
when itens.clasmod in('promocional c', 'coleção b')  then 'B/C'
when itens.anomod < 2018 and  itens.clasmod in('linha a-')  then '<2018 a-'
when itens.anomod in ('2019','2018') and  itens.clasmod in('linha a-')  then '2018/19 a-'
when itens.anomod <=2019 then '2019'
when itens.colmod in ('2020 01','2020 02','2020 03','2020 04','2020 05','2020 06') then '2020 01'
when itens.colmod in ('2020 07','2020 08','2020 09','2020 10','2020 11','2020 12') then '2020 08'
when itens.colmod in ('2021 01','2021 02','2021 03','2021 04','2021 05','2021 06') then '2021 01'
when itens.colmod in ('2021 07','2021 08','2021 09','2021 10','2021 11','2021 12') then '2021 08'

else itens.colmod end as 'Coleção',
itens.colmod Col_Modelo, itens.anoitem Ano_Item, 
itens.colitem Col_Item, itens.clasmod Class_Modelo, itens.clasitem Class_Item, 

saldos.disponivel Disponivel, (saldos.disponivel+saldos.res_temporaria) Disponivel_Fisico, 
case when (saldos.disponivel-ifnull(orcamentos.orcvalido,0))<=0 then 0 else (saldos.disponivel-ifnull(orcamentos.orcvalido,0)) end as Disponivel_Real, 
(saldos.conf_montado+ saldos.em_beneficiamento+ saldo_passivel+ saldos.cet+ saldos.cet_li+ ifnull(etq,0)+ ifnull(cep,0)) as Projecao_Disp,
saldos.existente Existente, saldos.res_definitiva, saldos.res_temporaria, ifnull(orcamentos.orcvalido,0) Orcamento, 
saldos.conf_montado Conf_Montado, saldos.em_beneficiamento Em_Beneficiamento, saldo_passivel Saldo_Parte, saldos.cet CET, 
ifnull(etq,0) ETQ, ifnull(cep,0) CEP, 
(saldos.disponivel+ saldos.conf_montado+ saldos.em_beneficiamento+ saldo_passivel+ saldos.cet+ saldos.cet_li+ifnull(etq,0)+ ifnull(cep,0)) as Estoque_Total,
(saldos.disponivel+ saldos.conf_montado+ saldos.em_beneficiamento+ saldo_passivel+ saldos.cet+ saldos.cet_li+ ifnull(etq,0)+ ifnull(cep,0))-ifnull(orcamentos.orcvalido,0) as Estoque_Total_Real,
saldos.saldo_most Mostruario, saldos.saldo_trocas Reserva_Trocas, saldos.saldo_manutencao Manutencao, 
case 
when fornecedor = 'KERING EYEWEAR SPA' and ifnull(sum(vendas_sint.ult_30dd),0)> 0 and ifnull(sum(vendas_sint.ult_60dd),0)>0 then format((ifnull(sum(vendas_sint.ult_30dd),0)+ifnull(sum(vendas_sint.ult_60dd),0))/2,0)
when  fornecedor = 'KERING EYEWEAR SPA' and ifnull(sum(vendas_sint.ult_30dd),0)> 0 and ifnull(sum(vendas_sint.ult_60dd),0)>0 and ifnull(sum(vendas_sint.ult_90dd),0)>0 then format(ifnull(sum(vendas_sint.ult_30dd),0)+ ifnull(sum(vendas_sint.ult_60dd),0)+ ifnull(sum(vendas_sint.ult_90dd),0)/3,0)
when fornecedor = 'KERING EYEWEAR SPA' and  ifnull(sum(vendas_sint.ult_30dd),0)> 0 then ifnull(sum(vendas_sint.ult_30dd),0)
when fornecedor = 'KERING EYEWEAR SPA' and  ifnull(sum(vendas_sint.ult_60dd),0)> 0 then ifnull(sum(vendas_sint.ult_60dd),0)
when  fornecedor = 'KERING EYEWEAR SPA' and ifnull(sum(vendas_sint.ult_90dd),0)> 0 then ifnull(sum(vendas_sint.ult_90dd),0)
when ifnull(sum(vendas_sint.ult_30dd),0)> 50 and ifnull(sum(vendas_sint.ult_60dd),0)>50 then format(((ifnull(sum(vendas_sint.ult_30dd),0)+ifnull(sum(vendas_sint.ult_60dd),0)))/2,0)
when  ifnull(sum(vendas_sint.ult_30dd),0)> 50  and ifnull(sum(vendas_sint.ult_90dd),0)>50 then format((ifnull(sum(vendas_sint.ult_30dd),0)+ifnull(sum(vendas_sint.ult_90dd),0))/2,0)
when  ifnull(sum(vendas_sint.ult_60dd),0)> 50  and ifnull(sum(vendas_sint.ult_90dd),0)>50 then format((ifnull(sum(vendas_sint.ult_60dd),0)+ifnull(sum(vendas_sint.ult_90dd),0))/2,0)
when  ifnull(sum(vendas_sint.ult_30dd),0)> 50 and ifnull(sum(vendas_sint.ult_60dd),0)>50 and ifnull(sum(vendas_sint.ult_90dd),0)>50 then format((ifnull(sum(vendas_sint.ult_30dd),0)+ ifnull(sum(vendas_sint.ult_60dd),0)+ ifnull(sum(vendas_sint.ult_90dd),0))/3,0)

when  ifnull(sum(vendas_sint.ult_30dd),0)>= 50 then ifnull(sum(vendas_sint.ult_30dd),0)
when  ifnull(sum(vendas_sint.ult_60dd),0)>= 50 then ifnull(sum(vendas_sint.ult_60dd),0)
when  ifnull(sum(vendas_sint.ult_90dd),0)>= 50 then ifnull(sum(vendas_sint.ult_90dd),0)
when  ifnull(sum(vendas_sint.ult_30dd),0)> ifnull(sum(vendas_sint.ult_90dd),0) and ifnull(sum(vendas_sint.ult_30dd),0)> ifnull(sum(vendas_sint.ult_60dd),0) then ifnull(sum(vendas_sint.ult_30dd),0)
when  ifnull(sum(vendas_sint.ult_60dd),0)> ifnull(sum(vendas_sint.ult_90dd),0)  then ifnull(sum(vendas_sint.ult_60dd),0)
else  ifnull(sum(vendas_sint.ult_90dd),0)
 end as Media_venda,
ifnull(sum(vendas_sint.ult_30dd),0) V_30D, ifnull(sum(vendas_sint.ult_60dd),0) V_60D, ifnull(sum(vendas_sint.ult_90dd),0) V_90D,
ifnull(sum(vendas_sint.ult_30dd+ vendas_sint.ult_60dd+ vendas_sint.ult_90dd),0) VDA_3Meses,
ifnull(sum(vendas_sint.ult_120dd),0) V_120D, ifnull(sum(vendas_sint.ult_150dd),0) V_150D, ifnull(sum(vendas_sint.ult_180dd),0) V_180D,
ifnull(sum(vendas_sint.ult_30dd+vendas_sint.ult_60dd+vendas_sint.ult_90dd+vendas_sint.ult_120dd+vendas_sint.ult_150dd+vendas_sint.ult_180dd),0) as VDA_6Meses,
 ifnull(sum(vendas_sint.ult_210dd),0) V_210D, ifnull(sum(vendas_sint.ult_240dd),0) V_240D, ifnull(sum(vendas_sint.ult_270dd),0) V_270D, 
 ifnull(sum(vendas_sint.ult_300dd),0) V_300D, ifnull(sum(vendas_sint.ult_330dd),0) V_330D, ifnull(sum(vendas_sint.ult_360dd),0) V_360D,
(ifnull(sum(vendas_sint.ult_30dd+vendas_sint.ult_60dd+vendas_sint.ult_90dd+vendas_sint.ult_120dd+vendas_sint.ult_150dd+vendas_sint.ult_180dd+
vendas_sint.ult_210dd+vendas_sint.ult_240dd+vendas_sint.ult_270dd+vendas_sint.ult_300dd+vendas_sint.ult_330dd+vendas_sint.ult_360dd),0)) as VDA_Ult_Ano,
 ifnull(sum(vendas_sint.vendastt),0) Venda_Total,

itens.tipoitem Tipo_Item, itens.codtipoarmaz Tipo_Armaz, itens.ncm NCM, itens.fornecedor Fornecedor, itens.ultstatus Ult_Status, 
itens.statusatual Status_Atual, itens.material Material, itens.genero Genero, itens.idade Idade, itens.estilo Estilo, itens.fixacao Fixacao, 
itens.formato Formato, itens.formatosec Formato_Sec, itens.tecnologia Tecnologia, itens.tamolho Tam_Olho, itens.tamponte Tam_Ponte, 
itens.tamhaste Tam_Haste, itens.corarm1  Cor, itens.codarm2  Cor_Sec, itens.corhas1  Cor_Haste, itens.corhas2  Cor_Haste_Sec, 
itens.corponteira  Cor_Ponteira, itens.corlente Cor_Lente, itens.ultcusto Ult_Custo, itens.mediacusto Media_Custo, itens.descricao Description


from go.itens 
left join go.saldos on itens.id = saldos.curto
left join go.vendas_sint on itens.id = vendas_sint.curto
left join go.orcamentos on itens.id = orcamentos.curto
left join pecaspassiveis on itens.id = pecaspassiveis.curto


where itens.codtipoarmaz not in ('O','0')
and codtipoitem = '006'
and itens.fornecedor <> 'WENZHOU ZHONGMIN GLASSES CO LTD'
-- and itens.codgrife in ('AA','AH','AM','AT','AZ','BC','BG','BR','BV','CH','CK','CL','CT','DU','DZ','EV','NG','GU','GO','HI','JM','JO','MM','MC','PO','PU','SM','ST','SP','TC','TM')
and itens.codgrife in ('ah','at','bg','hi','ev', 'jo', 'jm', 'sp', 'tc')
and itens.agrup not in ('MMA02 - MMA (RX)', 'PA01 - PANICO (SL)', 'MMA01 - MMA (SL)')
group by itens.id, itens.grife, itens.linha, itens.agrup, itens.modelo, itens.secundario, itens.primario, itens.ean, itens.valortabela, 
itens.anomod , itens.colmod , itens.anoitem , itens.colitem , itens.clasmod , itens.clasitem , 

saldos.disponivel, saldos.existente, saldos.res_definitiva, saldos.res_temporaria, orcamentos.orcvalido, 
saldos.conf_montado, saldos.em_beneficiamento, saldos.saldo_parte, saldos.cet, etq, 
cep, saldos.saldo_most, saldos.saldo_trocas, saldos.saldo_manutencao, 

itens.tipoitem, itens.codtipoarmaz, itens.ncm, itens.fornecedor, itens.formato, itens.formatosec, itens.formato, itens.formatosec,
itens.corarm1, itens.codarm2, itens.corhas1, itens.corhas2, itens.corponteira, itens.corlente,
itens.ultstatus, itens.statusatual, itens.material, itens.genero, itens.idade, itens.estilo, itens.fixacao, itens.tecnologia, 
itens.tamolho, itens.tamponte, itens.tamhaste, itens.ultcusto, itens.mediacusto, itens.descricao ,saldo_passivel,cet_li

order by itens.agrup, itens.modelo, itens.secundario asc) as base
group by Agrupamento, coleção) as base
group by agrupamento) as base2");
			//dd($volume);

			 return view('produtos.reports.volume_estoque')->with('volume', $volume);
		}
	
	public function emailEstoque() {

		$query = \DB::select("select grife, agrup, secundario, ean, disp_vendas from (

select * from (

select fim.*, sug.mdv_mensal,disp_vendas/ sug.mdv_mensal as perc from (

select itens.grife, itens.agrup, itens.secundario, itens.ean, clasmod, clasitem, 
case when clasmod = 'novo' then 'LINHA A' else clasmod end as clasmod_vinc,
case when clasitem = 'novo' then 'LINHA A' else clasitem end as clasitem_vinc,

case when saldos.disp_vendas < 0 then  0 else saldos.disp_vendas end as disp_vendas
from itens
left join saldos on saldos.curto = itens.id	
where clasmod like 'linha%' and codtipoarmaz <> 'o' and codtipoitem = '006' and 
(fornecedor not like 'kering%' or grife = 'puma')
and grife not in ('EVOKE NOVOS NEGÓCIOS','DINIZ')

) as fim

left join (select * from sugestoes 
where material = '' and idade = '' and genero = '') as sug
on sug.agrup = fim.agrup 
and sug.clas_mod = fim.clasmod_vinc
and sug.clas_item = fim.clasitem_vinc

) as fim2
    
    where ((grife = 'puma' and disp_vendas > 20 ) 
    or (clasmod_vinc = 'linha a+' and perc > 4 and disp_vendas > 30 )
or (clasmod_vinc = 'linha a' and perc > 3 and disp_vendas > 20 )
or (clasmod_vinc = 'linha a-' and perc > 1 and disp_vendas > 10 ))
    
and ean <> ''
) as fim3
    
order by grife, agrup, secundario");



		$spreadsheet = new Spreadsheet();

		$sheet = $spreadsheet->getActiveSheet();		

		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('E')->setAutoSize(true);

		
		$sheet->setCellValue('A1', 'Grife')
	            ->setCellValue('B1', 'Agrupamento')
	            ->setCellValue('C1', 'Secundario')
	            ->setCellValue('D1', 'EAN')
	            ->setCellValue('E1', 'Disponibilidade');

	
	    $index = 2;

		foreach ($query as $item) {

			
				
			$sheet->setCellValue('A'.$index, $item->grife)
		            ->setCellValue('B'.$index, $item->agrup)
		            ->setCellValue('C'.$index, $item->secundario)
		            ->setCellValue('D'.$index, $item->ean)
		            ->setCellValue('E'.$index, $item->disp_vendas);

				
				
			$index++;

		}            
		
		$nome = 'estoque.xlsx';
		// header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		// header('Content-Disposition: attachment;filename="'.$nome.'"');
		// header('Cache-Control: max-age=0');
		// // If you're serving to IE 9, then the following may be needed
		// header('Cache-Control: max-age=1');

		$writer = new Xlsx($spreadsheet);
		$writer->save($nome);		
	}


}
