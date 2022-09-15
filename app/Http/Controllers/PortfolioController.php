<?php

namespace App\Http\Controllers;

use App\Comentario;
use App\Portfolio;
use App\PortfolioItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PortfolioController extends Controller
{

    public function updateComments(Request $request, $id){

        $validator = Validator::make($request->only('comments'), [
            'comments' => 'required|min:2|max:300', 
        ]);

        $validator->validate();

        $item = PortfolioItem::where('id_compra_invoice', $id)->first();

        $row = Comentario::create([
            'id_usuario' => auth()->user()->id,
            'id_addressbook' => 1,
            'id_fornecedor_portfolio' => $item->id_portfolio,
            'id_fornecedor_portfolio_item' => $item->id,
            'comentario' => $request->comments,
        ]);

        return redirect()->back()->with('status', 'Comments added successfully!');

    }

}
