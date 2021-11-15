@extends('produtos/painel/index')

@section('title')
  <i class="fa fa-list"></i> Elastic
@append 

@section('conteudo')


$params = [
    'index' => 'go-jde-vendas',
	"body" => 
[ 
 "size" => 0, 
  "_source"=> [ 
  "No_Item", "Quant_Aberto", "Quant_Cancelada","Quant_Enviada","Quant_Enviada_ToDate", "Quant_Nao_Atendida", "Quantidade_Item" ],
  
    "query" => ["term" => [ "No_Item" => "AT6195I T02" ] ],
    
    "aggs" => [
        "item" => [
             "terms" => [
                 "field" => "No_Item"
             ],
             
    "aggs" => [
        "item" => [
             "terms" => [
                 "field" : "Nome_Diretor"
             ],
             
	"aggs" => [
		"qtde_item" => [
			 "sum" => [
				 "field" => "Quantidade_Item"
					]
				]
			]
 			    	]
  				]
  				 ]
  				]
 ]
]

<?php
$response = $client->search($params);
$resultado = $response["aggregations"]["item"]["buckets"];

//print_r ($resultado);


foreach ($resultado as $vendas) {
	
//	ezcho $troca["key"].' - ';
//	echo $troca["doc_count"].' - ';	
	
	echo '<pre>';
//	print_r($resultado2); //printa todos os dados que estao no array
	echo '</pre>';
		

?>
<html>
<div>
	<a href="principal.php?centro=_painel/catalogodet&agrup=<?=$vendas["key"]?>&ano=2016 ">
		<span class="label label-success disabled"><?=$vendas["key"]?></span></a>
			<?=$vendas["value"]?>
</div>
</html>

<?	}

?>


	
	
@stop