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
	<b>Tipo</b>: {{$invoice[0]->tipo}} <b>Pedido</b>: {{$invoice[0]->pedido}}<br>
	<b>Pedidos consumidos</b>: 
		  @foreach ($pedidos as $pedido)
		 <a href="/compras/{{$pedido->id_compra}}" ><i ></i> {{$pedido->id_compra.' - '}}</a> 
	
		  
		  @endforeach
		  </br>
<div class="col-md-6">
	<h3 align="center">Itens sem pedido</h3>
   		<table class="table table-condensed table-bordered" >
			<thead>
		
	
        </tr> 
			</thead>
		
			<thead>
		<tr class="bg-primary">
        <th align="center">Item</th>
		<th align="center">Qtd sem pedido</th>
        </tr> 
			</thead>
	   <tbody>
		@foreach ($sempedido as $item)
		<tr >
		<td align="center">{{$item->item}}</td>
		<td align="center">{{$item->qtd}}</td>
		
	
		</tr>
		@endforeach
		    @if(isset($sempedidototal))
			<tr>
		   	<td align="center"><b>TOTAL</b></td>
		<td align="center"><b>{{$sempedidototal[0]->tt}}</b></td>
		   </tr>
		   @endif
	</tbody>
		
	</table>
	</div>

<div class="col-md-6">
	<h3 align="center">Itens com custo divergente</h3>
   		<table class="table table-condensed table-bordered" >
			<thead>
		
	
        </tr> 
			</thead>
		
			<thead>
		<tr class="bg-primary">
        <th align="center">Item</th>
		<th align="center">Custo pedido</th>
		<th align="center">Custo invoice</th>
		<th align="center">Diferença</th>
        </tr> 
			</thead>
	   <tbody>
		 @if(isset($veririfcavalor))
		@foreach ($veririfcavalor as $itemvalor)
		<tr >
		<td align="center">{{$itemvalor->secundario}}</td>
		<td align="center">{{number_format($itemvalor->custo_pedido,2)}}</td>
		<td align="center">{{$itemvalor->custo_invoice}}</td>
		<td align="center">{{$itemvalor->custo_invoice-$itemvalor->custo_pedido}}</td>
		
	
		</tr>
		@endforeach
		   @endif
			
	</tbody>
		
	</table>
	</div>
	</div>
</br>
<div class="col-md-12">
    <div class="box box-widget box-body">
      
      <div class="row">
       
      
      
      </div>      
      <br><span class="pull-center"><a href="/compras/entregas/exclui_invoice/{{$invoice[0]->invoice}}" ><i ></i> Exclui</a>
       <table class="table table-bordered table-striped" id="myTable">
        <thead>
          <tr>
            <th align="center">Foto</th>
            <th align="center">Item</th>
            <th align="center">Qtd</th>
			<th align="center">Pedido consumido</th>
			<th align="center">Custo</th>
			<th>Ações</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($invoice as $itens)
          <tr>
             <td id="foto" align="center" style="min-height:60px;">
               
                <a href="" class="zoom" data-value="{{$itens->item}}"><img src="https://portal.goeyewear.com.br/teste999.php?referencia={{$itens->item}}" style="max-height: 60px;" class="img-responsive"></a>
                
              </td>
            
            <td align="center" @isset(json_decode($itens->portfolioItem->erros ?? null)->item) class="field-error" @endisset>{{$itens->item}}</td>
            <td align="center" @isset(json_decode($itens->portfolioItem->erros ?? null)->quantity) class="field-error" @endisset>{{$itens->qtd}}</td>
			  <td align="center">{{$itens->idcompra}}</td>
			  <td align="center" @isset(json_decode($itens->portfolioItem->erros ?? null)->price) class="field-error" @endisset>{{$itens->custo}}</td>
			<td>
        @isset($itens->portfolioItem)
          @if(!isset($itens->portfolioItem->aprovado_em))
            <a href="/row/{{$itens->id}}/aprovar" class="btn btn-sm btn-success"><i class="fa fa-thumbs-up"></i> &nbsp; Aprovar</a>
          @else
            <a href="/row/{{$itens->id}}/desaprovar" class="btn btn-sm btn-danger"><i class="fa fa-thumbs-down"></i> &nbsp; Desaprovar</a>
          @endif
          <button class="btn btn-sm btn-warning" data-toggle="modal"
          data-target="#addCommentsModal-{{$itens->id}}" data-row="{{$itens->id}}">
            <i class="fa fa-comments"></i> &nbsp; Comentar
            @isset($itens->comentarios)
              @if($itens->comentarios->where('id_usuario', '!=', auth()->user()->id)->where('visto_em', null)->first() != null)
                  <span class="badge bg-danger rounded-circle">
                      {{ $itens->comentarios->where('id_usuario', '!=', auth()->user()->id)->where('visto_em', null)->count() }}
                  </span>
              @endif
            @endisset
          </button>
        @endisset
			</td>
          </tr>  
            @endforeach
        </tbody>
      </table>
      @foreach($invoice as $item)
        @if($item->portfolioItem->aprovado_em != null)
          <a href="/embarques/{{$item->portfolioItem->importacao}}/download" class="btn btn-primary pull-right"><i class="fa fa-download"></i> &nbsp; Download planilha embarques</a>
          @break
        @endif
      @endforeach
      @foreach($invoice as $item)
        @if($item->portfolioItem->aprovado_em == null)
          <a href="/row/{{$item->portfolioItem->importacao}}/aprovar-todos" class="btn btn-primary pull-right"><i class="fa fa-thumbs-up"></i> &nbsp; Aprovar todos itens</a>
          @break
        @endif
      @endforeach
    </div>
</div>

@foreach ($invoice as $itens)
  @isset($itens->comentarios)
	  @include('produtos.compras.modal_comentarios', ['item' => $itens])
  @endisset
@endforeach

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

<style>
    .modal-dialog {
        max-width: 60% !important;
    }
    .comments-area {
        width: 100%;
        height: auto;
    }
    .my-comment-container {
        display: flex;
        justify-content: end;
        margin: 10px;
    }
    .their-comment-container {
        display: flex;
        justify-content: start;
        margin: 10px;
    }
    .comment-balloon {
        width: 80%;
        padding: 15px;
        border-radius: 15px;
    }
    .my-comment-container > .comment-balloon {
        background-color: lightblue;
    }
    .their-comment-container > .comment-balloon {
        background-color: lightgray;
    }
    .card {
        margin: 25px;
    }

    td {
        vertical-align: middle !important;
    }

    .card-header>h3 {
        margin-top: 5px;
    }

    form {
        display: inline-block;
        margin-block-end: unset;
    }

    .alert-danger {
        padding-left: 30px !important;
    }
    .field-error {
      background-color: red;
      color: white;
    }
</style>

<script>

  document.addEventListener('DOMContentLoaded', function(){
    $(document).on('show.bs.modal', '.modal', function(e){
      if(e.relatedTarget.querySelector('.badge')){
          fetch('/row/'+e.target.getAttribute('data-portfolio-id')+'/comments/'+e.target.getAttribute('data-last-comment')+'/read')
          .then(() => {
              e.relatedTarget.querySelector('.badge').remove()
          })
      }
    })
  })

</script>

@stop