@extends('layout.principal')

@section('title')
  <i class="fa fa-gears"></i> Erros cadastro
@append 

@section('conteudo')

@php




$query = DB::select("select 'linha_errada_rx' as tipo,count(secundario) qtd
			from itens
			where codtipoitem = '006'
			and colmod not in ('2019 07', '2019 08', '2019 11')
			and codgrife in ('AH', 'AT', 'BG', 'HI','SP', 'JO', 'TC','EV','NG','AM','BC','BV','CT','GU','MC','MM','PU','SM','ST')
            and agrup like '%02%' and linha = 'solar' and codtipoarmaz not in ('o', 'i')
            
union all

select 'linha_errada_sl' as tipo, count(secundario)as  qtd
			from itens
			where codtipoitem = '006'
			and colmod not in ('2019 07', '2019 08', '2019 11')
			and codgrife in ('AH', 'AT', 'BG', 'HI','SP', 'JO', 'TC','EV','NG','AM','BC','BV','CT','GU','MC','MM','PU','SM','ST')
            and agrup like '%01%' and linha = 'receituario' and codtipoarmaz not in ('o', 'i')

union all

select 'ajustar_armazenamento' as tipo, count(secundario)as  qtd
			from itens
			where codtipoitem = '006'
			and colmod not in ('2019 07', '2019 08', '2019 11')
			and codgrife in ('AH', 'AT', 'BG', 'HI','SP', 'JO', 'TC','EV','NG','AM','BC','BV','CT','GU','MC','MM','PU','SM','ST')
            and codstatusatual not in ('esgot','prod','','.') and anomod > '2016'  and codtipoarmaz in ('o', 'i')
            
union all

select 'ajustar_preco' as tipo, count(secundario)as  qtd
			from itens
			where codtipoitem = '006'
			and colmod not in ('2019 07', '2019 08', '2019 11')
			and codgrife in ('AH', 'AT', 'BG', 'HI','SP', 'JO', 'TC','EV','NG','AM','BC','BV','CT','GU','MC','MM','PU','SM','ST')
            and codstatusatual not in ('esgot','prod','.','') and anomod > '2016' and codtipoarmaz not in ('o', 'i')  and valortabela < '30'

union all
select 'colocar_inativo' as tipo, count(secundario)as  qtd
from itens
			where codtipoitem = '006'
			and colmod not in ('2019 07', '2019 08', '2019 11')
			and codgrife in ('AH', 'AT', 'BG', 'HI','SP', 'JO', 'TC','EV','NG','AM','BC','BV','CT','GU','MC','MM','PU','SM','ST')
            and codstatusatual = ('esgot')  
				and codtipoarmaz not in ('o', 'i') 
				and anomod < '2016'
                
union all
select 'colecao_modelo' as tipo, count(secundario) as  qtd
from itens
			where codtipoitem = '006'
			and colmod not in ('2019 07', '2019 08', '2019 11')
			and codgrife in ('AH', 'AT', 'BG', 'HI','SP', 'JO', 'TC','EV','NG','AM','BC','BV','CT','GU','MC','MM','PU','SM','ST')
            and colmod in ('.','')

union all
select 'colecao_item' as tipo, count(secundario) as  qtd
from itens
			where codtipoitem = '006'
			and colmod not in ('2019 07', '2019 08', '2019 11')
			and codgrife in ('AH', 'AT', 'BG', 'HI','SP', 'JO', 'TC','EV','NG','AM','BC','BV','CT','GU','MC','MM','PU','SM','ST')
            and colitem in ('.','')
		
union all
select 'ano_modelo' as tipo, count(secundario) as  qtd
from itens
			where codtipoitem = '006'
			and colmod not in ('2019 07', '2019 08', '2019 11')
			and codgrife in ('AH', 'AT', 'BG', 'HI','SP', 'JO', 'TC','EV','NG','AM','BC','BV','CT','GU','MC','MM','PU','SM','ST')
            and anomod in ('.','')
            
union all
select 'ano_item' as tipo, count(secundario)as  qtd
from itens
			where codtipoitem = '006'
			and colmod not in ('2019 07', '2019 08', '2019 11')
			and codgrife in ('AH', 'AT', 'BG', 'HI','SP', 'JO', 'TC','EV','NG','AM','BC','BV','CT','GU','MC','MM','PU','SM','ST')
            and anoitem in ('.','')
            
union all
select 'modelo' as tipo, count(secundario)as  qtd
from itens
			where codtipoitem = '006'
			and colmod not in ('2019 07', '2019 08', '2019 11')
			and codgrife in ('AH', 'AT', 'BG', 'HI','SP', 'JO', 'TC','EV','NG','AM','BC','BV','CT','GU','MC','MM','PU','SM','ST')
            and modelo in ('.','')
            
union all
select 'ean' as tipo, count(secundario)as  qtd
from itens
			where codtipoitem = '006'
			and colmod not in ('2019 07', '2019 08', '2019 11')
			and codgrife in ('AH', 'AT', 'BG', 'HI','SP', 'JO', 'TC','EV','NG','AM','BC','BV','CT','GU','MC','MM','PU','SM','ST')
            and  ean in ('.','') and codstatusatual not in ('esgot','prod') and anomod >= '2017' and codtipoarmaz not in ('o', 'i')
            
union all
select 'clas_mod' as tipo, count(secundario)as  qtd
from itens
			where codtipoitem = '006'
			and colmod not in ('2019 07', '2019 08', '2019 11')
			and codgrife in ('AH', 'AT', 'BG', 'HI','SP', 'JO', 'TC','EV','NG','AM','BC','BV','CT','GU','MC','MM','PU','SM','ST')
            and clasmod in ('.','')
            
union all
select 'clas_item' as tipo, count(secundario)as  qtd
from itens
			where codtipoitem = '006'
			and colmod not in ('2019 07', '2019 08', '2019 11')
			and codgrife in ('AH', 'AT', 'BG', 'HI','SP', 'JO', 'TC','EV','NG','AM','BC','BV','CT','GU','MC','MM','PU','SM','ST')
            and clasitem in ('.','')
            
union all
select 'fornecedor' as tipo, count(secundario) as  qtd
from itens
			where codtipoitem = '006'
			and colmod not in ('2019 07', '2019 08', '2019 11')
			and codgrife in ('AH', 'AT', 'BG', 'HI','SP', 'JO', 'TC','EV','NG','AM','BC','BV','CT','GU','MC','MM','PU','SM','ST')
            and fornecedor not in ('WENZHOU ZHONGMIN GLASSES CQ LTDA', 'TINYE OPTICAL GROUP COMPANY LIMITED', 'ALIALUX SRL', 'XIAMEN PROSPER OPTICAL TEC COM LTDA',
				'MIRAGE S P A', 'WENZHOU DECO INTERNATONAL CO', 'WENZHOU READSUN OPTICAL CO LTD', '', 'CLAIR MONT IND DE COM LTDA', 'BENSOL', 'KENERSON IND E COMERCIO DE PROD OPTICOS','KERING EYEWEAR USA INC', 'WENZHOU LUJIA CO LYDA', 'KENERSON IND E COM DE PROD OPTICOS LTDA', 'WEIMEI INTERNATIONAL TRADINGHK LIMITED', 'KERING EYEWEAR SPA',
				'kering','BRAZILIAN LAB EXPORT E IMPORT LTDA')
            
union all
select 'sem_cadastro' as tipo, count(cod_sec) as  qtd
from producoes_sint
			where id = '99999999'

");



@endphp



 
  <div class="row" >       
    <div class="col-md-12">
      <div class="box box-widget">

        <div class="box-header with-border">
        
        </div>         

        <div class="box-body">

			<h5>Where geral </br> codtipoitem = '006'
			</br>and colmod not in ('2019 07', '2019 08', '2019 11')
			</br>and codgrife in ('AH', 'AT', 'BG', 'HI','SP', 'JO', 'TC','EV','NG','AM','BC','BV','CT','GU','MC','MM','PU','SM','ST')</br></h5>     

          <table class="box-body table-responsive table-striped">
            <tr align="left">
              <td width="10%">Tipo</td>
              <td width="10%">Qtd</td>
              <td width="10%">Where</td>
                             	
            </tr>
			  
			   


           



            @foreach ($query as $dados) 

            


            <tr>

              <td align="left">
               
                <a href="erroscadastro?ajuste={{$dados->tipo}}" >{{$dados->tipo}}</a>
                
              </td>

              <td align="left">{{$dados->qtd}}</td>
                
			@php
				
				switch ($dados->tipo) {
            case 'linha_errada_rx':
              $mala = 'and agrup like %02% and linha = solar and codtipoarmaz not in (o, i)';
              break;
            case 'linha_errada_sl':
              $mala = 'and agrup like %01% and linha = receituario and codtipoarmaz not in (o, i)';
              break;
				case 'ajustar_armazenamento':
              $mala = 'and codstatusatual not in (esgot,prod,,.) and anomod > 2016  and codtipoarmaz in (o, i)';
              break;
				case 'ajustar_preco':
              $mala = 'and codstatusatual not in (esgot,prod,.,) and anomod > 2016 and codtipoarmaz not in (o, i)  and valortabela >30';
              break;
				case 'colocar_inativo':
              $mala = 'and codstatusatual = (esgot)  and codtipoarmaz not in (o, i) and anomod <2016';
              break;
				case 'colecao_modelo':
              $mala = 'and colmod in (.,)';
              break;
				case 'colecao_item':
              $mala = 'and colitem in (.,)';
              break;
				case 'ano_modelo':
              $mala = 'and anomod in (.,)';
              break;
				case 'ano_item':
              $mala = 'and anoitem in (.,)';
              break;
				case 'modelo':
              $mala = 'and modelo in (.,)';
              break;
				case 'ean':
              $mala = 'and  ean in (.,) and codstatusatual not in (esgot,prod) and anomod >= 2017 and codtipoarmaz not in (o, i)';
              break;
				case 'clas_mod':
              $mala = 'and clasmod in (.,)';
              break;
				case 'clas_item':
              $mala = 'and clasitem in (.,)';
              break;
				case 'fornecedor':
              $mala = 'and fornecedor not in (WENZHOU ZHONGMIN GLASSES CQ LTDA, TINYE OPTICAL GROUP COMPANY LIMITED, ALIALUX SRL, XIAMEN PROSPER OPTICAL TEC COM LTDA,
				MIRAGE S P A, WENZHOU DECO INTERNATONAL CO, WENZHOU READSUN OPTICAL CO LTD, , CLAIR MONT IND DE COM LTDA, BENSOL, KENERSON IND E COMERCIO DE PROD OPTICOS,KERING EYEWEAR USA INC, WENZHOU LUJIA CO LYDA, KENERSON IND E COM DE PROD OPTICOS LTDA, WEIMEI INTERNATIONAL TRADINGHK LIMITED, KERING EYEWEAR SPA,kering,BRAZILIAN LAB EXPORT E IMPORT LTDA) ';
              break;
				case 'sem_cadastro':
              $mala = 'cet itens sem cadastro na tabela itens';
              break;
				
				

            default:
              $mala = '-';

          } @endphp
				
				<td align="left">{{$mala}}</td> 
				
				
				
				
			  
			  
			   
			  
			 
			  
			  
			  
			  
			  
			  
			  
			              
              
              

            </tr>

            @endforeach
            
			  
			  
          </table>

        </div>	


      </div>
    </div>
  </div>

  @stop