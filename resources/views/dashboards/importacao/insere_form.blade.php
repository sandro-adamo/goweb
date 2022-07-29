@extends('layout.principal')

@php

$pedido = $_GET["pedido"];
$tipo = $_GET["tipo"];


$cli  = 100205;
$numero = 5883;



@endphp

@section('title')
<i class="fa fa-file-text-o"></i> Detalhe do pedido</i> 
@append 

@section('conteudo')
	@csrf
	@php

	
	$result = \DB::select("select id from compras_infos where id_pedido = $numero");
		
			if(count($result)==1){
			$id_info = 	$result[0]->id;
			$acao = "update";
			
			
			}else{
			$acao = "insert";
			$id_info = 	0;
	
			}
	
	

	$query_1 = \DB::select("
	

	

select * from (
	select ped.pedido num_pedido, ped.tipo tipo_pedido, ped.dt_pedido, ped.ref_go invoice, concat(ped.ult_status,' ', ped.prox_status) ult_prox_ped,
    ped.moeda moeda_pedido, sum(ped.qtde_sol) qtde_ped, sum(ped.vlr_total) vlr_pedido,
    Ref_Nac_01 num_di, ref_nac_02 data_di,
	nf.prenota, nf.dt_emissao dt_nf, concat(nf.ult_status, ' ', nf.prox_status) status_nf, sum(nf.qtde) qtde_nf, sum(nf.total) vlr_nf, sum(nf.icms) icms_nf, sum(nf.ipi) ipi_nf,
	'pecas - groupconcat' tipo_carga, 'group concat' grifes
    
	from importacoes_pedidos ped 
	left join importacoes_notas nf on nf.ped_original = ped.pedido and nf.tipo_original = ped.tipo and nf.linha_original = ped.linha
	
	where ped.pedido = '$pedido' and ped.tipo = '$tipo'

	group by ped.pedido, ped.tipo , ped.dt_pedido, ped.ref_go ,  concat(ped.ult_status,' ', ped.prox_status),
    Ref_Nac_01, ref_nac_02,
	nf.prenota, nf.dt_emissao ,  concat(nf.ult_status, ' ', nf.prox_status) , ped.moeda 
) as final


	left join (select * from compras_infos ) as ci
    on ci.id_pedido = final.num_pedido and ci.tipo_pedido = final.tipo_pedido




		
	");

	
	@endphp
		
		
<div class="row" >	
  <div class="col-md-10" >

    <div class="box box-widget">
      <div class="box-header with-border">
		 <h3 class="box-title"><i class="fa fa-file-text-o"></i> {{$tipo}} - {{$pedido}} </i> </h3>
		  <h6>
         <table class="table table-bordered table-condensed">
				
					
			
			<tr class="card-header bg-info text-center">
          
              <td><b>Dt Emissao</b></td>
			  <td><b>Tipo produto</b></td>
			  <td><b>Ult/Prox Status</b></td>
			  <td><b>Desc Status</b></td>
			 <td><b>Obs pedido</b></td>
            
            </tr>
				
            <tr class="text-center">
				
				<td>{{$query_1[0]->dt_pedido}}</td>
				<td>{{$query_1[0]->num_pedido}}</td>
				<td>{{$query_1[0]->ult_prox_ped}}</td>
				<td>{{$query_1[0]->num_pedido}}</td>
				<td></td>
            </tr>	

				
				
	 
		<form action="/import_form/grava" method="post" class="form-horizontal">
			
			<input type="hidden" id="id_info" name="id_info" size="50" value={{$id_info}}>
			<input type="hidden" id="id_info" name="id_info" size="50" value={{$acao}} >
			
			
	 	@csrf
	 
		</table></h6>
      </div>
      

 
	 
	 <div class="box-body"> 	
        <div class="box box-danger">
          <h3 class="box-title">Documentacao embarque</h3>
		  <h6>
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
              <td><input type="text" id="id_nome" name="id_nome" size="30" value={{$query_1[0]->invoice}} ></td>
			  <td><input type="text" id="id_nome" name="id_nome" size="10" value={{$query_1[0]->dt_invoice}} ></td>
			  <td><input type="text" id="id_nome" name="id_nome" size="5" value={{$query_1[0]->cubagem_m3}} ></td>
			  <td><input type="text" id="id_nome" name="id_nome" size="5" value={{$query_1[0]->volumes}} ></td>
			  <td><input type="text" id="id_nome" name="id_nome" size="10" value={{$query_1[0]->peso_bruto}} ></td>
			  <td><input type="text" id="id_nome" name="id_nome" size="35" value={{$query_1[0]->obs_invoice}} ></td>
	
            </tr>
			  								
			  
		<tr> <td colspan="6"></td></tr>
		
			  
			 <tr  class="card-header bg-info text-center">      
              
              <td><b>Doc Agrup	</b></td>
              <td><b>Tipo Carga</b></td>
              <td><b>Ref processo</b></td>
              <td><b>Numero OK</b></td>
              <td><b>Numero SX</b></td>
            </tr>		  

            <tr class="text-center">
              <td><input type="text" id="doc_agrup" name="doc_agrup" size="20" value={{$query_1[0]->doc_agrup}} ></td>
			  <td><input type="text" id="id_nome" name="id_nome" size="20" value={{$query_1[0]->tipo_carga}} ></td>
              <td size="20">{{$query_1[0]->ref_processo}}</td>
			  <td size="20">{{$query_1[0]->num_pedido}}</td>
			  <td size="20">{{$query_1[0]->num_pedido}}</td>
            </tr>
		  
		 	  
          </table> 
			</h6>
          </div>
		 
		 
		<div class="box box-warning">
        <h3 class="box-title">Transito</h3>
		<h6>
		 <table class="table table-bordered table-condensed">
            <tr  class="card-header bg-info text-center">
              <td><b>Dt solicitacao LI</b></td>
              <td><b>Dt deferimento LI</b></td>
			  <td><b>Cod agente cargas</b></td>
              <td><b>Agente cargas</b></td>
              <td><b>Dt aut Embarque</b></td>
			 <td><b>Obs Transito</b></td>
            </tr>
									
										 		 							
			  
            <tr class="text-center">
              <td><input type="text" id="id_nome" name="id_nome" size="10" value={{$query_1[0]->dt_sol_li}} ></td>
			  <td><input type="text" id="id_nome" name="id_nome" size="10" value={{$query_1[0]->dt_def_li}} ></td>
			  
			  <td>@php 
              $fornecedor = \DB::select("select id, fantasia from addressbook where nome like '%junior%'");
              @endphp
              <select class="form-control" name="genero" >			 
              <option value="">{{$query_1[0]->an8_agente}}</option>
                @foreach ($fornecedor as $forn)
              <option value="{{$forn->id}}">{{$forn->id}} - {{$forn->fantasia}}</option>
                @endforeach
              </select>
			  </td>	
				
				
			<!--	<td><input type="text" id="id_nome" name="id_nome" size="10" value={{$query_1[0]->an8_agente}} ></td>-->
				
				
			  <td></td>
			  <td><input type="text" id="id_nome" name="id_nome" size="10" value={{$query_1[0]->dt_aut_embarque}} ></td>
			  <td></td>
	
            </tr>
			  								
			  
		<tr> <td colspan="6"></td></tr>
		
			  
			 <tr  class="card-header bg-info text-center">      
              
              <td><b>Num HAWB</b></td>
              <td><b>Dt Embarque</b></td>
              <td><b>Dt Previsao Chegada</b></td>
              <td><b>Dt Chegada</b></td>
              <td><b>Obs Chegada</b></td>
			  <td><b>Dt remocao</b></td>
            </tr>		  
			 
            <tr class="text-center">
			<td><input type="text" id="id_nome" name="id_nome" size="20" value={{$query_1[0]->num_awb}} ></td>
			<td><input type="text" id="id_nome" name="id_nome" size="20" value={{$query_1[0]->dt_emb_int}} ></td>
			<td><input type="text" id="id_nome" name="id_nome" size="20" value={{$query_1[0]->dt_prev_chegada}} ></td>
			<td><input type="text" id="id_nome" name="id_nome" size="20" value={{$query_1[0]->dt_chegada}} ></td>
			<td><input type="text" id="id_nome" name="id_nome" size="20" value={{$query_1[0]->obs_chegada}} ></td>
			<td><input type="text" id="id_nome" name="id_nome" size="20" value={{$query_1[0]->dt_remocao}} ></td>
            </tr>
		  
		  </h6>	  
 
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
              <td><input type="text" id="id_nome" name="id_nome" size="20" value={{$query_1[0]->dt_registro}} ></td>
			  <td><input type="text" id="id_nome" name="id_nome" size="20" value={{$query_1[0]->protocolo_di}} ></td>
				<td>{{$query_1[0]->data_di}}</td>
            </tr>
			 
			 
		<tr> <td colspan="6"></td></tr>
			 
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
			 
			 
	<tr> <td colspan="6"></td></tr>
			 
			 <tr  class="card-header bg-info text-center">
				  
              <td><b>Dt prev Transp Nac</b></td>
              <td><b>Transporte Nac</b></td>
              <td><b>Dt Carregamento</b></td>
              <td><b>Dt entrega Fabrica</b></td>
             
             </tr>
			 
            <tr class="text-center">
				<td><input type="text" id="id_nome" name="id_nome" size="20" value={{$query_1[0]->dt_prev_embnac}} ></td>
			  <td>@php 
              $fornecedor = \DB::select("select id, fantasia from addressbook where nome like '%junior%'");
              @endphp
              <select class="form-control" name="genero" >			 
              <option value="">{{$query_1[0]->an8_agente}}</option>
                @foreach ($fornecedor as $forn)
              <option value="{{$forn->id}}">{{$forn->id}} - {{$forn->fantasia}}</option>
                @endforeach
              </select>
			  </td>	
				
				<td><input type="text" id="id_nome" name="id_nome" size="20" value={{$query_1[0]->dt_emb_nac}} ></td>
				<td><input type="text" id="id_nome" name="id_nome" size="20" value={{$query_1[0]->dt_recebimento}} ></td>
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
		  
		   <div class="box box-warning">
          <h3 class="box-title">ol / gl</h3>
			   <h6>
		 <table class="table table-bordered table-condensed">
			  <tr  class="card-header bg-info text-center">
              
              <td><b>tabea</b></td>
              
            </tr>
            <tr class="text-center">
              
              <td><textarea id="lentedireita"  name="lentedireita" rows="2" cols="10" class="form-control"></textarea></td>
                        </tr>
			  </h6>
          </table>
			 
	
    
	 
	 
	 
	 
	 
	 
   
<button type="submit" class="btn btn-primary"><i class="fa fa-refresh"></i> Enviar</button>
      
</form>


</div>

	  
	  
	  
</h6>

@stop
