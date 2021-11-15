<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Catalogo;


class ComercialController extends Controller
{

	public function buscaClientes(Request $request) {

		$clientes = \DB::select("select * from vendas_cml where cliente like '%$request->q%' limit 100");

		return view('comercial.carteira.carteira');

	}


}
