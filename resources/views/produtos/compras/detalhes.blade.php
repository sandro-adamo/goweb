		@extends('layout.principal')

@section('title')
<h3 style="margin-top: 0;">@lang('padrao.pedido_compra') #{{$capa[0]->id}} - P.O. Date {{$capa[0]->dt_emissao}} </h3>
@append 

@section('conteudo')

    @php
        $qtde = 0;
        $qtde_conf = 0;
	$qtde_entreguett = 0;

    @endphp

    @foreach ($itens as $item1)

        @php
            if ($item1->status <> 'CANCELADO') {

                $qtde += $item1->qtde;
                $qtde_conf += $item1->qtde_conf;
				$qtde_entreguett += $item1->qtd_entregue;

            }
        @endphp
	@endforeach

@if (Session::has('alert-success'))

    <div class="callout callout-success">{{Session::get('alert-success')}}</div>

@endif


@if (Session::has('alert-warning'))

    <div class="callout callout-warning"><h3><i class="fa fa-warning"></i> Erro</h3> {!!Session::get('alert-warning')!!}</div>

@endif

<div class="row">
    <div class="col-md-8">
        <div class="box box-widget">
            <div class="box-header with-border">
				<table class="table table-bordered table-condensed">
               <td> <h3 class="box-title">@lang('padrao.fornecedor')</h3>
            </div>
            <div class="box-body">
                <b style="font-size: 18px">{{$capa[0]->fornecedor}}</b> <a href="" data-toggle="modal" data-target="#modalAlteraFornecedor"><i class="fa fa-edit"></i></a><br>
                <address>
                    {{$capa[0]->endereco}} {{$capa[0]->numero}}<br>
                    {{$capa[0]->municipio}} - {{$capa[0]->uf}} {{$capa[0]->pais}}<br>
                    {{$capa[0]->email1}}<br>
                    {{$capa[0]->ddd1}} {{$capa[0]->tel1}}
                </address>
				<b><h3 class="box-title">@lang('padrao.obs_pedido'):</h3></b><br>
				<i style="font-size: 18px">{{$capa[0]->obs}}</i><br>
				
				<br>
				<h3 class="box-title"><b>Invoices</b></h3><br>
				@foreach ($invoices as $invoice)
				<a href="/compras/invoice/detalhes/{{$invoice->invoice}}" ><i style="font-size: 18px">{{$invoice->invoice}}</i><br></a>
				
				@endforeach
				
				
				<button type="button" class="btn btn-default pull-right btn-flat " data-target="#modalArquivo" data-toggle="modal"><i class="fa fa-archive"></i> Arquivos</button>
				
				
</td>
			<td>  
       <h3 class="box-title">Pagamento</h3>

       <div class="box"><b>Condi????o:</b>{{$capa[0]->condicao_pagamento}} </div>
				
           	
				
				
			<table class="table table-bordered table-condensed">
       <tr>
                <td>@lang('padrao.tipopagamento')</td>
                <td align="center">{{$capa[0]->pagamento}}</td>
            </tr>    

      <tr>
                <td>@lang('padrao.tipodeenvio')</td>
                <td align="center">{{$capa[0]->transporte}}</td>
            </tr> 
			
			<tr>
                <td><b>Prazo pagamento</b> <a href="" class="pull-right" data-toggle="modal" 
				data-target="#modalAlteraPagamento"><i class="fa fa-edit"></i></a></td>
                <td align="center"> @if($adiantamento) {{$adiantamento[0]->tipo}} @endif</td>
            </tr> 
				
			<tr>
                <td>Adiantamento Previsto</td>
                <td align="center">@if($adiantamento) {{$adiantamento[0]-> vencimento}} @endif</td>
            </tr> 
      <tr>
                <td>Valor Adiantamento</td>
                <td align="center">@if($adiantamento) 
                @if($adiantamento[0]->valor<>$adiantamento[0]->valor_parcelas)
                <font color="red">{{$adiantamento[0]->moeda.$adiantamento[0]->valor}}</font>
                @else
                {{$adiantamento[0]->moeda.$adiantamento[0]->valor}}
                @endif
                
                
                 @endif</td>
            </tr> 

        <tr>
                <td colspan="2"><b>Parcelas Adiantamento</b> <a href="" class="pull-center" data-toggle="modal" 
				data-target="#modalparcelasadiantamento"><i class="fa fa-edit"></i></a></td>
                
            </tr> 
			
			<tr>
                <td>Data m??nima  parcelas </td>
                <td align="center">@if($adiantamento) {{$adiantamento[0]->dt_vencimento_parcela}} @endif</td>
            </tr> 
       <tr>
                <td>Valor Adiantamento</td>
                <td align="center">@if($adiantamento) {{$adiantamento[0]->moeda.' '.$adiantamento[0]->valor_parcelas}} @endif</td>
            </tr> 

				
		
      <tr>

      
      <tr>
                <td>Status Adiantamento</td>
                <td align="center">pago/atrasado/nao env</td>
            </tr> 
				
			</table>
				
				
		</td>
		</table>
			
		
			
            </div>
        </div>                        
    </div>
    <div class="col-md-4">
        <div class="box box-body box-widget">    
            
			</br>
        <table class="table table-condensed table-bordered">
        <tr class="bg-primary">
            <td colspan="3" align="center"><small><b>@lang('padrao.resumo')</b></small></td>
        </tr>    
			<tr>
            <td align="center">@lang('padrao.agrup')</td>
            <td align="center">@lang('padrao.qtdsku')</td>
			<td align="center">@lang('padrao.qtdtotal')</td>
        </tr>  

        @foreach ($resumo as $agrup)

        <tr>
            <td align="center">{{$agrup->agrup}}</td>
            <td align="center">{{$agrup->qtde}}</td>
			<td align="center">{{number_format($agrup->totalpedido)}}</td>
        </tr>                

        @endforeach

        	<tr>
                <td><b>Valor Pedido</b></td>
                <td align="center"><b>@if($adiantamento) {{$adiantamento[0]->moeda.$capa[0]->valor_total}} @endif</b></td>
            </tr> 
        </table> 

        @if ($capa[0]->status == 'ABERTO')
            <form action="/compras/pedido/envia" method="post" id="frmLiberaPedido">
            <input type="hidden" name="id_pedido" value="{{$capa[0]->id}}">    
            @csrf
            </form>
        @endif

                <button type="submit" class="btn btn-default btn-flat pull-left " id="btnEnviaPedido"><i class="fa fa-send"></i> @lang('padrao.enviarpedido')</button>
        <a href="/compras/{{$capa[0]->id}}/imprimir" class="btn btn-default pull-right btn-flat " target="_blank" ><i class="fa fa-print"></i> @lang('padrao.imprimir')</a>

       
            <a href="/compras/pedido/{{$capa[0]->id}}/exporta" class="btn btn-default btn-flat "><i class="fa fa-file"></i> @lang('padrao.exportarpedido')</a>
     


        </div>
    </div>
</div>    
 

<form action="/compras/{{$capa[0]->id}}" method="post" > 
@csrf
<div class="box box-body">    
<div class="row">
    <div class="col-md-2">
        <select class="form-control" name="acao" id="acao">
            <option value="">@lang('padrao.acoesmassa')</option>
            @if ($qtde_conf < '0' or $qtde_conf =='')
            <option value="ABERTO" @if (isset($_GET["status"]) && $_GET["status"] == 'ABERTO') selected="" @endif>@lang('padrao.aberto')</option>
            <option value="ENVIADO" @if (isset($_GET["status"]) && $_GET["status"] == 'ENVIADO') selected="" @endif>@lang('padrao.enviado')</option>
            <option value="CONFIRMADO" @if (isset($_GET["status"]) && $_GET["status"] == 'CONFIRMADO') selected="" @endif>@lang('padrao.confirmado')</option>
			@endif
            <option value="DISTRIBUIDO" @if (isset($_GET["status"]) && $_GET["status"] == 'DISTRIBUIDO') selected="" @endif>@lang('padrao.distribuido')</option>
            <option value="PRODUCAO" @if (isset($_GET["status"]) && $_GET["status"] == 'PRODUCAO') selected="" @endif>@lang('padrao.producao')</option>
            <option value="INVOICE" @if (isset($_GET["status"]) && $_GET["status"] == 'INVOICE') selected="" @endif>@lang('padrao.invoice')</option>
			@if ($qtde_entreguett<= '0' or $qtde_entreguett == '')
            <option value="CANCELADO" @if (isset($_GET["status"]) && $_GET["status"] == 'CANCELADO') selected="" @endif>@lang('padrao.cancelado')</option>
			@endif
            <option value="CONCLUIDO" @if (isset($_GET["status"]) && $_GET["status"] == 'CONCLUIDO') selected="" @endif>@lang('padrao.concluido')</option>
	<option value="FINALIZADO SISTEMA" @if (isset($_GET["status"]) && $_GET["status"] == 'FINALIZADO SISTEMA') selected="" @endif>FINALIZADO SISTEMA</option>
	<option value="ENVIADO" @if (isset($_GET["status"]) && $_GET["status"] == 'ENVIADO') selected="" @endif>@lang('padrao.enviado')</option>
	<option value="AGUARDANDO DOCUMENTACAO" @if (isset($_GET["status"]) && $_GET["status"] == 'AGUARDANDO DOCUMENTACAO') selected="" @endif>AGUARDANDO DOCUMENTA????O</option>
	
          </select>          
        </select>
    </div>
    <div class="col-md-1">
        <button type="submit" id="aplicar" class="btn btn-default">@lang('padrao.aplicar')</button>
    </div>

    <div class="col-md-9">
		<div class="col-md-3">
        <button type="button" class="btn btn-default btn-block pull-right" id="btnImporta"><i class="fa fa-upload"></i> @lang('padrao.importarpedido')</button>
		</div>
		@if ($capa[0]->tipo=='PRE-PEDIDO')
		<div class="col-md-3">
		<a href="/compras/pedido/modelo/novo/{{$capa[0]->id}}" target="_blank" class="btn btn-default btn-block pull-right" ><i class="fa fa-plus"></i> New model</a>
		</div>
		
		<div class="col-md-3">
		<button type="button" class="btn btn-default btn-block pull-right" data-target="#modalImportaCores" data-toggle="modal"><i class="fa fa-upload"></i> Importar cores</button>
		</div>
			<div class="col-md-3">
		<a href="/compras/pedido/modelo/transformar_pedido/{{$capa[0]->id}}" target="_blank" class="btn btn-success btn-block pull-right" ><i class="fa fa-exchange"></i> Transformar em pedido</a>
		</div>
		
		<div class="col-md-3">
		<button type="button" class="btn btn-default btn-block pull-right" data-target="#modalCriarModelos" data-toggle="modal"><i class="fa fa-upload"></i> Criar modelos</button>
		</div>
		 
		@else
		<div class="col-md-3">
		<a href="" class="btn btn-default pull-right btn-flat " data-toggle="modal" data-target="#modalNovoItem"><i class="fa fa-plus"></i> @lang('padrao.novo_item')</a>
		</div>
<!--
			<div class="col-md-3">
		 <button type="button" class="btn btn-default pull-right btn-flat " id="btnImporta"><i class="fa fa-upload"></i> @lang('padrao.importarpedido')</button>
		</div>
-->
		
		<div class="col-md-3">
        <button align="left" type="button" class="btn btn-default pull-right btn-flat " data-target="#modalAtualizaPedido" data-toggle="modal"><i class="fa fa-refresh"></i> @lang('padrao.importarconf')</button>
		</div>
			
	<div class="col-md-3">
		
        <a class="btn btn pull-right btn-flat " href="/compras/entregas/downalod_edita/{{$capa[0]->id}}"><i class="fa fa-download"></i> Download deliveries</a>
		</div>
		<div class="col-md-3">
		<button type="button" class="btn btn-default pull-right btn-flat " data-target="#modalUploadEditaEntrega" data-toggle="modal"><i class="fa fa-refresh"></i> Edit deliveries</button>
		</div>
		@endif

		
       
    </div>

	
<br>
</div>
<div class="table-responsive">
<table class="table table-condensed table-bordered">
<thead>
<tr>
    <th style="text-align: center;"><input type="checkbox" id="seleAll"></th> 
    <th>Foto</th>
	<th>Id</th>
    <th>@lang('padrao.status')</th>
    <th>@lang('padrao.agrupamento') </th> 
    <th>@lang('padrao.modelo')</th> 
    <th>@lang('padrao.item')</th> 
    <th>@lang('padrao.qtde')</th> 
	<th>@lang('padrao.obs')</th> 
	<th>@lang('padrao.data_entrega')</th> 
    <th>@lang('padrao.confirmado')</th> 
	<th>Distribuido</th> 
	<th>@lang('padrao.entregue')</th> 
    <th>@lang('padrao.colecao')</th>  
    <th>@lang('padrao.classificacao')</th>  
    <th></th>  
</tr>
</thead>


<tbody>


    @foreach ($itens as $item)

       
		@php
		if ($item->dt_alterada>0){
		$cor = 'DarkOrange';
		$cor1 = 'yellow';}
		

		else{
		$cor = 'black';
		$cor1 = 'blue';}

	
	@endphp

        @if ($item->status == 'CANCELADO')
            <tr id="tabItensPedido" style="text-decoration: line-through;color:red">
            <td align="center"><input type="checkbox" name="itens[]" value="{{$item->id}}" class="seleItem" ></td> 
       @elseif ($item->status == 'FINALIZADO SISTEMA')
             <tr id="tabItensPedido" style="text-decoration: line-through;color:blue">
            <td align="center"><input type="checkbox" name="itens[]" value="{{$item->id}}" class="seleItem" ></td>  
       	@else
            <tr id="tabItensPedido">
             <td align="center"><input type="checkbox" name="itens[]" value="{{$item->id}}" class="seleItem"></td> 
       @endif
             <td id="foto" align="center" style="min-height:60px;">
               
                <a href="" class="zoom" data-value="{{$item->item}}"><img src="https://portal.goeyewear.com.br/teste999.php?referencia={{$item->item}}" style="max-height: 60px;" class="img-responsive"></a>
                
              </td>
			<td align="center">{{$item->id}}</td>
            <td align="center">{{$item->status}}</td>
            <td align="center">{{$item->agrupamento}} </td> 
            <td align="center">
			@if ($capa[0]->tipo=='PRE-PEDIDO')	
			<a href="/compras/pedido/modelo/{{$item->id_modelo}}">{{$item->modelo}} </a>
			@else
			<a href="/painel/{{$item->agrupamento}}/{{$item->modelo}}">{{$item->modelo}} </a>
			@endif
				
			</td>
            <td align="center">{{$item->item}}</td> 
            <td align="center">{{$item->qtde}}</td> 
				<td align="center">{{$item->note}}</td> 
				<td align="center"><font color="{{$cor}}">
					@if($item->dt_confirmada)
					{{$item->dt_confirmada}}</font></td> 
				@else
				{{$item->dt_prevista}}</font></td> 
				@endif
					
            <td align="center">{{$item->qtde_conf}}</td>
				<td align="center">{{$item->qtde_entrega}}</td>
				<td align="center">{{$item->qtd_entregue}}</td> 
            <td align="center">{{$item->colmod}}</td>  
            <td align="center">{{$item->clasmod}}</td>  

            @if ($item->status == 'ABERTO')
                <td align="center"><a href="" class="btnPlanejamentoItem" data-value="{{$item->id}}"><i class="fa fa-edit "></i></a></td>  
            @else
                @if ($item->status != 'CANCELADO') 
				
                    <td align="center"><a href="" class="btnPlanejamentoItem" data-value="{{$item->id}}"><i class="fa fa-calendar text-{{$cor1}} "></i></a></td>  
				
                    <td align="center"></td>  
                @endif
            @endif 
        </tr>

    @endforeach 
<?php
?>  
</tbody>
<tfoot>
    <tr class="bg-primary"> 
        <td align="center" colspan="6"><B>TOTAL</B></td>
        <td align="center"><b>{{$qtde}}</b></td>
        <td align="center" colspan="2"></td>
        <td align="center"><b>{{$qtde_conf}}</b></td>
		<td align="center"><b>{{$qtde_entreguett}}</b></td>
        <td colspan="6"></td>
    </tr>

</tfoot>

</table>
</div>
</div>
</form>


<form action="/compras/{{$capa[0]->id}}/edita" id="frmAlteraFornecedor" class="form-horizontal" method="post">
    @csrf 
<div class="modal fade" id="modalAlteraFornecedor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">@lang('padrao.altera_fornecedor')</h4>
      </div>
      <div class="modal-body">


          <div class="form-group">
            <label class="col-md-3 control-label">@lang('padrao.data')</label>
            <div class="col-md-4">
              <small>@lang('padrao.emissao')</small>
              <input type="date" name="dt_emissao" value="{{$capa[0]->dt_emissao}}" disabled id="dt_emissao" class="form-control">
            </div>         
            <div class="col-md-4">
              <small>@lang('padrao.entrega')</small>
              <input type="date" name="dt_entrega" id="dt_entrega"  value="{{$capa[0]->dt_entrega}}" class="form-control">
            </div>        
          </div>


          <div class="form-group">
            <label class="col-md-3 control-label">@lang('padrao.fornecedor')</label>
            <div class="col-md-8">
              <select name="id_fornecedor" id="id_fornecedor" class="form-control">
                <option value=""> @lang('padrao.selecione') </option>

                @php                  
                    $fornecedores = \DB::select("select * from caracteristicas where campo = 'Fornecedor'");
                @endphp                   

                @foreach ($fornecedores as $fornecedor) 
                    @if ($capa[0]->id_fornecedor == $fornecedor->codigo)
                        <option value="{{$fornecedor->codigo}}" selected=""> {{$fornecedor->valor}} </option>
                    @else
                        <option value="{{$fornecedor->codigo}}"> {{$fornecedor->valor}} </option>
                    @endif
                @endforeach

              </select>
            </div>        
          </div>


          <div class="form-group">
            <label class="col-md-3 control-label">@lang('padrao.transporte')</label>
            <div class="col-md-5">
              <select name="transporte" id="transporte" class="form-control">
                <option value=""> @lang('padrao.selecione') </option>
                <option @if ($capa[0]->transporte == 'a??reo') selected="" @endif> A??reo </option>
                <option @if ($capa[0]->transporte == 'mar??timo') selected="" @endif> Mar??timo </option>
				        <option @if ($capa[0]->transporte == 'cif') selected="" @endif> CIF </option>
                <option @if ($capa[0]->transporte == 'express') selected="" @endif> EXPRESS (FEDEX...) </option>
                <option @if ($capa[0]->transporte == 'terrestre') selected="" @endif> Terrestre </option>
              </select>
            </div>        
          </div>

          <div class="form-group">
            <label class="col-md-3 control-label">@lang('padrao.pagamento')</label>
            <div class="col-md-5">
              <select name="pagamento" id="pagamento" class="form-control">
                <option value=""> @lang('padrao.selecione') </option>
                <option @if ($capa[0]->pagamento == 'carta de cr??dito') selected="" @endif> Carta de cr??dito </option>
                <option @if ($capa[0]->pagamento == 'transfer??ncia banc??ria') selected="" @endif> Transfer??ncia Banc??ria </option>
                <option @if ($capa[0]->pagamento == 'boleto') selected="" @endif> Boleto </option>
                <option @if ($capa[0]->pagamento == 'risco sacado') selected="" @endif> Risco sacado </option>
              </select>
            </div>        
          </div>
		  
		  
		  <div class="form-group">
            <label class="col-md-3 control-label">@lang('padrao.obs')</label>
            <div class="col-md-8">
                <textarea name="obs" class="form-control">{{$capa[0]->obs}}</textarea>
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



<!-- novo modal para pagamento -->

<form action="/titulos_form/grava" id="frmAlteraPagamento" class="form-horizontal" method="post">
    @csrf 
<div class="modal fade" id="modalAlteraPagamento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
   <input type="hidden" name="id_compra" id="id_compra" value="{{$capa[0]->id}}">

  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Detalhes Pagamento</h4>
      </div>
      <div class="modal-body">


          <div class="form-group">
            <label class="col-md-3 control-label">Data Emiss??o</label>
            <div class="col-md-4">
              <small>@lang('padrao.emissao')</small>
              <input type="date" name="dt_emissao"  id="dt_emissao" class="form-control" required >
            </div>     
          
            <div class="col-md-4">
              <small>Data Vencimento</small>
              <input type="date" name="dt_vencimento" id="dt_vencimento"   class="form-control" required>
            </div>        
          </div>


          <div class="form-group">
            <label class="col-md-3 control-label">Condi????o de pagamento</label>
            <div class="col-md-8">
              <select  name="condicao_pagamento" class="form-control" required>
               @php                  
                    $prazo_pagamento = \DB::select("select id, descricao from compras_condicoes");
                @endphp                   

                @foreach ($prazo_pagamento as $prazo) 
                   
                        <option value="{{$prazo->id}}" selected=""> {{$prazo->descricao}} </option>
                   
                @endforeach

              </select>
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
            <label class="col-md-3 control-label">Valor total pedido </label>
            <div class="col-md-5">
              <input name="valor_total" type="decimal" required>
            </div>        
          </div>

          
		  
		  
		  <div class="form-group">
            <label class="col-md-3 control-label">Observa????o</label>
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


<!-- novo modal para parcelas do adiantamento -->

<form action="/parcelas_form/grava" id="frmmodalparcelasadiantamento" class="form-horizontal" method="post">
    @csrf 
<div class="modal fade" id="modalparcelasadiantamento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
   <input type="hidden" name="id_titulo"  value="{{$adiantamento[0]->numero}}">
    <input type="hidden" name="moeda"  value="{{$adiantamento[0]->moeda}}">

  <div class="modal-dialog  modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Parcelas adiantamento 
        <br> Condi????o de pagamento: {{$capa[0]->condicao_pagamento}}
        <br> Valor total pedido {{$adiantamento[0]->moeda.$capa[0]->valor_total}}
        <br>Adiantamento total {{ $capa[0]->perc_adiantamento.'%'}} - {{$adiantamento[0]->moeda.$adiantamento[0]->valor}}</h4>
      </div>
      <div class="modal-body">


          <div class="form-group">

          <div class="col-md-12">
        <div class="table-responsive">
          <table class="table table-condensed table-bordered" id="myTable">
          <tr>
          <td>Sel</td>
          <td >Tipo</td>
          <td>N??mero documento</td>
          <td >Data vencimento </td>
          <td >Data pagamento</td>
          <td >Valor</td>
          <td >Obs</td>
          <td >Proforma</td>
          <td >Fornecedor</td>
          

          </tr>

          <tr>
          <td><input name="confirma1" type="checkbox"></td>
          <td> <select  name="tipo1"  class="form-control">
              
              <option value="FR" selected>FR</option>
              <option value="AC">AC</option>
              <option value="MOSTRUARIO">MOSTRU??RIO</option>
              <option value="AGREGADO">AGREGADO</option>
              
             
              </select></td>
          <td><input type="text" name="documento1" value="{{$capa[0]->id}}" class="form-control" ></td>
          <td><input type="date" name="vencimento1" value="{{$adiantamento[0]-> vencimento}}" class="form-control" ></td>
          <td><input type="date" name="pagamento1" class="form-control" ></td>
          <td><input type="decimal" name="valor1" value="{{($capa[0]->valor_total*($capa[0]->perc_adiantamento/100))*0.4}}" class="form-control" ></td>
          <td><input type="text" name="obs1" class="form-control" ></td>
          @php                  
                    $fornecedores1 = \DB::select("select concat(nome,' - ',valor) as valor, caracteristicas.codigo , addressbook.nome nome from caracteristicas left join addressbook on caracteristicas.codigo = addressbook.id where addressbook.nome like '%{{$capa[0]->nome}}%' and campo = 'Fornecedor' 
                    union all
                    select concat(nome,' - ',valor) as valor, caracteristicas.codigo , addressbook.nome nome from caracteristicas left join addressbook on caracteristicas.codigo = addressbook.id where campo = 'Fornecedor'
                    ");
                    $agora = date( 'Hi' );
                    
                @endphp  
          <td><input type="text" name="proforma1" class="form-control" value="{{$capa[0]->nome.$fornecedores1[0]->codigo.date( 'Hi' )}}"></td>
          
          <td><select name="id_fornecedor1" id="id_fornecedor" class="form-control">
                <option value=""> Selecione </option>
                

                @foreach ($fornecedores1 as $fornecedor1) 
                    @if ($capa[0]->id_fornecedor == $fornecedor1->codigo)
                        <option value="{{$fornecedor1->codigo}}" selected=""> {{$fornecedor1->valor}} </option>
                    @else
                        <option value="{{$fornecedor1->codigo}}"> {{$fornecedor1->valor}} </option>
                    @endif
                @endforeach

              </select></td>

              

          </tr>

          <tr>
           <td><input name="confirma2" type="checkbox"></td>
          <td> <select  name="tipo2"  class="form-control">
              
              <option value="AC" selected>AC</option>
              <option value="FR" >FR</option>
              <option value="MOSTRUARIO">MOSTRU??RIO</option>
              <option value="AGREGADO">AGREGADO</option>
              
             
               

              </select></td>
          <td><input type="text" name="documento2" value="{{$capa[0]->id}}" class="form-control" ></td>
          <td><input type="date" name="vencimento2" value="{{$adiantamento[0]-> vencimento}}"  class="form-control" ></td>
          <td><input type="date" name="pagamento2" class="form-control" ></td>
          <td><input type="decimal" name="valor2" value="{{($capa[0]->valor_total*($capa[0]->perc_adiantamento/100))*0.6}}"class="form-control" ></td>
          <td><input type="text" name="obs2" class="form-control" ></td>
            @php                  
                    $fornecedores2 = \DB::select("select concat(nome,' - ',valor) as valor, caracteristicas.codigo , addressbook.nome nome from caracteristicas left join addressbook on caracteristicas.codigo = addressbook.id where addressbook.nome like '%{{$capa[0]->nome}}%' and campo = 'Fornecedor' 
                    union all
                    select concat(nome,' - ',valor) as valor, caracteristicas.codigo , addressbook.nome nome from caracteristicas left join addressbook on caracteristicas.codigo = addressbook.id where campo = 'Fornecedor'
                    ");
                 
                    
                @endphp  
      
           <td><input type="text" name="proforma2" class="form-control" value="{{$capa[0]->nome.$fornecedores2[0]->codigo.date( 'dmi' )}}"></td>
          <td><select name="id_fornecedor2" id="id_fornecedor" class="form-control">
                <option value=""> Selecione </option>
                

                @foreach ($fornecedores2 as $fornecedor2) 
                    @if ($capa[0]->id_fornecedor == $fornecedor2->codigo)
                        <option value="{{$fornecedor2->codigo}}" selected=""> {{$fornecedor2->valor}} </option>
                    @else
                        <option value="{{$fornecedor2->codigo}}"> {{$fornecedor2->valor}} </option>
                    @endif
                @endforeach

              </select></td>

          </tr>

           <tr>
            <td><input name="confirma3" type="checkbox"></td>
          <td> <select  name="tipo3"  class="form-control">
              <option value=""></option>
              <option value="FR">FR</option>
              <option value="AC">AC</option>
              <option value="MOSTRUARIO">MOSTRU??RIO</option>
              <option value="AGREGADO">AGREGADO</option>
             
               

              </select></td>
          <td><input type="text" name="documento3" value="{{$capa[0]->id}}" class="form-control" ></td>
          <td><input type="date" name="vencimento3" class="form-control" ></td>
          <td><input type="date" name="pagamento3" class="form-control" ></td>
          <td><input type="decimal" name="valor3" class="form-control" ></td>
          <td><input type="text" name="obs3" class="form-control" ></td>

           @php                  
                    $fornecedores = \DB::select("select concat(nome,' - ',valor) as valor, caracteristicas.codigo , addressbook.nome nome from caracteristicas left join addressbook on caracteristicas.codigo = addressbook.id where addressbook.id = 102603 and campo = 'Fornecedor' order by concat(nome,' - ',valor)");
                   
                @endphp  
          <td><input type="text" name="proforma3" class="form-control"></td>
          <td><select name="id_fornecedor3" id="id_fornecedor" class="form-control">
                <option value=""> Selecione </option>

                                

                @foreach ($fornecedores as $fornecedor) 
                    @if ($capa[0]->id_fornecedor == $fornecedor->codigo)
                        <option value="{{$fornecedor->codigo}}" selected=""> {{$fornecedor->valor}} </option>
                    @else
                        <option value="{{$fornecedor->codigo}}"> {{$fornecedor->valor}} </option>
                    @endif
                @endforeach

              </select></td>


          </tr>

           <tr>
            <td><input name="confirma4" type="checkbox"></td>
          <td> <select  name="tipo4"  class="form-control">
              <option value=""></option>
              <option value="FR">FR</option>
              <option value="AC">AC</option>
              <option value="MOSTRUARIO">MOSTRU??RIO</option>
              <option value="AGREGADO">AGREGADO</option>
             
               

              </select></td>
          <td><input type="text" name="documento4" value="{{$capa[0]->id}}" class="form-control" ></td>
          <td><input type="date" name="vencimento4" class="form-control" ></td>
          <td><input type="date" name="pagamento4" class="form-control" ></td>
          <td><input type="decimal" name="valor4" class="form-control" ></td>
          <td><input type="text" name="obs4" class="form-control" ></td>
           <td><input type="text" name="proforma4" class="form-control"></td>
          <td><select name="id_fornecedor4" id="id_fornecedor" class="form-control">
                <option value=""> Selecione </option>

                @php                  
                    $fornecedores = \DB::select("select concat(nome,' - ',valor) as valor, caracteristicas.codigo from caracteristicas left join addressbook on caracteristicas.codigo = addressbook.id where campo = 'Fornecedor' order by concat(nome,' - ',valor)");
                @endphp                   

                @foreach ($fornecedores as $fornecedor) 
                    @if ($capa[0]->id_fornecedor == $fornecedor->codigo)
                        <option value="{{$fornecedor->codigo}}" selected=""> {{$fornecedor->valor}} </option>
                    @else
                        <option value="{{$fornecedor->codigo}}"> {{$fornecedor->valor}} </option>
                    @endif
                @endforeach

              </select></td>


          </tr>

           <tr>
            <td><input name="confirma5" type="checkbox"></td>
          <td> <select  name="tipo5"  class="form-control">
              <option value=""></option>
              <option value="FR">FR</option>
              <option value="AC">AC</option>
              <option value="MOSTRUARIO">MOSTRU??RIO</option>
              <option value="AGREGADO">AGREGADO</option>
             
               

              </select></td>
          <td><input type="text" name="documento5" value="{{$capa[0]->id}}" class="form-control" ></td>
          <td><input type="date" name="vencimento5" class="form-control" ></td>
          <td><input type="date" name="pagamento5" class="form-control" ></td>
          <td><input type="decimal" name="valor5" class="form-control" ></td>
          <td><input type="text" name="obs5" class="form-control" ></td>
          <td><input type="text" name="proforma5" class="form-control"></td>
          <td><select name="id_fornecedor5" id="id_fornecedor" class="form-control">
                <option value=""> Selecione </option>

                @php                  
                    $fornecedores = \DB::select("select concat(nome,' - ',valor) as valor, caracteristicas.codigo from caracteristicas left join addressbook on caracteristicas.codigo = addressbook.id where campo = 'Fornecedor' order by concat(nome,' - ',valor)");
                @endphp                   

                @foreach ($fornecedores as $fornecedor) 
                    @if ($capa[0]->id_fornecedor == $fornecedor->codigo)
                        <option value="{{$fornecedor->codigo}}" selected=""> {{$fornecedor->valor}} </option>
                    @else
                        <option value="{{$fornecedor->codigo}}"> {{$fornecedor->valor}} </option>
                    @endif
                @endforeach

              </select></td>
          </tr>

          
          </table>
          
          </div>
                     

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">@lang('padrao.cancelar')</button>
        <button type="submit" class="btn btn-flat btn-primary">@lang('padrao.salvar') </button>
      </div>
    </div>
  </div>
</div>
</div>
</div>

</form>












@if ($capa[0]->tipo=='PRE-PEDIDO')
<form action="/compras/pedidos/modelos/criacao/upload" id="frmImporta" method="post" enctype="multipart/form-data">
	 @php $tipoarquivo ='O arquivo tem que estar no formato Xls'; @endphp
@else
<form action="/compras/pedido/importa" id="frmImporta" method="post" enctype="multipart/form-data">
	 @php $tipoarquivo ='O arquivo tem que estar no formato CSV'; @endphp
@endif
    @csrf 
<div class="modal fade" id="modalImportaItens" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">@lang('padrao.importarpedido') </h4>
      </div>

      <div class="modal-body">
        <input type="text" name="id_pedido" id="id_pedido" value="{{$capa[0]->id}}">
        <label>@lang('padrao.arquivo') </label>
        <input type="file" name="arquivo" required="" id="arquivo" class="form-control">
        <div style="color:#FE2E2E;">  {{$tipoarquivo}}</div>
		  @if ($capa[0]->tipo=='PRE-PEDIDO')
		  <br><h4>Arquivo de modelo: <a href="/storage/uploads/compras/arquivos/criacao_de_modelos__31_05_2021_17_23.Xlsx" >DOWNLOAD</a></h4>
		  @endif
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">@lang('padrao.cancelar')</button>
        <button type="submit" class="btn btn-flat btn-primary"><i class="fa fa-upload"></i> @lang('padrao.importar')</button>
      </div>
    </div>
  </div>
</div>
</form>
	
	
<form action="/compras/pedidos/cores/criacao/upload" id="frmImportaCores" method="post" enctype="multipart/form-data">
	 {{$tipoarquivo ='O arquivo tem que estar no formato Xls'}}

    @csrf 
<div class="modal fade" id="modalImportaCores" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Importa cores </h4>
      </div>

      <div class="modal-body">
        <input type="text" name="id_pedido" id="id_pedido" value="{{$capa[0]->id}}">
        <label>@lang('padrao.arquivo') </label>
        <input type="file" name="arquivo" required="" id="arquivo" class="form-control">
        <div style="color:#FE2E2E;">  {{$tipoarquivo}}</div>
		  <br><h4>Arquivo de modelo: <a href="/storage/uploads/compras/arquivos/criacao_de_cores__31_05_2021_14_58.Xlsx" >DOWNLOAD</a></h4>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">@lang('padrao.cancelar')</button>
        <button type="submit" class="btn btn-flat btn-primary"><i class="fa fa-upload"></i> @lang('padrao.importar')</button>
      </div>
    </div>
  </div>
</div>
</form>

<form action="/compras/{{$capa[0]->id}}/atualiza" method="post" id="" class="form-horizontal">
    @csrf
<div class="modal fade" id="modalPlanejamentoItem" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-calendar"></i> @lang('padrao.agendamentoentregas')</h4>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-4">
                <div id="foto"></div>
            </div>
            <div class="col-md-9">
				
                <input type="hidden" name="id_compra_item" id="id_compra_item">
                <div class="form-group">
                    <label class="col-md-2 control-label">@lang('padrao.item')</label>
                    <div class="col-md-8">
                        <div id="item"></div>
						<div id="id_compra_item"></div>
                     @php
                     $entregas = \DB::select("
                     select compras_itens.item as itemlk
                    from compras_itens
                    left join compras_entregas on compras_itens.id = compras_entregas.id_compra_item
                    where compras_itens.id_compra = 'id_compra_item'
                    and compras_itens.item = 'item'
						and compras_entregas.exclui is null");

                    
                     @endphp   
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-md-2 control-label">@lang('padrao.qtde') @lang('padrao.confirmado')</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control"  name="qtde" id="qtde">
                    </div>

                </div>

                  <p align="center"><img src="/img/carregando.gif" class="img-responsive" id="carregando"></p>
            </div>
        </div>

        <div id="tabPlanejamentoItem"></div>
      </div>
      <div class="modal-footer">
        
        <button type="button" class="btn btn-danger" data-dismiss="modal">@lang('padrao.cancelar')</button>
        
<!--		  <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> @lang('padrao.salvar')</button>-->
      </div>
    </div>
  </div>
</div>
</form>


<form action="/compras/{{$capa[0]->id}}/insere" id="frmPlanejamentoItem" method="post" class="form-horizontal">
    @csrf
<div class="modal fade" id="modalNovoItem" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">@lang("padrao.novo_item")</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id_pedido" value="{{$capa[0]->id}}">


        <div class="row" id="item">

            <div class="col-md-1">

            </div>

            <div class="col-md-11">
                <div class="form-group">
                    <label class="col-md-2 control-label">@lang('padrao.item')</label>
                    <div class="col-md-8">
                        <input type="text" name="item" required="" id="item" class="form-control">
                    </div>
                </div>        

                <div class="form-group">
                    <label class="col-md-2 control-label">@lang('padrao.qtde')</label>
                    <div class="col-md-4">
                        <input type="text" name="qtde" required="" id="qtde" class="form-control">
                    </div>
                </div> 

                <div class="form-group">
                    <label class="col-md-2 control-label">@lang('padrao.entrega')</label>
                    <div class="col-md-5">
                        <input type="date" name="dt_entrega" required="" id="dt_entrega" class="form-control">
                    </div>
                </div> 

                <div class="form-group">
                    <label class="col-md-2 control-label">@lang('padrao.obs')</label>
                    <div class="col-md-8">
                        <textarea name="obs"  class="form-control"></textarea>
                    </div>
                </div> 
            </div>
        </div>



      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('padrao.cancelar')</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> @lang('padrao.salvar')</button>
      </div>
    </div>
  </div>
</div>
</form>


<form action="/compras/{{$capa[0]->id}}/atualiza_importa" id="frmPlanejamentoItem" class="form-horizontal" method="post" enctype="multipart/form-data">
    @csrf
<div class="modal fade" id="modalAtualizaPedido" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">@lang("padrao.enviar_pedido")</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id_pedido" value="{{$capa[0]->id}}">


        <div class="form-group">
            <label class="col-md-2 control-label">@lang('padrao.obs')</label>
            <div class="col-md-9">
                <input type="file" name="arquivo" class="form-control">
            </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('padrao.cancelar')</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-refresh"></i> @lang('padrao.atualiza')</button>
      </div>
    </div>
  </div>
</div>
</form>

<form action="/compras/{{$capa[0]->id}}/envia" id="frmPlanejamentoItem" class="form-horizontal" method="post">
    @csrf
<div class="modal fade" id="modalEnviaPedido" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">@lang("padrao.enviar_pedido")</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id_pedido" value="{{$capa[0]->id}}">

        <div class="form-group">
            <label class="col-md-2 control-label">@lang('padrao.email')</label>
            <div class="col-md-8">
                <input type="email" name="email[]" class="form-control" value="{{$capa[0]->email1}}">
            </div>
            <div class="col-md-1">
                <button type="button" class="addEmail btn btn-flat btn-default"><i class="fa fa-plus"></i></button>
            </div>
        </div>

        <div id="emails"></div>


        <div class="form-group">
            <label class="col-md-2 control-label">@lang('padrao.msg')</label>
            <div class="col-md-9">
                <textarea name="obs" class="form-control"></textarea>
            </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('padrao.cancelar')</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-envelope"></i> @lang('padrao.enviar')</button>
      </div>
    </div>
  </div>
</div>
</form>
	
	<form action="/compras/entregas/upload_edita/{{$capa[0]->id}}" id="frmUploadEditaEntrega" class="form-horizontal" method="post" enctype="multipart/form-data">
    @csrf
<div class="modal fade" id="modalUploadEditaEntrega" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Atualizar datas de entrega</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id_pedido" value="{{$capa[0]->id}}">


        <div class="form-group">
            <label class="col-md-2 control-label">Escolher aquivo XLS</label>
            <div class="col-md-9">
                <input type="file" name="arquivo" class="form-control">
            </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('padrao.cancelar')</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-refresh"></i> Enviar</button>
      </div>
    </div>
  </div>
</div>
</form>

	<form action="/compras/arquivos/upload" id="frmArquivo" class="form-horizontal" method="post" enctype="multipart/form-data">
    @csrf
<div class="modal fade" id="modalArquivo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Arquivos</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id_pedido" value="{{$capa[0]->id}}">


        <div class="form-group">
            
            <div class="col-md-9">
				Nome
				<input type="text" name="nome" class="form-control"></input>
				Obs
				<input type="text" name="obs" class="form-control"></input>
				Data
	  			<input type="date" name="data" class="form-control"></input>
	  
	 			Tipo
			    <select class="form-control" name="tipo" >
                <option value="proforma">Proforma </option>
                <option value="swift">Swift </option>
                <option value="pedido assinado">Pedido assinado </option>
                <option value="invoice">Invoice </option>
				<option value="Pedido cota????o">Pedido cota????o </option>
					<option value="adiantamento">Adiantamento </option>
              </select>
				Arquivo
				<input type="file" name="arquivo" class="form-control">
				</input>
				</div>
	</br>
            <div class="col-md-12"> @foreach ($arquivos as $arquivo)
              <div class="col-md-6" > <a href="{{$arquivo->arquivo}}" class="btn btn-primary" >
                <div class="row">
					<div class="col-md-4" align="left"> <i class="fa fa-archive fa-2x"></i> </br><small>{{$arquivo->tipo}}</small></div>
                  <div class="col-md-8" align="left"> 
					 <small>Nome: {{$arquivo->nome}}</small><br>
					 <small> Obs: {{$arquivo->obs}}</small></br>
					<small> Usu??rio: {{$arquivo->usuario}}</small></br>
				  <small>Data: {{$arquivo->data}}</small></br>
	
				</div>
                </div>
                </a> </div>
              @endforeach
              
            </div>
</div>
            
	 
		  

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('padrao.cancelar')</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-refresh"></i> Enviar</button>
      </div>
</div>
    </div>
  </div>
</div>
</div>
</form>





	


@stop