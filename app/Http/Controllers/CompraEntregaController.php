<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompraEntregaController extends Controller
{


	public function listaDistribuicaoItem(Request $request) {


		if (isset($request->id_pedido_item)) {

			
		\App\CompraDistribuicao::where('id_pedido_item', $request->id_pedido_item)->get();

			return response()->json($distribuicao);
			

		}


	}

}
