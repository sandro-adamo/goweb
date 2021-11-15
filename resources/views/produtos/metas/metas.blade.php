@extends('produtos/painel/index')

@section('title')
  <i class="fa fa-list"></i> Produtos
@append 

@section('conteudo')
				@php
			  $data = date("n");
echo $data;
			  @endphp
                                         
            <div class="col-md-12">
          <div class="box">
            <div class="box-header"  >
              <h3 class="box-title">Meta 2019</h3>

              <div class="box-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                  

                  
                </div>
              </div>
            </div>
            <!-- /.box-header -->
			 
			  
            <div class="box-body table-responsive ">
              <table class="table table-hover">
        <thead >
        <tr id="dadosPedido">
            <th style="width: 200px;" >Agrup</th> 
            <th>Meta Jan </th> 
            <th>Realizado Jan</th>
			<th>Falta Jan</th>
			<th>% Jan</th>
			<th></th>
			@if ($data>='2'){
						   
            <th>Meta Fev</th> 
            <th>Realizado Fev</th>
			<th>Falta fev</th>
			<th>% Fev</th>
			<th></th>}
				  
				  @endif
			
           @if ($data>=3){
			<th>Meta Mar</th> 
            <th>Realizado mar</th>
			<th>Falta Mar</th>
			<th>% Mar</th>
			<th></th>}
				 
				  @endif
			
			@if ($data>=4){
			<th>Meta Abr</th> 
            <th>Realizado Abr</th>
			<th>Falta Abr</th>
			<th>% Abr</th>
			<th></th>}
				  
				  @endif
			
			@if ($data>=5){
			<th>Meta Mai</th> 
            <th>Realizado Mai</th>
			<th>Falta Mai</th>
			<th>% Mai</th>
			<th></th>}
				  
				  @endif
			
			@if ($data>=6){
			<th>Meta Jun</th> 
            <th>Realizado Jun</th>
			<th>Falta Jun</th>
			<th>% Jun</th>
			<th></th>}
				  
				  @endif
			
			@if ($data>=7){
			<th>Meta Jul</th> 
            <th>Realizado Jul</th>
			<th>Falta Jul</th>
			<th>% Jul</th>
			<th></th>}
				 
				  @endif
			
			@if ($data>=8){
			<th>Meta Ago</th> 
            <th>Realizado Ago</th>
			<th>Falta Ago</th>
			<th>% Ago</th>
			<th></th>}
				  
				  @endif
			
			@if ($data>=9){
			<th>Meta Set</th> 
            <th>Realizado Set</th>
			<th>Falta Set</th>
			<th>% Set</th>
			<th></th>}
				 
				  @endif
			
			@if ($data>=10){
			<th>Meta Out</th> 
            <th>Realizado Out</th>
			<th>Falta Out</th>
			<th>% Out</th>
			<th></th>}
				 
				  @endif
			
			@if ($data>=11){
			<th>Meta Nov</th> 
            <th>Realizado Nov</th>
			<th>Falta Nov</th>
			<th>% Nov</th>
			<th></th>}
				 
				  @endif
			
			@if ($data>=12){
			<th>Meta Dez</th> 
            <th>Realizado Dez</th>
			<th>Falta Dez</th>
			<th>% Dez</th>
			<th></th>}
				  
				  @endif
           
            
        </tr>
			</thead>
				  @php $valorTotal =0; @endphp
			@foreach ($metas as $metas1)
			<tr>
				@if ($data>=1){
            <td  style="min-width: 200px;" >{{$metas1->agrup}}</td> 
            <td>{{$metas1->meta_janeiro}}</td> 
            <td>{{$metas1->realizado_janeiro}}</td>
			<td>@if (($metas1->realizado_janeiro-$metas1->meta_janeiro)<0) <span class="text-red">{{ $metas1->realizado_janeiro-$metas1->meta_janeiro}}
				  </span>
				  @else <span class="text-blue">{{ $metas1->realizado_janeiro-$metas1->meta_janeiro}}
				  </span>
				  @endif
				  </td>
			<td>@if ((number_format(100*($metas1->realizado_janeiro / $metas1->meta_janeiro),2))<95) <span class="text-red">{{ number_format(100*($metas1->realizado_janeiro / $metas1->meta_janeiro),2)}}%
				  </span>
				  @else <span class="text-blue">{{ number_format(100*($metas1->realizado_janeiro / $metas1->meta_janeiro),2)}}%
				  </span>
				  @endif
				
				
				</td>
            <td></td> }
				 
				  @endif
				  
		     @if ($data>=2){
            <td>{{$metas1->meta_fevereiro}}</td> 
            <td>{{$metas1->realizado_fevereiro}}</td>
			<td>@if (($metas1->realizado_fevereiro-$metas1->meta_fevereiro)<0) <span class="text-red">{{ $metas1->realizado_fevereiro-$metas1->meta_fevereiro}}
				  </span>
				  @else <span class="text-blue">{{ $metas1->realizado_fevereiro-$metas1->meta_fevereiro}}
				  </span>
				  @endif
				  </td>
			<td>@if ((number_format(100*($metas1->realizado_fevereiro / $metas1->meta_fevereiro),2))<95) <span class="text-red">{{ number_format(100*($metas1->realizado_fevereiro / $metas1->meta_fevereiro),2)}}%
				  </span>
				  @else <span class="text-blue">{{ number_format(100*($metas1->realizado_fevereiro / $metas1->meta_fevereiro),2)}}%
				  </span>
				  @endif
            <td></td> }
				  
				  @endif
				  
			@if ($data>=3){
            <td>{{$metas1->meta_marco}}</td> 
            <td>{{$metas1->realizado_marco}}</td>
			<td>@if (($metas1->realizado_marco-$metas1->meta_marco)<0) <span class="text-red">{{ $metas1->realizado_marco-$metas1->meta_marco}}
				  </span>
				  @else <span class="text-blue">{{ $metas1->realizado_marco-$metas1->meta_marco}}
				  </span>
				  @endif
				  </td>
			<td>@if ((number_format(100*($metas1->realizado_marco / $metas1->meta_marco),2))<95) <span class="text-red">{{ number_format(100*($metas1->realizado_marco / $metas1->meta_marco),2)}}%
				  </span>
				  @else <span class="text-blue">{{ number_format(100*($metas1->realizado_marco / $metas1->meta_marco),2)}}%
				  </span>
				  @endif
            <td></td> }
				  
				  @endif
				  
			@if ($data>=4){
            <td>{{$metas1->meta_abril}}</td> 
            <td>{{$metas1->realizado_abril}}</td>
			<td>@if (($metas1->realizado_abril-$metas1->meta_abril)<0) <span class="text-red">{{ $metas1->realizado_abril-$metas1->meta_abril}}
				  </span>
				  @else <span class="text-blue">{{ $metas1->realizado_abril-$metas1->meta_abril}}
				  </span>
				  @endif
				  </td>
			<td>@if ((number_format(100*($metas1->realizado_abril / $metas1->meta_abril),2))<95) <span class="text-red">{{ number_format(100*($metas1->realizado_abril / $metas1->meta_abril),2)}}%
				  </span>
				  @else <span class="text-blue">{{ number_format(100*($metas1->realizado_abril / $metas1->meta_abril),2)}}%
				  </span>
				  @endif
            <td></td> }
				  
				  @endif
				  
			@if ($data>=5){
            <td>{{$metas1->meta_maio}}</td> 
            <td>{{$metas1->realizado_maio}}</td>
			<td>@if (($metas1->realizado_maio-$metas1->meta_maio)<0) <span class="text-red">{{ $metas1->realizado_maio-$metas1->meta_maio}}
				  </span>
				  @else <span class="text-blue">{{ $metas1->realizado_maio-$metas1->meta_maio}}
				  </span>
				  @endif
				  </td>
			<td>@if ((number_format(100*($metas1->realizado_maio / $metas1->meta_maio),2))<95) <span class="text-red">{{ number_format(100*($metas1->realizado_maio / $metas1->meta_maio),2)}}%
				  </span>
				  @else <span class="text-blue">{{ number_format(100*($metas1->realizado_maio / $metas1->meta_maio),2)}}%
				  </span>
				  @endif
            <td></td>  }
				  
				  @endif
				  
				  
		    @if ($data>=6){
            <td>{{$metas1->meta_junho}}</td> 
            <td>{{$metas1->realizado_junho}}</td>
			<td>@if (($metas1->realizado_junho-$metas1->meta_junho)<0) <span class="text-red">{{ $metas1->realizado_junho-$metas1->meta_junho}}
				  </span>
				  @else <span class="text-blue">{{ $metas1->realizado_junho-$metas1->meta_junho}}
				  </span>
				  @endif
				  </td>
			<td>@if ((number_format(100*($metas1->realizado_junho / $metas1->meta_junho),2))<95) <span class="text-red">{{ number_format(100*($metas1->realizado_junho / $metas1->meta_junho),2)}}%
				  </span>
				  @else <span class="text-blue">{{ number_format(100*($metas1->realizado_junho / $metas1->meta_junho),2)}}%
				  </span>
				  @endif
            <td></td> }
				  
				  @endif
				  
			 @if ($data>=7){
            <td>{{$metas1->meta_julho}}</td> 
            <td>{{$metas1->realizado_julho}}</td>
			<td>@if (($metas1->realizado_julho-$metas1->meta_julho)<0) <span class="text-red">{{ $metas1->realizado_julho-$metas1->meta_julho}}
				  </span>
				  @else <span class="text-blue">{{ $metas1->realizado_julho-$metas1->meta_julho}}
				  </span>
				  @endif
				  </td>
			<td>@if ((number_format(100*($metas1->realizado_julho / $metas1->meta_julho),2))<95) <span class="text-red">{{ number_format(100*($metas1->realizado_julho / $metas1->meta_julho),2)}}%
				  </span>
				  @else <span class="text-blue">{{ number_format(100*($metas1->realizado_julho / $metas1->meta_julho),2)}}%
				  </span>
				  @endif
            <td></td> }
				  
				  @endif
				  
			 @if ($data>=8){
            <td>{{$metas1->meta_agosto}}</td> 
            <td>{{$metas1->realizado_agosto}}</td>
			<td>@if (($metas1->realizado_agosto-$metas1->meta_agosto)<0) <span class="text-red">{{ $metas1->realizado_agosto-$metas1->meta_agosto}}
				  </span>
				  @else <span class="text-blue">{{ $metas1->realizado_agosto-$metas1->meta_agosto}}
				  </span>
				  @endif
				  </td>
			<td>@if ((number_format(100*($metas1->realizado_agosto / $metas1->meta_agosto),2))<95) <span class="text-red">{{ number_format(100*($metas1->realizado_agosto / $metas1->meta_agosto),2)}}%
				  </span>
				  @else <span class="text-blue">{{ number_format(100*($metas1->realizado_agosto / $metas1->meta_agosto),2)}}%
				  </span>
				  @endif
            <td></td> }
				  
				  @endif
				  
			@if ($data>=9){
            <td>{{$metas1->meta_setembro}}</td> 
            <td>{{$metas1->realizado_setembro}}</td>
			<td>@if (($metas1->realizado_setembro-$metas1->meta_setembro)<0) <span class="text-red">{{ $metas1->realizado_setembro-$metas1->meta_setembro}}
				  </span>
				  @else <span class="text-blue">{{ $metas1->realizado_setembro-$metas1->meta_setembro}}
				  </span>
				  @endif
				  </td>
			<td>@if ((number_format(100*($metas1->realizado_setembro / $metas1->meta_setembro),2))<95) <span class="text-red">{{ number_format(100*($metas1->realizado_setembro / $metas1->meta_setembro),2)}}%
				  </span>
				  @else <span class="text-blue">{{ number_format(100*($metas1->realizado_setembro / $metas1->meta_setembro),2)}}%
				  </span>
				  @endif
            <td></td>}
				 
				  @endif 
				  
			@if ($data>=10){
            <td>{{$metas1->meta_outubro}}</td> 
            <td>{{$metas1->realizado_outubro}}</td>
			<td>@if (($metas1->realizado_outubro-$metas1->meta_outubro)<0) <span class="text-red">{{ $metas1->realizado_outubro-$metas1->meta_outubro}}
				  </span>
				  @else <span class="text-blue">{{ $metas1->realizado_outubro-$metas1->meta_outubro}}
				  </span>
				  @endif
				  </td>
			<td>@if ((number_format(100*($metas1->realizado_outubro / $metas1->meta_outubro),2))<95) <span class="text-red">{{ number_format(100*($metas1->realizado_outubro / $metas1->meta_outubro),2)}}%
				  </span>
				  @else <span class="text-blue">{{ number_format(100*($metas1->realizado_outubro / $metas1->meta_outubro),2)}}%
				  </span>
				  @endif
            <td></td>}
				  
				  @endif  
				  
			@if ($data>=11){
            <td>{{$metas1->meta_novembro}}</td> 
            <td>{{$metas1->realizado_novembro}}</td>
			<td>@if (($metas1->realizado_novembro-$metas1->meta_novembro)<0) <span class="text-red">{{ $metas1->realizado_novembro-$metas1->meta_novembro}}
				  </span>
				  @else <span class="text-blue">{{ $metas1->realizado_novembro-$metas1->meta_novembro}}
				  </span>
				  @endif
				  </td>
			<td>@if ((number_format(100*($metas1->realizado_novembro / $metas1->meta_novembro),2))<95) <span class="text-red">{{ number_format(100*($metas1->realizado_novembro / $metas1->meta_novembro),2)}}%
				  </span>
				  @else <span class="text-blue">{{ number_format(100*($metas1->realizado_novembro / $metas1->meta_novembro),2)}}%
				  </span>
				  @endif
            <td></td> }
				 
				  @endif 
				  
			
			@if ($data>=12){
            <td>{{$metas1->meta_dezembro}}</td> 
            <td>{{$metas1->realizado_dezembro}}</td>
			<td>@if (($metas1->realizado_dezembro-$metas1->meta_dezembro)<0) <span class="text-red">{{ $metas1->realizado_dezembro-$metas1->meta_dezembro}}
				  </span>
				  @else <span class="text-blue">{{ $metas1->realizado_dezembro-$metas1->meta_dezembro}}
				  </span>
				  @endif
				  </td>
			<td>@if ((number_format(100*($metas1->realizado_dezembro / $metas1->meta_dezembro),2))<95) <span class="text-red">{{ number_format(100*($metas1->realizado_dezembro / $metas1->meta_dezembro),2)}}%
				  </span>
				  @else <span class="text-blue">{{ number_format(100*($metas1->realizado_dezembro / $metas1->meta_dezembro),2)}}%
				  </span>
				  @endif
            <td></td> }
				  
				  @endif 

        </tr>
@php $valorTotal += $metas1->meta_janeiro; @endphp

			@endforeach
				<td>TOTAL</td> 
<td>@php echo $valorTotal; @endphp</td> 
       		


            
        <tbody>
        
        
        </tbody>
           
      
      <div class="modal-footer">
        <th>Agrupamento</th> 
            <th>Meta Jan</th> 
            <th>Realizado Jan</th>
      </div>
				 

</table> 
			</div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
</header>


@stop