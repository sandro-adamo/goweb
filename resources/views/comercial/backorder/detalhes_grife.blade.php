@extends('layout.principal')

@section('title')
<i class="fa fa-file"></i> BackOrder
@append 

@section('conteudo')


<div class="row">
  <div class="col-md-12">
    <div class="box box-widget box-body">
      
      <span class="pull-left text-bold">Filtros: </span>

      @if (isset($_GET["grife"]))
        <div class=""> Grife: {{$_GET["grife"]}} </div>
      @endif

      <small class="pull-right"> registro(s)</small>


      <table class="table table-striped table-bordered" id="example2">
        <thead>
          <tr>
            <th width="25%" rowspan="2">Item</th>
            <th width="5%" rowspan="2">Colmod</th>
            <th width="10%" rowspan="2">Quantidade</th>
            <th width="20%" rowspan="2">Valor</th>
            <th width="10%" colspan="3">Atende</th>
            <th width="25%" colspan="2" rowspan="2"></th> 
          </tr>

          <tr>
            <th>Brasil</th>
            <th>Trânsito</th>
            <th>Produção</th>
          </tr>
        </thead>
        <tbody>

          @php

            $total_pecas = 0;
            $total_valor = 0;
            $total_atende = 0;
            $total_atende_transito = 0;
            $total_atende_prod = 0;

          @endphp


          @foreach ($itens as $item)


            @php

              $total_pecas += $item->pecas;
              $total_valor += $item->valor;
              $total_atende += $item->atende_disp;
              $total_atende_transito += $item->atende_transito;
              $total_atende_prod += $item->atende_prod;
              
            @endphp

            <tr>
              <td>{{$item->secundario}}</td>
              <td>{{$item->colmod}}</td>
			  <td align="center">{{$item->pecas}}</td>
              <td align="right">{{number_format($item->valor, 2, ',', '.')}}</td>
              <td align="center">{{number_format($item->atende_disp)}}</td>
              <td align="center">{{number_format($item->atende_transito)}}</td>
              <td align="center">{{number_format($item->atende_prod)}}</td>
          <td align="center"><a href="" class="btnEditaItem" data-value="{{$item->secundario}}"><i class="fa fa-edit text-blue"></i> Editar</a></td>
              <td align="center"><a href="" class="text-red"><i class="fa fa-close text-red"></i> Cancelar</a></td> 
            </tr>

          @endforeach

        </tbody>

        <tfoot>
          <tr>
            <th></th>
             <th></th>
            <th style="text-align: center">{{$total_pecas}}</th>
            <th style="text-align: right;">{{number_format($total_valor, 2, ',', '.')}}</th>
            <th style="text-align: center">{{$total_atende}}</th>
            <th style="text-align: center">{{$total_atende_transito}}</th>
            <th style="text-align: center">{{$total_atende_prod}}</th>
            <th colspan="2"></th>
          </tr>
        </tfoot>

      </table>
    </div>
</div>


<div class="modal fade" id="modalEditaItem" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-edit"></i> Edita Item</h4>
      </div>
      <div class="modal-body">
        <h3 id="item"></h3>
        <hr>
        <div id="tabela"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<script src="/js/jquery.min.js"></script>

<script>
  $(".btnEditaItem").click(function(event) {

    event.preventDefault();

    var referencia = $(this).data('value');

    $("#modalEditaItem").modal('show');

    var item = '<div class="row">';
    item += '<div class="col-md-8" >';
    item += '<img src="https://portal.goeyewear.com.br/teste999.php?referencia='+referencia+'" class="img-responsive" >';
    item += '</div>';
    item += '<div class="col-md-9">';
    item += referencia;
    item += '</div>';
    item += '</div>';
    $("#modalEditaItem #item").html(item);

    $.ajax({
      url: '/api/produto/cores?item='+referencia,
      dataType: "json",
      success: function(result) {

        var linha = '<div class="row">';

        $.each(result, function(index, value) {

          if (value.secundario != referencia) {
            linha += '<div class="col-md-6" align="center" style="height:350px;max-height:350px;min-height:350px;">';
            linha += '<a href="" class="substitui"><img src="https://portal.goeyewear.com.br/teste999.php?referencia='+value.secundario+'" class="img-responsive"><br>'+value.secundario+'</a>';
            linha += '</div>';
          }

        });

        linha += '</div>';

        $("#modalEditaItem #tabela").html(linha);

      }, 
      error: function(msg) {
        console.log('erro '+msg);
      }
    });


  });
</script>
@stop