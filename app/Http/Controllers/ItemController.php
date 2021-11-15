<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use SoapClient;

class ItemController extends Controller
{

	public function gravaAjusteImagem(Request $request) {


		$query = \DB::select("insert into fotos (foto_modelo, foto_referencia) values ('$request->modelo', '$request->secundario')");


		return redirect()->back();



	}

	public function novoItem(Request $request) {

		$novo_item = new \App\ItemNovo();
		$novo_item->id_usuario = \Auth::id();
		$novo_item->tipo = $request->tipo;
		$novo_item->modelo = $request->modelo;
		$novo_item->referencia = $request->referencia;
		$novo_item->descricao = $request->descricao;
		$novo_item->fornecedor = $request->fornecedor;
		$novo_item->colitem = 'NOVO';
		$novo_item->clasitem = 'NOVO';

		$novo_item->save();

		return redirect()->back();

		dd($request->all());

	}

	public function trocaImagem(Request $request) {

		if ($request->tipo == 'modelo') {

			$modelo = Item::where('modelo', $request->valor)->first();

			$nome_arquivo = explode('.',$_FILES['arquivo']['name']);
			$extensao = $nome_arquivo[1];


			$uploaddir = '/var/www/html/fotos/MODELO/'.$modelo->agrup.'/';
			$uploadfile = $uploaddir . $modelo->modelo.'.'.$extensao;

			$erros = array();

			if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $uploadfile)) {

			    if (file_exists($uploadfile)) {



			    }


			}



		} else {


			$item = Item::where('secundario', $request->valor)->first();

			$nome_arquivo = explode('.',$_FILES['arquivo']['name']);
			$extensao = $nome_arquivo[1];


			$uploaddir = '/var/www/html/fotos/ALTA/'.$item->agrup.'/';
			$uploadfile = $uploaddir . $item->secundario.'.'.$extensao;

			$erros = array();

		    if (file_exists($uploadfile)) {
				$arquivo_renomeado = $uploaddir . $item->secundario.'_'.date("YmdHis").'.'.$extensao;
				rename($uploadfile, $arquivo_renomeado);

		    }

			if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $uploadfile)) {

				return redirect()->back();

			}




		}

		dd($uploadfile);


	}

	public function importeAtualizacaoMassa() {

		return view('produtos.atualizacao.lista');


	}

	public function atualizacaoMassa(Request $request) {

		 ini_set("display_errors", 0);
        ini_set('memory_limit', -1);
		ini_set('max_execution_time', -1);
		
		$arquivo = '';
        $nameFile = null;
     
        // Verifica se informou o arquivo e se é válido
        if ($request->hasFile('arquivo') && $request->file('arquivo')->isValid()) {
            if ($request->arquivo->extension() == 'txt') {
                // Define um aleatório para o arquivo baseado no timestamps atual
                $name = uniqid(date('HisYmd'));
         
                // Recupera a extensão do arquivo
                $extension = $request->arquivo->extension();
         
                // Define finalmente o nome
                $nameFile = "{$name}.csv";
         
                // Faz o upload:
                $upload = $request->arquivo->storeAs('uploads/carga', $nameFile);

                $arquivo = '/var/www/html/portalgo/storage/app/'.$upload;
                // Se tiver funcionado o arquivo foi armazenado em storage/app/public/categories/nomedinamicoarquivo.extensao
            } else {
                $request->session()->flash('alert', 'Arquivo não é CSV');

            }


        } 

	    if (file_exists($arquivo)) {

	        $handle = fopen($arquivo, "r"); 

	        $linha = 1;

	        $campos = array();

	        $atualizacoes = array();

	        while (($line = fgetcsv($handle, 100000, ";")) !== FALSE) {

	        	if ($linha == 1) {

	        		foreach ($line as $key => $campo) {

	        			$campos[$key] = $campo;

	        		} 

	        	} else {

	        		foreach ($line as $key => $coluna) {

	        			if (trim($coluna) != '') {
	        				$item = $line[4];

	        				$campo = $campos[$key];

	        				//if ($campo <> 'filial') {
	        					$atualizacoes[$item][$campo] = $coluna;
	        				//}
	        				echo '['.$key.'] '. $campos[$key] .' -> '.$coluna.'<br>';
							
	        			}

	        		}

	        		$atualizacoes[$item]['filial'] = str_pad('01020000', 12, " ", STR_PAD_LEFT);

	        	}

	        	$linha++;

	        }
// 	        echo '-';
// 	        print_r($atualizacoes[815247]["filial"]);
// 	        echo '-';
// 	        die('teste');
//dd($atualizacoes);
			$client = \App\JDE::connect();

			if (isset($atualizacoes) && count($atualizacoes) > 0) {

				foreach ($atualizacoes as $chave => $atualiza) {

					$item = \App\Item::find($chave);
		       		$result = $client->itemUpdate($atualiza);

		       		echo $item ;
		       		foreach ($atualiza as $campo => $valor) { 

		       			if ($campo <> 'codItemCurto') {

			       			$depara = \DB::select("select itens from servico_jde where servico = '$campo' and itens <> '' ");

			       			if ($depara) {

								$alterado_de = $item->{$depara[0]->itens};

					  	        $item->{$depara[0]->itens} = $valor;
					  //        $item->grife = $grife->valor;
					            $item->save();

					            \App\ItemHistorico::gravaHistorico($item->id, 'caracteristica', 'Caracteristica <b>'.$depara[0]->itens.'</b> alterada de '.$alterado_de.' para '.$valor.'.');


  

			       			}
			       		}

				    }

					echo "REQUEST:\n" . htmlentities($client->__getLastRequest()) . "\n";

			    }

		    }
		        

	       	dd($atualizacoes);


	    }



	}
public function sobePreco() {

	$client = \App\JDE::connect();
	$precos = \DB::select("select id_item, preco, codtipoarmaz from 00preco left join itens on id = id_item ");
	

				foreach ($precos as $preco){
					


						 $result = $client->itemUpdate( array( 
			             "codItemCurto"=> $preco->id_item,
			             "filial"=> '    01020000',
			             "codtipoarmazenamento"=> $preco->codtipoarmaz,
			             "preco" => $preco->preco
			        ));
						 echo $preco->id_item ;
				}



		
			}

	public function alteraPreco(Request $request) {

		$id_usuario = \Auth::id();

		$autorizacao = \DB::select("insert into autorizacoes (id_usuario, tipo, id_item, campo, valor) values ($id_usuario, '$request->tipo', '$request->id_item', 'preco', '$request->caracteristica') ");

		return redirect()->back();	

	}

	public function consultaFotoModelo($modelo) {

				
		$produto = \DB::select("select trim(agrup) as agrupamento,  modelo
									from go.itens
					
									where modelo = '$modelo'");

		$link = 'https://portal.goeyewear.com.br/';

		
		$foto_modelo1 	= 'fotos/MODELO/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->modelo).'.JPG';

		$foto_modelo2 	= 'fotos/MODELO/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->modelo).'.jpg';

		$foto_ficha_design1 = $link.'fotos/FICHA_DESIGN/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->modelo).'.jpg';
		$foto_ficha_design2	= $link.'fotos/FICHA_DESIGN/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->modelo).'.JPG';

		$foto = 'fotos/nopicture.jpg';
	
		if (file_exists($foto_ficha_design1)and (\auth::user()->admin ==1 or \auth::user()->id_perfil ==2 or \auth::user()->id_perfil ==1 or \auth::user()->id_perfil ==7 or \auth::user()->id_perfil ==17)) {
			$foto = $foto_ficha_design1;
		}

		if (file_exists($foto_ficha_design2)and (\auth::user()->admin ==1 or \auth::user()->id_perfil ==2 or \auth::user()->id_perfil ==1 or \auth::user()->id_perfil ==7 or \auth::user()->id_perfil ==17)) {
			$foto = $foto_ficha_design2;
		}
		if (file_exists($foto_modelo1) ) {
			$foto = $foto_modelo1;	
		}
		
		if (file_exists($foto_modelo2)) {
			$foto = $foto_modelo2;	
		}



		$foto = $foto;
		
		return $foto;
							

	}

	public function consultaFotoThumb($referencia) {

				
		$produto = \DB::select("select trim(agrup) as agrupamento, trim(secundario) as item, modelo,
		case when secundario = '$referencia' then secundario else modelo end as item2
									from go.itens
									
									where (secundario = '$referencia' or modelo = '$referencia')");

		$link = 'https://portal.goeyewear.com.br/';

		
		$foto_modelo1 	= 'fotos/THUMBNAIL/'.trim($produto[0]->item2).'.JPG';

		$foto_modelo2 	= 'fotos/THUMBNAIL/'.trim($produto[0]->item2).'.jpg';

		$foto = 'fotos/nopicture.jpg';
	
		
		if (file_exists($foto_modelo1)) {
			$foto = $foto_modelo1;	
		}
		
		if (file_exists($foto_modelo2)) {
			$foto = $foto_modelo2;	
		}

		$foto = $foto;
		
		return $foto;
							

	}

  

	public function consultaFoto($referencia) {


		$produto = \DB::select("select trim(agrup) as agrupamento, trim(secundario) as item, modelo,
		case when secundario = '$referencia' then secundario else modelo end as item2
									from go.itens
									
									where (secundario = '$referencia' or modelo = '$referencia')");

		$link = 'https://portal.goeyewear.com.br/';

		if ($produto) {

			$foto_baixa1 	= 'fotos/BAIXA/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->item2).'.jpg';
			$foto_baixa2 	= 'fotos/BAIXA/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->item2).'.JPG';
			
			$foto_alta1 	= 'fotos/ALTA/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->item2).'.jpg';
			$foto_alta2 	= 'fotos/ALTA/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->item2).'.JPG';
			
			
			$foto_modelo1 	= 'fotos/MODELO/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->modelo).'.JPG';

			$foto_modelo2 	= 'fotos/MODELO/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->modelo).'.jpg';

			$foto_ficha1 	= 'fotos/FICHA/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->modelo).'.JPG';
			
			$foto_ficha2 	= 'fotos/FICHA/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->modelo).'.jpg';
			
			$foto_ficha_design2 	= 'fotos/FICHA_DESIGN/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->modelo).'.jpg';
			
			$foto_ficha_design1 	= 'fotos/FICHA_DESIGN/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->modelo).'.JPG';
			
			$foto_ficha2 	= 'fotos/FICHA_TECNICA/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->modelo).'.jpg';
			
			$foto_ficha1 	= 'fotos/FICHA_TECNICA/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->modelo).'.JPG';
			
			$foto_prototipo1 	= 'fotos/PROTOTIPO/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->modelo).'.jpg';
			
			$foto_prototipo2	= 'fotos/PROTOTIPO/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->modelo).'.JPG';
			

			$combinacao1 	= 'fotos/COMBINACAO/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->modelo).'.jpg';
			
			$combinacao2 	= 'fotos/COMBINACAO/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->modelo).'.JPG';
				
		
			$agregado1 	= 'fotos/AGREGADOS/'.trim($referencia).'.JPG';
			$agregado2	= 'fotos/AGREGADOS/'.trim($referencia).'.jpg';
	//die($foto_baixa1);
			//$foto = 'fotos/nopicture.jpg';
			$foto = 'fotos/no-image.png';
		
			
			if (file_exists($agregado1)) {

				$foto = $agregado1;	
			}
			
			if (file_exists($agregado2)) {
				$foto = $agregado2;	
			}

			if (file_exists($combinacao1) and (\auth::user()->admin ==1 or \auth::user()->id_perfil ==2 or \auth::user()->id_perfil ==1 or \auth::user()->id_perfil ==7 or \auth::user()->id_perfil ==17)) {
				$foto = $combinacao1;	
			}
			
			if (file_exists($combinacao2) and (\auth::user()->admin ==1 or \auth::user()->id_perfil ==2 or \auth::user()->id_perfil ==1 or \auth::user()->id_perfil ==7 or \auth::user()->id_perfil ==17)) {
				$foto = $combinacao2;	
			}
			
			if (file_exists($foto_ficha1) and (\auth::user()->admin ==1 or \auth::user()->id_perfil ==2 or \auth::user()->id_perfil ==1 or \auth::user()->id_perfil ==7 or \auth::user()->id_perfil ==17)) {
				$foto = $foto_ficha1;	
			}
			if (file_exists($foto_ficha2) and (\auth::user()->admin ==1 or \auth::user()->id_perfil ==2 or \auth::user()->id_perfil ==1 or \auth::user()->id_perfil ==7 or \auth::user()->id_perfil ==17)) {
				$foto = $foto_ficha2;
			}

			if (file_exists($foto_ficha_design1) and (\auth::user()->admin ==1 or \auth::user()->id_perfil ==2 or \auth::user()->id_perfil ==1 or \auth::user()->id_perfil ==7 or \auth::user()->id_perfil ==17)) {
				$foto = $foto_ficha_design1;	
			}
			if (file_exists($foto_ficha_design2) and (\auth::user()->admin ==1 or \auth::user()->id_perfil ==2 or \auth::user()->id_perfil ==1 or \auth::user()->id_perfil ==7 or \auth::user()->id_perfil ==17)) {
				$foto = $foto_ficha_design2;
			}

			if (file_exists($foto_ficha1) and (\auth::user()->admin ==1 or \auth::user()->id_perfil ==2 or \auth::user()->id_perfil ==1 or \auth::user()->id_perfil ==7 or \auth::user()->id_perfil ==17)) {
				$foto = $foto_ficha1;	
			}
			if (file_exists($foto_ficha2) and (\auth::user()->admin ==1 or \auth::user()->id_perfil ==2 or \auth::user()->id_perfil ==1 or \auth::user()->id_perfil ==7 or \auth::user()->id_perfil ==17)) {
				$foto = $foto_ficha2;
			}
			
		
			

			if (file_exists($foto_prototipo1) and (\auth::user()->admin ==1 or \auth::user()->id_perfil ==2 or \auth::user()->id_perfil ==1  or \auth::user()->id_perfil ==7 or \auth::user()->id_perfil ==17)) {
				$foto = $foto_prototipo1;	
			}
			if (file_exists($foto_prototipo2) and (\auth::user()->admin ==1 or \auth::user()->id_perfil ==2 or \auth::user()->id_perfil ==7 or \auth::user()->id_perfil ==1 or \auth::user()->id_perfil ==17)) {
				$foto = $foto_prototipo2;		
			}
			
			if (file_exists($foto_modelo1)and (\auth::user()->admin ==1 or \auth::user()->id_perfil ==2 or \auth::user()->id_perfil ==1 or \auth::user()->id_perfil ==7 or \auth::user()->id_perfil ==17)) {
				$foto = $foto_modelo1;	
			}
			
			if (file_exists($foto_modelo2)and (\auth::user()->admin ==1 or \auth::user()->id_perfil ==2 or \auth::user()->id_perfil ==1 or \auth::user()->id_perfil ==7 or \auth::user()->id_perfil ==17)) {
				$foto = $foto_modelo2;	
			}
			
			if (file_exists($foto_alta2)) {
				$foto = $foto_alta2;	
			}
			if (file_exists($foto_alta1)) {
				$foto = $foto_alta1;	
			}

			if (file_exists($foto_baixa2)) {
				$foto = $foto_baixa2;	
			}
			if (file_exists($foto_baixa1)) {
				$foto = $foto_baixa1;		
			}
			$foto = $foto;
		} else {

			$foto = 'fotos/no-image.png';


		}	
		
		return $foto;
							

	}


	public function consultaFotoAlta($referencia) {


		$produto = \DB::select("select trim(agrup) as agrupamento, trim(secundario) as item, modelo,
		case when secundario = '$referencia' then secundario else modelo end as item2
									from go.itens
									
									where (secundario = '$referencia' or modelo = '$referencia')");

		$link = 'https://portal.goeyewear.com.br/';

		if ($produto) {

			$foto_baixa1 	= 'fotos/BAIXA/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->item2).'.jpg';
			$foto_baixa2 	= 'fotos/BAIXA/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->item2).'.JPG';
			
			$foto_alta1 	= 'fotos/ALTA/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->item2).'.jpg';
			$foto_alta2 	= 'fotos/ALTA/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->item2).'.JPG';


			$qrcode1 	= 'fotos/QRCODE/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->item2).'.jpg';
			$qrcode2 	= 'fotos/QRCODE/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->item2).'.JPG';
			
			$foto_modelo1 	= 'fotos/MODELO/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->modelo).'.JPG';

			$foto_modelo2 	= 'fotos/MODELO/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->modelo).'.jpg';

			$foto_ficha1 	= 'fotos/FICHA/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->modelo).'.JPG';
			
			$foto_ficha2 	= 'fotos/FICHA/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->modelo).'.jpg';
			
			$foto_ficha2 	= 'fotos/FICHA_DESIGN/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->modelo).'.jpg';
			
			$foto_ficha1 	= 'fotos/FICHA_DESIGN/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->modelo).'.JPG';
			
			$foto_ficha2 	= 'fotos/FICHA_TECNICA/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->modelo).'.jpg';
			
			$foto_ficha1 	= 'fotos/FICHA_TECNICA/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->modelo).'.JPG';
			
			$foto_prototipo1 	= 'fotos/PROTOTIPO/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->modelo).'.jpg';
			
			$foto_prototipo2	= 'fotos/PROTOTIPO/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->modelo).'.JPG';
			

			$combinacao1 	= 'fotos/COMBINACAO/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->modelo).'.jpg';
			
			$combinacao2 	= 'fotos/COMBINACAO/'.trim($produto[0]->agrupamento).'/'.trim($produto[0]->modelo).'.JPG';
			
			$agregado1 	= 'fotos/AGREGADOS/'.trim($referencia).'.JPG';
			$agregado2	= 'fotos/AGREGADOS/'.trim($referencia).'.jpg';
	//die($foto_baixa1);
			//$foto = 'fotos/nopicture.jpg';
			$foto = 'fotos/no-image.png';
		
			
			if (file_exists($agregado1)) {

				$foto = $agregado1;	
			}
			
			if (file_exists($agregado2)) {
				$foto = $agregado2;	
			}

			if (file_exists($combinacao1) and (\auth::user()->admin ==1 or \auth::user()->id_perfil ==2 or \auth::user()->id_perfil ==1 or \auth::user()->id_perfil ==7 or \auth::user()->id_perfil ==17)) {
				$foto = $combinacao1;	
			}
			
			if (file_exists($combinacao2) and (\auth::user()->admin ==1 or \auth::user()->id_perfil ==2 or \auth::user()->id_perfil ==1 or \auth::user()->id_perfil ==7 or \auth::user()->id_perfil ==17)) {
				$foto = $combinacao2;	
			}
			
			if (file_exists($foto_ficha1) and (\auth::user()->admin ==1 or \auth::user()->id_perfil ==2 or \auth::user()->id_perfil ==1 or \auth::user()->id_perfil ==7 or \auth::user()->id_perfil ==17)) {
				$foto = $foto_ficha1;	
			}
			if (file_exists($foto_ficha2) and (\auth::user()->admin ==1 or \auth::user()->id_perfil ==2 or \auth::user()->id_perfil ==1 or \auth::user()->id_perfil ==7 or \auth::user()->id_perfil ==17)) {
				$foto = $foto_ficha2;
			}
			
		
			

			if (file_exists($foto_prototipo1) and (\auth::user()->admin ==1 or \auth::user()->id_perfil ==2 or \auth::user()->id_perfil ==1  or \auth::user()->id_perfil ==7 or \auth::user()->id_perfil ==17)) {
				$foto = $foto_prototipo1;	
			}
			if (file_exists($foto_prototipo2) and (\auth::user()->admin ==1 or \auth::user()->id_perfil ==2 or \auth::user()->id_perfil ==7 or \auth::user()->id_perfil ==1 or \auth::user()->id_perfil ==17)) {
				$foto = $foto_prototipo2;		
			}
			
			if (file_exists($foto_modelo1)) {
				$foto = $foto_modelo1;	
			}
			
			if (file_exists($foto_modelo2)) {
				$foto = $foto_modelo2;	
			}
			if (file_exists($foto_baixa2)) {
				$foto = $foto_baixa2;	
			}
			if (file_exists($foto_baixa1)) {
				$foto = $foto_baixa1;		
			}
			
			if (file_exists($qrcode2)) {
				$foto = $qrcode2;	
			}
			if (file_exists($qrcode1)) {
				$foto = $qrcode1;	
			}

			
			$foto = $foto;
		} else {

			$foto = 'fotos/no-image.png';


		}		
		return $foto;
							

	}



	public function consultaItem(Request $request) {

		$q = $request["q"];

		$itens = Item::where('secundario', 'LIKE', $q.'%')->paginate(16);

		return view('search.result')->with('itens', $itens);

	}


	public function processamentos() {

		$processamentos = \App\StatusProcessa::groupBy('processamento', 'data')->get(['processamento', 'data']);

		return view('produtos.status.processamentos')->with('processamentos', $processamentos);

	}



	public function dadosProduto($item) {


		$dados = Item::where('secundario', $item)->first();

		return response()->json($dados);


	}

	function faltaFoto() {


		$produto = \DB::select("select agrup, id, secundario, statusatual, codtipoarmaz
									from itens
									
									");
		
		$apaga = \DB::select("TRUNCATE falta_foto");
		//$produto = mysqli_fetch_assoc($produto);

		$link = '/var/www/html/';
		foreach($produto as $item){

		$foto_baixa1 	= $link.'fotos/BAIXA/'.$item->agrup.'/'.$item->secundario.'.jpg';
		$foto_baixa2 	= $link.'fotos/BAIXA/'.$item->agrup.'/'.$item->secundario.'.JPG';
		$link.
		$foto_alta1 	= $link.'fotos/ALTA/'.$item->agrup.'/'.$item->secundario.'.jpg';
		$foto_alta2 	= $link.'fotos/ALTA/'.$item->agrup.'/'.$item->secundario.'.JPG';
		$link.
		$foto_modelo1 	= $link.'fotos/MODELO/'.$item->agrup.'/'.$item->secundario.'.JPG';
$link.
		$foto_modelo2 	= $link.'fotos/MODELO/'.$item->agrup.'/'.$item->secundario.'.jpg';
$link.
		$foto_ficha1 	= $link.'fotos/FICHA/'.$item->agrup.'/'.$item->secundario.'.JPG';
		$link.
		$foto_ficha2 	= $link.'fotos/FICHA/'.$item->agrup.'/'.$item->secundario.'.jpg';

		$foto_prototipo1 	= $link.'fotos/PROTOTIPO/'.$item->agrup.'/'.$item->secundario.'.jpg';
		
		$foto_prototipo2	= $link.'fotos/PROTOTIPO/'.$item->agrup.'/'.$item->secundario.'.JPG';
		

		$combinacao1 	= $link.'fotos/COMBINACAO/'.$item->agrup.'/'.$item->secundario.'.jpg';
		
		$combinacao2 	= $link.'fotos/COMBINACAO/'.$item->agrup.'/'.$item->secundario.'.JPG';
		
		$agregado1 	= $link.'fotos/AGREGADOS/'.$item->secundario.'.JPG';
		$agregado2	= $link.'fotos/AGREGADOS/'.$item->secundario.'.jpg';
//die($foto_baixa1);
		$foto = 'fotos/no-image.png';
	
		
		
	
		if (file_exists($foto_alta2)) {
			$foto = $foto_alta2;	
		}
		if (file_exists($foto_alta1)) {
			$foto = $foto_alta1;	
		}



		if (file_exists($foto_baixa2)) {
			$foto = $foto_baixa2;	
		}
		if (file_exists($foto_baixa1)) {
			$foto = $foto_baixa1;		
		}
		$foto = $foto;
		if($foto == 'fotos/no-image.png'){

			
			$insere = \DB::select("INSERT INTO `falta_foto`(`agrup`, `id_item`, `item`, `tipo_armaz`, `statusatual`) VALUES ('$item->agrup','$item->id','$item->secundario','$item->codtipoarmaz','$item->statusatual')");
			
		}
		
		
							
	}
	$falta = \DB::select("select * from falta_foto");
		foreach($falta as $itensfalta){
			echo $itensfalta->agrup.' - '.$itensfalta->item.' - '.$itensfalta->statusatual.' - '.$itensfalta->tipo_armaz.'<br>';
		}
	}


	public function outrasCores(Request $request) {

//		$item = \App\Item::where('secundario', $request->item)->first();

//		$cores = \App\Item::where('modelo', $item->modelo)->select('secundario')->get();

		$id_cliente = 85252;
		$id_representante = 85252;

		$cores = \DB::select("select base.*, saldos.secundario, saldos.disp, saldos.transito, producoes.producao, dupl.pecas dupl from (
select secundario, modelo, id_item, sum(qtd_aberto) as pecas, sum(total) as valor
from orcamentos_anal
left join itens on id_item = itens.id
where secundario = '$request->item'
/**where id_cliente = $id_cliente and id_representante = $id_representante**/
group by  modelo, id_item, secundario
) as base

/**estoques  para itens substitutos**/
left join (
	select curto, modelo, saldos.secundario,
	case when disponivel < 0 then 0 else disponivel end as disp,
	case when (conf_montado+em_beneficiamento+cet+saldo_parte+qtd_rot_receb) < 0 then 0 else (conf_montado+em_beneficiamento+cet+saldo_parte+qtd_rot_receb) end as transito
	from saldos 
    left join itens on saldos.secundario = itens.secundario
    where itens.secundario <> '$request->item'
) as saldos
on saldos.modelo = base.modelo

/**estoque em producao para itens substitutos**/
left join (
	select id item, sum(producao+estoque) producao from producoes_sint group by id
	) as producoes
on producoes.item = saldos.curto


/**verifica se tem outro item do mesmo modelo tambem em aberto**/
left join (
select secundario, sum(qtd_aberto) as pecas, sum(total) as valor
from orcamentos_anal
left join itens on id_item = itens.id
where  secundario <> '$request->item'
/**where id_cliente = $id_cliente and id_representante = $id_representante**/
group by secundario
) as dupl
on dupl.secundario = saldos.secundario");

		return response()->json($cores);

	}




}
