<form action="/produto/caracteristica/genero" method="post">
@csrf
<div class="modal fade" id="modalGenero" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Genêro</h4>
      </div>
      <div class="modal-body">
        <input type="text" id="id_item" name="id_item">
        <select name="genero" class="form-control">
          <option value="MAS">Masculino</option>
          <option value="FEM">Feminino</option>
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary">Salvar</button>
      </div>
    </div>
  </div>
</div>
</form>