@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i> Clientes
@append 

@section('conteudo')


@php

  //echo \Auth::user()->id_perfil;
  //echo Session::get('representantes');
@endphp 
  <form action="" method="get">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-widget box-body">
        <div class="row">
    
          <div class="col-xs-9 col-md-6">
            <input type="text" name="busca" autofocus="" class="form-control" placeholder="Buscar clientes">
          </div>

          <div class="col-xs-3 col-md-2">
            <button class="btn btn-flat btn-default btn-block"><i class="fa fa-search"></i> <span class="hidden-xs">Pesquisar</span></button>
          </div>

        </div>      
        <br>
        <table class="table table-bordered" id="example3">
          <thead>
            <tr>

            <th class="visible-xs">Cliente</th>
             <th class="visible-lg" width="20%">Nome</th>
             <th class="visible-lg" width="20%">municipio</th>			 
             <th class="visible-lg" width="5%">PDV's</th>
             <th class="visible-lg" width="7%">$ 2017</th>
             <th class="visible-lg" width="7%">$ 2018</th>
             <th class="visible-lg" width="7%">$ 2019</th>
             <th class="visible-lg" width="7%">$ 2020</th>
             <th class="visible-lg" width="10%">Vencido</th>
             <th class="visible-lg" width="10%">A Vencer</th>

           </tr>
         </thead>
         <tbody>

          @if ($clientes && count($clientes) > 0)

            @foreach ($clientes as $pessoa)

              @php

                if ($pessoa->cliente <> ''){
                  $string_cliente = str_replace('/', '_subst_', $pessoa->cliente);
                } else {

                  $string_cliente = '';
                }



              @endphp


              <tr class="visible-lg">
                <td><a href="/clientes/{{$string_cliente}}">{{$pessoa->cliente}}</a></td>
                <td align="center">{{$pessoa->municipios}}</td>
                <td align="center">{{$pessoa->pdvs}}</td>
                <td align="center">{{number_format($pessoa->valor_17,2)}}</td>
                <td align="center">{{number_format($pessoa->valor_18,2)}}</td>
                <td align="center">{{number_format($pessoa->valor_19,2)}}</td>
                <td align="center">{{number_format($pessoa->valor_20,2)}}</td>
                <td align="center">{{number_FORMAT($pessoa->vencido,2)}}</td>
                <td align="center">{{number_format($pessoa->a_vencer,2)}}</td>
              </tr>
            @endforeach 

          @else 
            <tr>
              <td colspan="9" align="center">Nenhum registro encontrato.</td>  
            </tr>
          @endif
        </tbody>
      </table>
    </div>
  </div>
</div>
@stop