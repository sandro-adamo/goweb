@extends('layout.principal')

@php

$representantes = Session::get('representantes');
$grifes = Session::get('grifes');
$cli  = $_GET["cli"];

@endphp

@section('title')
<i class="fa fa-users">{{$cli}}</i> 
@append 

@section('conteudo')

<form action="/titulos_form/grava" method="post"> 
<input type="hidden" name="cliente" value="{{$cli}}">
	@csrf
	@php



	$query_1 = \DB::select("

		select distinct grife  from carteira where grife not in ('EP1','EP2','EP3','EP4','EP5','EP6') and status = 1 and rep in ($representantes)

		");

		
	$query_2 = \DB::select("	
		select distinct cliente, pesq.created_at data, us.nome from pesquisa_naovenda pesq left join usuarios us on us.id = pesq.usuario where cliente = '$cli'
	");
	
	
	$query_3 = \DB::select("	
		select distinct cliente, grife, motivo, obs, nome, date(pesq.created_at) data from pesquisa_naovenda pesq left join usuarios us on us.id = pesq.usuario where cliente = '$cli'
	");
	
	
	$i=0;
	
	@endphp

  <h6>
		<div class="row">
			<div class="col-md-4">
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
									<td colspan="1" align="center">data</td>
								</thead>	
							</tr>

							@foreach ($query_2 as $query2)

								<tr>
									<td><a href=""><i class="fa fa-file"></i></a></td>
										<td>{{$query2->nome}}</td>	
										<td>{{$query2->data}}</td>	
								
								</tr>


							@endforeach 

						</table>
					</div>
				</div>
			</div>
		















<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Timeline
     
      </h1>
   
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- row -->
      <div class="row">
        <div class="col-md-4">
          <!-- The time line -->
          <ul class="timeline">
            <!-- timeline time label -->
			  
			  @foreach ($query_3 as $query3)
			  
            <li class="time-label">
                  <span class="bg-red">
                    <td>{{$query3->data}}</td>	
                  </span>
            </li>
            <!-- /.timeline-label -->
            <!-- timeline item -->
            <li>
              <i class="fa fa-envelope bg-blue"></i>

              <div class="timeline-item">
                <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

                <h3 class="timeline-header"><a href="#"><td>{{$query3->nome}}</td>	</a> {{$query3->obs}}</h3>

                <div class="timeline-body">
                  <td>{{$query3->grife}}</td>
				<td>{{$query3->motivo}}</td>
                </div>
                <div class="timeline-footer">
                  <a class="btn btn-primary btn-xs">Read more</a>
                  <a class="btn btn-danger btn-xs">Delete</a>
                </div>
				    @endforeach 
              </div>
				
            </li>
            <!-- END timeline item -->
            <!-- timeline item -->
            
         
            <li>
              <i class="fa fa-clock-o bg-gray"></i>
            </li>
			
          </ul>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->



      </div>
	</div>	


</h6>

@stop
