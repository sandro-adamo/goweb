<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Importacao;
use Illuminate\Support\Facades\Storage;





class ImportacaoController extends Controller
{

	
	
	public function uploadDocumentos(Request $request) {
	
		$id_info = $request->id_info;
		$tipo = $request->tipo;
		
		$path = $request->file('arquivo')->store("uploads/historico/{$id_info}");
		
		$usuario  = \DB::select("
			insert into compras_docs (pedido, path, tipo_arquivo, origem, data) 
			values('{$request->id_info}', '{$path}','{$request->tipo}', '{$request->origem}',now()) 
			
			");
		
		return redirect()->back();
	}
	
	
	
	
	
	
	public function cadastraPagamento(Request $request) {
		
			
			$idusuario = \Auth::id();
			$usuario  = \DB::select("select nome from usuarios where id = $idusuario limit 1");
			$nome_usuario = $usuario[0]->nome;
			
					  
			
		
			$titulos  = \DB::select("select right(numero,1) ultimo_numero from compras_titulos where id_pedido = '$request->id_pedido' and origem = '$request->tipo_pedido'  order by id desc limit 1");

			if(count($titulos)==1){
			$numero_parcela = 	$titulos[0]->ultimo_numero+1;
			$numero = $request->id_pedido.'_'.$numero_parcela;
			
			}else{
			$numero = $request->id_pedido.'_1';
			}
			

			//$numero_titulo = $request->id_pedido."_1";

			$insert_adiantamento  = \DB::select("INSERT INTO `compras_titulos`(`id_pedido`, `origem`, `numero`, `tipo`, `valor`, `moeda`, `vencimento`, `emissao`, `user`, `obs`) VALUES ('$request->id_pedido', '$request->tipo_pedido','$numero', '$request->tipo_pagamento', '$request->valor','$request->moeda', '$request->dt_vencimento', '$request->dt_emissao','$nome_usuario','$request->obs') ");

			if($request->criar_parcela=='parcela_unica'){
				$insert_parcelas  = \DB::select("INSERT INTO `compras_parcelas`(`id_titulo`, `numero`, `tipo`, `valor`, `moeda`, `vencimento`, `emissao`,  `user`, `obs`) VALUES ('$numero', '$request->id_pedido','$request->tipo_pedido', '$request->valor','$request->moeda', '$request->dt_vencimento', '$request->dt_emissao','$nome_usuario','$request->obs') ");
			}
						  
				  
				  return redirect()->back();
			  

	}

	public function detalhesDSimport($tipo, $pedido) {
		//dd($tipo);
		$query_2 = \DB::select(" 

select pedido, tipo, ref_go, item , secundario, concat(trim(ref_despachante),' ',trim(ref_nac_01)) ref, ult_prox, desc_status, group_concat(distinct left(fornecedor,20),' ') fornecedor,  
group_concat(distinct tipoitem,' ') tipoitem, group_concat(distinct codgrife,' ') codgrife, group_concat(distinct linha,' ') linha,
case when CHAR_LENGTH(group_concat(distinct colmod,' ')) > 26 then concat('...',right(group_concat(distinct colmod,' '),26)) else group_concat(distinct colmod,' ') end as colmod, 
sum(qtde) qtde, sum(atende) atende,
(select modelo from itens where item = secundario) as modelo,
(select agrup from itens where item = secundario) as agrup from (

select *, case when orcamentos > qtde then qtde else orcamentos end as atende from (
	select pedido, tipo, ref_go, ref_despachante, ref_nac_01, ult_prox, desc_status, secundario, cod_item, codtipoitem, tipoitem, id_pai,
	item_pai, tipo_pai, id_filho, tipo_filho, agrupador, codgrife, colmod, fornecedor,  item, linha,
	
		ifnull((select sum(qtde) qtde_aberto
		from go.vendas_jde vds
		left join go.itens on itens.id = vds.id_item
		where ult_status not in ('980') and codtipoitem = 006 and prox_status = 515 
		and vds.item = final.item
		),0) as orcamentos,
	sum(qtde) qtde
	
	from (
		select *, case when item_pai is null then secundario else item_pai end as item 
		from (

			select pedido, tipo, ref_go, ref_despachante, ref_nac_01, 
			concat(ult_status, ' / ',prox_status) ult_prox, imp.secundario, cod_item, codtipoitem,
            
		case 
		when prox_status = 230 then 'ped_inserido' 
		when prox_status = 280 then 'PL_recebido' 
		when prox_status = 345 then 'confirmado' 
        when prox_status = 350 then 'aguardando_pagamento'
        when prox_status = 355 then 'li_deferida'
        when prox_status = 359 then 'emb_autorizado'
        when prox_status = 365 then 'booking'
        when prox_status = 369 then 'chegada_Br'
        when prox_status = 375 then 'removido'
        when prox_status = 379 then 'registrado'
        when prox_status = 385 then 'nf_emitida'
        when prox_status = 390 then 'carregada'
        when prox_status = 400 then 'chegou_TO' else '' end as desc_status,
			
			case  when codtipoitem = 006 then 'PECA' 
				 when (left(imp.secundario,3) = 'FR ' or left(imp.secundario,6) = 'PONTE ') then 'FRENTE' 
				 when left(imp.secundario,2) IN ('LE','LD','HE','HD','PL','SC','BL') then 'ACESSORIOS'
				 else 'OUTROS' end as tipoitem, qtde_sol qtde
			 
			from importacoes_pedidos imp 
			left join itens on itens.id = cod_item		
			where ref_go not in ('LA200501','QGKI17-7B') and 
			-- ult_status not in (980) and prox_status not in (999,400) and
			 pedido = $pedido and tipo = '$tipo'
			
		) as base 

			left join (select * from itens_estrutura   ) as estrutura
			on estrutura.id_filho = cod_item
	) as final

	left join (select secundario codsec, agrup, codgrife, colmod, fornecedor, left(linha,3) linha from itens ) item
	on item.codsec = final.item        
	
	where  tipoitem in ('FRENTE','PECA', 'ACESSORIOS', 'MPDV','AGREGADOS')


group by pedido, tipo, ref_go, ref_despachante, ref_nac_01, ult_prox, desc_status, secundario, cod_item, codtipoitem, tipoitem, id_pai,
item_pai, tipo_pai, id_filho, tipo_filho, agrupador, codgrife, colmod, fornecedor, linha

) as final1

) as final2
group by pedido, tipo, ref_go, ref_despachante, ref_nac_01, ult_prox, desc_status, item, secundario

");
//dd($tipo);

//$cadastrar_parcelas = \DB::select("select* ");

return view('dashboards.importacao.dashboard_importacaodet')->with('query_2', $query_2);


	}
	public function detalhesInvoice($invoice) {

		$total = \DB::select("
select format(sum(valor),3) as valor, format(sum(qtd),2) as qtd, invoice 
from(
			select coluna9 as invoice, sum(coluna4) as qtd, sum(coluna4)*coluna7 as valor,  coluna8 as moeda, date(coluna11) dt_invoice,coluna7 , fabrica
				from xpto
				where coluna9 = '$invoice'
				group by coluna9 , date(coluna11) , coluna8, coluna7, fabrica
				order by  date(coluna11) desc) as base
				group by invoice");

		$invoice = \DB::select("select coluna9 as invoice, sum(coluna4) as qtd, sum(coluna4)*coluna7 as valor, concat(coluna2,' ',coluna3) item, coluna8 as moeda, date(coluna11) dt_invoice,coluna7 as unitario , fabrica
				from xpto
				where coluna9 = '$invoice'
				group by coluna9 , date(coluna11) , coluna8, coluna2,coluna3,coluna7, fabrica
				order by  date(coluna11) desc");

		

		


		return view('importacoes.detalhes')->with('invoice', $invoice)->with('total', $total);
    	

    }

    public function listaImportacoes() {

		$lista = \DB::select("select invoice, sum(qtd) as qtd, format(sum(valor),2) as valor, dt_invoice, moeda, fabrica
					from(
				select coluna9 as invoice, sum(coluna4) as qtd, sum(coluna4)*coluna7 as valor, concat(coluna2,' ',coluna3) item, coluna8 as moeda, date(coluna11) dt_invoice,coluna7 , fabrica
				from xpto
				group by coluna9 , date(coluna11) , coluna8, coluna2,coluna3,coluna7, fabrica
				order by  date(coluna11) desc) as base
				group by invoice, dt_invoice, moeda, fabrica
				order by dt_invoice desc");


		return view('importacoes.lista')->with('lista', $lista);
    	

    }


    public function deletaInvoice($invoice,Request $request) {
    	

    	$deleta = \DB::select("DELETE FROM `xpto` WHERE coluna9 = '$invoice' ");

    	$request->session()->flash('alert-success', 'Invoice '.$invoice.' deletada.');

    	return redirect()->back();
    }


    public function importaArquivo(Request $request) {

		$cod_usuario = \Auth::id();

		$uploaddir = '/var/www/html/portalgo/storage/uploads/';
		$uploadfile = $uploaddir . basename($_FILES['arquivo']['name']);

		$id_update = date('YmdHis'); 
		$erros = array();

		if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $uploadfile)) {

		    if (file_exists($uploadfile)) {

		        $handle = fopen($uploadfile, "r"); 

		        $linha = 1;

		        while (($line = fgetcsv($handle, 100000, ";")) !== FALSE) {

		            if ($linha >= 2) {   

 						$coluna7 = str_replace(",", ".", $line[6]);


 						// $verifica = \DB::select("select * from xpto where coluna1 = '$line[0]' and coluna2 = '$line[1]' and coluna3 = '$line[2]' and coluna4 = '$line[3]' and coluna7 = $coluna7 and coluna9 = '$line[8]' ");


 						// if (!$verifica) {

				            $insere = new Importacao();
				            $insere->coluna1 = $line[0];
				            $insere->coluna2 = $line[1];
				            $insere->coluna3 = $line[2];
				            $insere->coluna4 = $line[3];
				            $insere->coluna5 = $line[4];
				            $insere->coluna6 = $line[5];
				            $insere->coluna7 = $coluna7;
				            $insere->coluna8 = $line[7];
				            $insere->coluna9 = $line[8];
				            $insere->coluna10 = $line[9];
				            $insere->fabrica = $line[10];
				            $insere->id_update = $id_update;
				            $insere->save();

				            


				        // } else {

				        // 	$erros[] = 'Linha '.$linha.' ja foi importada. <br>';

				         
				        // }



		            }


		            $linha++;

		        }
		     //    if(count($erros)>0){
		     //    print_r($erros);
		       
		    	// }
		    	// else{

		    	$request->session()->flash('alert-success', 'Arquivo importado.');
		    	return redirect()->back();
		    	// }


		    }

		}
    }

	
	public function gravaDadosImport(Request $request) {
		
	$id = $request->id_info;
	$id_usuario = \Auth::id();

	if ($request->acao=="update") {

		$compra = \App\CompraInfo::find($id);

	} else {
		
		$compra = new \App\CompraInfo();
		
	}

if ($request->dt_invoice <> '') { $compra->dt_invoice = $request->dt_invoice;} else {$compra->dt_invoice = null;}
if ($request->dt_sol_li <> '') 	{ $compra->dt_sol_li = $request->dt_sol_li;} else {$compra->dt_sol_li = null;}
if ($request->dt_def_li <> '') 	{ $compra->dt_def_li = $request->dt_def_li;} else {$compra->dt_def_li = null;}
if ($request->dt_aut_embarque <> '') { $compra->dt_aut_embarque = $request->dt_aut_embarque;} else {$compra->dt_aut_embarque = null;}
if ($request->dt_emb_int <> '') { $compra->dt_emb_int = $request->dt_emb_int;} else {$compra->dt_emb_int = null;}
if ($request->dt_prev_chegada <> '') { $compra->dt_prev_chegada = $request->dt_prev_chegada;} else {$compra->dt_prev_chegada = null;}
if ($request->dt_chegada <> '') { $compra->dt_chegada = $request->dt_chegada;} else {$compra->dt_chegada = null;}
if ($request->dt_remocao <> '') { $compra->dt_remocao = $request->dt_remocao;} else {$compra->dt_remocao = null;}
if ($request->dt_registro <> '') { $compra->dt_registro = $request->dt_registro;} else {$compra->dt_registro = null;}
if ($request->dt_prev_embnac <> '') { $compra->dt_prev_embnac = $request->dt_prev_embnac;} else {$compra->dt_prev_embnac = null;}
if ($request->dt_emb_nac <> '') { $compra->dt_emb_nac = $request->dt_emb_nac;} else {$compra->dt_emb_nac = null;}
if ($request->dt_recebimento <> '') { $compra->dt_recebimento = $request->dt_recebimento;} else {$compra->dt_recebimento = null;}

if ($request->venc_dupl_1 <> '') { $compra->venc_dupl_1 = $request->venc_dupl_1;} else {$compra->venc_dupl_1 = null;}

if ($request->dt_chegada <> '') 
		{ $compra->dt_perdimento = date('Y/m/d',strtotime('+120 days',strtotime($request->dt_chegada)));} 
											 else {$compra->dt_perdimento = null;}

		
		
	//	echo date('d/m/Y', strtotime('+5 days', strtotime('14-07-2014')));  strtotime('+5 days',strtotime($request->dt_aut_embarque))
		
		

		$compra->id_pedido = $request->pedido;
		$compra->tipo_pedido = $request->tipo;
		$compra->tipo_agrup = $request->tipo_agrup;
		$compra->doc_agrup = $request->doc_agrup;
		$compra->cubagem_m3 = $request->cubagem_m3;
		
		

			$compra->num_temp = $request->num_temp;
			$compra->volumes = $request->volumes;
			$compra->peso_bruto = $request->peso_bruto;
			$compra->peso_liquido = $request->peso_liquido;
			$compra->obs_invoice = $request->obs_invoice;
			$compra->tipo_carga =  $request->tipo_carga;	
			$compra->an8_agente_int =  $request->an8_agente_int;
			$compra->obs_transito =  $request->obs_transito;
			$compra->num_awb =  $request->num_awb;
			$compra->obs_chegada =  $request->obs_chegada;
			$compra->protocolo_di =  $request->protocolo_di;
			$compra->an8_agente_nac =  $request->an8_agente_nac;		
			$compra->moeda_nac =  $request->moeda_nac;
			$compra->taxa_nac =  $request->taxa_nac;		
			$compra->impostos_nac =  $request->impostos_nac;
			$compra->icms_nac =  $request->icms_nac;
			$compra->vlr_requisicao =  $request->vlr_requisicao;
		
			
		if ($request->impostos_nac > 0) { $compra->base_imposto =  $request->impostos_nac/$request->taxa_nac; }
		if ($request->icms_nac > 0 ) {	$compra->base_icms =  $request->icms_nac/$request->taxa_nac; }
				
			
			
		
		
		
		
		
		
		$compra->save();

		return redirect()->back();
		}
	
	
	

	
	
	
	
	
	
	
	
	
	public function gravaRegistroImport(Request $request) {
		
	$id = $request->id_info;
	$id_usuario = \Auth::id();

	$registro = new \App\CompraRegistro();
	
		$registro->id_pedido =  $request->id_pedido;
		$registro->moeda =  $request->moeda;

		$registro->taxa1 =  $request->taxa1;
		$registro->taxa2 =  $request->taxa2;
		$registro->taxa3 =  $request->taxa3;
		
		$registro->save();
		
		
		return redirect()->back();
		}
	
	
	
		
}

