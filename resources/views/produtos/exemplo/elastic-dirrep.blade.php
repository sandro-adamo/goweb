@extends('produtos/painel/index')

@section('title')
  <i class="fa fa-list"></i> Elastic
@append 

@section('conteudo')

@php

  $item = $_GET["item"];
 
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

  $indice = 'go-jde-vendas';

  $body = '{
                "size": 0, 
  "_source": [ 
  "No_Item", "Quant_Aberto", "Quant_Cancelada","Quant_Enviada","Quant_Enviada_ToDate", "Quant_Nao_Atendida", "Quantidade_Item" ],
    "query" : {
        "term" : { "No_Item" : "'.$item.'" }
        },
        
    "aggs" : {
        "item" : {
             "terms" : {
                 "field" : "No_Item"
             },
             
    "aggs" : {
        "item" : {
             "terms" : {
                 "field" : "Nome_Diretor"
             },
             
    "aggs" : {
        "representante" : {
             "terms" : {
                 "field" : "Razao_Representante",
                "size" : 100
                 
             },
             
    "aggs" : {
        "qtde_item" : {
           "sum" : {
                         "field" : "Quantidade_Item"
        }}}}}}}}}}
';


  
    
      
  $ch1 = curl_init('https://app.lumini360.com.br/v2/go-jde-vendas/_search');                                                                      
  curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "POST");   
  curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);                                                                  
  curl_setopt($ch1, CURLOPT_POSTFIELDS, $body);                                                                  
  curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);                                                                      
  curl_setopt($ch1, CURLOPT_HTTPHEADER, array(                                                                          
      'Content-Type: application/json',                                                                                
      'Authorization: Bearer ' . $token     )                                                                  
  );                                                                                                                   
                                                                                                                       
  $result2 = json_decode(curl_exec($ch1), true);
  $resultado = $result2["aggregations"]["item"]["buckets"][0];
  // echo '<pre>';
  // print_r($resultado);
  // echo '</pre>';
  // die();
   foreach ($resultado["item"]["buckets"] as $valor1) {

  // echo '<pre>';
  // print_r($valor1);
  // echo '</pre>';
  //   die();

    $diretor = $valor1["key"];
    echo '<b>'.$diretor.'</b>';
		foreach ($valor1["representante"]["buckets"] as $valor2){
  
  $key = $valor2["key"];
  $qtde = $valor2["qtde_item"]["value"];
  
  //echo '<pre>';
  //print_r($valor2);
  //echo '</pre>';
  
  //echo $key;
  //echo $qtde;
  
@endphp
  
 

<html>
	<body>
		<table class="table">
			<td width="30%"><small><?php echo $key; ?></small></td>
			<td width="20%"><small><?php echo $qtde; ?></small></td>
		</table>
	</body>
</html>

<?php	   
	   	   	   }
 			}
?>
		
				
@stop