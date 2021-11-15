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



  @if (isset($modeloagregado) && count($modeloagregado) > 0)
  @foreach ($modeloagregado as $catalogo)

  <div class="col-md-3">
    <div class="box box-widget">
      @if (Session::has('novocatalogo'))
        <input type="checkbox" name="modelo" class="addModeloCatalogo" @if (\App\Catalogo::verificaModeloHabilitado($catalogo->modelo) == true) checked @endif value="{{$catalogo->modelo}}">
      @endif

      <div class="box-header with-border" style="font-size:16px; padding: 3px 8px 3px 8px; margin-bottom: 0; vertical-align: top;">
        <span class="text-bold">{{$catalogo->modelo}}</span> 

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
        @endphp

        <a href="/painel/{{$catalogo->agrup}}/{{$catalogo->modelo}}">
          <img src="/{{$foto}}" style="max-height: 250px;" class="img-responsive">
        </a>

        <a href="" class="zoom" data-value="{{$catalogo->item}}"><i class="fa fa-search text-blue" style="position:absolute; top:215px; left:93%; opacity:0.8;" ></i></a>

        @if (\auth::user()->admin ==1 or \auth::user()->id_perfil ==2 or \auth::user()->id_perfil ==1 or \auth::user()->id_perfil ==11) 
        
        <a href="" class="fichaCompra"><i class="fa fa-file-text-o fa-1x text-teal" style="position:absolute; top:215px; left:85%; opacity:0.8;"  data-toggle="tooltip" data-placement="top" title="Ficha Compra" ></i></a>
        
        <a href="" class="fichaTecnica" data-modelo="AH1324" data-agrup="AH02 - ANA HICKMANN (RX)"><i class="fa fa-file-pdf-o fa-1x text-black" style="position:absolute; top:215px; left:80%; opacity:0.8;"  data-toggle="tooltip" data-placement="top" title="Ficha Tecnica" ></i></a>
        
        <a href="" class="fichaDesign" data-value="../../fotos/BAIXA/AH02 - ANA HICKMANN (RX)/AH1324 09B.jpg"><i class="fa fa-file-photo-o fa-1x text-teal" style="position:absolute; top:215px; left:75%; opacity:0.8;"  data-toggle="tooltip" data-placement="top" title="Ficha Design" ></i></a>
        
        <a href="" class="fichaTrade" data-modelo="AH1324" data-agrup="AH02 - ANA HICKMANN (RX)"><i class="fa fa-file-image-o fa-1x text-teal" style="position:absolute; top:215px; left:70%; opacity:0.8;"  data-toggle="tooltip" data-placement="top" title="Ficha Trade" ></i></a>
      @endif         
      
      </div>

      <div class="box-body">

        <div class="row">
          <div class="col-sm-6 col-md-6">
            @if (\auth::user()->admin ==1 or \auth::user()->id_perfil ==2 or \auth::user()->id_perfil ==1 or \auth::user()->id_perfil ==11) 
            <a href="" class="text-black alteraClasMod">{{$catalogo->clasmod}} @if ( \Auth::user()->admin == 1 )<a href="" class="alteraCaracteristica" data-tipo="modelo" data-caracteristica="classmod" data-value="{{$catalogo->id_item}}"><i class="fa fa-edit"></i></a>@endif</a>
            @endif
          </div>
          <div class="col-sm-6 col-md-6" align="right"><a href="" class="text-black">{{$catalogo->colecao}} @if ( \Auth::user()->admin == 1 )<a href="" class="alteraCaracteristica" data-tipo="modelo" data-caracteristica="colmod" data-value="{{$catalogo->id_item}}"><i class="fa fa-edit"></i></a>@endif</a></div>
        </div>
        @include('produtos.painel.info-compras')

        @include('produtos.painel.info-vendas')

        @include('produtos.painel.info-estoques')

       <table>
        <tr><b>Cores Mod:</b> {{ $catalogo->itens }} </tr>
        <tr><b>Cores Disp: </b>x</tr>
        <tr><b>Res ATP: </b>x</tr>
		</table>

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
@include('produtos.painel.modal.caracteristica')

@stop