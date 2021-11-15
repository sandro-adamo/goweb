@extends('layout.principal')

@section('titulo', 'Painel de Produtos') 

@section('title')
<i class="fa fa-object-group"></i> Products Panel
@append 

@section('conteudo') 

@php

	$query_1 = \DB::select("select distinct codgrife, grife from itens where codgrife in ('ah','at')");

	$query_2 = \DB::select("select distinct agrup from itens where codgrife = 'ah' and codtipoitem = 006 limit 10 ");

	$query_3 = \DB::select("select anomod, count(id) itens from itens where agrup like 'ah01%' and colmod like '202%' group by anomod limit 3");

@endphp

<h6>
      <div class="row">
		   @foreach ($query_1 as $grife)
		  
        <div class="col-md-3">
          <!-- Widget: user widget style 1 -->
          <div class="box box-widget widget-user-2">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-gray">
              <div class="widget-user-username">
				  <img src="/img/marcas/{{$grife->grife}}.png" width="180px" style="max-height: 60px;" class="img-responsive">
              </div>
            </div>
		
			  
		
				
					<div class="box-footer no-padding">
					@foreach ($query_2 as $agrup)
						<div class="col-md-6">
							<ul class="nav nav-stacked">
							<li><a href="#">{{$agrup->agrup}} <span class="pull-right badge bg-blue">31</span></a></li>
							</ul>	
							
							
									<div class="box-footer no-padding">
									@foreach ($query_3 as $anomod)
										<div class="col-md-2">
											<ul class="nav nav-stacked">
											<li><a href="#">{{$anomod->anomod}} <span class="pull-right badge bg-blue">{{$anomod->itens}}</span></a></li>
											</ul>	
										</div> 
									@endforeach
									</div>	  
							
						</div> 
					@endforeach
					</div>	  
			
			  
          </div>
          <!-- /.widget-user -->
        </div>

    @endforeach

  </div>
	</h6>
  @stop