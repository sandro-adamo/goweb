@extends('layout.principal')
@section('conteudo')

@php

$representantes = Session::get('representantes');


if($representantes==101815) 
	{$grifes="( 'AM', 'BC', 'BV', 'CT', 'SM', 'MC', 'CH', 'DU', 'AA', 'AZ', 'CL')";} 
		else {
			$grifes = Session::get('grifes'); }

echo $grifes;

$query_1 = \DB::select(" 

select distinct id id_rep
	from addressbook ab limit 1

");


			
@endphp

<form action="" method="get"> 

<h6>

	
							
<div class="row"> 
	
	<div class="col-md-12">	
	
	   <div class="box box-body">	
	   <table class="table table-striped table-bordered compact" id="myTable">
		  <thead>	
			
		 <tr>	

	 		<td colspan="12">Lista represernatantes</td>
		
				</tr>
		  			
				<tr>	
					<td colspan="1" align="center">id jde</td>
					<td colspan="1" align="center">id ssa</td>
				</tr>
			    </thead>
		   
			@foreach ($query_1 as $query1)
		   
			  
				<tr>
					<td align="center"><a href="">{{$query1->id_rep}}</a></td>
					<td align="center">{{$query1->id_rep}}</td>
				</tr>
			@endforeach 	
		   
			</table>
		</div>
	</div>	
</div>
</h6>			
</form>
	




<div class="row"> 	
	<div class="col-md-5">	
		<div class="box box-body">		

			<ul class="sidebar-menu tree" data-widget="tree">
				<li class="header">Diretoria</li>


				  <li class="treeview">
				  <a href="https://adminlte.io/themes/AdminLTE/index2.html#">
					<i class="fa fa-share"></i> <span>Total</span>
					<span class="pull-right-container">
					  <i class="fa fa-angle-left pull-right"></i>
					</span>
				  </a>

				@php	  
				 $query_2 = \DB::select("select distinct coddir, dir from carteira 
				 where status = 1 and dt_fim >= now() and cli_ativo = 1 ");
				@endphp	  
				  <ul class="treeview-menu">
				  @foreach ($query_2 as $query2)

		<!-- for diretor -->  			  
					<li class="treeview">
					  <a href="https://adminlte.io/themes/AdminLTE/index2.html#"><i class="fa fa-circle-o"></i> {{$query2->dir}}
						<span class="pull-right-container">
						 20 <i class="fa fa-angle-left pull-right"></i>
						</span>
					  </a>

		<!-- for supervisor -->
					@php
					$query_3 = \DB::select("select distinct codsuper, sup from carteira where status = 1 and dt_fim >= now() and coddir = '$query2->coddir' and cli_ativo = 1"); 
					@endphp


					  <ul class="treeview-menu">
					  @foreach ($query_3 as $query3)

						  <li class="treeview">
						  <a href="https://adminlte.io/themes/AdminLTE/index2.html#"><i class="fa fa-circle-o"></i> {{$query3->sup}}
							<span class="pull-right-container">
							  <i class="fa fa-angle-left pull-right"></i>
							</span>
						  </a>

		<!-- for rep -->			
								@php
								$query_4 = \DB::select("
	 						    select distinct rep, case when nome = '' then fantasia else nome end as nome 
							    from carteira cart left join addressbook ab on ab.id = rep 
							    where status = 1 and dt_fim >= now() and codsuper = '$query3->codsuper' and coddir = '$query2->coddir' and cli_ativo = 1"); 
								@endphp

								<ul class="treeview-menu">
								@foreach ($query_4 as $query4)

								<li><a href="https://adminlte.io/themes/AdminLTE/index2.html#"><i class="fa fa-circle-o"></i> {{$query4->rep}} {{$query4->nome}}</a>

								@endforeach	

								</ul>
						  </li>

						@endforeach    				  


					  </ul>
					</li>
					@endforeach 
			<!-- end for diretor -->		  

				  </ul>
				</li>
			  </ul>
		</div>
	</div>
</div>
	
	










	
@stop