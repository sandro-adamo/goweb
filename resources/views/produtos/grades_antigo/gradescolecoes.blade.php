@extends('produtos/painel/index')

@section('title')
  <i class="fa fa-list"></i> Produtos
@append 

@section('conteudo')
<div class="row">

  <div class="col-md-4">
    <span class="lead">Grade Ideal</span>
    <div class="box box-widget">
      <div class="box-header with-border bg-gray"> 
        <b>{{$modeloagregado[0]->agrup}}</b>
        <span class="pull-right"><b></b></span>
      </div>
      <div align="center" style="min-height: 100px;margin-top: 30px;">


        @php
          $foto = app('App\Http\Controllers\ItemController')->consultaFoto($modeloagregado[0]->grife);
        @endphp
		  

        <a href="" class="zoom" data-value="{{$modeloagregado[0]->grife}}">
           <!-- <img src="/{{$foto}}" class="img-responsive"> -->
			<img src="/img/marcas/{{$modeloagregado[0]->grife}}.png" style="max-height: 100px;" class="img-responsive">
        </a>
      </div>
		
		
	
		<div class="box-body">

        <div class="row">
          <div class="col-sm-6 col-md-6">
             <table class="table table-bordered" style="text-align: left;">
          <tr>
            <td class="text-danger"><i class="fa fa-chain-broken"></i><b> FALTA VINCULOsssss</td>
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
                            <td>{{number_format($modeloagregado[0]->modelos_grade)}}</td>
                        </tr>
                    </table>

                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-plane text-blue"></i></td>
                            <td>{{number_format($modeloagregado[0]->modelos_ent)}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td align="center"><img src="/img/china.png" width="15"></i></td>
                            <td>{{number_format($modeloagregado[0]->modelos_sai)}}</td>
                        </tr>
                    </table>
                </td>
               
                
               
				
            </tr>






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
			<td><i class="fa fa-cube"></i> Adulto</td>
			<td></td>
			<td>ideal </td>
			<td>atual </td>
			<td>30dd </td>
			<td>60dd </td>
			<td>180dd </td>
		</tr>     

				<tr>
					<td><i class="fa fa-th"></i> Gender</td>
					<td>TOTAL</td>
					<td>{{$modeloagregado[0]->codgrife}} </td>
					<td>{{$modeloagregado[0]->codgrife}} </td>
					<td>{{$modeloagregado[0]->codgrife}} </td>
					<td>{{$modeloagregado[0]->codgrife}} </td>
					<td>{{$modeloagregado[0]->codgrife}} </td>
				</tr>  

					<tr>
						<td></td>
						<td><i class="fa fa-female text-red"></i>   Female</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr>  

					<tr>
						<td></td>
						<td><i class="fa fa-male text-blue"></i>   Male</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr>  

		  
					<tr>
						<td></td>
						<td><i class="fa fa-female text-yellow"></i><i class="fa fa-male text-yellow"></i> Unissex</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr>  
			
			    <tr><td></td></tr>
				<tr>
					<td><i class="fa fa-th"></i> Age</td>
					<td>TOTAL</td>
					<td>{{$modeloagregado[0]->codgrife}} </td>
					<td>{{$modeloagregado[0]->codgrife}} </td>
					<td>{{$modeloagregado[0]->codgrife}} </td>
					<td>{{$modeloagregado[0]->codgrife}} </td>
					<td>{{$modeloagregado[0]->codgrife}} </td>
				</tr>  

					<tr>
						<td></td>
						<td><i class="fa fa-female text-red"></i>   Adult</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr>  

					<tr>
						<td></td>
						<td><i class="fa fa-male text-blue"></i>   Young</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr>  

		  
					<tr>
						<td></td>
						<td><i class="fa fa-female text-red"></i> Kids</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr>  
		  
			<tr><td></td></tr>
				<tr>
					<td><i class="fa fa-th"></i> Material</td>
					<td>TOTAL</td>
					<td>{{$modeloagregado[0]->codgrife}} </td>
					<td>{{$modeloagregado[0]->codgrife}} </td>
					<td>{{$modeloagregado[0]->codgrife}} </td>
					<td>{{$modeloagregado[0]->codgrife}} </td>
					<td>{{$modeloagregado[0]->codgrife}} </td>
				</tr>  

					<tr>
						<td></td>
						<td><i class="fa fa-female text-red"></i>   Metal</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr>  

					<tr>
						<td></td>
						<td><i class="fa fa-male text-blue"></i>   Acetate</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr>  

		  
					<tr>
						<td></td>
						<td><i class="fa fa-female text-red"></i> Plastic</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr> 
			<tr><td></td></tr>
				<tr>
					<td><i class="fa fa-th"></i> Fix</td>
					<td>TOTAL</td>
					<td>{{$modeloagregado[0]->modelos_grade}} </td>
					<td>{{$modeloagregado[0]->codgrife}} </td>
					<td>{{$modeloagregado[0]->codgrife}} </td>
					<td>{{$modeloagregado[0]->codgrife}} </td>
					<td>{{$modeloagregado[0]->codgrife}} </td>
				</tr>  

					<tr>
						<td></td>
						<td><i class="fa fa-female text-red"></i>   Full</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr>  

					<tr>
						<td></td>
						<td><i class="fa fa-male text-blue"></i>   Nylon</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr>  

		  
					<tr>
						<td></td>
						<td><i class="fa fa-female text-red"></i> Ballgrif</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr> 
			<tr><td></td></tr>
				<tr>
					<td><i class="fa fa-th"></i> Style</td>
					<td>TOTAL</td>
					<td>{{$modeloagregado[0]->codgrife}} </td>
					<td>{{$modeloagregado[0]->codgrife}} </td>
					<td>{{$modeloagregado[0]->codgrife}} </td>
					<td>{{$modeloagregado[0]->codgrife}} </td>
					<td>{{$modeloagregado[0]->codgrife}} </td>
				</tr>  

					<tr>
						<td></td>
						<td><i class="fa fa-female text-red"></i>   Casual</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr>  

					<tr>
						<td></td>
						<td><i class="fa fa-male text-blue"></i>   Fashion</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr>  

		  
					<tr>
						<td></td>
						<td><i class="fa fa-female text-red"></i> Sport</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr> 
					
					<tr>
						<td></td>
						<td><i class="fa fa-female text-red"></i> Luxury</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr> 
			
			<tr><td></td></tr>
				<tr>
					<td><i class="fa fa-th"></i> Size</td>
					<td>TOTAL</td>
					<td>{{$modeloagregado[0]->codgrife}} </td>
					<td>{{$modeloagregado[0]->codgrife}} </td>
					<td>{{$modeloagregado[0]->codgrife}} </td>
					<td>{{$modeloagregado[0]->codgrife}} </td>
					<td>{{$modeloagregado[0]->codgrife}} </td>
				</tr>  

					<tr>
						<td></td>
						<td><i class="fa fa-female text-red"></i>   40-50</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr>  

					<tr>
						<td></td>
						<td><i class="fa fa-male text-blue"></i>   51-53</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr>  

		  
					<tr>
						<td></td>
						<td><i class="fa fa-female text-red"></i> 54-56</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr> 
					
					<tr>
						<td></td>
						<td><i class="fa fa-female text-red"></i> >=57</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
					</tr> 
        </table>
  
       
      </div>
    </div>
  </div>



















<!-- comeca itens -->

  <div class=" box-body">
  <div class="col-md-8">
    <span class="lead">Lancamentos</span>
    <div class="row">
      @foreach ($itensagregado as $catalogo)

        @php
          switch ($catalogo->atual) {
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

      <div class="col-md-6">
        <div class="box box-widget">
			<div  class="box-header with-border" style="font-size:16px; padding: 12px 10px 12px 10px;"> 
			  <b> <td><a href="/produtos/gradescoldet/{{$catalogo->agrupamento}}?colecao={{$catalogo->colecao}}">{{$catalogo->colecao}}+999</a></td>
 </b>
		<!--	  <span class="pull-right">{{$catalogo->colecao}}</span> -->
			</div>

			
			  @if ($catalogo->atual > 0 )
 	<!--					<br>
						<table class="table table-bordered" style="text-align: left;">
					  <tr>
						  <td class=""><i class="fa fa-chain"></i><b> Itens</td>
						<td class="">{{$catalogo->atual}} </b>
							</td>
					  </tr> </table>
 	-->	
		
            @endif
              
				

		
		@if ($catalogo->atual > 0 and  $catalogo->atual < 1)
		<br><br>
		 <a title="Com estoque sem vinculo" href="" class="zoom" data-value="{{$catalogo->colecao}}"><i class="fa fa-chain-broken text-red fa-3x" style="position:absolute; top:200px; left:5%; opacity:0.8;" ></i> </a>
				
			@endif
            
		@if($catalogo->atual < 1 and  $catalogo->atual < 1)
		
		 <a title="Revisar item"  href="" class="zoom" data-value="{{$catalogo->colecao}}"><i class="fa fa-warning text-orange fa-2x" style="position:absolute; top:200px; left:5%; opacity:0.8;" ></i></a>
		
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
                            <td><i class="fa fa-suitcase text-green"></i></td>
                            <td>{{number_format($catalogo->grade_colecao)}}</td>
                        </tr>
                    </table>

                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-check-square text-blue"></i></td>
                            <td>{{number_format($catalogo->atual)}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-plus-square text-green"></i></td>
                            <td>{{number_format($catalogo->entradas)}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-minus-square text-red"></i></td>
                            <td>{{number_format($catalogo->saidas)}}</td>
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
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-female text-red"></i></td>
                            <td>{{number_format($catalogo->fem)}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-male text-blue"></i></td>
                            <td>{{number_format($catalogo->masc)}}</td>
                        </tr>
                    </table>
                </td>
				
				  <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-female text-red"></i><i class="fa fa-male text-yellow"></i></td>
                            <td>{{number_format($catalogo->unis)}}</td>
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
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>Adult</b></td>
                            <td>{{number_format($catalogo->adulto)}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>Young</b></td>
                            <td>{{number_format($catalogo->young)}}</td>
                        </tr>
                    </table>
                </td>
				  <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>Kids</b></td>
                            <td>{{number_format($catalogo->infantil)}}</td>
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
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>Metal</b></td>
                            <td>{{number_format($catalogo->metal)}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>Acetate</b></td>
                            <td>{{number_format($catalogo->acetato)}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>Plastic</b></td>
                            <td>{{number_format($catalogo->plastico)}}</td>
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
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>Full</b></td>
                            <td>{{number_format($catalogo->fechado)}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>Nylon</b></td>
                            <td>{{number_format($catalogo->nylon)}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>Ballgriff</b></td>
                            <td>{{number_format($catalogo->ballgrife)}}</td>
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
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>Casual</b></td>
                            <td>{{number_format($catalogo->casual)}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>Fashion</b></td>
                            <td>{{number_format($catalogo->fashion)}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>Sport</b></td>
                            <td>{{number_format($catalogo->sport)}}</td>
                        </tr>
                    </table>
                </td>
				 <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>Luxury</b></td>
                            <td>{{number_format($catalogo->luxo)}}</td>
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
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>40-50</b></td>
                            <td>{{number_format($catalogo->t50)}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>51-53</b></td>
                            <td>{{number_format($catalogo->t5153)}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>54-56</b></td>
                            <td>{{number_format($catalogo->t5456)}}</td>
                        </tr>
                    </table>
                </td>
 				<td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>>=57</b></td>
                            <td>{{number_format($catalogo->t57)}}</td>
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
</div>






@stop