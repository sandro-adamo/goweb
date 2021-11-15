<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\PedidoItem;

class PedidoController extends Controller
{
    
	public function listaPedidos_det(Request $request) {

		return view('pedidos.lista_det');

	}

	public function vinculaPedido($data, Request $request) {

		$query = \DB::select("update pedidos_itens set pedido = '$request->pedido_jde' where date(created_at) = '$data' ");

		return redirect('/pedidos');

	}
	
	
	public function listaPedidos(Request $request) {

		$id_rep = \Auth::user()->id_addressbook;

		$pedidos = \DB::select("select pedido, dt_venda, id_cliente, razao, sum(valor) as valor, sum(qtde) as qtde
				from vendas 
				left join addressbook on id_cliente = addressbook.id
				where month(dt_venda) = month(now()) and year(dt_venda) = year(now()) and id_rep = $id_rep
				group by pedido, dt_venda, id_cliente, razao
				order by dt_venda desc ");


		return view('pedidos.lista')->with('pedidos', $pedidos);

	}


	public function detalhesPedido($id) {

		$id_rep = \Auth::user()->id_addressbook;

		$itens = \DB::select("select *
				from vendas 
				left join addressbook on id_cliente = addressbook.id
				where pedido = '$id' and id_rep = $id_rep
				order by linha  ");

		return view('pedidos.detalhes')->with('itens', $itens);

	}

	public function importarPedido(Request $request) {


		$uploaddir = '/var/www/html/portalgo/storage/uploads/';
		$uploadfile = $uploaddir . basename($_FILES['arquivo']['name']);
		$erros = array();


		if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $uploadfile)) {

		    if (file_exists($uploadfile)) {

		        $handle = fopen($uploadfile, "r"); 

		        $linha = 1;

		        while (($line = fgetcsv($handle, 100000, ";")) !== FALSE) {

		            if ($linha >= 2) {   

		            	$pedido = $line[0];
		            	$ean = $line[1];
		            	$item = Item::where('ean', $ean)->first();

		            	if ($item) {

		            		//$checa_registro = PedidoItem::where('id_pedido', $pedido)->where('id_item', $item->id)->get();

		            		//if ($checa_registro) {

		            			//$erros[] = 'Pedido nº '.$pedido.' já existe';

		            		//} else {

				            	$pedido_item = new PedidoItem();
				            	$pedido_item->id_pedido = $pedido;
				            	$pedido_item->nome = $line[4];
				            	$pedido_item->email = $line[3];
				            	$pedido_item->id_item = $item->id;
				            	$pedido_item->item = $item->secundario;
				            	$pedido_item->qtde = $line[2];
				            	$pedido_item->unitario = $item->valortabela;
				            	$pedido_item->total = $line[2] * $item->valortabela;
				            	$pedido_item->created_by = 1;
				            	$pedido_item->save();

				            //}
 
			            } else {

			            	$erros[] = 'Produto '.$ean.' não existe';

			            }



		            }

		            $linha++;


		        }


		    }

		}


		if (count($erros) > 0) {

			$error = '<ul>';
		    foreach ($erros as $erro) {
		      $error .= '<li>'.$erro.'</li>';
		    }
			$error .= '</ul>';

		    $request->session()->flash('alert-danger', $error );

		}
		return redirect('/pedidos/');

	}


}
