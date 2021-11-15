<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Atendimento;

class AtendimentoController extends Controller
{
    
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

         $novo->dt_inicio = date('Y-m-d H:i:s');
   		$novo->contato = $request->contato;
   		$novo->telefone = $request->telefone;
   		$novo->origem = 'whatsapp';
   		$novo->save();

         if (isset($request->id_cliente)) {

            $checa_cliente = \App\AddressBook::find($request->id_cliente);
            if ($checa_cliente) {
               $novo->id_cliente = $request->id_cliente;

               $requerente = new \App\AtendimentoUsuario();
               $requerente->id_atendimento = $novo->id;
               $requerente->id_addressbook = $checa_cliente->id;
               $requerente->status =1;
               $requerente->tipo = 1;
               $requerente->nome_alternativo = $checa_cliente->razao;
               $requerente->email_alternativo = $checa_cliente->email1;
               $requerente->dt_inicio = date('Y-m-d');
               $requerente->hora_inicio = date('H:i:s');
               $requerente->save();
            }
         }
         $novo->save();

   		return response()->json($novo);



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
               return response()->json($atendimento);
            }

            if ($request->message_id && $request->message_uid) {

               $atendimento->message_id = $request->message_id;
               $atendimento->message_uid = $request->message_uid;
               $atendimento->save();
               return response()->json($atendimento);
            }

         } else {
            return response()->json("error");
         }


      }



      public function addMessage(Request $request) {

         $novo = new \App\BlipMessage(); 

         $novo->identifier = $request->identifier;
         $novo->message_id = $request->message_id;
         $novo->message_from = $request->message_from;
         $novo->message_to = $request->message_to;
         $novo->message_type = $request->message_type;

         $novo->message_content = $request->message_content;
         $novo->contact_name = $request->contact_name;
         $novo->state_name = $request->state_name;
         $novo->state_previous_name = $request->state_previous_name;
         $novo->save();
 

      }

   	public function addHistorico(Request $request, $id) {

   		$novo = new \App\AtendimentoHistorico(); 

   		$novo->id_usuario = 0;
   		$novo->id_atendimento = $id;
         $novo->data = date('Y-m-d H:i:s');
         $novo->tipo = 'chatbot';
         $novo->origem = 'whatsapp';
         $novo->contato = $request->contato;
         $novo->telefone = $request->telefone;
   		$novo->categoria = 'conversa';
   		$novo->historico = $request->historico;
         $novo->message_type = $request->message_type;
   		$novo->save();
 

   	}


      public function getAttendants() {

         $key = 'Key Z29leWV3ZWFycm91dGVyOm9HcmtFUXZkb2xyb056eDJ3VWFX';
         $bot_id = 'bd76bf56-5eb4-4882-9db4-7f0409d9f991';


         // GET TICKET 
         $body = '{
                    "id": "043f0eec-a0bc-4e6d-b4e5-4ea2639ba36a",
                    "to": "postmaster@desk.msging.net",
                    "method": "get",
                    "uri": "/attendants"
                  }';
         // consulta api ticket
         $ch1 = curl_init('https://http.msging.net/commands');                                                                      
         curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "POST");   
         curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);                                                                  
         curl_setopt($ch1, CURLOPT_POSTFIELDS, $body);                                                                  
         curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);                                                                      
         curl_setopt($ch1, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json',                                                                                
            'Authorization: '.$key,                                                                                
         ));                                                                                                                   
                                                                                                                             
         $result =  curl_exec($ch1);
         $array =  json_decode($result,TRUE);    



      }


      public function finalizaTicket(Request $request, $id) {

         $key = 'Key Z29leWV3ZWFycm91dGVyOm9HcmtFUXZkb2xyb056eDJ3VWFX';
         $bot_id = 'bd76bf56-5eb4-4882-9db4-7f0409d9f991';

         $atendimento = \App\Atendimento::find($id);

         if ($atendimento) {


            // GET TICKET 
            $body = '{
                       "id": "043f0eec-a0bc-4e6d-b4e5-4ea2639ba36a",
                       "to": "postmaster@desk.msging.net",
                       "method": "get",
                       "uri": "/ticket/'.$atendimento->message_id.'"
                     }';
            // consulta api ticket
            $ch1 = curl_init('https://http.msging.net/commands');                                                                      
            curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "POST");   
            curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);                                                                  
            curl_setopt($ch1, CURLOPT_POSTFIELDS, $body);                                                                  
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);                                                                      
            curl_setopt($ch1, CURLOPT_HTTPHEADER, array(                                                                          
               'Content-Type: application/json',                                                                                
               'Authorization: '.$key,                                                                                
            ));                                                                                                                   
                                                                                                                                
            $result =  curl_exec($ch1);
            $array =  json_decode($result,TRUE);    

            $customerIdentity = $array["resource"]["customerIdentity"]; 
            $storageDate = $array["resource"]["storageDate"]; 

            if (isset($array["status"]) && $array["status"] == 'success') {


               // GET TUNNEL 
               $body_tunnel = '{
                          "id": "bd76bf56-5eb4-4882-9db4-7f0409d9f991",
                          "to": "postmaster@tunnel.msging.net",
                          "method": "get",
                          "uri": "/tunnels/'.$customerIdentity.'"
                        }';
               // consulta api ticket
               $ch2 = curl_init('https://http.msging.net/commands');                                                                      
               curl_setopt($ch2, CURLOPT_CUSTOMREQUEST, "POST");   
               curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);                                                                  
               curl_setopt($ch2, CURLOPT_POSTFIELDS, $body_tunnel);                                                                  
               curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);                                                                      
               curl_setopt($ch2, CURLOPT_HTTPHEADER, array(                                                                          
                  'Content-Type: application/json',                                                                                
                  'Authorization: '.$key,                                                                                
               ));                                                                                                                   
                                                                                                                                   
               $result_tunnel =  curl_exec($ch2);
               $array_tunnel =  json_decode($result_tunnel,TRUE);    

               if ($array_tunnel["status"] == 'success') {
                  $originator = $array_tunnel["resource"]["originator"];
               }

            }          



            $body_thread = array(
                     "id" => "bd76bf56-5eb4-4882-9db4-7f0409d9f991",
                     "method" => "get",
                     "uri" => '/threads/'.$originator.'?$take=100&storageDate='.$storageDate.'&direction=asc'
            );

            $threads = curl_init('https://http.msging.net/commands');                                                                      
            curl_setopt($threads, CURLOPT_CUSTOMREQUEST, "POST");   
            curl_setopt($threads, CURLOPT_SSL_VERIFYPEER, false);                                                                  
            curl_setopt($threads, CURLOPT_POSTFIELDS, json_encode($body_thread));                                                                  
            curl_setopt($threads, CURLOPT_RETURNTRANSFER, true);                                                                      
            curl_setopt($threads, CURLOPT_HTTPHEADER, array(                                                                          
               'Content-Type: application/json',                                                                                
               'Authorization: '.$key,                                                                                
            ));                                                                                                                   
                                                                                                                                
            $threads =  json_decode(curl_exec($threads));

            if ($threads->status == 'success') {

               $id_usuario = 0;
               $id_grupo = 0;
               $contato = 'ChatBOT';
               $email = '';
               $tipo = 'chatbot';
               $message_uid = '';
               $message_id = '';


               $total = $threads->resource->total;

               $tickets = array();
               $tags = array();

               // INSERE HISTORICO

               foreach ($threads->resource->items as $item) {
                  $datetime = new \DateTime($item->date);
                  $datetime->setTimezone(new \DateTimeZone('America/Sao_Paulo'));
                  $data = $datetime->format('Y-m-d');
                  $hora = $datetime->format('H:i:s');
                  $datahora = $datetime->format('Y-m-d H:i:s');


                  if ($item->type == "application/vnd.iris.ticket+json") {

                     $tickets[] = $item->content->id;

                  }

                  $checa_historico = \App\AtendimentoHistorico::where('message_id', $item->id)->first();

                  if (!$checa_historico) {

                     $atendimento_historico = new \App\AtendimentoHistorico();
                     $atendimento_historico->id_atendimento = $atendimento->id;
                     $atendimento_historico->data = $datahora;
                     $atendimento_historico->origem = 'whatsapp';
                     $atendimento_historico->interno = 0;
                     $atendimento_historico->interno = 0;


                     if ($item->type == "application/vnd.iris.ticket+json") {
   
                        $atendimento_historico->tipo = 'ticket';
                        $atendimento_historico->historico = json_encode($item->content);

                     } 

                     if ($item->type == 'application/vnd.lime.media-link+json') {
                        //dd($item);

                        $atendimento_historico->tipo = 'link';
                        $atendimento_historico->historico = json_encode($item->content);


                     }

                     if ($item->type == 'text/plain') {
   

                        $atendimento_historico->tipo = 'interacao';


                        if ($item->direction == 'sent') {                 
                           $atendimento_historico->contato = $contato;
                           $atendimento_historico->email = $email;
                           $atendimento_historico->telefone = '';
                        }

                        if ($item->direction == 'received') {                 
                           $atendimento_historico->contato = $atendimento->contato;
                           $atendimento_historico->email = $atendimento->email;
                           $atendimento_historico->telefone = $atendimento->telefone;
                        }
                        $atendimento_historico->historico = $item->content;


                     }

                     if (isset($item->content->sequentialId)) {
                        $atendimento_historico->message_uid = $item->content->sequentialId;
                     }

                     if (isset($item->status)) {
                        $atendimento_historico->message_status = $item->status;
                     }

                     if (isset($item->id)) {
                        $atendimento_historico->message_id = $item->id;
                     }

                     if (isset($item->direction)) {
                        $atendimento_historico->message_direction = $item->direction;
                     }
                                       
                     if (isset($item->date)) {
                        $atendimento_historico->message_date = $datahora;
                     }

                     if (isset($item->type)) {
                        $atendimento_historico->message_type = $item->type;
                     }

                     $atendimento_historico->message_json = json_encode($item);

                     // if (isset($item->metadata->#stateName")) {
                     //    $atendimento_historico->bot_state = $item->metadata->"#stateName";
                     // }

                     // if (isset($item->metadata->#previousStateName)) {
                     //    $atendimento_historico->bot_previous_state = $item->metadata->#previousStateName;
                     // }

                     // if (isset($item->metadata->$originator)) {
                     //    $atendimento_historico->message_account = $item->metadata->$originator;
                     // }


                     $atendimento_historico->save();

                  }






               }

               // PESQUISA TICKETS

               foreach ($tickets as $ticket_id) {

                  $body_ticket = '{
                             "id": "043f0eec-a0bc-4e6d-b4e5-4ea2639ba36a",
                             "to": "postmaster@desk.msging.net",
                             "method": "get",
                             "uri": "/ticket/'.$ticket_id.'"
                           }';

                  $ticket = curl_init('https://http.msging.net/commands');                                                                      
                  curl_setopt($ticket, CURLOPT_CUSTOMREQUEST, "POST");   
                  curl_setopt($ticket, CURLOPT_SSL_VERIFYPEER, false);                                                                  
                  curl_setopt($ticket, CURLOPT_POSTFIELDS, $body_ticket);                                                                  
                  curl_setopt($ticket, CURLOPT_RETURNTRANSFER, true);                                                                      
                  curl_setopt($ticket, CURLOPT_HTTPHEADER, array(                                                                          
                     'Content-Type: application/json',                                                                                
                     'Authorization: '.$key,                                                                                
                  ));                                                                                                                   
                                                                                                                                      
                  $ticket =  json_decode(curl_exec($ticket));

                  if ($ticket->status == 'success') {

                     $agente = $ticket->resource->agentIdentity;
                     $time = $ticket->resource->team;

                     $checa_ticket = \App\AtendimentoUsuario::where('id_atendimento',$atendimento->id)->where('ticket_id', $ticket_id)->first();

                     if (!$checa_ticket) {

                        $responsavel = new \App\AtendimentoUsuario();

                        $responsavel->id_atendimento = $atendimento->id;

                        $checa_atendente = \App\BlipAttendant::where('identity', $agente)->first();
                        if ($checa_atendente) {
                           $checa_usuario = \App\AtendimentoAtendente::where('blip_id', $agente)->first();
      

                           if ($checa_usuario) {
                              $id_usuario = $checa_atendente->id;
                              $responsavel->id_usuario = $checa_atendente->id;
                           }

                           $responsavel->email_alternativo = $checa_atendente->email;
                           $responsavel->nome_alternativo = $checa_atendente->fullname;
                        } else {
                           //$novo_responsavel->id_usuario = $checa_atendente->id;
                           $responsavel->email_alternativo = $agente;
                           $responsavel->nome_alternativo = $agente;                  
                        }

                        $checa_grupo = \App\AtendimentoGrupo::where('nome', $time)->first();
                        if($checa_grupo) {
                           $responsavel->id_grupo = $checa_grupo->id;
                           $id_grupo = $checa_grupo->id;
                        }
                        $responsavel->grupo = $time;


                        $openDate = new \DateTime($ticket->resource->openDate);
                        $openDate->setTimezone(new \DateTimeZone('America/Sao_Paulo'));
                        $dt_inicio = $openDate->format('Y-m-d');
                        $hora_inicio = $openDate->format('H:i:s');

                        $closeDate = new \DateTime($ticket->resource->closeDate);
                        $closeDate->setTimezone(new \DateTimeZone('America/Sao_Paulo'));
                        $dt_fim = $closeDate->format('Y-m-d');
                        $hora_fim = $closeDate->format('H:i:s');

                        $responsavel->tipo = 3;
                        $responsavel->status = 1;

                        $responsavel->dt_inicio = $dt_inicio;
                        $responsavel->hora_inicio = $hora_inicio;
                        $responsavel->dt_fim = $dt_fim;
                        $responsavel->hora_fim = $hora_fim;

                        $responsavel->ticket_id = $ticket->resource->id;
                        $responsavel->ticket_number = $ticket->resource->sequentialId;
                        $responsavel->ticket_status = $ticket->resource->status;
                        $responsavel->ticket_date = $ticket->resource->storageDate;
                        if (isset($ticket->resource->parentSequentialId)) {
                           $responsavel->ticket_parent = $ticket->resource->parentSequentialId;
                        }
                        $responsavel->save();

                     }




                     if (isset($ticket->resource->tags)) {

                        foreach ($ticket->resource->tags as $tag) {
                           $tags[] = $tag;  
                        }

                     }


                  }
                     // usuarios (id, sequentialId, storageDate, status, team, tags)

                     // motivos


               }
            }


            if (count($tags) > 0) {
               
               foreach ($tags as $tag) {

                  $checa_tag = \App\AtendimentoMotivo::where('id_atendimento', $atendimento->id)->where('motivo', $tag)->first();

                  if (!$checa_tag) {

                     $motivo = \App\Motivo::where('descricao', $tag)->first();

                     if ($motivo) {

                        $novo_motivo = new \App\AtendimentoMotivo();
                        $novo_motivo->id_atendimento = $atendimento->id;
                        $novo_motivo->id_motivo = $motivo->id;
                        $novo_motivo->motivo = $motivo->descricao;
                        $novo_motivo->save();

                     } else {

                        $novo_motivo = new \App\AtendimentoMotivo();
                        $novo_motivo->id_atendimento = $atendimento->id;
                        $novo_motivo->motivo = $tag;
                        $novo_motivo->save();                 
                     
                     }

                  }

               }       
            }


            $atendimento->id_usuario = $id_usuario;
            $atendimento->id_grupo = $id_grupo;
            $atendimento->status = 'Finalizado';
            $atendimento->dt_fim = date('Y-m-d H:i:s');
            $atendimento->save();



         }
        

      }

      public function finali2zaTicket(Request $request, $id) {

         $key = 'Key Z29leWV3ZWFycHJvZHVjYW86RVhxdmhjZHMzMFZKbEc4ZWZhWGI=';
         $bot_id = 'bd76bf56-5eb4-4882-9db4-7f0409d9f991';

         $atendimento = \App\Atendimento::find($id);

         if ($atendimento) {


            // $team = $array["resource"]["team"];
            // $grupo = \App\AtendimentoGrupo::where('nome', $team)->first();
            // if ($grupo) {
            //    $atendimento->id_grupo = $grupo->id;
            // }

            // $email_atendente = $array["resource"]["agentIdentity"];
            // $atendente = \App\AtendimentoAtendente::where('blip_id', $email_atendente)->first();

            // if ($atendente) {

            //    $checa_usuario = \App\AtendimentoUsuario::where('id_atendimento', $atendimento->id)->where('tipo',3)->where('id_usuario', $atendente->id)->first();
            //    if ($checa_usuario) {

            //    } else {

            //       $novo_responsavel = new \App\AtendimentoUsuario();
            //       $novo_responsavel->id_atendimento = $atendimento->id;
            //       $novo_responsavel->id_usuario = $atendente->id;
            //       if($grupo) {
            //          $novo_responsavel->id_grupo = $grupo->id;
            //       }
            //       $novo_responsavel->tipo = 3;
            //       $novo_responsavel->status = 1;
            //       $openDate = explode('T',$array["resource"]["openDate"]);
            //       $closeDate = explode('T',$array["resource"]["closeDate"]);
            //       $novo_responsavel->dt_inicio = $openDate[0];
            //       $novo_responsavel->hora_inicio = explode('.',$openDate[1])[0];
            //       $novo_responsavel->dt_fim = $closeDate[0];
            //       $novo_responsavel->hora_fim = explode('.',$closeDate[1])[0];
            //       $novo_responsavel->email_alternativo = $atendente->email;
            //       $novo_responsavel->nome_alternativo = $atendente->nome;
            //       $novo_responsavel->save();

            //    }

            //    $atendimento->id_usuario = $atendente->id;

            // }

            // if (isset($array["resource"]["tags"]) && count($array["resource"]["tags"]) > 0) {
            //    foreach ($array["resource"]["tags"] as $linha) {
            //       $motivo = \App\Motivo::where('descricao', $linha)->first();
            //       if ($motivo) {
            //          $checa_motivo = \App\AtendimentoMotivo::where('id_atendimento', $atendimento->id)->where('id_motivo', $motivo->id)->first();
            //          if (!$checa_motivo) {
                        
            //             $novo_motivo = new \App\AtendimentoMotivo();
            //             $novo_motivo->id_atendimento = $atendimento->id;
            //             $novo_motivo->id_motivo = $motivo->id;
            //             $novo_motivo->motivo = $motivo->descricao;
            //             $novo_motivo->save();

            //          }

            //       } else {

            //          $novo_motivo = new \App\AtendimentoMotivo();
            //          $novo_motivo->id_atendimento = $atendimento->id;
            //          $novo_motivo->motivo = $linha;
            //          $novo_motivo->save();                 
            //       }
            //    }
            // }

            // $atendimento->status = 'Finalizado';
            // $atendimento->dt_fim = date('Y-m-d H:i:s');
            // $atendimento->save();

            $body = '{
                       "id": "$bot_id",
                       "to": "postmaster@desk.msging.net",
                       "method": "get",
                       "uri": "/tickets/'.$atendimento->message_id.'/messages?$ascending=true&$take=100&$skip=0"
                     }';
            // consulta api ticket
            $ch1 = curl_init('https://http.msging.net/commands');                                                                      
            curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "POST");   
            curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);                                                                  
            curl_setopt($ch1, CURLOPT_POSTFIELDS, $body);                                                                  
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);                                                                      
            curl_setopt($ch1, CURLOPT_HTTPHEADER, array(                                                                          
               'Content-Type: application/json',                                                                                
               "Authorization: $key",                                                                                
            ));                                                                                                                   
                                                                                                                                
            $result =  curl_exec($ch1);
            $array =  json_decode($result,TRUE);

            $contato = 'ChatBOT';
            $email = '';
            $tipo = 'chatbot';
            $message_uid = '';
            $message_id = '';

            $total = $array["resource"]["total"];
            $tickets = array();

            foreach ($array["resource"]["items"] as $linha) {
            //for ($i=$total;$i>0;$i--) {
              // $linha = $array["resource"]["items"][$i-1];

               if (isset($linha["content"]['sequentialId'])) {
                  $contato = 'Human';
                  $tipo = 'atendimento';
                  $email = '';
                  $message_uid = $linha["content"]['sequentialId'];
                  $message_id = $linha["content"]['id'];
                  $tickets[] = $linha["content"]['id'];

               }
               
               if (!is_array($linha["content"])) {

                  $checa_historico = \App\AtendimentoHistorico::where('message_id', $linha["id"])->first();

                  if (!$checa_historico) {

                     $atendimento_historico = new \App\AtendimentoHistorico();
                     $atendimento_historico->id_atendimento = $atendimento->id;
                     $atendimento_historico->data = explode('T', $linha["date"])[0] . ' ' . explode('.', explode('T', $linha["date"])[1])[0];
                     $atendimento_historico->tipo = $tipo;
                     $atendimento_historico->origem = 'whatsapp';
                     $atendimento_historico->interno = 0;

                     if ($linha["direction"] == 'sent') {                 
                        $atendimento_historico->contato = $contato;
                        $atendimento_historico->email = $email;
                        $atendimento_historico->telefone = '';
                     }

                     if ($linha["direction"] == 'received') {                 
                        $atendimento_historico->contato = $atendimento->contato;
                        $atendimento_historico->email = $atendimento->email;
                        $atendimento_historico->telefone = $atendimento->telefone;
                     }

                     //if ($message_id <> '') {
                        //$atendimento_historico->message_id = $message_id;
                     //}

                     if ($message_id <> '') {
                        $atendimento_historico->message_uid = $message_uid;
                     }

                     $atendimento_historico->historico = $linha["content"];
                     
                     if (isset($linha["id"])) {
                        $atendimento_historico->message_id = $linha["id"];
                     }

                     if (isset($linha["direction"])) {
                        $atendimento_historico->message_direction = $linha["direction"];
                     }
                                       
                     if (isset($linha["date"])) {
                        $atendimento_historico->message_date = explode('T', $linha["date"])[0] . ' ' . explode('.', explode('T', $linha["date"])[1])[0];
                     }

                     if (isset($linha["type"])) {
                        $atendimento_historico->message_type = $linha["type"];
                     }

                     if (isset($linha["metadata"]['#stateName'])) {
                        $atendimento_historico->bot_state = $linha["metadata"]['#stateName'];
                     }

                     if (isset($linha["metadata"]['#previousStateName'])) {
                        $atendimento_historico->bot_previous_state = $linha["metadata"]['#previousStateName'];
                     }

                     if (isset($linha["metadata"]['$originator'])) {
                        $atendimento_historico->message_account = $linha["metadata"]['$originator'];
                     }


                     $atendimento_historico->save();

                  }

               } else {
                  $tipo = 'humano';
               }

            }

            $id_usuario = 0;
            $id_grupo = 0;

            $tags = array();
            foreach ($tickets as $ticket) {

               $body = '{
                          "id": "043f0eec-a0bc-4e6d-b4e5-4ea2639ba36a",
                          "to": "postmaster@desk.msging.net",
                          "method": "get",
                          "uri": "/ticket/'.$ticket.'"
                        }';
               // consulta api ticket
               $ch1 = curl_init('https://http.msging.net/commands');                                                                      
               curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "POST");   
               curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);                                                                  
               curl_setopt($ch1, CURLOPT_POSTFIELDS, $body);                                                                  
               curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);                                                                      
               curl_setopt($ch1, CURLOPT_HTTPHEADER, array(                                                                          
                  'Content-Type: application/json',                                                                                
                  'Authorization: Key dmVyc2FvMzp2bnRzVkNSa0xhOHFOS24zcjZ1UQ==',                                                                                
               ));                                                                                                                   
                                                                                                                                   
               $result =  curl_exec($ch1);
               $array =  json_decode($result,TRUE);               
//dd($array);
               $ticket_id = $array["resource"]["id"];
               $agente = $array["resource"]["agentIdentity"];
               $time = $array["resource"]["team"];

               $checa_ticket = \App\AtendimentoUsuario::where('id_atendimento',$atendimento->id)->where('ticket_id', $ticket_id)->first();

               if (!$checa_ticket) {

                  $responsavel = new \App\AtendimentoUsuario();

                  $responsavel->id_atendimento = $atendimento->id;

                  $checa_atendente = \App\AtendimentoAtendente::where('blip_id', $agente)->first();

                  if ($checa_atendente) {
                     $responsavel->id_usuario = $checa_atendente->id;
                     $responsavel->email_alternativo = $checa_atendente->email;
                     $responsavel->nome_alternativo = $checa_atendente->nome;
                     $id_usuario = $checa_atendente->id;
                  } else {
                     //$novo_responsavel->id_usuario = $checa_atendente->id;
                     $responsavel->email_alternativo = $agente;
                     $responsavel->nome_alternativo = $agente;                  
                  }

                  $checa_grupo = \App\AtendimentoGrupo::where('nome', $time)->first();
                  if($checa_grupo) {
                     $responsavel->id_grupo = $checa_grupo->id;
                     $id_grupo = $checa_grupo->id;
                  }
                  $responsavel->grupo = $time;

                  $responsavel->tipo = 3;
                  $responsavel->status = 1;
                  $openDate = explode('T',$array["resource"]["openDate"]);
                  $closeDate = explode('T',$array["resource"]["closeDate"]);
                  $responsavel->dt_inicio = $openDate[0];
                  $responsavel->hora_inicio = explode('.',$openDate[1])[0];
                  $responsavel->dt_fim = $closeDate[0];
                  $responsavel->hora_fim = explode('.',$closeDate[1])[0];
                  $responsavel->ticket_id = $array["resource"]["id"];
                  $responsavel->ticket_number = $array["resource"]["sequentialId"];
                  $responsavel->ticket_status = $array["resource"]["status"];
                  $responsavel->save();

               }


               if (isset($array["resource"]["tags"])) {

                  foreach ($array["resource"]["tags"] as $tag) {
                     $tags[] = $tag;  
                  }

               }
               echo '<br>';
            }

            if (count($tags) > 0) {
               
               foreach ($tags as $tag) {

                  $checa_tag = \App\AtendimentoMotivo::where('id_atendimento', $atendimento->id)->where('motivo', $tag)->first();

                  if (!$checa_tag) {

                     $motivo = \App\Motivo::where('descricao', $tag)->first();

                     if ($motivo) {

                        $novo_motivo = new \App\AtendimentoMotivo();
                        $novo_motivo->id_atendimento = $atendimento->id;
                        $novo_motivo->id_motivo = $motivo->id;
                        $novo_motivo->motivo = $motivo->descricao;
                        $novo_motivo->save();

                     } else {

                        $novo_motivo = new \App\AtendimentoMotivo();
                        $novo_motivo->id_atendimento = $atendimento->id;
                        $novo_motivo->motivo = $tag;
                        $novo_motivo->save();                 
                     
                     }

                  }

               }       
            }


            $atendimento->id_usuario = $id_usuario;
            $atendimento->id_grupo = $id_grupo;
            $atendimento->status = 'Finalizado';
            $atendimento->dt_fim = date('Y-m-d H:i:s');
            $atendimento->save();

            dd($tags);
            return response()->json($array);


         }
         // consulta api ticket messages

         // fecha ticket


      }
}
