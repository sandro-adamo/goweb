@extends('layout.principal')

@section('title')
  <i class="fa fa-gears"></i> Processamento de Status
@append 

@section('conteudo')


	<a href="/produtos/processa/atualiza" class="btn btn-flat btn-default"><i class="fa fa-gears"></i> Processar</a>
<a href="http://goweb.goeyewear.com.br/painel/atualizastatusitens" class="btn btn-flat btn-default"><i class="fa fa-gears"></i> Atualiza Status WEB</a>




<div class="box box-body box-widget">
<table class="table table-bordered table-striped">
  @foreach ($processamentos as $processa)
    <tr>
    	<td><a href="/produtos/status/processamentos/{{ $processa->processamento }}">{{ $processa->processamento }}</a></td>
    	<td>{{ $processa->data }}</td>
    	<td width="10%" align="center"><a href="/produtos/status/processamentos/{{ $processa->processamento }}/excluir" class="text-red"><i class="fa fa-trash"></i> Excluir</a></td>
    </tr>
  @endforeach
</table>
</div>

@stop