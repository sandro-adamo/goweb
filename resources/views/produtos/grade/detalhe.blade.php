@extends('produtos/painel/index')
@php

$agrup = $_GET["agrup"];

if(isset($_GET["colecao"])){
	$colecao = $_GET["colecao"];
	$where = "where colecao = $colecao";

	$whereteste = "where agrup = '".$agrup."' and colecao = ".$colecao; 

} else { $where = "where 1=1" ; }
;


@endphp

@section('titulo') {{$agrup}} @append

@section('title')
  <i class="fa fa-list"></i> Produtos
@append 

@section('conteudo')


@php


$query = \DB::select("select * from itens where modelo = 'ah6254' ");
$data = '2021-01-01';


$modelos = \DB::select("
select * from (
select fornecedor, grife, codgrife, agrup, modelo, colecao, colmod, clasmod,
	sum(novos) novos, sum(aa) aa, sum(a) a, 
	sum(itens) itens, sum(imediata) imediata, sum(futura) futura, sum(producao) producao, sum(esgotado) esgotado, 
	sum(am3cores) am3cores, sum(b2cores) b2cores, sum(c1cor) c1cor, sum(d0cor) d0cor 
from (

	select fornecedor, grife, codgrife, agrup, colecao, modelo, colmod, clasmod,
	case when colecao = 'novo' then 1 else 0 end as novos,
	case when colecao = 'aa' then 1 else 0 end as aa, 
	case when colecao = 'a' then 1 else 0 end as a, 
	sum(itens) itens, sum(imediata) imediata, sum(futura) futura, sum(producao) producao, sum(esgotado) esgotado, 
	sum(am3cores) am3cores, sum(b2cores) b2cores, sum(c1cor) c1cor, sum(d0cor) d0cor 
	from (

		select fornecedor, grife, codgrife, agrup, modelo, clasmod, colmod, (itens) as itens, (imediata) imediata, (futura) futura, (producao) producao, (esgotado) esgotado,
			case when imediata+futura >= 3 then 1 else 0 end as am3cores,
			case when imediata+futura  = 2 then 1 else 0 end as b2cores,
			case when imediata+futura  = 1 then 1 else 0 end as c1cor,
			case when imediata+futura  = 0 then 1 else 0 end as d0cor,
			
			 case when colecao = 'novo' then 'novo'
			 when colecao <> 'novo' and clasmod in ('LINHA A++','LINHA A+','LINHA A','NOVO') then 'aa'
			 when colecao <> 'novo' and clasmod in ('LINHA A-') then 'a' else '' end as colecao
			
			
		from(

			select fornecedor, grife, codgrife, agrup, modelo, clasmod, colmod, colecao, sum(itens) as itens, sum(imediata) imediata, sum(futura) futura, sum(producao) producao, sum(esgotado) esgotado
			
			from (
			 
				select fornecedor, grife, codgrife, agrup, modelo, clasmod, colmod, colecao, 1 as itens,
					case when ultstatus = 'ENTREGA IMEDIATA' then 1 else 0 end as imediata,
					case when ultstatus like '%DIAS' then 1 else 0 end as futura,
					case when ultstatus like '%PROD%' then 1 else 0 end as producao,
					case when ultstatus like '%ESGOTADO%' then 1 else 0 end as esgotado
				from (
							
				    select case when fornecedor like 'kering%' then 'kering' else 'go' end as fornecedor,
					grife, codgrife, itens.agrup, itens.modelo, itens.secundario, colmod,  ultstatus,
					case when left(colmod,4) < year(now()) then 'lancado'
					when (left(colmod,4) = year(now()) and right(colmod,2) < month(now())) then 'lancado' else 'novo' end as colecao,
                    (select clasmod from itens iclas where iclas.id = itens.id and clasmod  not in ('','.','colecao europra','cancelado') order by clasmod limit 1) clasmod
					from itens 
					where itens.secundario not like '%semi%' and (clasmod like 'linha%' or clasmod like 'novo%') and codtipoitem = 006				 
					and codgrife in ('AH','AI',  'AT','BG','EV','JO','HI','SP','TC','JM','NG','GU','MM','ST','AM','MC','CT','BC','BV','SM') 
					and codtipoarmaz not in ('o')
					and agrup = '$agrup'
				) as fim2
			) as fim3 group by fornecedor, grife, codgrife, agrup, modelo, clasmod, colmod, colecao
		) as fim4 
	) as fim5 group by fornecedor, grife, codgrife, agrup, colecao, modelo, colmod, clasmod
) as fim6 
$where
group by fornecedor, grife, codgrife, agrup, modelo, colecao, colmod, clasmod
) as modelos

	left join (select modelo mod_saldo, sum(disponivel) disponivel, sum(conf_montado+em_beneficiamento+saldo_parte) beneficiamento, sum(cet) cet, sum(etq+cep) cep, sum(saldo_most) most,

sum(disponivel+conf_montado+em_beneficiamento+saldo_parte+cet+etq+cep) total

    from saldos left join itens on itens.id = saldos.curto where agrup = '$agrup'
    group by modelo ) as saldos
    on saldos.mod_saldo = modelo

	left join (select modelo mod_vda, sum(qtde) qtde_vda from vendas_jde vds left join itens on itens.id = id_item 
    where ult_status not in (980,984) and agrup = '$agrup' 
    group by modelo) as vendas
    on vendas.mod_vda =  modelo

order by fornecedor, agrup, modelo
");


@endphp



<div class="row">

  <div class="col-md-12">

 
    <!-- row -->
    <div class="row">
		<div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
				
				<li  class="active"><a href="#Painel" data-toggle="tab">Painel</a></li>
				<li><a href="#Tabela" data-toggle="tab">Tabela</a></li>
				<li><a href="#Grade" data-toggle="tab">Grade</a></li>
				<li><a href="#Representantes" data-toggle="tab">Representantes</a></li>
				<li><a href="#Mediasugest" data-toggle="tab">Mediasugest</a></li>
				<li><a href="#Timeline_lancamentos" data-toggle="tab">Timeline_lancamentos</a></li>
				<li><a href="#Estoques" data-toggle="tab">Estoques</a></li>
				<li><a href="#Clientes" data-toggle="tab">Clientes</a></li>
				

				
<div class="tab-content">
	
	
	
<!-- aba geral inicio -->	
<div class="active tab-pane" id="Painel">
<div class="col-md-12">
<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">


<br>	
<div class="col-md-12">
		
		@foreach ($modelos as $catalogo)
		
      <div class="col-sm-2">
        <div class="box box-widget">
         
			<div  class="box-header with-border" style="font-size:12px; padding: 15px 15px 15px 15px;"> 
				
          		<b><a href="/painel/{{$catalogo->agrup}}/{{$catalogo->modelo}}/{{$catalogo->modelo}}" class="text-black">{{$catalogo->modelo}}</a></b>
          		<span class="pull-center"></span>
			 	<span class="pull-right">{{$catalogo->colmod}}</span>
			</div>



        @php
          $foto = app('App\Http\Controllers\ItemController')->consultaFoto($catalogo->modelo);
        @endphp

          <div id="foto" align="center" style="min-height: 180px; max-height: 180px;">
            <a href="" class="zoom" data-value="{{$catalogo->modelo}}"><img src="/{{$foto}}" class="img-responsive"></a>   
          </div>
			
			
			 
		  <br>
		  <table width="100%"  style="font-size:12px;" style="text-align: center;">
			<tr>
				<td>
					<table class="table table-condensed table-bordered table2"  style="text-align: center;">
						<tr>
							<td>Itens:</td>
						</tr>
					</table>
				</td>

				
			 	<td>
					<table class="table table-condensed table-bordered table2"  style="text-align: center;">
						<tr>
							
							<td>{{number_format($catalogo->imediata)}}</td>
						</tr>
					</table>

				</td>
		  
				<td>
					<table class="table table-condensed table-bordered table2" style="text-align: center;">
						<tr>
						
							<td>{{number_format($catalogo->futura)}}</td>
						</tr>
					</table>
				</td>
				<td>
					<table class="table table-condensed table-bordered table2" style="text-align: center;">
						<tr>
							<td>{{number_format($catalogo->producao)}}</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
			
	
			
			<table width="100%" style="font-size:12px;" style="text-align: center;">
								<tr>

								
									<td>
										<table class="table table-condensed table-bordered table2" style="text-align: center;">
											<tr>
												<td><i class="fa fa-calendar-plus-o text-green"></i></td>
												<td>{{$catalogo->clasmod}}</td>
											</tr>
										</table>
									</td>
									<td>
										<table class="table table-condensed table-bordered table2" style="text-align: center;">
											<tr>
												<td><i class="fa fa-calendar-minus-o text-red"></i></td>
												<td>2022 03</td>
											</tr>
										</table>
									</td>
									

								</tr>
							</table>
			
<!--	
		<br>
		 <a title="Com estoque sem vinculo" href="" class="zoom" data-value="{{$catalogo->modelo}}"><i class="fa fa-chain-broken text-red fa-3x" style="position:absolute; top:200px; left:5%; opacity:0.8;" ></i> </a>
					
		 <a title="Revisar item"  href="" class="zoom" data-value="{{$catalogo->modelo}}"><i class="fa fa-warning text-orange fa-2x" style="position:absolute; top:50px; left:5%; opacity:0.8;" ></i></a>
		
-->
			  <br>
					
							<table width="100%" style="font-size:12px;" style="text-align: center;">
								<tr>

								 <td>
										<table class="table table-condensed table-bordered table2"  style="text-align: center;">
											<tr>
												<td align="center"><img src="/img/brasil.png" width="15"></i></td>
												<td>{{number_format($catalogo->disponivel)}}</td>
											</tr>
										</table>

									</td>
									<td>
										<table class="table table-condensed table-bordered table2" style="text-align: center;">
											<tr>
											<td align="center"><img src="/img/to.png" width="15"></i></td>
												<td>{{number_format($catalogo->beneficiamento)}}</td>
											</tr>
										</table>
									</td>
									<td>
										<table class="table table-condensed table-bordered table2" style="text-align: center;">
											<tr>
												<td><i class="fa fa-truck text-green"></i></td>
												<td>0</td>
											</tr>
										</table>
									</td>
									

								</tr>
							</table>
	
<!-- segunda linha -->
					
							<table width="100%" style="font-size:12px;" style="text-align: center;">
								<tr>

								 <td>
										<table class="table table-condensed table-bordered table2"  style="text-align: center;">
											<tr>
												<td><i class="fa fa-plane text-blue"></i></td>
												<td>{{number_format($catalogo->cet)}}</td>
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
												<td>Tt</td>
												<td>{{number_format($catalogo->total)}}</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
	<!-- terceira linha -->
					
							<table width="100%" style="font-size:12px;" style="text-align: center;">
								<tr>

								 <td>
										<table class="table table-condensed table-bordered table2"  style="text-align: center;">
											<tr>
												<td><i class="fa fa-suitcase text-blue"></i></td>
												<td>{{number_format($catalogo->most)}}</td>
											</tr>
										</table>

									</td>
									<td>
										<table class="table table-condensed table-bordered table2" style="text-align: center;">
											<tr>
												<td><i class="fa fa-suitcase text-red"></i></td>
												<td>0</td>
											</tr>
										</table>
									</td>
									<td>
										<table class="table table-condensed table-bordered table2" style="text-align: center;">
											<tr>
												<td><i class="fa fa-recycle text-purple"></i></td>
												<td>0</td>
											</tr>
										</table>
									</td>									
								</tr>
							</table>
	
	<!-- terceira linha -->
					<br>
							<table width="100%" style="font-size:12px;" style="text-align: center;">
								<tr>

								 <td>
										<table class="table table-condensed table-bordered table2"  style="text-align: center;">
											<tr>
												<td><i class="fa fa-shopping-cart text-green"></i></td>
												<td>{{number_format($catalogo->qtde_vda)}}</td>
												<td>{{number_format($catalogo->qtde_vda)}}</td>
												<td>{{number_format($catalogo->qtde_vda)}}</td>
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
     
	@endforeach
	
  </div>


</ul>
</div>
</div>
		  
				  

	  
	  
	  
	  
	  
	  
<div class="tab-pane" id="Tabela">
<!-- The timeline -->

<!-- timeline time label -->
<div class="col-md-12">

<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

<table class="table table-bordered" id="example3">
	<thead>
		<tr>
			<th width="5%">Status</th>
			<th width="8%">Modelo</th>
			<th width="8%">Clasmod</th>
			<th width="8%">Entrada</th>
			<th width="8%">Saida</th>
			<th width="5%">Genero</th>
			<th width="8%">Idade</th>
			<th width="15%">Material</th>
			<th width="15%">Fixacao</th>
			<th width="10%">Estilo</th>
			<th width="10%">Tamanho</th>
			<th width="5%">vds 30dd</th>
			<th width="5%">vds 180dd</th>
			<th width="5%">vds total</th>
			
			<th width="5%">etq disp</th>
			<th width="5%">etq tt</th>
			

		</tr>
	</thead>
	<tbody>
		@foreach ($modelos as $catalogo)

		@php
		switch ($catalogo->modelo) {
			case 'entradas':
			$formato = 'fa fa-plus-square text-green';
			
			break;
			case 'saidas':
			$formato = 'fa fa-minus-square text-red';
			
			break;             
			default:
			$formato = 'fa fa-check-square text-blue';

		}
		@endphp
		<tr>
			<td align="left" class="{{$formato}}"> {{$catalogo->imediata}}</td>
			<td align="left">  <a href="/painel/{{$catalogo->agrup}}/{{$catalogo->modelo}}">{{$catalogo->modelo}}</a></td>
			<td align="left"> {{$catalogo->imediata}}</td>
			
		</tr>
		@endforeach
	</tbody>
</table>
</ul>
</div>
</div>

				  
				  
				  
				  
				  
				  
				  
<div class="tab-pane" id="Grade">
                <!-- The timeline -->
              
                  <!-- timeline time label -->
                  <div class="col-md-12">
		 
 <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

<div class="row">

  <div class="col-md-4">
    <span class="lead">Grade Ideal</span>
    <div class="box box-widget">
      <div class="box-header with-border bg-gray"> 
        <b>{{$catalogo->agrup}}</b>
        <span class="pull-right"><b></b></span>
      </div>
      <div align="center" style="min-height: 100px;margin-top: 30px;">


        @php
          $foto = app('App\Http\Controllers\ItemController')->consultaFoto($catalogo->grife);
        @endphp
		  

        <a href="" class="zoom" data-value="{{$catalogo->grife}}">
           <!-- <img src="/{{$foto}}" class="img-responsive"> -->
			<img src="/img/marcas/{{$catalogo->grife}}.png" style="max-height: 100px;" class="img-responsive">
        </a>
      </div>
		
		
	
		<div class="box-body">

        <div class="row">
          <div class="col-sm-6 col-md-6">
             <table class="table table-bordered" style="text-align: left;">
          <tr>
            <td class="text-danger"><i class="fa fa-chain-broken"></i><b> FALTA VINCULOsssss</td>
            <td class="text-danger">{{$catalogo->grife}} </b>
              
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
                      <td>{{$catalogo->grife}}</td>
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
                              
                                <a href="/vendas_sint?modelo={{$catalogo->grife}}">{{$catalogo->agrup}}</a>
                            
                               
                            </td>
                            
                        </tr>
                    </table>

                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-line-chart text-blue"></i></td>
                            <td>{{$catalogo->codgrife}}</td>
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
                            <td>{{$catalogo->codgrife}}</td>
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
                            <td>modelos_grade</td>
                        </tr>
                    </table>

                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-plane text-blue"></i></td>
                            <td>modelos_entra</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td align="center"><img src="/img/china.png" width="15"></i></td>
                            <td>modelos_sai</td>
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
					<td>{{$catalogo->codgrife}} </td>
					<td>{{$catalogo->codgrife}} </td>
					<td>{{$catalogo->codgrife}} </td>
					<td>{{$catalogo->codgrife}} </td>
					<td>{{$catalogo->codgrife}} </td>
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
					<td>{{$catalogo->codgrife}} </td>
					<td>{{$catalogo->codgrife}} </td>
					<td>{{$catalogo->codgrife}} </td>
					<td>{{$catalogo->codgrife}} </td>
					<td>{{$catalogo->codgrife}} </td>
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
					<td>{{$catalogo->codgrife}} </td>
					<td>{{$catalogo->codgrife}} </td>
					<td>{{$catalogo->codgrife}} </td>
					<td>{{$catalogo->codgrife}} </td>
					<td>{{$catalogo->codgrife}} </td>
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
					<td>{{$catalogo->imediata}} </td>
					<td>{{$catalogo->codgrife}} </td>
					<td>{{$catalogo->codgrife}} </td>
					<td>{{$catalogo->codgrife}} </td>
					<td>{{$catalogo->codgrife}} </td>
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
					<td>{{$catalogo->codgrife}} </td>
					<td>{{$catalogo->codgrife}} </td>
					<td>{{$catalogo->codgrife}} </td>
					<td>{{$catalogo->codgrife}} </td>
					<td>{{$catalogo->codgrife}} </td>
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
					<td>{{$catalogo->codgrife}} </td>
					<td>{{$catalogo->codgrife}} </td>
					<td>{{$catalogo->codgrife}} </td>
					<td>{{$catalogo->codgrife}} </td>
					<td>{{$catalogo->codgrife}} </td>
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
      @foreach ($query as $catalogo)

        @php
          switch ($catalogo->statusatual) {
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
			  <b> <td><a href="/produtos/gradescoldet/{{$catalogo->agrup}}?colecao={{$catalogo->colmod}}">{{$catalogo->colmod}}+999</a></td>
 </b>
		<!--	  <span class="pull-right">{{$catalogo->colmod}}</span> -->
			</div>

			
			  @if ($catalogo->statusatual > 0 )
 	<!--					<br>
						<table class="table table-bordered" style="text-align: left;">
					  <tr>
						  <td class=""><i class="fa fa-chain"></i><b> Itens</td>
						<td class="">{{$catalogo->atual}} </b>
							</td>
					  </tr> </table>
 	-->	
		
            @endif
              
				

		
		@if ($catalogo->statusatual > 0 and  $catalogo->statusatual < 1)
		<br><br>
		 <a title="Com estoque sem vinculo" href="" class="zoom" data-value="{{$catalogo->colecao}}"><i class="fa fa-chain-broken text-red fa-3x" style="position:absolute; top:200px; left:5%; opacity:0.8;" ></i> </a>
				
			@endif
            
		@if($catalogo->statusatual < 1 and  $catalogo->statusatual < 1)
		
		 <a title="Revisar item"  href="" class="zoom" data-value="{{$catalogo->colmod}}"><i class="fa fa-warning text-orange fa-2x" style="position:absolute; top:200px; left:5%; opacity:0.8;" ></i></a>
		
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
                            <td>999</td>
                        </tr>
                    </table>

                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-check-square text-blue"></i></td>
                            <td>0</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-plus-square text-green"></i></td>
                            <td>0</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-minus-square text-red"></i></td>
                            <td>0</td>
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
                            <td>fem</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-male text-blue"></i></td>
                            <td>mas</td>
                        </tr>
                    </table>
                </td>
				
				  <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-female text-red"></i><i class="fa fa-male text-yellow"></i></td>
                            <td>unis</td>
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
                            <td>adul</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>Young</b></td>
                            <td>yo</td>
                        </tr>
                    </table>
                </td>
				  <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>Kids</b></td>
                            <td>inf</td>
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
                            <td></td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>Acetate</b></td>
                            <td> </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>Plastic</b></td>
                            <td> </td>
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
                            <td> }</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>Nylon</b></td>
                            <td> </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>Ballgriff</b></td>
                            <td> </td>
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
                            <td> </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>Fashion</b></td>
                            <td> </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>Sport</b></td>
                            <td> </td>
                        </tr>
                    </table>
                </td>
				 <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>Luxury</b></td>
                            <td> </td>
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
                            <td> </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>51-53</b></td>
                            <td> </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>54-56</b></td>
                            <td> </td>
                        </tr>
                    </table>
                </td>
 				<td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><b>>=57</b></td>
                            <td> </td>
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

					   </ul>
				  </div>
					   </div>
				  
				  
				  
				  

<div class="tab-pane" id="qualidade">
<div class="col-md-12">
<ul class="timeline">


@php

$fotos = \DB::select("select * from itens where modelo = 'ah6254' ");

	$result = count($fotos);
	echo 'resultado'.$result;
	
@endphp
	
	

<div class="col-md-12">
<span class="lead">Modelos</span>
<div class="row">		
		
		@foreach ($fotos as $catalogo)
		
		
		


<div class="row">

      <div class="col-md-2">
        <div class="box box-widget">
          <div  class="box-header with-border" style="font-size:14px; padding: 12px 10px 12px 10px;"> 
          <b><a href="/painel/{{$catalogo->agrup}}/{{$catalogo->modelo}}/{{$catalogo->modelo}}" class="text-black">{{$catalogo->modelo}}</a></b>
          <span class="pull-right">  {{$catalogo->modelo}}</span>
			  <span class="pull-right">{{$catalogo->colmod}}</span>
			 
          
                
			
			</div>



        @php
          $foto = app('App\Http\Controllers\ItemController')->consultaFoto($catalogo->modelo);
        @endphp

          <div id="foto" align="center" style="min-height: 100px; max-height: 100px;">
            <a href="" class="zoom" data-value="{{$catalogo->modelo}}"><img src="/{{$foto}}" class="img-responsive"></a>

                  
          </div>
			
			
			 
			<br>
			<table class="table table-bordered" style="text-align: left;">
          <tr>
			  <td class=""><i class="fa fa-chain"></i><b> Itens</td>
            <td class="">{{$catalogo->valortabela}} </b>
				</td>
          </tr> </table>
      
              
				

		
		
		<br><br>
		 <a title="Com estoque sem vinculo" href="" class="zoom" data-value="{{$catalogo->modelo}}"><i class="fa fa-chain-broken text-red fa-3x" style="position:absolute; top:200px; left:5%; opacity:0.8;" ></i> </a>
				
			
            
		
		
		 <a title="Revisar item"  href="" class="zoom" data-value="{{$catalogo->modelo}}"><i class="fa fa-warning text-orange fa-2x" style="position:absolute; top:50px; left:5%; opacity:0.8;" ></i></a>
		

		
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
                            <td>{{number_format($catalogo->valortabela)}}</td>
                        </tr>
                    </table>

                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-plane text-blue"></i></td>
                            <td>{{number_format($catalogo->valortabela)}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-industry text-purple"></i></td>
                            <td>{{number_format($catalogo->valortabela)}}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="table table-condensed table-bordered table2" style="text-align: center;">
                        <tr>
                            <td><i class="fa fa-warning text-yellow"></i></td>
                            <td>{{number_format($catalogo->valortabela)}}</td>
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


</ul>
</div>
</div>
				
	
	  
	  
	  
	  
	  
	  
	  
				  
				  
<div class="tab-pane" id="apontamentos">
                <!-- The timeline -->
              
                  <!-- timeline time label -->
                  <div class="col-md-12">
		 
        <!-- The time line -->
        <ul class="timeline">

@php

  $datas = \DB::select("select date(historicos.created_at) as data

from historicos 
left join itens on id_item = itens.id			
where secundario LIKE  'ah6254 a01%'
			and categoria = 'apontamentos'
group by date(historicos.created_at)
order by date(historicos.created_at) desc
		
			");

@endphp


@foreach ($datas as $data)

      <li class="time-label">
            <span class="bg-yellow">
              {{date("d/m/Y", strtotime($data->data))}}
            </span>
      </li>

    @php

      $historicos = \DB::select("select historicos.*, usuarios.nome
from historicos 
left join usuarios on id_usuario = usuarios.id
left join itens on id_item = itens.id			
where secundario LIKE  'ah6254 a01%' and date(historicos.created_at) = '$data->data'
			and categoria = 'apontamentos'
			order by historicos.created_at desc
			");

    @endphp

      @foreach ($historicos as $historico)

			<li>

            <i class="fa fa-envelope bg-yellow"></i>
			  

            <div class="timeline-item">
              <span class="time"><i class="fa fa-clock-o"></i> aaaa</span>

              <h3 class="timeline-header"><a href="#">{{$historico->nome}}</a> alterou uma {{$historico->categoria}}</h3>

              <div class="timeline-body">
                {!!$historico->historico!!}
                @if ($historico->arquivo <> '')

                  @php
                    $arquivo = explode('.', $historico->arquivo);
                  @endphp

                  @if (isset($arquivo[1]) && (strtolower($arquivo[1]) == 'jpg' or strtolower($arquivo[1]) == 'jpeg' )) 

                    <img src="/storage/{{$historico->arquivo}}" class="img-responsive">

                  @else

                    <br>Arquivo: <a href="/storage/{{$historico->arquivo}}" target="_blank">{{$historico->arquivo}}</a>

                  @endif
                  

                @endif
              </div>
				


              <div class="timeline-footer">
                <a href="/historico/{{$historico->id}}/deleta" class="btn btn-danger btn-xs">Delete</a>
              </div>
            </div>

          </li>
        @endforeach

@endforeach
					   </ul>
				  </div>
					   </div>

				  
<div class="tab-pane" id="reprocessos">
                <!-- The timeline -->
              
                  <!-- timeline time label -->
                  <div class="col-md-12">
		 
        <!-- The time line -->
        <ul class="timeline">

@php

  $datas = \DB::select("select date(historicos.created_at) as data
from historicos
left join itens on id_item = itens.id			
where secundario LIKE  'ah6254 a01%'
			
group by date(historicos.created_at)
order by date(historicos.created_at) desc
		
			");

@endphp


@foreach ($datas as $data)

      <li class="time-label">
            <span class="bg-blue">
              {{date("d/m/Y", strtotime($data->data))}}
            </span>
      </li>

    @php

      $reprocessos = \DB::select("select historicos.*, usuarios.nome
from historicos 
left join usuarios on id_usuario = usuarios.id
left join itens on id_item = itens.id			
where secundario LIKE  'ah6254 a01%' and date(historicos.created_at) = '$data->data'
			and categoria = 'reprocesso'
			order by historicos.created_at desc
			");

    @endphp

      @foreach ($reprocessos as $reprocesso)

			<li>

            <i class="fa fa-envelope bg-blue"></i>
			  

            <div class="timeline-item">
              <span class="time"><i class="fa fa-clock-o"></i> {{$reprocesso->created_at}}</span>

              <h3 class="timeline-header"><a href="#">{{$reprocesso->nome}}</a> alterou uma {{$reprocesso->categoria}}</h3>

              <div class="timeline-body">
                {!!$reprocesso->historico!!}
                @if ($reprocesso->arquivo <> '')

                  @php
                    $arquivo = explode('.', $reprocesso->arquivo);
                  @endphp

                  @if (isset($arquivo[1]) && (strtolower($arquivo[1]) == 'jpg' or strtolower($arquivo[1]) == 'jpeg' )) 

                    <img src="/storage/{{$reprocesso->arquivo}}" class="img-responsive">

                  @else

                    <br>Arquivo: <a href="/storage/{{$reprocesso->arquivo}}" target="_blank">{{$historico->arquivo}}</a>

                  @endif
                  

                @endif
              </div>
				


              <div class="timeline-footer">
                <a href="/historico/{{$reprocesso->id}}/deleta" class="btn btn-danger btn-xs">Delete</a>
              </div>
            </div>

          </li>
        @endforeach



@endforeach
					   </ul>
				  </div>
					   </div>			

             <div class="tab-pane" id="data_producao">
                <!-- The timeline -->
              
                  <!-- timeline time label -->
                  <div class="col-md-12">
     
        <!-- The time line -->
        <ul class="timeline">

@php

  $datas = \DB::select("select date(historicos.created_at) as data
from historicos
left join itens on id_item = itens.id     
where secundario LIKE  'ah6254 a01%'
      and categoria = 'data_producao'
group by date(historicos.created_at)
order by date(historicos.created_at) desc
    
      ");

@endphp


@foreach ($datas as $data)

      <li class="time-label">
            <span class="bg-blue">
              {{date("d/m/Y", strtotime($data->data))}}
            </span>
      </li>

    @php

      $data_producao = \DB::select("select historicos.*, usuarios.nome
from historicos 
left join usuarios on id_usuario = usuarios.id
left join itens on id_item = itens.id     
where secundario LIKE  'ah6254 a01%' and date(historicos.created_at) = '$data->data'
      and categoria = 'data_producao'
      order by historicos.created_at desc
      ");

    @endphp

      @foreach ($data_producao as $data_producao1)

      <li>

            <i class="fa fa-envelope bg-blue"></i>
        

            <div class="timeline-item">
              <span class="time"><i class="fa fa-clock-o"></i> {{$data_producao1->created_at}}</span>

              <h3 class="timeline-header"><a href="#">{{$data_producao1->nome}}</a> alterou uma {{$data_producao1->categoria}}</h3>

              <div class="timeline-body">
                {!!$data_producao1->historico!!}<br>
                <b>Nova data entrega</b> {!!$data_producao1->nova_data_producao!!}<br>
                <b>Pedido fábrica</b> {!!$data_producao1->pedido_fabrica!!}
                @if ($data_producao1->arquivo <> '')

                  @php
                    $arquivo = explode('.', $data_producao1->arquivo);
                  @endphp

                  @if (isset($arquivo[1]) && (strtolower($arquivo[1]) == 'jpg' or strtolower($arquivo[1]) == 'jpeg' )) 

                    <img src="/storage/{{$data_producao1->arquivo}}" class="img-responsive">

                  @else

                    <br>Arquivo: <a href="/storage/{{$data_producao1->arquivo}}" target="_blank">{{$historico->arquivo}}</a>

                  @endif
                  

                @endif
              </div>
        


              <div class="timeline-footer">
                <a href="/historico/{{$data_producao1->id}}/deleta" class="btn btn-danger btn-xs">Delete</a>
              </div>
            </div>

          </li>
        @endforeach

        

@endforeach
             </ul>
          </div>
             </div>     	  				  
				  				  				  
				  				  				  				  
				  				  				  				  				  				  
</div> <!-- div tab content -->

  </div>

</div>

@include('produtos.painel.modal.genero')
@include('produtos.painel.modal.caracteristica')
@stop