<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Session;

class EsgotadoController extends Controller
{

	public function grava_devolucao(Request $request) {


		if ($request->referencia) {


			$id_usuario = \Auth::id();

			$item = \App\Item::where('secundario', $request->referencia)->first();

			if ($item) {

				$grifes = Session::get('grifes');

				$situacao = \DB::select("

		       	select statusatual, codgrife, grife, modelo, secundario, codgrife, 
		        case when statusatual in ('ENTREGA IMEDIATA','DISPONÍVEL EM 15 DIAS','DISPONÍVEL EM 30 DIAS') and codgrife in $grifes
		        then ' MANTER' else 'DEVOLVER' end as Situacao_Peca
		        
		        from itens

				where secundario = '$item->secundario'");

				if ($situacao) {

					$situacao_item = $situacao[0]->Situacao_Peca;

					$query = \DB::select("insert into devolucoes ( id_usuario, id_lista, id_item, secundario, situacao) values ($id_usuario, 1, $item->id, '$item->secundario', '$situacao_item') ");

				}

			} else {

				$request->session()->flash('alert-warning', 'Item não encontrado');

			}


		}

		return redirect()->back()->with('item', $situacao);

	}



	public function gera_devolucao(Request $request) {

		$id_usuario = \Auth::id();

		$listas = \DB::select("select * from devolucoes where id_usuario = $id_usuario");

		return view('produtos.esgotados.gera_devolucao')->with('listas', $listas);

	}

	
	
	public function pesquisastatus(Request $request) {
/*
		$item = new \App\Item();

		if ($request->referencia) {
			$processa = \DB::select("select processamento  from processa
			order by data desc limit 1");
			$processamento = $processa[0]->processamento; 
			
			
			$item = \App\StatusProcessa::where('processamento', $processamento)
									     ->where('secundario', $request->referencia)->first();
			
		
			
			
			$modelo = \App\StatusProcessa::where('modelo', $item->modelo)
										 ->where('processamento', $processamento)->get();
			
			$foto_baixa1 	= '/img/BAIXA/'.$item->agrup.'/'.$item->secundario.'jpg';
		$foto_baixa2 	=  '/img/BAIXA/'.$item->agrup.'/'.$item->secundario.'JPG';
		
		
		if (file_exists($foto_baixa1)) {
			$array["foto_modelo"] = true;	
		}
		
		if (file_exists($foto_baixa2)) {
			$array["foto_modelo"] = true;	
		}		
		
 					
				
		}
		else {
			$processa = \DB::select("select processamento  from processa
			order by data desc limit 1");
			$processamento = $processa[0]->processamento; 
			
			
			$item = \App\StatusProcessa::where('processamento', $processamento)
									     ->where('secundario', 'ah6254 a01')->first();
			
			
			
			
			$modelo = \App\StatusProcessa::where('modelo', 'ah6254')
										 ->where('processamento', $processamento)->get();
		}
*/

//		return view('produtos.esgotados.pesquisa_status')->with('item', $item)->with('modelo', $modelo);;
		return view('produtos.esgotados.pesquisa_status');
		
		
	}



	public function listaEsgotados() {


		$id_usuario = \Auth::user()->id_addressbook;

		$processamento = \App\StatusProcessa::orderBy('data', 'desc')->select('processamento')->first();


		$geral = \DB::select("select statusatual as status_atual, count(malas.id_item) as itens
									from malas
									/*left join processa on processa.id_item = mostruarios.id_item and processamento = '$processamento->processamento'*/
									left join itens on id_item = itens.id
									where malas.id_rep = '$id_usuario'
									group by statusatual");


		$divergencia = \DB::select("select acao, ind_status_atual, statusatual as status_atual,  ind_ultimo_status, ultstatus as ultimo_st, count(itens) AS itens  from (

				select 
				case 
				when atual.indice = 9 and ultimo.indice = 9 	 then 'e_devolver'
				 when atual.indice <= 5 and ultimo.indice <= 5 then 'a_manter_venda'
				 when atual.indice <= 5 and ultimo.indice > 5  then 'b_retornar_venda'
				 when atual.indice > 5 and ultimo.indice <= 5  then 'c_tirar_venda'
				 when atual.indice > 5 and ultimo.indice > 5   then 'd_manter_fora' else 'o_outro'
				end as acao, 

				atual.indice as ind_status_atual, statusatual, ultimo.indice as ind_ultimo_status, ultstatus,  malas.id_item AS itens

				from malas
				/*left join processa on processa.id_item = malas.id_item and processamento = $processamento->processamento*/
				left join itens on id_item = itens.id
				left join ind_status atual on codstatusatual = atual.id_status
				left join ind_status ultimo on codultstatus = ultimo.id_status



				WHERE atual.indice <> ultimo.indice and malas.id_rep = '$id_usuario'

				) as sele1

				group by acao, ind_status_atual, status_atual, ind_ultimo_status, ultimo_st
				order by acao, ind_status_atual, ind_ultimo_status");


		return view('produtos.esgotados.lista')->with('geral', $geral)->with('divergencia', $divergencia);

	}



	public function exportaGeralMala() {

		$id_usuario = \Auth::user()->id_addressbook;

		$processamento = \App\StatusProcessa::orderBy('data', 'desc')->select('processamento')->first();

		$itens = \DB::select("
		 select  itens.agrup, itens.secundario, itens.statusatual as statusatual, date(datastatusatual) as dt_statusatual, itens.ultstatus as ultstatus, 
       
       date(dataultstatus) as dt_ultstatus,            
	       
      
        itens.colmod as  colmod, itens.valortabela as preco, itens.Ean as ean, itens.Descricao as descricao, tamolho
from malas

left join itens on itens.id = malas.id_item

where malas.id_rep = '$id_usuario'
and malas.local = 'mala'

group by  itens.agrup, itens.secundario, itens.statusatual , date(datastatusatual), itens.ultstatus , 
       
       date(dataultstatus),            
	       
      
        itens.colmod , itens.valortabela , itens.Ean , itens.Descricao , tamolho ");
	

		$spreadsheet = new Spreadsheet();

		$sheet = $spreadsheet->getActiveSheet();		

		$spreadsheet->getActiveSheet()->getStyle('D')->getNumberFormat()
		    ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER);
		$sheet->setCellValue('A1', 'Agrupamento')
	            ->setCellValue('B1', 'Codigo do Produto')
	            ->setCellValue('C1', 'Descricao do Produto')
	            ->setCellValue('D1', 'EAN')
	            ->setCellValue('E1', 'Colecao')
	            ->setCellValue('F1', 'Preco')
	            ->setCellValue('G1', 'Ultimo Status')
	            ->setCellValue('H1', 'Dt_Ultimo_status')
	            ->setCellValue('I1', 'Penultimo Status')
	            ->setCellValue('J1', 'Dt_Penultimo_status')
				->setCellValue('K1', 'tamolho');

	    $index = 2;

		foreach ($itens as $item) {

			
			$sheet->setCellValue('A'.$index, $item->agrup)
					->setCellValue('B'.$index, $item->secundario)
					->setCellValue('C'.$index, $item->descricao)
					->setCellValue('D'.$index, $item->ean)
					->setCellValue('E'.$index, $item->colmod)
					->setCellValue('F'.$index, $item->preco)
					->setCellValue('G'.$index, $item->statusatual)
					->setCellValue('H'.$index, $item->dt_statusatual)
					->setCellValue('I'.$index, $item->ultstatus)
					->setCellValue('J'.$index, $item->dt_ultstatus)
					->setCellValue('K'.$index, $item->tamolho);
			$index++;

		}            

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="geral_mala.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0


		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');



	}

	
	
	

	public function exportaDivergentes() {

		$id_usuario = \Auth::user()->id_addressbook;


		$processamento = \App\StatusProcessa::orderBy('data', 'desc')->select('processamento')->first();
		

		$itens = \DB::select("
			select*
			from(
select itens.agrup, itens.secundario, descricao, colmod, valortabela as preco, tamolho,
case when codultstatus in ('DIS', '15D', '30D') and codstatusatual in ('DIS', '15D', '30D') then 'manter_venda'
when codultstatus in ('DIS', '15D', '30D') and codstatusatual in ('esg', 'pro') then 'tirar_venda'
when codultstatus in ('esg', 'pro') and codstatusatual in ('DIS', '15D', '30D') then 'retornar_venda' 
when codultstatus in ('esg', 'pro') and codstatusatual in ('esg', 'pro') then 'manter_fora' 

else 'o_outro' end as acao, 
statusatual, date(datastatusatual) as dt_statusatual, ultstatus, date(dataultstatus) as dt_ultstatus
from malas

left join itens on malas.id_item = itens.id

/*left join processa on processa.id_item = malas.id_item and processamento = $processamento->processamento*/

WHERE  malas.id_rep = '$id_usuario'
and malas.local = 'mala'
order by itens.agrup, itens.modelo asc) as base1
where acao in ('tirar_venda','retornar_venda','manter_venda','manter_fora')
                
") ;



// 		$itens = \DB::select("
// select agrup, secundario, itens.descricao, colmod, itens.valortabela as preco, itens.tamolho,
// case when ultstatus in ('ENTREGA IMEDIATA', 'DISPONIVEL EM 15 DIAS', 'DISPONIVEL EM 30 DIAS') and statusatual in ('ENTREGA IMEDIATA', 'DISPONIVEL EM 15 DIAS', 'DISPONIVEL EM 30 DIAS') then 'manter_venda'
// when ultstatus in ('ENTREGA IMEDIATA', 'DISPONIVEL EM 15 DIAS', 'DISPONIVEL EM 30 DIAS') and statusatual in ('ESGOTADO', 'EM PRODUCAO') then 'tirar_venda'
// when ultstatus in ('ESGOTADO', 'EM PRODUCAO') and statusatual in ('ENTREGA IMEDIATA', 'DISPONIVEL EM 15 DIAS', 'DISPONIVEL EM 30 DIAS') then 'retornar_venda' 
// when ultstatus in ('ESGOTADO', 'EM PRODUCAO') and statusatual in ('ESGOTADO', 'EM PRODUCAO') then 'manter_fora' 

// else 'o_outro' end as acao, 
// statusatual, date(datastatusatual) as dt_statusatual, ultstatus, date(dataultstatus) as dt_ultstatus
// from  itens
// left join repXgrife on itens.codgrife = repXgrife.grife

// WHERE ultstatus <> statusatual 
// and repXgrife.an8 = $id_usuario
// and codtipoitem = 006
// and statusatual in ('ENTREGA IMEDIATA', 'DISPONIVEL EM 15 DIAS', 'DISPONIVEL EM 30 DIAS','ESGOTADO', 'EM PRODUCAO')
// and colmod <> 'CANCELADO'

// group by agrup, secundario, itens.descricao, colmod, itens.valortabela , itens.tamolho, date(datastatusatual) , date(dataultstatus), ultstatus, statusatual, modelo
//  order by agrup, modelo asc") 
// 		//or die(mysql_error())
// 		;
		

		$spreadsheet = new Spreadsheet();

		$sheet = $spreadsheet->getActiveSheet();		

		$sheet->setCellValue('A1', 'Acao')
			->setCellValue('B1', 'Agrupamento')
            ->setCellValue('C1', 'Codigo do Produto')
            ->setCellValue('D1', 'Descricao do Produto')
            ->setCellValue('E1', 'Colecao')
            ->setCellValue('F1', 'Preco')
            ->setCellValue('G1', 'Ultimo Status')
            ->setCellValue('H1', 'Dt_Ultimo_status')
            ->setCellValue('I1', 'Penultimo Status')
            ->setCellValue('J1', 'Dt_Penultimo_status')
			->setCellValue('K1', 'tamolho');

	    $index = 2;

		foreach ($itens as $item) {

			
			$sheet->setCellValue('A'.$index, $item->acao)
					->setCellValue('B'.$index, $item->agrup)
					->setCellValue('C'.$index, $item->secundario)
					->setCellValue('D'.$index, $item->descricao)
					->setCellValue('E'.$index, $item->colmod)
					->setCellValue('F'.$index, $item->preco)
					->setCellValue('G'.$index, $item->statusatual)
					->setCellValue('H'.$index, $item->dt_statusatual)
					->setCellValue('I'.$index, $item->ultstatus)
					->setCellValue('J'.$index, $item->dt_ultstatus)
					->setCellValue('K'.$index, $item->tamolho);
			$index++;

		}            
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="divergencia_semanal.xlsx"');
		header('Cache-Control: max-age=0');
		// // If you're serving to IE 9, then the following may be needed
		// header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		// header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		// header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		// header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		// header ('Pragma: public'); // HTTP/1.0
		
		$writer = new Xlsx($spreadsheet);
		//$writer->addHeaderLine('Content-Type', 'application/octet-stream');
		$writer->save('php://output');



	}


}
