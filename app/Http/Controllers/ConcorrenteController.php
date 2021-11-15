<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConcorrenteController extends Controller
{

    public function importaArquivo(Request $request) {



		$uploaddir = '/var/www/html/portalgo/storage/uploads/concorrentes.csv';

		$erros = array();

	    if (file_exists($uploaddir)) {

	        $handle = fopen($uploaddir, "r"); 

	        $linha = 1;

	        while (($line = fgetcsv($handle, 100000, ";")) !== FALSE) {

	            if ($linha >= 2) {   

	            	if ($line[4] == ''){
	            		$codgrupo = 0;
	            	} else {
	            		$codgrupo = $line[4];
	            	}
	            	$grupo = addslashes(utf8_encode($line[5]));
	            	$razao = addslashes(utf8_encode($line[7]));
	            	$endereco = addslashes(utf8_encode($line[9]));
	            	$cidade = addslashes(utf8_encode($line[11]));

	            	$qinsert = \DB::select("insert into concorrentes (codcliente, canal, micro, macro, codgrupo, grupo, an8, razao, endereco, categoria, uf, municipio, grife,  qtde17, qtde18) values ('$line[0]', '$line[1]', '$line[2]', '$line[3]', '$codgrupo','$grupo', '$line[6]', '$razao', '$endereco', '$line[9]', '$line[10]', '$cidade', '$line[12]', '$line[13]', '$line[14]') ");


	            }


	            $linha++;

	        }
	    }

    }
}
