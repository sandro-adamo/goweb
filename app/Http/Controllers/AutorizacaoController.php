<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class AutorizacaoController extends Controller
{
	public function listaAutorizacoes(Request $request) {


		$autorizacoes = \DB::select("select id_item, secundario, colmod, clasmod, 
											(select a2.valor from autorizacoes a2 where a2.id_item = autorizacoes.id_item order by id desc limit 1) as valor,
											(select a2.id from autorizacoes a2 where a2.id_item = autorizacoes.id_item order by id desc limit 1) as id
										from autorizacoes 
										left join itens on id_item = itens.id
										where status = 0
										group by id_item, secundario, colmod, clasmod");


		return view('sistema.autorizacoes.lista')->with('autorizacoes', $autorizacoes);

	}


	public function autoriza($id) {



		$autorizacoes = \DB::select("select * from autorizacoes where id = $id");

		if ($autorizacoes) {


			if ($autorizacoes[0]->tipo == 'modelo') {

				$modelo = \App\Item::find($autorizacoes[0]->id_item);
				$itens = \App\Item::where('modelo', $modelo->modelo)->get();

				$client = \App\JDE::connect();

				foreach ($itens as $item) {

			        $result = $client->itemUpdate( array( 
			             "codItemCurto"=> $item->id,
			             "filial"=> '    01020000',
			             "codtipoarmazenamento"=> $item->codtipoarmaz,
			             "preco" => $autorizacoes[0]->valor
			        ));


			        //$item->codclasmod = $valor;
	        		//\App\ItemHistorico::gravaHistorico($item->id, 'valor', 'Valor alterado de '.$item->valortabela.' para '.$autorizacoes[0]->valor.'.');

			        $item->valortabela = $autorizacoes[0]->valor;
			        $item->save();

				}

				$id_usuario = \Auth::id();

				$autorizacoes = \DB::select("update autorizacoes set status = 1, id_autorizador = $id_usuario, dt_autorizacao = NOW() where id_item = '$modelo->id' ");



			} else {




			}


		}

		return redirect()->back();







	}


}
