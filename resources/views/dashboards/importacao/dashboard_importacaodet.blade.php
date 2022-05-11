@extends('layout.principal')
@section('conteudo')

	@php
  if(isset($query_2[0]->tipo)){
		
$tipo = $query_2[0]->tipo;
$pedido = $query_2[0]->pedido;
}
else {
$tipo = 0;
$pedido = 0;
}
  @endphp
							
<div class="row"> 
	
		

		<div class="col-md-12">	
		<div class="box box-title">
		{{$tipo}} - {{$pedido}}
		</div>

		<div class="box box-body">
		
		<a  class="btn btn-default btn-flat pull-right"href="" class="pull-center" data-toggle="modal" 
				data-target="#modalcadastratitulo">Cadastrar parcela</a>
			
		<table class="table table-bordered">
			
			
		 <tr>	

	 		<td colspan="12"></td>
		
				</tr>
		  			
					<tr>
						<td ></td>	
						<td colspan="1" align="center">ref_go</td>	
					<td colspan="1" align="center">Secundario</td>
						
				
					<td colspan="1" align="center">desc1</td>
					
					<td colspan="1" align="center">fornecedor</td>
					<td colspan="1" align="center">ult_prox status</td>
					<td colspan="1" align="center">Tipo_item</td>
					<td colspan="1" align="center">Grifes </td>
					<td colspan="1" align="center">Colecoes</td>
					<td colspan="1" align="center">qtde pecas</td>
					<td colspan="1" align="center">atende</td>
				
			</tr>
			  
			  
			@foreach ($query_2 as $query2)
			  
				<tr>
					<td id="foto" align="center" style="min-height:60px;">
               
                <a href="" class="zoom" data-value="{{$query2->item}}"><img src="https://portal.goeyewear.com.br/teste999.php?referencia={{$query2->item}}" style="max-height: 60px;" class="img-responsive"></a>
                
              </td>
					
					<td align="left">{{$query2->tipo.' '.$query2->ref_go}}</td>	
					<td align="left"><a href="/painel/{{$query2->agrup}}/{{$query2->modelo}}">{{$query2->secundario}}</a></td>
					
					
					<td align="center">{{$query2->ref}}</td>
					
					<td align="left">{{$query2->fornecedor}}</td>
					<td align="center">{{$query2->ult_prox}}</td>
					<td align="center">{{$query2->tipoitem}}</td>
					<td align="center">{{$query2->codgrife}}</td>
					<td align="center">{{$query2->colmod}}</td>
					<td align="center">{{number_format($query2->qtde)}}</td>	
					<td align="center">{{number_format($query2->atende,0)}}</td>
				
					
				</tr>
			@endforeach 
			
			</table>
			
		</div>
	</div>	
</div>
</h6>			
	
</form>

<form action="/dsimportdet/cadastrapagamento" id="frmcadastratitulo" class="form-horizontal" method="post">
    @csrf 
<div class="modal fade" id="modalcadastratitulo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
   <input type="hidden" name="id_pedido" id="id_pedido" value="{{$pedido}}">
   <input type="hidden" name="tipo_pedido" id="tipo_pedido" value="{{$tipo}}">

  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Cadastro Pagamento</h4>
      </div>
      <div class="modal-body">

	  <div class="form-group">
	  
       <label class="col-md-3 control-label">Tipo de pagamento</label>   
	      <div class="col-md-8">
        <select  name="tipo_pagamento" class="form-control" required>
		
         <option value="EMBARQUE" > EMBARQUE </option>
		 <option value="PARCELA" > PARCELA </option>
		 
      	 </select>
          </div>        
          </div>
        
          <div class="form-group">
            <label class="col-md-3 control-label">Data Emissão</label>
            <div class="col-md-4">
              <small>Data Emissão</small>
              <input type="date" name="dt_emissao"  id="dt_emissao" class="form-control" required >
            </div>     
          
            <div class="col-md-4">
              <small>Data Vencimento</small>
              <input type="date" name="dt_vencimento" id="dt_vencimento"   class="form-control" required>
            </div>        
          </div>

          <div class="form-group">
            <label class="col-md-3 control-label">Moeda</label>
            <div class="col-md-5">
              <select name="moeda" id="moeda" class="form-control" required>
                <option value=""> @lang('padrao.selecione') </option>
                <option value="USD"> USD </option>
                <option value="EUR"> EUR </option>
                <option value="BRL"> BRL </option>
              </select>
            </div>        
          </div>

          <div class="form-group">
            <label class="col-md-3 control-label">Valor</label>
            <div class="col-md-5">
              <input name="valor" type="decimal"  required>
            </div>        
          </div>
      <div class="form-group">
            <label class="col-md-3 control-label">Criar parcelas</label>
            <div class="col-md-5">
              <select name="criar_parcela" id="criar_parcela" class="form-control" required>
                <option value="">Selecione </option>
                <option value="parcela_unica"> Parcela única </option>
                <option value="multiplas"> Multiplas parcelas </option>
                
              </select>
            </div>        
          </div>
		  
		  <div class="form-group">
            <label class="col-md-3 control-label">Observação</label>
            <div class="col-md-8">
                <textarea name="obs" class="form-control"></textarea>
            </div>        
          </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">@lang('padrao.cancelar')</button>
        <button type="submit" class="btn btn-flat btn-primary">@lang('padrao.salvar') </button>
      </div>
    </div>
  </div>
</div>
</form>

@stop