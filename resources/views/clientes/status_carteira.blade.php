@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i> Status da Carteira
@append 

@section('conteudo')

  <form action="" method="get">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-widget box-body">
        @if(isset($listaCli)) 
        <h2> Grife {{$listaCli[0]->grife}} <br> Status {{$listaCli[0]->sts_carteira}}</h2>
        <br>
                <div class="table-responsive">
                 
        <table class="table table-bordered" id="example1">
          <thead>
            <tr>
                <th>Cliente</th>
                <th>Razão</th>
                <th>DDD</th>
                <th>Telefone</th>
                <th>E-mail</th>
                <th>Status Financeiro</th>
                <th>Perfil</th>
                <th>UF</th>
                <th>Municipio</th>
                <th>Subgrupo</th>
                      
                  <th>Grupo</th> 
           </tr>
         </thead>
         <tbody>
            @foreach($listaCli as $cli)
            <tr>
            <th><a href="/clientes/pdv/{{$cli->cli}}">{{$cli->cli}}</a></th>
                <td>{{$cli->razao}}</td>
                <td>{{$cli->ddd1}}</td>
                <td>{{$cli->tel1}}</td>
                <td>{{$cli->email1}}</td>
                <td>{{$cli->financeiro}}</td>
                <td>{{$cli->perfil_compra_360dd}}</td>
                <td>{{$cli->uf}}</td>
                <td>{{$cli->municipio}}</td>
                <td>{{$cli->subgrupo}}</td>
                <td>{{$cli->grupo}}</td>
            </tr>
            @endforeach
        </tbody>
      </table>
      @endif

      @if(isset($listaSub)) 
        <h2> Grife {{$listaSub[0]->grife}} <br> Status {{$listaSub[0]->sts_carteira}}</h2>
        <br>
                <div class="table-responsive">
                 
        <table class="table table-bordered" id="example1">
          <thead>
            <tr>
                
               
              
               
                <th>Cliente</th>
                      
                  <th>Grupo</th> 
                  <th>Quantidade PDV</th> 
                  <th>Financeiro</th> 
           </tr>
         </thead>
         <tbody>
            @foreach($listaSub as $cli)
            
               @if ($cli->financeiro=' IN' or $cli->financeiro=='AC' )
            <tr style="color:red;">
          @else
            <tr style="color:black;">
          @endif
           
                <td><a href="/carteira/detalhe/{{$listaSub[0]->cod_status_carteira}}/{{$listaSub[0]->grife}}/lista_sub/{{$cli->cliente}}">{{$cli->cliente}}</a></td>
                <td>{{$cli->grupo}}</td>
                <td>{{$cli->qtd}}</td>
                <td>{{$cli->financeiro}}</td>
            </tr>
            @endforeach


        </tbody>
      </table>
      @endif

      @if(isset($pdvSub)) 
        <h2> Grife {{$pdvSub[0]->grife}} <br> </h2>
        <br>
                <div class="table-responsive">
                 
        <table class="table table-bordered" id="example1">
          <thead>
            <tr>
                <th>Cliente</th>
                <th>Razão</th>
                <th>DDD</th>
                <th>Telefone</th>
                <th>E-mail</th>
                <th>Status Financeiro</th>
                <th>Perfil</th>
                <th>UF</th>
                <th>Municipio</th>
                <th>Subgrupo</th>
                <th>Grupo</th> 
                <th>Status carteira</th>
           </tr>
         </thead>
         <tbody>
            @foreach($pdvSub as $cli)
            <tr>
            <th><a href="/clientes/pdv/{{$cli->cli}}">{{$cli->cli}}</a></th>
                <td>{{$cli->razao}}</td>
                <td>{{$cli->ddd1}}</td>
                <td>{{$cli->tel1}}</td>
                <td>{{$cli->email1}}</td>
                <td>{{$cli->financeiro}}</td>
                <td>{{$cli->perfil_compra_360dd}}</td>
                <td>{{$cli->uf}}</td>
                <td>{{$cli->municipio}}</td>
                <td>{{$cli->subgrupo}}</td>
                <td>{{$cli->grupo}}</td>
                <td>{{$cli->sts_carteira}}</td>
            </tr>
            @endforeach
        </tbody>
      </table>
      @endif
    </div>
    </div>
  </div>
</div>
@stop