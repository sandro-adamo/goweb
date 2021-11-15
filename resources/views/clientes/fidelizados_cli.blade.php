@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i> Clientes @if (isset($_GET["situacao"])) {{$_GET["situacao"]}} @endif
@append 

@section('conteudo')

@php
    $representantes = Session::get('representantes');
	$grifes = Session::get('grifes');
	$cliente 			= $_GET["cliente"];    
 	
 	
  	echo 'grifes'.$grifes.'</br>';
  	echo 'cliente: '.$cliente.'</br>';
    echo 'rep: '.$representantes;

    
    $where = ' where repres in ('.$representantes.') ';

   echo 'where : '.$where;
   
    if (isset($_GET["cliente"])) {
    $cliente = $_GET["cliente"];
    

    $query1 = \DB::select("
		select grife, supervisor, status_cli, financeiro
		from _fidelizados 
		where cliente = '$cliente' and grife in $grifes        
		group by grife, status_cli, financeiro, supervisor
    ");
    
    
	$query2 = \DB::select("
	select * from clientes_grife order by created_at desc
    ");
    
  }


  $index = 0;

  $motivos = \DB::select("select * from motivos where status = 1");
@endphp



    @if ($query1)

    <div class="row">
    @foreach ($query1 as $linha)
        @php  
          $index++;

          $grife = \DB::select("select valor from caracteristicas where campo = 'Grife' and codigo = '$linha->grife'");
          if ($grife) {

              $grife2 = $grife[0]->valor;

          }
        @endphp
      <form action="" method="post">@csrf
      <div class="col-md-3">
        <div class="box box-widget">
          <div class="box-header with-border" style="height: 100px;" align="center">
            <img src="/img/marcas/{{trim($grife[0]->valor)}}.png" class="img-responsive">
          </div>


          <div class="box-body">

            <input type="hidden" name="grife" value="{{$grife2}}">
            <input type="hidden" name="status" value="{{$linha->status_cli}}">
            <span style="font-size: 20px;" class="lead" >Status:  <span class="text-green">{{$linha->status_cli}}</span></span><br>


            <div class="row">
              <div class="col-md-12">

                 <select name="situacao" class="selSituacaoCliente2 form-control"  data-value="{{trim($grife[0]->valor)}}" >
                   <option value="">--- Selecione ---</option>          
                   <option value="Visitando">Visitando -- outro option</option>           
                   <option value="fechou/Trocou CNPJ">Fechou</option> 
                   <option value="falso">Vende falso / Pirata</option>
                   <option value="parceria">Parceria com outro cliente</option>
                   <option value="malpagador">Mal pagador</option>
                   <option value="perfil">Sem Perfil para a grife</option>
                 </select>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <button type="submit" class="pull-right"><i class="fa fa-save"></i> Salvar</button>
              </div>
            </div>
            @php

              $historicos = \DB::select("select * from clientes_visitas where cliente = '$cliente' and grife = '$grife2' order by id desc");

            @endphp

            @if ($historicos)

              @foreach ($historicos as $historico)
              <hr>
              <div class="row">
                <div class="col-md-3" align="center">
                  <img src="/img/logogo.png" class="img-responsive">
                </div>

                <div class="col-md-9">
                  <span class="lead"><i>{{$historico->situacao}}</i></span><br>
                  <small>{{$historico->created_at}}</small>
                </div>

                <div class="col-md-12"> 
                  @if ($historico->situacao == 'Visitando')
                    @php
                      $motivos_visita = \DB::select("select motivo 
                                                  from clientes_motivos
                                                  left join motivos on id_motivo = motivos.id
                                                  where id_visita = $historico->id ");
                    @endphp

                    <ul>
                      @foreach ($motivos_visita as $motivo)
                        <li> {{$motivo->motivo}} </li>
                      @endforeach
                    </ul>
                  @endif
                  {{-- <a href="" class="pull-right"><small>Alterar</small></a> --}}
                </div>
              </div>
              @endforeach

            @else 



             @endif


          </div>

          <div class="box-footer" align="right">
          </div>
        </div>
      </div>
      </form>
    @endforeach
    </div>
  @endif
<form action="" method="post"> @csrf
<div class="modal fade" id="modalDetalhes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Visitando</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12" id="imgGrife" align="center">

          </div>
        </div>
        <input type="hidden" name="cliente" id="cliente" value="{{$cliente}}">
        <input type="hidden" name="grife" id="grife">
        <input type="hidden" name="situacao" id="situacao" value="Visitando">
        <span class="lead">Motivo(s)</span>
        <hr>
        <div class="row">
        @foreach ($motivos as $motivo)
          <div class="col-md-6">
            <input type="checkbox" name="opcoes[]" value="{{$motivo->id}}"> {{$motivo->motivo}}
          </div>
        @endforeach
        </div>
        <br>
        <textarea class="form-control" name="obs"></textarea>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary">Salvar</button>
      </div>
    </div>
  </div>
</div>
</form>
@stop