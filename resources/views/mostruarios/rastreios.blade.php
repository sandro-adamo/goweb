@extends('layout.principal')

@section('title')
  <i class="fa fa-suitcase"></i> Rastreios Pedidos
@append 

@section('conteudo')

<div class="box box-widget box-body">
      <div class="row">

      <div class="col-md-12">
<button type="button" class="btn btn-default pull-right btn-flat " data-target="#modalRastreios" data-toggle="modal"><i class="fa fa-archive"></i> Upload rastreios</button>
        <table class="table table-condensed table-bordered" id="example1">
          <thead>
            <tr>
             
              <th width="">Id rep</th>
				<th width="">Nome</th>
				
				 <th width="">Pedido</th>
				<th width="">Tipo</th>
              <th width="">Qtd</th>
              <th width="">Rastreio</th>
              <th width="">Transportadora</th>
             

              
            </tr>                
          </thead>

          <tbody> 
          	
            @foreach ($listarastreio as $rastreio)
            <tr>
              <td align="center"> {{$rastreio->id_rep}}</td>
              <td align="center"> {{$rastreio->nome}}</td>
              <td align="center"> {{$rastreio->pedido}}</td>
              <td align="center"> {{$rastreio->tipo}}</td>
              <td align="center"> {{$rastreio->qtd}}</td>
              <td align="center"> {{$rastreio->rastreio}}</td>
              <td align="center"> {{$rastreio->transportadora}}</td>
            
              
               
            </tr>       

            @endforeach

          </tbody>
        </table>
      </div>
     
    </div>    
    </div> 



<form action="/mostruarios/rastreios/upload" id="frmRastreioso" class="form-horizontal" method="post" enctype="multipart/form-data">
    @csrf
<div class="modal fade" id="modalRastreios" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Upload</h4>
      </div>
      <div class="modal-body">
       
				Arquivo
				<input type="file" name="arquivo" class="form-control">
				</input>
				</div>
	</br>
        


      
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('padrao.cancelar')</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-refresh"></i> Enviar</button>
      </div>
	</div>
	</div>
</div>

</form>

@stop