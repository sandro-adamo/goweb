@extends('layout.principal')

@section('title')
<i class="fa fa-file-o"></i> @lang('padrao.pedido_compra')
@append 

@section('conteudo')

<form action="" method="get">
<div class="row">
  <div class="col-md-12">
    <div class="box box-widget box-body">
      <div class="row">
        <div class="col-md-2">
          <small>@lang('padrao.status')</small>
          <select class="form-control" name="status">
            <option value=""></option>
            <option value="ABERTO" @if (isset($_GET["status"]) && $_GET["status"] == 'ABERTO') selected="" @endif>@lang('padrao.aberto')</option>
            <option value="ENVIADO" @if (isset($_GET["status"]) && $_GET["status"] == 'ENVIADO') selected="" @endif>@lang('padrao.enviado')</option>
            <option value="CONFIRMADO" @if (isset($_GET["status"]) && $_GET["status"] == 'CONFIRMADO') selected="" @endif>@lang('padrao.confirmado')</option>
            <option value="DISTRIBUIDO" @if (isset($_GET["status"]) && $_GET["status"] == 'DISTRIBUIDO') selected="" @endif>@lang('padrao.distribuido')</option>
            <option value="PRODUCAO" @if (isset($_GET["status"]) && $_GET["status"] == 'PRODUCAO') selected="" @endif>@lang('padrao.producao')</option>
            <option value="INVOICE" @if (isset($_GET["status"]) && $_GET["status"] == 'INVOICE') selected="" @endif>@lang('padrao.invoice')</option>
            <option value="CANCELADO" @if (isset($_GET["status"]) && $_GET["status"] == 'CANCELADO') selected="" @endif>@lang('padrao.cancelado')</option>
            <option value="CONCLUIDO" @if (isset($_GET["status"]) && $_GET["status"] == 'CONCLUIDO') selected="" @endif>@lang('padrao.concluido')</option>
          </select>
        </div>

        <div class="col-md-2">
          <small>@lang('padrao.inicio')</small>
          <input type="date" name="inicio" placeholder="@lang('padrao.fornecedor')" class="form-control" @if (isset($_GET["inicio"])) value="{{$_GET["inicio"]}}" @endif>
        </div>
        <div class="col-md-2">
          <small>@lang('padrao.fim')</small>
          <input type="date" name="fim" placeholder="@lang('padrao.fornecedor')" class="form-control" @if (isset($_GET["fim"])) value="{{$_GET["fim"]}}" @endif>
        </div>
		         <div class="col-md-2">
          <small>Proforma</small>
          <input type="text" name="proforma" placeholder="proforma" class="form-control" @if (isset($_GET["proforma"])) value="{{$_GET["proforma"]}}" @endif>
        </div>

      </div>
      <div class="row">
        <div class="col-md-2">
          <small>@lang('padrao.pedido')</small>
          <input type="text" name="pedido" placeholder="@lang('padrao.pedido')" class="form-control" @if (isset($_GET["pedido"])) value="{{$_GET["pedido"]}}" @endif>
        </div>
        <div class="col-md-4">
          <small>@lang('padrao.fornecedor')</small>
            <select name="id_fornecedor" id="id_fornecedor" class="form-control" >
              <option value=""> Selecione </option>

              @php                  
              $fornecedores = \DB::select("select * from caracteristicas where campo = 'Fornecedor'");
              @endphp                   

              @foreach ($fornecedores as $fornecedor) 
                @if (isset($_GET["id_fornecedor"]) && $fornecedor->codigo == $_GET["id_fornecedor"])
                  <option value="{{$fornecedor->codigo}}" selected=""> {{$fornecedor->valor}} </option>
                @else
                  <option value="{{$fornecedor->codigo}}"> {{$fornecedor->valor}} </option>
                @endif
              @endforeach

            </select>
        </div>
		  
		     <div class="col-md-2">
          <small>Tipo</small>
            <select name="tipo" id="tipo" class="form-control" >
              <option value=""> Selecione </option>

              @php                  
              $tipo = \DB::select("select distinct tipo from compras ");
              @endphp                   
				<option value="" selected=""> </option>
              @foreach ($tipo as $tipos)
				<option value="{{$tipos->tipo}}"> {{$tipos->tipo}} </option>
           
              @endforeach

            </select>
        </div>

        <div class="col-md-2">
          <br>
          <button class="btn btn-default btn-flat"><i class="fa fa-search"></i> @lang('padrao.pesquisar')</button>
        </div>
        <div class="col-md-2"><br>
          <a href="" id="btnNovoPedido2" class="btn btn-primary btn-flat pull-right">@lang('padrao.novo_pedido')</a>
        </div>
      </div>        
      <br>
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th width="8%">@lang('padrao.data')</th>
            <th width="8%">@lang('padrao.pedido')</th>
			  <th width="8%">Status</th>
			  <th width="8%">Proforma</th>
            <th width="8%">@lang('padrao.tipo')</th>
            <th width="20%">@lang('padrao.fornecedor')</th>
			  <th width="25%">@lang('padrao.obs')</th>
			   <th width="25%">Ano mod</th>
			   <th width="30%">Grife</th>
            <th width="5%">@lang('padrao.qtde')</th>
			  <th width="5%">Entregue</th>
            <th width="10%">@lang('padrao.total')</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($pedidos as $pedido)
          <tr>
            <td align="center">{{$pedido->dt_emissao}}</td>
            <td align="center"><a href="/compras/{{$pedido->id}}">{{$pedido->id}}</a></td>
			  <td align="center">{{$pedido->status_item}}</td>
			  <td align="center">{{$pedido->proforma}}</td>
            <td align="center">{{$pedido->tipo}}</td>
            <td>{{$pedido->fornecedor}}</td>
			  <td>{{$pedido->obs}}</td>
			   <td>{{$pedido->anomod}}</td>
			   <td>{{$pedido->grife}}</td>
            <td align="center">{{number_format($pedido->itens,0,'.','.')}}</td>
			   <td align="center">{{number_format($pedido->qtd_entegue,0,'.','.')}}</td>
            <td align="right">{{number_format($pedido->total,2,',','.')}}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
</div>
</div>
</form>


<form action="/compras/pedido/novo" method="post" class="form-horizontal" enctype="">
  @csrf
  <div class="modal fade" id="modalNovoPedido" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">'
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> @lang('padrao.novo_pedido')</h4>
        </div>
        <div class="modal-body">

          <div class="form-group">
            <label class="col-md-2 control-label">@lang('padrao.tipo')</label>
            <div class="col-md-10">
              <input type="radio" name="tipo" value="PEDIDO" checked> @lang('padrao.pedido')
              <input type="radio" name="tipo" value="REPEDIDO"> @lang('padrao.repedido')
				<input type="radio" name="tipo" value="PRE-PEDIDO"> PRE-PEDIDO
            </div>        
          </div>



          <div class="form-group">
            <label class="col-md-2 control-label">@lang('padrao.data')</label>
            <div class="col-md-4">
              <small>@lang('padrao.emissao')</small>
              <input type="date" name="dt_emissao" value="" required id="dt_emissao" class="form-control">
            </div>         
            <div class="col-md-4">
              <small>@lang('padrao.entrega')</small>
              <input type="date" name="dt_entrega" id="dt_entrega" value="" required class="form-control">
            </div>        
          </div>

          <div class="form-group">
            <label class="col-md-2 control-label">@lang('padrao.fornecedor')</label>
            <div class="col-md-10">
              <select name="id_fornecedor" id="id_fornecedor" required class="form-control">
                <option value=""> Selecione </option>

                @php                  
                $fornecedores = \DB::select("select * from caracteristicas where campo = 'Fornecedor'");
                @endphp                   

                @foreach ($fornecedores as $fornecedor) 
                <option value="{{$fornecedor->codigo}}"> {{$fornecedor->valor}} </option>
                @endforeach

              </select>
            </div>        
          </div>

          <div class="form-group">
            <label class="col-md-2 control-label">@lang('padrao.transporte')</label>
            <div class="col-md-10">
              <select name="transporte" id="transporte" class="form-control">
                <option value=""> Selecione </option>
                <option> Plane </option>
                <option> Ship </option>
                <option> EXPRESS (FEDEX...) </option>
              </select>
            </div>        
          </div>

          <div class="form-group">
            <label class="col-md-2 control-label">@lang('padrao.pagamento')</label>
            <div class="col-md-10">
              <select name="pagamento" id="pagamento" class="form-control">
                <option value=""> Selecione </option>
                <option> Carta de Crédito </option>
                <option> Bank </option>
              </select>
            </div>        
          </div>

{{-- 
        <div class="form-group">
          <label class="col-md-2 control-label">@lang('padrao.importar')</label>
            <div class="col-md-10">
              <input type="file" name="arquivo" id="arquivo" class="form-control"></textarea>
            </div>        
          </div> --}}

          <div class="form-group">
            <label class="col-md-2 control-label">@lang('padrao.observacoes')</label>
            <div class="col-md-10">
              <textarea name="obs" id="obs" class="form-control"></textarea>
            </div>        
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">@lang('padrao.cancelar')</button>
          <button type="submit" class="btn btn-primary btn-flat">@lang('padrao.salvar')</button>
        </div>
      </div>
    </div>
  </div>
</form>

@stop