@extends('produtos/painel/index')

@section('title')
<i class="fa fa-list"></i> Novo
@append 

@section('conteudo')

@php

$ajuste = $_GET["ajuste"];





switch ($ajuste) {
			case 'linha_errada_rx':
				$where = "and agrup like '%02%' and linha = 'solar' and codtipoarmaz not in ('o', 'i')";
				$tabela = "	<td > Modelo</td>
				<td > secundario</td>
				<td >agrup</td>
				<td ><b>linha</b></td>                                     	
            </tr>";



			$query = DB::select("select secundario,agrup , linha, modelo
			from itens
			where codtipoitem = '006'
			and colmod not in ('2019 07', '2019 08', '2019 11')
			and codgrife in ('AH', 'AT', 'BG', 'HI','SP', 'JO', 'TC','EV','NG','AM','BC','BV','CT','GU','MC','MM','PU','SM','ST')
			$where

			");

            foreach ($query as $dados) {
	 
	            $tabela .= "<tr>
					<td ><a href=\"http://painel.goeyewear.com.br/painel/AH02%20-%20ANA%20HICKMANN%20(RX)/$dados->modelo\"> $dados->modelo </a></td>
					<td >  $dados->secundario </td>
					<td > $dados->agrup </td>
					<td ><b> $dados->linha </b></td>
					</tr>";

			}
				
				break;
				case 'linha_errada_sl':
				$where = "and agrup like '%01%' and linha = 'receituario' and codtipoarmaz not in ('o', 'i')";
$tabela = "		<td > Modelo</td>
				<td > secundario</td>
				
				<td >agrup</td>
				<td ><b>linha</b></td>
				
                                       	
            </tr>";



			$query = DB::select("select secundario,agrup , linha, codtipoarmaz, valortabela,  colmod, colitem, anomod,  anoitem,  modelo,  ean,  clasmod,  clasitem, fornecedor 
			from itens
			where codtipoitem = '006'
			and colmod not in ('2019 07', '2019 08', '2019 11')
			and codgrife in ('AH', 'AT', 'BG', 'HI','SP', 'JO', 'TC','EV','NG','AM','BC','BV','CT','GU','MC','MM','PU','SM','ST')
			$where

			");

            foreach ($query as $dados) {
	 
	            $tabela .= "<tr>
					<td ><a href=\"http://painel.goeyewear.com.br/painel/AH02%20-%20ANA%20HICKMANN%20(RX)/$dados->modelo\"> $dados->modelo </a></td>
					<td >  $dados->secundario </td>
					<td > $dados->agrup </td>
					<td ><b> $dados->linha </b></td>
					</tr>";

			}
				break;
			case 'ajustar_armazenamento':
				$where = "and codstatusatual not in ('esgot','prod','','.') and anomod > '2016'  and codtipoarmaz in ('o', 'i')";
$tabela = "		<td > Modelo</td>
				<td > secundario</td>
				<td >codtipoarmaz</td>
				<td ><b>codstatusatual</b></td>
				
                                       	
            </tr>";



			$query = DB::select("select secundario, codtipoarmaz, codstatusatual, modelo 
			from itens
			where codtipoitem = '006'
			and colmod not in ('2019 07', '2019 08', '2019 11')
			and codgrife in ('AH', 'AT', 'BG', 'HI','SP', 'JO', 'TC','EV','NG','AM','BC','BV','CT','GU','MC','MM','PU','SM','ST')
			$where

			");

            foreach ($query as $dados) {
	 
	            $tabela .= "<tr>
					<td ><a href=\"http://painel.goeyewear.com.br/painel/AH02%20-%20ANA%20HICKMANN%20(RX)/$dados->modelo\"> $dados->modelo</a></td>
					<td >  $dados->secundario </td>
					<td > $dados->codtipoarmaz </td>
					<td ><b> $dados->codstatusatual </b></td>";

			}
				break;
			case 'ajustar_preco':
				$where = "and codstatusatual not in ('esgot','prod','.','') and anomod > '2016' and codtipoarmaz not in ('o', 'i')  and valortabela < '30'";
				
			
$tabela = "<td > Modelo</td>
				<td > secundario</td>
				
				<td ><b>valortabela</b></td>
				 </tr>";



			$query = DB::select("select secundario,agrup , linha, codtipoarmaz, valortabela,  colmod, colitem, anomod,  anoitem,  modelo,  ean,  clasmod,  clasitem, fornecedor 
			from itens
			where codtipoitem = '006'
			and colmod not in ('2019 07', '2019 08', '2019 11')
			and codgrife in ('AH', 'AT', 'BG', 'HI','SP', 'JO', 'TC','EV','NG','AM','BC','BV','CT','GU','MC','MM','PU','SM','ST')
			$where

			");

            foreach ($query as $dados) {
	 
	            $tabela .= "<tr>
					<td ><a href=\"http://painel.goeyewear.com.br/painel/AH02%20-%20ANA%20HICKMANN%20(RX)/$dados->modelo\"> $dados->modelo </a></td>
					<td >  $dados->secundario </td>
					<td ><b> $dados->valortabela </b></td>
					</tr>";

			}
				break;

				case 'colocar_inativo':
				$where = "and codstatusatual = ('esgot')  
				and codtipoarmaz not in ('o', 'i') 
				and anomod < '2016'";
$tabela = "		<td > Modelo</td>
				<td > secundario</td>
				
				<td ><b>codtipoarmaz</b></td>
				<td >colmod</td>
				<td >colitem</td>
				<td >anomod</td>
				<td >anoitem</td>
				
                                       	
            </tr>";



			$query = DB::select("select secundario,agrup , linha, codtipoarmaz, valortabela,  colmod, colitem, anomod,  anoitem,  modelo,  ean,  clasmod,  clasitem, fornecedor, modelo 
			from itens
			where codtipoitem = '006'
			and colmod not in ('2019 07', '2019 08', '2019 11')
			and codgrife in ('AH', 'AT', 'BG', 'HI','SP', 'JO', 'TC','EV','NG','AM','BC','BV','CT','GU','MC','MM','PU','SM','ST')
			$where

			");

            foreach ($query as $dados) {
	 
	            $tabela .= "<tr>
					<td ><a href=\"http://painel.goeyewear.com.br/painel/AH02%20-%20ANA%20HICKMANN%20(RX)/$dados->modelo\">$dados->modelo </a></td>
					<td >  $dados->secundario</td>
					<td ><b> $dados->codtipoarmaz </b></td>
					<td > $dados->colmod </td>
					<td > $dados->colitem </td>
					<td > $dados->anomod </td>
					<td >$dados->anoitem </td>
					</tr>";

			}
				break;


			case 'colecao_modelo':
				$where = "and colmod in ('.','') ";
$tabela = "		<td > Modelo</td>
				<td > secundario</td>
				
				<td ><b>colmod</b></td>
				<td >colitem</td>
				<td >anomod</td>
				<td >anoitem</td>
				
                                       	
            </tr>";



			$query = DB::select("select secundario,agrup , linha, codtipoarmaz, valortabela,  colmod, colitem, anomod,  anoitem,  modelo,  ean,  clasmod,  clasitem, fornecedor, modelo 
			from itens
			where codtipoitem = '006'
			and colmod not in ('2019 07', '2019 08', '2019 11')
			and codgrife in ('AH', 'AT', 'BG', 'HI','SP', 'JO', 'TC','EV','NG','AM','BC','BV','CT','GU','MC','MM','PU','SM','ST')
			$where

			");

            foreach ($query as $dados) {
	 
	            $tabela .= "<tr>
					<td ><a href=\"http://painel.goeyewear.com.br/painel/AH02%20-%20ANA%20HICKMANN%20(RX)/$dados->modelo\">$dados->modelo </a></td>
					<td >  $dados->secundario</td>
					<td ><b> $dados->colmod </b></td>
					<td > $dados->colitem </td>
					<td > $dados->anomod </td>
					<td >$dados->anoitem </td>
					</tr>";

			}
				break;
			case 'colecao_item':
				$where = "and colitem in ('.','') ";
$tabela = "		<td > Modelo</td>
				<td > secundario</td>
				
				<td >agrup</td>
				<td >colmod</td>
				<td ><b>colitem</b></td>
				<td >anomod</td>
				<td >anoitem</td>
				
                                       	
            </tr>";



			$query = DB::select("select secundario,agrup , linha, codtipoarmaz, valortabela,  colmod, colitem, anomod,  anoitem,  modelo,  ean,  clasmod,  clasitem, fornecedor,modelo 
			from itens
			where codtipoitem = '006'
			and colmod not in ('2019 07', '2019 08', '2019 11')
			and codgrife in ('AH', 'AT', 'BG', 'HI','SP', 'JO', 'TC','EV','NG','AM','BC','BV','CT','GU','MC','MM','PU','SM','ST')
			$where

			");

            foreach ($query as $dados) {
	 
	            $tabela .= "<tr>
					<td ><a href=\"http://painel.goeyewear.com.br/painel/AH02%20-%20ANA%20HICKMANN%20(RX)/$dados->modelo\"> $dados->modelo </a></td>
					<td >  $dados->secundario </td>
					<td > $dados->agrup </td>
					<td > $dados->colmod </td>
					<td ><b> $dados->colitem </b></td>
					<td > $dados->anomod </td>
					<td > $dados->anoitem </td>
					</tr>";

			}
				break;
			case 'ano_modelo':
				$where = "and anomod in ('.','')";
$tabela = "		<td > Modelo</td>
				<td > secundario</td>
				
				<td >agrup</td>
				<td >colmod</td>
				<td >colitem</td>
				<td ><b>anomod</b></td>
				<td >anoitem</td>
		
                                       	
            </tr>";



			$query = DB::select("select secundario,agrup , linha, codtipoarmaz, valortabela,  colmod, colitem, anomod,  anoitem,  modelo,  ean,  clasmod,  clasitem, fornecedor,modelo 
			from itens
			where codtipoitem = '006'
			and colmod not in ('2019 07', '2019 08', '2019 11')
			and codgrife in ('AH', 'AT', 'BG', 'HI','SP', 'JO', 'TC','EV','NG','AM','BC','BV','CT','GU','MC','MM','PU','SM','ST')
			$where

			");

            foreach ($query as $dados) {
	 
	            $tabela .= "<tr>
					<td ><a href=\"http://painel.goeyewear.com.br/painel/AH02%20-%20ANA%20HICKMANN%20(RX)/$dados->modelo\"> $dados->modelo </a></td>
					<td > $dados->secundario </td>
					<td > $dados->colmod </td>
					<td > $dados->colitem </td>
					<td ><b> $dados->anomod </b></td>
					<td > $dados->anoitem </td>
					";

			}
				break;
			case 'ano_item':
				$where = "and anoitem in ('.','') ";
$tabela = "		<td > Modelo</td>
				<td > secundario</td>

				<td >colmod</td>
				<td >colitem</td>
				<td >anomod</td>
				<td ><b>anoitem</b></td>
				
                                       	
            </tr>";



			$query = DB::select("select secundario,agrup , linha, codtipoarmaz, valortabela,  colmod, colitem, anomod,  anoitem,  modelo,  ean,  clasmod,  clasitem, fornecedor, modelo 
			from itens
			where codtipoitem = '006'
			and colmod not in ('2019 07', '2019 08', '2019 11')
			and codgrife in ('AH', 'AT', 'BG', 'HI','SP', 'JO', 'TC','EV','NG','AM','BC','BV','CT','GU','MC','MM','PU','SM','ST')
			$where

			");

            foreach ($query as $dados) {
	 
	            $tabela .= "<tr>
					<td ><a href=\"http://painel.goeyewear.com.br/painel/AH02%20-%20ANA%20HICKMANN%20(RX)/$dados->modelo\"> $dados->modelo </a></td>
					<td >  $dados->secundario </td>
					<td > $dados->colmod </td>
					<td > $dados->colitem </td>
					<td > $dados->anomod </td>
					<td ><b> $dados->anoitem </b></td>
					</tr>";

			}
				break;
			case 'modelo':
				$where = "and modelo in ('.','')";
$tabela = "		<td ><b> Modelo</b></td>
				<td > secundario</td>
				
				
                                       	
            </tr>";



			$query = DB::select("select secundario,agrup , linha, codtipoarmaz, valortabela,  colmod, colitem, anomod,  anoitem,  modelo,  ean,  clasmod,  clasitem, fornecedor, modelo 
			from itens
			where codtipoitem = '006'
			and colmod not in ('2019 07', '2019 08', '2019 11')
			and codgrife in ('AH', 'AT', 'BG', 'HI','SP', 'JO', 'TC','EV','NG','AM','BC','BV','CT','GU','MC','MM','PU','SM','ST')
			$where

			");

            foreach ($query as $dados) {
	 
	            $tabela .= "<tr>
					<td ><a href=\"http://painel.goeyewear.com.br/painel/AH02%20-%20ANA%20HICKMANN%20(RX)/$dados->modelo\"><b> $dados->modelo </b></a></td>
					<td >  $dados->secundario </td>
					</tr>";

			}
				break;
			case 'ean':
				$where = "and  ean in ('.','') and codstatusatual not in ('esgot','prod') and anomod >= '2017' and codtipoarmaz not in ('o', 'i')";
$tabela = "	<td > secundario</td>
				<td ><b>ean</b></td>

                                       	
            </tr>";



			$query = DB::select("select secundario,agrup , linha, codtipoarmaz, valortabela,  colmod, colitem, anomod,  anoitem,  modelo,  ean,  clasmod,  clasitem, fornecedor, modelo
			from itens
			where codtipoitem = '006'
			and colmod not in ('2019 07', '2019 08', '2019 11')
			and codgrife in ('AH', 'AT', 'BG', 'HI','SP', 'JO', 'TC','EV','NG','AM','BC','BV','CT','GU','MC','MM','PU','SM','ST')
			$where

			");

            foreach ($query as $dados) {
	 
	            $tabela .= "<tr>
					<td ><a href=\"http://painel.goeyewear.com.br/painel/AH02%20-%20ANA%20HICKMANN%20(RX)/$dados->modelo\"> $dados->modelo </a></td>
					<td > $dados->secundario </td>
					<td ><b> $dados->ean </b></td>
</tr>";

			}
				break;
			case 'clas_mod':
				$where = "and clasmod in ('.','')";
$tabela = "		<td > Modelo</td>
				<td > secundario</td>
				
				<td ><b>clasmod</b></td>
				
                                       	
            </tr>";



			$query = DB::select("select secundario,agrup , linha, codtipoarmaz, valortabela,  colmod, colitem, anomod,  anoitem,  modelo,  ean,  clasmod,  clasitem, fornecedor, modelo 
			from itens
			where codtipoitem = '006'
			and colmod not in ('2019 07', '2019 08', '2019 11')
			and codgrife in ('AH', 'AT', 'BG', 'HI','SP', 'JO', 'TC','EV','NG','AM','BC','BV','CT','GU','MC','MM','PU','SM','ST')
			$where

			");

            foreach ($query as $dados) {
	 
	            $tabela .= "<tr>
					<td ><a href=\"http://painel.goeyewear.com.br/painel/AH02%20-%20ANA%20HICKMANN%20(RX)/$dados->modelo\">$dados->modelo </a></td>
					<td >  $dados->secundario </td>
					<td ><b> $dados->clasmod </b></td>
</tr>";

			}
				break;
			case 'clas_item':
				$where = "and clasitem in ('.','') ";
$tabela = "		<td > Modelo</td>
				<td > secundario</td>
				
				<td ><b>clasitem</b></td>
                                       	
            </tr>";



			$query = DB::select("select secundario,agrup , linha, codtipoarmaz, valortabela,  colmod, colitem, anomod,  anoitem,  modelo,  ean,  clasmod,  clasitem, fornecedor, modelo 
			from itens
			where codtipoitem = '006'
			and colmod not in ('2019 07', '2019 08', '2019 11')
			and codgrife in ('AH', 'AT', 'BG', 'HI','SP', 'JO', 'TC','EV','NG','AM','BC','BV','CT','GU','MC','MM','PU','SM','ST')
			$where

			");

            foreach ($query as $dados) {
	 
	            $tabela .= "<tr>
					<td ><a href=\"http://painel.goeyewear.com.br/painel/AH02%20-%20ANA%20HICKMANN%20(RX)/$dados->modelo\"> $dados->modelo </a></td>
					<td >  $dados->secundario </td>
					<td ><b> $dados->clasitem </b></td>
					</tr>";

			}
				break;
			case 'fornecedor':
				$where = "and fornecedor not in ('WENZHOU ZHONGMIN GLASSES CQ LTDA', 'TINYE OPTICAL GROUP COMPANY LIMITED', 'ALIALUX SRL', 'XIAMEN PROSPER OPTICAL TEC COM LTDA',
				'MIRAGE S P A', 'WENZHOU DECO INTERNATONAL CO', 'WENZHOU READSUN OPTICAL CO LTD', '', 'CLAIR MONT IND DE COM LTDA', 'BENSOL', 'KENERSON IND E COMERCIO DE PROD OPTICOS','KERING EYEWEAR USA INC', 'WENZHOU LUJIA CO LYDA', 'KENERSON IND E COM DE PROD OPTICOS LTDA', 'WEIMEI INTERNATIONAL TRADINGHK LIMITED', 'KERING EYEWEAR SPA',
				'kering','BRAZILIAN LAB EXPORT E IMPORT LTDA') ";
			$tabela = "	<td > Modelo</td>
						<td > secundario</td>
				
				<td ><b>fornecedor</b></td>
                                       	
            </tr>";



			$query = DB::select("select secundario,agrup , linha, codtipoarmaz, valortabela,  colmod, colitem, anomod,  anoitem,  modelo,  ean,  clasmod,  clasitem, fornecedor 
			from itens
			where codtipoitem = '006'
			and colmod not in ('2019 07', '2019 08', '2019 11')
			and codgrife in ('AH', 'AT', 'BG', 'HI','SP', 'JO', 'TC','EV','NG','AM','BC','BV','CT','GU','MC','MM','PU','SM','ST')
			$where

			");

            foreach ($query as $dados) {
	 
	            $tabela .= "<tr>
					<td ><a href=\"http://painel.goeyewear.com.br/painel/AH02%20-%20ANA%20HICKMANN%20(RX)/$dados->modelo\"> $dados->modelo </a></td>
					<td >  $dados->secundario </td>
					
					<td ><b> $dados->fornecedor </b></td></tr>";

			}
				break;

case 'sem_cadastro':
				
				$tabela = "	<td > Secundario</td>
				<td > Producoes</td>
				<td >Estoque</td>
				                                    	
            </tr>";



			$query = DB::select("select cod_sec, sum(producao) as producao, sum(estoque) as estoque
from producoes_sint
			where id = '99999999'
group by cod_sec

			");

            foreach ($query as $dados) {
	 
	            $tabela .= "<tr>
					<td ><a href=\"http://painel.goeyewear.com.br/painel/AH02%20-%20ANA%20HICKMANN%20(RX)/$dados->cod_sec\"> $dados->cod_sec </a></td>
					<td >  $dados->producao </td>
					<td > $dados->estoque </td>
					
					</tr>";

			}
				
				break;

			default:
				# code...
				break;
		}


 	



@endphp



 
  <div class="row" >       
    <div class="col-md-12">
      <div class="box box-widget">

        <div class="box-body">

          <h5>Lista de <b>@php echo $ajuste;@endphp  </b></h5>     

          <table class="table table-bordered table-condensed">
			
				{!!$tabela!!}
				
           
          </table>

        </div>	


      </div>
    </div>
  </div>

  @stop