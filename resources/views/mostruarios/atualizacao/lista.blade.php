@extends('layout.principal')

@section('title')
  <i class="fa fa-suitcase"></i> Atualização de NF
@append 

@section('conteudo')

<div class="box box-widget box-body">
      <div class="row">

      <div class="col-md-12">

        <table class="table table-condensed table-bordered" id="example1">
          <thead>
            <tr>
              <th width="">Inventário</th>
              <th width="">Id rep</th>
              <th width="">Razao</th>
              <th width="">Nome</th>
              <th width="">Id Atualização</th>
              <th width="">Status</th>
              <th width="">DT ult modificação</th>
              <th width="">Ação</th>

              
            </tr>                
          </thead>

          <tbody> 
          	
            @foreach ($atualizanf as $atualizanfs)
            <tr>
              <td align="center"> {{$atualizanfs->id_inventario}}</td>
              <td align="center"> {{$atualizanfs->id_rep}}</td>
              <td align="center"> {{$atualizanfs->razao}}</td>
              <td align="center"> {{$atualizanfs->nome}}</td>
              <td align="center"> {{$atualizanfs->id_atualizacao}}</td>
              <td align="center"> {{$atualizanfs->status_atualizacao}}</td>
              <td align="center"> {{$atualizanfs->updated_at}}</td>
              @if ($atualizanfs->id_atualizacao=='')
              <td align="center"><span class="pull-right"><a href="/mostruarios/atualizacao/inicia/{{$atualizanfs->id_inventario}}" class="btn btn-default btn-block"><i class="fa fa-edit"></i>Iniciar atualização</a></span></td>
              @else
              <td align="center">Atualização iniciada</td>
              @endif
              
               
            </tr>       

            @endforeach

          </tbody>
        </table>
      </div>
     
    </div>    
    </div> 

@stop