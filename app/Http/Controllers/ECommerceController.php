<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AddressBook;

class ECommerceController extends Controller
{
    
   	public function listaClientes(Request $request) {
         dd('manutencao');
   		$clientes = array();

   		if ($request->busca) {

   			$clientes = \DB::select("select id, razao, cliente,
	(select grife from perfil_vendas where id_cliente = addressbook.id and status = 1 and grife = 'AH') as AH,
	(select grife from perfil_vendas where id_cliente = addressbook.id and status = 1 and grife = 'AT') as AT,
	(select grife from perfil_vendas where id_cliente = addressbook.id and status = 1 and grife = 'BG') as BG,
	(select grife from perfil_vendas where id_cliente = addressbook.id and status = 1 and grife = 'HI') as HI,
	(select grife from perfil_vendas where id_cliente = addressbook.id and status = 1 and grife = 'EV') as EV,
	(select grife from perfil_vendas where id_cliente = addressbook.id and status = 1 and grife = 'JO') as JO,
	(select grife from perfil_vendas where id_cliente = addressbook.id and status = 1 and grife = 'TC') as TC,
	(select grife from perfil_vendas where id_cliente = addressbook.id and status = 1 and grife = 'SP') as SP,
	(select grife from perfil_vendas where id_cliente = addressbook.id and status = 1 and grife = 'PU') as PU

from addressbook
where (razao like '$request->busca%' or id = '$request->busca' or cliente like '%$request->busca%')
limit 30");
		 			
   		}

   		return view('comercial.ecommerce.lista')->with('clientes', $clientes);


   	}


   	public function detalhesCliente($id) {


   		$perfil = \App\PerfilVenda::where('id_cliente', $id)->get();

   		return view('comercial.ecommerce.detalhes')->with('perfil', $perfil);


   	}


      public function liberaGrifes(Request $request) {


         if ($request->status == "true") {

            $checa = \DB::connection('go')->select("select * from perfil_vendas where id_cliente = $request->id_cliente and grife = '$request->grife'");

            if (!$checa) {

               $insere = \DB::connection('go')->select("insert into perfil_vendas (id_cliente, grife, status) values ( $request->id_cliente, '$request->grife', 1)");

            } else {

               if ($checa[0]->status == 0) {

                  $insere = \DB::connection('go')->select("update perfil_vendas set status = 1 where id_cliente = $request->id_cliente and grife = '$request->grife'");

               }

            }

         } else {

 
            $checa = \DB::connection('go')->select("select * from perfil_vendas where id_cliente = $request->id_cliente and grife = '$request->grife' and status = 1");

            if ($checa) {

               $insere = \DB::connection('go')->select("update perfil_vendas set status = 0 where id_cliente = $request->id_cliente and grife = '$request->grife'");

            }


         }


         $grifes = array();
         $grifes[] = $request->grife;
         $grifes[] = $request->id_cliente;
         $grifes[] = $request->status;


         return response()->json($grifes);

      }

}
