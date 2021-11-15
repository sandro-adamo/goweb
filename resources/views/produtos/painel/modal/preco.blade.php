<form action="/produto/preco/atualiza" method="post">
@csrf
<div class="modal fade" id="modalPreco" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="titulo"></h4>
      </div>
      <div class="modal-body">
        <input type="hidden" id="tipo" name="tipo">
        <input type="text" id="id_item" name="id_item">
        <input type="text" id="caracteristica" name="caracteristica">
        
        <div id="valores"></div>

        <div id="alteraColItem"></div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary">Salvar</button>
      </div>
    </div>
  </div>
</div>
</form>