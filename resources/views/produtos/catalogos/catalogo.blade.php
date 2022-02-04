@extends('produtos/painel/index')

@section('title')
<i class="fa fa-book"></i> Catálogo
@append 

@section('conteudo')

@php
if (Session::has('alert-danger') ) {
	$erros = Session::get('alert-danger');
	echo '<div class="callout callout-danger"><ul>';
	foreach ($erros as $erro) {
		echo '<li>'.$erro.'</li>';
	}
	echo '</div>';

}
@endphp




  <form action="/catalogo/{{$catalogo->codigo}}/salva" class="form-horizontal" method="post">
    @csrf
      <div class="box box-widget">
        <div class="box-header with-border">
          
              @if (Session::has('novocatalogo')) 

                @if (\Auth::user()->id_perfil == 1 or \Auth::user()->id_perfil ==25)
                <div class="form-group">
                  <label class="col-md-2 control-label">Publico</label>
                  <div class="col-md-8">
                    <input type="checkbox" name="publico" value="1" @if (isset($catalogo->publico) && $catalogo->publico == 1) checked="" @endif>
                  </div>
                </div>
                @endif

                <div class="form-group">
                  <label class="col-md-2 control-label">Titulo</label>
                  <div class="col-md-8">
                    <input type="text" name="titulo" @if (isset($catalogo->titulo)) value="{{$catalogo->titulo}}" @endif class="form-control">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-2 control-label">Descrição</label>
                  <div class="col-md-8">
                    <textarea name="descricao" rows="4" class="form-control">@if (isset($catalogo->descricao)) {{$catalogo->descricao}} @endif</textarea> 
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-2 control-label">Imagem</label>
                  <div class="col-md-8">
                    <input type="file" name="imagem" value="" class="form-control">
                  </div>
                </div>

              @else
                <h3 class="box-title"><i class="fa fa-book"></i> Catálogo: {{$catalogo->titulo}}</h3>
              @endif
              <span class="pull-right">Por: {{$catalogo->usuario->nome}}</span>
        </div>
        <div class="box-body">
          <div class="row">
          @foreach ($grifes as $codgrife => $grife)
            <div class="col-md-2">
              <a href="/catalogo/{{$catalogo->codigo}}/?grife={{$codgrife}}"><img src="/img/marcas/{{$grife}}.png" class="img-responsive"></a>
            </div>
          @endforeach 
          </div>
        </div>
        @if (Session::has('novocatalogo'))
        <div class="box-footer">
          <a href="" class="btn btn-flat btn-default" data-toggle="modal" data-target="#modelImporta"><i class="fa fa-upload"></i> Importar</a>
          <a href="/catalogo/{{$catalogo->codigo}}/cancela" class="btn btn-flat btn-default pull-right"><i class="fa fa-close"></i> Cancelar </a>
          <button type="submit" class="btn btn-flat btn-default pull-right"><i class="fa fa-save"></i> Gravar </button>
        </div>
        @else
        <div class="box-footer">
          <a class="btn btn-flat btn-default" href="/catalogo/{{$catalogo->codigo}}/pdf" target="_blank"><i class="fa fa-file"></i> Exportar PDF</i> 
          <a href="/catalogo/{{$catalogo->codigo}}/edita" class="btn btn-flat btn-default pull-right"><i class="fa fa-edit"></i> Editar </a>
        </div>
        @endif
      </div>
  </form>

  <div class="row">
    @foreach ($modelos as $modelo)
    <div class="col-md-2">
      <div class="box box-body box-widget" align="center" style="min-height: 180px;">
        <a href="" class="zoom" data-value="{{$modelo->item}}"><img src="https://portal.goeyewear.com.br/teste999.php?referencia={{$modelo->item}}" class="img-responsive"></a>
        <span class="text-bold text-center">{{$modelo->item}}</span>
        <br>
        @if (Session::has('novocatalogo') and \Auth::user()->admin == 1 )
          @php
            $saldo = \DB::select("select disponivel from saldos where secundario = '$modelo->item'");
            if ($saldo) {
              echo $saldo[0]->disponivel;
            }
          @endphp
          
        @endif 
		  <a href="/catalogo/{{$modelo->codigo}}/{{$modelo->id}}/delItem" class="text-red"><i class="fa fa-close"></i> Excluir</a>
        </tr>
      </div>
    </div>
    @endforeach

    @if (Session::has('novocatalogo'))
    <div class="col-md-2">
      <div class="box box-body box-widget" align="center" style="min-height: 180px;">
        <a href="" class="btnAddItem"><i class="fa fa-plus fa-3x" style="margin-top: 50px;"></i><br>
        <span class="text-bold text-center">Novo Item</span></a>
        </tr>
      </div>
    </div>    
    @endif
    
  </div>


  <!-- Modal -->
  <form action="/api/catalogo/{{$catalogo->codigo}}/addItem" method="post" class="form-horizontal">
    @csrf
  <div class="modal fade" id="modalAddItem" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> Add Item</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label class="col-md-3 control-label">Item</label>
            <div class="col-md-9">
              <input type="text" name="item" class="form-control">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Fechar</button>
          <button type="submit" class="btn btn-flat btn-primary">Salvar</button>
        </div>
      </div>
    </div>
  </div>
  </form>




  <!-- Modal -->
  <form action="/catalogo/{{$catalogo->codigo}}/importar" method="post" class="form-horizontal" enctype="multipart/form-data">
   
    @csrf
<div class="modal fade" id="modelImporta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
		 
		  
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Upload</h4>
      </div>
      <div class="modal-body">
         <h4 class="modal-title" id="myModalLabel">O arquivo tem que conter apenas:
			 <br>- o código secundário (exemplo AT1001 A01) apartar da célula A2
			 <br>- salvar o arquivo em Xlsx</h4>
				
				<input type="file" name="arquivo" class="form-control">
				</input>
		<br>
<!--		<a href="/storage/uploads/20210923101629catalogo.xlsx">Download modelo arquivo.</a>-->
	</br>
        


      
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-refresh"></i> Importar</button>
      </div>
	</div>
	</div>
</div>

</form>

@stop