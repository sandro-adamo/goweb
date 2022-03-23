@extends('produtos/painel/index')

@section('titulo') {{$modelo->modelo}} @append

@section('title')
  <i class="fa fa-list"></i> Produtos
@append 
@section('conteudo')
@php
$N = '';

	if($modelo->modelo == 'AH1373' or 
 $modelo->modelo == 'AH1374' or 
 $modelo->modelo == 'AH1385' or 
 $modelo->modelo == 'AH1386' or 
 $modelo->modelo == 'AH1396' or 
 $modelo->modelo == 'AH1397' or 
 $modelo->modelo == 'AH3231' or 
 $modelo->modelo == 'AH6363' or 
 $modelo->modelo == 'AH6364' or 
 $modelo->modelo == 'AH6366I' or 
 $modelo->modelo == 'AH6367I' or 
 $modelo->modelo == 'AH6368' or 
 $modelo->modelo == 'AH6372' or 
 $modelo->modelo == 'AH6381' or 
 $modelo->modelo == 'AH6382' or 
 $modelo->modelo == 'AH6402' or 
 $modelo->modelo == 'AH6403' or 
 $modelo->modelo == 'AH6413' or 
 $modelo->modelo == 'AH6414' or 
 $modelo->modelo == 'AH6415' or 
 $modelo->modelo == 'AH6421' or 
 $modelo->modelo == 'H6176' or 
 $modelo->modelo == 'HI1130' or 
 $modelo->modelo == 'HI1139' or 
 $modelo->modelo == 'HI1140' or 
 $modelo->modelo == 'HI6170F' or 
 $modelo->modelo == 'HI6175' or 
 $modelo->modelo == 'HI6176' or 
 $modelo->modelo == 'HI6185' or 
 $modelo->modelo == 'HI6186' or
 $modelo->modelo == 'HI6186' or 
 $modelo->modelo == 'AH9295' or 
 $modelo->modelo == 'AH9294' or 
 $modelo->modelo == 'AH3231' or 
 $modelo->modelo == 'AH3232' or 
 $modelo->modelo == 'AH6407' or 
 $modelo->modelo == 'AH6428' or 
 $modelo->modelo == 'AH6387' or 
 $modelo->modelo == 'AH6388' or 
 $modelo->modelo == 'AH6383' or 
 $modelo->modelo == 'AH6384' or
$catalogo->modelo == 'AH1423'or
$catalogo->modelo == 'HI1130'or
$catalogo->modelo == 'HI1139'
 

){
 $N = ' N';

}

@endphp
<div class="row">

  <div class="col-md-4">
    <span class="lead">Modelo</span>
    <div class="box box-widget">
      <div class="box-header with-border bg-gray"> 
        <b>{{$modelo->modelo}} <font color="red">{{$N}}</font></b>
        <span class="pull-right">
              @if ( \Auth::user()->admin == 1 ) 
              <span class="pull-right">
                <a href="" class="alteraPreco" data-tipo="modelo" data-value="{{$modelo->id_item}}"><i class="fa fa-edit"></i></a>
              </span>
              @endif

              @php
                $alteracoes_preco = \DB::select("select * from autorizacoes where id_item = $modelo->id_item and status = 0 order by id desc");
			
              @endphp

              @if ($alteracoes_preco) 
                <span class="pull-right text-orange text-bold"><b> <i class="fa fa-warning"></i> R$ {{number_format($alteracoes_preco[0]->valor,2,',','.')}}   </b></span>
              @else
                <span class="pull-right"><b> R$ {{number_format($catalogo->valor,2,',','.')}}  </b></span>
              @endif

                        <b></b>

        </span>
      </div>
      <div align="center" style="min-height: 200px;margin-top: 30px;">
 @php
    $id_usuario = \Auth::id();
    $favoritos = \DB::select("select * from favoritos where modelo = '$modelo->modelo' and id_usuario = '$id_usuario'");
  @endphp

        @php
          $foto = app('App\Http\Controllers\ItemController')->consultaFoto($modelo->modelo);

          $outras_cores = \DB::select("select secundario from itens where modelo = '$modelo->modelo' and codtipoarmaz not in ('o','i')");

          $i = 0;
        @endphp
 
               <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                  <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>

                  @foreach ($outras_cores as $cor)
                    @php
                      $i++;
                    @endphp
                  <li data-target="#carousel-example-generic" data-slide-to="{{$i}}" class=""></li>
                  @endforeach
                </ol>
                <div class="carousel-inner">
                  <div class="item active">
                    <img src="/{{$foto}}" class="img-responsive">
                  </div>
                  @foreach ($outras_cores as $cor)
                    @php
                      $foto = app('App\Http\Controllers\ItemController')->consultaFoto($cor->secundario);
					
                    @endphp
					
                    @if ($foto <> 'fotos/nopicture.jpg')
                    <div class="item">
                        <img src="/{{$foto}}" class="img-responsive">
                    </div>
                    @endif
                  @endforeach 


                </div>
                <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                  <span class="fa fa-angle-left"></span>
                </a>
                <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                  <span class="fa fa-angle-right"></span>
                </a>
              </div>
{{--         <a href="" class="zoom" data-value="{{$modelo->modelo}}">
            <img src="/{{$foto}}" class="img-responsive">
        </a> --}}

 <a href="" class="addFavoritos" data-value="{{$modelo->modelo}}">
          @if ($favoritos)
            <i class="fa fa-heart text-red fa-2x" style="position:absolute; top:40px; left:90%; opacity:0.8;" ></i>
          @else 
            <i class="fa fa-heart-o text-red fa-2x" style="position:absolute; top:40px; left:90%; opacity:0.8;" ></i>
          @endif
        </a>

      </div>
      <div class=" box-body">
        <div class="row">
          <div class="col-md-6">
            @if ( \Auth::user()->admin == 1 or \Auth::user()->id_perfil == 2 or \auth::user()->id_perfil ==25) {{$modelo->clasmod}} <a href="" class="alteraCaracteristica" data-tipo="modelo" data-caracteristica="clasmod" data-value="{{$modelo->id_item}}"><i class="fa fa-edit"></i></a> 
            @endif
          </div>
			
			
			
			
			
			
          <div class="col-md-6" align="right">{{$modelo->colecao}} @if ( \Auth::user()->admin == 1 )<a href="" class="alteraCaracteristica" data-tipo="modelo" data-caracteristica="colmod" data-value="{{$modelo->id_item}}"><i class="fa fa-edit"></i></a>@endif</div>
        </div>
         @if ( \Auth::user()->admin == 1 or \auth::user()->id_perfil ==25) 
        <a href="/painel/imprimir/{{$modelo->modelo}}">Imprimir</a>
        @endif
		   <div> 
            @include('produtos.painel.info-compras')
            @include('produtos.painel.info-vendas')
            @include('produtos.painel.info-estoques')

            </div>
            <div>
      @if (\Auth::user()->id_perfil == 1 or \Auth::user()->id_perfil == 2 or \auth::user()->id_perfil ==25) 
                
                 <b>{{'Pedido aberto '}}</b>{{$modelo->qtde_compra}}
                @else  <b>{{'Pedido aberto '}}</b>{{0}}
        @endif  
      </div>
        <table class="table table-bordered" style="text-align: left;">
		
          <tr>
            <td><i class="fa fa-th"></i> Tipo</td>
            <td>{{$modelo->tipoitem}} 
              @if ( \Auth::user()->admin == 1 or \auth::user()->id_perfil ==25) 
              <span class="pull-right">
                <a href="" class="alteraCaracteristica" data-caracteristica="tipoitem" data-tipo="modelo" data-value="{{$modelo->id_item}}"><i class="fa fa-edit"></i></a>
              </span>
              @endif
            </td>
          </tr>  
          <tr>
            <td><i class="fa fa-tag"></i> Grife</td>
            <td>{{$modelo->grife}} 
              @if ( \Auth::user()->admin == 1 or \auth::user()->id_perfil ==25) 
              <span class="pull-right"><a href="" class="alteraCaracteristica" data-tipo="modelo"  data-caracteristica="grife" data-value="{{$modelo->id_item}}"><i class="fa fa-edit"></i></a></span>
              @endif
            </td>
          </tr>    
          <tr>
            <td><i class="fa fa-list"></i> Agrupamento</td>
            <td>{{$modelo->agrup}} 
              @if ( \Auth::user()->admin == 1 or \auth::user()->id_perfil ==25) 
              <span class="pull-right"><a href="" class="alteraCaracteristica" data-tipo="modelo" data-caracteristica="agrupamento" data-value="{{$modelo->id_item}}"><i class="fa fa-edit"></i></a></span>
              @endif
            </td>
          </tr>
          <tr>
            <td><i class="fa fa-code-fork"></i> Linha</td>
            <td>{{$modelo->linha}} 
              @if ( \Auth::user()->admin == 1 or \auth::user()->id_perfil ==25) 
              <span class="pull-right"><a href="" class="alteraCaracteristica" data-tipo="modelo" data-caracteristica="linha" data-value="{{$modelo->id_item}}"><i class="fa fa-edit"></i></a></span>
              @endif
            </td>
          </tr>   

          @if ( \Auth::user()->admin == 1 or \auth::user()->id_perfil ==25) 
          <tr>
            <td><i class="fa fa-industry"></i> Fornecedor</td>
            <td>{{$modelo->fornecedor2}} 
              @if ( \Auth::user()->admin == 1 or \auth::user()->id_perfil ==25) 
               <span class="pull-right"><a href="" class="alteraCaracteristica" data-tipo="modelo"  data-caracteristica="fornecedor" data-value="{{$modelo->id_item}}"><i class="fa fa-edit"></i></a></span>
              @endif
            </td>
          </tr>    
          @endif
          <tr>
            <td><i class="fa fa-intersex"></i> Genêro</td>
            <td>{{$modelo->genero}} 
              @if ( \Auth::user()->admin == 1 or \auth::user()->id_perfil ==25) 
              <span class="pull-right"><a href="" class="alteraCaracteristica" data-tipo="modelo"  data-caracteristica="genero" data-value="{{$modelo->id_item}}"><i class="fa fa-edit"></i></a></span>
              @endif
            </td>
          </tr>   
          <tr>
            <td><i class="fa fa-child"></i> Idade</td>
            <td>{{$modelo->idade}} 
              @if ( \Auth::user()->admin == 1 or \auth::user()->id_perfil ==25) 
              <span class="pull-right"><a href="" class="alteraCaracteristica" data-tipo="modelo"  data-caracteristica="idade" data-value="{{$modelo->id_item}}"><i class="fa fa-edit"></i></a></span>
              @endif
            </td>
          </tr>    
          <tr>
            <td><i class="fa fa-wrench"></i> Material</td>
            <td>{{$modelo->material}} 
              @if ( \Auth::user()->admin == 1 or \auth::user()->id_perfil ==25) 
              <span class="pull-right"><a href="" class="alteraCaracteristica" data-tipo="modelo"  data-caracteristica="material" data-value="{{$modelo->id_item}}"><i class="fa fa-edit"></i></a></span>
              @endif
            </td>
          </tr>                                           
          <tr>
            <td><i class="fa fa-link"></i> Fixação</td>
            <td>{{$modelo->fixacao}} 
              @if ( \Auth::user()->admin == 1 or \auth::user()->id_perfil ==25) 
              <span class="pull-right"><a href="" class="alteraCaracteristica" data-tipo="modelo"  data-caracteristica="fixacao" data-value="{{$modelo->id_item}}"><i class="fa fa-edit"></i></a></span>
              @endif
            </td>
          </tr>   
          <tr>
            <td><i class="fa fa-user-secret"></i> Estilo</td>
            <td>{{$modelo->estilo}} 
              @if ( \Auth::user()->admin == 1 or \auth::user()->id_perfil ==25 or \auth::user()->id ==525) 
              <span class="pull-right"><a href="" class="alteraCaracteristica" data-tipo="modelo"  data-caracteristica="estilo" data-value="{{$modelo->id_item}}"><i class="fa fa-edit"></i></a></span>
              @endif
            </td>
          </tr> 
			<tr>
            <td><i class="fa  fa-magic"></i> Tecnologia</td>
            <td>{{$modelo->tecnologia}} 
              @if ( \Auth::user()->admin == 1 or \auth::user()->id_perfil ==25) 
              <span class="pull-right"><a href="" class="alteraCaracteristica" data-tipo="modelo"  data-caracteristica="tecnologia" data-value="{{$modelo->id_item}}"><i class="fa fa-edit"></i></a></span>
              @endif
            </td>
          </tr> 
			<tr>
            <td><i class="fa   fa-tripadvisor"></i> Tamanho Olho</td>
            <td>{{$modelo->tamolho}} 
              @if ( \Auth::user()->admin == 1 or \auth::user()->id_perfil ==25) 
              <span class="pull-right"><a href="" class="alteraCaracteristica" data-tipo="modelo"  data-caracteristica="tamolho" data-value="{{$modelo->id_item}}"><i class="fa fa-edit"></i></a></span>
              @endif
            </td>
          </tr> 
				<tr>
            <td><i class="fa fa-code-fork"></i> Tamanho Haste</td>
            <td>{{$modelo->tamhaste}} 
              @if ( \Auth::user()->admin == 1 or \auth::user()->id_perfil ==25) 
              <span class="pull-right"><a href="" class="alteraCaracteristica" data-tipo="modelo"  data-caracteristica="tamhaste" data-value="{{$modelo->id_item}}"><i class="fa fa-edit"></i></a></span>
              @endif
            </td>
          </tr> 
				<tr>
            <td><i class="fa fa-map-signs"></i> Tamanho Ponte</td>
            <td>{{$modelo->tamponte}} 
              @if ( \Auth::user()->admin == 1 or \auth::user()->id_perfil ==25) 
              <span class="pull-right"><a href="" class="alteraCaracteristica" data-tipo="modelo"  data-caracteristica="tamponte" data-value="{{$modelo->id_item}}"><i class="fa fa-edit"></i></a></span>
              @endif
            </td>
          </tr> 
			
			
        </table>
  
        @if ( \Auth::user()->admin == 1 or \auth::user()->id_perfil ==25) 

        <label>CICLO DE COLEÇÕES (quando sera substituido)</label>
        <div class="row">
        @foreach ($colecoes as $colecao)
          <div class="col-md-3">
            <input type="checkbox" class="cicloColecao" data-modelo="{{$modelo->modelo}}" data-colecao="{{$colecao->valor}}" @if (App\CicloColecao::verificaCiclo($modelo->modelo, $colecao->valor)) checked @endif> {{ $colecao->valor }}
          </div>
        @endforeach
        </div>
        @endif

      </div>
    </div>
  </div>

  <div class="col-md-8">
    <span class="lead">Itens</span>
    <div class="row">
      @foreach ($itens as $catalogo)

        @php
          switch ($catalogo->codstatusatual) {
            case 'DIS':
              $cor = 'green';
              break;
            case 'ESG':
              $cor = 'red';
              break;
            case '15D':
              $cor = 'blue';
              break;
            case '30D':
              $cor = 'yellow';
              break;
            case 'PRO':
              $cor = 'purple';
              break;              
            default:
              $cor = 'blue';

          }
        @endphp

      <div class="col-lg-4 col-md-6">
        <div class="box box-widget">
          <div  @if ($catalogo->id == '')  class="box-header with-border bg-info" @else class="box-header with-border"  @endif  style="font-size:16px; padding: 3px 8px 3px 8px;" > 
          @if (Session::has('novocatalogo'))
            <input type="checkbox" name="item" class="addItemCatalogo" @if (\App\Catalogo::verificaItemHabilitado($catalogo->secundario) == true) checked @endif value="{{$catalogo->secundario}}">
          @endif
           @if ( \Auth::user()->admin == 1 or \auth::user()->id_perfil ==25) 

          <b><a  href="/painel/{{$catalogo->agrup}}/{{$catalogo->modelo}}/{{$catalogo->secundario}}" class="text-black">{{$catalogo->secundario}}</a></b>
          @else
			  <b><a   class="text-black">{{$catalogo->secundario}}</a></b></br>
           @endif


          <span class="pull-right">
            @if ($catalogo->id <> '')
              <b>R$ {{number_format($catalogo->valor,2,',','.')}}/R$ {{number_format($catalogo->valorsugerido,2,',','.')}}  @if ( \Auth::user()->admin == 1 ) <i href="" class="text-danger">{{$catalogo->moeda}}{{ number_format($catalogo->custo_2019,2,',','.') }}</i> @endif</b>
            @else
              <small><a href="" class="text-red"><i class="fa fa-trash"></i> Excluir</a></small>
            @endif
          </span>
          
          @if ( (\Auth::user()->admin == 1 or \Auth::user()->id_perfil ==2 or \Auth::user()->id_perfil ==1 or \auth::user()->id_perfil ==17 or \auth::user()->id_perfil ==25) and $catalogo->historico <> 0)
		        <span data-toggle="tooltip" title="{{$catalogo->historico}} Históricos" class="badge bg-yellow">{{$catalogo->historico}}</span>
			    @endif
                
			
			</div>



        @php
          $foto = app('App\Http\Controllers\ItemController')->consultaFoto($catalogo->secundario);
        @endphp

          <div id="foto" align="center" style="min-height: 180px; max-height: 180px;">
            <a href="" class="zoom" data-value="{{$catalogo->secundario}}"><img src="/{{$foto}}" class="img-responsive" style="max-height: 180px;"></a>
             @if ( \Auth::user()->admin == 1 or \auth::user()->id_perfil ==25) 
        @if ($catalogo->adv == 'sim') 
          <a href="/painel/campanhas/{{$catalogo->secundario}}"><i class="fa fa-camera text-purple fa-2x" style="position:absolute; top:40px; left:5%; opacity:0.8;" ></i></a>
        @else 
          <a href="/painel/campanhas/{{$catalogo->secundario}}"><i class="fa fa-camera text-gray fa-2x" style="position:absolute; top:40px; left:5%; opacity:0.8;" ></i></a>
        @endif  


        @if ($catalogo->midia == 'sim') 
          <a href="/painel/midias/{{$catalogo->secundario}}"><i class="fa fa-instagram text-blue fa-2x" style="position:absolute; top:40px; left:20%; opacity:0.8;" ></i></a>
        @else 
          <a href="/painel/midias/{{$catalogo->secundario}}"><i class="fa fa-instagram text-gray fa-2x" style="position:absolute; top:40px; left:20%; opacity:0.8;" ></i></a>
        @endif
        @endif

        @if ($catalogo->recall == 'sim') 
          <a href="" class="zoom" data-value="{{$catalogo->secundario}}"><i class="fa fa-warning text-orange fa-2x" style="position:absolute; top:200px; left:5%; opacity:0.8;" ></i></a>
        @endif               
          </div>
          <div class="box-body">
             @if ( \Auth::user()->admin == 1 or \auth::user()->id_perfil ==25) 
            <a href="" class="uploadFoto" data-value="{{$catalogo->secundario}}" data-tipo="item"><i class="fa fa-upload" style="position:absolute; top:215px; left:5%; opacity:0.8;"  data-toggle="tooltip" data-placement="top" title="Upload Foto" ></i></a>
            @endif

            <div class="row">
              <div class="col-md-8">@if ( \Auth::user()->admin == 1 or \auth::user()->id_perfil ==25) {{$catalogo->clasitem}} <a href="" class="alteraCaracteristica" data-tipo="item" data-caracteristica="clasitem" data-value="{{$catalogo->id}}"><i class="fa fa-edit"></i></a>@endif</div>
              <div class="col-md-4" align="right">{{$catalogo->colitem}} @if ( \Auth::user()->admin == 1 or \auth::user()->id_perfil ==25)<a href="" class="alteraCaracteristica" data-tipo="item" data-caracteristica="colitem" data-value="{{$catalogo->id}}"><i class="fa fa-edit"></i></a>@endif</div>
				<div class="col-md-8" align="left" ><b>EAN</b>: {{$catalogo->ean}}</div> 
				
				
				
				<div><a href="/itens_clientes?item={{$catalogo->secundario}}"><i class="fa fa-users"></i></i></a></div>
			 
				
				@if ($modelo->grife=="EVOKE") 
				
					<div class="col-md-12" align="left" ><b>Descrição</b>: {{$catalogo->descricao}}</div>@endif 
        @if ($catalogo->ordem=="2") 
        
          <div class="col-md-12" align="left" ><b>Descrição</b>: {{$catalogo->descricao}}</div>@endif 
				@if (\App\PermissaoUsuario::verificaPermissao(Auth::id(), 'estoques', 1))  	
				<div class="col-md-12" align="left" >

          <span class="pull-left"><b>Tipo Armaz:</b> {{' '.$catalogo->tipoarmazenamento.'  '}}<a href="" class="alteraCaracteristica" data-tipo="item"  data-caracteristica="tipoarmazenamento" data-value="{{$catalogo->id}}"><i class="fa fa-edit"></i></a></span>
          <b> / Curto</b>: {{$catalogo->id}}
        </div>
				
				@endif
	
				@if ($catalogo->codfornecedor == '47663' and $catalogo->disponivel_venda < 0)
				     <div class="col-md-12" align="left" ><b>Saldo</b>: 0</div> 
				
				@elseif ($catalogo->codfornecedor == '47663' and $catalogo->disponivel_venda >10)
						<div class="col-md-12" align="left" ><b>Saldo</b>:maior que 10</div>

				@elseif ($catalogo->codfornecedor == '47663' and $catalogo->disponivel_venda < 10)
						 <div class="col-md-12" align="left" ><b>Saldo</b>:{{number_format($catalogo->disponivel_venda)}}</div>
						 
						
            @endif
			
				@if ($catalogo->codfornecedor == '47663'  and $catalogo->etq > 30)
				     <div class="col-md-12" align="left" ><b>Com saldo na Kering</b>: </div>
				
				@elseif ($catalogo->codfornecedor == '47663'  and $catalogo->etq < 30)
						<div class="col-md-12" align="left" ><b>Sem saldo na Kering</b></div>
				
						 
						
             @endif
			
			
				
				
         
			@if ($catalogo->codfornecedor <> '47663'  and $catalogo->clasmod == 'COLECAO B' and $catalogo->disponivel_venda < 0)
				     <div class="col-md-12" align="left" ><b>Saldo</b>: 0</div>
				
				@elseif ($catalogo->codfornecedor <> '47663'  and  $catalogo->clasmod == 'COLECAO B' and $catalogo->disponivel_venda >10)
						<div class="col-md-12" align="left" ><b>Saldo</b>:maior que 10</div>
				@elseif ($catalogo->codfornecedor <> '47663'  and $catalogo->clasmod == 'COLECAO B'and $catalogo->disponivel_venda < 10)
						 <div class="col-md-12" align="left" ><b>Saldo</b>:{{number_format($catalogo->disponivel_venda)}}</div>
						 
				
				
			@endif
				
				
            </div>
	
            <div> 
            @include('produtos.painel.info-compras')
            @include('produtos.painel.info-vendas')
            @include('produtos.painel.info-estoques')
            </div>
            <small><i class="fa fa-circle text-{{$cor}}"></i> {{$catalogo->statusatual}} @if ( \Auth::user()->admin == 1 or \auth::user()->id_perfil ==25)<a href="" class="alteraCaracteristica" data-tipo="item" data-caracteristica="status" data-value="{{$catalogo->id}}"><i class="fa fa-edit"></i></a>@endif</small>
            @if (\Auth::user()->id_perfil == 1 or \Auth::user()->id_perfil == 2 or \auth::user()->id_perfil ==25) <a href="" class="novoPedido pull-right" data-value="{{$catalogo->secundario}}"><i title="Pedido em aberto" class="fa   fa-pencil text-blue"> 
								@if (isset($catalogo->pedidoaberto))
								{{$catalogo->pedidoaberto}}
								@else {{$catalogo->qtde_compra}}
			  @endif </i> Comprar</a> @endif
          </div>
        </div>
      </div>
      @endforeach

      @if ( \Auth::user()->admin == 1 or \Auth::user()->id_perfil == 2 or \auth::user()->id_perfil ==25) 
      <div class="col-md-4" align="center" style="height: 300px;"><a href="" data-toggle="modal" data-target="#modalNovoItem" ><i class="fa fa-plus fa-4x" style="margin-top: 80px;"></i><br> Novo Item</a></div>
      @endif

    </div>

@php
  
  $pedidos = \DB::select("select compras.id, id_fornecedor, nome as fornecedor 
                              from compras 
                              left join addressbook on id_fornecedor = addressbook.id
                              left join itens on itens.codfornecedor = addressbook.id
							left join compras_itens on compras.id = compras_itens.id_compra
                              where  modelo = '$modelo->modelo'  and (compras_itens.status = 'Aberto' or compras.status = 'Aberto')
                              group by compras.id, id_fornecedor, razao 
                              order by compras.id desc");


@endphp



<form action="" method="post" id="frmNovoRepedido">
<div class="modal fade" id="modalNovoRepedido" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Novo Repedido</h4>
      </div>
      <div class="modal-body">

          <div class="row"> 
          @foreach ($pedidos as $pedido)

            @php

              $fornecedor = explode(' ', $pedido->fornecedor);
              $datapedido = date('Y-m-d', strtotime('+5 days'));

            @endphp


            <a href="" class="selPedido" data-value="{{$pedido->id}}">
              <div class="col-md-2 thumbnail pedido" align="center" id="pedido{{$pedido->id}}"> 
                {{$pedido->id}}<br>
                {{$fornecedor[0]}}
              </div>
            </a>

          @endforeach 
          </div>
      
          <div class="row">      
              <div class="col-md-3" align="center">  
                  <div id="foto"></div>
                  <div id="referencia" class="lead"></div>
              </div>
              <div class="col-md-8">                  
{{--                     <div class="row">    
                      <div id="novoItem" >  
                            <div class="col-md-3">
                                <label for="qtde"><small>Modelo</small></label>
                                <input type="text" class="form-control campos" name="modelo" id="modelo">
                            </div>
                            <div class="col-md-3">
                                <label for="qtde"><small>Cor</small></label>
                                <input type="text" class="form-control campos" name="novacor" id="novacor">
                            </div>
                        </div>
                      </div> --}}
                      <div class="row">
                        <div class="col-md-3">
                            <label for="qtde"><small>Qtde</small></label>
                            <input type="text" class="form-control campos" name="qtde" id="qtde">
                            <input type="hidden" class="ukey campos" placeholder="item" name="item" id="item">
                            <input type="hidden" class="ukey campos" placeholder="id_compra" name="id_compra" id="id_compra">
                            <input type="hidden" class="ukey campos" placeholder="id_compra_item" name="id_compra_item" id="id_compra_item">
                            <input type="hidden" class="ukey campos" placeholder="id_modelo" name="id_modelo" id="id_modelo">
                        </div>
                        <div class="col-md-4">
                            <label for="data_entrega"><small>Data Entrega2 </small></label>
                            <input type="date"  class="form-control campos" name="data_entrega" id="data_entrega" >
                        </div>
                        <div class="col-md-2">
                            <br/>
                            <button type="submit" name="salvar" class="btn btn-primary btn-flat pull-right">Salvar</button>
                        </div>
                    </div>
                    <div class="row">        
                        <div class="col-md-12">
                            <label for="obs"><small>Observações</small></label>
                            <textarea class="form-control campos" name="obs" id="obs"></textarea>
                        </div>
                    </div>    
            </div>                                             
            <div class="col-md-1">


            </div>
        </div>

        <br />
        <table class="table table-condensed table-bordered" >
        <thead>
        <tr id="dadosPedido">
            <th>ID ITEM</th> 
            <th>ID PEDIDO</th> 
            <th>STATUS</th>
            <th>TIPO</th>  
            <th>DATA</th>
            <th>QTDE SOL</th>
            <th>QTDE CONF</th>
			<th>DT ENTREGA</th>
            <th>QTDE ENTREGUE</th>
            <th width="30%">OBS</th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
<!--
      // os dados são gerados no comprascontroler listaPedidosItem
		//	abre em public js compras function listaPedidosItem retorna os dados para esse ponto da página
-->
        </tbody>
    </table>        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
</form>


 

@include('produtos.painel.modal.pecaspassiveis')
@include('produtos.painel.modal.novoItem')

@include('produtos.painel.modal.genero')
@include('produtos.painel.modal.caracteristica')
@include('produtos.painel.modal.preco')
@include('produtos.painel.modal.uploadFoto')

@php
$path = "/var/www/html/fotos/MODELO/AH*";
// $diretorio = dir($path);
 
// echo "Lista de Arquivos do diretório '<strong>".$path."</strong>':<br />";
// while($arquivo = $diretorio -> read()){
// echo "<a href='".$path.$arquivo."'>".$arquivo."</a><br />";
// }
// $diretorio -> close();
@endphp

@stop