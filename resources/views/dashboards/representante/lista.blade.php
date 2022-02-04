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


select * from (
select id id_rep, codigo id_ssa, tipo, nome, fantasia, razao, uf, municipio, grupo, subgrupo, cadastro, flag_cadastro, sit_representante,
	tipo_comissao, diretoria
	from addressbook ab
	where tipo in ('re','ri') and id in (77065, 101415) limit 1
) as base


left join (
	select rep_cart, count(cli) clientes, sum(cli_ativos) cli_ativos, min(dt_inicio) dt_inicio, max(dt_fim) dt_fim from  (
		select rep rep_cart, cli,
		case when status = 1 then 1 else 0 end as cli_ativos,
		min(dt_inicio) dt_inicio, max(dt_fim) dt_fim 
		from carteira where rep in (77065, 101415)
		group by rep, status, cli limit 1
		) as fim group by rep_cart
	) as cart
on cart.rep_cart = base.id_rep


left join (select id_rep rep_most, sum(qtde) qtde_most from malas where id_rep in (77065, 101415) group by id_rep) as malas
on malas.rep_most = base.id_rep


left join (select id_rep rep_vda, sum(qtde) qtde_vda from vendas_jde where id_rep in (77065, 101415) and id_cliente = 1 group by id_rep) as vendas
on vendas.rep_vda = base.id_rep
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

	 		<td colspan="12">Compras Kering</td>
		
				</tr>
		  			
					<tr>	
					<td colspan="1" align="center">id jde</td>
					<td colspan="1" align="center">id ssa</td>
					<td colspan="1" align="center">sit rep</td>				
					<td colspan="1" align="center">flag cad</td>
					<td colspan="1" align="center">nome</td>
					<td colspan="1" align="center">fantasia</td>
					<td colspan="1" align="center">uf</td>
					<td colspan="1" align="center">municipio</td>
					<td colspan="1" align="center">diretoria</td>
					<td colspan="1" align="center">cli</td>
					<td colspan="1" align="center">cli ativo </td>
					<td colspan="1" align="center">qtde_most</td>
					
				
					</tr>
			    </thead>
			  
		  
		   
			@foreach ($query_1 as $query1)
		   
		   
			  
				<tr>
					<td align="center"><a href="">{{$query1->id_rep}}</a></td>
					<td align="center">{{$query1->id_ssa}}</td>
					<td align="center">{{$query1->sit_representante}}</td>
					<td align="center">{{$query1->flag_cadastro}}</td>
					<td align="center">{{$query1->nome}}</td>
					<td align="center">{{$query1->fantasia}}</td>
					<td align="center">{{$query1->uf}}</td>
					<td align="center">{{$query1->municipio}}</td>
					<td align="center">{{$query1->diretoria}}</td>
					<td align="center">{{$query1->clientes}}</td>
					<td align="center">{{$query1->cli_ativos}}</td>
					<td align="center">{{$query1->qtde_most}}</td>

					
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
        <li class="header">MAIN NAVIGATION</li>
        
  
		  <li class="treeview">
          <a href="https://adminlte.io/themes/AdminLTE/index2.html#">
            <i class="fa fa-share"></i> <span>Multilevel</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>

          <ul class="treeview-menu">
 
			  
<!-- for diretor -->  			  
            <li class="treeview">
              <a href="https://adminlte.io/themes/AdminLTE/index2.html#"><i class="fa fa-circle-o"></i> Diretor 1
                <span class="pull-right-container">
                 20 <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
				
<!-- for supervisor -->
				
              <ul class="treeview-menu">
                
				  <li class="treeview">
                  <a href="https://adminlte.io/themes/AdminLTE/index2.html#"><i class="fa fa-circle-o"></i> Supervisor 1
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  
<!-- for rep -->					
						<ul class="treeview-menu">
						<li><a href="https://adminlte.io/themes/AdminLTE/index2.html#"><i class="fa fa-circle-o"></i> Representente</a></li>
						<li><a href="https://adminlte.io/themes/AdminLTE/index2.html#"><i class="fa fa-circle-o"></i> Representente</a></li>
					  	</ul>
                	
				  </li>
				  
				  
				 
				  
				  
				  <li class="treeview">
                  <a href="https://adminlte.io/themes/AdminLTE/index2.html#"><i class="fa fa-circle-o"></i> Supervisor 2
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  
					
						<ul class="treeview-menu">
						<li><a href="https://adminlte.io/themes/AdminLTE/index2.html#"><i class="fa fa-circle-o"></i> Representente 1</a></li>
						<li><a href="https://adminlte.io/themes/AdminLTE/index2.html#"><i class="fa fa-circle-o"></i> Representente 2</a></li>
					  	</ul>
                	
				  </li>
              </ul>
            </li>
            
	<!-- end for diretor -->		  

          </ul>
        </li>
      </ul>

</div>
</div>
</div>
	
	
	
	
@stop