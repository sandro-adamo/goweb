@extends('layout.principal')

@section('title')
<i class="fa fa-barcode"></i> Notas Fiscais
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
             <th width="10%">Nota Fiscal</th>
             <th width="10%">Data</th>

             <th width="5%">Pedido</th>
             <th width="5%">Codígo</th>
             <th width="35%">Razão Social</th>
             <th width="20%">Subgrupo</th>
             <th width="10%">Valor</th>

           </tr>
         </thead>
         <tbody> 

          @php
            $total_vendas = 0;
          @endphp

          @if (isset($notas))


            @foreach ($notas as $linha)

              @php
                $total_vendas += $linha->valor;
              @endphp

              <tr>
                <td align="center" class="text-red"><a href="/notas/{{$linha->nf_legal}}">{{$linha->nf_legal}}</a></td>
                <td align="center">{{date('d/m/Y', strtotime($linha->dt_emissao))}}</td>                
                <td align="center"><a href="/vendas/{{$linha->pedido}}">{{$linha->pedido}}</a></td>
                <td align="center">{{$linha->id_cliente}}</td>
                <td>{{$linha->razao}}</td>
                <td>{{$linha->subgrupo}}</td>
                <td align="right">{{number_format($linha->valor, 2, ',', '.')}}</td>
                {{-- <td align="right">{{number_format($linha->orcamento, 2, ',', '.')}}</td> --}}
              </tr>

            @endforeach


          @endif
        </tbody>   
          <tr>       
            <th colspan="6"><b>TOTAL</b></td>
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
            <input type="text" name="busca" class="form-control" placeholder="Buscar código, cliente, CNPJ, Mobile" @if (isset($_GET["busca"])) value="{{$_GET["busca"]}}" @endif>            
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