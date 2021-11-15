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
       <div class="col-md-6">
       	<b>Pedido:</b> {{$oi[0]->pedido}}<br>
		<b>Tipo:</b> {{$oi[0]->tipo}}<br>
		 <b>Data pedido:</b> {{$oi[0]->dt_pedido}}<br>
		 <b> Fornecedor:</b> {{$oi[0]->fornecedor}}<br>
		 <b> Invoice:</b> {{$oi[0]->invoice}}
		</div>
		  

        <div class="col-md-12">
          <a href="/compras/oi/entrega/{{$oi[0]->pedido}}/{{$oi[0]->tipo}}" class="btn btn-upload btn-success pull-right">Entregar pedido</a>
        </div>


      </div>      
      <br>
       <table class="table table-bordered table-striped" id="myTable">
        <thead>
          <tr>
            <th>Foto</th>
			  <th>Grife</th>
            <th>Item</th>
            <th>Qtd</th>
			<th>Entregas em aberto</th>
			  <th>Pedidos a consumir</th>
            <th>Valor</th>
			<th>Valor Total</th>
			
         
            



          </tr>
        </thead>
        <tbody>
          @foreach ($oi as $ois)
          <tr>
            
           <td id="foto" align="center" style="min-height:60px;">
               
                <a href="" class="zoom" data-value="{{$ois->item}}"><img src="https://portal.goeyewear.com.br/teste999.php?referencia={{$ois->item}}" style="max-height: 60px;" class="img-responsive"></a>
                
              </td>
			<td>{{$ois->grife}}</td>
			  <td><a href="/painel/{{$ois->grife}}/{{$ois->modelo}}" >{{$ois->item}}</a></td>
            <td>{{$ois->qtde_sol}}</td>
			  @if ($ois->qtd_aberto<$ois->qtde_sol)
			<td bgcolor="#F8060A">{{$ois->qtd_aberto}}</td>
			  @else
			  <td >{{$ois->qtd_aberto}}</td>
			  @endif
			  <td>{{$ois->id_compra1}}</td>
            <td>{{$ois->vlr_unit}}</td>
			 <td>{{$ois->valor_tt}}</td>
			  
			  
            

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