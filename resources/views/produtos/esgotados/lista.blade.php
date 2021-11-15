@extends('layout.principal')

@section('title')
  <i class="fa fa-gears"></i> Esgotados
@append 

@section('conteudo')


<div class="row">
    <div class="col-md-5">
        <div class="box box-body">
            <h4>
                <a href="/mostruarios/exporta/geral" ><i class="fa fa-paste fa-2x"></i></a>
                <b> Qtde de itens por ultimo status (geral mala)</b>
            </h4> 
            
            <table class="table table-condensed table-bordered">
            
            <tr>
                <th>ULTIMO STATUS</th>
                <th>ITENS</th>
            
            </tr>

            @php
            	$total = 0 ;
            @endphp	 

            @if (count($geral) > 0)


	    		@foreach ($geral as $status) 


		            @php
		            	$total += $status->itens;
		            @endphp

	            <tr>
	                <td><a href="">{{$status->status_atual}}</a></td>
	                <td align="right">{{$status->itens}}</td>
	            
	            </tr>


	    		@endforeach 
    
	    	@else 

	            <tr>
	                <td align="center" colspan="2" class="text-bold"> Nenhum item na mala</td>	            
	            </tr>

	    	@endif

            <tr>
                <td><b>TOTAL</b></td>
                <td align="right"><b>{{$total}}</b></td>
            
            </tr>
            </table>
    
        </div> 
    </div>
    
	<div class="col-md-7">
    	<div class="box box-body">
            <h4>
            	<a href="/mostruarios/exporta/divergencia" ><i class="fa fa-paste fa-2x"></i></a>
            	<b> Qtde de itens divergentes na ultima semana (divergencia semanal)</b>
            </h4> 

            <table class="table table-condensed table-bordered">
                  
            <tr>
                <th>ACAO</th>
                <th>ULTIMO</th>
                <th>PENULTIMO</th>
                <th>ITENS</th>           
            </tr>

            @php
            	$total = 0 ;
            @endphp	 

            @if (count($divergencia) > 0)


	    		@foreach ($divergencia as $status) 


		            @php
		            	$total += $status->itens;
		            @endphp

	            <tr>
	                <td>{{$status->acao}}</td>
	                <td><a href="">{{$status->status_atual}}</a></td>
	                <td>{{$status->ultimo_st}}</td>
	                <td align="right">{{$status->itens}}</td>
	            
	            </tr>


	    		@endforeach 
    
	    	@else 

	            <tr>
	                <td align="center" colspan="4" class="text-bold"> Nenhum item na mala</td>	            
	            </tr>

	    	@endif

            </table>

        </div>
    </div>
</div>


@stop