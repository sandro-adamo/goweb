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
select distinct id_rep from (
select id id_rep, codigo id_ssa, tipo, nome, fantasia, razao, uf, municipio, grupo, subgrupo, cadastro, flag_cadastro, sit_representante,
	tipo_comissao, diretoria
	from addressbook ab
	where tipo in ('re','ri') and id in (77065, 101415) limit 1
) as final

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
<div class="col-md-4">	
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
		 $query_2 = \DB::select("select distinct dir from carteira where status = 1 and dt_fim >= now() "); 	  
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
			$query_3 = \DB::select("select distinct sup from carteira where status = 1 and dt_fim >= now() and dir = '$query2->dir' "); 
			@endphp
				
				
              <ul class="treeview-menu">
              @foreach ($query_3 as $query3)
				  
				  <li class="treeview">
                  <a href="https://adminlte.io/themes/AdminLTE/index2.html#"><i class="fa fa-circle-o"></i> {{$query3->sup}}
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
              @endforeach     
<!-- for rep -->			
					  
						<ul class="treeview-menu">
						@foreach ($query_3 as $query3)
						<li><a href="https://adminlte.io/themes/AdminLTE/index2.html#"><i class="fa fa-circle-o"></i> Representente</a>
						@endforeach	
						</li>
						
							
					  	</ul>
                	
				  </li>
				  
				  
				 
				  
				  
				 
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