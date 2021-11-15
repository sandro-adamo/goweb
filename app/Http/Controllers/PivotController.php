<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;

class PivotController extends Controller
{
    

	public function listaItens() {

		ini_set('memory_limit', -1);
		$itens = \DB::select("select * from itens limit 100");

		return response()->json($itens);
	}
    

	public function carrega(Request $request) {

		ini_set('memory_limit', -1);

		$dados = array();

		if ($request->select <> '') {
	
			$dados = \DB::select($request->select);

		}



		if ($request->tabela <> '') {
	
			$dados = \DB::select("select * from $request->tabela");

		}

		return response()->json($dados);
	}



	public function listaItens_10() {

		ini_set('memory_limit', -1);
		$itens = \DB::connection('goweb')->select("select * from itens limit 100");

		return response()->json($itens);
	}
    

	public function carrega_10(Request $request) {

		ini_set('memory_limit', -1);

		$dados = array();

		if ($request->select <> '') {
	
			$dados = \DB::connection('goweb')->select($request->select);

		}



		if ($request->tabela <> '') {
	
			$dados = \DB::connection('goweb')->select("select * from $request->tabela");

		}

		return response()->json($dados);
	}



	public function listaItens_site() {

		ini_set('memory_limit', -1);
		$itens = \DB::connection('site')->select("select * from itens limit 100");

		return response()->json($itens);
	}
    

	public function carrega_site(Request $request) {

		ini_set('memory_limit', -1);

		$dados = array();

		if ($request->select <> '') {
	
			$dados = \DB::connection('site')->select($request->select);

		}



		if ($request->tabela <> '') {
	
			$dados = \DB::connection('site')->select("select * from $request->tabela");

		}

		return response()->json($dados);
	}

}
