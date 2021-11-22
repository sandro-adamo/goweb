@extends('produtos/painel/index')

@section('title')
  <i class="fa fa-list"></i> Produtos
@append 

@section('conteudo')
<div class="row">

 
  <div class="col-md-12">
    <span class="lead">Modelos</span>
    <div class="row">
      @foreach ($itensagregado1 as $catalogo)

        @php
          switch ($catalogo->imediata) {
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

      <div class="col-md-2">
        <div class="box box-widget">
          <div  class="box-header with-border" style="font-size:14px; padding: 12px 10px 12px 10px;"> 
          <b><a href="/painel/{{$catalogo->agrup}}/{{$catalogo->modelo}}" class="text-black">{{$catalogo->modelo}}</a></b>
          <span class="pull-right">  {{$catalogo->classif}}</span>
			  <span class="pull-right">{{$catalogo->colmod}}</span>
			 
          
                
			
			</div>



        @php
          $foto = app('App\Http\Controllers\ItemController')->consultaFoto($catalogo->modelo);
        @endphp

          <div id="foto" align="center" style="min-height: 100px; max-height: 100px;">
            <a href="" class="zoom" data-value="{{$catalogo->modelo}}"><img src="/{{$foto}}" class="img-responsive"></a>

                  
          </div>
			
			
			  @if ($catalogo->itens > 0 )
			<br>
			<table class="table table-bordered" style="text-align: left;">
          <tr>
			  <td class=""><i class="fa fa-chain"></i><b> Itens</td>
            <td class="">{{$catalogo->itens}} </b>
				</td>
          </tr> </table>
            @endif
              
				

		
		@if ($catalogo->imediata > 0 and  $catalogo->imediata < 1)
		<br><br>
		 <a title="Com estoque sem vinculo" href="" class="zoom" data-value="{{$catalogo->modelo}}"><i class="fa fa-chain-broken text-red fa-3x" style="position:absolute; top:200px; left:5%; opacity:0.8;" ></i> </a>
				
			@endif
            
		@if($catalogo->imediata >= 1 and  $catalogo->futura >= 1)
		
		 <a title="Revisar item"  href="" class="zoom" data-value="{{$catalogo->modelo}}"><i class="fa fa-warning text-orange fa-2x" style="position:absolute; top:50px; left:5%; opacity:0.8;" ></i></a>
		
		@endif
		
          <div class="box-body">
           <!-- linha 452--> 
			  
			  
<div class="row">
    <div class="col-md-12">
        <table width="100%" style="text-align: center;">
            <tr>

			 <td>
                    <table class="table table-condensed table-bordered table2"  style="text-align: center;">
                        <tr>
                            <td align="center"><img src="/img/brasil.png" width="15"></i></td>
                            <td>{{number_format($catalogo->imediata)}}</td>
                        </tr>
                    </table>

                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-plane text-blue"></i></td>
                            <td>{{number_format($catalogo->futura)}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-industry text-purple"></i></td>
                            <td>{{number_format($catalogo->producao)}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-warning text-yellow"></i></td>
                            <td>{{number_format($catalogo->esgotado)}}</td>
                        </tr>
                    </table>
                </td>
 				
            </tr>




        </table>


    </div>
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
                            <td><i class="fa fa-shopping-cart text-green"></i></td>
                            
                            <td>
                              @if ( \Auth::user()->admin == 1  or  \Auth::user()->id_perfil == 11 
								or  \Auth::user()->id_perfil == 2 )
                                <a href="/vendas_sint?modelo={{$catalogo->modelo}}">{{number_format($catalogo->qtde_tt)}}/{{number_format($catalogo->qtde_30)}}</a>
                              @else 
                                {{number_format($catalogo->qtde30)}}
                              @endif 
                            </td>
                            
                        </tr>
                    </table>

                </td>

				
				<td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
						
                        <tr>
                            <td><i class="fa fa-hourglass-3 text-purple"></i></td>
                            <td>{{number_format($catalogo->imediata)}}</td>
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
                                <a href="/vendas_sint?modelo={{$catalogo->modelo}}">{{number_format($catalogo->qtde_tt)}}/{{number_format($catalogo->qtde_30)}}</a>
                              @else 
                                {{number_format($catalogo->qtde30)}}
                              @endif 
                            </td>
                            
                        </tr>
                    </table>

                </td>

				
				<td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
						
                        <tr>
                            <td><i class="fa fa-hourglass-3 text-purple"></i></td>
                            <td>{{number_format($catalogo->imediata)}}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</div>
				
				
				

            </div>
          </div>
        </div>
      </div>
      @endforeach

     

    </div>
  </div>

</div>






@stop