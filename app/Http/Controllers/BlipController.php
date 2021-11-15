<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Atendimento;
use App\AddressBook;
use App\Troca;
use App\Pedido;

class BlipController extends Controller
{
       

   public function pesquisaSatisfacao(Request $request, $id) {

      $checa = \App\Atendimento::find($id);

      if ($checa) {
         $pesquisa = new \App\AtendimentoPesquisa();

         $pesquisa->id_atendimento = $id;
         $pesquisa->pergunta = $request->pergunta;
         $pesquisa->resposta = $request->resposta;
         $pesquisa->save();
   
         return response()->json('true');
      }

      return response()->json('false');

   }
 
   public function verificaCliente($id) {

      $addressbook = \App\AddressBook::where('id', $id)->orWhere('cnpj', $id)->first();

      if ($addressbook) {
         $addressbook->status = 'success';
         return response()->json($addressbook);
      } else { 
         $addressbook = new \App\AddressBook();
         $addressbook->status = 'failure';
         return response()->json($addressbook);
      }

   }

   public function consultaPedido($id) {


      $pedido = Pedido::where('pedido', $id)->get();

      $response = array();

      if ($pedido) {

         $response["error"] = false;

      } else {

         $response["error"] = true;

      }

      $response["pedido"] = $pedido[0]->pedido;
      $response["tipo"] = $pedido[0]->tipo;
      $response["id_cliente"] = $pedido[0]->id_cliente;
      $response["condpag"] = $pedido[0]->condpag;


      return response()->json($response);

   }


   public function checaClienteTroca(Request $request, $id) {

      $addressbook = AddressBook::where('cnpj', $request->cnpj)->first();

      if ($addressbook) {

         $troca = Troca::where('id_troca', $id)->where('id_cliente', $addressbook->id)->get();

         if ($troca && count($troca) > 0) {

            $addressbook->error = false;
            return response()->json($addressbook);

         } 

      }

      $addressbook = new AddressBook();
      $addressbook->error = true;

      return response()->json($addressbook);

   }

   public function consultaTroca(Request $request, $id) {


//      $troca = Troca::where('id_troca', $id)->with('pedidos')->with('pedidos.notafiscal')->with('pedidos.notafiscal.rastreamento')->with('pedidos.notafiscal.rastreamento.transportadora')->get();
      $troca = Troca::where('id_troca', $id)->where('id_cliente', $request->id_cliente)->get();

      
//          $troca = \DB::select("select 


//    trocas.id_troca,
//     trocas.id_troca_item,
//     trocas.id_status_caso,
//     trocas.data_troca,
//     trocas.id_cliente,
//     trocas.id_item,
//     trocas.secundario,
//     trocas.substituto,
//     trocas.nf_origem,
//     trocas.nf_consumidor,
//     trocas.outro_codigo,
//     trocas.antecipado,
//     trocas.bloqueio,
//     trocas.parte_solicitacao,
//     trocas.defeito_solicitacao,
//     trocas.dt_recebida,
//     trocas.laudo,
//     trocas.laudo_parte,
//     trocas.laudo_defeito,
//     trocas_jde.pedido, 
//     trocas_jde.tipo as pedido_tipo, 
//     trocas_jde.dt_emissao pedido_emissao,
//     trocas_jde.suspensao as pedido_suspensao,
//     trocas_jde.item as pedido_item,
//     trocas_jde.ult_status as pedido_ult_status,
//     trocas_jde.prox_status as pedido_prox_status,
//     notas_jde.dt_emissao as nf_emissao,
//     substring(notas_jde.nf_legal,3,7) as nf_numero,
//     transportadora.razao as transportadora,
//     rastreamentos.rastreio, 
//     rastreamentos.data as data_rastreio,
//     rastreamentos.status as status_rastreio,
//     rastreamentos.entregue 
 

// from go.trocas 
// left join go.trocas_jde on trocas.id_troca_item = trocas_jde.id_troca_item and trocas.id_cliente = trocas_jde.id_cliente and trocas_jde.ult_status not in ('984', '980')
// left join go.notas_jde on notas_jde.ped_original = trocas_jde.pedido and notas_jde.tipo_original = trocas_jde.tipo and notas_jde.linha_original = trocas_jde.linha
// left join go.rastreamentos on rastreamentos.nota_fiscal = substring(notas_jde.nf_legal,3,7)
// left join go.addressbook as transportadora on rastreamentos.id_transportadora = transportadora.id

// where id_troca = 820939");

      if ($troca && count($troca) > 0 ) {

         $result = array();
         $result["status"] = 'success';
         $result["id_troca"] = $id;            
         $result["id_cliente"] = $troca[0]->id_cliente;            
         $result["registros"] = count($troca);
         $result["itens"] = $troca;

         return response()->json($result);

      } else {

         $result = array();
         $result["id_troca"] = $id;            
         $result["status"] = 'failure';

         return response()->json($result);
         
      }


   } 

   public function getAddressBook($id) {

      $addressbook = \App\AddressBook::where('id', $id)->orWhere('cnpj', $id)->first();

      if ($addressbook) {
         $addressbook->error = 0;
         return response()->json($addressbook);
      } else { 
         $addressbook = new \App\AddressBook();
         $addressbook->error = 1;
         return response()->json($addressbook);
      }

   }

	public function getProtocolo(Request $request) {

		$novo = new \App\Atendimento(); 

		$novo->contato = $request->contato;
		$novo->telefone = $request->telefone;
		$novo->id_originador = 0;
		$novo->id_usuario = 0;
		$novo->protocolo = 0;
		$novo->origem = 'WhatsApp';
		$novo->id_grupo = 1;
		$novo->save();


		return response()->json($novo->id);



	}

   public function update(Request $request, $id) {

      $atendimento = Atendimento::find($id);

      if ($atendimento) {


         if ($request->cliente) {
            $addressbook = \App\AddressBook::where('cnpj',$request->cliente)->first();
            if ($addressbook) {

               $atendimento->id_cliente = $addressbook->id;
               $atendimento->save();
               $addressbook->error = false;
               return response()->json($addressbook);
            } else {
               $addressbook = new \App\AddressBook();
               $addressbook->error = true;
               return response()->json($addressbook);
            }
         }

         if ($request->status) {

            $atendimento->status = $request->status;
            $atendimento->save();
            return response()->json(true);
         }

      }


   }



	public function addHistorico(Request $request, $id) {

		$novo = new \App\AtendimentoHistorico(); 

		$novo->id_usuario = 0;
		$novo->tabela = 'atendimentos';
		$novo->id_tabela = $id;
		$novo->categoria = 'conversa';
		$novo->historico = $request->historico;
		$novo->save();


	}
}
