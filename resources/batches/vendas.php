<?php

	ini_set('display_errors', 1);
	ini_set('memory_limit',"512M");
	ini_set('max_execution_time',"3000");

	$conn = mysqli_connect("127.0.0.1", "portalgo", "d6SHzwSu", "go");

    $arquivo = '/var/www/html/portalgo/storage/uploads/vendas.csv';

    if (file_exists($arquivo)) {
        $handle = fopen($arquivo, "r"); 


        $linha = 1;
        $result = mysqli_query($conn, "truncate table go.vendas_jde");

        while (($line = fgetcsv($handle, 100000, ";")) !== FALSE) {

            if ($linha > 2) {           
                
                $num_curto = $line[0];

                if ($num_curto != '') {         

				$query = "INSERT INTO `vendas_jde`( `curto`, `secundario`, `ult_30dd`, `ult_60dd`, `ult_90dd`, `ult_120dd`, `ult_150dd`, `ult_180dd`, `ult_210dd`, `ult_240dd`, `ult_270dd`, `ult_300dd`, `ult_330dd`, `ult_360dd`, `a_180dd`, `vendastt`) VALUES (";

                    foreach ($line as $key => $coluna) {

                        if ($key == 52 or $key == 53 or $key == 54) {
                            if ($coluna == '') {
                                $query .= "0,";
                            } else {
                                $query .= "'$coluna',";
                            }
                        } else {
                            $query .= "'$coluna',";
                        }
                    }

                    $query = substr_replace($query, '', -1);
                    $query .= ")";

                    echo $linha.' '.$line[0]."\n";
                    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

                }

            }   

            $linha++;

        }
    }
