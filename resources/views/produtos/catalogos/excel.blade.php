@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i> Catalogo Excel - Query
@append 

@section('conteudo')

<form action="" method="post" class="form-horizontal">
  @csrf

  <div class="box box-widget box-body">

      <div class="form-group">
        <label class="col-md-2 control-label">Origem</label>

        <div class="col-md-3">
          <select name="origem" class="form-control">
            <option value="go"  selected="" >MySQL Amazon</option>
            <option value="goweb" selected="" >MySQL Level3</option>
          </select>
        </div>
      </div>
 

      <div class="form-group">
        <label class="col-md-2 control-label">Consulta</label>
        <div class="col-md-10">
          <textarea rows="20" name="consulta" class="form-control"></textarea>
        </div>
      </div>       

      <div class="form-group">
        <label class="col-md-2 control-label"></label>
        <div class="col-md-6">
        <button class="btn btn-default btn-flat">Executar</button>
        </div>
      </div>       


  </div>
</form>
@stop