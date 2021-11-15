@extends('produtos/painel/index')

@section('title')
  <i class="fa fa-list"></i> Produtos
@append 

@section('conteudo')
<div class="row">

  <div class="col-md-4">
    <span class="lead">Modelo</span>
    <div class="box box-widget">
      <div class="box-header with-border bg-gray"> 
        <b>{{$modeloagregado[0]->modelo}}</b>
        <span class="pull-right"><b></b></span>
      </div>
      <div align="center" style="min-height: 200px;margin-top: 30px;">


        @php
          $foto = app('App\Http\Controllers\ItemController')->consultaFoto($modeloagregado[0]->modelo);
        @endphp
		  

        <a href="" class="zoom" data-value="{{$modeloagregado[0]->modelo}}">
            <img src="/{{$foto}}" class="img-responsive">
        </a>
      </div>
		
		
	
		<div class="box-body">

        <div class="row">
          <div class="col-sm-6 col-md-6">
             <table class="table table-bordered" style="text-align: left;">
          <tr>
            <td class="text-danger"><i class="fa fa-chain-broken"></i><b> FALTA VINCULO</td>
            <td class="text-danger">{{$modeloagregado[0]->ttvinc-$modeloagregado[0]->ttagrup}} </b>
              
            </td>
          </tr> </table>
          </div>
          
        </div>
        @php
    
      $mesesforn = 2;
   
@endphp     
       
<div class="row" style="padding-bottom: 2px;">
    <div class="col-md-12">
        <table width="100%">
            <tr>
              <td>
                <table class="table table-condensed table-bordered table2" style="text-align: center;">
                    <tr>					
                      <td><i class="fa fa-heartbeat text-red"></i></td>
                      @if ((($modeloagregado[0]->vda30dd+$modeloagregado[0]->vda60dd)/2)=='0')
                        <td>{{number_format(0)}}</td>
                      @else
                      <td>
                        {{number_format((($modeloagregado[0]->brasil+$modeloagregado[0]->cet+$modeloagregado[0]->etq+$modeloagregado[0]->cep)-$modeloagregado[0]->orcamentos)/(($modeloagregado[0]->vda30dd+$modeloagregado[0]->vda60dd)/2))}} 
                      </td>
                      <td>6M</td>
                      <td>{{number_format((($modeloagregado[0]->vda30dd+$modeloagregado[0]->vda60dd)/2)*6)}}</td>

                      <td>C</td>
                      <td>{{number_format(((($modeloagregado[0]->vda30dd+$modeloagregado[0]->vda60dd)/2)*6)-(($modeloagregado[0]->brasil+$modeloagregado[0]->cet+$modeloagregado[0]->etq+$modeloagregado[0]->cep)-$modeloagregado[0]->orcamentos))}}
                      </td>

                        @endif
                      <td>E</td>
                      <td>{{number_format((($modeloagregado[0]->brasil+$modeloagregado[0]->cet+$modeloagregado[0]->etq+$modeloagregado[0]->cep)-$modeloagregado[0]->orcamentos))}}</td>
                    </tr>
                </table>
        </td>
    </tr>
</table>
</div>
</div>

        <div class="row" style="padding-bottom: 2px;">
    <div class="col-md-12">
        <table width="100%">
            <tr>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-shopping-cart text-green"></i></td>
                            
                            <td>
                              
                                <a href="/vendas_sint?modelo={{$modeloagregado[0]->modelo}}">{{number_format($modeloagregado[0]->vda30dd)}}/{{number_format($modeloagregado[0]->vendas)}}</a>
                            
                               
                            </td>
                            
                        </tr>
                    </table>

                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-line-chart text-blue"></i></td>
                            <td>{{number_format(($modeloagregado[0]->vda30dd+$modeloagregado[0]->vda60dd)/2)}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
@php
	if ($modeloagregado[0]->fornecedor = 'kering eyewear usa inc' ) {
		$mesesforn = 2;
	} else {
		$mesesforn = 6;
	}
@endphp							
                            <td><i class="fa fa-heartbeat text-red"></i></td>
							@if ((($modeloagregado[0]->vda30dd+$modeloagregado[0]->vda60dd)/2)=='0')
                            <td>{{number_format(0)}}</td>
							@else
							<td>{{number_format((($modeloagregado[0]->brasil+$modeloagregado[0]->cet+$modeloagregado[0]->etq+$modeloagregado[0]->cep)-$modeloagregado[0]->orcamentos)/(($modeloagregado[0]->vda30dd+$modeloagregado[0]->vda60dd)/2))}} 

                                {{-- /6M {{number_format((($modeloagregado[0]->vda30dd+$modeloagregado[0]->vda60dd)/2)*$mesesforn)}} / C
								
								{{number_format(((($modeloagregado[0]->vda30dd+$modeloagregado[0]->vda60dd)/2)*$mesesforn)-(($modeloagregado[0]->brasil+$modeloagregado[0]->cet+$modeloagregado[0]->etq+$modeloagregado[0]->cep)-$modeloagregado[0]->orcamentos))}} /
								E {{number_format((($modeloagregado[0]->brasil+$modeloagregado[0]->cet+$modeloagregado[0]->etq+$modeloagregado[0]->cep)-$modeloagregado[0]->orcamentos))}}
								 --}}
							</td>
                            @endif
							
                        </tr>
						
						
                    </table>
                </td>
				
		<td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
						
                        <tr>
                            <td><i class="fa fa-hourglass-3 text-purple"></i></td>
                            <td>{{number_format($modeloagregado[0]->orcamentos)}}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</div>

        <div class="row">
    <div class="col-md-12">
        <table width="100%" style="text-align: center;">
            <tr>
                <td>
                    <table class="table table-condensed table-bordered table2"  style="text-align: center;">
                        <tr>
                            <td align="center"><img src="/img/brasil.png" width="15"></i></td>
                            <td>{{number_format($modeloagregado[0]->brasil)}}</td>
                        </tr>
                    </table>

                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-plane text-blue"></i></td>
                            <td>{{number_format($modeloagregado[0]->cet)}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td align="center"><img src="/img/china.png" width="15"></i></td>
                            <td>{{number_format($modeloagregado[0]->etq)}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-industry text-purple"></i></td>
                            <td>{{number_format($modeloagregado[0]->cep)}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-wrench text-orange"></i></td>
                            <td>{{number_format($modeloagregado[0]->saldo_manutencao,0)}}</td>
                        </tr>
                    </table>
                </td>
               
				
            </tr>

		 <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td>Total</td>
                            <td>{{number_format($modeloagregado[0]->brasil+$modeloagregado[0]->cet+$modeloagregado[0]->etq+$modeloagregado[0]->cep)}}</td>
                        </tr>
                    </table>
                </td> 






        </table>


    </div>
</div>

       

      </div>
		
		
		
		
		
	
      <div class=" box-body">
        <div class="row">
          <div class="col-md-6">
           
        
          </div>
          <div class="col-md-6" align="right"> </div>
        </div>
		  
		  
        <table class="table table-bordered" style="text-align: left;">
          <tr>
            <td><i class="fa fa-th"></i> Tipo</td>
            <td>{{$modeloagregado[0]->tipoitem}} 
              
            </td>
          </tr>  
          <tr>
            <td><i class="fa fa-tag"></i> Grife</td>
            <td>{{$modeloagregado[0]->grife}} 
              
            </td>
          </tr>    
          <tr>
            <td><i class="fa fa-list"></i> Agrupamento</td>
            <td>{{$modeloagregado[0]->agrup}} 
              
            </td>
          </tr>
          <tr>
            <td><i class="fa fa-code-fork"></i> Linha</td>
            <td>{{$modeloagregado[0]->linha}} 
              
            </td>
          </tr>                   
			
        </table>
  
       
      </div>
    </div>
  </div>

  <div class="col-md-8">
    <span class="lead">Itens</span>
    <div class="row">
      @foreach ($itensagregado as $catalogo)

        @php
          switch ($catalogo->codstatusatual) {
            case 'DISP':
              $cor = 'green';
              break;
            case 'ESGOT':
              $cor = 'red';
              break;
            case '15D':
              $cor = 'blue';
              break;
            case '30D':
              $cor = 'yellow';
              break;
            case 'PROD':
              $cor = 'purple';
              break;              
            default:
              $cor = 'blue';

          }
        @endphp

      <div class="col-md-5">
        <div class="box box-widget">
          <div  class="box-header with-border" style="font-size:16px; padding: 12px 10px 12px 10px;"> 
          <b><a href="/painel/{{$catalogo->agrup}}/{{$catalogo->modelo}}/{{$catalogo->secundario}}" class="text-black">{{$catalogo->secundario}}</a></b>
          <span class="pull-right">R$ {{number_format($catalogo->valor,2,',','.')}}</span>
			  <span class="pull-right" ><i class="fa fa-cny" >{{number_format($catalogo->ultcusto,2,',','.')}}</i></span>
          
                
			
			</div>



        @php
          $foto = app('App\Http\Controllers\ItemController')->consultaFoto($catalogo->secundario);
        @endphp

          <div id="foto" align="center" style="min-height: 180px; max-height: 180px;">
            <a href="" class="zoom" data-value="{{$catalogo->secundario}}"><img src="/{{$foto}}" class="img-responsive"></a>

                  
          </div>
			
			
			  @if ($catalogo->ttvinc > 0 )
			<br>
			<table class="table table-bordered" style="text-align: left;">
          <tr>
			  <td class=""><i class="fa fa-chain"></i><b> Vinculado</td>
            <td class="">{{$catalogo->ttvinc}} </b>
				</td>
          </tr> </table>
            @endif
              
				
				@if ($catalogo->brasil < 0 and $catalogo->ttvinc > 0 )
				
				<a title="Sem estoque"  href="" class="zoom" data-value="{{$catalogo->secundario}}"><i class="fa fa-battery-0 text-red fa-3x" style="position:absolute; top:200px; left:5%; opacity:0.8;" ></i></a>
					
				
					@endif
					
					@if ($catalogo->mesesestoque <> 'sv' and $catalogo->mesesestoque < 3 and $catalogo->mesesestoque > 0 and $catalogo->ttvinc > 0)
				
				<a title="Estoque baixo"  href="" class="zoom" data-value="{{$catalogo->secundario}}"><i class="fa fa-battery-1 text-yellow fa-2x" style="position:absolute; top:200px; left:5%; opacity:0.8;" ></i></a>
					
				
					@endif
					
					@if ($catalogo->mesesestoque <> 'sv' and $catalogo->mesesestoque < 5 and $catalogo->mesesestoque > 3 and $catalogo->ttvinc > 0)
				
				<a title="Estoque ok"  href="" class="zoom" data-value="{{$catalogo->secundario}}"><i class="fa fa-battery-full text-green fa-2x" style="position:absolute; top:200px; left:5%; opacity:0.8;" ></i></a>
					
				
					@endif
					
					@if ($catalogo->mesesestoque <> 'sv' and $catalogo->mesesestoque > 5  and $catalogo->ttvinc > 0)
				
				<a title="Estoque alto" href="" class="zoom" data-value="{{$catalogo->secundario}}"><i class="fa fa-battery-full text-red fa-2x" style="position:absolute; top:200px; left:5%; opacity:0.8;" ></i></a>
					
				
					@endif
		
		@if ($catalogo->brasil > 0 and  $catalogo->ttvinc < 1)
		<br><br>
		 <a title="Com estoque sem vinculo" href="" class="zoom" data-value="{{$catalogo->secundario}}"><i class="fa fa-chain-broken text-red fa-3x" style="position:absolute; top:200px; left:5%; opacity:0.8;" ></i> </a>
				
			@endif
            
		@if($catalogo->brasil < 1 and  $catalogo->ttvinc < 1)
		
		 <a title="Revisar item"  href="" class="zoom" data-value="{{$catalogo->secundario}}"><i class="fa fa-warning text-orange fa-2x" style="position:absolute; top:200px; left:5%; opacity:0.8;" ></i></a>
		
		@endif
		
          <div class="box-body">
            <div class="row">
              <div class="col-md-8">{{$catalogo->clasitem}} <a href="" class="alteraCaracteristica" data-tipo="item" data-caracteristica="classitem" ><i ></i></a></div>
              <div class="col-md-4" align="right">{{$catalogo->anoitem}}</div>
				<div class="col-md-8" align="left" ><b>EAN</b>: {{$catalogo->ean}}</div> 
				
				
				 
				
				
			<div class="col-md-12" align="left" ><b>Descrição</b>: {{$catalogo->descricao}}</div> 
			<div class="col-md-12" align="left" title="Fornecedor" ><i class="fa fa-industry"> </i>{{$catalogo->fornecedor}}</div> 
			<div class="col-md-12" align="left" title="Material" ><i class="fa fa-th"></i> {{$catalogo->material}}</div> 
			
			
				
				 <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td align="center"><div class="col-md-12" align="left" title="Altura" > <i class="fa fa-arrows-v"></i>{{$catalogo->altura.' cm'}}</div> </td>
                            <td><div class="col-md-12" align="left" title="Largura" ><i class="fa fa-arrows-h"></i> {{$catalogo->largura.' cm'}}</div></td>
							<td><div class="col-md-12" align="left" title="Profundidade" ><i class="fa fa-arrows"></i>{{$catalogo->profundidade.' cm'}}</div></td>
                        </tr>
                    </table>
			
			
			
				
            </div>
            <div> 
            @php
          $mesesforn = 2;
   
@endphp     

        
<div class="row" style="padding-bottom: 2px;">
    <div class="col-md-12">
        <table width="100%">
            <tr>
              <td>
                <table class="table table-condensed table-bordered table2" style="text-align: center;">
                    <tr>					
                      <td><i class="fa fa-heartbeat text-red"></i></td>
                      @if ((($catalogo->vda30dd+$catalogo->vda60dd)/2)=='0')
                        <td>{{number_format(0)}}</td>
                      @else
                      <td>
                        {{number_format((($catalogo->brasil+$catalogo->cet+$catalogo->etq+$catalogo->cep)-$catalogo->orcamentos)/(($catalogo->vda30dd+$catalogo->vda60dd)/2))}} 
                      </td>
                      <td>6M</td>
                      <td>{{number_format((($catalogo->vda30dd+$catalogo->vda60dd)/2)*6)}}</td>

                      <td>C</td>
                      <td>{{number_format(((($catalogo->vda30dd+$catalogo->vda60dd)/2)*6)-(($catalogo->brasil+$catalogo->cet+$catalogo->etq+$catalogo->cep)-$catalogo->orcamentos))}}
                      </td>

                        @endif
                      <td>E</td>
                      <td>{{number_format((($catalogo->brasil+$catalogo->cet+$catalogo->etq+$catalogo->cep)-$catalogo->orcamentos))}}</td>
                    </tr>
                </table>
        </td>
    </tr>
</table>
</div>
</div>
            <div class="row" style="padding-bottom: 2px;">
    <div class="col-md-12">
        <table width="100%">
            <tr>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-shopping-cart text-green"></i></td>
                            
                            <td>
                              @if ( \Auth::user()->admin == 1  or  \Auth::user()->id_perfil == 11 
								or  \Auth::user()->id_perfil == 2 )
                                <a href="/vendas_sint?modelo={{$catalogo->modelo}}">{{number_format($catalogo->a_180dd)}}/{{number_format($catalogo->vendas)}}</a>
                              @else 
                                {{number_format($catalogo->a_180dd)}}/{{number_format($catalogo->vendas)}}
                              @endif 
                            </td>
                            
                        </tr>
                    </table>

                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-line-chart text-blue"></i></td>
                            <td>{{number_format(($catalogo->vda30dd+$catalogo->vda60dd)/2)}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
@php
	
		$mesesforn = 2;

@endphp							
                            <td><i class="fa fa-heartbeat text-red"></i></td>
							@if ((($catalogo->vda30dd+$catalogo->vda60dd)/2)=='0')
                            <td>{{number_format(0)}}</td>
							@else
							<td>{{number_format((($catalogo->brasil+$catalogo->cet+$catalogo->etq+$catalogo->cep)-$catalogo->orcamentos)/(($catalogo->vda30dd+$catalogo->vda60dd)/2))}} 

                                {{-- /6M {{number_format((($catalogo->vda30dd+$catalogo->vda60dd)/2)*$mesesforn)}} / C
								
								{{number_format(((($catalogo->vda30dd+$catalogo->vda60dd)/2)*$mesesforn)-(($catalogo->brasil+$catalogo->cet+$catalogo->etq+$catalogo->cep)-$catalogo->orcamentos))}} /
								E {{number_format((($catalogo->brasil+$catalogo->cet+$catalogo->etq+$catalogo->cep)-$catalogo->orcamentos))}}
								 --}}
							</td>
                            @endif
							
                        </tr>
						
						
                    </table>
                </td>
				
		<td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
						
                        <tr>
                            <td><i class="fa fa-hourglass-3 text-purple"></i></td>
                            <td>{{number_format($catalogo->orcamentos)}}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</div>
            <div class="row">
    <div class="col-md-12">
        <table width="100%" style="text-align: center;">
            <tr>
                <td>
                    <table class="table table-condensed table-bordered table2"  style="text-align: center;">
                        <tr>
                            <td align="center"><img src="/img/brasil.png" width="15"></i></td>
                            <td>{{$catalogo->brasil}}</td>
                        </tr>
                    </table>

                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-plane text-blue"></i></td>
                            <td>{{$catalogo->cet}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td align="center"><img src="/img/china.png" width="15"></i></td>
                            <td>{{$catalogo->etq}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-industry text-purple"></i></td>
                            <td>{{$catalogo->cep}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-wrench text-orange"></i></td>
                            <td>{{number_format($catalogo->saldo_manutencao,0)}}</td>
                        </tr>
                    </table>
                </td>
			
            </tr>
		  
		                  <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td>Total</td>
                            <td>{{number_format($catalogo->brasil+$catalogo->cet+$catalogo->etq+$catalogo->cep)}}</td>
                        </tr>
                    </table>
                </td>





        </table>


    </div>
</div>
            </div>
            <small><i class="fa fa-circle text-{{$cor}}" title="Disponibilidade"></i> {{$catalogo->statusatual}} </small>
            @if (\Auth::user()->id_perfil == 1) <a href="" class="novoPedido pull-right" data-value="{{$catalogo->secundario}}">Comprar</a> @endif
          </div>
        </div>
      </div>
      @endforeach

     

    </div>
  </div>

</div>






@stop