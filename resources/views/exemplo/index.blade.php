@extends('layout.principal')

@section('conteudo')

@if(isset($success))
    <div class="alert alert-success">
        {{ $success }}
    </div>
@endif

<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Exemplo</h3>
            </div>
            <div class="box-body">
                <div class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr role="row">
                                        <th>ID Pedido</th>
                                        <th>Linha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pedidos as $pedido)
                                    <tr>
                                        <td>{{ $pedido->pedido }}</td>
                                        <td>{{ $pedido->linha }}</td>
                                        <td><button class="btn btn-primary btn-cadastrar" data-toggle="modal"
                                                data-target="#modal" data-pedido="{{ $pedido->pedido}}"
                                                data-linha="{{$pedido->linha}}" id="{{ $pedido->id }}">Cadastrar</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div style="display: flex; justify-content: center;">
                                {{ $pedidos->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" action="{{route('exemplo.store')}}" id="form-cadastrar">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="modalLabel">Modal Exemplo</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="campo-pedido">Pedido:</label>
                            <br>
                            <input type="text" class="form-control" id="campo-pedido" name="id_pedido">
                        </div>
                        <div class="col-md-12" style="display: none;">
                            <label for="campo-pedido">Linha:</label>
                            <br>
                            <input type="hidden" class="form-control" id="linha" name="linha">
                        </div>
                        <div class="col-md-12">
                            <label for="campo-pedido">Campo 1:</label>
                            <br>
                            <input type="text" class="form-control" id="campo1" name="campo1">
                        </div>
                        <div class="col-md-12">
                            <label for="campo-pedido">Campo 2:</label>
                            <br>
                            <input type="text" class="form-control" id="campo2" name="campo2">
                        </div>
                        <div class="col-md-12">
                            <label for="campo-pedido">Campo 3:</label>
                            <br>
                            <input type="text" class="form-control" id="campo3" name="campo3">
                        </div>
                        <div class="col-md-12">
                            <label for="campo-pedido">Campo 4:</label>
                            <br>
                            <input type="text" class="form-control" id="campo4" name="campo4">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@stop

@section('js')
    <script>

        var pedidos = @json($pedidos)

        $('.btn-cadastrar').on('click', function(){

            $('#form-cadastrar > div > div > div > input[type="text"]').val('')

            $('.modal-title').html('Pedido <b>#' + $(this).data('pedido') + '</b> Linha <b>#' + $(this).data('linha'))

            $('#campo-pedido').val($(this).data('pedido'))

            $('#linha').val($(this).data('linha'))
            
            pedidos.data.forEach( pedidoRow => {

                if(pedidoRow.exemplo){

                    pedidoRow.exemplo.forEach( exemploRow => {

                        if(exemploRow.id_pedido == $('#campo-pedido').val() && exemploRow.linha == $('#linha').val()){

                            $('#campo1').val(exemploRow.campo1)

                            $('#campo2').val(exemploRow.campo2)

                            $('#campo3').val(exemploRow.campo3)

                            $('#campo4').val(exemploRow.campo4)

                        }
                    })
                }
            })
        })
    </script>
@stop

@section('css')

    <style>
        .modal-body > .row > .col-md-12 {
            padding: 15px;
        }
        th, td {
            width: auto;
            text-align: center;
        }
    </style>

@stop