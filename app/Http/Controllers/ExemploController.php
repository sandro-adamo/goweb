<?php

namespace App\Http\Controllers;

use App\Exemplo;
use App\PedidoJDE;
use Illuminate\Http\Request;

class ExemploController extends Controller
{
    public function index(){

        $pedidos = PedidoJDE::orderBy('id', 'desc')->paginate(15);
		
        return view('exemplo.index', compact('pedidos'));

    }

    public function store(Request $request){
        
      // $exemplo = Exemplo::create($request->all());
		
		$exemplo = new Exemplo();
		
		$exemplo->campo1 = $request->campo1;
		$exemplo->save();
		
		
		dd($exemplo);
		
		$exemplo = Exemplo::find($id);
		$exemplo->campo1 = $request->campo1;
		$exemplo->save();
		
        return redirect()->route('exemplo.index');

    }
}
