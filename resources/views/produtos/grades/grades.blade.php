@extends('produtos/painel/index')

@section('title')
  <i class="fa fa-list"></i> Produtos
@append 

@section('conteudo')

<div class="col-md-8">
 <span class="lead">Grade de Modelos </span>
<div class="row">
 
 @foreach ($gradeslista as $catalogo)

  <div class="col-md-3">
    <div class="box box-widget">
		
      <div class="box-header with-border" style="font-size:10px; padding: 3px 5px 3px 5px; margin-bottom: 0; vertical-align: top;">
        <span class="text-bold">{{trim($catalogo->agrup)}}</span> 
      </div>
          
      <div id="foto" align="center" style="margin-top:20px; min-height:140;height:140; top:20%; margin-bottom:0; padding-bottom:0;">
  
		<a href="" class="zoom" data-value="{{$catalogo->modelos}}"></a>     

        @php
          $foto = app('App\Http\Controllers\ItemController')->consultaFoto(trim($catalogo->codgrife));
        @endphp

        <a href="/produtos/gradescolecoes/{{$catalogo->agrup}}">
          <img src="/img/marcas/{{$catalogo->grife}}.png" style="max-height: 250px;" class="img-responsive">
        </a>     
      
      </div>

		
		
		
      <div class="box-body">

        <div class="row">
          <div class="col-sm-4 col-md-4">
            
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
                    <table class="table table-condensed table-bordered table2" style="text-align: left;">                
						<tr>
							<td>Mod</i></td>  
                            <td><a href="/produtos/gradesmodelos/{{$catalogo->agrup}}">{{number_format($catalogo->modelos)}}</a></td> 
                        </tr>
                    </table>

                </td>
	  
	  
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td>N</i></td>
                            <td><a href="/produtos/gradesmodelos/{{$catalogo->agrup}}?cores=3">{{number_format($catalogo->am3cores)}}</a></td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>

							<td>A</td>
							<td><a href="/produtos/gradesmodelos/{{$catalogo->agrup}}?cores=2">{{number_format($catalogo->b2cores)}}</a></td>
							
                        </tr>
						
						
                    </table>
                </td>
				
		<td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
						
                        <tr>
                            <td>A-</td>
                            <td><a href="/produtos/gradesmodelos/{{$catalogo->agrup}}?cores=1">{{number_format($catalogo->c1cor)}}</a></td>
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
                            <td><i class="fa fa-battery-full text-green"></i></td>
                            <td><a href="/produtos/gradesmodelos/{{$catalogo->agrup}}?cores=3">{{number_format($catalogo->am3cores)}}</a></td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>

							<td><i class="fa fa-battery-half text-blue"></i></td>
							<td><a href="/produtos/gradesmodelos/{{$catalogo->agrup}}?cores=2">{{number_format($catalogo->b2cores)}}</a></td>
							
                        </tr>
						
						
                    </table>
                </td>
				
		<td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
						
                        <tr>
                            <td><i class="fa fa-battery-quarter text-yellow"></i></td>
                            <td><a href="/produtos/gradesmodelos/{{$catalogo->agrup}}?cores=1">{{number_format($catalogo->c1cor)}}</a></td>
                        </tr>
                    </table>
                </td>
	  
	  		<td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
						
                        <tr>
                            <td><i class="fa fa-battery-empty text-red"></i></td>
                            <td><a href="/produtos/gradesmodelos/{{$catalogo->agrup}}?cores=0">{{number_format($catalogo->d0cor)}}</a></td>
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
                            <td align="left">Itens</i></td>
							
								<td>{{number_format($catalogo->itens)}}</td>
							
						</tr>
                    </table>

                </td>
				
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
	</div>

	</div>
	</div> 

@endforeach 
   </div>
  </div>

</div>

@stop