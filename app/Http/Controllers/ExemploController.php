<?php

namespace App\Http\Controllers;

use App\Exemplo;
use App\PedidoJDE;
use Illuminate\Http\Request;

class ExemploController extends Controller
{
    public function index(){

<<<<<<< HEAD
        // $pedidos = PedidoJDE::orderBy('id', 'desc')->paginate(15);
		// $pedidos = \DB::select(" select * from pedidos_jde where pedido = '1074320'");
		
=======
        $pedidos = PedidoJDE::with('exemplo')->orderBy('id', 'desc')->paginate(15);

>>>>>>> origin/master
        return view('exemplo.index', compact('pedidos'));

    }

    public function store(Request $request){

        $exemplo = Exemplo::where('id_pedido', $request->id_pedido)->where('linha', $request->linha)->first();

        if($exemplo){

            $exemplo->update($request->only(['campo1', 'campo2', 'campo3', 'campo4']));
            
        }else{

            $exemplo = Exemplo::create($request->all());

        }

        return redirect()->route('exemplo.index');

    }
    
}
