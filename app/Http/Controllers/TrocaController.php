<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Troca;



class TrocaController extends Controller
{



	public function listaTrocas(Request $request) {

		$sql = '';
		if (isset($request->status)) {
			$sql .= " and id_status_caso not in ('999', '997')";
		}
		if (isset($request->id_cliente)) {
			$sql .= " and id_cliente = '$request->id_cliente'";
		}

		if (isset($request->titulo)) {
			$sql .= ' and titulo = '.$request->titulo;
		}

		if (isset($request->busca)) {
			$sql .= " and (razao like '$request->busca%' or addressbook.id = '$request->busca' )";
		}
		$id_representante = \Auth::user()->id_addressbook;
		//$titulos = Titulo::where('tipo','RI')->take(10)->get();


		if ($sql <> '') {
			$trocas = \DB::select("select trocas.*, razao
				from trocas 
				left join addressbook ab on ab.id = trocas.id_cliente
				where trocas.id is not null $sql
				order by data_troca desc");
		} else {
			$trocas = array();
		}

		return view('trocas.lista')->with('trocas', $trocas);

	}


}
