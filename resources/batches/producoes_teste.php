<?php

	ini_set('display_errors', 1);
	ini_set('memory_limit',"512M");
	ini_set('max_execution_time',"3000");

	$conn = mysqli_connect("127.0.0.1", "portalgo", "@supplych@1n2017@", "go");

    $job = mysqli_query($conn, "select source from jobs where id = 7");
    $source = mysqli_fetch_assoc($job);


    $arquivo = '/var/www/html/portalgo/storage/app/'.$source["source"];

    if (file_exists($arquivo)) {
        $handle = fopen($arquivo, "r"); 

        $linha = 1;
       $result = mysqli_query($conn, "truncate table go.producoes_hist_teste");

        $numero = date('dmYHis');
		
		

        while (($line = fgetcsv($handle, 100000, ";")) !== FALSE) {

            if ($linha > 2) {           


                //if ($_POST["inseretimestamp"] == 1) {
                  //  $timestamp = $_POST["timestamp"];
                //} else {
                    $timestamp = date("Y-m-d H:i:s");
                //}

                $colecao = $line[0];          
                $pedido = $line[1];
                $modelo_go = $line[2];
                $codigo_cor = $line[3];
                $dt_emissao = date("Y-m-d",strtotime(str_replace('/','-',$line[4])));
                
                $dt_entrega = date("Y-m-d",strtotime(str_replace('/','-',$line[5])));
        //      $dt_entrega = $sheetData[$i]["H"];
                $dt_confirmacao = date("Y-m-d",strtotime(str_replace('/','-',$line[6])));
        //      $dt_confirmacao = $sheetData[$i]["I"];
                $qtd = $line[7];
                $transito = $line[8];
                $saldo = $line[9];
                $estoque = $line[11];
                $producao = $line[10];
                $dt_relatorio = $line[13];
                $fabrica = $line[14];
                if ($fabrica == 'KERING') {
                    $cod_sec = $modelo_go.'-'.$codigo_cor;
                } else {
                    $cod_sec = $modelo_go.' '.$codigo_cor;
                }
                 if ($qtd ==''){
					 $qtd2 ='0';
				 }   else{
					 $qtd2 = $qtd;
				 }
				
				if ($transito ==''){
					 $transito2 ='0';
				 }   else{
					 $transito2 = $transito;
				 }
                $numero = date("dmYHm");

                $query = "insert into producoes_hist_teste (timestamp,dt_relatorio,numero , arquivo, linha, colecao, pedido, modelo_go, modelo_fab, cor, dt_emissao, dt_entrega, dt_confirmacao, qtd_pedido, qtd_enviada, saldo, estoque, producao, fabrica, cod_sec) 
            values ( current_timestamp, '$dt_relatorio','$numero','$arquivo', '$linha', '$colecao', '$pedido',  '$modelo_go','-', '$codigo_cor', '$dt_emissao', '$dt_entrega', '$dt_confirmacao', '$qtd2', '$transito2', '$saldo', '$estoque',  '$producao','$fabrica','$cod_sec' )";
 echo $query;
                $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
			

            }   

            $linha++;

        }
    }


//if (file_exists($arquivo)) {
//        $handle = fopen($arquivo, "r"); 
//
//
//        $linha = 1;
//       $result = mysqli_query($conn, "truncate table go.producoes_sint");
//
//        $numero = date('dmYHis');
//		
//		
//
//        while (($line = fgetcsv($handle, 100000, ";")) !== FALSE) {
//
//            if ($linha > 2) {           
//
//
//                //if ($_POST["inseretimestamp"] == 1) {
//                  //  $timestamp = $_POST["timestamp"];
//                //} else {
//                    $timestamp = date("Y-m-d H:i:s");
//                //}
//
//                $modelo_go = $line[2];
//                $codigo_cor = $line[3];
//                $estoque = $line[11];
//                $producao = $line[10];
//                $fabrica = $line[14];
//                if ($fabrica == 'KERING') {
//                    $cod_sec = $modelo_go.'-'.$codigo_cor;
//                } else {
//                    $cod_sec = $modelo_go.' '.$codigo_cor;
//                }
//				
//                $job2 = mysqli_query($conn, "select id from itens where secundario = '$cod_sec'");
//   				$result = mysqli_fetch_assoc($job2);
//				$iditem = $result["id"];                
//				
//                $query = "
//				INSERT INTO `producoes_sint`(id, `cod_sec`, `producao`, `estoque`) VALUES (
//				'$iditem', '$cod_sec',  '$producao','$estoque')";
// echo $query;
//                $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
//			
//
//            }   
//
//            $linha++;
//
//        }
//    }


   
