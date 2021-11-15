<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Catalogo;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \hpOffice\PhpSpreadsheet\Worksheet;

class CatalogoController extends Controller
{

	public function CatalogoExcel() {

		return view('produtos.catalogos.excel');

	}


    public function montaCatalogoExcel(Request $request) {

        if ($request->consulta) {

            $query = \DB::connection($request->origem)->select($request->consulta);


            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            //$sheet->getColumnDimension('A')->setWidth(20);

            //$spreadsheet->getActiveSheet()->setAutoFilter('B1:L1');        
            //$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(true);



            //$sheet->getColumnDimension('A')->setAutoSize(true);
            //$sheet->getColumnDimension('B')->setAutoSize(true);


		$sheet->getColumnDimension('A')->setWidth(20);
		
		$spreadsheet->getActiveSheet()->setAutoFilter('B1:L1');		
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('G')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('H')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('I')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('J')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('K')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('L')->setAutoSize(true);



            $linha = 1;

            $coluna = array();

            $letras = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W', 'X','Y','Z');


            foreach ($query as $registro) {
                $id_coluna = 0;

                foreach ($registro as $coluna => $valor ) {

                    if ($linha == 1) {
                        
                        $sheet->setCellValue($letras[$id_coluna].'1', $coluna);

                    } 

                    $id_coluna++;

                }


            }


            foreach ($query as $registro) {
                $id_coluna = 0;

                $linha++;

                foreach ($registro as $coluna => $valor ) {

                    if ($linha > 1) {
                        
                        //$sheet->setCellValue($letras[$id_coluna].'1', $coluna);

                        $spreadsheet->getActiveSheet()->getRowDimension($linha)->setRowHeight(100);

                        if ($id_coluna == 0) {




							$foto = app('App\Http\Controllers\ItemController')->consultaFoto(trim($valor));
							//$img = resize_image("/var/www/html/portalgo/public/logogo.png", 40, 40);
							if ($foto != 'fotos/nopicture.jpg') {


	                            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
	                            $drawing->setName('Paid');
	                            $drawing->setDescription('Paid');
	                            $drawing->setPath('/var/www/html/portalgo/public/'.$foto); // put your path and image here
	                            $drawing->setCoordinates('A'.$linha);
	                            //$drawing->setOffsetX(110);
	                            $drawing->setHeight(80);
	                            $drawing->setWidth(110);
	                    //      $drawing->setRotation(25);
	                            $drawing->getShadow()->setVisible(true);
	                    //      $drawing->getShadow()->setDirection(45);
	                            $drawing->setWorksheet($spreadsheet->getActiveSheet());


	                        }
                            
                        } else {
	                        $sheet->setCellValue($letras[$id_coluna].$linha, $valor);

                        }

                    }

                    $id_coluna++;

                }




            }

			$writer = new Xlsx($spreadsheet);
		//	$writer->save('hello world.xlsx');		
			
			$nome = 'orderGO_'.date("Y-m-d").'.xlsx';
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$nome.'"');
			
			header('Cache-Control: max-age=0');

			$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
			$writer->save('php://output');


            //echo '<a href="'.$nome_excel.'"> texte </a>';  
        }


    }
	public function novoCatalogo() {

		return view('produtos.catalogos.novo');
		
	}


	public function excluiCatalogo($codigo) {


		$catalogo = Catalogo::where('codigo', $codigo)->where('id_usuario', \Auth::id())->get();

		foreach ($catalogo as $item) {
			$item->status = 0;
			$item->save();
		}

		return redirect('/meus-catalogos');
		
	}

	public function meusCatalogos() {

		$id_usuario = \Auth::id();


		$catalogos = \DB::select("select date(created_at) as created_at, codigo, titulo, descricao
									from catalogos 
									where id_usuario = $id_usuario and status = 1 and date_add(created_at, interval +60 day) > now()
									group by date(created_at), codigo, titulo, descricao
									order by date(created_at) desc");

		//$catalogos = Catalogo::where('id_usuario', \Auth::id())->where('status', 1)->groupBy('created_at', 'codigo', 'titulo', 'descricao')->get(['created_at', 'codigo', 'titulo', 'descricao']);

		return view('produtos.catalogos.meus')->with('catalogos', $catalogos);
		
	}

	public function importarItens(Request $request, $codigo) {

		
		
			$catalogo = Catalogo::where('codigo', $codigo)->first();
		$id_usuario = $catalogo->id_usuario ;
		$titulo = $catalogo->titulo;
		$descricao = $catalogo->descricao;
		 $data = date('d-m-yy-H:i:s');
      
      $arquivo2 = $codigo."catalogo.xlsx";

      $uploaddir = '/var/www/html/portalgo/storage/uploads/';
      $uploadfile = $uploaddir .$arquivo2 ;
		
	
      

      $erros = array();

      if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $uploadfile)) {
		   
        
       if (file_exists($uploadfile)) {
		    
	

          $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");

          $spreadsheet = $reader->load($uploadfile);

          $sheet = $spreadsheet->getActiveSheet()->toArray();


          $i = 1;
          foreach ($sheet as $linha) {    

            if ($i > 1) {   
              $item = $linha[0];
				
				
				 
				$produto = \App\Item::where('secundario', $item)->first();
              if ($produto) {
				  //dd($produto);

						$item = new Catalogo();
						$item->id_usuario = $id_usuario;
						$item->codigo = $codigo;
						$item->titulo = $titulo;
						$item->descricao = $descricao;
						$item->grife = $produto->codgrife;
						$item->agrup = $produto->codagrup;
						$item->modelo = $produto->modelo;
						$item->item = $produto->secundario;
						$item->save();

					}
				else {
					//dd($item);
					$erros[] = 'Item '.$item.' não existe.';
                  $request->session()->flash('alert-danger', $erros);
				}
  
            }
            $i++;
          } 
	   }
	  }
		
		
		return redirect('/catalogo/'.$codigo)->with('erros', $erros);	
		
		
		
		
		
		
		

		$catalogo = Catalogo::where('codigo', $codigo)->first();

		if ($request->consulta <> '') {

			$query = \DB::select($request->consulta);
			dd($query);

			if ($query) {

				$i = 0;

				foreach ($query as $item) {

					$produto = \App\Item::where('secundario', $item->referencia)->first();

					if ($produto && $i < 100) {

						$item = new Catalogo();
						$item->id_usuario = 1;
						$item->codigo = $codigo;
						$item->titulo = $catalogo->titulo;
						$item->descricao = $catalogo->descricao;
						$item->grife = $produto->codgrife;
						$item->agrup = $produto->codagrup;
						$item->modelo = $produto->modelo;
						$item->item = $produto->secundario;
						$item->save();

					}

					$i++;

				} 


			}


		}

		return redirect('/catalogo/'.$codigo);			


	}

	public function verCatalogo(Request $request, $codigo) {


		if (isset($request->grife)) {
			$modelos = Catalogo::where('codigo', $codigo)->where('modelo', '<>', '.')->where('grife', $request->grife)->orderBy('modelo')->get();
		} else {
			$modelos = Catalogo::where('codigo', $codigo)->where('modelo', '<>', '.')->orderBy('modelo')->get();
		}

		$grifes = array();
		$catalogo = Catalogo::where('codigo', $codigo)->where('modelo', '<>', '.')->orderBy('modelo')->get();
		
		foreach ($catalogo as $modelo) {
			$item = \App\Item::where('modelo', $modelo->modelo)->first();
			$grifes[$item->codgrife] = $item->grife;
		}

		$catalogo = Catalogo::where('codigo', $codigo)->where('modelo', '.')->orderBy('modelo')->first();

		//if (count($modelos) > 0) {
			//return view('produtos.catalogos.catalogo')->with('modelos', $modelos)->with('grifes', $grifes);
		//} else {

		return view('produtos.catalogos.catalogo')->with('modelos', $modelos)->with('grifes', $grifes)->with('catalogo', $catalogo);
			//echo 'Nenhum item adicionado';
			//return redirect('/painel');
		//}

	}

	public function gravaCatalogo(Request $request, $codigo) {

		$catalogo = Catalogo::where("codigo", $codigo)->get(); 

		foreach ($catalogo as $item) {
			$item->titulo = $request->titulo;
			$item->descricao = $request->descricao;
			if (isset($request->publico)) {
				$item->publico = $request->publico;
			}
			$item->save();
		}

		$request->session()->forget('novocatalogo');

		return redirect('/catalogo/'.$codigo);

	}

	public function editaCatalogo(Request $request, $codigo) {

		$catalogo = Catalogo::where('codigo', $codigo)->first();

		$catalogo = array(
						"codigo" => $codigo,
						"titulo" => $catalogo->titulo,
						"descricao" => $catalogo->descricao,
						"publico" => $catalogo->publico
					);

		$request->session()->put('novocatalogo' , $catalogo);

		return redirect('/catalogo/'.$codigo);

	}

	public function cancelaCatalogo(Request $request, $codigo) {

		$request->session()->forget('novocatalogo');

		return redirect('/catalogo/'.$codigo);

	}

	public function montaCatalogo(Request $request) {

		$request->session()->forget('novocatalogo');

		$codigo = date("YmdHis");
		if (isset($request->publico)) {
			$publico = 1;
		} else {
			$publico = 0;
		}

		$catalogo = array(
						"codigo" => $codigo,
						"titulo" => $request->titulo,
						"descricao" => $request->descricao,
						"publico" => $publico
					);

		$request->session()->put('novocatalogo' , $catalogo);

		$catalogo = new Catalogo();
		$catalogo->id_usuario = \Auth::id();
		$catalogo->codigo = $codigo;
		$catalogo->titulo = $request->titulo;
		$catalogo->descricao = $request->descricao;
		$catalogo->modelo = '.';
		$catalogo->save();

		return redirect('/painel');

	}

	public function addModelo(Request $request, $codigo) {

		$catalogo = Catalogo::where('codigo', $codigo)->first();
		//if ($catalogo->modelo <> $request->modelo) {
			

			$modelo = \App\Item::where('modelo', $request->modelo)->where('codtipoitem', '006')->where('secundario', 'not like', '%semi%')->get();

			if ($modelo && count($modelo) > 0) {
				foreach ($modelo as $item) {

					$novo = new Catalogo();
					$novo->id_usuario = 1;
					$novo->codigo = $catalogo->codigo;
					$novo->titulo = $catalogo->titulo;
					$novo->descricao = $catalogo->descricao;
					$novo->grife = $item->codgrife;
					$novo->agrup = $item->codagrup;
					$novo->modelo = $item->modelo;
					$novo->item = $item->secundario;
					$novo->save();

				}
			} else {

				$modelo = \App\Item::where('secundario', $request->modelo)->where('codtipoitem', '006')->where('secundario', 'not like', '%semi%')->get();

				foreach ($modelo as $item) {

					$novo = new Catalogo();
					$novo->id_usuario = 1;
					$novo->codigo = $catalogo->codigo;
					$novo->titulo = $catalogo->titulo;
					$novo->descricao = $catalogo->descricao;
					$novo->grife = $item->codgrife;
					$novo->agrup = $item->codagrup;
					$novo->modelo = $item->modelo;
					$novo->item = $item->secundario;
					$novo->save();

				}

			}

			echo '1';
		//} else {
			//return '0';
		//}
	}


	public function addItem(Request $request, $codigo) {

		$catalogo = Catalogo::where('codigo', $codigo)->first();

		if ($catalogo->item <> strtoupper($request->item)) {

			$modelo = \App\Item::where('secundario', $request->item)->where('codtipoitem', '006')->first();

			if ($modelo) {
				$item = new Catalogo();
				$item->id_usuario = 1;
				$item->codigo = $catalogo->codigo;
				$item->titulo = $catalogo->titulo;
				$item->descricao = $catalogo->descricao;
				$item->grife = $modelo->codgrife;
				$item->agrup = $modelo->codagrup;
				$item->modelo = $modelo->modelo;
				$item->item = $request->item;
				$item->save();

				return redirect('/catalogo/'.$codigo);				

			} else {
				return 'erro';
			}

		} else {
			return '0';
		}

	}

	public function delItem($codigo, $id) {

		$catalogo = Catalogo::find($id);
		$catalogo->delete();

		return redirect('/catalogo/'.$codigo);

	}

	public function catalogoPadrao($tipo) {

		$grifes = \App\Permissao::getPermissao( \Auth::id() , 'grifes');

		$agrupamentos = \App\Item::whereIn('codgrife', $grifes)
									->where('codtipoitem', '006')
									->groupBy('codagrup', 'agrup')
									->select('codagrup', 'agrup')->get();

		return view('produtos.catalogos.padrao')->with('tipo', $tipo)->with('agrupamentos', $agrupamentos);

	}


	public function exportaCatalogo($codigo) {
		$id_usuario = \Auth::id();

		$mpdf = new \Mpdf\Mpdf();

		$catalogo = Catalogo::where('codigo', $codigo)->where('modelo', '<>', '.')->orderBy('agrup')->get();

		$stylesheet = '	.row {
							clear: both;
							width: 100%;
						}
						.col-md-3 {
							width: 25%;
						}';
		
		$stylesheet = file_get_contents(asset('/css/template.css'));

		if (0==0) {
			
			
			
			$html = '<div id="container">
					<div id="title">

						<h1><img src="/img/logogo.png" width="100" /> </h1>

					</div>

					<div id="description">

		  				<p> <span class="date">'.$catalogo[0]->titulo.'</span></p>

					</div>	
					<div id="itens">';
			
			

		} else {

			$html = '<div id="container">
						<div id="title">

							<h1><img src="/img/logogo.png" width="100" /> </h1>

						</div>

						<div id="description">

			  				<h4><span class="date">'.$catalogo[0]->titulo.'</span></h4>
							

						</div>	
						<div id="itens"></div>';		
		}
		
		$i = 1; 
		foreach ($catalogo as $modelo) {
				$result = \DB::select("select agrup, tamolho, tamhaste, tamponte, valortabela, fornecedor  from itens where secundario = '$modelo->item'");
   				$agrup2 = $result[0]->agrup; 
				$tamolho = $result[0]->tamolho;
				$tamhaste = $result[0]->tamhaste;
				$tamponte = $result[0]->tamponte;
				$valor = $result[0]->valortabela;
      

			// 	dd( $agrup2);
				if ($tamolho==''){$tamolho = '  -  ';}
								 else $tamolho = $tamolho;
				if ($tamhaste==''){$tamhaste = '  -  ';}
								 else $tamhaste = $tamhaste;
				if ($tamponte==''){$tamponte = '  -  ';}
								 else $tamponte = $tamponte;
			 
//			$html .= '<div class="thumbnail"><a class="thumbnailhover" style="color:black important;" ><img src="https://portal.goeyewear.com.br/teste999.php?referencia='.$modelo->item.'"> '.$modelo->item.'<br><small> Valor: R$'.number_format($valor,2).'<br> Olho / Ponte / Haste <br>'.$tamolho.'    /    '.$tamponte.'    /    '.$tamhaste.' </a></small></div>';
	

				if ($codigo == '20200409112717') {

					$gucci = \DB::select("select temp_gucci_china.*, disponivel 
											from temp_gucci_china
											left join saldos on secundario = item
											where item = '$modelo->item'"); 

					if ($gucci) { 
						$html .= '<div class="thumbnail"><a class="thumbnailhover" style="color:black important;" >
						<img src="https://portal.goeyewear.com.br/teste999.php?referencia='.$modelo->item.'"> '.$modelo->item.'<br>Price $'.$gucci[0]->preco.'<br>Stock '.$gucci[0]->disponivel.'</a></small></div>';
					}
					
				} 
			//if ($id_usuario==573 and empty($gucci) ) {
//					//$html .= '<div class="thumbnail"><a class="thumbnailhover" style="color:black important;" ><img src="https://portal.goeyewear.com.br/teste999.php?referencia='.$modelo->item.'"> '.$modelo->item.'<br><small> Valor: R$'.number_format($valor,2).'<br> Olho / Ponte / Haste <br>'.$tamolho.'    /    '.$tamponte.'    /    '.$tamhaste.' </a></small></div>';
//					$html .= '<div class="thumbnail"><a class="thumbnailhover" style="color:black important;" ><img src="https://portal.goeyewear.com.br/teste999.php?referencia='.$modelo->item.'"> '.$modelo->item.'</div>';
//					dd($id_usuario);
//
//				}	
				
			
			if ($id_usuario==573 or $id_usuario==314 or $id_usuario==316 or $id_usuario==472 or $id_usuario==474
				or $id_usuario==475 or $id_usuario==528 or $id_usuario==332 or $id_usuario==337 or $id_usuario==352
				or $id_usuario==359 or $id_usuario==392 or $id_usuario==417 or $id_usuario==429 or $id_usuario==447
				or $id_usuario==542 or $id_usuario==607 or $id_usuario==581){
			$html .= '<div class="thumbnail"><a class="thumbnailhover" style="color:black important;" ><img src="https://portal.goeyewear.com.br/teste999.php?referencia='.$modelo->item.'"> '.$modelo->item.'<br><small> Valor: R$'.number_format($valor,2).'<br> Olho / Ponte / Haste <br>'.$tamolho.'    /    '.$tamponte.'    /    '.$tamhaste.' </a></small></div>';
			}
			else {
					//$html .= '<div class="thumbnail"><a class="thumbnailhover" style="color:black important;" ><img src="https://portal.goeyewear.com.br/teste999.php?referencia='.$modelo->item.'"> '.$modelo->item.'<br> Olho / Ponte / Haste <br>'.$tamolho.'    /    '.$tamponte.'    /    '.$tamhaste.' </a></small></div>';
					
					$html .= '<div class="thumbnail"><a class="thumbnailhover" style="color:black important;" >
					<img src="https://portal.goeyewear.com.br/teste999.php?referencia='.$modelo->item.'"> 
					'.$modelo->item.'<br> Olho / Ponte / Haste <br>'.$tamolho.'    /    '.$tamponte.'    /    '.$tamhaste.' </a></small>
					</div>';

				}	

			$i++;
			
		}
		$html .= '</div>
					</div>';

		$rodape = '<div align="center">
						<small align="center"><b>Gerado por:</b> '.\Auth::user()->nome.' <b>em</b> '.date("d/m/Y H:i:s").'</small>
					</div>';



		// Write some HTML code:
		$mpdf->SetHTMLFooter($rodape);
		$mpdf->WriteHTML($stylesheet,1);
		$mpdf->WriteHTML($html,2);

		// Output a PDF file directly to the browser
		$fileName = $catalogo[0]->titulo." - ".date("d/m/Y");
		// Output a PDF file directly to the browser
		$mpdf->Output($fileName.".pdf","I");	
			

	}


	public function exportaCatalogoExcel() {

		function resize_image($file, $w, $h, $crop=FALSE) {
		    list($width, $height) = getimagesize($file);
		    $r = $width / $height;
		    if ($crop) {
		        if ($width > $height) {
		            $width = ceil($width-($width*abs($r-$w/$h)));
		        } else {
		            $height = ceil($height-($height*abs($r-$w/$h)));
		        }
		        $newwidth = $w;
		        $newheight = $h;
		    } else {
		        if ($w/$h > $r) {
		            $newwidth = $h*$r;
		            $newheight = $h;
		        } else {
		            $newheight = $w/$r;
		            $newwidth = $w;
		        }
		    }
		    $src = imagecreatefromjpeg($file);
		    $dst = imagecreatetruecolor($newwidth, $newheight);
		    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

		    return $dst;
		}

		$itens = \DB::select(" select * from (
select baseitem.agrup as agrupamento, baseitem.modelo as modelo, baseitem.secundario as secundario, Escudero, Escudero2, disponivel as saldo,
/**
case when disponivel<= 50 then disponivel
when disponivel >50 and disponivel<=100 then 'entre 50 e 100'
when disponivel>100 then 'maior que 100' else 0 end as 'saldo',
**/

case 
when anomod in ('2018','2017') then 'X'
when anomod not in ('2018','2017') and  clasmod = 'linha a-' then '§'
when anomod not in ('2018','2017') and clasmod = 'colecao b' then '§§'
when anomod not in ('2018','2017') and clasmod = 'promocional c' then '§§§'
else '§§§' end as 'Status'


, COLMOD, CLASMOD,  
case when tamolho = 'out' then '-'
else tamolho end as 'Tamanho_olho'


, 
case when ean='' then 0
else ean end as 'ean', valortabela, grife, '50%' as desconto


from
( SELECT itens.agrup AS AGRUP, itens.modelo AS MODELO, itens.secundario AS SECUNDARIO, clasmod, colmod, anomod, disponivel-ifnull(orctt,0) as disponivel, ultstatus, grife, tipoitem,
case when disponivel-ifnull(orctt,0) >'25' and grife = 't-charge' AND(( COLMOD <='2017' AND CLASMOD = 'LINHA A-') OR ( CLASMOD IN ('COLECAO B', 'PROMOCIONAL C')))  then '1'
when grife not in ('t-charge') and disponivel-ifnull(orctt,0) > '40' AND(( COLMOD <='2017' AND CLASMOD = 'LINHA A-') OR ( CLASMOD IN ('COLECAO B', 'PROMOCIONAL C')))then  '1' 
else '0' end as 'Escudero',
case when disponivel-ifnull(orctt,0) >'15' and grife = 't-charge' AND(( COLMOD <='2017' AND CLASMOD = 'LINHA A-') OR ( CLASMOD IN ('COLECAO B', 'PROMOCIONAL C')))  then '1'
when grife not in ('t-charge') and disponivel-ifnull(orctt,0) > '20'AND(( COLMOD <='2017' AND CLASMOD = 'LINHA A-') OR ( CLASMOD IN ('COLECAO B', 'PROMOCIONAL C'))) then  '1' 
else '0' end as 'Escudero2'
, itens.tamolho, itens.ean, itens.valortabela

from itens
left join saldos on saldos.secundario = itens.secundario
left join orcamentos on orcamentos.curto = itens.id

where grife in ('ana hickmann', 'atitude', 'bulget', 'hickmann', 't-charge')

and anomod <> '2019'


order by itens.agrup, itens.modelo, itens.secundario desc ) as baseitem


left join (select  AGRUP, modelo,
case when count(escudero2)>=count(escudero) then 'ok'
else 'nao' end as countmod
from(

		SELECT AGRUP, modelo, SECUNDARIO, clasmod, colmod, anomod, disponivel, ultstatus, grife, tipoitem, sum(Escudero) Escudero, sum(Escudero2) Escudero2
		FROM( 

			SELECT itens.agrup AS AGRUP, itens.modelo AS MODELO, itens.secundario AS SECUNDARIO, clasmod, colmod, anomod, disponivel-ifnull(orctt,0) as disponivel, ultstatus, grife, tipoitem,
			case when disponivel-ifnull(orctt,0) >'25' and grife = 't-charge' AND(( COLMOD <='2017' AND CLASMOD = 'LINHA A-') OR ( CLASMOD IN ('COLECAO B', 'PROMOCIONAL C')))  then '1'
			when grife not in ('t-charge') and disponivel-ifnull(orctt,0) > '40' AND(( COLMOD <='2017' AND CLASMOD = 'LINHA A-') OR ( CLASMOD IN ('COLECAO B', 'PROMOCIONAL C')))then  '1' 
			else '0' end as 'Escudero',
			case when disponivel-ifnull(orctt,0) >'15' and grife = 't-charge' AND(( COLMOD <='2017' AND CLASMOD = 'LINHA A-') OR ( CLASMOD IN ('COLECAO B', 'PROMOCIONAL C')))  then '1'
			when grife not in ('t-charge') and disponivel-ifnull(orctt,0) > '20'AND(( COLMOD <='2017' AND CLASMOD = 'LINHA A-') OR ( CLASMOD IN ('COLECAO B', 'PROMOCIONAL C'))) then  '1' 
			else '0' end as 'Escudero2'

			from itens
			left join saldos on saldos.secundario = itens.secundario
			left join orcamentos on orcamentos.curto = itens.id

			where grife in ('ana hickmann', 'atitude', 'bulget', 'hickmann', 't-charge') and anomod <> '2019'

			order by itens.agrup, itens.modelo, itens.secundario desc 
		) AS BASE
/** WHERE escudero+escudero2 > '0' **/

GROUP BY AGRUP, modelo, SECUNDARIO, clasmod, colmod, anomod, disponivel, ultstatus, grife, tipoitem

) as base2

/** WHERE escudero> '0' **/
group by AGRUP, modelo) as basemod on basemod.modelo = baseitem.modelo
where basemod.countmod = 'ok'
/** and escudero2 >0 **/
order by baseitem.agrup, baseitem.modelo, baseitem.secundario ) as fim
where saldo > 20
order by agrupamento asc, secundario desc
");

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$sheet->getColumnDimension('A')->setWidth(20);
		
		 $spreadsheet->getActiveSheet()->setAutoFilter('A1:K1');		
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('E')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('F')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('G')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('H')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('I')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('J')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('K')->setAutoSize(true);

		//$sheet->getColumnDimension('A')->setAutoSize(true);
		//$sheet->getColumnDimension('B')->setAutoSize(true);

		$sheet->setCellValue('A1', 'Picture');
		$sheet->setCellValue('B1', 'SKU');
		$sheet->setCellValue('C1', 'Agrupamento');
		$sheet->setCellValue('D1', 'Marca');
		$sheet->setCellValue('E1', 'Modelo');
		$sheet->setCellValue('F1', 'Tamanho');
		$sheet->setCellValue('G1', 'Status');
		$sheet->setCellValue('H1', 'Saldo');
		$sheet->setCellValue('I1', 'EAN');
		$sheet->setCellValue('J1', 'Tabela R$');
		$sheet->setCellValue('K1', 'Desconto');
		

		$linha = 1;




		foreach ($itens as $item) {


			$foto = app('App\Http\Controllers\ItemController')->consultaFotoThumb($item->secundario);

			//$img = resize_image("/var/www/html/portalgo/public/logogo.png", 40, 40);
			if ($foto != 'fotos/nopicture.jpg') {

				$linha++;

				$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
				$drawing->setName('Paid');
				$drawing->setDescription('Paid');
				$drawing->setPath('/var/www/html/portalgo/public/'.$foto); // put your path and image here
				$drawing->setCoordinates('A'.$linha);
				//$drawing->setOffsetX(110);
				$drawing->setHeight(80);
				$drawing->setWidth(110);
		//		$drawing->setRotation(25);
				$drawing->getShadow()->setVisible(true);
		//		$drawing->getShadow()->setDirection(45);
				$drawing->setWorksheet($spreadsheet->getActiveSheet());

				$spreadsheet->getActiveSheet()->getRowDimension($linha)->setRowHeight(80);

				//$sheet->setCellValue('A'.$linha, '');
				$sheet->setCellValue('B'.$linha, $item->secundario);
				$sheet->setCellValue('C'.$linha, $item->agrupamento);
				$sheet->setCellValue('D'.$linha, $item->grife);
				$sheet->setCellValue('E'.$linha, $item->modelo);
				$sheet->setCellValue('F'.$linha, $item->Tamanho_olho);
				$sheet->setCellValue('G'.$linha, $item->Status);
				$sheet->setCellValue('H'.$linha, $item->saldo);
				$sheet->setCellValue('I'.$linha, $item->ean);
				$sheet->setCellValue('J'.$linha, 'R$'.$item->valortabela);
				$sheet->setCellValue('K'.$linha, $item->desconto);

				
			}
			
				

		}


		$writer = new Xlsx($spreadsheet);
	//	$writer->save('hello world.xlsx');		
		
		$nome = 'orderGO_'.date("Y-m-d").'.xlsx';
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$nome.'"');
		
		header('Cache-Control: max-age=0');

		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
		$writer->save('php://output');



	}
	
	
	public function exportaCatalogoDisponivel() {

		function resize_image($file, $w, $h, $crop=FALSE) {
		    list($width, $height) = getimagesize($file);
		    $r = $width / $height;
		    if ($crop) {
		        if ($width > $height) {
		            $width = ceil($width-($width*abs($r-$w/$h)));
		        } else {
		            $height = ceil($height-($height*abs($r-$w/$h)));
		        }
		        $newwidth = $w;
		        $newheight = $h;
		    } else {
		        if ($w/$h > $r) {
		            $newwidth = $h*$r;
		            $newheight = $h;
		        } else {
		            $newheight = $w/$r;
		            $newwidth = $w;
		        }
		    }
		    $src = imagecreatefromjpeg($file);
		    $dst = imagecreatetruecolor($newwidth, $newheight);
		    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

		    return $dst;
		}

		$itens = \DB::select(" 
			SELECT itens.agrup AS AGRUP, itens.modelo AS MODELO, itens.secundario AS SECUNDARIO, tamolho, tamhaste, tamponte, material, genero, idade, colmod, valortabela

			from itens
			

			where grife in ('ana hickmann', 'atitude', 'bulget', 'hickmann', 't-charge', 'jolie', 'speedo', 'evoke' 'jean marcell') 
            and codstatusatual = 'dis'
			and colmod >= 2015
			and colmod < '2021 06'
			and itens.secundario not like '%semi%'
			 and codtipoarmaz in ('p','m')

			order by itens.agrup, itens.modelo, itens.secundario desc 
		
");
		

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$sheet->getColumnDimension('A')->setWidth(20);
		
		 $spreadsheet->getActiveSheet()->setAutoFilter('B1:M1');		
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('E')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('F')->setAutoSize(true);
		 $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('G')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('H')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('I')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('J')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('K')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('L')->setAutoSize(true);
		$spreadsheet->setActiveSheetIndex(0)->getColumnDimension('M')->setAutoSize(true);
		


		//$sheet->getColumnDimension('A')->setAutoSize(true);
		//$sheet->getColumnDimension('B')->setAutoSize(true);

		$sheet->setCellValue('A1', 'Foto');
		$sheet->setCellValue('B1', 'Agrupamento');
		$sheet->setCellValue('C1', 'Modelo');
		$sheet->setCellValue('D1', 'Secundario');
		$sheet->setCellValue('E1', 'Tamanho Olho');
		$sheet->setCellValue('F1', 'Tamanho Haste');
		$sheet->setCellValue('G1', 'Tamanho Ponte');
		$sheet->setCellValue('H1', 'Material');
		$sheet->setCellValue('I1', 'Gênero');
		$sheet->setCellValue('J1', 'Idade');
		$sheet->setCellValue('K1', 'Lançamento');
		$sheet->setCellValue('L1', 'Valor');
		$sheet->setCellValue('M1', 'Quantidade');
		
		

		

		$linha = 1;




		foreach ($itens as $item) {


			$foto = app('App\Http\Controllers\ItemController')->consultaFotoThumb($item->SECUNDARIO);

			//$img = resize_image("/var/www/html/portalgo/public/logogo.png", 40, 40);
			if ($foto != 'fotos/nopicture.jpg') {

				$linha++;

				$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
				$drawing->setName('Paid');
				$drawing->setDescription('Paid');
				$drawing->setPath('/var/www/html/portalgo/public/'.$foto); // put your path and image here
				$drawing->setCoordinates('A'.$linha);
				//$drawing->setOffsetX(110);
				$drawing->setHeight(80);
				$drawing->setWidth(110);
		//		$drawing->setRotation(25);
				$drawing->getShadow()->setVisible(true);
		//		$drawing->getShadow()->setDirection(45);
				$drawing->setWorksheet($spreadsheet->getActiveSheet());

				$spreadsheet->getActiveSheet()->getRowDimension($linha)->setRowHeight(80);

				//$sheet->setCellValue('A'.$linha, '');
				$sheet->setCellValue('B'.$linha, $item->AGRUP);
				$sheet->setCellValue('C'.$linha, $item->MODELO);
				$sheet->setCellValue('D'.$linha, $item->SECUNDARIO);
				$sheet->setCellValue('E'.$linha, $item->tamolho);
				$sheet->setCellValue('E'.$linha, $item->tamhaste);
				$sheet->setCellValue('G'.$linha, $item->tamponte);
				$sheet->setCellValue('H'.$linha, $item->material);
				$sheet->setCellValue('I'.$linha, $item->genero);
				$sheet->setCellValue('J'.$linha, $item->idade);
				$sheet->setCellValue('K'.$linha, $item->colmod);
				$sheet->setCellValue('L'.$linha, 'R$ '.$item->valortabela);
				$sheet->setCellValue('M'.$linha, '');
				
				

				
			}
			
				

		}


		$writer = new Xlsx($spreadsheet);
	//	$writer->save('hello world.xlsx');		
		
		$nome = 'orderGO_'.date("Y-m-d").'.xlsx';
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$nome.'"');
		
		header('Cache-Control: max-age=0');

		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
		$writer->save('php://output');



	}

	public function exportaCatalogoPadrao(Request $request, $tipo) {
		$id_usuario = \Auth::id();


		$mpdf = new \Mpdf\Mpdf();

		$colecoes = \App\Permissao::getPermissao( \Auth::id() , 'colecoes');
		$cat ="";
		
		$fornecedor = \DB::select("select fornecedor, grife from itens
									
									where codagrup = '$request->agrup'");
		
		
		
		if ($tipo =='imediato'){
				$codstatusatual = "('DIS','DISP')"; }
		
		elseif ($tipo =='lancamento'){
				$codstatusatual = "('DIS','15D','30D','DISP')"; }
		elseif ($tipo =='prime_lancamento'){
				$codstatusatual = "('DIS','15D','30D','DISP')"; }
		
		elseif ($tipo =='linha'){
				$codstatusatual = "('DIS','15D','30D','DISP')"; }
		elseif ($tipo =='essenciais'){
				$codstatusatual = "('DIS','15D','30D','DISP')"; }
		elseif ($tipo =='oportunidade'){
				$codstatusatual = "('DIS','DISP')"; }
		elseif ($tipo =='prime_imediato'){
				$codstatusatual = "	('DIS','DISP')"; }
		
		
			
		
		
		if (($fornecedor[0]->grife== 'ALEXANDER MCQUEEN' or $fornecedor[0]->grife=='ALTUZARRA' or $fornecedor[0]->grife=='BOTTEGA VENETA' or $fornecedor[0]->grife=='BOUCHERON' or $fornecedor[0]->grife=='BRIONI' or $fornecedor[0]->grife=='CARTIER' or $fornecedor[0]->grife=='CHRISTOPHER KANE' or $fornecedor[0]->grife=='MCQ' or $fornecedor[0]->grife=='POMELLATO' or $fornecedor[0]->grife=='STELLA MCCARTNEY' or $fornecedor[0]->grife=='TOMAS MAIER' or $fornecedor[0]->grife== 'AZZEDINE' or $fornecedor[0]->grife== 'CHLOE' or $fornecedor[0]->grife=='DUNHILL') && $tipo =='lancamento' ){
			$filtrolancamento = "and (colmod in ('2021 04','2021 05','2021 03','2021 01','2021 07','2021 08') or colitem in ('2021 04','2021 05','2021 03','2021 01','2021 07','2021 08','2021 09','2021 10'))";
			
		}
		elseif ($tipo =='lancamento' ){
				$filtrolancamento = "and (colmod in ('2021 04','2021 05','2021 03','2021 02','2021 01','2021 06','2021 07','2021 08') or colitem in ('2021 04','2021 05','2021 03','2021 02','2021 01','2021 06','2021 07','2021 08','2021 09','2021 10'))"; }
		
		elseif ($tipo =='prime_lancamento' ){
				$filtrolancamento = "and (colmod in ('2021 04','2021 05','2021 03','2021 02','2021 01','2021 06','2021 07','2021 08') or colitem in ('2021 04','2021 05','2021 03','2021 02','2021 01','2021 06','2021 07','2021 08','2021 09','2021 10'))"; }
		
		else {
				$filtrolancamento = ""; }
		
		
		
		if ($tipo =='imediato' and $fornecedor[0]->fornecedor == 'KERING EYEWEAR SPA'){
				$clasmod = ''; }
		
		elseif ($tipo =='imediato' and $fornecedor[0]->fornecedor <> 'KERING EYEWEAR SPA'){
				$clasmod = " and (clasmod in ('linha a-', 'linha a', 'linha a++', 'linha a+', 'novo') or (clasmod in ('colecao b') and left(colmod,4) >= '2018'))"; }
		
		elseif ($tipo =='prime_imediato' and $fornecedor[0]->fornecedor <> 'KERING EYEWEAR SPA'){
				$clasmod = " and (clasmod in ('linha a-', 'linha a', 'linha a++', 'linha a+', 'novo') or (clasmod in ('colecao b','promocional_c') and left(colmod,4) >= '2017'))"; }
		
		elseif ($tipo =='lancamento' and $fornecedor[0]->fornecedor <> 'KERING EYEWEAR SPA'){
				$clasmod = "and clasmod in ('linha a-', 'linha a', 'linha a++', 'linha a+', 'novo')"; }
		
		elseif ($tipo =='prime_lancamento' and $fornecedor[0]->fornecedor <> 'KERING EYEWEAR SPA'){
				$clasmod = "and clasmod in ('linha a-', 'linha a', 'linha a++', 'linha a+', 'novo')"; }
		
		elseif ($tipo =='linha' and $fornecedor[0]->fornecedor <> 'KERING EYEWEAR SPA'){
				$clasmod = "and clasmod like 'LINHA A%'"; }
		
		elseif ($tipo =='essenciais' and $fornecedor[0]->fornecedor <> 'KERING EYEWEAR SPA'){
				$clasmod = "and clasmod in ('linha a++', 'linha a+')"; }
		
		elseif ($tipo =='oportunidade' and $fornecedor[0]->fornecedor <> 'KERING EYEWEAR SPA'){
				$clasmod = "and clasmod in ('colecao b', 'promocional c')"; }
		
		else {	$clasmod = "and clasmod in ('linha a-', 'linha a', 'linha a++', 'linha a+','colecao B','promocional c', 'NOVO')"; }
		

		if ($tipo == 'top10') {

			$catalogo = \DB::select("select * from (
							select itens.modelo, sum(qtde) qtde
									from vendas_jde as vendas
									left join itens on vendas.id_item = itens.id
									left join saldos on saldos.curto = itens.id
									
									where itens.id is not null 
									and datediff(now(), dt_venda)  <= 30
                                    
									and ult_status not in ('980','984')  and itens.codagrup = '$request->agrup' and disp_vendas > 20
									and itens.secundario not like '%semi%'
									and codtipoarmaz in ('p','m')
                            group by itens.modelo
                            order by qtde desc limit 10
			) as base
                            
			
            left join (select modelo, itens.secundario, disp_vendas  ,tamolho,tamhaste,tamponte, agrup, valortabela
							from itens left join saldos on saldos.curto = itens.id where disp_vendas > 10 and itens.secundario not like '%semi%') as itens
            on itens.modelo = base.modelo");

		} 
		
		elseif ($tipo == 'prime_imediato') {

			$catalogo = \DB::select("select itens.* from itens
						left join saldos on saldos.curto = itens.id
									where codagrup = '$request->agrup'
									
									  $clasmod
									  $filtrolancamento
									  and codstatusatual in $codstatusatual
									and disp_vendas >10
									and codstatusatual not in ('ESGOT', 'PROD') 
									and itens.secundario not like '%semi%'
									and codtipoarmaz in ('p','m')
									order by itens.secundario asc");

		} 
		
		elseif ($tipo == 'prime_lancamento') {

			$catalogo = \DB::select("select * from itens
						left join saldos on saldos.curto = itens.id
									where codagrup = '$request->agrup'
									
									  $clasmod
									  $filtrolancamento
									  and codstatusatual in $codstatusatual
									and disp_vendas >10
									and codstatusatual not in ('ESGOT', 'PROD') 
									and itens.secundario not like '%semi%'
									and codtipoarmaz in ('p','m')
									order by itens.secundario asc");

		} 
		
		elseif ($tipo == 'musthave'){
			$catalogo = \DB::select("
				select itens.agrup, REPLACE(itens.secundario, 'EVOKE FOR YOU', 'FOR YOU') secundario2, itens.secundario, itens.modelo, itens.tamolho, tamponte, tamhaste, valortabela, genero, statusatual
				from essenciais_itens ei left join itens on ei.secundario = itens.secundario where itens.codagrup = '$request->agrup'
				
				order by genero, modelo
				");
			
		}
		
		
elseif ($tipo == 'musthave1'){
			$catalogo = \DB::select("
				
select itens.agrup, REPLACE(itens.secundario, 'EVOKE FOR YOU', 'FOR YOU') secundario2, itens.secundario, itens.modelo, itens.tamolho, tamponte, tamhaste, valortabela, genero, statusatual
from itens left join saldos on itens.id = saldos.curto where itens.secundario 
in ('GG0763S-005',
'GG0803S-005',
'GG0833O-001',
'GG0833O-002',
'GG0833O-003',
'GG0875S-001',
'GG0875S-002',
'GG0875S-003',
'GG0876S-001',
'GG0876S-002',
'GG0876S-003',
'GG0876S-004',
'GG0877S-001',
'GG0877S-002',
'GG0877S-004',
'GG0878S-001',
'GG0878S-002',
'GG0878S-003',
'GG0878S-004',
'GG0879S-001',
'GG0879S-002',
'GG0879S-003',
'GG0879S-004',
'GG0880O-004',
'GG0880O-005',
'GG0881SA-001',
'GG0881SA-002',
'GG0881SA-003',
'GG0881SA-004',
'GG0883OA-001',
'GG0883OA-002',
'GG0883OA-003',
'GG0885SA-001',
'GG0885SA-002',
'GG0885SA-004',
'GG0889S-001',
'GG0890O-001',
'GG0890O-002',
'GG0890O-003',
'GG0890S-001',
'GG0890S-002',
'GG0894S-001',
'GG0894S-003',
'GG0895S-001',
'GG0895S-002',
'GG0895S-003',
'GG0895S-004',
'GG0896S-001',
'GG0896S-002',
'GG0896S-004',
'GG0900S-001',
'GG0900S-002',
'GG0900S-005',
'GG0903S-001',
'GG0903S-002',
'GG0904S-001',
'GG0904S-002',
'GG0904S-003',
'GG0905S-001',
'GG0905S-003',
'GG0908S-001',
'GG0908S-002',
'GG0911S-001',
'GG0911S-002',
'GG0911S-003',
'GG0912S-001',
'GG0912S-002',
'GG0912S-003',
'GG0914O-001',
'GG0914O-002',
'GG0914O-003',
'GG0917S-001',
'GG0917S-002',
'GG0918S-001',
'GG0918S-002',
'GG0918S-003',
'GG0919O-001',
'GG0919O-002',
'GG0919O-003',
'GG0920O-004',
'GG0920O-005',
'GG0921S-001',
'GG0921S-002',
'GG0921S-004',
'GG0922O-005',
'GG0922O-006',
'GG0922O-007',
'GG0923O-001',
'GG0923O-002',
'GG0923O-003',
'GG0923O-004',
'GG0926S-001',
'GG0926S-002',
'GG0926S-003',
'GG0926S-005',
'GG0934OA-001',
'GG0934OA-003',
'GG0941S-001',
'GG0941S-002',
'GG0941S-003',
'GG0946OA-002',
'GG0946OA-003',
'GG0951O-001',
'GG0951O-002',
'GG0951O-003',
'GG0952O-001',
'GG0952O-002',
'GG0952O-003',
'GG0953S-001',
'GG0954S-001',
'GG0954S-002',
'GG0954S-003',
'GG0954S-004',
'GG0954S-005',
'GG0957S-001',
'GG0957S-002',
'GG0959O-001',
'GG0959O-002',
'GG0959O-003',
'GG0963O-001',
'GG0963O-002',
'GG0963O-003',
'GG0970S-001',
'GG0970S-002',
'GG0972S-001',
'GG0972S-002',
'GG0973O-001',
'GG0973O-002',
'GG0977S-001',
'GG0977S-002',
'GG0978S-001',
'GG0978S-004',


'MB0146O-004',
'MB0146O-005',
'MB0146O-006',
'MB0148O-001',
'MB0148O-002',
'MB0148O-003',
'MB0152O-005',
'MB0152O-006',
'MB0152O-007',
'MB0155O-001',
'MB0155O-002',
'MB0157SA-001',
'MB0157SA-002',
'MB0157SA-004',
'MB0159O-001',
'MB0159O-002',
'MB0160S-005',
'MB0161O-001',
'MB0161O-002',
'MB0161O-003',
'MB0169O-001',
'MB0169O-002',
'MB0169O-003',


'SL 301 LOULOU OPT-002',
'SL 301 LOULOU OPT-003',
'SL 425-002',
'SL 425-003',
'SL 425-005',
'SL 429-002',
'SL M79-001',
'SL M79-002',
'SL M79-003',
'SL M80-001',
'SL M80-002',
'SL M80-003',
'SL M81-001',
'SL M81-002',
'SL M81-004')
order by genero, modelo



				");
			
		}

		
		
		elseif ($tipo == 'Pro'){
			$catalogo = \DB::select("
				select itens.agrup, itens.secundario, itens.modelo, itens.tamolho, tamponte, tamhaste, valortabela, genero, statusatual
				from itens where secundario like '%pro%' and agrup like 'sp01%'
				order by genero, modelo
				");
			
		}
		
		
		else  {


		//$barrarcolecao = "and colmod < '2020 02'"	;
			
			$catalogo = \DB::select("select * from itens
									where codagrup = '$request->agrup'
									
									  $clasmod
									  $filtrolancamento
									  and codstatusatual in $codstatusatual
									
									and codstatusatual not in ('ESGOT', 'PROD') 
									-- and (colmod in ('2021 04','2021 05','2021 03') or colitem in ('2021 04','2021 05','2021 03','2021 06'))
									-- and colitem <= '2021 05'
									and secundario not like '%semi%'
									and codtipoarmaz in ('p','m')
									order by secundario asc");
			
			
		}	
		if (!$catalogo) {
			$request->session()->flash("alert-warning", "Nenhum item encontrado");
			return redirect()->back();
		}
		
		
		$stylesheet = '	.row {
							clear: both;
							width: 100%;
						}
						.col-md-3 {
							width: 25%;
						}';
		
		$stylesheet = file_get_contents(asset('/css/template.css'));


		$html = '<div id="container">
					<div id="title">

						<h1><img src="/img/logogo.png" width="100" /> </h1>

					</div>

					<div id="description">

		  				<p> <span class="date">'.strtoupper($tipo).'</span></p>

					</div>	
					<div id="itens">';

		foreach ($catalogo as $modelo) {
			if ($modelo->tamolho==''){$tamolho = '  -  ';}
								 else $tamolho = $modelo->tamolho;
				if ($modelo->tamhaste==''){$tamhaste = '  -  ';}
								 else $tamhaste = $modelo->tamhaste;
				if ($modelo->tamponte==''){$tamponte = '  -  ';}
								 else $tamponte = $modelo->tamponte;

			$foto_baixa1 	= 'fotos/BAIXA/'.trim($modelo->agrup).'/'.trim($modelo->secundario).'.jpg';
			$foto_baixa2 	= 'fotos/BAIXA/'.trim($modelo->agrup).'/'.trim($modelo->secundario).'.JPG';
			
			$foto_alta1 	= 'fotos/ALTA/'.trim($modelo->agrup).'/'.trim($modelo->secundario).'.jpg';
			$foto_alta2 	= 'fotos/ALTA/'.trim($modelo->agrup).'/'.trim($modelo->secundario).'.JPG';

			
		
			
			if (file_exists($foto_baixa1) or file_exists($foto_baixa2) or file_exists($foto_alta1) or file_exists($foto_alta2)) {

				
								
			if ($id_usuario==573 or $id_usuario==314 or $id_usuario==316 or $id_usuario==472 or $id_usuario==474
				or $id_usuario==475 or $id_usuario==528 or $id_usuario==332 or $id_usuario==337 or $id_usuario==352
				or $id_usuario==359 or $id_usuario==392 or $id_usuario==417 or $id_usuario==429 or $id_usuario==447
				or $id_usuario==542 or $id_usuario==607 or $id_usuario==581){
			$html .= '<div class="thumbnail"><a class="thumbnailhover" style="color:black important;" ><img src="https://portal.goeyewear.com.br/teste999.php?referencia='.$modelo->secundario.'"> '.$modelo->secundario.'<br><small> Valor: R$'.number_format($modelo->valortabela,2).'<br> Olho / Ponte / Haste <br>'.$tamolho.'    /    '.$tamponte.'    /    '.$tamhaste.' </a></small></div>';
			}
			
			
				
			elseif ($tipo == 'Essenciais'){
			$html .= '<div class="thumbnail"><a class="thumbnailhover" style="color:black important;" >
			<img src="https://portal.goeyewear.com.br/teste999.php?referencia='.$modelo->secundario.'"> 
			'.$modelo->secundario2.'<br>
			<small> 
			Valor: R$'.number_format($modelo->valortabela,2).'<br> 
			'.$modelo->statusatual.'  <br>
			'.$tamolho.'    /    '.$tamponte.'    /    '.$tamhaste.' 
			</a></small></div>';
			}
			
			
			else
			{

			$html .= '<div class="thumbnail"><a class="thumbnailhover" style="color:black important;" ><img src="https://portal.goeyewear.com.br/teste999.php?referencia='.$modelo->secundario.'"> 
			'.$modelo->secundario.'<br> 
			Olho / Haste / Ponte <br>
			'.$tamolho.'    /    '.$tamponte.'    /    '.$tamhaste.' </a></small></div>';
		}
		}
			}
		$html .= '</div>
					</div>';

		$rodape = '<div align="center">
						<small align="center"><b>Gerado por:</b> '.\Auth::user()->nome.' <b>em</b> '.date("d/m/Y H:i:s").'</small>
					</div>';

		// Write some HTML code:
		$mpdf->SetHTMLFooter($rodape);
		$mpdf->WriteHTML($stylesheet,1);
		$mpdf->WriteHTML($html,2);
		
		$fileName = $modelo->agrup." - ".strtoupper($tipo)." - ".date("d/m/Y");
		// Output a PDF file directly to the browser
		$mpdf->Output($fileName.".pdf","I");		

	}

}
