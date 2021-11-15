





@extends('produtos/painel/index')

@section('titulo') {{$_GET["modelo"]}} - Vendas @append
@section('title')
<i class="fa fa-list"></i> Novo
@append 

@section('conteudo')
@include('produtos.painel.modal.caracteristica')	
@php

$modelo = $_GET["modelo"];


$query = DB::select("select base.*,  trocas.trocas from (
select itens.id iditem, itens.agrup agrupamento, itens.modelo as modelos,itens.secundario as secundarios, 
(select colmod from itens a where a.secundario = itens.secundario and colmod not in ('','Add sales cat S5 codes here') limit 1 ) colmod, 
(select clasmod from itens b where b.secundario = itens.secundario and colmod not in ('','Add sales cat S5 codes here') limit 1 ) clasmod,
(select genero from itens c where c.secundario = itens.secundario and colmod not in ('','Add sales cat S5 codes here') limit 1 ) genero,

(select idade from itens d where d.secundario = itens.secundario and colmod not in ('','Add sales cat S5 codes here') limit 1 ) idade,
(select tamolho from itens d where d.secundario = itens.secundario and colmod not in ('','Add sales cat S5 codes here') limit 1 ) tamolho,

 vendas_sint.*, sld.disponivel+sld.reserva_most Availability, 
  conf_montado+em_beneficiamento+saldo_parte as Factoring_BR, 
  qtd_rot_receb+cet as In_transit, 
	etq as Stock_Factory, cep  as In_production,
  saldo_manutencao as Maintenance, saldo_trocas as Estrategy_reserve, saldo_most as Showcases,
	
    (select count(data) from processa st 
	where st.id_item = itens.id AND (st.status3 like 'entre%' or st.status3 like '%disp%' )
	) as semanas,
    ifnull((select sum(qtde) from compras_itens where status = 'aberto' and item = itens.secundario),0) as pedidos_aberto,
    ifnull((select sum(qtde) from compras_itens where status in ('confirmado','distribuido','concluido') and item = itens.secundario),0) as qtd_pedido,
    ifnull(orcvalido,0) orc
    
    


  from itens  
  left join go.vendas_sint 	on vendas_sint.secundario = itens.secundario
  left join go.saldos sld 	on sld.secundario = itens.secundario
  left join go.orcamentos orc 	on orc.secundario = itens.secundario
  

  where itens.modelo = '$modelo'
and codtipoitem = '006'

) as base






left join 
(select secundario, sum(qtde) trocas from trocas group by secundario) as trocas
on trocas.secundario = base.secundarios
order by a_180dd desc

");

/**echo $query[0]->colmod;**/

@endphp



 
  <div class="row" >       
    <div class="col-md-12">
      <div class="box box-widget">

        <div class="box-header with-border">
          <h3 class="box-title">
			  Modelo - <a href="/painel/{{$query[0]->agrupamento}}/{{$query[0]->modelo}}"><?php echo $modelo.' / ' ;?></a>
          
			  
            @if (\auth::user()->admin ==1 or \auth::user()->id_perfil ==2 or \auth::user()->id_perfil ==1 or \auth::user()->id_perfil ==11) 
            <a href="" class="text-black alteraClasMod">{{$query[0]->clasmod}} @if ( \Auth::user()->admin == 1 )<a href="" class="alteraCaracteristica" data-tipo="modelo" data-caracteristica="classmod" data-value="{{$query[0]->iditem}}"><i class="fa fa-edit"></i></a>@endif</a>
            @endif
        
          
          <?php echo '/'.$query[0]->colmod.' / '; ?>
          <?php echo $query[0]->genero.' / '; ?>
          <?php echo $query[0]->idade.' / '; ?>
          <?php echo $query[0]->tamolho; ?>
          </h3> 
        </div> 
		  

        <div class="box-body">

          <h5><b>Monthly Rolling Average</b></h5>     

          <table class="box-body table-responsive table-striped" id="example1">
            <thead>
            <tr align="center">
            <td width="1%">Hist</td>
              <td width="10%">Picture</td>
              <td width="7%">Item</td>
              <td width="3%" align="center">30dd</td>
              <td width="3%">60dd</td>
              <td width="3%">90dd</td>
              <td width="3%">120dd</td>
              <td width="3%">150dd</td>
              <td width="3%">180dd</td>
              <td width="3%">210dd</td>
              <td width="3%">240dd</td>
              <td width="3%">270dd</td>
              <td width="3%">300dd</td>
              <td width="3%">330dd</td>
              <td width="3%">360dd</td>               	
              <td width="4%">tt 180dd</td>               	
              <td width="4%"><b>total</b></td> 
				<td width="2%">BO</td>  
              <td width="2%">Weeks</td>               	
              <td width="3%">BR</td> 
              <td width="3%">CET</td> 
              <td width="3%">ETQ</td> 
              <td width="3%">CEP</td> 
              <td width="3%">OTH</td>
              <td width="2%">SC</td>
              <td width="2%">Repair</td>
               <td width="2%">Order</td>    
				<td width="2%">Open Order</td>    
            </tr>
			  
			   <tr align="center">
               <td width="1%"></td>
              <td width="10%">图片</td>
              <td width="7%">型号</td>
              <td width="3%" align="center">30天</td>
              <td width="3%">60天</td>
              <td width="3%">90天</td>
              <td width="3%">120天</td>
              <td width="3%">150天</td>
              <td width="3%">180天</td>
              <td width="3%">210天</td>
              <td width="3%">240天</td>
              <td width="3%">270天</td>
              <td width="3%">300天</td>
              <td width="3%">330天</td>
              <td width="3%">360天</td>               	
              <td width="4%">半年</td>               	
              <td width="4%"><b>累计</b></td> 
				   <td width="4%"><b></b></td> 
              <td width="2%">周数</td>               	
              <td width="3%">巴西</td> 
              <td width="3%">运输</td> 
              <td width="3%">中国</td> 
              <td width="3%">生产</td> 
              <td width="3%">其他</td>
              <td width="2%">样品</td>
              <td width="2%">损坏</td>
              <td width="2%">单量</td>
				   <td width="2%"></td>
                             	
            </tr>
          </thead>
          <tbody>

            @php  
            $totala = 0;
            $totalb = 0;
            $totalc = 0;
            $totald = 0;
            $totale = 0;
            $totalf = 0;
            $totalg = 0;
            $totalh = 0;
            $totali = 0;
            $totalj = 0;
            $totall = 0;
            $totalm = 0;
            $total180 = 0;
            $total = 0;
            $totalbr = 0;
            $totalcet = 0;
            $totaletq = 0;
            $totalcep = 0;
            $totaloth = 0;
            $totalsc = 0;
            $totaltr = 0;            
            $totalcompras = 0;
			   $totalbo = 0;
			    $totalpedidoaberto = 0;
            
            @endphp



            @foreach ($query as $dados) 

            @php 	
            $totala += $dados->ult_30dd;
            $totalb += $dados->ult_60dd;
            $totalc += $dados->ult_90dd;
            $totald += $dados->ult_120dd;
            $totale += $dados->ult_150dd;
            $totalf += $dados->ult_180dd;
            $totalg += $dados->ult_210dd;
            $totalh += $dados->ult_240dd;
            $totali += $dados->ult_270dd;
            $totalj += $dados->ult_300dd;
            $totall += $dados->ult_330dd;
            $totalm += $dados->ult_360dd;
            $total180 += $dados->a_180dd;
            $total += $dados->vendastt;
            $totalbr += $dados->Availability+$dados->Factoring_BR;
            $totalcet += $dados->In_transit;
            $totaletq += $dados->Stock_Factory;
            $totalcep += $dados->In_production;
            $totaloth += $dados->Maintenance+$dados->Estrategy_reserve;
            $totalsc += $dados->Maintenance+$dados->Showcases;
            $totaltr += $dados->trocas;
            $totalcompras += $dados->qtd_pedido;
			  $totalbo += $dados->orc;
			   $totalpedidoaberto += $dados->pedidos_aberto;
            @endphp


            <tr>
            <td><a href="https://painel.goeyewear.com.br/painel/{{$dados->agrupamento}}/{{$dados->modelo}}/{{$dados->secundarios}}" >C</a></td>

              <td id="foto" align="center" style="min-height:60px;">
               
                <a href="" class="zoom" data-value="{{$dados->secundarios}}"><img src="https://portal.goeyewear.com.br/teste999.php?referencia={{$dados->secundarios}}" style="max-height: 60px;" class="img-responsive"></a>
                
              </td>

              <td><small><a href="/vendas_rep?item={{$dados->secundarios}}">
              <?php echo $dados->secundarios?></a></small></td>
				
				
                           
              <td align="center"><small>{{ number_format($dados->ult_30dd,0) }}</small></td>
              <td align="center"><small>{{ number_format($dados->ult_60dd,0) }}</small></td>
              <td align="center"><small>{{ number_format($dados->ult_90dd,0) }}</small></td>
              <td align="center"><small>{{ number_format($dados->ult_120dd,0) }}</small></td>
              <td align="center"><small>{{ number_format($dados->ult_150dd,0) }}</small></td>
              <td align="center"><small>{{ number_format($dados->ult_180dd,0) }}</small></td>
              <td align="center"><small>{{ number_format($dados->ult_210dd,0) }}</small></td>
              <td align="center"><small>{{ number_format($dados->ult_240dd,0) }}</small></td>
              <td align="center"><small>{{ number_format($dados->ult_270dd,0) }}</small></td>
              <td align="center"><small>{{ number_format($dados->ult_300dd,0) }}</small></td>
              <td align="center"><small>{{ number_format($dados->ult_330dd,0) }}</small></td>
              <td align="center"><small>{{ number_format($dados->ult_360dd,0) }}</small></td>  
              <td align="center"><small>{{ number_format($dados->a_180dd,0) }}  </small></td> 
              <td align="center"><small><b>{{ number_format($dados->vendastt,0) }}</b> </small></td>
				 <td align="center"><small><b>{{ number_format($dados->orc,0) }}</b> </small></td>

              <td align="center"><small><?php echo number_format($dados->semanas,0)?></small></td>

             <td align="center"><small>	 				 
				 <a href="/estoque?secundario={{$dados->secundarios}}"><?php echo number_format($dados->Availability+$dados->Factoring_BR,0)?></small></a></td>
              <td align="center"><small><?php echo number_format($dados->In_transit,0)?> </small></td>
              <td align="center"><small><?php echo number_format($dados->Stock_Factory,0)?> </small></td>
              <td align="center"><small><?php echo number_format($dados->In_production,0)?> </small></td>
              <td align="center"><small><?php echo number_format($dados->Maintenance+$dados->Estrategy_reserve,0)?> </small></td>
              <td align="center"><small><?php echo number_format($dados->Maintenance+$dados->Showcases,0)?> </small></td>
				 <td align="center"><small>{{ number_format($dados->trocas,0) }}</small></td>
              <td align="center"><small><b>
              <a href="compras_det?item={{$dados->secundarios}}">{{ number_format($dados->qtd_pedido,0) }}</a></b> </small></td>
				 <td align="center"><small><b>
              {{ number_format($dados->pedidos_aberto,0) }}</b> </small></td>
              

            </tr>

            @endforeach
          </small>
          <tfoot>
            <tr style="text-align: center; font-weight: bold;">
            <td></td>
              <td></td>
              <td width="7%"><b>TOTAL</b></td>
              <td width="3%" align="center">{{$totala}}</td>
              <td width="3%" align="center">{{$totalb}}</td>
              <td width="3%" align="center">{{$totalc}}</td>
              <td width="3%" align="center">{{$totald}}</td>
              <td width="3%" align="center">{{$totale}}</td>
              <td width="3%" align="center">{{$totalf}}</td>
              <td width="3%" align="center">{{$totalg}}</td>
              <td width="3%" align="center">{{$totalh}}</td>
              <td width="3%" align="center">{{$totali}}</td>
              <td width="3%" align="center">{{$totalj}}</td>
              <td width="3%" align="center">{{$totall}}</td>
              <td width="3%" align="center">{{$totalm}}</td>
              <td width="3%" align="center">{{$total180}}</td>
              <td width="3%" align="center">{{$total}}</td>
				<td width="3%" align="center">{{$totalbo}}</td>
			  <td width="3%" align="center"></td>
              <td width="3%" align="center">{{$totalbr}}</td>
              <td width="3%" align="center">{{$totalcet}}</td>
              <td width="3%" align="center">{{$totaletq}}</td>
              <td width="3%" align="center">{{$totalcep}}</td>
              <td width="3%" align="center">{{$totaloth}}</td>
              <td width="3%" align="center">{{$totalsc}}</td>
              <td width="3%" align="center">{{$totaltr}}</td>
				<td width="3%" align="center"><a href="">{{$totalcompras}}</a></td>
				<td width="3%" align="center"><a href="">{{$totalpedidoaberto}}</a></td>

              
            </tr>
          </tfoot>
          </table>

        </div>	


      </div>
    </div>
  </div>

  @stop