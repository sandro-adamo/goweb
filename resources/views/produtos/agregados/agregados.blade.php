@extends('produtos/painel/index')

@section('title')
  <i class="fa fa-list"></i> Produtos
@append 

@section('conteudo')




  @if (isset($modeloagregado) && count($modeloagregado) > 0)
  @foreach ($modeloagregado as $catalogo)

  <div class="col-md-3">
    <div class="box box-widget">
     

      <div class="box-header with-border" style="font-size:16px; padding: 3px 8px 3px 8px; margin-bottom: 0; vertical-align: top;">
        <span class="text-bold">{{$catalogo->modelo}}</span> 

        </div>
      

         
      <div id="foto" align="center" style="margin-top:0px; min-height:223px;height:223px; top:50%; margin-bottom:0; padding-bottom:0;">

           

        @php
          $foto = app('App\Http\Controllers\ItemController')->consultaFoto($catalogo->modelo);
        @endphp

        <a href="/produtos/agregados/{{$catalogo->modelo}}">
          <img src="/{{$foto}}" style="max-height: 250px;" class="img-responsive">
        </a>

        <a href="" class="zoom" data-value="{{$catalogo->modelo}}"><i class="fa fa-search text-blue" style="position:absolute; top:215px; left:93%; opacity:0.8;" ></i></a>

               
      
      </div>

      <div class="box-body">

        <div class="row">
          <div class="col-sm-6 col-md-6">
            
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
				  <table class="table table-bordered" style="text-align: left;">
          <tr>
            <td class="text-danger"><i class="fa fa-chain-broken"></i><b> FALTA VINCULO</td>
            <td class="text-danger">{{$catalogo->ttvinc-$catalogo->ttagrup}} </b></td>
          </tr> 
		</table>
                 
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
                              
                                <a href="/vendas_sint?modelo={{$catalogo->modelo}}">{{number_format($catalogo->vda30dd)}}/{{number_format($catalogo->vendas)}}</a>
                            
                               
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
	if ($catalogo->fornecedor = 'kering eyewear usa inc' ) {
		$mesesforn = 2;
	} else {
		$mesesforn = 6;
	}
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
                            <td>{{number_format($catalogo->brasil)}}</td>
                        </tr>
                    </table>

                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-plane text-blue"></i></td>
                            <td>{{number_format($catalogo->cet)}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td align="center"><img src="/img/china.png" width="15"></i></td>
                            <td>{{number_format($catalogo->etq)}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-industry text-purple"></i></td>
                            <td>{{number_format($catalogo->cep)}}</td>
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
    </div>
  </div>
  
  @endforeach

  @else 

    <h3 align="center">Nenhum modelo encontrado!</h3>

  @endif

</div>
@include('produtos.painel.modal.caracteristica')

@stop