@extends('produtos/painel/index')

@section('title')
  <i class="fa fa-list"></i> Produtos
@append 

@section('conteudo')

@php
  if (isset($modelos) and count($modelos) > 0) {
    $agrup = $modelos[0]->agrup;
    $grife = $modelos[0]->grife;
  } else {
    $agrup = 'teste';
    $grife = 'teste';
  }
@endphp
<input type="hidden" name="agrup" value="{{$agrup}}" id="agrup">
<div class="row">
  <div class="col-md-12">
    <div class="box box-body box-widget">
      <div class="row">
        <div class="col-md-3" align="center">
          <img src="/img/marcas/{{$grife}}.png" class="img-responsive">
          <span class="text-blue"><b>{{$agrup}}</b></span>
          <br>
          <button disabled="">Modelos</button>
          <button>Itens</button>
        </div>
        <div class="col-md-9">
          <div class="row">
            <div class="col-md-3 col-md-offset-3">

              <center><small><b>Compras</b></small></center>
              <table width="100%" border="0" align="right">
                <tr>
                  <td width="15%" align="center"><i class="fa fa-shopping-cart text-green"></i></td>
                  <td width="43%"><small>Últimos 180 dias</small></td>
                  <td width="43%" align="right"><small>{{number_format($totais["total_vda_180"],0,'.','.')}}</small></td>
                </tr>
                <tr>
                  <td align="center"><i class="fa fa-shopping-cart text-green"></i></td>
                  <td><small>Vendas Total</small></td>
                  <td align="right"><small>{{number_format($totais["total_vda_total"],0,'.','.')}}</small></td>
                </tr>
                <tr>
                  <td align="center"><i class="fa fa-line-chart text-blue"></i></td>
                  <td><small>Média Mensal</small></td>
                  <td align="right"><small>{{number_format($totais["total_vda_media"],0,'.','.')}}</small></td>
                </tr>
                <tr>
                  <td align="center"><i class="fa fa-hourglass-3 text-purple"></td>
                  <td><small>Orçamentos</small></td>
                  <td align="right"><small>{{number_format($totais["total_vda_orcamento"],0,'.','.')}}</small></td>
                </tr>                              
                </table>    

            </div>
            <div class="col-md-3">
              <center><small><b>Sales</b></small></center>
              <table width="100%" border="0" align="right">
                <tr>
                  <td width="15%" align="center"><i class="fa fa-shopping-cart text-green"></i></td>
                  <td width="43%"><small>Last 180 days</small></td>
                  <td width="43%" align="right"><small>{{number_format($totais["total_vda_180"],0,'.','.')}}</small></td>
                </tr>
                <tr>
                  <td align="center"><i class="fa fa-shopping-cart text-green"></i></td>
                  <td><small>Total Sales</small></td>
                  <td align="right"><small>{{number_format($totais["total_vda_total"],0,'.','.')}}</small></td>
                </tr>
                <tr>
                  <td align="center"><i class="fa fa-line-chart text-blue"></i></td>
                  <td><small>Monthly Avg</small></td>
                  <td align="right"><small>{{number_format($totais["total_vda_media"],0,'.','.')}}</small></td>
                </tr>
                <tr>
                  <td align="center"><i class="fa fa-hourglass-3 text-purple"></td>
                  <td><small>Backorder</small></td>
                  <td align="right"><small>{{number_format($totais["total_vda_orcamento"],0,'.','.')}}</small></td>
                </tr>                              
                </table>                  
            </div>
            <div class="col-md-3">
              <center><small><b>Inventory</b></small></center>
              <table width="100%" border="0" align="right">
                <tr>
                  <td width="15%" align="center"><img src="/img/brasil.png" width="15"></td>
                  <td width="43%"><small>Brazil</small></td>
                  <td width="43%" align="right"><small>{{number_format($totais["total_etq_brasil"],0,'.','.')}}</small></td>
                </tr>
                <tr>
                  <td align="center"><i class="fa fa-plane"></i></td>
                  <td><small>Transit</small></td>
                  <td align="right"><small>{{number_format($totais["total_etq_transito"],0,'.','.')}}</small></td>
                </tr>
                <tr>
                  <td align="center"><img src="/img/china.png" width="15"></td>
                  <td><small>China</small></td>
                  <td align="right"><small>{{number_format($totais["total_etq_china"],0,'.','.')}}</small></td>
                </tr>
                <tr>
                  <td align="center"><i class="fa fa-industry"></i></td>
                  <td><small>Production</small></td>
                  <td align="right"><small>{{number_format($totais["total_etq_producao"],0,'.','.')}}</small></td>
                </tr>                        
                <tr style="border-top: 1px solid black !important;">
                  <td align="center"></td>
                  <td><small><b>Total<b></small></td>
                  <td align="right" style="border-top: 1px solid black !important;"><small><b>{{number_format($totais["total_etq"],0,'.','.')}}</b></small></td>
                </tr>            
                </table>              
            </div>
          </div>            


        </div>
      </div>
    </div>    

  </div>
</div>

<div class="row">

  @if (isset($_GET))
      <div class="col-md-12">
        <div class="box box-body box-widget">
          <small style="margin-right: 10px;">Filtros:</small>
            @foreach ($_GET as $chave => $valor)
                <span class="label bg-gray">{{$chave}}: {{$valor}} 
                <a href="" class="text-red"><i class="fa fa-close"></i></span></a>
            @endforeach
        </div>
      </div>
  @endif

  @if (isset($modelos) && count($modelos) > 0)

  @foreach ($modelos as $catalogo)

  <div class="col-md-3">
    <div class="box box-widget">
      @if (Session::has('novocatalogo'))
        <input type="checkbox" name="modelo" class="addModeloCatalogo" @if (\App\Catalogo::verificaModeloHabilitado($catalogo->modelo) == true) checked @endif value="{{$catalogo->modelo}}">
      @endif
      <span class="text-bold">{{$catalogo->modelo}}</span> 

      @if (\App\PermissaoUsuario::verificaPermissao(Auth::id(), 'valor',1))
        {{ number_format($catalogo->valor,2,',','.') }} 
      @endif

      @if (\App\PermissaoUsuario::verificaPermissao(Auth::id(), 'custo',1))
        {{ number_format($catalogo->custo,2,',','.') }}
      @endif
      
 	<tr>
    	<a href="/exemplo?modelo={{$catalogo->modelo}}">
    		<i class="fa fa-cart-plus pull-right" placeholder ="Data da Atualização">
    			</a></i></tr>
     
     
      <div id="foto" align="center" style="margin-top:0px; min-height:223px;height:223px; top:50%; margin-bottom:0; padding-bottom:0;">
        <a href="/painel/{{$catalogo->agrup}}/{{$catalogo->modelo}}">
          <img src="https://portal.goeyewear.com.br/teste999.php?referencia={{$catalogo->item}}" style="max-height: 200px;" class="img-responsive">
        </a>

        <a href="" class="zoom" data-value="{{$catalogo->item}}"><i class="fa fa-search text-blue" style="position:absolute; top:215px; left:93%; opacity:0.8;" ></i></a>

        <a href="" class="fichaCompra"><i class="fa fa-file-text-o fa-1x text-teal" style="position:absolute; top:215px; left:85%; opacity:0.8;"  data-toggle="tooltip" data-placement="top" title="Ficha Compra" ></i></a>
        
        <a href="" class="fichaTecnica" data-modelo="AH1324" data-agrup="AH02 - ANA HICKMANN (RX)"><i class="fa fa-file-pdf-o fa-1x text-black" style="position:absolute; top:215px; left:80%; opacity:0.8;"  data-toggle="tooltip" data-placement="top" title="Ficha Tecnica" ></i></a>
        
        <a href="" class="fichaDesign" data-value="../../fotos/BAIXA/AH02 - ANA HICKMANN (RX)/AH1324 09B.jpg"><i class="fa fa-file-photo-o fa-1x text-teal" style="position:absolute; top:215px; left:75%; opacity:0.8;"  data-toggle="tooltip" data-placement="top" title="Ficha Design" ></i></a>
        
        <a href="" class="fichaTrade" data-modelo="AH1324" data-agrup="AH02 - ANA HICKMANN (RX)"><i class="fa fa-file-image-o fa-1x text-teal" style="position:absolute; top:215px; left:70%; opacity:0.8;"  data-toggle="tooltip" data-placement="top" title="Ficha Trade" ></i></a>
        
      </div>

      <div class="box-body">

        <div class="row">
          <div class="col-sm-6 col-md-6"><a href="" class="text-black alteraClasMod">{{$catalogo->classificacao}}</a></div>
          <div class="col-sm-6 col-md-6" align="right"><a href="" class="text-black">{{$catalogo->colecao}}</a></div>
        </div>

        @include('produtos.painel.info-vendas')

        @include('produtos.painel.info-estoques')

        <span><b>Cores Mod:</b> {{ $catalogo->itens }} </span>
        <span class="pull-right"><b>Cores Disp: </b>6</span>


        <div class="col-xs-10 col-md-10 no-padding">
          <div class="progress xs">         
            <div class="progress-bar progress-bar-red" style="width: 0%" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
          </div>  
        </div>
        <div class="col-xs-2 col-md-2 no-padding">
          <small class="pull-right">0.00%</small></a>
        </div>      
        <br>  
        <small></small>

      </div>
    </div>
  </div>
  
  @endforeach

  @else 

    <h3 align="center">Nenhum modelo encontrado!</h3>

  @endif

</div>
@stop