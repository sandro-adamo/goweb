<?php

namespace App\ Http\ Controllers;

use Illuminate\ Http\ Request;
use App\ Compra;
use PhpOffice\ PhpSpreadsheet\ Spreadsheet;
use PhpOffice\ PhpSpreadsheet\ Writer\ Xlsx;
use PHPMailer\ PHPMailer\ PHPMailer;
use PHPMailer\ PHPMailer\ Exception;
use App\PortfolioItem;

class CompraController extends Controller {

	public function gravaParcelasCompras(Request $request) {
    $idusuario = \Auth::id();
    $usuario  = \DB::select("select nome from usuarios where id = $idusuario limit 1");
    $nome_usuario = $usuario[0]->nome;
    
    


    if($request->confirma1=='on'){
      
      if($request->pagamento1<>''){
        $pagamento1 = ",'".$request->pagamento1."'";
        $pagamento_insert = ", `pagamento`";
      }else{
        $pagamento1 = '';
        $pagamento_insert = '';
      }
     
    $insert_parcela  = \DB::select("INSERT INTO `compras_parcelas`( `id_titulo`, `numero`, `tipo`, `valor`, `moeda`, `vencimento`, `emissao`, `user`, `obs`, `proforma`, `id_fornecedor`$pagamento_insert) VALUES ('$request->id_titulo','$request->documento1','$request->tipo1','$request->valor1','$request->moeda','$request->vencimento1', current_date,'$nome_usuario','$request->obs1','$request->proforma1','$request->id_fornecedor1'$pagamento1 ) ");

    }
    
    if($request->confirma2=='on'){
      if($request->pagamento2<>''){
        $pagamento2 = ",'".$request->pagamento2."'";
        $pagamento_insert = ", `pagamento`";
      }else{
        $pagamento2 = '';
        $pagamento_insert = '';
      }
      $insert_parcela  = \DB::select("INSERT INTO `compras_parcelas`( `id_titulo`, `numero`, `tipo`, `valor`, `moeda`, `vencimento`, `emissao`, `user`, `obs`, `proforma`, `id_fornecedor`$pagamento_insert) VALUES ('$request->id_titulo','$request->documento2','$request->tipo2','$request->valor2','$request->moeda','$request->vencimento2', current_date,'$nome_usuario','$request->obs2','$request->proforma2','$request->id_fornecedor2'$pagamento2) ");
      }

    if($request->confirma3=='on'){
      if($request->pagamento3<>''){
        $pagamento3 = ",'".$request->pagamento3."'";
        $pagamento_insert = ", `pagamento`";
      }else{
        $pagamento3 = '';
        $pagamento_insert = '';
      }
        $insert_parcela  = \DB::select("INSERT INTO `compras_parcelas`( `id_titulo`, `numero`, `tipo`, `valor`, `moeda`, `vencimento`, `emissao`, `user`, `obs`, `proforma`, `id_fornecedor`$pagamento_insert) VALUES ('$request->id_titulo','$request->documento3','$request->tipo3','$request->valor3','$request->moeda','$request->vencimento3', current_date,'$nome_usuario','$request->obs3','$request->proforma3','$request->id_fornecedor3'$pagamento3) ");
        }
    if($request->confirma4=='on'){
      if($request->pagamento4<>''){
        $pagamento4 = ",'".$request->pagamento4."'";
        $pagamento_insert = ", `pagamento`";
      }else{
        $pagamento4 = '';
        $pagamento_insert = '';
      }
          $insert_parcela  = \DB::select("INSERT INTO `compras_parcelas`( `id_titulo`, `numero`, `tipo`, `valor`, `moeda`, `vencimento`, `emissao`, `user`, `obs`, `proforma`, `id_fornecedor`$pagamento_insert) VALUES ('$request->id_titulo','$request->documento4','$request->tipo4','$request->valor4','$request->moeda','$request->vencimento4', current_date,'$nome_usuario','$request->obs4','$request->proforma4','$request->id_fornecedor4'$pagamento4) ");
          }
    if($request->confirma5=='on'){
      if($request->pagamento5<>''){
        $pagamento5 = ",'".$request->pagamento5."'";
        $pagamento_insert = ", `pagamento`";
      }else{
        $pagamento5 = '';
        $pagamento_insert = '';
      }
            $insert_parcela  = \DB::select("INSERT INTO `compras_parcelas`( `id_titulo`, `numero`, `tipo`, `valor`, `moeda`, `vencimento`, `emissao`, `user`, `obs`, `proforma`, `id_fornecedor`$pagamento_insert) VALUES ('$request->id_titulo','$request->documento5','$request->tipo5','$request->valor5','$request->moeda','$request->vencimento5', current_date,'$nome_usuario','$request->obs5','$request->proforma5','$request->id_fornecedor5'$pagamento5) ");
            }
    $detalhes  = \DB::select("select * from compras_parcelas where id_titulo = '$request->id_titulo' ");
    return redirect()->back();

  }
	
	
		public function gravaTitulosCompras(Request $request) {
     
      $numero_titulo = $request->id_compra."_1";
      $idusuario = \Auth::id();
      $usuario  = \DB::select("select nome from usuarios where id = $idusuario limit 1");
      $nome_usuario = $usuario[0]->nome;
      
				
				

      $verifica_adiantamento  = \DB::select("select id, descricao, case when perc_adiantamento is null then 'NULL' ELSE perc_adiantamento END AS 'perc_adiantamento' from compras_condicoes where id = '$request->condicao_pagamento' ");

      if($verifica_adiantamento[0]->perc_adiantamento<>"null" or $verifica_adiantamento[0]->perc_adiantamento<>"0"){

        $percentual = (float)$verifica_adiantamento[0]->perc_adiantamento;
        $valortt = (float)$request->valor_total;
        $valor_adiantamento = ($percentual/100)*$valortt;
        $id_condicao = $verifica_adiantamento[0]->id;
        $desc_condicao = $verifica_adiantamento[0]->descricao;
        

        $insert_adiantamento  = \DB::select("INSERT INTO `compras_titulos`(`id_pedido`, `origem`, `numero`, `tipo`, `valor`, `moeda`, `vencimento`, `emissao`, `user`, `obs`) VALUES ('$request->id_compra', 'COMPRAS','$numero_titulo', 'ADIANTAMENTO', '$valor_adiantamento','$request->moeda', '$request->dt_vencimento', '$request->dt_emissao','$nome_usuario','$request->obs') ");

        $update_capa  = \DB::select("update compras set valor_total = '$valortt', id_condicao_pagamento = '$id_condicao', condicao_pagamento = '$desc_condicao' where id = '$request->id_compra' ");


        //dd($valor_adiantamento);
      }
      else{
        $update_capa  = \DB::select("update compras set valor_total = '$valortt', id_condicao_pagamento = '$id_condicao', condicao_pagamento = '$desc_condicao' where id = '$request->id_compra' ");
        //dd($verifica_adiantamento[0]->perc_adiantamento);
      }
      
      //dd($request); 
				
			
			return redirect()->back();
		}
	
	


  public function UploadArquivos( Request $request ) {

    $agora = date( 'd_m_Y_H_i' );
    
    $id_compras = $request->id_pedido;
    $id_compras = $request->id_pedido;
    $nome = $request->nome;
    $obs = $request->obs;
    $data = $request->data;
    $arquivo = '/storage/uploads/compras/arquivos/' . $agora . '_' . $request->arquivo->getClientOriginalName();
    $tipo = $request->tipo;
    $usuario = \Auth::id();
    

    $uploaddir = '/var/www/html/portal-gestao/storage/app/uploads/compras/arquivos/' . $agora . '_';

    $uploadfile = $uploaddir . basename( $_FILES[ 'arquivo' ][ 'name' ] );
    $arquivo1 = '/storage'.substr($uploadfile, 39);
   

    $erros = array();

    if ( move_uploaded_file( $_FILES[ 'arquivo' ][ 'tmp_name' ], $uploadfile ) ) {} else {
      dd( 'erro' );
    }


    $insere_arquivo = \DB::select( "INSERT INTO `compras_arquivos`( `id_compra`, `tipo`, `arquivo`, `nome`, `obs`, `exclui`, `data`, `usuario`) VALUES ('{$id_compras}','{$tipo}','{$arquivo1}','{$nome}','{$obs}','0','{$data}','$usuario')" );
	  
	  

    return redirect()->back();
  }

  public function UploadEditaEntregas( $id_compra, Request $request ) {
    ini_set( 'memory_limit', -1 );

    $agora = date( '_d_m_Y_H_i' );
	
	$idcompra = $id_compra; 
    $uploaddir1 = '/var/www/html/portal-gestao';
	$uploaddir2 = '/storage/';
	$uploaddir3 = 'app/';
	$uploaddir4 = 'uploads/compras/edita_entrega/edita_entrega_pedido_' . $id_compra . '_' . $agora;
    $uploadfile = $uploaddir1.$uploaddir2.$uploaddir3.$uploaddir4 . '.Xlsx';
	  
	 
	  
	
	

    if ( move_uploaded_file( $_FILES[ 'arquivo' ][ 'tmp_name' ], $uploadfile ) ) {
			
		 

      if ( file_exists( $uploadfile ) ) {
		  
		  $cod_usuario = \Auth::id();
		$uploadfilearquivo = $uploaddir2.$uploaddir4 . '.Xlsx';
		  $inserearquivos = \DB::select( "INSERT INTO `compras_arquivos`( `id_compra`, `tipo`, `arquivo`, `nome`, `obs`, `data`, `usuario`, `exclui`) VALUES ('$idcompra','REDISTRIBUIÇÃO','$uploadfilearquivo','Redistribuição de data', '',CURRENT_DATE,'$cod_usuario','0')");

        $startRow = 0;
        $endRow = 0;

        $reader = \PhpOffice\ PhpSpreadsheet\ IOFactory::createReader( "Xlsx" );

        $spreadsheet = $reader->load( $uploadfile );

        $sheet = $spreadsheet->getActiveSheet()->toArray();


        $i = 0;
		  

        foreach ( $sheet as $linha ) {

          if ( $i > 3 ) {
            $id_entrega = $linha[ 0 ];
            $item = $linha[ 1 ];
            $qtd_pedido = $linha[ 2 ];
            $dt_original = $linha[ 4 ];
            $acao = $linha[ 6 ];
            $nova_data = $linha[ 7 ];
            $obs_nova_data = $linha[ 8 ];
            $qtd_redistribuir = $linha[ 9 ];
            $qtd_datas_redistribuir = $linha[ 10 ];
            $redistribuir_qtd1 = $linha[ 11 ];
            $redistribuiar_data1 = $linha[ 12 ];
            $redistribuir_obs1 = $linha[ 13 ];
            $redistribuir_qtd2 = $linha[ 14 ];
            $redistribuiar_data2 = $linha[ 15 ];
            $redistribuir_obs2 = $linha[ 16 ];
            $redistribuir_qtd3 = $linha[ 17 ];
            $redistribuiar_data3 = $linha[ 18 ];
            $redistribuir_obs3 = $linha[ 19 ];
			
			  
			 $id_compra = \DB::select( "select id_compra 
				from compras_itens 
				left join compras_entregas on compras_entregas.id_compra_item = compras_itens.id where compras_entregas.id = '$id_entrega' " );
			  
			 // dd( $id_compra);
			  
            if ( $acao == 'MANTER'
              or $acao == '' ) {
              //echo $linha[ 0].$linha[ 1].' MANTER</br>';
            }

            if ( $acao == 'AJUSTAR_DATA' ) {
              $obs = 'Data alterada de ' . $dt_original . ' para ' . $nova_data . ' Motivo: ' . $obs_nova_data;

              $grava_historico = \DB::select( "INSERT INTO `compras_entregas_datas`(`id_entregas`, `data_antiga`, `data_nova`, `obs`) VALUES ('$id_entrega', '$dt_original', '$nova_data','$obs_nova_data')" );

              $atualiza_data_entregas = \DB::select( "UPDATE `compras_entregas` SET dt_confirmada = '$nova_data', obs = '$obs' where id = '$id_entrega'" );
				
			
              //echo $linha[ 0].$linha[ 1].$linha[ 6].'</br>';
            }
            if ( $acao == 'REDISTRIBUIR' ) {
              if ( ( $redistribuir_qtd1 + $redistribuir_qtd2 + $redistribuir_qtd3 ) == $qtd_redistribuir ) {
                $dados_gerar_entrega = \DB::select( "select*
				from compras_entregas
				where id = $id_entrega
				" );
                echo $dados_gerar_entrega[ 0 ]->id;

                if ( $qtd_datas_redistribuir == 1 or $qtd_datas_redistribuir == 2 or $qtd_datas_redistribuir == 3 ) {

                  $msg_linha_original = 'Quantidade de ' . $qtd_redistribuir . ' redistribuida para outras entregas.';
                  $atualiza_data_entregas = \DB::select( "UPDATE `compras_entregas` SET qtde_entrega = qtde_entrega-$qtd_redistribuir, dt_alterada = 1, obs = '$msg_linha_original' where id = '$id_entrega'" );

                  $entrega = new\ App\ CompraEntrega();
                  $entrega->id_compra_item = $dados_gerar_entrega[ 0 ]->id_compra_item;
                  $entrega->id_usuario = \Auth::id();
                  $entrega->tipo = 'confirmacao';
                  $entrega->dt_entrega = $dt_original;
                  $entrega->dt_confirmada = $redistribuiar_data1;
				$entrega->qtde_confirmada =  $qtd_pedido;
                  $entrega->qtde_entrega = $redistribuir_qtd1;
                  $entrega->linha_original = $id_entrega;
                  $entrega->qtd_entregue = 0;
                  $entrega->obs = 'Pedido redistribuido da linha ' . $id_entrega . ' motivo_alteracao ' . $redistribuir_obs1;
                  $entrega->save();


                  echo $linha[ 0 ] . $linha[ 1 ] . $linha[ 6 ] . '</br>';
                }
                if ( $qtd_datas_redistribuir == 2 or $qtd_datas_redistribuir == 3 ) {


                  $entrega = new\ App\ CompraEntrega();
                  $entrega->id_compra_item = $dados_gerar_entrega[ 0 ]->id_compra_item;
                  $entrega->id_usuario = \Auth::id();
                  $entrega->tipo = 'confirmacao';
                  $entrega->dt_entrega = $dt_original;
                  $entrega->dt_confirmada = $redistribuiar_data2;
					$entrega->qtde_confirmada =  $qtd_pedido;
                  $entrega->qtde_entrega = $redistribuir_qtd2;
                  $entrega->qtde_confirmada = $qtd_pedido;
                  $entrega->linha_original = $id_entrega;
                  $entrega->qtd_entregue = 0;
                  $entrega->obs = 'pedido redistribuido da linha ' . $id_entrega . ' motivo_alteracao ' . $redistribuir_obs2;
                  $entrega->save();


                  echo $linha[ 0 ] . $linha[ 1 ] . $linha[ 6 ] . '</br>';
                }
                if ( $qtd_datas_redistribuir == 3 ) {


                  $entrega = new\ App\ CompraEntrega();
                  $entrega->id_compra_item = $dados_gerar_entrega[ 0 ]->id_compra_item;
                  $entrega->id_usuario = \Auth::id();
                  $entrega->tipo = 'confirmacao';
                  $entrega->dt_entrega = $dt_original;
                  $entrega->dt_confirmada = $redistribuiar_data3;
					$entrega->qtde_confirmada =  $qtd_pedido;
                  $entrega->qtde_entrega = $redistribuir_qtd3;
                  $entrega->linha_original = $id_entrega;
                  $entrega->qtde_confirmada = $qtd_pedido;
                  $entrega->qtd_entregue = 0;
                  $entrega->obs = 'pedido redistribuido da linha ' . $id_entrega . ' motivo_alteracao ' . $redistribuir_obs3;
                  $entrega->save();


                  echo $linha[ 0 ] . $linha[ 1 ] . $linha[ 6 ] . '</br>';
                }
              } else {
                $request->session()->flash( 'alert-warning', 'Quantidade distribuida do item <b>' . $item . '</b> diferente do saldo disponivel.' );

              }
            }


          }


          $i++;

        }

      }
    }
	  
	 
	 
    return redirect()->back();

  }
  public function DownloadEditaEntregas( $id_compra ) {

    $entregas = \DB::select( "	select compras_entregas.id as id_linha_entregas,item,  qtde_confirmada as qtd_pedido, qtde_entrega, dt_confirmada, 
	qtd_entregue, qtde_entrega-ifnull(qtd_entregue,0) as saldo_redistribuir,
	razao as fornecedor,
	razao, endereco, municipio, uf, pais ,
	dt_emissao,pagamento,transporte,
	compras.obs as obs,
	compras.id as id_compra
	from compras_itens
	left join compras_entregas on compras_entregas.id_compra_item = compras_itens.id
	left join compras on compras_itens.id_compra = compras.id
	left join addressbook on addressbook.id = compras.id_fornecedor
	where compras_entregas.exclui is null
	and compras_itens.status in ('distribuido', 'producao', 'aguardando documentacao')
	and compras_entregas.id is not null
	and id_compra = '$id_compra'
	 and qtde_entrega-ifnull(qtd_entregue,0) >0

	order by compras_itens.item, compras_itens.id desc " );


    //dd( $entregas );

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();


    $spreadsheet->getActiveSheet()->getColumnDimension( 'A' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'B' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'C' )->setWidth( 20 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'D' )->setWidth( 20 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'E' )->setWidth( 20 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'F' )->setWidth( 15 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'G' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'H' )->setWidth( 15 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'I' )->setWidth( 20 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'J' )->setWidth( 25 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'K' )->setWidth( 30 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'L' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'M' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'N' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'O' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'P' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'Q' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'R' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'S' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'T' )->setWidth( 35 );


    $drawing = new\ PhpOffice\ PhpSpreadsheet\ Worksheet\ Drawing();
    $drawing->setName( 'Paid' );
    $drawing->setDescription( 'Paid' );
    $drawing->setPath( '/var/www/html/portal-gestao/public/img/logogo.png' ); // put your path and image here
    $drawing->setCoordinates( 'A1' );
    //$drawing->setOffsetX(110);
    $drawing->setHeight( 100 );
    $drawing->setWidth( 130 );
    //		$drawing->setRotation(25);
    $drawing->getShadow()->setVisible( true );
    //		$drawing->getShadow()->setDirection(45);
    $drawing->setWorksheet( $spreadsheet->getActiveSheet() );

    $spreadsheet->getActiveSheet()->getRowDimension( 1 )->setRowHeight( 100 );

    $spreadsheet->getActiveSheet()->getRowDimension( 4 )->setRowHeight( 25 );
    $spreadsheet->getActiveSheet()->getStyle( 'A4:AA4' )->getFont()->setBold( true );
    $spreadsheet->getActiveSheet()->getStyle( 'A4:AA4' )->getFill()->setFillType( \PhpOffice\ PhpSpreadsheet\ Style\ Fill::FILL_SOLID );


    $spreadsheet->getActiveSheet()->getStyle( 'A4:F4' )->getFill()->getStartColor()->setARGB( 'fef271' );
    $spreadsheet->getActiveSheet()->getStyle( 'J4' )->getFill()->getStartColor()->setARGB( 'fef271' );
    $spreadsheet->getActiveSheet()->getStyle( 'G4' )->getFill()->getStartColor()->setARGB( 'C0C0C0' );
    $spreadsheet->getActiveSheet()->getStyle( 'L4:T4' )->getFill()->getStartColor()->setARGB( '6495ED' );


    $spreadsheet->getActiveSheet()->getStyle( 'A4:K4' )->getFill()->getStartColor()->setARGB( '004c98' );
    $spreadsheet->getActiveSheet()->getStyle( 'A4:K4' )->getFont()->getColor()->setARGB( \PhpOffice\ PhpSpreadsheet\ Style\ Color::COLOR_WHITE );

    $spreadsheet->getActiveSheet()->getStyle( 'A1:Z1' )->getFill()->setFillType( \PhpOffice\ PhpSpreadsheet\ Style\ Fill::FILL_SOLID );
    $spreadsheet->getActiveSheet()->getStyle( 'A1:Z1' )->getFill()->getStartColor()->setARGB( 'ffffff' );

    $spreadsheet->getActiveSheet()->getStyle( 'A2:Z2' )->getFill()->setFillType( \PhpOffice\ PhpSpreadsheet\ Style\ Fill::FILL_SOLID );
    $spreadsheet->getActiveSheet()->getStyle( 'A2:Z2' )->getFill()->getStartColor()->setARGB( 'ffffff' );

    $spreadsheet->getActiveSheet()->getStyle( 'A3:Z3' )->getFill()->setFillType( \PhpOffice\ PhpSpreadsheet\ Style\ Fill::FILL_SOLID );
    $spreadsheet->getActiveSheet()->getStyle( 'A3:Z3' )->getFill()->getStartColor()->setARGB( 'ffffff' );


    $spreadsheet->getActiveSheet()->getStyle( 'K3:T3' )->getFill()->getStartColor()->setARGB( 'fef271' );
    $spreadsheet->getActiveSheet()->mergeCells( 'K3:T3' );
    $spreadsheet->getActiveSheet()->getStyle( 'H3:I3' )->getFill()->getStartColor()->setARGB( 'fef271' );
    $spreadsheet->getActiveSheet()->mergeCells( 'H3:I3' );

    $spreadsheet->getActiveSheet()->getStyle( 'K3' )->getAlignment()->setHorizontal( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::HORIZONTAL_CENTER );
    $spreadsheet->getActiveSheet()->getStyle( 'H3' )->getAlignment()->setHorizontal( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::HORIZONTAL_CENTER );

    $spreadsheet->getActiveSheet()->getStyle( 'K3' )->getFont()->setSize( 13 );
    $spreadsheet->getActiveSheet()->getStyle( 'K3' )->getFont()->setBold( true );

    $spreadsheet->getActiveSheet()->getStyle( 'H3' )->getFont()->setSize( 13 );
    $spreadsheet->getActiveSheet()->getStyle( 'H3' )->getFont()->setBold( true );


    if( $entregas[ 0 ]->FORNECEDOR = 'KERING EYEWEAR SPA' ) {
      $NOTESKU = 'PO NAME';
    }
    else {
      $NOTESKU = 'NOTE SKU';
    }

    if( $entregas[ 0 ]->FORNECEDOR = 'KERING EYEWEAR SPA' ) {
      $PODATE = 'PO DATE';
    }
    else {
      $PODATE = 'DELIVERY DATE';
    }


    $sheet->setCellValue( 'K3', 'SUPPLIER' );
    $sheet->setCellValue( 'H3', 'SUPPLIER' );
    $sheet->setCellValue( 'A4', 'ID' );
    $sheet->setCellValue( 'B4', 'ITEM' );
    $sheet->setCellValue( 'C4', 'QTT ORDER' );
    $sheet->setCellValue( 'D4', 'QTT OF DELIVERY' );
    $sheet->setCellValue( 'E4', 'DATE DELIVERY' );
    $sheet->setCellValue( 'F4', 'QTT DELIVERED' );
    $sheet->setCellValue( 'G4', 'ACTION' );

    $sheet->setCellValue( 'H4', 'NEW DATE(aaaa-mm-dd)' );
    $sheet->setCellValue( 'I4', 'NOTE NEW DATE' );

    $sheet->setCellValue( 'J4', 'BALANCE TO DELIVER' );
    $sheet->setCellValue( 'K4', 'QUANTITY OF DELIVERIES' );

    $sheet->setCellValue( 'L4', 'QTT DELIVERY 1' );
    $sheet->setCellValue( 'M4', 'DATE DELIVERY 1(aaaa-mm-dd)' );
    $sheet->setCellValue( 'N4', 'NOTE DELIVERY 1' );

    $sheet->setCellValue( 'O4', 'QTT DELIVERY 2' );
    $sheet->setCellValue( 'P4', 'DATE DELIVERY 2(aaaa-mm-dd)' );
    $sheet->setCellValue( 'Q4', 'NOTE DELIVERY 2' );

    $sheet->setCellValue( 'R4', 'QTT DELIVERY 3' );
    $sheet->setCellValue( 'S4', 'DATE DELIVERY 3(aaaa-mm-dd)' );
    $sheet->setCellValue( 'T4', 'NOTE DELIVERY 3' );


    $spreadsheet->getActiveSheet()->getStyle( 'A4:AA4' )->getAlignment()->setHorizontal( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::HORIZONTAL_CENTER )->setVertical( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::VERTICAL_CENTER );

    $spreadsheet->getActiveSheet()->getStyle( 'B1' )->getAlignment()->setHorizontal( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::HORIZONTAL_LEFT )->setVertical( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::VERTICAL_CENTER );
    $spreadsheet->getActiveSheet()->getStyle( 'B1' )->getFont()->setSize( 13 );
    $spreadsheet->getActiveSheet()->getStyle( 'B1' )->getBorders()->getTop()->setBorderStyle( \PhpOffice\ PhpSpreadsheet\ Style\ Border::BORDER_THICK );
    $spreadsheet->getActiveSheet()->getStyle( 'B1' )->getBorders()->getBottom()->setBorderStyle( \PhpOffice\ PhpSpreadsheet\ Style\ Border::BORDER_THICK );
    $spreadsheet->getActiveSheet()->getStyle( 'B1' )->getBorders()->getLeft()->setBorderStyle( \PhpOffice\ PhpSpreadsheet\ Style\ Border::BORDER_THICK );
    $spreadsheet->getActiveSheet()->getStyle( 'B1' )->getBorders()->getRight()->setBorderStyle( \PhpOffice\ PhpSpreadsheet\ Style\ Border::BORDER_THICK );
    $spreadsheet->getActiveSheet()->getStyle( 'B1' )->getFont()->setSize( 13 );

    $spreadsheet->getActiveSheet()->getStyle( 'B1' )->getFont()->setBold( true );


    $spreadsheet->getActiveSheet()->getStyle( 'G1' )->getAlignment()->setHorizontal( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::HORIZONTAL_LEFT )->setVertical( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::VERTICAL_CENTER );

    $spreadsheet->getActiveSheet()->getStyle( 'G1' )->getBorders()->getTop()->setBorderStyle( \PhpOffice\ PhpSpreadsheet\ Style\ Border::BORDER_THICK );
    $spreadsheet->getActiveSheet()->getStyle( 'G1' )->getBorders()->getBottom()->setBorderStyle( \PhpOffice\ PhpSpreadsheet\ Style\ Border::BORDER_THICK );
    $spreadsheet->getActiveSheet()->getStyle( 'G1' )->getBorders()->getLeft()->setBorderStyle( \PhpOffice\ PhpSpreadsheet\ Style\ Border::BORDER_THICK );
    $spreadsheet->getActiveSheet()->getStyle( 'G1' )->getBorders()->getRight()->setBorderStyle( \PhpOffice\ PhpSpreadsheet\ Style\ Border::BORDER_THICK );


    $spreadsheet->getActiveSheet()->getStyle( 'G1' )->getFont()->setBold( true );

    $spreadsheet->getActiveSheet()->getStyle( 'G1' )->getAlignment()->setWrapText( true );
    $sheet->setCellValue( 'G1',
      'SUPPLIER' . "\n" . $entregas[ 0 ]->razao . "\n" . $entregas[ 0 ]->endereco . "\n" . $entregas[ 0 ]->municipio . ' - ' . $entregas[ 0 ]->uf . ' - ' . $entregas[ 0 ]->pais );


    $spreadsheet->getActiveSheet()->getStyle( 'B1' )->getAlignment()->setWrapText( true );

    IF( $entregas[ 0 ]->FORNECEDOR = 'KERING EYEWEAR SPA' ) {
      $PURCHASEORDER = 'PURCHASE ORDER #' . $entregas[ 0 ]->obs . "\n" .
      'INTERNAL CODE #' . $entregas[ 0 ]->id_compra . "\n";


    }
    ELSE {
      $PURCHASEORDER = 'PURCHASE ORDER #' . $entregas[ 0 ]->id_compra . "\n" . "\n";
    }

    $sheet->setCellValue( 'B1',

      $PURCHASEORDER
      . 'P.O. Data: ' . $entregas[ 0 ]->dt_emissao . "\n" .
      'Payment Terms: ' . $entregas[ 0 ]->pagamento . "\n" .
      'Shipping Methods: ' . $entregas[ 0 ]->transporte )


    ;


    $linha = 4;

    foreach ( $entregas as $item ) {

      $linha++;
      $spreadsheet->getActiveSheet()->getRowDimension( $linha )->setRowHeight( 25 );


      $spreadsheet->getActiveSheet()->getStyle( 'A' . $linha . ':AA' . $linha )->getAlignment()->setVertical( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::VERTICAL_CENTER );
      $spreadsheet->getActiveSheet()->getStyle( 'A' . $linha . ':AA' . $linha )->getAlignment()->setHorizontal( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::HORIZONTAL_CENTER );

     
      $spreadsheet->getActiveSheet()->getStyle( 'F' . $linha )->getNumberFormat()->setFormatCode( \PhpOffice\ PhpSpreadsheet\ Style\ NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2 );
      $spreadsheet->getActiveSheet()->getStyle( 'J' . $linha );


      $sheet->setCellValue( 'A' . $linha, $item->id_linha_entregas );
      $sheet->setCellValue( 'B' . $linha, $item->item );
      $sheet->setCellValue( 'C' . $linha, $item->qtd_pedido );
      $sheet->setCellValue( 'D' . $linha, $item->qtde_entrega );
      $sheet->setCellValue( 'E' . $linha, $item->dt_confirmada );
      $sheet->setCellValue( 'F' . $linha, $item->qtd_entregue );

      $validation = $spreadsheet->getActiveSheet()->getCell( 'G' . $linha )->getDataValidation();
      $validation->setType( \PhpOffice\ PhpSpreadsheet\ Cell\ DataValidation::TYPE_LIST );
      $validation->setErrorStyle( \PhpOffice\ PhpSpreadsheet\ Cell\ DataValidation::STYLE_INFORMATION );
      $validation->setAllowBlank( false );
      $validation->setShowInputMessage( true );
      $validation->setShowErrorMessage( true );
      $validation->setShowDropDown( true );
      $validation->setErrorTitle( 'Input error' );
      $validation->setError( 'Value is not in list.' );
      $validation->setPromptTitle( 'Selecione a ação' );
      $validation->setPrompt( 'Por favor selecionar a ação' );
      $validation->setFormula1( '"MANTER,AJUSTAR_DATA,REDISTRIBUIR"' );


      $sheet->getCell( 'G' . $linha )->setDataValidation( clone $validation );

      $validation = $spreadsheet->getActiveSheet()->getCell( 'K' . $linha )->getDataValidation();
      $validation->setType( \PhpOffice\ PhpSpreadsheet\ Cell\ DataValidation::TYPE_LIST );
      $validation->setErrorStyle( \PhpOffice\ PhpSpreadsheet\ Cell\ DataValidation::STYLE_INFORMATION );
      $validation->setAllowBlank( false );
      $validation->setShowInputMessage( true );
      $validation->setShowErrorMessage( true );
      $validation->setShowDropDown( true );
      $validation->setErrorTitle( 'Input error' );
      $validation->setError( 'Value is not in list.' );
      $validation->setPromptTitle( 'Selecione a quantidade de entrega' );
      $validation->setPrompt( 'Selecione a quantidade de entrega' );
      $validation->setFormula1( '"1,2,3"' );


      $sheet->getCell( 'K' . $linha )->setDataValidation( clone $validation );

      $sheet->setCellValue( 'J' . $linha, $item->saldo_redistribuir );


    }

    $writer = new Xlsx( $spreadsheet );
    //	$writer->save('hello world.xlsx');		
    header( 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' );
    header( 'Content-Disposition: attachment;filename="DELIVERY_ADJUSTEMENT #' . $entregas[ 0 ]->id_compra . ' -' . date( 'Y-m-d', strtotime( $entregas[ 0 ]->dt_emissao ) ) . '.xlsx"' );
    header( 'Cache-Control: max-age=0' );

    $writer = \PhpOffice\ PhpSpreadsheet\ IOFactory::createWriter( $spreadsheet, 'Xlsx' );
    $writer->save( 'php://output' );


    $nome_excel = '/var/www/html/portal-gestao/storage/app/pedido.xlsx';
    // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    // header('Content-Disposition: attachment;filename="'.$nome.'"');
    // header('Cache-Control: max-age=0');
    // // If you're serving to IE 9, then the following may be needed
    // header('Cache-Control: max-age=1');

    $writer = new Xlsx( $spreadsheet );
    $writer->save( $nome_excel );

  }


  public function ExcluiInvoice( $invoice, Request $request ) {

	  
    $compras_entregas_invoices = \DB::select( "select*
					from compras_entregas_invoices
					where invoice = '$invoice'
					and (compras_entregas_invoices.exclui is null or compras_entregas_invoices.exclui = 0)
					" );


    foreach ( $compras_entregas_invoices as $entregas_invoices ) {


      if ( $entregas_invoices->id_compras_entrega > 0 ) {


        $update = \DB::select( " update compras_entregas set qtd_entregue = qtd_entregue-$entregas_invoices->qtd_entregue where compras_entregas.id = '$entregas_invoices->id_compras_entrega'" );

        $delete = \DB::select( " update compras_entregas_invoices set  exclui = 1  where id = '$entregas_invoices->id'" );

        $update_status_id_item = \DB::select( "select id_compra_item
					from compras_entregas 
					where compras_entregas.id = '$entregas_invoices->id_compras_entrega'
                    limit 1" );
        $idcomprasitem = $update_status_id_item[ 0 ]->id_compra_item;

        $update_status = \DB::select( "update compras_itens set status = 'distribuido' where status <> 'finalizado sistema' and id = '$idcomprasitem'" );

      } else {
        $delete = \DB::select( " update compras_entregas_invoices set  exclui = 1  where id = '$entregas_invoices->id'" );
      }
    }
    $delete_compras_invoice = \DB::select( " update compras_invoices set  exclui = 1  where invoice = '$invoice'" );

    $request->session()->flash( 'alert-warning', 'Invoice ' . $invoice . ' excluida com sucesso.' );
    return redirect( "/compras/invoice/lista" );

  }
  public function programacaoEntrega() {


    $entregas = \DB::select( "
			select ordem, dt_completa, anoentrega, sum(previsao) as previsao, sum(confirmado) confirmado, sum(qtd_embarque) as tt_pedido
			from(
			select ordem, dt_completa, anoentrega,fornecedor,
			case when tipo = 'previsao' then sum(qtentrega) else 0 end as 'previsao',
			case when tipo = 'confirmado' then sum(qtentrega) else 0 end as 'confirmado',
            case when tipo = 'confirmado' then sum(qtentrega)/20000 else 0 end as 'qtd_embarque'
from(select *,
		case 
		when month(dtentrega) = 1 then 'a'
		when month(dtentrega) = 2 then 'b'
		when month(dtentrega) = 3 then 'c'
		when month(dtentrega) = 4 then 'd'
		when month(dtentrega) = 5 then 'e'
		when month(dtentrega) = 6 then 'f'
		when month(dtentrega) = 7 then 'g'
		when month(dtentrega) = 8 then 'h'
		when month(dtentrega) = 9 then 'i'
		when month(dtentrega) = 10 then 'j'
		when month(dtentrega) = 11 then 'k'
		when month(dtentrega) = 12 then 'l'
		else 'Z' end as ordem,
		concat(year(dtentrega),'-',month(dtentrega)) as dt_completa,
        year(dtentrega) anoentrega
        from(
		select agrup, compras_entregas.dt_confirmada dtconfirmada, fornecedor, compras.obs, item, ifnull(qtde_confirmada,0) as qtd_confirmada, ifnull(qtde,0) as qtde_pedida, compras.id,  ifnull(compras_entregas.qtde_entrega,0) as qtd_entrega,
		concat(year(compras_entregas.dt_confirmada),'-',month(compras_entregas.dt_confirmada)) as dt,year(compras_entregas.dt_confirmada) as ano,
       compras_entregas.dt_confirmada,  case 
      
      when compras_entregas.dt_confirmada is null and compras.dt_entrega  <= current_date then concat(year(current_date),'-',month(current_date),'-28')
       when compras_entregas.dt_confirmada is null then compras.dt_entrega
        when compras_entregas.dt_confirmada is not null and compras_entregas.dt_confirmada <= current_date then concat(year(current_date),'-',month(current_date),'-28')
       else compras_entregas.dt_confirmada end as dtentrega
       , case when compras_entregas.dt_confirmada is null then 'previsao' else 'confirmado' end as tipo,
       case when compras_entregas.dt_entrega is null then ifnull(qtde,0) else ifnull(compras_entregas.qtde_entrega,0)-ifnull(compras_entregas.qtd_entregue,0) end as qtentrega
        from compras
		left join compras_itens on compras_itens.id_compra = compras.id
		left join compras_entregas on compras_itens.id = compras_entregas.id_compra_item
		left join itens on itens.secundario = compras_itens.item
		where compras.dt_emissao >= 2020-06-01
		and fornecedor not like '%kering%'
		and fornecedor not like '%ZHONGMIN%'
		and compras_itens.status not in ('cancelado','FINALIZADO SISTEMA','concluido')
		and compras_itens.status in ('distribuido', 'aguardando documentacao')
		and ifnull(compras_entregas.qtde_entrega,0)-ifnull(compras_entregas.qtd_entregue,0) > 0
		and (compras_entregas.exclui is null or compras_entregas.exclui = 0)
		and ifnull(compras_entregas.qtde_entrega,0) <> 0
		 and (compras_entregas.dt_alterada is null or compras_entregas.dt_alterada =0)
		 and fornecedor not like '%kenerson%'
		 and itens.codtipoitem = 006
		) as base) as base2

        group by ordem, dt_completa, anoentrega, tipo, fornecedor) base3
        group by anoentrega, ordem , dt_completa
        order by anoentrega asc, ordem asc, dt_completa asc

		" );


    return view( 'produtos.compras.programacao_entrega' )->with( 'entregas', $entregas );


  }


  public function detalhesInvoice( Request $request, $invoices ) {
	

    $invoice = \DB::select( "select compras_invoices.id as id, compras_invoices.invoice, compras_invoices.qtd as qtd, compras_invoices.item, compras_invoices.dt_invoice, compras_invoices.custo as custo, compras_invoices.pedido, compras_invoices.tipo,
(select id_compra from compras_entregas left join compras_itens on compras_itens.id = compras_entregas.id_compra_item where compras_entregas_invoices.id_compras_entrega = compras_entregas.id) as idcompra

				from 	compras_invoices
				left join compras_entregas_invoices on compras_invoices.id  = compras_entregas_invoices.id_compras_invoices
				where compras_invoices.invoice = '$invoices'
				and (compras_invoices.exclui is null or compras_invoices.exclui = 0)
			
				order by item asc" );

        foreach($invoice as $item){

          $portfolioItem = PortfolioItem::with('comentarios')->with('comentarios.usuario')->where('id_compra_invoice', $item->id)->first();

          if(isset($portfolioItem))
            $item->portfolioItem = $portfolioItem;

          if(isset($portfolioItem->comentarios))
            $item->comentarios = $portfolioItem->comentarios;

        }


    $pedidos = \DB::select( "select id_compra 
		from compras_itens 
		left join compras_entregas   on compras_itens.id = compras_entregas.id_compra_item
		left join compras_entregas_invoices on compras_entregas_invoices.id_compras_entrega = compras_entregas.id 

		where  (compras_entregas.exclui is null or compras_entregas.exclui = 0)
         and (compras_entregas_invoices.exclui is null or compras_entregas_invoices.exclui = 0)
		 and invoice = '$invoices'
		 
		 group by id_compra" );

    $sempedido = \DB::select( "select item, sum(qtd_entregue) as qtd, invoice from compras_entregas_invoices where invoice = '$invoices' and status = 'SEM PEDIDO'
		and (compras_entregas_invoices.exclui is null or compras_entregas_invoices.exclui = 0)
		group by item,invoice" );

    $veririfcavalor = \DB::select( "select secundario, custos_2019.custo as custo_pedido, compras_invoices.custo as custo_invoice 
		from compras_invoices 
		left join custos_2019 on custos_2019.secundario = compras_invoices.item 
		where custos_2019.custo <> compras_invoices.custo and invoice = '$invoices'
		and (compras_invoices.exclui is null or compras_invoices.exclui = 0)" );

    if ( count( $sempedido ) > 0 ) {

      $sempedidototal = \DB::select( "select invoice, sum(qtd_entregue) as tt from compras_entregas_invoices where invoice = '$invoices' and status = 'SEM PEDIDO'
		and (compras_entregas_invoices.exclui is null or compras_entregas_invoices.exclui = 0)
		" );
	
		return view( 'produtos.compras.detalhes_invoice' )->with( 'invoice', $invoice )->with( 'sempedido', $sempedido )->with( 'sempedidototal', $sempedidototal )->with( 'veririfcavalor', $veririfcavalor )->with( 'pedidos', $pedidos );;


    } else {
      return view( 'produtos.compras.detalhes_invoice' )->with( 'invoice', $invoice )->with( 'sempedido', $sempedido )->with('veririfcavalor', $veririfcavalor)->with( 'pedidos', $pedidos );

    }


  }
  public function listainvoices( Request $request ) {

    $invoices = \DB::select( "select invoice,pedido, count(item) itens, sum(qtd) as qtd, dt_invoice from compras_invoices 
		 where (compras_invoices.exclui is null or compras_invoices.exclui <> 1)
		group by invoice, dt_invoice,pedido order by dt_invoice desc " );

    return view( 'produtos.compras.invoices' )->with( 'invoices', $invoices );
  }

  public function entregaOI( Request $request ) {
	  
	   $dtpedido = \DB::select( "
select  ip.dt_pedido
from importacoes_pedidos ip
left join itens_estrutura  ie on ie.id_filho = ip.cod_item and ie.tipo_filho = '002'

left join itens i on i.id = ip.cod_item
where   -- ip.dt_pedido > '2020-09-01'and 
ip.secundario not in ('FRJC 1928 C1             ','FR PLASTICO              ',
 'FR METAL                 ','FR ACETATO               ','FRASCO 30ML              ')
 and (tipo = 'oi' or (tipo = 'op' and tipo_linha = 'bs'))

 and ult_status <> 980
 and ip.pedido = '$request->pedido'
 and ip.tipo = '$request->tipo'
 group by ip.dt_pedido
		
" );
	  $dtpedido1 = $dtpedido[0]->dt_pedido;

    $oi = \DB::select( "
			select base.*,itens.agrup as grife,
			ifnull((select sum(compras_entregas.qtde_entrega-ifnull(compras_entregas.qtd_entregue,0)) from compras_itens left join compras_entregas on compras_entregas.id_compra_item = compras_itens.id
			where (compras_entregas.exclui is null or compras_entregas.exclui = 0) and compras_entregas.qtde_entrega-ifnull(compras_entregas.qtd_entregue,0) 
			and compras_itens.item = base.item and status in ('distribuido', 'producao', 'aguardando documentacao') 
			 -- and pedido_dt <= $dtpedido1  
			),0) as qtd_aberto, vlr_unit,
			concat(nome, ' - ', fornecedor) as fornecedor, qtde_sol*vlr_unit as valor_tt
			from(
			select  ip.dt_pedido, ip.linha, ip.tipo, ip.pedido,ltrim(rtrim(ip.ref_go)) as invoice,
			case when tipo_filho = '002' then ltrim(rtrim(id_pai)) 
			else ltrim(rtrim(i.id)) end as id_item,
			case when tipo_filho = '002' then ltrim(rtrim(item_pai)) else ltrim(rtrim(ip.secundario)) end as item, qtde_sol as qtde_sol, vlr_unit vlr_unit,
			qtde_sol* vlr_unit as tt_valor

			from importacoes_pedidos ip
			left join itens_estrutura  ie on ie.id_filho = ip.cod_item and ie.tipo_filho = '002'
			
			left join itens i on i.id = ip.cod_item
			where  -- ip.dt_pedido > '2020-09-01' and 
			ip.secundario not in ('FRJC 1928 C1             ','FR PLASTICO              ',
			 'FR METAL                 ','FR ACETATO               ','FRASCO 30ML              ')

			 and ult_status <> 980
			 and ip.pedido = '$request->pedido'
			 and ip.tipo = '$request->tipo'
			 and (tipo = 'oi' or (tipo = 'op' and tipo_linha = 'bs'))
			 and IFNULL(ie.clasitemfilho,'') <> 'PARTE CLIPON'
       and ip.secundario not like '%semi%'
			 group by ip.dt_pedido, ip.linha, ip.tipo, ip.pedido,ip.ref_go,
			item_filho, id_pai,
			  ip.secundario , qtde_sol , vlr_unit ,i.id, item_pai, tipo_filho
			
			 ) as base
			  left join compras_invoices ci on ci.pedido = base.pedido and ci.item = base.item and ci.linha = base.linha and ci.qtd = base.qtde_sol and ci.dt_invoice = base.dt_pedido and ci.exclui <> 1
			 left join itens on base.item = itens.secundario
			 left join addressbook ad on ad.id = itens.codfornecedor 
			 where ci.id_item is null

       union all

       select base.*,itens.agrup as grife,
			ifnull((select sum(compras_entregas.qtde_entrega-ifnull(compras_entregas.qtd_entregue,0)) from compras_itens left join compras_entregas on compras_entregas.id_compra_item = compras_itens.id
			where (compras_entregas.exclui is null or compras_entregas.exclui = 0) and compras_entregas.qtde_entrega-ifnull(compras_entregas.qtd_entregue,0) 
			and compras_itens.item = base.item and status in ('distribuido', 'producao', 'aguardando documentacao') 
			 -- and pedido_dt <= $dtpedido1  
			),0) as qtd_aberto, vlr_unit,
			concat(nome, ' - ', fornecedor) as fornecedor, qtde_sol*vlr_unit as valor_tt
			from(
			select  ip.dt_pedido, ip.linha, ip.tipo, ip.pedido,ltrim(rtrim(ip.ref_go)) as invoice,
			id_item_destino as id_item,
			item_destino as item, qtde as qtde_sol, vlr_unit vlr_unit,
			qtde* vlr_unit as tt_valor

			from importacoes_pedidos ip
	left join entrada_agrupada  ea on ea.id_item_invoice = ip.cod_item and ref_go = invoice and ip.pedido = ea.pedido
	left join itens i on i.id = ea.id_item_destino

			where 

			 ult_status <> 980
			 and ip.pedido = '$request->pedido'
			 and ip.tipo = '$request->tipo'
			 and (tipo = 'oi' or (tipo = 'op' and tipo_linha = 'bs'))
       and ip.secundario like '%semi%'
			 
			 group by ip.dt_pedido, ip.linha, ip.tipo, ip.pedido,ip.ref_go,
			item_destino, id_item_destino,
			  ip.secundario , qtde, vlr_unit ,i.id
			
			 ) as base
			  left join compras_invoices ci on ci.pedido = base.pedido and ci.item = base.item and ci.linha = base.linha and ci.qtd = base.qtde_sol and ci.dt_invoice = base.dt_pedido and ci.exclui <> 1
			 left join itens on base.item = itens.secundario
			 left join addressbook ad on ad.id = itens.codfornecedor 
			 where ci.id_item is null
			

			" );

    foreach ( $oi as $linha ) {
      if ( $linha->id_item == '' ) {
        $id_item = '0';
      } else {
        $id_item = $linha->id_item;
      }
      $insere_compras = \DB::select( "INSERT INTO `compras_invoices`( `id_item`,`item`, `qtd`, `invoice`, `dt_invoice`, `custo`, `pedido`, `tipo`, `linha`, `exclui`, `usado`) VALUES ('$id_item','$linha->item','$linha->qtde_sol','$linha->invoice','$linha->dt_pedido','$linha->vlr_unit','$linha->pedido','$linha->tipo','$linha->linha','0','0')
		" );
    }
    //dd($oi);
    $num_invoice = $oi[ 0 ]->invoice;
    $itens = \DB::select( "select* from compras_invoices  where invoice = '$num_invoice' 
		 and exclui = 0 " );


    foreach ( $itens as $item ) {
      $saldo = $item->qtd;
      //echo ' realizado ' . $item->item . ' ' . $saldo . '<br>';


      $verifica_compra = \DB::select( "select *, compras_entregas.id id_entregas, qtde_entrega-ifnull(qtd_entregue,0) as falta_entrega, compras_entregas.id as id_compras_entregas, ifnull(qtd_entregue,0) entregue1
						from compras_itens
						left join compras_entregas on compras_entregas.id_compra_item = compras_itens.id
						left join compras on compras.id = id_compra
						where item = '$item->item'
						
						and compras_entregas.id_compra_item is not null
						 and qtde_entrega-ifnull(qtd_entregue,0) > 0 
						 and qtde_entrega-ifnull(qtd_entregue,0) <> 0 
						 and compras_itens.status in ('distribuido','producao', 'aguardando documentacao')
						 and (compras_entregas.exclui is null or compras_entregas.exclui = 0)
						--  and pedido_dt <= '$item->dt_invoice'
						order by pedido_dt asc, compras_entregas.qtde_entrega  desc 
						" );
      //dd($verifica_compra);
      if ( count( $verifica_compra ) > 0 ) {
        foreach ( $verifica_compra as $linha ) {
          $sobra_usado = \DB::select( "select*, qtd-usado sobra from compras_invoices  where id = '$item->id' and exclui = 0  and qtd-usado>0 " );
          if ( count( $sobra_usado ) > 0 ) {
            //dd($sobra_usado);

            if ( $linha->falta_entrega >= $sobra_usado[ 0 ]->sobra ) {
              $sobrausado = $sobra_usado[ 0 ]->sobra;
              $gravar_entrega = \DB::select( "update compras_entregas set qtd_entregue = ifnull($linha->entregue1,0)+$sobrausado where id = '$linha->id_entregas'" );

              $grava_compras_entregas_invoices = \DB::select( "INSERT INTO compras_entregas_invoices(item, id_compras_invoices, qtd_invoice, id_compras_entrega, qtd_entregue, status,invoice,dt_invoice,qtde_tabela_entregas) VALUES ('$linha->item','$item->id','$item->qtd','$linha->id_entregas','$sobrausado','PEDIDO ENTREGUE','$num_invoice','$item->dt_invoice','$linha->entregue1')" );

              $usado = \DB::select( "update compras_invoices set usado = usado+$sobrausado	  where id = '$item->id' " );

              echo 'consumido ' . $sobra_usado[ 0 ]->sobra . '<br>';
            }
            if ( $linha->falta_entrega < $sobra_usado[ 0 ]->sobra & $linha->falta_entrega > 0 ) {

              $gravar_entrega = \DB::select( "update compras_entregas set qtd_entregue = ifnull($linha->entregue1,0)+$linha->falta_entrega where id = '$linha->id_entregas'" );

              $grava_compras_entregas_invoices = \DB::select( "INSERT INTO compras_entregas_invoices(item, id_compras_invoices, qtd_invoice, id_compras_entrega, qtd_entregue, status,invoice,dt_invoice,qtde_tabela_entregas) VALUES ('$linha->item','$item->id','$item->qtd','$linha->id_entregas','$linha->falta_entrega','PEDIDO ENTREGUE','$num_invoice','$item->dt_invoice','$linha->entregue1')" );

              $usado = \DB::select( "update compras_invoices set usado = usado+$linha->falta_entrega	  where id = '$item->id' " );

              echo 'consumido2 ' . $linha->falta_entrega . '<br>';

            }


          }


        }

      }
    }

    $sobra_pedido1 = \DB::select( "select*, qtd-usado sobra from compras_invoices  where invoice = '$num_invoice' and exclui = 0 																and qtd-usado>0 " );

    foreach ( $sobra_pedido1 as $linhas_sobra ) {
      $grava_compras_entregas_invoices = \DB::select( "INSERT INTO compras_entregas_invoices(item, id_compras_invoices, qtd_invoice, id_compras_entrega, qtd_entregue, status,invoice,dt_invoice,qtde_tabela_entregas) VALUES ('$linhas_sobra->item','$linhas_sobra->id','$linhas_sobra->qtd','','$linhas_sobra->sobra','SEM PEDIDO','$num_invoice','$linhas_sobra->dt_invoice','$linhas_sobra->qtd')" );

      echo 'sempedido ' . $linhas_sobra->sobra . '<br>';
      $request->session()->flash( 'alert-warning', 'Invoice sem pedido' );

    }

    $muda_status = \DB::select( "select *, compras_itens.id as id_compras_itens 
		from compras_itens 
		left join compras_entregas   on compras_itens.id = compras_entregas.id_compra_item
		left join compras_entregas_invoices on compras_entregas_invoices.id_compras_entrega = compras_entregas.id 

		where compras_entregas.qtd_entregue = compras_entregas.qtde_entrega
		and (compras_entregas.exclui is null or compras_entregas.exclui = 0)
		and (compras_entregas_invoices.exclui is null or compras_entregas_invoices.exclui = 0)
		and invoice = '$num_invoice'" );
    //dd($muda_status);

    foreach ( $muda_status as $ids ) {
      $linha_status = \DB::select( "  select id_compras_itens
		 from(select  compras_itens.id as id_compras_itens  ,sum(compras_entregas.qtd_entregue) qtd_entregue, sum(compras_entregas.qtde_entrega) qtde_entrega
		from compras_itens 
		left join compras_entregas   on compras_itens.id = compras_entregas.id_compra_item
		left join compras_entregas_invoices on compras_entregas_invoices.id_compras_entrega = compras_entregas.id 

		where  compras_itens.id = '$ids->id_compras_itens'
		and (compras_entregas.exclui is null or compras_entregas.exclui = 0)
		and (compras_entregas_invoices.exclui is null or compras_entregas_invoices.exclui = 0)
		) as base
		
        where qtd_entregue = qtde_entrega" );

      if ( count( $linha_status ) > 0 ) {
        $idcompra = $linha_status[ 0 ]->id_compras_itens;
        $atualiza_status = \DB::select( "update compras_itens set status = 'Concluido' where id = $idcompra" );
      }
    }

    //dd($veririfcavalor);
    return redirect( '/compras/invoice/detalhes/' . $num_invoice );


  }
  public function detalhesOI( Request $request ) {

	   $dtpedido = \DB::select( "
select  ip.dt_pedido
from importacoes_pedidos ip
left join itens_estrutura  ie on ie.id_filho = ip.cod_item and ie.tipo_filho = '002'

left join itens i on i.id = ip.cod_item
where   -- ip.dt_pedido > '2020-09-01'and 
ip.secundario not in ('FRJC 1928 C1             ','FR PLASTICO              ',
 'FR METAL                 ','FR ACETATO               ','FRASCO 30ML              ')
 and (tipo = 'oi' or (tipo = 'op' and tipo_linha = 'bs'))
 and IFNULL(ie.clasitemfilho,'') <> 'PARTE CLIPON'
 and ult_status <> 980
 and ip.pedido = '$request->pedido'
 and ip.tipo = '$request->tipo'
 group by ip.dt_pedido
		
" );
	  $dtpedido1 = $dtpedido[0]->dt_pedido;
	  //dd($dtpedido);
    $oi = \DB::select( "
select base.*,itens.agrup as grife,
ifnull((select sum(compras_entregas.qtde_entrega-ifnull(compras_entregas.qtd_entregue,0)) from compras_itens left join compras_entregas on compras_entregas.id_compra_item = compras_itens.id
where (compras_entregas.exclui is null or compras_entregas.exclui = 0) and compras_entregas.qtde_entrega-ifnull(compras_entregas.qtd_entregue,0) 
and compras_itens.item = base.item and status in ('distribuido', 'producao', 'aguardando documentacao') 
--  and  pedido_dt <= '$dtpedido1'   
),0) as qtd_aberto,
ifnull((select group_concat(distinct compras_itens.id_compra)from compras_itens left join compras_entregas on compras_entregas.id_compra_item = compras_itens.id
where (compras_entregas.exclui is null or compras_entregas.exclui = 0) and compras_entregas.qtde_entrega-ifnull(compras_entregas.qtd_entregue,0) 
and compras_itens.item = base.item and status in ('distribuido', 'producao', 'aguardando documentacao') 
--  and  pedido_dt <= '$dtpedido1'   
),0) as id_compra1,
format(vlr_unit,2),
concat(nome, ' - ', fornecedor) as fornecedor, format(qtde_sol*vlr_unit,2) as valor_tt, modelo
from(
select  ip.dt_pedido, ip.linha, ip.tipo, ip.pedido,
case when tipo_filho = '002' then ltrim(rtrim(id_pai)) else ltrim(rtrim(i.id)) end as id_item,
case when tipo_filho = '002' then ltrim(rtrim(item_pai)) else ltrim(rtrim(ip.secundario)) end as item, format(qtde_sol,0) as qtde_sol, format(vlr_unit,2) vlr_unit,ltrim(rtrim(ip.ref_go)) as invoice,
qtde_sol* vlr_unit as tt_valor

from importacoes_pedidos ip
left join itens_estrutura  ie on ie.id_filho = ip.cod_item and ie.tipo_filho = '002'

left join itens i on i.id = ip.cod_item
where   -- ip.dt_pedido > '2020-09-01'and 
ip.secundario not in ('FRJC 1928 C1             ','FR PLASTICO              ',
 'FR METAL                 ','FR ACETATO               ','FRASCO 30ML              ')
 and (tipo = 'oi' or (tipo = 'op' and tipo_linha = 'bs'))

 and ult_status <> 980
 and ip.pedido = '$request->pedido'
 and ip.tipo = '$request->tipo'
 and ip.secundario not like '%semi%'
 and IFNULL(ie.clasitemfilho,'') <> 'PARTE CLIPON'
 group by ip.dt_pedido, ip.linha, ip.tipo, ip.pedido,ip.ref_go,
			item_filho, id_pai,
			  ip.secundario , qtde_sol , vlr_unit ,i.id, item_pai, tipo_filho
			
 ) as base
 left join compras_invoices ci on ci.pedido = base.pedido and ci.id_item = base.id_item and ci.linha = base.linha and ci.qtd = base.qtde_sol and ci.dt_invoice = base.dt_pedido and ci.exclui <> 1	

 left join itens on base.id_item = itens.id
 left join addressbook ad on ad.id = itens.codfornecedor
 where ci.id_item is null

 union all

 select base.*,itens.agrup as grife,
ifnull((select sum(compras_entregas.qtde_entrega-ifnull(compras_entregas.qtd_entregue,0)) from compras_itens left join compras_entregas on compras_entregas.id_compra_item = compras_itens.id
where (compras_entregas.exclui is null or compras_entregas.exclui = 0) and compras_entregas.qtde_entrega-ifnull(compras_entregas.qtd_entregue,0) 
and compras_itens.item = base.item and status in ('distribuido', 'producao', 'aguardando documentacao') 
--  and  pedido_dt <= '$dtpedido1'   
),0) as qtd_aberto,
ifnull((select group_concat(distinct compras_itens.id_compra)from compras_itens left join compras_entregas on compras_entregas.id_compra_item = compras_itens.id
where (compras_entregas.exclui is null or compras_entregas.exclui = 0) and compras_entregas.qtde_entrega-ifnull(compras_entregas.qtd_entregue,0) 
and compras_itens.item = base.item and status in ('distribuido', 'producao', 'aguardando documentacao') 
--  and  pedido_dt <= '$dtpedido1'   
),0) as id_compra1,
format(vlr_unit,2),
concat(nome, ' - ', fornecedor) as fornecedor, format(qtde_sol*vlr_unit,2) as valor_tt, modelo
from(
select  ip.dt_pedido, ip.linha, ip.tipo, ip.pedido,
id_item_destino as id_item,
item_destino as item, format(qtde,0) as qtde_sol, format(vlr_unit,2) vlr_unit,ltrim(rtrim(ip.ref_go)) as invoice,
qtde* vlr_unit as tt_valor

from importacoes_pedidos ip
	left join entrada_agrupada  ea on ea.id_item_invoice = ip.cod_item and ref_go = invoice and ip.pedido = ea.pedido
	left join itens i on i.id = ea.id_item_destino

where  
 (tipo = 'oi' or (tipo = 'op' and tipo_linha = 'bs'))

 and ult_status <> 980
 and ip.pedido = '$request->pedido'
 and ip.tipo = '$request->tipo'
 and ip.secundario like '%semi%'

 group by ip.dt_pedido, ip.linha, ip.tipo, ip.pedido,ip.ref_go,
			item_destino, id_item_destino,
			  ip.secundario , qtde , vlr_unit ,i.id
			
 ) as base
 left join compras_invoices ci on ci.pedido = base.pedido and ci.id_item = base.id_item and ci.linha = base.linha and ci.qtd = base.qtde_sol and ci.dt_invoice = base.dt_pedido and ci.exclui <> 1	

 left join itens on base.id_item = itens.id
 left join addressbook ad on ad.id = itens.codfornecedor
 where ci.id_item is null
 


" );

    //dd($oi);
    return view( 'produtos.compras.detalhes_oi' )->with( 'oi', $oi );


  }
  public function listaOI() {
    $oi = \DB::select( "-- left join entrada_agrupada  ea on ea.id_item_invoice = ip.cod_item and ref_go = invoice and ip.pedido = ea.pedido
    select dt_pedido,pedido, tipo, format(sum(qtde_sol),0) as qtd,format(sum(tt_valor),2)as tt_valor, group_concat(distinct codgrife separator ',') as grife, fornecedor, sum(qtd_aberto) as qtd_aberto, invoice
    from(
    select base.*, 
    ifnull((select sum(compras_entregas.qtde_entrega-ifnull(compras_entregas.qtd_entregue,0)) from compras_itens left join compras_entregas on compras_entregas.id_compra_item = compras_itens.id
    where (compras_entregas.exclui is null or compras_entregas.exclui = 0) and compras_entregas.qtde_entrega-ifnull(compras_entregas.qtd_entregue,0) 
    and compras_itens.id_item = base.id_item and status in ('distribuido', 'producao', 'aguardando documentacao') 
     and pedido_dt <= dt_pedido
    ),0) as qtd_aberto
    from(
    select  ip.dt_pedido, ip.linha, ip.tipo, ip.pedido,ltrim(rtrim(ip.ref_go)) as invoice,
    case when tipo_filho = 002 then ltrim(rtrim(id_pai)) 
    else ltrim(rtrim(i.id)) end as id_item,
    case when tipo_filho = 002 then ltrim(rtrim(item_pai)) else ltrim(rtrim(ip.secundario)) end as item, qtde_sol, vlr_unit,
    qtde_sol* vlr_unit as tt_valor
    
    from importacoes_pedidos ip
    left join itens_estrutura  ie on ie.id_filho = ip.cod_item and ie.tipo_filho = '002'
    left join itens i on i.id = ip.cod_item
    left join itens iip on ip.cod_item = iip.id
    -- left join entrada_agrupada  ea on ea.id_item_invoice = ip.cod_item and ref_go = invoice and ip.pedido = ea.pedido
    where  
    -- ip.dt_pedido > '2020-09-01' and 
    ip.secundario not in ('FRJC 1928 C1             ','FR PLASTICO              ','FR METAL                 ','FR ACETATO               ','FRASCO 30ML              ') and
    ip.secundario not like '%semi%'
     -- and ci.id is not null
     and ult_status <> 980
     and (tipo = 'oi' or (tipo = 'op' and tipo_linha = 'bs'))
      and IFNULL(ie.clasitemfilho,'') <> 'PARTE CLIPON'
      and (iip.codtipoitem = 006 or ie.tipo_filho = '002')
       -- and ref_go = '152_21'
     group by ip.dt_pedido, ip.linha, ip.tipo, ip.pedido,ip.ref_go,
          item_filho, id_pai,
            ip.secundario , qtde_sol , vlr_unit ,i.id, item_pai, tipo_filho
          
     ) as base
     left join compras_invoices ci on ci.pedido = base.pedido and ci.id_item = base.id_item and ci.linha = base.linha and ci.qtd = base.qtde_sol and ci.dt_invoice = base.dt_pedido and ci.exclui <> 1
    where ci.id_item is null
     ) as base2
     left join itens on itens.id = base2.id_item
     where codtipoitem is not null
     and codtipoitem = 006
      and codfornecedor <> 102606
     -- and fornecedor = 'KERING EYEWEAR SPA'
     and pedido <> 4542
     group by pedido, tipo, fornecedor,dt_pedido, invoice
    
     union all
     select dt_pedido,pedido, tipo, format(sum(qtde_sol),0) as qtd,format(sum(tt_valor),2)as tt_valor, group_concat(distinct codgrife separator ',') as grife, fornecedor, sum(qtd_aberto) as qtd_aberto, invoice
    from(
    select base.*, 
    ifnull((select sum(compras_entregas.qtde_entrega-ifnull(compras_entregas.qtd_entregue,0)) from compras_itens left join compras_entregas on compras_entregas.id_compra_item = compras_itens.id
    where (compras_entregas.exclui is null or compras_entregas.exclui = 0) and compras_entregas.qtde_entrega-ifnull(compras_entregas.qtd_entregue,0) 
    and compras_itens.id_item = base.id_item and status in ('distribuido', 'producao', 'aguardando documentacao') 
     and pedido_dt <= dt_pedido
    ),0) as qtd_aberto
    from(
    select  ip.dt_pedido, ip.linha, ip.tipo, ip.pedido,ltrim(rtrim(ip.ref_go)) as invoice,
     ltrim(rtrim(ea.id_item_destino)) as id_item,
    ltrim(rtrim(ea.item_destino)) as item, ea.qtde qtde_sol, vlr_unit,
    qtde* vlr_unit as tt_valor
    
    from importacoes_pedidos ip
    left join entrada_agrupada  ea on ea.id_item_invoice = ip.cod_item and ref_go = invoice and ip.pedido = ea.pedido
    left join itens i on i.id = ip.cod_item
    left join itens iip on ea.id_item_destino = iip.id
     
    where  
    -- ip.dt_pedido > '2020-09-01' and 
    ip.secundario not in ('FRJC 1928 C1             ','FR PLASTICO              ',
     'FR METAL                 ','FR ACETATO               ','FRASCO 30ML              ')
     and ip.secundario like '%semi%'
     -- and ci.id is not null
     and ult_status <> 980
     and (tipo = 'oi' or (tipo = 'op' and tipo_linha = 'bs'))
      and (iip.codtipoitem = 006 )
    
     group by ip.dt_pedido, ip.linha, ip.tipo, ip.pedido,ip.ref_go,
           ea.qtde , vlr_unit ,ea.id_item_destino, item_destino
          
     ) as base
     
     left join compras_invoices ci on ci.pedido = base.pedido and ci.id_item = base.id_item and ci.linha = base.linha and ci.qtd = base.qtde_sol and ci.dt_invoice = base.dt_pedido and ci.exclui <> 1
    where ci.id_item is null
     ) as base2
     left join itens on itens.id = base2.id_item
     where codtipoitem is not null
     and codtipoitem = 006
      and codfornecedor <> 102606
     -- and fornecedor = 'KERING EYEWEAR SPA'
     and pedido <> 4542
     group by pedido, tipo, fornecedor,dt_pedido, invoice
     order by fornecedor asc, grife asc
     
 

 
 
" );
    return view( 'produtos.compras.lista_oi' )->with( 'oi', $oi );


  }
  public function importainvoice( Request $request ) {

    $cod_usuario = \Auth::id();
    $invoice = $request->invoice;
    $dt_invoice = $request->data;
    $etq_kering = $request->etq;
    $tipo_kering = $request->kering;
    if($tipo_kering==''){
    $tipo_kering = '';
    $tipo_tabela = '';
    }
    else{
      $tipo_kering = ",'".$tipo_kering."'";
      $tipo_tabela = ",tipo";
    }


    $verifica_invoices = \DB::select( "select invoice from compras_invoices 
		 where  invoice = '$invoice'
		group by invoice, dt_invoice " );


    if ( count( $verifica_invoices ) > 0 ) {

      $request->session()->flash( 'alert-warning', 'O número de invoice ' . $invoice . ' já existe no sistema.' );
      return redirect()->back();

    } else {

      $agora = date( '_d_m_Y_H_i' );

      $uploaddir = '/var/www/html/portal-gestao/storage/app/uploads/compras/invoices/invoice_' . $invoice . '_' . $agora;
      $uploadfile = $uploaddir . '.Xlsx';


      $erros = array();
 
      if ( move_uploaded_file( $_FILES[ 'arquivo' ][ 'tmp_name' ], $uploadfile ) ) {
		// dd('oi');

        if ( file_exists( $uploadfile ) ) {


          $reader = \PhpOffice\ PhpSpreadsheet\ IOFactory::createReader( "Xlsx" );

          $spreadsheet = $reader->load( $uploadfile );

          $sheet = $spreadsheet->getActiveSheet()->toArray();


          $i = '';


          foreach ( $sheet as $linha ) {


            if ( $i >= 1 ) {

              $item = $linha[ 0 ];


              $verifica = \DB::select( "select * from itens where secundario = '$item'" );


              if ( count( $verifica ) > 0 ) {
				  if($linha[2])
				  {
					  $custo2 = $linha[2];
				  }
				  else {
					  $custo2 = 0;
				  }
				  	
                $verifica = \DB::select( "insert into compras_invoices (`item`, `qtd`, `invoice`, `dt_invoice`, `custo`, `usado`,etq $tipo_tabela) values ('$linha[0]','$linha[1]','$invoice','$dt_invoice','$custo2','0','$etq_kering' $tipo_kering)" );


              } else {

                //$erros[] = 'Linha ' . $linha . ' ja foi importada. <br>';
				   //$request->session()->flash( 'alert-warning', 'Item ' . $item . ' não existe. </br>' );
				  
				  $erros[] = 'Item ' . $item . ' não existe. </br>';
                  $request->session()->flash('alert-warning2', $erros);


              }


            }


            $i++;

          }


        }

      }


      $itens = \DB::select( "select* from compras_invoices  where invoice = '$invoice' 
		 and (exclui is null or exclui = 0) " );
      //dd($itens);


      foreach ( $itens as $item ) {
        $saldo = $item->qtd;
        //echo ' realizado ' . $item->item . ' ' . $saldo . '<br>';


        $verifica_compra = \DB::select( "select *, compras_entregas.id id_entregas, qtde_entrega-ifnull(qtd_entregue,0) as falta_entrega, compras_entregas.id as id_compras_entregas, ifnull(qtd_entregue,0) entregue1
						from compras_itens
						left join compras_entregas on compras_entregas.id_compra_item = compras_itens.id
						left join compras on compras.id = id_compra
						where item = '$item->item'
						
						and compras_entregas.id_compra_item is not null
						 and qtde_entrega-ifnull(qtd_entregue,0) > 0 
						 and qtde_entrega-ifnull(qtd_entregue,0) <> 0 
						 and compras_itens.status in ('distribuido','producao', 'aguardando documentacao')
						 and (compras_entregas.exclui is null or compras_entregas.exclui = 0)
						
						order by pedido_dt asc, compras_entregas.qtde_entrega  desc 
						" );

        foreach ( $verifica_compra as $linha ) {
          $sobra_usado = \DB::select( "select*, qtd-usado sobra from compras_invoices  where id = '$item->id' and exclui is null and qtd-usado>0 " );
          if ( count( $sobra_usado ) > 0 ) {
            //dd($sobra_usado);

            if ( $linha->falta_entrega >= $sobra_usado[ 0 ]->sobra ) {
              $sobrausado = $sobra_usado[ 0 ]->sobra;
              $gravar_entrega = \DB::select( "update compras_entregas set qtd_entregue = ifnull($linha->entregue1,0)+$sobrausado where id = '$linha->id_entregas'" );

              $grava_compras_entregas_invoices = \DB::select( "INSERT INTO compras_entregas_invoices(item, id_compras_invoices, qtd_invoice, id_compras_entrega, qtd_entregue, status,invoice,dt_invoice,qtde_tabela_entregas) VALUES ('$linha->item','$item->id','$item->qtd','$linha->id_entregas','$sobrausado','PEDIDO ENTREGUE','$invoice','$item->dt_invoice','$linha->entregue1')" );

              $usado = \DB::select( "update compras_invoices set usado = usado+$sobrausado	  where id = '$item->id' " );

              echo 'consumido ' . $sobra_usado[ 0 ]->sobra . '<br>';
            }
            if ( $linha->falta_entrega < $sobra_usado[ 0 ]->sobra & $linha->falta_entrega > 0 ) {

              $gravar_entrega = \DB::select( "update compras_entregas set qtd_entregue = ifnull($linha->entregue1,0)+$linha->falta_entrega where id = '$linha->id_entregas'" );

              $grava_compras_entregas_invoices = \DB::select( "INSERT INTO compras_entregas_invoices(item, id_compras_invoices, qtd_invoice, id_compras_entrega, qtd_entregue, status,invoice,dt_invoice,qtde_tabela_entregas) VALUES ('$linha->item','$item->id','$item->qtd','$linha->id_entregas','$linha->falta_entrega','PEDIDO ENTREGUE','$invoice','$item->dt_invoice','$linha->entregue1')" );

              $usado = \DB::select( "update compras_invoices set usado = usado+$linha->falta_entrega	  where id = '$item->id' " );

              echo 'consumido2 ' . $linha->falta_entrega . '<br>';

            }


          }


        }


      }
    }
    $sobra_pedido1 = \DB::select( "select*, qtd-usado sobra from compras_invoices  where invoice = '$invoice' and exclui is null and qtd-usado>0 " );

    foreach ( $sobra_pedido1 as $linhas_sobra ) {
      $grava_compras_entregas_invoices = \DB::select( "INSERT INTO compras_entregas_invoices(item, id_compras_invoices, qtd_invoice, id_compras_entrega, qtd_entregue, status,invoice,dt_invoice,qtde_tabela_entregas,exclui) VALUES ('$linhas_sobra->item','$linhas_sobra->id','$linhas_sobra->qtd','','$linhas_sobra->sobra','SEM PEDIDO','$invoice','$linhas_sobra->dt_invoice','$linhas_sobra->qtd','0')" );

      echo 'sempedido ' . $linhas_sobra->sobra . '<br>';
    }
    $sempedido = \DB::select( "select item, sum(qtd_entregue) as qtd, invoice from compras_entregas_invoices where invoice = '$invoice' and status = 'SEM PEDIDO' and (compras_entregas_invoices.exclui is null or compras_entregas_invoices.exclui = 0)
		group by item,invoice" );
    if ( count( $sempedido ) > 0 ) {

      $sempedidototal = \DB::select( "select invoice, sum(qtd_entregue) as tt from compras_entregas_invoices where invoice = '$invoice' and status = 'SEM PEDIDO'  and (compras_entregas_invoices.exclui is null or compras_entregas_invoices.exclui = 0)" );

      $veririfcavalor = \DB::select( "select secundario, custos_2019.custo as custo_pedido, compras_invoices.custo as custo_invoice from custos_2019 left join compras_invoices on custos_2019.secundario = compras_invoices.item where custos_2019.custo <> compras_invoices.custo and invoice = '$invoice'" );
      //dd($veririfcavalor);

      return view( 'produtos.compras.relatorio_invoice' )->with( 'sempedido', $sempedido )->with( 'sempedidototal', $sempedidototal )->with( 'veririfcavalor', $veririfcavalor );


    } else {

      $request->session()->flash( 'alert-success', 'Invoice 100% correta, quantidade e valores' );
      return redirect()->back();
    }

    
  }
	public function TransformarEmPedido($idcompra){
		
		$dt_prevista = date('Y-m-d', strtotime('+90 days'));
		$note = 'Pré-pedido convertido';
		
		$corespedido = \DB::select( "Select cm.*, cin.*, concat(modelo_go,' ',cod_cor) as item , itens.id as id_item
		from compras_modelos cm 
		left join compras_itens_novos cin on cin.id_modelo = cm.id  
		left join itens on concat(modelo_go,' ',cod_cor) = itens.secundario
		where cm.id_compra = $idcompra 
		and (cin.exclui = 0 or cin.exclui is null) limit 1
		");
		
		foreach($corespedido as $cores){
		$novoitem = new\ App\ CompraItem();
        $novoitem->id_compra = $idcompra;
        $novoitem->dt_prevista = $dt_prevista;
        $novoitem->note = $note;
        $novoitem->item = $cores->item;
        $novoitem->id_item = $cores->id_item;
        $novoitem->qtde = $cores->quantidade;
        $novoitem->status = "ABERTO";
        $novoitem->origem = "PEDIDO";
        $novoitem->solicitante = "BRASIL";
        $novoitem->pedido_dt = date( "Y-m-d" );
        $novoitem->dt_status = date( "Y-m-d H:i:s" );
        $novoitem->save();
			
		}
		
		$corespedido = \DB::select( "update compras set tipo = 'PEDIDO', status = 'ABERTO' where id = $idcompra");
		
		return redirect()->back();
	}
	public function ModeloNovoCoresUpload( Request $request ) {

    $usuario = \Auth::id();




      $agora = date( '_d_m_Y_H_i' );

      $uploaddir = '/var/www/html/portal-gestao/storage/app/uploads/compras/arquivos/criacao_de_cores_' . $agora;
      $uploadfile = $uploaddir . '.Xlsx';


      $erros = array();

      if ( move_uploaded_file( $_FILES[ 'arquivo' ][ 'tmp_name' ], $uploadfile ) ) {

        if ( file_exists( $uploadfile ) ) {


          $reader = \PhpOffice\ PhpSpreadsheet\ IOFactory::createReader( "Xlsx" );

          $spreadsheet = $reader->load( $uploadfile );

          $sheet = $spreadsheet->getActiveSheet()->toArray();


          $i = '';


          foreach ( $sheet as $linhas ) {


            if ( $i >= 1 && $linhas[0] <>'' && $linhas[2] <>'' && $linhas[3] <>'' && $linhas[6] <>'' && $linhas[14] <>'') {$id_modelo = $linhas[0];
$cod_cor = $linhas[1];
$cod_cor_fornecedor = $linhas[2];
$cor_frente1 = $linhas[3];
$cor_frente2 = $linhas[4];
$cor_frente3 = $linhas[5];
$cor_haste1 = $linhas[6];
$cor_haste2 = $linhas[7];
$cor_haste3 = $linhas[8];
$cor_ponteira = $linhas[9];
$cor_logo = $linhas[10];
$cor_lente = $linhas[11];
$custo = $linhas[12];
$clasitem = $linhas[13];
$quantidade = $linhas[14];
$cor_frente1_jde = $linhas[15];
$cor_frente2_jde = $linhas[16];
$cor_haste1_jde = $linhas[17];
$cor_haste2_jde = $linhas[18];
$cor_lente_jde = $linhas[19];
$cor_ponteira_jde = $linhas[20];
$cor_logo_jde = $linhas[21];
							







             $inseremodelo = \DB::select( "INSERT INTO `compras_itens_novos`( `id_modelo`, `cod_cor`, `cod_cor_fornecedor`, `cor_frente1`, `cor_frente2`, `cor_frente3`, `cor_haste1`, `cor_haste2`, `cor_haste3`, `cor_ponteira`, `cor_logo`, `cor_lente`, `custo`, `clasitem`, `quantidade`, `cor_frente1_jde`, `cor_frente2_jde`, `cor_haste1_jde`, `cor_haste2_jde`, `cor_lente_jde`, `cor_ponteira_jde`, `cor_logo_jde`, `usuario`, `exclui`,`arquivo`) VALUES ('$id_modelo','$cod_cor','$cod_cor_fornecedor','$cor_frente1','$cor_frente2','$cor_frente3','$cor_haste1','$cor_haste2','$cor_haste3','$cor_ponteira','$cor_logo','$cor_lente','$custo','$clasitem','$quantidade','$cor_frente1_jde','$cor_frente2_jde','$cor_haste1_jde','$cor_haste2_jde','$cor_lente_jde','$cor_ponteira_jde','$cor_logo_jde','$usuario','0','$uploadfile')");

				
			


            }
			  
			  
			  elseif ($i >= 2){
				  $linhaerro = $i+1;
				 $request->session()->flash( 'alert-warning', 'A linha '.$linhaerro.' tem campo obrigatório não preenchido, AS LINHAS ANTERIORES FORAM IMPORTADAS.' ); 
				  return redirect()->back();
			  }


            $i++;

          }


        }

      }


              $request->session()->flash( 'alert-success', 'Cores criadas com sucesso' );

      
      return redirect()->back();
    

    
  }
	public function ModeloNovoUpload( Request $request ) {

    $usuario = \Auth::id();




      $agora = date( '_d_m_Y_H_i' );

      $uploaddir = '/var/www/html/portal-gestao/storage/app/uploads/compras/arquivos/criacao_de_modelos_' . $agora;
      $uploadfile = $uploaddir . '.Xlsx';


      $erros = array();

      if ( move_uploaded_file( $_FILES[ 'arquivo' ][ 'tmp_name' ], $uploadfile ) ) {

        if ( file_exists( $uploadfile ) ) {


          $reader = \PhpOffice\ PhpSpreadsheet\ IOFactory::createReader( "Xlsx" );

          $spreadsheet = $reader->load( $uploadfile );

          $sheet = $spreadsheet->getActiveSheet()->toArray();


          $i = '';


          foreach ( $sheet as $linhas ) {


            if ( $i >= 2 && $linhas[0] <>'' && $linhas[1] <>'' && $linhas[2] <>'' && $linhas[7] <>'' && $linhas[8] <>'' && $linhas[9] <>'' && $linhas[12] <>'' && $linhas[13] <>'' && $linhas[15] <>'' && $linhas[16] <>'' && $linhas[21] <>'' && $linhas[22] <>'' && $linhas[30] <>'' && $linhas[31] <>'' && $linhas[42] <>'' && $linhas[43] <>'' && $linhas[44] <>'' ) {
				$id_compra = $linhas[0];
$tipo = $linhas[1];
$id_fornecedor = $linhas[2];
$cod_fabrica = $linhas[3];
$modelo_go = $linhas[4];
$cod_molde_frente = $linhas[5];
$cod_molde_haste = $linhas[6];
$grife = $linhas[7];
$tipo_modelo = $linhas[8];
$agrupamento = $linhas[9];
$ncm = $linhas[10];
$armazenamento = $linhas[11];
$ano_mod = $linhas[12];
$col_mod = $linhas[13];
$class_mod = $linhas[14];
$genero = $linhas[15];
$idade = $linhas[16];
$linha = $linhas[17];
$faixa = $linhas[18];
$col_estilo = $linhas[19];
$tecnologia = $linhas[20];
$material_frente = $linhas[21];
$material_haste = $linhas[22];
$material_logo = $linhas[23];
$plaqueta = $linhas[24];
$material_plaqueta = $linhas[25];
$material_lente = $linhas[26];
$material_ponteira = $linhas[27];
$curvatura_lente = $linhas[28];
$formato = $linhas[29];
$fixacao = $linhas[30];
$graduacao = $linhas[31];
$tamanho_lente = $linhas[32];
$tamanho_frente = $linhas[33];
$altura_lente = $linhas[34];
$tamanho_ponte = $linhas[35];
$tamanho_haste = $linhas[36];
$gravacao_lente_direita = $linhas[37];
$gravacao_lente_esquerda = $linhas[38];
$gravacao_haste_direita = $linhas[39];
$gravacao_haste_esquerda = $linhas[40];
$gravacao_logo = $linhas[41];
$aprovacao_conceito = $linhas[42];
$aprovacao_prototipo_design = $linhas[43];
$aprovacao_prototipo_cores = $linhas[44];







             $inseremodelo = \DB::select( "INSERT INTO `compras_modelos`( `id_compra`, `tipo`, `id_fornecedor`, `cod_fabrica`, `modelo_go`, `cod_molde_frente`, `cod_molde_haste`, `grife`, `tipo_modelo`, `agrupamento`, `ncm`, `armazenamento`, `ano_mod`, `col_mod`, `class_mod`, `genero`, `idade`, `linha`, `faixa`, `col_estilo`, `tecnologia`, `material_frente`, `material_haste`, `material_logo`, `plaqueta`, `material_plaqueta`, `material_lente`, `material_ponteira`, `curvatura_lente`, `formato`, `fixacao`, `graduacao`, `tamanho_lente`, `tamanho_frente`, `altura_lente`, `tamanho_ponte`, `tamanho_haste`, `gravacao_lente_direita`, `gravacao_lente_esquerda`, `gravacao_haste_direita`, `gravacao_haste_esquerda`, `gravacao_logo`, `aprovacao_conceito`, `aprovacao_prototipo_design`, `aprovacao_prototipo_cores`, `usuario`,`arquivo`) VALUES ('$id_compra', '$tipo', '$id_fornecedor', '$cod_fabrica', '$modelo_go', '$cod_molde_frente', '$cod_molde_haste', '$grife', '$tipo_modelo', '$agrupamento', '$ncm', '$armazenamento', '$ano_mod', '$col_mod', '$class_mod', '$genero', '$idade', '$linha', '$faixa', '$col_estilo', '$tecnologia', '$material_frente', '$material_haste', '$material_logo', '$plaqueta', '$material_plaqueta', '$material_lente', '$material_ponteira', '$curvatura_lente', '$formato', '$fixacao', '$graduacao', '$tamanho_lente', '$tamanho_frente', '$altura_lente', '$tamanho_ponte', '$tamanho_haste', '$gravacao_lente_direita', '$gravacao_lente_esquerda', '$gravacao_haste_direita', '$gravacao_haste_esquerda', '$gravacao_logo', '$aprovacao_conceito', '$aprovacao_prototipo_design', '$aprovacao_prototipo_cores', '$usuario','$uploadfile')");

				
			


            }
			  elseif ($i >= 2){
				  $linhaerro = $i+1;
				 $request->session()->flash( 'alert-warning', 'A linha '.$linhaerro.' tem campo obrigatório não preenchido, AS LINHAS ANTERIORES FORAM IMPORTADAS.' ); 
				  return redirect()->back();
			  }


            $i++;

          }


        }

      }


              $request->session()->flash( 'alert-success', 'Modelos criados com sucesso' );

      
      return redirect()->back();
    

    
  }
	public function UploadArquivoModelo( $idmodelo, $tipo,Request $request ) {
		
		
		 ini_set( 'memory_limit', -1 );

    $agora = date( '_d_m_Y_H_i' );
	$tipo = $tipo;
	$idmodelo = $request->idmodelo;
	$obs = $request->obs;
	$data = $request->data;
	$arquivo_up = $_FILES['arquivo']['name'];
	$extensao = pathinfo($arquivo_up);
	$extensao = $extensao['extension'];
		
		
    $uploaddir1 = '/var/www/html/portal-gestao';
	$uploaddir2 = '/storage/';
	$uploaddir3 = 'app/';
	$uploaddir4 = 'uploads/compras/arquivos/'.$tipo.'_'. $idmodelo . '_' . $agora;
    $uploadfile = $uploaddir1.$uploaddir2.$uploaddir3.$uploaddir4 . '.'.$extensao;
	  

	  
	
	

    if ( move_uploaded_file( $_FILES[ 'arquivo' ][ 'tmp_name' ], $uploadfile ) ) {
			
		 

      if ( file_exists( $uploadfile ) ) {
		  
		  $cod_usuario = \Auth::id();
		$uploadfilearquivo = $uploaddir2.$uploaddir4 . '.'.$extensao;
		  $inserearquivos = \DB::select( "INSERT INTO `compras_modelos_arquivos`(`id_modelo`, `tipo`, `arquivo`, `obs`, `data`, `usuario`, `exclui`, `extensao`) VALUES ('$idmodelo','$tipo','$uploadfilearquivo','$obs', '$data','$cod_usuario','0','$extensao')");
	
	  }
		
	}
		return redirect()->back();
		
	}
	
	public function ModeloNovoHistorico( Request $request ) {
		ini_set( 'memory_limit', -1 );

    $agora = date( '_d_m_Y_H_i' );
	$usuario = \Auth::id();
	$idmodelo = $request->idmodelo;
	$obs = $request->obs;
	$data = $request->data;
	$arquivo_up = $_FILES['arquivo']['name'];
	$extensao = pathinfo($arquivo_up);
	$extensao = $extensao['extension'];
		
		
    $uploaddir1 = '/var/www/html/portal-gestao';
	$uploaddir2 = '/storage/';
	$uploaddir3 = 'app/';
	$uploaddir4 = 'uploads/compras/arquivos/historico_'. $idmodelo . '_' . $agora;
    $uploadfile = $uploaddir1.$uploaddir2.$uploaddir3.$uploaddir4 . '.'.$extensao;
	  

	  
	 
	

    if ( move_uploaded_file( $_FILES[ 'arquivo' ][ 'tmp_name' ], $uploadfile ) ) {
			
		 

      if ( file_exists( $uploadfile ) ) {
		  
		  $cod_usuario = \Auth::id();
		$uploadfilearquivo = $uploaddir2.$uploaddir4 . '.'.$extensao;
		  
		$historico = \DB::select( "INSERT INTO `compras_historico`(`id_tabela`, `tabela`, `id_usuario`, `historico`, `arquivo`, `data`, `obs`, `extensao`) VALUES (	'$request->idmodelo','compras_modelos','$usuario','histórico','$uploadfilearquivo','$request->data','$request->obs','$extensao') " );
	
	  }
		
	}
		return redirect()->back();
		
	}
  public function ModeloNovoCopiar( Request $request ) {

    $usuario = \Auth::id();

	 
	  $idmodelo = $request->idmodelo;


    if ( isset( $request->id_compra ) ) {
      $id_compra = ",id_compra = '" . $request->id_compra . "'";
    } else {
      $id_compra = '';
    }
    if ( isset( $request->tipo ) ) {
      $tipo = ",tipo = '" . $request->tipo . "'";
    } else {
      $tipo = '';
    }
    if ( isset( $request->id_fornecedor ) ) {
      $id_fornecedor = ",id_fornecedor = '" . $request->id_fornecedor . "'";
    } else {
      $id_fornecedor = '';
    }
    if ( isset( $request->cod_fabrica ) ) {
      $cod_fabrica = ",cod_fabrica = '" . $request->cod_fabrica . "'";
    } else {
      $cod_fabrica = '';
    }
    if ( isset( $request->modelo_go ) ) {
      $modelo_go = ",modelo_go = '" . $request->modelo_go . "'";
    } else {
      $modelo_go = '';
    }
    if ( isset( $request->cod_molde_frente ) ) {
      $cod_molde_frente = ",cod_molde_frente = '" . $request->cod_molde_frente . "'";
    } else {
      $cod_molde_frente = '';
    }
    if ( isset( $request->cod_molde_haste ) ) {
      $cod_molde_haste = ",cod_molde_haste = '" . $request->cod_molde_haste . "'";
    } else {
      $cod_molde_haste = '';
    }
    if ( isset( $request->grife ) ) {
      $grife = ",grife = '" . $request->grife . "'";
    } else {
      $grife = '';
    }
    if ( isset( $request->tipo_modelo ) ) {
      $tipo_modelo = ",tipo_modelo = '" . $request->tipo_modelo . "'";
    } else {
      $tipo_modelo = '';
    }
    if ( isset( $request->agrupamento ) ) {
      $agrupamento = ",agrupamento = '" . $request->agrupamento . "'";
    } else {
      $agrupamento = '';
    }
    if ( isset( $request->ncm ) ) {
      $ncm = ",ncm = '" . $request->ncm . "'";
    } else {
      $ncm = '';
    }
    if ( isset( $request->armazenamento ) ) {
      $armazenamento = ",armazenamento = '" . $request->armazenamento . "'";
    } else {
      $armazenamento = '';
    }
    if ( isset( $request->ano_mod ) ) {
      $ano_mod = ",ano_mod = '" . $request->ano_mod . "'";
    } else {
      $ano_mod = '';
    }
    if ( isset( $request->col_mod ) ) {
      $col_mod = ",col_mod = '" . $request->col_mod . "'";
    } else {
      $col_mod = '';
    }
    if ( isset( $request->class_mod ) ) {
      $class_mod = ",class_mod = '" . $request->class_mod . "'";
    } else {
      $class_mod = '';
    }
    if ( isset( $request->genero ) ) {
      $genero = ",genero = '" . $request->genero . "'";
    } else {
      $genero = '';
    }
    if ( isset( $request->idade ) ) {
      $idade = ",idade = '" . $request->idade . "'";
    } else {
      $idade = '';
    }
    if ( isset( $request->linha ) ) {
      $linha = ",linha = '" . $request->linha . "'";
    } else {
      $linha = '';
    }
    if ( isset( $request->faixa ) ) {
      $faixa = ",faixa = '" . $request->faixa . "'";
    } else {
      $faixa = '';
    }
    if ( isset( $request->col_estilo ) ) {
      $col_estilo = ",col_estilo = '" . $request->col_estilo . "'";
    } else {
      $col_estilo = '';
    }
    if ( isset( $request->tecnologia ) ) {
      $tecnologia = ",tecnologia = '" . $request->tecnologia . "'";
    } else {
      $tecnologia = '';
    }
    if ( isset( $request->material_frente ) ) {
      $material_frente = ",material_frente = '" . $request->material_frente . "'";
    } else {
      $material_frente = '';
    }
    if ( isset( $request->material_haste ) ) {
      $material_haste = ",material_haste = '" . $request->material_haste . "'";
    } else {
      $material_haste = '';
    }
    if ( isset( $request->material_logo ) ) {
      $material_logo = ",material_logo = '" . $request->material_logo . "'";
    } else {
      $material_logo = '';
    }
    if ( isset( $request->plaqueta ) ) {
      $plaqueta = ",plaqueta = '" . $request->plaqueta . "'";
    } else {
      $plaqueta = '';
    }
    if ( isset( $request->material_plaqueta ) ) {
      $material_plaqueta = ",material_plaqueta = '" . $request->material_plaqueta . "'";
    } else {
      $material_plaqueta = '';
    }
    if ( isset( $request->material_lente ) ) {
      $material_lente = ",material_lente = '" . $request->material_lente . "'";
    } else {
      $material_lente = '';
    }
    if ( isset( $request->material_ponteira ) ) {
      $material_ponteira = ",material_ponteira = '" . $request->material_ponteira . "'";
    } else {
      $material_ponteira = '';
    }
    if ( isset( $request->curvatura_lente ) ) {
      $curvatura_lente = ",curvatura_lente = '" . $request->curvatura_lente . "'";
    } else {
      $curvatura_lente = '';
    }
    if ( isset( $request->formato ) ) {
      $formato = ",formato = '" . $request->formato . "'";
    } else {
      $formato = '';
    }
    if ( isset( $request->fixacao ) ) {
      $fixacao = ",fixacao = '" . $request->fixacao . "'";
    } else {
      $fixacao = '';
    }
    if ( isset( $request->graduacao ) ) {
      $graduacao = ",graduacao = '" . $request->graduacao . "'";
    } else {
      $graduacao = '';
    }
    if ( isset( $request->tamanho_lente ) ) {
      $tamanho_lente = ",tamanho_lente = '" . $request->tamanho_lente . "'";
    } else {
      $tamanho_lente = '';
    }
    if ( isset( $request->tamanho_frente ) ) {
      $tamanho_frente = ",tamanho_frente = '" . $request->tamanho_frente . "'";
    } else {
      $tamanho_frente = '';
    }
    if ( isset( $request->altura_lente ) ) {
      $altura_lente = ",altura_lente = '" . $request->altura_lente . "'";
    } else {
      $altura_lente = '';
    }
    if ( isset( $request->tamanho_ponte ) ) {
      $tamanho_ponte = ",tamanho_ponte = '" . $request->tamanho_ponte . "'";
    } else {
      $tamanho_ponte = '';
    }
    if ( isset( $request->tamanho_haste ) ) {
      $tamanho_haste = ",tamanho_haste = '" . $request->tamanho_haste . "'";
    } else {
      $tamanho_haste = '';
    }
    if ( isset( $request->gravacao_lente_direita ) ) {
      $gravacao_lente_direita = ",gravacao_lente_direita = '" . $request->gravacao_lente_direita . "'";
    } else {
      $gravacao_lente_direita = '';
    }
    if ( isset( $request->gravacao_lente_esquerda ) ) {
      $gravacao_lente_esquerda = ",gravacao_lente_esquerda = '" . $request->gravacao_lente_esquerda . "'";
    } else {
      $gravacao_lente_esquerda = '';
    }
    if ( isset( $request->gravacao_haste_direita ) ) {
      $gravacao_haste_direita = ",gravacao_haste_direita = '" . $request->gravacao_haste_direita . "'";
    } else {
      $gravacao_haste_direita = '';
    }
    if ( isset( $request->gravacao_haste_esquerda ) ) {
      $gravacao_haste_esquerda = ",gravacao_haste_esquerda = '" . $request->gravacao_haste_esquerda . "'";
    } else {
      $gravacao_haste_esquerda = '';
    }
    if ( isset( $request->gravacao_logo ) ) {
      $gravacao_logo = ",gravacao_logo = '" . $request->gravacao_logo . "'";
    } else {
      $gravacao_logo = '';
    }
    if ( isset( $request->aprovacao_conceito ) ) {
      $aprovacao_conceito = ",aprovacao_conceito = '" . $request->aprovacao_conceito . "'";
    } else {
      $aprovacao_conceito = '';
    }
    if ( isset( $request->aprovacao_prototipo_design ) ) {
      $aprovacao_prototipo_design = ",aprovacao_prototipo_design = '" . $request->aprovacao_prototipo_design . "'";
    } else {
      $aprovacao_prototipo_design = '';
    }
    if ( isset( $request->aprovacao_prototipo_cores ) ) {
      $aprovacao_prototipo_cores = ",aprovacao_prototipo_cores = '" . $request->aprovacao_prototipo_cores . "'";
    } else {
      $aprovacao_prototipo_cores = '';
    }


    

   

    if ( $request->infomodelo ==	 'novo' ) {
	
		
      $criamodelo = \DB::select( "insert compras_modelos (`usuario`) values ($usuario)" );

      $idmodelonovo = \DB::select( "select* from compras_modelos order by id desc limit 1" );

      $idnovo = $idmodelonovo[ 0 ]->id;

      $copiainfo = \DB::select( "update  compras_modelos set  usuario = '$usuario' $id_compra $tipo $id_fornecedor $cod_fabrica $modelo_go $cod_molde_frente $cod_molde_haste $grife $tipo_modelo $agrupamento $ncm $armazenamento $ano_mod $col_mod $class_mod $genero $idade $linha $faixa $col_estilo $tecnologia $material_frente $material_haste $material_logo $plaqueta $material_plaqueta $material_lente $material_ponteira $curvatura_lente $formato $fixacao $graduacao $tamanho_lente $tamanho_frente $altura_lente $tamanho_ponte $tamanho_haste $gravacao_lente_direita $gravacao_lente_esquerda $gravacao_haste_direita $gravacao_haste_esquerda $gravacao_logo $aprovacao_conceito $aprovacao_prototipo_design $aprovacao_prototipo_cores where id = '$idnovo' " );
		
		$cod_usuario = \Auth::id();
		$obs_historico = 'Criação do modelo de id '.$idnovo.' a partir dos dados do id '.$idmodelo.' Dados copiados'.$id_compra.$tipo.$id_fornecedor.$cod_fabrica.$modelo_go.$cod_molde_frente.$cod_molde_haste.$grife.$tipo_modelo.$agrupamento.$ncm.$armazenamento.$ano_mod.$col_mod.$class_mod.$genero.$idade.$linha.$faixa.$col_estilo.$tecnologia.$material_frente.$material_haste.$material_logo.$plaqueta.$material_plaqueta.$material_lente.$material_ponteira.$curvatura_lente.$formato.$fixacao.$graduacao.$tamanho_lente.$tamanho_frente.$altura_lente.$tamanho_ponte.$tamanho_haste.$gravacao_lente_direita.$gravacao_lente_esquerda.$gravacao_haste_direita.$gravacao_haste_esquerda.$gravacao_logo.$aprovacao_conceito.$aprovacao_prototipo_design.$aprovacao_prototipo_cores;
		$obs_historico_ajustado = str_replace( [ "'" ], ' ', $obs_historico  );
	$historico = \DB::select( "INSERT INTO `compras_historico`(`id_tabela`, `tabela`, `id_usuario`, `obs`, `historico`) VALUES (	'$idnovo','compras_modelos','$cod_usuario','$obs_historico_ajustado','Criação modelo') " );

      if ( isset($request->cor) ) {
        $verificacores = \DB::select( "select* from compras_itens_novos where id_modelo = $idmodelo and exclui = 0" );

        foreach ( $verificacores as $cores ) {
			if(isset($idnovo)){
				$idgrava = $idnovo;
			}
			else{
				$idgrava = $idmodelo;
			}
          $inserecores = \DB::select( "INSERT INTO `compras_itens_novos`( `id_modelo`, `cod_cor`, `cod_cor_fornecedor`, `cor_frente1`, `cor_frente2`, `cor_frente3`, `cor_haste1`, `cor_haste2`, `cor_haste3`, `cor_ponteira`, `cor_logo`, `cor_lente`, `custo`, `clasitem`, `quantidade`,  `usuario`,  `exclui`, `cor_frente1_jde`, `cor_frente2_jde`, `cor_haste1_jde`, `cor_haste2_jde`, `cor_lente_jde`, `cor_ponteira_jde`, `cor_logo_jde`) VALUES ('$idgrava','$cores->cod_cor','$cores->cod_cor_fornecedor','$cores->cor_frente1','$cores->cor_frente2','$cores->cor_frente3','$cores->cor_haste1','$cores->cor_haste2','$cores->cor_haste3','$cores->cor_ponteira','$cores->cor_logo','$cores->cor_lente','$cores->custo','$cores->clasitem','$cores->quantidade','$usuario','0','$cores->cor_frente1_jde','$cores->cor_frente2_jde','$cores->cor_haste1_jde','$cores->cor_haste2_jde','$cores->cor_lente_jde','$cores->cor_ponteira_jde','$cores->cor_logo_jde')" );
			
		$cod_usuario = \Auth::id();
		$obs_historico = 'Criação das cores copiadas a partir dos dados do id '.$idmodelo.' Dados copiados 	 /  '.$cores->cod_cor.' /  '.$cores->cod_cor_fornecedor.' /  '.$cores->cor_frente1.' /  '.$cores->cor_frente2.' /  '.$cores->cor_frente3.' /  '.$cores->cor_haste1.' /  '.$cores->cor_haste2.' /  '.$cores->cor_haste3.' /  '.$cores->cor_ponteira.' /  '.$cores->cor_logo.' /  '.$cores->cor_lente.' /  '.$cores->custo.' /  '.$cores->clasitem.' /  '.$cores->quantidade;
		$obs_historico_ajustado = str_replace( [ "'" ], ' ', $obs_historico  );
	$historico = \DB::select( "INSERT INTO `compras_historico`(`id_tabela`, `tabela`, `id_usuario`, `obs`, `historico`) VALUES (	'$idnovo','compras_modelos','$cod_usuario','$obs_historico_ajustado',' Criação de cores') " );
        }
       


      }
		 $request->session()->flash( 'alert-success', 'Modelo novo criado com sucesso' );
        return redirect( "/compras/pedido/modelo/" . $idgrava );

    } else {


      $copiainfo = \DB::select( "update  compras_modelos set usuario = '$usuario' $id_compra $tipo $id_fornecedor $cod_fabrica $modelo_go $cod_molde_frente $cod_molde_haste $grife $tipo_modelo $agrupamento $ncm $armazenamento $ano_mod $col_mod $class_mod $genero $idade $linha $faixa $col_estilo $tecnologia $material_frente $material_haste $material_logo $plaqueta $material_plaqueta $material_lente $material_ponteira $curvatura_lente $formato $fixacao $graduacao $tamanho_lente $tamanho_frente $altura_lente $tamanho_ponte $tamanho_haste $gravacao_lente_direita $gravacao_lente_esquerda $gravacao_haste_direita $gravacao_haste_esquerda $gravacao_logo $aprovacao_conceito $aprovacao_prototipo_design $aprovacao_prototipo_cores where id = '$request->infomodelo' " );
		
		$cod_usuario = \Auth::id();
		$obs_historico = 'Dados copiados do modelo de id '.$request->infomodelo.' a partir dos dados do id '.$idmodelo.' Dados copiados'.$id_compra.$tipo.$id_fornecedor.$cod_fabrica.$modelo_go.$cod_molde_frente.$cod_molde_haste.$grife.$tipo_modelo.$agrupamento.$ncm.$armazenamento.$ano_mod.$col_mod.$class_mod.$genero.$idade.$linha.$faixa.$col_estilo.$tecnologia.$material_frente.$material_haste.$material_logo.$plaqueta.$material_plaqueta.$material_lente.$material_ponteira.$curvatura_lente.$formato.$fixacao.$graduacao.$tamanho_lente.$tamanho_frente.$altura_lente.$tamanho_ponte.$tamanho_haste.$gravacao_lente_direita.$gravacao_lente_esquerda.$gravacao_haste_direita.$gravacao_haste_esquerda.$gravacao_logo.$aprovacao_conceito.$aprovacao_prototipo_design.$aprovacao_prototipo_cores;
		$obs_historico_ajustado = str_replace( [ "'" ], ' ', $obs_historico  );
	$historico = \DB::select( "INSERT INTO `compras_historico`(`id_tabela`, `tabela`, `id_usuario`, `obs`, `historico`) VALUES (	'$request->infomodelo','compras_modelos','$cod_usuario','$obs_historico_ajustado','Cópia de dados') " );
		
		if ( isset($request->cor) ) {
        $verificacores = \DB::select( "select* from compras_itens_novos where id_modelo = $idmodelo and exclui = 0" );

        foreach ( $verificacores as $cores ) {
			if(isset($idnovo)){
				$idgrava = $idnovo;
			}
			else{
				$idgrava = $idmodelo;
			}

          $inserecores = \DB::select( "INSERT INTO `compras_itens_novos`( `id_modelo`, `cod_cor`, `cod_cor_fornecedor`, `cor_frente1`, `cor_frente2`, `cor_frente3`, `cor_haste1`, `cor_haste2`, `cor_haste3`, `cor_ponteira`, `cor_logo`, `cor_lente`, `custo`, `clasitem`, `quantidade`,  `usuario`,  `exclui`, `cor_frente1_jde`, `cor_frente2_jde`, `cor_haste1_jde`, `cor_haste2_jde`, `cor_lente_jde`, `cor_ponteira_jde`, `cor_logo_jde`) VALUES ('$idgrava','$cores->cod_cor','$cores->cod_cor_fornecedor','$cores->cor_frente1','$cores->cor_frente2','$cores->cor_frente3','$cores->cor_haste1','$cores->cor_haste2','$cores->cor_haste3','$cores->cor_ponteira','$cores->cor_logo','$cores->cor_lente','$cores->custo','$cores->clasitem','$cores->quantidade','$usuario','0','$cores->cor_frente1_jde','$cores->cor_frente2_jde','$cores->cor_haste1_jde','$cores->cor_haste2_jde','$cores->cor_lente_jde','$cores->cor_ponteira_jde','$cores->cor_logo_jde')" );
			
			$cod_usuario = \Auth::id();
		$obs_historico = 'Criação das cores copiadas a partir dos dados do id '.$idmodelo.' Dados copiados 	:  '.$cores->cod_cor.' /  '.$cores->cod_cor_fornecedor.' /  '.$cores->cor_frente1.' /  '.$cores->cor_frente2.' /  '.$cores->cor_frente3.' /  '.$cores->cor_haste1.' /  '.$cores->cor_haste2.' /  '.$cores->cor_haste3.' /  '.$cores->cor_ponteira.' /  '.$cores->cor_logo.' /  '.$cores->cor_lente.' /  '.$cores->custo.' /  '.$cores->clasitem.' /  '.$cores->quantidade;
		$obs_historico_ajustado = str_replace( [ "'" ], ' ', $obs_historico  );
	$historico = \DB::select( "INSERT INTO `compras_historico`(`id_tabela`, `tabela`, `id_usuario`, `obs`, `historico`) VALUES (	'$request->infomodelo','compras_modelos','$cod_usuario','$obs_historico_ajustado','Cópia de cores') " );
        }
       


      }
      $request->session()->flash( 'alert-success', 'Dados alterados sucesso' );
      return redirect( "/compras/pedido/modelo/" . $request->infomodelo );
    }


  }
  public function ModeloNovoInsere($idcompra) {
	 $compras = \DB::select( "select compras.*, ad.nome from compras left join addressbook ad on ad.id = id_fornecedor  where compras.id= $idcompra");
	 
    
	  return view( 'produtos.compras.modelo_novo' )->with( 'compras', $compras );
  }
  public function ModeloNovoInsereGrava( Request $request ) {

    $usuario = \Auth::id();

    $insere = \DB::select( "INSERT INTO `compras_modelos`(`id_compra`, `tipo`, `id_fornecedor`, `cod_fabrica`, `modelo_go`, `cod_molde_frente`, `cod_molde_haste`, `grife`, `tipo_modelo`, `agrupamento`, `ncm`, `armazenamento`, `ano_mod`, `col_mod`, `class_mod`, `genero`, `idade`, `linha`, `faixa`, `col_estilo`, `tecnologia`, `material_frente`, `material_haste`, `material_logo`, `plaqueta`, `material_plaqueta`, `material_lente`, `material_ponteira`, `curvatura_lente`, `formato`, `fixacao`, `graduacao`, `tamanho_lente`, `tamanho_frente`, `altura_lente`, `tamanho_ponte`, `tamanho_haste`, `gravacao_lente_direita`, `gravacao_lente_esquerda`, `gravacao_haste_direita`, `gravacao_haste_esquerda`, `gravacao_logo`, `aprovacao_conceito`, `aprovacao_prototipo_design`, `aprovacao_prototipo_cores`, `usuario`) VALUES ('$request->id_compra','$request->tipo','$request->fornecedor','$request->codfabrica','$request->modelo','$request->codmoldefrente','$request->codmoldehaste','$request->grife','$request->tipomodelo','$request->agrupamento','$request->ncm','$request->armazenamento','$request->anomod','$request->colmod','$request->clasmod','$request->genero','$request->idade','$request->linha','$request->categoria','$request->colecaoestilo', '$request->tecnologia','$request->materialfrente','$request->materialhaste','$request->materiallogo','$request->plaqueta','$request->materialplaqueta','$request->materiallente','$request->materialponteira','$request->curvatura_lente','$request->formato','$request->fixacao','$request->graduacao','$request->tamanholente','$request->tamanhofrente','$request->alturalente','$request->tamanhoponte','$request->tamanhohaste','$request->lentedireita','$request->lenteesquerda','$request->hastedireita','$request->hasteesquerda','$request->gravacaologo','$request->aprovacaoconceito','$request->aprovacaoprototipodesign','$request->aprovacaoprototipocores','$usuario' )" );

    $modelos = \DB::select( "Select* from compras_modelos order by id desc limit 1" );
	  
	 $cod_usuario = \Auth::id();
		$obs_historico = 'Modelo criado com os dados  '.$modelos[0]->id_compra.' /  '.$modelos[0]->tipo.' /  '.$modelos[0]->id_fornecedor.' /  '.$modelos[0]->cod_fabrica.' /  '.$modelos[0]->modelo_go.' /  '.$modelos[0]->cod_molde_frente.' /  '.$modelos[0]->cod_molde_haste.' /  '.$modelos[0]->grife.' /  '.$modelos[0]->tipo_modelo.' /  '.$modelos[0]->agrupamento.' /  '.$modelos[0]->ncm.' /  '.$modelos[0]->armazenamento.' /  '.$modelos[0]->ano_mod.' /  '.$modelos[0]->col_mod.' /  '.$modelos[0]->class_mod.' /  '.$modelos[0]->genero.' /  '.$modelos[0]->idade.' /  '.$modelos[0]->linha.' /  '.$modelos[0]->faixa.' /  '.$modelos[0]->col_estilo.', '.$modelos[0]->tecnologia.' /  '.$modelos[0]->material_frente.' /  '.$modelos[0]->material_haste.' /  '.$modelos[0]->material_logo.' /  '.$modelos[0]->plaqueta.' /  '.$modelos[0]->material_plaqueta.' /  '.$modelos[0]->material_lente.' /  '.$modelos[0]->material_ponteira.' /  '.$modelos[0]->curvatura_lente.' /  '.$modelos[0]->formato.' /  '.$modelos[0]->fixacao.' /  '.$modelos[0]->graduacao.' /  '.$modelos[0]->tamanho_lente.' /  '.$modelos[0]->tamanho_frente.' /  '.$modelos[0]->altura_lente.' /  '.$modelos[0]->tamanho_ponte.' /  '.$modelos[0]->tamanho_haste.' /  '.$modelos[0]->gravacao_lente_direita.' /  '.$modelos[0]->gravacao_lente_esquerda.' /  '.$modelos[0]->gravacao_haste_direita.' /  '.$modelos[0]->gravacao_haste_esquerda.' /  '.$modelos[0]->gravacao_logo.' /  '.$modelos[0]->aprovacao_conceito.' /  '.$modelos[0]->aprovacao_prototipo_design.' /  '.$modelos[0]->aprovacao_prototipo_cores;
	  
		$obs_historico_ajustado = str_replace( [ "'" ], ' ', $obs_historico  );
	 
	 $modeloid =  $modelos[0]->id;
	$historico = \DB::select( "INSERT INTO `compras_historico`(`id_tabela`, `tabela`, `id_usuario`, `obs`, `historico`) VALUES (	'$modeloid','compras_modelos','$cod_usuario','$obs_historico_ajustado','Modelo criado') " );
	  
    return redirect( "/compras/pedido/modelo/" . $modelos[ 0 ]->id );


  }
  public function ModeloNovo( $idmodelo ) {

    $modelo = \DB::select( "select* ,compras_modelos.tipo_modelo as 'tipo_modelo',compras_modelos.tipo as 'tipo_ficha', fornecedor.valor as 'fornecedor', pais.pais as 'pais', compras_modelos.id as idmodelo,
	ifnull((select count(id) from compras_modelos_arquivos cma where cma.id_modelo = compras_modelos.id and tipo = 'inspiracao_modelo'),0) as inspiracao_modelo,
	ifnull((select count(id) from compras_modelos_arquivos cma where cma.id_modelo = compras_modelos.id and tipo = 'desenho_tecnico_pdf'),0) as desenho_tecnico_pdf,
	ifnull((select count(id) from compras_modelos_arquivos cma where cma.id_modelo = compras_modelos.id and tipo = 'desenho_tecnico_dwg'),0) as desenho_tecnico_dwg,
	ifnull((select count(id) from compras_modelos_arquivos cma where cma.id_modelo = compras_modelos.id and tipo = 'foto_prototipo'),0) as foto_prototipo,
	ifnull((select count(id) from compras_modelos_arquivos cma where cma.id_modelo = compras_modelos.id and tipo = 'foto_combinacao'),0) as foto_combinacao,
	ifnull((select count(id) from compras_modelos_arquivos cma where cma.id_modelo = compras_modelos.id and tipo = 'referencia_cores'),0) as referencia_cores,
	ifnull((select count(id) from compras_modelos_arquivos cma where cma.id_modelo = compras_modelos.id and tipo = 'patern'),0) as patern
	from compras_modelos 
			left join caracteristicas fornecedor on fornecedor.campo = 'fornecedor' and id_fornecedor = fornecedor.codigo
			left join addressbook pais on pais.id = id_fornecedor
			left join compras_itens_novos ci on ci.id_modelo = compras_modelos.id
			where compras_modelos.id = '$idmodelo' " );
    $itens = \DB::select( "select* 
	 		from compras_itens_novos
			where id_modelo = '$idmodelo'
			and exclui = 0" );
	  $arquivos = \DB::select( "select compras_modelos_arquivos.*, usuarios.nome 
	 		from compras_modelos_arquivos
			left join usuarios on usuarios.id = usuario
			where id_modelo = '$idmodelo'
			and exclui = 0 order by id desc" );
	  $historicos = \DB::select( "select compras_historico.* , usuarios.nome, date(compras_historico.created_at) as data_historico, time(compras_historico.created_at) as hora_historico
	  from compras_historico
	  left join usuarios on usuarios.id = id_usuario
	  where tabela = 'compras_modelos' and id_tabela = '$idmodelo' order by id desc" );

    return view( 'produtos.compras.modelo' )->with( 'modelo', $modelo )->with( 'itens', $itens )->with( 'arquivos', $arquivos )->with( 'historicos', $historicos );


  }


  public function ModeloNovoEdita( $idmodelo ) {

    $modelo = \DB::select( "select* ,compras_modelos.tipo_modelo as 'tipo_modelo',compras_modelos.tipo as 'tipo_ficha', fornecedor.valor as 'fornecedor', pais.pais as 'pais', compras_modelos.id as idmodelo from compras_modelos 
			left join caracteristicas fornecedor on fornecedor.campo = 'fornecedor' and id_fornecedor = fornecedor.codigo
			left join addressbook pais on pais.id = id_fornecedor
			left join compras_itens_novos ci on ci.id_modelo = compras_modelos.id
			where compras_modelos.id = '$idmodelo'" );

    return view( 'produtos.compras.modelo_edita' )->with( 'modelo', $modelo );


  }
  public function ModeloNovoEditaSalva( Request $request ) {

    $usuario = \Auth::id();
    $update = \DB::select( "UPDATE `compras_modelos` SET `id_compra`='$request->id_compra',`tipo`='$request->tipo',`id_fornecedor`='$request->fornecedor',`cod_fabrica`='$request->codfabrica',`modelo_go`='$request->modelo',`cod_molde_frente`='$request->codmoldefrente',`cod_molde_haste`='$request->codmoldehaste',`grife`='$request->grife',`tipo_modelo`='$request->tipomodelo',`agrupamento`='$request->agrupamento',`ncm`='$request->ncm',`armazenamento`='',`ano_mod`='$request->anomod',`col_mod`='$request->colmod',`class_mod`='$request->clasmod',`genero`='$request->genero',`idade`='$request->idade',`linha`='$request->linha',`faixa`='$request->categoria',`col_estilo`='$request->colecaoestilo',`tecnologia`='$request->tecnologia',`material_frente`='$request->materialfrente',`material_haste`='$request->materialhaste',`material_logo`='$request->materiallogo',`plaqueta`='$request->plaqueta',`material_plaqueta`='$request->materialplaqueta',`material_lente`='$request->materiallente',`material_ponteira`='$request->materialponteira',`curvatura_lente`='$request->curvatura',`formato`='$request->formato',`fixacao`='$request->fixacao',`graduacao`='$request->graduacao',`tamanho_lente`='$request->tamanholente',`tamanho_frente`='$request->tamanhofrente',`altura_lente`='$request->alturalente',`tamanho_ponte`='$request->tamanhoponte',`tamanho_haste`='$request->tamanhohaste',`gravacao_lente_direita`='$request->lentedireita',`gravacao_lente_esquerda`='$request->lenteesquerda',`gravacao_haste_direita`='$request->hastedireita',`gravacao_haste_esquerda`='$request->hasteesquerda',`gravacao_logo`='$request->gravacaologo',`aprovacao_conceito`='$request->aprovacaoconceito',`aprovacao_prototipo_design`='$request->aprovacaoprototipodesign',`aprovacao_prototipo_cores`='$request->aprovacaoprototipocores',`usuario`='$usuario'  WHERE id = '$request->id_modelo'" );
	  
	  $modelos = \DB::select( "select* from compras_modelos where id = '$request->id_modelo' ");
	  $cod_usuario = \Auth::id();
		$obs_historico = 'Modelo editado com os dados  '.$modelos[0]->id_compra.' /  '.$modelos[0]->tipo.' /  '.$modelos[0]->id_fornecedor.' /  '.$modelos[0]->cod_fabrica.' /  '.$modelos[0]->modelo_go.' /  '.$modelos[0]->cod_molde_frente.' /  '.$modelos[0]->cod_molde_haste.' /  '.$modelos[0]->grife.' /  '.$modelos[0]->tipo_modelo.' /  '.$modelos[0]->agrupamento.' /  '.$modelos[0]->ncm.' /  '.$modelos[0]->armazenamento.' /  '.$modelos[0]->ano_mod.' /  '.$modelos[0]->col_mod.' /  '.$modelos[0]->class_mod.' /  '.$modelos[0]->genero.' /  '.$modelos[0]->idade.' /  '.$modelos[0]->linha.' /  '.$modelos[0]->faixa.' /  '.$modelos[0]->col_estilo.', '.$modelos[0]->tecnologia.' /  '.$modelos[0]->material_frente.' /  '.$modelos[0]->material_haste.' /  '.$modelos[0]->material_logo.' /  '.$modelos[0]->plaqueta.' /  '.$modelos[0]->material_plaqueta.' /  '.$modelos[0]->material_lente.' /  '.$modelos[0]->material_ponteira.' /  '.$modelos[0]->curvatura_lente.' /  '.$modelos[0]->formato.' /  '.$modelos[0]->fixacao.' /  '.$modelos[0]->graduacao.' /  '.$modelos[0]->tamanho_lente.' /  '.$modelos[0]->tamanho_frente.' /  '.$modelos[0]->altura_lente.' /  '.$modelos[0]->tamanho_ponte.' /  '.$modelos[0]->tamanho_haste.' /  '.$modelos[0]->gravacao_lente_direita.' /  '.$modelos[0]->gravacao_lente_esquerda.' /  '.$modelos[0]->gravacao_haste_direita.' /  '.$modelos[0]->gravacao_haste_esquerda.' /  '.$modelos[0]->gravacao_logo.' /  '.$modelos[0]->aprovacao_conceito.' /  '.$modelos[0]->aprovacao_prototipo_design.' /  '.$modelos[0]->aprovacao_prototipo_cores;
	  
		$obs_historico_ajustado = str_replace( [ "'" ], ' ', $obs_historico  );
	 
	 $modeloid =  $modelos[0]->id;
	$historico = \DB::select( "INSERT INTO `compras_historico`(`id_tabela`, `tabela`, `id_usuario`, `obs`, `historico`) VALUES (	'$modeloid','compras_modelos','$cod_usuario','$obs_historico_ajustado','Modelo editado') " );

    return redirect( "/compras/pedido/modelo/" . $request->id_modelo );
  }
  public function ItemNovoInsere( Request $request ) {

    $usuario = \Auth::id();
    $custo = str_replace( [ '.', ',' ], '.', $request->custo );

    $insere = \DB::select( "INSERT INTO `compras_itens_novos`( `id_modelo`, `cod_cor`, `cod_cor_fornecedor`, `cor_frente1`, `cor_frente2`, `cor_frente3`, `cor_haste1`, `cor_haste2`, `cor_haste3`, `cor_ponteira`, `cor_logo`, `cor_lente`, `custo`, `usuario`, `quantidade`, `clasitem`, `exclui`, `cor_frente1_jde`, `cor_frente2_jde`, `cor_haste1_jde`, `cor_haste2_jde`, `cor_lente_jde`, `cor_ponteira_jde`, `cor_logo_jde`) VALUES ('$request->id_modelo','$request->codcorgo','$request->codcorfabrica','$request->corfrente1','$request->corfrente2','$request->corfrente3','$request->corhaste1','$request->corhaste2','$request->corhaste3','$request->corponteira','$request->corlogo','$request->corlente','$custo','$usuario','$request->quantidade','$request->clasitem','0','$request->corarm1jde','$request->corarm2jde','$request->corhaste1jde','$request->corhaste2jde','$request->corteclentejde','$request->corponteirajde','$request->corlogojde')
		" );
	  
	 $modelos = \DB::select( "select* from compras_itens_novos order by id desc limit 1 ");
	  $cod_usuario = \Auth::id();
		$obs_historico = 'Cor criada com os dados  '.$modelos[0]->id_modelo.' / '.$modelos[0]->cod_cor.' / '.$modelos[0]->cod_cor_fornecedor.' / '.$modelos[0]->cor_frente1.' / '.$modelos[0]->cor_frente2.' / '.$modelos[0]->cor_frente3.' / '.$modelos[0]->cor_haste1.' / '.$modelos[0]->cor_haste2.' / '.$modelos[0]->cor_haste3.' / '.$modelos[0]->cor_ponteira.' / '.$modelos[0]->cor_logo.' / '.$modelos[0]->cor_lente.' / '.$custo.' / '.$modelos[0]->quantidade.' / '.$modelos[0]->clasitem;
	  
		$obs_historico_ajustado = str_replace( [ "'" ], ' ', $obs_historico  );
	 
	 $modeloid =  $request->id_modelo;
	$historico = \DB::select( "INSERT INTO `compras_historico`(`id_tabela`, `tabela`, `id_usuario`, `obs`, `historico`) VALUES (	'$modeloid','compras_modelos','$cod_usuario','$obs_historico_ajustado','Cor criada') " );
    return redirect( "/compras/pedido/modelo/" . $request->id_modelo );
  }

  public function ItemNovoEdita( Request $request ) {


    $item = \DB::select( "Select ci.*, cm.modelo_go, cm.cod_fabrica, fr1.valor cor_frente1_jde_valor, fr2.valor cor_frente2_jde_valor, haste1.valor cor_haste1_jde_valor, haste2.valor cor_haste2_jde_valor, logo.valor cor_logo_jde_valor, ponteira.valor cor_ponteira_jde_valor, lente.valor cor_lente_jde_valor from compras_itens_novos ci
		left join compras_modelos cm on ci.id_modelo = cm.id 
        left join caracteristicas fr1 on fr1.codigo = cor_frente1_jde and fr1.campo = 'corarm1'
        left join caracteristicas fr2 on fr2.codigo = cor_frente2_jde and fr2.campo = 'corarm2'
        left join caracteristicas haste1 on haste1.codigo = cor_haste1_jde and haste1.campo = 'corhaste1'
        left join caracteristicas haste2 on haste2.codigo = cor_haste2_jde and haste2.campo = 'corhaste2'
        left join caracteristicas lente on lente.codigo = cor_lente_jde and lente.campo = 'corteclente'
        left join caracteristicas ponteira on ponteira.codigo = cor_ponteira_jde and ponteira.campo = 'corhaste2'
        left join caracteristicas logo on logo.codigo = cor_logo_jde  and logo.campo = 'corarm1'  where ci.id= $request->id
		" );


    return view( 'produtos.compras.item_edita' )->with( 'item', $item );
  }
  public function ItemNovoEditaSalva( Request $request ) {
    //dd($request);
    $usuario = \Auth::id();
    $custo = str_replace( [ '.', ',' ], '.', $request->custo );

    $item = \DB::select( "UPDATE `compras_itens_novos` SET `cod_cor`='$request->codcorgo',`cod_cor_fornecedor`='$request->codcorfabrica',`cor_frente1`='$request->corfrente1',`cor_frente2`='$request->corfrente2',`cor_frente3`='$request->corfrente3',`cor_haste1`='$request->corhaste1',`cor_haste2`='$request->corhaste2',`cor_haste3`='$request->corhaste3',`cor_ponteira`='$request->corponteira',`cor_logo`='$request->corlogo',`cor_lente`='$request->corlente',`custo`='$custo',`clasitem`='$request->clasitem',`quantidade`='$request->quantidade',`usuario`='$usuario', `cor_frente1_jde`='$request->corarm1jde',`cor_frente2_jde`='$request->corarm2jde',`cor_haste1_jde`='$request->corhaste1jde',`cor_haste2_jde`='$request->corhaste2jde',`cor_lente_jde`='$request->corteclentejde',`cor_ponteira_jde`='$request->corponteirajde',`cor_logo_jde`='$request->corlogojde' WHERE id = $request->id_item " );
	  
	  $modelos = \DB::select( "select* from compras_itens_novos where id = $request->id_item ");
	  $cod_usuario = \Auth::id();
		$obs_historico = 'Cor editada com os dados  '.$modelos[0]->id_modelo.' / '.$modelos[0]->cod_cor.' / '.$modelos[0]->cod_cor_fornecedor.' / '.$modelos[0]->cor_frente1.' / '.$modelos[0]->cor_frente2.' / '.$modelos[0]->cor_frente3.' / '.$modelos[0]->cor_haste1.' / '.$modelos[0]->cor_haste2.' / '.$modelos[0]->cor_haste3.' / '.$modelos[0]->cor_ponteira.' / '.$modelos[0]->cor_logo.' / '.$modelos[0]->cor_lente.' / '.$custo.' / '.$modelos[0]->quantidade.' / '.$modelos[0]->clasitem;
	  
		$obs_historico_ajustado = str_replace( [ "'" ], ' ', $obs_historico  );
	 
	 $modeloid =  $request->id_modelo;
	$historico = \DB::select( "INSERT INTO `compras_historico`(`id_tabela`, `tabela`, `id_usuario`, `obs`, `historico`) VALUES (	'$modeloid','compras_modelos','$cod_usuario','$obs_historico_ajustado','Cor editada') " );

    return redirect( "/compras/pedido/modelo/" . $request->id_modelo );

  }
	  public function ItemNovoExclui( $id ) {
   
    $usuario = \Auth::id();
  $modelos = \DB::select( "select* from compras_itens_novos where id = $id ");
	  $cod_usuario = \Auth::id();
		$obs_historico = 'Cor excluida com os dados  '.$modelos[0]->id_modelo.' / '.$modelos[0]->cod_cor.' / '.$modelos[0]->cod_cor_fornecedor.' / '.$modelos[0]->cor_frente1.' / '.$modelos[0]->cor_frente2.' / '.$modelos[0]->cor_frente3.' / '.$modelos[0]->cor_haste1.' / '.$modelos[0]->cor_haste2.' / '.$modelos[0]->cor_haste3.' / '.$modelos[0]->cor_ponteira.' / '.$modelos[0]->cor_logo.' / '.$modelos[0]->cor_lente.' / '.$modelos[0]->custo.' / '.$modelos[0]->quantidade.' / '.$modelos[0]->clasitem;
	  
		$obs_historico_ajustado = str_replace( [ "'" ], ' ', $obs_historico  );
	 
	 $modeloid =  $modelos[0]->id_modelo;
	$historico = \DB::select( "INSERT INTO `compras_historico`(`id_tabela`, `tabela`, `id_usuario`, `obs`, `historico`) VALUES (	'$modeloid','compras_modelos','$cod_usuario','$obs_historico_ajustado','Cor excluida') " );

    $item = \DB::select( "UPDATE `compras_itens_novos` SET `exclui`='1' ,`usuario`='$usuario' WHERE id = '$id' " );

    return redirect()->back();

  }
	
	

  public function PrePedidoAgrupamento() {


    $agrupamentos = \DB::select( "select agrupamento, 
(select grife from itens i where i.codgrife = codgrifes limit 1) as grife 
from(
Select agrupamento, grife as codgrifes
from compras_modelos group by agrupamento, grife ) as base
		" );


    return view( 'produtos.compras.agrupamentos' )->with( 'agrupamentos', $agrupamentos );
  }


  public function fornecedor( Request $request ) {

    return view( 'produtos.compras.fornecedor' );

  }
  public function listaLis( Request $request ) {
    dd( 'oi' );

  }

  public function importaLi( Request $request ) {
    dd( 'oi' );

    ini_set( 'memory_limit', -1 );

    $path = $request->file( 'arquivo' )->store( 'uploads/compras' );

    if ( file_exists( '/var/www/html/portal-gestao/storage/app/' . $path ) ) {

      $reader = \PhpOffice\ PhpSpreadsheet\ IOFactory::createReader( "Xlsx" );

      $spreadsheet = $reader->load( '/var/www/html/portal-gestao/storage/app/' . $path );

      $sheet = $spreadsheet->getActiveSheet()->toArray();


      $i = 0;
      foreach ( $sheet as $linha ) {

        if ( $i > 3 ) {


          $compra_item = \App\ CompraItem::find( $linha[ 4 ] );
          //rever o id da tabela e vai ler na linah de cima

          if ( $compra_item ) {


            //						$dt_confirmacao = explode('/', $linha[10]);
            //						$dt_confirmacao2 = $dt_confirmacao[2].'-'.$dt_confirmacao[1].'-'.$dt_confirmacao[0];


            if ( $linha[ 10 ] == 0 ) {
              $status = 'CANCELADO';

            } else {
              $status = 'DISTRIBUIDO';


              if ( $linha[ 11 ] == 1 ) {

                /*1ª entrega*/
                $entrega = new\ App\ CompraEntrega();
                $entrega->id_compra_item = $compra_item->id;
                $entrega->id_usuario = \Auth::id();
                $entrega->tipo = 'confirmacao';
                $entrega->dt_entrega = $linha[ 7 ];
                $entrega->dt_confirmada = $linha[ 13 ];
                $entrega->qtde_entrega = $linha[ 12 ];
                $entrega->qtde_confirmada = $linha[ 10 ];
                $entrega->save();
                echo $linha[ 11 ];
              }

              if ( $linha[ 11 ] == 2 ) {

                /*1ª entrega*/
                $entrega = new\ App\ CompraEntrega();
                $entrega->id_compra_item = $compra_item->id;
                $entrega->id_usuario = \Auth::id();
                $entrega->tipo = 'confirmacao';
                $entrega->dt_entrega = $linha[ 7 ];
                $entrega->dt_confirmada = $linha[ 13 ];
                $entrega->qtde_entrega = $linha[ 12 ];
                $entrega->qtde_confirmada = $linha[ 10 ];
                $entrega->save();


                /*2ª entrega*/
                $entrega = new\ App\ CompraEntrega();
                $entrega->id_compra_item = $compra_item->id;
                $entrega->id_usuario = \Auth::id();
                $entrega->tipo = 'confirmacao';
                $entrega->dt_entrega = $linha[ 7 ];
                $entrega->dt_confirmada = $linha[ 16 ];
                $entrega->qtde_entrega = $linha[ 15 ];
                $entrega->qtde_confirmada = $linha[ 10 ];
                $entrega->save();
                echo $linha[ 11 ];

              }

              if ( $linha[ 11 ] == 3 ) {

                /*1ª entrega*/
                $entrega = new\ App\ CompraEntrega();
                $entrega->id_compra_item = $compra_item->id;
                $entrega->id_usuario = \Auth::id();
                $entrega->tipo = 'confirmacao';
                $entrega->dt_entrega = $linha[ 7 ];
                $entrega->dt_confirmada = $linha[ 13 ];
                $entrega->qtde_entrega = $linha[ 12 ];
                $entrega->qtde_confirmada = $linha[ 10 ];
                $entrega->save();

                /*2ª entrega*/
                $entrega = new\ App\ CompraEntrega();
                $entrega->id_compra_item = $compra_item->id;
                $entrega->id_usuario = \Auth::id();
                $entrega->tipo = 'confirmacao';
                $entrega->dt_entrega = $linha[ 7 ];
                $entrega->dt_confirmada = $linha[ 16 ];
                $entrega->qtde_entrega = $linha[ 15 ];
                $entrega->qtde_confirmada = $linha[ 10 ];
                $entrega->save();

                /*3ª entrega*/
                $entrega = new\ App\ CompraEntrega();
                $entrega->id_compra_item = $compra_item->id;
                $entrega->id_usuario = \Auth::id();
                $entrega->tipo = 'confirmacao';
                $entrega->dt_entrega = $linha[ 7 ];
                $entrega->dt_confirmada = $linha[ 19 ];
                $entrega->qtde_entrega = $linha[ 18 ];
                $entrega->qtde_confirmada = $linha[ 10 ];
                $entrega->save();

                echo $linha[ 11 ];
              }

              if ( $linha[ 11 ] == 4 ) {

                /*1ª entrega*/
                $entrega = new\ App\ CompraEntrega();
                $entrega->id_compra_item = $compra_item->id;
                $entrega->id_usuario = \Auth::id();
                $entrega->tipo = 'confirmacao';
                $entrega->dt_entrega = $linha[ 7 ];
                $entrega->dt_confirmada = $linha[ 13 ];
                $entrega->qtde_entrega = $linha[ 12 ];
                $entrega->qtde_confirmada = $linha[ 10 ];
                $entrega->save();

                /*2ª entrega*/
                $entrega = new\ App\ CompraEntrega();
                $entrega->id_compra_item = $compra_item->id;
                $entrega->id_usuario = \Auth::id();
                $entrega->tipo = 'confirmacao';
                $entrega->dt_entrega = $linha[ 7 ];
                $entrega->dt_confirmada = $linha[ 16 ];
                $entrega->qtde_entrega = $linha[ 15 ];
                $entrega->qtde_confirmada = $linha[ 10 ];
                $entrega->save();

                /*3ª entrega*/
                $entrega = new\ App\ CompraEntrega();
                $entrega->id_compra_item = $compra_item->id;
                $entrega->id_usuario = \Auth::id();
                $entrega->tipo = 'confirmacao';
                $entrega->dt_entrega = $linha[ 7 ];
                $entrega->dt_confirmada = $linha[ 19 ];
                $entrega->qtde_entrega = $linha[ 18 ];
                $entrega->qtde_confirmada = $linha[ 10 ];
                $entrega->save();

                /*4ª entrega*/
                $entrega = new\ App\ CompraEntrega();
                $entrega->id_compra_item = $compra_item->id;
                $entrega->id_usuario = \Auth::id();
                $entrega->tipo = 'confirmacao';
                $entrega->dt_entrega = $linha[ 7 ];
                $entrega->dt_confirmada = $linha[ 22 ];
                $entrega->qtde_entrega = $linha[ 21 ];
                $entrega->qtde_confirmada = $linha[ 10 ];
                $entrega->save();

                echo $linha[ 11 ];
              }

              if ( $linha[ 11 ] == 5 ) {

                /*1ª entrega*/
                $entrega = new\ App\ CompraEntrega();
                $entrega->id_compra_item = $compra_item->id;
                $entrega->id_usuario = \Auth::id();
                $entrega->tipo = 'confirmacao';
                $entrega->dt_entrega = $linha[ 7 ];
                $entrega->dt_confirmada = $linha[ 13 ];
                $entrega->qtde_entrega = $linha[ 12 ];
                $entrega->qtde_confirmada = $linha[ 10 ];
                $entrega->save();

                /*2ª entrega*/
                $entrega = new\ App\ CompraEntrega();
                $entrega->id_compra_item = $compra_item->id;
                $entrega->id_usuario = \Auth::id();
                $entrega->tipo = 'confirmacao';
                $entrega->dt_entrega = $linha[ 7 ];
                $entrega->dt_confirmada = $linha[ 16 ];
                $entrega->qtde_entrega = $linha[ 15 ];
                $entrega->qtde_confirmada = $linha[ 10 ];
                $entrega->save();

                /*3ª entrega*/
                $entrega = new\ App\ CompraEntrega();
                $entrega->id_compra_item = $compra_item->id;
                $entrega->id_usuario = \Auth::id();
                $entrega->tipo = 'confirmacao';
                $entrega->dt_entrega = $linha[ 7 ];
                $entrega->dt_confirmada = $linha[ 19 ];
                $entrega->qtde_entrega = $linha[ 18 ];
                $entrega->qtde_confirmada = $linha[ 10 ];
                $entrega->save();

                /*4ª entrega*/
                $entrega = new\ App\ CompraEntrega();
                $entrega->id_compra_item = $compra_item->id;
                $entrega->id_usuario = \Auth::id();
                $entrega->tipo = 'confirmacao';
                $entrega->dt_entrega = $linha[ 7 ];
                $entrega->dt_confirmada = $linha[ 22 ];
                $entrega->qtde_entrega = $linha[ 21 ];
                $entrega->qtde_confirmada = $linha[ 10 ];
                $entrega->save();

                /*5ª entrega*/
                $entrega = new\ App\ CompraEntrega();
                $entrega->id_compra_item = $compra_item->id;
                $entrega->id_usuario = \Auth::id();
                $entrega->tipo = 'confirmacao';
                $entrega->dt_entrega = $linha[ 7 ];
                $entrega->dt_confirmada = $linha[ 25 ];
                $entrega->qtde_entrega = $linha[ 24 ];
                $entrega->qtde_confirmada = $linha[ 10 ];
                $entrega->save();
                echo $linha[ 11 ];

              }
            }
            //dd('ok');


            $compra_item->qtde_conf = $linha[ 10 ];
            $compra_item->status = $status;

            if ( empty( $linha[ 14 ] )and empty( $compra_item->note ) ) {} else {
              $note = $linha[ 14 ];
              $compra_item->note = $note;
            }

            $compra_item->save();

            //rever campo da tabela item
            //$compra_item->$qtd_confirmadas

            if ( $status == 'DISTRIBUIDO' ) {
              $compra = \App\ Compra::find( $compra_item->id_compra );
              $compra->status = 'DISTRIBUIDO';
              $compra->save();
            }


            // grava qtde confirmada
            // grava entregas


          }

        }


        $i++;

      }

    }
    return redirect()->back();

  }

  public function importaAtualiza( Request $request, $id ) {


    ini_set( 'memory_limit', -1 );
	  	
		 $cod_usuario = \Auth::id();
		
		

    $path = $request->file( 'arquivo' )->store( 'uploads/compras' );
	  $arquivo = '/storage/'.$path;
	//dd('oi');
    if ( file_exists( '/var/www/html/portal-gestao/storage/app/' . $path ) ) {
		
		 
		$inserearquivos = \DB::select( "INSERT INTO `compras_arquivos`( `id_compra`, `tipo`, `arquivo`, `nome`, `obs`, `data`, `usuario`, `exclui`) VALUES ('$id','DISTRIBUIÇÃO','$arquivo','Distribuição de datas', '',CURRENT_DATE,'$cod_usuario','0')");
		

      $reader = \PhpOffice\ PhpSpreadsheet\ IOFactory::createReader( "Xlsx" );

      $spreadsheet = $reader->load( '/var/www/html/portal-gestao/storage/app/' . $path );

      $sheet = $spreadsheet->getActiveSheet()->toArray();


      $i = 0;

      foreach ( $sheet as $linha ) {


        if ( $i > 3 ) {


          $compra_item = \App\ CompraItem::find( $linha[ 4 ] );
          //rever o id da tabela e vai ler na linah de cima

          if ( $compra_item ) {

 //dd($linha[ 27 ] );
            //						$dt_confirmacao = explode('/', $linha[10]);
            //						$dt_confirmacao2 = $dt_confirmacao[2].'-'.$dt_confirmacao[1].'-'.$dt_confirmacao[0];


            if ( $linha[ 10 ] == 0 ) {
              $status = 'CANCELADO';

            } else {
              $status = 'DISTRIBUIDO';

				if($status = 'DISTRIBUIDO'){
              if ( $linha[ 11 ] == 1 ) {


                /*1ª entrega*/
                $entrega = new\ App\ CompraEntrega();
                $entrega->id_compra_item = $compra_item->id;
                $entrega->id_usuario = \Auth::id();
                $entrega->tipo = 'confirmacao';
                $entrega->dt_entrega = $linha[ 7 ];
                $entrega->dt_confirmada = $linha[ 13 ];
                $entrega->qtde_entrega = $linha[ 12 ];
                $entrega->qtde_confirmada = $linha[ 10 ];
                $entrega->save();
                echo $linha[ 11 ];
              }

              if ( $linha[ 11 ] == 2 ) {


                /*1ª entrega*/
                $entrega = new\ App\ CompraEntrega();
                $entrega->id_compra_item = $compra_item->id;
                $entrega->id_usuario = \Auth::id();
                $entrega->tipo = 'confirmacao';
                $entrega->dt_entrega = $linha[ 7 ];
                $entrega->dt_confirmada = $linha[ 13 ];
                $entrega->qtde_entrega = $linha[ 12 ];
                $entrega->qtde_confirmada = $linha[ 10 ];
                $entrega->save();


                /*2ª entrega*/
                $entrega = new\ App\ CompraEntrega();
                $entrega->id_compra_item = $compra_item->id;
                $entrega->id_usuario = \Auth::id();
                $entrega->tipo = 'confirmacao';
                $entrega->dt_entrega = $linha[ 7 ];
                $entrega->dt_confirmada = $linha[ 16 ];
                $entrega->qtde_entrega = $linha[ 15 ];
                $entrega->qtde_confirmada = $linha[ 10 ];
                $entrega->save();
                echo $linha[ 11 ];

              }

              if ( $linha[ 11 ] == 3 ) {

                /*1ª entrega*/
                $entrega = new\ App\ CompraEntrega();
                $entrega->id_compra_item = $compra_item->id;
                $entrega->id_usuario = \Auth::id();
                $entrega->tipo = 'confirmacao';
                $entrega->dt_entrega = $linha[ 7 ];
                $entrega->dt_confirmada = $linha[ 13 ];
                $entrega->qtde_entrega = $linha[ 12 ];
                $entrega->qtde_confirmada = $linha[ 10 ];
                $entrega->save();

                /*2ª entrega*/
                $entrega = new\ App\ CompraEntrega();
                $entrega->id_compra_item = $compra_item->id;
                $entrega->id_usuario = \Auth::id();
                $entrega->tipo = 'confirmacao';
                $entrega->dt_entrega = $linha[ 7 ];
                $entrega->dt_confirmada = $linha[ 16 ];
                $entrega->qtde_entrega = $linha[ 15 ];
                $entrega->qtde_confirmada = $linha[ 10 ];
                $entrega->save();

                /*3ª entrega*/
                $entrega = new\ App\ CompraEntrega();
                $entrega->id_compra_item = $compra_item->id;
                $entrega->id_usuario = \Auth::id();
                $entrega->tipo = 'confirmacao';
                $entrega->dt_entrega = $linha[ 7 ];
                $entrega->dt_confirmada = $linha[ 19 ];
                $entrega->qtde_entrega = $linha[ 18 ];
                $entrega->qtde_confirmada = $linha[ 10 ];
                $entrega->save();

                echo $linha[ 11 ];
              }

              if ( $linha[ 11 ] == 4 ) {

                /*1ª entrega*/
                $entrega = new\ App\ CompraEntrega();
                $entrega->id_compra_item = $compra_item->id;
                $entrega->id_usuario = \Auth::id();
                $entrega->tipo = 'confirmacao';
                $entrega->dt_entrega = $linha[ 7 ];
                $entrega->dt_confirmada = $linha[ 13 ];
                $entrega->qtde_entrega = $linha[ 12 ];
                $entrega->qtde_confirmada = $linha[ 10 ];
                $entrega->save();

                /*2ª entrega*/
                $entrega = new\ App\ CompraEntrega();
                $entrega->id_compra_item = $compra_item->id;
                $entrega->id_usuario = \Auth::id();
                $entrega->tipo = 'confirmacao';
                $entrega->dt_entrega = $linha[ 7 ];
                $entrega->dt_confirmada = $linha[ 16 ];
                $entrega->qtde_entrega = $linha[ 15 ];
                $entrega->qtde_confirmada = $linha[ 10 ];
                $entrega->save();

                /*3ª entrega*/
                $entrega = new\ App\ CompraEntrega();
                $entrega->id_compra_item = $compra_item->id;
                $entrega->id_usuario = \Auth::id();
                $entrega->tipo = 'confirmacao';
                $entrega->dt_entrega = $linha[ 7 ];
                $entrega->dt_confirmada = $linha[ 19 ];
                $entrega->qtde_entrega = $linha[ 18 ];
                $entrega->qtde_confirmada = $linha[ 10 ];
                $entrega->save();

                /*4ª entrega*/
                $entrega = new\ App\ CompraEntrega();
                $entrega->id_compra_item = $compra_item->id;
                $entrega->id_usuario = \Auth::id();
                $entrega->tipo = 'confirmacao';
                $entrega->dt_entrega = $linha[ 7 ];
                $entrega->dt_confirmada = $linha[ 22 ];
                $entrega->qtde_entrega = $linha[ 21 ];
                $entrega->qtde_confirmada = $linha[ 10 ];
                $entrega->save();

                echo $linha[ 11 ];
              }

              if ( $linha[ 11 ] == 5 ) {

                /*1ª entrega*/
                $entrega = new\ App\ CompraEntrega();
                $entrega->id_compra_item = $compra_item->id;
                $entrega->id_usuario = \Auth::id();
                $entrega->tipo = 'confirmacao';
                $entrega->dt_entrega = $linha[ 7 ];
                $entrega->dt_confirmada = $linha[ 13 ];
                $entrega->qtde_entrega = $linha[ 12 ];
                $entrega->qtde_confirmada = $linha[ 10 ];
                $entrega->save();

                /*2ª entrega*/
                $entrega = new\ App\ CompraEntrega();
                $entrega->id_compra_item = $compra_item->id;
                $entrega->id_usuario = \Auth::id();
                $entrega->tipo = 'confirmacao';
                $entrega->dt_entrega = $linha[ 7 ];
                $entrega->dt_confirmada = $linha[ 16 ];
                $entrega->qtde_entrega = $linha[ 15 ];
                $entrega->qtde_confirmada = $linha[ 10 ];
                $entrega->save();

                /*3ª entrega*/
                $entrega = new\ App\ CompraEntrega();
                $entrega->id_compra_item = $compra_item->id;
                $entrega->id_usuario = \Auth::id();
                $entrega->tipo = 'confirmacao';
                $entrega->dt_entrega = $linha[ 7 ];
                $entrega->dt_confirmada = $linha[ 19 ];
                $entrega->qtde_entrega = $linha[ 18 ];
                $entrega->qtde_confirmada = $linha[ 10 ];
                $entrega->save();

                /*4ª entrega*/
                $entrega = new\ App\ CompraEntrega();
                $entrega->id_compra_item = $compra_item->id;
                $entrega->id_usuario = \Auth::id();
                $entrega->tipo = 'confirmacao';
                $entrega->dt_entrega = $linha[ 7 ];
                $entrega->dt_confirmada = $linha[ 22 ];
                $entrega->qtde_entrega = $linha[ 21 ];
                $entrega->qtde_confirmada = $linha[ 10 ];
                $entrega->save();

                /*5ª entrega*/
                $entrega = new\ App\ CompraEntrega();
                $entrega->id_compra_item = $compra_item->id;
                $entrega->id_usuario = \Auth::id();
                $entrega->tipo = 'confirmacao';
                $entrega->dt_entrega = $linha[ 7 ];
                $entrega->dt_confirmada = $linha[ 25 ];
                $entrega->qtde_entrega = $linha[ 24 ];
                $entrega->qtde_confirmada = $linha[ 10 ];
                $entrega->save();
                echo $linha[ 11 ];

              }
            }
            //dd('ok');

			if ($linha[ 8 ] <> '') {
 						$valor = str_replace(",", ".", $linha[ 8 ]);
 						//$valor = str_replace(",", ".", $valor);
 					} else {
 						$valor = 0;
 					}
				$custo = $valor;
            $compra_item->qtde_conf = $linha[ 10 ];
            $compra_item->status = $status;
			$compra_item->custo = $custo;
			$compra_item->save();

            //rever campo da tabela item
            //$compra_item->$qtd_confirmadas
				
            if ( $status == 'DISTRIBUIDO' ) {
              $compra = \App\ Compra::find( $compra_item->id_compra );
              $compra->status = 'DISTRIBUIDO';
				
              $compra->save();
            }
			}


            // grava qtde confirmada
            // grava entregas


          }

        }


        $i++;

      }

    }
    return redirect()->back();

  }


  public function acoes( Request $request ) {


    //if ($request->acao == 'cancelar') {

    foreach ( $request->itens as $item ) {

      $cancela = \DB::select( "update compras_itens set status = '$request->acao' where id = '$item'" );

    }

    //}

    return redirect()->back();


  }


  public function atualizaCompraItem( Request $request ) {

    if ( $request->id_compra_item ) {

      $compra_item = \App\ CompraItem::find( $request->id_compra_item );

      $qtde_conf = 0;

      $compra_item->qtde = $request->qtde;

      if ( isset( $request->qtde_plan ) && $compra_item->status <> 'ABERTO' ) {

        $entregas = count( $request->qtde_plan );

        $limpa = \DB::select( "update  compras_entregas set exclui = 1 where id_compra_item = $request->id_compra_item" );

        for ( $i = 0; $i < $entregas; $i++ ) {

          if ( $request->qtde_plan[ $i ] > 0 ) {

            $entrega = new\ App\ CompraEntrega();
            $entrega->id_compra_item = $request->id_compra_item;
            $entrega->id_usuario = \Auth::id();
            $entrega->tipo = 'teste';
            $entrega->dt_entrega = $request->dt_entrega[ $i ];
            $entrega->dt_confirmada = $request->dt_confirmada[ $i ];
            $entrega->qtde_entrega = $request->qtde_plan[ $i ];
            $entrega->qtde_confirmada = $request->obs[ $i ];
            $entrega->save();

            $qtde_conf += $request->qtde_plan[ $i ];

          }

        }


      }

      $compra_item->qtde_conf = $qtde_conf;
      $compra_item->save();

    }

    return redirect( '/compras/' . $compra_item->id_compra );

  }

  public function atualiza( Request $request, $id ) {

    $compra = Compra::find( $id );

    $compra->id_fornecedor = $request->id_fornecedor;
    $compra->transporte = $request->transporte;
    $compra->pagamento = $request->pagamento;
    $compra->dt_entrega = $request->dt_entrega;
    $compra->obs = $request->obs;
    $compra->save();


    $request->session()->flash( 'alert-success', 'Fornecedor alterado com sucesso' );

    return redirect( "/compras/" . $id );
  }


  public function confirmacao() {


  }

  public function imprimir( $id ) {

    //
    //    $itens = \DB::select( "select *,  (select custo from custos_2019 where custos_2019.secundario = compras_itens.item  limit 1) as custo
    //				from compras_itens 
    //				left join compras on id_compra = compras.id
    //				left join addressbook on id_fornecedor = addressbook.id
    //				left join itens on item = itens.secundario
    //				where id_compra = '$id' and compras_itens.status <> 'CANCELADO'
    //				and compras_itens.status <> 'FINALIZADO SISTEMA'
    //				order by agrup, modelo, secundario" );

    $itens = \DB::select( "
		    select left(agrup,4) as agrup , itens.modelo,grife, compras_itens.item , compras_analise.colmod,compras_analise.clasmod, cet  ,ifnull(etq,0) etq,
           ifnull(compras_analise.cep,0)cep,ifnull(orctt,0) orctt,ifnull(orcvalido,0) orcvalido,format(ifnull(media_venda,0),0) media_venda,ifnull(ult_30dd,0) ult_30dd,
           ifnull(ult_60dd,0) ult_60dd,ifnull(vendastt,0) vendastt,dt_entrega,qtde,razao,endereco,
		   municipio,uf,pais,email1,ddd1,tel1, numero,compras.id ,pedido_dt,  ( select distinct group_concat(dt_confirmada separator ', ')  from compras_entregas where
		   compras_entregas.id_compra_item = compras_itens.id order by dt_entrega asc) as dt_entrega,  (select custo from custos_2019 where custos_2019.secundario = compras_itens.item  limit 1) as custo,
		   ifnull(disponivel,0)+ifnull(saldo_parte,0)+ifnull(em_beneficiamento,0) as br,
		   ifnull((select distinct group_concat(ifnull(nome,'') separator ', ') from compras_arquivos where tipo = 'proformas' and exclui = 0 and 'compras.id' = compras_arquivos.id_compra),'') as proforma, ifnull(transporte,'') as transporte, ifnull(pagamento,'') pagamento
		  from compras_itens 
		  left join compras on compras.id = compras_itens.id_compra
		  left join compras_analise on compras_analise.id_compra_item = compras_itens.id
		  left join addressbook ad on ad.id = compras.id_fornecedor 
		  left join itens on compras_itens.id_item = itens.id
		  where compras_itens.id_compra = $id
		  and compras_itens.status not in ('cancelado')
		  and compras_analise.id  is not null
		  " );


    $constructor = [
      'mode' => '+aCJK',
      'format' => 'A4',
      'default_font_size' => 0,
      'default_font' => '',
      'margin_left' => 5,
      'margin_right' => 5,
      'margin_top' => 5,
      'margin_bottom' => 5,
      'margin_header' => 0,
      'margin_footer' => 0,
      'orientation' => 'L',
      "autoScriptToLang" => true,
      // "allow_charset_conversion" => false,
      "autoLangToFont" => true
    ];


    $mpdf = new\ Mpdf\ Mpdf( $constructor );
    //$mpdf = new \mPDF('c', 'A4', '', '', 0, 0, 0, 0, 0, 0);
    //dd($modelos);
    $html = '<html><body style="font-size:9px;">';

    $html .= '<table class="table">
			<tr>
			<td width="10%" valign="top"><img src="/var/www/html/portal-gestao/public/img/logogo.png" width="100"></td>
			<td width="60%" valign="top">
			<b style="text-size: 22px; font-weight:bold">Supplier</b>
			<h4>' . $itens[ 0 ]->razao . '</h4>
			<address>
			' . $itens[ 0 ]->endereco . ' ' . $itens[ 0 ]->numero . '<br>
			' . $itens[ 0 ]->municipio . ' - ' . $itens[ 0 ]->uf . ' ' . $itens[ 0 ]->pais . '<br>
			' . $itens[ 0 ]->email1 . '<br>
			' . $itens[ 0 ]->ddd1 . ' ' . $itens[ 0 ]->tel1 . '
			</address>
			</td>
			<td align="left" valign="top">
			<h3>PURCHASE ORDER #' . $id . '</h3>
			<table class="table table-bordered">
			<tr>
			<td>P.O. Data</td>
			<td align="right">' . $itens[ 0 ]->pedido_dt . '</td>
			
			</tr>
			<tr>
			<td>Payment Terms</td>
			<td align="right">' . $itens[ 0 ]->pagamento . '</td>
			</tr>
			<tr>
			<td>Shipping Methods</td>
			<td align="right">' . $itens[ 0 ]->transporte . '</td>
			</tr>
			<tr>
			<td>Proforma</td>
			<td align="right">' . $itens[ 0 ]->proforma . '</td>
			</tr>
			</table>
			</td>
			</tr>
			</table>

			<br>
			<br>
			<br>
			<br>
			<table class="table table-bordered" >';
    $html .= '

			<tr>
			<th style="text-align: center"></th>
			<th  style="text-align: center">Agrup</th>
			<th style="text-align: center" bgcolor="#0000ff">Item</th>
			<th style="text-align: center">Col</th>
			<th style="text-align: center">Clas</th>
			<th style="text-align: center">BR</th>
			<th style="text-align: center">CET</th>
			<th style="text-align: center">ETQ</th>
			<th style="text-align: center">CEP</th>
			<th style="text-align: center">Orc</th>
			<th style="text-align: center">MDV</th>
			<th style="text-align: center">Vda 30</th>
			<th style="text-align: center">Vda 60</th>
			<th style="text-align: center">Vda tt</th>
			<th style="text-align: center">Qtt</th>
			<th style="text-align: center">Dt entrega</th>
			<th style="text-align: center">Custo</th>
			<th style="text-align: center">Total Custo</th>
			</tr>';
    $total_qtde = 0;
    $total_unitario = 0;
    $total_pedido = 0;


    foreach ( $itens as $item ) {

      $total_qtde += $item->qtde;
      $total_pedido += $item->qtde * $item->custo;
      $total_qtde += 0;
      $total_qtde += 0;

      $foto = app( 'App\Http\Controllers\ItemController' )->consultaFoto( $item->item );

      $html .= '
				<tr>
				<td align="center"><img src="/' . $foto . '" width="10" style="min-height:50x;"></td>
				<td align="center" width="5%">' . $item->agrup . '</td>
				<td align="center">' . $item->item . '</td>
				<td align="center">' . $item->colmod . '</td>
				<td align="center">' . $item->clasmod . '</td>
				<td align="center" width="4%">' . $item->br . '</td>
				<td align="center" width="4%">' . $item->cet . '</td>
				<td align="center" width="4%">' . $item->etq . '</td>
				<td align="center" width="4%">' . $item->cep . '</td>
				<td align="center" width="4%">' . $item->orctt . '/' . $item->orcvalido . '</td>
				<td align="center" width="4%">' . $item->media_venda . '</td>
				<td align="center" width="4%">' . $item->ult_30dd . '</td>
				<td align="center" width="4%">' . $item->ult_60dd . '</td>
				<td align="center">' . $item->vendastt . '</td>
				<td align="center">' . $item->qtde . '</td>
				<td align="center">' . $item->dt_entrega . '</td>
				<td align="center">' . $item->custo . '</td>
				<td align="center">' . $item->custo * $item->qtde . '</td>
				</tr>';
    }
    $html .= '
			<tr>
			<th style="text-align: center" colspan="14"></th>

			<th style="text-align: center">' . number_format( $total_qtde, 0, ',', ',' ) . '</th>
			<th style="text-align: center"></th>
			<th style="text-align: center"></th>
			<th style="text-align: center">' . number_format( $total_pedido, 0, ',', ',' ) . '</th>
			
			
			
			</tr>';
    $html .= '

			</table>
			
			';
    if ( $itens[ 0 ]->grife == 'SAINT LAURENT'
      or $itens[ 0 ]->grife == 'GUCCI'
      or $itens[ 0 ]->grife == 'MONTBLANC'
    ) {
      $html .= ' <br> <br> _________________________________<br> Leonardo Riekstins  <br> Data:' . $itens[ 0 ]->agrup;
    }
    if ( $itens[ 0 ]->grife == 'STELLA MCCARTNEY'
      or $itens[ 0 ]->grife == 'BOTTEGA VENETA'
      or $itens[ 0 ]->grife == 'MCQ'
      or $itens[ 0 ]->grife == 'ALEXANDER MCQUEEN'
      or $itens[ 0 ]->grife == 'BOUCHERON'
      or $itens[ 0 ]->grife == 'POMELLATO'
      or $itens[ 0 ]->grife == 'BRIONI'
      or $itens[ 0 ]->grife == 'CHRISTOPHER KANE'
      or $itens[ 0 ]->grife == 'TOMAS MAIER'
      or $itens[ 0 ]->grife == 'CARTIER'
      or $itens[ 0 ]->grife == 'ALTUZARRA'
      or $itens[ 0 ]->grife == 'AZZEDINE'
      or $itens[ 0 ]->grife == 'COURREGES'
      or $itens[ 0 ]->grife == 'CHLOE'
      or $itens[ 0 ]->grife == 'DUNHILL'

    ) {
      $html .= ' <br> <br> _________________________________<br> Eliane Gonçales  <br> Data:';
    }
    if ( $itens[ 0 ]->grife <> 'STELLA MCCARTNEY'
      or $itens[ 0 ]->grife <> 'SAINT LAURENT'
      or $itens[ 0 ]->grife <> 'GUCCI'
      or $itens[ 0 ]->grife <> 'BOTTEGA VENETA'
      or $itens[ 0 ]->grife <> 'MCQ'
      or $itens[ 0 ]->grife <> 'ALEXANDER MCQUEEN'
      or $itens[ 0 ]->grife <> 'BOUCHERON'
      or $itens[ 0 ]->grife <> 'POMELLATO'
      or $itens[ 0 ]->grife <> 'BRIONI'
      or $itens[ 0 ]->grife <> 'CHRISTOPHER KANE'
      or $itens[ 0 ]->grife <> 'TOMAS MAIER'
      or $itens[ 0 ]->grife <> 'CARTIER'
      or $itens[ 0 ]->grife <> 'ALTUZARRA'
      or $itens[ 0 ]->grife <> 'AZZEDINE'
      or $itens[ 0 ]->grife <> 'MONTBLANC'
      or $itens[ 0 ]->grife <> 'AGREGADOS'
      or $itens[ 0 ]->grife <> 'COURREGES'
      or $itens[ 0 ]->grife <> 'CHLOE'
      or $itens[ 0 ]->grife <> 'DUNHILL'


    ) {
      $html .= ' <br> <br> _________________________________<br> Sandro Adamo  <br> Data:';
    }
    $html .= '</body></html>';

    //			$modelos = \DB::select("select modelo
    //				from compras_itens 
    //				left join itens on item = itens.secundario
    //				where id_compra = '$id' and compras_itens.status <> 'CANCELADO'
    //				group by modelo
    //				");
    //
    //
    //			$total_modelos = count($modelos);
    //			$index_modelo = 0;
    //
    //			foreach ($modelos as $modelo) {
    //				$index_modelo++;
    //
    //				$html .= '<h4>'.$modelo->modelo.'</h4>';
    //
    //				$itens = \DB::select("select base.*,  trocas.trocas from (
    //					select compras_itens.qtde as qtde_compra, itens.id iditem, itens.agrup agrupamento, itens.modelo as modelos,itens.secundario as secundarios, 
    //					(select colmod from itens a where a.secundario = itens.secundario and colmod not in ('','Add sales cat S5 codes here') limit 1 ) colmod, 
    //					(select clasmod from itens b where b.secundario = itens.secundario and colmod not in ('','Add sales cat S5 codes here') limit 1 ) clasmod,
    //					(select genero from itens c where c.secundario = itens.secundario and colmod not in ('','Add sales cat S5 codes here') limit 1 ) genero,
    //
    //					(select idade from itens d where d.secundario = itens.secundario and colmod not in ('','Add sales cat S5 codes here') limit 1 ) idade,
    //					(select tamolho from itens d where d.secundario = itens.secundario and colmod not in ('','Add sales cat S5 codes here') limit 1 ) tamolho,
    //
    //					vendas_sint.*, sld.disp_vendas Availability, 
    //					conf_montado+em_beneficiamento+saldo_parte as Factoring_BR, 
    //					qtd_rot_receb+cet as In_transit, 
    //					(Select sum(estoque) from producoes_sint where producoes_sint.id = itens.id) as Stock_Factory, (Select sum(producao) from producoes_sint where producoes_sint.id = itens.id)  as In_production,
    //					saldo_manutencao as Maintenance, saldo_trocas as Estrategy_reserve, saldo_most as Showcases
    //
    //
    //
    //
    //					from itens  
    //					left join go.vendas_sint 	on vendas_sint.curto = itens.id
    //					left join go.saldos sld 	on sld.curto = itens.id
    //					left join go.compras_itens on compras_itens.item = itens.secundario  and id_compra = '$id' and compras_itens.status <> 'CANCELADO'
    //
    //
    //					where itens.modelo = '$modelo->modelo'
    //					and codtipoitem = '006'
    //
    //					) as base
    //
    //
    //					
    //
    //
    //
    //					left join 
    //					(select secundario, sum(qtde) trocas from trocas group by secundario) as trocas
    //					on trocas.secundario = base.secundarios
    //					where qtde_compra <> ''
    //					and qtde_compra >0
    //					order by a_180dd desc");
    //				
    //			
    //
    //
    //				$html .= '
    //				<table class="table table-bordered" style="font-size:11px;">
    //				<tr align="center">
    //				<td style="font-weight: bold; text-align: center;" width="10%">Picture</td>
    //				<td style="font-weight: bold; text-align: center;" width="8%">Item</td>
    //				<td style="font-weight: bold; text-align: center;" width="4%" align="center">30dd</td>
    //				<td style="font-weight: bold; text-align: center;" width="4%">60dd</td>
    //				<td style="font-weight: bold; text-align: center;" width="4%">90dd</td>
    //				<td style="font-weight: bold; text-align: center;" width="4%">120dd</td>
    //				<td style="font-weight: bold; text-align: center;" width="4%">150dd</td>
    //				<td style="font-weight: bold; text-align: center;" width="4%">180dd</td>
    //				<td style="font-weight: bold; text-align: center;" width="4%">210dd</td>
    //				<td style="font-weight: bold; text-align: center;" width="4%">240dd</td>
    //				<td style="font-weight: bold; text-align: center;" width="4%">270dd</td>
    //				<td style="font-weight: bold; text-align: center;" width="4%">300dd</td>
    //				<td style="font-weight: bold; text-align: center;" width="4%">330dd</td>
    //				<td style="font-weight: bold; text-align: center;" width="4%">360dd</td>               	
    //				<td style="font-weight: bold; text-align: center;" width="4%">tt 180dd</td>               	
    //				<td style="font-weight: bold; text-align: center;" width="4%"><b>total</b></td> 
    //				<td style="font-weight: bold; text-align: center;" width="4%">BR</td> 
    //				<td style="font-weight: bold; text-align: center;" width="4%">CET</td> 
    //				<td style="font-weight: bold; text-align: center;" width="4%">ETQ</td> 
    //				<td style="font-weight: bold; text-align: center;" width="4%">CEP</td> 
    //				<td style="font-weight: bold; text-align: center;" width="4%">OTH</td>
    //				<td style="font-weight: bold; text-align: center;" width="4%">SC</td>
    //				<td style="font-weight: bold; text-align: center;" width="4%">Repair</td>
    //				            	
    //				<td style="font-weight: bold; text-align: center;" width="4%">Reorder</td>               	
    //				</tr>
    //
    //				<tr align="center">
    //				<td style="font-weight: bold; text-align: center;" >图片</td>
    //				<td style="font-weight: bold; text-align: center;" >型号</td>
    //				<td style="font-weight: bold; text-align: center;" >30天</td>
    //				<td style="font-weight: bold; text-align: center;" >60天</td>
    //				<td style="font-weight: bold; text-align: center;" >90天</td>
    //				<td style="font-weight: bold; text-align: center;" >120天</td>
    //				<td style="font-weight: bold; text-align: center;" >150天</td>
    //				<td style="font-weight: bold; text-align: center;" >180天</td>
    //				<td style="font-weight: bold; text-align: center;" >210天</td>
    //				<td style="font-weight: bold; text-align: center;" >240天</td>
    //				<td style="font-weight: bold; text-align: center;" >270天</td>
    //				<td style="font-weight: bold; text-align: center;" >300天</td>
    //				<td style="font-weight: bold; text-align: center;" >330天</td>
    //				<td style="font-weight: bold; text-align: center;" >360天</td>               	
    //				<td style="font-weight: bold; text-align: center;" >半年</td>               	
    //				<td style="font-weight: bold; text-align: center;" ><b>累计</b></td> 
    //				<td style="font-weight: bold; text-align: center;" >巴西</td> 
    //				<td style="font-weight: bold; text-align: center;" >运输</td> 
    //				<td style="font-weight: bold; text-align: center;" >中国</td> 
    //				<td style="font-weight: bold; text-align: center;" >生产</td> 
    //				<td style="font-weight: bold; text-align: center;" >其他</td>
    //				<td style="font-weight: bold; text-align: center;" >样品</td>
    //				<td style="font-weight: bold; text-align: center;" >损坏</td>
    //				
    //				<td style="font-weight: bold; text-align: center;" >Reorder</td>
    //
    //				</tr>';
    //
    //
    //				foreach ($itens as $item) {
    //
    //					$foto = app('App\Http\Controllers\ItemController')->consultaFoto($item->secundarios);
    //
    //					$html .= '<tr align="center">
    //					<td align="center"><img src="/'.$foto.'" width="100" style="min-height:100px;"></td>
    //					<td align="center" >'.$item->secundarios.'</td>
    //					<td align="center">'.$item->ult_30dd.'</td>
    //					<td align="center">'.$item->ult_60dd.'</td>
    //					<td align="center">'.$item->ult_90dd.'</td>
    //					<td align="center">'.$item->ult_120dd.'</td>
    //					<td align="center">'.$item->ult_150dd.'</td>
    //					<td align="center">'.$item->ult_180dd.'</td>
    //					<td align="center">'.$item->ult_210dd.'</td>
    //					<td align="center">'.$item->ult_240dd.'</td>
    //					<td align="center">'.$item->ult_270dd.'</td>
    //					<td align="center">'.$item->ult_300dd.'</td>
    //					<td align="center">'.$item->ult_330dd.'</td>
    //					<td align="center">'.$item->ult_360dd.'</td>               	
    //					<td align="center">'.$item->a_180dd.'</td>               	
    //					<td align="center"><b>'.$item->vendastt.'</b></td> 
    //					<td align="center">'.number_format($item->Availability+$item->Factoring_BR,0).'</td> 
    //					<td align="center">'.number_format($item->In_transit,0).'</td> 
    //					<td align="center">'.number_format($item->Stock_Factory,0).'</td> 
    //					<td align="center">'.number_format($item->In_production,0).'</td> 
    //					<td align="center">'.number_format($item->Maintenance+$item->Estrategy_reserve,0).'</td>
    //					<td align="center">'.number_format($item->Maintenance+$item->Showcases,0).'</td>
    //					<td align="center">'.number_format($item->trocas,0).'</td>';
    //
    //					if ($item->qtde_compra > 0) {
    //						$html .= '<td align="center" style="background-color: gray;">'.number_format($item->qtde_compra,0).'</td>';
    //					} else {
    //						$html .= '<td align="center"></td>';				              	
    //					}
    //					$html .= '                             	
    //					</tr>';
    //
    //				}
    //
    //				$html .= '</table>'; 
    //
    //				if ($index_modelo < $total_modelos) {
    ////					$html .= "<pagebreak />";
    //				}
    //			}
    //			
    //
    //
    //
    //
    //				
    //
    //
    //			if($itens[0]->agrupamento='GG01 - GUCCI (SL)' or $itens[0]->agrupamento='GG02 - GUCCI (RX)' or $itens[0]->agrupamento='MM01 - MONTBLANC (SL)' or $itens[0]->agrupamento='MM02 - MONTBLANC (RX)' or $itens[0]->agrupamento='SL01 - SAINT LAURENT (SL)'or $itens[0]->agrupamento='SL02 - SAINT LAURENT (RX)'){
    //			$html .= ' <br> <br> _________________________________<br> Leonardo Riekstins  <br> Data:'; 
    //			}
    //			else{
    //				$html .= ' <br> <br> _________________________________<br> Eliane Gonçales  <br> Data:'; 
    //			}
    //			$html .= '</body></html>';

    // Write some HTML code:
    //		$mpdf->SetHTMLFooter($rodape);
    $stylesheet = file_get_contents( '/var/www/html/portal-gestao/public/css/bootstrap.min.css' );

    $mpdf->WriteHTML( $stylesheet, 1 );
    $mpdf->WriteHTML( $html, 2 );

    // Output a PDF file directly to the browser
    $mpdf->Output();

  }

  public function importaPedido( Request $request ) {

    $id_pedido = $request->id_pedido;
    $pedido_capa = \App\ Compra::find( $id_pedido );


    $uploaddir = '/var/www/html/portal-gestao/storage/uploads/';
    $uploadfile = $uploaddir . basename( $_FILES[ 'arquivo' ][ 'name' ] );

    $erros = array();

    if ( move_uploaded_file( $_FILES[ 'arquivo' ][ 'tmp_name' ], $uploadfile ) ) {

      if ( file_exists( $uploadfile ) ) {

        $handle = fopen( $uploadfile, "r" );

        $linha = 1;

        while ( ( $line = fgetcsv( $handle, 100000, ";" ) ) !== FALSE ) {

          if ( $linha >= 2 ) {


            $referencia = $line[ 0 ];
            $qtde = $line[ 1 ];
            $obs = $line[ 2 ];


            // verifica se o item existe no cadastro
            $item = \App\ Item::where( 'secundario', $referencia )->first();


            if ( $item ) {

              // // verifica se o item existe no pedido 
              $pedido_item = \App\ CompraItem::where( 'id_compra', $id_pedido )->where( 'item', $referencia )->where( 'status', '<>', 'CANCELADO' )->first();
              //if (!$pedido_item) {

              $item = \App\ Item::where( 'secundario', $referencia )->first();


              if ( $pedido_capa->id_fornecedor == $item->codfornecedor ) {

                $insere = new\ App\ CompraItem();
                $insere->id_compra = $id_pedido;
                $insere->pedido_dt = date( "Y-m-d" );
                $insere->dt_prevista = date( "Y-m-d" );
                $insere->status = 'ABERTO';
                $insere->origem = 'REPEDIDO';
                $insere->solicitante = 'BRASIL';
                $insere->item = $referencia;
                $insere->id_item = $item->id;
                $insere->qtde = $qtde;
                $insere->note = $obs;
                $insere->save();

                $id_item_compra = \DB::select( "select id from compras_itens where id_compra = '$id_pedido' and id_item = '$item->id' and qtde = '$qtde'  and status = 'aberto'" );

                $iditemcompra = $id_item_compra[ 0 ]->id;

                $grava_historico_item = \DB::select( "select i.agrup, i.modelo, i.id, i.secundario, i.clasmod, i.clasitem,i.colmod, i.colitem, i.statusatual, i.valortabela,
			ifnull(sa.saldo_most,0) saldo_most, ifnull((select sum(qtde) 
            from trocas
            where  id_item = i.id
            ),0) as trocas, case when vs.ult_30dd > 0  and vs.ult_60dd > 0 then vs.ult_30dd+vs.ult_60dd /2
                 when vs.ult_30dd > 0  and vs.ult_60dd = 0 then vs.ult_30dd
                 when vs.ult_30dd = 0  and vs.ult_60dd > 0 then vs.ult_60dd
                 when vs.ult_30dd = 0  and vs.ult_60dd = 0 and vs.ult_90dd > 0 then vs.ult_90dd
                  else 0 end as media_venda, ifnull(vs.ult_30dd,0)ult_30dd, ifnull(vs.ult_60dd,0)ult_60dd, ifnull(vs.ult_90dd,0)ult_90dd, ifnull(vs.ult_120dd,0)ult_120dd, ifnull(vs.ult_150dd,0)ult_150dd, ifnull(vs.ult_180dd,0)ult_180dd,
			ifnull(vs.a_180dd,0)a_180dd, ifnull(vs.a_180dd,0)+ifnull(vs.ult_210dd,0)+ ifnull(vs.ult_240dd,0)+ ifnull(vs.ult_270dd,0)+ ifnull(vs.ult_300dd,0)+ ifnull(vs.ult_330dd,0)+ ifnull(vs.ult_360dd,0) as a_360dd, ifnull(vs.vendastt,0)vendastt,
			sa.existente+sa.saldo_trocas as existente, sa.res_temporaria, sa.res_definitiva, (sa.existente+sa.saldo_trocas)-( sa.res_temporaria+ sa.res_definitiva) as disponivel, 
			sa.saldo_parte, sa.em_beneficiamento, sa.cet, sa.etq+sa.cet_li as  etq, sa.cep, sa.saldo_manutencao+sa.sucata as manutencao,ifnull((select sum(qtde) as qtde_compra
            from compras_itens
            
            where status = 'aberto' and pedido_dt > '2020-06-01'
			and item = i.secundario
            ),0) as compras_aberto, ifnull(orc.orctt,0) orctt, ifnull(orcvalido,0) orcvalido
			from itens i
			left join vendas_sint vs on i.id = vs.curto
			left join saldos sa on sa.curto = i.id
			left join orcamentos orc on orc.curto = i.id

			where i.id = '$item->id'" );
                // dd($id_item_compra);

                foreach ( $grava_historico_item as $gravaitem ) {

                  $insert_historico = \DB::select( "INSERT INTO `compras_analise`(`id_compra_item`, `id_compra`, `agrupamento`, `modelo`, `id_item`, `item`, `clasmod`, `clasitem`, `colmod`, `colitem`, `statusatual`, `valortabela`, `mostruarios`, `trocas`, `media_venda`,`ult_30dd`, `ult_60dd`, `ult_90dd`, `ult_120dd`, `ult_150dd`, `ult_180dd`, `a_180dd`, `a_360dd`, `vendastt`, `existente`, `res_temporaria`, `res_definitiva`, `disponivel`, `saldo_parte`, `em_beneficiamento`, `cet`, `etq`, `cep`, `manutencao`, `compras_aberto`, `orctt`, `orcvalido`, `exclui`, `atualizado`) VALUES ('$iditemcompra','$id_pedido','$gravaitem->agrup','$gravaitem->modelo','$gravaitem->id','$gravaitem->secundario','$gravaitem->clasmod','$gravaitem->clasitem','$gravaitem->colmod','$gravaitem->colitem','$gravaitem->statusatual','$gravaitem->valortabela','$gravaitem->saldo_most','$gravaitem->trocas','$gravaitem->media_venda','$gravaitem->ult_30dd','$gravaitem->ult_60dd','$gravaitem->ult_90dd','$gravaitem->ult_120dd','$gravaitem->ult_150dd','$gravaitem->ult_180dd','$gravaitem->a_180dd','$gravaitem->a_360dd','$gravaitem->vendastt','$gravaitem->existente','$gravaitem->res_temporaria','$gravaitem->res_definitiva','$gravaitem->disponivel','$gravaitem->saldo_parte','$gravaitem->em_beneficiamento','$gravaitem->cet','$gravaitem->etq','$gravaitem->cep','$gravaitem->manutencao','$gravaitem->compras_aberto','$gravaitem->orctt','$gravaitem->orcvalido','0','0')	" );
                }


                echo 'Item inserido com sucesso';

              } else {
                $erros[] = '[ ' . $referencia . ' ] - Fornecedor incorreto para o item.';

                //$request->session()->flash('alert-warning', 'Fornecedor incorreto para o item.');

              }

              //} else {

              //	$erros[] = '[ ' . $referencia . ' ] - Item ja existe no pedido';

              //}

            } else {

              $erros[] = '[ ' . $referencia . ' ] - Item não existe';

            }


          }

          $linha++;

        }

      } else {
        echo 'erro';
      }


    } else {
      echo "Possível ataque de upload de arquivo!\n";
    }

    if ( count( $erros ) > 0 ) {

      $msg = '<ul>';

      foreach ( $erros as $erro ) {

        $msg .= '<li> ' . $erro . '</li>';

      }

      $msg .= '</ul>';


      $request->session()->flash( 'alert-warning2', $msg );


    }

    $request->session()->flash( 'alert-success', "Arquivo importado com sucesso!" );

    return redirect( '/compras/' . $id_pedido );

  }

  public function listaCompras( Request $request ) {

    $sql = '';
    $proforma = '';

    if ( $request->status ) {
      $sql .= " and compras_itens.status = '$request->status' ";
    }

    if ( $request->pedido ) {
      $sql .= " and compras.id = '$request->pedido' ";
    }

    if ( $request->id_fornecedor ) {
      $sql .= " and compras.id_fornecedor = '$request->id_fornecedor' ";
    }

    if ( $request->inicio && $request->fim ) {
      $sql .= " and (compras.dt_emissao >= '$request->inicio' &&  compras.dt_emissao <= '$request->fim') ";
    }
    if ( $request->proforma ) {
      $proforma .= " where proforma like '%$request->proforma%'  ";
    }
	  if ( $request->tipo ) {
      $tipo .= " and tipo like '%$request->tipo%'  ";
    }
    $pedidos = \DB::select( "select date(dt_emissao) as dt_emissao, id, tipo, fornecedor, sum(qtde) as itens, sum(custo) as total, obs, proforma 
				, group_concat(distinct  status_item separator ', ')  status_item
				, group_concat(distinct  codgrife separator ', ')  grife
				, group_concat(distinct  anomod separator ', ')  anomod
                ,
				ifnull((select sum(compras_entregas.qtd_entregue) as qtd_entegue from compras_itens left join compras_entregas on compras_itens.id = compras_entregas.id_compra_item where id_compra = sele1.id and compras_itens.status <> 'cancelado' and compras_entregas.exclui is null),0) as qtd_entegue
	from( select compras.*, razao as fornecedor, qtde, (qtde * ifnull(custos_2019.custo,0)) as custo,
				ifnull((select distinct group_concat(nome separator ', ')  from compras_arquivos where id_compra = compras.id and tipo in ('proforma','pedido assinado') and exclui = 0),0) as proforma
				,compras_itens.status as status_item, itens.codgrife as codgrife, itens.anomod anomod
				
				from compras
				left join addressbook on addressbook.id = id_fornecedor
				left join compras_itens on id_compra = compras.id
				left join itens on secundario = compras_itens.item
				left join custos_2019 on custos_2019.secundario = compras_itens.item
				where id_fornecedor <> 0 
				and compras_itens.status <> 'cancelado'
				and compras_itens.status <> 'finalizado sistema'
				and compras.status <> 'finalizado sistema'
				and compras.tipo <> 'pre-pedido'
				$sql
				) as sele1
				$proforma
				group by dt_emissao, id, tipo, fornecedor, obs, proforma
				
				union all
				
				select date(timestamp) as dt_emissao, id_compra, tipo, nome, count(id_item) as itens, ifnull(sum(valor),0) as total, obs, ''proforma , status_pedido,
group_concat(distinct grife separator ', ') as grife, group_concat(distinct anomod separator ', ') as anomod, '0' qtd_entergue
from(
Select 'cotacao' as status_pedido, cm.id as id, cm.created_at as 'timestamp', cin.id as iditem_old, cin.id as id_item, cm.id_compra, '' as pedido_nro, date(cin.created_at) as pedido_dt, ''dt_status, ''dt_prevista, ''dt_conf, 'cotação' as status, 'cotacao' origem,
'' solicitante, concat(cm.id,' - ',modelo_go,' - ',cod_fabrica) as item, cm.id as id_modelo, cin.quantidade qtde  , '0' qtde_conf  , '' note ,
ad.nome,  cm.created_at as dt_emissao, cm.agrupamento, concat(cm.id,' - ',modelo_go,' - ',cod_fabrica) as modelo, cm.col_mod colmod, cm.class_mod clasmod, '0'qtd_entregue, '0' qtde_entrega,
''dt_alterada, ifnull(cin.quantidade,0)*ifnull(cin.custo,0) valor, compras.tipo, compras.obs, compras.status as status_compras, cm.grife as grife,
cm.ano_mod anomod, compras.id_fornecedor

from compras 
left join compras_modelos cm on compras.id = cm.id_compra
left join compras_itens_novos cin on cin.id_modelo = cm.id 
left join addressbook ad on ad.id = cm.id_fornecedor

) as compras
where  status_pedido <> '' 
and status_pedido <> 'cancelado' $sql
group by date(timestamp)  , id_compra, tipo, nome,obs,status_pedido


				order by id desc" );

    //dd($pedidos);
    return view( 'produtos.compras.lista' )->with( 'pedidos', $pedidos );

  }


  public function detalhesCompra( $id ) {
    
   
	
	$adiantamento = \DB::select( "
	select *, (select sum(valor) from compras_parcelas where compras_parcelas.id_titulo = cp.numero and cp.tipo = 'adiantamento') as valor_parcelas, (select date(min(vencimento)) from compras_parcelas where compras_parcelas.id_titulo = cp.numero and cp.tipo = 'adiantamento') as dt_vencimento_parcela
	from compras_titulos cp 
    where cp.id_pedido = $id and cp.tipo = 'adiantamento' and cp.origem = 'compras' 
    union all 
    select '' id, '' id_pedido, ''origem, ''numero, ''tipo, ''valor, ''moeda, ''vencimento, ''emissao, ''user, ''obs, ''created_at, ''updated_at, ''valor_parcelas, ''dt_vencimento_parcela
	 ");
  
	  
	  

    $capa = \DB::select( "select compras.*, razao as fornecedor, endereco, numero, municipio, uf, pais, email1, ddd1, tel1, date(dt_emissao) as dt_emissao, addressbook.nome,
    perc_adiantamento
            from compras
            left join addressbook on addressbook.id = id_fornecedor
            left join compras_itens on id_compra = compras.id
            left join itens on item = secundario
            left join compras_condicoes on compras_condicoes.id = id_condicao_pagamento
				where compras.id = $id" );
	  
	  if($capa[0]->tipo=='PRE-PEDIDO'){
		  
		  $itens = \DB::select( "Select 'cotacao' as status_pedido, cm.id as id, cm.created_at as 'timestamp', cin.id as iditem_old, cin.id as id_item, cm.id_compra, '' as pedido_nro, date(cin.created_at) as pedido_dt, ''dt_status, ''dt_prevista, ''dt_conf, cm.tipo as status, 'cotacao' origem,
'' solicitante, concat(cin.id,' - ',cod_cor,' - ',cod_cor_fornecedor) as item, cm.id as id_modelo, cin.quantidade qtde  , '0' qtde_conf  , '' note ,
ad.nome,  cm.created_at as dt_emissao, cm.agrupamento, concat(cm.id,' - ',modelo_go,' - ',cod_fabrica) as modelo, cm.col_mod colmod, cm.class_mod clasmod, '0'qtd_entregue, '0' qtde_entrega,
''dt_alterada,''dt_confirmada

from compras co
left join compras_modelos cm on cm.id_compra = co.id
left join compras_itens_novos cin on cin.id_modelo = cm.id 
left join addressbook ad on ad.id = cm.id_fornecedor
where co.id = $id
and (cin.exclui = 0 or cin.exclui is null) " );
		//  dd($id);
		  
		  $resumo = \DB::select( "select agrupamento agrup, count(id_item) as qtde, sum(qtde) as 							  totalpedido from(
				Select 'cotacao' as status_pedido, cm.id as id, cm.created_at as 'timestamp', cin.id as iditem_old, cin.id as id_item, cm.id_compra, '' as pedido_nro, date(cin.created_at) as pedido_dt, ''dt_status, ''dt_prevista, ''dt_conf, 'cotação' as status, 'cotacao' origem,
'' solicitante, concat(modelo_go,' - ',cod_fabrica) as item, cm.id as id_modelo, cin.quantidade qtde  , '0' qtde_conf  , '' note ,
ad.nome,  cm.created_at as dt_emissao, cm.agrupamento, concat(modelo_go,' - ',cod_fabrica) as modelo, cm.col_mod colmod, cm.class_mod clasmod, '0'qtd_entregue, '0' qtde_entrega,
''dt_alterada

from compras_modelos cm
left join compras_itens_novos cin on cin.id_modelo = cm.id 
left join addressbook ad on ad.id = cm.id_fornecedor
where cm.id_compra = $id
and cin.exclui = 0) as base 
group by agrupamento" );
	  }
	  else{
		  
    $itens = \DB::select( "select compras.status as status_pedido, compras_itens.*, razao as fornecedor, date(dt_emissao) as dt_emissao, agrup as agrupamento, modelo, colmod, clasmod,
				( select sum(qtd_entregue) from compras_entregas where compras_entregas.id_compra_item = compras_itens.id and compras_entregas.exclui is null and compras_entregas.dt_alterada is null
			) as qtd_entregue,
			( select sum(qtde_entrega) from compras_entregas where compras_entregas.id_compra_item = compras_itens.id and compras_entregas.exclui is null and compras_entregas.dt_alterada is null
			) as qtde_entrega,
            ( select dt_confirmada from compras_entregas where compras_entregas.id_compra_item = compras_itens.id and compras_entregas.exclui is null and compras_entregas.dt_alterada is null   order by dt_confirmada asc, qtde_entrega-qtd_entregue desc limit 1
			)dt_confirmada,
			( select count(id) from compras_entregas where compras_entregas.id_compra_item = compras_itens.id and compras_entregas.exclui is null and compras_entregas.dt_alterada = 1
			) as dt_alterada
				from compras
				left join addressbook on addressbook.id = id_fornecedor
				left join compras_itens on id_compra = compras.id
				left join itens on item = secundario
				where compras.id = $id
				order by secundario, status asc" );
		  $resumo = \DB::select( "select agrup, count(*) as qtde, sum(compras_itens.qtde) as 							  totalpedido
				from compras
				left join addressbook on addressbook.id = id_fornecedor
				left join compras_itens on id_compra = compras.id
				left join itens on item = secundario
				where compras.id = $id
				group by agrup
				order by agrup asc" );
		   
	  }

    

    $invoices = \DB::select( "select invoice
                from compras_itens
                left join compras_entregas on compras_entregas.id_compra_item = compras_itens.id
                left join compras_entregas_invoices on compras_entregas.id = compras_entregas_invoices.id_compras_entrega
                where compras_itens.id_compra = $id 
				and (compras_entregas.exclui is null or compras_entregas.exclui = 0)
				and (compras_entregas_invoices.exclui is null or compras_entregas_invoices.exclui = 0)
				
				group by invoice" );
    $arquivos = \DB::select( "select compras_arquivos.*, usuarios.nome as usuario
	  			from compras_arquivos
				left join usuarios on usuarios.id = compras_arquivos.usuario
                where compras_arquivos.id_compra = $id 
				and compras_arquivos.exclui = 0
				" );
       


    return view( 'produtos.compras.detalhes' )->with( 'arquivos', $arquivos )->with( 'invoices', $invoices )->with( 'capa', $capa )->with( 'itens', $itens )->with( 'resumo', $resumo )->with( 'adiantamento', $adiantamento );

  }


  public function novoPedido( Request $request ) {


    $novo = new\ App\ Compra();
    $novo->id_fornecedor = $request->id_fornecedor;
    $novo->id_usuario = \Auth::id();
    $novo->tipo = $request->tipo;
    $novo->status = "ABERTO";
    $novo->solicitante = "BRASIL";
    $novo->dt_emissao = date( "Y-m-d H:i:s" );
    $novo->obs = $request->obs;
    $novo->transporte = $request->transporte;
    $novo->pagamento = $request->pagamento;
    $novo->dt_entrega = $request->dt_entrega;
    $novo->save();


    $request->session()->flash( 'alert', 'Pedido criado com sucesso!' );

    return redirect( '/compras/' . $novo->id );


  }


  // API
  public function listaPedidosItem( $item ) {

    $pedidos = \DB::select( "select *,(select compras_entregas.dt_confirmada from compras_entregas where id_compra_item = compras_itens.id and (ifnull(qtde_entrega,0)-ifnull(qtd_entregue,0) >0 or ifnull(qtde_entrega,0)-ifnull(qtd_entregue,0)=0)and (compras_entregas.exclui is null or compras_entregas.exclui = 0)limit 1) as dt_entrega
,(select ifnull(sum(compras_entregas.qtd_entregue),0) from compras_entregas where id_compra_item = compras_itens.id and (compras_entregas.exclui is null or compras_entregas.exclui = 0)) as qtd_entregue from compras_itens where item = '$item' order by id desc" );

    return response()->json( $pedidos );

  }

  public function inserePlanejamento( Request $request ) {

    $distribuicao = new\ App\ CompraDistribuicao();
    $distribuicao->id_pedido_item = $request->id_pedido_item;
    $distribuicao->id_usuario = $request->id_usuario;
    $distribuicao->tipo = $request->tipo;
    $distribuicao->ano = $request->ano;
    $distribuicao->mes = $request->mes;
    $distribuicao->qtde = $request->qtde;
    $distribuicao->obs = $request->obs;
    $distribuicao->save();


    return response()->json( $request->all() );

  }

  public function insereItem( Request $request ) {

    $pedido = \App\ Compra::find( $request->pedido );

    if ( $pedido ) {


      $item = \App\ Item::where( 'secundario', $request->item )->first();

      if ( $item ) {
        // dd($item);

        $novoitem = new\ App\ CompraItem();
        $novoitem->id_compra = $request->pedido;
        $novoitem->dt_prevista = $request->entrega;
        $novoitem->note = $request->obs;
        $novoitem->item = $request->item;
        $novoitem->id_item = $item->id;
        $novoitem->qtde = $request->qtde;
        $novoitem->status = "ABERTO";
        $novoitem->origem = "REPEDIDO";
        $novoitem->solicitante = "BRASIL";
        $novoitem->pedido_dt = date( "Y-m-d" );
        $novoitem->dt_status = date( "Y-m-d H:i:s" );
        $novoitem->save();

        $id_item_compra = \DB::select( "select id from compras_itens where id_compra = '$request->pedido' and id_item = '$item->id' and qtde = '$request->qtde'  and status = 'aberto'" );

        $iditemcompra = $id_item_compra[ 0 ]->id;

        $grava_historico_item = \DB::select( "select i.agrup, i.modelo, i.id, i.secundario, i.clasmod, i.clasitem,i.colmod, i.colitem, i.statusatual, i.valortabela,
			ifnull(sa.saldo_most,0) saldo_most, ifnull((select sum(qtde) 
            from trocas
            where  id_item = i.id
            ),0) as trocas, case when vs.ult_30dd > 0  and vs.ult_60dd > 0 then vs.ult_30dd+vs.ult_60dd /2
                 when vs.ult_30dd > 0  and vs.ult_60dd = 0 then vs.ult_30dd
                 when vs.ult_30dd = 0  and vs.ult_60dd > 0 then vs.ult_60dd
                 when vs.ult_30dd = 0  and vs.ult_60dd = 0 and vs.ult_90dd > 0 then vs.ult_90dd
                  else 0 end as media_venda, vs.ult_30dd, vs.ult_60dd, vs.ult_90dd, vs.ult_120dd, vs.ult_150dd, vs.ult_180dd,
			vs.a_180dd, vs.a_180dd+vs.ult_210dd+ vs.ult_240dd+ vs.ult_270dd+ vs.ult_300dd+ vs.ult_330dd+ vs.ult_360dd as a_360dd, vs.vendastt,
			sa.existente+sa.saldo_trocas as existente, sa.res_temporaria, sa.res_definitiva, (sa.existente+sa.saldo_trocas)-( sa.res_temporaria+ sa.res_definitiva) as disponivel, 
			sa.saldo_parte, sa.em_beneficiamento, sa.cet, sa.etq+sa.cet_li as  etq, sa.cep, sa.saldo_manutencao+sa.sucata as manutencao,ifnull((select sum(qtde) as qtde_compra
            from compras_itens
            
            where status = 'aberto' and pedido_dt > '2020-06-01'
			and item = i.secundario
            ),0) as compras_aberto, ifnull(orc.orctt,0) orctt, ifnull(orcvalido,0) orcvalido
			from itens i
			left join vendas_sint vs on i.id = vs.curto
			left join saldos sa on sa.curto = i.id
			left join orcamentos orc on orc.curto = i.id

			where i.id = '$item->id'" );
        // dd($id_item_compra);

        foreach ( $grava_historico_item as $gravaitem ) {

          $insert_historico = \DB::select( "INSERT INTO `compras_analise`(`id_compra_item`, `id_compra`, `agrupamento`, `modelo`, `id_item`, `item`, `clasmod`, `clasitem`, `colmod`, `colitem`, `statusatual`, `valortabela`, `mostruarios`, `trocas`, `media_venda`,`ult_30dd`, `ult_60dd`, `ult_90dd`, `ult_120dd`, `ult_150dd`, `ult_180dd`, `a_180dd`, `a_360dd`, `vendastt`, `existente`, `res_temporaria`, `res_definitiva`, `disponivel`, `saldo_parte`, `em_beneficiamento`, `cet`, `etq`, `cep`, `manutencao`, `compras_aberto`, `orctt`, `orcvalido`, `exclui`, `atualizado`) VALUES ('$iditemcompra','$request->pedido','$gravaitem->agrup','$gravaitem->modelo','$gravaitem->id','$gravaitem->secundario','$gravaitem->clasmod','$gravaitem->clasitem','$gravaitem->colmod','$gravaitem->colitem','$gravaitem->statusatual','$gravaitem->valortabela','$gravaitem->saldo_most','$gravaitem->trocas','$gravaitem->media_venda','$gravaitem->ult_30dd','$gravaitem->ult_60dd','$gravaitem->ult_90dd','$gravaitem->ult_120dd','$gravaitem->ult_150dd','$gravaitem->ult_180dd','$gravaitem->a_180dd','$gravaitem->a_360dd','$gravaitem->vendastt','$gravaitem->existente','$gravaitem->res_temporaria','$gravaitem->res_definitiva','$gravaitem->disponivel','$gravaitem->saldo_parte','$gravaitem->em_beneficiamento','$gravaitem->cet','$gravaitem->etq','$gravaitem->cep','$gravaitem->manutencao','$gravaitem->compras_aberto','$gravaitem->orctt','$gravaitem->orcvalido','0','0')	" );
        }
      } else {


        $novoitem = new\ App\ CompraItem();
        $novoitem->id_compra = $request->pedido;
        $novoitem->dt_prevista = $request->entrega;
        $novoitem->note = $request->obs;
        $novoitem->item = $request->item;
        $novoitem->qtde = $request->qtde;
        $novoitem->status = "ABERTO";
        $novoitem->origem = "REPEDIDO";
        $novoitem->solicitante = "BRASIL";
        $novoitem->pedido_dt = date( "Y-m-d" );
        $novoitem->dt_status = date( "Y-m-d H:i:s" );
        $novoitem->save();


      }
    }

  }


  public function insereItemPedido( Request $request ) {


    $pedido = \App\ Compra::find( $request->id_pedido );
	  
	  $item = \App\ Item::where( 'secundario', $request->item )->first();
	  if ($item->codtipoitem==007){
			
			
          $novoitem = new\ App\ CompraItem();
          $novoitem->id_compra = $request->id_pedido;
          $novoitem->dt_prevista = $request->dt_entrega;
          $novoitem->note = $request->obs;
          $novoitem->item = $item->secundario;
          $novoitem->id_item = $item->id;
          $novoitem->qtde = $request->qtde;
          $novoitem->status = "ABERTO";
          $novoitem->origem = "REPEDIDO";
          $novoitem->solicitante = "BRASIL";
          $novoitem->pedido_dt = date( "Y-m-d" );
          $novoitem->dt_status = date( "Y-m-d H:i:s" );

          $novoitem->save();
		   $request->session()->flash( 'alert-success', $item->secundario.' inserido com sucesso.' );
			
		}else{

    if ( $pedido  ) {

      

      if ( $item ) {
		
		
        if ( $pedido->id_fornecedor == $item->codfornecedor) {


          $novoitem = new\ App\ CompraItem();
          $novoitem->id_compra = $request->id_pedido;
          $novoitem->dt_prevista = $request->dt_entrega;
          $novoitem->note = $request->obs;
          $novoitem->item = $item->secundario;
          $novoitem->id_item = $item->id;
          $novoitem->qtde = $request->qtde;
          $novoitem->status = "ABERTO";
          $novoitem->origem = "REPEDIDO";
          $novoitem->solicitante = "BRASIL";
          $novoitem->pedido_dt = date( "Y-m-d" );
          $novoitem->dt_status = date( "Y-m-d H:i:s" );

          $novoitem->save();

          $id_item_compra = \DB::select( "select id from compras_itens where id_compra = '$request->id_pedido' and id_item = '$item->id' and qtde = '$request->qtde'  and status = 'aberto'" );

          $iditemcompra = $id_item_compra[ 0 ]->id;

          $grava_historico_item = \DB::select( "select i.agrup, i.modelo, i.id, i.secundario, i.clasmod, i.clasitem,i.colmod, i.colitem, i.statusatual, i.valortabela,
			ifnull(sa.saldo_most,0)saldo_most, ifnull((select sum(qtde) 
            from trocas
            where  id_item = i.id
            ),0) as trocas, case when vs.ult_30dd > 0  and vs.ult_60dd > 0 then vs.ult_30dd+vs.ult_60dd /2
                 when vs.ult_30dd > 0  and vs.ult_60dd = 0 then vs.ult_30dd
                 when vs.ult_30dd = 0  and vs.ult_60dd > 0 then vs.ult_60dd
                 when vs.ult_30dd = 0  and vs.ult_60dd = 0 and vs.ult_90dd > 0 then vs.ult_90dd
                  else 0 end as media_venda, vs.ult_30dd, vs.ult_60dd, vs.ult_90dd, vs.ult_120dd, vs.ult_150dd, vs.ult_180dd,
			vs.a_180dd, vs.a_180dd+vs.ult_210dd+ vs.ult_240dd+ vs.ult_270dd+ vs.ult_300dd+ vs.ult_330dd+ vs.ult_360dd as a_360dd, vs.vendastt,
			sa.existente+sa.saldo_trocas as existente, sa.res_temporaria, sa.res_definitiva, (sa.existente+sa.saldo_trocas)-( sa.res_temporaria+ sa.res_definitiva) as disponivel, 
			sa.saldo_parte, sa.em_beneficiamento, sa.cet, sa.etq+sa.cet_li as  etq, sa.cep, sa.saldo_manutencao+sa.sucata as manutencao,ifnull((select sum(qtde) as qtde_compra
            from compras_itens
            
            where status = 'aberto' and pedido_dt > '2020-06-01'
			and item = i.secundario
            ),0) as compras_aberto, ifnull(orc.orctt,0) orctt, ifnull(orcvalido,0) orcvalido
			from itens i
			left join vendas_sint vs on i.id = vs.curto
			left join saldos sa on sa.curto = i.id
			left join orcamentos orc on orc.curto = i.id

			where i.id = '$item->id'" );
          // dd($id_item_compra);

          foreach ( $grava_historico_item as $gravaitem ) {

            $insert_historico = \DB::select( "INSERT INTO `compras_analise`(`id_compra_item`, `id_compra`, `agrupamento`, `modelo`, `id_item`, `item`, `clasmod`, `clasitem`, `colmod`, `colitem`, `statusatual`, `valortabela`, `mostruarios`, `trocas`, `media_venda`,`ult_30dd`, `ult_60dd`, `ult_90dd`, `ult_120dd`, `ult_150dd`, `ult_180dd`, `a_180dd`, `a_360dd`, `vendastt`, `existente`, `res_temporaria`, `res_definitiva`, `disponivel`, `saldo_parte`, `em_beneficiamento`, `cet`, `etq`, `cep`, `manutencao`, `compras_aberto`, `orctt`, `orcvalido`, `exclui`, `atualizado`) VALUES ('$iditemcompra','$request->id_pedido','$gravaitem->agrup','$gravaitem->modelo','$gravaitem->id','$gravaitem->secundario','$gravaitem->clasmod','$gravaitem->clasitem','$gravaitem->colmod','$gravaitem->colitem','$gravaitem->statusatual','$gravaitem->valortabela','$gravaitem->saldo_most','$gravaitem->trocas','$gravaitem->media_venda','$gravaitem->ult_30dd','$gravaitem->ult_60dd','$gravaitem->ult_90dd','$gravaitem->ult_120dd','$gravaitem->ult_150dd','$gravaitem->ult_180dd','$gravaitem->a_180dd','$gravaitem->a_360dd','$gravaitem->vendastt','$gravaitem->existente','$gravaitem->res_temporaria','$gravaitem->res_definitiva','$gravaitem->disponivel','$gravaitem->saldo_parte','$gravaitem->em_beneficiamento','$gravaitem->cet','$gravaitem->etq','$gravaitem->cep','$gravaitem->manutencao','$gravaitem->compras_aberto','$gravaitem->orctt','$gravaitem->orcvalido','0','0')	" );
          }


          //          $entrega = new\ App\ CompraEntrega();
          //          $entrega->id_compra_item = $novoitem->id;
          //          $entrega->id_usuario = \Auth::id();
          //          $entrega->tipo = 'teste';
          //          $entrega->dt_entrega = $request->dt_entrega;
          //          $entrega->qtde_entrega = $request->qtde;
          //          $entrega->save();

          $request->session()->flash( 'alert-success', 'Item inserido com sucesso1.' );

        } else {

          $request->session()->flash( 'alert-warning', 'Fornecedor incorreto para o item1.' );

        }

      } else {

        $request->session()->flash( 'alert-warning', 'Item não existe.' );

      }


    } else {

      $request->session()->flash( 'alert-warning', 'Pedido não existe.' );

    }
	  }

    return redirect( '/compras/' . $request->id_pedido );

  }

  public function editaItem( Request $request ) {

    $edita = \App\ CompraItem::findOrFail( $request->id_compra_item );


    $edita->id_compra = $request->pedido;
    $edita->dt_prevista = $request->entrega;
    $edita->note = $request->obs;
    //$novoitem->item = $request->item;
    $edita->qtde = $request->qtde;
    //$novoitem->status = "ABERTO";
    //$novoitem->origem = "REPEDIDO";
    //$novoitem->solicitante = "BRASIL";
    //$novoitem->pedido_dt = date("Y-m-d");
    //$novoitem->dt_status = date("Y-m-d H:i:s");
    $edita->save();

    $iditemcompra = $request->id_compra_item;
    $id_pedido = $request->pedido;
    $deleta_analise = \DB::select( "DELETE FROM `compras_analise` WHERE id_compra_item = '$iditemcompra' " );


    $verifica_id = \DB::select( "select id_item from compras_itens where id = '$iditemcompra'" );
    $id_item = $verifica_id[ 0 ]->id_item;

    $grava_historico_item = \DB::select( "select i.agrup, i.modelo, i.id, i.secundario, i.clasmod, i.clasitem,i.colmod, i.colitem, i.statusatual, i.valortabela,
			sa.saldo_most, ifnull((select sum(qtde) 
            from trocas
            where  id_item = i.id
            ),0) as trocas, case when vs.ult_30dd > 0  and vs.ult_60dd > 0 then vs.ult_30dd+vs.ult_60dd /2
                 when vs.ult_30dd > 0  and vs.ult_60dd = 0 then vs.ult_30dd
                 when vs.ult_30dd = 0  and vs.ult_60dd > 0 then vs.ult_60dd
                 when vs.ult_30dd = 0  and vs.ult_60dd = 0 and vs.ult_90dd > 0 then vs.ult_90dd
                  else 0 end as media_venda, vs.ult_30dd, vs.ult_60dd, vs.ult_90dd, vs.ult_120dd, vs.ult_150dd, vs.ult_180dd,
			vs.a_180dd, vs.a_180dd+vs.ult_210dd+ vs.ult_240dd+ vs.ult_270dd+ vs.ult_300dd+ vs.ult_330dd+ vs.ult_360dd as a_360dd, vs.vendastt,
			sa.existente+sa.saldo_trocas as existente, sa.res_temporaria, sa.res_definitiva, (sa.existente+sa.saldo_trocas)-( sa.res_temporaria+ sa.res_definitiva) as disponivel, 
			sa.saldo_parte, sa.em_beneficiamento, sa.cet, sa.etq+sa.cet_li as  etq, sa.cep, sa.saldo_manutencao+sa.sucata as manutencao,ifnull((select sum(qtde) as qtde_compra
            from compras_itens
            
            where status = 'aberto' and pedido_dt > '2020-06-01'
			and item = i.secundario
            ),0) as compras_aberto, ifnull(orc.orctt,0) orctt, ifnull(orcvalido,0) orcvalido
			from itens i
			left join vendas_sint vs on i.id = vs.curto
			left join saldos sa on sa.curto = i.id
			left join orcamentos orc on orc.curto = i.id

			where i.id = '$id_item'" );
    // dd($id_item_compra);

    foreach ( $grava_historico_item as $gravaitem ) {

      $insert_historico = \DB::select( "INSERT INTO `compras_analise`(`id_compra_item`, `id_compra`, `agrupamento`, `modelo`, `id_item`, `item`, `clasmod`, `clasitem`, `colmod`, `colitem`, `statusatual`, `valortabela`, `mostruarios`, `trocas`, `media_venda`,`ult_30dd`, `ult_60dd`, `ult_90dd`, `ult_120dd`, `ult_150dd`, `ult_180dd`, `a_180dd`, `a_360dd`, `vendastt`, `existente`, `res_temporaria`, `res_definitiva`, `disponivel`, `saldo_parte`, `em_beneficiamento`, `cet`, `etq`, `cep`, `manutencao`, `compras_aberto`, `orctt`, `orcvalido`, `exclui`, `atualizado`) VALUES ('$iditemcompra','$id_pedido','$gravaitem->agrup','$gravaitem->modelo','$gravaitem->id','$gravaitem->secundario','$gravaitem->clasmod','$gravaitem->clasitem','$gravaitem->colmod','$gravaitem->colitem','$gravaitem->statusatual','$gravaitem->valortabela','$gravaitem->saldo_most','$gravaitem->trocas','$gravaitem->media_venda','$gravaitem->ult_30dd','$gravaitem->ult_60dd','$gravaitem->ult_90dd','$gravaitem->ult_120dd','$gravaitem->ult_150dd','$gravaitem->ult_180dd','$gravaitem->a_180dd','$gravaitem->a_360dd','$gravaitem->vendastt','$gravaitem->existente','$gravaitem->res_temporaria','$gravaitem->res_definitiva','$gravaitem->disponivel','$gravaitem->saldo_parte','$gravaitem->em_beneficiamento','$gravaitem->cet','$gravaitem->etq','$gravaitem->cep','$gravaitem->manutencao','$gravaitem->compras_aberto','$gravaitem->orctt','$gravaitem->orcvalido','0','0')	" );
    }

  }


  public function excluiItem( Request $request ) {

    $excluir = \App\ CompraItem::findOrFail( $request->id_compra_item );
    $excluir->status = 'CANCELADO';
    $excluir->save();

  }

  public function consultaItem( Request $request ) {

    $item = \App\ CompraItem::findOrFail( $request->id_compra_item );
    //dd($item);

    $entregas = \DB::select( "select *, compras_entregas.obs as obs_entrega from compras_entregas where id_compra_item = $request->id_compra_item
			 and exclui is null 
			order by qtd_entregue asc" );

    //$entregas = \App\CompraEntrega::where('id_compra_item', $request->id_compra_item)->where('exclui', 'is null	')->get();

    $item[ "qtde_restante" ] = $item->qtde - $item->qtde_conf;
    $item[ "distribuicao" ] = $entregas;


    return response()->json( $item );

  }

  public function exportaPedido( $id_pedido ) {

    $itens = \DB::connection( 'go' )->select( "
				select*, compras_itens.id as id_compras_itens , (select custo from custos_2019 where secundario = item  limit 1) as custo

				from compras_itens 
				left join itens on compras_itens.item = itens.secundario
				left join compras on id_compra = compras.id
				left join addressbook on id_fornecedor = addressbook.id

				where compras_itens.status <> 'CANCELADO'
				 AND compras_itens.status <> 'FINALIZADO SISTEMA'
				 
				and id_compra = '$id_pedido' " );


    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();


    $spreadsheet->getActiveSheet()->getColumnDimension( 'A' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'B' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'C' )->setWidth( 20 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'D' )->setWidth( 20 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'E' )->setWidth( 10 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'F' )->setWidth( 15 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'G' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'H' )->setWidth( 15 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'I' )->setWidth( 10 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'J' )->setWidth( 25 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'K' )->setWidth( 30 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'L' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'M' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'N' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'P' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'Q' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'R' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'S' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'T' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'U' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'V' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'W' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'X' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'Y' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'Z' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'AA' )->setWidth( 35 );


    $drawing = new\ PhpOffice\ PhpSpreadsheet\ Worksheet\ Drawing();
    $drawing->setName( 'Paid' );
    $drawing->setDescription( 'Paid' );
    $drawing->setPath( '/var/www/html/portal-gestao/public/img/logogo.png' ); // put your path and image here
    $drawing->setCoordinates( 'A1' );
    //$drawing->setOffsetX(110);
    $drawing->setHeight( 100 );
    $drawing->setWidth( 130 );
    //		$drawing->setRotation(25);
    $drawing->getShadow()->setVisible( true );
    //		$drawing->getShadow()->setDirection(45);
    $drawing->setWorksheet( $spreadsheet->getActiveSheet() );

    $spreadsheet->getActiveSheet()->getRowDimension( 1 )->setRowHeight( 100 );

    $spreadsheet->getActiveSheet()->getRowDimension( 4 )->setRowHeight( 25 );
    $spreadsheet->getActiveSheet()->getStyle( 'A4:AA4' )->getFont()->setBold( true );
    $spreadsheet->getActiveSheet()->getStyle( 'A4:AA4' )->getFill()->setFillType( \PhpOffice\ PhpSpreadsheet\ Style\ Fill::FILL_SOLID );


    $spreadsheet->getActiveSheet()->getStyle( 'K4:L4' )->getFill()->getStartColor()->setARGB( 'fef271' );
    $spreadsheet->getActiveSheet()->getStyle( 'M4:O4' )->getFill()->getStartColor()->setARGB( 'C0C0C0' );
    $spreadsheet->getActiveSheet()->getStyle( 'K4:L4' )->getFill()->getStartColor()->setARGB( '6495ED' );
    $spreadsheet->getActiveSheet()->getStyle( 'P4:R4' )->getFill()->getStartColor()->setARGB( '66CDAA' );
    $spreadsheet->getActiveSheet()->getStyle( 'S4:U4' )->getFill()->getStartColor()->setARGB( '32CD32' );
    $spreadsheet->getActiveSheet()->getStyle( 'V4:X4' )->getFill()->getStartColor()->setARGB( 'CD853F' );
    $spreadsheet->getActiveSheet()->getStyle( 'Y4:AA4' )->getFill()->getStartColor()->setARGB( 'BA55D3' );


    $spreadsheet->getActiveSheet()->getStyle( 'A4:J4' )->getFill()->getStartColor()->setARGB( '004c98' );
    $spreadsheet->getActiveSheet()->getStyle( 'A4:J4' )->getFont()->getColor()->setARGB( \PhpOffice\ PhpSpreadsheet\ Style\ Color::COLOR_WHITE );

    $spreadsheet->getActiveSheet()->getStyle( 'A1:Z1' )->getFill()->setFillType( \PhpOffice\ PhpSpreadsheet\ Style\ Fill::FILL_SOLID );
    $spreadsheet->getActiveSheet()->getStyle( 'A1:Z1' )->getFill()->getStartColor()->setARGB( 'ffffff' );

    $spreadsheet->getActiveSheet()->getStyle( 'A2:Z2' )->getFill()->setFillType( \PhpOffice\ PhpSpreadsheet\ Style\ Fill::FILL_SOLID );
    $spreadsheet->getActiveSheet()->getStyle( 'A2:Z2' )->getFill()->getStartColor()->setARGB( 'ffffff' );

    $spreadsheet->getActiveSheet()->getStyle( 'A3:Z3' )->getFill()->setFillType( \PhpOffice\ PhpSpreadsheet\ Style\ Fill::FILL_SOLID );
    $spreadsheet->getActiveSheet()->getStyle( 'A3:Z3' )->getFill()->getStartColor()->setARGB( 'ffffff' );


    $spreadsheet->getActiveSheet()->getStyle( 'K3:AA3' )->getFill()->getStartColor()->setARGB( 'fef271' );
    $spreadsheet->getActiveSheet()->mergeCells( 'K3:AA3' );

    $spreadsheet->getActiveSheet()->getStyle( 'K3' )->getAlignment()->setHorizontal( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::HORIZONTAL_CENTER );

    $spreadsheet->getActiveSheet()->getStyle( 'K3' )->getFont()->setSize( 13 );
    $spreadsheet->getActiveSheet()->getStyle( 'K3' )->getFont()->setBold( true );


    IF( $itens[ 0 ]->FORNECEDOR = 'KERING EYEWEAR SPA' ) {
      $NOTESKU = 'PO NAME';
    }
    ELSE {
      $NOTESKU = 'NOTE SKU';
    }

    IF( $itens[ 0 ]->FORNECEDOR = 'KERING EYEWEAR SPA' ) {
      $PODATE = 'PO DATE';
    }
    ELSE {
      $PODATE = 'DELIVERY DATE';
    }


    $sheet->setCellValue( 'K3', 'SUPPLIER' );

    $sheet->setCellValue( 'A4', 'BRAND' );
    $sheet->setCellValue( 'B4', 'STYLE NAME' );
    $sheet->setCellValue( 'C4', 'DESCRIPTION' );
    $sheet->setCellValue( 'D4', 'SKU' );
    $sheet->setCellValue( 'E4', 'ID' );
    $sheet->setCellValue( 'F4', 'QUANTITY' );
    $sheet->setCellValue( 'G4', $NOTESKU );
    $sheet->setCellValue( 'H4', $PODATE );
    $sheet->setCellValue( 'I4', 'PRICE' );

    $sheet->setCellValue( 'J4', 'VALUE' );
    $sheet->setCellValue( 'K4', 'QTT TOTAL CONFIRMED ORDER' );

    $sheet->setCellValue( 'L4', 'TOTAL DELIVERIES' );

    $sheet->setCellValue( 'M4', '1º DELIVERY QUANTITY' );
    $sheet->setCellValue( 'N4', '1º DATE DELIVERY CONFIRMED' );
    $sheet->setCellValue( 'O4', '1º NOTE' );

    $sheet->setCellValue( 'P4', '2º DELIVERY QUANTITY' );
    $sheet->setCellValue( 'Q4', '2º DATE DELIVERY CONFIRMED' );
    $sheet->setCellValue( 'R4', '2º NOTE' );

    $sheet->setCellValue( 'S4', '3º DELIVERY QUANTITY' );
    $sheet->setCellValue( 'T4', '3º DATE DELIVERY CONFIRMED' );
    $sheet->setCellValue( 'U4', '3º NOTE' );


    $sheet->setCellValue( 'V4', '4º DELIVERY QUANTITY' );
    $sheet->setCellValue( 'W4', '4º DATE DELIVERY CONFIRMED' );
    $sheet->setCellValue( 'X4', '4º NOTE' );

    $sheet->setCellValue( 'Y4', '5º DELIVERY QUANTITY' );
    $sheet->setCellValue( 'Z4', '5º DATE DELIVERY CONFIRMED' );
    $sheet->setCellValue( 'AA4', '5º NOTE' );

    $spreadsheet->getActiveSheet()->getStyle( 'A4:AA4' )->getAlignment()->setHorizontal( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::HORIZONTAL_CENTER )->setVertical( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::VERTICAL_CENTER );

    $spreadsheet->getActiveSheet()->getStyle( 'B1' )->getAlignment()->setHorizontal( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::HORIZONTAL_LEFT )->setVertical( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::VERTICAL_CENTER );
    $spreadsheet->getActiveSheet()->getStyle( 'B1' )->getFont()->setSize( 13 );
    $spreadsheet->getActiveSheet()->getStyle( 'B1' )->getBorders()->getTop()->setBorderStyle( \PhpOffice\ PhpSpreadsheet\ Style\ Border::BORDER_THICK );
    $spreadsheet->getActiveSheet()->getStyle( 'B1' )->getBorders()->getBottom()->setBorderStyle( \PhpOffice\ PhpSpreadsheet\ Style\ Border::BORDER_THICK );
    $spreadsheet->getActiveSheet()->getStyle( 'B1' )->getBorders()->getLeft()->setBorderStyle( \PhpOffice\ PhpSpreadsheet\ Style\ Border::BORDER_THICK );
    $spreadsheet->getActiveSheet()->getStyle( 'B1' )->getBorders()->getRight()->setBorderStyle( \PhpOffice\ PhpSpreadsheet\ Style\ Border::BORDER_THICK );
    $spreadsheet->getActiveSheet()->getStyle( 'B1' )->getFont()->setSize( 13 );

    $spreadsheet->getActiveSheet()->getStyle( 'B1' )->getFont()->setBold( true );


    $spreadsheet->getActiveSheet()->getStyle( 'G1' )->getAlignment()->setHorizontal( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::HORIZONTAL_LEFT )->setVertical( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::VERTICAL_CENTER );

    $spreadsheet->getActiveSheet()->getStyle( 'G1' )->getBorders()->getTop()->setBorderStyle( \PhpOffice\ PhpSpreadsheet\ Style\ Border::BORDER_THICK );
    $spreadsheet->getActiveSheet()->getStyle( 'G1' )->getBorders()->getBottom()->setBorderStyle( \PhpOffice\ PhpSpreadsheet\ Style\ Border::BORDER_THICK );
    $spreadsheet->getActiveSheet()->getStyle( 'G1' )->getBorders()->getLeft()->setBorderStyle( \PhpOffice\ PhpSpreadsheet\ Style\ Border::BORDER_THICK );
    $spreadsheet->getActiveSheet()->getStyle( 'G1' )->getBorders()->getRight()->setBorderStyle( \PhpOffice\ PhpSpreadsheet\ Style\ Border::BORDER_THICK );


    $spreadsheet->getActiveSheet()->getStyle( 'G1' )->getFont()->setBold( true );

    $spreadsheet->getActiveSheet()->getStyle( 'G1' )->getAlignment()->setWrapText( true );
    $sheet->setCellValue( 'G1',
      'SUPPLIER' . "\n" . $itens[ 0 ]->razao . "\n" . $itens[ 0 ]->endereco . "\n" . $itens[ 0 ]->municipio . ' - ' . $itens[ 0 ]->uf . ' - ' . $itens[ 0 ]->pais );


    $spreadsheet->getActiveSheet()->getStyle( 'B1' )->getAlignment()->setWrapText( true );

    IF( $itens[ 0 ]->FORNECEDOR = 'KERING EYEWEAR SPA' ) {
      $PURCHASEORDER = 'PURCHASE ORDER #' . $itens[ 0 ]->obs . "\n" .
      'INTERNAL CODE #' . $itens[ 0 ]->id_compra . "\n";


    }
    ELSE {
      $PURCHASEORDER = 'PURCHASE ORDER #' . $itens[ 0 ]->id_compra . "\n" . "\n";
    }

    $sheet->setCellValue( 'B1',

      $PURCHASEORDER
      . 'P.O. Data: ' . $itens[ 0 ]->dt_emissao . "\n" .
      'Payment Terms: ' . $itens[ 0 ]->pagamento . "\n" .
      'Shipping Methods: ' . $itens[ 0 ]->transporte )


    ;


    $linha = 4;

    foreach ( $itens as $item ) {

      $linha++;
      $spreadsheet->getActiveSheet()->getRowDimension( $linha )->setRowHeight( 25 );
      if ( $item->custo > 0 ) {
        $custo = $item->custo;
      } else {
        $custo = $item->ultcusto;
      }

      $total = $custo * $item->qtde;

      $spreadsheet->getActiveSheet()->getStyle( 'A' . $linha . ':AA' . $linha )->getAlignment()->setVertical( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::VERTICAL_CENTER );
      $spreadsheet->getActiveSheet()->getStyle( 'A' . $linha . ':AA' . $linha )->getAlignment()->setHorizontal( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::HORIZONTAL_CENTER );

      $spreadsheet->getActiveSheet()->getStyle( 'I' . $linha . ':J' . $linha )->getNumberFormat()->setFormatCode( \PhpOffice\ PhpSpreadsheet\ Style\ NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2 );


      $sheet->setCellValue( 'A' . $linha, $item->agrup );
      $sheet->setCellValue( 'B' . $linha, $item->item );
      $sheet->setCellValue( 'C' . $linha, $item->descricao );
      $sheet->setCellValue( 'D' . $linha, $item->primario );
      $sheet->setCellValue( 'E' . $linha, $item->id_compras_itens );
      $sheet->setCellValue( 'F' . $linha, $item->qtde );
      $sheet->setCellValue( 'G' . $linha, $item->note );
      $sheet->setCellValue( 'H' . $linha, $item->dt_prevista );
      $sheet->setCellValue( 'I' . $linha, $custo );
      $sheet->setCellValue( 'J' . $linha, $total );

    }

    $writer = new Xlsx( $spreadsheet );
    //	$writer->save('hello world.xlsx');		
    header( 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' );
    header( 'Content-Disposition: attachment;filename="PURCHASE ORDER #' . $itens[ 0 ]->id_compra . ' -' . date( 'Y-m-d', strtotime( $itens[ 0 ]->dt_emissao ) ) . '.xlsx"' );
    header( 'Cache-Control: max-age=0' );

    $writer = \PhpOffice\ PhpSpreadsheet\ IOFactory::createWriter( $spreadsheet, 'Xlsx' );
    $writer->save( 'php://output' );


    $nome_excel = '/var/www/html/portal-gestao/storage/app/pedido.xlsx';
    // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    // header('Content-Disposition: attachment;filename="'.$nome.'"');
    // header('Cache-Control: max-age=0');
    // // If you're serving to IE 9, then the following may be needed
    // header('Cache-Control: max-age=1');

    $writer = new Xlsx( $spreadsheet );
    $writer->save( $nome_excel );


  }


  public function exportaPedidoEmail( $id_pedido ) {

    $itens = \DB::connection( 'go' )->select( "
			select*, compras_itens.id as id_compras_itens , (select custo from custos_2019 where secundario = item  limit 1) as custo

				from compras_itens 
				left join itens on compras_itens.item = itens.secundario
				left join compras on id_compra = compras.id
				left join addressbook on id_fornecedor = addressbook.id

				where compras_itens.status <> 'CANCELADO'
				and compras_itens.status <> 'FINALIZADO SISTEMA'
				and compras_itens.status <> 'CANCELADO'
				and id_compra = '$id_pedido' " );


    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();


    $spreadsheet->getActiveSheet()->getColumnDimension( 'A' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'B' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'C' )->setWidth( 20 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'D' )->setWidth( 20 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'E' )->setWidth( 10 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'F' )->setWidth( 15 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'G' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'H' )->setWidth( 15 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'I' )->setWidth( 10 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'J' )->setWidth( 25 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'K' )->setWidth( 30 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'L' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'M' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'N' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'P' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'Q' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'R' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'S' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'T' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'U' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'V' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'W' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'X' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'Y' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'Z' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'AA' )->setWidth( 35 );


    $drawing = new\ PhpOffice\ PhpSpreadsheet\ Worksheet\ Drawing();
    $drawing->setName( 'Paid' );
    $drawing->setDescription( 'Paid' );
    $drawing->setPath( '/var/www/html/portal-gestao/public/img/logogo.png' ); // put your path and image here
    $drawing->setCoordinates( 'A1' );
    //$drawing->setOffsetX(110);
    $drawing->setHeight( 100 );
    $drawing->setWidth( 130 );
    //		$drawing->setRotation(25);
    $drawing->getShadow()->setVisible( true );
    //		$drawing->getShadow()->setDirection(45);
    $drawing->setWorksheet( $spreadsheet->getActiveSheet() );

    $spreadsheet->getActiveSheet()->getRowDimension( 1 )->setRowHeight( 100 );

    $spreadsheet->getActiveSheet()->getRowDimension( 4 )->setRowHeight( 25 );
    $spreadsheet->getActiveSheet()->getStyle( 'A4:AA4' )->getFont()->setBold( true );
    $spreadsheet->getActiveSheet()->getStyle( 'A4:AA4' )->getFill()->setFillType( \PhpOffice\ PhpSpreadsheet\ Style\ Fill::FILL_SOLID );


    $spreadsheet->getActiveSheet()->getStyle( 'K4:L4' )->getFill()->getStartColor()->setARGB( 'fef271' );
    $spreadsheet->getActiveSheet()->getStyle( 'M4:O4' )->getFill()->getStartColor()->setARGB( 'C0C0C0' );
    $spreadsheet->getActiveSheet()->getStyle( 'K4:L4' )->getFill()->getStartColor()->setARGB( '6495ED' );
    $spreadsheet->getActiveSheet()->getStyle( 'P4:R4' )->getFill()->getStartColor()->setARGB( '66CDAA' );
    $spreadsheet->getActiveSheet()->getStyle( 'S4:U4' )->getFill()->getStartColor()->setARGB( '32CD32' );
    $spreadsheet->getActiveSheet()->getStyle( 'V4:X4' )->getFill()->getStartColor()->setARGB( 'CD853F' );
    $spreadsheet->getActiveSheet()->getStyle( 'Y4:AA4' )->getFill()->getStartColor()->setARGB( 'BA55D3' );


    $spreadsheet->getActiveSheet()->getStyle( 'A4:J4' )->getFill()->getStartColor()->setARGB( '004c98' );
    $spreadsheet->getActiveSheet()->getStyle( 'A4:J4' )->getFont()->getColor()->setARGB( \PhpOffice\ PhpSpreadsheet\ Style\ Color::COLOR_WHITE );

    $spreadsheet->getActiveSheet()->getStyle( 'A1:Z1' )->getFill()->setFillType( \PhpOffice\ PhpSpreadsheet\ Style\ Fill::FILL_SOLID );
    $spreadsheet->getActiveSheet()->getStyle( 'A1:Z1' )->getFill()->getStartColor()->setARGB( 'ffffff' );

    $spreadsheet->getActiveSheet()->getStyle( 'A2:Z2' )->getFill()->setFillType( \PhpOffice\ PhpSpreadsheet\ Style\ Fill::FILL_SOLID );
    $spreadsheet->getActiveSheet()->getStyle( 'A2:Z2' )->getFill()->getStartColor()->setARGB( 'ffffff' );

    $spreadsheet->getActiveSheet()->getStyle( 'A3:Z3' )->getFill()->setFillType( \PhpOffice\ PhpSpreadsheet\ Style\ Fill::FILL_SOLID );
    $spreadsheet->getActiveSheet()->getStyle( 'A3:Z3' )->getFill()->getStartColor()->setARGB( 'ffffff' );


    $spreadsheet->getActiveSheet()->getStyle( 'K3:AA3' )->getFill()->getStartColor()->setARGB( 'fef271' );
    $spreadsheet->getActiveSheet()->mergeCells( 'K3:AA3' );

    $spreadsheet->getActiveSheet()->getStyle( 'K3' )->getAlignment()->setHorizontal( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::HORIZONTAL_CENTER );

    $spreadsheet->getActiveSheet()->getStyle( 'K3' )->getFont()->setSize( 13 );
    $spreadsheet->getActiveSheet()->getStyle( 'K3' )->getFont()->setBold( true );


    IF( $itens[ 0 ]->FORNECEDOR = 'KERING EYEWEAR SPA' ) {
      $NOTESKU = 'PO NAME';
    }
    ELSE {
      $NOTESKU = 'NOTE SKU';
    }

    IF( $itens[ 0 ]->FORNECEDOR = 'KERING EYEWEAR SPA' ) {
      $PODATE = 'PO DATE';
    }
    ELSE {
      $PODATE = 'DELIVERY DATE';
    }


    $sheet->setCellValue( 'K3', 'SUPPLIER' );

    $sheet->setCellValue( 'A4', 'BRAND' );
    $sheet->setCellValue( 'B4', 'STYLE NAME' );
    $sheet->setCellValue( 'C4', 'DESCRIPTION' );
    $sheet->setCellValue( 'D4', 'SKU' );
    $sheet->setCellValue( 'E4', 'ID' );
    $sheet->setCellValue( 'F4', 'QUANTITY' );
    $sheet->setCellValue( 'G4', $NOTESKU );
    $sheet->setCellValue( 'H4', $PODATE );
    $sheet->setCellValue( 'I4', 'PRICE' );

    $sheet->setCellValue( 'J4', 'VALUE' );
    $sheet->setCellValue( 'K4', 'QTT TOTAL CONFIRMED ORDER' );

    $sheet->setCellValue( 'L4', 'TOTAL DELIVERIES' );

    $sheet->setCellValue( 'M4', '1º DELIVERY QUANTITY' );
    $sheet->setCellValue( 'N4', '1º DATE DELIVERY CONFIRMED' );
    $sheet->setCellValue( 'O4', '1º NOTE' );

    $sheet->setCellValue( 'P4', '2º DELIVERY QUANTITY' );
    $sheet->setCellValue( 'Q4', '2º DATE DELIVERY CONFIRMED' );
    $sheet->setCellValue( 'R4', '2º NOTE' );

    $sheet->setCellValue( 'S4', '3º DELIVERY QUANTITY' );
    $sheet->setCellValue( 'T4', '3º DATE DELIVERY CONFIRMED' );
    $sheet->setCellValue( 'U4', '3º NOTE' );


    $sheet->setCellValue( 'V4', '4º DELIVERY QUANTITY' );
    $sheet->setCellValue( 'W4', '4º DATE DELIVERY CONFIRMED' );
    $sheet->setCellValue( 'X4', '4º NOTE' );

    $sheet->setCellValue( 'Y4', '5º DELIVERY QUANTITY' );
    $sheet->setCellValue( 'Z4', '5º DATE DELIVERY CONFIRMED' );
    $sheet->setCellValue( 'AA4', '5º NOTE' );
	  


    $spreadsheet->getActiveSheet()->getStyle( 'A4:AB4' )->getAlignment()->setHorizontal( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::HORIZONTAL_CENTER )->setVertical( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::VERTICAL_CENTER );

    $spreadsheet->getActiveSheet()->getStyle( 'B1' )->getAlignment()->setHorizontal( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::HORIZONTAL_LEFT )->setVertical( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::VERTICAL_CENTER );
    $spreadsheet->getActiveSheet()->getStyle( 'B1' )->getFont()->setSize( 13 );
    $spreadsheet->getActiveSheet()->getStyle( 'B1' )->getBorders()->getTop()->setBorderStyle( \PhpOffice\ PhpSpreadsheet\ Style\ Border::BORDER_THICK );
    $spreadsheet->getActiveSheet()->getStyle( 'B1' )->getBorders()->getBottom()->setBorderStyle( \PhpOffice\ PhpSpreadsheet\ Style\ Border::BORDER_THICK );
    $spreadsheet->getActiveSheet()->getStyle( 'B1' )->getBorders()->getLeft()->setBorderStyle( \PhpOffice\ PhpSpreadsheet\ Style\ Border::BORDER_THICK );
    $spreadsheet->getActiveSheet()->getStyle( 'B1' )->getBorders()->getRight()->setBorderStyle( \PhpOffice\ PhpSpreadsheet\ Style\ Border::BORDER_THICK );
    $spreadsheet->getActiveSheet()->getStyle( 'B1' )->getFont()->setSize( 13 );

    $spreadsheet->getActiveSheet()->getStyle( 'B1' )->getFont()->setBold( true );


    $spreadsheet->getActiveSheet()->getStyle( 'G1' )->getAlignment()->setHorizontal( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::HORIZONTAL_LEFT )->setVertical( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::VERTICAL_CENTER );

    $spreadsheet->getActiveSheet()->getStyle( 'G1' )->getBorders()->getTop()->setBorderStyle( \PhpOffice\ PhpSpreadsheet\ Style\ Border::BORDER_THICK );
    $spreadsheet->getActiveSheet()->getStyle( 'G1' )->getBorders()->getBottom()->setBorderStyle( \PhpOffice\ PhpSpreadsheet\ Style\ Border::BORDER_THICK );
    $spreadsheet->getActiveSheet()->getStyle( 'G1' )->getBorders()->getLeft()->setBorderStyle( \PhpOffice\ PhpSpreadsheet\ Style\ Border::BORDER_THICK );
    $spreadsheet->getActiveSheet()->getStyle( 'G1' )->getBorders()->getRight()->setBorderStyle( \PhpOffice\ PhpSpreadsheet\ Style\ Border::BORDER_THICK );


    $spreadsheet->getActiveSheet()->getStyle( 'G1' )->getFont()->setBold( true );

    $spreadsheet->getActiveSheet()->getStyle( 'G1' )->getAlignment()->setWrapText( true );
    $sheet->setCellValue( 'G1',
      'SUPPLIER' . "\n" . $itens[ 0 ]->razao . "\n" . $itens[ 0 ]->endereco . "\n" . $itens[ 0 ]->municipio . ' - ' . $itens[ 0 ]->uf . ' - ' . $itens[ 0 ]->pais );


    $spreadsheet->getActiveSheet()->getStyle( 'B1' )->getAlignment()->setWrapText( true );

    IF( $itens[ 0 ]->FORNECEDOR = 'KERING EYEWEAR SPA' ) {
      $PURCHASEORDER = 'PURCHASE ORDER #' . $itens[ 0 ]->obs . "\n" .
      'INTERNAL CODE #' . $itens[ 0 ]->id_compra . "\n";


    }
    ELSE {
      $PURCHASEORDER = 'PURCHASE ORDER #' . $itens[ 0 ]->id_compra . "\n" . "\n";
    }

    $sheet->setCellValue( 'B1',

      $PURCHASEORDER
      . 'P.O. Data: ' . $itens[ 0 ]->dt_emissao . "\n" .
      'Payment Terms: ' . $itens[ 0 ]->pagamento . "\n" .
      'Shipping Methods: ' . $itens[ 0 ]->transporte )


    ;


    $linha = 4;

    foreach ( $itens as $item ) {

      $linha++;
      $spreadsheet->getActiveSheet()->getRowDimension( $linha )->setRowHeight( 25 );
      if ( $item->custo > 0 ) {
        $custo = $item->custo;
      } else {
        $custo = $item->ultcusto;
      }

      $total = $custo * $item->qtde;

      $spreadsheet->getActiveSheet()->getStyle( 'A' . $linha . ':AA' . $linha )->getAlignment()->setVertical( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::VERTICAL_CENTER );
      $spreadsheet->getActiveSheet()->getStyle( 'A' . $linha . ':AA' . $linha )->getAlignment()->setHorizontal( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::HORIZONTAL_CENTER );

      $spreadsheet->getActiveSheet()->getStyle( 'I' . $linha . ':J' . $linha )->getNumberFormat()->setFormatCode( \PhpOffice\ PhpSpreadsheet\ Style\ NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2 );


      $sheet->setCellValue( 'A' . $linha, $item->agrup );
      $sheet->setCellValue( 'B' . $linha, $item->item );
      $sheet->setCellValue( 'C' . $linha, $item->descricao );
      $sheet->setCellValue( 'D' . $linha, $item->primario );
      $sheet->setCellValue( 'E' . $linha, $item->id_compras_itens );
      $sheet->setCellValue( 'F' . $linha, $item->qtde );
      $sheet->setCellValue( 'G' . $linha, $item->note );
      $sheet->setCellValue( 'H' . $linha, $item->dt_prevista );
      $sheet->setCellValue( 'I' . $linha, $custo );
      $sheet->setCellValue( 'J' . $linha, $total );

    }

    $writer = new Xlsx( $spreadsheet );
    //	$writer->save('hello world.xlsx');		
    header( 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' );
    header( 'Content-Disposition: attachment;filename="PURCHASE ORDER #' . $itens[ 0 ]->id_compra . ' -' . date( 'Y-m-d', strtotime( $itens[ 0 ]->dt_emissao ) ) . '.xlsx"' );
    header( 'Cache-Control: max-age=0' );


    $nome_excel = '/var/www/html/portal-gestao/storage/app/order#' . $itens[ 0 ]->id_compra . '.xlsx';
    // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    // header('Content-Disposition: attachment;filename="'.$nome.'"');
    // header('Cache-Control: max-age=0');
    // // If you're serving to IE 9, then the following may be needed
    // header('Cache-Control: max-age=1');

    $writer = new Xlsx( $spreadsheet );
    $writer->save( $nome_excel );


  }

  public function enviaPedido( Request $request ) {


    $itens = \App\ CompraItem::where( 'id_compra', $request->id_pedido )->get();

    if ( $itens ) {

      $this->exportaPedidoEmail( $request->id_pedido );

      $id_usuario = \Auth::id();

      $data = date( "Y-m-d H:i:s" );

      $atualiza = \DB::select( "update compras set status = 'ENVIADO' where id = $request->id_pedido and status = 'aberto'" );
      $atualiza = \DB::select( "update compras_itens set status = 'ENVIADO' where id_compra = $request->id_pedido and status in ('ABERTO')" );

      $historico = \DB::select( "insert into compras_historico (id_tabela,tabela, id_usuario, data, historico) values ($request->id_pedido,'compras', $id_usuario, '$data', 'Pedido enviado para o fornecedor') " );


      $mail = new PHPMailer( true ); // Passing `true` enables exceptions

      try {

  $mail->CharSet = 'UTF-8';
                //Server settings
                $mail->SMTPDebug = 0;                                 // Enable verbose debug output
                $mail->isSMTP();                                      // Set mailer to use SMTP
                //$mail->isMail();                                      // Set mailer to use SMTP
                $mail->Host = 'imap.gmail.com';  // Specify main and backup SMTP servers
                $mail->SMTPAuth = true;                               // Enable SMTP authentication
                $mail->Username = 'goweb@goeyewear.com.br';                 // SMTP username
                $mail->Password = 'd6SHzwSu';                           // SMTP password
                $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 465;                                    // TCP port to connect to

        //Recipients
        $mail->setFrom( 'grifes@goeyewear.com.br', 'Gestão de Grifes GO Eyewear' );

        $mail->addReplyTo( 'grifes@goeyewear.com.br', 'Gestão de Grifes GO Eyewear' );
        //$mail->addAddress('mariana.marao@goeyewear.com.br'); 
        $mail->addAddress( 'sandro@goeyewear.com.br' );
        $mail->addAddress( 'ivan@goeyewear.com.br' );
        $mail->addAddress( 'grifes@goeyewear.com.br' );

        foreach ( $request->email as $email ) {

          $mail->addAddress( $email ); // Add a recipient

        }

        $nome_excel = '/var/www/html/portal-gestao/storage/app/order#' . $request->id_pedido . '.xlsx';
        $mail->AddAttachment( $nome_excel );
        //$mail->addAddress('fabio@oncore.com.br');               // Name is optional
        //$mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('cc@example.com');
        // $mail->addBCC('fabio@oncore.com.br');

        //Content
        $mail->isHTML( true ); // Set email format to HTML
        $mail->Subject = 'GO Eyewear - Purchase Order #' . $request->id_pedido;
        $msg = nl2br( $request->obs );
        $mail->Body = $msg;
        //web$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();


      } catch ( Exception $e ) {
        echo '<br>Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
      }

    }


    //$this->exportaPedido($request->id_pedido);

    $request->session()->flash( 'alert-success', 'Pedido enviado com sucesso!' );

    return redirect( '/compras/' . $request->id_pedido );

  }


  public function enviaatrasos() {

    $atraso_analitico = \DB::connection( 'go' )->select( "
    		select 
    		agrup, 
    		modelo,
    		clasmod,
    		colmod,
    		secundario,
    		id_compra, 
    		pedido_nro,
    		pedido_dt,
    		dt_conf,
    		dt_confirmacao, 
    		dt_entrega_real, 
    		qtde_conf, 
    		estoque, 
    		producao, 
    		perc_producao, 
    		TIMESTAMPDIFF(DAY,dt_entrega_real,now()) as dias_atraso 
    		from(
    		select*, 
    		case when nova_data_producao <> '' then nova_data_producao else dt_entrega end as dt_entrega_real
    		from(
    		select itens.agrup as agrup, 
    		itens.modelo,
    		clasmod,
    		itens.colmod as colmod,
    		itens.secundario, 
    		pedido_nro,
    		compras_itens.pedido_dt,
    		id_compra,
    		dt_conf,
    		dt_confirmacao, 
    		dt_entrega, 
    		qtde_conf, 
    		estoque, 
    		producao, 
    		concat(format((producao/qtd_pedido)*100,0),'%') as 'perc_producao', 

    		case when (clasmod = 'linha a+' or clasmod = 'linha a++') then 1
    		when clasmod = 'novo' then 2
    		when clasmod = 'linha a' then 3
    		when clasmod = 'linha a-' then 4
    		when clasmod = 'colecao b' then 5
    		when clasmod = 'promocional c' then 6
    		else 7 end as 'ordem2',
    		(select nova_data_producao from historicos where itens.id = historicos.id_item and compras_itens.pedido_nro = historicos.pedido_fabrica order by historicos.created_at desc limit 1) as nova_data_producao

    		from compras_itens
    		left join producoes_anterior on compras_itens.pedido_nro = producoes_anterior.pedido and compras_itens.item = producoes_anterior.cod_sec
    		left join itens on compras_itens.item = itens.secundario


    		where producao/qtd_pedido > 0
    		and dt_conf < current_date()
    		and (id_compra >= 202007 or id_compra = '201973')
    		and dt_confirmacao <> '1969-12-31'


    		) as base
    		ORDER BY agrup asc, clasmod asc, TIMESTAMPDIFF(DAY,dt_conf,current_date()) desc, modelo asc
    		) as base2
    		where TIMESTAMPDIFF(DAY,dt_entrega_real,now()) > 0
    		ORDER BY agrup asc, ordem2 asc, TIMESTAMPDIFF(DAY,dt_entrega_real,current_date()) desc, modelo asc" );


    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();


    $spreadsheet->getActiveSheet()->getColumnDimension( 'A' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'B' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'C' )->setWidth( 20 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'D' )->setWidth( 20 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'E' )->setWidth( 10 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'F' )->setWidth( 15 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'G' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'H' )->setWidth( 15 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'I' )->setWidth( 10 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'J' )->setWidth( 25 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'K' )->setWidth( 25 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'L' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'M' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'N' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'O' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'P' )->setWidth( 35 );


    $drawing = new\ PhpOffice\ PhpSpreadsheet\ Worksheet\ Drawing();
    $drawing->setName( 'Paid' );
    $drawing->setDescription( 'Paid' );
    $drawing->setPath( '/var/www/html/portal-gestao/public/img/logogo.png' ); // put your path and image here

    //		$drawing->getShadow()->setDirection(45);
    //$drawing->setWorksheet($spreadsheet->getActiveSheet());

    $spreadsheet->getActiveSheet()->getRowDimension( 1 )->setRowHeight( 100 );

    $spreadsheet->getActiveSheet()->getRowDimension( 4 )->setRowHeight( 25 );
    $spreadsheet->getActiveSheet()->getStyle( 'A1:P1' )->getFont()->setBold( true );
    //$spreadsheet->getActiveSheet()->getStyle('A1:P1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
    $spreadsheet->getActiveSheet()->getStyle( 'A1:P1' )->getAlignment()->setHorizontal( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::HORIZONTAL_CENTER )->setVertical( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::VERTICAL_CENTER );
    $spreadsheet->getActiveSheet()->setAutoFilter( 'A1:P1' );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'I' )->setAutoSize( true );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'E' )->setAutoSize( true );


    $sheet->setCellValue( 'A1', 'Grouping' );
    $sheet->setCellValue( 'B1', 'Model' );
    $sheet->setCellValue( 'C1', 'Class_Model' );
    $sheet->setCellValue( 'D1', 'Colec_Model' );
    $sheet->setCellValue( 'E1', 'SKU' );
    $sheet->setCellValue( 'F1', 'ID Brazil' );
    $sheet->setCellValue( 'G1', 'Order_No' );
    $sheet->setCellValue( 'H1', 'Order_Date' );
    $sheet->setCellValue( 'I1', 'Order_Delivery_Projection' );
    $sheet->setCellValue( 'J1', 'Delivery_Date' );
    $sheet->setCellValue( 'K1', 'Confirmed_Delivery_date' );
    $sheet->setCellValue( 'L1', 'Confirmed_Qty' );
    $sheet->setCellValue( 'M1', 'Stock' );
    $sheet->setCellValue( 'N1', 'Production' );
    $sheet->setCellValue( 'O1', 'Perc_Production' );
    $sheet->setCellValue( 'P1', 'Delayed_Days' );

    $spreadsheet->getActiveSheet()->getStyle( 'A:P' )->getAlignment()->setHorizontal( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::HORIZONTAL_CENTER )->setVertical( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::VERTICAL_CENTER );

    $linha = 1;

    foreach ( $atraso_analitico as $atraso_analitico2 ) {

      $linha++;
      $spreadsheet->getActiveSheet()->getRowDimension( $linha )->setRowHeight( 25 );


      $sheet->setCellValue( 'A' . $linha, $atraso_analitico2->agrup );
      $sheet->setCellValue( 'B' . $linha, $atraso_analitico2->modelo );
      $sheet->setCellValue( 'C' . $linha, $atraso_analitico2->clasmod );
      $sheet->setCellValue( 'D' . $linha, $atraso_analitico2->colmod );
      $sheet->setCellValue( 'E' . $linha, $atraso_analitico2->secundario );
      $sheet->setCellValue( 'F' . $linha, $atraso_analitico2->id_compra );
      $sheet->setCellValue( 'G' . $linha, $atraso_analitico2->pedido_nro );
      $sheet->setCellValue( 'H' . $linha, $atraso_analitico2->pedido_dt );
      $sheet->setCellValue( 'I' . $linha, $atraso_analitico2->dt_conf );
      $sheet->setCellValue( 'J' . $linha, $atraso_analitico2->dt_confirmacao );
      $sheet->setCellValue( 'K' . $linha, $atraso_analitico2->dt_entrega_real );
      $sheet->setCellValue( 'L' . $linha, $atraso_analitico2->qtde_conf );
      $sheet->setCellValue( 'M' . $linha, $atraso_analitico2->estoque );
      $sheet->setCellValue( 'N' . $linha, $atraso_analitico2->producao );
      $sheet->setCellValue( 'O' . $linha, $atraso_analitico2->perc_producao );
      $sheet->setCellValue( 'P' . $linha, $atraso_analitico2->dias_atraso );


    }

    $data = NOW();

    $writer = new Xlsx( $spreadsheet );
    //	$writer->save('hello world.xlsx');		
    //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //header('Content-Disposition: attachment;filename="PURCHASE ORDER #'.$data.'.xlsx"');
    //header('Cache-Control: max-age=0');


    $nome_excel1 = '/var/www/html/portal-gestao/storage/app/relatorio_atrasos_' . $data . '.xlsx';
    // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    // header('Content-Disposition: attachment;filename="'.$nome.'"');
    // header('Cache-Control: max-age=0');
    // // If you're serving to IE 9, then the following may be needed
    // header('Cache-Control: max-age=1');

    $writer = new Xlsx( $spreadsheet );
    $writer->save( $nome_excel1 );


    $atrasos = \DB::select( "

			select agrup, clasmod, count(secundario) as qtd, concat(format(avg(dias_atraso),0),' dias') as 'media_atraso', sum(producao) as producao1
			from(
			select *,TIMESTAMPDIFF(DAY,dt_entrega_real,now()) as dias_atraso from(
			select*, 
			case when nova_data_producao <> '' then nova_data_producao else dt_conf end as dt_entrega_real
			from(
			select itens.agrup as agrup, 
			itens.modelo,
			clasmod,
			itens.colmod as colmod,
			itens.secundario, 
			pedido_nro,
			compras_itens.pedido_dt,
			dt_conf,
			month(dt_conf) mes,
			dt_confirmacao, 
			dt_entrega, 
			qtde_conf, 
			estoque, 
			producao, 
			concat(format((producao/qtd_pedido)*100,0),'%') as 'perc_producao', 

			case when (clasmod = 'linha a+' or clasmod = 'linha a++') then 1
			when clasmod = 'novo' then 2
			when clasmod = 'linha a' then 3
			when clasmod = 'linha a-' then 4
			when clasmod = 'colecao b' then 5
			when clasmod = 'promocional c' then 6
			else 7 end as 'ordem2',
			(select nova_data_producao from historicos where itens.id = historicos.id_item and compras_itens.pedido_nro = historicos.pedido_fabrica order by historicos.created_at desc limit 1) as nova_data_producao

			from compras_itens
			left join producoes_anterior on compras_itens.pedido_nro = producoes_anterior.pedido and compras_itens.item = producoes_anterior.cod_sec
			left join itens on compras_itens.item = itens.secundario


			where producao/qtd_pedido > 0
			and dt_conf < current_date()
			and (id_compra >= 202007 or id_compra = '201973')
			and dt_confirmacao <> '1969-12-31'
			and year(dt_conf) = 2019
			-- and item = 'AH9293 G21'
			) as base
			ORDER BY agrup asc, clasmod asc, TIMESTAMPDIFF(DAY,dt_conf,current_date()) desc, modelo asc
			) as base2) as final
			group by agrup, clasmod
			order by agrup asc, ordem2 asc" );


    $mail = new PHPMailer( true ); // Passing `true` enables exceptions

    try {

  $mail->CharSet = 'UTF-8';
                //Server settings
                $mail->SMTPDebug = 0;                                 // Enable verbose debug output
                $mail->isSMTP();                                      // Set mailer to use SMTP
                //$mail->isMail();                                      // Set mailer to use SMTP
                $mail->Host = 'imap.gmail.com';  // Specify main and backup SMTP servers
                $mail->SMTPAuth = true;                               // Enable SMTP authentication
                $mail->Username = 'goweb@goeyewear.com.br';                 // SMTP username
                $mail->Password = 'd6SHzwSu';                           // SMTP password
                $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 465;                                    // TCP port to connect to

      //Recipients
      $mail->setFrom( 'grifes@goeyewear.com.br', 'Gestão de Grifes GO Eyewear' );

      $mail->addReplyTo( 'grifes@goeyewear.com.br', 'Gestão de Grifes GO Eyewear' );
      //$mail->addAddress('mariana.marao@goeyewear.com.br'); 
      $mail->addAddress( 'sandro@goeyewear.com.br' );
      $mail->addAddress( 'grifes@goeyewear.com.br' );
      //$mail->addCC('grifes@goeyewear.com.br');
      //$mail->addAddress('wellington.rodrigues@goeyewear.com.br');  
      //$mail->addAddress('edenilton.silva@goeyewear.com.br');            // Add a recipient

      //foreach ($request->email as $email) {

      //  $mail->addAddress($email);     // Add a recipient

      //} 

      //$nome_excel = '/var/www/html/portal-gestao/storage/app/order#'.$request->id_pedido.'.xlsx';
      $mail->AddAttachment( $nome_excel1 );
      //$mail->addAddress('fabio@oncore.com.br');               // Name is optional
      //$mail->addReplyTo('info@example.com', 'Information');
      //$mail->addCC('cc@example.com');
      // $mail->addBCC('fabio@oncore.com.br');

      //Content
      $mail->isHTML( true ); // Set email format to HTML
      $mail->Subject = 'Pedidos em atraso - ' . $date = date( 'Y-m-d' );

      $msg = "

                <body>

                <font color='black' face='arial'  size='2px'> <p>
                <h3>Pedidos em atraso</h3>
                Olá <b>Edenilton</b>, <br>
                Favor verificar com a fábrica os atrasos desses pedidos e pedir uma data e motivo pelo atraso.<br>
                Linha A+ - finalizar urgente.<br>
                Linha A - finalizar urgente.<br>
                <= Linha A- se for atrasar muito ideal seria cancelar.<br>
                Obrigado,<br>
                GOWEB<br>

                </font>


                <table border='1px' bgcolor='#ffffff' > 
                <tr >
                <td align='center' ><b></b></td> 
                <td align='center' ><b>Grouping</b></td> 
                <td align='center'><b>Class model</b></td>
                <td align='center'><b>Qtt SKU</b></td>
                <td align='center'><b>AVG delayed days</b></td>
                <td align='center'><b>Sum production</b></td>

                </tr>";
      $linha = 0;
      foreach ( $atrasos as $atrasos1 ) {
        $linha++;

        $msg .= '

                	<tr>
                	<td  >' . $linha . '</td> 
                	<td  >' . $atrasos1->agrup . '</td> 
                	<td >' . $atrasos1->clasmod . '</td>
                	<td >' . $atrasos1->qtd . '</td>
                	<td >' . $atrasos1->media_atraso . '</td>
                	<td >' . $atrasos1->producao1 . '</td>


                	</tr>';

      }
      $msg .= '      

                </table>
                </div>	';


      $mail->Body = $msg;
      //web$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

      $mail->send();


    } catch ( Exception $e ) {
      echo '<br>Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }

  }


  //$this->exportaPedido($request->id_pedido);

  //$request->session()->flash('alert-success', 'Pedido enviado com sucesso!');

  //return redirect('/compras/'.$request->id_pedido);


  public function diferencapedidos() {

    $diferencapedidosexcel = \DB::connection( 'go' )->select( "

    		select producoes.cod_sec as cod_sec, producoes.colecao, producoes.pedido  as numero_pedido,  producoes.qtd_pedido as qtd, dt_emissao,
    		producoes.dt_entrega
    		from producoes
    		left join compras_itens on producoes.pedido = compras_itens.pedido_nro and producoes.cod_sec = compras_itens.item
    		where  compras_itens.pedido_nro is null


    		and producoes.fabrica = 'zhongmin'


    		" );
    //dd($diferencapedidosexcel);


    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();


    $spreadsheet->getActiveSheet()->getColumnDimension( 'A' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'B' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'C' )->setWidth( 20 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'D' )->setWidth( 20 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'E' )->setWidth( 10 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'F' )->setWidth( 15 );


    $drawing = new\ PhpOffice\ PhpSpreadsheet\ Worksheet\ Drawing();
    $drawing->setName( 'Paid' );
    $drawing->setDescription( 'Paid' );
    $drawing->setPath( '/var/www/html/portal-gestao/public/img/logogo.png' ); // put your path and image here

    //		$drawing->getShadow()->setDirection(45);
    //$drawing->setWorksheet($spreadsheet->getActiveSheet());

    $spreadsheet->getActiveSheet()->getRowDimension( 1 )->setRowHeight( 100 );

    $spreadsheet->getActiveSheet()->getRowDimension( 4 )->setRowHeight( 25 );
    $spreadsheet->getActiveSheet()->getStyle( 'A1:P1' )->getFont()->setBold( true );
    //$spreadsheet->getActiveSheet()->getStyle('A1:P1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
    $spreadsheet->getActiveSheet()->getStyle( 'A1:P1' )->getAlignment()->setHorizontal( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::HORIZONTAL_CENTER )->setVertical( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::VERTICAL_CENTER );
    $spreadsheet->getActiveSheet()->setAutoFilter( 'A1:P1' );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'I' )->setAutoSize( true );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'E' )->setAutoSize( true );


    $sheet->setCellValue( 'A1', 'SKU' );
    $sheet->setCellValue( 'B1', 'Collection' );
    $sheet->setCellValue( 'C1', 'Number order' );
    $sheet->setCellValue( 'D1', 'Qtt' );
    $sheet->setCellValue( 'E1', 'Date order' );
    $sheet->setCellValue( 'F1', 'Delivery order' );

    $spreadsheet->getActiveSheet()->getStyle( 'A:P' )->getAlignment()->setHorizontal( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::HORIZONTAL_CENTER )->setVertical( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::VERTICAL_CENTER );

    $linha = 1;

    foreach ( $diferencapedidosexcel as $diferencapedidosexcel1 ) {

      $linha++;
      $spreadsheet->getActiveSheet()->getRowDimension( $linha )->setRowHeight( 25 );


      $sheet->setCellValue( 'A' . $linha, $diferencapedidosexcel1->cod_sec );
      $sheet->setCellValue( 'B' . $linha, $diferencapedidosexcel1->colecao );
      $sheet->setCellValue( 'C' . $linha, $diferencapedidosexcel1->numero_pedido );
      $sheet->setCellValue( 'D' . $linha, $diferencapedidosexcel1->qtd );
      $sheet->setCellValue( 'E' . $linha, $diferencapedidosexcel1->dt_emissao );
      $sheet->setCellValue( 'F' . $linha, $diferencapedidosexcel1->dt_entrega );


    }

    $data = NOW();

    $writer = new Xlsx( $spreadsheet );
    //	$writer->save('hello world.xlsx');		
    //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //header('Content-Disposition: attachment;filename="PURCHASE ORDER #'.$data.'.xlsx"');
    //header('Cache-Control: max-age=0');


    $nome_excel1 = '/var/www/html/portal-gestao/storage/app/relatorio_diferencapedido_' . $data . '.xlsx';
    // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    // header('Content-Disposition: attachment;filename="'.$nome.'"');
    // header('Cache-Control: max-age=0');
    // // If you're serving to IE 9, then the following may be needed
    // header('Cache-Control: max-age=1');

    $writer = new Xlsx( $spreadsheet );
    $writer->save( $nome_excel1 );


    $diferencapedidosemail = \DB::select( "

			select sigla, colecao, sum(qtd) as qtd
			from(select case when sigla in ('ah', 'at', 'bg', 'hi', 'jo', 'sp') then sigla 
            when left(sigla,1) = 't' then 'T'
            else 'SP' end as sigla, colecao , sum(Qtd) as qtd
			from(
			select  LEFT(producoes.cod_sec,2) as sigla, producoes.colecao , sum(producoes.qtd_pedido) as Qtd
			from producoes
			left join compras_itens on producoes.pedido = compras_itens.pedido_nro and producoes.cod_sec = compras_itens.item
			where  compras_itens.pedido_nro is null

				
			and producoes.fabrica = 'zhongmin'
			group by LEFT(producoes.cod_sec,2), producoes.colecao) as base
			group by  sigla, colecao) as base2
			group by  sigla, colecao
			order by sigla asc



			" );


    $mail = new PHPMailer( true ); // Passing `true` enables exceptions

    try {

  $mail->CharSet = 'UTF-8';
                //Server settings
                $mail->SMTPDebug = 0;                                 // Enable verbose debug output
                $mail->isSMTP();                                      // Set mailer to use SMTP
                //$mail->isMail();                                      // Set mailer to use SMTP
                $mail->Host = 'imap.gmail.com';  // Specify main and backup SMTP servers
                $mail->SMTPAuth = true;                               // Enable SMTP authentication
                $mail->Username = 'goweb@goeyewear.com.br';                 // SMTP username
                $mail->Password = 'd6SHzwSu';                           // SMTP password
                $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 465;                                    // TCP port to connect to

      //Recipients
      $mail->setFrom( 'grifes@goeyewear.com.br', 'Gestão de Grifes GO Eyewear' );

      $mail->addReplyTo( 'grifes@goeyewear.com.br', 'Gestão de Grifes GO Eyewear' );
      //$mail->addAddress('mariana.marao@goeyewear.com.br'); 

      $mail->addAddress( 'grifes@goeyewear.com.br' );
      //$mail->addCC('grifes@goeyewear.com.br');
      //$mail->addAddress('wellington.rodrigues@goeyewear.com.br');  
      //$mail->addAddress('edenilton.silva@goeyewear.com.br');            // Add a recipient

      //foreach ($request->email as $email) {

      //  $mail->addAddress($email);     // Add a recipient

      //} 

      //$nome_excel = '/var/www/html/portal-gestao/storage/app/order#'.$request->id_pedido.'.xlsx';
      $mail->AddAttachment( $nome_excel1 );
      //$mail->addAddress('fabio@oncore.com.br');               // Name is optional
      //$mail->addReplyTo('info@example.com', 'Information');
      //$mail->addCC('cc@example.com');
      // $mail->addBCC('fabio@oncore.com.br');

      //Content
      $mail->isHTML( true ); // Set email format to HTML
      $mail->Subject = 'Pedidos no CAT sem pedido no compras - ' . $date = date( 'Y-m-d' );

      $msg = "

                <body>

                <font color='black' face='arial'  size='2px'> <p>
                <h3>Pedidos no CAT sem pedido no compras </h3>
                Olá <b>Edenilton</b>, <br>
                Favor verificar esses pedidos em anexo que não temos em nosso controle, se são para existir favor inserir um pedido e vincular os numeros de pedidos da fábrica, ou questionar a fábrica se inseriram repedidos sem consultar a gente.<br>
                
                Obrigado,<br>
                GOWEB<br>

                </font>


                <table border='1px' bgcolor='#ffffff' > 
                <tr >
                <td align='center' ><b></b></td> 
                <td align='center' ><b>Grouping</b></td> 
                <td align='center'><b>Col model</b></td>
                <td align='center'><b>Sum production</b></td>

                </tr>";
      $linha = 0;
      foreach ( $diferencapedidosemail as $diferencapedidosemail1 ) {
        $linha++;

        $msg .= '

                	<tr>
                	<td  >' . $linha . '</td> 
                	<td  >' . $diferencapedidosemail1->sigla . '</td> 
                	<td >' . $diferencapedidosemail1->colecao . '</td>
                	<td >' . $diferencapedidosemail1->qtd . '</td>



                	</tr>';

      }
      $msg .= '      

                </table>
                </div>	';


      $mail->Body = $msg;
      //web$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

      $mail->send();


    } catch ( Exception $e ) {
      echo '<br>Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }

  }


  //$this->exportaPedido($request->id_pedido);

  //$request->session()->flash('alert-success', 'Pedido enviado com sucesso!');

  //return redirect('/compras/'.$request->id_pedido);

  //}

  public function mudancadataentrega() {

    $mudancadataentregasexcel = \DB::connection( 'go' )->select( "

    				Select*
        from(
			select agrup, itens.colmod, itens.clasmod ,  producoes.cod_sec, producoes.pedido, producoes.producao,
           producoes.dt_confirmacao as dt_confirmacao_atual , producoes_anterior.dt_confirmacao as dt_confirmacao_anterior,
             TIMESTAMPDIFF(DAY,producoes_anterior.dt_confirmacao,producoes.dt_confirmacao) as dias_atraso,
           
            case when (clasmod = 'linha a+' or clasmod = 'linha a++') then 1
			when clasmod = 'novo' then 2
			when clasmod = 'linha a' then 3
			when clasmod = 'linha a-' then 4
			when clasmod = 'colecao b' then 5
			when clasmod = 'promocional c' then 6
			else 7 end as 'ordem2'
            
			from producoes
			left join producoes_anterior on producoes_anterior.pedido = producoes.pedido and producoes_anterior.cod_sec = producoes.cod_sec
            left join itens on producoes.cod_sec = itens.secundario
			where  
            producoes.dt_confirmacao <> producoes_anterior.dt_confirmacao 
            and  producoes_anterior.pedido is not null

				
			and producoes.fabrica = 'zhongmin'
            and producoes.dt_confirmacao <>'1969-12-31'
            and producoes_anterior.dt_confirmacao <>'1969-12-31'
            and TIMESTAMPDIFF(DAY,producoes_anterior.dt_confirmacao,producoes.dt_confirmacao)  > 0
            
             ) as base
           
            order by agrup, ordem2, cod_sec asc
			


    		" );
    //dd($diferencapedidosexcel);


    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();


    $spreadsheet->getActiveSheet()->getColumnDimension( 'A' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'B' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'C' )->setWidth( 20 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'D' )->setWidth( 20 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'E' )->setWidth( 10 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'F' )->setWidth( 15 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'G' )->setWidth( 15 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'H' )->setWidth( 15 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'I' )->setWidth( 15 );


    $drawing = new\ PhpOffice\ PhpSpreadsheet\ Worksheet\ Drawing();
    $drawing->setName( 'Paid' );
    $drawing->setDescription( 'Paid' );
    $drawing->setPath( '/var/www/html/portal-gestao/public/img/logogo.png' ); // put your path and image here

    //		$drawing->getShadow()->setDirection(45);
    //$drawing->setWorksheet($spreadsheet->getActiveSheet());

    $spreadsheet->getActiveSheet()->getRowDimension( 1 )->setRowHeight( 100 );

    $spreadsheet->getActiveSheet()->getRowDimension( 4 )->setRowHeight( 25 );
    $spreadsheet->getActiveSheet()->getStyle( 'A1:I1' )->getFont()->setBold( true );
    //$spreadsheet->getActiveSheet()->getStyle('A1:P1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
    $spreadsheet->getActiveSheet()->getStyle( 'A1:I1' )->getAlignment()->setHorizontal( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::HORIZONTAL_CENTER )->setVertical( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::VERTICAL_CENTER );
    $spreadsheet->getActiveSheet()->setAutoFilter( 'A1:I1' );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'I' )->setAutoSize( true );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'E' )->setAutoSize( true );


    $sheet->setCellValue( 'A1', 'Grouping' );
    $sheet->setCellValue( 'B1', 'Collection' );
    $sheet->setCellValue( 'C1', 'Class Model' );
    $sheet->setCellValue( 'D1', 'SKU' );
    $sheet->setCellValue( 'E1', 'Order_No' );
    $sheet->setCellValue( 'F1', 'Production' );
    $sheet->setCellValue( 'G1', 'Date Delivery current' );
    $sheet->setCellValue( 'H1', 'Date Delivery before' );
    $sheet->setCellValue( 'I1', 'Delayed_Days' );


    $spreadsheet->getActiveSheet()->getStyle( 'A:I' )->getAlignment()->setHorizontal( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::HORIZONTAL_CENTER )->setVertical( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::VERTICAL_CENTER );

    $linha = 1;

    foreach ( $mudancadataentregasexcel as $mudancadataentregasexcel1 ) {

      $linha++;
      $spreadsheet->getActiveSheet()->getRowDimension( $linha )->setRowHeight( 25 );


      $sheet->setCellValue( 'A' . $linha, $mudancadataentregasexcel1->agrup );
      $sheet->setCellValue( 'B' . $linha, $mudancadataentregasexcel1->colmod );
      $sheet->setCellValue( 'C' . $linha, $mudancadataentregasexcel1->clasmod );
      $sheet->setCellValue( 'D' . $linha, $mudancadataentregasexcel1->cod_sec );
      $sheet->setCellValue( 'E' . $linha, $mudancadataentregasexcel1->pedido );
      $sheet->setCellValue( 'F' . $linha, $mudancadataentregasexcel1->producao );
      $sheet->setCellValue( 'G' . $linha, $mudancadataentregasexcel1->dt_confirmacao_atual );
      $sheet->setCellValue( 'H' . $linha, $mudancadataentregasexcel1->dt_confirmacao_anterior );
      $sheet->setCellValue( 'I' . $linha, $mudancadataentregasexcel1->dias_atraso );


    }

    $data = NOW();

    $writer = new Xlsx( $spreadsheet );
    //	$writer->save('hello world.xlsx');		
    //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //header('Content-Disposition: attachment;filename="PURCHASE ORDER #'.$data.'.xlsx"');
    //header('Cache-Control: max-age=0');


    $nome_excel1 = '/var/www/html/portal-gestao/storage/app/relatorio_mudancadataentrega_' . $data . '.xlsx';
    // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    // header('Content-Disposition: attachment;filename="'.$nome.'"');
    // header('Cache-Control: max-age=0');
    // // If you're serving to IE 9, then the following may be needed
    // header('Cache-Control: max-age=1');

    $writer = new Xlsx( $spreadsheet );
    $writer->save( $nome_excel1 );


    $mudancadataentregassemail = \DB::select( "

						select agrup,  sum(contagem) as contagem, colmod, clasmod , sum(soma) as soma,
             format(avg(dias_atraso),0) as dias_atrado, ordem2
            from(
			select agrup,  count(producoes.cod_sec) as contagem, itens.colmod, itens.clasmod , sum(producoes.producao) as soma,
             TIMESTAMPDIFF(DAY,producoes_anterior.dt_confirmacao,producoes.dt_confirmacao) as dias_atraso,
           
            case when (clasmod = 'linha a+' or clasmod = 'linha a++') then 1
			when clasmod = 'novo' then 2
			when clasmod = 'linha a' then 3
			when clasmod = 'linha a-' then 4
			when clasmod = 'colecao b' then 5
			when clasmod = 'promocional c' then 6
			else 7 end as 'ordem2'
            
			from producoes
			left join producoes_anterior on producoes_anterior.pedido = producoes.pedido and producoes_anterior.cod_sec = producoes.cod_sec
            left join itens on producoes.cod_sec = itens.secundario
			where  
            producoes.dt_confirmacao <> producoes_anterior.dt_confirmacao 
            and  producoes_anterior.pedido is not null

				
			and producoes.fabrica = 'zhongmin'
            and producoes.dt_confirmacao <>'1969-12-31'
            and producoes_anterior.dt_confirmacao <>'1969-12-31'
            and TIMESTAMPDIFF(DAY,producoes_anterior.dt_confirmacao,producoes.dt_confirmacao)  > 0
            
            group by agrup,  itens.colmod, itens.clasmod, producoes.dt_confirmacao, producoes_anterior.dt_confirmacao ) as base
            group by agrup,  colmod, clasmod 
            order by agrup, ordem2 asc
			



			" );


    $mail = new PHPMailer( true ); // Passing `true` enables exceptions

    try {
  $mail->CharSet = 'UTF-8';
                //Server settings
                $mail->SMTPDebug = 0;                                 // Enable verbose debug output
                $mail->isSMTP();                                      // Set mailer to use SMTP
                //$mail->isMail();                                      // Set mailer to use SMTP
                $mail->Host = 'imap.gmail.com';  // Specify main and backup SMTP servers
                $mail->SMTPAuth = true;                               // Enable SMTP authentication
                $mail->Username = 'goweb@goeyewear.com.br';                 // SMTP username
                $mail->Password = 'd6SHzwSu';                           // SMTP password
                $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 465;                                    // TCP port to connect to

      //Recipients
      $mail->setFrom( 'grifes@goeyewear.com.br', 'Gestão de Grifes GO Eyewear' );

      $mail->addReplyTo( 'grifes@goeyewear.com.br', 'Gestão de Grifes GO Eyewear' );
      //$mail->addAddress('mariana.marao@goeyewear.com.br'); 
      $mail->addAddress( 'sandro@goeyewear.com.br' );
      $mail->addAddress( 'grifes@goeyewear.com.br' );
      //$mail->addCC('grifes@goeyewear.com.br');
      //$mail->addAddress('wellington.rodrigues@goeyewear.com.br');  
      //$mail->addAddress('edenilton.silva@goeyewear.com.br');            // Add a recipient

      //foreach ($request->email as $email) {

      //  $mail->addAddress($email);     // Add a recipient

      //} 

      //$nome_excel = '/var/www/html/portal-gestao/storage/app/order#'.$request->id_pedido.'.xlsx';
      $mail->AddAttachment( $nome_excel1 );
      //$mail->addAddress('fabio@oncore.com.br');               // Name is optional
      //$mail->addReplyTo('info@example.com', 'Information');
      //$mail->addCC('cc@example.com');
      // $mail->addBCC('fabio@oncore.com.br');

      //Content
      $mail->isHTML( true ); // Set email format to HTML
      $mail->Subject = 'Mudança data de entrega - ' . $date = date( 'Y-m-d' );

      $msg = "

                <body>

                <font color='black' face='arial'  size='2px'> <p>
                <h3>Mudança de data semana atual X semana passada </h3>
                Olá <b>Edenilton</b>, <br>
                Favor verificar esses pedidos em anexo que tiveram mudança na data de entrega da semana passada para essa e cobrar a fábrica.<br>
                
                Obrigado,<br>
                GOWEB<br>

                </font>


                <table border='1px' bgcolor='#ffffff' > 
                <tr >
                <td align='center' ><b></b></td> 
                <td align='center' ><b>Grouping</b></td> 
                <td align='center'><b>Col model</b></td>                
                <td align='center'><b>Class model</b></td>
                <td align='center'><b>Count SKU</b></td>
                <td align='center'><b>Sum production</b></td>
                <td align='center'><b>Days delayed</b></td>


                </tr>";
      $linha = 0;
      foreach ( $mudancadataentregassemail as $mudancadataentregassemail1 ) {
        $linha++;

        $msg .= '

                

                	<tr>
                	<td  >' . $linha . '</td> 
                	<td  >' . $mudancadataentregassemail1->agrup . '</td> 
                	<td >' . $mudancadataentregassemail1->colmod . '</td>
                	<td >' . $mudancadataentregassemail1->clasmod . '</td>
                	<td >' . $mudancadataentregassemail1->contagem . '</td>
                	<td >' . $mudancadataentregassemail1->soma . '</td>
                	<td >' . $mudancadataentregassemail1->dias_atrado . '</td>





                	</tr>';

      }
      $msg .= '      

                </table>
                </div>	';


      $mail->Body = $msg;
      //web$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

      $mail->send();


    } catch ( Exception $e ) {
      echo '<br>Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }

  }


  public function faltacadastro() {

    $faltacadastroexcel = \DB::connection( 'go' )->select( "

    				
			select cod_sec, colecao, sum(producao) as sum
			from producoes 
			left join itens on producoes.cod_sec = itens.secundario
			where  
            secundario is null
            and fabrica = 'zhongmin'
            group by cod_sec, colecao
            order by cod_sec asc, colecao desc


    		" );
    //dd($diferencapedidosexcel);


    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();


    $spreadsheet->getActiveSheet()->getColumnDimension( 'A' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'B' )->setWidth( 35 );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'C' )->setWidth( 35 );


    $drawing = new\ PhpOffice\ PhpSpreadsheet\ Worksheet\ Drawing();
    $drawing->setName( 'Paid' );
    $drawing->setDescription( 'Paid' );
    $drawing->setPath( '/var/www/html/portal-gestao/public/img/logogo.png' ); // put your path and image here

    //		$drawing->getShadow()->setDirection(45);
    //$drawing->setWorksheet($spreadsheet->getActiveSheet());

    $spreadsheet->getActiveSheet()->getRowDimension( 1 )->setRowHeight( 100 );

    $spreadsheet->getActiveSheet()->getRowDimension( 4 )->setRowHeight( 25 );
    $spreadsheet->getActiveSheet()->getStyle( 'A1:C1' )->getFont()->setBold( true );
    //$spreadsheet->getActiveSheet()->getStyle('A1:P1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
    $spreadsheet->getActiveSheet()->getStyle( 'A1:C1' )->getAlignment()->setHorizontal( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::HORIZONTAL_CENTER )->setVertical( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::VERTICAL_CENTER );
    $spreadsheet->getActiveSheet()->setAutoFilter( 'A1:C1' );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'A' )->setAutoSize( true );
    $spreadsheet->getActiveSheet()->getColumnDimension( 'C' )->setAutoSize( true );


    $sheet->setCellValue( 'A1', 'Código secundário' );
    $sheet->setCellValue( 'B1', 'Coleção' );
    $sheet->setCellValue( 'C1', 'Produção' );


    $spreadsheet->getActiveSheet()->getStyle( 'A:C' )->getAlignment()->setHorizontal( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::HORIZONTAL_CENTER )->setVertical( \PhpOffice\ PhpSpreadsheet\ Style\ Alignment::VERTICAL_CENTER );

    $linha = 1;

    foreach ( $faltacadastroexcel as $faltacadastroexcel1 ) {

      $linha++;
      $spreadsheet->getActiveSheet()->getRowDimension( $linha )->setRowHeight( 25 );


      $sheet->setCellValue( 'A' . $linha, $faltacadastroexcel1->cod_sec );
      $sheet->setCellValue( 'B' . $linha, $faltacadastroexcel1->colecao );
      $sheet->setCellValue( 'C' . $linha, $faltacadastroexcel1->sum );


    }

    $data = NOW();

    $writer = new Xlsx( $spreadsheet );
    //	$writer->save('hello world.xlsx');		
    //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //header('Content-Disposition: attachment;filename="PURCHASE ORDER #'.$data.'.xlsx"');
    //header('Cache-Control: max-age=0');


    $nome_excel1 = '/var/www/html/portal-gestao/storage/app/relatorio_faltacadastro_' . $data . '.xlsx';
    // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    // header('Content-Disposition: attachment;filename="'.$nome.'"');
    // header('Cache-Control: max-age=0');
    // // If you're serving to IE 9, then the following may be needed
    // header('Cache-Control: max-age=1');

    $writer = new Xlsx( $spreadsheet );
    $writer->save( $nome_excel1 );


    $faltacadastroemail = \DB::select( "

						
				
			select colecao, count(cod_sec) as count, sum(sum) as sum
    		from(		
			select cod_sec, producoes.colecao, sum(producao) as sum
			from producoes 
			left join itens on producoes.cod_sec = itens.secundario
			where  
            secundario is null
            and fabrica = 'zhongmin'
            group by cod_sec, producoes.colecao
            order by cod_sec asc, producoes.colecao desc) as base
            group by colecao
            order by colecao asc


			" );


    $mail = new PHPMailer( true ); // Passing `true` enables exceptions

    try {
  $mail->CharSet = 'UTF-8';
                //Server settings
                $mail->SMTPDebug = 0;                                 // Enable verbose debug output
                $mail->isSMTP();                                      // Set mailer to use SMTP
                //$mail->isMail();                                      // Set mailer to use SMTP
                $mail->Host = 'imap.gmail.com';  // Specify main and backup SMTP servers
                $mail->SMTPAuth = true;                               // Enable SMTP authentication
                $mail->Username = 'goweb@goeyewear.com.br';                 // SMTP username
                $mail->Password = 'd6SHzwSu';                           // SMTP password
                $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 465;                                    // TCP port to connect to

      //Recipients
      $mail->setFrom( 'grifes@goeyewear.com.br', 'Gestão de Grifes GO Eyewear' );

      $mail->addReplyTo( 'grifes@goeyewear.com.br', 'Gestão de Grifes GO Eyewear' );
      //$mail->addAddress('mariana.marao@goeyewear.com.br'); 

      $mail->addAddress( 'grifes@goeyewear.com.br' );
      //$mail->addAddress('wellington.rodrigues@goeyewear.com.br');  
      //$mail->addAddress('edenilton.silva@goeyewear.com.br');            // Add a recipient

      //foreach ($request->email as $email) {

      //  $mail->addAddress($email);     // Add a recipient

      //} 

      //$nome_excel = '/var/www/html/portal-gestao/storage/app/order#'.$request->id_pedido.'.xlsx';
      $mail->AddAttachment( $nome_excel1 );
      //$mail->addAddress('fabio@oncore.com.br');               // Name is optional
      //$mail->addReplyTo('info@example.com', 'Information');
      //$mail->addCC('cc@example.com');
      // $mail->addBCC('fabio@oncore.com.br');

      //Content
      $mail->isHTML( true ); // Set email format to HTML
      $mail->Subject = 'Falta cadastro - ' . $date = date( 'Y-m-d' );

      $msg = "

                <body>

                <font color='black' face='arial'  size='2px'> <p>
                <h3>Sem cadastro no JDE </h3>
                Olá <b>Wellington</b>, <br>
                Favor verificar esses itens em anexo que vieram no CAT dessa semana e nós não temos cadastrado no JDE, enviar as informação para operações cadastrar.<br>
                
                Obrigado,<br>
                GOWEB<br>

                </font>


                <table border='1px' bgcolor='#ffffff' > 
                <tr >
                <td align='center' ><b></b></td> 
                <td align='center' ><b>Col mod</b></td> 
                <td align='center'><b>Contagem SKU</b></td>                
                <td align='center'><b>Soma produção</b></td>


                </tr>";
      $linha = 0;
      foreach ( $faltacadastroemail as $faltacadastroemail1 ) {
        $linha++;

        $msg .= '

                

                	<tr>
                	<td  >' . $linha . '</td> 
                	<td  >' . $faltacadastroemail1->colecao . '</td> 
                	<td >' . $faltacadastroemail1->count . '</td>
                	<td >' . $faltacadastroemail1->sum . '</td>





                	</tr>';

      }
      $msg .= '      

                </table>
                </div>	';


      $mail->Body = $msg;
      //web$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

      $mail->send();


    } catch ( Exception $e ) {
      echo '<br>Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }

  }


  //$this->exportaPedido($request->id_pedido);

  //$request->session()->flash('alert-success', 'Pedido enviado com sucesso!');

  //return redirect('/compras/'.$request->id_pedido);

  //}


}