@extends('layout.principal')

@section('title')
<i class="fa fa-refresh"></i> Trocas
@append 

@section('conteudo')

  <div class="row">
    <div class="col-md-12">
      <div class="box box-widget box-body"> 
        <br>
        <div class="table-responsive">
        <table class="table table-bordered" id="myTable">
          <thead>
            <tr>
             <th>Status</th>
             <th>ST</th>
             <th>Data</th>
             <th>Cliente</th>
             <th>Item</th>
             <th>Observações</th>
            </tr>
         </thead>
         <tbody> 

          @foreach ($trocas as $troca)
            <tr>
              <td align="center">

                @switch($troca->id_status_caso)
                  @case('999')
                    <span class="text-bold text-green">Concluída</span>
                    @break
                  @case('210')
                    <span class="text-bold text-purple">Aguardando</span>
                    @break
                  @case('135')
                    <span class="text-bold text-orange">Pendente</span>
                    @break
                  @case('110')
                    <span class="text-bold text-blue">Registrada</span>
                    @break
                  @default  
                    {{$troca->id_status_caso}}
                @endswitch

              </td>
              <td align="center">{{$troca->id_troca}}</td>
              <td align="center">{{date("d/m/Y", strtotime($troca->data_troca))}}</td>
              <td>{{$troca->razao}}</td>
              <td align="center">{{$troca->secundario}}</td>
              <td>
                <ul>
                  @if ($troca->nf_origem == '')
                    <li> Nota fiscal de origem não encontrada</li>
                  @endif
                  @if ($troca->substituto == '')
                    <li> Aguardando substituição</li>
                  @endif

                  @if ($troca->nf_consumidor == 1)
                    <li> Enviar cupom fiscal</li>
                  @endif
                  @if ($troca->outro_codigo == 1)
                    <li> Autorização de envio para outro código</li>
                  @endif

                  @if ($troca->nf_origem <> '' and $troca->nf_consumidor == 0 and $troca->outro_codigo == 0 and $troca->substituto <> '' and $troca->id_status_caso == '135') 
                    <li> Pendência Fiscal</li>
                  @endif
                  @if ($troca->id_status_caso == '210')
                    <li>Aguardando devolução da peça com defeito</li>
                  @endif
                </ul>

              </td>
            </tr>              


          @endforeach
        </tbody>           


      </table>
      
    </div>

    </div>
  </div>
@stop