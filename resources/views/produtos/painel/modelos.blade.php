@extends('produtos/painel/index')

@section('titulo') {{$modelos[0]->agrup}} @append

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
        </div>
        <div class="col-md-9">
          <div class="row">
            @if (\App\PermissaoUsuario::verificaPermissao(Auth::id(), 'compras', 1))        
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
            @endif

            @if (\App\PermissaoUsuario::verificaPermissao(Auth::id(), 'vendas', 1))                    
            <div class="col-md-3">
              <center><small><b>@lang('painel.vendas')</b></small></center>
              <table width="100%" border="0" align="right">
                <tr>
                  <td width="15%" align="center"><i class="fa fa-shopping-cart text-green"></i></td>
                  <td width="43%"><small>@lang('painel.vendas180dias')</small></td>
                  <td width="43%" align="right"><small>{{number_format($totais["total_vda_180"],0,'.','.')}}</small></td>
                </tr>
                <tr>
                  <td align="center"><i class="fa fa-shopping-cart text-green"></i></td>
                  <td><small>@lang('painel.vendas_totais')</small></td>
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
            @endif


            @if (\App\PermissaoUsuario::verificaPermissao(Auth::id(), 'estoques', 1))                    
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
            @endif
            
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
            @if (Session::has('novocatalogo'))
              <input type="checkbox" id="selectAll"> Seleciona Todos
            @endif
        </div>
      </div>
  @endif


  @if (isset($modelos) && count($modelos) > 0)
  @foreach ($modelos as $catalogo)

  @php
    $id_usuario = \Auth::id();
    $favoritos = \DB::select("select * from favoritos where modelo = '$catalogo->modelo' and id_usuario = '$id_usuario'");
	$N = '';

	if($catalogo->modelo == 'AH1373' or 
 $catalogo->modelo == 'AH1374' or 
 $catalogo->modelo == 'AH1385' or 
 $catalogo->modelo == 'AH1386' or 
 $catalogo->modelo == 'AH1396' or 
 $catalogo->modelo == 'AH1397' or 
 $catalogo->modelo == 'AH3231' or 
 $catalogo->modelo == 'AH6363' or 
 $catalogo->modelo == 'AH6364' or 
 $catalogo->modelo == 'AH6366I' or 
 $catalogo->modelo == 'AH6367I' or 
 $catalogo->modelo == 'AH6368' or 
 $catalogo->modelo == 'AH6372' or 
 $catalogo->modelo == 'AH6381' or 
 $catalogo->modelo == 'AH6382' or 
 $catalogo->modelo == 'AH6402' or 
 $catalogo->modelo == 'AH6403' or 
 $catalogo->modelo == 'AH6413' or 
 $catalogo->modelo == 'AH6414' or 
 $catalogo->modelo == 'AH6415' or 
 $catalogo->modelo == 'AH6421' or 
 $catalogo->modelo == 'H6176' or 
 $catalogo->modelo == 'HI1130' or 
 $catalogo->modelo == 'HI1139' or 
 $catalogo->modelo == 'HI1140' or 
 $catalogo->modelo == 'HI6170F' or 
 $catalogo->modelo == 'HI6175' or 
 $catalogo->modelo == 'HI6176' or 
 $catalogo->modelo == 'HI6185' or 
 $catalogo->modelo == 'HI6186' or
	 $catalogo->modelo == 'HI6186' or 
 $catalogo->modelo == 'AH9295' or 
 $catalogo->modelo == 'AH9294' or 
 $catalogo->modelo == 'AH3231' or 
 $catalogo->modelo == 'AH3232' or 
 $catalogo->modelo == 'AH6407' or 
 $catalogo->modelo == 'AH6428' or 
 $catalogo->modelo == 'AH6387' or 
 $catalogo->modelo == 'AH6388' or 
 $catalogo->modelo == 'AH6383' or 
 $catalogo->modelo == 'AH6384' or
$catalogo->modelo == 'AH1423'or
$catalogo->modelo == 'HI1130'or
$catalogo->modelo == 'HI1139'




){
 $N = 'N';

}

  @endphp

  <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
    <div class="box box-widget">
      @if (Session::has('novocatalogo'))
        <input type="checkbox" name="modelo" class="addModeloCatalogo" @if (\App\Catalogo::verificaModeloHabilitado($catalogo->modelo) == true) checked @endif value="{{$catalogo->modelo}}">
      @endif

      <div class="box-header with-border" style="font-size:16px; padding: 3px 8px 3px 8px; margin-bottom: 0; vertical-align: top; height: 30px;" >
        <span class="text-bold" color="red">{{$catalogo->modelo}} <font color="red">{{$N}}</font> </span> 
		  
		  @if ((\auth::user()->admin ==1 or \auth::user()->id_perfil ==2 or \auth::user()->id_perfil ==1 or \auth::user()->id_perfil ==17) and $catalogo->historico <> 0)
		  <a href="/painel/{{$catalogo->agrup}}/{{$catalogo->modelo}}/{{$catalogo->modelo}}"></a><span  data-toggle="tooltip" title="{{$catalogo->historico}} Históricos" class="badge bg-yellow">
		  {{$catalogo->historico}}</span></a>
		  
			@endif
		  
        <span class="pull-right">R$ {{ number_format($catalogo->valor,2,',','.') }} 

        @if (\App\PermissaoUsuario::verificaPermissao(Auth::id(), 'custo',1))
          / {{ number_format($catalogo->custo,2,',','.') }}
        @endif
			
			 @if ( \Auth::user()->admin == 1 ) 
            <a href="" class="text-danger">{{$catalogo->moeda}}{{ number_format($catalogo->custo_2019,2,',','.') }}</a>
            @endif
        </span>
      </div>
      

      @if (\auth::user()->admin ==1 or \auth::user()->id_perfil ==2 or \auth::user()->id_perfil ==1 or \auth::user()->id_perfil ==11) 
    	<a href="/exemplo?modelo={{$catalogo->modelo}}">
    		<i class="fa fa-cart-plus pull-right" placeholder ="Data da Atualização"></i>
      </a>
      @endif     
     
      <div id="foto" align="center" style="margin-top:0px; min-height:223px;height:223px; top:50%; margin-bottom:0; padding-bottom:0;">

        @if (isset($catalogo->recall) && $catalogo->recall == 'sim') 
          <a href="" class="zoom" data-value="{{$catalogo->item}}"><i class="fa fa-warning text-orange fa-2x" style="position:absolute; top:215px; left:5%; opacity:0.8;" ></i></a>
        @endif         

        @php
          $foto = app('App\Http\Controllers\ItemController')->consultaFoto($catalogo->item);
          $outras_cores = \DB::select("select secundario from itens where modelo = '$catalogo->modelo'");
          $i = 0;
        @endphp


        <div id="carousel-{{$catalogo->modelo}}" class="carousel slide" data-ride="carousel">
          <ol class="carousel-indicators">
            <li data-target="#carousel-{{$catalogo->modelo}}" data-slide-to="0" class="active"></li>

            @foreach ($outras_cores as $cor)
              @php
                $i++;
              @endphp
            <li data-target="#carousel-{{$catalogo->modelo}}" data-slide-to="{{$i}}" class=""></li>
            @endforeach
          </ol>
          <div class="carousel-inner">

            <div class="item active">
                <a href="/painel/{{$catalogo->agrup}}/{{$catalogo->modelo}}">
                  <img src="/{{$foto}}" style="max-height: 250px;" class="img-responsive">
					<div class="carousel-caption" style="color:#000 !important; left:-50%;">
                    {{$catalogo->item}}
                  </div>
                </a>
            </div>

            @foreach ($outras_cores as $cor)
              @php
              $foto = app('App\Http\Controllers\ItemController')->consultaFoto($cor->secundario);
              @endphp
			  
			
			  
			   @if ($foto <> 'fotos/nopicture.jpg')
			  
              <div class="item">
                <a href="/painel/{{$catalogo->agrup}}/{{$catalogo->modelo}}">
                  
					
					<img src="/{{$foto}}" style="max-height: 250px;" class="img-responsive">
  					
                  <div class="carousel-caption" style="color:#000 !important; left:-50%;">
                    {{$cor->secundario}}
                  </div>
					
                </a>

              </div>
			  @endif
			 
            @endforeach
			  

          </div>
          <a class="left carousel-control" href="#carousel-{{$catalogo->modelo}}" data-slide="prev">
            <span class="fa fa-angle-left"></span>
          </a>
          <a class="right carousel-control" href="#carousel-{{$catalogo->modelo}}" data-slide="next">
            <span class="fa fa-angle-right"></span>
          </a>
        </div>


		  
        <a href="" class="addFavoritos" data-value="{{$catalogo->modelo}}">
          @if ($favoritos)
            <i class="fa fa-heart text-red fa-2x" style="position:absolute; top:40px; left:90%; opacity:0.8;" ></i>
          @else 
            <i class="fa fa-heart-o text-red fa-2x" style="position:absolute; top:40px; left:90%; opacity:0.8;" ></i>
          @endif
        </a>


        @php
          $essenciais = \DB::select("select * from essenciais where modelo = '$catalogo->modelo'");
        @endphp
      
        <a href="" class="" data-value="{{$catalogo->modelo}}">
          @if (isset($essenciais) && count($essenciais) > 0)
            <i class="fa fa-star text-yellow fa-2x" style="position:absolute; top:40px; left:2%; opacity:0.8;" ></i>
          @else 
            <i class="fa fa-star text-gray fa-2x" style="position:absolute; top:40px; left:2%; opacity:0.8;" ></i>
          @endif
        </a>

         

        <a href="" class="zoom" data-value="{{$catalogo->item}}"><i class="fa fa-search text-blue" style="position:absolute; top:235px; left:93%; opacity:0.8;" ></i></a>

        @if (\auth::user()->admin ==1 or \auth::user()->id_perfil ==2 or \auth::user()->id_perfil ==4 or \auth::user()->id_perfil ==1 or \auth::user()->id_perfil ==11) 

        @if ($catalogo->adv == 'sim') 
          <a href="/painel/campanhas/{{$catalogo->modelo}}"><i class="fa fa-camera text-purple fa-2x" style="position:absolute; top:40px; left:5%; opacity:0.8;" ></i></a>
        @endif 

        @if ($catalogo->midia == 'sim') 
          <a href="/painel/midias/{{$catalogo->modelo}}"><i class="fa fa-instagram text-blue fa-2x" style="position:absolute; top:60px; left:20%; opacity:0.8;" ></i></a>
        @endif 
        <a href="" class="fichaCompra"><i class="fa fa-file-text-o fa-1x text-teal" style="position:absolute; top:235px; left:85%; opacity:0.8;"  data-toggle="tooltip" data-placement="top" title="Ficha Compra" ></i></a>
        
        <a href="" class="fichaTecnica" data-modelo="AH1324" data-agrup="AH02 - ANA HICKMANN (RX)"><i class="fa fa-file-pdf-o fa-1x text-black" style="position:absolute; top:235px; left:80%; opacity:0.8;"  data-toggle="tooltip" data-placement="top" title="Ficha Tecnica" ></i></a>
        
        <a href="" class="fichaDesign" data-value="../../fotos/BAIXA/AH02 - ANA HICKMANN (RX)/AH1324 09B.jpg"><i class="fa fa-file-photo-o fa-1x text-teal" style="position:absolute; top:235px; left:75%; opacity:0.8;"  data-toggle="tooltip" data-placement="top" title="Ficha Design" ></i></a>
        
        <a href="" class="fichaTrade" data-modelo="AH1324" data-agrup="AH02 - ANA HICKMANN (RX)"><i class="fa fa-file-image-o fa-1x text-teal" style="position:absolute; top:235px; left:70%; opacity:0.8;"  data-toggle="tooltip" data-placement="top" title="Ficha Trade" ></i></a>

        @if (\Auth::user()->admin == 1)
          <a href="" class="uploadFoto" data-value="{{$catalogo->modelo}}" data-tipo="modelo"><i class="fa fa-upload" style="position:absolute; top:235px; left:5%; opacity:0.8;"  data-toggle="tooltip" data-placement="top" title="Upload Foto" ></i></a>
        @endif

      @endif         
      
      </div>

      <div class="box-body">

        <div class="row">
          <div class="col-sm-6 col-md-6">

{{--             @if (\auth::user()->admin ==1 or \auth::user()->id_perfil ==2 or \auth::user()->id_perfil ==1 or \auth::user()->id_perfil ==11) 
 --}}
            <a href="" class="text-black alteraClasMod">
              {{$catalogo->clasmod}} 
              @if ( \Auth::user()->admin == 1 )<a href="" class="alteraCaracteristica" data-tipo="modelo" data-caracteristica="clasmod" data-value="{{$catalogo->id_item}}"><i class="fa fa-edit"></i></a>@endif</a>

{{--             @endif
 --}}            
          </div>
          <div class="col-sm-6 col-md-6" align="right"><a href="" class="text-black">{{$catalogo->colecao}} @if ( \Auth::user()->admin == 1 )<a href="" class="alteraCaracteristica" data-tipo="modelo" data-caracteristica="colmod" data-value="{{$catalogo->id_item}}"><i class="fa fa-edit"></i></a>@endif</a></div>
			
			
        </div>
        @include('produtos.painel.info-compras')

        @include('produtos.painel.info-vendas')

        @include('produtos.painel.info-estoques')
      <div>
      @if (\Auth::user()->id_perfil == 1 or \Auth::user()->id_perfil == 2) 
                @if (isset($catalogo->qtde_compra))
                 <b>{{'Pedido aberto '}}</b>{{$catalogo->qtde_compra}}
                @else  <b>{{'Pedido aberto '}}</b>{{0}}
        @endif  @endif
      </div>
       @if(isset($catalogo->sec_disp))
         
       <table>
        <tr><b>Cores Mod:</b> {{ $catalogo->itens }} </tr>
        <tr>X<b>Cores Disp:</b>  <font color="green">{{$catalogo->sec_disp}}</font></tr>
		   	    @if ( \Auth::user()->admin == 1 ) 
		    <tr>
            <td><i class="fa fa-user-secret"></i> Estilo</td>
            <td>{{$catalogo->estilo}} 
             
              <span class="pull-right"><a href="" class="alteraCaracteristica" data-tipo="modelo"  data-caracteristica="estilo" data-value="{{$catalogo->id_item}}"><i class="fa fa-edit"></i></a></span>
             
            </td>
          </tr> 
		    @endif
       
	
		</table>
          @php
            $cor = 'green';
            if ((($catalogo->sec_disp/$catalogo->itens)*100)<=50) {
              $cor = "red";  
            } elseif ((($catalogo->sec_disp/$catalogo->itens)*100)>51 and (($catalogo->sec_disp/$catalogo->itens)*100)<80) {
              $cor = "yellow"; 
            }
          @endphp
        <div class="col-xs-10 col-md-10 no-padding">
          <div class="progress xs"> 
           
            <div class="progress-bar progress-bar-{{$cor}}" style="width: {{$catalogo->sec_disp/$catalogo->itens*100}}%" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
          </div>  
        </div>
        <div class="col-xs-2 col-md-2 no-padding">
          <small class="pull-right">{{number_format($catalogo->sec_disp/$catalogo->itens*100,2)}}% </small></a>
        </div>      
        <br>  
        <small></small>
        @endif
      </div>

    </div>
  </div>
  
  @endforeach

  @else 

    <h3 align="center">Nenhum modelo encontrado!</h3>

  @endif

</div>
@include('produtos.painel.modal.caracteristica')
@include('produtos.painel.modal.uploadFoto')

@stop