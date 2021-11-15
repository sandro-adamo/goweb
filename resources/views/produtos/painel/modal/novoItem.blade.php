<form action="/produtos/novo" method="post" enctype="multipart/form-data" class="form-horizontal">
@csrf
<div class="modal fade" id="modalNovoItem" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog " role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">@lang('padrao.novo_item')</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="modelo" value="{{$modelo->modelo}}">
		  <input type="hidden" name="fornecedor" value="{{$modelo->fornecedor2}}">
        <div class="form-group">
          <label class="col-md-3 control-label">Tipo</label>
          <div class="col-md-4">
            <select name="tipo" class="form-control">
              <option value="006">Peça</option>
              <option value="007">Agregado</option>
              <option value="004">MPDV</option>

            </select>
          </div>
        </div>


        <div class="form-group">
          <label class="col-md-3 control-label">Referência</label>
          <div class="col-md-8">
            <input type="text" class="form-control" name="referencia" required="">
          </div>
        </div>



        <div class="form-group">
          <label class="col-md-3 control-label">Descrição</label>
          <div class="col-md-8">
            <textarea class="form-control" rows="3" name="descricao"></textarea>
          </div>
        </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary">Salvar</button>
      </div>
    </div>
  </div>
</div>
</form>