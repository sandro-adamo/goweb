@extends('layout.principal')

@php

$representantes = Session::get('representantes');
$tipo  = $_GET["tipo"];
$pedido  = $_GET["pedido"];

echo 'pedido'.$pedido;
echo 'tipo'.$tipo;


@endphp

@section('title')
<i class="fa fa-users">{{$tipo}}</i> 
@append 

@section('conteudo')

<form action="/titulos_form/grava" method="post"> 
<input type="hidden" name="cliente" value="{{$pedido}}">
	@csrf
	@php


	$query_1 = \DB::select("

		select distinct grife  from carteira where grife not in ('EP1','EP2','EP3','EP4','EP5','EP6') and status = 1 and rep in ($representantes)

		");

		
	$query_2 = \DB::select("	
		select distinct cliente, pesq.created_at data, us.nome from pesquisa_naovenda pesq left join usuarios us on us.id = pesq.usuario where cliente = 'CTM - FRANQUIA - EDUARDO'
	");
	
	
	$query_3 = \DB::select("	
		select distinct cliente, grife, motivo, obs, nome, date(pesq.created_at) data from pesquisa_naovenda pesq left join usuarios us on us.id = pesq.usuario where cliente = 'CTM - FRANQUIA - EDUARDO'
	");
	
	$query_4 = \DB::select("	
		select * from compras_parcelas where id_pedido = $pedido and tipo = '$tipo'
	");
	
	
	$i=0;
	
	@endphp

  <h6>
		<div class="row">
			<div class="col-md-12">
  				<div class="box box-widget box-body">
					<div class="table-responsive">

						<table class="table table-bordered" id="example3">
							<thead>
								<tr>	
									<td colspan="5">Motivo da nao venda</td>
								</tr>

								<tr>	
									<td colspan="1" align="center">Grifes</td>
									<td colspan="1" align="center">Motivo</td>
									
						  </thead>	
							</tr>

							@foreach ($query_1 as $query1)

								<tr>
									<td align="left">
										<label><input type="checkbox" name="grife[]" value="{{$query1->grife}}" id="CheckboxGroup1_0"> {{$query1->grife}}</label>	
									</td>
									<td align="left">					
										<label for="cars"></label>
										<select name="motivos{{$query1->grife}}">
											<option value="">Tipo:</option>
											<option value="estoque">Adiantamento</option>
											<option value="recurso">Embarque</option>
											<option value="reagenda">Parcelas</option>
											<option value="naogrife">Despesas</option>
											<option value="fechou">Impostos</option>
											
										</select>

									</td>
								{{-- 	
									<td align="left">					
										<label for="atend"></label>
										<select name="catalogos{{$query1->grife}}">
											<option value="">catalogo:</option>
											<option TYPE="checkbox" NAME="OPCAO" VALUE="op1"> opção1
												 <input value="1" checked id="box2" type="checkbox" onClick="CheckBoxClick('box2');">											
										</select>

									</td>
								--}}
									
								</tr>

								@php
									$i++;
								@endphp

							@endforeach 

						</table>
					</div>
				
				
				
				</div>
			
			tipo de contato
			
			
			
			<p>
			  <label>
			    <input type="radio" name="atendimento" value="presencial" id="RadioGroup1_0">
			    Atendimento Presencial</label>
			  <br>
			  <label>
			    <input type="radio" name="atendimento" value="virtual" id="RadioGroup1_1">
			    Atendimento Virtual/Telefone</label>
			  <br>
  			</p>
			
			
			<p>
			  <label>Adiantamento : 
			    <input type="text" name="adiantamento" value={{$pedido}} id="RadioGroup1_0">
			    </label>
			  <br>
				
			  <label> 
			   Valor Embarque <input type="text" name="embarque" value={{$tipo}} id="RadioGroup1_1">
			   Dt Embarque 	  <input type="text" name="dt_embarque" value={{$tipo}} id="RadioGroup1_1">  
			  </label>
				
			  <br>
  			</p>
			
			
			
  <label for="obs">obs:</label>
						<input type="text" id="obs" name="obs" size="50">
  <button type="submit" >Salvar</button>
</form>	

</div>
		
		
		
		
		
		
		<div class="col-md-4">
				<div class="box box-widget box-body">
					<div class="table-responsive">

						<table class="table table-bordered" id="example3">
							<thead>
								<tr>	
									<td colspan="5">Pesquisas</td>
								</tr>

								<tr>	
									<td>form</td>
									<td colspan="1" align="center">Usuario</td>
									
								</thead>	
							</tr>

							@foreach ($query_4 as $query4)

								<tr>
									<td><a href=""><i class="fa fa-file"></i></a></td>
										<td>{{$query4->numero}}</td>	
								</tr>


							@endforeach 

						</table>
					</div>
				</div>
			</div>
		</div>	


</h6>

@stop
