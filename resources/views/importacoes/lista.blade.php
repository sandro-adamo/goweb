@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i> Invoices  
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
       
       
        <div class="col-md-12">
          <a href="/xpto/importar" id="btnImportaArquivo" class="btn btn-flat btn-default pull-right">Importar Arquivo</a>
        </div>
      </div>      
      <br>
       <table class="table table-bordered table-striped" id="myTable">
        <thead>
          <tr>
            <th>Fábrica</th>
            <th>Invoice</th>
            <th>Qtd</th>
            <th>Moeda</th>
            <th>Valor</th>
            <th>Data</th>
            <th>Ação</th>
            



          </tr>
        </thead>
        <tbody>
          @foreach ($lista as $invoice)
          <tr>
            
            <td>{{$invoice->fabrica}}</td>
            <td><span class="pull-center"><a href="/compras/invoice/detalhes/importacoes/{{$invoice->invoice}}" ><i ></i> {{$invoice->invoice}}</a></td>
            <td>{{$invoice->qtd}}</td>
            <td>{{$invoice->moeda}}</td>
            <td>{{$invoice->valor}}</td>
            <td>{{$invoice->dt_invoice}}</td>
            <td><span class="pull-center"><a href="/compras/invoice/deleta/{{$invoice->invoice}}" ><i ></i> Exclui</a></td>

          </tr>  
            @endforeach
        </tbody>
      </table>
    </div>
</div>




<form action="/xpto/importa" id="frmImporta" method="post" enctype="multipart/form-data">
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
      <div class="modal-footer">
        <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-flat btn-primary"><i class="fa fa-upload"></i> Importar</button>
      </div>
    </div>
  </div>
</div>
</form>

@stop