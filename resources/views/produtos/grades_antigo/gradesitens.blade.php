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
        <b>{{$modeloagregado[0]->grife}}</b>
        <span class="pull-right"><b></b></span>
      </div>
      <div align="center" style="min-height: 200px;margin-top: 30px;">


        @php
          $foto = app('App\Http\Controllers\ItemController')->consultaFoto($modeloagregado[0]->grife);
        @endphp
		  

        <a href="" class="zoom" data-value="{{$modeloagregado[0]->grife}}">
           <!-- <img src="/{{$foto}}" class="img-responsive"> -->
			<img src="/img/marcas/{{$modeloagregado[0]->grife}}.png" style="max-height: 250px;" class="img-responsive">
        </a>
      </div>
		
		
	
		<div class="box-body">

        <div class="row">
          <div class="col-sm-6 col-md-6">
             <table class="table table-bordered" style="text-align: left;">
          <tr>
            <td class="text-danger"><i class="fa fa-chain-broken"></i><b> FALTA VINCULO</td>
            <td class="text-danger">{{$modeloagregado[0]->grife}} </b>
              
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
                      
                      <td>E</td>
                      <td>{{$modeloagregado[0]->grife}}</td>
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
                              
                                <a href="/vendas_sint?modelo={{$modeloagregado[0]->grife}}">{{$modeloagregado[0]->agrup}}</a>
                            
                               
                            </td>
                            
                        </tr>
                    </table>

                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-line-chart text-blue"></i></td>
                            <td>{{$modeloagregado[0]->codgrife}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>

							
				<td><i class="fa fa-heartbeat text-red"></i></td>
							
							
                        </tr>
						
						
                    </table>
                </td>
				
		<td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
						
                        <tr>
                            <td><i class="fa fa-hourglass-3 text-purple"></i></td>
                            <td>{{$modeloagregado[0]->codgrife}}</td>
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
                            <td>{{number_format($modeloagregado[0]->itens)}}</td>
                        </tr>
                    </table>

                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-plane text-blue"></i></td>
                            <td>{{number_format($modeloagregado[0]->itens)}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td align="center"><img src="/img/china.png" width="15"></i></td>
                            <td>{{number_format($modeloagregado[0]->itens)}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-industry text-purple"></i></td>
                            <td>{{number_format($modeloagregado[0]->itens)}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-wrench text-orange"></i></td>
                            <td>{{number_format($modeloagregado[0]->itens,0)}}</td>
                        </tr>
                    </table>
                </td>
               
				
            </tr>

		 <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td>Total</td>
                            <td>{{number_format($modeloagregado[0]->itens)}}</td>
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
            <td>{{$modeloagregado[0]->codgrife}} 
              
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
            <td>{{$modeloagregado[0]->codgrife}} 
              
            </td>
          </tr>                   
			
        </table>
  
       
      </div>
    </div>
  </div>





<!-- comeca itens -->


  <div class="col-md-8">
    <span class="lead">Itens</span>
    <div class="row">
      @foreach ($itensagregado as $catalogo)

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
          <div  class="box-header with-border" style="font-size:16px; padding: 12px 10px 12px 10px;"> 
          <b><a href="/painel/{{$catalogo->agrup}}/{{$catalogo->modelo}}/{{$catalogo->modelo}}" class="text-black">{{$catalogo->modelo}}</a></b>
          <span class="pull-right">{{$catalogo->colmod}}</span>
			 
          
                
			
			</div>



        @php
          $foto = app('App\Http\Controllers\ItemController')->consultaFoto($catalogo->modelo);
        @endphp

          <div id="foto" align="center" style="min-height: 80px; max-height: 80px;">
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
            
		@if($catalogo->imediata < 1 and  $catalogo->imediata < 1)
		
		 <a title="Revisar item"  href="" class="zoom" data-value="{{$catalogo->modelo}}"><i class="fa fa-warning text-orange fa-2x" style="position:absolute; top:200px; left:5%; opacity:0.8;" ></i></a>
		
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
                                <a href="/vendas_sint?modelo={{$catalogo->modelo}}">{{number_format($catalogo->imediata)}}/{{number_format($catalogo->imediata)}}</a>
                              @else 
                                {{number_format($catalogo->imediata)}}/{{number_format($catalogo->imediata)}}
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