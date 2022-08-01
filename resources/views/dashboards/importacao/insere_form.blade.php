@extends('layout.principal')

@php

$pedido = $_GET["pedido"];
$tipo = $_GET["tipo"];





@endphp

@section('title')
<i class="fa fa-file-text-o"></i> Detalhe do pedido</i> 
@append 

@section('conteudo')
	@csrf
	@php

	
	$result = \DB::select("select id from compras_infos where id_pedido = $pedido");
		
			if(count($result)==1){
			$id_info = 	$result[0]->id;
			$acao = "update";
			
			
			}else{
			$acao = "insert";
			$id_info = 	0;
	
			}
	
	

	$query_1 = \DB::select("
	

	

select *, ifnull(volumes,0) volumes1, ifnull(peso_bruto,0) peso_bruto1

from (
	select ped.pedido num_pedido, ped.tipo tipo_pedido, ped.dt_pedido, ped.ref_go invoice, concat(ped.ult_status,' ', ped.prox_status) ult_prox_ped,
    ped.moeda moeda_pedido, sum(ped.qtde_sol) qtde_ped, sum(ped.vlr_total) vlr_pedido,
    Ref_Nac_01 num_di, ref_nac_02 data_di,
	nf.prenota, nf.dt_emissao dt_nf, concat(nf.ult_status, ' ', nf.prox_status) status_nf, sum(nf.qtde) qtde_nf, sum(nf.total) vlr_nf, sum(nf.icms) icms_nf, sum(nf.ipi) ipi_nf,
	'pecas - groupconcat' tipo_carga, 'group concat' grifes, '' as obs_transito,
case 
when ped.prox_status = 230 then 'ped_inserido' 
when ped.prox_status = 280 then 'PL_recebido' 
when ped.prox_status = 345 then 'confirmado' 
when ped.prox_status = 350 then 'li_solicitado'
when ped.prox_status = 355 then 'li_deferida'
when ped.prox_status = 359 then 'emb_autorizado'
when ped.prox_status = 365 then 'booking'
when ped.prox_status = 369 then 'chegada_Br'
when ped.prox_status = 375 then 'removido'
when ped.prox_status = 379 then 'registrado'
when ped.prox_status = 385 then 'nf_emitida'
when ped.prox_status = 390 then 'carregada'
when ped.prox_status = 400 then 'chegou_TO' else '' end as desc_status
    
	from importacoes_pedidos ped 
	left join importacoes_notas nf on nf.ped_original = ped.pedido and nf.tipo_original = ped.tipo and nf.linha_original = ped.linha
	
	where ped.pedido = '$pedido' and ped.tipo = '$tipo'

	group by ped.pedido, ped.tipo , ped.dt_pedido, ped.ref_go ,  concat(ped.ult_status,' ', ped.prox_status),
    Ref_Nac_01, ref_nac_02,
	nf.prenota, nf.dt_emissao ,  concat(nf.ult_status, ' ', nf.prox_status) , ped.moeda , ped.prox_status
) as final


	left join (select * from compras_infos ) as ci
    on ci.id_pedido = final.num_pedido and ci.tipo_pedido = final.tipo_pedido	");

	
@endphp
		

<div class="row">



   <div class="col-md-12">
          <div class="nav-tabs-custom">
           
			<ul class="nav nav-tabs">
              <li class="active"><a href="#dados" data-toggle="tab">Dados</a></li>
              <li><a href="#timeline" data-toggle="tab">Timeline</a></li>
              <li><a href="#settings" data-toggle="tab">Settings</a></li>
            </ul>
			  
			  
			  
            <div class="tab-content">
				
				
				
				
              <div class="active tab-pane" id="dados">
				  <form action="/import_form/grava" method="post" class="form-horizontal">
<input type="hidden" id="id_info" name="id_info" size="50" value={{$id_info}}>
<input type="hidden" id="acao" name="acao" size="50" value={{$acao}} >
<input type="hidden" id="pedido" name="pedido" size="50" value={{$pedido}} >
<input type="hidden" id="tipo" name="tipo" size="50" value={{$tipo}} >	

                <!-- Post -->
                <div class="post">
					

					<div class="box box-danger">
					  <h3 class="box-title">Documentacao embarque</h3>
						<th>{{$acao}} - {{$id_info}}  </th>
	
					  <table class="table table-bordered table-condensed">
						<tr  class="card-header bg-info text-center">
						  <td><b>Num Invoice</b></td>
						  <td><b>Dt emissao</b></td>
						  <td><b>Cubagem</b></td>
						  <td><b>Volumes</b></td>
						  <td><b>Peso Bruto</b></td>
						 <td><b>Obs Invoice</b></td>
						</tr>


						<tr class="text-center">
						  <td><input type="text" id="invoice" name="invoice" size="30" value={{$query_1[0]->invoice}} ></td>
						  <td><input type="date" id="dt_invoice" name="dt_invoice" size="10" value={{$query_1[0]->dt_invoice}} ></td>
						  <td><input type="text" id="cubagem_m3" name="cubagem_m3" size="5" value={{$query_1[0]->cubagem_m3}} ></td>
						  <td><input type="text" id="volumes" name="volumes" size="5" value={{$query_1[0]->volumes1}}></td>
						  <td><input type="text" id="peso_bruto" name="peso_bruto" size="10" value={{$query_1[0]->peso_bruto1}} ></td>
						  <td><input type="text" id="obs_invoice" name="obs_invoice" size="35" value={{$query_1[0]->obs_invoice}} ></td>

						</tr>
					</table>
						  
						  
					 <table class="table table-bordered table-condensed">
						 <tr  class="card-header bg-info text-center">      

						  <td><b>Doc Agrup	</b></td>
						  <td><b>Tipo Carga</b></td>
						  <td><b>Ref processo</b></td>
						  <td><b>Numero OK</b></td>
						  <td><b>Numero SX</b></td>
						</tr>		  

						<tr class="text-center">
						  <td><input type="text" id="doc_agrup" name="doc_agrup" size="20" value='{{$query_1[0]->doc_agrup}}' ></td>
						  <td><input type="text" id="tipo_carga" name="tipo_carga" size="20" value={{$query_1[0]->tipo_carga}} ></td>
						  <td size="20">{{$query_1[0]->ref_processo}}</td>
						  <td size="20">{{$query_1[0]->num_pedido}}</td>
						  <td size="20">{{$query_1[0]->num_pedido}}</td>
						</tr>


					  </table> 
			
					  </div> 
					
					
					
				<div class="box box-warning">
				<h3 class="box-title">Transito</h3>

				 <table class="table table-bordered table-condensed">
					<tr  class="card-header bg-info text-center">
					  <td><b>Dt solicitacao LI</b></td>
					  <td><b>Dt deferimento LI</b></td>
					  <td><b>Transp Internacional</b></td>
					  <td><b>Dt aut Embarque</b></td>
					 <td><b>Obs Transito</b></td>
					</tr>



					<tr class="text-center">
					  <td><input type="date" id="dt_sol_li" name="dt_sol_li" size="10" value={{$query_1[0]->dt_sol_li}} ></td>
					  <td><input type="date" id="dt_def_li" name="dt_def_li" size="10" value={{$query_1[0]->dt_def_li}} ></td>

						  <td>@php 
						  $fornecedor1 = \DB::select("select id, fantasia from addressbook where nome like '%junior%'");
						  @endphp
						  <select class="form-control" name="an8_agente_int" >			 
						  <option value="">{{$query_1[0]->an8_agente_int}}</option>
							@foreach ($fornecedor1 as $forn1)
						  <option value="{{$forn1->id}} - {{$forn1->fantasia}}">{{$forn1->id}} - {{$forn1->fantasia}}</option>
							@endforeach
						  </select>
						  </td>

					  <td><input type="date" id="dt_aut_embarque" name="dt_aut_embarque" size="10" value={{$query_1[0]->dt_aut_embarque}} ></td>
					  <td><input type="text" id="obs_transito" name="obs_transito" size="10" value={{$query_1[0]->obs_transito}} ></td>

					</tr>


					</table>
					
					

					<table class="table table-bordered table-condensed">
					 <tr  class="card-header bg-info text-center">      
					  <td><b>Num HAWB</b></td>
					  <td><b>Dt Embarque</b></td>
					  <td><b>Dt Previsao Chegada</b></td>
					  <td><b>Dt Chegada</b></td>
					  <td><b>Obs Chegada</b></td>
					  <td><b>Dt remocao</b></td>
					  
					</tr>		  

					<tr class="text-center">
					<td><input type="text" id="num_awb" name="num_awb" size="20" value={{$query_1[0]->num_awb}} ></td>
					<td><input type="date" id="dt_emb_int" name="dt_emb_int" size="20" value={{$query_1[0]->dt_emb_int}} ></td>
					<td><input type="date" id="dt_prev_chegada" name="dt_prev_chegada" size="20" value={{$query_1[0]->dt_prev_chegada}} ></td>
					<td><input type="date" id="dt_chegada" name="dt_chegada" size="20" value={{$query_1[0]->dt_chegada}} ></td>
					<td><input type="text" id="obs_chegada" name="obs_chegada" size="20" value={{$query_1[0]->obs_chegada}} ></td>
					<td><input type="date" id="dt_remocao" name="dt_remocao" size="20" value={{$query_1[0]->dt_remocao}} ></td>
			
					</tr>

				  </table>
					
				 </div>
					
					
			
			
				
					
					<div class="box box-info">
					  <h3 class="box-title">Nacionalizacao</h3>
					  <h6>
					 <table class="table table-bordered table-condensed">


						 <tr  class="card-header bg-info text-center">
						  <td><b>Numero DI ->jde</b></td>
						  <td><b>Dt registro DI  ->jde</b></td>
						  <td><b>Num protocolo DI</b></td>
						  <td><b>Cambio registro ex dt_di</b></td>
						</tr>


						<tr class="text-center">
							<td>{{$query_1[0]->num_di}}</td>
						  <td><input type="date" id="dt_registro" name="dt_registro" size="20" value={{$query_1[0]->dt_registro}} ></td>
						  <td><input type="text" id="protocolo_di" name="protocolo_di" size="20" value={{$query_1[0]->protocolo_di}} ></td>
							<td>{{$query_1[0]->data_di}}</td>
						</tr>

						  </table>
						<table class="table table-bordered table-condensed">

						 <tr  class="card-header bg-info text-center">

						  <td><b>Num Pre-NF (NI)</b></td>
						  <td><b>Num NF legal</b></td>
						  <td><b>Dt emissao NF</b></td>
						  <td><b>Qtde NF</b></td>
						  <td><b>Valor NF</b></td>

						 </tr>

						<tr class="text-center">
						<td>{{$query_1[0]->prenota}}</td>
						 <td></td>
						 <td></td>
						 <td></td>
						 <td></td> 
						</tr>

						  </table>
						  
						<table class="table table-bordered table-condensed">

						 <tr  class="card-header bg-info text-center">

						  <td><b>Dt prev Transp Nac</b></td>
						  <td><b>Transporte Nac</b></td>
						  <td><b>Dt Carregamento</b></td>
						  <td><b>Dt entrega Fabrica</b></td>

						 </tr>

						<tr class="text-center">
							<td><input type="date" id="dt_prev_embnac" name="dt_prev_embnac" size="20" value={{$query_1[0]->dt_prev_embnac}} ></td>
						  <td>@php 
						  $fornecedor2 = \DB::select("select id, fantasia from addressbook where nome like '%junior%'");
						  @endphp
						  <select class="form-control" name="an8_agente_nac" >			 
						  <option value="">{{$query_1[0]->an8_agente_nac}}</option>
							@foreach ($fornecedor2 as $forn2)
						  <option value="{{$forn2->id}} - {{$forn2->fantasia}}">{{$forn2->id}} - {{$forn2->fantasia}}</option>
							@endforeach
						  </select>
						  </td>	

							<td><input type="date" id="dt_emb_nac" name="dt_emb_nac" size="20" value={{$query_1[0]->dt_emb_nac}} ></td>
							<td><input type="date" id="dt_recebimento" name="dt_recebimento" size="20" value={{$query_1[0]->dt_recebimento}} ></td>
						</tr>		 
					  </table>
					  </h6>
					  </div>
		
					
					

					
					
					<div class="box box-success">
					  <h3 class="box-title">Trading OP</h3>
						   <h6>
					 <table class="table table-bordered table-condensed">

						<tr  class="card-header bg-info text-center">
						  <td><b>num_invoice</b></td>
						  <td><b>dt_invoice</b></td>
						  <td><b>num_nf</b></td>
						  <td><b>dt_nf</b></td>
						  <td><b>ref_comex</b></td>
						  <td><b>valor_total</b></td>
						</tr>	 

						<tr class="text-center">
						<div></div>	
						<div></div>	
						<div></div>	
						<div></div>	
						<div></div>	
						<div></div>	  
						</tr>

					<tr> <td colspan="6"></td></tr> 


						<tr  class="card-header bg-info text-center">
						  <td><b>desc_dupl_1</b></td>
						  <td><b>valor_dupl_1</b></td>
						  <td><b>venc_dupl_1</b></td>
						  <td><b>desc_dupl_2</b></td>
						  <td><b>valor_dupl_2</b></td>
						  <td><b>venc_dupl_2</b></td>
						  <td><b>desc_dupl_3</b></td>
						  <td><b>valor_dupl_3</b></td>
						  <td><b>venc_dupl_3</b></td>

						</tr>	 		 
						<tr class="text-center">
						<div></div>	
						<div></div>	
						<div></div>	
						<div></div>	
						<div></div>	
						<div></div>	  
						<div></div>	
						<div></div>	
						<div></div>	
						</tr>

					<tr> <td colspan="6"></td></tr> 

						 <tr  class="card-header bg-info text-center">
						  <td><b>taxa_cambio_fat</b></td>
						  <td><b>taxa_cambio_lc</b></td>
						  <td><b>nf_complementar</b></td>
						  <td><b>valor_nfc</b></td>
						  <td><b>venc_nfc</b></td>
						  <td><b>dt_pgto_nfc</b></td>
						</tr>	 

						<tr class="text-center">
						<div></div>	
						<div></div>	
						<div></div>	
						<div></div>	
						<div></div>	
						<div></div>	  
						</tr>

						 </h6>
					  </table>		 
						</div>	
					
					
				<button type="submit" class="btn btn-primary"><i class="fa fa-refresh"></i> Enviar</button>	
				</form>
					
                </div>
                <!-- /.post -->
              </div>
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
              <!-- /.tab-pane -->
              <div class="tab-pane" id="timeline">
                <!-- The timeline -->
                <ul class="timeline timeline-inverse">
                  <!-- timeline time label -->
                  <li class="time-label">
                        <span class="bg-red">
                          10 Feb. 2014
                        </span>
                  </li>
                  <!-- /.timeline-label -->
                  <!-- timeline item -->
                  <li>
                    <i class="fa fa-envelope bg-blue"></i>

                    <div class="timeline-item">
                      <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

                      <h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>

                      <div class="timeline-body">
                        Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                        weebly ning heekya handango imeem plugg dopplr jibjab, movity
                        jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                        quora plaxo ideeli hulu weebly balihoo...
                      </div>
                      <div class="timeline-footer">
                        <a class="btn btn-primary btn-xs">Read more</a>
                        <a class="btn btn-danger btn-xs">Delete</a>
                      </div>
                    </div>
                  </li>
                  <!-- END timeline item -->
                  <!-- timeline item -->
                  <li>
                    <i class="fa fa-user bg-aqua"></i>

                    <div class="timeline-item">
                      <span class="time"><i class="fa fa-clock-o"></i> 5 mins ago</span>

                      <h3 class="timeline-header no-border"><a href="#">Sarah Young</a> accepted your friend request
                      </h3>
                    </div>
                  </li>
                  <!-- END timeline item -->
                  <!-- timeline item -->
                  <li>
                    <i class="fa fa-comments bg-yellow"></i>

                    <div class="timeline-item">
                      <span class="time"><i class="fa fa-clock-o"></i> 27 mins ago</span>

                      <h3 class="timeline-header"><a href="#">Jay White</a> commented on your post</h3>

                      <div class="timeline-body">
                        Take me to your leader!
                        Switzerland is small and neutral!
                        We are more like Germany, ambitious and misunderstood!
                      </div>
                      <div class="timeline-footer">
                        <a class="btn btn-warning btn-flat btn-xs">View comment</a>
                      </div>
                    </div>
                  </li>
                  <!-- END timeline item -->
                  <!-- timeline time label -->
                  <li class="time-label">
                        <span class="bg-green">
                          3 Jan. 2014
                        </span>
                  </li>
                  <!-- /.timeline-label -->
                  <!-- timeline item -->
                  <li>
                    <i class="fa fa-camera bg-purple"></i>

                    <div class="timeline-item">
                      <span class="time"><i class="fa fa-clock-o"></i> 2 days ago</span>

                      <h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos</h3>

                      <div class="timeline-body">
                        <img src="http://placehold.it/150x100" alt="..." class="margin">
                        <img src="http://placehold.it/150x100" alt="..." class="margin">
                        <img src="http://placehold.it/150x100" alt="..." class="margin">
                        <img src="http://placehold.it/150x100" alt="..." class="margin">
                      </div>
                    </div>
                  </li>
                  <!-- END timeline item -->
                  <li>
                    <i class="fa fa-clock-o bg-gray"></i>
                  </li>
                </ul>
              </div>
              <!-- /.tab-pane -->

              <div class="tab-pane" id="settings">
                <form class="form-horizontal">
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Name</label>

                    <div class="col-sm-10">
                      <input type="email" class="form-control" id="inputName" placeholder="Name">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                    <div class="col-sm-10">
                      <input type="email" class="form-control" id="inputEmail" placeholder="Email">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Name</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="inputName" placeholder="Name">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputExperience" class="col-sm-2 control-label">Experience</label>

                    <div class="col-sm-10">
                      <textarea class="form-control" id="inputExperience" placeholder="Experience"></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputSkills" class="col-sm-2 control-label">Skills</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="inputSkills" placeholder="Skills">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-danger">Submit</button>
                    </div>
                  </div>
                </form>
              </div>
              <!-- /.tab-pane -->
				
				
				
				
            </div>
            <!-- /.tab-content -->
			  
			  
			  
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->

</div>


@stop
