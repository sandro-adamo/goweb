@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i>
@append 

@section('conteudo')



<div class="row">
  <div class="col-md-12">
    <div class="box box-widget box-body">
      <form action="/mostruarios/inventarios/alteracao" method="post" class="form-horizontal">
    @csrf 

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="title">Altera Situação  para {{$acao}}</h4>
 
      
        <div class="form-group">
          <input type="hidden" name="id_linha" id="id_linha">
          <label class="col-md-2 control-label">Item </label>
          <div class="col-md-4">
          <input type="hidden" name="devolver" value="1">
			  <input type="hidden" name="acao" value="{{$acao}}">
          <input type="text" name="referencia" id="referencia"  class="form-control" readonly="" value = "{{$item[0]->item}}">
          </div>  
      </div>

        <div class="form-group">

          <label class="col-md-2 control-label">Motivo</label>
          <div class="col-md-4">

            <select name="motivo" class="form-control" required="">

              <option value="">-- Selecione --</option>

              <option>Não desejo</option>
              <option>Duplicidade</option>
              <option>Não vendo a grife</option>
              <option>Problema técnico</option>
              <option>Outros</option>

            </select>

          </div>

        </div>

        <input type="hidden" name="id_tabela_invetario" value="{{$item[0]->id}}">
        <div class="form-group">

          <label class="col-md-2 control-label">Observações</label>
          <div class="col-md-4">

            <textarea name="obs" class="form-control"></textarea>

          </div>

      </div>
      
     
   
<div class="form-footer">
        <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-flat btn-primary"><i class="fa fa-save"></i> Salvar</button>
      </div>
       </div>
   
</div>
   </div>

      </div>

</form>



@stop