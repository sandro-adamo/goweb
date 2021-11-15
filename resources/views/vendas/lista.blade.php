@extends('layout.principal')

@section('title')
<i class="fa fa-shopping-cart"></i> Minhas Vendas
@append 

@section('conteudo')

  <div class="row">
    <div class="col-md-12">
      <div class="box box-widget box-body">
        <div class="row">
          <div class="col-md-12">
            <button type="button" data-toggle="modal" data-target="#modalFiltros" class="btn btn-flat btn-default"><i class="fa fa-filter"></i> Filtros</button>
          </div>
        </div>      
        <br>
        <div class="table-responsive">
        <table class="table table-bordered" id="example3">
          <thead>
            <tr>
             <th>Pedido</th>
             <th>Data</th>
             <th>Mobile</th>
             <th>Codígo</th>
             <th>Razão Social</th>
             <th>Subgrupo</th>
             <th width="8%">Cond Pgto</th>
             <th width="8%">Tab desconto</th>
             <th>Valor</th>
             {{-- <th>Vlr backorder</th> --}}

           </tr>
         </thead>
         <tbody> 

          @php
            $total_vendas = 0;
          @endphp


          @if (isset($vendas))

            @foreach ($vendas as $linha)

              @php
                $total_vendas += $linha->valor;
              @endphp

              <tr @if (trim($linha->financeiro) <> '') class="warning" @endif>
                <td align="center" class="text-red"><a href="/vendas/{{$linha->pedido}}">{{$linha->pedido}}</a></td>
                <td align="center">{{date('d/m/Y', strtotime($linha->dt_venda))}}</td>                
                <td align="center">{{$linha->pc_cliente}}</td>
                <td align="center">{{$linha->id_cliente}}</td>
                <td>{{$linha->razao}}</td>
                <td align="left">{{$linha->subgrupo}}</td> 
                <td  align="center">{{$linha->condpag}}</td>
                <td  align="center">{{$linha->desconto}}</td>
                <td align="right">{{number_format($linha->valor, 2, ',', '.')}}</td>
                {{-- <td align="right">{{number_format($linha->orcamento, 2, ',', '.')}}</td> --}}
              </tr>

            @endforeach


          @endif
        </tbody>   
          <tr>       
            <th colspan="5"><b>TOTAL</b></td>
            <th class="hidden-md"></th>
            <th class="hidden-md"></th>
			      <th class="hidden-md"></th>
            <td align="right" class="text-bold">{{number_format($total_vendas, 2, ',', '.')}}</td>
          </tr>
        </tr>
      </table>
    </div>
      


    </div>
  </div>


<form action="" method="get" class="form-horizontal">
<div class="modal fade" id="modalFiltros" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-filter"></i> Filtros</h4>
      </div>
      <div class="modal-body">


        <div class="form-group">
          <label class="col-md-2 control-label">Cliente</label>
          <div class="col-md-9">
            <input type="text" name="busca" class="form-control" placeholder="Buscar código, cliente, CNPJ, Mobile, Municipio" @if (isset($_GET["busca"])) value="{{$_GET["busca"]}}" @endif>            
          </div>
        </div>

        
        <div class="form-group">
          <label class="col-md-2 control-label">Período</label>
          <div class="col-md-4">
            <input type="date" name="inicio" class="form-control" @if (isset($_GET["inicio"])) value="{{$_GET["inicio"]}}" @endif>
          </div>
          <div class="col-md-4">
            <input type="date" name="fim" class="form-control" @if (isset($_GET["fim"])) value="{{$_GET["fim"]}}" @endif>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary btn-flat">Filtrar</button>
      </div>
    </div>
  </div>
</div>
</form>
@stop