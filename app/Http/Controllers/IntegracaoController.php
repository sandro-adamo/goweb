<?php

namespace App\Http\Controllers;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\Request;
use Elasticsearch;
use SoapClient;
use SoapVar;
use SoapHeaders;

class IntegracaoController extends Controller
{
   
    

 

    public function consultaSaldo($referencia) {

        function AddWSSUsernameToken($client, $username, $password)
        {
            $wssNamespace = "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd";

            $username = new SoapVar($username, 
                XSD_STRING, 
                null, null, 
                'Username', 
                $wssNamespace);

            $password = new SoapVar($password, 
                XSD_STRING, 
                null, null, 
                'Password', 
                $wssNamespace);

            $usernameToken = new SoapVar(array($username, $password), 
                SOAP_ENC_OBJECT, 
                null, null, 'UsernameToken', 
                $wssNamespace);

            $usernameToken = new SoapVar(array($usernameToken), 
                SOAP_ENC_OBJECT, 
                null, null, null, 
                $wssNamespace);

            $wssUsernameTokenHeader = new \SoapHeader($wssNamespace, 'Security', $usernameToken);

            $client->__setSoapHeaders($wssUsernameTokenHeader); 
        }   

        $url_inventory = "https://189.125.137.62:443/PD920/InventoryManager?WSDL"; 

        $context = stream_context_create([
            'ssl' => [
                // set some SSL/TLS specific options
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ]);
        
        //$url        = "http://189.125.137.61:7161/PY920/ItemServiceManager?wsdl"; 
        $client     = new SoapClient($url_inventory, array("trace" => 1, 'encoding'=>'ISO-8859-1', 'stream_context' => $context)); 
        AddWSSUsernameToken($client, 'KSILVA', 'Ninecon2017');


        $result = $client->getCalculatedAvailability(array( 
                                                        'branchPlantList'=>'01020000',
                                                        'itemSecond'=> $referencia,
                                                        'omitZeroQty'=>''
                                                    )); 
       
        return response()->json($result);

    }

    public function alteraCaracterisca() {


        function AddWSSUsernameToken($client, $username, $password)
        {
            $wssNamespace = "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd";

            $username = new SoapVar($username, 
                XSD_STRING, 
                null, null, 
                'Username', 
                $wssNamespace);

            $password = new SoapVar($password, 
                XSD_STRING, 
                null, null, 
                'Password', 
                $wssNamespace);

            $usernameToken = new SoapVar(array($username, $password), 
                SOAP_ENC_OBJECT, 
                null, null, 'UsernameToken', 
                $wssNamespace);

            $usernameToken = new SoapVar(array($usernameToken), 
                SOAP_ENC_OBJECT, 
                null, null, null, 
                $wssNamespace);

            $wssUsernameTokenHeader = new SoapHeader($wssNamespace, 'Security', $usernameToken);

            $client->__setSoapHeaders($wssUsernameTokenHeader); 
        }   


        $context = stream_context_create([
            'ssl' => [
                // set some SSL/TLS specific options
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ]);
        
        $url        = "http://189.125.137.61:7161/PY920/ItemServiceManager?wsdl"; 
        $client     = new SoapClient($url, array("trace" => 1, 'encoding'=>'ISO-8859-1', 'stream_context' => $context)); 
        AddWSSUsernameToken($client, 'KSILVA', 'Ninecon2017');


        $result = $client->itemUpdate( array( 
             "genero"=>"2018",
             "anomod"=>"2018",
             "codItemCurto"=>"10658",
             "codstatusatutal" => "ESGOT"
        ));

        echo "REQUEST:\n<pre>" . htmlentities($client->__getLastRequest()) . "</pre>\n";

        //echo $xml->asXML(); // <?xml ... <a><b><c>text</c><c>stuff</c> ...

        //      print_r($result);

    }


    public function listaIntegracoes() {

    	$jobs = \App\Job::all();

		
		

		$atualizacaobase = \DB::select("
		select tabela,  registros,  inicio, fim, tempo, Data_hora_atualizacao
		from(
		
		
		
		select 'orcamentos' as tabela, count(*) as registros, min(created_at) as inicio, max(created_at) as fim, timediff(max(created_at), min(created_at)) as tempo, 'Diario 19h' as 'Data_hora_atualizacao'
from orcamentos

union all

select 'itens' as tabela, count(*) as registros, min(created_at) as inicio, max(created_at) as fim, timediff(max(created_at), min(created_at)) as tempo, 'Diario 19h10' as 'Data_hora_atualizacao'
from itens
union all
select 'saldos' as tabela, count(*) as registros, min(created_at) as inicio, max(created_at) as fim, timediff(max(created_at), min(created_at)) as tempo, 'Diario 19h40' as 'Data_hora_atualizacao'
from saldos

union all
select 'mostruarios' as tabela, count(*) as registros, min(created_at) as inicio, max(created_at) as fim, timediff(max(created_at), min(created_at)) as tempo, 'Quinta 10h20' as 'Data_hora_atualizacao'
from mostruarios

union all
select 'addressbook' as tabela, count(*) as registros, min(created_at) as inicio, max(created_at) as fim, timediff(max(created_at), min(created_at)) as tempo, 'Diario 10h20' as 'Data_hora_atualizacao'
from addressbook

union all
select 'f0005' as tabela, count(*) as registros, min(created_at) as inicio, max(created_at) as fim, timediff(max(created_at), min(created_at)) as tempo, 'Quinta 21h20' as 'Data_hora_atualizacao'
from caracteristicas

union all
select 'vendas_jde' as tabela, count(*) as registros, min(created_at) as inicio, max(created_at) as fim, timediff(max(created_at), min(created_at)) as tempo, 'Diario 21h40' as 'Data_hora_atualizacao'
from vendas_jde

union all
select 'vendas_analiticas_sinteticas' as tabela, count(*) as registros, min(created_at) as inicio, max(created_at) as fim, timediff(max(created_at), min(created_at)) as tempo, 'Diario 22h20' as 'Data_hora_atualizacao'
from vendas_sint





union all
select 'vendas_12meses' as tabela, count(*) as registros, min(dt_criacao) as inicio, max(dt_criacao) as fim, timediff(max(dt_criacao), min(dt_criacao)) as tempo, 'Diario 00h20' as 'Data_hora_atualizacao'
from vendas_12meses

union all
select 'carteira' as tabela, count(*) as registros, min(created_at) as inicio, max(created_at) as fim, timediff(max(created_at), min(created_at)) as tempo, 'Diario 23h30' as 'Data_hora_atualizacao'
from carteira

union all
select 'orcamentos_anal' as tabela, count(*) as registros, min(created_at) as inicio, max(created_at) as fim, timediff(max(created_at), min(created_at)) as tempo, 'Diario 23h' as 'Data_hora_atualizacao'
from orcamentos_anal

union all
select 'vendas_2019' as tabela, count(*) as registros, min(created_at) as inicio, max(created_at) as fim, timediff(max(created_at), min(created_at)) as tempo, 'Diario 2h20' as 'Data_hora_atualizacao'
from vendas_2019


union all
select 'trocas' as tabela, count(*) as registros, min(created_at) as inicio, max(created_at) as fim, timediff(max(created_at), min(created_at)) as tempo, '-' as 'Data_hora_atualizacao'
from trocas



union all
select 'importacoes_analitica' as tabela, count(*) as registros, min(created_at) as inicio, max(created_at) as fim, timediff(max(created_at), min(created_at)) as tempo, 'Diario 3h20' as 'Data_hora_atualizacao'
from importacoes_analitica

union all
select 'importacoes_sintetica' as tabela, count(*) as registros, min(created_at) as inicio, max(created_at) as fim, timediff(max(created_at), min(created_at)) as tempo, 'Diario 3h' as 'Data_hora_atualizacao'
from importacoes_sintetica


union all
select 'trocas' as tabela, count(*) as registros, min(created_at) as inicio, max(created_at) as fim, timediff(max(created_at), min(created_at)) as tempo, 'Sabado 3h40' as 'Data_hora_atualizacao'
from trocas






) as base
order by inicio asc





");



    	return view('sistema.integracoes.jobs')->with('jobs', $jobs)->with('atualizacaobase', $atualizacaobase); 

    }


    public function integraItens() {

        $linha = 0;
        echo $linha.' '.date("H:i:s").'<br>';

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

        $body = '';

        $ch1 = curl_init('https://app.lumini360.com.br/v2/go-jde-itens/_search?scroll=1m&size=3000');                                                                      
        curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "POST");   
        curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);                                                                  
        curl_setopt($ch1, CURLOPT_POSTFIELDS, $body);                                                                  
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);                                                                      
        curl_setopt($ch1, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json',                                                                                
            'Authorization: Bearer ' . $token     )                                                                  
        );                                                                                                                   
                                                                                                                             
        $result2 = json_decode(curl_exec($ch1), true);


        $scroll_id = $result2["_scroll_id"];
        $registros = $result2["hits"]["total"];
        $paginas = ceil($registros/500);

        foreach ($result2["hits"]["hits"] as $nf) {
            $linha++;
            echo $linha.' '.date("H:i:s").'<br>';
            // echo '<pre>';
            // print_r($nf);
            // echo '</pre>';

        }


        for ($i=0;$i<$paginas;$i++) {

            $body = '{
                      "scroll": "1m",
                      "scroll_id": "'.$scroll_id.'"
                    }';

            $ch2 = curl_init('https://app.lumini360.com.br/v2/_search/scroll');                                                                      
            curl_setopt($ch2, CURLOPT_CUSTOMREQUEST, "POST");   
            curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);                                                                  
            curl_setopt($ch2, CURLOPT_POSTFIELDS, $body);                                                                  
            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);                                                                      
            curl_setopt($ch2, CURLOPT_HTTPHEADER, array(                                                                          
                'Content-Type: application/json',                                                                                
                'Authorization: Bearer ' . $token     )                                                                  
            );                                                                                                                   
                                                                                                                                 
            $result3 = json_decode(curl_exec($ch2), true);   



            foreach ($result3["hits"]["hits"] as $nf) {
                $linha++;
                echo $linha.' '.date("H:i:s").'<br>';;
                // echo '<pre>';
                // print_r($nf);
                // echo '</pre>';

            }            

        }
    }

    

    public function integraVendas() {

    	ini_set('memory_limit',-1);
		$inputFileName = storage_path('uploads\vendas.xlsx');
		$spreadsheet = IOFactory::load($inputFileName);
		$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
		var_dump($sheetData);


    }

    public function consultaEstoque() {

       function AddWSSUsernameToken($client, $username, $password)
        {
            $wssNamespace = "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd";

            $username = new \SoapVar($username, 
                XSD_STRING, 
                null, null, 
                'Username', 
                $wssNamespace);

            $password = new \SoapVar($password, 
                XSD_STRING, 
                null, null, 
                'Password', 
                $wssNamespace);

            $usernameToken = new \SoapVar(array($username, $password), 
                SOAP_ENC_OBJECT, 
                null, null, 'UsernameToken', 
                $wssNamespace);

            $usernameToken = new \SoapVar(array($usernameToken), 
                SOAP_ENC_OBJECT, 
                null, null, null, 
                $wssNamespace);

            $wssUsernameTokenHeader = new \SoapHeader($wssNamespace, 'Security', $usernameToken);

            $client->__setSoapHeaders($wssUsernameTokenHeader); 
        }   

        $url_inventory = "https://integra.goeyewear.com.br/PD920/InventoryManager?WSDL"; 

        $context = stream_context_create([
            'ssl' => [
                // set some SSL/TLS specific options
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ]);
        
        //$url        = "http://189.125.137.61:7161/PY920/ItemServiceManager?wsdl"; 
        $client     = new \SoapClient($url_inventory, array("trace" => 1, 'encoding'=>'ISO-8859-1')); 
        AddWSSUsernameToken($client, 'KSILVA', 'Ninecon2017');


        $result = $client->getCalculatedAvailability(array( 
                                                        'branchPlantList'=>'01020000',
                                                        'itemSecond'=> 'AH6254 A01',
                                                        'omitZeroQty'=>''
                                                    )); 
       

        print_r($result); 
    }

    public function uploadArquivo(Request $request) {
        ini_set("display_errors", 0);
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
     

        $nameFile = null;
     
        // Verifica se informou o arquivo e se é válido
        if ($request->hasFile('arquivo') && $request->file('arquivo')->isValid()) {
            if ($request->arquivo->extension() == 'txt') {
                // Define um aleatório para o arquivo baseado no timestamps atual
                $name = uniqid(date('HisYmd'));
         
                // Recupera a extensão do arquivo
                $extension = $request->arquivo->extension();
         
                // Define finalmente o nome
                $nameFile = "{$name}.csv";
         
                // Faz o upload:
                $upload = $request->arquivo->storeAs('uploads', $nameFile);
                // Se tiver funcionado o arquivo foi armazenado em storage/app/public/categories/nomedinamicoarquivo.extensao

                $arquivo = '/var/www/html/portalgo/storage/app/'.$upload;

                if (file_exists($arquivo)) {
                    $handle = fopen($arquivo, "r"); 
                    $query21 = \DB::select("TRUNCATE TABLE `producoes`");

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
                            

                            \DB::select("insert into producoes_hist (timestamp,dt_relatorio,numero , arquivo, linha, colecao, pedido, modelo_go, modelo_fab, cor, dt_emissao, dt_entrega, dt_confirmacao, qtd_pedido, qtd_enviada, saldo, estoque, producao, fabrica, cod_sec, status) 
                        values ( current_timestamp, '$dt_relatorio','$numero','$arquivo', '$linha', '$colecao', '$pedido',  '$modelo_go','$modelo_fab', '$codigo_cor', '$dt_emissao', '$dt_entrega', '$dt_confirmacao', '$qtd2', '$transito2', '0', '$estoque',  '$producao','$fabrica','$cod_sec','$status' )");
                            echo '<br>'. $cod_sec;
                            
                            
                          
                        

                        }   

                        $linha++;

                    }
                }


            } else {
                $request->session()->flash('alert', 'Arquivo não é CSV');

            } 


        } else {
            $request->session()->flash('alert', 'Arquivo inválido');
        }

        //return redirect('/jobs/'.$job->id.'/executa');

        app('App\Http\Controllers\IntegracaoController')->atualizaProducoes();
    }

    public function atualizaProducoes() {
  
    

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

              \DB::select("truncate table producoes_sint");
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



        \DB::select("TRUNCATE TABLE `producoes`");
        

        $linha = 1;
//       $result = mysqli_query($conn, "truncate table go.producoes");

        $numero = date('dmYHis');
        
        

       

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
                

                \DB::select("insert into producoes (timestamp,dt_relatorio,numero , arquivo, linha, colecao, pedido, modelo_go, modelo_fab, cor, dt_emissao, dt_entrega, dt_confirmacao, qtd_pedido, qtd_enviada, saldo, estoque, producao, fabrica, cod_sec, status) 
            values ( current_timestamp, '$dt_relatorio','$numero','$arquivo', '$linha', '$colecao', '$pedido',  '$modelo_go','$modelo_fab', '$codigo_cor', '$dt_emissao', '$dt_entrega', '$dt_confirmacao', '$qtd2', '$transito2', '0', '$estoque',  '$producao','$fabrica','$cod_sec','$status' )");

                
                
            

            }    

            $linha++;

        

        $producoes_anterior = \DB::select("

select timestamp, dt_relatorio,numero , arquivo, linha, colecao, pedido, modelo_go, modelo_fab, cor, dt_emissao, dt_entrega, dt_confirmacao, qtd_pedido, qtd_enviada, saldo, estoque, producao, fabrica, cod_sec, status
from producoes_hist

where date(producoes_hist.timestamp) = date((select date(timestamp) from producoes_hist where date(timestamp) <> (select date(timestamp) from producoes_hist order by date(timestamp) desc limit 1) order by timestamp desc limit 1))



");

        if (count($producoes_anterior) > 0) {
            $total_sql = count($producoes_anterior);

                \DB::select("truncate table producoes_anterior");
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
           
                

    }
     app('App\Http\Controllers\CompraController')->enviaatrasos();
     app('App\Http\Controllers\CompraController')->diferencapedidos();
     app('App\Http\Controllers\CompraController')->mudancadataentrega();
     app('App\Http\Controllers\CompraController')->faltacadastro();
    echo 'DEU CERTO';

    }
	
	public function listaProducoes() {

		$producoes = \DB::select("select  date(dt_relatorio) as data1, numero, count(id) qtd
											from producoes_hist
                                            group by dt_relatorio, numero
                                            order by dt_relatorio desc
											limit 15");

		// $processamentos = StatusProcessa::groupBy('processamento',\DB::raw('date(data)'))
		// 									->select('processamento',\DB::raw('date(data) as data'))
											
		// 									->orderBy('data', 'desc')
		// 									->get();

		
		return view('sistema.integracoes.producoes')->with('producoes', $producoes);

	}
	
	public function excluiProducoes($numero) {


		$processamentos = \DB::select("delete from producoes_hist where numero = '$numero'");		

		return redirect('/integracao/producoes');

	}
	

}


