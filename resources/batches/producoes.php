<?php

	ini_set('display_errors', 1);
	ini_set('memory_limit',"512M");
	ini_set('max_execution_time',"3000");

	$conn = mysqli_connect("10.30.210.15", "fabio", "e9pbKUf4", "go");

    $job = mysqli_query($conn, "select source from jobs where id = 7");
    $source = mysqli_fetch_assoc($job);


    $arquivo = '/var/www/html/portalgo/storage/app/'.$source["source"];

    if (file_exists($arquivo)) {
        $handle = fopen($arquivo, "r"); 
		$query21 = "TRUNCATE TABLE `producoes`";
		$result = mysqli_query($conn, $query21) or die(mysqli_error($conn));

        $linha = 1;
//       $result = mysqli_query($conn, "truncate table go.producoes");

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
                $estoque = $line[9];
                $producao = $line[10];
				$modelo_fab = $line[11];
				$status = $line[12];
				$fabrica = $line[13];
                $dt_relatorio = $line[14];
				
				
				if ($fabrica == 'KERING' and strlen($codigo_cor) ==1){
					$codigo_cor = '00'.$codigo_cor;
				}
				else if ($fabrica == 'KERING' and strlen($codigo_cor) ==2){
					$codigo_cor = '0'.$codigo_cor;
				}
				
				
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
				

                $query = "insert into producoes_hist (timestamp,dt_relatorio,numero , arquivo, linha, colecao, pedido, modelo_go, modelo_fab, cor, dt_emissao, dt_entrega, dt_confirmacao, qtd_pedido, qtd_enviada, saldo, estoque, producao, fabrica, cod_sec, status) 
            values ( current_timestamp, '$dt_relatorio','$numero','$arquivo', '$linha', '$colecao', '$pedido',  '$modelo_go','$modelo_fab', '$codigo_cor', '$dt_emissao', '$dt_entrega', '$dt_confirmacao', '$qtd2', '$transito2', '0', '$estoque',  '$producao','$fabrica','$cod_sec','$status' )";
 echo '<br>'. $query;
				
				
                $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
			

            }   

            $linha++;

        }
    }


    ini_set('display_errors', 1);
    ini_set('memory_limit',"512M");
    ini_set('max_execution_time',"3000");
        $producoes = \DB::select("

select id, cod_sec, sum(Producao), sum(Estoque)
from(
select case when id2 is null then '99999999' else id2 end as 'id', cod_sec, Producao, Estoque
from(
select itens.id as id2, cod_sec, sum(estoque) Estoque, sum(producao) Producao, date(timestamp), sum(estoque)+sum(producao) as total
from producoes_hist
left join itens on secundario = cod_sec
where date(producoes_hist.timestamp) = date((select timestamp from producoes_hist order by timestamp desc limit 1))

group by itens.id, cod_sec, timestamp
order by cod_sec
) as base

) as base1
group by id, cod_sec


");

        if (count($producoes) > 0) {
            $total_sql = count($producoes);

				DB::select("truncate table producoes_sint");
            $index = 0;
            foreach ($producoes as $producoes1) {
                $index++;
                $query = "INSERT INTO `producoes_sint`(`id`, `cod_sec`, `producao`, `estoque`) VALUES (";    

                foreach ($producoes1 as $coluna => $valor) {

                    $valor2 = addslashes($valor);
                    $query .= "'$valor2',";

                }        
                $query = substr($query, 0, -1);
                $query .= ')';
                
				

                \DB::insert($query);

            }
            if ($total_sql != $producoes) {
                echo 'erro, contagem nao bate';
            }
        } else {
            echo 'erro';
        }


if (file_exists($arquivo)) {
        $handle = fopen($arquivo, "r"); 
		$query21 = "TRUNCATE TABLE `producoes`";
		$result = mysqli_query($conn, $query21) or die(mysqli_error($conn));

        $linha = 1;
//       $result = mysqli_query($conn, "truncate table go.producoes");

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
                $estoque = $line[9];
                $producao = $line[10];
				$modelo_fab = $line[11];
				$status = $line[12];
				$fabrica = $line[13];
                $dt_relatorio = $line[14];
				
				
				if ($fabrica == 'KERING' and strlen($codigo_cor) ==1){
					$codigo_cor = '00'.$codigo_cor;
				}
				else if ($fabrica == 'KERING' and strlen($codigo_cor) ==2){
					$codigo_cor = '0'.$codigo_cor;
				}
				
				
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
				

                $query31 = "insert into producoes (timestamp,dt_relatorio,numero , arquivo, linha, colecao, pedido, modelo_go, modelo_fab, cor, dt_emissao, dt_entrega, dt_confirmacao, qtd_pedido, qtd_enviada, saldo, estoque, producao, fabrica, cod_sec, status) 
            values ( current_timestamp, '$dt_relatorio','$numero','$arquivo', '$linha', '$colecao', '$pedido',  '$modelo_go','$modelo_fab', '$codigo_cor', '$dt_emissao', '$dt_entrega', '$dt_confirmacao', '$qtd2', '$transito2', '0', '$estoque',  '$producao','$fabrica','$cod_sec','$status' )";
 echo '<br>'. $query31;
				
				
                $result = mysqli_query($conn, $query31) or die(mysqli_error($conn));
			

            }   

            $linha++;

        }

        $producoes_anterior = \DB::select("

select timestamp, dt_relatorio,numero , arquivo, linha, colecao, pedido, modelo_go, modelo_fab, cor, dt_emissao, dt_entrega, dt_confirmacao, qtd_pedido, qtd_enviada, saldo, estoque, producao, fabrica, cod_sec, status
from producoes_hist

where date(producoes_hist.timestamp) = date((select date(timestamp) from producoes_hist where date(timestamp) <> (select date(timestamp) from producoes_hist order by date(timestamp) desc limit 1) order by timestamp desc limit 1))



");

        if (count($producoes_anterior) > 0) {
            $total_sql = count($producoes_anterior);

                DB::select("truncate table producoes_anterior");
            $index = 0;
            foreach ($producoes_anterior as $producoes_anterior1) {
                $index++;
                $query = "insert into producoes_anterior (timestamp, dt_relatorio,numero , arquivo, linha, colecao, pedido, modelo_go, modelo_fab, cor, dt_emissao, dt_entrega, dt_confirmacao, qtd_pedido, qtd_enviada, saldo, estoque, producao, fabrica, cod_sec, status) 
            values (";    

                foreach ($producoes_anterior1 as $coluna => $valor) {

                    $valor2 = addslashes($valor);
                    $query .= "'$valor2',";

                }        
                $query = substr($query, 0, -1);
                $query .= ')';
                
                

                \DB::insert($query);


            }
            if ($total_sql != $producoes_anterior) {
                echo 'erro, contagem nao bate';
            }
        } else {
            echo 'erro';
        }
         app('App\Http\Controllers\CompraController')->enviaatrasos();
         app('App\Http\Controllers\CompraController')->diferencapedidos();
         app('App\Http\Controllers\CompraController')->mudancadataentrega();
         app('App\Http\Controllers\CompraController')->faltacadastro();

    }




        