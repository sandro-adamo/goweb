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
	
	
	
	
	public function atualizarastreio(Request $request){
		
		


      $data = date('d-m-yy-H:i:s');
      
      $arquivo2 = "rastreio-".$data.".xlsx";

      $uploaddir = '/var/www/html/portal-gestao/storage/uploads/';
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
              $pedido = $linha[0];
				$tipo = $linha[1];
				$rastreio = $linha[2];
				$transportadora = $linha[3];
				
				 $listarastreio = \DB::select("INSERT INTO `mostruarios_rastreios`(`pedido`, `tipo`, `rastreio`, `transportadora`) VALUES ('$pedido','$tipo','$rastreio','$transportadora')");
              
  
            }
            $i++;
          } 
	   }
	  }
		return redirect()->back();
		
	}
	public function listaRastreio() {
	
		 $listarastreio = \DB::select("select atualizado, base.pedido, base.tipo, base.id_rep, base.qtd,
case when rastreio_manual is not null then rastreio_manual else base.rastreio end as 'rastreio',
case when transportadora_manual is not null then transportadora_manual else base.transportadora end as 'transportadora',
(select concat(nome,' - ',razao) as nome from addressbook where id = base.id_rep ) as nome
		 from (select atualizado, pedido, tipo, id_cliente as id_rep, rastreio, transportadora, count(id) as qtd,  
         (select rastreio from mostruarios_rastreios mr where mr.pedido = mo.pedido and mr.tipo = mo.tipo order by id desc limit 1) as rastreio_manual ,
         (select transportadora from  mostruarios_rastreios mr where mr.pedido = mo.pedido and mr.tipo = mo.tipo order by id desc limit 1) as transportadora_manual 
		 from mostruarios mo
		 where date(created_at) >= date_sub(current_date(), interval 30 day) 
		 and id_cliente not in ('1','10')
		 group by atualizado, pedido, tipo, id_cliente , rastreio, transportadora) as base
  ");
		
		
		
		 return view('mostruarios.rastreios')->with('listarastreio', $listarastreio);
		
	}
  public function liberaAtualizacaopainel(Request $request) {

    \DB::select("INSERT INTO `mostruario_autoriza`( `id_rep`, `dt_liberado`, `status`) VALUES ('$request->rep2',current_timestamp,'liberado')");
	$email = \DB::select("select email1, razao from addressbook where id = '$request->rep2'");
	$email_correto = strtolower ($email[0]->email1);
	
	 
	 $msg = 'Aos cuidados empresa de representação <b>'.$email[0]->razao.'</b>, <br><br>
	   	Foi liberado no Portal GOWEB o módulo de atualização de mostruários, favor entrar no link abaixo e solicitar as peças que a empresa deseja receber para compor o seu mostruário.<br><br>
                <b>Link:</b> http://painel.goeyewear.com.br <br><br>
				<b>Menu</b> <br>
				
            Mostruários <br>
            Atualizações (ícone em verde) <br>
			Obrigado,<br><br>
			Equipe de Produtos';

       
            $mail = new PHPMailer(true);                              // Passing `true` enables exceptions

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
                $mail->setFrom('goweb@goeyewear.com.br', 'Operações GO Eyewear');
                
                $mail->addReplyTo('mesa.operacoes3@goeyewear.com.br', 'Erika Souza');
                
                $mail->addCC('operacoes@goeyewear.com.br'); 
                $mail->addAddress($email_correto); 
                //$mail->addAddress('ivan@goeyewear.com.br'); 
                //$mail->addAddress('grifes@goeyewear.com.br');

                

                
               //$mail->AddAttachment($salvar);
                //$mail->addAddress('fabio@oncore.com.br');               // Name is optional
                //$mail->addReplyTo('info@example.com', 'Information');
                //$mail->addCC('cc@example.com');
                // $mail->addBCC('fabio@oncore.com.br');

                //Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = 'Liberação de atualização de mostruário';
                
                $mail->Body    = $msg;
                //web$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                $mail->send();


            } catch (Exception $e) {
              echo '<br>Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
            } 
	
  return redirect('/mostruarios/atualizacao/liberaAtulizacaolista');
  }

  

  public function solicitacaoREP($pedido) {
      
      $detahepedido = \DB::select("select agrup, item, colmod
                from mostruario_solicitacao
                left join itens on secundario = item
                where $pedido
                group by agrup, item, colmod
                
                order by item asc");
     

      return view('mostruarios.pedidos.solicitacaoREP')->with('detahepedido', $detahepedido);
    }
    public function pedidosSM($pedido) {
      
      $detahepedido = \DB::select("select agrup, item, colmod
                from mostruarios
                left join itens on secundario = item
                where mostruarios.pc_cliente = ''
                and mostruarios.tipo = 'sm'
                and $pedido
                group by agrup, item, colmod
                
                order by item asc");
     

      return view('mostruarios.pedidos.pedidoSM')->with('detahepedido', $detahepedido);
    }

  public function pedidosAtendidos(Request $request) {
    

    \DB::select("update mostruario_solicitacao set dt_enviado = current_timestamp, status = 'enviado' where id_rep = $request->id_rep and status = 'aberto'");
    return redirect('/mostruarios/atualizacao/aberto');
  }
  


    public function pedidosSolicitados(Request $request) {
                  
      $reps =  \DB::select("select ms.filial, nome,razao, date(ms.created_at) as dt_solicitado, id_rep, count(*) as qtd
from mostruario_solicitacao ms
left join addressbook ad on ad.id = id_rep
where status = 'aberto'
group by filial, nome,ms.created_at,razao, id_rep");
      return view('mostruarios.pedidos.aberto')->with('reps', $reps);
    
    }

    public function liberaAtulizacaolista() {
    $reps = \DB::select("select distinct razao, nome, an8 as rep,  sum(ah) ah,sum(at) at,sum(bg) bg,sum(ev) ev,sum(hi) hi,sum(jo) jo,sum(ng) ng,sum(pu) pu,sum(sp) sp,sum(tc) tc,sum(gu) gu,sum(mm) mm,sum(st) st
from(
select razao, nome,an8,   
case when grife = 'AH' then '1' else ''end as 'AH',
case when grife = 'AT' then '1' else '' end as 'AT',
case when grife = 'BG' then '1' else '' end as 'BG',
case when grife = 'EV' then '1' else '' end as 'EV',
case when grife = 'HI'then '1' else '' end as 'HI',
case when grife = 'JO' then '1' else '' end as 'JO',
case when grife = 'NG' then '1' else '' end as 'NG',
case when grife = 'PU' then '1' else '' end as 'PU',
case when grife = 'SP' then '1' else '' end as 'SP',
case when grife = 'TC' then '1' else '' end as 'TC',
case when grife = 'GU' then '1' else '' end as 'GU',
case when grife = 'MM' then '1' else '' end as 'MM',
case when grife = 'st' then '1' else '' end as 'st'
from repXgrife
left join addressbook ad on ad.id = an8
where sit_representante <> 'vo'
group by razao, nome, grife, an8) as base
group by razao, nome, an8
 ");
    return view('mostruarios.pedidos.autoriza')->with('reps', $reps);
   
    }

    public function confirmaPedidomostruario(Request $request) {
      $itens = $request->item;

      foreach ($itens as $item) {
        
        \DB::select("INSERT INTO `mostruario_solicitacao`( `item`, `id_rep`, `filial`,`status`) VALUES ('$item', '$request->rep','$request->filial','aberto')");
      //mostruario_solicitacao
      }
       \DB::select("UPDATE `mostruario_autoriza` SET `status`='realizado' WHERE id_rep = '$request->rep' and status = 'liberado' ");


        $spreadsheet = new Spreadsheet();


      // aba 1
      $spreadsheet->createSheet(); 
      $sheet = $spreadsheet->getActiveSheet(0); 
           	$resumo2 = \DB::select("Select count(item) as tt from mostruario_solicitacao where id_rep = '$request->rep' and  filial = '$request->filial' and status = 'aberto'");

            $resumo = \DB::select("Select *from mostruario_solicitacao where id_rep = '$request->rep' and  filial = '$request->filial' and status = 'aberto'");

      $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(true);
      $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(true);
      $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(true);
      $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(true);
      $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('E')->setAutoSize(true);
      $spreadsheet->getActiveSheet()->getStyle('A1:d1')->getFont()->setBold(true);


       $linha = 4;

      $spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Cod rep');
      $spreadsheet->setActiveSheetIndex(0)->setCellValue('B1', $request->rep);
      $spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', 'Filial');
      $spreadsheet->setActiveSheetIndex(0)->setCellValue('B2', $request->filial);


      $spreadsheet->setActiveSheetIndex(0)->setCellValue('A4', 'Item');
      $spreadsheet->setActiveSheetIndex(0)->setCellValue('B4', 'Local');
      $spreadsheet->setActiveSheetIndex(0)->setCellValue('C4', 'Cadastro filial');
     
     
      


      foreach ($resumo as $resumo1) {

        $saldo = \DB::select("

          Select*
          from(
              select case when disponivel>0 and local = 'log_nacional' then 'log_nacional'
                when disponivel>0 and local = 'log_importado' then 'log_importado'
                else '' end as 'local'
                from saldos_analitico
                where  item = '$resumo1->item'
                and local in ('LOG_NACIONAL','LOG_IMPORTADO')
                order by  local desc limit 1) as local
                where local <> ''
                ");
		  //dd($saldo[0]->local);
        $filial = \DB::select("select *from cadastrofilial where item = '$resumo1->item' and filial = 'resumo1->filial' ");

        

         $linha++;

         if ($linha > 0) {
             $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$linha,$resumo1->item );
			 if(isset($saldo[0]->local)){
             $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$linha,$saldo[0]->local );
			 }
            
             if(count($filial)>0){
             $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$linha,'ok' );
             }
             else{
               $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$linha,'Cadastrar na filial');
             }
            
           }
          }

         

      $spreadsheet->getActiveSheet(0)->setTitle('Pedido');
      $spreadsheet->setActiveSheetIndex(0);

      
      $nome_excel = 'Pedido rep'.$request->rep.'_'.$request->filial.'_'.date("Y-m-d H:i:s").'.xlsx';
      //$nome_excel = 'Pedido rep.xlsx';
      // salvar na rede
      
      $salvar = '/var/www/html/portal-gestao/storage/uploads/pedidos/'.$nome_excel;
      $writer = new Xlsx($spreadsheet);
      $writer->save($salvar);




       $msg = 'Erika, <br>
	   	favor fazer a atualização de mostruário e enviar o número do pedido para o Fabio Junio através do e-mail 							        admlogistica5@kenerson.com.br.<br><br>
                Pedido de mostruário .<br>
            <b>Id rep: </b> '.$request->rep.' <br>
            <b>Filial:</b>'.$request->filial.'   <br>
            <b>Data: </b> '.date('d/m/Y').' <br>
			<b>Total: </b> '.$resumo2[0]->tt.' <br>';

       
            $mail = new PHPMailer(true);                              // Passing `true` enables exceptions

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
                $mail->setFrom('goweb@goeyewear.com.br', 'Gestão de Grifes GO Eyewear');
                
                $mail->addReplyTo('mesa.operacoes3@goeyewear.com.br', 'Erika Souza');
                
                $mail->addAddress('operacoes@goeyewear.com.br'); 
                //$mail->addAddress('mostruario@goeyewear.com.br'); 
                //$mail->addAddress('mesa.operacoes4@goeyewear.com.br'); 
				        //$mail->addAddress('mesa.operacoes3@goeyewear.com.br'); 
                

                

                
               $mail->AddAttachment($salvar);
                //$mail->addAddress('fabio@oncore.com.br');               // Name is optional
                //$mail->addReplyTo('info@example.com', 'Information');
                //$mail->addCC('cc@example.com');
                // $mail->addBCC('fabio@oncore.com.br');

                //Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = 'Atualização de mostruário Id rep '.$request->rep.' Filial '.$request->filial;
                
                $mail->Body    = $msg;
                //web$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                $mail->send();


            } catch (Exception $e) {
              echo '<br>Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
            } 


     
       return redirect('/mostruarios');
    }
    public function listaPedidomostruario() {

    $id_usuario = \Auth::id();
    $id_rep = \Auth::user()->id_addressbook;
   
      $nome = \Auth::user()->nome;
      $usuario = \Auth::user()->usuario;

      $pedidos = \DB::select("select*, tamolho, tamhaste,tamponte ,(select case when sum(qtde)>0 then sum(qtde) else 0 end as qtd  from malas where malas.id_item = base.num_curto and malas.id_rep = base.rep  and local = 'mala') as Mala
from(
select Rep, filial Filial, Num_Curto, Marca, Agrupamento, Modelo, Cod_Secundario, Valor, Col_Item, Class_Modelo, Disponivel_Real, Estoque_Total,
(select acao from inventarios where id_rep = base2.rep and id_item = base2.num_curto and exclui = 0 and created_at >  now()-interval 4 month  limit 1) as inventario,
(select razao from carteira left join addressbook on addressbook.id = coddir where carteira.rep = base2.rep limit 1) as diretor, statusatual, disponivel_analitico

from(
select repXgrife.an8 Rep, repXgrife.grife Grife, Num_Curto, Marca, Agrupamento, Modelo, Cod_Secundario, Valor, Col_Item, Class_Modelo,
ifnull((select id_item from go.malas where id_rep = repXgrife.an8 and id_item = num_curto group by id_item limit 1),'Enviar') Inventario,
Disponivel_Real, Estoque_Total, statusatual, disponivel_analitico

from(
select itens.id Num_Curto, itens.codgrife, itens.grife Marca, itens.agrup Agrupamento, itens.modelo Modelo, itens.secundario Cod_Secundario, valortabela Valor, itens.colitem Col_Item, itens.clasmod Class_Modelo, 
case when (disponivel_analitico-ifnull(orcamentos.orcvalido,0))<=0 then 0 else (disponivel_analitico-ifnull(orcamentos.orcvalido,0)) end as Disponivel_Real, 
(disponivel_analitico+ saldos.conf_montado+ saldos.em_beneficiamento+ saldos.saldo_parte+ saldos.cet+ ifnull(producoes_sint.estoque,0)+ ifnull(producoes_sint.producao,0)) as Estoque_Total, statusatual,
(select sum(disponivel) as disponivel_analitico from saldos_analitico where id_item = itens.id  and local in ('LOG_NACIONAL','LOG_IMPORTADO')) as disponivel_analitico

from go.itens 
left join (
select sum(disponivel) disponivel_analitico, id_item from go.saldos_analitico where  local in ('LOG_NACIONAL','LOG_IMPORTADO') group by id_item) as analitico on analitico.id_item = itens.id
left join saldos on curto = itens.id

left join go.orcamentos on itens.id = orcamentos.curto
left join 
(select sum(estoque) estoque, sum(producao) producao, cod_sec
from go.producoes_sint
group by cod_sec) as producoes_sint  on itens.secundario = producoes_sint.cod_sec

where
itens.codtipoarmaz not like 'O' and itens.codtipoarmaz not like '0'  and itens.codtipoarmaz not like 'i' 
and codtipoitem = '006' and
itens.agrup not like 'ff0%' 
and itens.agrup not like 'gf0%' 
and itens.agrup not like 'jr0%' 
and itens.agrup not like 'mb0%' 
and itens.agrup not like 'mo0%' 
and itens.agrup not like 'ms0%' 
and itens.agrup not like 'or0%' 
and itens.agrup not like 'pp0%' 
and itens.agrup not like 'sy0%' 
and itens.agrup not like 'pu0%'
and statusatual not in ('esgotado','em producao')
and itens.colitem <= date_format(curdate(),'%Y %m')

and anomod >= 2012

and colmod <= '2021 06'
and itens.secundario not like '%kn95%'

order by itens.agrup, itens.modelo, itens.secundario asc
) as base

left join go.repXgrife on base.codgrife = repXgrife.grife

where 
((codgrife in ('gu','mm','st') and Disponivel_Real >= 8) or (codgrife not in ('gu','mm','st','bg','sp','jo','tc','ev') and Disponivel_Real >= 35 and Estoque_Total >70) or (codgrife in ('bg','sp','jo','tc','ev') and Disponivel_Real >= 30 and Estoque_Total >50))



and repXgrife.an8 = $id_rep
) as base2

left join (select id, filial from go.addressbook where tipo = 'RE' and grupo = 'REPRESENTANTES' and sit_representante not in ('VO','VG')) as adrsbk on base2.Rep = adrsbk.id

where base2.Inventario = 'Enviar'
and adrsbk.id is not null

order by Rep, Agrupamento, Modelo, Cod_Secundario asc)
as base 
left join itens on itens.id = base.num_curto
where (inventario is null or inventario = 'devolver')
and rep = $id_rep
and disponivel_analitico >0
and disponivel_analitico is not null
and (select case when sum(qtde)>0 then sum(qtde) else 0 end as qtd  from malas where malas.id_item = base.num_curto and malas.id_rep = base.rep and local  in ('mala', 'solicitado'))=0
order by Agrupamento, Cod_Secundario asc");
      return view('mostruarios.pedidos.sugestao')->with('pedidos', $pedidos);
     
    }


    public function processaAnalise() {
		 ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);

     $reps = \DB::select("select id_rep from inventarios 
	 where exclui = 0
					and acao = 'manter'
					and status = 'finalizado'
					group by id_rep");
		foreach ($reps as $rep) {

        $inventarios = \DB::select("select*
					from inventarios
					where exclui = 0
					and acao = 'manter'
					and status = 'finalizado'
					and id_rep = $rep->id_rep
					
                                   
                                    ");
		



       


       

        foreach ($inventarios as $item) {
                   

           

          $nf_origem = \DB::select("select   
                          base2.id_item, 
                          base2.id_rep, 
                          base2.item,
                          base2.valor,  
                          '1' as qtde, 
                          ifnull(base2.pre_nf,0) pre_nf, 
                          ifnull(serie_nf,0)serie_nf, 
                          ifnull(faixa_prox_num,0) faixa_prox_num, 
                          ifnull(tipo_nf,0) tipo_nf, 
                          ifnull(pedido,0) pedido, 
                          ifnull(tipo_pedido,0) tipo_pedido, 
                          ifnull(base2.num_linha,0)num_linha, 
                          case when base2.pre_nf is null then 'Sem NF origem' else 'Com NF origem' end as 'NF_encontrada',
                          base2.local_origem,
                          case when base2.local_origem like '%imp%' then 'DEVOLUCAO_IMP'
                          else 'DEVOLUCAO_NAC' end as 'local_destino'
                     from(                          
                 select base.id_item,  base.id_rep, base.item, base.valor,  ifnull(base.pre_nf,0) pre_nf, ifnull(serie_nf,0)serie_nf, ifnull(faixa_prox_num,0) faixa_prox_num, ifnull(tipo_nf,0) tipo_nf, 
                  ifnull(mostruarios_analise.pedido,0) pedido,  ifnull(tipo_pedido,0) tipo_pedido, ifnull(base.num_linha,0)num_linha, base.local_origem    from (
                        select id_cliente as id_rep, id_item, item, max(pre_nf) as pre_nf, num_linha, valor, local_origem 
                        from mostruarios_analise 
                        where 
                          tipo = 'sm' 
                          and mostruarios_analise.id_item = $item->id_item
                          and prox_status = 999 and ult_status = 620  
                          and mostruarios_analise.id_cliente = '$item->id_rep'
                          and codigo_update is null 
                          and qtde >= (utilizado + 1)
						  and dt_emissao > '2020-04-01'
                          
                        group by id_rep, id_item, item, num_linha, valor, local_origem
                      ) as base
                      left join mostruarios_analise on tipo = 'sm' and mostruarios_analise.id_item = base.id_item and prox_status = 999 and ult_status = 620 and base.pre_nf = mostruarios_analise.pre_nf and base.num_linha = mostruarios_analise.num_linha
                      left join sm_utilizado on mostruarios_analise.pedido = sm_utilizado.pedido and mostruarios_analise.tipo = sm_utilizado.tipo and mostruarios_analise.id_item = sm_utilizado.id_item 
                      and mostruarios_analise.linha = sm_utilizado.linha
                      where (vl is null or vl = '')) as base2");
            


          if ($nf_origem && count($nf_origem) > 0) {

            $dados = $nf_origem[0];


            $atualiza = \DB::select("UPDATE mostruarios_analise SET utilizado = utilizado + 1 where pedido = $dados->pedido and item = '$item->item' and num_linha = $dados->num_linha");
            

           \DB::select("INSERT INTO update_mostruarios_analise( id_rep, id_item, item, qtde,valor, pre_nf, serie_nf, faixa_prox_num, tipo_nf, pedido, tipo_pedido, num_linha, nf_encontrada,local_origem,local_destino, id_devolucao, status) VALUES('$item->id_rep','$item->id_item', '$item->item', '$dados->qtde', '$dados->valor', '$dados->pre_nf', '$dados->serie_nf', '$dados->faixa_prox_num', '$dados->tipo_nf', '$dados->pedido', '$dados->tipo_pedido', '$dados->num_linha', '$dados->NF_encontrada', '$dados->local_origem', '$dados->local_destino', '$item->id_inventario','Prosseguir')");
              

          } else {
    
            \DB::select("INSERT INTO update_mostruarios_analise( id_rep, id_item, item, qtde, nf_encontrada,local_origem,local_destino, id_devolucao, status) VALUES('$item->id_rep','$item->id_item', '$item->item', '1',  'Sem NF origem', '', '', '$item->id_inventario','Apenas SM')");
          
          }

        }
        

        $checa = \DB::select("select * from update_mostruarios_analise where id_devolucao = $item->id_inventario and status = 'Aguardar'");

        if ($checa && count($checa) > 0) {

          $update = \DB::select("update update_mostruarios_analise set status = 'Aguardar' where id_devolucao = $item->id_inventario");

         
		}
		}
        


        

               
            


    

    }
	
	
	public function iniciaAtualizacao(Request $request) {
      

      $id_usuario = \Auth::id();
      $id_rep = \Auth::user()->id_addressbook;
      $nome = \Auth::user()->nome;
      $usuario = \Auth::user()->usuario;


      $itens = \DB::select("select * from inventarios where  id_inventario = '$request->id_inventario'  and acao = 'MANTER' and exclui = 0 and tipo = 'inventario'");
     



      $devolucao = \App\Devolucao::novaDevolucao($id_usuario, $itens[0]->id_rep, 'Atualizacao', 'PortalRep', 'Aberta', $nome,'0', $id_usuario);

      $itens = \DB::select("select * from inventarios where  id_inventario = '$request->id_inventario' and acao = 'MANTER' and exclui = 0 and tipo = 'inventario'");

      foreach ($itens as $item) {
        $novo_item = \App\DevolucaoItem::novaDevolucaoItem($devolucao, 1, $item->id_item, $item->item, 1, 0, 0, 'atualizacao', $item->id, $item->obs);
        $atualiza = \DB::select("update inventarios set id_devolucao = $devolucao where id = $item->id");

        $atualiza_NF = \DB::select("update atualiza_nf set id_atualizacao = $devolucao, status_atualizacao = 'Aberta' where id_inventario = $request->id_inventario");
      }
      $direcao = 'http://goweb.goeyewear.com.br/devolucoes/'.$devolucao;
      return redirect($direcao);
    }
    public function listaAtualizacao() {
  

      $atualizanf = \DB::select("select id_inventario, id_rep, razao, nome, id_atualizacao, status_atualizacao, updated_at from atualiza_nf  left join addressbook ad on ad.id = atualiza_nf.id_rep 
         ");

      return view('mostruarios.atualizacao.lista')->with('atualizanf', $atualizanf);
    } 

    public function geraAtualizacao() {
  
      
      $atualizanf = \DB::select("select id_inventario, id_rep, razao, nome, id_atualizacao, status_atualizacao, updated_at from atualiza_nf  left join addressbook ad on ad.id = atualiza_nf.id_rep 
         ");

      return view('mostruarios.atualizacao.lista')->with('atualizanf', $atualizanf);
    } 


    public function exportaInventario($id) {

      $id_usuario = \Auth::id();

      $spreadsheet = new Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();

      $linha = 1;


      $sheet->setCellValue('A1', 'Agrupamento');
      $sheet->setCellValue('B1', 'Item');
      $sheet->setCellValue('C1', 'Qtde');
      $sheet->setCellValue('D1', 'Acao');


      $query = \DB::select("select agrup, itens.secundario, acao, count(*) as qtde
                              from inventarios
                              left join itens on id_item = itens.id
                              where id_inventario = $id and exclui = 0
                             
                              group by agrup, itens.secundario, acao");

      foreach ($query as $registro) {

         $linha++;

         if ($linha > 1) {
            $sheet->setCellValue('A'.$linha, $registro->agrup);
            $sheet->setCellValue('B'.$linha, $registro->secundario);
            $sheet->setCellValue('C'.$linha, $registro->qtde);
            $sheet->setCellValue('D'.$linha, $registro->acao);
         }

      }

      $writer = new Xlsx($spreadsheet);
    // $writer->save('hello world.xlsx');     
      
      $nome = 'inv_'.$id.'.xlsx';
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="'.$nome.'"');
      
      header('Cache-Control: max-age=0');

      $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
      $writer->save('php://output');


    }



    public function exportaDevolucao($id) {

      $id_usuario = \Auth::id();

      $spreadsheet = new Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();

      $linha = 1;


      $sheet->setCellValue('A1', 'Agrupamento');
      $sheet->setCellValue('B1', 'Item');
      $sheet->setCellValue('C1', 'Qtde');


      $query = \DB::select("select agrup, itens.secundario, count(*) as qtde
                              from inventarios
                              left join itens on id_item = itens.id
                              where id_inventario = $id and exclui = 0 and acao = 'DEVOLVER'
                              group by agrup, itens.secundario");

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
      
      $nome = 'dev_'.$id.'.xlsx';
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="'.$nome.'"');
      
      header('Cache-Control: max-age=0');

      $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
      $writer->save('php://output');


    }

    public function enviaInventario(Request $request) {

      $id_usuario = \Auth::id();
      $id_rep = \Auth::user()->id_addressbook;
      $nome = \Auth::user()->nome;
      $usuario = \Auth::user()->usuario;
      

        $inventario = \DB::select("Select* from inventarios where id_inventario = '$request->id_inventario' and exclui = 0 and status <> 'Finalizado' limit 1");

        
        $acao_movimentacao = '';
        if($inventario[0]->tipo=='Recebendo'){
          $acao_movimentacao = 'and id_destino = '.$inventario[0]->id_rep;
        }
        elseif ($inventario[0]->tipo=='Enviando') {
     
        $acao_movimentacao = 'and id_origem = '.$inventario[0]->id_rep;

        }


        

      $movimentacao = \DB::select("select id_movimentacao, tipo, codgrife from movimentacoes_most where status in ('Em processo','Iniciado')  
        $acao_movimentacao  
        group by id_movimentacao, tipo, codgrife ");
      

      $tipodevolucao = '';
      if($inventario[0]->tipo=='Enviando' && $movimentacao[0]->tipo = 'troca'){
          $tipodevolucao = 'Troca_enviando';
          $acao = 'devolucao';
      }
      elseif ($inventario[0]->tipo=='inventario') {
        $tipodevolucao = 'Mostruario';
        $acao = 'devolucao';
      }

      elseif ($inventario[0]->tipo=='Recebendo') {
        $tipodevolucao = 'Troca_recebendo';
        $acao = 'devolucao';
      }
      

      
     
      if($tipodevolucao =='Mostruario'){

        $atualiza = \DB::select("update inventarios set status = 'Finalizado', volumes = $request->volumes where id_inventario = '$request->id_inventario' and id_rep = $id_rep ");

      
      $itens = \DB::select("select * from inventarios where  id_inventario = '$request->id_inventario' and id_rep = $id_rep and acao = 'DEVOLVER' and exclui = 0 and tipo = 'inventario'");

      

        if(count($itens)>0){

      $devolucao = \App\Devolucao::novaDevolucao($id_usuario, $id_rep, 'Mostruario', 'PortalRep', 'Aberta', $nome, $request->volumes, $id_usuario);
    
              


      foreach ($itens as $item) {
        $novo_item = \App\DevolucaoItem::novaDevolucaoItem($devolucao, 1, $item->id_item, $item->item, 1, 0, 0, 'inventarios', $item->id, $item->obs);
        $atualiza = \DB::select("update inventarios set id_devolucao = $devolucao where id = $item->id");
        
      }
      }
      if(isset($devolucao)){
        $dev = $devolucao;
        $status = 'Aberta';      }
        else {
          $dev = '0';
          $status = 'troca mala';
        }
        
      $statusinventario = \DB::select("INSERT INTO `atualiza_nf`(`id_rep`, `id_inventario`, `id_devolucao`, `status_devolucao`) value ('$id_rep','$request->id_inventario','$dev','$status')  ");
    }



    if($tipodevolucao=='Troca_recebendo'){
       
      $atualiza = \DB::select("update inventarios set status = 'Finalizado', volumes = $request->volumes where id_inventario = '$request->id_inventario' and id_rep = $id_rep ");

      $itens = \DB::select("select * from inventarios where  id_inventario = '$request->id_inventario' and id_rep = $id_rep and exclui = 0 and tipo = 'Recebendo'");


        if(count($itens)>0){

      $devolucao = \App\Devolucao::novaDevolucao($id_usuario, $id_rep, 'Mostruario troca recebendo', 'PortalRep', 'Aberta', $nome, $request->volumes, $id_usuario);
    
              


      foreach ($itens as $item) {
        $novo_item = \App\DevolucaoItem::novaDevolucaoItem($devolucao, 1, $item->id_item, $item->item, 1, 0, 0, 'inventarios', $item->id, $item->obs);
        $atualiza = \DB::select("update inventarios set id_devolucao = $devolucao where id = $item->id");
        
      }
      }
     
        $atualiza = \DB::select("update inventarios set acao = 'MANTER' where id_inventario = '$request->id_inventario' and acao = 'devolver'");
        
      
    }


    if($tipodevolucao=='Troca_enviando'){

       
        $atualiza = \DB::select("update inventarios set status = 'Finalizado', volumes = $request->volumes where id_inventario = '$request->id_inventario' and id_rep = $id_rep ");

      $destino = \DB::select("select acao from inventarios where id_inventario = '$request->id_inventario' and exclui = 0 group by acao");
    
      foreach($destino as $destinos)
      if($destinos->acao=='DEVOLVER'){
      $itens = \DB::select("select * from inventarios where  id_inventario = '$request->id_inventario' and id_rep = $id_rep and exclui = 0 and acao = 'devolver' and tipo = 'Enviando'");
      


        if(count($itens)>0){

      $devolucao = \App\Devolucao::novaDevolucao($id_usuario, $id_rep, 'Mostruario troca enviando devolver', 'PortalRep', 'Aberta', $nome, $request->volumes, $id_usuario);
    
              


      foreach ($itens as $item) {
        $novo_item = \App\DevolucaoItem::novaDevolucaoItem($devolucao, 1, $item->id_item, $item->item, 1, 0, 0, 'inventarios', $item->id, $item->obs);
        $atualiza = \DB::select("update inventarios set id_devolucao = $devolucao where id = $item->id");
        
      }
      }
    }

     if($destinos->acao=='MANTER'){
      $itens = \DB::select("select * from inventarios where  id_inventario = '$request->id_inventario' and id_rep = $id_rep and exclui = 0 and acao = 'manter' and tipo = 'Enviando'");
      


        if(count($itens)>0){

      $devolucao = \App\Devolucao::novaDevolucao($id_usuario, $id_rep, 'Mostruario troca enviando manter', 'PortalRep', 'Aberta', $nome, $request->volumes, $id_usuario);
    
              


      foreach ($itens as $item) {
        $novo_item = \App\DevolucaoItem::novaDevolucaoItem($devolucao, 1, $item->id_item, $item->item, 1, 0, 0, 'inventarios', $item->id, $item->obs);
        $atualiza = \DB::select("update inventarios set id_devolucao = $devolucao where id = $item->id");
        
      }
      }
    }
      
      
      
    }

      return redirect('/mostruarios');
    }

    public function excluiItemInvetario($id, $acao) {

      $lista = \DB::select("select * from inventarios where id = $id");

      $inventario = \DB::select("update inventarios set exclui = 1 where id = $id");


      return redirect('/mostruarios/inventarios/detalhes/'.$acao.'/'.$lista[0]->id_inventario);

    }

    public function consultaSituacao(Request $request) {

      $inventario = \DB::select("select * from inventarios where exclui = 0 and id = $request->id and tipo = 'inventario' ");

      return response()->json($inventario);

    }


    public function alteraInventario($id, $id_inventario,$acao ='') {
	
     
        $item = \DB::select("select item, id from inventarios where id = $id");

      


        return view('mostruarios.inventarios.altera')->with('item', $item)->with('acao', $acao);

    } 
     public function alteracaoInventario(Request $request) {
      	
		
		 $acao =$request->acao;
        $item = \DB::select("select* from inventarios where id = '$request->id_tabela_invetario'");
        
       
     
        $alterar = \DB::select("
          update inventarios set 
          obs = '$request->obs', 
          motivo = '$request->motivo',
          acao = '$acao'
          where id = '$request->id_tabela_invetario'");
        $acao =  $item[0]->tipo;

        $erros[] = 'O item '.$item[0]->item.' foi alterado de manter para DEVOLVER.';
                   $request->session()->flash('alert-success', $erros);


        return redirect('/mostruarios/inventarios/detalhes/'.$acao.'/'.$item[0]->id_inventario)->with('acao', $acao)->with('erros', $erros);

    } 

    public function listaInventario($acao) {
     

      $id_rep = \Auth::user()->id_addressbook;
      $id_usuario = \Auth::id();


      if(\Auth::user()->id_perfil == 4 or \Auth::user()->id_perfil == 23 or \auth::user()->id_perfil ==25 ){

      $inventarios = \DB::select("select id_inventario, cast(min(created_at) as date) as dt_inicio, count(item) as item, status, tipo  from inventarios where  exclui = 0 and id_rep = $id_rep and tipo = '$acao' group by id_inventario, status, tipo ");
      }else{
      $inventarios = \DB::select("select  email,id_rep, usuarios.nome, id_inventario, cast(min(inventarios.created_at) as date) as dt_inicio, count(item) as item, inventarios.status, inventarios.tipo, inventarios.id_devolucao  from inventarios
      left join addressbook on addressbook.id = id_rep 
      left join usuarios on id_addressbook = addressbook.id
      where  exclui = 0  group  by id_rep, usuarios.nome, id_inventario, inventarios.status, email, id_devolucao,inventarios.tipo ");
      } 

      return view('mostruarios.inventarios.lista')->with('inventarios', $inventarios)->with('acao', $acao);


    } 

    public function reabreInvetario(Request $request) {

      $id = \DB::select("select id_devolucao from inventarios where id_inventario = '$request->id_inventario'  and id_devolucao <> '' and acao = 'devolver' and exclui = 0 limit 1");


      $inventarios = \DB::select("update inventarios set status = 'Iniciada' where id_inventario = '$request->id_inventario' ");
      $excluiatualizaNF = \DB::select("DELETE FROM `atualiza_nf` WHERE id_inventario = '$request->id_inventario' ");

      if(isset($id[0]->id_devolucao)){
      $iddev = $id[0]->id_devolucao;
       $cancela = \DB::connection('goweb')->select("update devolucoes set situacao = 'Cancelada' where id = '$iddev'");
     }
       return redirect('/mostruarios/inventarios/inventario');
    } 


    public function cancelaInventario($id_inventario) {
      //dd($id_inventario);


      $inventarios = \DB::select("update inventarios set status = 'cancelado', exclui = '1', obs='cancelado por operações' where id_inventario = '$id_inventario' and status = 'iniciada' ");
      
       return redirect('/mostruarios/inventarios/inventario');
    } 

    public function importaInventario(Request $request, $acao) {

      $id_inventario = $request->id_inventario;
      $id_rep = \Auth::user()->id_addressbook;

      $data = date('d-m-yy-H:i:s');
      
      $arquivo2 = "num_inventario-".$id_inventario."-id_representante-".$id_rep."-data-".$data.".xlsx";

      $uploaddir = '/var/www/html/portal-gestao/storage/uploads/inventarios/';
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
              $referencia = $linha[0];
              $produto = \DB::select("select * from itens where secundario = '$referencia' and codtipoarmaz not in ('o','0')");

              if (count($produto)>0) {

                $id_produto = $produto[0]->id;
                $produto = $produto[0]->secundario;
  
                $inventario = \DB::select("select * from inventarios where exclui = 0 and item = '$referencia' and tipo = '$acao' and status <> 'Finalizado' and id_inventario = '$id_inventario' ");

                if (count($inventario)>0) {


                   $situacao = \DB::select("

                       select distinct statusatual, codgrife, grife, itens.modelo, itens.secundario, codgrife, statusatual,
                    case 
					WHEN fornecedor like '%kering%'
                    AND colmod >= concat(year(DATE_sub(CURDATE(), INTERVAL 180 DAY)),LPAD(month(DATE_sub(CURDATE(), INTERVAL 180 DAY)), 2, '0'))
                    then 'MANTER'
                    
                    when fornecedor like '%kering%' and (disp_vendas+conf_montado+cet+ifnull(producao,0)+saldo_most)-ifnull(orcvalido,0) >=1
                    AND codstatusatual in ('pro','dis','15d','30d')
                    then 'MANTER'
                    when fornecedor like '%kering%' and (disp_vendas+conf_montado+cet+ifnull(producao,0)+saldo_most)-ifnull(orcvalido,0) <1
                    then 'DEVOLVER'
                    
                    
                    when codstatusatual in ('pro','dis','15d','30d')
                    and fornecedor not like '%kering%'
                    then 'MANTER'
                    when codstatusatual = 'esg' 
                    and colmod > concat(year(DATE_sub(CURDATE(), INTERVAL 60 DAY)),LPAD(month(DATE_sub(CURDATE(), INTERVAL 60 DAY)), 2, '0'))
                    and fornecedor not like '%kering%'
                    then 'MANTER'
                    when codstatusatual = 'esg' 
                    and colmod > concat(year(DATE_sub(CURDATE(), INTERVAL 60 DAY)),LPAD(month(DATE_sub(CURDATE(), INTERVAL 60 DAY)), 2, '0'))
                    and clasmod in ('linha a+', 'linha a++')
                    and fornecedor not like '%kering%'
                    then 'MANTER'
                    
                    when codstatusatual = 'esg' 
                    and colmod < concat(year(DATE_sub(CURDATE(), INTERVAL 60 DAY)),LPAD(month(DATE_sub(CURDATE(), INTERVAL 60 DAY)), 2, '0'))
                    then 'DEVOLVER'
                    
                    
                    else 'DEVOLVER' end as Situacao_Peca, concat(year(DATE_sub(CURDATE(), INTERVAL 60 DAY)),LPAD(month(DATE_sub(CURDATE(), INTERVAL 60 DAY)), 2, '0')) as mes, colmod
                   
                    from itens
                    left join saldos on saldos.secundario = itens.secundario
                    left join producoes_sint on itens.secundario = producoes_sint.cod_sec
                    left join orcamentos on itens.secundario = orcamentos.secundario


                     where itens.secundario = '$produto'");

                  $situacao_produto = $situacao[0]->Situacao_Peca;
                
                  $insere = \DB::select("insert into inventarios (id_inventario, id_rep, id_item, item, situacao, acao, arquivo, tipo) values ($id_inventario, $id_rep, $id_produto, '$produto', '$situacao_produto', '$situacao_produto', '$uploadfile', '$acao') ");

                  $erros[] = 'Linha: '.$i.' - Item '.$referencia.' duplicado.';
                  $request->session()->flash('alert-danger', $erros);
                 

                } else {

                   $situacao = \DB::select("

                      select distinct statusatual, codgrife, grife, itens.modelo, itens.secundario, codgrife, statusatual,
                    case 
					WHEN fornecedor like '%kering%'
                    AND colmod >= concat(year(DATE_sub(CURDATE(), INTERVAL 180 DAY)),LPAD(month(DATE_sub(CURDATE(), INTERVAL 180 DAY)), 2, '0'))
                    then 'MANTER'
                    
                    when fornecedor like '%kering%' and (disp_vendas+conf_montado+cet+ifnull(producao,0)+saldo_most)-ifnull(orcvalido,0) >=1
                    AND codstatusatual in ('pro','dis','15d','30d')
                    then 'MANTER'
                    when fornecedor like '%kering%' and (disp_vendas+conf_montado+cet+ifnull(producao,0)+saldo_most)-ifnull(orcvalido,0) <1
                    then 'DEVOLVER'
                    
                    
                    when codstatusatual in ('pro','dis','15d','30d')
                    and fornecedor not like '%kering%'
                    then 'MANTER'
                    when codstatusatual = 'esg' 
                    and colmod > concat(year(DATE_sub(CURDATE(), INTERVAL 60 DAY)),LPAD(month(DATE_sub(CURDATE(), INTERVAL 60 DAY)), 2, '0'))
                    and fornecedor not like '%kering%'
                    then 'MANTER'
                    when codstatusatual = 'esg' 
                    and colmod > concat(year(DATE_sub(CURDATE(), INTERVAL 60 DAY)),LPAD(month(DATE_sub(CURDATE(), INTERVAL 60 DAY)), 2, '0'))
                    and clasmod in ('linha a+', 'linha a++')
                    and fornecedor not like '%kering%'
                    then 'MANTER'
                    
                    when codstatusatual = 'esg' 
                    and colmod < concat(year(DATE_sub(CURDATE(), INTERVAL 60 DAY)),LPAD(month(DATE_sub(CURDATE(), INTERVAL 60 DAY)), 2, '0'))
                    then 'DEVOLVER'
                    
                    
                    else 'DEVOLVER' end as Situacao_Peca, concat(year(DATE_sub(CURDATE(), INTERVAL 60 DAY)),LPAD(month(DATE_sub(CURDATE(), INTERVAL 60 DAY)), 2, '0')) as mes, colmod
                   
                    from itens
                    left join saldos on saldos.secundario = itens.secundario
                    left join producoes_sint on itens.secundario = producoes_sint.cod_sec
                    left join orcamentos on itens.secundario = orcamentos.secundario


                     where itens.secundario = '$produto'");

                  $situacao_produto = $situacao[0]->Situacao_Peca;
                
                  $insere = \DB::select("insert into inventarios (id_inventario, id_rep, id_item, item, situacao, acao, arquivo,tipo) values ($id_inventario, $id_rep, $id_produto, '$produto', '$situacao_produto', '$situacao_produto', '$uploadfile','$acao') ");
                  $erros2[] = 'Linha: '.$i.' - Item '.$referencia.' inserido.';
                   $request->session()->flash('alert-success', $erros2);
                

                } 

              } else {

                $erros[] = 'Linha: '.$i.' - Item '.$referencia.' não existe no cadastro.';
                 $request->session()->flash('alert-danger', $erros);

              }
  
            }
            $i++;
          } 

 
                      


        }
      }
   
     
  
   
      return redirect('/mostruarios/inventarios/detalhes/'.$acao.'/'.$id_inventario)->with('erros', $erros)->with('acao', $acao);

    }



    public function importaDevolucao(Request $request, $acao) {
      

      $id_inventario = $request->id_inventario;
      $id_rep = \Auth::user()->id_addressbook;

      $data = date('d-m-yy-H:i:s');
      
      $arquivo2 = "num_inventario-".$id_inventario."-id_representante-".$id_rep."-data-".$data.".xlsx";

      $uploaddir = '/var/www/html/portal-gestao/storage/uploads/inventarios/';
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
              $referencia = $linha[0];
              $produto = \DB::select("select * from itens where secundario = '$referencia' and codtipoarmaz not in ('o','0')");

              if (count($produto) >0) {

                $id_produto = $produto[0]->id;
                $produto = $produto[0]->secundario;
  
               $inventario = \DB::select("select * from inventarios where exclui = 0 and item = '$referencia' and tipo = '$acao' and status <> 'Finalizado' and id_inventario = '$id_inventario' ");
               

                if (count($inventario)>0) {

                   $situacao = \DB::select("

                       select distinct statusatual, codgrife, grife, itens.modelo, itens.secundario, codgrife, statusatual,
                    case 
					WHEN fornecedor like '%kering%'
                    AND colmod >= concat(year(DATE_sub(CURDATE(), INTERVAL 180 DAY)),LPAD(month(DATE_sub(CURDATE(), INTERVAL 180 DAY)), 2, '0'))
                    then 'MANTER'
                    
                    when fornecedor like '%kering%' and (disp_vendas+conf_montado+cet+ifnull(producao,0)+saldo_most)-ifnull(orcvalido,0) >=1
                    AND codstatusatual in ('pro','dis','15d','30d')
                    then 'MANTER'
                    when fornecedor like '%kering%' and (disp_vendas+conf_montado+cet+ifnull(producao,0)+saldo_most)-ifnull(orcvalido,0) <1
                    then 'DEVOLVER'
                    
                    
                    when codstatusatual in ('pro','dis','15d','30d')
                    and fornecedor not like '%kering%'
                    then 'MANTER'
                    when codstatusatual = 'esg' 
                    and colmod > concat(year(DATE_sub(CURDATE(), INTERVAL 60 DAY)),LPAD(month(DATE_sub(CURDATE(), INTERVAL 60 DAY)), 2, '0'))
                    and fornecedor not like '%kering%'
                    then 'MANTER'
                    when codstatusatual = 'esg' 
                    and colmod > concat(year(DATE_sub(CURDATE(), INTERVAL 60 DAY)),LPAD(month(DATE_sub(CURDATE(), INTERVAL 60 DAY)), 2, '0'))
                    and clasmod in ('linha a+', 'linha a++')
                    and fornecedor not like '%kering%'
                    then 'MANTER'
                    
                    when codstatusatual = 'esg' 
                    and colmod < concat(year(DATE_sub(CURDATE(), INTERVAL 60 DAY)),LPAD(month(DATE_sub(CURDATE(), INTERVAL 60 DAY)), 2, '0'))
                    then 'DEVOLVER'
                    
                    
                    else 'DEVOLVER' end as Situacao_Peca, concat(year(DATE_sub(CURDATE(), INTERVAL 60 DAY)),LPAD(month(DATE_sub(CURDATE(), INTERVAL 60 DAY)), 2, '0')) as mes, colmod
                   
                    from itens
                    left join saldos on saldos.secundario = itens.secundario
                    left join producoes_sint on itens.secundario = producoes_sint.cod_sec
                    left join orcamentos on itens.secundario = orcamentos.secundario

                     where itens.secundario = '$produto'");

                  $situacao_produto = $situacao[0]->Situacao_Peca;
                
                  $insere = \DB::select("insert into inventarios (id_inventario, id_rep, id_item, item, situacao, acao, arquivo, tipo) values ($id_inventario, $id_rep, $id_produto, '$produto', '$situacao_produto', 'DEVOLVER', '$uploadfile', '$acao') ");

                  $erros[] = 'Linha: '.$i.' - Item '.$referencia.' duplicado.';
                  $request->session()->flash('alert-danger', $erros);

                } else {

                   $situacao = \DB::select("

                       select distinct statusatual, codgrife, grife, itens.modelo, itens.secundario, codgrife, statusatual,
                    case 
					WHEN fornecedor like '%kering%'
                    AND colmod >= concat(year(DATE_sub(CURDATE(), INTERVAL 180 DAY)),LPAD(month(DATE_sub(CURDATE(), INTERVAL 180 DAY)), 2, '0'))
                    then 'MANTER'
                    
                    when fornecedor like '%kering%' and (disp_vendas+conf_montado+cet+ifnull(producao,0)+saldo_most)-ifnull(orcvalido,0) >=1
                    AND codstatusatual in ('pro','dis','15d','30d')
                    then 'MANTER'
                    when fornecedor like '%kering%' and (disp_vendas+conf_montado+cet+ifnull(producao,0)+saldo_most)-ifnull(orcvalido,0) <1
                    then 'DEVOLVER'
                    
                    
                    when codstatusatual in ('pro','dis','15d','30d')
                    and fornecedor not like '%kering%'
                    then 'MANTER'
                    when codstatusatual = 'esg' 
                    and colmod > concat(year(DATE_sub(CURDATE(), INTERVAL 60 DAY)),LPAD(month(DATE_sub(CURDATE(), INTERVAL 60 DAY)), 2, '0'))
                    and fornecedor not like '%kering%'
                    then 'MANTER'
                    when codstatusatual = 'esg' 
                    and colmod > concat(year(DATE_sub(CURDATE(), INTERVAL 60 DAY)),LPAD(month(DATE_sub(CURDATE(), INTERVAL 60 DAY)), 2, '0'))
                    and clasmod in ('linha a+', 'linha a++')
                    and fornecedor not like '%kering%'
                    then 'MANTER'
                    
                    when codstatusatual = 'esg' 
                    and colmod < concat(year(DATE_sub(CURDATE(), INTERVAL 60 DAY)),LPAD(month(DATE_sub(CURDATE(), INTERVAL 60 DAY)), 2, '0'))
                    then 'DEVOLVER'
                    
                    
                    else 'DEVOLVER' end as Situacao_Peca, concat(year(DATE_sub(CURDATE(), INTERVAL 60 DAY)),LPAD(month(DATE_sub(CURDATE(), INTERVAL 60 DAY)), 2, '0')) as mes, colmod
                   
                    from itens
                    left join saldos on saldos.secundario = itens.secundario
                    left join producoes_sint on itens.secundario = producoes_sint.cod_sec
                    left join orcamentos on itens.secundario = orcamentos.secundario


                     where itens.secundario = '$produto'");

                  $situacao_produto = $situacao[0]->Situacao_Peca;
                
                  $insere = \DB::select("insert into inventarios (id_inventario, id_rep, id_item, item, situacao, acao, arquivo, tipo) values ($id_inventario, $id_rep, $id_produto, '$produto', '$situacao_produto', 'DEVOLVER', '$uploadfile', '$acao') ");
                  $erros2[] = 'Linha: '.$i.' - Item '.$referencia.' inserido.';
                   $request->session()->flash('alert-success', $erros2);


                } 

              } else {

                 $erros[] = 'Linha: '.$i.' - Item '.$referencia.' não existe no cadastro.';
                 $request->session()->flash('alert-danger', $erros);

              }
  
            }
            $i++;
          } 

 
                      


        }
      }
     
       return redirect('/mostruarios/inventarios/detalhes/'.$acao.'/'.$id_inventario)->with('erros', $erros)->with('acao', $acao);

    }

    public function importaManter(Request $request, $acao) {
      

      $id_inventario = $request->id_inventario;
      $id_rep = \Auth::user()->id_addressbook;

      $data = date('d-m-yy-H:i:s');
      
      $arquivo2 = "num_inventario-".$id_inventario."-id_representante-".$id_rep."-data-".$data.".xlsx";

      $uploaddir = '/var/www/html/portal-gestao/storage/uploads/inventarios/';
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
              $referencia = $linha[0];
              $produto = \DB::select("select * from itens where secundario = '$referencia' and codtipoarmaz not in ('o','0')");

              if (count($produto) >0) {

                $id_produto = $produto[0]->id;
                $produto = $produto[0]->secundario;
  
               $inventario = \DB::select("select * from inventarios where exclui = 0 and item = '$referencia' and tipo = '$acao' and status <> 'Finalizado' and id_inventario = '$id_inventario' ");
               

                if (count($inventario)>0) {

                   $situacao = \DB::select("

                      select distinct statusatual, codgrife, grife, itens.modelo, itens.secundario, codgrife, statusatual,
                    case 
					WHEN fornecedor like '%kering%'
                    AND colmod >= concat(year(DATE_sub(CURDATE(), INTERVAL 180 DAY)),LPAD(month(DATE_sub(CURDATE(), INTERVAL 180 DAY)), 2, '0'))
                    then 'MANTER'
                    
                    when fornecedor like '%kering%' and (disp_vendas+conf_montado+cet+ifnull(producao,0)+saldo_most)-ifnull(orcvalido,0) >=1
                    AND codstatusatual in ('pro','dis','15d','30d')
                    then 'MANTER'
                    when fornecedor like '%kering%' and (disp_vendas+conf_montado+cet+ifnull(producao,0)+saldo_most)-ifnull(orcvalido,0) <1
                    then 'DEVOLVER'
                    
                    
                    when codstatusatual in ('pro','dis','15d','30d')
                    and fornecedor not like '%kering%'
                    then 'MANTER'
                    when codstatusatual = 'esg' 
                    and colmod > concat(year(DATE_sub(CURDATE(), INTERVAL 60 DAY)),LPAD(month(DATE_sub(CURDATE(), INTERVAL 60 DAY)), 2, '0'))
                    and fornecedor not like '%kering%'
                    then 'MANTER'
                    when codstatusatual = 'esg' 
                    and colmod > concat(year(DATE_sub(CURDATE(), INTERVAL 60 DAY)),LPAD(month(DATE_sub(CURDATE(), INTERVAL 60 DAY)), 2, '0'))
                    and clasmod in ('linha a+', 'linha a++')
                    and fornecedor not like '%kering%'
                    then 'MANTER'
                    
                    when codstatusatual = 'esg' 
                    and colmod < concat(year(DATE_sub(CURDATE(), INTERVAL 60 DAY)),LPAD(month(DATE_sub(CURDATE(), INTERVAL 60 DAY)), 2, '0'))
                    then 'DEVOLVER'
                    
                    
                    else 'DEVOLVER' end as Situacao_Peca, concat(year(DATE_sub(CURDATE(), INTERVAL 60 DAY)),LPAD(month(DATE_sub(CURDATE(), INTERVAL 60 DAY)), 2, '0')) as mes, colmod
                   
                    from itens
                    left join saldos on saldos.secundario = itens.secundario
                    left join producoes_sint on itens.secundario = producoes_sint.cod_sec
                    left join orcamentos on itens.secundario = orcamentos.secundario

                     where itens.secundario = '$produto'");

                  $situacao_produto = $situacao[0]->Situacao_Peca;
                
                  $insere = \DB::select("insert into inventarios (id_inventario, id_rep, id_item, item, situacao, acao, arquivo, tipo) values ($id_inventario, $id_rep, $id_produto, '$produto', '$situacao_produto', 'MANTER', '$uploadfile', '$acao') ");

                  $erros[] = 'Linha: '.$i.' - Item '.$referencia.' duplicado.';
                  $request->session()->flash('alert-danger', $erros);

                } else {

                   $situacao = \DB::select("

                      select distinct statusatual, codgrife, grife, itens.modelo, itens.secundario, codgrife, statusatual,
                    case 
					WHEN fornecedor like '%kering%'
                    AND colmod >= concat(year(DATE_sub(CURDATE(), INTERVAL 180 DAY)),LPAD(month(DATE_sub(CURDATE(), INTERVAL 180 DAY)), 2, '0'))
                    then 'MANTER'
                    
                    when fornecedor like '%kering%' and (disp_vendas+conf_montado+cet+ifnull(producao,0)+saldo_most)-ifnull(orcvalido,0) >=1
                    AND codstatusatual in ('pro','dis','15d','30d')
                    then 'MANTER'
                    when fornecedor like '%kering%' and (disp_vendas+conf_montado+cet+ifnull(producao,0)+saldo_most)-ifnull(orcvalido,0) <1
                    then 'DEVOLVER'
                    
                    
                    when codstatusatual in ('pro','dis','15d','30d')
                    and fornecedor not like '%kering%'
                    then 'MANTER'
                    when codstatusatual = 'esg' 
                    and colmod > concat(year(DATE_sub(CURDATE(), INTERVAL 60 DAY)),LPAD(month(DATE_sub(CURDATE(), INTERVAL 60 DAY)), 2, '0'))
                    and fornecedor not like '%kering%'
                    then 'MANTER'
                    when codstatusatual = 'esg' 
                    and colmod > concat(year(DATE_sub(CURDATE(), INTERVAL 60 DAY)),LPAD(month(DATE_sub(CURDATE(), INTERVAL 60 DAY)), 2, '0'))
                    and clasmod in ('linha a+', 'linha a++')
                    and fornecedor not like '%kering%'
                    then 'MANTER'
                    
                    when codstatusatual = 'esg' 
                    and colmod < concat(year(DATE_sub(CURDATE(), INTERVAL 60 DAY)),LPAD(month(DATE_sub(CURDATE(), INTERVAL 60 DAY)), 2, '0'))
                    then 'DEVOLVER'
                    
                    
                    else 'DEVOLVER' end as Situacao_Peca, concat(year(DATE_sub(CURDATE(), INTERVAL 60 DAY)),LPAD(month(DATE_sub(CURDATE(), INTERVAL 60 DAY)), 2, '0')) as mes, colmod
                   
                    from itens
                    left join saldos on saldos.secundario = itens.secundario
                    left join producoes_sint on itens.secundario = producoes_sint.cod_sec
                    left join orcamentos on itens.secundario = orcamentos.secundario


                     where itens.secundario = '$produto'");

                  $situacao_produto = $situacao[0]->Situacao_Peca;
                
                  $insere = \DB::select("insert into inventarios (id_inventario, id_rep, id_item, item, situacao, acao, arquivo, tipo) values ($id_inventario, $id_rep, $id_produto, '$produto', '$situacao_produto', 'MANTER', '$uploadfile', '$acao') ");
                  $erros2[] = 'Linha: '.$i.' - Item '.$referencia.' inserido.';
                   $request->session()->flash('alert-success', $erros2);


                } 

              } else {

                 $erros[] = 'Linha: '.$i.' - Item '.$referencia.' não existe no cadastro.';
                 $request->session()->flash('alert-danger', $erros);

              }
  
            }
            $i++;
          } 

 
                      


        }
      }
     
       return redirect('/mostruarios/inventarios/detalhes/'.$acao.'/'.$id_inventario)->with('erros', $erros)->with('acao', $acao);

    }


    public function novoInventario($acao) {



      $id_rep = \Auth::user()->id_addressbook;
 

      $inventarios = \DB::select("select inventarios.*, itens.statusatual from inventarios 
        left join itens on item = secundario where exclui = 0 and id_rep = '$id_rep' and status <> 'Finalizado' 
        and tipo = '$acao'  order by inventarios.id desc ");
       

      $resumo = array();
      $resumo = \DB::select("
                              select agrup, sum(devolver) as devolver, sum(manter) as manter
                              from (
                              select agrup, 
                                  case 

                                  when acao = 'DEVOLVER' then 1 else 0 end as devolver ,
                                  case when acao = 'MANTER' then 1 else 0 end as manter 
                              from inventarios 
                              left join itens on id_item = itens.id
                              where exclui = 0 and id_rep = $id_rep and status <> 'Finalizado' 
                              and tipo = '$acao'
                              ) as base
                              group by agrup");

      return view('mostruarios.inventarios.novo')->with('itens', $inventarios)->with('resumo', $resumo)->with('acao', $acao);


    }


    public function detalhesInventario($acao,$id ) {


      $id_rep = \Auth::user()->id_addressbook;
      if($id_rep==96778 or $id_rep==97043 or $id_rep==97043 or $id_rep==89889 or $id_rep==32991){

      $inventarios = \DB::select("select inventarios.*, itens.statusatual 
              from inventarios 
              left join itens on item = secundario 
              where exclui = 0 and inventarios.id_inventario = '$id'
              and tipo = '$acao'
              order by inventarios.id desc  ");
    }
    else{
      $inventarios = \DB::select("select inventarios.*, itens.statusatual 
              from inventarios 
              left join itens on item = secundario 
              where exclui = 0 and inventarios.id_inventario = '$id'
              and tipo = '$acao'
              order by inventarios.id desc  ");
    }

      $resumo = array();
      if ($inventarios) {

        $id_rep = $inventarios[0]->id_rep;
        $resumo = \DB::select("
                                select agrup, sum(devolver) as devolver, sum(manter) as manter
                                from (
                                select agrup, 
                                    case when acao = 'DEVOLVER' then 1 else 0 end as devolver ,
                                    case when acao = 'MANTER' then 1 else 0 end as manter 
                                from inventarios 
                                left join itens on id_item = itens.id
                                where exclui = 0 and id_inventario = $id
                                and tipo = '$acao'
                                ) as base
                                group by agrup");
      }

      return view('mostruarios.inventarios.detalhes')->with('itens', $inventarios)->with('resumo', $resumo)->with('acao', $acao);


    }

    public function confereInventario(Request $request, $acao) {
      

        // if (\Auth::id() <> 1 and \Auth::id() <> 411) {
        //   die('manutencao');
        // 
        $id_rep = \Auth::user()->id_addressbook;

        $inventario_aberto = \DB::select("select * from inventarios where exclui = 0 and id_rep = $id_rep and status <> 'Finalizado' and tipo = '$acao' order by created_at desc");
       
        

        if (count($inventario_aberto)>0) {
          $id_inventario = $inventario_aberto[0]->id_inventario;
        } else {
          $id_inventario = $id_rep.date('YmdHis');          
        }

        
       
        // $devolucao = DevolucaoMostruario::where('id_usuario', $id_usuario)->where('situacao', '<>', 'Concluída')->first();

        if ($request->referencia) {
			
				$item = \DB::select("select * from itens where secundario = '$request->referencia' and codtipoarmaz not in ('o','0') limit 1");
         //  $item = \App\Item::where('secundario', $request->referencia)->first();
		

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
					WHEN fornecedor like '%kering%'
                    AND colmod >= concat(year(DATE_sub(CURDATE(), INTERVAL 180 DAY)),LPAD(month(DATE_sub(CURDATE(), INTERVAL 180 DAY)), 2, '0'))
                    then 'MANTER'
                    
                    when fornecedor like '%kering%' and (disp_vendas+conf_montado+cet+ifnull(producao,0)+saldo_most)-ifnull(orcvalido,0) >=1
                    AND codstatusatual in ('pro','dis','15d','30d')
                    then 'MANTER'
                    when fornecedor like '%kering%' and (disp_vendas+conf_montado+cet+ifnull(producao,0)+saldo_most)-ifnull(orcvalido,0) <1
                    then 'DEVOLVER'
                    
                    
                    when codstatusatual in ('pro','dis','15d','30d')
                    and fornecedor not like '%kering%'
                    then 'MANTER'
                    when codstatusatual = 'esg' 
                    and colmod > concat(year(DATE_sub(CURDATE(), INTERVAL 60 DAY)),LPAD(month(DATE_sub(CURDATE(), INTERVAL 60 DAY)), 2, '0'))
                    and fornecedor not like '%kering%'
                    then 'MANTER'
                    when codstatusatual = 'esg' 
                    and colmod > concat(year(DATE_sub(CURDATE(), INTERVAL 60 DAY)),LPAD(month(DATE_sub(CURDATE(), INTERVAL 60 DAY)), 2, '0'))
                    and clasmod in ('linha a+', 'linha a++')
                    and fornecedor not like '%kering%'
                    then 'MANTER'
                    
                    when codstatusatual = 'esg' 
                    and colmod < concat(year(DATE_sub(CURDATE(), INTERVAL 60 DAY)),LPAD(month(DATE_sub(CURDATE(), INTERVAL 60 DAY)), 2, '0'))
                    then 'DEVOLVER'
                    
                    
                    else 'DEVOLVER' end as Situacao_Peca, concat(year(DATE_sub(CURDATE(), INTERVAL 60 DAY)),LPAD(month(DATE_sub(CURDATE(), INTERVAL 60 DAY)), 2, '0')) as mes, colmod
                   
                    from itens
                    left join saldos on saldos.secundario = itens.secundario
                    left join producoes_sint on itens.secundario = producoes_sint.cod_sec
                    left join orcamentos on itens.secundario = orcamentos.secundario


                   where itens.secundario = '$request->referencia' ");



                 $verifica_leitura = \DB::select("select * from inventarios where exclui = 0 and item = '$request->referencia' and status <> 'Finalizado' and tipo = '$acao' and id_inventario = '$id_inventario'  ");
                 

                 if ($verifica_leitura && count($verifica_leitura) >0 && !isset($request->duplica)) {
                    
                    $request->session()->flash('alert-warning', 'Este item já foi inserido, deseja inserir novamente?');
                    return redirect('/mostruarios/inventarios/detalhes/'.$acao.'/'.$id_inventario.'?duplicado=1&referencia='.$request->referencia);

                 }

                 if ($situacao) {
					

                    //$id_lista = $request->id_lista;

                    $situacao_item = $situacao[0]->Situacao_Peca;
					 
					 foreach($item as $item2){
						 
						 $item1 = $item2->secundario;
					 $iditem1 = $item2->id;
				
					 }
					 

                    if (isset($request->devolver) && $request->devolver == 1) {
					
                       $query = \DB::select("insert into inventarios ( id_inventario, id_rep, id_item, item, situacao, acao, obs, tipo) values ('$id_inventario', $id_rep, $iditem1, '$item1', '$situacao_item', 'DEVOLVER', '$request->motivo', '$acao') ");
                    } else {
							
                       $query = \DB::select("insert into inventarios ( id_inventario, id_rep, id_item, item, situacao, acao, tipo) values ('$id_inventario', $id_rep, $iditem1, '$item1', '$situacao_item', '$situacao_item', '$acao') ");

                    }

                 }

           } else {

              $request->session()->flash('alert-warning', 'Item não encontrado');
              $situacao = array();

           }


        }


        return redirect('/mostruarios/inventarios/detalhes/'.$acao.'/'.$id_inventario)->with('item', $situacao)->with('acao', $acao);




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
                $mail->Host = 'imap.gmail.com';  // Specify main and backup SMTP servers
                $mail->SMTPAuth = true;                               // Enable SMTP authentication
                $mail->Username = 'goweb@goeyewear.com.br';                 // SMTP username
                $mail->Password = 'd6SHzwSu';                           // SMTP password
                $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 465;                                    // TCP port to connect to                                  // TCP port to connect to

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

      
      $nome_excel = '/var/www/html/portal-gestao/storage/app/devolucao_'.$usuario->nome.'_'.date('d-m-Y').'.xlsx';
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

   // public function detalhesDevolu2cao() {
      
   //    if (\Auth::id() <> 1 and \Auth::id() <> 411) {
   //      // die('manutencao');
   //    }      

   //    if (\Auth::user()->id_addressbook == '') {



   //    }

   //    $id_usuario = \Auth::id();

   //    $devolucao = DevolucaoMostruario::where('id_usuario', $id_usuario)->where('status', '<>', 'Concluída')->first();

   //    if ($devolucao) {

   //       $grifes = Session::get('grifes');

   //       $itens = \DB::select("select devolucoes2.*, statusatual, codgrife, grife, modelo, devolucoes2.secundario, codgrife, 
   //               case when statusatual in ('EM PRODUÇÃO', 'DISPONÍVEL','ENTREGA IMEDIATA','DISPONÍVEL EM 15 DIAS','DISPONÍVEL EM 30 DIAS') and codgrife in $grifes
   //               then ' MANTER' else 'DEVOLVER' end as Situacao_Peca

   //             from devolucoes2
   //             left join itens on devolucoes2.id_item = itens.id 

   //             where id_usuario = $id_usuario -- and devolucoes2.status = 1
   //             order by id desc");
   //       dd($grifes);

         
   //       $resumo = \DB::select("select agrup, count(*) as qtde
   //                               from devolucoes2
   //                               left join itens on id_item = itens.id
   //                               where id_usuario = $id_usuario and acao = 'DEVOLVER'
   //                               group by agrup");

   //       $id = 1;

   //       return view('mostruarios.devolucoes.detalhes')->with('devolucao', $devolucao)->with('itens', $itens)->with('resumo', $resumo);

   //   } else {

   //      return redirect('/mostruarios/devolucoes/nova');

   //   }


   // }

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
                 case when codstatusatual in ('pro', 'DIS','15d','30d') and codgrife in $grifes
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
        case when statusatual in ('pro', 'dis','15d','30d') and codgrife in $grifes
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


//		$pedidos = \DB::select("select id_cliente as razao
//-- , filial
//, pedido, tipo, dt_emissao nf, dt_nf, 
// sum(qtde) as pecas,
// case 
//  when max(ult_status) <= '540' then 'Pedido Emitido' 
//     when max(ult_status) > '540' and max(ult_status) < '620'then 'Em separação' 
//       when max(ult_status) = '620' then 'Faturado' 
//             else ''
//     end as 'Status'
// -- ,
//
//from mostruarios
//where id is not null 
//$sql
//group by id_cliente
//-- , filial
//, pedido, tipo, dt_emissao, nf, dt_nf
//limit 10");


    

//		return view('mostruarios.lista')->with('pedidos', $pedidos);
		return view('mostruarios.lista');
	}
}
