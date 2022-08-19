@extends('layout.principal')

@section('title')

@append 

@section('conteudo')

<div class="row">
   
   
  <div class="col-md-12">
    <div class="box box-widget box-body">
      <h4>
      <b>Invoice:</b> {{$invoice[0]->invoice}}<br>
      <b>Data</b>: {{$invoice[0]->dt_invoice}}<br>
      <b>Quantidade Total:</b> {{$total[0]->qtd}}<br>
      <b>Valor Total:</b> {{$total[0]->valor}}<br></h4>
      <div class="row">
       
      
      
      </div>      
      <br>
       <table class="table table-bordered table-striped" id="myTable">
        <thead>
          <tr>
            <th>Foto</th>
            <th>Item</th>
            <th>Qtd</th>
            <th>Valor Unitário</th>
            <th>Moeda</th>
            <th>Valor Total</th>
            
            



          </tr>
        </thead>
        <tbody>
          @foreach ($invoice as $itens)
          <tr>
             <td id="foto" align="center" style="min-height:60px;">
               
                <a href="" class="zoom" data-value="{{$itens->item}}"><img src="https://portal.goeyewear.com.br/teste999.php?referencia={{$itens->item}}" style="max-height: 60px;" class="img-responsive"></a>
                
              </td>
            
            <td>{{$itens->item}}</td>
            <td>{{$itens->qtd}}</td>
            <td>{{$itens->unitario}}</td>
            <td>{{$itens->moeda}}</td>
            <td>{{$itens->valor}}</td>
            

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