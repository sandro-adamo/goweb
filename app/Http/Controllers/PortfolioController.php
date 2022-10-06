<?php

namespace App\Http\Controllers;

use App\Comentario;
use App\Exports\EmbarquesExport;
use App\Imports\EmbarqueImport;
use App\Portfolio;
use App\PortfolioItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


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

    public function markCommentsAsRead($idCompraInvoice, $idLastSeenComment){

        $portfolioItem = PortfolioItem::where('id_compra_invoice', $idCompraInvoice)->first();

        $comments = Comentario::where('id_fornecedor_portfolio_item', $portfolioItem->id)
        ->whereNull('visto_em')
        ->where('id', '<=', $idLastSeenComment)->where('id_usuario', '!=', auth()->user()->id)->get();

        foreach($comments as $comment)
            $comment->update([
                'visto_em' => now(),
            ]);

        return response()->json([], 200);
    }

    public function aprovar($idCompraInvoice){

        $portfolioItem = PortfolioItem::where('id_compra_invoice', $idCompraInvoice)->first();

        $portfolioItem->update([
            'aprovado_em' => now(),
        ]);

        if(!$portfolioItem->portfolio->itens()->whereNull('aprovado_em')->first())
            $portfolioItem->portfolio->update(['aprovado_em' => now()]);

        return redirect()->back();

    }

    public function aprovarTodosItens($invoice){

        $portfolioItens = PortfolioItem::where('importacao', $invoice)->get();

        foreach($portfolioItens as $item){
            $item->update([
                'aprovado_em' => now(),
            ]);
        }

        $portfolioItens->first()->portfolio->update([
            'aprovado_em' => now()
        ]);

        return redirect()->back();

    }

    public function desaprovar($idCompraInvoice){

        $portfolioItem = PortfolioItem::where('id_compra_invoice', $idCompraInvoice)->first();

        $portfolioItem->update([
            'aprovado_em' => null,
        ]);

        return redirect()->back();

    }

    public function embarquesDownload($invoice){

        $portfolioItens = PortfolioItem::where('importacao', $invoice)
        ->whereNotNull('aprovado_em')
        ->get();

        $linhas = [];

        $linhas[] = [
            'Referência',
            'Quantidade',
            'Valor Unitário',
            'Tipo', //Montadas ou desmontadas
            'Qtde. Embarque 1',
            'Qtde. Embarque 2',
            'Qtde. Embarque 3',
            'Qtde. Embarque 4',
            'Qtde. Embarque 5',
        ];

        foreach($portfolioItens as $item){

            $linhas[] = [
                $item->secundario,
                $item->qtde,
                $item->valor,
            ];

        }

        return Excel::download(new EmbarquesExport($linhas), 'invoices.xlsx');

    }

    public function embarquesUpload($invoice, Request $request){

        if(in_array($request->file('arquivo')->extension(), ['txt', 'csv'])){

            $result = Excel::import(new EmbarqueImport($invoice), $request->file('arquivo'), null, \Maatwebsite\Excel\Excel::CSV);

        }elseif($request->file('arquivo')->extension() == 'xlsx'){

            $result = Excel::import(new EmbarqueImport($invoice), $request->file('arquivo'), null, \Maatwebsite\Excel\Excel::XLSX);

        }elseif($request->file('arquivo')->extension() == 'xls'){

            $result = Excel::import(new EmbarqueImport($invoice), $request->file('arquivo'), null, \Maatwebsite\Excel\Excel::XLS);

        }else{

            return redirect()->back()->withErrors(['error' => 'Formato do arquivo inv. Use .xlsx, .xls or .csv.']);

        }

        return redirect()->back()->with('success', 'Planilha de embarque enviada com sucesso!');
        
    }

}
