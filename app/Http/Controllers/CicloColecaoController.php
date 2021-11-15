<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CicloColecao;

class CicloColecaoController extends Controller
{
    

	public function alteraCiclo(Request $request) {


		$ciclo = CicloColecao::where('modelo', $request->modelo)->where('colecao', $request->colecao)->first();

		// // valida modelo
		// $modelo = \App\Item::where('modelo', $request->modelo)->count();
		// if ($modelo == 0) {
		// 	return response()->json(array("status"=> "ERROR", "msg"=> "modelo nao existe"));			
		// }

		// // valida colecao
		// $colecao = \App\Item::where('colmod', $request->colecao)->count();
		// if ($colecao == 0) {
		// 	return response()->json(array("status"=> "ERROR", "msg"=> "colecao nao existe"));			
		// }


		if ($ciclo) {

			$ciclo->delete();
			return response()->json(array("status"=> "OK", "msg"=> "registro excluido"));

		} else {

			$ciclo = new CicloColecao();
			$ciclo->modelo = $request->modelo;
			$ciclo->colecao = $request->colecao;
			$ciclo->save();
			return response()->json(array("status"=> "OK", "msg"=> "registro inserido"));

		}

		return '1';

	}


}
