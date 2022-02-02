<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Caracteristica;

class CaracteristicaController extends Controller
{



	public function getCaracteristicas($caracteristica) {

		$caracteristica = Caracteristica::where('campo', $caracteristica)->get();

		return response()->json($caracteristica);

	}


	public function alteraCaracteristica(Request $request) {

	
		if  (\Auth::user()->id_perfil <> 1 and \Auth::user()->id_perfil <> 2 and \Auth::user()->id_perfil <> 25) {
			die('sem permissao');
		}

		$client = \App\JDE::connect();

		$dados = $request->all();
		$tipo = $request->tipo;
		
		//dd($request);

		if ($tipo == 'modelo') {
			$painel_modelo = \DB::select("select * from painel_modelo where id_item = '$request->id_item' ");
		$valor_codigo = \DB::select("select ltrim(rtrim(valor)) as valor from caracteristicas where ltrim(rtrim(campo)) = '$request->caracteristica' and ltrim(rtrim(codigo)) = '$request->valor' ");

//dd($painel_modelo);

		
		$item = \App\Item::find($painel_modelo[0]->id_item);


			$itens = \App\Item::where('modelo', $item->modelo)->get();
			
		

			foreach ($itens as $item) {
				

				$caracteristica1 = \DB::select("select* from servico_jde left join caracteristicas on servico_jde.itens_descricao = campo where itens_descricao = '$request->caracteristica' and codigo = '$request->valor'");
				

				switch ($request->caracteristica) {
					case 'colmod':

						if (isset($request->alteraColItem) && $request->alteraColItem == 1) {
							$this->alteraColitem($client, $item->id, $request->valor);
						}
						$this->alteraColmod($client, $item->id, $request->valor);
						break;
					case 'clasmod':
							
						$this->alteraClasmod($client, $item->id, $request->valor);
				
						break;
					case 'tipoitem':

						$this->alteraTipoItem($client, $item->id, $request->valor);
						break;
					case 'grife':
					
						$this->alteraGrife($client, $item->id, $request->valor);
						break;
					case 'agrupamento':

						$this->alteraAgrupamento($client, $item->id, $request->valor);
						break;
					case 'linha':
					
						$this->alteraLinha($client, $item->id, $request->valor);
						break;
					case 'fornecedor':
					
						$this->alteraFornecedor($client, $item->id, $request->valor);
						break;
					case 'genero':
					
						$this->alteraGenero($client, $item->id, $request->valor);
						break;
					case 'idade':
					
						$this->alteraIdade($client, $item->id, $request->valor);
						break;
					case 'material':
					
						$this->alteraMaterial($client, $item->id, $request->valor);
						break;
					case 'fixacao':
					
						$this->alteraFixacao($client, $item->id, $request->valor);
						break;
					case 'estilo':
					
						$this->alteraEstilo($client, $item->id, $request->valor);
						break;
					case 'tecnologia':
					
						$this->alteraTecnologia($client, $item->id, $request->valor);
						break;
					case 'tipoarmazenamento':
					
						$this->alteraTipoArmazenamento($client, $item->id, $request->valor);
						break;					
					case 'tamolho':
					
						$this->alteraTamanhoOlho($client, $item->id, $request->valor);
						break;
					case 'tamhaste':
					
						$this->alteraTamanhoHaste($client, $item->id, $request->valor);
						break;
					case 'tamponte':
				
						$this->alteraTamanhoPonte($client, $item->id, $request->valor);
						break;
					
					default:
						# code...
						break;
				}
				switch ($request->caracteristica) {
				case 'clasmod':
							
						$caract = 'clasmod';
						break;
				case 'agrupamento':
							
						$caract = 'agrup';
						break;
				case 'tamanhoolho':
							
						$caract = 'tamolho';
						break;
				case 'tamanhoponte':
							
						$caract = 'tamponte';
						break;
				case 'tamanhohaste':
							
						$caract = 'tamhaste';
						break;
				case 'tipoarmazenamento':
							
						$caract = 'tipoarmaz';
						break;
				
					default:
						$caract = $request->caracteristica;
						break;
				}
				
				$valor = $valor_codigo[0]->valor;
				
				$itens_descricao1 = 	$caracteristica1[0]->itens_descricao ;
				$valor1 = 	$caracteristica1[0]->valor;
				$itens1 = 	$caracteristica1[0]->itens;
				$codigo1 =	$caracteristica1[0]->codigo;
				
					
				$itens = \DB::select("update itens set $itens_descricao1 = '$valor1',$itens1 = '$codigo1'  where modelo = '$item->modelo'  ");
				
				// ajustar depois $atualiza_painel_modelo = \DB::select("update painel_modelo set $itens_descricao1 = '$valor1',$itens1 = '$codigo1'  where modelo = '$item->modelo'  ");
				$atualiza_painel_modelo = \DB::select("update painel_modelo set $itens_descricao1 = '$valor1' where modelo = '$item->modelo'  ");
				
						
				// $atualiza = \DB::select("update itens set $caract = '$valor' where modelo = '$item->modelo'");
				// $atualiza_painel = \DB::select("update painel_modelo set $caract = '$valor' where modelo = '$item->modelo'");
				
			

			}

		} else {
			$iditem = $request->id_item;
			//dd($iditem);

			switch ($request->caracteristica) {
				case 'clasitem':
					$this->alteraClasitem($client, $iditem, $request->valor);
					break;
				
				case 'colitem':
					$this->alteraColitem($client, $iditem, $request->valor);
					break;
				
				case 'status':
					$this->alteraStatusAtual($client, $iditem, $request->valor);
					break;
			
				case 'tipoarmazenamento':
					$this->alteraTipoArmazenamento($client, $iditem, $request->valor);
					break;
				default:
					# code...
					break;
			}



		}

		return redirect()->back();

	}




	public function alteraColmod($client, $id, $valor) {


		$item = \App\Item::find($id);

        $colmod = Caracteristica::where('campo', 'colmod')->where('codigo', $valor)->first();

        if ($colmod) {


	        $result = $client->itemUpdate( array( 
	             "codItemCurto"=> $item->id,
				 "filial" => '    01020000',
	             "colmod" => $valor
	        ));

			$alterado_de = $item->colmod;

	        //$item->codclasmod = $valor;
	        $item->colmod = $colmod->valor;
			
	        $item->save();

	        \App\ItemHistorico::gravaHistorico($item->id, 'caracteristica', 'Caracteristica <b>Colmod</b> alterada de '.$alterado_de.' para '.$colmod->valor.'.');

	    }

	}




	public function alteraColitem($client, $id, $valor) {


		$item = \App\Item::find($id);

        $colitem = Caracteristica::where('campo', 'colitem')->where('codigo', $valor)->first();

        if ($colitem) {

	        $result = $client->itemUpdate( array( 
	             "codItemCurto"=> $item->id,
				"filial" => '    01020000',
	             "colitem" => $valor
	        ));

			$alterado_de = $item->colitem;

	        //$item->codclasmod = $valor;
	        $item->colitem = $colitem->valor;
	        $item->save();

	        \App\ItemHistorico::gravaHistorico($item->id, 'caracteristica', 'Caracteristica <b>Colitem</b> alterada de '.$alterado_de.' para '.$colitem->valor.'.');

	    }

	}


	public function alteraTipoArmazenamento($client, $iditem, $valor) {
//dd('oi');

		$item = \DB::select("select* from itens where id = '$iditem'");
		//dd($item);

        $tipoaramaz = Caracteristica::where('campo', 'tipoarmazenamento')->where('codigo', $valor)->first();

//dd($tipoaramaz);
        if ($tipoaramaz) {

	        $result = $client->itemUpdate( array( 
	             "codItemCurto"=> $iditem,
	             "codtipoarmazenamento" => $valor,
	             "filial" => '    01020000',
	        ));


			$alterado_de = $item[0]->tipoarmaz;

	        //$item->codclasmod = $valor;
	        // $item[0]->tipoarmaz = $tipoaramaz->codigo;
	        // $item->save();

	        \App\ItemHistorico::gravaHistorico($item[0]->id, 'caracteristica', 'Caracteristica <b>Tipo Armazenamento</b> alterada de '.$alterado_de.' para '.$tipoaramaz->valor.'.');

	    }

	}

	public function alteraClasmod($client, $id, $valor) {

		$item = \App\Item::find($id);

        $clasmod = Caracteristica::where('campo', 'clasmod')->where('codigo', $valor)->first();
//dd($clasmod);
        if ($clasmod) {

	        $result = $client->itemUpdate( array( 
	             "codItemCurto"=> $item->id,
	             "codclassmod" => $valor,
	             "codtipoarmazenamento" => $item->codtipoarmaz,
	             "filial" => '    01020000'

	        ));

	        //dd($result);
			$alterado_de = $item->codclasmod .' '.$item->clasmod;

	        $item->codclasmod = $valor;
	        $item->clasmod = $clasmod->valor;
	        $item->save();

	        \App\ItemHistorico::gravaHistorico($item->id, 'caracteristica', 'Caracteristica <b>Clasmod</b> alterada de '.$alterado_de.' para '.$clasmod->valor.'.');

	    }

	}


	public function alteraClasitem($client, $id, $valor) {


		$item = \App\Item::find($id);

        $clasitem = Caracteristica::where('campo', 'clasitem')->where('codigo', $valor)->first();

        if ($clasitem) {

	        $result = $client->itemUpdate( array( 
	             "codItemCurto"=> $item->id,
				"filial" => '    01020000',
	             "codclassitem" => $valor
	        ));

			$alterado_de = $item->codclasitem .' '.$item->clasitem;

	        $item->codclasitem = $valor;
	        $item->clasitem = $clasitem->valor;
	        $item->save();

	        \App\ItemHistorico::gravaHistorico($item->id, 'caracteristica', 'Caracteristica <b>Clasitem</b> alterada de '.$alterado_de.' para '.$clasitem->valor.'.');

	    }

	}


	public function alteraTipoItem($client, $id, $valor) {


		$item = \App\Item::find($id);

        $tipoitem = Caracteristica::where('campo', 'tipoitem')->where('codigo', $valor)->first();

        if ($tipoitem) {

	        $result = $client->itemUpdate( array( 
	             "codItemCurto"=> $item->id,
				"filial" => '    01020000',
	             "codtipoitem" => $valor
	        ));

			$alterado_de = $item->codtipoitem .' '.$item->tipoitem;

	        $item->codtipoitem = $valor;
	        $item->tipoitem = $tipoitem->valor;
	        $item->save();

	        \App\ItemHistorico::gravaHistorico($item->id, 'caracteristica', 'Caracteristica <b>Tipo Item</b> alterada de '.$alterado_de.' para '.$tipoitem->valor.'.');

	    }

	}




	public function alteraGrife($client, $id, $valor) {


		$item = \App\Item::find($id);

        $grife = Caracteristica::where('campo', 'grife')->where('codigo', $valor)->first();

        if ($grife) {

	        $result = $client->itemUpdate( array( 
	             "codItemCurto"=> $item->id,
				"filial" => '    01020000',
	             "codgrife" => $valor
	        ));


			$alterado_de = $item->codgrife .' '.$item->grife;

	        $item->codgrife = $valor;
	        $item->grife = $grife->valor;
	        $item->save();

	        \App\ItemHistorico::gravaHistorico($item->id, 'caracteristica', 'Caracteristica <b>Tipo Item</b> alterada de '.$alterado_de.' para '.$grife->valor.'.');

	    }

	}




	public function alteraAgrupamento($client, $id, $valor) {


		$item = \App\Item::find($id);

        
        $agrupamento = \DB::select("select ltrim(rtrim(valor)) as descricao from caracteristicas where ltrim(rtrim(campo)) = 'agrupamento' and ltrim(rtrim(codigo)) = '$valor' ");

        if ($agrupamento) {

	        $result = $client->itemUpdate( array( 
	             "codItemCurto"=> $item->id,
				"filial" => '    01020000',
	             "codagrup" => $valor
	        ));

			$alterado_de = $item->codagrup .' '.$item->agrup;

	        $item->codagrup = $valor;
	        $item->agrup = $agrupamento[0]->descricao;
	        $item->save();

	        \App\ItemHistorico::gravaHistorico($item->id, 'caracteristica', 'Caracteristica <b>Agrupamento</b> alterada de '.$alterado_de.' para '.$agrupamento[0]->descricao.'.');

	    }

	}



	public function alteraLinha($client, $id, $valor) {


		$item = \App\Item::find($id);

        $linha = Caracteristica::where('campo', 'linha')->where('codigo', $valor)->first();

        if ($linha) {

	        $result = $client->itemUpdate( array( 
	             "codItemCurto"=> $item->id,
				"filial" => '    01020000',
	             "codlinha" => $valor
	        ));

			$alterado_de = $item->codlinha .' '.$item->linha;

	        $item->codlinha = $valor;
	        $item->linha = $linha->valor;
	        $item->save();

	        \App\ItemHistorico::gravaHistorico($item->id, 'caracteristica', 'Caracteristica <b>Agrupamento</b> alterada de '.$alterado_de.' para '.$linha->valor.'.');

	    }

	}



	public function alteraGenero($client, $id, $valor) {


		$item = \App\Item::find($id);

        $genero = Caracteristica::where('campo', 'genero')->where('codigo', $valor)->first();

        if ($genero) {

	        $result = $client->itemUpdate( array( 
	             "codItemCurto"=> $item->id,
				"filial" => '    01020000',
	             "cod_genero" => $valor
	        ));

			$alterado_de = $item->codgenero .' '.$item->genero;

	        $item->codgenero = $valor;
	        $item->genero = $genero->valor;
	        $item->save();

	        \App\ItemHistorico::gravaHistorico($item->id, 'caracteristica', 'Caracteristica <b>Genero</b> alterada de '.$alterado_de.' para '.$genero->valor.'.');

	    }

	}




	public function alteraIdade($client, $id, $valor) {


		$item = \App\Item::find($id);

        $idade = Caracteristica::where('campo', 'idade')->where('codigo', $valor)->first();

        if ($idade) {

	        $result = $client->itemUpdate( array( 
	             "codItemCurto"=> $item->id,
				"filial" => '    01020000',
	             "cod_idade" => $valor
	        ));

			$alterado_de = $item->codidade .' '.$item->idade;

	        $item->codidade = $valor;
	        $item->idade = $idade->valor;
	        $item->save();

	        \App\ItemHistorico::gravaHistorico($item->id, 'caracteristica', 'Caracteristica <b>Genero</b> alterada de '.$alterado_de.' para '.$idade->valor.'.');

	    }

	}



	public function alteraMaterial($client, $id, $valor) {


		$item = \App\Item::find($id);

        $material = Caracteristica::where('campo', 'material')->where('codigo', $valor)->first();

        if ($material) {

	        $result = $client->itemUpdate( array( 
	             "codItemCurto"=> $item->id,
				"filial" => '    01020000',
	             "material" => $valor
	        ));

			$alterado_de = $item->codmaterial .' '.$item->material;

	        $item->codmaterial = $valor;
	        $item->material = $material->valor;
	        $item->save();

	        \App\ItemHistorico::gravaHistorico($item->id, 'caracteristica', 'Caracteristica <b>Genero</b> alterada de '.$alterado_de.' para '.$material->valor.'.');

	    }

	}




	public function alteraFixacao($client, $id, $valor) {


		$item = \App\Item::find($id);

        $fixacao = Caracteristica::where('campo', 'fixacao')->where('codigo', $valor)->first();

        if ($fixacao) {

	        $result = $client->itemUpdate( array( 
	             "codItemCurto"=> $item->id,
				"filial" => '    01020000',
	             "fixacao" => $valor
	        ));

			$alterado_de = $item->codfixacao .' '.$item->fixacao;

	        $item->codfixacao = $valor;
	        $item->fixacao = $fixacao->valor;
	        $item->save();

	        \App\ItemHistorico::gravaHistorico($item->id, 'caracteristica', 'Caracteristica <b>Genero</b> alterada de '.$alterado_de.' para '.$fixacao->valor.'.');

	    }

	}	





	public function alteraEstilo($client, $id, $valor) {


		$item = \App\Item::find($id);

        $estilo = Caracteristica::where('campo', 'estilo')->where('codigo', $valor)->first();

        if ($estilo) {

	        $result = $client->itemUpdate( array( 
	             "codItemCurto"=> $item->id,
				"filial" => '    01020000',
	             "codestilo" => $valor
	        ));

			$alterado_de = $item->codestilo .' '.$item->estilo;

	        $item->codestilo = $valor;
	        $item->estilo = $estilo->valor;
	        $item->save();

	        \App\ItemHistorico::gravaHistorico($item->id, 'caracteristica', 'Caracteristica <b>Genero</b> alterada de '.$alterado_de.' para '.$estilo->valor.'.');

	    }

	}	



	public function alteraStatusAtual($client, $id, $valor) {


		$item = \App\Item::find($id);

        $status = Caracteristica::where('campo', 'status')->where('codigo', $valor)->first();

        if ($status) {

	        $result = $client->itemUpdate( array( 
	             "codItemCurto"=> $item->id,
				"filial" => '    01020000',
	             "codstatusatutal" => $valor
	        ));


			$alterado_de = $item->codstatusatual .' '.$item->statusatual;

	        $item->codstatusatual = $valor;
	        $item->statusatual = $status->valor;
	        $item->save();

	        \App\ItemHistorico::gravaHistorico($item->id, 'caracteristica', 'Caracteristica <b>Status</b> alterada de '.$alterado_de.' para '.$status->valor.'.');

	    }

	}



	public function alteraTamanhoOlho($client, $id, $valor) {


		$item = \App\Item::find($id);

        $tamanhoolho = Caracteristica::where('campo', 'tamolho')->where('codigo', $valor)->first();

        if ($tamanhoolho) {

	        $result = $client->itemUpdate( array( 
	             "codItemCurto"=> $item->id,
				"filial" => '    01020000',
	             "tam_aro" => $valor
	        ));


			$alterado_de = $item->tamolho;

	        $item->tamolho = $valor;
	        $item->save();

	        \App\ItemHistorico::gravaHistorico($item->id, 'caracteristica', 'Caracteristica <b>Tamanho Olho</b> alterada de '.$alterado_de.' para '.$tamanhoolho->valor.'.');

	    }

	}



	public function alteraTamanhoHaste($client, $id, $valor) {


		$item = \App\Item::find($id);

        $tamanhohaste = Caracteristica::where('campo', 'tamhaste')->where('codigo', $valor)->first();

        if ($tamanhohaste) {

	        $result = $client->itemUpdate( array( 
	             "codItemCurto"=> $item->id,
				"filial" => '    01020000',
	             "tam_haste" => $valor
	        ));


			$alterado_de = $item->tamhaste;

	        $item->tamhaste = $valor;
	        $item->save();

	        \App\ItemHistorico::gravaHistorico($item->id, 'caracteristica', 'Caracteristica <b>Tamanho Haste</b> alterada de '.$alterado_de.' para '.$tamanhohaste->valor.'.');

	    }

	}


	public function alteraTamanhoPonte($client, $id, $valor) {


		$item = \App\Item::find($id);

        $tamanhoponte = Caracteristica::where('campo', 'tamponte')->where('codigo', $valor)->first();

        if ($tamanhoponte) {

	        $result = $client->itemUpdate( array( 
	             "codItemCurto"=> $item->id,
				"filial" => '    01020000',
	             "tam_ponte" => $valor
	        ));


			$alterado_de = $item->tamponte;

	        $item->tamponte = $valor;
	        $item->save();

	        \App\ItemHistorico::gravaHistorico($item->id, 'caracteristica', 'Caracteristica <b>Status</b> alterada de '.$alterado_de.' para '.$tamanhoponte->valor.'.');

	    }

	}

	public function alteraFornecedor($client, $id, $valor) {


		$item = \App\Item::find($id);

        $fornecedor = Caracteristica::where('campo', 'fornecedor')->where('codigo', $valor)->first();

        if ($fornecedor) {

	        $result = $client->itemUpdate( array( 
	             "codItemCurto"=> $item->id,
				"filial" => '    01020000',
	             "codFornecedor" => $valor
	        ));


			$alterado_de = $item->fornecedor;

	        $item->fornecedor = $valor;
	        $item->save();

	        \App\ItemHistorico::gravaHistorico($item->id, 'caracteristica', 'Caracteristica <b>Status</b> alterada de '.$alterado_de.' para '.$fornecedor->valor.'.');

	    }

	}

/*

		foreach ($itens as $item) {

			if ($request->caracteristica == 'grife') {

		        $result = $client->itemUpdate( array( 
		             "codItemCurto"=> $item->id,
		             "codgrife" => $request->valor
		        ));

		        $grife = Caracteristica::where('campo', 'Grife')->where('codigo', $request->valor)->first();
		        $item->codgrife = $request->valor;
		        $item->grife = $grife->valor;
		        $item->save();

		        \App\ItemHistorico::gravaHistorico($item->id, 'caracteristica', 'Alterado');

			}

			if ($request->caracteristica == 'classmod') {

				$itens_modelo = \App\Item::find($item->id);
		        $result = $client->itemUpdate( array( 
		             "codItemCurto"=> $item->id,
		             "codclassmod" => $request->valor
		        ));


//echo "REQUEST:\n" . htmlentities($client->__getLastRequest()) . "\n";
//die();
		        $clasmod = Caracteristica::where('campo', 'classmod')->where('codigo', $request->valor)->first();
		        $item->codclasmod = $request->valor;
		        $item->clasmod = $clasmod->valor;
		        $item->save();

		        \App\ItemHistorico::gravaHistorico($item->id, 'caracteristica', 'Alterado');

			}		

			if ($request->caracteristica == 'tipoitem') {

		        $result = $client->itemUpdate( array( 
		             "codItemCurto"=> $item->id,
		             "codtipoitem" => $request->valor
		        ));

		        $tipoitem = Caracteristica::where('campo', 'tipoitem')->where('codigo', $request->valor)->first();
		        $item->codtipoitem = $request->valor;
		        $item->tipoitem = $tipoitem->valor;
		        $item->save();

			}


			if ($request->caracteristica == 'linha') {

		        $result = $client->itemUpdate( array( 
		             "codItemCurto"=> $item->id,
		             "codlinha" => $request->valor
		        ));

		        $linha = Caracteristica::where('campo', 'linha')->where('codigo', $request->valor)->first();
		        $item->codlinha = $request->valor;
		        $item->linha = $linha->valor;
		        $item->save();

			}			

			if ($request->caracteristica == 'agrupamento') {

		        $result = $client->itemUpdate( array( 
		             "codItemCurto"=> $item->id,
		             "codagrup" => $request->valor
		        ));

		        $linha = Caracteristica::where('campo', 'agrupamento')->where('codigo', $request->valor)->first();
		        $item->codagrup = $request->valor;
		        $item->agrup = $linha->valor;
		        $item->save();

			}			
	        // se alterar no JDE , altera tabela iten

	    }		
*/


}
