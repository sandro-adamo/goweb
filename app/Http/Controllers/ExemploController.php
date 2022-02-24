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
        
        $exemplo = Exemplo::create($request->all());

        return redirect()->route('exemplo.index');

    }
}
