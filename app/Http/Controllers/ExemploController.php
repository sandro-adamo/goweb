<?php

namespace App\Http\Controllers;

use App\Exemplo;
use App\PedidoJDE;
use Illuminate\Http\Request;

class ExemploController extends Controller
{
    public function index(){

        // $pedidos = PedidoJDE::orderBy('id', 'desc')->paginate(15);
		$pedidos = \DB::select(" select * from pedidos_jde where pedido = '1074320'");
		
        return view('exemplo.index', compact('pedidos'));

    }

    public function store(Request $request){
        
      // $exemplo = Exemplo::create($request->all());
		
		$exemplo = new Exemplo();
		$exemplo->id_pedido = $request->id_pedido;
		$exemplo->campo1 = $request->campo1;
		$exemplo->campo2 = $request->campo2;
		$exemplo->campo3 = $request->campo3;
		$exemplo->campo4 = $request->campo4;
		$exemplo->save();
		
		
		// dd($exemplo);
		
		// $exemplo = Exemplo::find($id);
		// $exemplo->campo1 = $request->campo1;
		// $exemplo->save();
		
         // return redirect()->route('exemplo.index');
		// return redirect()->route('dashboards.importacao.dashboard_importacao');
		return redirect()->back();
    }
}
