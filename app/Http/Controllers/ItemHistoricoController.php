<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemHistoricoController extends Controller
{
   

	public function deletaHistorico($id) {


		$historico = \App\ItemHistorico::find($id)->delete();

		return redirect()->back();

	}




}
