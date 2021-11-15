@extends('layout.principal')

@section('title')
<i class="fa fa-file"></i> BackOrder
@append 

@section('conteudo')

<h3> {{$cliente->id}} - {{$cliente->fantasia}} </h3>
<div class="row">
  
<!--
  <div class="col-md-6">
    <div class="box box-body box-widget">
      <table class="table table-bordered table-condensed">
        <thead>
          <tr>
            <th width="55%">Grife</th>
            <th width="20%">Peças</th>
            <th width="25%">Valor</th>
          </tr>
        </thead>

        <tbody>


        </tbody>
        <tfoot>

            <tr>
              <th>TOTAL</th>
              <th style="text-align: center;"></th>
              <th style="text-align: right;"></th>
            </tr>          

        </tfoot>
      </table>
    </div>
  </div>
-->
    

<h6>
  <form action="/backorder/atende" method="post">
  @csrf 
  <div class="col-md-6">
    <div class="box box-body box-widget">
      <div class="row">
        <input type="hidden" name="id_cliente" value="{{$cliente->id}}">
        <div class="col-md-6">
          <select name="acao" class="form-control">
            <option value=""></option>
            <option>Atender</option>
			       <option>Aguardar</option>
			  
          </select>
        </div>
        <div class="col-md-6">
          <button class="btn btn-flat btn-default" type="submit">Salvar</button>
        </div>
      </div>

      <br><br>
      <label>Obs:</label>
      <div class="row">

        <div class="col-md-6">
          <textarea name="novo_endereco" class="form-control" placeholder="Obs"></textarea>
        </div>

      </div>
    </div>
  </div>  
  </form>

</div>


<div class="row">
  <div class="col-md-10">
    <div class="box box-widget box-body">
      
      <span class="pull-left text-bold">Filtros: </span>

      @if (isset($_GET["grife"]))
        <div class=""> Grife: {{$_GET["grife"]}} </div>
      @endif

      <small class="pull-right"> registro(s)</small>

      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th width="4%" rowspan="2">grife</th>
			<th width="20%" rowspan="2">Item</th>
			<th width="20%" rowspan="2">repres</th>
            <th width="5%" rowspan="2">qtde</th>
            <th width="7%" rowspan="2">Valor</th>
            <th width="10%" colspan="1">Atende</th>
          </tr>

          <tr>
            <th>Brasil</th>
           <!--  <th>Trânsito</th>
            <th>Produção</th>  -->
          </tr>
        </thead>
        <tbody>

          @php

            $total_pecas = 0;
            $total_valor = 0;
            $total_atende = 0;

          @endphp


          @foreach ($itens as $item)


            @php

              $total_pecas += $item->aberto;
              $total_valor += $item->valor;
              $total_atende += $item->atende;
              
            @endphp

            <tr>
			  <td>{{$item->codgrife}}</td>
              <td>{{$item->secundario}}</td>
			  <td>{{$item->nome}}</td>
              <td align="center">{{$item->aberto}}</td>
              <td align="right">{{number_format($item->valor, 2, ',', '.')}}</td>
              <td align="center">{{number_format($item->atende)}}</td>
         <!--      <td align="center">{{number_format($item->atende)}}</td>
              <td align="center">{{number_format($item->atende)}}</td> 
              <td align="center"><a href="" class="btnEditaItem" data-value="{{$item->secundario}}"><i class="fa fa-edit text-blue"></i> Editar</a></td>
              <td align="center"><a href="" class="text-red"><i class="fa fa-close text-red"></i> Cancelar</a></td>-->
            </tr>

          @endforeach

        </tbody>

        <tfoot>
          <tr>
            <th></th>
			<th></th>
			  <th></th>
            <th style="text-align: center">{{$total_pecas}}</th>
            <th style="text-align: right;">{{number_format($total_valor, 2, ',', '.')}}</th>
            <th style="text-align: center">{{$total_atende}}</th>
          </tr>
        </tfoot>

      </table>
    </div>
</div>


<div class="modal fade" id="modalEditaItem" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-edit"></i> Edita Item</h4>
      </div>
      <div class="modal-body">
        <h3 id="item"></h3>
        <hr>
        <div id="tabela"></div>

        <br><br>
      </div>
    </div>
  </div>
</div>
</h6>
<script src="/js/jquery.min.js"></script>

<script>
  $(".btnEditaItem").click(function(event) {

    event.preventDefault();

    var referencia = $(this).data('value');

    $("#modalEditaItem").modal('show');

    var item = '<div class="row">';
    item += '<div class="col-md-3" >';
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
            linha += '<div class="col-md-4" align="center">';

            linha += '<div id="foto" style="height:150px;max-height:150px;min-height:150px;">';
            linha += '<a href="" class="substitui"><img src="https://portal.goeyewear.com.br/teste999.php?referencia='+value.secundario+'" class="img-responsive" style="max-height:150px;"></a>';
            linha += '</div>';

            linha += '<table class="table table-condensed">';
            linha += '<tr><td colspan="3" align="center" class="text-bold">'+value.secundario+'</td></tr>';
            linha += '<tr><td width="33%" align="center"><small>BR<br>'+parseInt(value.disp)+'</small></td><td width="33%" align="center"><small>Trânsito <br>'+parseInt(value.transito)+'</small></td><td align="center"><small width="33%">Produção<br> '+parseInt(value.producao)+'</small></td></tr>';
            linha += '</table>';

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