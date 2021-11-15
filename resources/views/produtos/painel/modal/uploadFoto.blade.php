<form action="/produtos/imagem/troca" method="post" enctype="multipart/form-data">
@csrf
<div class="modal fade" id="modalUploadFoto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Upload Foto</h4>
      </div>
      <div class="modal-body">
        <input type="text" id="tipo" name="tipo">
        <input type="text" id="valor" name="valor">
        <input type="file" id="arquivo" name="arquivo">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary">Salvar</button>
      </div>
    </div>
  </div>
</div>
</form>