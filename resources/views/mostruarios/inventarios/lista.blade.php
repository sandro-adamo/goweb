@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i> Lista de 
            @if ($acao=='Recebendo'){{'Recebimento'}}@endif
            @if ($acao=='Enviando'){{'Envio'}}@endif
            @if ($acao=='inventario'){{'Inventário'}}@endif
@append 

@section('conteudo')



<div class="row">
  <div class="col-md-12">
    <div class="box box-widget box-body">
      <div class="row">
        <div class="col-md-6">
        </div>
        <div class="col-md-2">
        </div>
        <div class="col-md-4">
         
          <!-- @if (!isset($inventarios[0]->status) or ( isset($inventarios[0]->status) && $inventarios[0]->status<>"Iniciada")) -->
            <a href="/mostruarios/inventarios/novo/{{$acao}}" class="btn btn-flat btn-default pull-right">
            @if ($acao=='Recebendo'){{'Iniciar recebimento'}}@endif
            @if ($acao=='Enviando'){{'Iniciar envio'}}@endif
            @if ($acao=='inventario'){{'Novo inventário'}}@endif


          </a>
         <!--  @endif -->
        </div>
      </div>      
      <br>
      <table class="table table-condensed table-bordered" id="example1">
        <thead>
          <tr>
            <th>Numero 
              @if ($acao=='Recebendo'){{'recebimento'}}@endif
            @if ($acao=='Enviando'){{'envio'}}@endif
            @if ($acao=='inventario'){{'inventário'}}@endif
            </th>
            @if (\Auth::user()->id_perfil <> 4 and \Auth::user()->id_perfil <> 23)
            <th>Nome</th>
            <th>Codigo Rep</th>
             <th>Email</th>
              <th>Tipo</th>
            @endif

            <th>Data inicio</th>
            <th>Quantidade</th>
            <th>Status</th>
             @if (\Auth::user()->id_perfil <> 4 and \Auth::user()->id_perfil <> 23)
             <th>Id devolução</th>
            <th>Reabrir</th>
            <th>Cancelar</th>
             @endif
          </tr>
        </thead>
        <tbody>

          @if ($inventarios && count($inventarios) > 0)
            @foreach ($inventarios as $inventario)
            <tr>
              <td> <a href="/mostruarios/inventarios/detalhes/{{$acao}}/{{$inventario->id_inventario}}" >{{$inventario->id_inventario}} </a></td>
              @if (\Auth::user()->id_perfil <> 4  and \Auth::user()->id_perfil <> 23 )
            <th>{{$inventario->nome}}</th>
            <th>{{$inventario->id_rep}}</th>
            <th>{{$inventario->email}}</th>
             <th>{{$inventario->tipo}}</th>
            @endif
              <td>{{$inventario->dt_inicio}}</td>
              <td>{{$inventario->item}}</td>
              <td>{{$inventario->status}}</td>
                @if (\Auth::user()->id_perfil <> 4 and \Auth::user()->id_perfil <> 23)
                <td>{{$inventario->id_devolucao}}</td>
              <td> <a href="/mostruarios/inventarios/reabre/{{$inventario->id_inventario}}" >Reabrir</a></td>
              @if($inventario->status=='iniciada')
              <td> <a href="/mostruarios/inventarios/cancela/{{$inventario->id_inventario}}" >Cancelar</a></td>
              @else
              <td></td>
              @endif
              
              @endif
            </tr>
            @endforeach
          @else

            <tr>
              <td colspan="4"> Nenhum inventário iniciado</td>
            </tr>

          @endif 

        </tbody>
      </table>
    </div>
</div>



@stop