@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i> Pedidos de importação para entregar  
@append 

@section('conteudo')
@if (Session::has('alert-success'))
  <div class="callout callout-success">{{Session::get("alert-success")}}</div>
@endif 
@if (Session::has('alert-warning'))
  <div class="callout callout-warning">{{Session::get('alert-warning')}}</div>
@endif
<div class="row">
  <div class="col-md-12">
    <div class="box box-widget box-body">
      <div class="row">
       
       
<!--
        <div class="col-md-12">
          <a href="/compras/invoice/importa" id="btnImportaArquivo" class="btn btn-flat btn-default pull-right">Importar Arquivo</a>
        </div>
-->
      </div>      
      <br>
       <table class="table table-bordered table-striped" id="myTable">
        <thead>
          <tr>
			  <th>Invoice</th>
            <th>Pedido</th>
            <th>Tipo</th>
            <th>Qtd</th>
			<th>Entregas em aberto</th>
            <th>Valor</th>
			<th>Data</th>
			<th>Grife</th>
			<th>Fornecedor</th>
			<th>Ação</th>
         
            



          </tr>
        </thead>
        <tbody>
          @foreach ($oi as $ois)
          <tr>
            <td> {{$ois->invoice}}</a></td>
            <td> {{$ois->pedido}}</a></td>
            <td>{{$ois->tipo}}</td>
            <td>{{$ois->qtd}}</td>
			<td>{{$ois->qtd_aberto}}</td>
            <td>{{$ois->tt_valor}}</td>
			 <td>{{$ois->dt_pedido}}</td>
			  <td>{{$ois->grife}}</td>
			  <td>{{$ois->fornecedor}}</td>
            
            <td><span class="pull-center"><a href="/compras/oi/detalhes/{{$ois->pedido}}/{{$ois->tipo}}" ><i ></i> Entregar</a></td>

          </tr>  
            @endforeach
        </tbody>
      </table>
    </div>
</div>




<form action="/compras/invoice/importa" id="frmImporta" method="post" enctype="multipart/form-data">
    @csrf 
<div class="modal fade" id="modalImportaXPTO" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Importar Itens</h4>
      </div>
		
      <div class="modal-body">
        <label>Arquivo</label>
        <input type="file" name="arquivo" required="" id="arquivo" class="form-control">
      </div>
		 <div class="col-md-4">
		<h6>O arquivo deverá estar em Xlsx, Coluna 1 Item<br>, Coluna 2 quantidade<br>, Coluna 3 custo unitario(campo formatado em texto e se for decimal deverá estar com ponto e não virgula.</h6>
		</div>
      <div class="col-md-4">
        <label>Invoice</label>
        <input type="text" name="invoice" required="" id="invoice" class="form-control">
        </div>

        <div class="col-md-4">
        <label>Data</label>
        <input type="date" name="data" required="" id="data" class="form-control">
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-flat btn-primary"><i class="fa fa-upload"></i> Importar</button>
      </div>
    </div>
  </div>
</div>
</form>

@stop