<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comissao;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class ComissaoController extends Controller
{


	public function listaComissoes() {

		$id_addressbook = \Auth::user()->id_addressbook;

// 		$comissao = \DB::select("select ano, periodo, id_rep, rep as razao, sum(Comissao) as 'Comissao', sum(Devolucao) as 'Devolucao', sum(Adiantamento) as 'Adiantamento', sum(Inadimplencia) as 'Inadimplencia', sum(Estorno) as 'Estorno' ,
// 	( sum(Comissao) + sum(Estorno) - sum(Devolucao) - sum(Adiantamento) - sum(Inadimplencia) ) as total
// from (
// 	select ano, periodo, id_rep, case when nome <> '' then nome else rtrim(razao) end as rep, 
// 		case when processo in ( 'Apuração Faturamento','Parcelas Pagas no Mês', 'Parcelas Pagamento Legado', 'Parcelas de Acordo Pagas no Mês', 'Parcelas Acordo Pagamento Legado', 'VARIAVEL GARANTIDA') and estorno = '' then sum(comissao) else 0 end as 'Comissao',
// 		case when processo in ( 'Devoluções') then sum(comissao) else 0 end as 'Devolucao',
// 		case when processo in ( 'Adiantamentos') then sum(comissao) else 0 end as 'Adiantamento',
// 		case when processo in ( 'Desconto Inadimplencia','Desconto Acordo Legado Inadimplencia') then sum(comissao) else 0 end as 'Inadimplencia',
//         case when processo in ( 'Parcelas Pagas no Mês' ) and estorno = 'SIM' then sum(comissao) else 0 end as 'Estorno'
// 				from comissoes 
// 				left join addressbook on id_rep = addressbook.id
// 				where id_rep = $id_addressbook
// 				group by razao, ano, periodo,id_rep,processo, estorno
// ) as base
// group by ano, periodo, id_rep, rep
// order by ano desc, periodo desc");


// 		$comissao = \DB::select("
// 	select ano, periodo, id_rep, razao, sum(valor_nf) as valor_nf, sum(valor_irpj) as valor_irpj, sum(valor_descontos) as valor_descontos,
// 	( sum(valor_nf) - sum(valor_irpj) - sum(valor_descontos) ) as valor_liquido 
// from (
// 	select ano, periodo, id_rep, case when nome <> '' then nome else rtrim(razao) end as razao, 
// 	case when categoria = 'Comissoes' then sum(comissao) else 0 end as valor_nf,
// 	case when categoria = 'IRPJ' then sum(comissao) else 0 end as valor_irpj,
// 	case when categoria = 'Adiantamentos' then sum(comissao) else 0 end as valor_descontos

// 					from comissoes 
// 					left join addressbook on id_rep = addressbook.id
// 					where id_rep = $id_addressbook and comissoes.status = 1 and validado <> 0
// 					group by razao, ano, periodo,id_rep, categoria
// ) as base
// group by ano, periodo, id_rep, razao
// order by ano desc, periodo desc");

		$representante = \Session::get('representantes');
	
		$comissao = \DB::select("select ano, periodo,  razao, sum(valor_nf)+sum(ajuste_nf)-sum(Diferenca_pagamento) as valor_nf, sum(valor_irpj) as valor_irpj, sum(valor_descontos)+sum(Diferenca_pagamento) as valor_descontos,
	( sum(valor_nf) - (sum(valor_irpj) + sum(valor_descontos) + sum(Diferenca_pagamento)) )as valor_liquido ,notafiscal


from (
	select ano, periodo, id_rep, case when nome <> '' then nome else rtrim(razao) end as razao, notafiscal,
	-- case when categoria in ('Comissoes','Bonus') then ifnull(sum(comissao),0) else 0 end as valor_nf,
	ifnull(sum(comissao),0) as valor_nf,
	ifnull((select sum(valor) from descontos adt where tipo in ('Imposto') and adt.id_rep in ($representante) and adt.periodo = comissoes.periodo and adt.ano = comissoes.ano),0) as valor_irpj,
	ifnull((select sum(valor) from descontos adt where tipo in ('Adiantamento', 'Leitor', 'Antecipação') and adt.id_rep in ($representante)and adt.periodo = comissoes.periodo and adt.ano = comissoes.ano),0) as valor_descontos,
	ifnull((select sum(valor) from descontos adt where tipo in ('Diferença pagamento') and adt.id_rep in ($representante)and adt.periodo = comissoes.periodo and adt.ano = comissoes.ano),0) as 'Diferenca_pagamento',
	ifnull((select sum(valor) from descontos adt where tipo in ('ajuste nf') and adt.id_rep in ($representante)and adt.periodo = comissoes.periodo and adt.ano = comissoes.ano),0) as 'ajuste_nf'

					from comissoes 
					left join addressbook on id_rep = addressbook.id
					where id_rep in ($representante) and comissoes.status = 1
					group by razao, ano, periodo,id_rep, notafiscal
) as base
group by ano, periodo, razao, notafiscal
order by ano desc, periodo desc");

		return view('comissoes.lista')->with("comissao", $comissao);

	}


	public function detalhesComissao(Request $request, $ano, $periodo) {

		$representante = \Session::get('representantes');

		$id_addressbook = \Auth::user()->id_addressbook;

		$sql_tipo = '';

		if ($request->tipo) {
			switch ($request->tipo) {
				case 'Faturamento':
					$sql_tipo = " and processo in ('Apuração Faturamento') ";
					break;
				case 'Inadimplencia':
					$sql_tipo = " and processo in ('Desconto Inadimplencia') ";
					break;
				case 'Devolucao':
					$sql_tipo = " and processo in ('Devoluções') ";
					break;
				case 'Estorno':
					$sql_tipo = " and processo in ('Parcelas Pagas no Mês') and estorno = 'SIM' ";
					break;				
				default:
					$sql_tipo = '';
					break;
			}
		}

		$sql_nota = '';

		if ($request->nota) {
			$sql_nota = " and nota = '$request->nota' ";
		}


		$sql_titulo = '';
		if ($request->fatura && $request->parcela) {
			$sql_titulo = " and fatura = '$request->fatura' and parcela = '$request->parcela' ";
		}

		$comissao = \DB::select("select *, rep.tipo_comissao,
	(select concat(tipo,' ',obs) from comissoes_eventos com where com.nota = comissoes.nota and id_repres in ($representante) limit 1) detalhe
			from comissoes 
			left join addressbook rep on id_rep = rep.id
			left join addressbook cli on id_cliente = cli.id
			where id_rep in ($representante) and ano = '$ano' and periodo = '$periodo' and comissoes.status = 1 $sql_tipo $sql_nota $sql_titulo");


		$resumo = \DB::select("
select sum(Faturamento) as Faturamento, sum(Inadimplencia) as Inadimplencia, sum(Devolucao) as Devolucao, sum(Estorno) as Estorno, sum(Diferenca_pagamento) as 'Diferença pagamento'
from (
	select 

		case when processo = 'Apuração Faturamento' then valor else 0 end as Faturamento,
		case when processo = 'Desconto Inadimplencia' then valor else 0 end as Inadimplencia,
		case when processo = 'Devoluções' then valor else 0 end as Devolucao,
		case when processo = 'Diferença pagamento' then valor else 0 end as 'Diferenca_pagamento',
		case when processo = 'Parcelas Pagas no Mês' and estorno = 'SIM' then valor else 0 end as Estorno
		
	from comissoes 
	where id_rep in ($representante) and ano = $ano and periodo = $periodo
) as base");

		return view('comissoes.detalhes')->with('resumo', $resumo)->with('comissao', $comissao)->with('ano', $ano)->with('periodo', $periodo);

	}



    public function importaComissoes(Request $request) {



		$uploadfile = '/var/www/html/portalgo/storage/uploads/apuracao072020.csv';
	//	$uploadfile = $uploaddir . basename($_FILES['arquivo']['name']);

		$erros = array();

		//if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $uploadfile)) {

		    if (file_exists($uploadfile)) {

		        $handle = fopen($uploadfile, "r"); 

		        $linha = 1;

		        while (($line = fgetcsv($handle, 100000, ";")) !== FALSE) {

		            if ($linha >= 2) {   
		            	$comissao = new Comissao();

 						if ($line[12] <> '') {
	 						$valor_titulo = str_replace(".", "", $line[12]);
	 						$valor_titulo = str_replace(",", ".", $valor_titulo);
	 					} else {
	 						$valor_titulo = 0;
	 					}

 						if ($line[14] <> '') {
	 						$valor_comissao = str_replace(".", "", $line[14]);
	 						$valor_comissao = str_replace(",", ".", $valor_comissao);
	 					} else {
	 						$valor_comissao = 0;
	 					}

 						if ($line[4] <> '') {
	 						$dt_fatura = explode('/', $line[4]);
	 						$dt_fatura2 = $dt_fatura[2].'-'.$dt_fatura[1].'-'.$dt_fatura[0];
	 					} else {
	 						$dt_fatura2 = NULL;
	 					}

 						if ($line[5] <> '') {
	 						$dt_vencimento = explode('/', $line[5]);
	 						$dt_vencimento2 = $dt_vencimento[2].'-'.$dt_vencimento[1].'-'.$dt_vencimento[0];
	 					} else {
	 						$dt_vencimento2 = NULL;
	 					}

 						if ($line[6] <> '') {
	 						$dt_pagamento = explode('/', $line[6]);
	 						$dt_pagamento2 = $dt_pagamento[2].'-'.$dt_pagamento[1].'-'.$dt_pagamento[0];
	 					} else {
	 						$dt_pagamento2 = NULL;
	 					}

 						$percentual = str_replace(",", ".", $line[13]);

		            	$comissao->ano = '2020';
		            	$comissao->periodo = '8';
		            	$comissao->referencia = $line[0].'-'.$line[1];
		            	$comissao->id_rep = $line[2];
		            	$comissao->id_cliente = $line[3];
		            	$comissao->dt_fatura = $dt_fatura2;
		            	$comissao->dt_vencimento = $dt_vencimento2;
		            	$comissao->dt_pagamento = $dt_pagamento2;
		            	$comissao->nota = $line[7];
		            	$comissao->serie = $line[8];
		            	$comissao->fatura = $line[9];
		            	$comissao->tipo = $line[10];
		            	if ($line[11] <> '') {
		            		$comissao->parcela = $line[11];
		            	} else {
		            		$comissao->parcela = NULL;
		            	}
		            	$comissao->valor = $valor_titulo;
		            	$comissao->percentual = $percentual;
		            	$comissao->validado = 0;

		            	$comissao->status = 0;
		            	$comissao->comissao = $valor_comissao;
		            	$comissao->processo = $line[15];
		            	$comissao->estorno = $line[16];
		            	$comissao->save();


		            }


		            $linha++;

		        } 
		        print_r($erros);
		    } else {

		        	echo 'teste';
		    }

//		}

    }


    public function enviaNotaFiscal(Request $request) {

		ini_set('memory_limit', -1);

		$id_rep = \Auth::user()->id_addressbook;
		$nome_rep = \Auth::user()->nome;

		if ($request->arquivo) {

			$extension = $request->file('arquivo')->extension();

			$nome_arquivo = date('d-m-Y H-i').'_'.$nome_rep;


			$path = $request->file('arquivo')->storeAs('uploads/comissoes/notas/'.$request->ano.'/'.$request->periodo, $nome_arquivo.'.'.$extension);

			if (file_exists('/var/www/html/portalgo/storage/app/'.$path)) {

				$atualiza = \DB::select("update comissoes set notafiscal = '$path' where id_rep = $id_rep and ano = $request->ano and periodo = $request->periodo");

				$request->session()->flash("alert-success", 'Nota Fiscal enviada com sucesso!');

			}

		}

		return redirect()->back();
    }

    public function exportaComissoesFat($ano, $periodo) {


    	$id_rep = \Auth::user()->id_addressbook;

		$spreadsheet = new Spreadsheet();

		$sheet = $spreadsheet->getActiveSheet(0);		

		$spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Agrupamento')
	            ->setCellValue('B1', 'Codigo do Produto');

	    $index = 2;
		$spreadsheet->getActiveSheet(0)->setTitle('Resumo');


		$resumo = \DB::select("
select sum(Faturamento) as Faturamento, sum(Inadimplencia) as Inadimplencia, sum(Devolucao) as Devolucao, sum(Estorno) as Estorno, sum(Diferenca_pagamento) as 'Diferença pagamento'
from (
	select 

		case when processo = 'Apuração Faturamento' then valor else 0 end as Faturamento,
		case when processo = 'Desconto Inadimplencia' then valor else 0 end as Inadimplencia,
		case when processo = 'Devoluções' then valor else 0 end as Devolucao,
		case when processo = 'Diferença pagamento' then valor else 0 end as 'Diferenca_pagamento',
		case when processo = 'Parcelas Pagas no Mês' and estorno = 'SIM' then valor else 0 end as Estorno
		
	from comissoes 
	where id_rep = $id_rep and ano = $ano and periodo = $periodo
) as base");

		$comissoes = \DB::select("select *
from (
	select comissoes.*, cli.razao,

		case 
			when processo = 'Apuração Faturamento' then 'Faturamento'
			when processo = 'Desconto Inadimplencia' then 'Indadimplencia' 
			when processo = 'Devoluções' then  'Devolucao'  
		--	when processo = 'Diferença pagamento' then valor else 0 end as 'Diferenca_pagamento',
			when processo = 'Parcelas Pagas no Mês' and estorno = 'SIM' then  'Estorno' 
			else '' end as processo2
		
	from comissoes 
	left join addressbook cli on id_cliente = cli.id
	where id_rep = $id_rep and ano = $ano and periodo = $periodo
) as base");



		$spreadsheet->getActiveSheet(0)->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet(0)->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet(0)->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet(0)->getColumnDimension('D')->setAutoSize(true);
		
		$spreadsheet->setActiveSheetIndex(0)
				->setCellValue('A1', 'Faturamento no mês')
				->setCellValue('B1', 'Devoluções no mês')
				->setCellValue('C1', 'Inadimplências')
				->setCellValue('D1', 'Estorno Inadimplência');


		if ($resumo) {

			$spreadsheet->setActiveSheetIndex(0)
					->setCellValue('A2', $resumo[0]->Faturamento)
					->setCellValue('B2', $resumo[0]->Devolucao)
					->setCellValue('C2', $resumo[0]->Inadimplencia)
					->setCellValue('D2', $resumo[0]->Estorno);

		}


	   	$spreadsheet->createSheet();	

		$sheet = $spreadsheet->getActiveSheet(1);

		$spreadsheet->setActiveSheetIndex(1)->setCellValue('A1', 'Código')
	            ->setCellValue('B1', 'Razão')
	            ->setCellValue('C1', 'Data Faturamento')
	            ->setCellValue('D1', 'Valor')
	            ->setCellValue('E1', 'Percentual')
	            ->setCellValue('F1', 'Comissão')
	            ->setCellValue('G1', 'Processo');

		$spreadsheet->getActiveSheet(1)->setTitle('Faturamentos');
		$spreadsheet->getActiveSheet(1)->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet(1)->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet(1)->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet(1)->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->getActiveSheet(1)->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->getActiveSheet(1)->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->getActiveSheet(1)->getColumnDimension('G')->setAutoSize(true);

		$index = 2;
		foreach ($comissoes as $comissao) {

			if ($comissao->processo2 == 'Faturamento') {
					
				$spreadsheet->setActiveSheetIndex(1)
						->setCellValue('A'.$index, $comissao->id_cliente)
						->setCellValue('B'.$index, $comissao->razao)
						->setCellValue('C'.$index, $comissao->dt_fatura)
						->setCellValue('D'.$index, $comissao->valor)
						->setCellValue('E'.$index, $comissao->percentual)
						->setCellValue('F'.$index, $comissao->comissao)
						->setCellValue('G'.$index, $comissao->processo);
				$index++;

			}

		}   


	   	$spreadsheet->createSheet();	

		$sheet = $spreadsheet->getActiveSheet(2);

		$spreadsheet->setActiveSheetIndex(2)->setCellValue('A1', 'Código')
	            ->setCellValue('B1', 'Razão')
	            ->setCellValue('C1', 'Data Faturamento')
	            ->setCellValue('D1', 'Valor')
	            ->setCellValue('E1', 'Percentual')
	            ->setCellValue('F1', 'Comissão')
	            ->setCellValue('G1', 'Processo');

		$spreadsheet->getActiveSheet(2)->setTitle('Devoluções');
		$spreadsheet->getActiveSheet(2)->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet(2)->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet(2)->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet(2)->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->getActiveSheet(2)->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->getActiveSheet(2)->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->getActiveSheet(2)->getColumnDimension('G')->setAutoSize(true);
		$index = 2;
		foreach ($comissoes as $comissao) {


			if ($comissao->processo2 == 'Devolucao') {
					
				$spreadsheet->setActiveSheetIndex(2)
						->setCellValue('A'.$index, $comissao->id_cliente)
						->setCellValue('B'.$index, $comissao->razao)
						->setCellValue('C'.$index, $comissao->dt_fatura)
						->setCellValue('D'.$index, $comissao->valor)
						->setCellValue('E'.$index, $comissao->percentual)
						->setCellValue('F'.$index, $comissao->comissao)
						->setCellValue('G'.$index, $comissao->processo);
				$index++;

			}

		}   



	   	$spreadsheet->createSheet();	

		$sheet = $spreadsheet->getActiveSheet(3);

		$spreadsheet->setActiveSheetIndex(3)->setCellValue('A1', 'Código')
	            ->setCellValue('B1', 'Razão')
	            ->setCellValue('C1', 'Data Faturamento')
	            ->setCellValue('D1', 'Valor')
	            ->setCellValue('E1', 'Percentual')
	            ->setCellValue('F1', 'Comissão')
	            ->setCellValue('G1', 'Processo');

		$spreadsheet->getActiveSheet(3)->setTitle('Inadimplências');
		$spreadsheet->getActiveSheet(3)->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet(3)->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet(3)->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet(3)->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->getActiveSheet(3)->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->getActiveSheet(3)->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->getActiveSheet(3)->getColumnDimension('G')->setAutoSize(true);
		$index = 2;
		foreach ($comissoes as $comissao) {


			if ($comissao->processo2 == 'Indadimplencia') {
					
				$spreadsheet->setActiveSheetIndex(3)
						->setCellValue('A'.$index, $comissao->id_cliente)
						->setCellValue('B'.$index, $comissao->razao)
						->setCellValue('C'.$index, $comissao->dt_fatura)
						->setCellValue('D'.$index, $comissao->valor)
						->setCellValue('E'.$index, $comissao->percentual)
						->setCellValue('F'.$index, $comissao->comissao)
						->setCellValue('G'.$index, $comissao->processo);
				$index++;

			}

		}   

		$spreadsheet->setActiveSheetIndex(0);

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="comissao.xlsx"');
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
}
