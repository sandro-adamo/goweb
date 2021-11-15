@extends('produtos/painel/index')

@section('title')
  <i class="fa fa-list"></i> Elastic
@append 

@section('conteudo')

@php


  $data = array("accessKey" => "dFq8g8rM89la8Ssrlg701pVaj49/zvtaxFduk9VCRab9uNEyP7Uckz/fuClch5ZrrXJVTlgUvKbHW8lQ3rwDEQ==", "userKey" => "ddd52c72-001c-44f9-b95a-04d1086724e5", "tenant" => "go");                                                                    
  $data_string = json_encode($data);                                                                                   
                                                                                                                       
  $ch = curl_init('https://app.lumini360.com.br/v2/api/login');                                                                      
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");   
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);                                                                  
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
      'Content-Type: application/json',                                                                              
      'Content-Length: ' . strlen($data_string))                                                                       
  );                                                                                                                   
                                                                                                                       
  $result = json_decode(curl_exec($ch), true);

  $token = $result["accessToken"];


  $indice = 'go-jde-itens';

  $body = '{
              "query": {
                "match": {
                  "secundario": "AH6254 A01"
                }
              }
            }';


  
    
      
  $ch1 = curl_init('https://app.lumini360.com.br/v2/go-jde-itens/_search');                                                                      
  curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "POST");   
  curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);                                                                  
  curl_setopt($ch1, CURLOPT_POSTFIELDS, $body);                                                                  
  curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);                                                                      
  curl_setopt($ch1, CURLOPT_HTTPHEADER, array(                                                                          
      'Content-Type: application/json',                                                                                
      'Authorization: Bearer ' . $token     )                                                                  
  );                                                                                                                   
                                                                                                                       
  $result2 = json_decode(curl_exec($ch1), true);
  echo '<pre>';
  print_r($result2);
  echo '</pre>';

@endphp

	
@stop