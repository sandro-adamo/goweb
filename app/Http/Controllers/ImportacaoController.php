<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Importacao;



class ImportacaoController extends Controller
{


	public function detalhesInvoice($invoice) {

		$total = \DB::select("
select format(sum(valor),3) as valor, format(sum(qtd),2) as qtd, invoice 
from(
			select coluna9 as invoice, sum(coluna4) as qtd, sum(coluna4)*coluna7 as valor,  coluna8 as moeda, date(coluna11) dt_invoice,coluna7 , fabrica
				from xpto
				where coluna9 = '$invoice'
				group by coluna9 , date(coluna11) , coluna8, coluna7, fabrica
				order by  date(coluna11) desc) as base
				group by invoice");

		$invoice = \DB::select("select coluna9 as invoice, sum(coluna4) as qtd, sum(coluna4)*coluna7 as valor, concat(coluna2,' ',coluna3) item, coluna8 as moeda, date(coluna11) dt_invoice,coluna7 as unitario , fabrica
				from xpto
				where coluna9 = '$invoice'
				group by coluna9 , date(coluna11) , coluna8, coluna2,coluna3,coluna7, fabrica
				order by  date(coluna11) desc");

		

		


		return view('importacoes.detalhes')->with('invoice', $invoice)->with('total', $total);
    	

    }

    public function listaImportacoes() {

		$lista = \DB::select("select invoice, sum(qtd) as qtd, format(sum(valor),2) as valor, dt_invoice, moeda, fabrica
					from(
				select coluna9 as invoice, sum(coluna4) as qtd, sum(coluna4)*coluna7 as valor, concat(coluna2,' ',coluna3) item, coluna8 as moeda, date(coluna11) dt_invoice,coluna7 , fabrica
				from xpto
				group by coluna9 , date(coluna11) , coluna8, coluna2,coluna3,coluna7, fabrica
				order by  date(coluna11) desc) as base
				group by invoice, dt_invoice, moeda, fabrica
				order by dt_invoice desc");


		return view('importacoes.lista')->with('lista', $lista);
    	

    }


    public function deletaInvoice($invoice,Request $request) {
    	

    	$deleta = \DB::select("DELETE FROM `xpto` WHERE coluna9 = '$invoice' ");

    	$request->session()->flash('alert-success', 'Invoice '.$invoice.' deletada.');

    	return redirect()->back();
    }


    public function importaArquivo(Request $request) {

		$cod_usuario = \Auth::id();

		$uploaddir = '/var/www/html/portalgo/storage/uploads/';
		$uploadfile = $uploaddir . basename($_FILES['arquivo']['name']);

		$id_update = date('YmdHis'); 
		$erros = array();

		if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $uploadfile)) {

		    if (file_exists($uploadfile)) {

		        $handle = fopen($uploadfile, "r"); 

		        $linha = 1;

		        while (($line = fgetcsv($handle, 100000, ";")) !== FALSE) {

		            if ($linha >= 2) {   

 						$coluna7 = str_replace(",", ".", $line[6]);


 						// $verifica = \DB::select("select * from xpto where coluna1 = '$line[0]' and coluna2 = '$line[1]' and coluna3 = '$line[2]' and coluna4 = '$line[3]' and coluna7 = $coluna7 and coluna9 = '$line[8]' ");


 						// if (!$verifica) {

				            $insere = new Importacao();
				            $insere->coluna1 = $line[0];
				            $insere->coluna2 = $line[1];
				            $insere->coluna3 = $line[2];
				            $insere->coluna4 = $line[3];
				            $insere->coluna5 = $line[4];
				            $insere->coluna6 = $line[5];
				            $insere->coluna7 = $coluna7;
				            $insere->coluna8 = $line[7];
				            $insere->coluna9 = $line[8];
				            $insere->coluna10 = $line[9];
				            $insere->fabrica = $line[10];
				            $insere->id_update = $id_update;
				            $insere->save();

				            


				        // } else {

				        // 	$erros[] = 'Linha '.$linha.' ja foi importada. <br>';

				         
				        // }



		            }


		            $linha++;

		        }
		     //    if(count($erros)>0){
		     //    print_r($erros);
		       
		    	// }
		    	// else{

		    	$request->session()->flash('alert-success', 'Arquivo importado.');
		    	return redirect()->back();
		    	// }


		    }

		}
		
		


    }

}
