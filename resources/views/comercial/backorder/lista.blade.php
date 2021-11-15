@extends('layout.principal')

@section('title')
<i class="fa fa-file-o"></i> Meus Orçamentos
@append 

@section('conteudo')

@php
  $data = \DB::select("select created_at from orcamentos limit 1");
@endphp

<small> Última atualização {{$data[0]->created_at}}</small>

<div class="row">
  
<!--
  <div class="col-md-6">
    <div class="box box-body box-widget">
      <table class="table table-bordered table-condensed">
        <thead>
          <tr>
            <th width="45%">Grife</th>
            <th width="15%" style="text-align: center;">Peças</th>
            <th width="15%" style="text-align: center;">Valor</th>
            <th width="15%" style="text-align: center;">Atende</th>
          </tr>
        </thead>

        <tbody>

          @php

            $total_grife_pecas = 0;
            $total_grife_valor = 0;
            $total_grife_atende = 0;

          @endphp

          @foreach ($grifes as $grife)

            @php

              $total_grife_pecas += $grife->pecas;
              $total_grife_valor += $grife->valor;
              $total_grife_atende += $grife->atende;

            @endphp

            <tr>
              <td><a href="?grife={{$grife->codgrife}}">{{$grife->grife}}</a></td>
              <td align="center"><a href="/backorder/detalhes/{{$grife->codgrife}}">{{number_format($grife->pecas, 0, '.', '.')}}</a></td>
              <td align="right">{{number_format($grife->valor, 2, ',', '.')}}</td>
              <td align="center">{{number_format($grife->atende, 0, '.', '.')}}</td>
            </tr>
          @endforeach

        </tbody>
        <tfoot>

            <tr>
              <th>TOTAL</th>
              <th style="text-align: center;">{{number_format($total_grife_pecas, 0, '.', '.')}}</th>
              <th style="text-align: right;">{{number_format($total_grife_valor, 2, ',', '.')}}</th>
              <th style="text-align: center;">{{number_format($total_grife_atende, 0, ',', '.')}}</th>
            </tr>          

        </tfoot>
      </table>
    </div>
  </div>
-->
    
<!--
  <div class="col-md-6">
    <div class="box box-body box-widget">
      <table class="table table-bordered table-condensed compact" >
        <thead>
          <tr>
            <th width="45%">Status</th>
            <th width="15%">Peças</th>
            <th width="20%">Valor</th>
            <th width="20%">Atende</th>
          </tr>
        </thead>

        <tbody>


          @php

            $total_finan_pecas = 0;
            $total_finan_valor = 0;
            $total_finan_atende = 0;

          @endphp

          @foreach ($financeiro as $finan)

            @php

              $total_finan_pecas += $finan->pecas;
              $total_finan_valor += $finan->valor;
              $total_finan_atende += $finan->atende;
            @endphp


            <tr>
              <td><a href="?finan={{$finan->financeiro}}">{{$finan->status}} ({{$finan->financeiro}})</a></td>
              <td align="center">{{number_format($finan->pecas, 0, '.', '.')}}</td>
              <td align="right">{{number_format($finan->valor, 2, ',', '.')}}</td>
               <td align="right">{{number_format($finan->atende, 0, ',', '.')}}</td>
            </tr>
          @endforeach

        </tbody>
        <tfoot>

            <tr>
              <th>TOTAL</th>
              <th style="text-align: center;">{{number_format($total_finan_pecas, 0, '.', '.')}}</th>
              <th style="text-align: right;">{{number_format($total_finan_valor, 2, ',', '.')}}</th>
               <th style="text-align: center;">{{number_format($total_finan_atende, 0, ',', '.')}}</th>
            </tr>          

        </tfoot>
      </table>
    </div>
  </div>  
-->

</div>

<form action="/backorder/atende" method="post">
  @csrf
<div class="row">
  <div class="col-md-12">
    <div class="box box-widget box-body">

  
      @if (isset($_GET["grife"]))
      <div class=""> Grife: {{$_GET["grife"]}} </div>
      @endif

      <small class="pull-right">{{count($clientes)}} registro(s)</small>

      <h6>
        <table class="table table-striped table-bordered compact" id="myTable">
          <thead>
            <tr>
            
             <th width="10%">Diretoria</th>
			 <th width="10%">Código</th>
             <th width="10%">Fin</th>
             <th width="30%">Fantasia</th>
             <th width="5%">Grupo</th>
             <th width="10%">Sub-Grupo</th>
             <th width="2%">UF</th>
             <th width="10%">Municipio</th>
             <th width="10%">Bairro</th>
             <th width="15%">Tel</th>
             <th width="5%">Peças</th>
             <th width="5%">Atende</th>
				
           </tr>
         </thead>
         <tbody>

          @foreach ($clientes as $cliente)

          @if (trim($cliente->financeiro) == 'IN')
            <tr style="color:red;">
          @else
            <tr style="color:black;">
          @endif
             
              <td width="4%" align="center">{{$cliente->diretoria}}</td>
			  <td width="4%" align="center"><a href="/backorder/{{$cliente->id}}?fornec={{$cliente->id}}">{{$cliente->id}}</a></td>
		      <td width="4%" align="center">{{$cliente->financeiro}}</td>
              <td width="30%">{{$cliente->fantasia}}</td>
              <td width="5%">{{$cliente->grupo}}</td>
              <td width="10%">{{$cliente->subgrupo}}</td>
              <td width="3%" align="right">{{$cliente->uf}}</td>
              <td width="15%" align="right">{{$cliente->municipio}}</td>
              <td width="15%" align="right">{{$cliente->bairro}}</td>  
              <td width="15%" align="right">{{$cliente->ddd1}} - {{$cliente->tel1}}</td>
              <td width="5%" align="center">{{$cliente->aberto}}</td>
              <td width="5%" align="center"><span class="text-green text-bold">{{$cliente->atende}}</span></td>
            </tr>

            @endforeach

          </tbody>
        </table>
      </h6>
    </div>
  </div>
</form>
@stop