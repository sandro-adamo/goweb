<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \hpOffice\PhpSpreadsheet\Worksheet;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\DevolucaoMostruario;
use App\DevolucaoItemMostruario;

class MostruarioController extends Controller
{


    public function alteraInventario(Request $request) {

        $update = \DB::select("update inventarios set acao = 'DEVOLVER', motivo = '$request->motivo', obs = '$request->obs' where id = $request->id_linha and id_inventario = '1'");


        return redirect('/mostruarios/inventarios/novo');

    } 

    public function listaInventario() {

      $id_rep = \Auth::user()->id_addressbook;
      $id_usuario = \Auth::id();


      $inventarios = \DB::select("select id_inventario, cast(min(created_at) as date) as dt_inicio, count(item) as item, status  from inventarios where id_rep = $id_rep group by id_inventario, status ");

      return view('mostruarios.inventarios.lista')->with('inventarios', $inventarios);


    }

    

    public function novoInventario() {


      $id_rep = \Auth::user()->id_addressbook;

      $inventarios = \DB::select("select * from inventarios where id_rep = '$id_rep' order by created_at desc ");

      $resumo = array();
      $resumo = \DB::select("
                              select agrup, sum(devolver) as devolver, sum(manter) as manter
                              from (
                              select agrup, 
                                  case when acao = 'DEVOLVER' then 1 else 0 end as devolver ,
                                  case when acao = 'MANTER' then 1 else 0 end as manter 
                              from inventarios 
                              left join itens on id_item = itens.id
                              where id_rep = $id_rep
                              ) as base
                              group by agrup");

      return view('mostruarios.inventarios.novo')->with('itens', $inventarios)->with('resumo', $resumo);


    }

    public function confereInventario(Request $request) {




        // if (\Auth::id() <> 1 and \Auth::id() <> 411) {
        //   die('manutencao');
        // 
        $id_rep = \Auth::user()->id_addressbook;


       
        // $devolucao = DevolucaoMostruario::where('id_usuario', $id_usuario)->where('situacao', '<>', 'Concluída')->first();

        if ($request->referencia) {

           $item = \App\Item::where('secundario', $request->referencia)->first();

           if ($item) {

                 $grifes = Session::get('grifes');

                 // $situacao = \DB::select("

                 //    select statusatual, codgrife, grife, modelo, secundario, codgrife, 
                 //   case when statusatual in ('EM PRODUÇÃO', 'DISPONÍVEL','ENTREGA IMEDIATA','DISPONÍVEL EM 15 DIAS','DISPONÍVEL EM 30 DIAS') and codgrife in $grifes
                 //   then ' MANTER' else 'DEVOLVER' end as Situacao_Peca
                   
                 //   from itens

                 // where secundario = '$item->secundario'");

                 $situacao = \DB::select("

                     select distinct statusatual, codgrife, grife, itens.modelo, itens.secundario, codgrife, statusatual,
                    case 
                    
                    when fornecedor like '%kering%' and (disp_vendas+conf_montado+cet+ifnull(producao,0))-orcvalido >=1
                    AND statusatual in ('EM PRODUÇÃO','ENTREGA IMEDIATA','DISPONÍVEL EM 15 DIAS','DISPONÍVEL EM 30 DIAS')
                    then 'MANTER'
                    when fornecedor like '%kering%' and (disp_vendas+conf_montado+cet+ifnull(producao,0))-orcvalido <1
                    then 'DEVOLVER'
                    WHEN fornecedor like '%kering%'
                    AND colmod >= 2020 
                    then 'MANTER'
                    
                    when statusatual in ('EM PRODUÇÃO','ENTREGA IMEDIATA','DISPONÍVEL EM 15 DIAS','DISPONÍVEL EM 30 DIAS')
                    and fornecedor not like '%kering%'
                    then 'MANTER'
                    when statusatual = 'ESGOTADO' 
                    and colmod > 2019
                    and fornecedor not like '%kering%'
                    then 'MANTER'
                    when statusatual = 'ESGOTADO' 
                    and colmod > 2018
                    and clasmod in ('linha a+', 'linha a++')
                    and fornecedor not like '%kering%'
                    then 'MANTER'
                    
                    when statusatual = 'ESGOTADO' 
                    and colmod < 2020
                    then 'DEVOLVER'
                    
                    
                    else 'DEVOLVER' end as Situacao_Peca
                    
                    from itens
                    left join saldos on saldos.secundario = itens.secundario
                    left join producoes_sint on itens.secundario = producoes_sint.cod_sec
                    left join orcamentos on itens.secundario = orcamentos.secundario

                   where itens.secundario = '$request->referencia'");



                 $verifica_leitura = \DB::select("select * from inventarios where id_rep = $id_rep and item = '$request->referencia' ");

                 if ($verifica_leitura && count($verifica_leitura) >0 && !isset($request->duplica)) {
      
                    $request->session()->flash('alert-warning', 'Este item já foi inserido, deseja inserir novamente?');
                    return redirect('/mostruarios/inventarios/novo?duplicado=1&referencia='.$request->referencia);

                 }

                 if ($situacao) {

                    //$id_lista = $request->id_lista;

                    $situacao_item = $situacao[0]->Situacao_Peca;


                    if (isset($request->devolver) && $request->devolver == 1) {
                       $query = \DB::select("insert into inventarios ( id_rep, id_item, item, situacao, acao, obs) values ($id_rep, $item->id, '$item->secundario', '$situacao_item', 'DEVOLVER', '$request->motivo') ");
                    } else {
                       $query = \DB::select("insert into inventarios ( id_rep, id_item, item, situacao, acao) values ($id_rep, $item->id, '$item->secundario', '$situacao_item', '$situacao_item') ");

                    }

                 }

           } else {

              $request->session()->flash('alert-warning', 'Item não encontrado');
              $situacao = array();

           }


        }


        return redirect('/mostruarios/inventarios/novo')->with('item', $situacao);




    }

   public function reabre() {


      $id_usuario = \Auth::id();
      $query = \DB::select("update devolucoes2 set situacao = 'Iniciada', status = 'Iniciada' where id_usuario = '$id_usuario'");


   }


   public function enviarDevolucao(Request $request) {


      $id_usuario = \Auth::id();
      //$usuario = \Auth::user()->nome;

      $usuario = \App\Usuario::find($id_usuario);


      $devolucao = new \App\Devolucao();
      $devolucao->id_usuario = 999;
      $devolucao->id_cliente = $usuario->id_addressbook;
      $devolucao->tipo = 'Mostruario';
      $devolucao->origem = 'PortalRep';
      $devolucao->situacao = 'Aberta';
      $devolucao->solicitante = $usuario->nome;
      $devolucao->volumes = $request->volumes;
      $devolucao->email = $usuario->usuario;
      $devolucao->save();

      $itens = \DB::select("select * from devolucoes2 where id_usuario = '$id_usuario' and status = 'Iniciada'");

       
      // $resumo = \DB::select("select agrup, count(*) as qtde
      //                          from devolucoes2
      //                          left join itens on id_item = itens.id
      //                          where id_usuario = $id_usuario and acao = 'DEVOLVER' and status = 'Iniciada'
      //                          group by agrup");

      $arquivo = $this->geraListaExcel($id_usuario);

      foreach ($itens as $item) {

         if ($item->id_item <> '') {

              $produto_goweb = \DB::connection('goweb')->select("select * from produtos where ukey = $item->id_item");

              if ($produto_goweb) {

                if ($item->acao == 'DEVOLVER') {
                  $dev_item = new \App\DevolucaoItem();
                  $dev_item->id_devolucao = $devolucao->id;
                  $dev_item->status = 1;
                  $dev_item->id_produto = $produto_goweb[0]->id;
                  $dev_item->produto = $produto_goweb[0]->referencia;
                  $dev_item->qtde = 1;
                  $dev_item->unitario = 0;
                  $dev_item->total = 0;
                  $dev_item->tabela = 'devolucoes2';
                  $dev_item->id_tabela = $item->id;
                  $dev_item->obs = $item->obs;

                  $dev_item->save();
                }

                $query = \DB::select("update devolucoes2 set status = 'Enviada', id_devolucao = $devolucao->id where id = '$item->id'");
              }
         }

      }

      \DB::connection('goweb')->select("insert into historicos (id_usuario, origem, contato, historico,categoria, tabela, id_tabela) values ($id_usuario, 'PortalRep', 'teste', 'Devolução de mostruário enviada.', 'EnvioDevMost', 'devolucoes2', $devolucao->id)");
      // envia email


      // $msg = '<h3>Devolução de Mostruário</h3><hr>
      //          <b>Data: </b> '.date('d/m/Y').' <br>
      //          <b>Situação:  <span style="color:green"> Enviada </span> </b><br>
      //          <b>Status:    <span style="color:green"> Aguardando Nota Fiscal </span> </b><br>
      //          <b>Representante: </b>'.$usuario->nome.'<br>
      //          <b>Volumes: </b> '.$request->volumes.'<br><br>

      //          <b>Resumo da devolução</b>';


      //    $total_resumo = 0;

      //    $msg .= '
      //       <table border="1" width="50%">

      //          <tr>
      //             <th>Agrupamento</th>
      //             <th>Quantidade</th>
      //          </tr>';


      //    foreach ($resumo as $lista) {

      //       $total_resumo += $lista->qtde;

      //       $msg .= '
      //             <tr>
      //                <td>'.$lista->agrup.'</td>
      //                <td align="center">'.$lista->qtde.'</td>
      //             </tr>';

      //    }

      //    $msg .= '
      //             <tr>
      //                <th style="text-align: right;">TOTAL</th>
      //                <th style="text-align: center;">'.$total_resumo.'</th>
      //             </tr>
      //       </table><br>


      //    <p style="color:orange;"><b><i> Aguarde a emissão da nota fiscal e autorização de postagem</i></p></p>';



      // $mail = new PHPMailer(true);                              // Passing `true` enables exceptions

      // try {

      //     $mail->CharSet = 'UTF-8';
      //     //Server settings
      //     $mail->SMTPDebug = 0;                                 // Enable verbose debug output
      //     $mail->isSMTP();                                      // Set mailer to use SMTP
      //     //$mail->isMail();                                      // Set mailer to use SMTP
      //     $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
      //     $mail->SMTPAuth = true;                               // Enable SMTP authentication
      //     $mail->Username = 'goweb@goeyewear.com.br';                 // SMTP username
      //     $mail->Password = 'e9pbKUf4';                           // SMTP password
      //     $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      //     $mail->Port = 587;                                    // TCP port to connect to

      //     //Recipients
      //     $mail->setFrom('goweb@goeyewear.com.br', 'GO Eyewear');
      //     $mail->addReplyTo('goweb@goeyewear.com.br', 'GO Eyewear');
      //     //$mail->addAddress('produtos@goeyewear.com.br');     // Add a recipient

      //     //foreach ($request->email as $email) {
      //         $email_usuario = \Auth::user()->usuario;

      //         $mail->addAddress('fabio@oncore.com.br');     // Add a recipient
      //         //$mail->addAddress('operacoes@goeyewear.com.br');     // Add a recipient
      //         //$mail->addAddress($usuario->usuario);     // Add a recipient

      //     //} 

      //     //$nome_excel = '/var/www/html/portalgo/storage/app/order#'.$request->id_pedido.'.xlsx';
      //     $mail->AddAttachment($arquivo);
      //     //$mail->addAddress('fabio@oncore.com.br');               // Name is optional
      //     //$mail->addReplyTo('info@example.com', 'Information');
      //     //$mail->addCC('cc@example.com');
      //     // $mail->addBCC('fabio@oncore.com.br');

      //     //Content
      //     $mail->isHTML(true);                                  // Set email format to HTML
      //     $mail->Subject = 'Devolução de Mostruário - '.$usuario->nome;
      //     //$msg = nl2br($request->obs);
      //     $mail->Body    = $msg;
      //     //web$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

      //     $mail->send();


      // } catch (Exception $e) {
      //     echo '<br>Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
      // }   



      // // grava no 10




      return redirect('/mostruarios/devolucoes');

   }


   public function reenviaEmail($id_usuario) {

      $usuario = \App\Usuario::find($id_usuario);


      // envia email

      $arquivo = $this->geraListaExcel($id_usuario);

      $msg = '<h3>Devolução de Mostruário</h3><hr>
               <b>Data: </b> '.date('d/m/Y').' <br>
               <b>Situação:  <span style="color:green"> Enviada </span> </b><br>
               <b>Status:    <span style="color:green"> Aguardando Nota Fiscal </span> </b><br>
               <b>Representante: </b>'.$usuario->nome.'<br>
               <b>Volumes: </b> <br><br>

               <b>Resumo da devolução</b>';

         
         $resumo = \DB::select("select agrup, count(*) as qtde
                                 from devolucoes2
                                 left join itens on id_item = itens.id
                                 where id_usuario = $id_usuario and acao = 'DEVOLVER'
                                 group by agrup");
         $total_resumo = 0;

         $msg .= '
            <table border="1" width="50%">

               <tr>
                  <th>Agrupamento</th>
                  <th>Quantidade</th>
               </tr>';


         foreach ($resumo as $lista) {

            $total_resumo += $lista->qtde;

            $msg .= '
                  <tr>
                     <td>'.$lista->agrup.'</td>
                     <td align="center">'.$lista->qtde.'</td>
                  </tr>';

         }

         $msg .= '
                  <tr>
                     <th style="text-align: right;">TOTAL</th>
                     <th style="text-align: center;">'.$total_resumo.'</th>
                  </tr>
            </table><br>


         <p style="color:orange;"><b><i> Aguarde a emissão da nota fiscal e autorização de postagem</i></p></p>';



      $mail = new PHPMailer(true);                              // Passing `true` enables exceptions

      try {

          $mail->CharSet = 'UTF-8';
          //Server settings
          $mail->SMTPDebug = 0;                                 // Enable verbose debug output
          $mail->isSMTP();                                      // Set mailer to use SMTP
          //$mail->isMail();                                      // Set mailer to use SMTP
          $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
          $mail->SMTPAuth = true;                               // Enable SMTP authentication
          $mail->Username = 'goprodutos@gmail.com';                 // SMTP username
          $mail->Password = 'go123456';                           // SMTP password
          $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
          $mail->Port = 587;                                    // TCP port to connect to

          //Recipients
          $mail->setFrom('produtos@goeyewear.com.br', 'GO Eyewear');
          $mail->addReplyTo('produtos@goeyewear.com.br', 'GO Eyewear');
          //$mail->addAddress('produtos@goeyewear.com.br');     // Add a recipient

          //foreach ($request->email as $email) {
              $email_usuario = \Auth::user()->usuario;

              //$mail->addAddress('fabio@oncore.com.br');     // Add a recipient
              $mail->addAddress('operacoes@goeyewear.com.br');     // Add a recipient
              $mail->addAddress($usuario->email);     // Add a recipient

          //} 

          //$nome_excel = '/var/www/html/portalgo/storage/app/order#'.$request->id_pedido.'.xlsx';
          $mail->AddAttachment($arquivo);
          //$mail->addAddress('fabio@oncore.com.br');               // Name is optional
          //$mail->addReplyTo('info@example.com', 'Information');
          //$mail->addCC('cc@example.com');
          // $mail->addBCC('fabio@oncore.com.br');

          //Content
          $mail->isHTML(true);                                  // Set email format to HTML
          $mail->Subject = 'Devolução de Mostruário - '.$usuario->nome;
          //$msg = nl2br($request->obs);
          $mail->Body    = $msg;
          //web$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

          $mail->send();


      } catch (Exception $e) {
          echo '<br>Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
      }   



   }

   public function geraListaExcel($id_usuario) {

      // $id_usuario = \Auth::id();
      // $usuario = \Auth::user()->nome;

      $usuario = \App\Usuario::find($id_usuario);

      $spreadsheet = new Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();

      $linha = 1;


      $sheet->setCellValue('A1', 'Agrupamento');
      $sheet->setCellValue('B1', 'Item');
      $sheet->setCellValue('C1', 'Qtde');


      // if (isset($_GET["id"])) {
      //    $id_usuario = $_GET["id"];
      // }

      $query = \DB::select("select agrup, devolucoes2.secundario, count(*) as qtde
                              from devolucoes2
                              left join itens on id_item = itens.id
                              where id_usuario = $usuario->id  and status = 'Iniciada'
                              group by agrup, devolucoes2.secundario");
      dd($query);

      foreach ($query as $registro) {

         $linha++;

         if ($linha > 1) {
            $sheet->setCellValue('A'.$linha, $registro->agrup);
            $sheet->setCellValue('B'.$linha, $registro->secundario);
            $sheet->setCellValue('C'.$linha, $registro->qtde);
         }

      }

      $writer = new Xlsx($spreadsheet);
   // $writer->save('hello world.xlsx');     
      
//      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//      header('Content-Disposition: attachment;filename="'.$nome.'"');
      
//      header('Cache-Control: max-age=0');

      $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
      //$writer->save('php://output');

      
      $nome_excel = '/var/www/html/portalgo/storage/app/devolucao_'.$usuario->nome.'_'.date('d-m-Y').'.xlsx';
      // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      // header('Content-Disposition: attachment;filename="'.$nome.'"');
      // header('Cache-Control: max-age=0');
      // // If you're serving to IE 9, then the following may be needed
      // header('Cache-Control: max-age=1');

      $writer = new Xlsx($spreadsheet);
      $writer->save($nome_excel);      

      return $nome_excel;


   }


   public function baixaListaExcel($id) {

      $id_usuario = \Auth::id();
      $usuario = \Auth::user()->nome;

      $spreadsheet = new Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();

      $linha = 1;


      $sheet->setCellValue('A1', 'Agrupamento');
      $sheet->setCellValue('B1', 'Item');
      $sheet->setCellValue('C1', 'Qtde');


      if (isset($_GET["id"])) {
         $id_usuario = $_GET["id"];
      }

      $query = \DB::connection('goweb')->select("select agrupamentos.descricao as agrup, produto as secundario, count(*) as qtde
                              from devolucoes2_itens
                              left join produtos on id_produto = produtos.id
                              left join agrupamentos on id_agrupamento = agrupamentos.id
                              where id_devolucao = $id 
                              group by agrupamentos.descricao, produto");

      foreach ($query as $registro) {

         $linha++;

         if ($linha > 1) {
            $sheet->setCellValue('A'.$linha, $registro->agrup);
            $sheet->setCellValue('B'.$linha, $registro->secundario);
            $sheet->setCellValue('C'.$linha, $registro->qtde);
         }

      }

      $writer = new Xlsx($spreadsheet);
      $nome_excel = 'devolucao.xlsx';
   // $writer->save('hello world.xlsx');     
      
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="'.$nome_excel.'"');
      
      header('Cache-Control: max-age=0');

      $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
      //$writer->save('php://output');

      $writer->save('php://output');
  
      //  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      //  header('Content-Disposition: attachment;filename="'.$nome_excel.'"');
      //  header('Cache-Control: max-age=0');
      // // // If you're serving to IE 9, then the following may be needed
      //  header('Cache-Control: max-age=1');

      //$writer = new Xlsx($spreadsheet);
      //$writer->save($nome_excel);      

      //return $nome_excel;


   }



   public function baixaListaExcel2() {

      $id_usuario = \Auth::id();
      $usuario = \Auth::user()->nome;

      $itens = \DB::select("select * from devolucoes2 where id_usuario = '$id_usuario' and status = 'Iniciada' and id_devolucao is null order by created_at desc");

      $spreadsheet = new Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();

      $linha = 1;


      $sheet->setCellValue('A1', 'Agrupamento');
      $sheet->setCellValue('B1', 'Item');
      $sheet->setCellValue('C1', 'Qtde');


      if (isset($_GET["id"])) {
         $id_usuario = $_GET["id"];
      }

      $query = \DB::select("select itens.agrup, itens.secundario, count(*) as qtde
                            from devolucoes2 
                            left join itens on id_item = itens.id
                            where id_usuario = '$id_usuario' and status = 'Iniciada' and id_devolucao is null  and acao = 'DEVOLVER'
                            group by itens.agrup, itens.secundario");
      dd($query);

      // $query = \DB::connection('goweb')->select("select agrupamentos.descricao as agrup, produto as secundario, count(*) as qtde
      //                         from devolucoes2_itens
      //                         left join produtos on id_produto = produtos.id
      //                         left join agrupamentos on id_agrupamento = agrupamentos.id
      //                         where id_devolucao = $id 
      //                         group by agrupamentos.descricao, produto");

      foreach ($query as $registro) {

         $linha++;

         if ($linha > 1) {
            $sheet->setCellValue('A'.$linha, $registro->agrup);
            $sheet->setCellValue('B'.$linha, $registro->secundario);
            $sheet->setCellValue('C'.$linha, $registro->qtde);
         }

      }

      $writer = new Xlsx($spreadsheet);
      $nome_excel = 'devolucao.xlsx';
   // $writer->save('hello world.xlsx');     
      
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="'.$nome_excel.'"');
      
      header('Cache-Control: max-age=0');

      $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
      //$writer->save('php://output');

      $writer->save('php://output');
  
      //  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      //  header('Content-Disposition: attachment;filename="'.$nome_excel.'"');
      //  header('Cache-Control: max-age=0');
      // // // If you're serving to IE 9, then the following may be needed
      //  header('Cache-Control: max-age=1');

      //$writer = new Xlsx($spreadsheet);
      //$writer->save($nome_excel);      

      //return $nome_excel;


   }  

   public function finalizaDevolucao($id) {

      $id_usuario = \Auth::id();


      $query = \DB::select("update devolucoes2 set status_lista = 'Aguardando Conferência' where id_usuario = '$id_usuario' and id_lista = '$id' ");


      return redirect('/mostruarios/devolucoes');


   }

   public function excluirItem($id_item) {

      $id_usuario = \Auth::id();


      //$query = \DB::select("update devolucoes2 set exclui = 1  where id_usuario = '$id_usuario' and id = '$id_item'");
      $query = \DB::select("delete from devolucoes2  where id_usuario = '$id_usuario' and id = '$id_item'");


      return redirect()->back();


   }
 
   public function novaDevolucao() {

      $id_usuario = \Auth::id();

      $itens = \DB::select("select * from devolucoes2 where id_usuario = '$id_usuario' and status = 'Iniciada' order by created_at desc");


      if ($itens) {       
        $resumo = \DB::select("select agrup, count(*) as qtde
                                 from devolucoes2
                                 left join itens on id_item = itens.id
                                 where id_usuario = $id_usuario -- and acao = 'DEVOLVER'
                                 group by agrup");

      } else {
        $resumo = array();
      }
      // if (\Auth::id() <> 1 and \Auth::id() <> 411) {
      //   die('manutencao');
      // }

      // $id_usuario = \Auth::id();

      // $lista = DevolucaoMostruario::where('id_usuario', $id_usuario)->where('status', '<>', 'Concluída')->first();

      // if ($lista) {
      //    return redirect('/mostruarios/devolucoes');
      // } 

      return view('mostruarios.devolucoes.nova')->with('itens', $itens)->with('resumo', $resumo);

   }

   public function gravaNovaDevolucao(Request $request) {

      if (\Auth::id() <> 1 and \Auth::id() <> 411) {
        die('manutencao');
      }
      $devolucao = new DevolucaoMostruario();
      $devolucao->id_usuario = \Auth::id();
      $devolucao->save();

      return redirect('/mostruarios/devolucoes');

   }

   public function listadevolucoes() {

      $usuario = \App\Usuario::find(\Auth::id());

      //$iniciadas = \DB::select("select * from devolucoes2 where id_usuario = '$usuario->id' and status = 'Iniciada'");

      //if ($iniciadas) {
        //return redirect('/mostruarios/devolucoes/nova');
      //}

      //$listas = \DB::select("select * from devolucoes2 where id_usuario = $id_usuario order by id_lista desc");

      $devolucoes2 = DevolucaoMostruario::where('id_cliente', $usuario->id_addressbook)->where('situacao', '<>', 'Cancelada')->get();

      //if ($devolucoes2 && count($devolucoes2) > 0) {
        return view('mostruarios.devolucoes.lista')->with('devolucoes2', $devolucoes2);

     // } else {
        //return redirect('/mostruarios/devolucoes/nova');

      //}


   }

   public function detalhesDevolucao($id) {

    $devolucao = DevolucaoMostruario::find($id);

    $itens = DevolucaoItemMostruario::where('id_devolucao', $id)->get();


    return view('mostruarios.devolucoes.detalhes')->with('devolucao', $devolucao)->with('itens', $itens);

   }

   public function detalhesDevolu2cao() {
      
      if (\Auth::id() <> 1 and \Auth::id() <> 411) {
        // die('manutencao');
      }      

      if (\Auth::user()->id_addressbook == '') {



      }

      $id_usuario = \Auth::id();

      $devolucao = DevolucaoMostruario::where('id_usuario', $id_usuario)->where('status', '<>', 'Concluída')->first();

      if ($devolucao) {

         $grifes = Session::get('grifes');

         $itens = \DB::select("select devolucoes2.*, statusatual, codgrife, grife, modelo, devolucoes2.secundario, codgrife, 
                 case when statusatual in ('EM PRODUÇÃO', 'DISPONÍVEL','ENTREGA IMEDIATA','DISPONÍVEL EM 15 DIAS','DISPONÍVEL EM 30 DIAS') and codgrife in $grifes
                 then ' MANTER' else 'DEVOLVER' end as Situacao_Peca

               from devolucoes2
               left join itens on devolucoes2.id_item = itens.id 

               where id_usuario = $id_usuario -- and devolucoes2.status = 1
               order by id desc");
         dd($grifes);

         
         $resumo = \DB::select("select agrup, count(*) as qtde
                                 from devolucoes2
                                 left join itens on id_item = itens.id
                                 where id_usuario = $id_usuario and acao = 'DEVOLVER'
                                 group by agrup");

         $id = 1;

         return view('mostruarios.devolucoes.detalhes')->with('devolucao', $devolucao)->with('itens', $itens)->with('resumo', $resumo);

     } else {

        return redirect('/mostruarios/devolucoes/nova');

     }


   }

   public function confereDevolucaoItem(Request $request) {


      // if (\Auth::id() <> 1 and \Auth::id() <> 411) {
      //   die('manutencao');
      // }
      $id_usuario = \Auth::id();

      // $devolucao = DevolucaoMostruario::where('id_usuario', $id_usuario)->where('situacao', '<>', 'Concluída')->first();

      if ($request->referencia) {

         $item = \App\Item::where('secundario', $request->referencia)->first();

         if ($item) {

               $grifes = Session::get('grifes');

               $situacao = \DB::select("

                  select statusatual, codgrife, grife, modelo, secundario, codgrife, 
                 case when statusatual in ('EM PRODUÇÃO', 'DISPONÍVEL','ENTREGA IMEDIATA','DISPONÍVEL EM 15 DIAS','DISPONÍVEL EM 30 DIAS') and codgrife in $grifes
                 then ' MANTER' else 'DEVOLVER' end as Situacao_Peca
                 
                 from itens

               where secundario = '$item->secundario'");

               if ($situacao) {

                  $id_lista = $request->id_lista;

                  $situacao_item = $situacao[0]->Situacao_Peca;


                  if (isset($request->devolver) && $request->devolver == 1) {
                     $query = \DB::select("insert into devolucoes2 ( id_usuario, id_item, secundario, situacao, acao, obs) values ($id_usuario, $item->id, '$item->secundario', '$situacao_item', 'DEVOLVER', '$request->motivo') ");
                  } else {
                     $query = \DB::select("insert into devolucoes2 ( id_usuario, id_item, secundario, situacao, acao) values ($id_usuario, $item->id, '$item->secundario', '$situacao_item', '$situacao_item') ");

                  }

               }

         } else {

            $request->session()->flash('alert-warning', 'Item não encontrado');
            $situacao = array();

         }


      }


      return redirect('/mostruarios/devolucoes/nova')->with('item', $situacao);


   }

   public function checaItemDevolver(Request $request) {


      $grifes = Session::get('grifes');
      dd('teste');

      $situacao = \DB::select("

         select statusatual, codgrife, grife, modelo, secundario, codgrife, 
        case when statusatual in ('EM PRODUÇÃO', 'DISPONÍVEL','ENTREGA IMEDIATA','DISPONÍVEL EM 15 DIAS','DISPONÍVEL EM 30 DIAS') and codgrife in $grifes
        then ' MANTER' else 'DEVOLVER' end as Situacao_Peca
        
        from itens

      where secundario = '$request->item'");

      if ($situacao) {

         return response()->json($situacao);
      } else {

         return false;
      }


   }

	public function listaPedidos(Request $request) {

		$sql = '';


		if ($request->filial) {

			$sql .= " and filial = '$request->filial' ";

		}

		if ($request->razao) {

			$sql .= " and descricao_AN8 like '$request->razao%' ";
		}

		if ($request->codigo) {

			$sql .= " and AN8 = '$request->codigo' ";
		}

		if ($request->ped_inicio && $request->ped_fim) {

			$sql .= " and (dt_pedido >= '$request->ped_inicio' and dt_pedido <= '$request->ped_fim')";
		}

		if ($request->num_pedido) {

			$sql .= " and pedido = '$request->num_pedido' ";
		}


		if ($request->ns_inicio && $request->ns_fim) {

			$sql .= " and (dt_nf >= '$request->ns_inicio' and dt_nf <= '$request->ns_fim')";
		}

		if ($request->num_ns) {

			$sql .= " and ns = '$request->num_ns' ";
		}


		if ($request->nf_inicio && $request->nf_fim) {

			$sql .= " and (dt_nf >= '$request->nf_inicio' and dt_nf <= '$request->nf_fim')";
		}

		if ($request->num_nf) {

			$sql .= " and nf = '$request->num_nf' ";
		}


		$pedidos = \DB::select("select id_cliente as razao
-- , filial
, pedido, tipo, dt_emissao nf, dt_nf, 
 sum(qtde) as pecas,
 case 
  when max(ult_status) <= '540' then 'Pedido Emitido' 
     when max(ult_status) > '540' and max(ult_status) < '620'then 'Em separação' 
       when max(ult_status) = '620' then 'Faturado' 
             else ''
     end as 'Status'
 -- ,

from mostruarios
where id is not null 
$sql
group by id_cliente
-- , filial
, pedido, tipo, dt_emissao, nf, dt_nf
limit 10");

		return view('mostruarios.lista')->with('pedidos', $pedidos);

	}
}
